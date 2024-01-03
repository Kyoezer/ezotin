@extends('master')
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
				<table class="table table-bordered table-striped table-condensed flip-content">
					<tbody>
						<tr>
							<td><strong>Procuring Agency</strong></td>
							<td>{{$contractDetail->ProcuringAgency}}</td>
						</tr>
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
							<td><strong>Service Category</strong></td>
							<td>{{$contractDetail->ServiceCategory}}</td>
						</tr>
						<tr>
							<td><strong>Service Name</strong></td>
							<td>
								{{$contractDetail->ServiceCode}}
								<span class="font-green-seagreen" data-toggle="tooltip" title="{{$contractDetail->ServiceName}}"><i class="fa fa-question-circle"></i></span>
							</td>
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
				<h5 class="font-blue-madison bold">Consultant who has been awarded the work</h5>
				<table class="table table-bordered table-striped table-condensed flip-content">
					<thead>
						<tr>
							<th>Name of Consultant</th>
							<th class="text-right">Bid Amount</th>
							<th class="text-right">Evaluated Amount</th>
						</tr>
					</thead>
					<tbody>
						<?php $bidAmountTotal=0;$evaluatedAmountTotal=0; ?>
						@foreach($workAwardedConsultants as $workAwardedConsultant)
						<?php $bidAmountTotal+=$workAwardedConsultant->BidAmount;$evaluatedAmountTotal+=$workAwardedConsultant->EvaluatedAmount; ?>
						<tr>
							<td>{{$workAwardedConsultant->NameOfFirm}}</td>
							<td class="text-right">{{number_format($workAwardedConsultant->BidAmount,2)}}</td>
							<td class="text-right">{{number_format($workAwardedConsultant->EvaluatedAmount,2)}}</td>
						</tr>
						@endforeach
						<tr class="bold warning">
							<td class="text-right">Total</td>
							<td class="text-right">{{number_format($bidAmountTotal,2)}}</td>
							<td class="text-right">{{number_format($evaluatedAmountTotal,2)}}</td>
						</tr>
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
		{{ Form::open(array('url' => $model,'role'=>'form'))}}
		<input type="hidden" name="RedirectRoute" value="{{$redirectRoute}}" />
		@foreach($detailsOfCompletedWorks as $detailsOfCompletedWork)
		<div class="form-body">
			<div class="row">
				<div class="col-md-3">
					<div class="form-group">
						<input type="hidden" name="Id" value="{{$contractDetails[0]->Id}}" />
						<label class="control-label">Status</label>
						<select name="CmnWorkExecutionStatusId" class="form-control input-sm workcompletionstatuscontrol">
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
							<input type="text" name="ContractPriceInitial" class="number form-control text-right input-sm workstatuscompletedcontrol" value="{{$detailsOfCompletedWork->ContractPriceInitial}}" />
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label class="control-label">Contract Price (Final) Nu.</label>
							<input type="text" name="ContractPriceFinal" class="number form-control text-right input-sm workstatuscompletedcontrol" value="{{$detailsOfCompletedWork->ContractPriceFinal}}" />
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label class="control-label">Date of Commencement (Offcial)</label>
							<div class="input-icon right">
								<i class="fa fa-calendar"></i>
								<input type="text" name="CommencementDateOffcial" class="form-control datepicker input-sm workstatuscompletedcontrol" readonly="readonly" value="{{convertDateToClientFormat($detailsOfCompletedWork->CommencementDateOffcial)}}">
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label class="control-label">Date of Commencement (Actual)</label>
							<div class="input-icon right">
								<i class="fa fa-calendar"></i>
								<input type="text" name="CommencementDateFinal" class="form-control datepicker input-sm workstatuscompletedcontrol" readonly="readonly" value="{{convertDateToClientFormat($detailsOfCompletedWork->CommencementDateFinal)}}">
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
								<input type="text" name="CompletionDateOffcial" class="form-control datepicker input-sm workstatuscompletedcontrol" readonly="readonly" value="{{convertDateToClientFormat($detailsOfCompletedWork->CompletionDateOffcial)}}">
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label class="control-label">Date of Completion (Final)</label>
							<div class="input-icon right">
								<i class="fa fa-calendar"></i>
								<input type="text" name="CompletionDateFinal" class="form-control datepicker input-sm workstatuscompletedcontrol" readonly="readonly" value="{{convertDateToClientFormat($detailsOfCompletedWork->CompletionDateFinal)}}">
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label class="control-label">Ontime Completion Score (out of 30)</label>
							<input type="text" name="OntimeCompletionScore" class="form-control input-sm number range" data-min="0" data-max="30" value="{{$detailsOfCompletedWork->OntimeCompletionScore}}"/>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label class="control-label">Quality of Execution Score (out of 70)</label>
							<input type="text" name="QualityOfExecutionScore" class="form-control input-sm number range" data-min="0" data-max="70" value="{{$detailsOfCompletedWork->QualityOfExecutionScore}}"/>
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
					<a href="{{URL::to('contractor/worklist')}}" class="btn red">Cancel</a>
				</div>
			</div>
		</div>
	</div>
	{{Form::close()}}
</div>
@stop