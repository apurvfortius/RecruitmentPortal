@extends("la.layouts.app")

@section("contentheader_title")
	<a href="{{ url(config('laraadmin.adminRoute') . '/assign_positions') }}">Assign Position</a> :
@endsection
@section("contentheader_description", $assign_position->$view_col)
@section("section", "Assign Positions")
@section("section_url", url(config('laraadmin.adminRoute') . '/assign_positions'))
@section("sub_section", "Edit")

@section("htmlheader_title", "Assign Positions Edit : ".$assign_position->$view_col)

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
				{!! Form::model($assign_position, ['route' => [config('laraadmin.adminRoute') . '.assign_positions.update', $assign_position->id ], 'method'=>'PUT', 'id' => 'assign_position-edit-form']) !!}
					@la_form($module)
					
					{{--
					@la_input($module, 'position_id')
					@la_input($module, 'candidate_id')
					@la_input($module, 'assigned_by')
					--}}
                    <br>
					<div class="form-group">
						{!! Form::submit( 'Update', ['class'=>'btn btn-success']) !!} <button class="btn btn-default pull-right"><a href="{{ url(config('laraadmin.adminRoute') . '/assign_positions') }}">Cancel</a></button>
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
	$("#assign_position-edit-form").validate({
		
	});
});
</script>
@endpush
