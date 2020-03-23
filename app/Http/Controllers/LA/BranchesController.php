<?php
/**
 * Controller genrated using LaraAdmin
 * Help: Contact Sagar Upadhyay (usagar80@gmail.com)
 */

namespace App\Http\Controllers\LA;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use Auth;
use DB;
use Validator;
use Datatables;
use Collective\Html\FormFacade as Form;
use Dwij\Laraadmin\Models\Module;
use Dwij\Laraadmin\Models\ModuleFields;

use App\Models\Company;
use App\Models\Branch;
use App\Models\Country;
use App\Models\State;
use App\Models\City;

class BranchesController extends Controller
{
	public $show_action = true;
	public $view_col = 'company_id';
	public $listing_cols = ['id', 'company_id', 'type', 'country_id', 'state_id', 'city_id', 'address', 'contact_persopn', 'mobile', 'telephone', 'email'];
	
	public function __construct() {
		// Field Access of Listing Columns
		if(\Dwij\Laraadmin\Helpers\LAHelper::laravel_ver() == 5.3) {
			$this->middleware(function ($request, $next) {
				$this->listing_cols = ModuleFields::listingColumnAccessScan('Branches', $this->listing_cols);
				return $next($request);
			});
		} else {
			$this->listing_cols = ModuleFields::listingColumnAccessScan('Branches', $this->listing_cols);
		}
		parent::checkNotification();
	}
	
	/**
	 * Display a listing of the Branches.
	 *
	 * @return \Illuminate\Http\Response
	 */
	// public function index()
	// {
	// 	$module = Module::get('Branches');
		
	// 	if(Module::hasAccess($module->id)) {
	// 		return View('la.branches.index', [
	// 			'show_actions' => $this->show_action,
	// 			'listing_cols' => $this->listing_cols,
	// 			'module' => $module
	// 		]);
	// 	} else {
    //         return redirect(config('laraadmin.adminRoute')."/");
    //     }
	// }

