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
						<label class="control-label">Contractor/Firm</label>
						<select name="CrpContractorIdMyTask" class="form-control select2me">
							<option value="">---SELECT ONE---</option>
							@foreach($contractorMyTaskLists as $contractorMyTaskList)
								<option value="{{$contractorMyTaskList->Id}}" @if($contractorIdMyTask==$contractorMyTaskList->Id)selected="selected"@endif>{{$contractorMyTaskList->NameOfFirm}}</option>
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
							Services
						</th>
						<th>
							Action
						</th>
					</tr>
				</thead>
				<tbody>
					@forelse($contractorMyTaskLists as $contractorMyTaskList)
					<tr>
						<td>
							 {{$contractorMyTaskList->ReferenceNo}}
						</td>
						<td>
							 {{convertDateToClientFormat($contractorMyTaskList->ApplicationDate)}}
						</td>
						<td>
							{{convertDateToClientFormat($contractorMyTaskList->InitialDate)}}
						</td>
						<td>
							{{$contractorMyTaskList->CDBNo}} 
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
							{{$contractorMyTaskList->ServiceApplied}}
						</td>
						<td>
							@if(Request::path()=="contractor/approveserviceapplicationlist")
									<a href="{{URL::to('contractor/approveserviceapplicationprocess/'.$contractorMyTaskList->Id)}}" class="btn default btn-xs green-seagreen processaction"><i class="fa fa-edit"></i> Process</a>
							@elseif(Request::path()=="contractor/approveserviceapplicationfeepaymentlist")
									<a href="{{URL::to('contractor/approveserviceapplicationpaymentprocess/'.$contractorMyTaskList->Id)}}" class="btn default btn-xs green-seagreen processaction"><i class="fa fa-edit"></i> Process</a>
							@else
								<a href="{{URL::to('contractor/verifyserviceapplicationprocess/'.$contractorMyTaskList->Id)}}" class="btn default btn-xs green-seagreen processaction"><i class="fa fa-edit"></i> Process</a>
							@endif
								<a href="{{URL::to('dropapplication')}}?model=ContractorModel&id={{$contractorMyTaskList->Id}}&redirectUrl={{$redirectUrl}}" class="btn default btn-xs purple dropaction"><i class="fa fa-times"></i> Drop</a>
						</td>
					</tr>
					@empty
					<tr>
						<td class="font-red text-center" colspan="14">No data to display</td>
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
						<label class="control-label">Contractor/Firm</label>
						<select name="CrpContractorIdAll" class="form-control select2me">
							<option value="">---SELECT ONE---</option>
							@foreach($contractorLists as $contractorList)
								<option value="{{$contractorList->Id}}" @if($contractorIdAll==$contractorList->Id)selected="selected"@endif>{{$contractorList->NameOfFirm}}</option>
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
					<th>Initial Dt.</th>
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
						Services
					</th>
					<th>
						Action
					</th>
				</tr>
			</thead>
			<tbody>
				@forelse($contractorLists as $contractorList)
				<tr>
					<td>
						 {{$contractorList->ReferenceNo}}
					</td>
					<td>
						 {{convertDateToClientFormat($contractorList->ApplicationDate)}}
					</td>
					<td>
						{{convertDateToClientFormat($contractorList->InitialDate)}}
					</td>
					<td>
						{{$contractorList->CDBNo}} 
					</td>
					<td>
						{{$contractorList->OwnershipType}} 
					</td>
					<td>
						{{$contractorList->NameOfFirm}} 
					</td>
					<td>
						<span data-toggle="tooltip" title="{{$contractorList->ClassificationName}}">{{$contractorList->ClassificationCode}}</span>
					</td>
					<td>
						 {{$contractorList->Country}}
					</td>
					<td>
						 {{$contractorList->Dzongkhag}}
					</td>
					<td>
						 {{$contractorList->MobileNo}}
					</td>
					<td>
						{{$contractorList->TelephoneNo}}
					</td>
					<td>
						{{$contractorList->Email}}
					</td>
					<td>
						{{$contractorList->ServiceApplied}}
					</td>
					<td>
						<a href="{{URL::to('contractor/lockapplication/'.$contractorList->Id.'?redirectUrl='.$recordLockReditectUrl)}}" class="btn default btn-xs green-seagreen pickaction"><i class="fa fa-edit"></i> Pick</a>
					</td>
				</tr>
				@empty
				<tr>
					<td class="font-red text-center" colspan="14">No data to display</td>
				</tr>
				@endforelse
			</tbody>
		</table>
	</div>
</div>
@stop