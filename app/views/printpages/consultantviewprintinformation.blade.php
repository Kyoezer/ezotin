@extends('printmaster')
@section('content')
<table class="data">
	<tbody>
		<tr>
			<td><b>Status</b></td>
			<td>{{$generalInformation[0]->CurrentStatus}}</td>
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
			<td><b>Name</b></td>
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
	</tbody>
</table>
<p class="heading">Name of Owner, Partners and/or others with Controlling Intrest</p>
<table class="data-large">
	<thead>
		<tr class="success">
			<th class="x-x-small">Sl#</th>
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
			<th>
				Show in Certificate
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
			<td class="x-small">
				@if((int)$ownerPartnerDetail->ShowInCertificate==1)
				<input type="checkbox" checked="checked" disabled="disabled">
				@endif
			</td>
		</tr>
		<?php $sl+=1;?>
		@endforeach
	</tbody>
</table>
<p class="heading">Service Classification</p>
<table class="data-large consultantservice">
	<thead>
		<tr class="success">
			<th>
				Service
			</th>
			<th>
				Applied
			</th>
			<th>
				Assessed
			</th>
			<th>
				Approved
			</th>
		</tr>
	</thead>
	<tbody>
		@foreach($serviceClassifications as $serviceClassification)
			<tr>
				<td class="small-medium">{{$serviceClassification->Category}}</td>
				<td>{{$serviceClassification->AppliedService}}</td>
				<td>{{$serviceClassification->VerifiedService}}</td>
				<td>{{$serviceClassification->ApprovedService}}</td>
			</tr>
		@endforeach
	</tbody>
</table>
<p class="heading">Human Resource</p>
<table class="data-large">
	<thead>
		<tr>
			<th class="x-x-small">Sl#</th>
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
				 Country
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
		@foreach($consultantHumanResources as $consultantHumanResource)
		<tr>
			<td>
				{{$sl}}
			</td>
			<td>{{$consultantHumanResource->Name}}</td>
			<td>{{$consultantHumanResource->CIDNo}}</td>
			<td>{{$consultantHumanResource->Sex}}</td>
			<td>{{$consultantHumanResource->Country}}</td>
			<td>{{$consultantHumanResource->Qualification}}</td>
			<td>{{$consultantHumanResource->Designation}}</td>
			<td>{{convertDateToClientFormat($consultantHumanResource->JoiningDate)}}</td>
			<td>{{$consultantHumanResource->Trade}}</td>
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
		@foreach($consultantEquipments as $consultantEquipment)
		<tr>
			<td>
				{{$sl}}
			</td>
			<td>{{$consultantEquipment->Name}}</td>
			<td>{{$consultantEquipment->RegistrationNo}}</td>
			<td>{{$consultantEquipment->ModelNo}}</td>
			<td>{{$consultantEquipment->Quantity}}</td>
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
			<td>
				@if($consultantTrackrecord->ProcuringAgency!=$currentProcuringAgeny)
				{{$consultantTrackrecord->ProcuringAgency}}
				@endif
			</td>
			<td>{{$consultantTrackrecord->WorkOrderNo}}</td>
			<td>{{$consultantTrackrecord->NameOfWork}}</td>
			<td>{{$consultantTrackrecord->ServiceCategory}}</td>
			<td>{{$consultantTrackrecord->ServiceName}}</td>
			<td>{{$consultantTrackrecord->ContractPeriod}}</td>
			<td>{{convertDateToClientFormat($consultantTrackrecord->WorkStartDate)}}</td>
			<td>{{convertDateToClientFormat($consultantTrackrecord->WorkCompletionDate)}}</td>
			<?php $currentProcuringAgeny=$consultantTrackrecord->ProcuringAgency;$sl+=1; ?>
		</tr>
		@empty
		<tr>
			<td colspan="9" class="text-center font-red">No Track Records till {{date('d-M-Y')}}</td>
		</tr>
		@endforelse
	</tbody>
</table>
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
		@if(!$commentsAdverseRecords->isEmpty())
			<?php $sl=1;?>
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
			<?php $sl+=1;?>
			@endforeach
			<tr>
				<td colspan="3"><strong><i>Adverse Records</i></strong></td>
			</tr>
			<?php $sl=1;?>
			@foreach($commentsAdverseRecords as $commentsAdverseRecord)
				@if((int)$commentsAdverseRecord->Type==2)
				<tr>
					<td>{{$sl}}</td>
					<td>{{convertDateToClientFormat($commentsAdverseRecord->Date)}}</td>
					<td>{{$commentsAdverseRecord->Remarks}}</td>
				</tr>
				@endif
				<?php $sl+=1;?>
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