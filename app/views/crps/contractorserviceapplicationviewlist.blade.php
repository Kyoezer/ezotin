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
				<div class="col-md-3">
					<div class="form-group">
						<label class="control-label">Contractor/Firm</label>
						<div class="ui-widget">
							<input type="hidden" class="contractor-id" name="ContractorId" value="{{Input::get('ContractorId')}}"/>
							<input type="text" name="Contractor" value="{{Input::get('Contractor')}}" class="form-control contractor-autocomplete"/>
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label class="control-label">Application date between</label>
						<div class="input-group input-large date-picker input-daterange">
							<input type="text" name="FromDateMyTask" class="form-control datepickerfrom" value="{{Input::has('FromDateMyTask')?convertDateTimeToClientFormat(Input::get('FromDateMyTask')):''}}" />
							<span class="input-group-addon">
							to </span>
							<input type="text" name="ToDateMyTask" class="form-control datepickerto" value="{{Input::has('ToDateMyTask')?convertDateTimeToClientFormat(Input::get('ToDateMyTask')):''}}" />
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
						@if(isset($isEditService))
							<td>
								<a href="{{URL::to('contractor/editservicesdetail/'.$contractorMyTaskList->Id)}}" class="btn default btn-xs green-seagreen editaction"><i class="fa fa-edit"></i> Edit</a>
								<a href="{{URL::to('contractor/deleteservice/'.$contractorMyTaskList->Id)}}" class="btn default btn-xs red deleteaction"><i class="fa fa-times"></i> Delete</a>
							</td>
						@else
							<td>
								<a href="{{URL::to('contractor/viewserviceapplicationdetails/'.$contractorMyTaskList->Id)}}" class="btn default btn-xs green-seagreen processaction"><i class="fa fa-edit"></i> Details</a>
							</td>
						@endif
					</tr>
					@empty
					<tr>
						<td class="font-red text-center" colspan="13">No data to display</td>
					</tr>
					@endforelse
				</tbody>
			</table>
		</div>
		<?php pagination($noOfPages,Input::all(),Input::get('page'),$route); ?>
	</div>
</div>
@stop