	/**
	 * Show the form for creating a new branch.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create($id)
	{
		if(Module::hasAccess("Branches", "create")) {			
			$module = Module::get('Branches');
			
			$company = Company::find($id);
			return view('la.branches.create', [
				'module' => $module,
				'view_col' => $this->view_col,
			])->with(['id' => $id, 'company' => $company]);
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Store a newly created branch in database.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		if(Module::hasAccess("Branches", "create")) {
		
			$rules = Module::validateRules("Branches", $request);
			
			$validator = Validator::make($request->all(), $rules);
			
			if ($validator->fails()) {
				return redirect()->back()->withErrors($validator)->withInput();
			}
			//check and store country
			$country_id = parent::getCountryID($request->country_id);
			$request->merge([ 'country_id' => $country_id['id']]);
			
			//check and store state
			$state_id = parent::getStateID($request->state_id, $country_id['countryID']);
			$request->merge([ 'state_id' => $state_id]);

			//check and store city
			$city = parent::getCityID($request->city_id, $country_id['countryID'], $state_id);
			$request->merge([ 'city_id' => $city]);

			$insert_id = Module::insert("Branches", $request);
			
			return redirect()->route(config('laraadmin.adminRoute') . '.companies.index');
			
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Display the specified branch.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	// public function show($id)
	// {
	// 	if(Module::hasAccess("Branches", "view")) {
			
	// 		$branch = Branch::find($id);
	// 		if(isset($branch->id)) {
	// 			$module = Module::get('Branches');
	// 			$module->row = $branch;
				
	// 			return view('la.branches.show', [
	// 				'module' => $module,
	// 				'view_col' => $this->view_col,
	// 				'no_header' => true,
	// 				'no_padding' => "no-padding"
	// 			])->with('branch', $branch);
	// 		} else {
	// 			return view('errors.404', [
	// 				'record_id' => $id,
	// 				'record_name' => ucfirst("branch"),
	// 			]);
	// 		}
	// 	} else {
	// 		return redirect(config('laraadmin.adminRoute')."/");
	// 	}
	// }

	/**
	 * Show the form for editing the specified branch.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		if(Module::hasAccess("Branches", "edit")) {			
			$branch = Branch::find($id);
			if(isset($branch->id)) {	
				$module = Module::get('Branches');
				
				$module->row = $branch;

				$data = array();
				$data['country'] = parent::getNameByID($branch->country_id, 'Country');
				$data['state'] = parent::getNameByID($branch->state_id, 'State');
				$data['city'] = parent::getNameByID($branch->city_id, 'City'); 
				
				return view('la.branches.edit', [
					'module' => $module,
					'view_col' => $this->view_col,
				])->with(['branch' => $branch, 'data' => $data]);
			} else {
				return view('errors.404', [
					'record_id' => $id,
					'record_name' => ucfirst("branch"),
				]);
			}
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Update the specified branch in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		if(Module::hasAccess("Branches", "edit")) {
			
			$rules = Module::validateRules("Branches", $request, true);
			
			$validator = Validator::make($request->all(), $rules);
			
			if ($validator->fails()) {
				return redirect()->back()->withErrors($validator)->withInput();;
			}

			//check and store country
			$country_id = parent::getCountryID($request->country_id);
			$request->merge([ 'country_id' => $country_id['id']]);
			
			//check and store state
			$state_id = parent::getStateID($request->state_id, $country_id['countryID']);
			$request->merge([ 'state_id' => $state_id]);

			//check and store city
			$city = parent::getCityID($request->city_id, $country_id['countryID'], $state_id);
			$request->merge([ 'city_id' => $city]);
			
			$insert_id = Module::updateRow("Branches", $request, $id);
			
			return redirect()->route(config('laraadmin.adminRoute') . '.companies.index');
			
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Remove the specified branch from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		if(Module::hasAccess("Branches", "delete")) {
			Branch::find($id)->delete();
			
			// Redirecting to index() method
			return redirect()->route(config('laraadmin.adminRoute') . '.companies.index');
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}
	
	/**
	 * Datatable Ajax fetch
	 *
	 * @return
	 */
	public function dtajax(Request $request)
	{
		$com_id = $request->company_id;
		$values = DB::table('branches')->select($this->listing_cols)->whereNull('deleted_at')->where('company_id', $com_id);
		$out = Datatables::of($values)->make();
		$data = $out->getData();

		$fields_popup = ModuleFields::getModuleFields('Branches');
		
		for($i=0; $i < count($data->data); $i++) {
			for ($j=0; $j < count($this->listing_cols); $j++) { 
				$col = $this->listing_cols[$j];
				if($fields_popup[$col] != null && starts_with($fields_popup[$col]->popup_vals, "@")) {
					$data->data[$i][$j] = ModuleFields::getFieldValue($fields_popup[$col], $data->data[$i][$j]);
				}
				if($col == $this->view_col) {
					$data->data[$i][$j] = '<a href="'.url(config('laraadmin.adminRoute') . '/branches/'.$data->data[$i][0]).'">'.$data->data[$i][$j].'</a>';
				}
			}
			
			if($this->show_action) {
				$output = '';
				if(Module::hasAccess("Branches", "edit")) {
					$output .= '<a href="'.url(config('laraadmin.adminRoute') . '/branches/'.$data->data[$i][0].'/edit').'" class="btn btn-warning btn-xs action_btn_table" style="display:inline;padding:2px 5px 3px 5px;"><i class="fa fa-edit"></i></a>';
				}
				
				if(Module::hasAccess("Branches", "delete")) {
					$output .= Form::open(['route' => [config('laraadmin.adminRoute') . '.branches.destroy', $data->data[$i][0]], 'method' => 'delete', 'style'=>'display:inline']);
					$output .= ' <button class="btn btn-danger btn-xs" type="submit"><i class="fa fa-times"></i></button>';
					$output .= Form::close();
				}
				$data->data[$i][] = (string)$output;
			}
		}
		$out->setData($data);
		return $out;
	}
}
