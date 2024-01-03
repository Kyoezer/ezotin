@extends('printmaster')
@section('content')
	@if((int)Input::get('ref')==1 || (int)Input::get('ref') == 8)
	<table class="data-large">
		<thead>
			<tr>
				<th>Sl#</th>
				<th>CDB No.</th>
				<th>Name of Firm</th>
				<th>Class</th>
				<th>Name of Participant</th>
				<th>CID/Work Permit No. of Participant</th>
				<th>Designation</th>
				<th>Email</th>
				<th>Contact No.</th>
				<th>Registered On</th>
			</tr>	
		</thead>
		<tbody>
			<?php $sl=1; ?>
			@forelse($listOfRegisteredTraniees as $listOfRegisteredTraniee)
			<tr>
				<td>{{$sl}}</td>
				<td>{{$listOfRegisteredTraniee->CDBNo}}</td>
				<td>{{$listOfRegisteredTraniee->NameOfFirm}}</td>
				<td>{{$listOfRegisteredTraniee->ClassCode}}</td>
				<td>{{$listOfRegisteredTraniee->FullName}}</td>
				<td>{{$listOfRegisteredTraniee->CIDNoOfParticipant}}</td>
				<td>{{$listOfRegisteredTraniee->Designation}}</td>
				<td>{{$listOfRegisteredTraniee->Email}}</td>
				<td>{{$listOfRegisteredTraniee->ContactNo}}</td>
				<td>{{convertDateTimeToClientFormat($listOfRegisteredTraniee->CreatedOn)}}</td>
			</tr>
			<?php $sl++; ?>
			@empty
			<tr>
				<td colspan="10" class="text-center font-red">No Registered Traniees</td>
			</tr>
			@endforelse
		</tbody>
	</table>
	@else
	<table class="data-large">
		<thead>
			<tr>
				<th>Sl#</th>
				<th>Name of Participant</th>
				<th>CID/Work Permit No. of Participant</th>
				<th>Agency</th>
				<th>Designation</th>
				<th>Email</th>
				<th>Contact No.</th>
				<th>Registered On</th>
				<th>Venue</th>
			</tr>	
		</thead>
		<tbody>
			<?php $sl=1; ?>
			@forelse($listOfRegisteredTraniees as $listOfRegisteredTraniee)
			<tr>
				<td>{{$sl}}</td>
				<td>{{$listOfRegisteredTraniee->FullName}}</td>
				<td>{{$listOfRegisteredTraniee->CIDNoOfParticipant}}</td>
				<td>{{$listOfRegisteredTraniee->Agency}}</td>
				<td>{{$listOfRegisteredTraniee->Designation}}</td>
				<td>{{$listOfRegisteredTraniee->Email}}</td>
				<td>{{$listOfRegisteredTraniee->ContactNo}}</td>
				<td>{{convertDateTimeToClientFormat($listOfRegisteredTraniee->CreatedOn)}}</td>
				<td>{{$listOfRegisteredTraniee->venue}}</td>
			</tr>
			<?php $sl++; ?>
			@empty
			<tr>
				<td colspan="8" class="text-center font-red">No Registered Traniees</td>
			</tr>
			@endforelse
		</tbody>
	</table>
	@endif
@stop