@extends("la.layouts.app")

@section("contentheader_title")
	<a href="{{ url(config('laraadmin.adminRoute') . '/employees') }}">Employees</a> :
@endsection
@section("contentheader_description", $employee->$view_col)
@section("section", "Employees")
@section("section_url", url(config('laraadmin.adminRoute') . '/employees'))
@section("sub_section", "Create")

@section("htmlheader_title", "Employee Create : ".$employee->$view_col)

@section("main-content")

@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="box">
	<div class="box-header">
		
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				{!! Form::open(['action' => 'LA\EmployeesController@store', 'id' => 'employee-add-form']) !!}
					{{-- @la_form($module) --}}
						
					{{-- @la_input($module, 'employee_code') --}}
					@la_input($module, 'name')
					@la_input($module, 'gender')
					@la_input($module, 'mobile')
					@la_input($module, 'email')
					@la_input($module, 'date_hire')
					@la_input($module, 'date_left')
					@la_input($module, 'country_id')
					
					<div class="form-group">
						<label for="state_id">State* :</label>
						<select class="form-control" required="1" data-placeholder="Select State" rel="select2" name="state_id" id="state_id">
							<option value="">Select State</option>
						</select>
					</div>

					<div class="form-group">
						<label for="city">City* :</label>
						<select class="form-control" required="1" data-placeholder="Select City" rel="select2" name="city" id="city">
							<option value="">Select City</option>
						</select>
					</div>

					{{-- @la_input($module, 'state_id')
					@la_input($module, 'city') --}}
					@la_input($module, 'crnt_address')
					
					
                    <div class="form-group">
						<label for="role">Role* :</label>
						<select class="form-control" required="1" data-placeholder="Select Role" rel="select2" name="role">
							<?php $roles = App\Role::all(); ?>
							@foreach($roles as $role)
								@if($role->id != 1 || Entrust::hasRole("SUPER_ADMIN"))
									<option value="{{ $role->id }}">{{ $role->name }}</option>
								@endif
							@endforeach
						</select>
					</div>
					<br>
					<div class="form-group">
						{!! Form::submit( 'Submit', ['class'=>'btn btn-success']) !!} <button class="btn btn-default pull-right"><a href="{{ url(config('laraadmin.adminRoute') . '/employees') }}">Cancel</a></button>
					</div>
				{!! Form::close() !!}				
			</div>
		</div>
	</div>
</div>

@endsection

@push('scripts')
<script>
$(function () {
	$("#employee-add-form").validate({
		
	});
});
</script>
<script>
	$( "select[name='country_id']" ).change(function () {
		var str = "";
		$( "select[name='country_id'] option:selected" ).each(function() {
		  str += $( this ).val() + " ";
		});

		$.ajax({
			url : "{{ url(config('laraadmin.adminRoute') . '/getStates') }}",
			method:"post",
			data : {"_token": "{{ csrf_token() }}", id: str },
			success: function(data){
				$('#state_id').html(data);
			}
		});
	}).change();
</script>

<script>
	$( "select[name='state_id']" ).change(function () {
		var str = "";
		$( "select[name='state_id'] option:selected" ).each(function() {
		  str += $( this ).val() + " ";
		});

		$.ajax({
			url : "{{ url(config('laraadmin.adminRoute') . '/getCity') }}",
			method:"post",
			data : {"_token": "{{ csrf_token() }}", id: str },
			success: function(data){
				$('#city').html(data);
			}
		});
	}).change();
</script>
@endpush