<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\CallHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CrmController extends Controller
{
    function callHistory() {
    //    Cache::flush();
        $result = $this->curlCall(
            'https://np3.nextiva.com/NextOSPortal/ncp/enterprise/callHistory',
            true,
            [
                'Content-Type:application/x-www-form-urlencoded',
            ],
            [
                'fromDate' => $now = Carbon::now()->format('m/d/Y'),
                'toDate' => $now,
                'userId' => '',
                'callType' => 'all'
            ]
        );
        if($result) {
            libxml_use_internal_errors(true);
            $dom = new \DOMDocument;
            $dom->preserveWhiteSpace = false;
            $dom->loadHTML($result);
            $trs = $dom->getElementById('call_history_search_results')->getElementsByTagName('tbody')[0]->getElementsByTagName('tr');

            foreach($trs as $tr) {
                $tds = $tr->getElementsByTagName('td');
                $key = $tds[0]->nodeValue;
                $value = $tds[2]->nodeValue;
                $currentValue = (int)Cache::get($key);
                if($currentValue != $value) {
                    Cache::forever($key, $value);
                    $query = parse_url($tr->getElementsByTagName('a')[0]->getAttribute('href'), PHP_URL_QUERY);
                    $query = explode('&returnToPage', $query)[0];
                    $query .= '&fullListSize=0&draw=1&length=100&start=0&sortCol=1&sortType=desc&_='.time();
                    $json = $this->curlCall(
                        'https://np3.nextiva.com/NextOSPortal/ncp/enterprise/getNextCallDetails?'.$query,
                        false
                    );
                    $json = json_decode($json)->data;
                    for($i = 0; $i < $value - $currentValue; $i++) {
                        try {
                            CallHistory::create([
                                'orig_number' => $json[$i]->origNumber,
                                'dest_number' => $json[$i]->destNumber,
                                'duration' => $json[$i]->callDuration,
                                'called_date' => $json[$i]->displayUserStartDate,
                                'called_time' => $json[$i]->displayUserStartTime,
                                'call_type' => $json[$i]->callType,
                                'username' => $json[$i]->userName,
                            ]);
                        } catch(\Illuminate \ Database \ QueryException $e) {
                            if($e->getCode() != 23000) echo $e->getMessage();
                        }
                    }
                }
            }
        } else {
            $this->callLogin();
            $this->callHistory();
        }
    }

    private function callLogin() {
        $result = $this->curlCall(
            'https://np3.nextiva.com/NextOSPortal/ncp/login',
            true,
            [
                'Content-Type:application/x-www-form-urlencoded'
            ],
            [
                'loginUserName' => env('NEXTIVA_USERNAME'),
                'loginPassword' => env('NEXTIVA_PASSWORD'),
                'validateFields' => true
            ]
        );
    }

    private function curlCall($link, $post = true, $header = [], $data = []) {
        $ch = curl_init();
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $link);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_COOKIEFILE, storage_path('app/cookie.txt'));
        curl_setopt($ch, CURLOPT_COOKIEJAR, storage_path('app/cookie.txt'));
        if($post) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        }
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}
