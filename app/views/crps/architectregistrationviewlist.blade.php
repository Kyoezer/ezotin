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
						<div class="input-group input-large">
							<input type="text" name="FromDateMyTask" class="form-control" value="{{$fromDateMyTask}}" />
							<span class="input-group-addon">
							to </span>
							<input type="text" name="ToDateMyTask" class="form-control datepicker" value="{{$toDateMyTask}}" />
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
							<a href="{{URL::to('architect/viewregistrationprocess/'.$architectMyTaskList->Id)}}" class="btn default btn-xs green-seagreen processaction"><i class="fa fa-edit"></i> Details</a>
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