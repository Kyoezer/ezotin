@extends('printmaster')
@section('content')
<table class="data">
	<tbody>
		<tr>
			<td><b>Status</b></td>
			<td>{{$generalInformation[0]->CurrentStatus == "Approved"?"Active":$generalInformation[0]->CurrentStatus}}</td>
		</tr>
		<tr>
			<td><b>CDB No.</b></td>
			<td>{{$generalInformation[0]->CDBNo}}</td>
			<td><b>Email</b></td>
			<td>{{$generalInformation[0]->Email}}</td>
		</tr>
		<tr>
			<td><b>Ownership Type</b></td>
			<td>{{$generalInformation[0]->OwnershipType}}</td>
			<td><b>Telephone No.</b></td>
			<td>{{$generalInformation[0]->TelephoneNo}}</td>
		</tr>
		<tr>
			<td><b>Name of Firm</b></td>
			<td>{{$generalInformation[0]->NameOfFirm}}</td>
			<td><b>Mobile No.</b></td>
			<td>{{$generalInformation[0]->MobileNo}}</td>
		</tr>
		<tr>
			<td><b>Country</b></td>
			<td>{{$generalInformation[0]->Country}}</td>
			<td><b>Fax No.</b></td>
			<td>{{$generalInformation[0]->FaxNo}}</td>
		</tr>
		<tr>
			<td><b>Dzongkhag</b></td>
			<td>{{$generalInformation[0]->Dzongkhag}}</td>
			<td><b>Address</b></td>
			<td>{{$generalInformation[0]->Address}}</td>
		</tr>
		<tr>
			<td><b>TPN</b></td>
			<td>{{$generalInformation[0]->TPN or '--'}}</td>
			<td><b>Trade License No.</b></td>
			<td>{{$generalInformation[0]->TradeLicenseNo or '--'}}</td>
		</tr>
		<tr>
			<td><b>Last Renewal Date</b></td>
			<td>{{convertDateToClientFormat($generalInformation[0]->RenewalDate)}}</td>
			<td><b>Expiry Date</b></td>
			<td>{{convertDateToClientFormat($generalInformation[0]->RegistrationExpiryDate)}}</td>
		</tr>
		<tr>
			<td><b>Deregistered Date</b></td>
			<td>{{convertDateToClientFormat($generalInformation[0]->DeRegisteredDate)}}</td>

		</tr>
        @if(Input::has('type'))
        <tr>
            <td><b>Registered Address</b></td>
            <td>{{$generalInformation[0]->RegisteredAddress}}</td>
            <td><b>Registration Expiry Date</b></td>
            <td>{{date_format(date_create($generalInformation[0]->RegistrationExpiryDate),'d-m-Y')}}</td>
        </tr>
        @endif
	</tbody>
</table>
<p class="heading">Name of Owner, Partners and/or others with Controlling Interest</p>
<table class="data-large">
	<thead>
		<tr>
			<th class="x-x-small">
			 	Sl#
			</th>
			<th>
				Name
			</th>
			<th>
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
		</tr>
	</thead>
	<tbody>
		<?php $sl=1;?>
		@foreach($ownerPartnerDetails as $ownerPartnerDetail)
		<tr>
			<td>
				{{$sl}}
			</td>
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
		</tr>
		<?php $sl+=1;?>
		@endforeach
	</tbody>
</table>
<p class="heading">Work Classification</p>
<table class="data-large">
	<thead>
		<tr class="success">
			<th>
				Category
			</th>
            @if(!Input::has('type'))
			<th>
				Applied Class
			</th>
			<th>
				Assessed Class
			</th>
            @endif
			<th>
				Approved Class
			</th>
		</tr>
	</thead>
	<tbody>
		@foreach($contractorWorkClassifications as $contractorWorkClassification)
		<tr>
			<td>{{$contractorWorkClassification->Code.'('.$contractorWorkClassification->Category.')'}}</td>
            @if(!Input::has('type'))
			<td>{{$contractorWorkClassification->AppliedClassification}}</td>
			<td>{{$contractorWorkClassification->VerifiedClassification}}</td>
            @endif
			<td>{{$contractorWorkClassification->ApprovedClassification}}</td>
		</tr>
		@endforeach
	</tbody>
</table>
@if(!Input::has('type'))
<p class="heading">Human Resource</p>
<table class="data-large">
	<thead>
		<tr>
			<th class="x-x-small">
			 	Sl#
			</th>
			<th>
				 Name
			</th>
			<th>
				 CID/Work Permit No.
			</th>
			<th>
				 Sex
			</th>
			<th>
				 Nationality
			</th>
			<th>
				 Qualification
			</th>
			<th>
				 Designation
			</th>
			<th>Joining Date</th>
			<th>
				Trade/Fields
			</th>
		</tr>
	</thead>
	<tbody>
		<?php $sl=1;?>
		@foreach($contractorHumanResources as $contractorHumanResource)
		<tr>
			<td>{{$sl}}</td>
			<td>{{$contractorHumanResource->Name}}</td>
			<td>{{$contractorHumanResource->CIDNo}}</td>
			<td>{{$contractorHumanResource->Sex}}</td>
			<td>{{$contractorHumanResource->Country}}</td>
			<td>{{$contractorHumanResource->Qualification}}</td>
			<td>{{$contractorHumanResource->Designation}}</td>
			<td>{{convertDateToClientFormat($contractorHumanResource->JoiningDate)}}</td>
			<td>{{$contractorHumanResource->Trade}}</td>
		</tr>
		<?php $sl+=1;?>
		@endforeach
	</tbody>
