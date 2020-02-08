@extends("la.layouts.app")

@section("contentheader_title")
	<a href="{{ url(config('laraadmin.adminRoute') . '/email_testers') }}">Email Tester</a> :
@endsection
@section("contentheader_description", $email_tester->$view_col)
@section("section", "Email Testers")
@section("section_url", url(config('laraadmin.adminRoute') . '/email_testers'))
@section("sub_section", "Edit")

@section("htmlheader_title", "Email Testers Edit : ".$email_tester->$view_col)

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
				{!! Form::model($email_tester, ['route' => [config('laraadmin.adminRoute') . '.email_testers.update', $email_tester->id ], 'method'=>'PUT', 'id' => 'email_tester-edit-form']) !!}
					@la_form($module)
					
					{{--
					@la_input($module, 'to')
					--}}
                    <br>
					<div class="form-group">
						{!! Form::submit( 'Update', ['class'=>'btn btn-success']) !!} <button class="btn btn-default pull-right"><a href="{{ url(config('laraadmin.adminRoute') . '/email_testers') }}">Cancel</a></button>
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
	$("#email_tester-edit-form").validate({
		
	});
});
</script>
@endpush
