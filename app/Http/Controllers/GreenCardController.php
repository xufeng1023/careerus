<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use App\{GreenCard, GreenCardInventory};

class GreenCardController extends Controller
{
    public function index()
    {
        return $this->generateVisaPage('greenCardFrame');
    }

    public function subscribe()
    {
        return request()->server;
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
        $today = Carbon::now()->format('Y-m');
        $last_record_date = GreenCard::orderBy('id', 'desc')->pluck('check_at')->first();

       if(!$last_record_date || ($today > $last_record_date->format('Y-m'))) {
            try {
                return file_get_contents(
                    'https://travel.state.gov/content/travel/en/legal/visa-law0/visa-bulletin/'.date('Y').'/visa-bulletin-for-'.strtolower(date('F-Y')).'.html'
                );
            } catch(\ErrorException $e) {
                return response('数据还未更新', 404);
            }
        }

        return response($today.'的数据已经爬过了。', 409);
    }

    public function crawlInventory()
    {
        $month_year = Carbon::now()->format('F_Y');
        $url = 'https://www.uscis.gov/sites/default/files/USCIS/Green%20Card/Green%20Card%20Through%20a%20Job/I-485%20Employment-Based%20Inventory%20Statistics/Employment-based_I-485_Pending_at_the_Service_Centers_as_of_'.$month_year.'.pdf';
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

        $page = $pdf->getHtml()->getPage(2);

        $dom = new \DOMDocument();
        $dom->loadHTML($page);

        $tags = (new \DOMXPath($dom))->query('//b');

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
                ['title' => $visa['title'], 'country' => $visa['country'], 'check_at' => date('Y-m-d')],
                [array_pop($keys) => array_pop($visa)]
            );
        }
    }
}
