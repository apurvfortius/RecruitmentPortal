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

use App\Models\Candidate;
use App\Models\Candidate_Experience;
use App\Models\City;
use App\Models\Assign_Position;

class CandidatesController extends Controller
{
	public $show_action = true;
	public $view_col = 'name';
	public $listing_cols = ['id', 'name', 'city', 'total_experience', 'qualification_ug', 'qualification_pg', 'crrnt_ctc', 'expected_ctc', 'notice_period', 'mobile_1', 'email_1', 'skype'];
	
	public $listing_cols2 = ['id', 'candidate_id', 'company', 'working_from', 'working_to', 'job_profile'];
	
	public $position_cols = ['id', 'position_code', 'company_id', 'title', 'position_level', 'no_position', 'jd_available', 'website', 'pos_date', 'job_description'];

	public function __construct() {
		// Field Access of Listing Columns
		if(\Dwij\Laraadmin\Helpers\LAHelper::laravel_ver() == 5.3) {
			$this->middleware(function ($request, $next) {
				$this->listing_cols = ModuleFields::listingColumnAccessScan('Candidates', $this->listing_cols);
				$this->listing_cols2 = ModuleFields::listingColumnAccessScan('Candidate_Experiences', $this->listing_cols2);
				return $next($request);
			});
		} else {
			$this->listing_cols = ModuleFields::listingColumnAccessScan('Candidates', $this->listing_cols);
			$this->listing_cols2 = ModuleFields::listingColumnAccessScan('Candidate_Experiences', $this->listing_cols2);
		}
		parent::checkNotification();
	}
	
