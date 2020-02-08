@extends("la.layouts.app")

@section("contentheader_title")
	<a href="{{ url(config('laraadmin.adminRoute') . '/settings') }}">Misc Test</a> :
@endsection
@section("contentheader_description", 'Email Test')
@section("section", "Email Test")
@section("section_url", url(config('laraadmin.adminRoute') . '/settings'))
@section("sub_section", "Test")

@section("htmlheader_title", "Email tester")

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
				{!! Form::model($setting, ['route' => [config('laraadmin.adminRoute') . '.settings.update', $setting->id ], 'method'=>'PUT', 'id' => 'setting-edit-form']) !!}
					@la_form($module)
					
					{{--
					@la_input($module, 'setting_key')
					@la_input($module, 'setting_value')
					@la_input($module, 'description')
					@la_input($module, 'field_type')
					@la_input($module, 'is_encrypted')
					--}}
                    <br>
					<div class="form-group">
						{!! Form::submit( 'Update', ['class'=>'btn btn-success']) !!} <button class="btn btn-default pull-right"><a href="{{ url(config('laraadmin.adminRoute') . '/settings') }}">Cancel</a></button>
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
	$("#setting-edit-form").validate({
		
	});
});
</script>
@endpush
