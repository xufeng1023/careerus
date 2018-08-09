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
        return view('admin.applies');
    }

    public function save()
    {
        if(!auth()->user()->confirmed) return response('', 403);

        if(auth()->user()->suspended) return response(['toastr' => trans('front.bad resume msg')], 403);
        
        if(app()->environment() !== 'testing') {
            if(! \Storage::exists(auth()->user()->resume)) {
                return response(['errors' => ['resume' => trans('front.resume invalid')]], 422);
            }
        }

        if(auth()->user()->applyCount >= cache('apply_times_a_day', 5)) {
            return response('', 422);
        }

        $post = (new Post)->findPost(request('job'), request('identity'));

        if($post->applyTimes() >= cache('job_applies_limit', 10)) {
            return response('', 422);
        }

        (new Apply)->apply($post);

        //event(new \App\Events\StudentAppliedEvent($post));

        return '/dashboard/applies';
    }

    public function fetch()
    {
        return Apply::with(['post.company', 'user'])->latest()->get();
    }

    public function send($emails = [])
    {
        $applies = Apply::with(['post.company', 'user'])->where('is_applied', 0)->get();

        foreach($applies as $a) {
            $emails[$a->post->company->email][$a->post->title][] = $a->user;

            $a->is_applied = 1;
            $a->save();
        }

        foreach($emails as $hrEmail => $jobs) {
            foreach($jobs as $job => $user) {
                \Mail::to($hrEmail)->send(new \App\Mail\NotifyHREmail($user, $job));
            }
        }
    }

    // public function save()
    // {
    //     $user = auth()->user();
    //     $couponUsed = $user->applies()->count();

    //     if(($couponUsed >= 5) && ($user->points < 20)) {
    //         return response(['errors' => ['points' => trans('front.no points')]], 422);
    //     }
        
    //     $post = (new Post)->findPost(request('job'), request('identity'));

    //     (new Apply)->apply($post); 

    //     if($couponUsed >= 5) {
    //         $user->decrement('points', 20);
    //     }

    //     return '/dashboard/applies';
    // }

    // public function notify(Apply $apply)
    // {
    //     $apply->is_applied = 1;
    //     $apply->save();

    //     event(new \App\Events\jobIsAppliedForStudent($apply));
        
    //     return [
    //         'msg' => trans('admin.notified'),
    //         'status' => trans('admin.applied')
    //     ];
    // }
}
