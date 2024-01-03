@extends('certificatemaster')
@section('content')
<div class="centerdiv">
	<table class="with-outer-border-only">
		<tbody>
			<tr>
				<td class="small-medium">CDB Registration No.:</td>
				<td>{{$engineerInfo[0]->CDBNo}}</td>
			</tr>
			<tr>
				<td class="small-medium">Registration Date:</td>
				<td>{{convertDateToClientFormat($engineerInfo[0]->RegistrationApprovedDate)}}</td>
			</tr>
			<tr>
				<td class="small-medium">Registration Expiry Date:</td>
				<td>{{convertDateToClientFormat($engineerInfo[0]->RegistrationExpiryDate)}}</td>
			</tr>
		</tbody>
	</table>
</div>
<p class="description">
	This is to certify that <i><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$engineerInfo[0]->Name}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong></i> bearing CID No. <i><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$engineerInfo[0]->CIDNo}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong></i> is a registered Engineer with Construction Development Board.
</p>
@stop