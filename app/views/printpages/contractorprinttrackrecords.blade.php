@extends('printmaster')
@section('content')
<table class="data">
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
<br />
<table class="data-large">
	<thead class="flip-content">
	<tr>
		<th>Sl.No.</th>
		<th>Year</th>
		<th>Agency</th>
		<th>Work Id</th>
		<th>Work Name</th>
		<th>Category</th>
		<th>Awarded Amount</th>
		<th>Final Amount</th>
		<th>Dzongkhag</th>
		<th>Status</th>
		<th>APS scoring</th>
		<th>APS (100)</th>
		<th>Remarks</th>
	</tr>
	</thead>
	<tbody>
	<?php $count = 1; $awardedAmount = 0;?>
	@forelse($trackRecords as $workDetail)
		<tr>
			<td>{{$count}}</td>
			<td>{{$workDetail->Year}}</td>
			<td>{{$workDetail->Agency}}</td>
			<td>{{$workDetail->WorkId}}</td>
			<td>{{$workDetail->NameOfWork}}</td>
			<td>{{$workDetail->Category}}</td>
			<td>{{$workDetail->AwardedAmount}}</td>
			<td>{{$workDetail->FinalAmount}}</td>
			<td>{{$workDetail->Dzongkhag}}</td>
			<td>{{$workDetail->Status}}<?php $awardedAmount+=($workDetail->ReferenceNo == 3001)?1:0;?></td>
			<td>
				<?php if((int)$workDetail->APS == 100) {
					$points = 10;
				}
				if(((int)$workDetail->APS < 100) && ((int)$workDetail->APS >= 50)) {
					$points = 10 - (ceil((100 - (int)$workDetail->APS) / 5));
				}
				if((int)$workDetail->APS < 50){
					$points = 0;
				}
				?>
				{{$points}}
			</td>
			<td>{{$workDetail->APS}}</td>
			<td>{{$workDetail->Remarks}}</td>
		</tr>
		<?php $count++ ?>
	@empty
		<tr><td colspan="12" class="font-red text-center">No data to display</td></tr>
	@endforelse
	</tbody>
</table>
@stop