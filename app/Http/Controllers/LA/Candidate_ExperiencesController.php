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

use App\Models\Candidate_Experience;
 
class Candidate_ExperiencesController extends Controller
{
	public $show_action = true;
	public $view_col = 'candidate_id';
	public $listing_cols = ['id', 'candidate_id', 'company', 'working_from', 'working_to', 'job_profile'];
	
	public function __construct() {
		// Field Access of Listing Columns
		if(\Dwij\Laraadmin\Helpers\LAHelper::laravel_ver() == 5.3) {
			$this->middleware(function ($request, $next) {
				$this->listing_cols = ModuleFields::listingColumnAccessScan('Candidate_Experiences', $this->listing_cols);
				return $next($request);
			});
		} else {
			$this->listing_cols = ModuleFields::listingColumnAccessScan('Candidate_Experiences', $this->listing_cols);
		}
		parent::checkNotification();
	}
	
	/**
	 * Display a listing of the Candidate_Experiences.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$module = Module::get('Candidate_Experiences');
		
		if(Module::hasAccess($module->id)) {
			return View('la.candidate_experiences.index', [
				'show_actions' => $this->show_action,
				'listing_cols' => $this->listing_cols,
				'module' => $module
			]);
		} else {
            return redirect(config('laraadmin.adminRoute')."/");
        }
	}

	/**
	 * Show the form for creating a new candidate_experience.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$module = Module::get('Candidate_Experiences');
		if($module->insert_type == 'NEWPAGE'){
			if(Module::hasAccess("Candidate_Experiences", "create")) {			
				
					
				return view('la.candidate_experiences.create', [
					'module' => $module,
					'view_col' => $this->view_col,
				]);
			} else {
				return redirect(config('laraadmin.adminRoute')."/");
			}
		}
		else{
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Store a newly created candidate_experience in database.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		if(Module::hasAccess("Candidate_Experiences", "create")) {
		
			$rules = Module::validateRules("Candidate_Experiences", $request);
			
			$validator = Validator::make($request->all(), $rules);
			
			if ($validator->fails()) {
				return redirect()->back()->withErrors($validator)->withInput();
			}
			
			$insert_id = Module::insert("Candidate_Experiences", $request);
			
			return redirect()->route(config('laraadmin.adminRoute') . '.candidate_experiences.index');
			
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Display the specified candidate_experience.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		if(Module::hasAccess("Candidate_Experiences", "view")) {
			
			$candidate_experience = Candidate_Experience::find($id);
			if(isset($candidate_experience->id)) {
				$module = Module::get('Candidate_Experiences');
				$module->row = $candidate_experience;
				
				return view('la.candidate_experiences.show', [
					'module' => $module,
					'view_col' => $this->view_col,
					'no_header' => true,
					'no_padding' => "no-padding"
				])->with('candidate_experience', $candidate_experience);
			} else {
				return view('errors.404', [
					'record_id' => $id,
					'record_name' => ucfirst("candidate_experience"),
				]);
			}
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Show the form for editing the specified candidate_experience.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		if(Module::hasAccess("Candidate_Experiences", "edit")) {			
			$candidate_experience = Candidate_Experience::find($id);
			if(isset($candidate_experience->id)) {	
				$module = Module::get('Candidate_Experiences');
				
				$module->row = $candidate_experience;
				
				return view('la.candidate_experiences.edit', [
					'module' => $module,
					'view_col' => $this->view_col,
				])->with('candidate_experience', $candidate_experience);
			} else {
				return view('errors.404', [
					'record_id' => $id,
					'record_name' => ucfirst("candidate_experience"),
				]);
			}
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Update the specified candidate_experience in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		if(Module::hasAccess("Candidate_Experiences", "edit")) {
			
			$rules = Module::validateRules("Candidate_Experiences", $request, true);
			
			$validator = Validator::make($request->all(), $rules);
			
			if ($validator->fails()) {
				return redirect()->back()->withErrors($validator)->withInput();;
			}
			
			$insert_id = Module::updateRow("Candidate_Experiences", $request, $id);
			
			return redirect()->route(config('laraadmin.adminRoute') . '.candidate_experiences.index');
			
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Remove the specified candidate_experience from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		if(Module::hasAccess("Candidate_Experiences", "delete")) {
			Candidate_Experience::find($id)->delete();
			
			// Redirecting to index() method
			return redirect()->route(config('laraadmin.adminRoute') . '.candidates.index');
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	public function delete($id)
	{
		if(Module::hasAccess("Candidate_Experiences", "delete")) {
			Candidate_Experience::find($id)->delete();
			return response()->json("success", 200);
		} else {
			return response()->json("you can not delete", 200);
		}
	}
	
	/**
	 * Datatable Ajax fetch
	 *
	 * @return
	 */
	public function dtajax(Request $request)
	{
		$id = $request->candidate_id;
		$values = DB::table('candidate_experiences')->select($this->listing_cols)->whereNull('deleted_at')->where('candidate_id', $id);
		$out = Datatables::of($values)->make();
		$data = $out->getData();

		$fields_popup = ModuleFields::getModuleFields('Candidate_Experiences');
		
		for($i=0; $i < count($data->data); $i++) {
			for ($j=0; $j < count($this->listing_cols); $j++) { 
				$col = $this->listing_cols[$j];
				if($fields_popup[$col] != null && starts_with($fields_popup[$col]->popup_vals, "@")) {
					$data->data[$i][$j] = ModuleFields::getFieldValue($fields_popup[$col], $data->data[$i][$j]);
				}
				if($col == $this->view_col) {
					$data->data[$i][$j] = '<a href="'.url(config('laraadmin.adminRoute') . '/candidate_experiences/'.$data->data[$i][0]).'">'.$data->data[$i][$j].'</a>';
				}
				// else if($col == "author") {
				//    $data->data[$i][$j];
				// }
			}
			
			if($this->show_action) {
				$output = '';
				if(Module::hasAccess("Candidate_Experiences", "edit")) {
					$output .= '<a href="'.url(config('laraadmin.adminRoute') . '/candidate_experiences/'.$data->data[$i][0].'/edit').'" class="btn btn-warning btn-xs" style="display:inline;padding:2px 5px 3px 5px;"><i class="fa fa-edit"></i></a>';
				}
				
				if(Module::hasAccess("Candidate_Experiences", "delete")) {
					$output .= Form::open(['route' => [config('laraadmin.adminRoute') . '.candidate_experiences.destroy', $data->data[$i][0]], 'method' => 'delete', 'style'=>'display:inline']);
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
