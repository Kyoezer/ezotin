@extends('master')
@section('pagescripts')
{{ HTML::script('assets/global/scripts/contractor.js') }}
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
<div id="sendback" class="modal fade" role="dialog" aria-labelledby="sendback" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			{{Form::open(array('url'=>'sendapplicationback','role'=>'form'))}}
			<input type="hidden" name="Id" value="{{$generalInformation[0]->Id}}" />
			<input type="hidden" name="Model" value="ContractorModel"/>
			<input type="hidden" name="Status" value="{{CONST_CMN_APPLICATIONREGISTRATIONSTATUS_VERIFIED}}"/>
			<input type="hidden" name="RedirectUrl" value="contractor/approveserviceapplicationfeepaymentlist"/>
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
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption font-green-seagreen">
			<i class="fa fa-cogs"></i>
			<span class="caption-subject">Service Application Details</span>
		</div>
	</div>
	<div class="portlet-body form">
		<div class="note note-danger">
          	<p>If any detail(s) submitted by the applicant is different from the CDB record then the field will be marked in red. To view the current record with CDB, please hover over <i class="font-blue">[old]</i> next to the field.</p>
        </div>
		<div class="row">
			<div class="col-md-6 table-responsive">
				<table class="table table-condensed">
					<tbody>
						<tr>
							<td colspan="2" class="font-blue-madison bold warning">General Info</td>
						</tr>
						<tr>
							<td><strong>Ownership Type</strong></td>
							<td>
								@if($generalInformation[0]->OwnershipType==$generalInformationFinal[0]->OwnershipType)
									{{$generalInformation[0]->OwnershipType}}
								@else
									<span class="font-red">{{$generalInformation[0]->OwnershipType}}</span>
									<a href="#" class="tooltips" data-placement="top" data-original-title="{{$generalInformationFinal[0]->OwnershipType}}"><i class="font-blue">[old]</i></a>
								@endif
							</td>
						</tr>
						<tr>
							<td><strong>CDB No.</strong></td>
							<td>
								{{$generalInformationFinal[0]->CDBNo}}
							</td>
						</tr>
						<tr>
							<td><strong>Name/Firm</strong></td>
							<td>
								@if($generalInformation[0]->NameOfFirm==$generalInformationFinal[0]->NameOfFirm)
									{{$generalInformation[0]->NameOfFirm}}
								@else
									<span class="font-red">{{$generalInformation[0]->NameOfFirm}}</span>
									<a href="#" class="tooltips" data-placement="top" data-original-title="{{$generalInformationFinal[0]->NameOfFirm}}"><i class="font-blue">[old]</i></a>
								@endif
							</td>
						</tr>
						<tr>
							<td><strong>Country</strong></td>
							<td>
								@if($generalInformation[0]->Country==$generalInformationFinal[0]->Country)
									{{$generalInformation[0]->Country}}
								@else
									<span class="font-red">{{$generalInformation[0]->Country}}</span>
									<a href="#" class="tooltips" data-placement="top" data-original-title="{{$generalInformationFinal[0]->Country}}"><i class="font-blue">[old]</i></a>
								@endif
							</td>
						</tr>
						<tr>
							<td><strong>Previous Validity</strong></td>
							<td>
								{{convertDateToClientFormat($generalInformationFinal[0]->RegistrationApprovedDate)}} to {{convertDateToClientFormat($generalInformationFinal[0]->RegistrationExpiryDate)}}
							</td>
						</tr>
						<tr>
							<td colspan="2" class="font-blue-madison bold warning">Registered Address</td>
						</tr>
						<tr>
							<td><strong>Dzongkhag</strong></td>
							<td>
								@if($generalInformation[0]->RegisteredDzongkhag==$generalInformationFinal[0]->RegisteredDzongkhag)
									{{$generalInformation[0]->RegisteredDzongkhag}}
								@else
									<span class="font-red">{{$generalInformation[0]->Dzongkhag}}</span>
									<a href="#" class="tooltips" data-placement="top" data-original-title="{{$generalInformationFinal[0]->RegisteredDzongkhag}}"><i class="font-blue">[old]</i></a>
								@endif
							</td>
						</tr>
						<tr>
							<td><strong>Village</strong></td>
							<td>
								@if($generalInformation[0]->Village==$generalInformationFinal[0]->Village)
									{{$generalInformation[0]->Village}}
								@else
									<span class="font-red">{{$generalInformation[0]->Village}}</span>
									<a href="#" class="tooltips" data-placement="top" data-original-title="{{$generalInformationFinal[0]->Village}}"><i class="font-blue">[old]</i></a>
								@endif
							</td>
						</tr>
						<tr>
							<td><strong>Gewog</strong></td>
							<td>
								@if($generalInformation[0]->Dzongkhag==$generalInformationFinal[0]->Gewog)
									{{$generalInformation[0]->Gewog}}
								@else
									<span class="font-red">{{$generalInformation[0]->Gewog}}</span>
									<a href="#" class="tooltips" data-placement="top" data-original-title="{{$generalInformationFinal[0]->Gewog}}"><i class="font-blue">[old]</i></a>
								@endif
							</td>
						</tr>
						<tr>
							<td><strong>Address</strong></td>
							<td>
								@if($generalInformation[0]->RegisteredAddress==$generalInformationFinal[0]->RegisteredAddress)
									{{$generalInformation[0]->RegisteredAddress}}
								@else
									<span class="font-red">{{$generalInformation[0]->RegisteredAddress}}</span>
									<a href="#" class="tooltips" data-placement="top" data-original-title="{{$generalInformationFinal[0]->RegisteredAddress}}"><i class="font-blue">[old]</i></a>
								@endif
							</td>
						</tr>
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
							<td>
								@if($generalInformation[0]->Dzongkhag==$generalInformationFinal[0]->Dzongkhag)
									{{$generalInformation[0]->Dzongkhag}}
								@else
									<span class="font-red">{{$generalInformation[0]->Dzongkhag}}</span>
									<a href="#" class="tooltips" data-placement="top" data-original-title="{{$generalInformationFinal[0]->Dzongkhag}}"><i class="font-blue">[old]</i></a>
								@endif
							</td>
						</tr>
						<tr>
							<td><strong>Address</strong></td>
							<td>
								@if($generalInformation[0]->Address==$generalInformationFinal[0]->Address)
									{{$generalInformation[0]->Address}}
								@else
									<span class="font-red">{{$generalInformation[0]->Address}}</span>
									<a href="#" class="tooltips" data-placement="top" data-original-title="{{$generalInformationFinal[0]->Address}}"><i class="font-blue">[old]</i></a>
								@endif
							</td>
						</tr>
						<tr>
							<td><strong>Email</strong></td>
							<td>
								@if($generalInformation[0]->Email==$generalInformationFinal[0]->Email)
									{{$generalInformation[0]->Email}}
								@else
									<span class="font-red">{{$generalInformation[0]->Email}}</span>
									<a href="#" class="tooltips" data-placement="top" data-original-title="{{$generalInformationFinal[0]->Email}}"><i class="font-blue">[old]</i></a>
								@endif
							</td>
						</tr>
						<tr>
							<td><strong>Telephone No.</strong></td>
							<td>
								@if($generalInformation[0]->Telephone==$generalInformationFinal[0]->Telephone)
									{{$generalInformation[0]->TelephoneNo}}
								@else
									<span class="font-red">{{$generalInformation[0]->Telephone}}</span>
									<a href="#" class="tooltips" data-placement="top" data-original-title="{{$generalInformationFinal[0]->Telephone}}"><i class="font-blue">[old]</i></a>
								@endif
							</td>
						</tr>
						<tr>
							<td><strong>Mobile No.</strong></td>
							<td>
								@if($generalInformation[0]->MobileNo==$generalInformationFinal[0]->MobileNo)
									{{$generalInformation[0]->MobileNo}}
								@else
									<span class="font-red">{{$generalInformation[0]->MobileNo}}</span>
									<a href="#" class="tooltips" data-placement="top" data-original-title="{{$generalInformationFinal[0]->MobileNo}}"><i class="font-blue">[old]</i></a>
								@endif
							</td>
						</tr>
						<tr>
							<td><strong>Fax No.</strong></td>
							<td>
								@if($generalInformation[0]->FaxNo==$generalInformationFinal[0]->FaxNo)
									{{$generalInformation[0]->FaxNo}}
								@else
									<span class="font-red">{{$generalInformation[0]->FaxNo}}</span>
									<a href="#" class="tooltips" data-placement="top" data-original-title="{{$generalInformationFinal[0]->FaxNo}}"><i class="font-blue">[old]</i></a>
								@endif
							</td>
						</tr>
						@if(!empty($serviceApplicationsAttachments))
							<tr>
								<td colspan="2" class="font-blue-madison bold warning">Attachments</td>
							</tr>
							@foreach($serviceApplicationsAttachments as $serviceApplicationsAttachment)
							<tr>
								<td colspan="2">
									<i class="fa fa-check"></i> <a href="{{URL::to($serviceApplicationsAttachment->DocumentPath)}}" target="_blank">{{$serviceApplicationsAttachment->DocumentName}}</a>
								</td>
							</tr>
							@endforeach
						@endif
						{{--<tr>--}}
							{{--<td><strong>Reason for change of owner</strong></td>--}}
							{{--<td>--}}
								{{--{{$generalInformation[0]->ChangeOfOwnershipRemarks}}--}}
							{{--</td>--}}
						{{--</tr>--}}
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
					Owner/Partner Details</a>
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
				<div class="tab-pane active row" id="partnerdetails">
					<div class="col-md-12 table-responsive">
						<h5 class="font-blue-madison bold">Old Owners/Partners</h5>
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

								<th width="15%">
									Show in Certificate
								</th>
								<th>
									Action
								</th>
							</tr>
							</thead>
							<tbody>
							@forelse($ownerPartnerDetailsFinal as $oldOwnerPartner)
								<?php $randomKey = randomString(); ?>
								<tr>
									<td>
										{{$oldOwnerPartner->Name}}
									</td>
									<td>
										{{$oldOwnerPartner->CIDNo}}
									</td>
									<td>
										{{$oldOwnerPartner->Sex}}
									</td>
									<td>
										{{$oldOwnerPartner->Country}}
									</td>
									<td>
										{{$oldOwnerPartner->Designation}}
									</td>
									<td>
										@if((int)$oldOwnerPartner->ShowInCertificate==1)
											<i class="fa fa-check"></i>
										@endif
									</td>
									<td>
										<button type="button" data-cid="{{trim($oldOwnerPartner->CIDNo)}}" class="btn blue btn-sm checkhrdbandwebservice">Check</button>
									</td>
								</tr>
							@empty
								<tr>
									<td colspan="8" class="text-center font-red">No old partners/owners</td>
								</tr>
							@endforelse
							</tbody>
						</table>
						<h5 class="font-blue-madison bold">Name of Owner, Partners and/or others with Controlling Intrest</h5>
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
									<th width="15%">
										Show in Certificate
									</th>
									{{--<th>--}}
										{{--Action--}}
									{{--</th>--}}
									<th>
										Approved
									</th>
								</tr>
							</thead>
							<tbody>
								@forelse($ownerPartnerDetails as $ownerPartnerDetail)
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
									<td>
										@if((int)$ownerPartnerDetail->ShowInCertificate==1)
										<i class="fa fa-check"></i>
										@endif
									</td>
									{{--<td>--}}
										{{--<button type="button" data-cid="{{trim($ownerPartnerDetail->CIDNo)}}" class="btn blue btn-sm checkhrdbandwebservice">Check</button>--}}
									{{--</td>--}}
									<td>
										@if((int)$ownerPartnerDetail->Approved==1)
											<i class="fa fa-check"></i>
										@endif
									</td>
								</tr>
								@empty
									<tr>
										<td class="text-center" colspan="7">No new owners/partners</td>
									</tr>
								@endforelse
							</tbody>
						</table>
						@if((int)$serviceApplicationApproved == 0)
						<a href="{{URL::to('contractor/editgeneralinfo/'.$generalInformation[0]->Id.'?redirectUrl=contractor/verifyserviceapplicationprocess')}}" class="btn blue-madison editaction">Edit General Information/Partner Details</a>
						@endif
					</div>		
				</div>
				<div class="tab-pane" id="workclassification">
					<input type="hidden" name="ContractorId" value="{{$generalInformation[0]->Id}}" />
					<h5 class="font-blue-madison bold">Existing Category/Classification</h5>
					<table id="workclasssficationverification" class="table table-bordered table-striped table-condensed flip-content">
						<thead>
							<tr>
								<th>
									Category
								</th>
								<th>
									Classification
								</th>
							</tr>
						</thead>
						<tbody>
							@foreach($contractorWorkClassificationsFinal as $contractorWorkClassificationFinal)
							<tr>
								<td>
									{{$contractorWorkClassificationFinal->Code.'('.$contractorWorkClassificationFinal->Category.')'}}
								</td>
								<td>{{$contractorWorkClassificationFinal->ApprovedClassification}}</td>
							</tr>
							@endforeach
						</tbody>
					</table>
					<h5 class="font-blue-madison bold">Change in Category/Classification</h5>
					<table id="workclasssficationverification" class="table table-bordered table-striped table-condensed flip-content">
						<thead>
							<tr>
								<th>
									Category
								</th>
								<th>
									Applied
								</th>
								<th class="">
									Assessed
								</th>
								<th>
									Approved
								</th>
							</tr>
						</thead>
						<tbody>
							@forelse($contractorWorkClassifications as $contractorWorkClassification)
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
									<select name="ContractorWorkClassificationModel[{{$randomKey}}][CmnVerifiedClassificationId]" class="form-control input-sm">
										<option value="">---SELECT ONE---</option>
										@if((int)$contractorWorkClassification->CategoryReferenceNo!=6002)
											@foreach($classes as $class)
												<option value="{{$class->Id}}" @if($contractorWorkClassification->CmnVerifiedClassificationId==$class->Id)selected="selected"@endif>{{$class->Name}}</option>
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
							@empty
							<tr>
								<td colspan="3">No Change in Category/Classification</td>
							</tr>
							@endforelse
						</tbody>
					</table>
					{{---<a href="{{URL::to('contractor/editworkclassification/'.$generalInformation[0]->Id.'?redirectUrl=contractor/verifyserviceapplicationprocess')}}" class="btn blue-madison">Edit Work Classification</a>---}}
				</div>
				<div class="tab-pane" id="humanresource">
					<div class="table-responsive">
						<h5 class="font-blue-madison bold">Existing Human Resource</h5>
						<table class="table table-bordered table-striped table-condensed flip-content">
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
							</thead>
							<tbody>
								@foreach($contractorHumanResourcesFinal as $contractorHumanResourceFinal)
								<tr>
									<td>{{$contractorHumanResourceFinal->Salutation.' '.$contractorHumanResourceFinal->Name}}</td>
									<td>{{$contractorHumanResourceFinal->CIDNo}}</td>
									<td>{{$contractorHumanResourceFinal->Sex}}</td>
									<td>{{$contractorHumanResourceFinal->Country}}</td>
									<td>{{$contractorHumanResourceFinal->Qualification}}</td>
									<td>{{$contractorHumanResourceFinal->Designation}}</td>
									<td>{{$contractorHumanResourceFinal->Trade}}</td>
									<td>
										@foreach($contractorHumanResourceAttachmentsFinal as $contractorHumanResourceAttachmentFinal)
											@if($contractorHumanResourceAttachmentFinal->CrpContractorHumanResourceFinalId==$contractorHumanResourceFinal->Id)
											<a href="{{URL::to($contractorHumanResourceAttachmentFinal->DocumentPath)}}" target="_blank">{{$contractorHumanResourceAttachmentFinal->DocumentName}}</a>,
											@endif
										@endforeach
									</td>
								</tr>
								@endforeach
							</tbody>
						</table>
						<h5 class="font-blue-madison bold">Newly added Human Resource</h5>
						<table id="HumanResource" class="table table-bordered table-striped table-condensed flip-content">
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
								{{--<th>Action</th>--}}
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
											<a href="{{URL::to($contractorHumanResourceAttachment->DocumentPath)}}" target="_blank">{{$contractorHumanResourceAttachment->DocumentName}}</a>,
											@endif
										@endforeach
									</td>
									{{--<td>--}}
										{{--<button type="button" data-cid="{{trim($contractorHumanResource->CIDNo)}}" class="btn blue btn-sm checkhrdbandwebservice">Check</button>--}}
									{{--</td>--}}
									<td>
										@if((int)$contractorHumanResource->Approved==1)
											<i class="fa fa-check"></i>
										@endif
									</td>
								</tr>
								@empty
								<tr>
									<td colspan="10" class="font-red text-center">
										No Change in Human Resource
									</td>
								</tr>
								@endforelse
							</tbody>
						</table>
					</div>
					@if((int)$serviceApplicationApproved == 0)
						<a href="{{URL::to('contractor/edithumanresource/'.$generalInformation[0]->Id.'?redirectUrl=contractor/verifyserviceapplicationprocess')}}" class="btn blue-madison editaction">Edit Human Resource</a>
					@endif
				</div>
				<div class="tab-pane" id="equipment">
					<div class="table-responsive">
					<h5 class="font-blue-madison bold">Existing Equipment</h5>
						<table id="Equipment" class="table table-bordered table-striped table-condensed flip-content">
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
								</tr>
							</thead>
							<tbody>
								@foreach($contractorEquipmentsFinal as $contractorEquipmentFinal)
								<?php $randomKey=randomString(); ?>
								<tr>
									<td>{{$contractorEquipmentFinal->Name}}</td>
									<td>{{$contractorEquipmentFinal->RegistrationNo}}</td>
									<td>{{$contractorEquipmentFinal->ModelNo}}</td>
									<td>{{$contractorEquipmentFinal->Quantity}}</td>
									<td>
										@foreach($contractorEquipmentAttachmentsFinal as $contractorEquipmentAttachmentFinal)
											@if($contractorEquipmentAttachmentFinal->CrpContractorEquipmentFinalId==$contractorEquipmentFinal->Id)
											<a href="{{URL::to($contractorEquipmentAttachmentFinal->DocumentPath)}}" target="_blank">{{$contractorEquipmentAttachmentFinal->DocumentName}}</a>,
											@endif
										@endforeach
									</td>
								</tr>
								@endforeach
							</tbody>
						</table>
						<h5 class="font-blue-madison bold">Newly added Equipment</h5>
						<table id="Equipment" class="table table-bordered table-striped table-condensed flip-content">
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
									{{--<th>Action</th>--}}
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
											<a href="{{URL::to($contractorEquipmentAttachment->DocumentPath)}}" target="_blank">{{$contractorEquipmentAttachment->DocumentName}}</a>,
											@endif
										@endforeach
									</td>
									{{--<td>--}}
										{{--<button type="button" data-regno="{{trim($contractorEquipment->RegistrationNo)}}" data-vehicletype="{{$contractorEquipment->VehicleType}}" class="btn blue btn-sm checkeqdbandwebservice">Check--}}
										{{--</button>--}}
									{{--</td>--}}
									<td>
										@if((int)$contractorEquipment->Approved==1)
											<i class="fa fa-check"></i>
										@endif
									</td>
								</tr>
								@empty
								<tr>
									<td  colspan="7" class="text-center font red">
										No Change in Equipment
									</td>
								</tr>
								@endforelse
							</tbody>
						</table>
					</div>
					@if((int)$serviceApplicationApproved == 0)
						<a href="{{URL::to('contractor/editequipment/'.$generalInformation[0]->Id.'?redirectUrl=contractor/verifyserviceapplicationprocess')}}" class="btn blue-madison editaction">Edit Equipment</a>
					@endif
				</div>
			</div>
		</div>
	</div>
