<?php

namespace App\Http\Controllers;

use App\{Post, Apply};

class ApplyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function all()
    {
        $applies = Apply::with(['post', 'user'])->latest()->get();

        return view('admin.applies', compact('applies'));
    }

    public function save()
    {
        $user = auth()->user();
        $couponUsed = $user->applies()->count();

        if(($couponUsed >= 5) && ($user->points < 20)) {
            return response(['errors' => ['points' => trans('front.no points')]], 422);
        }
        
        $post = (new Post)->findPost(request('job'), request('identity'));

        (new Apply)->apply($post); 

        if($couponUsed >= 5) {
            $user->decrement('points', 20);
        }

        return '/dashboard/applies';
    }

    public function notify(Apply $apply)
    {
        $apply->is_applied = 1;
        $apply->save();

        event(new \App\Events\jobIsAppliedForStudent($apply));
        
        return [
            'msg' => trans('admin.notified'),
            'status' => trans('admin.applied')
        ];
    }
}
