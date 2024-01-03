@extends('printmaster')
@section('content')
<table class="data">
	<tbody>
		<tr>
			<td><b>Application No.</b></td>
			<td>{{$generalInformation[0]->ReferenceNo}}, Dt. {{$generalInformation[0]->ApplicationDate}}</td>
			<td><b>Email</b></td>
			<td>{{$generalInformation[0]->Email}}</td>

		</tr>
		<tr>
			<td><b>Proposed Name</b></td>
			<td>{{$generalInformation[0]->NameOfFirm}}</td>
			<td><b>Contact No.</b></td>
			<td>{{$generalInformation[0]->MobileNo}}/{{$generalInformation[0]->TelephoneNo}}</td>
		</tr>
		<tr>
			<td><b>Ownership Type</b></td>
			<td>{{$generalInformation[0]->OwnershipType}}</td>
			<td><b>Country</b></td>
			<td>{{$generalInformation[0]->Country}}</td>
		</tr>
		<tr>
			<td><b>Dzongkhag</b></td>
			<td>{{$generalInformation[0]->Dzongkhag}}</td>
			<td><b>Address</b></td>
			<td>{{$generalInformation[0]->Address}}</td>
		</tr>
	</tbody>
</table>
{{--@if((int)$generalInformation[0]->OwnershipTypeReferenceNo!=14001)--}}
{{--<p class="heading">Documents Attached with this Application</p>--}}
{{--<table class="data-large">--}}
	{{--<thead>--}}
		{{--<tr>--}}
			{{--<th class="text-left">Document Name</th>--}}
		{{--</tr>--}}
	{{--</thead>--}}
	{{--<tbody>--}}
		{{--@foreach($attachments as $attachment)--}}
		{{--<tr>--}}
			{{--<td>{{$attachment->DocumentName}}</td>--}}
		{{--</tr>--}}
		{{--@endforeach--}}
	{{--</tbody>--}}
{{--</table>--}}
{{--@endif--}}
<p class="heading">Fee Structure as per Applied Categories</p>
<table class="data-large">
	<thead>
		<tr>
			<th>Category</th>
			<th>Class</th>
			<th class="text-right">Fee (Nu.)</th>
		</tr>
	</thead>
	<tbody>
		<?php $totalFee=0; ?>
		@foreach($feeStructures as $feeStructure)
			<tr>
				<td>
					{{$feeStructure->CategoryCode.' ('.$feeStructure->Category.')'}}
				</td>
				<td>
					{{$feeStructure->AppliedClassificationCode.' ('.$feeStructure->AppliedClassification.')'}}
				</td>
				<td class="text-right">
					<?php $feeAmount=$feeStructure->AppliedRegistrationFee; ?>
					{{number_format($feeStructure->AppliedRegistrationFee,2)}}
				</td>
			<?php $totalFee+=$feeAmount;?>	
			</tr>
		@endforeach
		<tr class="text-right">
			<td colspan="2"><strong>Total</strong></td>
			<td><strong>{{number_format($totalFee,2)}}</strong></td>
		</tr>
	</tbody>
</table>
<strong>NOTE: </strong>These fees are subject to change by the final approver. Please do not make payment based on this.
<br>
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
			<th>
				Applied Class
			</th>
		</tr>
	</thead>
	<tbody>
		@foreach($contractorWorkClassifications as $contractorWorkClassification)
		<tr>
			<td>{{$contractorWorkClassification->Code.'('.$contractorWorkClassification->Category.')'}}</td>
			<td>{{$contractorWorkClassification->AppliedClassification}}</td>
		</tr>
		@endforeach
	</tbody>
</table>
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
				 Country
			</th>
			<th>
				 Qualification
			</th>
			<th>
				 Designation
			</th>
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
@stop