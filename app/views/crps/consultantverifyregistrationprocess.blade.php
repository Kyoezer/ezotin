@extends('master')
@section('lastcdbno')
<div class="page-toolbar">
	<div id="dashboard-report-range" class="pull-right tooltips btn btn-fit-height grey-cascade" data-placement="top" data-original-title="Last used Consultant CDB No.">
		<span class="thin visible-lg-inline-block">Last used CDB No.: <span class="bold">{{lastUsedConsultantCDBNo();}}</span></span>
	</div>
</div>
@stop
@section('pagescripts')
{{ HTML::script('assets/global/scripts/consultant.js') }}
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
<div id="verify" class="modal fade" role="dialog" aria-labelledby="verify" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h3 class="modal-title font-green-seagreen">Verify Registration</h3>
			</div>
			<div class="modal-body">
				<p class="bold">Are you sure you want to verify this application?</p>
			</div>
			<div class="modal-footer">
				<button id="verifyconsultantregistration" type="button" class="btn green">Verify</button>
				<button class="btn red" data-dismiss="modal" aria-hidden="true">Cancel</button>
			</div>
		</div>
	</div>
</div>
<div id="rejectregistration" class="modal fade" role="dialog" aria-labelledby="rejectregistration" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			{{ Form::open(array('url' => 'consultant/mrejectregistration','role'=>'form','id'=>'rejectregistrationconsultant'))}}
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h3 class="modal-title font-red">Reject Registration</h3>
			</div>
			<div class="modal-body">
				<input type="hidden" name="RedirectRoute" value="verifyregistration">
				<p class="bold">Are you sure you want to reject this application?</p>
				<input type="hidden" name="ConsultantReference" value="{{$generalInformation[0]->Id}}" />
				<input type="hidden" name="RejectedDate" value="{{date('Y-m-d G:i:s')}}" />
				<div class="form-group">
					<label>Remarks</label>
					<textarea name="RemarksByRejector" class="form-control remarksbyrejector" rows="3"></textarea>
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
{{ Form::open(array('url' => $modelPost,'role'=>'form','id'=>'verifyregistrationconsultant'))}}
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption font-green-seagreen">
			<i class="fa fa-cogs"></i>
			<span class="caption-subject">Registration Information</span>
		</div>
		<div class="actions">
			<a href="{{URL::to('consultant/verifyregistration')}}" class="btn btn-default btn-sm">Back to List</a>
		</div>
	</div>
	<div class="portlet-body form">
		<input type="hidden" name="VerifiedDate" value="{{date('Y-m-d G:i:s')}}" />
		<input type="hidden" name="SysLockedByUserId" value="" />
		<input type="hidden" name="RedirectRoute" value="verifyregistration">
		<input type="hidden" name="ConsultantReference" value="{{$generalInformation[0]->Id}}" />
		<input type="hidden" name="CmnApplicationRegistrationStatusId" value="{{CONST_CMN_APPLICATIONREGISTRATIONSTATUS_VERIFIED}}">
		<div class="row">
			<div class="col-md-12 table-responsive">
				<h5 class="font-blue-madison bold">Fee strcuture as per <i class="font-red">APPLIED CATEGORIES by the applicant</i></h5>
				<table class="table table-bordered table-striped table-condensed">
					<thead>
						<tr>
							<th>Category</th>
							<th>Applied</th>
							<th class="text-center">No. of Service (Applied) X Fee</th>
							<th class="text-right">Total</th>
						</tr>
					</thead>
					<tbody>
						<?php $noOfServicePerCategory=0;$overAllTotalAmount=0; ?>
						@foreach($serviceCategories as $serviceCategory)
							<tr>
								<td>{{$serviceCategory->Name}}</td>
								<td class="info">
									@foreach($appliedCategoryServices as $appliedServiceFee)
										@if($appliedServiceFee->CmnConsultantServiceCategoryId==$serviceCategory->Id)
											<?php $noOfServicePerCategory+=1; ?>
											{{$appliedServiceFee->ServiceCode}}
											<span class="font-green-seagreen tooltips" data-placement="top" data-original-title="{{$appliedServiceFee->ServiceName}}"><i class="fa fa-question-circle"></i></span>
										@endif
									@endforeach
								</td>
								<td class="text-center">
									{{$noOfServicePerCategory.' X '.number_format($feeStructures[0]->ConsultantAmount,2)}}
								</td>
								<td class="text-right">
									<?php $curTotalAmount=$noOfServicePerCategory*3000;$overAllTotalAmount+=$curTotalAmount; ?>
									{{number_format($noOfServicePerCategory*$feeStructures[0]->ConsultantAmount,2)}}
								</td>
							</tr>
							<?php $noOfServicePerCategory=0; ?>
							@endforeach
							<tr class="bold text-right">
								<td colspan="3"><span class="font-red bold pull-left">*Nu {{number_format($feeStructures[0]->ConsultantAmount,2)}} is applicable for each service applied under the category</span> Total</td>
								<td>{{number_format($overAllTotalAmount,2)}}</td>
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
								<td colspan="2" class="font-blue-madison bold warning">Certificate of Incorporation (Attachments)</td>
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
		</div>
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
					<h5 class="font-blue-madison bold">Name of Owner, Partners and/or others with Controlling Intrest</h5>
					<div class="table-responsive">
						<table class="table table-bordered table-striped table-condensed">
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
									<th>Action</th>
									<th>
										Verified
									</th>
									<th width="15%">
										Show in Certificate
									</th>

								</tr>
							</thead>
							<tbody>
								@foreach($ownerPartnerDetails as $ownerPartnerDetail)
								<?php $randomKey = randomString(); ?>
								<tr>
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
										<button type="button" data-cid="{{trim($ownerPartnerDetail->CIDNo)}}" class="btn blue btn-sm checkhrdbandwebservice">Check</button>
									</td>
									<td>
										<input type="hidden" name="ConsultantHumanResourceModel[{{$randomKey}}][Id]" value="{{$ownerPartnerDetail->Id}}">
										<input type="checkbox" name="ConsultantHumanResourceModel[{{$randomKey}}][Verified]" checked="checked" class="checkboxes" value="1"/>
									</td>
									<td>
										@if((int)$ownerPartnerDetail->ShowInCertificate==1)
											<i class="fa fa-check"></i>
										@endif
									</td>
								</tr>
								@endforeach
							</tbody>
						</table>	
					</div>
					<a href="{{URL::to('consultant/editgeneralinfo/'.$generalInformation[0]->Id.'?redirectUrl=consultant/verifyregistrationprocess')}}" class="btn blue-madison editaction">Edit General Information/Partner Details</a>
				</div>
				<div class="tab-pane" id="workclassification">
					<input type="hidden" name="ConsultantId" value="{{$generalInformation[0]->Id}}" />
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
									Verify
								</th>
							</tr>
						</thead>
						<tbody>
							@foreach($serviceCategories as $serviceCategory)
							<tr>
								<td>{{$serviceCategory->Name}}</td>
								<td>
									@foreach($appliedCategoryServices as $appliedService1)
										@if($appliedService1->CmnConsultantServiceCategoryId==$serviceCategory->Id)
										<span class="font-green-seagreen tooltips" data-placement="top" data-original-title="{{$appliedService1->ServiceName}}"><i class="fa fa-question-circle"></i></span>
										{{$appliedService1->ServiceCode}}
									@endif
									@endforeach
								</td>
								<td>
									@foreach($appliedCategoryServices as $appliedService2)
										<?php $randomKey=randomString(); ?>
										@if($appliedService2->CmnConsultantServiceCategoryId==$serviceCategory->Id)
											<input type="hidden" name="ConsultantWorkClassificationModel[{{$randomKey}}][Id]" value="{{$appliedService2->Id}}" class="setselectservicecontrol" />
											<input type="checkbox" name="ConsultantWorkClassificationModel[{{$randomKey}}][CmnVerifiedServiceId]" value="{{$appliedService2->ServiceId}}" class="selectconsultantservice" checked="checked" />
											<span class="font-green-seagreen" data-toggle="tooltip" title="{{$appliedService2->ServiceName}}"><i class="fa fa-question-circle"></i></span>
											{{$appliedService2->ServiceCode}}
										@endif
									@endforeach
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
					{{---<a href="{{URL::to('consultant/editworkclassification/'.$generalInformation[0]->Id.'?redirectUrl=consultant/verifyregistrationprocess')}}" class="btn blue-madison">Edit Work Classification</a>---}}
				</div>
				<div class="tab-pane" id="humanresource">
					<div class="table-responsive">
						<table id="HumanResource" class="table table-bordered table-striped table-condensed">
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
								<th>
									Verified
								</th>
							</thead>
							<tbody>
								@foreach($consultantHumanResources as $consultantHumanResource)
								<?php $randomKey=randomString(); ?>
								<tr>
									<td>{{{$consultantHumanResource->Salutation.' '.$consultantHumanResource->Name}}}</td>
									<td>{{{$consultantHumanResource->CIDNo}}}</td>
									<td>{{{$consultantHumanResource->Sex}}}</td>
									<td>{{{$consultantHumanResource->Country}}}</td>
									<td>{{{$consultantHumanResource->Qualification}}}</td>
									<td>{{{$consultantHumanResource->Designation}}}</td>
									<td>{{{$consultantHumanResource->Trade}}}</td>
									<td>
										@foreach($consultantHumanResourceAttachments as $consultantHumanResourceAttachment)
											@if($consultantHumanResourceAttachment->CrpConsultantHumanResourceId==$consultantHumanResource->Id)
											<i class="fa fa-check"></i> <a href="{{URL::to($consultantHumanResourceAttachment->DocumentPath)}}" target="_blank">{{{$consultantHumanResourceAttachment->DocumentName}}}</a><br />
											@endif
										@endforeach
									</td>
									<td>
										<button type="button" data-cid="{{trim($consultantHumanResource->CIDNo)}}" class="btn blue btn-sm checkhrdbandwebservice">Check</button>
									</td>
									<td>
										<input type="hidden" name="ConsultantHumanResourceModel[{{$randomKey}}][Id]" value="{{$consultantHumanResource->Id}}">
										<input type="checkbox" name="ConsultantHumanResourceModel[{{$randomKey}}][Verified]" class="checkboxes" value="1"/>
									</td>
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>
					<a href="{{URL::to('consultant/edithumanresource/'.$generalInformation[0]->Id.'?redirectUrl=consultant/verifyregistrationprocess')}}" class="btn blue-madison editaction">Edit Human Resource</a>
				</div>
				<div class="tab-pane" id="equipment">
					<div class="table-scrollable">
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
									<th>Action</th>
									<th class="table-checkbox">
										Verified
									</th>
								</tr>
							</thead>
							<tbody>
								@foreach($consultantEquipments as $consultantEquipment)
								<?php $randomKey=randomString(); ?>
								<tr>
									<td>{{{$consultantEquipment->Name}}}</td>
									<td>{{{$consultantEquipment->RegistrationNo}}}</td>
									<td>{{{$consultantEquipment->ModelNo}}}</td>
									<td>{{{$consultantEquipment->Quantity}}}</td>
									<td>
										@foreach($consultantEquipmentAttachments as $consultantEquipmentAttachment)
											@if($consultantEquipmentAttachment->CrpConsultantEquipmentId==$consultantEquipment->Id)
											<i class="fa fa-check"></i> <a href="{{URL::to($consultantEquipmentAttachment->DocumentPath)}}" target="_blank">{{{$consultantEquipmentAttachment->DocumentName}}}</a><br />
											@endif
										@endforeach
									</td>
									<td>
										<button type="button" data-regno="{{trim($consultantEquipment->RegistrationNo)}}" data-vehicletype="{{$consultantEquipment->VehicleType}}" class="btn blue btn-sm checkeqdbandwebservice">Check
										</button>
									</td>
									<td>
										<input type="hidden" name="ConsultantEquipmentModel[{{$randomKey}}][Id]" value="{{$consultantEquipment->Id}}">
										<input type="checkbox" name="ConsultantEquipmentModel[{{$randomKey}}][Verified]" class="checkboxes" value="1"/>
									</td>
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>
					<a href="{{URL::to('consultant/editequipment/'.$generalInformation[0]->Id.'?redirectUrl=consultant/verifyregistrationprocess')}}" class="btn blue-madison editaction">Edit Equipment</a>
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
			<div class="form-group">
				<input type="hidden" name="SysVerifierUserId" value="{{Auth::user()->Id}}" />
				<label>Remarks</label>
				<textarea name="RemarksByVerifier" class="form-control" rows="3"></textarea>
			</div>
		</div>
		<div class="form-actions">
			<div class="btn-set">
				<a href="#verify" data-toggle="modal" class="btn green">Verify</a>
				<a href="#rejectregistration" data-toggle="modal" class="btn red">Reject</a>
			</div>
			<button type="button" class="btn blue-ebonyclay pull-right">Next</button>
		</div>
	</div>
</div>
{{Form::close()}}
@stop