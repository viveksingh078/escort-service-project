<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\State;
use App\Models\City;

class LocationController extends Controller
{
    // Fetch all countries
    public function getCountries()
    {
        $countries = Country::orderBy('name')->get();
        return response()->json($countries);
    }

    // Fetch states by country
    public function getStates($country_id)
    {
        $states = State::where('country_id', $country_id)->orderBy('name')->get();
        if (request()->query('with_key', false)) {
            return response()->json(['states' => $states]); // For homepage JS
        }
        return response()->json($states); // For admin panel (legacy)
    }

    // Fetch cities by state
    public function getCities($state_id)
    {
        $cities = City::where('state_id', $state_id)->orderBy('name')->get();
        if (request()->query('with_key', false)) {
            return response()->json(['cities' => $cities]); // For homepage JS
        }
        return response()->json($cities); // For admin panel (legacy)
    }
}