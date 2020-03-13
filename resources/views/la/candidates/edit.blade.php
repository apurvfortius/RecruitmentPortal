@extends("la.layouts.app")

@section("contentheader_title")
	<a href="{{ url(config('laraadmin.adminRoute') . '/candidates') }}">Candidate</a> :
@endsection
@section("contentheader_description", $candidate->$view_col)
@section("section", "Candidates")
@section("section_url", url(config('laraadmin.adminRoute') . '/candidates'))
@section("sub_section", "Edit")

@section("htmlheader_title", "Candidates Edit : ".$candidate->$view_col)

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
				{!! Form::model($candidate, ['route' => [config('laraadmin.adminRoute') . '.candidates.update', $candidate->id ], 'method'=>'PUT', 'id' => 'candidate-edit-form']) !!}
					{{-- @la_form($module) --}}
					
					@la_input($module, 'name')
					{{-- @la_input($module, 'city') --}}
					<div class="form-group">
						<label for="city">City* :</label>
						<input list="city_list" value="{{ $city->cityName }}" class="form-control" placeholder="Enter City" data-rule-minlength="1" data-rule-maxlength="255" required="1" name="city" type="text" value="" aria-required="true">
						<datalist id="city_list">
						</datalist>
						{{-- <input class="form-control" placeholder="Enter City" data-rule-minlength="1" data-rule-maxlength="255" required="1" name="city" type="text" value="" aria-required="true"> --}}
					</div>

					{{-- @la_input($module, 'native_place') --}}
					
					<div class="form-group">
						<label for="native_place">Native Place* :</label>
						<input class="form-control" value="{{ $candidate->native_place }}" placeholder="Enter Native Place" data-rule-minlength="1" data-rule-maxlength="255" required="1" name="native_place" type="text" value="" aria-required="true">
					</div>

					@la_input($module, 'crnnt_desgnation')
					@la_input($module, 'report_to')
					@la_input($module, 'total_experience')
					@la_input($module, 'qualification_ug')
					@la_input($module, 'qualification_pg')
					@la_input($module, 'crrnt_ctc')
					@la_input($module, 'expected_ctc')
					@la_input($module, 'notice_period')
					@la_input($module, 'notice_buy_out')
					<div class="form-group">
						<label for="enter_age">Enter Age* :</label>
						<br>
						<div class="radio">
							<label>
								<input name="enter_age" type="radio" value="Yes" checked> Yes 
							</label>
							<label>
								<input name="enter_age" type="radio" value="No"> No 
							</label>
						</div>
					</div>

					<div class="form-group" id="birthdiv" style="display:none;">
						<label for="birthdate">Birth Date* :</label>
						<div class="input-group date">
							<input class="form-control birthDate" onchange="getAgeFromDate(this.value)" placeholder="Select Birth Date" required="1" name="birthdate" type="text" value="" aria-required="true">
							<div class="input-group-addon" class="examDateCalendarIcon">
								<i class="fa fa-calendar"></i>
							</div>
						</div>
					</div>

					@la_input($module, 'age')
					@la_input($module, 'family_detail')
					@la_input($module, 'recruitor')
					@la_input($module, 'recruitor_note')
					
					
					
					@la_input($module, 'mobile_1')
					@la_input($module, 'mobile_2')
					@la_input($module, 'email_1')
					@la_input($module, 'email_2')
					@la_input($module, 'skype')
					@la_input($module, 'remark')
					@la_input($module, 'resume')
					{{-- @la_input($module, 'created_by')
					@la_input($module, 'last_edited_by') --}}


					<div class="row multi-field-wrapper" id="p_scents">
						<div class="multi-fields">
							@foreach($experience as $item)
								<div class="multi-field">
									<input type="hidden" value="{{ $item->id }}" name="experiece_id[]">
									<div class="col-md-6">
										<div class="form-group">
											<label for="company">Company* :</label>
										<input class="form-control" value="{{ $item->company }}" placeholder="Enter Company" data-rule-minlength="1" data-rule-maxlength="255" required="1" name="company[]" type="text" value="" aria-required="true">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="working_from">Working From* :</label>
											<div class="input-group">
												<input class="form-control examDate" value="{{ $item->working_from }}" placeholder="Enter Working From" required="1" name="working_from[]" type="text" value="" aria-required="true">
												<div class="input-group-addon" class="examDateCalendarIcon">
													<i class="fa fa-calendar"></i>
												</div>
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="working_to">Working To* :</label>
											<div class="input-group">
												<input class="form-control examDate" value="{{ $item->working_to }}" placeholder="Enter Working To" required="1" name="working_to[]" type="text" value="" aria-required="true">
												<div class="input-group-addon" class="examDateCalendarIcon">
													<i class="fa fa-calendar"></i>
												</div>
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="job_profile">Job Profile* :</label>
											<input class="form-control" value="{{ $item->job_profile }}" placeholder="Enter Job Profile" data-rule-minlength="1" data-rule-maxlength="255" required="1" name="job_profile[]" type="text" value="" aria-required="true">
										</div>
									</div>
									<button type="button" id="removefield" onclick="removediv(this, '{{ $item->id }}')">Remove</button>
								</div>
							@endforeach
						</div>	
						<a href="javascript:void(0)" class="add-field btn btn-info pull-right"><i class="fa fa-plus"></i></a>
						<div class="multi-field template" style="display: none;">
							<div class="col-md-6">
								<div class="form-group">
									<label for="company">Company* :</label>
									<input class="form-control" placeholder="Enter Company" data-rule-minlength="1" data-rule-maxlength="255" required="1" name="company[]" type="text" value="" aria-required="true">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="working_from">Working From* :</label>
									<div class="input-group date">
										<input class="form-control examDate" placeholder="Enter Working From" required="1" name="working_from[]" type="text" value="" aria-required="true">
										<div class="input-group-addon" class="examDateCalendarIcon">
											<i class="fa fa-calendar"></i>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="working_to">Working To* :</label>
									<div class="input-group date">
										<input class="form-control examDate" placeholder="Enter Working To" required="1" name="working_to[]" type="text" value="" aria-required="true">
										<div class="input-group-addon" class="examDateCalendarIcon">
											<i class="fa fa-calendar"></i>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="job_profile">Job Profile* :</label>
									<input class="form-control" placeholder="Enter Job Profile" data-rule-minlength="1" data-rule-maxlength="255" required="1" name="job_profile[]" type="text" value="" aria-required="true">
								</div>
							</div>
							<button type="button" id="removefield" onclick="removediv(this)">Remove</button>
						</div>				
					</div>	
                    <br>
					<div class="form-group">
						{!! Form::submit( 'Update', ['class'=>'btn btn-success']) !!} <button class="btn btn-default pull-right"><a href="{{ url(config('laraadmin.adminRoute') . '/candidates') }}">Cancel</a></button>
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
	$("#candidate-edit-form").validate({
		
	});
});
</script>
<script>
	$( "input[name='city']" ).change(function () {
		var str = $("input[name='city']").val();
		$.ajax({
			url : "{{ url(config('laraadmin.adminRoute') . '/candidates/getCity') }}",
			method:"post",
			data : {"_token": "{{ csrf_token() }}", str: str },
			success: function(data){
				$('#city_list').html(data);
			}
		});
	}).change();	
