@extends("la.layouts.app")

@section("contentheader_title")
	<a href="{{ url(config('laraadmin.adminRoute') . '/sub_departments') }}">Sub Department</a> :
@endsection
@section("contentheader_description", $sub_department->$view_col)
@section("section", "Sub Departments")
@section("section_url", url(config('laraadmin.adminRoute') . '/sub_departments'))
@section("sub_section", "Edit")

@section("htmlheader_title", "Sub Departments Edit : ".$sub_department->$view_col)

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
				{!! Form::model($sub_department, ['route' => [config('laraadmin.adminRoute') . '.sub_departments.update', $sub_department->id ], 'method'=>'PUT', 'id' => 'sub_department-edit-form']) !!}
					@la_form($module)
					
					{{--
					@la_input($module, 'department_id')
					@la_input($module, 'title')
					--}}
                    <br>
					<div class="form-group">
						{!! Form::submit( 'Update', ['class'=>'btn btn-success']) !!} <button class="btn btn-default pull-right"><a href="{{ url(config('laraadmin.adminRoute') . '/sub_departments') }}">Cancel</a></button>
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
	$("#sub_department-edit-form").validate({
		
	});
});
</script>
@endpush
