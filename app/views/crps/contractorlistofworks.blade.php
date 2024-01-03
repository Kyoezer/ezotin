@extends('master')
@section('content')
<input type="hidden" name="FullURL" value="{{CONST_APACHESITELINK}}"/>
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption font-green-seagreen">
			<i class="fa fa-cogs"></i>
			<span class="caption-subject">List of Works</span>
			<span class="caption-helper">The contracts listed below are all <span class="font-red-thunderbird bold">@if((int)$underProcess==1){{'under process'}}@else{{'completed/terminated'}}@endif</span></span>
		</div>
	</div>
	<div class="portlet-body">
		{{ Form::open(array('url' =>Request::URL(),'role'=>'form','method'=>'get')) }}
		<div class="row">
			<div class="col-md-2">
				<div class="form-group">
					<label class="control-label">CDB No. </label>
					<input type="text" name="CDBNo" value="{{Input::get('CDBNo')}}" class="form-control">
				</div>
			</div>
			<div class="col-md-2">
				<div class="form-group">
					<label class="control-label">Work Order No.</label>
					<input type="text" name="WorkOrderNo" value="{{$workOrderNo}}" class="form-control">
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label class="control-label">Procuring Agency</label>
					<select name="ProcuringAgency" class="form-control select2me">
						<option value="">---SELECT ONE---</option>
						@foreach($procuringAgencies as $procuringAgency)
						<option value="{{$procuringAgency->Id}}" @if($procuringAgency->Id==$procuringAgencyId)selected="selected"@endif>{{$procuringAgency->Name}}</option>
						@endforeach
					</select>
				</div>
			</div>
			@if((int)$underProcess==1)
			<div class="col-md-4">
				<div class="form-group">
					<label class="control-label">Work Start Date Between</label>
					<div class="input-group input-large date-picker input-daterange">
						<input type="text" name="WorkStartDateFrom" class="form-control datepickerfrom" value="{{$workStartDateFrom}}" />
						<span class="input-group-addon">
						to </span>
						<input type="text" name="WorkStartDateTo" class="form-control datepickerto" value="{{$workStartDateTo}}" />
					</div>
				</div>
			</div>
			@else
			<div class="col-md-3">
				<div class="form-group">
					<label class="control-label">Status</label>
					<select name="WorkExecutionStatus" class="form-control">
						<option value="">---SELECT ONE---</option>
						@foreach($workExecutionStatus as $workExecutionStatus)
						<option value="{{$workExecutionStatus->Id}}" @if($workExecutionStatus->Id==$workStatus)selected="selected"@endif>{{$workExecutionStatus->Name}}</option>
						@endforeach
					</select>
				</div>
			</div>
			@endif
			<div class="col-md-2">
				<!-- <label class="control-label">&nbsp;</label> -->
				<div class="btn-set">
					<button type="submit" class="btn blue-hoki btn-sm">Search</button>
					<a href="{{Request::URL()}}" class="btn grey-cascade btn-sm">Clear</a>
				</div>
			</div>
		</div>
		{{Form::close()}}
		<br>
		<table class="table table-bordered table-striped table-condensed flip-content">
			<thead class="flip-content">
				<tr>
					<th>
						Procuring Agency
					</th>
					<th>
						CDB No
					</th>
					<th class="">
						Work Order No.
					</th>
					<th class="">
						Name of Work
					</th>
					<th class="">
						Work Category
					</th>
					<th class="">
						Class
					</th>
					<th>
						Contract Period (In Months)
					</th>
					<th>
						Start Date
					</th>
					<th>
						Completion Date
					</th>
					@if((int)$underProcess==0)
					<th>
						Status
					</th>
					@endif
					<th>
						Action
					</th>
				</tr>
			</thead>
			<tbody>
				<?php $currentProcuringAgeny=""; ?>
				@forelse($listOfWorks as $lsitOfWork)
				<tr>
					<td>
						@if($lsitOfWork->ProcuringAgency!=$currentProcuringAgeny)
						{{$lsitOfWork->ProcuringAgency}}
						@endif
					</td>
					<td>{{$lsitOfWork->CDBNo}}</td>
					<td>{{$lsitOfWork->WorkOrderNo}}</td>
					<td>{{$lsitOfWork->NameOfWork}}</td>
					<td>{{$lsitOfWork->WorkCategory}}</td>
					<td>{{$lsitOfWork->ContractorClass}}</td>
					<td>{{$lsitOfWork->ContractPeriod}}</td>
					<td>{{convertDateToClientFormat($lsitOfWork->WorkStartDate)}}</td>
					<td>{{convertDateToClientFormat($lsitOfWork->WorkCompletionDate)}}</td>
					@if((int)$underProcess==0)
					<td>
						{{$lsitOfWork->WorkExecutionStatus}}
					</td>
					@endif
					<td>
						@if(Request::path()=="contractor/editcompletedworklist" || Request::path()=="contractor/worklist")
						<a href="{{URL::to('contractor/workcompletionform/'.$lsitOfWork->Id)}}" class="btn default btn-xs green-seagreen"><i class="fa fa-edit"></i> View</a>
						@elseif(Request::path()=="contractor/editbiddingformlist")
						<a href="{{URL::to('contractor/biddingform?bidreference='.$lsitOfWork->Id)}}" class="btn default editaction btn-xs green-seagreen"><i class="fa fa-edit"></i> Edit</a>
						@endif
                        <a href="#" data-id="{{$lsitOfWork->Id}}" class="btn default btn-xs bg-red-sunglo deletebid"><i class="fa fa-edit"></i> Delete</a>
					</td>
					<?php $currentProcuringAgeny=$lsitOfWork->ProcuringAgency; ?>
				</tr>
				@empty
				<tr>
					<td colspan="10" class="text-center font-red">No data to display</td>
				</tr>
				@endforelse
			</tbody>
		</table>
	</div>
</div>
@stop