@extends("la.layouts.app")

@section("contentheader_title")
	<a href="{{ url(config('laraadmin.adminRoute') . '/positions') }}">Position</a> :
@endsection
@section("contentheader_description", $position->$view_col)
@section("section", "Positions")
@section("section_url", url(config('laraadmin.adminRoute') . '/positions'))
@section("sub_section", "Create")

@section("htmlheader_title", "Positions Create : ".$position->$view_col)

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
				{!! Form::open(['action' => 'LA\PositionsController@store', 'id' => 'position-add-form']) !!}
					{{-- @la_form($module) --}}
					
					{{-- Position Code will be created while inserting --}}
					{{-- @la_input($module, 'position_code') --}}
					
					@la_input($module, 'company_id')
					@la_input($module, 'title')
					@la_input($module, 'position_level')
					@la_input($module, 'industry_id')
					
					<div class="form-group">
						<label for="state_id">Department* :</label>
						<select class="form-control" required="1" data-placeholder="Select Department" rel="select2" name="department_id" id="department_id">
							<option value="">Select Department</option>
						</select>
					</div>

					<div class="form-group">
						<label for="state_id">Sub Department* :</label>
						<select class="form-control" required="1" data-placeholder="Select Sub Department" rel="select2" name="sub_department_id" id="sub_department_id">
							<option value="">Select Sub Department</option>
						</select>
					</div>
					{{-- @la_input($module, 'department_id')
					@la_input($module, 'sub_department_id') --}}
					@la_input($module, 'report_to')
					@la_input($module, 'team_size')
					{{-- @la_input($module, 'location') --}}
					<div class="form-group">
						<label for="state_id">Location* :</label>
						<select class="form-control" required="1" data-placeholder="Select Location" rel="select2" name="location" id="location">
							<option value="">Select Location</option>
						</select>
					</div>
					@la_input($module, 'budget_id')
					@la_input($module, 'qualification_ug')
					@la_input($module, 'qualification_pg')
					@la_input($module, 'no_position')
					@la_input($module, 'req_exp_id')
					@la_input($module, 'urgency_pos')
					@la_input($module, 'buy_out')
					@la_input($module, 'com_turnover')
					@la_input($module, 'emp_strength')
					@la_input($module, 'jd_available')
					@la_input($module, 'website')
					@la_input($module, 'pos_date')
					@la_input($module, 'job_description')
					@la_input($module, 'pos_given_by')
					@la_input($module, 'pos_assign_to')
					{{-- @la_input($module, 'created_by')
					@la_input($module, 'last_edited_by') --}}
					<input type="hidden" name="created_by" value="{{ auth()->user()->id}}">
                    <br>
					<div class="form-group">
						{!! Form::submit( 'Submit', ['class'=>'btn btn-success']) !!}
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
			$("#position-add-form").validate({
				
			});
		});
	</script>

	<script>
		$( "select[name='industry_id']" ).change(function () {
			var str = "";
			$( "select[name='industry_id'] option:selected" ).each(function() {
				str += $( this ).val() + " ";
			});

			$.ajax({
				url : "{{ url(config('laraadmin.adminRoute') . '/getDepartments') }}",
				method:"post",
				data : {"_token": "{{ csrf_token() }}", id: str },
				success: function(data){
					$('#department_id').html(data);
				}
			});
		}).change();	
	</script>

	<script>
		$( "select[name='department_id']" ).change(function () {
			var str = "";
			$( "select[name='department_id'] option:selected" ).each(function() {
			str += $( this ).val() + " ";
			});

			$.ajax({
				url : "{{ url(config('laraadmin.adminRoute') . '/getSubDepartments') }}",
				method:"post",
				data : {"_token": "{{ csrf_token() }}", id: str },
				success: function(data){
					$('#sub_department_id').html(data);
				}
			});
		}).change();
	</script>

<script>
	$( "select[name='company_id']" ).change(function () {
		var str = "";
		$( "select[name='company_id'] option:selected" ).each(function() {
			str += $( this ).val() + " ";
		});

		$.ajax({
			url : "{{ url(config('laraadmin.adminRoute') . '/getLocations') }}",
			method:"post",
			data : {"_token": "{{ csrf_token() }}", id: str },
			success: function(data){
				$('#location').html(data);
			}
		});
	}).change();
</script>
@endpush
