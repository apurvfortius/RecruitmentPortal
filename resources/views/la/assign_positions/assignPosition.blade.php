@extends("la.layouts.app")

@section("contentheader_title")
	Assign Position : {{ $candidate->name }}
	
@endsection
@section("contentheader_description", $assign_position->$view_col)
@section("section", "Assign Position")
@section("section_url", url(config('laraadmin.adminRoute') . '/assign_positions'))
@section("sub_section", "Create")

@section("htmlheader_title", "Assign Position : ".$assign_position->$view_col)

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
				<div class="nav-tabs-custom">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="false">Basic Search</a></li>
						<li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false">Advance Search</a></li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane active" id="tab_1">
							<form name="basic_search">
								<div class="form-group">
									<input class="form-control" name="search_basic" id="search_basic" placeholder="Search By Keyword">
								</div>
							</form>
						</div>
						<!-- /.tab-pane -->
						<div class="tab-pane" id="tab_2">
							<form name="advance_search" id="advance_search">
								<div class="form-group">
									<label>Company</label>
									<select class="form-control" name="company_id[]" id="e2_1" style="width: 100%;">
										@foreach ($data['company'] as $key => $item)
												<option value="{{ $key }}">{{ $item }}</option>	
											@endforeach 
									</select>
								</div>

								<div class="form-group">
									<label>City</label>
									<select class="form-control" name="location[]" id="e2_6" style="width: 100%;">
										@foreach ($data['location'] as $key => $item)
												<option value="{{ $key }}">{{ $item }}</option>	
											@endforeach 
									</select>
								</div>

								<div class="form-group">
									<label>Required Experience</label>
									<select class="form-control" name="req_exp_id[]" id="e2_2" data-placeholder="" style="width: 100%;">
										@foreach ($data['experience'] as $key => $item)
											<option value="{{ $key }}">{{ $item }}</option>	
										@endforeach 
									</select>
								</div>

								<div class="form-group">
									<label>Under Graduate</label>
									<select class="form-control" name="qualification_ug[]" id="e2_3"  data-placeholder="" style="width: 100%;">
										@foreach ($data['qualification_ug'] as $key => $item)
											<option value="{{ $key }}">{{ $item }}</option>	
										@endforeach 
									</select>
								</div>

								<div class="form-group">
									<label>Post Graduate</label>
									<select class="form-control" name="qualification_pg[]" id="e2_4" data-placeholder="" style="width: 100%;">
										@foreach ($data['qualification_pg'] as $key => $item)
											<option value="{{ $key }}">{{ $item }}</option>	
										@endforeach 
									</select>
								</div>

								<div class="form-group">
									<label>Industry</label>
									<select class="form-control" name="industry_id[]" id="e2_5" data-placeholder="" style="width: 100%;">
										@foreach ($data['industry'] as $key => $item)
											<option value="{{ $key }}">{{ $item }}</option>	
										@endforeach 
									</select>
								</div>

								<div class="form-group">
									<label>Department</label>
									<select class="form-control" name="department_id[]" id="e2_7" data-placeholder="" style="width: 100%;">
										@foreach ($data['department'] as $key => $item)
											<option value="{{ $key }}">{{ $item }}</option>	
										@endforeach 
									</select>
								</div>

								<div class="form-group">
									<label>Industry</label>
									<select class="form-control" name="sub_department_id[]" id="e2_8" data-placeholder="" style="width: 100%;">
										@foreach ($data['sub_department'] as $key => $item)
											<option value="{{ $key }}">{{ $item }}</option>	
										@endforeach 
									</select>
								</div>

								<div class="form-group">
									<label>Industry</label>
									<select class="form-control" name="budget_id[]" id="e2_9" data-placeholder="" style="width: 100%;">
										@foreach ($data['budget'] as $key => $item)
											<option value="{{ $key }}">{{ $item }}</option>	
										@endforeach 
									</select>
								</div>

								<div class="form-group">
									<input id="search" type="button" class="btn btn-primary" value="Search">
								</div>
							</div>
						</form>
					</div>
					<!-- /.tab-content -->
				</div>
			</div>
		</div>
	</div>
	
	<div class="alert alert-success alert-dismissible" id="message" style="display:none;">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		<h5><i class="icon fas fa-check"></i> Alert!</h5>
		<label>Success alert preview. This alert is dismissable.</label>
	</div>

	<div class="box-body">
		<div class="card">
			<div class="card-header">
				<h4 class="card-title">Search Result</h4>
			</div>
			<!-- /.card-header -->
			<div class="card-body" id="search_body">
			  
			</div>
			<!-- /.card-body -->
			<div class="box-footer card-footer clearfix">
				<div class="row">
					<div class="col-md-12">
						<button type="button" onclick="assignCandidate();" class="btn btn-block btn-outline-primary">Assign To Selected</button>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4">
						<label id="pageText" class="callout-info"></label>
					</div>
					<div class="col-md-8">
						<ul class="pagination pagination-sm no-margin pull-right">
							<li class="page-item" ><label class="page-link" id="previous_page"><< Previous </label></li>
							<li class="page-item" ><label class="page-link" id="current_page"></label></li>
							<li class="page-item" ><label class="page-link" id="next_page">Next >></label></li>
						</ul>
					</div>
				</div>				
			</div>
		</div>
	</div>
