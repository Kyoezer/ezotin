@extends('certificatemaster')
@section('content')
	@if($nonBhutanese)
		<center><strong style="color: darkorange; z-index: 999999; font-weight: bolder;">"ONLY FOR INTERNATIONAL COMPETITIVE BIDDING"</strong></center>
	@endif
<table class="with-outer-border-only">
	<tbody>
		<tr>
			<td class="small-medium">CDB Registration No.:</td>
			<td class="certificate-detail">{{$info[0]->CDBNo}}</td>
			<td rowspan="4" class="text-center">
				<p class="heading">Contractor's Name & CID No.</p><br />
				<span class="certificate-detail"><i>{{$contractorName}}</i><br />
				<i>({{$contractorCIDNo}})</i></span>
			</td>
		</tr>
		<tr>
			<td class="small-medium">Initial Registration Dt.:</td>
			<td class="certificate-detail">{{convertDateToClientFormat($InitialDate)}}</td>
		</tr>
		<tr>
			<td class="small-medium">Up-Gr/Revalidation Dt.:</td>
			<td class="certificate-detail">{{convertDateToClientFormat($info[0]->ApplicationDate)}}</td>
		</tr>
		<tr>
			<td class="small-medium">Registration Expiry Dt.:</td>
			<td class="certificate-detail">{{convertDateToClientFormat($info[0]->RegistrationExpiryDate)}}</td>
		</tr>
	</tbody>
</table>
<p class="description">
	This is to certify that M/s <i><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="certificate-detail">{{$info[0]->NameOfFirm}}</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong></i> @if($nonBhutanese){{"from"}}@else{{"of"}}@endif <i><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="certificate-detail">@if($nonBhutanese){{$info[0]->Country}}@else{{$info[0]->Dzongkhag}}@endif&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></strong></i> @if(!$nonBhutanese){{"Dzongkhag"}}@endif is a<?php if($nonBhutanese): ?>{{" <u><strong>Non-Bhutanese</strong></u>"}}<?php endif; ?> registered Construction Firm with the Construction Development Board. The firm is registered in the following categories and classifications:
</p>
<table class="data-large">
	<thead>
		<tr class="success">
			<th>
				Category
			</th>
			<th>
				Classification
			</th>
		</tr>
	</thead>
	<tbody>
		@foreach($contractorWorkClassifications as $contractorWorkClassification)
		<tr>
			<td>{{$contractorWorkClassification->Code.' - '.$contractorWorkClassification->Category}}</td>
			<td class="certificate-detail">
				@if((bool)$contractorWorkClassification->ApprovedClassification!=NULL)
				{{$contractorWorkClassification->ApprovedClassification}}
				@else 
				<strong>-</strong>
				@endif
			</td>
		</tr>
		@endforeach
	</tbody>
</table>
@stop