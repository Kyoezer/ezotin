@extends('master')
@section('pagescripts')
{{ HTML::script('assets/global/scripts/consultant.js') }}
@stop
@section('lastcdbno')
<div class="page-toolbar">
	<div id="dashboard-report-range" class="pull-right tooltips btn btn-fit-height grey-cascade" data-placement="top" data-original-title="Last used Contractor CDB No.">
		<span class="thin visible-lg-inline-block">Last used CDB No.: <span class="bold">{{lastUsedConsultantCDBNo();}}</span></span>
	</div>
</div>
@stop
@section('content')
	<div id="sendback" class="modal fade" role="dialog" aria-labelledby="sendback" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				{{Form::open(array('url'=>'sendapplicationback','role'=>'form'))}}
				<input type="hidden" name="Id" value="{{$generalInformation[0]->Id}}" />
				<input type="hidden" name="Model" value="ConsultantModel"/>
				<input type="hidden" name="Status" value="{{CONST_CMN_APPLICATIONREGISTRATIONSTATUS_VERIFIED}}"/>
				<input type="hidden" name="RedirectUrl" value="consultant/approveserviceapplicationfeepaymentlist"/>
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
				<button id="approveconsultantregistration" type="button" class="btn green">Approve</button>
				<button class="btn red" data-dismiss="modal" aria-hidden="true">Cancel</button>
			</div>
		</div>
	</div>
