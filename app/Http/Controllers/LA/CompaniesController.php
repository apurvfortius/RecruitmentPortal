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

class CompaniesController extends Controller
{
	public $show_action = true;
	public $view_col = 'title';
	public $listing_cols = ['id', 'title', 'website', 'description', 'profile_image'];

	//for display branches 
	public $listing_cols2 = ['id', 'company_id', 'type', 'country_id', 'state_id', 'city_id', 'address', 'contact_persopn', 'mobile', 'telephone', 'email'];
	
	public function __construct() {
		// Field Access of Listing Columns
		if(\Dwij\Laraadmin\Helpers\LAHelper::laravel_ver() == 5.3) {
			$this->middleware(function ($request, $next) {
				$this->listing_cols = ModuleFields::listingColumnAccessScan('Companies', $this->listing_cols);
				$this->listing_cols2 = ModuleFields::listingColumnAccessScan('Branches', $this->listing_cols2);
				return $next($request);
			});
		} else {
			$this->listing_cols = ModuleFields::listingColumnAccessScan('Companies', $this->listing_cols);
			$this->listing_cols2 = ModuleFields::listingColumnAccessScan('Branches', $this->listing_cols2);
		}
	}
	
	/**
	 * Display a listing of the Companies.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$module = Module::get('Companies');
		
		if(Module::hasAccess($module->id)) {
			return View('la.companies.index', [
				'show_actions' => $this->show_action,
				'listing_cols' => $this->listing_cols,
				'module' => $module
			]);
		} else {
            return redirect(config('laraadmin.adminRoute')."/");
		}
		parent::checkNotification();
	}

	/**
	 * Show the form for creating a new company.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		if(Module::hasAccess("Companies", "edit")) {			
			$module = Module::get('Companies');
			
			$module->row = $company;
			
			return view('la.companies.create', [
				'module' => $module,
				'view_col' => $this->view_col,
			]);
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Store a newly created company in database.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		if(Module::hasAccess("Companies", "create")) {
		
			$rules = Module::validateRules("Companies", $request);
			
			$validator = Validator::make($request->all(), $rules);
			
			if ($validator->fails()) {
				return redirect()->back()->withErrors($validator)->withInput();
			}
			
			$insert_id = Module::insert("Companies", $request);
			
			return redirect()->route(config('laraadmin.adminRoute') . '.companies.index');
			
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Display the specified company.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		if(Module::hasAccess("Companies", "view")) {
			
			$company = Company::find($id);
			if(isset($company->id)) {
				$module = Module::get('Companies');
				$module->row = $company;

				$moduleBranch = Module::get('Branches');
				
				$branch = Branch::where('company_id', $id)->get();
				return view('la.companies.show', [
					'module' => $module,
					'view_col' => $this->view_col,
					'no_header' => true,
					'no_padding' => "no-padding",
					'listing_cols2' => $this->listing_cols2,
					'moduleBranch' => $moduleBranch,
					'show_actions' => $this->show_action,
				])->with(['company' => $company, 'id' => $id]);
			} else {
				return view('errors.404', [
					'record_id' => $id,
					'record_name' => ucfirst("company"),
				]);
			}
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Show the form for editing the specified company.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		if(Module::hasAccess("Companies", "edit")) {			
			$company = Company::find($id);
			if(isset($company->id)) {	
				$module = Module::get('Companies');
				
				$module->row = $company;
				
				return view('la.companies.edit', [
					'module' => $module,
					'view_col' => $this->view_col,
				])->with('company', $company);
			} else {
				return view('errors.404', [
					'record_id' => $id,
					'record_name' => ucfirst("company"),
				]);
			}
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Update the specified company in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		if(Module::hasAccess("Companies", "edit")) {
			
			$rules = Module::validateRules("Companies", $request, true);
			
			$validator = Validator::make($request->all(), $rules);
			
			if ($validator->fails()) {
				return redirect()->back()->withErrors($validator)->withInput();;
			}
			
			$insert_id = Module::updateRow("Companies", $request, $id);
			
			return redirect()->route(config('laraadmin.adminRoute') . '.companies.index');
			
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Remove the specified company from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		if(Module::hasAccess("Companies", "delete")) {
			Company::find($id)->delete();
			
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
	public function dtajax()
	{
		$values = DB::table('companies')->select($this->listing_cols)->whereNull('deleted_at');
		$out = Datatables::of($values)->make();
		$data = $out->getData();

		$fields_popup = ModuleFields::getModuleFields('Companies');
		
		for($i=0; $i < count($data->data); $i++) {
			for ($j=0; $j < count($this->listing_cols); $j++) { 
				$col = $this->listing_cols[$j];
				if($fields_popup[$col] != null && starts_with($fields_popup[$col]->popup_vals, "@")) {
					$data->data[$i][$j] = ModuleFields::getFieldValue($fields_popup[$col], $data->data[$i][$j]);
				}
				if($col == $this->view_col) {
					$data->data[$i][$j] = '<a href="'.url(config('laraadmin.adminRoute') . '/companies/'.$data->data[$i][0]).'">'.$data->data[$i][$j].'</a>';
				}
				// else if($col == "author") {
				//    $data->data[$i][$j];
				// }
			}
			
			if($this->show_action) {
				$output = '';
				
				$output .= '<a href="'.url(config('laraadmin.adminRoute') . '/companies/branch/'.$data->data[$i][0]).'" class="btn btn-info btn-xs action_btn_table" style="display:inline;padding:2px 5px 3px 5px;" title="Add Branch"><i class="fa fa-plus"></i></a>';

				if(Module::hasAccess("Companies", "edit")) {
					$output .= '<a href="'.url(config('laraadmin.adminRoute') . '/companies/'.$data->data[$i][0].'/edit').'" class="btn btn-warning btn-xs action_btn_table" style="display:inline;padding:2px 5px 3px 5px;"><i class="fa fa-edit"></i></a>';
				}
				
				if(Module::hasAccess("Companies", "delete")) {
					$output .= Form::open(['route' => [config('laraadmin.adminRoute') . '.companies.destroy', $data->data[$i][0]], 'method' => 'delete', 'style'=>'display:inline']);
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
