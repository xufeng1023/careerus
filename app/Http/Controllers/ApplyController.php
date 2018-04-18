<?php

namespace App\Http\Controllers;

use App\{Post, Apply};

class ApplyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function save()
    {
        $user = auth()->user();
        $couponUsed = $user->applies()->count();

        if(($couponUsed >= 5) && ($user->points < 20)) {
            return trans('front.no points');
        }
        
        $post = (new Post)->findPost(request('job'), request('identity'));

        (new Apply)->apply($post); 

        if($couponUsed >= 5) {
            $user->decrement('points', 20);
        }
    }
}
