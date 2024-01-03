@extends('master')
@section('lastcdbno')
<div class="page-toolbar">
	<div id="dashboard-report-range" class="pull-right tooltips btn btn-fit-height grey-cascade" data-placement="top" data-original-title="Last used Contractor CDB No.">
		<span class="thin visible-lg-inline-block">Last used CDB No.: <span class="bold">{{lastUsedContractorCDBNo();}}</span></span>
	</div>
</div>
@stop
@section('content')
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption font-green-seagreen">
			<i class="fa fa-cogs"></i>
			<span class="caption-subject">{{$pageTitle}} (My Task)<small class="font-red"><i>-Click on the process button to view the details of registration</i></small></span>
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
						<label class="control-label">Contractor/Firm</label>
						<select name="CrpContractorIdMyTask" class="form-control select2me">
							<option value="">---SELECT ONE---</option>
							@foreach($contractorLists as $contractorMyTaskList)
								<option value="{{$contractorMyTaskList->Id}}" @if($contractorIdMyTask==$contractorMyTaskList->Id)selected="selected"@endif>{{$contractorMyTaskList->NameOfFirm}}</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label class="control-label">Application Date</label>
						<div class="input-group input-large">
							<input type="text" readonly class="form-control" value="01-06-2016" />
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
		<table class="table table-bordered table-striped table-condensed flip-content">
			<thead class="flip-content">
				<tr>
					<th>
						 Ref#
					</th>
					<th>
						 Application Dt.
					</th>
					<th>
						Ownership Type
					</th>
					<th>
						Name of Firm
					</th>
					<th>
						Class
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
						Tel#
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
				@forelse($contractorLists as $contractorMyTaskList)
				<tr>
					<td>
						 {{$contractorMyTaskList->ReferenceNo}}
					</td>
					<td>
						 {{convertDateToClientFormat($contractorMyTaskList->ApplicationDate)}}
					</td>
					<td>
						{{$contractorMyTaskList->OwnershipType}} 
					</td>
					<td>
						{{$contractorMyTaskList->NameOfFirm}} 
					</td>
					<td>
						<span data-toggle="tooltip" title="{{$contractorMyTaskList->ClassificationName}}">{{$contractorMyTaskList->ClassificationCode}}</span>
					</td>
					<td>
						 {{$contractorMyTaskList->Country}}
					</td>
					<td>
						 {{$contractorMyTaskList->Dzongkhag}}
					</td>
					<td>
						 {{$contractorMyTaskList->MobileNo}}
					</td>
					<td>
						{{$contractorMyTaskList->TelephoneNo}}
					</td>
					<td>
						{{$contractorMyTaskList->Email}}
					</td>
					<td>
						<a href="{{URL::to('contractor/viewregistrationprocess/'.$contractorMyTaskList->Id)}}" class="btn default btn-xs green-seagreen"><i class="fa fa-edit"></i> Details</a>
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
@stop