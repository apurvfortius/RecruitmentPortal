@extends("la.layouts.app")

@section("contentheader_title")
	<a href="{{ url(config('laraadmin.adminRoute') . '/qualification_pgs') }}">Qualification PG</a> :
@endsection
@section("contentheader_description", $qualification_pg->$view_col)
@section("section", "Qualification PGs")
@section("section_url", url(config('laraadmin.adminRoute') . '/qualification_pgs'))
@section("sub_section", "Edit")

@section("htmlheader_title", "Qualification PGs Edit : ".$qualification_pg->$view_col)

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
				{!! Form::model($qualification_pg, ['route' => [config('laraadmin.adminRoute') . '.qualification_pgs.update', $qualification_pg->id ], 'method'=>'PUT', 'id' => 'qualification_pg-edit-form']) !!}
					@la_form($module)
					
					{{--
					@la_input($module, 'title')
					--}}
                    <br>
					<div class="form-group">
						{!! Form::submit( 'Update', ['class'=>'btn btn-success']) !!} <button class="btn btn-default pull-right"><a href="{{ url(config('laraadmin.adminRoute') . '/qualification_pgs') }}">Cancel</a></button>
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
	$("#qualification_pg-edit-form").validate({
		
	});
});
</script>
@endpush
