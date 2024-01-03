@extends('master')
@section('lastcdbno')
<div class="page-toolbar">
	<div id="dashboard-report-range" class="pull-right tooltips btn btn-fit-height grey-cascade" data-placement="top" data-original-title="Last used Specialized Trade CDB No.">
		<span class="thin visible-lg-inline-block">Last used SP No.: <span class="bold">{{lastUsedSpecializedTradeNo();}}</span></span>
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
				<div class="col-md-4">
					<div class="form-group">
						<label class="control-label">Name</label>
						<select name="CrpSpecializedTradeIdMyTask" class="form-control select2me">
							<option value="">---SELECT ONE---</option>
							@foreach($specializedTradeMyTaskLists as $specializedTradeMyTaskList)
								<option value="{{$specializedTradeMyTaskList->Id}}" @if($specializedTradeIdMyTask==$specializedTradeMyTaskList->Id)selected="selected"@endif>{{$specializedTradeMyTaskList->SpecializedTradeName}}</option>
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
							 Name
						</th>
						<th>
							CID
						</th>
						<th>
							 Dzongkhag
						</th>
						<th>
							Mobile#
						</th>
						<th>
							Telephone#
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

					@forelse($specializedTradeMyTaskLists as $specializedTradeMyTaskList)
					<tr>
						<td>
							 {{$specializedTradeMyTaskList->ReferenceNo}}
						</td>
						<td>
							 {{convertDateToClientFormat($specializedTradeMyTaskList->ApplicationDate)}}
						</td>
						<td>
							{{$specializedTradeMyTaskList->Salutation.' '.$specializedTradeMyTaskList->SpecializedTradeName}} 
						</td>
						<td>
							{{$specializedTradeMyTaskList->CIDNo}}
						</td>
						<td>
							 {{$specializedTradeMyTaskList->Dzongkhag}}
						</td>
						<td>
							 {{$specializedTradeMyTaskList->MobileNo}}
						</td>
						<td>
							 {{$specializedTradeMyTaskList->TelephoneNo}}
						</td>
						<td>
							{{$specializedTradeMyTaskList->Email}}
						</td>
						<td>
							@if(Route::current()->getUri()=="specializedtrade/approveregistration")
								<a href="{{URL::to('specializedtrade/approveregistrationprocess/'.$specializedTradeMyTaskList->Id)}}" class="btn default btn-xs green-seagreen processaction"><i class="fa fa-edit"></i> Process</a>
							@else
								<a href="{{URL::to('specializedtrade/verifyregistrationprocess/'.$specializedTradeMyTaskList->Id)}}" class="btn default btn-xs green-seagreen processaction"><i class="fa fa-edit"></i> Process</a>
							@endif
								<a href="{{URL::to('dropapplication')}}?model=SpecializedTradeModel&id={{$specializedTradeMyTaskList->Id}}&redirectUrl={{Request::path()}}" class="btn default btn-xs purple dropaction"><i class="fa fa-times"></i> Drop</a>
						</td>
					</tr>
					@empty
					<tr>
						<td class="font-red text-center" colspan="9">No data to display</td>
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
			<span class="caption-subject">Pick the applications you wish to work on.<small class="font-red"><i>-Click on the arrow at right hand corner to expand the list</i></small></span>
		</div>
		<div class="tools">
			<a href="javascript:;" class="expand"></a>
		</div>
	</div>
	<div class="portlet-body display-hide">
		<div class="form-body">
			{{ Form::open(array('url' =>Request::URL(),'role'=>'form','method'=>'get')) }}
			<div class="row">
				<div class="col-md-4">
					<div class="form-group">
						<label class="control-label">Name</label>
						<select name="CrpSpecializedTradeIdAll" class="form-control select2me">
							<option value="">---SELECT ONE---</option>
							@foreach($specializedTradeLists as $specializedTradeList)
								<option value="{{$specializedTradeList->Id}}" @if($specializedTradeIdAll==$specializedTradeList->Id)selected="selected"@endif>{{$specializedTradeList->SpecializedTradeName}}</option>
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
							 Name
						</th>
						<th>
							CID No.
						</th>
						<th>
							 Dzongkhag
						</th>
						<th>
							Mobile#
						</th>
						<th>
							Telephone#
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
					@forelse($specializedTradeLists as $specializedTradeList)
					<tr>
						<td>
							 {{$specializedTradeList->ReferenceNo}}
						</td>
						<td>
							 {{convertDateToClientFormat($specializedTradeList->ApplicationDate)}}
						</td>
						<td>
							{{$specializedTradeList->Salutation.' '.$specializedTradeList->SpecializedTradeName}} 
						</td>
						<td>
							{{$specializedTradeList->CIDNo}}
						</td>
						<td>
							 {{$specializedTradeList->Dzongkhag}}
						</td>
						<td>
							 {{$specializedTradeList->MobileNo}}
						</td>
						<td>
							 {{$specializedTradeList->TelephoneNo}}
						</td>
						<td>
							{{$specializedTradeList->Email}}
						</td>
						<td>
							@if(Route::current()->getUri()=="specializedtrade/approveregistration")
								<a href="{{URL::to('specializedtrade/lockapplication/'.$specializedTradeList->Id.'?redirectUrl=specializedtrade/approveregistration')}}" class="btn default btn-xs green-seagreen pickaction"><i class="fa fa-edit"></i> Pick</a>
							@else
								<a href="{{URL::to('specializedtrade/lockapplication/'.$specializedTradeList->Id.'?redirectUrl=specializedtrade/verifyregistration')}}" class="btn default btn-xs green-seagreen pickaction"><i class="fa fa-edit"></i> Pick</a>
							@endif
						</td>
					</tr>
					@empty
					<tr>
						<td class="font-red text-center" colspan="9">No data to display</td>
					</tr>
					@endforelse
				</tbody>
			</table>
		</div>
	</div>
</div>
@stop