@extends('master')
@section('pagescripts')
{{ HTML::script('assets/global/scripts/consultant.js') }}
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
			{{ Form::open(array('url' => 'consultant/blacklistandderegister','role'=>'form'))}}
			<input type="hidden" name="ConsultantReference" value="" class="deregisterid"/>
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
<div id="blacklist" class="modal fade" role="dialog" aria-labelledby="blacklist" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h4 class="modal-title font-green-seagreen">Are you sure you want to Revoke/Suspend/Debarred this contractor?</h4>
			</div>
			{{ Form::open(array('url' => 'consultant/blacklistandderegister','role'=>'form'))}}
			<input type="hidden" name="ConsultantReference" value="" class="blacklistId"/>
			<input type="hidden" name="CmnApplicationRegistrationStatusId" value="{{CONST_CMN_APPLICATIONREGISTRATIONSTATUS_BLACKLISTED}}">
			<div class="modal-body">
				<h4 class="displaycontractorname"></h4>
				<h4 class="displaycontractorcdbno"></h4>
				<div class="form-group">
					<label class="control-label">Date</label>
					<div class="input-icon right input-medium">
						<i class="fa fa-calendar"></i>
						<input type="text" name="BlacklistedDate" class="form-control datepicker input-sm required input-medium" value="{{date('d-m-Y')}}" readonly="readonly">
					</div>
				</div>
				<div class="form-group">
					<label>Remarks</label>
					<textarea name="BlacklistedRemarks" class="form-control required" rows="3"></textarea>
				</div>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn green">Blacklist</button>
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
				<h4 class="modal-title font-green-seagreen">Are you sure you want to re-register this consultant?</h4>
			</div>
			{{ Form::open(array('url' => 'consultant/blacklistandderegister','role'=>'form'))}}
			<input type="hidden" name="ConsultantReference" value="" class="reregisterId"/>
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
			<span class="caption-helper">The consultants listed below are all <span class="font-red-thunderbird bold">{{$captionHelper}}</span> consultants.</span>
		</div>
	</div>
	<div class="portlet-body flip-scroll">
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
                        <label class="control-label">Consultant/Firm</label>
                        <div class="ui-widget">
                            <input type="hidden" class="consultant-id" name="CrpConsultantId" value="{{Input::get('CrpConsultantId')}}"/>
                            <input type="text" name="Consultant" value="{{Input::get('Consultant')}}" class="form-control consultant-autocomplete"/>
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
		<table class="table table-bordered table-striped table-condensed flip-content">
			<thead class="flip-content">
				<tr>
					<th>
						CDB No.
					</th>
					<th class="">
						 Name of Firm
					</th>
					<th class="">
						 Country
					</th>
					<th class="">
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
				@forelse($consultantLists as $consultantList)
				<tr>
					<td>
						<input type="hidden" value="{{$consultantList->Id}}" class="consultantid" />
						<input type="hidden" value="{{$consultantList->NameOfFirm}}" class="consultantname" />
						<input type="hidden" value="{{$consultantList->CDBNo}}" class="consultantcdbno" />
						 {{$consultantList->CDBNo}}
					</td>
					<td>
						{{$consultantList->NameOfFirm}} 
					</td>
					<td class="">
						 {{$consultantList->Country}}
					</td>
					<td class="">
						 {{$consultantList->Dzongkhag}}
					</td>
					<td class="">
						 {{$consultantList->MobileNo}}
					</td>
					<td>
						{{$consultantList->TelephoneNo}}
					</td>
					<td>
						{{$consultantList->Email}}
					</td>
					<td>
						@if((int)$reRegistration==0)
							@if((int)$type==1)
							<a href="#deregister" data-toggle="modal" class="deregisterconsultant">Deregister</a>
							@else
							<a href="#blacklist" data-toggle="modal" class="blacklistconsultant">Blacklist</a>
							@endif
						@else
							<a href="#reregister" data-toggle="modal" class="reregistrationconsultant">Re-Registration</a>
						@endif
					</td>
				</tr>
				@empty
				<tr>
					<td class="font-red text-center" colspan="8">No data to display</td>
				</tr>
				@endforelse
			</tbody>
		</table>
	</div>
</div>
@stop