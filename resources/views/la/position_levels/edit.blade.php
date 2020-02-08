@extends("la.layouts.app")

@section("contentheader_title")
	<a href="{{ url(config('laraadmin.adminRoute') . '/position_levels') }}">Position Level</a> :
@endsection
@section("contentheader_description", $position_level->$view_col)
@section("section", "Position Levels")
@section("section_url", url(config('laraadmin.adminRoute') . '/position_levels'))
@section("sub_section", "Edit")

@section("htmlheader_title", "Position Levels Edit : ".$position_level->$view_col)

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
				{!! Form::model($position_level, ['route' => [config('laraadmin.adminRoute') . '.position_levels.update', $position_level->id ], 'method'=>'PUT', 'id' => 'position_level-edit-form']) !!}
					@la_form($module)
					
					{{--
					@la_input($module, 'title')
					--}}
                    <br>
					<div class="form-group">
						{!! Form::submit( 'Update', ['class'=>'btn btn-success']) !!} <button class="btn btn-default pull-right"><a href="{{ url(config('laraadmin.adminRoute') . '/position_levels') }}">Cancel</a></button>
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
	$("#position_level-edit-form").validate({
		
	});
});
</script>
@endpush
