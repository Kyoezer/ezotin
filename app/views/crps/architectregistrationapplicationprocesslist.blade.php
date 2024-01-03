@extends('master')
@section('lastcdbno')
<div class="page-toolbar">
	<div id="dashboard-report-range" class="pull-right tooltips btn btn-fit-height grey-cascade" data-placement="top" data-original-title="Last used Consultant CDB No.">
		<span class="thin visible-lg-inline-block">Last used AR No.: <span class="bold">{{lastUsedArchitectNo();}}</span></span>
	</div>
</div>
@stop
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
				<div class="col-md-3">
					<div class="form-group">
						<label class="control-label">Architect Type</label>
						<select name="CmnServiceSectorTypeIdMyTask" class="form-control select2me">
							<option value="">---SELECT ONE---</option>
							@foreach($serviceSectorTypes as $serviceSectorType)
								<option value="{{$serviceSectorType->Id}}" @if($serviceSectorTypeIdMyTask==$serviceSectorType->Id)selected="selected"@endif>{{$serviceSectorType->Name}}</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label class="control-label">Name</label>
						<select name="CrpArchitectIdMyTask" class="form-control select2me">
							<option value="">---SELECT ONE---</option>
							@foreach($architectMyTaskLists as $architectMyTaskList)
								<option value="{{$architectMyTaskList->Id}}" @if($architectIdMyTask==$architectMyTaskList->Id)selected="selected"@endif>{{$architectMyTaskList->ArchitectName}}</option>
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
					<label class="control-label">|</label>
					<div class="btn-set">
						<button type="submit" class="btn blue-hoki btn-sm">Search</button>
						<a href="{{Request::URL()}}" class="btn grey-cascade btn-sm">Clear</a>
					</div>
				</div>
			</div>
		</div>
		{{Form::close()}}
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
							Architect Type
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
							Action
						</th>
					</tr>
				</thead>
				<tbody>
					@forelse($architectMyTaskLists as $architectMyTaskList)
					<tr>
						<td>
							 {{$architectMyTaskList->ReferenceNo}}
						</td>
						<td>
							 {{convertDateToClientFormat($architectMyTaskList->ApplicationDate)}}
						</td>
						<td>
							{{$architectMyTaskList->ArchitectType}}
						</td>
						<td>
							{{$architectMyTaskList->Salutation.' '.$architectMyTaskList->ArchitectName}} 
						</td>
						<td>
							{{$architectMyTaskList->CIDNo}}
						</td>
						<td>
							 {{$architectMyTaskList->Country}}
						</td>
						<td>
							 {{$architectMyTaskList->Dzongkhag}}
						</td>
						<td>
							 {{$architectMyTaskList->MobileNo}}
						</td>
						<td>
							{{$architectMyTaskList->Email}}
						</td>
						<td>
							@if(Route::current()->getUri()=="architect/approveregistration")
								<a href="{{URL::to('architect/approveregistrationprocess/'.$architectMyTaskList->Id)}}" class="btn default btn-xs green-seagreen processaction"><i class="fa fa-edit"></i> Process</a>
							@elseif(Request::path()=="architect/approvefeepayment")
								<a href="{{URL::to('architect/approvepaymentregistrationprocess/'.$architectMyTaskList->Id)}}" class="btn default btn-xs green-seagreen processaction"><i class="fa fa-edit"></i> Process</a>
							@else
								<a href="{{URL::to('architect/verifyregistrationprocess/'.$architectMyTaskList->Id)}}" class="btn default btn-xs green-seagreen processaction"><i class="fa fa-edit"></i> Process</a>
							@endif
							<a href="{{URL::to('dropapplication')}}?model=ArchitectModel&id={{$architectMyTaskList->Id}}&redirectUrl={{Request::path()}}" class="btn default btn-xs purple dropaction"><i class="fa fa-times"></i> Drop</a>
						</td>
					</tr>
					@empty
					<tr>
						<td class="font-red text-center" colspan="10">No data to display</td>
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
				<div class="col-md-3">
					<div class="form-group">
						<label class="control-label">Architect Type</label>
						<select name="CmnServiceSectorTypeIdAll" class="form-control select2me">
							<option value="">---SELECT ONE---</option>
							@foreach($serviceSectorTypes as $serviceSectorType)
								<option value="{{$serviceSectorType->Id}}" @if($serviceSectorTypeIdAll==$serviceSectorType->Id)selected="selected"@endif>{{$serviceSectorType->Name}}</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label class="control-label">Name</label>
						<select name="CrpArchitectIdAll" class="form-control select2me">
							<option value="">---SELECT ONE---</option>
							@foreach($architectLists as $architectList)
								<option value="{{$architectList->Id}}" @if($architectIdAll==$architectList->Id)selected="selected"@endif>{{$architectList->ArchitectName}}</option>
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
					<label class="control-label">|</label>
					<div class="btn-set">
						<button type="submit" class="btn blue-hoki btn-sm">Search</button>
						<a href="{{Request::URL()}}" class="btn grey-cascade btn-sm">Clear</a>
					</div>
				</div>
			</div>
		</div>
		{{Form::close()}}
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
							Architect Type
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
							Action
						</th>
					</tr>
				</thead>
				<tbody>
					@forelse($architectLists as $architectList)
					<tr>
						<td>
							 {{$architectList->ReferenceNo}}
						</td>
						<td>
							 {{convertDateToClientFormat($architectList->ApplicationDate)}}
						</td>
						<td>
							{{$architectList->ArchitectType}}
						</td>
						<td>
							{{$architectList->Salutation.' '.$architectList->ArchitectName}} 
						</td>
						<td>
							{{$architectList->CIDNo}}
						</td>
						<td>
							 {{$architectList->Country}}
						</td>
						<td>
							 {{$architectList->Dzongkhag}}
						</td>
						<td>
							 {{$architectList->MobileNo}}
						</td>
						<td>
							{{$architectList->Email}}
						</td>
						<td>
							@if(Route::current()->getUri()=="architect/approveregistration")
								<a href="{{URL::to('architect/lockapplication/'.$architectList->Id.'?redirectUrl=architect/approveregistration')}}" class="btn default btn-xs green-seagreen pickaction"><i class="fa fa-edit"></i> Pick</a>
							@elseif(Request::path()=="architect/approvefeepayment")
								<a href="{{URL::to('architect/lockapplication/'.$architectList->Id.'?redirectUrl=architect/approvefeepayment')}}" class="btn default btn-xs green-seagreen pickaction"><i class="fa fa-edit"></i> Pick</a>
							@else
								<a href="{{URL::to('architect/lockapplication/'.$architectList->Id.'?redirectUrl=architect/verifyregistration')}}" class="btn default btn-xs green-seagreen pickaction"><i class="fa fa-edit"></i> Pick</a>
							@endif
						</td>
					</tr>
					@empty
					<tr>
						<td class="font-red text-center" colspan="10">No data to display</td>
					</tr>
					@endforelse
				</tbody>
			</table>
		</div>
	</div>
</div>
@stop