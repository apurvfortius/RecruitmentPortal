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
					{{-- @la_form($module) --}}
					<input type="hidden" value="{{ $branch->company_id }}" name="company_id">
					
					{{-- @la_input($module, 'company_id') --}}
					@la_input($module, 'type')
					{{-- @la_input($module, 'country_id')
					@la_input($module, 'state_id')
					@la_input($module, 'city_id') --}}

					<div class="form-group">
						<label for="country_id">Country* :</label>
						<input list="country_list" value="{{ $data['country'] }}" class="form-control" placeholder="Enter Country" data-rule-minlength="1" data-rule-maxlength="255" required="1" name="country_id" type="text" value="" aria-required="true">
						<datalist id="country_list">
						</datalist>
					</div>

					<div class="form-group">
						<label for="state_id">State* :</label>
						<input list="state_list" value="{{ $data['state'] }}" class="form-control" placeholder="Enter State" data-rule-minlength="1" data-rule-maxlength="255" required="1" name="state_id" type="text" value="" aria-required="true">
						<datalist id="state_list">
						</datalist>
					</div>

					<div class="form-group">
						<label for="city_id">City* :</label>
						<input list="city_list" value="{{ $data['city'] }}" class="form-control" placeholder="Enter City" data-rule-minlength="1" data-rule-maxlength="255" required="1" name="city_id" type="text" value="" aria-required="true">
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
						{!! Form::submit( 'Update', ['class'=>'btn btn-success']) !!} <button class="btn btn-default pull-right"><a href="{{ url(config('laraadmin.adminRoute') . '/companies') }}">Cancel</a></button>
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
<script>
	//$( "select[name='state_id']" ).change(function () {
	function getCity(){
		var str = "";
		$( "select[name='state_id'] option:selected" ).each(function() {
		  str += $( this ).val() + " ";
		});

		$.ajax({
			url : "{{ url(config('laraadmin.adminRoute') . '/getCity') }}",
			method:"post",
			data : {"_token": "{{ csrf_token() }}", id: str, selected: "{{ $branch->city_id }}" },
			success: function(data){
				$('#city_id').html(data);
			}
		});
	}
	
		
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
			data : {"_token": "{{ csrf_token() }}", id: str, selected: "{{ $branch->state_id }}" },
			success: function(data){
				$('#state_id').html(data);
				getCity();
			}
		});
	}).change();
</script>


@endpush
