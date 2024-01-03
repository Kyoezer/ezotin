@extends('master')
@section('content')
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption font-green-seagreen">
			<i class="fa fa-cogs"></i>
			<span class="caption-subject">{{$pageTitle}} - My Task</span>
			<span class="caption-helper font-red-intense">{{$pageTitleHelper}}</span>
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
						<label class="control-label">CDB No.</label>
						<input type="text" name="CDBNoMyTask" class="form-control" value="{{$CDBNoMyTask}}">
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label class="control-label">Consultant/Firm</label>
						<select name="CrpConsultantIdMyTask" class="form-control select2me">
							<option value="">---SELECT ONE---</option>
							@foreach($consultantMyTaskLists as $consultantMyTaskList)
								<option value="{{$consultantMyTaskList->Id}}" @if($consultantIdMyTask==$consultantMyTaskList->Id)selected="selected"@endif>{{$consultantMyTaskList->NameOfFirm}}</option>
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
			<table class="table table-bordered table-striped table-condensed flip-content">
				<thead>
					<tr>
						<th>
							 Ref#
						</th>
						<th>
							 Dt.
						</th>
						<th>
							CDB No.
						</th>
						<th>
							Ownership Type
						</th>
						<th>
							 Name of Firm
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
							Services
						</th>
						<th>
							Action
						</th>
					</tr>
				</thead>
				<tbody>
					@forelse($consultantMyTaskLists as $consultantMyTaskList)
					<tr>
						<td>
							 {{$consultantMyTaskList->ReferenceNo}}
						</td>
						<td>
							 {{convertDateToClientFormat($consultantMyTaskList->ApplicationDate)}}
						</td>
						<td>
							{{$consultantMyTaskList->CDBNo}} 
						</td>
						<td>
							{{$consultantMyTaskList->OwnershipType}} 
						</td>
						<td>
							{{$consultantMyTaskList->NameOfFirm}} 
						</td>
						<td>
							 {{$consultantMyTaskList->Country}}
						</td>
						<td>
							 {{$consultantMyTaskList->Dzongkhag}}
						</td>
						<td>
							 {{$consultantMyTaskList->MobileNo}}
						</td>
						<td>
							{{$consultantMyTaskList->TelephoneNo}}
						</td>
						<td>
							{{$consultantMyTaskList->Email}}
						</td>
						<td>
							{{$consultantMyTaskList->ServiceApplied}}
						</td>
						<td>
							<a href="{{URL::to('consultant/viewserviceapplicationdetails/'.$consultantMyTaskList->Id)}}" class="btn default btn-xs green-seagreen processaction"><i class="fa fa-edit"></i> Details</a>
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
@stop