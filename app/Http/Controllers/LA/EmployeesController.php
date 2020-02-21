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
use Dwij\Laraadmin\Models\LAConfigs;
use Dwij\Laraadmin\Helpers\LAHelper;

use App\User;
use App\Models\Employee;
use App\Models\Setting;
use App\Models\Message;
use App\Role;
use Mail;
use Log;

class EmployeesController extends Controller
{
	public $show_action = true;
	public $view_col = 'name';
	public $listing_cols = ['id', 'employee_code', 'name', 'mobile', 'email', 'country_id', 'state_id', 'city', 'crnt_address'];
	
	public function __construct() {
		// Field Access of Listing Columns
		if(\Dwij\Laraadmin\Helpers\LAHelper::laravel_ver() == 5.3) {
			$this->middleware(function ($request, $next) {
				$this->listing_cols = ModuleFields::listingColumnAccessScan('Employees', $this->listing_cols);
				return $next($request);
			});
		} else {
			$this->listing_cols = ModuleFields::listingColumnAccessScan('Employees', $this->listing_cols);
		}
	}
	
	/**
	 * Display a listing of the Employees.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$module = Module::get('Employees');
		
		// // Get User Table Information
		// $user = User::where('context_id', '=', $id)->firstOrFail();
		
		if(Module::hasAccess($module->id)) {
			return View('la.employees.index', [
				'show_actions' => $this->show_action,
				'listing_cols' => $this->listing_cols,
				'module' => $module
			]);
		} else {
            return redirect(config('laraadmin.adminRoute')."/");
        }
	}

	/**
	 * Show the form for creating a new employee.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		if(Module::hasAccess("Employees", "create")) {
			$module = Module::get('Employees');
			
			// Get User Table Information
			//$user = User::where('context_id', '=', $id)->firstOrFail();
			
			return view('la.employees.create', [
				'module' => $module,
				'view_col' => $this->view_col,
				//'user' => $user,
			]);
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Store a newly created employee in database.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		if(Module::hasAccess("Employees", "create")) {
			$last_id = Employee::orderBy('id', 'desc')->first();			
			if($last_id){
				$number = sprintf("%04s", $last_id->id + 1);
			}
			else{
				$number = sprintf("%04s", 1);
			}
			$request->request->add(['employee_code' => "NCS_".$number]);
			$rules = Module::validateRules("Employees", $request);
			
			$validator = Validator::make($request->all(), $rules);
			
			if ($validator->fails()) {
				return redirect()->back()->withErrors($validator)->withInput();
			}
			
			// generate password
			$password = LAHelper::gen_password();
			
			// Create Employee
			$employee_id = Module::insert("Employees", $request);

			// Create User
			$user = User::create([
				'name' => $request->name,
				'email' => $request->email,
				'password' => bcrypt($password),
				'context_id' => $employee_id,
				'type' => "Employee",
				'verifytoken' => uniqid(),
				'verifytokengenerateat' => date('Y-m-d h:i:s')
			]);
	
			// update user role
			$user->detachRoles();
			$role = Role::find($request->role);
			$user->attachRole($role);

			if(parent::CanEmailSend()) {
				// Send mail to User his Password
				Mail::send('emails.send_login_cred', ['user' => $user], function ($m) use ($user) {
					$m->from(env('MAIL_FROM'), 'LaraAdmin');
					$m->to($user->email, $user->name)->subject(Setting::getByKey('NewUserMessage'));
				});
			}

			Log::info("User created: username: ".$user->email);
			return redirect()->route(config('laraadmin.adminRoute') . '.employees.index');
			
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Display the specified employee.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		if(Module::hasAccess("Employees", "view")) {
			
			$employee = Employee::find($id);
			if(isset($employee->id)) {
				$module = Module::get('Employees');
				$module->row = $employee;
				
				// Get User Table Information
				$user = User::where('context_id', '=', $id)->firstOrFail();

				// Initialise the 2FA class
				$google2fa = app('pragmarx.google2fa');
				$googleCode = $google2fa->generateSecretKey();
				// Generate the QR image. This is the image the user will scan with their app
				// to set up two factor authentication
				$QR_Image = $google2fa->getQRCodeInline(
					LAConfigs::getByKey('sitename'),
					$employee->email,
					$googleCode
				);
				
				return view('la.employees.show', [
					'user' => $user,
					'module' => $module,
					'QR_Image' => $QR_Image,
					'google_code' => $googleCode,
					'view_col' => $this->view_col,
					'no_header' => true,
					'no_padding' => "no-padding"
				])->with('employee', $employee);
			} else {
				return view('errors.404', [
					'record_id' => $id,
					'record_name' => ucfirst("employee"),
				]);
			}
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Show the form for editing the specified employee.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		if(Module::hasAccess("Employees", "edit")) {
			
			$employee = Employee::find($id);
			if(isset($employee->id)) {
				$module = Module::get('Employees');
				
				$module->row = $employee;
				
				// Get User Table Information
				$user = User::where('context_id', '=', $id)->firstOrFail();
				
				return view('la.employees.edit', [
					'module' => $module,
					'view_col' => $this->view_col,
					'user' => $user,
				])->with('employee', $employee);
			} else {
				return view('errors.404', [
					'record_id' => $id,
					'record_name' => ucfirst("employee"),
				]);
			}
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Update the specified employee in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		if(Module::hasAccess("Employees", "edit")) {
			
			$rules = Module::validateRules("Employees", $request, true);
			
			$validator = Validator::make($request->all(), $rules);
			
			if ($validator->fails()) {
				return redirect()->back()->withErrors($validator)->withInput();;
			}
			
			$employee_id = Module::updateRow("Employees", $request, $id);
        	
			// Update User
			$user = User::where('context_id', $employee_id)->first();
			$user->name = $request->name;
			$user->save();
			
			// update user role
			$user->detachRoles();
			$role = Role::find($request->role);
			$user->attachRole($role);
			
			return redirect()->route(config('laraadmin.adminRoute') . '.employees.index');
			
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Remove the specified employee from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		if(Module::hasAccess("Employees", "delete")) {
			Employee::find($id)->delete();
			
			// Redirecting to index() method
			return redirect()->route(config('laraadmin.adminRoute') . '.employees.index');
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
		$values = DB::table('employees')->select($this->listing_cols)->whereNull('deleted_at');
		$out = Datatables::of($values)->make();
		$data = $out->getData();

		$fields_popup = ModuleFields::getModuleFields('Employees');
		
		for($i=0; $i < count($data->data); $i++) {
			for ($j=0; $j < count($this->listing_cols); $j++) { 
				$col = $this->listing_cols[$j];
				if($fields_popup[$col] != null && starts_with($fields_popup[$col]->popup_vals, "@")) {
					$data->data[$i][$j] = ModuleFields::getFieldValue($fields_popup[$col], $data->data[$i][$j]);
				}
				if($col == $this->view_col) {
					$data->data[$i][$j] = '<a href="'.url(config('laraadmin.adminRoute') . '/employees/'.$data->data[$i][0]).'">'.$data->data[$i][$j].'</a>';
				}
				// else if($col == "author") {
				//    $data->data[$i][$j];
				// }
			}
			
			if($this->show_action) {
				$output = '';
				if(Module::hasAccess("Employees", "edit")) {
					$output .= '<a href="'.url(config('laraadmin.adminRoute') . '/employees/'.$data->data[$i][0].'/edit').'" class="btn btn-warning btn-xs" style="display:inline;padding:2px 5px 3px 5px;"><i class="fa fa-edit"></i></a> ';

					if(parent::CanEmailSend()) {
						$output .= Form::open(['url' => url(config('laraadmin.adminRoute') . '/employees/reset/'.$data->data[$i][0]), 'method' => 'post', 'style'=>'display:inline']);
						$output .= '<button class="btn btn-info btn-xs" onclick="return confirm(\''.Message::getBykey($this->module, 'ConfirmReset').'\')" type="submit"><i class="fa fa-rotate-left"></i></button> ';
						$output .= Form::close();
					}

					if($data->data[$i][4] == 0){
						$output .= Form::open(['url' => url(config('laraadmin.adminRoute') . '/employees/lock/'.$data->data[$i][0]), 'method' => 'post', 'style'=>'display:inline']);
						$output .= '<button class="btn btn-primary mb1 bg-green btn-xs" onclick="return confirm(\''.Message::getBykey($this->module, 'ConfirmLock').'\')" type="submit"><i class="fa fa-lock"></i></button> ';
						$output .= Form::close();
					}
					else {
						$output .= Form::open(['url' => url(config('laraadmin.adminRoute') . '/employees/unlock/'.$data->data[$i][0]), 'method' => 'post', 'style'=>'display:inline']);
						$output .= '<button class="btn btn-primary mb1 bg-green btn-xs" onclick="return confirm(\''.Message::getBykey($this->module, 'ConfirmUnlock').'\')" type="submit"><i class="fa fa-unlock"></i></button>';
						$output .= Form::close();
					}
				}
				
				if(Module::hasAccess("Employees", "delete")) {
					$output .= Form::open(['route' => [config('laraadmin.adminRoute') . '.employees.destroy', $data->data[$i][0]], 'method' => 'delete', 'style'=>'display:inline']);
					$output .= ' <button class="btn btn-danger btn-xs" type="submit"><i class="fa fa-times"></i></button>';
					$output .= Form::close();
				}
				$data->data[$i][] = (string)$output;
			}
		}
		$out->setData($data);
		return $out;
	}

	/**
	 * Lock user account by setting status to 1
	 */
	public function lock($id) {
		$employee = Employee::find($id);
		$user = User::where("context_id", $employee->id)->where('type', 'Employee')->first();	
		$user->status = 1;
		$user->save();
		return redirect()->route(config('laraadmin.adminRoute') . '.employees.index');
	}

