@extends('master')
@section('pagescripts')
	@if((int)$serviceByConsultant==1)
		<script>
			//$('#workclassificationservice').modal('show');
			$('input.tablerowcheckbox').attr('disabled','disabled');
		</script>
	@endif
@stop
@section('content')
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-cogs"></i>Edit Work Classification
		</div>
		@if((int)$serviceByConsultant==1)
			<button id="reselectchangeofcategoryclassification" type="button" class="btn green-seagreen btn-sm pull-right">Re-select Service</button>
		@endif
	</div>
	<div class="portlet-body">
		{{ Form::open(array('url' => 'consultant/mconsultantworkclassification','role'=>'form','files'=>true))}}
		<input type="hidden" value="1" name="EditByCdb">
		<input type="hidden" name="PostBackUrl" value="{{$redirectUrl}}">
		<input type="hidden" name="ServiceByConsultant" value="{{$serviceByConsultant}}">
		<input type="hidden" name="CrpApplicationConsultantId" value="{{$consultantId}}" />
		@if(isset($finalEdit) && (bool)$finalEdit)
			<input type="hidden" name="FinalEdit" value="1" />
		@endif
		@if((int)$serviceByConsultant==1)
			<div class="row">
				<div class="col-md-6 table-responsive">
					<p class="bold font-green-seagreen">Current Classification</p>
					<table class="table table-bordered table-striped table-condensed">
						<thead class="">
							<tr>
								<th width="40%">
									Service Category
								</th>
								<th>
									Service
								</th>
							</tr>
						</thead>
						<tbody>
							@foreach($currentServiceClassifications as $currrentServiceClassification)
								<tr>
									<td class="small-medium">{{$currrentServiceClassification->Category}}</td>
									<td>{{$currrentServiceClassification->ApprovedService}}</td>
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>
				<div class="upgradedowngradeattachments">
					<div class="col-md-6 table-responsive">
						<h5 class="font-blue-madison bold">Attachments</h5>
						<table id="upgradedowngradeattachments" class="table table-bordered table-striped table-condensed">
							<thead>
								<tr>
									<th></th>
									<th>Document Name</th>
									<th>Upload File</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>
										<button type="button" class="deletetablerow"><i class="fa fa-times fa-lg"></i></button>
									</td>
									<td>
										<input type="text" name="DocumentName[]" class="dontdisable form-control input-sm">
									</td>
									<td>
										<input type="file" name="attachments[]" class="dontdisable input-sm" multiple="multiple" />
									</td>
								</tr>
								<tr class="notremovefornew">
									<td>
										<button type="button" class="addtablerow"><i class="fa fa-plus fa-lg"></i></button>
									</td>
									<td colspan="2"></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>	
		@endif
		@include('crps.consultantworkclassificationcontrols');
		{{Form::close()}}
	</div>
</div>
@stop