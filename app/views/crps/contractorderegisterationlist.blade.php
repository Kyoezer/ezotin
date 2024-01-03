@extends('master')
@section('pagescripts')
{{ HTML::script('assets/global/scripts/contractor.js?ver='.randomString()) }}
@stop
@section('content')
@if((int)$reRegistration==0)
<div id="deregister" class="modal fade" role="dialog" aria-labelledby="deregister" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h4 class="modal-title font-green-seagreen">Are you sure you want to deregister this contractor?</h4>
			</div>
			{{ Form::open(array('url' => 'contractor/blacklistandderegister','role'=>'form'))}}
			<input type="hidden" name="ContractorReference" value="" class="deregisterid"/>
			<input type="hidden" name="CmnApplicationRegistrationStatusId" value="{{CONST_CMN_APPLICATIONREGISTRATIONSTATUS_DEREGISTERED}}">
			<div class="modal-body">
				<h4 class="displaycontractorname"></h4>
				<h4 class="displaycontractorcdbno"></h4>
				<div class="form-group">
					<label class="control-label">Date</label>
					<div class="input-icon right input-medium">
						<i class="fa fa-calendar"></i>
						<input type="text" name="DeRegisteredDate" value="{{date('d-m-Y')}}" class="form-control datepicker input-sm required input-medium" readonly="readonly">
					</div>
				</div>
				<div class="form-group">
					<label>Remarks</label>
					<textarea name="DeregisteredRemarks" class="form-control required" rows="3"></textarea>
				</div>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn green">Deregister</button>
				<button class="btn red" data-dismiss="modal" aria-hidden="true">Cancel</button>
			</div>
			{{Form::close()}}
		</div>
	</div>
</div>
<div id="surrender" class="modal fade" role="dialog" aria-labelledby="deregister" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h4 class="modal-title font-green-seagreen">Are you sure you want to surrender this contractor's certificate?</h4>
			</div>
			{{ Form::open(array('url' => 'contractor/surrender','role'=>'form'))}}
			<input type="hidden" name="ContractorReference" value="" class="surrenderid"/>
			<input type="hidden" name="CmnApplicationRegistrationStatusId" value="{{CONST_CMN_APPLICATIONREGISTRATIONSTATUS_SURRENDERED}}">
			<div class="modal-body">
				<h4 class="contractorname"></h4>
				<h4 class="contractorcdbno"></h4>
				<div class="form-group">
					<label class="control-label">Date</label>
					<div class="input-icon right input-medium">
						<i class="fa fa-calendar"></i>
						<input type="text" name="SurrenderedDate" value="{{date('d-m-Y')}}" class="form-control datepicker input-sm required input-medium" readonly="readonly">
					</div>
				</div>
				<div class="form-group">
					<label>Remarks</label>
					<textarea name="SurrenderedRemarks" class="form-control required" rows="3"></textarea>
				</div>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn green">Surrender Certificate</button>
				<button class="btn red" data-dismiss="modal" aria-hidden="true">Cancel</button>
			</div>
			{{Form::close()}}
		</div>
	</div>
</div>
<div id="revoke" class="modal fade" role="dialog" aria-labelledby="revoke" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h4 class="modal-title font-green-seagreen">Are you sure you want to Revoke/Suspend/Debarred this contractor?</h4>
			</div>
			{{ Form::open(array('url' => 'contractor/blacklistandderegister','role'=>'form'))}}
			<input type="hidden" name="ContractorReference" value="" class="revokeId"/>
			{{--<input type="hidden" name="CmnApplicationRegistrationStatusId" value="{{CONST_CMN_APPLICATIONREGISTRATIONSTATUS_REVOKED}}">--}}
			<div class="modal-body">
				<h4 class="displaycontractorname"></h4>
				<h4 class="displaycontractorcdbno"></h4>
				<div class="form-group">
					<label class="control-label">Date</label>
					<div class="input-icon right input-medium">
						<i class="fa fa-calendar"></i>
						<input type="text" name="RevokedDate" class="form-control datepicker input-sm required input-medium" value="{{date('d-m-Y')}}" readonly="readonly">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label">Status</label>
					<select name="CmnApplicationRegistrationStatusId" class="form-control required input-medium">
						<option value="">---SELECT---</option>
						@foreach($statuses as $status)
							<option value="{{$status->Id}}">{{$status->Name}}</option>
						@endforeach
					</select>
				</div>
				<div class="form-group">
					<label>Remarks</label>
					<textarea name="RevokedRemarks" class="form-control required" rows="3"></textarea>
				</div>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn green">Revoke/Suspend/Debar</button>
				<button class="btn red" data-dismiss="modal" aria-hidden="true">Cancel</button>
			</div>
			{{Form::close()}}
		</div>
	</div>
