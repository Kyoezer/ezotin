@extends('master')
@section('pagescripts')
{{ HTML::script('assets/global/scripts/contractor.js') }}
@stop
@section('lastcdbno')
<div class="page-toolbar">
	<div id="dashboard-report-range" class="pull-right tooltips btn btn-fit-height grey-cascade" data-placement="top" data-original-title="Last used Contractor CDB No.">
		<span class="thin visible-lg-inline-block">Last used CDB No.: <span class="bold">@if($generalInformation[0]->Country != "Bhutan"){{"NB"}}@endif{{(int)lastUsedContractorCDBNo($generalInformation[0]->Country)+1}}</span></span>
	</div>
</div>
@stop
@section('content')
<div id="contractorcommentadverserecordedit" class="modal fade" role="dialog" aria-labelledby="contractorcommentadverserecordedit" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			{{ Form::open(array('url' => 'contractor/meditcommentadverserecords','role'=>'form'))}}
			<input type="hidden" name="Id" class="contractorcommentadverserecordid" />
			<input type="hidden" name="CrpContractorFinalId" value="{{$generalInformation[0]->Id}}" />
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h3 class="modal-title font-green-seagreen">Edit</h3>
			</div>
			<div class="modal-body">
				<div class="form-body">
					<div class="form-group">
						<label for="Date">Date</label>
						<div class="input-icon right input-medium">
							<i class="fa fa-calendar"></i>
							<input type="text" name="Date" autocomplete="off" class="form-control datepicker required commentadverserecorddate" placeholder="">
						</div>
					</div>
					<div class="form-group">
						<label class="labeladversecomment">Remarks</label>
						<textarea name="Remarks" class="form-control required commentadverserecord" rows="3"></textarea>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn green">Update</button>
				<button class="btn red" data-dismiss="modal" aria-hidden="true">Cancel</button>
			</div>
			{{Form::close()}}
		</div>
	</div>
</div>
<div id="addcommentadverserecord" class="modal fade" role="dialog" aria-labelledby="addcommentadverserecord" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			{{ Form::open(array('url' => 'contractor/mcommentadverserecord','role'=>'form'))}}
			<input type="hidden" name="Id" class="contractorcommentadverserecordid" />
			<input type="hidden" name="CrpContractorFinalId" value="{{$generalInformation[0]->Id}}" />
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h3 class="modal-title font-green-seagreen">Add Info</h3>
			</div>
			<div class="modal-body">
				<div class="form-body">
					<div class="form-body">
						<div class="form-group">
							<label>Type</label>
							<select name="Type" class="form-control input-medium required">
								<option value="">---SELECT ONE---</option>
								<option value="1" @if((int)Input::old('Type')==1)selected="selected"@endif>Comment</option>
								<option value="2" @if((int)Input::old('Type')==2)selected="selected"@endif>Adverse Record</option>
								<option value="4" @if((int)Input::old('Type')==4)selected="selected"@endif>Monitoring Record</option>
							</select>
						</div>
						<div class="form-group">
							<input type="hidden" name="CrpContractorFinalId" value="{{$generalInformation[0]->Id}}" />
							<label for="Date">Date</label>
							<div class="input-icon right input-medium">
								<i class="fa fa-calendar"></i>
								<input type="text"  autocomplete="off" name="Date" class="form-control datepicker required" value="{{Input::old('Date')}}" placeholder="" readonly>
							</div>
						</div>
						<div class="form-group">
							<label>Remarks</label>
							<textarea name="Remarks" class="form-control required" rows="3">{{Input::old('Remarks')}}</textarea>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn green">Update</button>
				<button class="btn red" data-dismiss="modal" aria-hidden="true">Cancel</button>
			</div>
			{{Form::close()}}
		</div>
	</div>
</div>


<div id="addmonitoringcomment" class="modal fade" role="dialog" aria-labelledby="addmonitoringcomment" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			{{ Form::open(array('url' => 'contractor/mcommentadverserecord','role'=>'form'))}}
			<input type="hidden" name="Id" class="contractorcommentadverserecordid" />
			<input type="hidden" name="CrpContractorFinalId" value="{{$generalInformation[0]->Id}}" />
			<input type="hidden" name="Type" value="4" />
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h3 class="modal-title font-green-seagreen">Add Info</h3>
			</div>
			<div class="modal-body">
				<div class="form-body">
					<div class="form-body">
						<div class="form-group">
							<input type="hidden" name="CrpContractorFinalId" value="{{$generalInformation[0]->Id}}" />
							<label for="Date">Date</label>
							<div class="input-icon right input-medium">
								<i class="fa fa-calendar"></i>
								<input type="text" name="Date" class="form-control datepicker required" value="{{Input::old('Date')}}" placeholder="" readonly>
							</div>
						</div>
						<div class="form-group">
							<label>Remarks</label>
							<textarea name="Remarks" class="form-control required" rows="3">{{Input::old('Remarks')}}</textarea>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn green">Update</button>
				<button class="btn red" data-dismiss="modal" aria-hidden="true">Cancel</button>
			</div>
			{{Form::close()}}
		</div>
	</div>
</div>


