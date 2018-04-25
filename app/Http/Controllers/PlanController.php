<?php

namespace App\Http\Controllers;

use App\Plan;

class PlanController extends Controller
{
    public function index()
    {
        if(request('id')) $plans[] = Plan::find(request('id'));
        else $plans = Plan::all();

        return view('admin.plans', compact('plans'));
    }

    public function save()
    {
        Plan::create(request()->all());

        return back();
    }

    public function update(Plan $plan)
    {
        $plan->update(request()->all());

        return back()->with('updated', trans('admin.updated'));
    }
}
