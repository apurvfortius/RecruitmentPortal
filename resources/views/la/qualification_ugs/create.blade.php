@extends("la.layouts.app")

@section("contentheader_title")
	<a href="{{ url(config('laraadmin.adminRoute') . '/qualification_ugs') }}">Qualification UG</a> :
@endsection
@section("contentheader_description", $qualification_ug->$view_col)
@section("section", "Qualification UGs")
@section("section_url", url(config('laraadmin.adminRoute') . '/qualification_ugs'))
@section("sub_section", "Edit")

@section("htmlheader_title", "Qualification UGs Edit : ".$qualification_ug->$view_col)

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
				{!! Form::open(['action' => 'LA\Qualification_UGsController@store', 'id' => 'qualification_ug-add-form']) !!}
					@la_form($module)
					
					{{--
					@la_input($module, 'title')
					--}}
                    <br>
					<div class="form-group">
						{!! Form::submit( 'Submit', ['class'=>'btn btn-success']) !!} <button class="btn btn-default pull-right"><a href="{{ url(config('laraadmin.adminRoute') . '/qualification_ugs') }}">Cancel</a></button>
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
	$("#qualification_ug-edit-form").validate({
		
	});
});
</script>
@endpush
