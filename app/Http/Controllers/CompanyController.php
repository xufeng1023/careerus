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
        if(request('id')) $users[] = $companies[] = Company::find(request('id'));
        else $companies = Company::all();

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
}
