@extends('printmaster')
@section('content')
@foreach($specializedTradeInformations as $specializedTradeInformation)
<p class="heading">Registration Details</p>
<table class="data-large">
	<tbody>
		@if((int)$isFinalPrint==1)
		<tr>
			<td class="small-medium"><strong>Current Status</strong></td>
			<td>{{$specializedTradeInformation->CurrentStatus}}</td>
		</tr>
		<tr>
			<td class="small-medium"><strong>SP No. </strong></td>
			<td>{{$specializedTradeInformation->SPNo}}</td>
		</tr>
		@endif
		<tr>
			<td class="small-medium"><strong>Name</strong></td>
			<td>{{$specializedTradeInformation->Name}}</td>
		</tr>
		<tr>
			<td class="small-medium"><strong>CID No.</strong></td>
			<td>{{$specializedTradeInformation->CIDNo}}</td>
		</tr>
		<tr>
			<td class="small-medium"><strong>Dzongkhag</strong></td>
			<td>{{$specializedTradeInformation->Dzongkhag}}</td>
		</tr>
		<tr>
			<td class="small-medium"><strong>Gewog</strong></td>
			<td>{{$specializedTradeInformation->Gewog}}</td>
		</tr>
		<tr>
			<td class="small-medium"><strong>Village</strong></td>
			<td>{{$specializedTradeInformation->Village}}</td>
		</tr>
		<tr>
			<td class="small-medium"><strong>Email</strong></td>
			<td>{{$specializedTradeInformation->Email}}</td>
		</tr>
		<tr>
			<td class="small-medium"><strong>Mobile No.</strong></td>
			<td>{{$specializedTradeInformation->MobileNo}}</td>
		</tr>
		<tr>
			<td class="small-medium"><strong>Telephone No.</strong></td>
			<td>{{$specializedTradeInformation->TelephoneNo}}</td>
		</tr>
		<tr>
			<td class="small-medium"><strong>Employer Name</strong></td>
			<td>{{'M/s.'.$specializedTradeInformation->EmployerName}}</td>
		</tr>
		<tr>
			<td class="small-medium"><strong>Employer Address</strong></td>
			<td>{{$specializedTradeInformation->EmployerAddress}}</td>
		</tr>
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
@endforeach	
@stop