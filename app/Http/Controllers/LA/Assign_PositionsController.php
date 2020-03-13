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

use App\Models\Assign_Position;
use App\Models\Position;
use App\Models\Candidate;

class Assign_PositionsController extends Controller
{
	public $show_action = true;
	public $view_col = 'name';
	public $listing_cols = ['id', 'name', 'city', 'cityName', 'crnnt_desgnation', 'experience_title', 'crrnt_ctc', 'expected_ctc', 'notice_period', 'mobile_1', 'email_1', 'skype', 'assigned_position', 'assigned_position_id'];

	//public $position_cols = ['id', 'position_code', 'company_id', 'title', 'position_level', 'industry_id', 'department_id', 'sub_department_id', 'report_to', 'team_size', 'location', 'budget_id', 'qualification_ug', 'qualification_pg', 'no_position', 'req_exp_id', 'urgency_pos', 'buy_out', 'com_turnover', 'emp_strength'];

	//public $listing_label = ['Action', 'Name', 'City', 'Current Designation', 'Experience', 'Current CTC', 'Expected CTC', 'Notice Period', 'Mobile', 'Email', 'Skype'];
	
	public function __construct() {
		// Field Access of Listing Columns
		if(\Dwij\Laraadmin\Helpers\LAHelper::laravel_ver() == 5.3) {
			$this->middleware(function ($request, $next) {
				//$this->listing_cols = ModuleFields::listingColumnAccessScan('Candidates', $this->listing_cols);
				return $next($request);
			});
		} else {
			//$this->listing_cols = ModuleFields::listingColumnAccessScan('Candidates', $this->listing_cols);
		}
	}