</div>
@endsection

@push('scripts')
<script>
	$("#e2_1").select2({
		placeholder: "Select a City",
		allowClear: true,
		multiple: true,
	});
	$("#e2_2").select2({
		placeholder: "Select a Experience",
		allowClear: true,
		multiple: true,
	});
	$("#e2_3").select2({
		placeholder: "Select a Under Graduate",
		allowClear: true,
		multiple: true,
	});
	$("#e2_4").select2({
		placeholder: "Select a Post Graduate",
		allowClear: true,
		multiple: true,
	});
	$("#e2_5").select2({
		placeholder: "Select a notice Period",
		allowClear: true,
		multiple: true,
	});
	$("#e2_6").select2({
		placeholder: "Select a notice Period",
		allowClear: true,
		multiple: true,
	});
	$("#e2_7").select2({
		placeholder: "Select a notice Period",
		allowClear: true,
		multiple: true,
	});
	$("#e2_8").select2({
		placeholder: "Select a notice Period",
		allowClear: true,
		multiple: true,
	});
	$("#e2_9").select2({
		placeholder: "Select a notice Period",
		allowClear: true,
		multiple: true,
	});

	
	var next = '';
	var previous = '';
	$(document).ready(function(){
		$('#search_basic').keyup(function(){
			getList($(this).val(), '/admin/getBasicSearch/Position'); 
		});
	});

	$(document).ready(function(){
		$('#next_page').click(function(){
			getList($('#search_basic').val(), next);
		});
	});

	$(document).ready(function(){
		$('#previous_page').click(function(){
			getList($('#search_basic').val(), previous);
		});
	});

	function getList(search, url, type){
		if(type == "advance"){
			$.ajax({
				url: url,
				type: 'POST',
				data: $('#advance_search').serialize()+"&_token={{ csrf_token() }}&id={{ $id }}",
				success: function (data) { 
					$('#search_body').html(data.html);
					var string = data.result.from+" to "+data.result.to+" Candidate out of "+data.result.total;
					$('#pageText').html(string);
					$('#current_page').html(data.result.current_page);
					next = data.result.next_page_url;
					previous = data.result.prev_page_url;
				}
			});
		}
		else{
			$.ajax({
				url: url,
				type: 'POST',
				data: { "_token": "{{ csrf_token() }}", "str": search, "id": "{{ $id }}" },
				success: function (data) { 
					$('#search_body').html(data.html);
					var string = data.result.from+" to "+data.result.to+" Candidate out of "+data.result.total;
					$('#pageText').html(string);
					$('#current_page').html(data.result.current_page);
					next = data.result.next_page_url;
					previous = data.result.prev_page_url;
				}
			});
		}		
	}

	function assignCandidate(){
		let a = [];
		$("input[id=assignbox]:checked").each(function() {
			a.push(this.value);
		});

		$.ajax({
			url: '/admin/assignPosition',
			type: 'POST',
			data: { "_token": "{{ csrf_token() }}", 'ids': a, 'candidate': '{{ $candidate->id }}' },
			success: function (data) { 
				$('#message').css('display', '');
				$('#message label').html(data.msg);
				hideAlert();
			}
		});
	}
	
	function unasignedCandidate(position, candidate) {
		$.ajax({
			url: '/admin/unassignCandidate',
			type: 'POST',
			data: { "_token": "{{ csrf_token() }}", 'position': position, 'candidate': candidate },
			success: function (data) { 
				$('#message').css('display', '');
				$('#message label').html(data.msg);
				hideAlert();
			}
		});
	}

	$(document).ready(function(){
		$('#search').click(function(){
			var form = $('#advance_search').serializeArray();
			getList(form, '/admin/getAdvanceSearch/Position', 'advance');
		});
	});

	// function hideAlert() {
	// 	$("#message").fadeTo(2000, 500).slideUp(500, function() {
	// 		$("#message").slideUp(500);
	// 		$('#message').css('display', 'none');
	// 	});
	// }
</script>
@endpush
