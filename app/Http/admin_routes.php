<?php

/* ================== Homepage ================== */
Route::get('/', 'Auth\AuthController@showLoginForm');
Route::get('/home', 'HomeController@index');
Route::auth();
Route::get('/verify/{email}/token/{token}','Auth\AuthController@varify');
Route::post('/verify','Auth\AuthController@varifypost');
Route::post('/2fa', function () {
    return redirect(URL()->previous());
})->name('2fa')->middleware('2fa');

/* ================== Access Uploaded Files ================== */
Route::get('files/{hash}/{name}', 'LA\UploadsController@get_file');

/*
|--------------------------------------------------------------------------
| Admin Application Routes
|--------------------------------------------------------------------------
*/

$as = "";
if(\Dwij\Laraadmin\Helpers\LAHelper::laravel_ver() == 5.3) {
	$as = config('laraadmin.adminRoute').'.';
	
	// Routes for Laravel 5.3
	Route::get('/logout', 'Auth\LoginController@logout');
}

Route::group(['as' => $as, 'middleware' => ['auth', '2fa', 'permission:ADMIN_PANEL']], function () {
	
	/* ================== Dashboard ================== */
	
	Route::get(config('laraadmin.adminRoute'), 'LA\DashboardController@index');
	Route::get(config('laraadmin.adminRoute'). '/dashboard', 'LA\DashboardController@index');
	
	/* ================== Users ================== */
	Route::resource(config('laraadmin.adminRoute') . '/users', 'LA\UsersController');
	Route::get(config('laraadmin.adminRoute') . '/user_dt_ajax', 'LA\UsersController@dtajax');
	
	/* ================== Uploads ================== */
	Route::resource(config('laraadmin.adminRoute') . '/uploads', 'LA\UploadsController');
	Route::post(config('laraadmin.adminRoute') . '/upload_files', 'LA\UploadsController@upload_files');
	Route::get(config('laraadmin.adminRoute') . '/uploaded_files', 'LA\UploadsController@uploaded_files');
	Route::post(config('laraadmin.adminRoute') . '/uploads_update_caption', 'LA\UploadsController@update_caption');
	Route::post(config('laraadmin.adminRoute') . '/uploads_update_filename', 'LA\UploadsController@update_filename');
	Route::post(config('laraadmin.adminRoute') . '/uploads_update_public', 'LA\UploadsController@update_public');
	Route::post(config('laraadmin.adminRoute') . '/uploads_delete_file', 'LA\UploadsController@delete_file');
	
	/* ================== Roles ================== */
	Route::resource(config('laraadmin.adminRoute') . '/roles', 'LA\RolesController');
	Route::get(config('laraadmin.adminRoute') . '/role_dt_ajax', 'LA\RolesController@dtajax');
	Route::post(config('laraadmin.adminRoute') . '/save_module_role_permissions/{id}', 'LA\RolesController@save_module_role_permissions');
	
	/* ================== Permissions ================== */
	Route::resource(config('laraadmin.adminRoute') . '/permissions', 'LA\PermissionsController');
	Route::get(config('laraadmin.adminRoute') . '/permission_dt_ajax', 'LA\PermissionsController@dtajax');
	Route::post(config('laraadmin.adminRoute') . '/save_permissions/{id}', 'LA\PermissionsController@save_permissions');
	
	
	/* ================== Employees ================== */
	Route::resource(config('laraadmin.adminRoute') . '/employees', 'LA\EmployeesController');
	Route::get(config('laraadmin.adminRoute') . '/employee_dt_ajax', 'LA\EmployeesController@dtajax');
	Route::post(config('laraadmin.adminRoute') . '/change_password/{id}', 'LA\EmployeesController@change_password');
	Route::post(config('laraadmin.adminRoute') . '/change_googleauth/{id}', 'LA\EmployeesController@change_GoogleAuth');
	Route::post(config('laraadmin.adminRoute') . '/employees/reset/{id}', 'LA\EmployeesController@reset');
	Route::post(config('laraadmin.adminRoute') . '/employees/lock/{id}', 'LA\EmployeesController@lock');
	Route::post(config('laraadmin.adminRoute') . '/employees/unlock/{id}', 'LA\EmployeesController@unlock');
	

	/* ================== Backups ================== */
	Route::resource(config('laraadmin.adminRoute') . '/backups', 'LA\BackupsController');
	Route::get(config('laraadmin.adminRoute') . '/backup_dt_ajax', 'LA\BackupsController@dtajax');
	Route::post(config('laraadmin.adminRoute') . '/create_backup_ajax', 'LA\BackupsController@create_backup_ajax');
	Route::get(config('laraadmin.adminRoute') . '/downloadBackup/{id}', 'LA\BackupsController@downloadBackup');

	/* ================== Settings ================== */
	Route::resource(config('laraadmin.adminRoute') . '/settings', 'LA\SettingsController');
	Route::get(config('laraadmin.adminRoute') . '/setting_dt_ajax', 'LA\SettingsController@dtajax');



	/* ================== Messages ================== */
	Route::resource(config('laraadmin.adminRoute') . '/messages', 'LA\MessagesController');
	Route::get(config('laraadmin.adminRoute') . '/message_dt_ajax', 'LA\MessagesController@dtajax');

	/* ================== Email_Testers ================== */
	Route::resource(config('laraadmin.adminRoute') . '/email_testers', 'LA\Email_TestersController');
	Route::get(config('laraadmin.adminRoute') . '/email_tester_dt_ajax', 'LA\Email_TestersController@dtajax');



	//below controller defined by apurv to get ajax responses.
	Route::post(config('laraadmin.adminRoute') . '/getDepartments', 'LA\CustomController@getDepartments');
	Route::post(config('laraadmin.adminRoute') . '/getSubDepartments', 'LA\CustomController@getSubDepartments');
	Route::post(config('laraadmin.adminRoute') . '/getLocations', 'LA\CustomController@getLocations');
	Route::post(config('laraadmin.adminRoute') . '/getCountry', 'LA\CustomController@getCountry');
	Route::post(config('laraadmin.adminRoute') . '/getStates', 'LA\CustomController@getState');
	Route::post(config('laraadmin.adminRoute') . '/getCity', 'LA\CustomController@getCity');

	/* ================== Countries ================== */
	Route::resource(config('laraadmin.adminRoute') . '/countries', 'LA\CountriesController');
	Route::get(config('laraadmin.adminRoute') . '/country_dt_ajax', 'LA\CountriesController@dtajax');

	/* ================== States ================== */
	Route::resource(config('laraadmin.adminRoute') . '/states', 'LA\StatesController');
	Route::get(config('laraadmin.adminRoute') . '/state_dt_ajax', 'LA\StatesController@dtajax');

	/* ================== Cities ================== */
	Route::resource(config('laraadmin.adminRoute') . '/cities', 'LA\CitiesController');
	Route::get(config('laraadmin.adminRoute') . '/city_dt_ajax', 'LA\CitiesController@dtajax');

	/* ================== Industries ================== */
	Route::resource(config('laraadmin.adminRoute') . '/industries', 'LA\IndustriesController');
	Route::get(config('laraadmin.adminRoute') . '/industry_dt_ajax', 'LA\IndustriesController@dtajax');

	/* ================== Departments ================== */
	Route::resource(config('laraadmin.adminRoute') . '/departments', 'LA\DepartmentsController');
	Route::get(config('laraadmin.adminRoute') . '/department_dt_ajax', 'LA\DepartmentsController@dtajax');

	/* ================== Sub_Departments ================== */
	Route::resource(config('laraadmin.adminRoute') . '/sub_departments', 'LA\Sub_DepartmentsController');
	Route::get(config('laraadmin.adminRoute') . '/sub_department_dt_ajax', 'LA\Sub_DepartmentsController@dtajax');

	/* ================== Experiences ================== */
	Route::resource(config('laraadmin.adminRoute') . '/experiences', 'LA\ExperiencesController');
	Route::get(config('laraadmin.adminRoute') . '/experience_dt_ajax', 'LA\ExperiencesController@dtajax');

	/* ================== Qualification_UGs ================== */
	Route::resource(config('laraadmin.adminRoute') . '/qualification_ugs', 'LA\Qualification_UGsController');
	Route::get(config('laraadmin.adminRoute') . '/qualification_ug_dt_ajax', 'LA\Qualification_UGsController@dtajax');

	/* ================== Qualification_PGs ================== */
	Route::resource(config('laraadmin.adminRoute') . '/qualification_pgs', 'LA\Qualification_PGsController');
	Route::get(config('laraadmin.adminRoute') . '/qualification_pg_dt_ajax', 'LA\Qualification_PGsController@dtajax');

	/* ================== Position_Levels ================== */
	Route::resource(config('laraadmin.adminRoute') . '/position_levels', 'LA\Position_LevelsController');
	Route::get(config('laraadmin.adminRoute') . '/position_level_dt_ajax', 'LA\Position_LevelsController@dtajax');

	/* ================== Budgets ================== */
	Route::resource(config('laraadmin.adminRoute') . '/budgets', 'LA\BudgetsController');
	Route::get(config('laraadmin.adminRoute') . '/budget_dt_ajax', 'LA\BudgetsController@dtajax');

	/* ================== Companies ================== */
	Route::resource(config('laraadmin.adminRoute') . '/companies', 'LA\CompaniesController');
	Route::get(config('laraadmin.adminRoute') . '/company_dt_ajax', 'LA\CompaniesController@dtajax');
	Route::get(config('laraadmin.adminRoute') . '/companies/branch/{id}', 'LA\BranchesController@create');
	Route::post(config('laraadmin.adminRoute') . '/companies/branch/{id}', 'LA\BranchesController@store');

	/* ================== Branches ================== */
	Route::resource(config('laraadmin.adminRoute') . '/branches', 'LA\BranchesController');
	Route::get(config('laraadmin.adminRoute') . '/branch_dt_ajax', 'LA\BranchesController@dtajax');

	/* ================== Positions ================== */
	Route::resource(config('laraadmin.adminRoute') . '/positions', 'LA\PositionsController');
	Route::get(config('laraadmin.adminRoute') . '/position_dt_ajax', 'LA\PositionsController@dtajax');
	Route::post(config('laraadmin.adminRoute') . '/getWebsite', 'LA\CustomController@getWebsite');

	/* ================== Candidates ================== */
	Route::resource(config('laraadmin.adminRoute') . '/candidates', 'LA\CandidatesController');
	Route::get(config('laraadmin.adminRoute') . '/candidate_dt_ajax', 'LA\CandidatesController@dtajax');
	Route::post(config('laraadmin.adminRoute') . '/candidates/getCity', 'LA\CandidatesController@getCity');

	/* ================== Candidate_Experiences ================== */
	Route::resource(config('laraadmin.adminRoute') . '/candidate_experiences', 'LA\Candidate_ExperiencesController');
	Route::get(config('laraadmin.adminRoute') . '/candidate_experience_dt_ajax', 'LA\Candidate_ExperiencesController@dtajax');
	Route::get(config('laraadmin.adminRoute') . '/candidate_experiences/{id}/delete', 'LA\Candidate_ExperiencesController@delete');

	/* ================== Assign_Positions ================== */
	Route::get(config('laraadmin.adminRoute') . '/assign_candidate/{id}', 'LA\Assign_PositionsController@showAssignPage');
	//Route::resource(config('laraadmin.adminRoute') . '/assign_positions', 'LA\Assign_PositionsController');
	//Route::get(config('laraadmin.adminRoute') . '/assign_position_dt_ajax', 'LA\Assign_PositionsController@dtajax');
	Route::post(config('laraadmin.adminRoute') . '/getBasicSearch/{search}', 'LA\Assign_PositionsController@getBasicSearch');
	Route::post(config('laraadmin.adminRoute') . '/getAdvanceSearch/{search}', 'LA\Assign_PositionsController@getAdvanceSearch');
	Route::post(config('laraadmin.adminRoute') . '/assignCandidate', 'LA\Assign_PositionsController@assignCandidate');
	Route::post(config('laraadmin.adminRoute') . '/unassignCandidate', 'LA\Assign_PositionsController@unassignCandidate');

	Route::get(config('laraadmin.adminRoute') . '/assign_position/{id}', 'LA\Assign_PositionsController@showAssignPositionPage');
	Route::post(config('laraadmin.adminRoute') . '/assignPosition', 'LA\Assign_PositionsController@assignPosition');
});
