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
			<td><b>Name</b></td>
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
<table class="data-large">
	<thead>
		<tr>
			<th class="x-x-small">Sl#</th>
			<th>
				Procuring Agency
			</th>
			<th class="">
				Work Order No.
			</th>
			<th class="">
				Name of Work
			</th>
			<th class="">
				Category
			</th>
			<th class="">
				Service
			</th>
			<th>
				Period (Months)
			</th>
			<th>
				Start Date
			</th>
			<th>
				Completion Date
			</th>
			<th>
				Status
			</th>
		</tr>
	</thead>
	<tbody>
		<?php $sl=1; ?>
		@forelse($consultantTrackrecords as $consultantTrackrecord)
		<tr>
			<td>
				{{$sl}}
			</td>
			<td>{{$consultantTrackrecord->ProcuringAgency}}</td>
			<td>{{$consultantTrackrecord->WorkOrderNo}}</td>
			<td>{{$consultantTrackrecord->NameOfWork}}</td>
			<td>{{$consultantTrackrecord->ServiceCategory}}</td>
			<td>{{$consultantTrackrecord->ServiceName}}</td>
			<td>{{$consultantTrackrecord->ContractPeriod}}</td>
			<td>{{convertDateToClientFormat($consultantTrackrecord->WorkStartDate)}}</td>
			<td>{{convertDateToClientFormat($consultantTrackrecord->WorkCompletionDate)}}</td>
			<td>{{$consultantTrackrecord->CurrentWorkStatus}}</td>
			<?php $sl+=1; ?>
		</tr>
		@empty
		<tr>
			<td colspan="10" class="text-center font-red">No Track Records till {{date('d-M-Y')}}</td>
		</tr>
		@endforelse
	</tbody>
</table>
@stop