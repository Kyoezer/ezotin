@extends('master')
@section('pagescripts')
{{ HTML::script('assets/global/scripts/specializedtrade.js') }}
@stop

@section('content')

<div id="specializedtradecommentadverserecordedit" class="modal fade" role="dialog" aria-labelledby="specializedtradecommentadverserecordedit" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			{{ Form::open(array('url' => 'specializedfirm/meditcommentadverserecords','role'=>'form'))}}
			<input type="hidden" name="Id" class="specializedtradecommentadverserecordid" />
			<input type="hidden" name="CrpSpecializedtradeFinalId" value="{{$generalInformation[0]->Id}}" />
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
			{{ Form::open(array('url' => 'specializedfirm/mcommentadverserecord','role'=>'form'))}}
			<input type="hidden" name="Id" class="specializedtradecommentadverserecordid" />
			<input type="hidden" name="CrpSpecializedtradeFinalId" value="{{$generalInformation[0]->Id}}" />
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
							<input type="hidden" name="CrpSpecializedtradeFinalId" value="{{$generalInformation[0]->Id}}" />
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

	<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption font-green-seagreen">
			<i class="fa fa-cogs"></i>
			<span class="caption-subject">Registration Information</span> 
		</div>
		@if((int)$userSpecializedtrade==1)
		<div class="actions">
			<a href="{{URL::to('specializedfirm/viewprintdetails/'.$specializedtradeId)}}" class="btn btn-sm blue-madison" target="_blank"><i class="fa fa-print"></i> Print Information</a>
			<a href="{{URL::to('specializedfirm/certificate/'.$specializedtradeId)}}" class="btn btn-sm green-seagreen" target="_blank"><i class="fa fa-print"></i> Print Certificate</a>
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
							<td><strong>SP No.</strong></td>
							<td>{{{$generalInformation[0]->SPNo}}}</td>
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
							<td>{{{$generalInformation[0]->ApplicationDate}}}</td>
						</tr>
						<tr>
							<td><strong>Registration Expiry Date</strong></td>
								<td >
					
							@if($generalInformation[0]->RegistrationExpiryDate<=date('Y-m-d G:i:s'))
							<p class="font-red bold warning">	{{convertDateToClientFormat($generalInformation[0]->RegistrationExpiryDate)}} </p>
							@else
							{{convertDateToClientFormat($generalInformation[0]->RegistrationExpiryDate)}}
							@endif
					
					</td>						</tr>
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
									<td>{{$generalInformation[0]->DeRegisteredDate}}</td>
								</tr>
								<tr>
									<td><strong>Reason</strong></td>
									<td>{{$generalInformation[0]->DeregisteredRemarks}}</td>
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
						@if(isset($specializedtradeAttachments))
							@if(count($specializedtradeAttachments)>0)
								@foreach($specializedtradeAttachments as $specializedtradeAttachment)
								<tr>
									<td colspan="2">
										<i class="fa fa-check"></i> <a href="{{URL::to($specializedtradeAttachment->DocumentPath)}}" target="_blank">{{($specializedtradeAttachment->DocumentName!="")?$specializedtradeAttachment->DocumentName:"Attachment"}}</a>
									</td>
								</tr>
								@endforeach
							@endif
						@endif
					</tbody>	
				</table>
			</div>
		</div>
		
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
					@if((int)$userSpecializedtrade==0)
						
							<a href="{{URL::to('specializedfirm/editgeneralinfo/'.$generalInformation[0]->Id.'?redirectUrl=specializedfirm/editdetails&usercdb=edit&final=true')}}" class="btn blue-madison editaction">Edit General Information/Partner Details</a>
						
					@else
						<a href="#editgeneralinfo" data-toggle="modal" class="btn blue-madison"><i class="fa fa-edit"></i> Edit General Info</a>
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
								@forelse($specializedtradeHumanResources as $specializedtradeHumanResource)
								<tr>
									<td>{{$humanResourceCount}}</td>
									<td>{{{$specializedtradeHumanResource->Name}}}</td>
									<td>{{{$specializedtradeHumanResource->CIDNo}}}</td>
									<td class="font-blue-madison bold warning">{{{$specializedtradeHumanResource->CDBNo1}}}{{{$specializedtradeHumanResource->CDBNo2}}}{{{$specializedtradeHumanResource->CDBNo3}}}{{{$specializedtradeHumanResource->CDBNo4}}}</td>
									<td>{{{$specializedtradeHumanResource->Sex}}}</td>
									<td>{{{$specializedtradeHumanResource->Country}}}</td>
									<td>{{{$specializedtradeHumanResource->Qualification}}}</td>
									<td>{{{$specializedtradeHumanResource->Designation}}}</td>
									<td>{{{convertDateToClientFormat($specializedtradeHumanResource->JoiningDate)}}}</td>
									<td>{{{$specializedtradeHumanResource->Trade}}}</td>
									<td>
										@foreach($specializedtradeHumanResourceAttachments as $specializedtradeHumanResourceAttachment)
											@if($specializedtradeHumanResourceAttachment->CrpSpecializedtradeHumanResourceId==$specializedtradeHumanResource->Id)
											<i class="fa fa-check"></i> <a href="{{URL::to($specializedtradeHumanResourceAttachment->DocumentPath)}}" target="_blank">{{$specializedtradeHumanResourceAttachment->DocumentName}}</a><br />
											@endif
										@endforeach
									</td>
									<th>
										@if(isset($specializedtradeHumanResource->EditedOn))
										{{convertDateTimeToClientFormat($specializedtradeHumanResource->EditedOn)}}
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
					@if((int)$userSpecializedtrade==0)
						
							<a href="{{URL::to('specializedfirm/edithumanresource/'.$generalInformation[0]->Id.'?redirectUrl=specializedfirm/editdetails&usercdb=edit')}}" class="btn blue-madison editaction">Edit Human Resource</a>
						
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
								@forelse($specializedtradeEquipments as $specializedtradeEquipment)
								<tr>
									<td>{{$equipmentCount}}</td>
									<td>{{{$specializedtradeEquipment->Name}}}</td>
									<td>{{{$specializedtradeEquipment->RegistrationNo}}}</td>
								
									<td>{{{$specializedtradeEquipment->Quantity}}}</td>
									<td>
										@foreach($specializedtradeEquipmentAttachments as $specializedtradeEquipmentAttachment)
											@if($specializedtradeEquipmentAttachment->CrpSpecializedtradeEquipmentId==$specializedtradeEquipment->Id)
											<i class="fa fa-check"></i> <a href="{{URL::to($specializedtradeEquipmentAttachment->DocumentPath)}}" target="_blank">{{$specializedtradeEquipmentAttachment->DocumentName}}</a><br />
											@endif
										@endforeach
									</td>
								
								
									<td>
										@if(isset($specializedtradeHumanResource->EditedOn))
											{{convertDateTimeToClientFormat($specializedtradeHumanResource->EditedOn)}}
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
					@if((int)$userSpecializedtrade==0)
						
							<a href="{{URL::to('specializedfirm/editequipment/'.$generalInformation[0]->Id.'?redirectUrl=specializedfirm/editdetails&usercdb=edit')}}" class="btn blue-madison editaction">Edit Equipment</a>
						
					@endif
				</div>

                            <!-- Work Classification Begins from -->


				<div class="tab-pane" id="workclassification">
					
				<h5 class="font-blue-madison bold">Category Information</h5>
					<table class="table table-bordered table-striped table-condensed">
						<thead>
							<tr>
								<th>Category</th>
								<th width="5%" class="table-checkbox">Applied</th>
								<th width="5%" class="table-checkbox">Verified</th>
								<th width="5%" class="table-checkbox">Approved</th>
							</tr>
						</thead>
						<tbody>
							@foreach($specializedtradeWorkClassifications as $specializedtradeWorkClassification)
								<tr>
									<td>
										{{$specializedtradeWorkClassification->Code.' ('.$specializedtradeWorkClassification->Category.')'}}
									</td>
									<td class="text-center">
										@if((bool)$specializedtradeWorkClassification->CmnAppliedCategoryId!=NULL)
										<i class="fa fa-check font-green-seagreen"></i>
										@else
										<i class="fa fa-times font-red"></i>
										@endif
									</td>
									<td class="text-center">
										@if((bool)$specializedtradeWorkClassification->CmnVerifiedCategoryId!=NULL)
										<i class="fa fa-check font-green-seagreen"></i>
										@else
										<i class="fa fa-times font-red"></i>
										@endif
									</td>
									<td class="text-center">
										@if((bool)$specializedtradeWorkClassification->CmnApprovedCategoryId!=NULL)
										<i class="fa fa-check font-green-seagreen"></i>
										@else
										<i class="fa fa-times font-red"></i>
										@endif
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>

					
							{{--<a href="{{URL::to('specializedfirm/editworkclassification/'.$generalInformation[0]->Id.'?redirectUrl=specializedfirm/editdetails&usercdb=edit')}}" class="btn blue-madison">Edit Work Classification</a>--}}
					
				
					@if((int)$userSpecializedtrade==0)
					
							<a href="{{URL::to('specializedfirm/editworkclassification/'.$generalInformation[0]->Id.'?redirectUrl=specializedfirm/editdetails&usercdb=edit&final=true&updatedFrom=SYSTEM')}}" class="btn blue-madison">Edit Work Classification</a>
					
					@endif
					
					</div>
				<!-- track records of Specializedfirm -->

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
							@forelse($specializedtradeTrackrecords as $workDetail)
								<tr>
									<td>{{$count}}</td>
									<td>{{($workDetail->ReferenceNo == 3003)?date_format(date_create($workDetail->WorkCompletionDate),'Y'):date_format(date_create($workDetail->WorkStartDate),'Y')}}</td>
									<td>{{$workDetail->Agency}}</td>
									<td>{{$workDetail->WorkOrderNo}}</td>
									<td>{{strip_tags($workDetail->NameOfWork)}}</td>
									<td>{{$workDetail->Category}}</td>
									<td>{{$workDetail->ApprovedAgencyEstimate}}</td>
									<td>{{$workDetail->ApprovedAgencyEstimate}}</td>
									<td>{{$workDetail->Dzongkhag}}</td>
									<td>{{$workDetail->WorkExecutionStatus}}<?php $awardedAmount+=($workDetail->ReferenceNo == 3001)?1:0;?></td>
									<td>{{$workDetail->OntimeCompletionScore}}</td>
									<td>{{$workDetail->QualityOfExecutionScore}}</td>
								
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
							@foreach($specializedtradeComments as $comment)
								<tr>
									<td>
										{{convertDateToClientFormat($comment->Date)}}</td>
									<td>{{{$comment->Remarks}}}</td>
									<td>
										<input type="hidden" class="commentid" value="{{$comment->Id}}">
										<input type="hidden" class="specializedtradecommentdate" value="{{convertDateToClientFormat($comment->Date)}}" />
										<input type="hidden" class="specializedtradecomment" value="{{$comment->Remarks}}" />
										{{--<button type="button" class="btn green-haze btn-sm editspecializedtradecomment">Edit</button>--}}
										{{--<button type="button" class="btn bg-red-sunglo btn-sm deletespecializedtradecomment">Delete</button>--}}
										
											@if(isset($registrationApproved) && $registrationApproved == 0))
												<a href="#" class="editspecializedtradecomment">Edit</a>
												&nbsp;|&nbsp;
												<a href="#" class="deletespecializedtradecomment">Delete</a>
											
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
							@foreach($specializedtradeAdverseRecords as $adverseRecord)
								<tr>
									<td>
										{{convertDateToClientFormat($adverseRecord->Date)}}</td>
									<td>{{{$adverseRecord->Remarks}}}</td>
									<td>
										<input type="hidden" class="adverserecordid" value="{{$adverseRecord->Id}}">
										<input type="hidden" class="specializedtradeadverserecorddate" value="{{convertDateToClientFormat($adverseRecord->Date)}}" />
										<input type="hidden" class="specializedtradeadverserecord" value="{{$adverseRecord->Remarks}}" />
										{{--<button type="button" class="btn green-haze btn-sm editspecializedtradecomment">Edit</button>--}}
										{{--<button type="button" class="btn bg-red-sunglo btn-sm deletespecializedtradecomment">Delete</button>--}}
										@if(((isset($registrationApproved) && (int)$registrationApproved!=1)) || (isset($isAdmin) && $isAdmin))
										<a href="#" class="editspecializedtradeadverserecord">Edit</a>
										&nbsp;|&nbsp;
										<a href="#" class="deletespecializedtradeadverserecord">Delete</a>
										@endif
									</td>
								</tr>
							@endforeach
							</tbody>
						</table>
					</div>
					@if((int)$userSpecializedtrade==0)
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

@stop