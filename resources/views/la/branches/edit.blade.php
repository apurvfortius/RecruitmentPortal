@extends("la.layouts.app")

@section("contentheader_title")
	<a href="{{ url(config('laraadmin.adminRoute') . '/branches') }}">Branch</a> :
@endsection
@section("contentheader_description", $branch->$view_col)
@section("section", "Branches")
@section("section_url", url(config('laraadmin.adminRoute') . '/branches'))
@section("sub_section", "Edit")

@section("htmlheader_title", "Branches Edit : ".$branch->$view_col)

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
				{!! Form::model($branch, ['route' => [config('laraadmin.adminRoute') . '.branches.update', $branch->id ], 'method'=>'PUT', 'id' => 'branch-edit-form']) !!}
					@la_form($module)
					
					{{--
					@la_input($module, 'company_id')
					@la_input($module, 'type')
					@la_input($module, 'country_id')
					@la_input($module, 'state_id')
					@la_input($module, 'city_id')
					@la_input($module, 'address')
					@la_input($module, 'contact_persopn')
					@la_input($module, 'mobile')
					@la_input($module, 'telephone')
					@la_input($module, 'email')
					--}}
                    <br>
					<div class="form-group">
						{!! Form::submit( 'Update', ['class'=>'btn btn-success']) !!} <button class="btn btn-default pull-right"><a href="{{ url(config('laraadmin.adminRoute') . '/branches') }}">Cancel</a></button>
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
	$("#branch-edit-form").validate({
		
	});
});
</script>
@endpush
