@extends('master')
@section('pagescripts')
{{ HTML::script('assets/global/scripts/contractor.js') }}
@stop
@section('content')
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption font-green-seagreen">
			<i class="fa fa-cogs"></i>
			<span class="caption-subject">Contractor Information</span>
		</div>
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
							<td colspan="2" class="font-blue-madison bold warning">Correspondence Address</td>
						</tr>
						<tr>
							<td><strong>Dzongkhag</strong></td>
							<td>{{{$generalInformation[0]->Dzongkhag}}}</td>
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
					<a href="#trackrecord" data-toggle="tab">
					Track Record</a>
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
				</div>
				<div class="tab-pane flip-content" id="trackrecord">
					<div class="col-md-12">
						<h4>Below is the list of contractor's work in hand</h4>
					<div class="table-responsive" style="overflow-x:scroll">
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
								<th>Action</th>
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
									<td><a href="{{URL::to('monitoringreport/siterecord/'.$workDetail->RowId."/".$workDetail->Type."?contractor=".$contractorId)}}" class="btn btn-xs red"><i class="fa fa-edit"></i> Record
											<br> Monitoring</a></td>
								</tr>
								<?php $count++ ?>
							@empty
								<tr><td colspan="10" class="font-red text-center">No data to display</td></tr>
							@endforelse
							</tbody>
						</table>
					</div>
					</div><div class="clearfix"></div>
				</div>
			</div>
		</div>
	</div>
</div>
@stop