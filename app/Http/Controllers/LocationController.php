<?php

namespace App\Http\Controllers;

use App\{City, State};

class LocationController extends Controller
{
    public function states()
    {
        return State::all();
    }

    public function search()
    {
        $s = request('s');

        if($s && strlen($s) >= 3) {
            $cities = City::where('name', 'LIKE', $s.'%')->take(5)->get();

            $cities->load('state');
            
            return $cities;
        }
    }

    public function citiesByState()
    {
        $state = State::where('STATE_CODE', request('s'))->firstOrFail();

        return City::where('ID_STATE', $state->ID)->where('CITY', 'LIKE', "%".request('k')."%")->get();
    }
}