</div>
<div id="reject" class="modal fade" role="dialog" aria-labelledby="reject" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			{{ Form::open(array('url' => 'consultant/mrejectregistration','role'=>'form'))}}
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h3 class="modal-title font-green-seagreen">Reject Registration</h3>
			</div>
			<div class="modal-body">
				<input type="hidden" name="RedirectRoute" value="verifyregistration">
				<p class="bold">Are you sure you want to reject this application?</p>
				<input type="hidden" name="ConsultantReference" value="{{$generalInformation[0]->Id}}" />
				<input type="hidden" name="RejectedDate" value="{{date('Y-m-d G:i:s')}}" />
				<div class="form-group">
					<label>Remarks</label>
					<textarea name="RemarksByRejector" class="form-control" rows="3"></textarea>
				</div>
			</div>
			<div class="modal-footer">
				<button id="rejectconsultantregistration" type="submit" class="btn green">Reject</button>
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
			<div class="col-md-12">
				<h5 class="font-blue-madison bold">General Information</h5>
			</div>
			<div class="col-md-6 table-responsive">
				<table class="table table-bordered table-striped table-condensed">
					<tbody>
						<tr>
							<td><strong>CDB No.</strong></td>
							<td>
								{{$generalInformationFinal[0]->CDBNo}}
							</td>
						</tr>
						<tr>
							<td><strong>Ownership Type</strong></td>
							<td>
								@if($generalInformation[0]->OwnershipType==$generalInformationFinal[0]->OwnershipType)
									{{$generalInformation[0]->OwnershipType}}
								@else
									<span class="font-red">{{{$generalInformation[0]->OwnershipType}}}</span>
									<a href="#" class="tooltips" data-placement="top" data-original-title="{{{$generalInformationFinal[0]->OwnershipType}}}"><i class="font-blue">[old]</i></a>
								@endif
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
							<td><strong>Previous Validity</strong></td>
							<td>
								{{convertDateToClientFormat($generalInformationFinal[0]->RegistrationApprovedDate)}} to {{convertDateToClientFormat($generalInformationFinal[0]->RegistrationExpiryDate)}}
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="col-md-6 table-responsive">
				<table class="table table-bordered table-striped table-condensed flip-content">
					<tbody>
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
					</tbody>	
				</table>
			</div>
		</div>
		<div class="tabbable-custom nav-justified">
			<ul class="nav nav-tabs nav-justified">
				<li class="active">
					<a href="#partnerdetails" data-toggle="tab">
					General Info/Owner/Partner Details</a>
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
					<div class="table-responsive">
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
									<th>Action</th>
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
									<td>
										<button type="button" data-cid="{{trim($ownerPartnerDetail->CIDNo)}}" class="btn blue btn-sm checkhrdbandwebservice">Check</button>
									</td>
								</tr>
								@empty
									<tr>
										<td colspan="7" class="text-center">No new owners/partners</td>
									</tr>
								@endforelse
							</tbody>
						</table>
					</div>		
					@if($serviceApplicationApproved == 0)
						<a href="{{URL::to('consultant/editgeneralinfo/'.$generalInformation[0]->Id.'?redirectUrl=consultant/verifyserviceapplicationprocess')}}" class="btn blue-madison editaction">Edit General Information/Partner Details</a>
					@endif
				</div>
				<div class="tab-pane" id="workclassification">
					<input type="hidden" name="ConsultantId" value="{{$generalInformation[0]->Id}}" />
					<h5 class="font-blue-madison bold">Existing Category/Classification</h5>
					<table class="table table-bordered table-striped table-condensed">
						<thead class="">
							<tr>
								<th width="40%">
									Service Category
								</th>
								<th>
									Service
								</th>
							</tr>
						</thead>
						<tbody>
							@foreach($currentServiceClassifications as $currrentServiceClassification)
								<tr>
									<td class="small-medium">{{$currrentServiceClassification->Category}}</td>
									<td>{{$currrentServiceClassification->ApprovedService}}</td>
								</tr>
							@endforeach
						</tbody>
					</table>
					<h5 class="font-blue-madison bold">Change in Category/Classification</h5>
					<table class="table table-bordered table-striped table-condensed">
						<thead>
							<tr>
								<th>
									Category
								</th>
								<th>
									Applied
								</th>
								<th>
									Assessed
								</th>
								<th>
									Approve
								</th>
							</tr>
						</thead>
						<tbody>
							@foreach($appliedCategories as $appliedCategory)
								<tr>
									<td>{{$appliedCategory->Category}}</td>
									<td>
										@foreach($appliedCategoryServices as $appliedCategoryService)
											@if($appliedCategoryService->CmnServiceCategoryId==$appliedCategory->Id)
											<span class="font-green-seagreen" data-toggle="tooltip" title="{{$appliedCategoryService->ServiceName}}"><i class="fa fa-question-circle"></i></span>
											{{$appliedCategoryService->ServiceCode}}
										@endif
										@endforeach
									</td>
									<td>
										@foreach($verifiedCategoryServices as $verifiedCategoryService)
											<?php $randomKey=randomString(); ?>
											@if($verifiedCategoryService->CmnServiceCategoryId==$appliedCategory->Id)
											<span>
												<span class="font-green-seagreen" data-toggle="tooltip" title="{{$verifiedCategoryService->ServiceName}}"><i class="fa fa-question-circle"></i></span>
												{{$verifiedCategoryService->ServiceCode}}
											</span>
											@endif
										@endforeach
									</td>
									<td>
										@foreach($verifiedCategoryServices as $verifiedCategoryService)
										<?php $randomKey=randomString(); ?>
											@if($verifiedCategoryService->CmnServiceCategoryId==$appliedCategory->Id)
											<span>
												<input type="hidden" name="ConsultantWorkClassificationModel[{{$randomKey}}][Id]" value="{{$verifiedCategoryService->Id}}" class="setcheckcontrol" disabled="disabled">
												<input type="checkbox" name="ConsultantWorkClassificationModel[{{$randomKey}}][CmnVerifiedServiceId]" value="{{$verifiedCategoryService->ServiceId}}" class="consultantservicecheck" checked />
												<span class="font-green-seagreen" data-toggle="tooltip" title="{{$verifiedCategoryService->ServiceName}}"><i class="fa fa-question-circle"></i></span>
												{{$verifiedCategoryService->ServiceCode}}
											</span>
											@endif
										@endforeach
									</td>
								</tr>
								@endforeach
						</tbody>
					</table>
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
								<th>Action</th>
							</thead>
							<tbody>
								@foreach($consultantHumanResourcesFinal as $consultantHumanResourceFinal)
								<tr>
									<td>{{$consultantHumanResourceFinal->Salutation.' '.$consultantHumanResourceFinal->Name}}</td>
									<td>{{$consultantHumanResourceFinal->CIDNo}}</td>
									<td>{{$consultantHumanResourceFinal->Sex}}</td>
									<td>{{$consultantHumanResourceFinal->Country}}</td>
									<td>{{$consultantHumanResourceFinal->Qualification}}</td>
									<td>{{$consultantHumanResourceFinal->Designation}}</td>
									<td>{{$consultantHumanResourceFinal->Trade}}</td>
									<td>
										@foreach($consultantHumanResourceAttachmentsFinal as $consultantHumanResourceAttachmentFinal)
											@if($consultantHumanResourceAttachmentFinal->CrpConsultantHumanResourceId==$consultantHumanResourceFinal->Id)
											<a href="{{URL::to($consultantHumanResourceAttachmentFinal->DocumentPath)}}" target="_blank">{{$consultantHumanResourceAttachmentFinal->DocumentName}}</a>,
											@endif
										@endforeach
									</td>
									<td>
										<button type="button" data-cid="{{trim($consultantHumanResourceFinal->CIDNo)}}" class="btn blue btn-sm checkhrdbandwebservice">Check</button>
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
								<th>
									Approved
								</th>
								<th>Action</th>
							</thead>
							<tbody>
								@forelse($consultantHumanResources as $consultantHumanResource)
								<?php $randomKey=randomString(); ?>
								<tr>
									<td>{{$consultantHumanResource->Salutation.' '.$consultantHumanResource->Name}}</td>
									<td>{{$consultantHumanResource->CIDNo}}</td>
									<td>{{$consultantHumanResource->Sex}}</td>
									<td>{{$consultantHumanResource->Country}}</td>
									<td>{{$consultantHumanResource->Qualification}}</td>
									<td>{{$consultantHumanResource->Designation}}</td>
									<td>{{$consultantHumanResource->Trade}}</td>
									<td>
										@foreach($consultantHumanResourceAttachments as $consultantHumanResourceAttachment)
											@if($consultantHumanResourceAttachment->CrpConsultantHumanResourceId==$consultantHumanResource->Id)
											<a href="{{URL::to($consultantHumanResourceAttachment->DocumentPath)}}" target="_blank">{{$consultantHumanResourceAttachment->DocumentName}}</a>,
											@endif
										@endforeach
									</td>
									<td>
										@if((int)$consultantHumanResource->Approved==1)
											<i class="fa fa-check"></i>
										@endif
									</td>
									<td>
									<td>
										<button type="button" data-cid="{{trim($consultantHumanResource->CIDNo)}}" class="btn blue btn-sm checkhrdbandwebservice">Check</button>
									</td>
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
					@if($serviceApplicationApproved == 0)
						<a href="{{URL::to('consultant/edithumanresource/'.$generalInformation[0]->Id.'?redirectUrl=consultant/verifyserviceapplicationprocess')}}" class="btn blue-madison editaction">Edit Human Resource</a>
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
								@foreach($consultantEquipmentsFinal as $consultantEquipmentFinal)
								<?php $randomKey=randomString(); ?>
								<tr>
									<td>{{$consultantEquipmentFinal->Name}}</td>
									<td>{{$consultantEquipmentFinal->RegistrationNo}}</td>
									<td>{{$consultantEquipmentFinal->ModelNo}}</td>
									<td>{{$consultantEquipmentFinal->Quantity}}</td>
									<td>
										@foreach($consultantEquipmentAttachmentsFinal as $consultantEquipmentAttachmentFinal)
											@if($consultantEquipmentAttachmentFinal->CrpConsultantEquipmentId==$consultantEquipmentFinal->Id)
											<a href="{{URL::to($consultantEquipmentAttachmentFinal->DocumentPath)}}" target="_blank">{{$consultantEquipmentAttachmentFinal->DocumentName}}</a>,
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
									<th class="table-checkbox">
										Approved
									</th>
								</tr>
							</thead>
							<tbody>
								@forelse($consultantEquipments as $consultantEquipment)
								<?php $randomKey=randomString(); ?>
								<tr>
									<td>{{$consultantEquipment->Name}}</td>
									<td>{{$consultantEquipment->RegistrationNo}}</td>
									<td>{{$consultantEquipment->ModelNo}}</td>
									<td>{{$consultantEquipment->Quantity}}</td>
									<td>
										@foreach($consultantEquipmentAttachments as $consultantEquipmentAttachment)
											@if($consultantEquipmentAttachment->CrpConsultantEquipmentId==$consultantEquipment->Id)
											<a href="{{URL::to($consultantEquipmentAttachment->DocumentPath)}}" target="_blank">{{$consultantEquipmentAttachment->DocumentName}}</a>,
											@endif
										@endforeach
									</td>
									{{--<td>--}}
										{{--<button type="button" data-regno="{{trim($consultantEquipment->RegistrationNo)}}" data-vehicletype="{{$consultantEquipment->VehicleType}}" class="btn blue btn-sm checkeqdbandwebservice">Check--}}
										{{--</button>--}}
									</td>
									<td>
										@if((int)$consultantEquipment->Approved==1)
											<i class="fa fa-check"></i>
										@endif
									</td>
								</tr>
								@empty
								<tr>
									<td  colspan="6" class="text-center font red">
										No Change in Equipment
									</td>
								</tr>
								@endforelse
							</tbody>
						</table>
					</div>
					@if($serviceApplicationApproved == 0)
						<a href="{{URL::to('consultant/editequipment/'.$generalInformation[0]->Id.'?redirectUrl=consultant/verifyserviceapplicationprocess')}}" class="btn blue-madison editaction">Edit Equipment</a>
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
				<button id="approveconsultantpaymentregistration" type="button" class="btn green">Approve</button>
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
	<div class="row">
		{{ Form::open(array('url' => $modelPost,'role'=>'form','id'=>'registrationpaymentdoneconsultant'))}}
		<div class="col-md-12">
			<h5 class="font-blue-madison bold">Fee Details for Applied Services</h5>
			<table class="table table-bordered table-striped table-condensed dont-flip">
				<thead>
				<tr>
					<th>Service(s) Applied</th>
					<th>Details</th>
					<th width="10%" class="text-right">Fee (Nu.)</th>
				</tr>
				</thead>
				<tbody>
				<?php $totalFeeApplicable=0; $categoryClassificationFeeTotal=0;$lateFeeAmount=0;?>
				@foreach($appliedServices as $appliedService)
					<?php $randomKey = randomString(); ?>
					<input type="hidden" name="crpconsultantservicepayment[{{$randomKey}}][CmnServiceTypeId]" value="{{$appliedService->Id}}">
					<tr>
						<td @if($appliedService->Id!=CONST_SERVICETYPE_RENEWAL && $appliedService->Id!=CONST_SERVICETYPE_LATEFEE && $appliedService->Id!=CONST_SERVICETYPE_CHANGEOFCATEGORYCLASSIFICATION)colspan="2"@endif>
							{{{$appliedService->ServiceName}}}
						</td>
						@if($appliedService->Id==CONST_SERVICETYPE_RENEWAL || $appliedService->Id==CONST_SERVICETYPE_CHANGEOFCATEGORYCLASSIFICATION)
							<td>
								@if($hasRenewal && $hasChangeInCategoryClassification)
									@if($appliedService->Id==CONST_SERVICETYPE_RENEWAL)
										<table class="table table-bordered table-hover table-condensed">
											<thead>
											<tr class="success">
												<th>Category</th>
												<th>Applied</th>
												<th>Verified</th>
												<th>Approved</th>
												<th>No. of Service (Approved) X Fee</th>
												<th class="text-right">Total</th>
											</tr>
											</thead>
											<tbody>
											@foreach($hasCategoryClassificationsFee as $hasCategoryClassificationFee)
												<?php $approvedServiceCount = $newServiceCount = $oldServiceCount = 0; $feeString = ""; $randomKey3 = randomString(); ?>
												<tr>
													<td>
														<input type="hidden" name="crpconsultantservicepaymentdetail[{{$randomKey3}}][CmnServiceCategoryId]" value="{{$hasCategoryClassificationFee->ServiceCategoryId}}"/>{{{$hasCategoryClassificationFee->ServiceCategoryName}}}</td>
													<td>
														@if((bool)$hasCategoryClassificationFee->AppliedService!=NULL)
															{{{$hasCategoryClassificationFee->AppliedService}}}
														@else
															-
														@endif
														<input type="hidden" name="crpconsultantservicepaymentdetail[{{$randomKey3}}][AppliedService]" value="{{$hasCategoryClassificationFee->AppliedService}}"/>
													</td>
													<td>
														@if((bool)$hasCategoryClassificationFee->VerifiedService!=NULL)
															{{{$hasCategoryClassificationFee->VerifiedService}}}
														@else
															-
														@endif
														<input type="hidden" name="crpconsultantservicepaymentdetail[{{$randomKey3}}][VerifiedService]" value="{{$hasCategoryClassificationFee->VerifiedService}}"/>
													</td>
													<td>
														@if((bool)$hasCategoryClassificationFee->ApprovedService!=NULL)
															{{{$hasCategoryClassificationFee->ApprovedService}}}
														@else
															-
														@endif
														<input type="hidden" name="crpconsultantservicepaymentdetail[{{$randomKey3}}][ApprovedService]" value="{{$hasCategoryClassificationFee->ApprovedService}}"/>

														@if($appliedService->Id==CONST_SERVICETYPE_RENEWAL)
															@foreach($approvedCategoryServicesArray[$hasCategoryClassificationFee->ServiceCategoryId] as $approvedServiceIndividual)
																<?php $approvedServiceCount++; ?>
																@if(!in_array($approvedServiceIndividual,$existingCategoryServicesArray[$hasCategoryClassificationFee->ServiceCategoryId]))
																	<?php $newServiceCount++; ?>
																@else
																	<?php $oldServiceCount++; ?>
																@endif
															@endforeach
														@else
															@foreach($approvedCategoryServicesArray[$hasCategoryClassificationFee->ServiceCategoryId] as $approvedServiceIndividual)
																@if(!in_array($approvedServiceIndividual,$existingCategoryServicesArray[$hasCategoryClassificationFee->ServiceCategoryId]))
																	<?php $approvedServiceCount++; ?>
																@endif
															@endforeach
														@endif
													</td>
													<td class="text-center">
														@if($appliedService->Id==CONST_SERVICETYPE_RENEWAL)
															@if((int)$newServiceCount>0)
																{{$newServiceCount.' X '.number_format($newRegistrationAmount)}}
																<?php $feeString.=$newServiceCount.' X '.number_format($newRegistrationAmount); ?>
															@endif
															@if((int)$oldServiceCount>0)
																@if($feeString!="")
																	<?php
																	echo ", ";
																	$feeString.=", ";
																	?>
																@endif
																{{$oldServiceCount.' X '.number_format($feeAmount[0]->ConsultantAmount)}}
																<?php $feeString.=$oldServiceCount.' X '.number_format($feeAmount[0]->ConsultantAmount); ?>
															@endif
															<input type="hidden" data-oldservice="{{$oldServiceCount}}" data-newservice="{{$newServiceCount}}" name="crpconsultantservicepaymentdetail[{{$randomKey3}}][ServiceXFee]" value="{{$feeString}}"/>
														@else
															{{$approvedServiceCount.' X '.number_format($feeAmount[0]->ConsultantAmount)}}
															<input type="hidden" name="crpconsultantservicepaymentdetail[{{$randomKey3}}][ServiceXFee]" value="{{$approvedServiceCount.' X '.number_format($feeAmount[0]->ConsultantAmount)}}"/>
														@endif
														{{--{{$approvedServiceCount.' X '.number_format($feeAmount[0]->ConsultantAmount)}}--}}
														{{--<input type="hidden" name="crpconsultantservicepaymentdetail[{{$randomKey3}}][ServiceXFee]" value="{{$approvedServiceCount.' X '.number_format($feeAmount[0]->ConsultantAmount)}}"/>--}}

													</td>
													<td class="text-right">
														@if($appliedService->Id==CONST_SERVICETYPE_RENEWAL)
															<?php $categoryClassificationFeeTotal+=$oldServiceCount* $feeAmount[0]->ConsultantAmount;?>
															<?php $categoryClassificationFeeTotal+=$newServiceCount* $newRegistrationAmount;?>
															{{number_format(($oldServiceCount* $feeAmount[0]->ConsultantAmount)+($newServiceCount* $newRegistrationAmount))}}
															<input type="hidden" name="crpconsultantservicepaymentdetail[{{$randomKey3}}][Amount]" value="{{(($oldServiceCount* $feeAmount[0]->ConsultantAmount)+($newServiceCount* $newRegistrationAmount))}}"/>
														@else
															<?php $categoryClassificationFeeTotal+=$approvedServiceCount* $feeAmount[0]->ConsultantAmount;?>
															{{number_format($approvedServiceCount* $feeAmount[0]->ConsultantAmount)}}
															<input type="hidden" name="crpconsultantservicepaymentdetail[{{$randomKey3}}][Amount]" value="{{$approvedServiceCount* $feeAmount[0]->ConsultantAmount}}"/>
														@endif
													</td>
												</tr>
											@endforeach
											<tr class="bold">
												<td colspan="5">Total</td>
												<td class="text-right">
													<?php $appliedService->ConsultantAmount=$categoryClassificationFeeTotal; ?>
													{{number_format($categoryClassificationFeeTotal,2)}}
												</td>
											</tr>
											</tbody>
										</table>
									@else
										<span class="font-red">*Fee details has been already displayed against Renewal of CDB Certificate.</span>
									@endif
								@else
									<table class="table table-bordered table-hover table-condensed">
										<thead>
										<tr class="success">
											<th>Category</th>
											<th>Applied</th>
											<th>Verified</th>
											<th>Approved</th>
											<th>No. of Service (Approved) X Fee</th>
											<th class="text-right">Total</th>
										</tr>
										</thead>
										<tbody>
										@foreach($hasCategoryClassificationsFee as $hasCategoryClassificationFee)
											<?php $randomKey2 = randomString(); ?>
											<?php $approvedServiceCount = $newServiceCount = $oldServiceCount = 0; $feeString = "";?>
											<tr>
												<td>
													<input type="hidden" name="crpconsultantservicepaymentdetail[{{$randomKey2}}][CmnServiceCategoryId]" value="{{$hasCategoryClassificationFee->ServiceCategoryId}}"/>
													{{{$hasCategoryClassificationFee->ServiceCategoryName}}}
												</td>
												<td>
													@if((bool)$hasCategoryClassificationFee->AppliedService!=NULL)
														{{{$hasCategoryClassificationFee->AppliedService}}}

													@else
														-
													@endif
													<input type="hidden" name="crpconsultantservicepaymentdetail[{{$randomKey2}}][AppliedService]" value="{{$hasCategoryClassificationFee->AppliedService}}"/>
												</td>
												<td>
													@if((bool)$hasCategoryClassificationFee->VerifiedService!=NULL)
														{{{$hasCategoryClassificationFee->VerifiedService}}}
													@else
														-
													@endif
													<input type="hidden" name="crpconsultantservicepaymentdetail[{{$randomKey2}}][VerifiedService]" value="{{$hasCategoryClassificationFee->VerifiedService}}"/>
												</td>
												<td>
													@if((bool)$hasCategoryClassificationFee->ApprovedService!=NULL)
														{{{$hasCategoryClassificationFee->ApprovedService}}}
													@else
														-
													@endif
													<input type="hidden" name="crpconsultantservicepaymentdetail[{{$randomKey2}}][ApprovedService]" value="{{$hasCategoryClassificationFee->ApprovedService}}"/>
													@if($appliedService->Id==CONST_SERVICETYPE_RENEWAL || $appliedService->Id==CONST_SERVICETYPE_CHANGEOFCATEGORYCLASSIFICATION)
														@foreach($approvedCategoryServicesArray[$hasCategoryClassificationFee->ServiceCategoryId] as $approvedServiceIndividual)
															<?php $approvedServiceCount++; ?>
															@if(!in_array($approvedServiceIndividual,$existingCategoryServicesArray[$hasCategoryClassificationFee->ServiceCategoryId]))
																<?php $newServiceCount++; ?>
															@else
                                                                                                                           @if($appliedService->Id == CONST_SERVICETYPE_RENEWAL)
																<?php $oldServiceCount++; ?>
                                                                                                                           @endif
															@endif
														@endforeach
													@else
														@foreach($approvedCategoryServicesArray[$hasCategoryClassificationFee->ServiceCategoryId] as $approvedServiceIndividual)
															@if(!in_array($approvedServiceIndividual,$existingCategoryServicesArray[$hasCategoryClassificationFee->ServiceCategoryId]))
																<?php $approvedServiceCount++; ?>
															@endif
														@endforeach
													@endif
												</td>
												<td class="text-center">
													@if($appliedService->Id==CONST_SERVICETYPE_RENEWAL || $appliedService->Id == CONST_SERVICETYPE_CHANGEOFCATEGORYCLASSIFICATION)
														@if((int)$newServiceCount>0)
															{{$newServiceCount.' X '.number_format($newRegistrationAmount)}}
															<?php $feeString.=$newServiceCount.' X '.number_format($newRegistrationAmount); ?>
														@endif
														@if((int)$oldServiceCount>0)
															@if($feeString!="")
																<?php
																	echo ", ";
																	$feeString.=", ";
																?>
															@endif
															{{$oldServiceCount.' X '.number_format($feeAmount[0]->ConsultantAmount)}}
															<?php $feeString.=$oldServiceCount.' X '.number_format($feeAmount[0]->ConsultantAmount); ?>
														@endif
														<input type="hidden" name="crpconsultantservicepaymentdetail[{{$randomKey2}}][ServiceXFee]" value="{{$feeString}}"/>
													@else
														{{$approvedServiceCount.' X '.number_format($feeAmount[0]->ConsultantAmount)}}
														<input type="hidden" name="crpconsultantservicepaymentdetail[{{$randomKey2}}][ServiceXFee]" value="{{$approvedServiceCount.' X '.number_format($feeAmount[0]->ConsultantAmount)}}"/>
													@endif
												</td>
												<td class="text-right">
													@if($appliedService->Id==CONST_SERVICETYPE_RENEWAL || $appliedService->Id==CONST_SERVICETYPE_CHANGEOFCATEGORYCLASSIFICATION)
														<?php $categoryClassificationFeeTotal+=$oldServiceCount* $feeAmount[0]->ConsultantAmount;?>
														<?php $categoryClassificationFeeTotal+=$newServiceCount* $newRegistrationAmount;?>
														{{number_format(($oldServiceCount* $feeAmount[0]->ConsultantAmount)+($newServiceCount* $newRegistrationAmount))}}
														<input type="hidden" name="crpconsultantservicepaymentdetail[{{$randomKey2}}][Amount]" value="{{(($oldServiceCount* $feeAmount[0]->ConsultantAmount)+($newServiceCount* $newRegistrationAmount))}}"/>
													@else
														<?php $categoryClassificationFeeTotal+=$approvedServiceCount* $feeAmount[0]->ConsultantAmount;?>
														{{number_format($approvedServiceCount* $feeAmount[0]->ConsultantAmount)}}
														<input type="hidden" name="crpconsultantservicepaymentdetail[{{$randomKey2}}][Amount]" value="{{$approvedServiceCount* $feeAmount[0]->ConsultantAmount}}"/>
													@endif

												</td>
											</tr>
										@endforeach
										<tr class="bold">
											<td colspan="5">Total</td>
											<td class="text-right">
												<?php $appliedService->ConsultantAmount=$categoryClassificationFeeTotal; ?>
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
											<small><i class="font-red">Registration Expired on {{convertDateToClientFormat($hasLateFeeAmount[0]->RegistrationExpiryDate)}}</i></small><br />
											<small><i class="font-red">* Application for Renewal on {{convertDateToClientFormat($hasLateFeeAmount[0]->ApplicationDate)}}</i></small>
											<input type="hidden" name="latefeedetails[NoOfDaysLate]" value="{{$hasLateFeeAmount[0]->PenaltyNoOfDays-1}}"/>
										</td>
										<td>
											<?php $lateFeeAfterGracePeriod=$hasLateFeeAmount[0]->PenaltyNoOfDays-30-1; ?>
											{{--ADDED--}}
											@if(($hasLateFeeAmount[0]->PenaltyNoOfDays-1)>30)
												<?php $lateFeeAfterGracePeriod=$hasLateFeeAmount[0]->PenaltyNoOfDays-30-1; ?>
											@else
												<?php $lateFeeAfterGracePeriod = 0; ?>
											@endif
											{{--Added--}}
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
											{{number_format((int)$lateFeeAfterGracePeriod*(int)$hasLateFeeAmount[0]->PenaltyLateFeeAmount,2)}}
											<input type="hidden" name="latefeedetails[TotalAmount]" value="{{$lateFeeAmount}}"/>
										</td>
									</tr>
									<tr class="bold">
										<td colspan="2">Total</td>
										<td>
											<?php $appliedService->ConsultantAmount=$lateFeeAmount;?>
											@if($generalInformation[0]->WaiveOffLateFee == 1)
												<span style="text-decoration: line-through;">
												@endif
													{{number_format($lateFeeAmount,2)}}
													@if($generalInformation[0]->WaiveOffLateFee == 1)
												</span>
												&nbsp;
												{{{$generalInformation[0]->NewLateFeeAmount}}}
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
							@if((bool)$appliedService->ConsultantAmount!=NULL)
								@if($appliedService->Id==CONST_SERVICETYPE_CHANGEOFCATEGORYCLASSIFICATION)
									@if(!$hasRenewal)
                                        <?php $totalFeeApplicable+=$appliedService->ConsultantAmount; ?>
									@endif
								@else
                                    <?php $totalFeeApplicable+=$appliedService->ConsultantAmount; ?>
								@endif

								@if($appliedService->Id==CONST_SERVICETYPE_LATEFEE)
									@if($generalInformation[0]->WaiveOffLateFee == 1)
										<span style="text-decoration: line-through;">
											@endif
											@endif
											@if($appliedService->Id==CONST_SERVICETYPE_CHANGEOFCATEGORYCLASSIFICATION)
												@if(!$hasRenewal)
													{{{number_format($appliedService->ConsultantAmount,2)}}}
												@endif
											@else
												{{{number_format($appliedService->ConsultantAmount,2)}}}
											@endif

											@if($appliedService->Id==CONST_SERVICETYPE_LATEFEE)
												@if($generalInformation[0]->WaiveOffLateFee == 1)
													<input type="hidden" name="crpconsultantservicepayment[{{$randomKey}}][WaiveOffLateFee]" value="1"/>
											</span>
										&nbsp;
										{{{number_format($generalInformation[0]->NewLateFeeAmount,2)}}}
										<input type="hidden" name="crpconsultantservicepayment[{{$randomKey}}][NewLateFeeAmount]" value="{{$generalInformation[0]->NewLateFeeAmount}}"/>
									@endif
								@endif
							@else
								-
							@endif

							<input type="hidden" name="crpconsultantservicepayment[{{$randomKey}}][PaymentAmount]" value="{{$appliedService->ConsultantAmount}}"/>
						</td>
					</tr>
				@endforeach
				<tr class="text-right bold">
					<td colspan="2">Total Amount Payable</td>
					<td>
						@if((int)$generalInformation[0]->WaiveOffLateFee == 1)
							<span style="text-decoration: line-through;">
									@endif
								{{{number_format($totalFeeApplicable,2)}}}
								@if((int)$generalInformation[0]->WaiveOffLateFee == 1)
										</span>
							<br>
							{{{number_format((int)$totalFeeApplicable-((int)$lateFeeAmount-(int)$generalInformation[0]->NewLateFeeAmount),2)}}}
						@endif
					</td>
				</tr>
				</tbody>
			</table>
		</div>
	@if(!isset($serviceApplicationApproved))
		<?php $serviceApplicationApproved = 0; ?>
	@endif
	@if((int)$serviceApplicationApproved == 0)
	<div class="portlet-body form">
		<div class="form-body"><div class="clearfix"></div>
			@include('crps.applicationhistory')
			<input type="hidden" name="PaymentApprovedDate" value="{{date('Y-m-d G:i:s')}}" />
			<input type="hidden" name="SysLockedByUserId" value="" />
			<input type="hidden" name="ConsultantReference" value="{{$generalInformation[0]->Id}}" />
			<input type="hidden" name="CmnApplicationRegistrationStatusId" value="{{CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED}}" />

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
						<textarea name="RemarksByPaymentApprover" class="form-control required" rows="3"></textarea>
					</div>
				</div>
			</div>
		</div><div class="clearfix"></div>
		<div class="form-actions">
			<div class="btn-set">
				<a href="#paymentdoneregistration" data-toggle="modal" class="btn green">Approve & Update Certificate</a>
				@if($isAdmin)
					<a href="#sendback" data-toggle="modal" class="btn purple">Send back to Approver</a>
				@endif
			</div>
		</div>
		{{Form::close()}}
		@else
			<div class="form-body">
				{{ Form::open(array('url' => 'consultant/msavefinalremarks','role'=>'form','id'=>'registrationpaymentdonecontractor'))}}
				<input type="hidden" name="ConsultantReference" value="{{$generalInformation[0]->Id}}" />
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
