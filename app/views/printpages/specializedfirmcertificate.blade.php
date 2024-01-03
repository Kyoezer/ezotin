@extends('certificatemaster')
@section('content')
<table class="with-outer-border-only">
	<tbody>
		<tr>
			<td class="small-medium">CDB Registration No.:</td>
			<td class="certificate-detail">{{$info[0]->SPNo}}</td>
			<td rowspan="4" class="text-center">
				<p class="heading">MySpecializedfirm's Name & CID No.</p><br />
				<span class="certificate-detail"><i>{{$specializedtradeName}}</i><br />
				<i>({{$specializedtradeCIDNo}})</i></span>
			</td>
		</tr>
		<tr>
			<td class="small-medium">Initial Registration Dt.:</td>
			<td class="certificate-detail">{{convertDateToClientFormat($InitialDate)}}</td>
		</tr>
	
		<tr>
			<td class="small-medium">Registration Expiry Dt.:</td>
			<td class="certificate-detail">{{convertDateToClientFormat($info[0]->RegistrationExpiryDate)}}</td>
		</tr>
	</tbody>
</table>
<p class="description">
This is to certify that <i><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="certificate-detail">{{$info[0]->NameOfFirm}}</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong></i> of <i><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$info[0]->Dzongkhag}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong></i> Dzongkhag is a registered "<strong><i>Specialized Firm</i></strong>" with the Construction Development Board. The Specialized Trade is registered with the following specialization.
</p>
<table class="data-large">
	<thead>
		<tr class="success">
			<th>
				Category
			</th>
			<th>
				Specialty Classification
			</th>
		</tr>
	</thead>
	<tbody>
		@foreach($specializedTradeWorkClassifications as $specializedTradeWorkClassification)
		<tr>
			<td>{{$specializedTradeWorkClassification->Code.' - '.$specializedTradeWorkClassification->Name}}</td>
			<td class="text-center">
				@if((bool)$specializedTradeWorkClassification->CmnApprovedCategoryId!=NULL)
					<center><strong><img src="{{asset('assets/global/img/tick.png')}}" width="10"/></strong></center>
				@else 
				<strong>-</strong>
				@endif
			</td>
		</tr>
		@endforeach
	</tbody>
</table>
@stop