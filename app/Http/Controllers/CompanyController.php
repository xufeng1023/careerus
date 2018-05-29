<?php

namespace App\Http\Controllers;

use App\Company;

class CompanyController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function all()
    {
        if(request('id')) $companies[] = Company::find(request('id'))->load('visaJobs');
        else $companies = Company::latest()->get();

        return view('admin.company', compact('companies'));
    }

    public function save()
    {
        Company::create(request()->all());

        return back();
    }

    public function update(Company $company)
    {
        $company->update(request()->all());

        return back()->with('updated', trans('admin.updated'));
    }

    public function addVisa(Company $company)
    {
        try {
            $visa = $company->visaJobs()->create(request()->all());
        } catch(\Exception $e) {
            return response(trans('admin.visa may duplicate'), 422);
        }

        return response(['msg' => trans('admin.updated'), 'data' => $visa]);
    }

    public function delete(Company $company)
    {
        $company->visaJobs->each->remove();
        $company->posts->each->remove();
        $company->delete();
    }
}
