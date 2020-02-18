@extends("la.layouts.app")

@section("contentheader_title")
	<a href="{{ url(config('laraadmin.adminRoute') . '/companies') }}">Company</a> :
@endsection
@section("contentheader_description", $company->$view_col)
@section("section", "Companies")
@section("section_url", url(config('laraadmin.adminRoute') . '/companies'))
@section("sub_section", "Create")

@section("htmlheader_title", "Companies Create : ".$company->$view_col)

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
				{!! Form::open(['action' => 'LA\CompaniesController@store', 'id' => 'company-add-form']) !!}
                @la_form($module)
                
                <div class="form-group">
                    {!! Form::submit( 'Submit', ['class'=>'btn btn-success']) !!}
                </div>                                                   
			</div>
		</div>
	</div>
</div>

@endsection

@push('scripts')
<script>
$(function () {
	$("#company-add-form").validate({
		
	});
});
</script>
@endpush
