<?php

namespace App\Http\Controllers;

use App\{Post, State};

class WechatController extends Controller
{
    public function search()
    {
        $query = Post::select('title', 'chinese_title', 'identity');

        if(request('search')) {
            $query->where(function($query) {
                $query->where('title', 'LIKE', request('search').' %')
                ->orWhere('title', 'LIKE', '% '.request('search'))
                ->orWhere('title', 'LIKE', '% '.request('search').' %')
                ->orWhere('chinese_title', 'LIKE', '%'.request('search').'%');
            });
        }
            
        if(request('state') && request('state') != '全部') {
            $state = State::where('simplified_name', 'LIKE', request('state').'%')->first();
            if($state) {
                $query->where('location', 'LIKE', '%'.$state->STATE_CODE);
            } else {
                $query->whereRaw('1=2');
            }
        }

        if(!$query->count()) return [];

        return $query->take(15)->get()->unique('title');
    }
}
