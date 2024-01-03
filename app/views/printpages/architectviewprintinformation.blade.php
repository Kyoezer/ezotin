@extends('printmaster')
@section('content')
@foreach($architectInformations as $architectInformation)
@if((int)$isFinalPrint==0)
<p class="heading">Registration Fee Structure</p>
 <table class="data-large">
	<thead>
		<tr>
			<th>Type</th>
			<th width="20%" class="text-center">Validity (yrs)</th>
			<th class="text-right">Amount</th>
		</tr>
	</thead>
	<tbody>
		<?php $totalFeesApplicable=0; ?>
		@foreach($feeDetails as $feeDetail)
		<tr>
			<td class="text-center">{{$feeDetail->SectorType}}</td>
			<td class="text-center">
				@if(empty($feeDetail->RegistrationValidity))
					-
				@else
				{{$feeDetail->RegistrationValidity}}
				@endif
			</td>
			<td class="text-right">
				@if(empty($feeDetail->NewRegistrationFee))
					-
				@else
				{{number_format($feeDetail->NewRegistrationFee,2)}}
				@endif
			</td>
			<?php $totalFeesApplicable+=$feeDetail->NewRegistrationFee; ?>
		</tr>
		@endforeach
		<tr>
			<td colspan="2" class="bold text-right">
				Total
			</td>
			<td class="text-right">
				{{number_format($totalFeesApplicable,2)}}
			</td>
		</tr>
	</tbody>
</table>
<strong>NOTE: </strong>These fees are subject to change by the final approver. Please do not make payment based on this.
<br>
@endif
<p class="heading">Registration Details</p>
<table class="data-large">
	<tbody>
		@if((int)$isFinalPrint==1)
		<tr>
			<td class="small-medium"><strong>Current Status</strong></td>
			<td>{{$architectInformation->CurrentStatus}}</td>
		</tr>
		<tr>
			<td class="small-medium"><strong>AR No. </strong></td>
			<td>{{$architectInformation->ARNo}}</td>
		</tr>
		@endif
		<tr>
			<td class="small-medium"><strong>Type of Architect </strong></td>
			<td>{{$architectInformation->ArchitectType}}</td>
		</tr>
		<tr>
			<td class="small-medium"><strong>Name</strong></td>
			<td>{{$architectInformation->Name}}</td>
		</tr>
		<tr>
			<td class="small-medium"><strong>CID No./Work Permit No.</strong></td>
			<td>{{$architectInformation->CIDNo}}</td>
		</tr>
		<tr>
			<td class="small-medium"><strong>Country</strong></td>
			<td>{{$architectInformation->Country}}</td>
		</tr>
		<tr>
			<td class="small-medium"><strong>Dzongkhag</strong></td>
			<td>{{$architectInformation->Dzongkhag}}</td>
		</tr>
		<tr>
			<td class="small-medium"><strong>Gewog</strong></td>
			<td>{{$architectInformation->Gewog}}</td>
		</tr>
		<tr>
			<td class="small-medium"><strong>Village</strong></td>
			<td>{{$architectInformation->Village}}</td>
		</tr>
		<tr>
			<td class="small-medium"><strong>Email</strong></td>
			<td>{{$architectInformation->Email}}</td>
		</tr>
		<tr>
			<td class="small-medium"><strong>Mobile No.</strong></td>
			<td>{{$architectInformation->MobileNo}}</td>
		</tr>
		<tr>
			<td class="small-medium"><strong>Employer Name</strong></td>
			<td>
				@if(!empty($architectInformation->EmployerName))
				{{'M/s.'.$architectInformation->EmployerName}}
				@endif
			</td>
		</tr>
		<tr>
			<td class="small-medium"><strong>Employer Address</strong></td>
			<td>{{$architectInformation->EmployerAddress}}</td>
		</tr>
	</tbody>
</table>
<p class="heading">Professional Qualification</p>
<table class="data-large">
	<tbody>
		<tr>
			<td class="small-medium"><strong>Qualification</strong></td>
			<td>{{$architectInformation->Qualification}}</td>
		</tr>
		<tr>
			<td class="small-medium"><strong>Year of Graduation</strong></td>
			<td>{{$architectInformation->GraduationYear}}</td>
		</tr>
		<tr>
			<td class="small-medium"><strong>Name of University</strong></td>
			<td>{{$architectInformation->NameOfUniversity}}</td>
		</tr>
		<tr>
			<td class="small-medium"><strong>University Country</strong></td>
			<td>{{$architectInformation->UniversityCountry}}</td>
		</tr>
	</tbody>
</table>
@endforeach	
@stop