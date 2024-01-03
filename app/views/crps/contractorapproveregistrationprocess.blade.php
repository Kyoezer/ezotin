@extends('master')
@section('lastcdbno')
<div class="page-toolbar">
	<div id="dashboard-report-range" class="pull-right tooltips btn btn-fit-height grey-cascade" data-placement="top" data-original-title="Last used Contractor CDB No.">
		<span class="thin visible-lg-inline-block">Last used CDB No.: <span class="bold">@if($generalInformation[0]->Country != "Bhutan"){{"NB"}}@endif{{lastUsedContractorCDBNo($generalInformation[0]->Country);}}</span></span>
	</div>
</div>
@stop
@section('content')
<div id="hrcheckmodal" class="modal fade in" role="dialog" aria-labelledby="modalmessagebox" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h3 id="modalmessageheader" class="modal-title font-red-intense">HR Check</h3>
			</div>
			<div class="modal-body">
			</div>
		</div>
	</div>
</div>
<div id="eqcheckmodal" class="modal fade in" role="dialog" aria-labelledby="modalmessagebox" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h3 id="modalmessageheader" class="modal-title font-red-intense">Equipment Check</h3>
			</div>
			<div class="modal-body">
			</div>
		</div>
	</div>
</div>
<div id="approve" class="modal fade" role="dialog" aria-labelledby="approve" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h3 class="modal-title font-green-seagreen">Approve Registration</h3>
			</div>
			<div class="modal-body">
				<p class="bold">Are you sure you want to approve this application?</p>
			</div>
			<div class="modal-footer">
				<button id="approvecontractorregistration" type="button" class="btn green">Approve</button>
				<button class="btn red" data-dismiss="modal" aria-hidden="true">Cancel</button>
			</div>
		</div>
	</div>
</div>
<div id="sendback" class="modal fade" role="dialog" aria-labelledby="sendback" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			{{Form::open(array('url'=>'sendapplicationback','role'=>'form'))}}
			<input type="hidden" name="Id" value="{{$generalInformation[0]->Id}}" />
			<input type="hidden" name="Model" value="ContractorModel"/>
			<input type="hidden" name="Status" value="{{CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW}}"/>
			<input type="hidden" name="RedirectUrl" value="contractor/approveregistration"/>
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
<div id="reject" class="modal fade" role="dialog" aria-labelledby="reject" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			{{ Form::open(array('url' => 'contractor/mrejectregistration','role'=>'form'))}}
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h3 class="modal-title font-green-seagreen">Reject Registration</h3>
			</div>
			<div class="modal-body">
				<input type="hidden" name="RedirectRoute" value="approveregistration">
				<p class="bold">Are you sure you want to reject this application?</p>
				<input type="hidden" name="ContractorReference" value="{{$generalInformation[0]->Id}}" />
				<input type="hidden" name="RejectedDate" value="{{date('Y-m-d G:i:s')}}" />
				<div class="form-group">
					<label>Remarks</label>
					<textarea name="RemarksByRejector" class="form-control" rows="3"></textarea>
				</div>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn green">Reject</button>
				<button class="btn red" data-dismiss="modal" aria-hidden="true">Cancel</button>
			</div>
			{{Form::close()}}
		</div>
	</div>
