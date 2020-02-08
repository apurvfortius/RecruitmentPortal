@extends("la.layouts.app")

@section("contentheader_title")
	<a href="{{ url(config('laraadmin.adminRoute') . '/countries') }}">Country</a> :
@endsection
@section("contentheader_description", $country->$view_col)
@section("section", "Countries")
@section("section_url", url(config('laraadmin.adminRoute') . '/countries'))
@section("sub_section", "Edit")

@section("htmlheader_title", "Countries Edit : ".$country->$view_col)

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
				{!! Form::model($country, ['route' => [config('laraadmin.adminRoute') . '.countries.update', $country->id ], 'method'=>'PUT', 'id' => 'country-edit-form']) !!}
					@la_form($module)
					
					{{--
					@la_input($module, 'countryID')
					@la_input($module, 'countryName')
					@la_input($module, 'localName')
					@la_input($module, 'webCode')
					@la_input($module, 'region')
					@la_input($module, 'continent')
					@la_input($module, 'latitude')
					@la_input($module, 'longitude')
					@la_input($module, 'surfaceArea')
					@la_input($module, 'population')
					--}}
                    <br>
					<div class="form-group">
						{!! Form::submit( 'Update', ['class'=>'btn btn-success']) !!} <button class="btn btn-default pull-right"><a href="{{ url(config('laraadmin.adminRoute') . '/countries') }}">Cancel</a></button>
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
	$("#country-edit-form").validate({
		
	});
});
</script>
@endpush