<div id="editgeneralinfo" class="modal fade" role="dialog" aria-labelledby="addcommentadverserecord" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			{{ Form::open(array('url' => 'contractor/meditbasicinfo','role'=>'form'))}}
			<input type="hidden" name="CrpContractorFinalId" value="{{$generalInformation[0]->Id}}" />
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h3 class="modal-title font-green-seagreen">Edit Info</h3>
			</div>
			<div class="modal-body">
				<div class="form-body">
					<div class="form-body">
						<span class="font-blue-madison bold warning">&nbsp;&nbsp;&nbsp;&nbsp;Registered Address</span> <br>
						<div class="col-md-3">
							<div class="form-group">
								<input type="hidden" name="CrpContractorFinalId" value="{{$generalInformation[0]->Id}}" />
								<label for="Village" class="control-label">Village</label>
								<input type="text" id="Village" name="Village" class="form-control input-sm" value="{{$generalInformation[0]->Village}}" >
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="Gewog" class="control-label">Gewog</label>
								<input type="text" name="Gewog" id="Gewog" class="form-control input-sm" value="{{$generalInformation[0]->Gewog}}" >
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="RegisteredAddress" class="control-label">Address</label>
								<input type="text" name="RegisteredAddress" id="RegisteredAddress" class="form-control input-sm" value="{{$generalInformation[0]->RegisteredAddress}}" >
							</div>
						</div>
						<div class="clearfix"></div>
						<span class="font-blue-madison bold warning">&nbsp;&nbsp;&nbsp;&nbsp;Correspondence Address</span> <br>
						<div class="col-md-3">
							<div class="form-group">
								<label for="Village" class="control-label">Address</label>
								<input type="text" id="Address" name="Address" class="form-control input-sm required" value="{{$generalInformation[0]->Address}}" >
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="Gewog" class="control-label">Telephone No.</label>
								<input type="text" name="TelephoneNo" id="TelephoneNo" class="form-control input-sm" value="{{$generalInformation[0]->TelephoneNo}}" >
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="MobileNo" class="control-label">Mobile No</label>
								<input type="text" name="MobileNo" id="MobileNo" class="form-control input-sm" value="{{$generalInformation[0]->MobileNo}}" >
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="FaxNo" class="control-label">Fax No</label>
								<input type="text" name="FaxNo" id="FaxNo" class="form-control input-sm" value="{{$generalInformation[0]->FaxNo}}" >
							</div>
						</div>

					</div>

				</div>
			</div>
			<div class="clearfix"></div>
			<div class="modal-footer">
				<button type="submit" class="btn green">Update</button>
				<button class="btn red" data-dismiss="modal" aria-hidden="true">Cancel</button>
			</div>
			{{Form::close()}}
		</div>
	</div>
</div>
<div id="sendback" class="modal fade" role="dialog" aria-labelledby="sendback" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			{{Form::open(array('url'=>'sendapplicationback','role'=>'form'))}}
			<input type="hidden" name="Id" value="{{$generalInformation[0]->Id}}" />
			<input type="hidden" name="Model" value="ContractorModel"/>
			<input type="hidden" name="Status" value="{{CONST_CMN_APPLICATIONREGISTRATIONSTATUS_VERIFIED}}"/>
			<input type="hidden" name="RedirectUrl" value="contractor/approvefeepayment"/>
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h3 class="modal-title font-green-seagreen">Send Application back</h3>
			</div>
			<div class="modal-body">
				<p class="bold">Are you sure you want to send this application back?</p>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn green">Send Back</button>
				<button class="btn red" data-dismiss="modal" aria-hidden="true">Cancel</button>
			</div>
			{{Form::close()}}
		</div>
	</div>
</div>
<div id="downgrade-modal" class="modal fade" role="dialog" aria-labelledby="sendback" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h3 class="modal-title font-green-seagreen">Downgrade this contractor</h3>
			</div>
			<div class="modal-body">
				<table class="table table-bordered table-striped table-condensed flip-content">
					<thead class="">
					<tr>
						<th width="5%" class="table-checkbox"></th>
						<th width="40%">
							Category
						</th>
						<th>
							Class
						</th>
					</tr>
					</thead>
					<tbody>
						<tr>
							<td><input type="checkbox" checked="checked"/></td>
							<td>W2</td>
							<td>
								<select class="form-control input-sm">
									<option value=""></option>
									<option value="">Large</option>
									<option value="" selected="selected">Medium</option>
									<option value="">Small</option>
								</select>
							</td>
						</tr>
						<tr>
							<td><input type="checkbox" /></td>
							<td>W1</td>
							<td>
								<select class="form-control input-sm" disabled="disabled">
									<option value="">---SELECT---</option>
									<option value="">Registered</option>
								</select>
							</td>
						</tr>
						<tr>
							<td><input type="checkbox" checked="checked"/></td>
							<td>W3</td>
							<td>
								<select class="form-control input-sm">
									<option value=""></option>
									<option value="">Large</option>
									<option value="" selected="selected">Medium</option>
									<option value="">Small</option>
								</select>
							</td>
						</tr>
						<tr>
							<td><input type="checkbox" checked="checked"/></td>
							<td>W4</td>
							<td>
								<select class="form-control input-sm">
									<option value=""></option>
									<option value="" selected="selected">Large</option>
									<option value="">Medium</option>
									<option value="">Small</option>
								</select>
							</td>
						</tr>
					</tbody>
				</table>
				<div class="form-group">
					<label class="control-label">Reason for downgrade</label>
					<textarea class="form-control"></textarea>
				</div>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn green">Update</button>
				<button class="btn red" data-dismiss="modal" aria-hidden="true">Cancel</button>
			</div>
			{{Form::close()}}
		</div>
	</div>
