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

use App\Models\Position;
use App\Models\Assign_Position;

class PositionsController extends Controller
{
	public $show_action = true;
	public $view_col = 'title';
	public $listing_cols = ['id', 'position_code', 'company_id', 'title', 'position_level', 'no_position', 'jd_available', 'website', 'pos_date', 'job_description'];

	public $candidate_cols = ['id', 'name', 'city', 'total_experience', 'crrnt_ctc', 'expected_ctc', 'notice_period', 'mobile_1', 'email_1', 'skype'];
	
	public function __construct() {
		// Field Access of Listing Columns
		if(\Dwij\Laraadmin\Helpers\LAHelper::laravel_ver() == 5.3) {
			$this->middleware(function ($request, $next) {
				$this->listing_cols = ModuleFields::listingColumnAccessScan('Positions', $this->listing_cols);
				$this->candidate_cols = ModuleFields::listingColumnAccessScan('Candidates', $this->candidate_cols);
				return $next($request);
			});
		} else {
			$this->listing_cols = ModuleFields::listingColumnAccessScan('Positions', $this->listing_cols);
			$this->candidate_cols = ModuleFields::listingColumnAccessScan('Candidates', $this->candidate_cols);
		}
	}
	
	/**
	 * Display a listing of the Positions.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$module = Module::get('Positions');
		
		if(Module::hasAccess($module->id)) {
			return View('la.positions.index', [
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
	 * Show the form for creating a new position.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		if(Module::hasAccess("Positions", "create")) {			
			$module = Module::get('Positions');
			
			$module->row = $position;
			$last_id = Position::orderBy('id', 'desc')->first();			
			if($last_id){
				$no = explode("_", $last_id->position_code);
				
				$number = $no[2] + 1;
				$number = "NCS_".date('Y')."_".sprintf("%05s", $number);
			}
			else{
				$number = "NCS_".date('Y')."_".sprintf("%05s", 1);
			}
			return view('la.positions.create', [
				'module' => $module,
				'view_col' => $this->view_col,
				'number' => $number,
			]);
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Store a newly created position in database.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		if(Module::hasAccess("Positions", "create")) {
			$last_id = Position::orderBy('id', 'desc')->first();			
			if($last_id){
				$no = explode("_", $last_id->position_code);
				$number = $no[2] + 1;
				$number = sprintf("%05s", $number);
			}
			else{
				$number = sprintf("%05s", 1);
			}
			$request->request->add(['position_code' => "NCS_".date('Y')."_".$number]);
			//$request->position_code = "NCS_".date('Y')."_".$number;
			
			$rules = Module::validateRules("Positions", $request);
			
			$validator = Validator::make($request->all(), $rules);
			
			if ($validator->fails()) {
				return redirect()->back()->withErrors($validator)->withInput();
			}
			
			$insert_id = Module::insert("Positions", $request);
			
			return redirect()->route(config('laraadmin.adminRoute') . '.positions.index');
			
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Display the specified position.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		if(Module::hasAccess("Positions", "view")) {
			$position = Position::find($id);
			if(isset($position->id)) {
				$module = Module::get('Positions');
				$module->row = $position;
				$candidate_module = Module::get('Candidates');
							
				return view('la.positions.show', [
					'module' => $module,
					'view_col' => $this->view_col,
					'no_header' => true,
					'no_padding' => "no-padding",
					'candidate_cols' => $this->candidate_cols,
					'candidate_module' => $candidate_module,
				])->with(['position' =>  $position]);
			} else {
				return view('errors.404', [
					'record_id' => $id,
					'record_name' => ucfirst("position"),
				]);
			}
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Show the form for editing the specified position.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		if(Module::hasAccess("Positions", "edit")) {			
			$position = Position::find($id);
			if(isset($position->id)) {	
				$module = Module::get('Positions');
				
				$module->row = $position;
				
				return view('la.positions.edit', [
					'module' => $module,
					'view_col' => $this->view_col,
				])->with('position', $position);
			} else {
				return view('errors.404', [
					'record_id' => $id,
					'record_name' => ucfirst("position"),
				]);
			}
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Update the specified position in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		if(Module::hasAccess("Positions", "edit")) {
			
			$rules = Module::validateRules("Positions", $request, true);
			
			$validator = Validator::make($request->all(), $rules);
			
			if ($validator->fails()) {
				return redirect()->back()->withErrors($validator)->withInput();;
			}
			
			$insert_id = Module::updateRow("Positions", $request, $id);
			
			return redirect()->route(config('laraadmin.adminRoute') . '.positions.index');
			
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Remove the specified position from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		if(Module::hasAccess("Positions", "delete")) {
			Position::find($id)->delete();
			
			// Redirecting to index() method
			return redirect()->route(config('laraadmin.adminRoute') . '.positions.index');
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
		$values = DB::table('positions')->select($this->listing_cols)->whereNull('deleted_at');
		$out = Datatables::of($values)->make();
		$data = $out->getData();

		$fields_popup = ModuleFields::getModuleFields('Positions');
		
		for($i=0; $i < count($data->data); $i++) {
			for ($j=0; $j < count($this->listing_cols); $j++) { 
				$col = $this->listing_cols[$j];
				if($fields_popup[$col] != null && starts_with($fields_popup[$col]->popup_vals, "@" && $col !== "assigned_candidate")) {
					$data->data[$i][$j] = ModuleFields::getFieldValue($fields_popup[$col], $data->data[$i][$j]);
				}
				if($col == $this->view_col) {
					$data->data[$i][$j] = '<a href="'.url(config('laraadmin.adminRoute') . '/positions/'.$data->data[$i][0]).'">'.$data->data[$i][$j].'</a>';
				}
			}
			$res = Assign_Position::where('position_id', $data->data[$i][0])->count();					
			$data->data[$i][] = $res;
			
			if($this->show_action) {
				$output = '';
				$output .= '<a href="'.url(config('laraadmin.adminRoute') . '/assign_candidate/'.$data->data[$i][0]).'" class="btn btn-info btn-xs" style="display:inline; padding:2px 5px 3px 5px;"><i class="fa fa-plus"></i></a>';
				//$output .= '<a href="'.url(config('laraadmin.adminRoute') . '/assign_positions/'.$data->data[$i][0].'/show').'" class="btn btn-success btn-xs" style="display:inline; padding:2px 5px 3px 5px;"><i class="fa fa-eye"></i></a>';
				if(Module::hasAccess("Positions", "edit")) {
					$output .= '<a href="'.url(config('laraadmin.adminRoute') . '/positions/'.$data->data[$i][0].'/edit').'" class="btn btn-warning btn-xs" style="display:inline; padding:2px 5px 3px 5px;"><i class="fa fa-edit"></i></a>';
				}
				
				if(Module::hasAccess("Positions", "delete")) {
					$output .= Form::open(['route' => [config('laraadmin.adminRoute') . '.positions.destroy', $data->data[$i][0]], 'method' => 'delete', 'style'=>'display:inline']);
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
