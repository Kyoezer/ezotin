@extends('printmaster')
@section('content')
<p style="font-size: 10pt; display: inline-block;">
	<table class="data-large table-smallfont" style="width: 100%; margin-top: -10px;">
		<tbody>
			<tr>
				<td width="15%"><strong>Firm Name</strong></td>
				<td width="35%">
					{{$monitoringDetails[0]->NameOfFirm}}
				</td>
				<td width="31%"><strong>Has Office Establishment</strong></td>
				<td class="text-center">
					{{($monitoringDetails[0]->HasOfficeEstablishment==1)?"<img src='".asset('assets/global/img/tick.png')."' width='14'/>":"<img src='".asset('assets/global/img/cross.png')."' width='10'/>"}}
				</td>
			</tr>
			<tr>
				<td width="15%"><strong>CDB No</strong></td>
				<td width="35%">{{$monitoringDetails[0]->CDBNo}}</td>
				<td width="31%"><strong>Has Sign Board</strong></td>
				<td class="text-center">
					{{($monitoringDetails[0]->HasSignBoard==1)?"<img src='".asset('assets/global/img/tick.png')."' width='14'/>":"<img src='".asset('assets/global/img/cross.png')."' width='10'/>"}}
				</td>
			</tr>
			<tr>
				<td width="15%"><strong>Monitored On</strong></td>
				<td width="35%">{{convertDateToClientFormat($monitoringDetails[0]->MonitoringDate)}}</td>
				<td width="31%"><strong>Cannot be contacted</strong></td>
				<td class="text-center">
					{{($monitoringDetails[0]->CannotBeContacted==1)?"<img src='".asset('assets/global/img/tick.png')."' width='14'/>":"<img src='".asset('assets/global/img/cross.png')."' width='10'/>"}}
				</td>
			</tr>
			<tr>
				<td width="15%"><strong>For Year</strong></td>
				<td width="35%">{{$monitoringDetails[0]->Year}}</td>
				<td width="31%"><strong>Deceiving on Location Change</strong></td>
				<td class="text-center">
					{{($monitoringDetails[0]->DeceivingOnLocationChange==1)?"<img src='".asset('assets/global/img/tick.png')."' width='14'/>":"<img src='".asset('assets/global/img/cross.png')."' width='10'/>"}}
				</td>
			</tr>
		</tbody>
	</table>
</p>
<br>
<table class="data-large table-smallfont" style="width: 100%;">
	<tbody>
		<tr style="background: #ccc;"><td colspan="2"><center><strong>REQUIRED EQUIPMENT</strong></center></td></tr>
		@foreach($monitoringEquipmentDetails as $eqDetail)
		<tr>
			<td width="50%"><strong>{{$eqDetail->Equipment}}@if((bool)$eqDetail->RegistrationNo){{" (".$eqDetail->RegistrationNo.")"}}@endif</strong></td>
			<td class="text-center">
				{{($eqDetail->Checked==1)?"<img src='".asset('assets/global/img/tick.png')."' width='14'/>":"<img src='".asset('assets/global/img/cross.png')."' width='10'/>"}}
			</td>
		</tr>
		@endforeach
	</tbody>
</table>
<br>
<table class="data-large table-smallfont" style="width: 100%;">
	<tbody>
	<tr style="background: #ccc;"><td colspan="2"><center><strong>REQUIRED PERSONNEL</strong></center></td></tr>
	@foreach($monitoringHRDetails as $hrDetail)
		<tr>
			<td width="50%"><strong>{{$hrDetail->Designation." (".$hrDetail->Personnel.")"}}</strong></td>
			<td class="text-center">
				{{($hrDetail->Checked==1)?"<img src='".asset('assets/global/img/tick.png')."' width='14'/>":"<img src='".asset('assets/global/img/cross.png')."' width='10'/>"}}
			</td>
		</tr>
	@endforeach
	</tbody>
</table>
<br>
<p style="font-size: 10pt;">Remarks: {{(bool)$monitoringDetails[0]->Remarks?$monitoringDetails[0]->Remarks:"---"}}</p>
<p style="font-size: 10pt;">Status: {{($monitoringDetails[0]->MonitoringStatus == 1)?"Fulfilled":"Not fulfilled"}}</p>
@stop