	/**
	 * Unlock user account by setting status to 0
	 */
	public function unlock($id) {
		$employee = Employee::find($id);
		$user = User::where("context_id", $employee->id)->where('type', 'Employee')->first();	
		$user->status = 0;
		$user->save();		
		return redirect()->route(config('laraadmin.adminRoute') . '.employees.index');
	}

	/**
	 * Reset user account
	 */
	public function reset($id) {
		$employee = Employee::find($id);
		$user = User::where("context_id", $employee->id)->where('type', 'Employee')->first();

		if($user) {			
			if(parent::CanEmailSend()) {
				$user->verifytoken = uniqid();
				$user->verifytokengenerateat = date('Y-m-d h:i:s');

				// Send mail to User his Password
				Mail::send('emails.send_login_cred', ['user' => $user], function ($m) use ($user) {
					//$m->from('hello@laraadmin.com', 'LaraAdmin');
					$m->to($user->email, $user->name)->subject(Setting::getByKey('NewUserMessage'));
				});

				$user->save();

				Log::info("User accoutn Reset: username: ".$user->email);
			}
		}
		return redirect()->route(config('laraadmin.adminRoute') . '.employees.index');
	}
	
	/**
     * Change Employee Password
     *
     * @return
     */
	public function change_password($id, Request $request) {
		
		$validator = Validator::make($request->all(), [
            'password' => 'required|min:6',
			'password_confirmation' => 'required|min:6|same:password'
        ]);
		
		if ($validator->fails()) {
			return \Redirect::to(config('laraadmin.adminRoute') . '/employees/'.$id)->withErrors($validator);
		}
		
		$employee = Employee::find($id);
		$user = User::where("context_id", $employee->id)->where('type', 'Employee')->first();
		$user->password = bcrypt($request->password);
		$user->save();
		
		\Session::flash('success_message', 'Password is successfully changed');
		
		// Send mail to User his new Password
		if(env('MAIL_USERNAME') != null && env('MAIL_USERNAME') != "null" && env('MAIL_USERNAME') != "") {
			// Send mail to User his new Password
			Mail::send('emails.send_login_cred_change', ['user' => $user, 'password' => $request->password], function ($m) use ($user) {
				$m->from(LAConfigs::getByKey('default_email'), LAConfigs::getByKey('sitename'));
				$m->to($user->email, $user->name)->subject('LaraAdmin - Login Credentials chnaged');
			});
		} else {
			Log::info("User change_password: username: ".$user->email." Password: ".$request->password);
		}
		
		return redirect(config('laraadmin.adminRoute') . '/employees/'.$id.'#tab-account-settings');
	}

	/**
     * Change Employee Google Authentication
     *
     * @return
     */
	public function change_GoogleAuth($id, Request $request)
	{
		$user = Auth::user();
		$user->google2fa_secret = $request->google_code;
		$user->save();
		
		return redirect(config('laraadmin.adminRoute') . '/employees/'.$id.'#tab-account-googleauth');
	}
}
