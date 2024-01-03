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
							 Application Dt.
						</th>
						<th>
							Initial Dt.
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
							{{convertDateToClientFormat($consultantMyTaskList->InitialDate)}}
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
							@if(Request::path()=="consultant/approveserviceapplicationlist")
									<a href="{{URL::to('consultant/approveserviceapplicationprocess/'.$consultantMyTaskList->Id)}}" class="btn default btn-xs green-seagreen processaction"><i class="fa fa-edit"></i> Process</a>
							@elseif(Request::path()=="consultant/approveserviceapplicationfeepaymentlist")
									<a href="{{URL::to('consultant/approveserviceapplicationpaymentprocess/'.$consultantMyTaskList->Id)}}" class="btn default btn-xs green-seagreen processaction"><i class="fa fa-edit"></i> Process</a>
							@else
								<a href="{{URL::to('consultant/verifyserviceapplicationprocess/'.$consultantMyTaskList->Id)}}" class="btn default btn-xs green-seagreen processaction"><i class="fa fa-edit"></i> Process</a>
							@endif
							<a href="{{URL::to('dropapplication')}}?model=ConsultantModel&id={{$consultantMyTaskList->Id}}&redirectUrl={{$redirectUrl}}" class="btn default btn-xs purple dropaction"><i class="fa fa-times"></i> Drop</a>
						</td>
					</tr>
					@empty
					<tr>
						<td class="font-red text-center" colspan="13">No data to display</td>
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
			<span class="caption-helper font-red-intense">{{$pageTitleHelper}}</span>
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
						<label class="control-label">CDB No.</label>
						<input type="text" name="CDBNoAll" class="form-control" value="{{$CDBNoAll}}">
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label class="control-label">Consultant/Firm</label>
						<select name="CrpConsultantIdAll" class="form-control select2me">
							<option value="">---SELECT ONE---</option>
							@foreach($consultantLists as $consultantList)
								<option value="{{$consultantList->Id}}" @if($consultantIdAll==$consultantList->Id)selected="selected"@endif>{{$consultantList->NameOfFirm}}</option>
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
						Initial Dt.
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
				@forelse($consultantLists as $consultantList)
				<tr>
					<td>
						 {{$consultantList->ReferenceNo}}
					</td>
					<td>
						 {{convertDateToClientFormat($consultantList->ApplicationDate)}}
					</td>
					<td>
						{{convertDateToClientFormat($consultantList->InitialDate)}}
					</td>
					<td>
						{{$consultantList->CDBNo}} 
					</td>
					<td>
						{{$consultantList->OwnershipType}} 
					</td>
					<td>
						{{$consultantList->NameOfFirm}} 
					</td>
					<td>
						 {{$consultantList->Country}}
					</td>
					<td>
						 {{$consultantList->Dzongkhag}}
					</td>
					<td>
						 {{$consultantList->MobileNo}}
					</td>
					<td>
						{{$consultantList->TelephoneNo}}
					</td>
					<td>
						{{$consultantList->Email}}
					</td>
					<td>
						{{$consultantList->ServiceApplied}}
					</td>
					<td>
						<a href="{{URL::to('consultant/lockapplication/'.$consultantList->Id.'?redirectUrl='.$recordLockReditectUrl)}}" class="btn default btn-xs green-seagreen pickaction"><i class="fa fa-edit"></i> Pick</a>
					</td>
				</tr>
				@empty
				<tr>
					<td class="font-red text-center" colspan="13">No data to display</td>
				</tr>
				@endforelse
			</tbody>
		</table>
	</div>
</div>
@stop