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
use App\Models\Company;

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

	//used in position module
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

	//used in position module
	public function getWebsite(Request $request)
    {
        $result = Company::find($request->id);
		return $result->website;
	}

	public function getCountry(Request $request)
	{
		$result = Country::where('countryName', 'like', '%'.$request->id.'%')->get();

		$html = "";
		foreach($result as $item){
			$html .= "<option value=".$item->countryName.">";
		}
		return $html;
	}
	
	public function getState(Request $request)
	{
		$id = Country::where('countryName', $request->id)->get();
		$result = State::where('countryID', $id[0]->countryID)->get();
		$out = '';
		foreach($result as $item){
			if(isset($request->selected) && !empty($request->selected) && $request->selected == $item->id){
				$out .= "<option value=".$item->stateName." selected>";
			}
			else{
				$out .= "<option value=".$item->stateName.">";
			}			
		}
		return $out;
	}

	public function getCity(Request $request)
	{
		$id = State::where('stateName', $request->id)->get();
		$result = City::where('stateID', $id[0]->id)->get();
		$out = '';
		foreach($result as $item){
			if(isset($request->selected) && !empty($request->selected) && $request->selected == $item->id){
				$out .= "<option value=".$item->cityName." selected>";
			}
			else{
				$out .= "<option value=".$item->cityName.">";
			}			
		}
		return $out;
	}
}
