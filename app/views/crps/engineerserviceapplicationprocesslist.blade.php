@extends('master')
@section('content')
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption font-green-seagreen">
			<i class="fa fa-cogs"></i>
			<span class="caption-subject">{{$pageTitle}} - My Task</span>
		</div>
		<div class="tools">
			<a href="javascript:;" class="collapse"></a>
		</div>
	</div>
	<div class="portlet-body">
		<div class="form-body">
			{{ Form::open(array('url' =>Request::URL(),'role'=>'form','method'=>'get')) }}
			<div class="row">
				<div class="col-md-2">
					<div class="form-group">
						<label class="control-label">Engineer Type</label>
						<select name="CmnServiceSectorTypeIdMyTask" class="form-control">
							<option value="">---SELECT ONE---</option>
							@foreach($serviceSectorTypes as $serviceSectorType)
								<option value="{{$serviceSectorType->Id}}" @if($serviceSectorTypeIdMyTask==$serviceSectorType->Id)selected="selected"@endif>{{$serviceSectorType->Name}}</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<label class="control-label">Trade</label>
						<select name="CmnTradeIdMyTask" class="form-control">
							<option value="">---SELECT ONE---</option>
							@foreach($trades as $trade)
								<option value="{{$trade->Id}}" @if($tradeIdMyTask==$trade->Id)selected="selected"@endif>{{$trade->Name}}</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label class="control-label">Name</label>
						<select name="CrpEngineerIdMyTask" class="form-control select2me">
							<option value="">---SELECT ONE---</option>
							@foreach($engineerMyTaskLists as $engineerMyTaskList)
								<option value="{{$engineerMyTaskList->Id}}" @if($engineerIdMyTask==$engineerMyTaskList->Id)selected="selected"@endif>{{$engineerMyTaskList->EngineerName}}</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label class="control-label">Application date between</label>
						<div class="input-group input-large date-picker input-daterange">
							<input type="text" name="FromDateMyTask" class="form-control datepickerfrom" value="{{$fromDateMyTask}}" />
							<span class="input-group-addon">
							to </span>
							<input type="text" name="ToDateMyTask" class="form-control datepickerto" value="{{$toDateMyTask}}" />
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="btn-set">
						<button type="submit" class="btn blue-hoki btn-sm">Search</button>
						<a href="{{Request::URL()}}" class="btn grey-cascade btn-sm">Clear</a>
					</div>
				</div>
			</div>
			{{Form::close()}}
		</div><br />
		<div class="table-responsive">
			<table class="table table-bordered table-striped table-condensed">
				<thead>
					<tr>
						<th>
							 Ref#
						</th>
						<th>
							 Application Dt.
						</th>
						<th>
							Engineer Type
						</th>
						<th>
							Trade
						</th>
						<th>
							 Name
						</th>
						<th>
							CID/Work Permit No.
						</th>
						<th>
							 Country
						</th>
						<th>
							 Dzongkhag
						</th>
						<th>
							Mobile#
						</th>
						<th>
							Email
						</th>
						<th>
							Applied for
						</th>
						<th>
							Action
						</th>
					</tr>
				</thead>
				<tbody>
					@forelse($engineerMyTaskLists as $engineerMyTaskList)
					<tr>
						<td>
							 {{$engineerMyTaskList->ReferenceNo}}
						</td>
						<td>
							 {{convertDateToClientFormat($engineerMyTaskList->ApplicationDate)}}
						</td>
						<td>
							{{$engineerMyTaskList->EngineerType}}
						</td>
						<td>
							{{$engineerMyTaskList->Trade}}
						</td>
						<td>
							{{$engineerMyTaskList->Salutation.' '.$engineerMyTaskList->EngineerName}} 
						</td>
						<td>
							{{$engineerMyTaskList->CIDNo}}
						</td>
						<td>
							 {{$engineerMyTaskList->Country}}
						</td>
						<td>
							 {{$engineerMyTaskList->Dzongkhag}}
						</td>
						<td>
							 {{$engineerMyTaskList->MobileNo}}
						</td>
						<td>
							{{$engineerMyTaskList->Email}}
						</td>
						<td>
							{{$engineerMyTaskList->AppliedService}}
						</td>
						<td>
							@if(Request::path()=="engineer/approveserviceapplicationlist")
								<a href="{{URL::to('engineer/approveserviceapplicationprocess/'.$engineerMyTaskList->Id)}}" class="btn default btn-xs green-seagreen processaction"><i class="fa fa-edit"></i> Process</a>
							@elseif(Request::path()=="engineer/approveserviceapplicationfeepaymentlist")
								<a href="{{URL::to('engineer/approveserviceapplicationpaymentprocess/'.$engineerMyTaskList->Id)}}" class="btn default btn-xs green-seagreen processaction"><i class="fa fa-edit"></i> Process</a>
							@else
								<a href="{{URL::to('engineer/verifyserviceapplicationprocess/'.$engineerMyTaskList->Id)}}" class="btn default btn-xs green-seagreen processaction"><i class="fa fa-edit"></i> Process</a>
							@endif
							<a href="{{URL::to('dropapplication')}}?model=EngineerModel&id={{$engineerMyTaskList->Id}}&redirectUrl={{$redirectUrl}}" class="btn default btn-xs purple dropaction"><i class="fa fa-times"></i> Drop</a>
						</td>
					</tr>
					@empty
					<tr>
						<td class="font-red text-center" colspan="12">No data to display</td>
					</tr>
					@endforelse
				</tbody>
			</table>
		</div>
	</div>