	/**
	 * Display a listing of the Candidates.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$module = Module::get('Candidates');
		
		if(Module::hasAccess($module->id)) {
			return View('la.candidates.index', [
				'show_actions' => $this->show_action,
				'listing_cols' => $this->listing_cols,
				'module' => $module
			]);
		} else {
            return redirect(config('laraadmin.adminRoute')."/");
        }
	}

	/**
	 * Show the form for creating a new candidate.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$module = Module::get('Candidates');
		if($module->insert_type == 'NEWPAGE'){
			if(Module::hasAccess("Candidates", "create")) {			
				//$module2 = Module::get('Candidate_Experiences');
					
				return view('la.candidates.create', [
					'module' => $module,
					'view_col' => $this->view_col,
					//'module2' => $module2,
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
	 * Store a newly created candidate in database.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		//return $request;
		if(Module::hasAccess("Candidates", "create")) {
			$request->merge([ 'created_by' => Auth::user()->id, 'last_edited_by' => Auth::user()->id ]);

			$rules = Module::validateRules("Candidates", $request);
			
			$validator = Validator::make($request->all(), $rules);
			
			if ($validator->fails()) {
				return redirect()->back()->withErrors($validator)->withInput();
			}

			$city = City::where('cityName', '=', $request->city)->first();
			if($city){
				$request->merge([ 'city' => $city->id]);
			}
			else{
				$id = City::create([
					'cityName' => $request->city,
					'stateID' => '0',
					'countryID' => '0',
					'latitude' => '0',
					'longitude' => '0',
				]);

				$request->merge([ 'city' => $id->id]);
			}			

			$insert_id = Module::insert("Candidates", $request);
			foreach($request->company as $key => $item){
				if(!empty($item)){
					$working_from = $request->working_from[$key];
					$working_to = $request->working_to[$key];
					$job_profile = $request->job_profile[$key];

					Candidate_Experience::create([
						'candidate_id' => $insert_id,
						'company' => $item,
						'working_from' => $working_from,
						'working_to' => $working_to,
						'job_profile' => $job_profile,
					]);
				}
			}
			
			return redirect()->route(config('laraadmin.adminRoute') . '.candidates.index');
			
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Display the specified candidate.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		if(Module::hasAccess("Candidates", "view")) {
			
			$candidate = Candidate::find($id);
			if(isset($candidate->id)) {
				$module = Module::get('Candidates');
				$module->row = $candidate;
				
				$moduleCandidate = Module::get('Candidate_Experiences');

				return view('la.candidates.show', [
					'module' => $module,
					'view_col' => $this->view_col,
					'no_header' => true,
					'no_padding' => "no-padding",
					'listing_cols2' => $this->listing_cols2,
					'moduleCandidate' => $this->moduleCandidate,
					'show_actions' => $this->show_action,
					'position_cols' => $this->position_cols
				])->with(['candidate' => $candidate, 'id' => $id]);
			} else {
				return view('errors.404', [
					'record_id' => $id,
					'record_name' => ucfirst("candidate"),
				]);
			}
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Show the form for editing the specified candidate.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		if(Module::hasAccess("Candidates", "edit")) {			
			$candidate = Candidate::find($id);
			if(isset($candidate->id)) {	
				$module = Module::get('Candidates');
				
				$module->row = $candidate;
				$city = City::find($candidate->city);
				$experience = Candidate_Experience::where('candidate_id', $candidate->id)->get();
				return view('la.candidates.edit', [
					'module' => $module,
					'view_col' => $this->view_col,
				])->with(['candidate' => $candidate, 'city' => $city, 'experience' => $experience]);
			} else {
				return view('errors.404', [
					'record_id' => $id,
					'record_name' => ucfirst("candidate"),
				]);
			}
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Update the specified candidate in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		if(Module::hasAccess("Candidates", "edit")) {
			$request->merge([ 'last_edited_by' => Auth::user()->id ]);
			$rules = Module::validateRules("Candidates", $request, true);
			
			$validator = Validator::make($request->all(), $rules);
			
			if ($validator->fails()) {
				return redirect()->back()->withErrors($validator)->withInput();;
			}

			$city = City::where('cityName', '=', $request->city)->first();
			if($city){
				$request->merge([ 'city' => $city->id]);
			}
			else{
				$id = City::create([
					'cityName' => $request->city,
					'stateID' => '0',
					'countryID' => '0',
					'latitude' => '0',
					'longitude' => '0',
				]);

				$request->merge([ 'city' => $id->id]);
			}
			
			$insert_id = Module::updateRow("Candidates", $request, $id);

			foreach($request->company as $key => $item){
				if(!empty($item)){
					$working_from = $request->working_from[$key];
					$working_to = $request->working_to[$key];
					$job_profile = $request->job_profile[$key];
					if(isset($request->experiece_id[$key])){
						Candidate_Experience::where(['candidate_id' => $insert_id, 'id' => $request->experiece_id[$key]])
						->update([						
							'company' => $item,
							'working_from' => $working_from,
							'working_to' => $working_to,
							'job_profile' => $job_profile,
						]);
					}
					else{
						Candidate_Experience::create([
							'candidate_id' => $insert_id,
							'company' => $item,
							'working_from' => $working_from,
							'working_to' => $working_to,
							'job_profile' => $job_profile,
						]);
					}					
				}
			}
			
			return redirect()->route(config('laraadmin.adminRoute') . '.candidates.index');
			
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Remove the specified candidate from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		if(Module::hasAccess("Candidates", "delete")) {
			Candidate::find($id)->delete();
			
			// Redirecting to index() method
			return redirect()->route(config('laraadmin.adminRoute') . '.candidates.index');
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
		$values = DB::table('candidates')->select($this->listing_cols)->whereNull('deleted_at');
		$out = Datatables::of($values)->make();
		$data = $out->getData();

		$fields_popup = ModuleFields::getModuleFields('Candidates');
		
		for($i=0; $i < count($data->data); $i++) {
			for ($j=0; $j < count($this->listing_cols); $j++) { 
				$col = $this->listing_cols[$j];
				if($fields_popup[$col] != null && starts_with($fields_popup[$col]->popup_vals, "@")) {
					$data->data[$i][$j] = ModuleFields::getFieldValue($fields_popup[$col], $data->data[$i][$j]);
				}
				if($col == $this->view_col) {
					$data->data[$i][$j] = '<a href="'.url(config('laraadmin.adminRoute') . '/candidates/'.$data->data[$i][0]).'">'.$data->data[$i][$j].'</a>';
				}
				// else if($col == "author") {
				//    $data->data[$i][$j];
				// }
			}
			
			$res = Assign_Position::where('candidate_id', $data->data[$i][0])->count();					
			$data->data[$i][] = $res;
			
			if($this->show_action) {
				$output = '';

				$output .= '<a href="'.url(config('laraadmin.adminRoute') . '/assign_position/'.$data->data[$i][0]).'" class="btn btn-info btn-xs" style="display:inline; padding:2px 5px 3px 5px;"><i class="fa fa-plus"></i></a>';
				if(Module::hasAccess("Candidates", "edit")) {
					$output .= '<a href="'.url(config('laraadmin.adminRoute') . '/candidates/'.$data->data[$i][0].'/edit').'" class="btn btn-warning btn-xs" style="display:inline;padding:2px 5px 3px 5px;"><i class="fa fa-edit"></i></a>';
				}
				
				if(Module::hasAccess("Candidates", "delete")) {
					$output .= Form::open(['route' => [config('laraadmin.adminRoute') . '.candidates.destroy', $data->data[$i][0]], 'method' => 'delete', 'style'=>'display:inline']);
					$output .= ' <button class="btn btn-danger btn-xs" type="submit"><i class="fa fa-times"></i></button>';
					$output .= Form::close();
				}
				$data->data[$i][] = (string)$output;
			}
		}
		$out->setData($data);
		return $out;
	}
	
	public function getCity(Request $request)
	{
		$name = $request->str;
		$result = City::where('cityName', 'like', $name.'%')->get();

		$html = "";
		foreach($result as $item){
			$html .= "<option value=".$item->cityName.">";
		}
		return $html;
	}

	public function assign_candidate_dt_ajax($id)
	{
		$values = DB::table('candidates')->select($this->listing_cols)->whereNull('deleted_at');
		$out = Datatables::of($values)->make();
		$data = $out->getData();

		$fields_popup = ModuleFields::getModuleFields('Candidates');
		
		for($i=0; $i < count($data->data); $i++) {
			for ($j=0; $j < count($this->listing_cols); $j++) { 
				$col = $this->listing_cols[$j];
				if($fields_popup[$col] != null && starts_with($fields_popup[$col]->popup_vals, "@")) {
					$data->data[$i][$j] = ModuleFields::getFieldValue($fields_popup[$col], $data->data[$i][$j]);
				}
				if($col == $this->view_col) {
					$data->data[$i][$j] = '<a href="'.url(config('laraadmin.adminRoute') . '/candidates/'.$data->data[$i][0]).'">'.$data->data[$i][$j].'</a>';
				}
				// else if($col == "author") {
				//    $data->data[$i][$j];
				// }
			}
			
			if($this->show_action) {
				$output = '';

				$output .= '<a href="'.url(config('laraadmin.adminRoute') . '/assign_position/'.$data->data[$i][0]).'" class="btn btn-info btn-xs" style="display:inline; padding:2px 5px 3px 5px;"><i class="fa fa-plus"></i></a>';
				if(Module::hasAccess("Candidates", "edit")) {
					$output .= '<a href="'.url(config('laraadmin.adminRoute') . '/candidates/'.$data->data[$i][0].'/edit').'" class="btn btn-warning btn-xs" style="display:inline;padding:2px 5px 3px 5px;"><i class="fa fa-edit"></i></a>';
				}
				
				if(Module::hasAccess("Candidates", "delete")) {
					$output .= Form::open(['route' => [config('laraadmin.adminRoute') . '.candidates.destroy', $data->data[$i][0]], 'method' => 'delete', 'style'=>'display:inline']);
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
