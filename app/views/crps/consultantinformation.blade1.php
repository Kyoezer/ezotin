@extends('master')
@section('pagescripts')
{{ HTML::script('assets/global/scripts/consultant.js') }}
@stop
@section('lastcdbno')
<div class="page-toolbar">
	<div id="dashboard-report-range" class="pull-right tooltips btn btn-fit-height grey-cascade" data-placement="top" data-original-title="Last used Consultant CDB No.">
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
	<div id="editgeneralinfo" class="modal fade" role="dialog" aria-labelledby="addcommentadverserecord" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				{{ Form::open(array('url' => 'consultant/meditbasicinfo','role'=>'form'))}}
				<input type="hidden" name="CrpConsultantFinalId" value="{{$generalInformation[0]->Id}}" />
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
<div id="consultantcommentadverserecordedit" class="modal fade" role="dialog" aria-labelledby="consultantcommentadverserecordedit" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			{{ Form::open(array('url' => 'consultant/meditcommentadverserecords','role'=>'form'))}}
			<input type="hidden" name="Id" class="consultantcommentadverserecordid" />
			<input type="hidden" name="CrpConsultantFinalId" value="{{$generalInformation[0]->Id}}" />
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
							<input type="text" name="Date" class="form-control datepicker required commentadverserecorddate" placeholder="">
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
			{{ Form::open(array('url' => 'consultant/mcommentadverserecord','role'=>'form'))}}
			<input type="hidden" name="Id" class="consultantcommentadverserecordid" />
			<input type="hidden" name="CrpConsultantFinalId" value="{{$generalInformation[0]->Id}}" />
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
							</select>
						</div>
						<div class="form-group">
							<input type="hidden" name="CrpConsultantFinalId" value="{{$generalInformation[0]->Id}}" />
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
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-cogs"></i>
			<span class="caption-subject">Registration Information</span> 
		</div>
		@if((int)$userConsultant==1)
		<div class="actions">
			<a href="{{URL::to('consultant/viewprintdetails/'.$consultantId)}}" class="btn btn-small blue-madison" target="_blank"><i class="fa fa-print"></i> Print Information</a>
			<a href="{{URL::to('consultant/certificate/'.$consultantId)}}" class="btn btn-small green-seagreen" target="_blank"><i class="fa fa-print"></i> Print Certificate</a>
		</div>
		@endif
	</div>
	<div class="portlet-body">
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
						Comments/Adverse Records
					</a>
				</li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane active" id="partnerdetails">
					<div class="row">
						<div class="col-md-6">
							<table class="table table-bordered table-striped table-condensed flip-content">
								<tbody>
									<tr>
										<td><strong>CDB No.</strong></td>
										<td>{{$generalInformation[0]->CDBNo}}</td>
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
										<td><strong>Dzongkhag</strong></td>
										<td>{{$generalInformation[0]->Dzongkhag}}</td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="col-md-6">
							<table class="table table-bordered table-striped table-condensed flip-content">
								<tbody>
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
									@if(isset($incorporationOwnershipTypes) && (int)$generalInformation[0]->OwnershipTypeReferenceNo!=14001)
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
					<h5 class="font-blue-madison bold">Name of Owner, Partners and/or others with Controlling Intrest</h5>
					<table class="table table-bordered table-striped table-condensed flip-content">
						<thead>
							<tr>
								<th>Sl#</th>
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
							</tr>
						</thead>
						<tbody>
							<?php $ownerPartnerCount = 1; ?>
							@foreach($ownerPartnerDetails as $ownerPartnerDetail)
							<tr>
								<td>{{$ownerPartnerCount}}</td>
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
							</tr>
								<?php $ownerPartnerCount++; ?>
							@endforeach
						</tbody>
					</table>
					@if((int)$userConsultant==0)
						@if(((isset($registrationApproved) && (int)$registrationApproved!=1)) || (isset($isAdmin) && $isAdmin))
							<a href="{{URL::to('consultant/editgeneralinfo/'.$generalInformation[0]->Id.'?redirectUrl=consultant/editdetails&usercdb=edit')}}" class="btn blue-madison editaction">Edit General Information/Partner Details</a>
						@endif
					@else
						<a href="#editgeneralinfo" data-toggle="modal" class="btn blue-madison"><i class="fa fa-edit"></i> Edit General Info</a>
					@endif
				</div>
				<div class="tab-pane" id="workclassification">
					<div class="table-responsive">
						<table id="workclasssficationverification" class="table table-bordered table-striped table-condensed flip-content">
							<thead>
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
										Approved
									</th>
								</tr>
							</thead>
							<tbody>
								@foreach($serviceCategories as $serviceCategory)
									<?php $randomKey = randomString(); ?>
								<tr>
									<td>{{$serviceCategory->Name}}

									</td>
									<td>
										@foreach($appliedCategoryServices as $appliedService1)
											@if($appliedService1->CmnConsultantServiceCategoryId==$serviceCategory->Id)
												<span class="font-green-seagreen tooltips" data-placement="top" data-original-title="{{$appliedService1->ServiceName}}"><i class="fa fa-question-circle"></i></span>
												{{$appliedService1->ServiceCode}}
											@endif
										@endforeach
									</td>
									<td>
										@foreach($verifiedCategoryServices as $verifiedService1)
											@if($verifiedService1->CmnConsultantServiceCategoryId==$serviceCategory->Id)
												<span class="font-green-seagreen tooltips" data-placement="top" data-original-title="{{$verifiedService1->ServiceName}}"><i class="fa fa-question-circle"></i></span>
												{{$verifiedService1->ServiceCode}}
											@endif
										@endforeach
									</td>
									<td>
										@foreach($approvedCategoryServices as $approvedService2)
												@if($approvedService2->CmnConsultantServiceCategoryId==$serviceCategory->Id)
												<span>
													<span class="font-green-seagreen" data-toggle="tooltip" title="{{$approvedService2->ServiceName}}"><i class="fa fa-question-circle"></i></span>
												</span>
												{{$approvedService2->ServiceCode}}
											@endif
										@endforeach
									</td>
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>
{{--					@if((int)$userConsultant==0 && $generalInformation[0]->CmnApplicationRegistrationStatusId==CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVEDFORPAYMENT)--}}
					@if(((isset($registrationApproved) && (int)$registrationApproved!=1)) || (isset($isAdmin) && $isAdmin))
						<a href="{{URL::to('consultant/editworkclassification/'.$generalInformation[0]->Id.'?redirectUrl=consultant/editdetails&usercdb=edit&final='.(isset($final)?'true':'false'))}}" class="btn blue-madison">Edit Work Classification</a>
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
									 Country
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
							</thead>
							<tbody>
							<?php $hrCount = 1; ?>
								@foreach($consultantHumanResources as $consultantHumanResource)
								<tr>
									<td>{{$hrCount}}</td>
									<td>{{$consultantHumanResource->Name}}</td>
									<td>{{$consultantHumanResource->CIDNo}}</td>
									<td class="font-blue-madison bold warning">{{{$consultantHumanResource->CDBNo1}}}{{{$consultantHumanResource->CDBNo2}}}{{{$consultantHumanResource->CDBNo3}}}{{{$consultantHumanResource->CDBNo4}}}</td>
									<td>{{$consultantHumanResource->Sex}}</td>
									<td>{{$consultantHumanResource->Country}}</td>
									<td>{{$consultantHumanResource->Qualification}}</td>
									<td>{{$consultantHumanResource->Designation}}</td>
									<td>{{convertDateToClientFormat($consultantHumanResource->JoiningDate)}}</td>
									<td>{{$consultantHumanResource->Trade}}</td>
									<td>
										@foreach($consultantHumanResourceAttachments as $consultantHumanResourceAttachment)
											@if($consultantHumanResourceAttachment->CrpConsultantHumanResourceId==$consultantHumanResource->Id)
											<i class="fa fa-check"></i> <a href="{{URL::to($consultantHumanResourceAttachment->DocumentPath)}}" target="_blank">{{$consultantHumanResourceAttachment->DocumentName}}</a><br />
											@endif
										@endforeach
									</td>
								</tr>
								<?php $hrCount++; ?>
								@endforeach
							</tbody>
						</table>
					</div>
					@if((int)$userConsultant==0)
						{{--@if($registrationApproved == 0)--}}
						@if(((isset($registrationApproved) && (int)$registrationApproved!=1)) || (isset($isAdmin) && $isAdmin))
							<a href="{{URL::to('consultant/edithumanresource/'.$generalInformation[0]->Id.'?redirectUrl=consultant/editdetails&usercdb=edit')}}" class="btn blue-madison editaction">Edit Human Resource</a>
						@endif
					@endif
				</div>
				<div class="tab-pane" id="equipment">
					<div class="table-scrollable">
						<table class="table table-bordered table-striped table-condensed flip-content">
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
								</tr>
							</thead>
							<tbody>
							<?php $eqCount = 1; ?>
								@foreach($consultantEquipments as $consultantEquipment)
								<tr>
									<td>{{$eqCount}}</td>
									<td>{{$consultantEquipment->Name}}</td>
									<td>{{$consultantEquipment->RegistrationNo}}</td>
									<td>{{$consultantEquipment->ModelNo}}</td>
									<td>{{$consultantEquipment->Quantity}}</td>
									<td>
										@foreach($consultantEquipmentAttachments as $consultantEquipmentAttachment)
											@if($consultantEquipmentAttachment->CrpConsultantEquipmentId==$consultantEquipment->Id)
											<i class="fa fa-check"></i> <a href="{{URL::to($consultantEquipmentAttachment->DocumentPath)}}" target="_blank">{{$consultantEquipmentAttachment->DocumentName}}</a><br />
											@endif
										@endforeach
									</td>
								</tr>
									<?php $eqCount++; ?>
								@endforeach
							</tbody>
						</table>
					</div>
					@if((int)$userConsultant==0)
						@if(((isset($registrationApproved) && (int)$registrationApproved!=1)) || (isset($isAdmin) && $isAdmin))
							<a href="{{URL::to('consultant/editequipment/'.$generalInformation[0]->Id.'?redirectUrl=consultant/editdetails&usercdb=edit')}}" class="btn blue-madison editaction">Edit Equipment</a>
						@endif
					@endif
				</div>
				@if((int)$registrationApprovedForPayment!=1)
				<div class="tab-pane" id="trackrecord">
					<div class="table-responsive">
						<table class="table table-bordered table-striped table-condensed">
							<thead>
								<tr>
									<th width="5%">Sl#</th>
									<th>Year</th>
									<th>
										Procuring Agency
									</th>
									<th class="">
										Work Order No.
									</th>
									<th class="">
										Name of Work
									</th>
									<th class="">
										Category
									</th>
									<th class="">
										Service
									</th>
									<th>
										Period (Months)
									</th>
									<th>
										Start Date
									</th>
									<th>
										Completion Date
									</th>
								</tr>
							</thead>
							<tbody>
								<?php $currentProcuringAgeny="";$sl=1; ?>
								@forelse($consultantTrackrecords as $consultantTrackrecord)
								<tr>
									<td>
										{{$sl}}
									</td>
									<td>{{($consultantTrackrecord->ReferenceNo == 3003)?date_format(date_create($consultantTrackrecord->WorkCompletionDate),'Y'):date_format(date_create($consultantTrackrecord->WorkStartDate),'Y')}}</td>
									<td>
										@if($consultantTrackrecord->ProcuringAgency!=$currentProcuringAgeny)
										{{$consultantTrackrecord->ProcuringAgency}}
										@endif
									</td>
									<td>{{$consultantTrackrecord->WorkOrderNo}}</td>
									<td>{{strip_tags($consultantTrackrecord->NameOfWork)}}</td>
									<td>{{$consultantTrackrecord->ServiceCategory}}</td>
									<td>{{$consultantTrackrecord->ServiceName}}</td>
									<td>{{$consultantTrackrecord->ContractPeriod}}</td>
									<td>{{convertDateToClientFormat($consultantTrackrecord->WorkStartDate)}}</td>
									<td>{{convertDateToClientFormat($consultantTrackrecord->WorkCompletionDate)}}</td>
									<?php $currentProcuringAgeny=$consultantTrackrecord->ProcuringAgency;$sl+=1; ?>
								</tr>
								@empty
								<tr>
									<td colspan="10" class="text-center font-red">No Track Records till {{date('d-M-Y')}}</td>
								</tr>
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
							@foreach($consultantComments as $comment)
								<tr>
									<td>
										{{convertDateToClientFormat($comment->Date)}}</td>
									<td>{{{$comment->Remarks}}}</td>
									<td>
										<input type="hidden" class="commentid" value="{{$comment->Id}}">
										<input type="hidden" class="consultantcommentdate" value="{{convertDateToClientFormat($comment->Date)}}" />
										<input type="hidden" class="consultantcomment" value="{{$comment->Remarks}}" />
										{{--<button type="button" class="btn green-haze btn-sm editcontractorcomment">Edit</button>--}}
										{{--<button type="button" class="btn bg-red-sunglo btn-sm deletecontractorcomment">Delete</button>--}}
										@if($userConsultant == 0 && $isAdmin)
											@if($isAdmin || (isset($registrationApproved) && $registrationApproved == 0))
												<a href="#" class="editconsultantcomment">Edit</a>
												&nbsp;|&nbsp;
												<a href="#" class="deleteconsultantcomment">Delete</a>
											@endif
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
							@foreach($consultantAdverseRecords as $adverseRecord)
								<tr>
									<td>
										{{convertDateToClientFormat($adverseRecord->Date)}}</td>
									<td>{{{$adverseRecord->Remarks}}}</td>
									<td>
										<input type="hidden" class="adverserecordid" value="{{$adverseRecord->Id}}">
										<input type="hidden" class="consultantadverserecorddate" value="{{convertDateToClientFormat($adverseRecord->Date)}}" />
										<input type="hidden" class="consultantadverserecord" value="{{$adverseRecord->Remarks}}" />
										{{--<button type="button" class="btn green-haze btn-sm editcontractorcomment">Edit</button>--}}
										{{--<button type="button" class="btn bg-red-sunglo btn-sm deletecontractorcomment">Delete</button>--}}
										@if(((isset($registrationApproved) && (int)$registrationApproved!=1)) || (isset($isAdmin) && $isAdmin))
										<a href="#" class="editconsultantadverserecord">Edit</a>
										&nbsp;|&nbsp;
										<a href="#" class="deleteconsultantadverserecord">Delete</a>
										@endif
									</td>
								</tr>
							@endforeach
							</tbody>
						</table>
					</div>
					@if((int)$userConsultant==0)
						@if(((isset($registrationApproved) && (int)$registrationApproved!=1)) || (isset($isAdmin) && $isAdmin))
							<a href="#addcommentadverserecord" data-toggle="modal" class="btn blue-madison">Add Comment/ Adverse Record</a>
						@endif
					@endif
				</div>
			</div>
		</div>
		@include('crps.applicationhistory')
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
	<div class="portlet-body form">
		<div class="form-body">
			{{ Form::open(array('url' => 'consultant/mapprovepaymentforregistration','role'=>'form','id'=>'registrationpaymentdoneconsultant'))}}
			<input type="hidden" name="RegistrationPaymentApprovedDate" value="{{date('Y-m-d G:i:s')}}" />
			<input type="hidden" name="SysLockedByUserId" value="" />
			<input type="hidden" name="ConsultantReference" value="{{$generalInformation[0]->Id}}" />
			<input type="hidden" name="CmnApplicationRegistrationStatusId" value="{{CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED}}" />
			<div class="row">
				<div class="col-md-12">
					<h5 class="font-blue-madison bold">Fee structure as per <i class="font-red">APPROVED CATEGORIES</i> by {{$generalInformation[0]->Verifier}}</h5>
					<table class="table table-bordered table-striped table-condensed">
						<thead>
							<tr>
								<th>Category</th>
								<th>Applied</th>
								<th>Verified</th>
								<th>Approved</th>
								<th class="text-center">No. of Service (Approved) X Fee.</th>
								<th class="text-right">Total</th>
							</tr>
						</thead>
						<tbody>
							<?php $noOfServicePerCategory=0;$overAllTotalAmount=0; ?>
							@foreach($serviceCategories as $serviceCategory)
								<?php $randomKey = randomString(); ?>
								<tr>
									<td>{{$serviceCategory->Name}}<input type="hidden" name="consultantregistrationpayment[{{$randomKey}}][CmnServiceCategoryId]" value="{{$serviceCategory->Id}}"/></td>
									<td class="info">
										@foreach($appliedCategoryServices as $appliedServiceFee)
											@if($appliedServiceFee->CmnConsultantServiceCategoryId==$serviceCategory->Id)
												<span class="font-green-seagreen tooltips" data-placement="top" data-original-title="{{$appliedServiceFee->ServiceName}}"><i class="fa fa-question-circle"></i></span>
												{{$appliedServiceFee->ServiceCode}}

											@endif
										@endforeach
										<input type="hidden" name="consultantregistrationpayment[{{$randomKey}}][AppliedService]" value="{{(!empty($appliedCategoryServicesArray[$serviceCategory->Id]))?$appliedCategoryServicesArray[$serviceCategory->Id][0]->Service:""}}"/>
									</td>
									<td class="success">
										@foreach($verifiedCategoryServices as $verifiedServiceFee)
											@if($verifiedServiceFee->CmnConsultantServiceCategoryId==$serviceCategory->Id)
												<span class="font-green-seagreen tooltips" data-placement="top" data-original-title="{{$verifiedServiceFee->ServiceName}}"><i class="fa fa-question-circle"></i></span>
												{{$verifiedServiceFee->ServiceCode}}

											@endif
										@endforeach
										<input type="hidden" name="consultantregistrationpayment[{{$randomKey}}][VerifiedService]" value="{{(!empty($verifiedCategoryServicesArray[$serviceCategory->Id]))?$verifiedCategoryServicesArray[$serviceCategory->Id][0]->Service:''}}"/>
									</td>
									<td class="warning">
										@foreach($approvedCategoryServices as $approvedServiceFee)
											@if($approvedServiceFee->CmnConsultantServiceCategoryId==$serviceCategory->Id)
												<?php $noOfServicePerCategory+=1; ?>
												<span class="font-green-seagreen tooltips" data-placement="top" data-original-title="{{$approvedServiceFee->ServiceName}}"><i class="fa fa-question-circle"></i></span>
												{{$approvedServiceFee->ServiceCode}}

											@endif
										@endforeach
										<input type="hidden" name="consultantregistrationpayment[{{$randomKey}}][ApprovedService]" value="{{(!empty($approvedCategoryServicesArray[$serviceCategory->Id]))?$approvedCategoryServicesArray[$serviceCategory->Id][0]->Service:''}}"/>
									</td>
									<td class="text-center">
										{{$noOfServicePerCategory.' X '.number_format($feeStructures[0]->ConsultantAmount,2)}}
										<input type="hidden" name="consultantregistrationpayment[{{$randomKey}}][ServiceXFee]" value="{{$noOfServicePerCategory.' X '.number_format($feeStructures[0]->ConsultantAmount,2)}}"/>
									</td>
									<td class="text-right">
										<?php $curTotalAmount=$noOfServicePerCategory*$feeStructures[0]->ConsultantAmount;$overAllTotalAmount+=$curTotalAmount; ?>
										{{number_format($noOfServicePerCategory*$feeStructures[0]->ConsultantAmount,2)}}
											<input type="hidden" name="consultantregistrationpayment[{{$randomKey}}][Amount]" value="{{$noOfServicePerCategory*$feeStructures[0]->ConsultantAmount}}"/>
									</td>
								</tr>
								<?php $noOfServicePerCategory=0; ?>
								@endforeach
								<tr class="bold text-right">
									<td colspan="5"><span class="font-red bold pull-left">*Nu {{number_format($feeStructures[0]->ConsultantAmount,2)}} is applicable for each service under the category</span> Total</td>
									<td>{{number_format($overAllTotalAmount,2)}}</td>
								</tr>
						</tbody>
					</table>
				</div>
			</div>
			<div class="row">
				<div class="note note-success">
					<h4 class="">Verified by {{$generalInformation[0]->Verifier}} on {{convertDateToClientFormat($generalInformation[0]->VerifiedDate)}}<small><i class="font-red">{{showDateTimeDuration($generalInformation[0]->VerifiedDate)}}</i></small></h4>
					<p>
						{{$generalInformation[0]->RemarksByVerifier}}
					</p>
				</div>
				<div class="note note-success">
					<h4 class="">Approved by {{$generalInformation[0]->Approver}} on {{convertDateToClientFormat($generalInformation[0]->RegistrationApprovedDate)}}<small><i class="font-red">{{showDateTimeDuration($generalInformation[0]->RegistrationApprovedDate)}}</i></small></h4>
					<p>
						{{$generalInformation[0]->RemarksByApprover}}
					</p>
				</div>
				<div class="col-md-2">
					<?php
						$lastUsedCDBNo = lastUsedConsultantCDBNo();
						$lastUsedCDBNo+=1;
					?>
					<div class="form-group">
						<input type="hidden" value="checkcdbnoconsultant" class="cdbnocheckurl">
						<label class="control-label">CDB No.</label>
						<input type="text" name="CDBNo" class="form-control required checkcdbno" value="{{$lastUsedCDBNo}}" />
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label class="control-label">Date</label>
						<div class="input-icon right">
							<i class="fa fa-calendar"></i>
							<input type="text" name="PaymentReceiptDate" class="form-control datepicker required" readonly="readonly" />
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label class="control-label">Receipt No.</label>
						<input type="text" name="PaymentReceiptNo" class="form-control required" />
					</div>
				</div>
				<div class="col-md-12">
					<input type="hidden" name="SysPaymentApproverUserId" value="{{Auth::user()->Id}}">
					<div class="form-group">
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
				<div class="form-body">
					{{ Form::open(array('url' => 'consultant/msavefinalremarks','role'=>'form','id'=>'registrationpaymentdonecontractor'))}}
					<input type="hidden" name="ConsultantReference" value="{{$generalInformation[0]->Id}}" />
					<div class="row">
						<div class="col-md-12">
							<h5 class="font-blue-madison bold">Fee structure as per <i class="font-red">APPROVED CATEGORIES</i> by {{$generalInformation[0]->Verifier}}</h5>
							<strong>Payment Receipt Date: {{convertDateToClientFormat($generalInformation[0]->PaymentReceiptDate)}}</strong> <br>
							<strong>Payment Receipt No.: {{$generalInformation[0]->PaymentReceiptNo}}</strong> <br> <br>
							<table class="table table-bordered table-striped table-condensed">
								<thead>
								<tr>
									<th>Category</th>
									<th>Applied</th>
									<th>Verified</th>
									<th>Approved</th>
									<th class="text-center">No. of Service (Approved) X Fee.</th>
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
													<span class="font-green-seagreen tooltips" data-placement="top" data-original-title="{{$appliedServiceFee->ServiceName}}"><i class="fa fa-question-circle"></i></span>
													{{$appliedServiceFee->ServiceCode}}
												@endif
											@endforeach
										</td>
										<td class="success">
											@foreach($verifiedCategoryServices as $verifiedServiceFee)
												@if($verifiedServiceFee->CmnConsultantServiceCategoryId==$serviceCategory->Id)
													<span class="font-green-seagreen tooltips" data-placement="top" data-original-title="{{$verifiedServiceFee->ServiceName}}"><i class="fa fa-question-circle"></i></span>
													{{$verifiedServiceFee->ServiceCode}}
												@endif
											@endforeach
										</td>
										<td class="warning">
											@foreach($approvedCategoryServices as $approvedServiceFee)
												@if($approvedServiceFee->CmnConsultantServiceCategoryId==$serviceCategory->Id)
													<?php $noOfServicePerCategory+=1; ?>
													<span class="font-green-seagreen tooltips" data-placement="top" data-original-title="{{$approvedServiceFee->ServiceName}}"><i class="fa fa-question-circle"></i></span>
													{{$approvedServiceFee->ServiceCode}}
												@endif
											@endforeach
										</td>
										<td class="text-center">
											{{$noOfServicePerCategory.' X '.number_format($feeStructures[0]->ConsultantAmount,2)}}
										</td>
										<td class="text-right">
											<?php $curTotalAmount=$noOfServicePerCategory*$feeStructures[0]->ConsultantAmount;$overAllTotalAmount+=$curTotalAmount; ?>
											{{number_format($noOfServicePerCategory*$feeStructures[0]->ConsultantAmount,2)}}
										</td>
									</tr>
									<?php $noOfServicePerCategory=0; ?>
								@endforeach
								<tr class="bold text-right">
									<td colspan="5"><span class="font-red bold pull-left">*Nu {{number_format($feeStructures[0]->ConsultantAmount,2)}} is applicable for each service under the category</span> Total</td>
									<td>{{number_format($overAllTotalAmount,2)}}</td>
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