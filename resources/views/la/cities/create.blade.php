@extends("la.layouts.app")

@section("contentheader_title")
	<a href="{{ url(config('laraadmin.adminRoute') . '/cities') }}">City</a> :
@endsection
@section("contentheader_description", $city->$view_col)
@section("section", "Cities")
@section("section_url", url(config('laraadmin.adminRoute') . '/cities'))
@section("sub_section", "Create")

@section("htmlheader_title", "Cities Create : ".$city->$view_col)

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
				{!! Form::open(['action' => 'LA\CitiesController@store', 'id' => 'city-add-form']) !!}
					{{-- @la_form($module) --}}
					
					
					@la_input($module, 'cityName')
					@la_input($module, 'countryID')
					{{-- @la_input($module, 'stateID') --}}

					<div class="form-group">
						<label for="stateID">State* :</label>
						<select class="form-control" required="1" data-placeholder="Select State" rel="select2" name="stateID" id="stateID">
							<option value="">Select State</option>
						</select>
					</div>
					
					@la_input($module, 'latitude')
					@la_input($module, 'longitude')
					
                    <br>
					<div class="form-group">
						{!! Form::submit( 'Submit', ['class'=>'btn btn-success']) !!} <button class="btn btn-default pull-right"><a href="{{ url(config('laraadmin.adminRoute') . '/cities') }}">Cancel</a></button>
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
	$("#city-add-form").validate({
		
	});
});
</script>
<script>
	$( "select[name='countryID']" ).change(function () {
		var str = "";
		$( "select[name='countryID'] option:selected" ).each(function() {
		  str += $( this ).val() + " ";
		});

		$.ajax({
			url : "{{ url(config('laraadmin.adminRoute') . '/getStates') }}",
			method:"post",
			data : {"_token": "{{ csrf_token() }}", id: str },
			success: function(data){
				$('#stateID').html(data);
			}
		});
	}).change();
</script>
@endpush