</div>
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption font-green-seagreen">
			<i class="fa fa-cogs"></i>
			<span class="caption-subject">Pick the applications you wish to work on.</span>
		</div>
		<div class="tools">
			<a href="javascript:;" class="expand"></a>
		</div>
	</div>
	<div class="portlet-body display-hide">
		<div class="form-body">
			{{ Form::open(array('url' =>Request::URL(),'role'=>'form','method'=>'get')) }}
			<div class="row">
				<div class="col-md-2">
					<div class="form-group">
						<label class="control-label">Engineer Type</label>
						<select name="CmnServiceSectorTypeIdAll" class="form-control">
							<option value="">---SELECT ONE---</option>
							@foreach($serviceSectorTypes as $serviceSectorType)
								<option value="{{$serviceSectorType->Id}}" @if($serviceSectorTypeIdAll==$serviceSectorType->Id)selected="selected"@endif>{{$serviceSectorType->Name}}</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<label class="control-label">Trade</label>
						<select name="CmnTradeIdAll" class="form-control">
							<option value="">---SELECT ONE---</option>
							@foreach($trades as $trade)
								<option value="{{$trade->Id}}" @if($tradeIdAll==$trade->Id)selected="selected"@endif>{{$trade->Name}}</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label class="control-label">Name</label>
						<select name="CrpEngineerIdAll" class="form-control select2me">
							<option value="">---SELECT ONE---</option>
							@foreach($engineerLists as $engineerList)
								<option value="{{$engineerList->Id}}" @if($engineerIdAll==$engineerList->Id)selected="selected"@endif>{{$engineerList->EngineerName}}</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label class="control-label">Application date between</label>
						<div class="input-group input-large date-picker input-daterange">
							<input type="text" name="FromDateAll" class="form-control datepickerfrom" value="{{$fromDateAll}}" />
							<span class="input-group-addon">
							to </span>
							<input type="text" name="ToDateAll" class="form-control datepickerto" value="{{$toDateAll}}" />
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="btn-set">
						<button type="submit" class="btn blue-hoki btn-sm">Search</button>
						<a href="{{Request::URL()}}" class="btn grey-cascade btn-sm">Clear</a>
					</div>
				</div>
			</div>
			{{Form::close()}}
		</div><br />
		<div class="table-responsive">
			<table class="table table-bordered table-striped table-condensed">
				<thead>
					<tr>
						<th>
							 Ref#
						</th>
						<th>
							 Application Dt.
						</th>
						<th>
							Engineer Type
						</th>
						<th>
							Trade
						</th>
						<th>
							 Name
						</th>
						<th>
							CID/Work Permit No.
						</th>
						<th>
							 Country
						</th>
						<th>
							 Dzongkhag
						</th>
						<th>
							Mobile#
						</th>
						<th>
							Email
						</th>
						<th>
							Applied for
						</th>
						<th>
							Action
						</th>
					</tr>
				</thead>
				<tbody>
					@forelse($engineerLists as $engineerList)
					<tr>
						<td>
							 {{$engineerList->ReferenceNo}}
						</td>
						<td>
							 {{convertDateToClientFormat($engineerList->ApplicationDate)}}
						</td>
						<td>
							{{$engineerList->EngineerType}}
						</td>
						<td>
							{{$engineerList->Trade}}
						</td>
						<td>
							{{$engineerList->Salutation.' '.$engineerList->EngineerName}} 
						</td>
						<td>
							{{$engineerList->CIDNo}}
						</td>
						<td>
							 {{$engineerList->Country}}
						</td>
						<td>
							 {{$engineerList->Dzongkhag}}
						</td>
						<td>
							 {{$engineerList->MobileNo}}
						</td>
						<td>
							{{$engineerList->Email}}
						</td>
						<td>
							{{$engineerList->AppliedService}}
						</td>
						<td>
							<a href="{{URL::to('engineer/lockapplication/'.$engineerList->Id.'?redirectUrl='.$recordLockReditectUrl)}}" class="btn default btn-xs green-seagreen pickaction"><i class="fa fa-edit"></i> Pick</a>
						</td>
					</tr>
					@empty
					<tr>
						<td class="font-red text-center" colspan="11">No data to display</td>
					</tr>
					@endforelse
				</tbody>
			</table>
		</div>
	</div>
</div>
@stop