	//below methods used by position module
	//assign candidate start here
	public function showAssignPage($id){
		$module = Module::get('Assign_Positions');
		if($module->insert_type == 'NEWPAGE'){
			if(Module::hasAccess("Assign_Positions", "create")) {			
				$position = Position::find($id);
				$result = DB::table('candidate_view')->groupBy('id')->get();
				
				$data = array();
				foreach($result as $filter){
					$data['notice_period'][$filter->notice_period] = $filter->notice_period;
					$data['city'][$filter->city] = $filter->cityName;
					$data['native_place'][$filter->native_place] = $filter->native_place;
					$data['crnnt_desgnation'][$filter->crnnt_desgnation] = $filter->crnnt_desgnation;
					if($filter->total_experience !== 0){
						$data['total_experience'][$filter->total_experience] = $filter->experience_title;
					}
					$data['qualification_ug'][$filter->qualification_ug] = $filter->under_graduate;
					$data['qualification_pg'][$filter->qualification_pg] = $filter->post_graduate;
				}
				
				//return $data['total_experience'];
				return view('la.assign_positions.assignCandidate', [
					// 'show_actions' => $this->show_action,
					// 'listing_cols' => $this->listing_cols,
					// 'module' => $module,
					'view_col' => $this->view_col,
					'position' => $position,
					'data' => $data,
					'id' => $id,
				]);
			} else {
				return redirect(config('laraadmin.adminRoute')."/");
			}
		}
		else{
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}
	
	public function getBasicSearch(Request $request, $search){
		if($search == 'Candidate'){
			$string = trim($request->str);
			$result = DB::table('candidate_view')->select($this->listing_cols)->where(function ($query) use ($string) {
				$query->where('name', 'LIKE', '%'.$string.'%')
						->orWhere('native_place', 'LIKE', '%'.$string.'%')
						->orWhere('crnnt_desgnation', 'LIKE', '%'.$string.'%')
						->orWhere('crnnt_desgnation', 'LIKE', '%'.$string.'%')
						->orWhere('company', 'LIKE', '%'.$string.'%')
						->orWhere('job_profile', 'LIKE', '%'.$string.'%');
			})->groupBy('id')->paginate(10);
			$data = array();
			$data = ['html' => static::createHtmlResponse($result), 'result' => $result];
			return response()->json($data, 200);
		}
		elseif($search == 'Position'){
			$string = trim($request->str);
			$result = DB::table('position_view')->where(function ($query) use ($string) {
				$query->where('title', 'LIKE', '%'.$string.'%')
						->orWhere('company_title', 'LIKE', '%'.$string.'%')
						->orWhere('position_level_title', 'LIKE', '%'.$string.'%')
						->orWhere('industry_title', 'LIKE', '%'.$string.'%')
						->orWhere('department_title', 'LIKE', '%'.$string.'%')
						->orWhere('cityName', 'LIKE', '%'.$string.'%')
						->orWhere('budget_title', 'LIKE', '%'.$string.'%')
						->orWhere('under_graduate', 'LIKE', '%'.$string.'%')
						->orWhere('post_graduate', 'LIKE', '%'.$string.'%')
						->orWhere('experience_title', 'LIKE', '%'.$string.'%')
						->orWhere('position_assigned_name', 'LIKE', '%'.$string.'%')
						->orWhere('sub_department_title', 'LIKE', '%'.$string.'%');
			})->groupBy('id')->paginate(10);
			$data = array();
			$data = ['html' => static::createHtmlPositionResponse($result), 'result' => $result];
			return response()->json($data, 200);
		}		
	}

	public function getAdvanceSearch(Request $request)
	{
		$query = DB::table('candidate_view')->select($this->listing_cols);
		if($request->has('city')){
			$query->whereIn('city', $request->city);				
		}

		if($request->has('total_experience')){
			$query->whereIn('total_experience', $request->total_experience);				
		}

		if($request->has('qualification_ug')){
			$query->whereIn('qualification_ug', $request->qualification_ug);				
		}

		if($request->has('qualification_pg')){
			$query->whereIn('qualification_pg', $request->qualification_pg);				
		}

		if($request->has('notice_period')){
			$query->whereIn('notice_period', $request->notice_period);				
		}

		$result = $query->groupBy('id')->paginate(10);
		
		$data = array();
		$data = ['html' => static::createHtmlResponse($result), 'result' => $result];
		return response()->json($data, 200);
	}

	public function assignCandidate(Request $request){
		$ids = $request->ids;
		foreach($ids as $id){
			$check = Assign_Position::where(['position_id' => $request->position, 'candidate_id' => $id ])->first();
			if(!$check){
				Assign_Position::create([
					'position_id' => $request->position,
					'candidate_id' => $id,
					'assigned_by' => Auth::user()->id,
				]);	
			}
		}

		return response()->json(array('msg' => "Candidate Assigned Successfully"), 200);
	}

	public function assignPosition(Request $request){
		$ids = $request->ids;
		foreach($ids as $id){
			$check = Assign_Position::where(['position_id' => $id, 'candidate_id' => $request->candidate ])->first();
			if(!$check){
				Assign_Position::create([
					'position_id' => $id,
					'candidate_id' => $request->candidate,
					'assigned_by' => Auth::user()->id,
				]);	
			}
		}

		return response()->json(array('msg' => "Position Assigned Successfully"), 200);
	}

	public function createHtmlResponse($result)
	{
		$html = "";
		foreach($result as $item){
			$html .= "<div class='row'>";			
				$html .= "<div class='col-md-12'>";
					$html .= "<div class='box box-success box-solid direct-chat direct-chat-info'>";
						$html .= "<div class='box-header with-border'><h3 class='box-title'>".$item->name."</h3>";
							$html .= "<div class='box-tools pull-right'>";
										if(!empty($item->assigned_position_id)){
											$html .= "<button class='btn btn-box-tool' data-toggle='tooltip' title='".$item->assigned_position." Position Assigned' data-widget='chat-pane-toggle'><i class='fa fa-comments'></i></button>";	
										}
										$html .= "<span data-toggle='tooltip' title='' class='badge bg-light-blue' data-original-title='Select to Assign'><input class='custom-control-input' id='assignbox' name='assignbox[".$item->id."]' type='checkbox' id='customCheckbox1' value=".$item->id."></span>";
										$html .= "<button type='button' class='btn btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i></button>"; 
							$html .= "</div>";
						$html .= "</div>";
						
						$html .= "<div class='box-body' style='padding: 15px !important'>";
							$html .= "<div class='row'>";
								$html .= "<div class='col-md-4'><h5>Detail</h5> <br><label>City : ".$item->cityName."</label><br><label>Experience : ".$item->experience_title."</label><br>
								<label>Notice Period : ".$item->notice_period."</label></div>";

								$html .= "<div class='col-md-4'><h5>Contact</h5> <br><label>Mobile : ".$item->mobile_1."/".$item->mobile_2."</label><br><label>Email : ".$item->email_1."/".$item->email_2."</label><br>
								<label>Skype : ".$item->skype."</label></div>";

								$html .= "<div class='col-md-4'><h5>CTC</h5> <br><label>Current : ".$item->crrnt_ctc."</label><br><label>Expected : ".$item->expected_ctc."</label></div>";
							$html .= "</div>";
							if(!empty($item->assigned_position_id)){
								$assigned = static::getAssignedPosition(explode(",", $item->assigned_position_id));
								$html .= "<div class='direct-chat-contacts' style='height: auto !important'>";
									$html .= "<ul class='contacts-list'>";
										foreach($assigned as $row){
											$html .= "<li>";
												$html .= "<a href='#'>";
													$html .= "<div class='contacts-list-info'>";
														$html .= "<span class='contacts-list-name'>".$row->title."</span>";
														$html .= "<span class='contacts-list-msg'>".$row->position_code."</span>";
														$html .= "<span class='btn btn-danger btn-xs' onclick='unasignedCandidate(".$row->id.", ".$item->id.");' >Un-Assign Position</span>";
													$html .= "</div>";
												$html .= "</a>";
											$html .= "</li>";
										}										
									$html .= "</ul>";
								$html .= "</div>";
							}								
						$html .= "</div>";	
					$html .= "</div>";
				$html .= "</div>";
			$html .= "</div>";
		}
		return $html;
	}

	public function createHtmlPositionResponse($result)
	{
		$html = "";
		foreach($result as $item){
			$html .= "<div class='row'>";			
				$html .= "<div class='col-md-12'>";
					$html .= "<div class='box box-success box-solid direct-chat direct-chat-info'>";
						$html .= "<div class='box-header with-border'><h3 class='box-title'>".$item->title."</h3> &nbsp&nbsp&nbsp<h4 class='box-title'>".$item->position_code."</h4>";
							$html .= "<div class='box-tools pull-right'>";
										// if(!empty($item->assigned_position_id)){
										// 	$html .= "<button class='btn btn-box-tool' data-toggle='tooltip' title='".$item->assigned_position." Position Assigned' data-widget='chat-pane-toggle'><i class='fa fa-comments'></i></button>";	
										// }
										$html .= "<span data-toggle='tooltip' title='' class='badge bg-light-blue' data-original-title='Select to Assign'><input class='custom-control-input' id='assignbox' name='assignbox[".$item->id."]' type='checkbox' id='customCheckbox1' value=".$item->id."></span>";
										$html .= "<button type='button' class='btn btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i></button>"; 
							$html .= "</div>";
						$html .= "</div>";
						
						$html .= "<div class='box-body' style='padding: 15px !important'>";
							$html .= "<div class='row'>";
								$html .= "<div class='col-md-4'><h5>Company</h5>";
									$html .= "<label>".$item->company_title."</label>";
								$html .= "</div>";
								$html .= "<div class='col-md-4'><h5>Website</h5>";
									$html .= "<label>".$item->company_website."</label>";
								$html .= "</div>";
								$html .= "<div class='col-md-4'><h5>Given By</h5>";
									$html .= "<label>".$item->pos_given_by."</label>";
								$html .= "</div>";
							$html .= "</div>";

							$html .= "<div class='row'>";
								$html .= "<div class='col-md-4'><h5>Detail</h5> <br>";
									$html .= "<label>Level : ".$item->position_level_title."</label><br>";
									$html .= "<label>Required Experience : ".$item->experience_title."</label><br>";
									$html .= "<label>Location : ".$item->cityName."</label>";
								$html .= "</div>";

								$html .= "<div class='col-md-4'><h5>Detail</h5> <br>";
									$html .= "<label>Industry : ".$item->industry_title."</label><br>";
									$html .= "<label>Department : ".$item->department_title."</label><br>";
									$html .= "<label>Sub Department : ".$item->sub_department_title."</label>";
								$html .= "</div>";

								$html .= "<div class='col-md-4'><h5>Detail</h5> <br>";
									$html .= "<label>Team Size : ".$item->team_size."</label><br>";
									$html .= "<label>Qualification UG : ".$item->under_graduate."</label><br>";
									$html .= "<label>Qualification PG : ".$item->post_graduate."</label>";
								$html .= "</div>";
							$html .= "</div>";
							if(!empty($item->assigned_position_id)){
								$assigned = static::getAssignedPosition(explode(",", $item->assigned_position_id));
								$html .= "<div class='direct-chat-contacts' style='height: auto !important'>";
									$html .= "<ul class='contacts-list'>";
										foreach($assigned as $row){
											$html .= "<li>";
												$html .= "<a href='#'>";
													$html .= "<div class='contacts-list-info'>";
														$html .= "<span class='contacts-list-name'>".$row->title."</span>";
														$html .= "<span class='contacts-list-msg'>".$row->position_code."</span>";
														$html .= "<span class='btn btn-danger btn-xs' onclick='unasignedCandidate(".$row->id.", ".$item->id.");' >Un-Assign Position</span>";
													$html .= "</div>";
												$html .= "</a>";
											$html .= "</li>";
										}										
									$html .= "</ul>";
								$html .= "</div>";
							}								
						$html .= "</div>";	
					$html .= "</div>";
				$html .= "</div>";
			$html .= "</div>";
		}
		return $html;
	}

	public function getAssignedPosition($id)
	{
		$data = array();
		foreach($id as $i){
			$data[] = Position::find($i);
		}
		return $data;
	}

	public function unassignCandidate(Request $request)
	{
		$query = Assign_Position::where(['position_id' => $request->position, 'candidate_id' => $request->candidate])
					->update(['deleted_at' => \Carbon\Carbon::now()]);
		if($query){
			return response()->json(['status' => 'Success', 'msg' => 'Successfully Updated'], 200);
		}
		else{
			return response()->json(['status' => 'Fail', 'msg' => 'Error'], 200);
		}
	}
	//assign candidtate end here


	//assign position start here
	public function showAssignPositionPage($id)
	{
		$module = Module::get('Assign_Positions');
		if($module->insert_type == 'NEWPAGE'){
			if(Module::hasAccess("Assign_Positions", "create")) {			
				$candidate = Candidate::find($id);
				$result = DB::table('position_view')->groupBy('id')->get();
				
				$data = array();
				foreach($result as $filter){
					$data['notice_period'][$filter->notice_period] = $filter->notice_period;
					
				}
				
				//return $data['total_experience'];
				return view('la.assign_positions.assignPosition', [
					// 'show_actions' => $this->show_action,
					// 'listing_cols' => $this->listing_cols,
					// 'module' => $module,
					'view_col' => $this->view_col,
					'candidate' => $candidate,
					'data' => $data,
					'id' => $id,
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
	 * Display a listing of the Assign_Positions.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$module = Module::get('Assign_Positions');
		
		if(Module::hasAccess($module->id)) {
			return View('la.assign_positions.index', [
				'show_actions' => $this->show_action,
				'listing_cols' => $this->listing_cols,
				'module' => $module
			]);
		} else {
            return redirect(config('laraadmin.adminRoute')."/");
        }
	}

	/**
	 * Show the form for creating a new assign_position.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$module = Module::get('Assign_Positions');
		if($module->insert_type == 'NEWPAGE'){
			if(Module::hasAccess("Assign_Positions", "create")) {			
				
					
				return view('la.assign_positions.create', [
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
	 * Store a newly created assign_position in database.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		if(Module::hasAccess("Assign_Positions", "create")) {
		
			$rules = Module::validateRules("Assign_Positions", $request);
			
			$validator = Validator::make($request->all(), $rules);
			
			if ($validator->fails()) {
				return redirect()->back()->withErrors($validator)->withInput();
			}
			
			$insert_id = Module::insert("Assign_Positions", $request);
			
			return redirect()->route(config('laraadmin.adminRoute') . '.assign_positions.index');
			
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Display the specified assign_position.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		if(Module::hasAccess("Assign_Positions", "view")) {
			
			$assign_position = Assign_Position::find($id);
			if(isset($assign_position->id)) {
				$module = Module::get('Assign_Positions');
				$module->row = $assign_position;
				
				return view('la.assign_positions.show', [
					'module' => $module,
					'view_col' => $this->view_col,
					'no_header' => true,
					'no_padding' => "no-padding"
				])->with('assign_position', $assign_position);
			} else {
				return view('errors.404', [
					'record_id' => $id,
					'record_name' => ucfirst("assign_position"),
				]);
			}
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Show the form for editing the specified assign_position.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		if(Module::hasAccess("Assign_Positions", "edit")) {			
			$assign_position = Assign_Position::find($id);
			if(isset($assign_position->id)) {	
				$module = Module::get('Assign_Positions');
				
				$module->row = $assign_position;
				
				return view('la.assign_positions.edit', [
					'module' => $module,
					'view_col' => $this->view_col,
				])->with('assign_position', $assign_position);
			} else {
				return view('errors.404', [
					'record_id' => $id,
					'record_name' => ucfirst("assign_position"),
				]);
			}
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Update the specified assign_position in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		if(Module::hasAccess("Assign_Positions", "edit")) {
			
			$rules = Module::validateRules("Assign_Positions", $request, true);
			
			$validator = Validator::make($request->all(), $rules);
			
			if ($validator->fails()) {
				return redirect()->back()->withErrors($validator)->withInput();;
			}
			
			$insert_id = Module::updateRow("Assign_Positions", $request, $id);
			
			return redirect()->route(config('laraadmin.adminRoute') . '.assign_positions.index');
			
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Remove the specified assign_position from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		if(Module::hasAccess("Assign_Positions", "delete")) {
			Assign_Position::find($id)->delete();
			
			// Redirecting to index() method
			return redirect()->route(config('laraadmin.adminRoute') . '.assign_positions.index');
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
		$values = DB::table('assign_positions')->select($this->listing_cols)->whereNull('deleted_at');
		$out = Datatables::of($values)->make();
		$data = $out->getData();

		$fields_popup = ModuleFields::getModuleFields('Assign_Positions');
		
		for($i=0; $i < count($data->data); $i++) {
			for ($j=0; $j < count($this->listing_cols); $j++) { 
				$col = $this->listing_cols[$j];
				if($fields_popup[$col] != null && starts_with($fields_popup[$col]->popup_vals, "@")) {
					$data->data[$i][$j] = ModuleFields::getFieldValue($fields_popup[$col], $data->data[$i][$j]);
				}
				if($col == $this->view_col) {
					$data->data[$i][$j] = '<a href="'.url(config('laraadmin.adminRoute') . '/assign_positions/'.$data->data[$i][0]).'">'.$data->data[$i][$j].'</a>';
				}
				// else if($col == "author") {
				//    $data->data[$i][$j];
				// }
			}
			
			if($this->show_action) {
				$output = '';
				if(Module::hasAccess("Assign_Positions", "edit")) {
					$output .= '<a href="'.url(config('laraadmin.adminRoute') . '/assign_positions/'.$data->data[$i][0].'/edit').'" class="btn btn-warning btn-xs" style="display:inline;padding:2px 5px 3px 5px;"><i class="fa fa-edit"></i></a>';
				}
				
				if(Module::hasAccess("Assign_Positions", "delete")) {
					$output .= Form::open(['route' => [config('laraadmin.adminRoute') . '.assign_positions.destroy', $data->data[$i][0]], 'method' => 'delete', 'style'=>'display:inline']);
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