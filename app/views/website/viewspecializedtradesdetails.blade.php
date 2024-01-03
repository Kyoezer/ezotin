@extends('websitemaster')
@section('main-content')
@foreach($specializedTradeInformations as $specializedTradeInformation)
<h4 class="text-primary"><strong>{{'Specialized Trade '.$specializedTradeInformation->Salutation.' '.$specializedTradeInformation->Name.' Details'}}</strong></h4>
<div class="row">
	<div class="col-md-6">
		<h5><strong>Registration Details</strong></h5>
		<table class="table table-bordered table-condensed">
			<tbody>
				<tr>
					<td class="small-medium"><strong>SP No. </strong></td>
					<td>{{$specializedTradeInformation->SPNo}}</td>
				</tr>
				<tr>
					<td class="small-medium"><strong>Name</strong></td>
					<td>{{$specializedTradeInformation->Salutation.' '.$specializedTradeInformation->Name}}</td>
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
					<td class="small-medium"><strong>Employer Name</strong></td>
					<td>{{'M/s.'.$specializedTradeInformation->EmployerName}}</td>
				</tr>
				<tr>
					<td class="small-medium"><strong>Employer Address</strong></td>
					<td>{{$specializedTradeInformation->EmployerAddress}}</td>
				</tr>
			</tbody>
		</table>
	</div>
	@endforeach
	<div class="col-md-6">
	<h5><strong>Specialization</strong></h5>
	<table class="table table-bordered table-condensed">
		<thead>
			<tr class="success">
				<th>Category</th>
				<th class="text-center">Service</th>
			</tr>
		</thead>
		<tbody>
			@foreach($workClasssifications as $workClasssification)
				<tr>
					<td>
						{{$workClasssification->Code.' ('.$workClasssification->Name.')'}}
					</td>
					<td class="text-center">
						@if((bool)$workClasssification->CmnApprovedCategoryId!=NULL)
						<i class="fa fa-check text-success"></i>
						@else
						<i class="fa fa-times text-danger"></i>
						@endif
					</td>
				</tr>
			@endforeach
	</table>
	</div>
</div>
@stop