@extends('websitemaster')
@section('main-content')
<h4 class="text-primary"><strong>{{'Contractor '.$generalInformation[0]->NameOfFirm.' Details'}}</strong></h4>
<h5><b>General Information</b></h5>
<table class="table table-bordered table-condensed">
	<tbody>
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
	</tbody>
</table>

<h5><b>Name of Owner, Partners and/or others with Controlling Interest</b></h5>
<table class="table table-bordered table-condensed">
	<thead>
		<tr class="success">
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

<h5><b>Work Classification</b></h5>
<table class="table table-bordered table-condensed">
	<thead>
		<tr class="success">
			<th>
				Category
			</th>
			<th>
				Applied Class
			</th>
			<th>
				Assessed Class
			</th>
			<th>
				Approved Class
			</th>
		</tr>
	</thead>
	<tbody>
		@foreach($contractorWorkClassifications as $contractorWorkClassification)
		<tr>
			<td>{{$contractorWorkClassification->Code.'('.$contractorWorkClassification->Category.')'}}</td>
			<td>{{$contractorWorkClassification->AppliedClassification}}</td>
			<td>{{$contractorWorkClassification->VerifiedClassification}}</td>
			<td>{{$contractorWorkClassification->ApprovedClassification}}</td>
		</tr>
		@endforeach
	</tbody>
</table>
@stop