@extends("la.layouts.app")

@section("contentheader_title")
	<a href="{{ url(config('laraadmin.adminRoute') . '/branches') }}">Branch</a> :
@endsection
@section("contentheader_description", "Create New")
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
				{!! Form::open(['action' => 'LA\BranchesController@store', 'id' => 'branch-add-form']) !!}
					{{-- @la_form($module) --}}
					
					
					@la_input($module, 'company_id')
					@la_input($module, 'type')
                    @la_input($module, 'country_id')
                    
					<div class="form-group">
						<label for="state_id">State* :</label>
						<select class="form-control" required="1" data-placeholder="Select State" rel="select2" name="state_id" id="state_id">
							<option value="">Select State</option>
						</select>
					</div>

					<div class="form-group">
						<label for="city_id">City* :</label>
						<select class="form-control" required="1" data-placeholder="Select City" rel="select2" name="city_id" id="city_id">
							<option value="">Select City</option>
						</select>
					</div>
					{{-- @la_input($module, 'state_id')
					@la_input($module, 'city_id') --}}
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
	$( "select[name='country_id']" ).change(function () {
		var str = "";
		$( "select[name='country_id'] option:selected" ).each(function() {
		  str += $( this ).val() + " ";
		});

		$.ajax({
			url : "{{ url(config('laraadmin.adminRoute') . '/getStates') }}",
			method:"post",
			data : {"_token": "{{ csrf_token() }}", id: str },
			success: function(data){
				$('#state_id').html(data);
			}
		});
	}).change();
</script>

<script>
	$( "select[name='state_id']" ).change(function () {
		var str = "";
		$( "select[name='state_id'] option:selected" ).each(function() {
		  str += $( this ).val() + " ";
		});

		$.ajax({
			url : "{{ url(config('laraadmin.adminRoute') . '/getCity') }}",
			method:"post",
			data : {"_token": "{{ csrf_token() }}", id: str },
			success: function(data){
				$('#city_id').html(data);
			}
		});
	}).change();
</script>
@endpush
