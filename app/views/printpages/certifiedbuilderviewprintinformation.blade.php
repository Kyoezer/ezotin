@extends('printmaster')
@section('content')
@foreach($certifiedBuilderInformations as $certifiedBuilderInformation)

<table class="data">
	<tbody>
		<tr>
			<td><b>Status</b></td>
			<td>{{$generalInformation[0]->CurrentStatus == "Approved"?"Active":$generalInformation[0]->CurrentStatus}}</td>
		</tr>
		<tr>
			<td><b>SP No.</b></td>
			<td>{{$generalInformation[0]->SPNo}}</td>
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
			<td><b>Initial Date</b></td>
			<td>{{convertDateToClientFormat($generalInformation[0]->InitialDate)}}</td>

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
		@foreach($certifiedbuilderHumanResources as $certifiedbuilderHumanResource)
		<tr>
			<td>
				{{$sl}}
			</td>
			<td>{{$certifiedbuilderHumanResource->Name}}</td>
			<td>{{$certifiedbuilderHumanResource->CIDNo}}</td>
			<td>{{$certifiedbuilderHumanResource->Sex}}</td>
			<td>{{$certifiedbuilderHumanResource->Country}}</td>
			<td>{{$certifiedbuilderHumanResource->Qualification}}</td>
			<td>{{$certifiedbuilderHumanResource->Designation}}</td>
			<td>{{convertDateToClientFormat($certifiedbuilderHumanResource->JoiningDate)}}</td>
			<td>{{$certifiedbuilderHumanResource->Trade}}</td>
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
		@foreach($certifiedbuilderEquipments as $certifiedbuilderEquipment)
		<tr>
			<td>
				{{$sl}}
			</td>
			<td>{{$certifiedbuilderEquipment->Name}}</td>
			<td>{{$certifiedbuilderEquipment->RegistrationNo}}</td>
			<td>{{$certifiedbuilderEquipment->ModelNo}}</td>
			<td>{{$certifiedbuilderEquipment->Quantity}}</td>
		</tr>
		<?php $sl+=1;?>
		@endforeach
	</tbody>
</table>
@endforeach	
@stop