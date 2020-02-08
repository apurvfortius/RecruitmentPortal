<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Setting;
use App\Role;
use Validator;
use Eloquent;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/admin';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware($this->guestMiddleware(), ['except' => 'logout']);
    }
    
    public function showRegistrationForm()
    {
        $roleCount = Role::count();
		if($roleCount != 0) {
			$userCount = User::count();
			if($userCount == 0) {
				return view('auth.register');
			} else {
				return redirect('login');
			}
		} else {
			return view('errors.error', [
				'title' => 'Migration not completed',
				'message' => 'Please run command <code>php artisan db:seed</code> to generate required table data.',
			]);
		}
    }
    
    public function showLoginForm()
    {
		$roleCount = Role::count();
		if($roleCount != 0) {
			$userCount = User::count();
			if($userCount == 0) {
				return redirect('register');
			} else {
				return view('auth.login');
			}
		} else {
			return view('errors.error', [
				'title' => 'Migration not completed',
				'message' => 'Please run command <code>php artisan db:seed</code> to generate required table data.',
			]);
		}
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        // TODO: This is Not Standard. Need to find alternative
        Eloquent::unguard();
        
        $employee = Employee::create([
            'name' => $data['name'],
            'designation' => "Super Admin",
            'mobile' => "8888888888",
            'mobile2' => "",
            'email' => $data['email'],
            'gender' => 'Male',
            'city' => "Pune",
            'address' => "Karve nagar, Pune 411030",
            'about' => "About user / biography",
            'date_birth' => date("Y-m-d"),
            'date_hire' => date("Y-m-d"),
            'date_left' => date("Y-m-d"),
            'salary_cur' => 0,
        ]);
        
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'context_id' => $employee->id,
            'type' => "Employee",
        ]);
        $role = Role::where('name', 'SUPER_ADMIN')->first();
        $user->attachRole($role);
    
        return $user;
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    public function varify($email, $token)
    {
        if($this->validateverify($email, $token)) {
            return view('auth.verify', ["email" => $email, "token" => $token]);
        }
    }

    /**
     * POST: Update User password with all validation rule.
     */
    public function varifypost(Request $request)
    {
        // Checking Allow to update password
        $email = $request->email;
        $token = $request->token;
        $user = $this->validateverify($email, $token);

        // Checking form data
        $validate = $this->verifyvalidator($request->all());
        if($validate->fails()){
            return redirect()->back()->withErrors($validate)->withInput();
        }
        if($user) {
            // Update user data
            $user->verifytoken = null;
            $user->verifytokengenerateat = null;
            $user->password = bcrypt($request->new_password);
            $user->save();
            return redirect('/login');
        }
    }

    /**
     * Get a validator for an incoming reset password request for Verify Account.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function verifyvalidator(array $data)
    {
        return Validator::make($data, [
            'new_password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Rule for verify or update password request
     * @param string $email
     * @param string $token
     * return bool if validation performed
     */
    protected function validateverify($email, $token)
    {
        if(!isset($email) || empty($email) || !isset($token) || empty($token)) {
            abort(404);
        }

        $user = User::where('email', $email)->first();
        if(!$user) {
            abort(404);
        }

        if($token != $user->verifytoken){
            abort(404);
        }

        $linkExpire = Setting::getbykey('LinkExpireIn');
        if(isset($linkExpire) && !empty($linkExpire)) {
            // And Token Expire Date Time? From DB
            $dateTimeNow = new \DateTime();
            $tokenDateTimeTemp = $user->verifytokengenerateat;
            $tokenDateTime = new \DateTime($tokenDateTimeTemp);
        
            // Capturing difference between current time and LInk generation time.
            $interval = abs($dateTimeNow->getTimestamp() - $tokenDateTime->getTimestamp()) / 60;
            
            //Checking Condition for Link Expiration
            // NOTE: If code is unable to find settign from DB for LinkExpireIn 
            // then, the link expiration condition will be skipped
            if($interval >= (int)Setting::getbykey('LinkExpireIn')) {
                abort(404);
            }
        }

        return $user;
    }
}
