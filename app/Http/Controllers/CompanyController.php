<?php

namespace App\Http\Controllers;

use App\{CompanyData};

class CompanyController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function select2Companies()
    {
        return CompanyData::where('name', 'like', request('search').'%')->take(5)->get();
    }

    public function all()
    {
        if(request('id')) $companies[] = CompanyData::find(request('id'))->load('visaJobs');
        else $companies = CompanyData::latest()->get();

        return view('admin.company', compact('companies'));
    }

    public function save()
    {
        CompanyData::create(request()->all());

        return back();
    }

    public function update(CompanyData $company)
    {
        $company->update(request()->all());

        return back()->with('updated', trans('admin.updated'));
    }

    public function addVisa(CompanyData $company)
    {
        try {
            $visa = $company->visaJobs()->create(request()->all());
        } catch(\Exception $e) {
            return response(trans('admin.visa may duplicate'), 422);
        }

        return response(['msg' => trans('admin.updated'), 'data' => $visa]);
    }
}
