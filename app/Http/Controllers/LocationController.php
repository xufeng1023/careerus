<?php

namespace App\Http\Controllers;

use App\City;

class LocationController extends Controller
{
    public function search()
    {
        $s = request('s');

        if($s && strlen($s) >= 3) {
            $cities = City::where('name', 'LIKE', $s.'%')->take(5)->get();

            $cities->load('state');

            return $cities;
        }
    }
}
