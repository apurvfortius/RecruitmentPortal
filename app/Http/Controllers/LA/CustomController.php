<?php

namespace App\Http\Controllers\LA;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Department;
use App\Models\Sub_Department;
use App\Models\City;
use App\Models\Branch;
use App\Models\Country;
use App\Models\State;

class CustomController extends Controller
{
    public function getDepartments(Request $request)
    {
        $result = Department::where('industry_id', $request->id)->get();
        $out = '';
		foreach($result as $item){
			if(isset($request->selected) && !empty($request->selected) && $request->selected == $item->id){
				$out .= "<option value=".$item->id." selected>".$item->title."</option>";
			}
			else{
				$out .= "<option value=".$item->id.">".$item->title."</option>";
			}			
		}
		return $out;
    }

    public function getSubDepartments(Request $request)
    {
        $result = Sub_Department::where('department_id', $request->id)->get();
        $out = '';
		foreach($result as $item){
			if(isset($request->selected) && !empty($request->selected) && $request->selected == $item->id){
				$out .= "<option value=".$item->id." selected>".$item->title."</option>";
			}
			else{
				$out .= "<option value=".$item->id.">".$item->title."</option>";
			}			
		}
		return $out;
    }

    public function getLocations(Request $request)
    {
        $result = Branch::where('company_id', $request->id)->get();
        $out = '';
		foreach($result as $item){
            $city = City::find($item->city_id);

			if(isset($request->selected) && !empty($request->selected) && $request->selected == $item->id){
				$out .= "<option value=".$item->id." selected>".$item->address." ".$city->cityName."</option>";
			}
			else{
				$out .= "<option value=".$item->id.">".$item->address." ".$city->cityName."</option>";
			}			
		}
		return $out;
	}
	
	public function getState(Request $request)
	{
		$id = Country::find($request->id);
		$result = State::where('countryID', $id->countryID)->get();
		$out = '';
		foreach($result as $item){
			if(isset($request->selected) && !empty($request->selected) && $request->selected == $item->id){
				$out .= "<option value=".$item->id." selected>".$item->stateName."</option>";
			}
			else{
				$out .= "<option value=".$item->id.">".$item->stateName."</option>";
			}			
		}
		return $out;
	}

	public function getCity(Request $request)
	{
		$result = City::where('stateID', $request->id)->get();
		$out = '';
		foreach($result as $item){
			if(isset($request->selected) && !empty($request->selected) && $request->selected == $item->id){
				$out .= "<option value=".$item->id." selected>".$item->cityName."</option>";
			}
			else{
				$out .= "<option value=".$item->id.">".$item->cityName."</option>";
			}			
		}
		return $out;
	}
}
