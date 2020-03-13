@extends("la.layouts.app")

@section("contentheader_title")
	<a href="{{ url(config('laraadmin.adminRoute') . '/branches') }}">Branch</a> :
@endsection
@section("contentheader_description", $company->title)
@section("section", "Branches")
@section("section_url", url(config('laraadmin.adminRoute') . '/branches'))
@section("sub_section", "Create")

@section("htmlheader_title", "Branches Create : "."Create New")

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
				{!! Form::open(['method'=>'POST', 'action' => 'LA\BranchesController@store', 'id' => 'branch-add-form']) !!}
					{{-- @la_form($module) --}}
					<input type="hidden" value="{{ $id }}" name="company_id">
					
					{{-- @la_input($module, 'company_id') --}}
					@la_input($module, 'type')
					{{-- @la_input($module, 'country_id')
					@la_input($module, 'state_id')
					@la_input($module, 'city_id') --}}

                    <div class="form-group">
						<label for="country_id">Country* :</label>
						<input list="country_list" class="form-control" placeholder="Enter Country" data-rule-minlength="1" data-rule-maxlength="255" required="1" name="country_id" type="text" value="" aria-required="true">
						<datalist id="country_list">
						</datalist>
					</div>

					<div class="form-group">
						<label for="state_id">State* :</label>
						<input list="state_list" class="form-control" placeholder="Enter State" data-rule-minlength="1" data-rule-maxlength="255" required="1" name="state_id" type="text" value="" aria-required="true">
						<datalist id="state_list">
						</datalist>
					</div>

					<div class="form-group">
						<label for="city_id">City* :</label>
						<input list="city_list" class="form-control" placeholder="Enter State" data-rule-minlength="1" data-rule-maxlength="255" required="1" name="city_id" type="text" value="" aria-required="true">
						<datalist id="city_list">
						</datalist>
					</div>
					
					@la_input($module, 'address')
					@la_input($module, 'contact_persopn')
					@la_input($module, 'mobile')
					@la_input($module, 'telephone')
					@la_input($module, 'email')
					
                    <br>
					<div class="form-group">
				        {!! Form::submit( 'Submit', ['class'=>'btn btn-success']) !!}
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
	$("#branch-add-form").validate({
		
	});
});
</script>
<script>
	$( "input[name='country_id']" ).keyup(function () {
		var str = $( "input[name='country_id']").val();

		$.ajax({
			url : "{{ url(config('laraadmin.adminRoute') . '/getCountry') }}",
			method:"post",
			data : {"_token": "{{ csrf_token() }}", id: str },
			success: function(data){
				$('#country_list').html(data);
			}
		});
	}).change();
</script>

<script>
	$( "input[name='country_id']" ).change(function () {
		var str = $( "input[name='country_id']" ).val();

		$.ajax({
			url : "{{ url(config('laraadmin.adminRoute') . '/getStates') }}",
			method:"post",
			data : {"_token": "{{ csrf_token() }}", id: str },
			success: function(data){
				$('#state_list').html(data);
			}
		});
	}).change();
</script>

<script>
	$( "input[name='state_id']" ).change(function () {
		var str = $( "input[name='state_id']" ).val();

		$.ajax({
			url : "{{ url(config('laraadmin.adminRoute') . '/getCity') }}",
			method:"post",
			data : {"_token": "{{ csrf_token() }}", id: str},
			success: function(data){
				$('#city_list').html(data);
			}
		});
	}).change();
</script>
@endpush