</script>

<script>
	$('.multi-field-wrapper').each(function() {
		var $wrapper = $('.multi-fields', this);
		$(".add-field").click(function(){
			var examDateClonedObj = $(".template").clone();
			$(examDateClonedObj).removeClass("template");
			$(examDateClonedObj).css("display", "");
			$(examDateClonedObj).appendTo(".multi-fields");
			$(examDateClonedObj).show();
		});
	});

	function removediv(obj, id = 0) {
		if(id !== 0){
			$.ajax({
				url : "{{ url(config('laraadmin.adminRoute') . '/candidate_experiences') }}/"+id+"/delete",
				method:"get",
				data : {"_token": "{{ csrf_token() }}" },
				success: function(data){
					$(obj).parent('.multi-field').remove();
				}
			});
		}
		else{
			$(obj).parent('.multi-field').remove();
		}	
		$(obj).parent('.multi-field').remove();	
	}

	$('body').on('focus',".examDate", function(){
		$(this).datetimepicker({
			format: 'YYYY-MM-DD',
			ignoreReadonly: true,
			showTodayButton: true
		});
	});

	$('body').on('focus',".birthDate", function(){
		$(this).datetimepicker({
			format: 'YYYY-MM-DD',
			ignoreReadonly: true,
			showTodayButton: true
		});
	});

	$(document).ready(function(){
        $("input[name='enter_age']").click(function(){
            var radioValue = $("input[name='enter_age']:checked").val();
			if(radioValue == 'Yes'){
                $('input[name="age"]').attr('disabled', false);
				$('#birthdiv').css('display', 'none');
				$('input[name="birthdate"]').css('display', 'none');
				$('input[name="birthdate"]').attr('disabled', true);
            }
			else if(radioValue == 'No'){
				$('#birthdiv').css('display', '');
				$('input[name="birthdate"]').css('display', '');
				$('input[name="birthdate"]').attr('disabled', false);
                $('input[name="age"]').attr('disabled', true);
            }
        });
    });

	$(document).ready(function(){
        $("input[name='birthdate']").blur(function(){
            var dob = $(this).val();
			dob = new Date(dob);
			var today = new Date();
			var age = Math.floor((today-dob) / (365.25 * 24 * 60 * 60 * 1000));
			$('input[name="age"]').val(age);
        });
    });
</script>
@endpush
