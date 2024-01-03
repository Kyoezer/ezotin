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



<br>
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
		</tr>
	</thead>
	<tbody>
		@foreach($serviceClassifications as $serviceClassification)
			<tr>
				<td class="small-medium">{{$serviceClassification->Category}}</td>
				<td>{{$serviceClassification->AppliedService}}</td>
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
		@foreach($specializedtradeHumanResources as $specializedtradeHumanResource)
		<tr>
			<td>
				{{$sl}}
			</td>
			<td>{{$specializedtradeHumanResource->Name}}</td>
			<td>{{$specializedtradeHumanResource->CIDNo}}</td>
			<td>{{$specializedtradeHumanResource->Sex}}</td>
			<td>{{$specializedtradeHumanResource->Country}}</td>
			<td>{{$specializedtradeHumanResource->Qualification}}</td>
			<td>{{$specializedtradeHumanResource->Designation}}</td>
			<td>{{$specializedtradeHumanResource->JoiningDate}}</td>
			<td>{{$specializedtradeHumanResource->Trade}}</td>
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
		@foreach($specializedtradeEquipments as $specializedtradeEquipment)
		<tr>
			<td>
				{{$sl}}
			</td>
			<td>{{$specializedtradeEquipment->Name}}</td>
			<td>{{$specializedtradeEquipment->RegistrationNo}}</td>
			<td>{{$specializedtradeEquipment->ModelNo}}</td>
			<td>{{$specializedtradeEquipment->Quantity}}</td>
		</tr>
		<?php $sl+=1;?>
		@endforeach
	</tbody>
</table>
@stop