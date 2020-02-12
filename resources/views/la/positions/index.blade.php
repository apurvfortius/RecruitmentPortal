@extends("la.layouts.app")

@section("contentheader_title", "Positions")
@section("contentheader_description", "Positions listing")
@section("section", "Positions")
@section("sub_section", "Listing")
@section("htmlheader_title", "Positions Listing")

@section("headerElems")
@la_access("Positions", "create")
	<button class="btn btn-success btn-sm pull-right" data-toggle="modal" data-target="#AddModal">Add Position</button>
@endla_access
@endsection

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

<div class="box box-success">
	<!--<div class="box-header"></div>-->
	<div class="box-body">
		<table id="example1" class="table table-bordered">
		<thead>
		<tr class="success">
			@foreach( $listing_cols as $col )
			<th>{{ $module->fields[$col]['label'] or ucfirst($col) }}</th>
			@endforeach
			@if($show_actions)
			<th>Actions</th>
			@endif
		</tr>
		</thead>
		<tbody>
			
		</tbody>
		</table>
	</div>
</div>

@la_access("Positions", "create")
<div class="modal fade" id="AddModal" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Add Position</h4>
			</div>
			{!! Form::open(['action' => 'LA\PositionsController@store', 'id' => 'position-add-form']) !!}
			<div class="modal-body">
				<div class="box-body">
                    @la_form($module)
					
					{{--
					@la_input($module, 'position_code')
					@la_input($module, 'company_id')
					@la_input($module, 'title')
					@la_input($module, 'position_level')
					@la_input($module, 'industry_id')
					@la_input($module, 'department_id')
					@la_input($module, 'sub_department_id')
					@la_input($module, 'report_to')
					@la_input($module, 'team_size')
					@la_input($module, 'location')
					@la_input($module, 'budget_id')
					@la_input($module, 'qualification_ug')
					@la_input($module, 'qualification_pg')
					@la_input($module, 'no_position')
					@la_input($module, 'req_exp_id')
					@la_input($module, 'urgency_pos')
					@la_input($module, 'buy_out')
					@la_input($module, 'com_turnover')
					@la_input($module, 'emp_strength')
					@la_input($module, 'jd_available')
					@la_input($module, 'website')
					@la_input($module, 'pos_date')
					@la_input($module, 'job_description')
					@la_input($module, 'pos_given_by')
					@la_input($module, 'pos_assign_to')
					--}}
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				{!! Form::submit( 'Submit', ['class'=>'btn btn-success']) !!}
			</div>
			{!! Form::close() !!}
		</div>
	</div>
</div>
@endla_access

@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('la-assets/plugins/datatables/datatables.min.css') }}"/>
@endpush

@push('scripts')
<script src="{{ asset('la-assets/plugins/datatables/datatables.min.js') }}"></script>
<script>
$(function () {
	$("#example1").DataTable({
		processing: true,
        serverSide: true,
        ajax: "{{ url(config('laraadmin.adminRoute') . '/position_dt_ajax') }}",
		language: {
			lengthMenu: "_MENU_",
			search: "_INPUT_",
			searchPlaceholder: "Search"
		},
		@if($show_actions)
		columnDefs: [ { orderable: false, targets: [-1] }],
		@endif
	});
	$("#position-add-form").validate({
		
	});
});
</script>
@endpush
