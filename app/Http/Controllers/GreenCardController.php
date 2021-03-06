<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\{GreenCard, GreenCardInventory, GreenCardSubscribe};
use App\Mail\GreenCardUpdated;

class GreenCardController extends Controller
{
    public function index()
    {
        return $this->generateVisaPage('greenCardFrame');
    }

    public function subscribe()
    {
        $data = request()->validate([
            'url' => 'required|url',
            'email' => 'required|string|email|max:255'
        ]);

        try {
            GreenCardSubscribe::create($data);
        } catch(\Illuminate\Database\QueryException $e) {
            return response(['errors' => ['email' => '此邮箱已经订阅']], 422);
        }

        return '订阅成功!';
    }

    public function visaBulletin()
    {
        return $this->generateVisaPage('visaBulletin');
    }

    private function generateVisaPage($view)
    {
        $visas = GreenCard::orderBy('check_at', 'desc')->take(32)->get();

        $inventories = GreenCardInventory::whereCountry('china')->get();

        $first = $visas->first();

        return view($view, compact('visas', 'inventories', 'first'));
    }

    public function alladmin()
    {
        $inventories = GreenCardInventory::all();
        $bulletins = GreenCard::orderBy('check_at', 'desc')->get();
        return view('admin.greenCard', compact('bulletins', 'inventories'));
    }

    public function crawl()
    {
        $next = Carbon::now()->addMonth();
        $today = $next->format('Y-m');
        $last_record_date = GreenCard::orderBy('id', 'desc')->pluck('check_at')->first();

       if(!$last_record_date || ($today > $last_record_date->format('Y-m'))) {
            try {
                return file_get_contents(
                    'https://travel.state.gov/content/travel/en/legal/visa-law0/visa-bulletin/2019/visa-bulletin-for-'.strtolower($next->format('F-Y')).'.html'
                );
            } catch(\ErrorException $e) {
                return response('数据还未更新', 404);
            }
        }

        return response($today.'的数据已经爬过了。', 409);
    }

    public function crawlInventory()
    {
        $month_year = 'July_2018';//Carbon::now()->format('F_Y');
        $url = 'https://www.uscis.gov/sites/default/files/Employment-based_I-485_Pending_at_the_Service_Centers_as_of_'.$month_year.'.pdf';
        try {
            $resource = file_get_contents($url);
        } catch(\ErrorException $e) {
            return response('数据还未更新', 404);
        }

        Storage::put($month_year.'.pdf', $resource);
        
        $pdf = new \TonchikTm\PdfToHtml\Pdf(storage_path('app\\'.$month_year.'.pdf'),
        [
            'pdftohtml_path' => config('services.poppler.html_path'),
            'pdfinfo_path' => config('services.poppler.info_path')
        ]);

        $pages = $pdf->getHtml()->getAllPages();

        $dom = new \DOMDocument();

        foreach($pages as $page) {
            $dom->loadHTML($page);
        }

        $tags = (new \DOMXPath($dom))->query('//b');
 foreach($tags as $key => $tag) {
     echo $key.' '.$tag->nodeValue."\n";
 }
        foreach ([53,107,161,215,269,323] as $k => $key) {
            GreenCardInventory::updateOrCreate(
                ['country' => 'china', 'preference' => ++$k.'st'],
                ['amount' => (int)str_replace(',', '', $tags[$key]->nodeValue), 'updated_at' => Carbon::now()->format('Y-m-d')]
            );
        }

        Storage::delete($month_year.'.pdf');
    }

    public function save()
    {
        foreach(request()->all()['visa'] as $visa) {
            $keys = array_keys($visa);
            GreenCard::updateOrCreate(
                ['title' => $visa['title'], 'country' => $visa['country'], 'check_at' => date("Y-m-d", strtotime("+1 month", time()))],
                [array_pop($keys) => array_pop($visa)]
            );
        }
    }

    public function notifySubscribers1()
    {
        $subscribers = GreenCardSubscribe::whereUrl('https://greencardlegal.com')->get();

        config([
            'mail.username' => env('GCL_MAIL_USERNAME'),
            'mail.password' => env('GCL_MAIL_PASSWORD'),
            'mail.from.address' => env('GCL_MAIL_FROM_ADDRESS'),
            'mail.from.name' => 'greencardlegal'
        ]);
        
        foreach($subscribers as $sub) {
            Mail::to($sub->email)->send(new GreenCardUpdated($sub->url)); 
        }
    }

    public function notifySubscribers2()
    {
        $subscribers = GreenCardSubscribe::whereUrl('https://careerus.com')->get();

        foreach($subscribers as $sub) {
            Mail::to($sub->email)->send(new GreenCardUpdated($sub->url)); 
        }
    }
}
