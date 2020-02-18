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

use App\Models\Qualification_UG;

class Qualification_UGsController extends Controller
{
	public $show_action = true;
	public $view_col = 'title';
	public $listing_cols = ['id', 'title'];
	
	public function __construct() {
		// Field Access of Listing Columns
		if(\Dwij\Laraadmin\Helpers\LAHelper::laravel_ver() == 5.3) {
			$this->middleware(function ($request, $next) {
				$this->listing_cols = ModuleFields::listingColumnAccessScan('Qualification_UGs', $this->listing_cols);
				return $next($request);
			});
		} else {
			$this->listing_cols = ModuleFields::listingColumnAccessScan('Qualification_UGs', $this->listing_cols);
		}
	}
	
	/**
	 * Display a listing of the Qualification_UGs.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$module = Module::get('Qualification_UGs');
		
		if(Module::hasAccess($module->id)) {
			return View('la.qualification_ugs.index', [
				'show_actions' => $this->show_action,
				'listing_cols' => $this->listing_cols,
				'module' => $module
			]);
		} else {
            return redirect(config('laraadmin.adminRoute')."/");
        }
	}

	/**
	 * Show the form for creating a new qualification_ug.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		if(Module::hasAccess("Qualification_UGs", "create")) {			
			$module = Module::get('Qualification_UGs');
			
			$module->row = $qualification_ug;
			
			return view('la.qualification_ugs.create', [
				'module' => $module,
				'view_col' => $this->view_col,
			]);
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Store a newly created qualification_ug in database.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		if(Module::hasAccess("Qualification_UGs", "create")) {
		
			$rules = Module::validateRules("Qualification_UGs", $request);
			
			$validator = Validator::make($request->all(), $rules);
			
			if ($validator->fails()) {
				return redirect()->back()->withErrors($validator)->withInput();
			}
			
			$insert_id = Module::insert("Qualification_UGs", $request);
			
			return redirect()->route(config('laraadmin.adminRoute') . '.qualification_ugs.index');
			
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Display the specified qualification_ug.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		if(Module::hasAccess("Qualification_UGs", "view")) {
			
			$qualification_ug = Qualification_UG::find($id);
			if(isset($qualification_ug->id)) {
				$module = Module::get('Qualification_UGs');
				$module->row = $qualification_ug;
				
				return view('la.qualification_ugs.show', [
					'module' => $module,
					'view_col' => $this->view_col,
					'no_header' => true,
					'no_padding' => "no-padding"
				])->with('qualification_ug', $qualification_ug);
			} else {
				return view('errors.404', [
					'record_id' => $id,
					'record_name' => ucfirst("qualification_ug"),
				]);
			}
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Show the form for editing the specified qualification_ug.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		if(Module::hasAccess("Qualification_UGs", "edit")) {			
			$qualification_ug = Qualification_UG::find($id);
			if(isset($qualification_ug->id)) {	
				$module = Module::get('Qualification_UGs');
				
				$module->row = $qualification_ug;
				
				return view('la.qualification_ugs.edit', [
					'module' => $module,
					'view_col' => $this->view_col,
				])->with('qualification_ug', $qualification_ug);
			} else {
				return view('errors.404', [
					'record_id' => $id,
					'record_name' => ucfirst("qualification_ug"),
				]);
			}
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Update the specified qualification_ug in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		if(Module::hasAccess("Qualification_UGs", "edit")) {
			
			$rules = Module::validateRules("Qualification_UGs", $request, true);
			
			$validator = Validator::make($request->all(), $rules);
			
			if ($validator->fails()) {
				return redirect()->back()->withErrors($validator)->withInput();;
			}
			
			$insert_id = Module::updateRow("Qualification_UGs", $request, $id);
			
			return redirect()->route(config('laraadmin.adminRoute') . '.qualification_ugs.index');
			
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Remove the specified qualification_ug from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		if(Module::hasAccess("Qualification_UGs", "delete")) {
			Qualification_UG::find($id)->delete();
			
			// Redirecting to index() method
			return redirect()->route(config('laraadmin.adminRoute') . '.qualification_ugs.index');
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
		$values = DB::table('qualification_ugs')->select($this->listing_cols)->whereNull('deleted_at');
		$out = Datatables::of($values)->make();
		$data = $out->getData();

		$fields_popup = ModuleFields::getModuleFields('Qualification_UGs');
		
		for($i=0; $i < count($data->data); $i++) {
			for ($j=0; $j < count($this->listing_cols); $j++) { 
				$col = $this->listing_cols[$j];
				if($fields_popup[$col] != null && starts_with($fields_popup[$col]->popup_vals, "@")) {
					$data->data[$i][$j] = ModuleFields::getFieldValue($fields_popup[$col], $data->data[$i][$j]);
				}
				if($col == $this->view_col) {
					$data->data[$i][$j] = '<a href="'.url(config('laraadmin.adminRoute') . '/qualification_ugs/'.$data->data[$i][0]).'">'.$data->data[$i][$j].'</a>';
				}
				// else if($col == "author") {
				//    $data->data[$i][$j];
				// }
			}
			
			if($this->show_action) {
				$output = '';
				if(Module::hasAccess("Qualification_UGs", "edit")) {
					$output .= '<a href="'.url(config('laraadmin.adminRoute') . '/qualification_ugs/'.$data->data[$i][0].'/edit').'" class="btn btn-warning btn-xs" style="display:inline;padding:2px 5px 3px 5px;"><i class="fa fa-edit"></i></a>';
				}
				
				if(Module::hasAccess("Qualification_UGs", "delete")) {
					$output .= Form::open(['route' => [config('laraadmin.adminRoute') . '.qualification_ugs.destroy', $data->data[$i][0]], 'method' => 'delete', 'style'=>'display:inline']);
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