</table>
<p class="heading">Equipments</p>
<table class="data-large">
	<thead>
		<tr>
			<th class="x-x-small">Sl#</th>
			<th>
				Equipment Name
			</th>
			<th>
				 Registration No
			</th>
			<th>
				Equipment Model
			</th>
			<th>
				Quantity
			</th>
		</tr>
	</thead>
	<tbody>
		<?php $sl=1;?>
		@foreach($contractorEquipments as $contractorEquipment)
		<tr>
			<td>{{$sl}}</td>
			<td>{{$contractorEquipment->Name}}</td>
			<td>{{$contractorEquipment->RegistrationNo}}</td>
			<td>{{$contractorEquipment->ModelNo}}</td>
			<td>{{$contractorEquipment->Quantity}}</td>
		</tr>
		<?php $sl+=1;?>
		@endforeach
	</tbody>
</table>
@if((int)$isfinalprint==1)
<p class="heading">Track Records</p>
<table class="data-large">
	<thead>
		<tr>
			<th class="x-x-small">Sl#</th>
			<th>Year</th>
			<th>
				Procuring Agency
			</th>
			<th class="">
				Work Id
			</th>
			<th class="">
				Name of Work
			</th>
			<th class="">
				Category
			</th>
			<th>
				Contract Amount
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
			<th>Status</th>
			<th>APS</th>
		</tr>
	</thead>
	<tbody>
		<?php $currentProcuringAgeny="";$sl=1; ?>
		@forelse($contractorTrackrecords as $contractorTrackrecord)
		<tr>
			<td>{{$sl}}</td>
			<td>@if($contractorTrackrecord->WorkStatus == 'Completed'){{date_format(date_create($contractorTrackrecord->WorkCompletionDate),'Y')}}@else{{date_format(date_create($contractorTrackrecord->WorkStartDate),'Y')}}@endif</td>
			<td>
				@if($contractorTrackrecord->ProcuringAgency!=$currentProcuringAgeny)
				{{$contractorTrackrecord->ProcuringAgency}}
				@endif
			</td>
			<td>{{$contractorTrackrecord->WorkId}}</td>
			<td>{{$contractorTrackrecord->NameOfWork}}</td>
			<td>{{$contractorTrackrecord->ProjectCategory}}</td>
			<td>{{$contractorTrackrecord->EvaluatedAmount}}</td>
			<td>{{$contractorTrackrecord->ContractPeriod}}</td>
			<td>{{convertDateToClientFormat($contractorTrackrecord->WorkStartDate)}}</td>
			<td>{{convertDateToClientFormat($contractorTrackrecord->WorkCompletionDate)}}</td>
			<td>{{$contractorTrackrecord->WorkStatus}}</td>
			<td>{{$contractorTrackrecord->OntimeCompletionScore+$contractorTrackrecord->QualityOfExecutionScore}}</td>
			<?php $currentProcuringAgeny=$contractorTrackrecord->ProcuringAgency; $sl+=1;?>
		</tr>
		@empty
		<tr>
			<td colspan="9" class="text-center font-red">No Track Records till {{date('d-M-Y')}}</td>
		</tr>
		@endforelse
	</tbody>
</table>
@endif
<p class="heading">Comments/Adverse Records</p>
<table class="data-large">
	<thead>
		<tr>
			<th class="x-x-small">Sl#</th>
			<th class="small-s">
				Date
			</th>
			<th>
				Remarks
			</th>
		</tr>
	</thead>
	<tbody>
		<?php $sl=1;?>
		@if(!$commentsAdverseRecords->isEmpty())
			<tr>
				<td colspan="3"><strong><i>Comments</i></strong></td>
			</tr>
			@foreach($commentsAdverseRecords as $commentsAdverseRecord)
				@if((int)$commentsAdverseRecord->Type==1)
				<tr>
					<td>{{$sl}}</td>
					<td>{{convertDateToClientFormat($commentsAdverseRecord->Date)}}</td>
					<td>{{$commentsAdverseRecord->Remarks}}</td>
				</tr>
				@endif
			@endforeach
			<tr>
				<td colspan="3"><strong><i>Adverse Records</i></strong></td>
			</tr>
			@foreach($commentsAdverseRecords as $commentsAdverseRecord)
				@if((int)$commentsAdverseRecord->Type==2)
				<tr>
					<td>{{$sl}}</td>
					<td>{{convertDateToClientFormat($commentsAdverseRecord->Date)}}</td>
					<td>{{$commentsAdverseRecord->Remarks}}</td>
				</tr>
				@endif
			@endforeach
		@else
			<tr class="text-center">
				<td colspan="3">No Comments or Adverse Records till {{date('d-M-Y')}}</td>
			</tr>
		@endif
	</tbody>
</table>
@endif
@stop