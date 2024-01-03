@extends('certificatemaster')
@section('content')
<div class="centerdiv">
	<table class="with-outer-border-only">
		<tbody>
			<tr>
				<td class="small-medium" style="width:128px;">CDB Registration No.:</td>
				<td class="certificate-detail">{{$architectInfo[0]->ARNo}}</td>

			</tr>
			<tr>
				<td class="small-medium" style="width:128px;">Registration Date:</td>
				<td class="certificate-detail">{{convertDateToClientFormat($architectInfo[0]->RegistrationApprovedDate)}}</td>
			</tr>
			<tr>
				<td class="small-medium" style="width:128px;">Expiry Date:</td>
				<td class="certificate-detail">{{convertDateToClientFormat($architectInfo[0]->RegistrationExpiryDate)}}</td>
			</tr>
		</tbody>
	</table>
</div>
<p class="description">
	This is to certify that <i><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="certificate-detail">{{$architectInfo[0]->Name}}</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong></i> bearing CID No. <i><strong><span class="certificate-detail">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$architectInfo[0]->CIDNo}}</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong></i> is a registered Architect with Construction Development Board.
</p>

<br><br><br><br><br><br><br><br>
@stop