</div>
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption font-green-seagreen">
			<i class="fa fa-cogs"></i>
			<span class="caption-subject">Registration Information</span> 
		</div>
		@if((int)$userContractor==1)
		<div class="actions">
			<a href="{{URL::to('contractor/viewprintdetails/'.$contractorId)}}" class="btn btn-sm blue-madison" target="_blank"><i class="fa fa-print"></i> Print Information</a>
			<a href="{{URL::to('contractor/certificate/'.$contractorId)}}" class="btn btn-sm green-seagreen" target="_blank"><i class="fa fa-print"></i> Print Certificate</a>
		</div>
		@endif
	</div>
	<div class="portlet-body">
		<div class="row">
			<div class="col-md-6 table-responsive">
				<table class="table table-condensed">
					<tbody>
						<tr>
							<td colspan="2" class="font-blue-madison bold warning">General Info</td>
						</tr>
						<tr>
							<td><strong>CDB No.</strong></td>
							<td>{{{$generalInformation[0]->CDBNo}}}</td>
						</tr>
						<tr>
							<td><strong>Ownership Type</strong></td>
							<td>{{{$generalInformation[0]->OwnershipType}}}</td>
						</tr>
						<tr>
							<td><strong>Company Name</strong></td>
							<td>{{{$generalInformation[0]->NameOfFirm}}}</td>
						</tr>
						<tr>
							<td><strong>Trade License No.</strong></td>
							<td>{{{$generalInformation[0]->TradeLicenseNo}}}</td>
						</tr>
						<tr>
							<td><strong>TPN</strong></td>
							<td>{{{$generalInformation[0]->TPN}}}</td>
						</tr>
						<tr>
							<td><strong>Country</strong></td>
							<td>{{{$generalInformation[0]->Country}}}</td>
						</tr>
						<tr>
							<td><strong>Application Date</strong></td>
							<td>{{convertDateToClientFormat($generalInformation[0]->ApplicationDate)}}</td>
						</tr>
						<tr>
							<td><strong>Registration Expiry Date</strong></td>
							<td>{{convertDateToClientFormat($generalInformation[0]->RegistrationExpiryDate)}}</td>
						</tr>
						<tr>
							<td colspan="2" class="font-blue-madison bold warning">Registered Address</td>
						</tr>
						<tr>
							<td><strong>Dzongkhag</strong></td>
							<td>{{$generalInformation[0]->Dzongkhag}}</td>
						</tr>
						<tr>
							<td><strong>Village</strong></td>
							<td>{{$generalInformation[0]->Village}}</td>
						</tr>
						<tr>
							<td><strong>Gewog</strong></td>
							<td>{{$generalInformation[0]->Gewog}}</td>
						</tr>
						<tr>
							<td><strong>Address</strong></td>
							<td>{{$generalInformation[0]->RegisteredAddress}}</td>
						</tr>
						@if($generalInformation[0]->CmnApplicationRegistrationStatusId != CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED)
							<tr>
								<td><strong>Status</strong></td>
								<td>{{$generalInformation[0]->Status}}</td>
							</tr>
							@if($generalInformation[0]->CmnApplicationRegistrationStatusId == CONST_CMN_APPLICATIONREGISTRATIONSTATUS_DEREGISTERED)
								<tr>
									<td><strong>Deregistered Date</strong></td>
									<td>{{$generalInformation[0]->DeregisteredDate}}</td>
								</tr>
								<tr>
									<td><strong>Reason</strong></td>
									<td>{{$generalInformation[0]->DeregisteredRemarks}}</td>
								</tr>
							@endif
							@if(($generalInformation[0]->CmnApplicationRegistrationStatusId == CONST_CMN_APPLICATIONREGISTRATIONSTATUS_BLACKLISTED )|| ($generalInformation[0]->CmnApplicationRegistrationStatusId == CONST_CMN_APPLICATIONREGISTRATIONSTATUS_REVOKED )|| ($generalInformation[0]->CmnApplicationRegistrationStatusId == CONST_CMN_APPLICATIONREGISTRATIONSTATUS_SUSPENDED )|| ($generalInformation[0]->CmnApplicationRegistrationStatusId == CONST_CMN_APPLICATIONREGISTRATIONSTATUS_DEBARRED))
								<tr>
									<td><strong>{{$generalInformation[0]->Status}} Date</strong></td>
									<td>{{$generalInformation[0]->RevokedDate}}</td>
								</tr>
								<tr>
									<td><strong>Reason</strong></td>
									<td>{{$generalInformation[0]->RevokedRemarks}}</td>
								</tr>
							@endif
							@if(($generalInformation[0]->CmnApplicationRegistrationStatusId == CONST_CMN_APPLICATIONREGISTRATIONSTATUS_SURRENDERED))
								<tr>
									<td><strong>{{$generalInformation[0]->Status}} Date</strong></td>
									<td>{{$generalInformation[0]->SurrenderedDate}}</td>
								</tr>
								<tr>
									<td><strong>Reason</strong></td>
									<td>{{$generalInformation[0]->SurrenderedRemarks}}</td>
								</tr>
							@endif
						@endif
					</tbody>
				</table>
			</div>
			<div class="col-md-6 table-responsive">
				<table class="table table-condensed">
					<tbody>
						<tr>
							<td colspan="2" class="font-blue-madison bold warning">Establishment Address</td>
						</tr>
						<tr>
							<td><strong>Dzongkhag (Displayed in certificate)</strong></td>
							<td>{{{$generalInformation[0]->RegisteredDzongkhag}}}</td>
						</tr>
						<tr>
							<td><strong>Address</strong></td>
							<td>{{{$generalInformation[0]->Address}}}</td>
						</tr>
						<tr>
							<td><strong>Email</strong></td>
							<td>{{{$generalInformation[0]->Email}}}</td>
						</tr>
						<tr>
							<td><strong>Telephone No.</strong></td>
							<td>{{{$generalInformation[0]->TelephoneNo}}}</td>
						</tr>
						<tr>
							<td><strong>Mobile No.</strong></td>
							<td>{{{$generalInformation[0]->MobileNo}}}</td>
						</tr>
						<tr>
							<td><strong>Fax No.</strong></td>
							<td>{{{$generalInformation[0]->FaxNo}}}</td>
						</tr>
						@if(isset($incorporationOwnershipTypes) && (int)$generalInformation[0]->OwnershipTypeReferenceNo!=14001)
							<tr>
								<td colspan="2" class="font-blue-madison bold warning">Attachments</td>
							</tr>
							@foreach($incorporationOwnershipTypes as $incorporationOwnershipType)
							<tr>
								<td colspan="2">
									<i class="fa fa-check"></i> <a href="{{URL::to($incorporationOwnershipType->DocumentPath)}}" target="_blank">{{$incorporationOwnershipType->DocumentName}}</a>
								</td>
							</tr>
							@endforeach
						@endif
						@if(isset($contractorAttachments))
							@if(count($contractorAttachments)>0)
								@foreach($contractorAttachments as $contractorAttachment)
								<tr>
									<td colspan="2">
										<i class="fa fa-check"></i> <a href="{{URL::to($contractorAttachment->DocumentPath)}}" target="_blank">{{($contractorAttachment->DocumentName!="")?$contractorAttachment->DocumentName:"Attachment"}}</a>
									</td>
								</tr>
								@endforeach
							@endif
						@endif
					</tbody>	
				</table>
			</div>
		</div>
		@if(isset($trainingsAttended))
			@if(count($trainingsAttended)>0)
				<div class="row">
					<div class="col-md-12">
						<h4>Trainings Attended</h4>
						<div class="table-responsive">
							<table class="table table-condensed table-striped table-bordered">
								<thead>
									<tr>
										<th>Sl #</th>
										<th>Training Type</th>
										<th>Training Dates</th>
										<th>Modules</th>
										<th>Participant</th>
										<th>CID No</th>
									</tr>
								</thead>
								<tbody>
									<?php $count = 1; ?>
									@foreach($trainingsAttended as $training)
										<tr>
											<td>{{$count++}}</td>
											<td>{{$training->TrainingType}}</td>
											<td>{{convertDateToClientFormat($training->TrainingFromDate)}} to {{convertDateToClientFormat($training->TrainingToDate)}}</td>
											<td>{{((int)$training->TrainingReference == 1602)?$training->Module:"----"}}</td>
											<td>{{$training->Participant}}</td>
											<td>{{$training->CIDNo}}</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>
				</div>
			@endif
		@endif
		<div class="tabbable-custom nav-justified">
			<ul class="nav nav-tabs nav-justified">
				<li class="active">
					<a href="#partnerdetails" data-toggle="tab">
					General Information</a>
				</li>
				<li>
					<a href="#humanresource" data-toggle="tab">
					Human Resource </a>
				</li>
				<li>
					<a href="#equipment" data-toggle="tab">
					Equipment</a>
				</li>
				<li>
					<a href="#workclassification" data-toggle="tab">
					Work Classification</a>
				</li>
				@if((int)$registrationApprovedForPayment!=1)
				<li>
					<a href="#trackrecord" data-toggle="tab">
					Track Record</a>
				</li>
				@endif
				<li>
					<a href="#commentsadverserecords" data-toggle="tab">
						Comments/ Adverse Records
					</a>
				</li>
				<!-- <li>
					<a href="#monitoringRecord" data-toggle="tab">
						Monitoring Records
					</a>
				</li> -->
			</ul>
			<div class="tab-content">
				<div class="tab-pane active" id="partnerdetails">
					<h5 class="font-blue-madison bold">Name of Owner, Partners and/or others with Controlling Interest</h5>
					<table class="table table-bordered table-striped table-condensed flip-content">
						<thead>
							<tr>
								<th>
									Sl#
								</th>
								<th>
									Name
								</th>
								<th class="">
									CID No.
								</th>
								
								<th>
									Sex
								</th>
								<th>
									Nationality
								</th>
								<th>
									Designation
								</th>
								<th width="15%">
									Show in Certificate
								</th>
							</tr>
						</thead>
						<tbody>
						<?php $ownerPartnerCount = 1; ?>
							@foreach($ownerPartnerDetails as $ownerPartnerDetail)
							<tr>
								<td>{{$ownerPartnerCount}}</td>
								<td>
									{{{$ownerPartnerDetail->Name}}}
								</td>
								<td>
									{{{$ownerPartnerDetail->CIDNo}}}
								</td>
								<td>
									{{{$ownerPartnerDetail->Sex}}}
								</td>
								<td>
									{{{$ownerPartnerDetail->Country}}}
								</td>
								<td>
									{{{$ownerPartnerDetail->Designation}}}
								</td>
								<td>
									@if((int)$ownerPartnerDetail->ShowInCertificate==1)
									<i class="fa fa-check"></i>
									@endif
								</td>
							</tr>
							<?php $ownerPartnerCount++; ?>
							@endforeach
						</tbody>
					</table>
					@if((int)$userContractor==0)
						@if(((isset($registrationApproved) && (int)$registrationApproved!=1)) || (isset($isAdmin) && $isAdmin))
							<a href="{{URL::to('contractor/editgeneralinfo/'.$generalInformation[0]->Id.'?redirectUrl=contractor/editdetails&usercdb=edit&final=true')}}" class="btn blue-madison editaction">Edit General Information/Partner Details</a>
						@endif
					@else
						<a href="#editgeneralinfo" data-toggle="modal" class="btn blue-madison"><i class="fa fa-edit"></i> Edit General Info</a>
					@endif
				</div>
				<div class="tab-pane" id="workclassification">
					<table class="table table-bordered table-striped table-condensed flip-content">
						<thead class="flip-content">
							<tr>
								<th>
									Class
								</th>
								<th>
									Applied
								</th>
								<th>Verified</th>
								<th>Approved</th>
							</tr>
						</thead>
						<tbody>
							@foreach($contractorWorkClassifications as $contractorWorkClassification)
							<tr>
								<td>{{{$contractorWorkClassification->Code.'('.$contractorWorkClassification->Category.')'}}}</td>
								<td>{{{$contractorWorkClassification->AppliedClassification}}}</td>
								<td>{{{$contractorWorkClassification->VerifiedClassification}}}</td>
								<td>{{{$contractorWorkClassification->ApprovedClassification}}}</td>
							</tr>
							@endforeach
						</tbody>
					</table>
					{{--@if((int)$userContractor==0 && $generalInformation[0]->CmnApplicationRegistrationStatusId==CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVEDFORPAYMENT)--}}
							{{--<a href="{{URL::to('contractor/editworkclassification/'.$generalInformation[0]->Id.'?redirectUrl=contractor/editdetails&usercdb=edit')}}" class="btn blue-madison">Edit Work Classification</a>--}}
					{{--@endif--}}
					@if(isset($isAdmin) && (bool)$isAdmin)
						@if(((isset($registrationApproved) && (int)$registrationApproved!=1)) || (isset($isAdmin) && $isAdmin))
{{--						@if((bool)$isAdmin &&$generalInformation[0]->CmnApplicationRegistrationStatusId==CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED)--}}
							<a href="{{URL::to('contractor/editworkclassification/'.$generalInformation[0]->Id.'?redirectUrl=contractor/editdetails&usercdb=edit&final=true&updatedFrom=SYSTEM')}}" class="btn blue-madison">Edit Work Classification</a>
							<a href="{{URL::to('contractor/editworkclassification/'.$generalInformation[0]->Id.'?redirectUrl=contractor/editdetails&usercdb=edit&final=true&downgrade=true&updatedFrom=SYSTEM')}}" data-toggle="modal" class="btn purple">Downgrade</a>
						@endif
						{{--@endif--}}
					@endif

				</div>
				<div class="tab-pane" id="humanresource">
					<div class="table-scrollable">
						<table class="table table-bordered table-striped table-condensed flip-content">
							<thead class="flip-content">
								<th>
									Sl#
								</th>
								<th>
									 Name
								</th>
								<th width="6%">
									 CID/Work Permit No.
								</th>
								<th width="6%">
									CDB No.
								</th>
								<th width="5%">
									 Sex
								</th>
								<th class="">
									 Nationality
								</th>
								<th width="6%">
									 Qualification
								</th>
								<th>
									 Designation
								</th>
								<th>
									Joining Date
								</th>
								<th>
									Trade/Fields
								</th>
								<th>
									Attachments(CV/UT/AT)
								</th>
								<th>
									Edited On
								</th>
							</thead>
							<tbody>
								<?php $humanResourceCount = 1; ?>
								@forelse($contractorHumanResources as $contractorHumanResource)
								<tr>
									<td>{{$humanResourceCount}}</td>
									<td>{{{$contractorHumanResource->Name}}}</td>
									<td>{{{$contractorHumanResource->CIDNo}}}</td>
									<td class="font-blue-madison bold warning">{{{$contractorHumanResource->CDBNo1}}}{{{$contractorHumanResource->CDBNo2}}}{{{$contractorHumanResource->CDBNo3}}}{{{$contractorHumanResource->CDBNo4}}}</td>
									<td>{{{$contractorHumanResource->Sex}}}</td>
									<td>{{{$contractorHumanResource->Country}}}</td>
									<td>{{{$contractorHumanResource->Qualification}}}</td>
									<td>{{{$contractorHumanResource->Designation}}}</td>
									<td>{{{convertDateToClientFormat($contractorHumanResource->JoiningDate)}}}</td>
									<td>{{{$contractorHumanResource->Trade}}}</td>
									<td>
										@foreach($contractorHumanResourceAttachments as $contractorHumanResourceAttachment)
											@if($contractorHumanResourceAttachment->CrpContractorHumanResourceId==$contractorHumanResource->Id)
											<i class="fa fa-check"></i> <a href="{{URL::to($contractorHumanResourceAttachment->DocumentPath)}}" target="_blank">{{$contractorHumanResourceAttachment->DocumentName}}</a><br />
											@endif
										@endforeach
									</td>
									<th>
										@if(isset($contractorHumanResource->EditedOn))
										{{convertDateTimeToClientFormat($contractorHumanResource->EditedOn)}}
										@endif
									</th>
								</tr>
								<?php $humanResourceCount++; ?>
								@empty
								<tr>
									<td colspan="10" class="font-red text-center">No data to display</td>
								</tr>
								@endforelse
							</tbody>
						</table>
					</div>
					@if((int)$userContractor==0)
						@if(((isset($registrationApproved) && (int)$registrationApproved!=1)) || (isset($isAdmin) && $isAdmin))
							<a href="{{URL::to('contractor/edithumanresource/'.$generalInformation[0]->Id.'?redirectUrl=contractor/editdetails&usercdb=edit')}}" class="btn blue-madison editaction">Edit Human Resource</a>
						@endif
					@endif
				</div>
				<div class="tab-pane" id="equipment">
					<div class="table-responsive">
						<table class="table table-bordered table-striped table-condensed">
							<thead>
								<tr>
									<th>
										Sl#
									</th>
									<th>
										Equipment Name
									</th>
									<th scope="col" class="">
										 Registration No
									</th>
									<th scope="col" class="">
										Equipment Model
									</th>
									<th>
										Quantity
									</th>
									<th>
										Attachment
									</th>
									<th>
										Edited On
									</th>
								</tr>
							</thead>
							<tbody>
							<?php $equipmentCount = 1; ?>
								@forelse($contractorEquipments as $contractorEquipment)
								<tr>
									<td>{{$equipmentCount}}</td>
									<td>{{{$contractorEquipment->Name}}}</td>
									<td>{{{$contractorEquipment->RegistrationNo}}}</td>
									<td>{{{$contractorEquipment->ModelNo}}}</td>
									<td>{{{$contractorEquipment->Quantity}}}</td>
									<td>
										@foreach($contractorEquipmentAttachments as $contractorEquipmentAttachment)
											@if($contractorEquipmentAttachment->CrpContractorEquipmentId==$contractorEquipment->Id)
											<i class="fa fa-check"></i> <a href="{{URL::to($contractorEquipmentAttachment->DocumentPath)}}" target="_blank">{{$contractorEquipmentAttachment->DocumentName}}</a><br />
											@endif
										@endforeach
									</td>
									<td>
										@if(isset($contractorHumanResource->EditedOn))
											{{convertDateTimeToClientFormat($contractorHumanResource->EditedOn)}}
										@endif
									</td>
								</tr>
								<?php $equipmentCount++; ?>
								@empty
								<tr>
									<td colspan="7" class="font-red text-center">No data to display</td>
								</tr>
								@endforelse
							</tbody>
						</table>
					</div>
					@if((int)$userContractor==0)
						@if(((isset($registrationApproved) && (int)$registrationApproved!=1)) || (isset($isAdmin) && $isAdmin))
							<a href="{{URL::to('contractor/editequipment/'.$generalInformation[0]->Id.'?redirectUrl=contractor/editdetails&usercdb=edit')}}" class="btn blue-madison editaction">Edit Equipment</a>
						@endif
					@endif
				</div>
				@if((int)$registrationApprovedForPayment!=1)
				<div class="tab-pane" id="trackrecord">
					<div class="table-responsive">
						<table class="table table-bordered table-striped table-condensed">
							<thead class="flip-content">
							<tr>
								<th>Sl.No.</th>
								<th>Year</th>
								<th>Agency</th>
								<th>Work Id</th>
								<th>Work Name</th>
								<th>Category</th>
								<th>Awarded Amount</th>
								<th>Final Amount</th>
								<th>Dzongkhag</th>
								<th>Status</th>
								<th>APS scoring</th>
								<th>APS (100)</th>
								<th>Remarks</th>
							</tr>
							</thead>
							<tbody>
							<?php $count = 1; $awardedAmount = 0;?>
							@forelse($contractorTrackrecords as $workDetail)
								<tr>
									<td>{{$count}}</td>
									<td>{{($workDetail->ReferenceNo == 3003)?date_format(date_create($workDetail->WorkCompletionDate),'Y'):date_format(date_create($workDetail->WorkStartDate),'Y')}}</td>
									<td>{{$workDetail->Agency}}</td>
									<td>{{$workDetail->WorkId}}</td>
									<td>{{strip_tags($workDetail->NameOfWork)}}</td>
									<td>{{$workDetail->Category}}</td>
									<td>{{$workDetail->AwardedAmount}}</td>
									<td>{{$workDetail->FinalAmount}}</td>
									<td>{{$workDetail->Dzongkhag}}</td>
									<td>{{$workDetail->Status}}<?php $awardedAmount+=($workDetail->ReferenceNo == 3001)?1:0;?></td>
									<td>
										<?php if((int)$workDetail->APS == 100) {
											$points = 10;
										}
										if(((int)$workDetail->APS < 100) && ((int)$workDetail->APS >= 50)) {
											$points = 10 - (ceil((100 - (int)$workDetail->APS) / 5));
										}
										if((int)$workDetail->APS < 50){
											$points = 0;
										}
										?>
										{{$points}}
									</td>
									<td>{{$workDetail->APS}}</td>
									<td>@if(isset($workDetail->APSFormPath) && (bool)$workDetail->APSFormPath)<a href="{{asset($workDetail->APSFormPath)}}">APS Form</a>@endif</td>
									<td>{{$workDetail->Remarks}}</td>
								</tr>
								<?php $count++ ?>
							@empty
								<tr><td colspan="12" class="font-red text-center">No data to display</td></tr>
							@endforelse
							</tbody>
						</table>
					</div>
				</div>
				@endif
				<div class="tab-pane" id="commentsadverserecords">
					<h5 class="font-blue-madison bold">Comments</h5>
					<div class="table-responsive">
						<table class="table table-bordered table-striped table-condensed">
							<thead>
								<tr>
									<th style="width: 10%;">Date</th>
									<th>Remarks</th>
									<th style="width: 10%;">Action</th>
								</tr>
							</thead>
							<tbody>
								@foreach($contractorComments as $contractorComment)
								<tr>
									<td>
										{{convertDateToClientFormat($contractorComment->Date)}}</td>
									<td>{{{$contractorComment->Remarks}}}</td>
									<td>
										<input type="hidden" class="commentid" value="{{$contractorComment->Id}}">
										<input type="hidden" class="contractorcommentdate" value="{{convertDateToClientFormat($contractorComment->Date)}}" />
										<input type="hidden" class="contractorcomment" value="{{$contractorComment->Remarks}}" />
										{{--<button type="button" class="btn green-haze btn-sm editcontractorcomment">Edit</button>--}}
										{{--<button type="button" class="btn bg-red-sunglo btn-sm deletecontractorcomment">Delete</button>--}}
										@if($userContractor == 0)
										<a href="#" class="editcontractorcomment">Edit</a>
										&nbsp;|&nbsp;
										<a href="#" class="deletecontractorcomment">Delete</a>
										@endif
									</td>
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>

					<h5 class="font-blue-madison bold">Adverse Records</h5>
					<div class="table-responsive">
						<table class="table table-bordered table-striped table-condensed">
							<thead>
							<tr>
								<th style="width: 10%;">Date</th>
								<th>Remarks</th>
								<th style="width: 10%;">Action</th>
							</tr>
							</thead>
							<tbody>
							@foreach($contractorAdverseRecords as $contractorAdverseRecord)
								<tr>
									<td>
										{{convertDateToClientFormat($contractorAdverseRecord->Date)}}</td>
									<td>{{{$contractorAdverseRecord->Remarks}}}</td>
									<td>
										<input type="hidden" class="adverserecordid" value="{{$contractorAdverseRecord->Id}}">
										<input type="hidden" class="contractoradverserecorddate" value="{{convertDateToClientFormat($contractorAdverseRecord->Date)}}" />
										<input type="hidden" class="contractoradverserecord" value="{{$contractorAdverseRecord->Remarks}}" />
										@if($userContractor == 0 && $isAdmin)
											@if(((isset($registrationApproved) && (int)$registrationApproved!=1)) || (isset($isAdmin) && $isAdmin))
												<a href="#" class="editcontractoradverserecord">Edit</a>
												&nbsp;|&nbsp;
												<a href="#" class="deletecontractoradverserecord">Delete</a>
											@endif
										@endif
									</td>
								</tr>
							@endforeach
							</tbody>
						</table>
					</div>
					
					<h5 class="font-blue-madison bold">Monitoring Record</h5>
					<div class="table-responsive">
						<table class="table table-bordered table-striped table-condensed">
							<thead>
							<tr>
								<th style="width: 10%;">Year</th>
								<th>Remarks</th>
								<th>Type</th>
								<th>Option</th>
							</tr>
							</thead>
							<tbody>
						
								@foreach($monitoringcomment as $contractorAdverseRecord)
										<tr>
											<td>{{convertDateToClientFormat($contractorAdverseRecord->MonitoringDate)}}</td>
											<td>{{$contractorAdverseRecord->Remarks}}</td>
											<td>
												<?php 	if($contractorAdverseRecord->ActionTaken==1){echo 'Downgrade';}
														elseif($contractorAdverseRecord->ActionTaken==2){echo 'Suspend';}
														elseif($contractorAdverseRecord->ActionTaken==3){echo 'Warning';}
														elseif($contractorAdverseRecord->ActionTaken==4){echo 'Monitoring Record';}
														elseif($contractorAdverseRecord->ActionTaken==5){echo 'Reinstate';}
												?>
											</td>
											<td>
												<input type="hidden" class="adverserecordid" value="{{$contractorAdverseRecord->Id}}">
												<input type="hidden" class="contractoradverserecorddate" value="{{convertDateToClientFormat($contractorAdverseRecord->MonitoringDate)}}" />
												<input type="hidden" class="contractoradverserecord" value="{{$contractorAdverseRecord->Remarks}}" />
												<a href="#" class="editcontractoradverserecord">Edit</a>
												&nbsp;|&nbsp;
												<a href="#" class="deletecontractoradverserecord">Delete</a>
											</td>
										</tr>
									@endforeach
							</tbody>
						</table>
					</div>
					@if((int)$userContractor==0)
						@if(((isset($registrationApproved) && (int)$registrationApproved!=1)) || (isset($isAdmin) && $isAdmin))
							<a href="#addcommentadverserecord" data-toggle="modal" class="btn blue-madison">Add Comment/ Adverse Record</a>
						@endif
					@endif
				</div>
			
			</div>
		</div>
		@if((int)$registrationApprovedForPayment==1)
		<div class="note note-success">
			<h4 class="">Verified by {{$generalInformation[0]->Verifier}} on {{convertDateToClientFormat($generalInformation[0]->RegistrationVerifiedDate)}}<small><i class="font-red">{{showDateTimeDuration($generalInformation[0]->RegistrationVerifiedDate)}}</i></small></h4>
			<p>
				{{{$generalInformation[0]->RemarksByVerifier}}}
			</p>
		</div>
		<div class="note note-success">
			<h4 class="">Approved by {{$generalInformation[0]->Approver}} on {{convertDateToClientFormat($generalInformation[0]->RegistrationApprovedDate)}}<small><i class="font-red">{{showDateTimeDuration($generalInformation[0]->RegistrationApprovedDate)}}</i></small></h4>
			<p>
				{{{$generalInformation[0]->RemarksByApprover}}}
			</p>
		</div>
		@endif
		@if(isset($registrationApproved))
			@if((int)$registrationApproved == 1)
				<div class="note note-success">
					<h4 class="">Payment Approved by {{$generalInformation[0]->PaymentApprover}} on {{convertDateToClientFormat($generalInformation[0]->RegistrationPaymentApprovedDate)}}<small><i class="font-red">{{showDateTimeDuration($generalInformation[0]->RegistrationPaymentApprovedDate)}}</i></small></h4>
					<p>
						{{{$generalInformation[0]->RemarksByPaymentApprover}}}
					</p>
				</div>
			@endif
		@endif
	</div>
