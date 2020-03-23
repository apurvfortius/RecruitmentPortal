<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;

use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\Admin_Notice;

class Controller extends BaseController
{
    use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;
    
    public function checkNotification()
    {
        $data = Admin_Notice::whereNull('read_at')->get();
        session()->put('assigned', $data);
    }

    public function CanEmailSend()
    {
        $var = \config('mail.username');
        return $var != null && $var != "null" && $var != "";
    }

    public function getCountryID($name)
    {
        $country = Country::where('countryName', '=', $name)->first();
        if($country){
            return (['countryID' => $country->countryID, 'id' => $country->id]);
        }
        else{
            $country = Country::create([
                'countryID' => strtoupper($name),
                'countryName' => $name,
                'localName' => $name,
                'latitude' => '0',
                'longitude' => '0',
            ]);
            return (['countryID' => $country->countryID, 'id' => $country->id]);
        }
    }

    public function getStateID($name, $country_id = 0)
    {
        $state = State::where('stateName', '=', $name)->first();
        if($state){
            return $state->id;
        }
        else{
            $state = State::create([
                'stateName' => $name,
                'countryID' => $country_id,
                'latitude' => '0',
                'longitude' => '0',
            ]);

            return $state->id;
        }
    }

    public function getCityID($name, $country_id = 0, $state_id = 0)
    {
        $city = City::where('cityName', '=', $name)->first();
        if($city){
            return $city->id;
        }
        else{
            $id = City::create([
                'cityName' => $name,
                'stateID' => $state_id,
                'countryID' => $country_id,
                'latitude' => '0',
                'longitude' => '0',
            ]);

            return $id->id;
        }
    }

    public function getNameByID($id, $type)
    {
        if($type == 'Country'){
            $result = Country::find($id);
            return $result->countryName;
        }
        elseif($type == 'State'){
            $result = State::find($id);
            return $result->stateName;
        }
        elseif($type == 'City'){
            $result = City::find($id);
            return $result->cityName;
        }
    }
}
