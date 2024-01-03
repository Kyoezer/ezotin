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
<p class="heading">Fee Structure as per Applied Services</p>
<table class="data-large">
	<thead>
		<tr>
			<th>Category</th>
			<th>Applied</th>
			<th class="text-center">No. of Service (Applied) X Fee (Nu)</th>
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
							<?php $noOfServicePerCategory+=1; ?>
							{{$appliedServiceFee->ServiceCode}},
						@endif
					@endforeach
				</td>
				<td class="text-center">
					{{$noOfServicePerCategory.' X '.number_format($feeStructures[0]->ConsultantAmount,2)}}
				</td>
				<td class="text-right">
					<?php $curTotalAmount=$noOfServicePerCategory*3000;$overAllTotalAmount+=$curTotalAmount; ?>
					{{number_format($noOfServicePerCategory*$feeStructures[0]->ConsultantAmount,2)}}
				</td>
			</tr>
			<?php $noOfServicePerCategory=0; ?>
			@endforeach
			<tr class="bold text-right">
				<td colspan="3"><strong>Total</strong></td>
				<td><strong>{{number_format($overAllTotalAmount,2)}}</strong></td>
			</tr>
			<tr>
				<td colspan="4">
					*Nu {{number_format($feeStructures[0]->ConsultantAmount,2)}} is applicable for each service applied under the category
				</td>
			</tr>
	</tbody>
</table>
<strong>NOTE: </strong>These fees are subject to change by the final approver. Please do not make payment based on this.
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
			<td>{{$consultantHumanResource->JoiningDate}}</td>
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
@stop