</div>
@if($generalInformation[0]->CmnApplicationRegistrationStatusId==CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVEDFORPAYMENT && (int)$registrationApprovedForPayment==1)
<div id="paymentdoneregistration" class="modal fade" role="dialog" aria-labelledby="paymentdoneregistration" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h3 class="modal-title font-green-seagreen">Approve Payment</h3>
			</div>
			<div class="modal-body">
				<p class="bold">Are you sure you want to approve payment for this application?</p>
			</div>
			<div class="modal-footer">
				<button id="approvecontractorpaymentregistration" type="button" class="btn green">Approve</button>
				<button class="btn red" data-dismiss="modal" aria-hidden="true">Cancel</button>
			</div>
		</div>
	</div>
</div>
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-cogs"></i>
			<span class="caption-subject">Payment Details</span>
		</div>
	</div>
	<div class="portlet-body form">
		<div class="form-body">
			@include('crps.applicationhistory')
			{{ Form::open(array('url' => 'contractor/mapprovepaymentforregistration','role'=>'form','id'=>'registrationpaymentdonecontractor'))}}
			<input type="hidden" name="RegistrationPaymentApprovedDate" value="{{date('Y-m-d G:i:s')}}" />
			<input type="hidden" name="SysLockedByUserId" value="" />
			<input type="hidden" name="ContractorReference" value="{{$generalInformation[0]->Id}}" />
			<input type="hidden" name="CmnApplicationRegistrationStatusId" value="{{CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED}}" />
			<div class="row">
				<div class="col-md-12">
					<h5 class="font-blue-madison bold">Registration Fee Details (<i class="font-red">FINAL</i>)</h5>
					<table class="table table-bordered table-striped table-condensed">
						<thead>
							<tr>
								<th></th>
								<th class="text-center" colspan="2">Applied</th>
								<th class="text-center" colspan="2">Verified</th>
								<th class="text-center" colspan="2">Approved</th>
							</tr>
							<tr>
								<th>Catgeory</th>
								<th>Class</th>
								<th>Amount (Nu.)</th>
								<th>Class</th>
								<th>Amount (Nu.)</th>
								<th>Class</th>
								<th>Amount (Nu.)</th>
							</tr>
						</thead>
						<tbody>
							<?php $totalFeeApplied=0;$totalFeeVerified=0;$totalFeeApproved=0; ?>
							@foreach($feeStructures as $feeStructure)
								<?php $randomKey = randomString(); ?>
								<tr>
									<td>
										{{{$feeStructure->CategoryCode.' ('.$feeStructure->Category.')'}}}
										<input type="hidden" name="crpcontractorregistrationpayment[{{$randomKey}}][CmnCategoryId]" value="{{$feeStructure->CategoryId}}"/>
									</td>
									<td class="success">
										{{{$feeStructure->AppliedClassificationCode.' ('.$feeStructure->AppliedClassification.')'}}}
										<input type="hidden" name="crpcontractorregistrationpayment[{{$randomKey}}][CmnAppliedClassificationId]" value="{{$feeStructure->AppliedClassificationId}}"/>
									</td>
									<td class="text-right success">
										{{{number_format($feeStructure->AppliedRegistrationFee,2)}}}
										<input type="hidden" name="crpcontractorregistrationpayment[{{$randomKey}}][AppliedAmount]" value="{{$feeStructure->AppliedRegistrationFee}}"/>
									</td>
									<td class="warning">
										{{{$feeStructure->VerifiedClassificationCode.' ('.$feeStructure->VerifiedClassification.')'}}}
										<input type="hidden" name="crpcontractorregistrationpayment[{{$randomKey}}][CmnVerifiedClassificationId]" value="{{$feeStructure->VerifiedClassificationId}}"/>
									</td>
									<td class="text-right warning">
										{{{number_format($feeStructure->VerifiedRegistrationFee,2)}}}
										<input type="hidden" name="crpcontractorregistrationpayment[{{$randomKey}}][VerifiedAmount]" value="{{$feeStructure->VerifiedRegistrationFee}}"/>
									</td>
									<td class="info">
										{{{$feeStructure->ApprovedClassificationCode.' ('.$feeStructure->ApprovedClassification.')'}}}
										<input type="hidden" name="crpcontractorregistrationpayment[{{$randomKey}}][CmnApprovedClassificationId]" value="{{$feeStructure->ApprovedClassificationId}}"/>
									</td>
									<td class="text-right info">
										{{{number_format($feeStructure->ApprovedRegistrationFee,2)}}}
										<input type="hidden" name="crpcontractorregistrationpayment[{{$randomKey}}][ApprovedAmount]" value="{{$feeStructure->ApprovedRegistrationFee}}"/>
									</td>
								</tr>
								<?php
									$totalFeeApplied+=$feeStructure->AppliedRegistrationFee;
									$totalFeeVerified+=$feeStructure->VerifiedRegistrationFee;
									$totalFeeApproved+=$feeStructure->ApprovedRegistrationFee;
								?>
							@endforeach
								<tr class="text-right bold">
									<td>Total</td>
									<td colspan="2" class="success">{{number_format($totalFeeApplied,2)}}</td>
									<td colspan="2" class="warning">{{number_format($totalFeeVerified,2)}}</td>
									<td colspan="2" class="info">{{number_format($totalFeeApproved,2)}}</td>
								</tr>
						</tbody>
					</table>
				</div>
			</div>
			<div class="row">
				<div class="col-md-2">
					<?php
						$lastUsedCDBNo = lastUsedContractorCDBNo($generalInformation[0]->Country);
						$lastUsedCDBNo+=1;
						if($generalInformation[0]->Country != "Bhutan"):
							$lastUsedCDBNo="NB".$lastUsedCDBNo;
						endif;
					?>
					<div class="form-group">
						<input type="hidden" value="checkcdbnocontractor" class="cdbnocheckurl">
						<label class="control-label">CDB No.</label>
						<input type="text" name="CDBNo" class="form-control required checkcdbno" value="{{$lastUsedCDBNo}}" />
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label class="control-label">Payment Date</label>
						<div class="input-icon right">
							<i class="fa fa-calendar"></i>
							<input type="text" name="PaymentReceiptDate" class="form-control datepicker required" placeholder="" readonly="readonly"/>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label class="control-label">Receipt No.</label>
						<input type="text" name="PaymentReceiptNo" class="form-control required" class="text-right" />
					</div>
				</div>
				
				<div class="col-md-12">
					<div class="form-group">
						<input type="hidden" name="SysPaymentApproverUserId" value="{{Auth::user()->Id}}">
						<label>Remarks</label>
						<textarea name="RemarksByPaymentApprover" class="form-control" rows="3"></textarea>
					</div>
				</div>
			</div>
		</div>
		<div class="form-actions">
			<div class="btn-set">
				<a href="#paymentdoneregistration" data-toggle="modal" class="btn green">Approve & Generate Certificate</a>
				@if($isAdmin)
					<a href="#sendback" data-toggle="modal" class="btn purple">Send back to Approver</a>
				@endif
			</div>
		</div>
		{{Form::close()}}
	</div>
