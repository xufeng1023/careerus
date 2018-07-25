<?php

namespace App\Http\Controllers;

use App\{Post, State};
use SteelyWing\Chinese\Chinese;

class WechatController extends Controller
{
    public function search()
    {
        $query = Post::select('title', 'chinese_title', 'identity');

        if(request('search')) {
            $chinese = new Chinese();
            $search = $chinese->to(Chinese::CHS, request('search'));

            $query->where(function($query) use($search) {
                $query->where('title', 'LIKE', $search.' %')
                ->orWhere('title', 'LIKE', '% '.$search)
                ->orWhere('title', 'LIKE', '% '.$search.' %')
                ->orWhere('chinese_title', 'LIKE', '%'.$search.'%');
            });
        }
            
        if(request('state') && request('state') != '任何地区') {
            $state = State::where('simplified_name', 'LIKE', request('state').'%')->first();
            if($state) {
                $query->where('location', 'LIKE', '%'.$state->STATE_CODE);
            } else {
                $query->whereRaw('1=2');
            }
        }

        if(!$query->count()) return [];

        return $query->take(15)->get()->load('company.visaJobs')->unique('title');
    }
}
