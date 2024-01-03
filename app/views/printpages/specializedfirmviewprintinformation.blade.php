@extends('printmaster')
@section('content')
@foreach($specializedTradeInformations as $specializedTradeInformation)

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
<p class="heading">Work Category</p>
<table class="data-large">
	<thead>
		<tr>
			<th>Category</th>
			<th>Applied</th>
			@if((int)$isFinalPrint==1)
			<th>Verified</th>
			<th>Approved</th>
			@endif
		</tr>
	</thead>
	<tbody>
		@foreach($workClasssifications as $workClasssification)
			<tr>
				<td>
					{{$workClasssification->Code.' ('.$workClasssification->Name.')'}}
				</td>
				<td class="text-center">
					@if((bool)$workClasssification->CmnAppliedCategoryId!=NULL)
					<img src="assets/cdb/layout/img/tick.png" />
					@else
					<i class="fa fa-times"></i>	
					<strong>-</strong>
					@endif
				</td>
				@if((int)$isFinalPrint==1)
					<td class="text-center">
						@if((bool)$workClasssification->CmnVerifiedCategoryId!=NULL)
						<img src="assets/cdb/layout/img/tick.png" />
						@else
						<strong>-</strong>
						@endif
					</td>
					<td class="text-center">
						@if((bool)$workClasssification->CmnApprovedCategoryId!=NULL)
						<img src="assets/cdb/layout/img/tick.png" />
						@else
						<strong>-</strong>
						@endif
					</td>
				@endif
			</tr>
		@endforeach
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
			<td>{{convertDateToClientFormat($specializedtradeHumanResource->JoiningDate)}}</td>
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
@endforeach	
@stop