</div>
@endif
@if(isset($registrationApproved))
	@if((int)$registrationApproved == 1)
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-cogs"></i>
					<span class="caption-subject">Payment Details</span>
				</div>
			</div>
			<div class="portlet-body form">
				@include('crps.applicationhistory')
				<div class="form-body">
					{{ Form::open(array('url' => 'contractor/msavefinalremarks','role'=>'form','id'=>'registrationpaymentdonecontractor'))}}
					<input type="hidden" name="ContractorReference" value="{{$generalInformation[0]->Id}}" />
					<div class="row">
						<div class="col-md-12">
							<h5 class="font-blue-madison bold">Registration Fee Details (<i class="font-red">FINAL</i>)</h5>
							<strong>Payment Receipt Date: {{convertDateToClientFormat($generalInformation[0]->PaymentReceiptDate)}}</strong> <br>
							<strong>Payment Receipt No.: {{$generalInformation[0]->PaymentReceiptNo}}</strong> <br> <br>
							<table class="table table-bordered table-striped table-condensed">
								<thead>
								<tr>
									<th></th>
									<th class="text-center" colspan="2">Applied</th>
									<th class="text-center" colspan="2">Verified</th>
									<th class="text-center" colspan="2">Approved</th>
								</tr>
								<tr>
									<th>Catgeory</th>
									<th>Class</th>
									<th>Amount (Nu.)</th>
									<th>Class</th>
									<th>Amount (Nu.)</th>
									<th>Class</th>
									<th>Amount (Nu.)</th>
								</tr>
								</thead>
								<tbody>
								<?php $totalFeeApplied=0;$totalFeeVerified=0;$totalFeeApproved=0; ?>
								@foreach($feeStructures as $feeStructure)
									<tr>
										<td>
											{{{$feeStructure->CategoryCode.' ('.$feeStructure->Category.')'}}}
										</td>
										<td class="success">
											{{{$feeStructure->AppliedClassificationCode.' ('.$feeStructure->AppliedClassification.')'}}}
										</td>
										<td class="text-right success">
											{{{number_format($feeStructure->AppliedRegistrationFee,2)}}}
										</td>
										<td class="warning">
											{{{$feeStructure->VerifiedClassificationCode.' ('.$feeStructure->VerifiedClassification.')'}}}
										</td>
										<td class="text-right warning">
											{{{number_format($feeStructure->VerifiedRegistrationFee,2)}}}
										</td>
										<td class="info">
											{{{$feeStructure->ApprovedClassificationCode.' ('.$feeStructure->ApprovedClassification.')'}}}
										</td>
										<td class="text-right info">
											{{{number_format($feeStructure->ApprovedRegistrationFee,2)}}}
										</td>
									</tr>
									<?php
									$totalFeeApplied+=$feeStructure->AppliedRegistrationFee;
									$totalFeeVerified+=$feeStructure->VerifiedRegistrationFee;
									$totalFeeApproved+=$feeStructure->ApprovedRegistrationFee;
									?>
								@endforeach
								<tr class="text-right bold">
									<td>Total</td>
									<td colspan="2" class="success">{{number_format($totalFeeApplied,2)}}</td>
									<td colspan="2" class="warning">{{number_format($totalFeeVerified,2)}}</td>
									<td colspan="2" class="info">{{number_format($totalFeeApproved,2)}}</td>
								</tr>
								</tbody>
							</table>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<input type="hidden" name="SysFinalApproverUserId" value="{{Auth::user()->Id}}">
								<input type="hidden" name="SysFinalApprovedDate" value="{{date('Y-m-d G:i:s')}}">
								<label>Any Remarks</label>
								<textarea name="RemarksByFinalApprover" class="form-control" rows="3"></textarea>
							</div>
						</div>
					</div>
				</div>
				<div class="form-actions">
					<div class="btn-set">
						<button type="submit" class="btn green">Save</button>
					</div>
				</div>
				{{Form::close()}}
			</div>
		</div>
	@endif
@endif
@stop