</div>
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
		@if(!isset($serviceApplicationApproved))
			<?php $serviceApplicationApproved = 0; ?>
		@endif
		@if((int)$serviceApplicationApproved == 0)
		{{ Form::open(array('url' => $modelPost,'role'=>'form','id'=>'registrationpaymentdonecontractor'))}}
		<div class="row">
			<div class="col-md-12">
				<h5 class="font-blue-madison bold">Fee Details for Applied Services</h5>
				<table class="table table-bordered table-striped table-condensed flip-content">
					<thead>
						<tr>
							<th>Service(s) Applied</th>
							<th>Details</th>
							<th width="10%" class="text-right">Amount (Nu.)</th>
						</tr>
					</thead>
					<tbody>
						<?php $hasChangeOfOwner = false; $totalFeeApplicable=0; $categoryClassificationFeeTotal=0;?>
						@foreach($appliedServices as $appliedService)
							<?php $randomKey = randomString(); ?>
							@if($appliedService->Id == CONST_SERVICETYPE_CHANGEOWNER)
								<?php $hasChangeOfOwner=true; ?>
							@endif
							<input type="hidden" name="crpcontractorservicepayment[{{$randomKey}}][CmnServiceTypeId]" value="{{$appliedService->Id}}">
						<tr>
							<td @if($appliedService->Id!=CONST_SERVICETYPE_RENEWAL && $appliedService->Id!=CONST_SERVICETYPE_LATEFEE && $appliedService->Id!=CONST_SERVICETYPE_CHANGEOFCATEGORYCLASSIFICATION)colspan="2"@endif>
								{{{$appliedService->ServiceName}}}
							</td>
							@if($appliedService->Id==CONST_SERVICETYPE_RENEWAL || $appliedService->Id==CONST_SERVICETYPE_CHANGEOFCATEGORYCLASSIFICATION)
							<td>
								@if($hasRenewal && $hasChangeInCategoryClassification)
									@if($appliedService->Id==CONST_SERVICETYPE_RENEWAL)
										<table class="table table-hover table-condensed">
											<thead>
												<tr class="success">
													<th>Category</th>
													<th>Existing</th>
													<th>Applied</th>
													<th>Verified</th>
													<th>Approved</th>
													<th>Amount</th>
												</tr>
											</thead>
											<tbody>
												@foreach($hasCategoryClassificationsFee as $hasCategoryClassificationFee)
													<?php $randomKey2 = randomString(); ?>
												<tr>
													<td>{{{$hasCategoryClassificationFee->MasterCategoryCode}}}
														<input type="hidden" value="{{$hasCategoryClassificationFee->MasterCategoryId}}" name="crpcontractorservicepaymentdetail[{{$randomKey2}}][CmnCategoryId]"/>
													</td>
													<td>
														@if((bool)$hasCategoryClassificationFee->FinalApprovedClassification!=NULL)
															{{{$hasCategoryClassificationFee->FinalApprovedClassification}}}

														@else 
															-
														@endif
															<input type="hidden" name="crpcontractorservicepaymentdetail[{{$randomKey2}}][CmnExistingClassificationId]" value="{{$hasCategoryClassificationFee->FinalApprovedClassificationId}}"/>
													</td>
													<td>
														@if((bool)$hasCategoryClassificationFee->AppliedClassification!=NULL)
															{{{$hasCategoryClassificationFee->AppliedClassification}}}
															@if($hasCategoryClassificationFee->AppliedClassificationPriority<$hasCategoryClassificationFee->FinalClassificationPriority)
															<i class="font-red"><small> (Downgraded)</small></i>
															@elseif(!empty($hasCategoryClassificationFee->FinalClassificationPriority) && $hasCategoryClassificationFee->AppliedClassificationPriority>$hasCategoryClassificationFee->FinalClassificationPriority)
															<i class="font-red"><small> (Upgraded)</small></i>
															@elseif(empty($hasCategoryClassificationFee->FinalClassificationPriority))
															<i class="font-red"><small>(New)</small></i>
															@endif
														@else 
															-
														@endif
															<input type="hidden" name="crpcontractorservicepaymentdetail[{{$randomKey2}}][CmnAppliedClassificationId]" value="{{$hasCategoryClassificationFee->AppliedClassificationId}}"/>
													</td>
													<td>
														@if((bool)$hasCategoryClassificationFee->VerifiedClassification!=NULL)
															{{{$hasCategoryClassificationFee->VerifiedClassification}}}
															@if($hasCategoryClassificationFee->VerifiedClassificationPriority<$hasCategoryClassificationFee->FinalClassificationPriority)
															<i class="font-red"><small> (Downgraded)</small></i>
															@elseif(!empty($hasCategoryClassificationFee->FinalClassificationPriority) && $hasCategoryClassificationFee->VerifiedClassificationPriority>$hasCategoryClassificationFee->FinalClassificationPriority)
															<i class="font-red"><small> (Upgraded)</small></i>
															@elseif(empty($hasCategoryClassificationFee->FinalClassificationPriority))
															<i class="font-red"><small>(New)</small></i>
															@endif
														@else 
															-
														@endif
															<input type="hidden" name="crpcontractorservicepaymentdetail[{{$randomKey2}}][CmnVerifiedClassificationId]" value="{{$hasCategoryClassificationFee->VerifiedClassificationId}}"/>
													</td>
													<td>
														@if((bool)$hasCategoryClassificationFee->ApprovedClassification!=NULL)
															{{{$hasCategoryClassificationFee->ApprovedClassification}}}
															@if($hasCategoryClassificationFee->ApprovedClassificationPriority<$hasCategoryClassificationFee->FinalClassificationPriority)
															<i class="font-red"><small> (Downgraded)</small></i>
															@elseif(!empty($hasCategoryClassificationFee->FinalClassificationPriority) && $hasCategoryClassificationFee->ApprovedClassificationPriority>$hasCategoryClassificationFee->FinalClassificationPriority)
															<i class="font-red"><small> (Upgraded)</small></i>
															@elseif(empty($hasCategoryClassificationFee->FinalClassificationPriority))
															<i class="font-red"><small>(New)</small></i>
															@endif
														@else 
															-
														@endif
															<input type="hidden" name="crpcontractorservicepaymentdetail[{{$randomKey2}}][CmnApprovedClassificationId]" value="{{$hasCategoryClassificationFee->ApprovedClassificationId}}"/>
													</td>
													<td>
														<?php $amount = NULL; ?>
														@if((bool)$hasCategoryClassificationFee->ApprovedClassification!=NULL)
															@if($hasCategoryClassificationFee->ApprovedClassificationId==$hasCategoryClassificationFee->FinalApprovedClassificationId)
																<?php $categoryClassificationFeeTotal+=$hasCategoryClassificationFee->ApprovedRenewalFee; ?>
																{{number_format($hasCategoryClassificationFee->ApprovedRenewalFee,2)}}
																<?php $amount = $hasCategoryClassificationFee->ApprovedRenewalFee; ?>
															@else
																<?php $categoryClassificationFeeTotal+=$hasCategoryClassificationFee->ApprovedRegistrationFee; ?>
																{{number_format($hasCategoryClassificationFee->ApprovedRegistrationFee,2)}}
																<?php $amount = $hasCategoryClassificationFee->ApprovedRegistrationFee; ?>
															@endif
														@else 
															-
														@endif
														<input type="hidden" name="crpcontractorservicepaymentdetail[{{$randomKey2}}][Amount]" value="{{$amount}}"/>
													</td>
												</tr>
												@endforeach
												<tr class="bold">
													<td colspan="5">Total</td>
													<td>
														<?php $appliedService->ContractorAmount=$categoryClassificationFeeTotal; ?>
														{{number_format($categoryClassificationFeeTotal,2)}}
													</td>
												</tr>
											</tbody>
										</table>
									@else 
									 <span class="font-red">*Fee details has been already displayed aganist Renewal of CDB Certificate.</span>
									@endif
								@else 
									<table class="table table-hover table-condensed">
										<thead>
											<tr class="success">
												<th>Category</th>
												<th>Existing</th>
												<th>Applied</th>
												<th>Verified</th>
												<th>Approved</th>
												<th>Amount</th>
											</tr>
										</thead>
										<tbody>
											@foreach($hasCategoryClassificationsFee as $hasCategoryClassificationFee)
											<?php $randomKey2 = randomString(); ?>
											<tr>
												<td>
													{{{$hasCategoryClassificationFee->MasterCategoryCode}}}
													<input type="hidden" value="{{$hasCategoryClassificationFee->MasterCategoryId}}" name="crpcontractorservicepaymentdetail[{{$randomKey2}}][CmnCategoryId]"/>
												</td>
												<td>
													@if((bool)$hasCategoryClassificationFee->FinalApprovedClassification!=NULL)
														{{{$hasCategoryClassificationFee->FinalApprovedClassification}}}
													@else 
														-
													@endif
													<input type="hidden" name="crpcontractorservicepaymentdetail[{{$randomKey2}}][CmnExistingClassificationId]" value="{{$hasCategoryClassificationFee->FinalApprovedClassificationId}}"/>
												</td>
												<td>
														@if((bool)$hasCategoryClassificationFee->AppliedClassification!=NULL)
															{{{$hasCategoryClassificationFee->AppliedClassification}}}
															@if($hasCategoryClassificationFee->AppliedClassificationPriority<$hasCategoryClassificationFee->FinalClassificationPriority)
															<i class="font-red"><small> (Downgraded)</small></i>
															@elseif(!empty($hasCategoryClassificationFee->FinalClassificationPriority) && $hasCategoryClassificationFee->AppliedClassificationPriority>$hasCategoryClassificationFee->FinalClassificationPriority)
															<i class="font-red"><small> (Upgraded)</small></i>
															@elseif(empty($hasCategoryClassificationFee->FinalClassificationPriority))
															<i class="font-red"><small>(New)</small></i>
															@endif
														@else 
															-
														@endif
															<input type="hidden" name="crpcontractorservicepaymentdetail[{{$randomKey2}}][CmnAppliedClassificationId]" value="{{$hasCategoryClassificationFee->AppliedClassificationId}}"/>
													</td>
													<td>
														@if((bool)$hasCategoryClassificationFee->VerifiedClassification!=NULL)
															{{{$hasCategoryClassificationFee->VerifiedClassification}}}
															@if($hasCategoryClassificationFee->VerifiedClassificationPriority<$hasCategoryClassificationFee->FinalClassificationPriority)
															<i class="font-red"><small> (Downgraded)</small></i>
															@elseif(!empty($hasCategoryClassificationFee->FinalClassificationPriority) && $hasCategoryClassificationFee->VerifiedClassificationPriority>$hasCategoryClassificationFee->FinalClassificationPriority)
															<i class="font-red"><small> (Upgraded)</small></i>
															@elseif(empty($hasCategoryClassificationFee->FinalClassificationPriority))
															<i class="font-red"><small>(New)</small></i>
															@endif
														@else 
															-
														@endif
														<input type="hidden" name="crpcontractorservicepaymentdetail[{{$randomKey2}}][CmnVerifiedClassificationId]" value="{{$hasCategoryClassificationFee->VerifiedClassificationId}}"/>
													</td>
													<td>
														@if((bool)$hasCategoryClassificationFee->ApprovedClassification!=NULL)
															{{{$hasCategoryClassificationFee->ApprovedClassification}}}
															@if($hasCategoryClassificationFee->ApprovedClassificationPriority<$hasCategoryClassificationFee->FinalClassificationPriority)
															<i class="font-red"><small> (Downgraded)</small></i>
															@elseif(!empty($hasCategoryClassificationFee->FinalClassificationPriority) && $hasCategoryClassificationFee->ApprovedClassificationPriority>$hasCategoryClassificationFee->FinalClassificationPriority)
															<i class="font-red"><small> (Upgraded)</small></i>
															@elseif(empty($hasCategoryClassificationFee->FinalClassificationPriority))
															<i class="font-red"><small>(New)</small></i>
															@endif
														@else 
															-
														@endif
														<input type="hidden" name="crpcontractorservicepaymentdetail[{{$randomKey2}}][CmnApprovedClassificationId]" value="{{$hasCategoryClassificationFee->ApprovedClassificationId}}"/>
													</td>
												<td>
													<?php $amount = NULL; ?>
													@if((bool)$hasCategoryClassificationFee->ApprovedClassification!=NULL)
														@if($hasCategoryClassificationFee->ApprovedClassificationId==$hasCategoryClassificationFee->FinalApprovedClassificationId)
															@if($hasRenewal)
																<?php $categoryClassificationFeeTotal+=$hasCategoryClassificationFee->ApprovedRenewalFee; ?>
																{{number_format($hasCategoryClassificationFee->ApprovedRenewalFee,2)}}
																<?php $amount = $hasCategoryClassificationFee->ApprovedRenewalFee; ?>
															@else
																-
															@endif

														@else
															<?php $categoryClassificationFeeTotal+=$hasCategoryClassificationFee->ApprovedRegistrationFee; ?>
															{{number_format($hasCategoryClassificationFee->ApprovedRegistrationFee,2)}}
															<?php $amount = $hasCategoryClassificationFee->ApprovedRegistrationFee; ?>
														@endif
													@else 
														-
													@endif

													<input type="hidden" name="crpcontractorservicepaymentdetail[{{$randomKey2}}][Amount]" value="{{$amount}}"/>
												</td>
											</tr>
											@endforeach
											<tr class="bold">
												<td colspan="5">Total</td>
												<td>
													<?php $appliedService->ContractorAmount=$categoryClassificationFeeTotal; ?>
													{{number_format($categoryClassificationFeeTotal,2)}}
												</td>
											</tr>
										</tbody>
									</table>
								@endif	
							</td>
							@elseif($appliedService->Id==CONST_SERVICETYPE_LATEFEE)
							<td>
								<table class="table table-hover table-condensed">
									<thead>
										<tr class="danger">
											<th>No. of Days Late (Actual)</th>
											<th>No. of Days Late After Grace Period</th>
											<th>Penalty per Day (Nu.)</th>
											<th>Total Amount (Nu.)</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>
												{{$hasLateFeeAmount[0]->PenaltyNoOfDays-1}}<br />
												<small><i class="font-red">* 30 days is grace period.</i></small><br />
												<small><i class="font-red">* Registration Expired on {{convertDateToClientFormat($hasLateFeeAmount[0]->RegistrationExpiryDate)}}</i></small><br />
												<small><i class="font-red">* Application for Renewal on {{convertDateToClientFormat($hasLateFeeAmount[0]->ApplicationDate)}}</i></small>
												<input type="hidden" name="latefeedetails[NoOfDaysLate]" value="{{$hasLateFeeAmount[0]->PenaltyNoOfDays-1}}"/>
											</td>
											<td>
												<?php $lateFeeAfterGracePeriod=$hasLateFeeAmount[0]->PenaltyNoOfDays-30-1; ?>
												@if(($hasLateFeeAmount[0]->PenaltyNoOfDays-1)>30)
													<?php $lateFeeAfterGracePeriod=$hasLateFeeAmount[0]->PenaltyNoOfDays-30-1; ?>
												@else
													<?php $lateFeeAfterGracePeriod = 0; ?>
												@endif
												@if($lateFeeAfterGracePeriod>0)
												{{$lateFeeAfterGracePeriod}}
												@endif
													<input type="hidden" name="latefeedetails[NoOfDaysAfterGracePeriod]" value="{{$lateFeeAfterGracePeriod}}"/>
											</td>
											<td>
												{{number_format($hasLateFeeAmount[0]->PenaltyLateFeeAmount,2)}}
												<input type="hidden" name="latefeedetails[PenaltyPerDay]" value="{{$hasLateFeeAmount[0]->PenaltyLateFeeAmount}}"/>
											</td>
											<td>
												<?php $lateFeeAmount=(int)$lateFeeAfterGracePeriod*(int)$hasLateFeeAmount[0]->PenaltyLateFeeAmount; ?>
												@if((int)$maxClassification == 998 || (int)$maxClassification == 997)
													@if($lateFeeAmount > 3000)
														<?php $lateFeeAmount = 3000; ?>
													@endif
												@endif
												{{number_format($lateFeeAmount,2)}}
													<input type="hidden" name="latefeedetails[TotalAmount]" value="{{$lateFeeAmount}}"/>
											</td>
										</tr>
										<tr class="bold">
											<td colspan="3">Total</td>
											<td>
												<?php $appliedService->ContractorAmount=$lateFeeAmount;?>
												@if($generalInformation[0]->WaiveOffLateFee == 1)
													<span style="text-decoration: line-through;">
												@endif
												{{number_format($lateFeeAmount,2)}}
												@if($generalInformation[0]->WaiveOffLateFee == 1)
												</span>
													&nbsp;
												{{{number_format($generalInformation[0]->NewLateFeeAmount,2)}}}
												<input type="hidden" name="latefeedetails[WaiveOffLateFee]" value="1"/>
												<input type="hidden" name="latefeedetails[NewLateFeeAmount]" value="{{$generalInformation[0]->NewLateFeeAmount}}"/>
												@endif
											</td>
										</tr>
										<tr>
											<td colspan="1">
												<div class="form-group pull-right">
													<label for="">
														Waiver Late Fee
														<input type="checkbox" name="WaiveOffLateFee" @if($generalInformation[0]->WaiveOffLateFee == 1)checked="checked"@endif disabled value="1" id="waiver">
													</label>
												</div>
											</td>
											<td colspan="3">
												<div class="form-group">
													<input type="text" disabled class="form-control number input-sm input-medium" value="@if($generalInformation[0]->WaiveOffLateFee == 1){{{$generalInformation[0]->NewLateFeeAmount}}}@endif" name="NewLateFeeAmount" placeholder="New Late Fees Amount" {{--@if($generalInformation[0]->WaiveOffLateFee == 0)disabled="disabled"@endif--}}>
												</div>
											</td>
										</tr>
									</tbody>
								</table>
							</td>
							@endif
							<td class="text-right">
								@if((bool)$appliedService->ContractorAmount!=NULL)
								@if($appliedService->Id==CONST_SERVICETYPE_LATEFEE)
									@if($generalInformation[0]->WaiveOffLateFee == 1)
										<span style="text-decoration: line-through;">
									@endif
								@endif
								@if($appliedService->Id == CONST_SERVICETYPE_INCORPORATION)
									@if($generalInformation[0]->OwnershipType!=$generalInformationFinal[0]->OwnershipType)
										<?php $totalFeeApplicable+=$appliedService->ContractorAmount; ?>
										{{{number_format($appliedService->ContractorAmount,2)}}}
										<input type="hidden" name="crpcontractorservicepayment[{{$randomKey}}][PaymentAmount]" value="{{$appliedService->ContractorAmount}}"/>
									@else
										0.00 <br>(This firm was already incorporated)
											<input type="hidden" name="crpcontractorservicepayment[{{$randomKey}}][PaymentAmount]" value="0"/>
									@endif
								@else
									<?php $totalFeeApplicable+=$appliedService->ContractorAmount; ?>
									{{{number_format($appliedService->ContractorAmount,2)}}}
									<input type="hidden" name="crpcontractorservicepayment[{{$randomKey}}][PaymentAmount]" value="{{$appliedService->ContractorAmount}}"/>
								@endif

									@if($appliedService->Id==CONST_SERVICETYPE_LATEFEE)
										@if($generalInformation[0]->WaiveOffLateFee == 1)
										<input type="hidden" name="crpcontractorservicepayment[{{$randomKey}}][WaiveOffLateFee]" value="1"/>
										</span>
											&nbsp;
											{{{$generalInformation[0]->NewLateFeeAmount}}}
											<input type="hidden" name="crpcontractorservicepayment[{{$randomKey}}][NewLateFeeAmount]" value="{{$generalInformation[0]->NewLateFeeAmount}}"/>
										@endif
									@endif
								@else 
								-
								@endif
							</td>
						</tr>
						@endforeach
						<tr class="text-right bold">
							<td colspan="2">Total Amount Payable</td>

							<td>
								@if($generalInformation[0]->WaiveOffLateFee == 1)
									<span style="text-decoration: line-through;">
								@endif
									{{{number_format($totalFeeApplicable,2)}}}
								@if($generalInformation[0]->WaiveOffLateFee == 1)
									</span>
									<br>
									{{{number_format((int)$totalFeeApplicable-((int)$lateFeeAmount-(int)$generalInformation[0]->NewLateFeeAmount),2)}}}
								@endif
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>

		<div class="form-body">
			@include('crps.applicationhistory')
			<input type="hidden" name="PaymentApprovedDate" value="{{date('Y-m-d G:i:s')}}" />
			<input type="hidden" name="SysLockedByUserId" value="" />
			<input type="hidden" name="ContractorReference" value="{{$generalInformation[0]->Id}}" />
			<input type="hidden" name="CmnApplicationRegistrationStatusId" value="{{CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED}}" />
			<div class="row">
				<div class="col-md-4">
					<div class="form-group">
						<label class="control-label">Payment Date</label>
						<div class="input-icon right">
							<i class="fa fa-calendar"></i>
							<input type="text" name="PaymentReceiptDate" class="form-control datepicker required" placeholder="" readonly="readonly"/>
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label class="control-label">Receipt No.</label>
						<input type="text" name="PaymentReceiptNo" class="form-control required" class="text-right" />
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group">
						<input type="hidden" name="SysPaymentApproverUserId" value="{{Auth::user()->Id}}">
						<label>Remarks</label>
						<textarea name="RemarksByPaymentApprover" class="form-control required" rows="3"></textarea>
					</div>
				</div>
			</div>
		</div>
		<div class="form-actions">
			<div class="btn-set">
				<a href="#paymentdoneregistration" data-toggle="modal" class="btn green">Approve Payment</a>
				@if($isAdmin)
					<a href="#sendback" data-toggle="modal" class="btn purple">Send back to Approver</a>
				@endif
			</div>
		</div>
		{{Form::close()}}
		@else
			<div class="form-body">
				@include('crps.applicationhistory')
				{{ Form::open(array('url' => 'contractor/msavefinalremarks','role'=>'form','id'=>'registrationpaymentdonecontractor'))}}
				<input type="hidden" name="ContractorReference" value="{{$generalInformation[0]->Id}}" />
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<input type="hidden" name="IsServiceApplication" value="1"/>
							<input type="hidden" name="SysFinalApproverUserId" value="{{Auth::user()->Id}}">
							<input type="hidden" name="SysFinalApprovedDate" value="{{date('Y-m-d G:i:s')}}">
							<strong>Payment Receipt Date: {{convertDateToClientFormat($generalInformation[0]->PaymentReceiptDate)}}</strong> <br>
							<strong>Payment Receipt No.: {{$generalInformation[0]->PaymentReceiptNo}}</strong> <br> <br>
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
		@endif
	</div>
</div>
@stop