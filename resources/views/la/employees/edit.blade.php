@extends("la.layouts.app")

@section("contentheader_title")
	<a href="{{ url(config('laraadmin.adminRoute') . '/employees') }}">Employee</a> :
@endsection
@section("contentheader_description", $employee->$view_col)
@section("section", "Employees")
@section("section_url", url(config('laraadmin.adminRoute') . '/employees'))
@section("sub_section", "Edit")

@section("htmlheader_title", "Employees Edit : ".$employee->$view_col)

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
				{!! Form::model($employee, ['route' => [config('laraadmin.adminRoute') . '.employees.update', $employee->id ], 'method'=>'PUT', 'id' => 'employee-edit-form']) !!}
					{{-- @la_form($module) --}}
					
					<div class="form-group">
						<label for="employee_code">Employee Code* :</label>
						<input class="form-control valid" readonly disabled type="text" name="employee_code" value="{{ $employee->employee_code }}">
					</div>
					{{-- @la_input($module, 'employee_code') --}}
					@la_input($module, 'name')
					@la_input($module, 'gender')
					@la_input($module, 'mobile')
					@la_input($module, 'email')					
					@la_input($module, 'date_hire')
					@la_input($module, 'date_left')					
					@la_input($module, 'country_id')
					{{-- @la_input($module, 'country_id')
					@la_input($module, 'state_id')
					@la_input($module, 'city') --}}

					<div class="form-group">
						<label for="country_id">Country* :</label>
						<input list="country_list" value="{{ $data['country'] }}" class="form-control" placeholder="Enter Country" data-rule-minlength="1" data-rule-maxlength="255" required="1" name="country_id" type="text" value="" aria-required="true">
						<datalist id="country_list">
						</datalist>
					</div>

					<div class="form-group">
						<label for="state_id">State* :</label>
						<input list="state_list" value="{{ $data['state'] }}" class="form-control" placeholder="Enter State" data-rule-minlength="1" data-rule-maxlength="255" required="1" name="state_id" type="text" value="" aria-required="true">
						<datalist id="state_list">
						</datalist>
					</div>

					<div class="form-group">
						<label for="city">City* :</label>
						<input list="city_list" value="{{ $data['city'] }}" class="form-control" placeholder="Enter State" data-rule-minlength="1" data-rule-maxlength="255" required="1" name="city" type="text" value="" aria-required="true">
						<datalist id="city_list">
						</datalist>
					</div>

					@la_input($module, 'crnt_address')
					
					<div class="form-group">
						<label for="role">Role* :</label>
						<select class="form-control" required="1" data-placeholder="Select Role" rel="select2" name="role">
							<?php $roles = App\Role::all(); ?>
							@foreach($roles as $role)
								@if($role->id != 1 || Entrust::hasRole("SUPER_ADMIN"))
									@if($user->hasRole($role->name))
										<option value="{{ $role->id }}" selected>{{ $role->name }}</option>
									@else
										<option value="{{ $role->id }}">{{ $role->name }}</option>
									@endif
								@endif
							@endforeach
						</select>
					</div>
                    <br>
					<div class="form-group">
						{!! Form::submit( 'Update', ['class'=>'btn btn-success']) !!} <button class="btn btn-default pull-right"><a href="{{ url(config('laraadmin.adminRoute') . '/employees') }}">Cancel</a></button>
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
	$("#employee-edit-form").validate({
		
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
			data : {"_token": "{{ csrf_token() }}", id: str, "selected": "{{ $employee->state_id }}" },
			success: function(data){
				$('#state_id').html(data);
			}
		});
	}).change();
</script>

<script>
	$( "select[name='state_id']" ).change(function () {
		var str = $( "select[name='state_id'] option:selected" ).val();
		if(str == ''){
			str = "{{ $employee->state_id }}";
		}

		$.ajax({
			url : "{{ url(config('laraadmin.adminRoute') . '/getCity') }}",
			method:"post",
			data : {"_token": "{{ csrf_token() }}", id: str, "selected": "{{ $employee->city }}" },
			success: function(data){
				$('#city').html(data);
				//getCity();
			}
		});
	}).change();
</script>
@endpush
