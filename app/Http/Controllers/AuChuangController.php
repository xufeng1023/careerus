<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\{AuChuang, WechatChatHistory};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Cache, Log};

class AuChuangController extends Controller
{
    public function updateChatOnlyRecent() {
        $from = Carbon::now()->subday()->timestamp * 1000;
        $to = Carbon::now()->timestamp * 1000;
        $contacts = AuChuang::orderBy('id', 'desc')->get();
        foreach($contacts as $c) {
            $result = $this->curlCall(
                'https://user.keduowei.com:9991/api/FriendMessage/searchMessage?keyword=&msgType=&accountId=&count=20&messageId=&olderData=true&wechatAccountId='.$c->wechatAccountId.'&wechatFriendId='.$c->id.'&from='.$from.'&to='.$to,
                false,
                [
                    'Authorization: Bearer '.Cache::get('auchuang-bearer-token'),
                    'Content-Type: application/json'
                ],
                []
            );

            if($result['code'] == 401) {
                $this->loginFetch();
                $this->updateChatOnlyRecent();
            } else {
                var_dump($result);
            }

            die;
        } 
    }
    public function getAllContacts() {
        $result = $this->curlCall(
            'https://user.keduowei.com:9991/api/WechatFriend/friendlistData',
            true,
            [
                'Authorization: Bearer '.Cache::get('auchuang-bearer-token'),
                'Content-Type: application/json'
            ],
            '{
                "addFrom": "",
                "allotAccountId": "",
                "extendFields": {},
                "gender": "",
                "groupId": null,
                "isDeleted": false,
                "isPass": true,
                "keyword": "",
                "labels": [],
                "pageIndex": 0,
                "pageSize": 20,
                "preFriendId": "",
                "wechatAccountKeyword": ""
            }'
        );
        Log::info($result);
        if($result['code'] == 401) {
            $this->loginFetch();
            $this->getAllContacts();
        } else {
            $data = json_decode($result['data']);
            foreach($data as $contact) {
                try {
                    $row = (array) $contact;
                    $d = new AuChuang;
                    foreach($row as $key => $value) {
                        $d->$key = is_array($value)? json_encode($value) : $value;
                    }
                    $d->save();
                } catch(\Illuminate \ Database \ QueryException $e) {
                    // echo $e->getMessage();
                    // die;
                }                
            }
        }
    }

    private function loginFetch() {
        $result = $this->curlCall(
            'https://user.keduowei.com:9991/token',
            true,
            ['Content-Type: application/x-www-form-urlencoded'],
            [
                'grant_type' => 'refresh_token',
                'refresh_token' => Cache::get('auchuang-refresh_token')
            ]
        );

        if($result['code'] == 400) {
            Log::info('login failed');
            die;
        }
        $data = json_decode($result['data']);

        Cache::forever('auchuang-bearer-token', $data->access_token);
        Cache::forever('auchuang-refresh_token', $data->refresh_token);
    }

    private function curlCall($link, $post = true, $header = [], $data) {
        $ch = curl_init();
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $link);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        if($post) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            if(is_array($data)) curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        }
        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return ['data' => $result, 'code' => $httpCode];
    }
}
