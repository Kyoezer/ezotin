@extends('horizontalmenumaster')
@section('content')
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption font-green-seagreen">
			<i class="fa fa-cogs"></i>
			<span class="caption-subject">Work Summary</span>
			<span class="caption-helper">Click on the icon at right hand corner to expand and view the work summary</span>
		</div>
		<div class="tools">
			<a href="javascript:;" class="expand"></a>
		</div>
	</div>
	<div class="portlet-body display-hide">
		<div class="row">
			@foreach($contractDetails as $contractDetail)
			<div class="col-md-6">
				<table class="table table-bordered table-striped table-condensed">
					<tbody>
						<tr>
							<td><strong>Work Order No.</strong></td>
							<td>{{$contractDetail->WorkOrderNo}}</td>
						</tr>
						<tr>
							<td><strong>Name of Contract Work</strong></td>
							<td>{{$contractDetail->NameOfWork}}</td>
						</tr>
						<tr>
							<td><strong>Dzongkhag</strong></td>
							<td>{{$contractDetail->Dzongkhag}}</td>
						</tr>
						<tr>
							<td><strong>Category of Work</strong></td>
							<td>{{$contractDetail->ProjectCategory}}</td>
						</tr>
						<tr>
							<td><strong>Class</strong></td>
							<td>{{$contractDetail->ContractorClass}}</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="col-md-6">
				<table class="table table-bordered table-striped table-condensed flip-content">
					<tbody>
						<tr>
							<td><strong>Contract Description</strong></td>
							<td>{{$contractDetail->DescriptionOfWork}}</td>
						</tr>
						<tr>
							<td><strong>Approved Agency Estimate</strong></td>
							<td>{{$contractDetail->ApprovedAgencyEstimate}}</td>
						</tr>
						<tr>
							<td><strong>Contract Period (In Months)</strong></td>
							<td>{{$contractDetail->ContractPeriod}}</td>
						</tr>
						<tr>
							<td><strong>Start Date</strong></td>
							<td>{{convertDateToClientFormat($contractDetail->WorkStartDate)}}</td>
						</tr>
						<tr>
							<td><strong>Completion Date</strong></td>
							<td>{{convertDateToClientFormat($contractDetail->WorkCompletionDate)}}</td>
						</tr>
					</tbody>
				</table>
			</div>
			@endforeach
		</div>
		<div class="row">
			<div class="col-md-6">
				<h5 class="font-blue-madison bold">Contractor who has been awarded the work</h5>
				<table class="table table-bordered table-striped table-condensed flip-content">
					<thead>
						<tr>
							<th>Name of Contractor</th>
							<th class="text-right">Bid Amount</th>
							<th class="text-right">Evaluated Amount</th>
						</tr>
					</thead>
					<tbody>
						<?php $bidAmountTotal=0;$evaluatedAmountTotal=0; ?>
						@foreach($workAwardedContractor as $workAwardedContractor)
						<?php $bidAmountTotal+=$workAwardedContractor->BidAmount;$evaluatedAmountTotal+=$workAwardedContractor->EvaluatedAmount; ?>
						<tr>
							<td>{{$workAwardedContractor->NameOfFirm}}</td>
							<td class="text-right">{{number_format($workAwardedContractor->BidAmount,2)}}</td>
							<td class="text-right">{{number_format($workAwardedContractor->EvaluatedAmount,2)}}</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption font-green-seagreen">
			<i class="fa fa-cogs"></i>Work Completion Form
		</div>
	</div>
	<div class="portlet-body form">
		{{ Form::open(array('url' => $model,'role'=>'form','files'=>true))}}
		<input type="hidden" name="RedirectRoute" value="{{$redirectRoute}}" />
		@foreach($detailsOfCompletedWorks as $detailsOfCompletedWork)
		<div class="form-body">
			<div class="row">
				<div class="col-md-3">
					<div class="form-group">
						<input type="hidden" name="Id" value="{{$contractDetails[0]->Id}}" />
						<label class="control-label">Status</label>
						<select name="CmnWorkExecutionStatusId" class="form-control input-sm workcompletionstatuscontrol required">
							<option value="">---SELECT ONE---</option>
							@foreach($workExecutionStatus as $workExecutionStatus)
							<option value="{{$workExecutionStatus->Id}}" data-referenceno="{{$workExecutionStatus->ReferenceNo}}" @if($workExecutionStatus->Id==$detailsOfCompletedWork->CmnWorkExecutionStatusId)selected="selected"@endif>{{$workExecutionStatus->Name}}</option>
							@endforeach
						</select>
					</div>
				</div>
			</div>
			<div class="workcompletedinfo @if(empty($detailsOfCompletedWork->CmnWorkExecutionStatusId) || $detailsOfCompletedWork->CmnWorkExecutionStatusId==CONST_CMN_WORKEXECUTIONSTATUS_TERMINATED){{'hide'}}@endif">
				<div class="row">
					<div class="col-md-3">
						<div class="form-group">
							<label class="control-label">Contract Price (Initial) Nu.</label>
							<input type="text" name="ContractPriceInitial" class="form-control text-right input-sm workstatuscompletedcontrol required" value="{{$detailsOfCompletedWork->ContractPriceInitial}}" />
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label class="control-label">Contract Price (Final) Nu.</label>
							<input type="text" name="ContractPriceFinal" class="form-control text-right input-sm workstatuscompletedcontrol required" value="{{$detailsOfCompletedWork->ContractPriceFinal}}" />
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label class="control-label">Date of Commencement (Offcial)</label>
							<div class="input-icon right">
								<i class="fa fa-calendar"></i>
								<input type="text" name="CommencementDateOffcial" class="form-control datepicker input-sm workstatuscompletedcontrol required" readonly="readonly" value="@if(!empty($detailsOfCompletedWork->CommencementDateOffcial)){{convertDateToClientFormat($detailsOfCompletedWork->CommencementDateOffcial)}}@endif">
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label class="control-label">Date of Commencement (Actual)</label>
							<div class="input-icon right">
								<i class="fa fa-calendar"></i>
								<input type="text" name="CommencementDateFinal" class="form-control datepicker input-sm workstatuscompletedcontrol required" readonly="readonly" value="@if($detailsOfCompletedWork->CommencementDateFinal){{convertDateToClientFormat($detailsOfCompletedWork->CommencementDateFinal)}}@endif">
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-3">
						<div class="form-group">
							<label class="control-label">Date of Completion (Offcial)</label>
							<div class="input-icon right">
								<i class="fa fa-calendar"></i>
								<input type="text" name="CompletionDateOffcial" class="form-control datepicker input-sm workstatuscompletedcontrol required" readonly="readonly" value="@if($detailsOfCompletedWork->CompletionDateOffcial){{convertDateToClientFormat($detailsOfCompletedWork->CompletionDateOffcial)}}@endif">
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label class="control-label">Date of Completion (Final)</label>
							<div class="input-icon right">
								<i class="fa fa-calendar"></i>
								<input type="text" name="CompletionDateFinal" class="form-control datepicker input-sm workstatuscompletedcontrol required" readonly="readonly" value="@if($detailsOfCompletedWork->CompletionDateFinal){{convertDateToClientFormat($detailsOfCompletedWork->CompletionDateFinal)}}@endif">
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label class="control-label">Ontime Completion Score (out of 30)</label>
							<input type="text" name="OntimeCompletionScore" class="form-control input-sm workstatuscompletedcontrol number range required" data-min="0" data-max="30" value="{{$detailsOfCompletedWork->OntimeCompletionScore}}"/>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label class="control-label">Quality of Execution Score (out of 70)</label>
							<input type="text" name="QualityOfExecutionScore" class="form-control input-sm workstatuscompletedcontrol number range required" data-min="0" data-max="70" value="{{$detailsOfCompletedWork->QualityOfExecutionScore}}"/>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-3">
						<div class="form-group">
							<label class="control-label">APS Form</label>
							<input type="file" name="APSForm" class="form-control input @if(!$isAdmin){{'required'}}@endif" />
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label class="control-label">Remarks</label>
						<textarea name="Remarks" id="" rows="3" class="form-control">{{$detailsOfCompletedWork->Remarks}}</textarea>
					</div>
				</div>
			</div>
		</div>
		@endforeach
		<div class="form-actions">
			<div class="row">
				<div class="col-md-12">
					<button type="submit" class="btn green">Save</button>
					<a href="{{URL::to('cinet/worklist')}}" class="btn red">Cancel</a>
				</div>
			</div>
		</div>
	</div>
	{{Form::close()}}
</div>
@stop