</div>
{{ Form::open(array('url' => $modelPost,'role'=>'form','id'=>'approveregistrationcontractor'))}}
<input type="hidden" name="SysLockedByUserId" value="" />
<input type="hidden" name="ContractorReference" value="{{$generalInformation[0]->Id}}" />
<input type="hidden" name="CmnApplicationRegistrationStatusId" value="{{CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVEDFORPAYMENT}}" />
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-cogs"></i>
			<span class="caption-subject">Registration Information</span>
		</div>
		<div class="actions">
			<a href="{{URL::to('contractor/approveregistration')}}" class="btn btn-default btn-sm">Back to List</a>
		</div>
	</div>
	<div class="portlet-body">
		<div class="row">
			<div class="col-md-12">
				<h5 class="font-blue-madison bold">Fee structure as per <i class="font-red">APPLIED and VERIFIED CATEGORIES</i> by the applicant and verifier <i class="font-red">( {{{$generalInformation[0]->Verifier}}} )</i></h5>
				<table class="table table-bordered table-striped table-condensed">
					<thead>
						<tr>
							<th></th>
							<th class="text-center" colspan="2">Applied</th>
							<th class="text-center" colspan="2">Verified</th>
						</tr>
						<tr>
							<th>Category</th>
							<th>Class</th>
							<th>Amount (Nu.)</th>
							<th>Class</th>
							<th>Amount (Nu.)</th>
						</tr>
					</thead>
					<tbody>
						<?php $totalFeeApplied=0;$totalFeeVerified=0; ?>
						@foreach($feeStructures as $feeStructure)
							<tr>
								<td>
									{{{$feeStructure->CategoryCode.' ('.$feeStructure->Category.')'}}}
								</td>
								<td class="info">
									{{{$feeStructure->AppliedClassificationCode.' ('.$feeStructure->AppliedClassification.')'}}}
								</td>
								<td class="text-right info">
									<?php $feeAmountApplied=$feeStructure->AppliedRegistrationFee; ?>
									{{{number_format($feeStructure->AppliedRegistrationFee,2)}}}
								</td>
								<td class="success">
									{{{$feeStructure->VerifiedClassificationCode.' ('.$feeStructure->VerifiedClassification.')'}}}
								</td>
								<td class="text-right success">
									<?php $feeAmountVerified=$feeStructure->VerifiedRegistrationFee; ?>
									{{{number_format($feeStructure->VerifiedRegistrationFee,2)}}}
								</td>
								<?php $totalFeeApplied+=$feeAmountApplied;$totalFeeVerified+=$feeAmountVerified;?>	
							</tr>
						@endforeach
						<tr class="bold text-right">
							<td>Total</td>
							<td colspan="2" class="info">{{{number_format($totalFeeApplied,2)}}}</td>
							<td colspan="2" class="success">{{{number_format($totalFeeVerified,2)}}}</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6 table-responsive">
				<table class="table table-condensed">
					<tbody>
						<tr>
							<td colspan="2" class="font-blue-madison bold warning">General Info</td>
						</tr>
						<tr>
							<td><strong>CDB No.</strong></td>
							<td>{{$generalInformation[0]->CDBNo}}</td>
						</tr>
						<tr>
							<td><strong>Ownership Type</strong></td>
							<td>{{$generalInformation[0]->OwnershipType}}</td>
						</tr>
						<tr>
							<td><strong>Proposed Name</strong></td>
							<td>{{$generalInformation[0]->NameOfFirm}}</td>
						</tr>
						<tr>
							<td><strong>Country</strong></td>
							<td>{{$generalInformation[0]->Country}}</td>
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
							<td>{{$generalInformation[0]->RegisteredDzongkhag}}</td>
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
						<tr>
							<td colspan="2" class="font-blue-madison bold warning">Attachments</td>
						</tr>
						@foreach($contractorAttachments as $contractorAttachment)
							<tr>
								<td colspan="2">
									<i class="fa fa-check"></i> <a href="{{URL::to($contractorAttachment->DocumentPath)}}" target="_blank">{{$contractorAttachment->DocumentName}}</a>
								</td>
							</tr>
						@endforeach
					</tbody>
					</tbody>
				</table>
			</div>
			<div class="col-md-6 table-responsive">
				<table class="table table-condensed">
					<tbody>
						<tr>
							<td colspan="2" class="font-blue-madison bold warning">Correspondence Address</td>
						</tr>
						<tr>
							<td><strong>Dzongkhag</strong></td>
							<td>{{$generalInformation[0]->Dzongkhag}}</td>
						</tr>
						<tr>
							<td><strong>Address</strong></td>
							<td>{{$generalInformation[0]->Address}}</td>
						</tr>
						<tr>
							<td><strong>Email</strong></td>
							<td>{{$generalInformation[0]->Email}}</td>
						</tr>
						<tr>
							<td><strong>Telephone No.</strong></td>
							<td>{{$generalInformation[0]->TelephoneNo}}</td>
						</tr>
						<tr>
							<td><strong>Mobile No.</strong></td>
							<td>{{$generalInformation[0]->MobileNo}}</td>
						</tr>
						<tr>
							<td><strong>Fax No.</strong></td>
							<td>{{$generalInformation[0]->FaxNo}}</td>
						</tr>
						@if((int)$generalInformation[0]->OwnershipTypeReferenceNo!=14001)
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
					Partner Details</a>
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
			</ul>
			<div class="tab-content">
				<div class="tab-pane active" id="partnerdetails">
					<p class="font-blue-madison">Name of Owner, Partners and/or others with Controlling Interest</p>
					<table class="table table-bordered table-striped table-condensed flip-content">
						<thead>
							<tr>
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
									Country
								</th>
								<th>
									Designation
								</th>
								<th>Other Remarks</th>
								<th>Action</th>
								<th width="15%">
									Show in Certificate
								</th>
								<th>
									Accepted
								</th>
								<th>
									Approved
								</th>
							</tr>
						</thead>
						<tbody>
							@foreach($ownerPartnerDetails as $ownerPartnerDetail)
							<?php $randomKey = randomString(); ?>
							<tr>
								<td>
									{{$ownerPartnerDetail->Name}}
								</td>
								<td>
									{{$ownerPartnerDetail->CIDNo}}
								</td>
								<td>
									{{$ownerPartnerDetail->Sex}}
								</td>
								<td>
									{{$ownerPartnerDetail->Country}}
								</td>
								<td>
									{{$ownerPartnerDetail->Designation}}
								</td>
								<td>{{$ownerPartnerDetail->OtherRemarks}}</td>
								<td>
									<button type="button" data-cid="{{trim($ownerPartnerDetail->CIDNo)}}" class="btn blue btn-sm checkhrdbandwebservice">Check</button>
								</td>
								<td>
									@if((int)$ownerPartnerDetail->ShowInCertificate==1)
										<i class="fa fa-check"></i>
									@endif
								</td>
								<td class="text-center">
									@if((int)$ownerPartnerDetail->Verified==1)
										<i class="fa fa-check"></i>
									@else
										<i class="fa fa-times"></i>
									@endif
								</td>
								<td>
									<input type="hidden" name="ContractorHumanResourceModel[{{$randomKey}}][Id]" value="{{$ownerPartnerDetail->Id}}">
									<input type="checkbox" name="ContractorHumanResourceModel[{{$randomKey}}][Approved]" class="checkboxes" value="1"/>
								</td>

							</tr>
							@endforeach
						</tbody>
					</table>
					<a href="{{URL::to('contractor/editgeneralinfo/'.$generalInformation[0]->Id.'?redirectUrl=contractor/approveregistrationprocess')}}" class="btn blue-madison">Edit General Information/Partner Details</a>								
				</div>
				<div class="tab-pane" id="workclassification">
					<p class="font-blue-madison">*Please select the classification aganist each of the applied/assessed category. </p>
					<table id="workclasssficationverification" class="table table-bordered table-striped table-condensed flip-content">
						<thead class="flip-content">
							<tr>
								<th>
									Class
								</th>
								<th>
									Applied
								</th>
								<th class="">
									Verified
								</th>
								<th>
									Approve
								</th>
							</tr>
						</thead>
						<tbody>
							@foreach($contractorWorkClassifications as $contractorWorkClassification)
							<?php $randomKey=randomString(); ?>
							<tr>
								<td>
									<input type="hidden" name="ContractorWorkClassificationModel[{{$randomKey}}][Id]" value="{{$contractorWorkClassification->Id}}">
									{{$contractorWorkClassification->Code.'('.$contractorWorkClassification->Category.')'}}
								</td>
								<td>{{$contractorWorkClassification->AppliedClassification}}</td>
								<td>
									{{$contractorWorkClassification->VerifiedClassification}}
								</td>
								<td>
									<select name="ContractorWorkClassificationModel[{{$randomKey}}][CmnApprovedClassificationId]" class="form-control input-sm">
										<option value="">---SELECT ONE---</option>
										@if((int)$contractorWorkClassification->CategoryReferenceNo!=6002)
											@foreach($classes as $class)
												@if((int)$class->ReferenceNo!=4)
												<option value="{{$class->Id}}" @if($contractorWorkClassification->CmnVerifiedClassificationId==$class->Id)selected="selected"@endif>{{$class->Name}}</option>
												@endif
											@endforeach
										@else
											@foreach($classes as $class)
												@if((int)$class->ReferenceNo==4)
												<option value="{{$class->Id}}" @if($contractorWorkClassification->CmnVerifiedClassificationId==$class->Id)selected="selected"@endif>{{$class->Name}}</option>
												@endif
											@endforeach
										@endif
									</select>
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
					{{--<a href="{{URL::to('contractor/editworkclassification/'.$generalInformation[0]->Id.'?redirectUrl=contractor/approveregistrationprocess')}}" class="btn blue-madison">Edit Work Classification</a>--}}
				</div>
				<div class="tab-pane" id="humanresource">
					<div class="table-responsive">
						<table class="table table-bordered table-striped table-condensed flip">
							<thead>
								<th>
									 Name
								</th>
								<th width="6%">
									 CID/Work Permit No.
								</th>
								<th width="5%">
									 Sex
								</th>
								<th class="">
									 Country
								</th>
								<th width="6%">
									 Qualification
								</th>
								<th>
									 Designation
								</th>
								<th>
									Trade/Fields
								</th>
								<th>
									Attachments(CV/UT/AT)
								</th>
								<th>Other Remarks</th>
								<th>Action</th>
								<th>
									Accepted
								</th>
								<th>
									Approved
								</th>
							</thead>
							<tbody>
								@forelse($contractorHumanResources as $contractorHumanResource)
								<?php $randomKey=randomString(); ?>
								<tr>
									<td>{{$contractorHumanResource->Salutation.' '.$contractorHumanResource->Name}}</td>
									<td>{{$contractorHumanResource->CIDNo}}</td>
									<td>{{$contractorHumanResource->Sex}}</td>
									<td>{{$contractorHumanResource->Country}}</td>
									<td>{{$contractorHumanResource->Qualification}}</td>
									<td>{{$contractorHumanResource->Designation}}</td>
									<td>{{$contractorHumanResource->Trade}}</td>
									<td>
									@foreach($contractorHumanResourceAttachments as $contractorHumanResourceAttachment)
										@if($contractorHumanResourceAttachment->CrpContractorHumanResourceId==$contractorHumanResource->Id)
										<i class="fa fa-check"></i> <a href="{{URL::to($contractorHumanResourceAttachment->DocumentPath)}}" target="_blank">{{$contractorHumanResourceAttachment->DocumentName}}</a><br />
										@endif
									@endforeach
									</td>
									<td>{{$contractorHumanResource->OtherRemarks}}</td>
									<td>
										<button type="button" data-cid="{{trim($contractorHumanResource->CIDNo)}}" class="btn blue btn-sm checkhrdbandwebservice">Check</button>
									</td>
									<td class="text-center">
										@if((int)$contractorHumanResource->Verified==1)
											<i class="fa fa-check"></i>
										@else
											<i class="fa fa-times"></i>
										@endif
									</td>
									<td>
										<input type="hidden" name="ContractorHumanResourceModel[{{$randomKey}}][Id]" value="{{$contractorHumanResource->Id}}">
										<input type="checkbox" name="ContractorHumanResourceModel[{{$randomKey}}][Approved]" class="checkboxes" value="1"/>
									</td>
								</tr>
								@empty
								<tr>
									<td colspan="10" class="font-red text-center">No data to display</td>
								</tr>
								@endforelse
							</tbody>
						</table>
					</div>
					<a href="{{URL::to('contractor/edithumanresource/'.$generalInformation[0]->Id.'?redirectUrl=contractor/approveregistrationprocess')}}" class="btn blue-madison">Edit Human Resource</a>
				</div>
				<div class="tab-pane" id="equipment">
					<div class="table-responsive">
						<table class="table table-bordered table-striped table-condensed">
							<thead>
								<tr>
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
									<th>Action</th>
									<th>Accepted</th>
									<th>Approved</th>
								</tr>
							</thead>
							<tbody>
								@forelse($contractorEquipments as $contractorEquipment)
								<?php $randomKey=randomString(); ?>
								<tr>
									<td>{{$contractorEquipment->Name}}</td>
									<td>{{$contractorEquipment->RegistrationNo}}</td>
									<td>{{$contractorEquipment->ModelNo}}</td>
									<td>{{$contractorEquipment->Quantity}}</td>
									<td>
										@foreach($contractorEquipmentAttachments as $contractorEquipmentAttachment)
											@if($contractorEquipmentAttachment->CrpContractorEquipmentId==$contractorEquipment->Id)
											<i class="fa fa-check"></i> <a href="{{URL::to($contractorEquipmentAttachment->DocumentPath)}}" target="_blank">{{$contractorEquipmentAttachment->DocumentName}}</a><br />
											@endif
										@endforeach
									</td>
									<td>
										<button type="button" data-regno="{{trim($contractorEquipment->RegistrationNo)}}" data-vehicletype="{{$contractorEquipment->VehicleType}}" class="btn blue btn-sm checkeqdbandwebservice">Check
										</button>
									</td>
									<td class="text-center">
										@if((int)$contractorEquipment->Verified==1)
											<i class="fa fa-check"></i>
										@else
											<i class="fa fa-times"></i>
										@endif
									</td>
									<td>
										<input type="hidden" name="ContractorEquipmentModel[{{$randomKey}}][Id]" value="{{$contractorEquipment->Id}}">
										<input type="checkbox" name="ContractorEquipmentModel[{{$randomKey}}][Approved]" class="checkboxes" value="1"/>
									</td>
								</tr>
								@empty
								<tr>
									<td colspan="7" class="font-red text-center">No data to display.</td>
								</tr>
								@endforelse
							</tbody>
						</table>
					</div>
					<a href="{{URL::to('contractor/editequipment/'.$generalInformation[0]->Id.'?redirectUrl=contractor/approveregistrationprocess')}}" class="btn blue-madison">Edit Equipment</a>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-cogs"></i>
			<span class="caption-subject">Application</span>
		</div>
	</div>
	<div class="portlet-body form">
		<div class="form-body">
			@include('crps.applicationhistory')
			<div class="row">
				<div class="col-md-12">
					<h5 class="font-red bold">*Validity of registration for contractors is {{$registrationValidityYears}} years.</h5>
				</div>
				
				<div class="col-md-4">
					<div class="form-group">
						<label class="control-label">Approved Date</label>
						<div class="input-icon right">
							<i class="fa fa-calendar"></i>
							<input type="text" name="RegistrationApprovedDate" class="form-control required" placeholder="" value="{{date('d-m-Y G:i:s')}}" readonly="readonly"/>
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label class="control-label">Expiry Date</label>
						<div class="input-icon right">
							<i class="fa fa-calendar"></i>
							<input type="text" name="RegistrationExpiryDate" value="{{registrationExpiryDateCalculator($registrationValidityYears)}}" class="form-control datepicker required" placeholder="" readonly="readonly" />
						</div>
					</div>
				</div>
			</div>
			<div class="form-group">
				<input type="hidden" name="SysApproverUserId" value="{{Auth::user()->Id}}" />
				<label>Remarks</label>
				<textarea name="RemarksByApprover" class="form-control" rows="3"></textarea>
			</div>
		</div>
		<div class="form-actions">
			<div class="btn-set">
				<a href="#approve" data-toggle="modal" class="btn green">Approve</a>
				<a href="#sendback" data-toggle="modal" class="btn purple">Send back to Verifier</a>
				<a href="#reject" data-toggle="modal" class="btn red">Reject</a>
			</div>
			<button type="button" class="btn blue-ebonyclay pull-right">Next</button>
		</div>
	</div>
</div>
{{Form::close()}}
@stop
@section('pagescripts')
{{ HTML::script('assets/global/scripts/contractor.js') }}
@stop