</div>
@else
<div id="reregister" class="modal fade" role="dialog" aria-labelledby="reregister" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h4 class="modal-title font-green-seagreen">Are you sure you want to re-register this contractor?</h4>
			</div>
			{{ Form::open(array('url' => 'contractor/blacklistandderegister','role'=>'form'))}}
			<input type="hidden" name="ContractorReference" value="" class="reregisterId"/>
			<input type="hidden" name="CmnApplicationRegistrationStatusId" value="{{CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED}}">
			<div class="modal-body">
				<h4 class="displaycontractorname"></h4>
				<h4 class="displaycontractorcdbno"></h4>
				<div class="form-group">
					<label class="control-label">Date</label>
					<div class="input-icon right input-medium">
						<i class="fa fa-calendar"></i>
						<input type="text" name="ReRegistrationDate" class="form-control datepicker input-sm required input-medium" readonly="readonly" value="{{date('d-m-Y')}}">
					</div>
				</div>
				<div class="form-group">
					<label>Remarks</label>
					<textarea name="ReRegistrationRemarks" class="form-control required" rows="3"></textarea>
				</div>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn green">Re-Register</button>
				<button class="btn red" data-dismiss="modal" aria-hidden="true">Cancel</button>
			</div>
			{{Form::close()}}
		</div>
	</div>
</div>
@endif
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-cogs"></i>
			<span class="caption-subject">{{$captionSubject}}</span>
			<span class="caption-helper">The contractor listed below are all <span class="font-red-thunderbird bold">{{$captionHelper}}</span> contractors.</span>
		</div>
	</div>
	<div class="portlet-body flip-scroll" style="overflow-x: scroll;">
		<div class="form-body">
			{{ Form::open(array('url' =>Request::URL(),'role'=>'form','method'=>'get')) }}
			<div class="row">
				<div class="col-md-2">
					<div class="form-group">
						<label class="control-label">CDB No.</label>
						<input type="text" name="CDBNo" class="form-control" value="{{$CDBNo}}"/>
					</div>
				</div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">Contractor/Firm</label>
                        <div class="ui-widget">
                            <input type="hidden" class="contractor-id" name="ContractorId" value="{{Input::get('ContractorId')}}"/>
                            <input type="text" name="Contractor" value="{{Input::get('Contractor')}}" class="form-control contractor-autocomplete"/>
                        </div>
                    </div>
                </div>
				<div class="col-md-2">
					<div class="form-group">
						<label class="control-label">Trade License No.</label>
						<input type="text" name="TradeLicenseNo" class="form-control" value="{{Input::get('TradeLicenseNo')}}">
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label class="control-label">Approved Between</label>
						<div class="input-group input-large date-picker input-daterange">
							<input type="text" name="FromDate" class="form-control date-picker" value="{{Input::get('FromDate')}}" />
						<span class="input-group-addon">
						to </span>
							<input type="text" name="ToDate" class="form-control date-picker" value="{{Input::get('ToDate')}}" />
						</div>
					</div>
				</div>
				<div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">No. of Rows</label>
                        {{Form::select('Limit',array(10=>10,20=>20,30=>30,'All'=>'All'),Input::has('Limit')?Input::get('Limit'):20,array('class'=>'form-control'))}}
                    </div>
                </div>
				<div class="col-md-2">
					<label class="control-label">&nbsp;</label>
					<div class="btn-set">
						<button type="submit" class="btn blue-hoki btn-sm">Search</button>
						<a href="{{Request::URL()}}" class="btn grey-cascade btn-sm">Clear</a>
					</div>
				</div>
			</div>
			{{FOrm::close()}}
		</div>
		<div class="note note-info">Scroll right to view complete information</div>
		<table class="table table-bordered table-striped table-condensed flip-content">
			<thead class="flip-content">
				<tr>
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
						Action
					</th>
				</tr>
			</thead>
			<tbody>
				@forelse($contractorLists as $contractorList)
				<tr>
					<td>
						<input type="hidden" value="{{$contractorList->Id}}" class="contractorid" />
						<input type="hidden" value="{{$contractorList->NameOfFirm}}" class="contractorname" />
						<input type="hidden" value="{{$contractorList->CDBNo}}" class="contractorcdbno" />
						 {{$contractorList->CDBNo}}
					</td>
					<td>
						{{$contractorList->OwnershipType}} 
					</td>
					<td>
						{{$contractorList->NameOfFirm}} 
					</td>
					<td>
						{{$contractorList->ClassificationCode}} 
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
						@if((int)$reRegistration==0)
							@if((int)$type==1)
							<a href="#deregister" data-toggle="modal" class="deregistercontractor">Deregister</a>
							@else
								@if((int)$type == 3)
									<a href="#blacklist" data-toggle="modal" class="blacklistcontractor">Blacklist</a>
								@else
									@if((int)$type == 4)
										<a href="#surrender" data-toggle="modal" class="surrendercertificate">Surrender Certificate</a>
									@else
										<a href="#revoke" data-toggle="modal" class="revokecontractor">Revoke/Suspend/Debar</a>
									@endif
								@endif
							@endif
						@else
							<a href="#reregister" data-toggle="modal" class="reregistrationcontractor">Re-Registration</a>
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
@stop