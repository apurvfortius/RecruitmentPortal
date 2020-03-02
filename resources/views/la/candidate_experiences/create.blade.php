@extends("la.layouts.app")

@section("contentheader_title")
	<a href="{{ url(config('laraadmin.adminRoute') . '/candidate_experiences') }}">Candidate Experience</a> :
@endsection
@section("contentheader_description", $candidate_experience->$view_col)
@section("section", "Candidate Experiences")
@section("section_url", url(config('laraadmin.adminRoute') . '/candidate_experiences'))
@section("sub_section", "Create")

@section("htmlheader_title", "Candidate Experiences Create : ".$candidate_experience->$view_col)

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
				{!! Form::open(['action' => 'LA\Candidate_ExperiencesController@store', 'id' => 'candidate_experience-add-form']) !!}
					@la_form($module)
					
					{{--
					@la_input($module, 'candidate_id')
					@la_input($module, 'company')
					@la_input($module, 'working_from')
					@la_input($module, 'working_to')
					@la_input($module, 'job_profile')
					--}}
                    <br>
					<div class="form-group">
						{!! Form::submit( 'Submit', ['class'=>'btn btn-success']) !!} <button class="btn btn-default pull-right"><a href="{{ url(config('laraadmin.adminRoute') . '/candidate_experiences') }}">Cancel</a></button>
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
	$("#candidate_experience-add-form").validate({
		
	});
});
</script>
@endpush
