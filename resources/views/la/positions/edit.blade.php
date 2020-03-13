@extends("la.layouts.app")

@section("contentheader_title")
	<a href="{{ url(config('laraadmin.adminRoute') . '/positions') }}">Position</a> :
@endsection
@section("contentheader_description", $position->$view_col)
@section("section", "Positions")
@section("section_url", url(config('laraadmin.adminRoute') . '/positions'))
@section("sub_section", "Edit")

@section("htmlheader_title", "Positions Edit : ".$position->$view_col)

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
				{!! Form::model($position, ['route' => [config('laraadmin.adminRoute') . '.positions.update', $position->id ], 'method'=>'PUT', 'id' => 'position-edit-form']) !!}
					{{-- @la_form($module) --}}
					
					<div class="form-group">
						<label for="position_code">Position Code* :</label>
						<input class="form-control valid" readonly disabled type="text" name="position_code" value="{{ $position->position_code }}">
					</div>

					{{-- @la_input($module, 'position_code') --}}
					@la_input($module, 'company_id')
					@la_input($module, 'title')
					@la_input($module, 'position_level')
					@la_input($module, 'industry_id')
					@la_input($module, 'department_id')
					@la_input($module, 'sub_department_id')
					@la_input($module, 'report_to')
					@la_input($module, 'team_size')
					@la_input($module, 'location')
					@la_input($module, 'budget_id')
					@la_input($module, 'qualification_ug')
					@la_input($module, 'qualification_pg')
					@la_input($module, 'no_position')
					@la_input($module, 'req_exp_id')
					@la_input($module, 'urgency_pos')
					@la_input($module, 'buy_out')
					@la_input($module, 'com_turnover')
					@la_input($module, 'emp_strength')
					
					@la_input($module, 'website')
					@la_input($module, 'pos_date')

					@la_input($module, 'jd_available')
					<div class="form-group" id="jd_div" @if($position->jd_available) style="display:none;" @endif>
						<label for="job_description">Job Description :</label>
						<textarea class="form-control" placeholder="Enter Job Description" cols="30" rows="3" id="job_description" name="job_description" @if($position->jd_available) disabled @endif></textarea>
					</div>
					{{-- @la_input($module, 'job_description') --}}
					@la_input($module, 'pos_given_by')
					@la_input($module, 'pos_assign_to')
					@la_input($module, 'created_by')
					@la_input($module, 'last_edited_by')
					
                    <br>
					<div class="form-group">
						{!! Form::submit( 'Update', ['class'=>'btn btn-success']) !!} <button class="btn btn-default pull-right"><a href="{{ url(config('laraadmin.adminRoute') . '/positions') }}">Cancel</a></button>
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
		$("#position-edit-form").validate({
			
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

			$.ajax({
				url : "{{ url(config('laraadmin.adminRoute') . '/getWebsite') }}",
				method:"post",
				data : {"_token": "{{ csrf_token() }}", id: str },
				success: function(data){
					$('input[name="website"]').val(data);
				}
			});
		}).change();
	</script>

	<script>
		$( "input[name='jd_available']" ).click(function () {
			var radioValue = $("input[name='jd_available']:checked").val();
			if(radioValue == 'Yes'){
				$('#jd_div').css('display', '');
				$('#job_description').prop('disabled', false);
			}
			else{
				$('#jd_div').css('display', 'none');
				$('#job_description').prop('disabled', true);
			}
		}).change();
	</script>
@endpush
