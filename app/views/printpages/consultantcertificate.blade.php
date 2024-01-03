@extends('certificatemaster')
@section('content')
<table class="with-outer-border-only table_lessspace" style="margin-top:-18px;">
	<tbody>
		<tr>
			<td class="small-medium">CDB Registration No.:</td>
			<td class="certificate-detail">{{$info[0]->CDBNo}}</td>
			<td rowspan="4" class="text-center">
				<p class="heading">Proprietors Name & CID No.</p><br />
				<span class="certificate-detail">
					<i>{{$consultantName}}</i><br />
					<i>({{$consultantCIDNo}})</i>
				</span>
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
<p class="description" style="line-height: 1; padding-top: 4px;">
	This is to certify that M/s <i><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="certificate-detail">{{$info[0]->NameOfFirm}}</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong></i> is a registered Consultancy Firm with the Construction Development Board. The Firm is registered in the following categories for the purpose of Consultancy Services only:
</p>
</p>
{{--<p style="font-size: 10pt;">--}}
{{--@foreach($services as $service)--}}
		{{--{{$service->Service}}--}}
{{--@endforeach--}}
{{--</p>--}}

<p style="font-size: 10pt; display: inline-block;">
		<table class="data-large table-smallfont" style="width: 45%; margin-top: -5px;">
			<tbody>
				<tr><td colspan="2"><strong>Civil Engineering Services</strong></td></tr>
				@foreach($consultantServices['f39b9245-bc15-11e4-81ac-080027dcfac6'] as $consultantService)
					<tr>
						<td>
							{{$consultantService->Name}} ({{$consultantService->Code}})
						</td>
						<td style="width: 10px;">
							@if(in_array($consultantService->Code,$services))
								<center><strong><img src="{{asset('assets/global/img/tick.png')}}" width="10"/></strong></center>
							@else
								<center><strong><img src="{{asset('assets/global/img/cross.png')}}" width="6"/></strong></center>
							@endif
						</td>
					</tr>
				@endforeach
			</tbody>
		</table>
		<table class="data-large table-smallfont" style="width: 84%;float: right; margin-top: -183px; margin-left: 260px;">
			<tbody>
			<tr><td colspan="2"><strong>Electrical Engineering Services</strong></td></tr>
			@foreach($consultantServices['fb2aa1a7-bc15-11e4-81ac-080027dcfac6'] as $consultantService)
				<tr>
					<td>
						{{$consultantService->Name}} ({{$consultantService->Code}})
					</td>
					<td style="width: 10px;">
						@if(in_array($consultantService->Code,$services))
							<center><strong><img src="{{asset('assets/global/img/tick.png')}}" width="10"/></strong></center>
						@else
							<center><strong><img src="{{asset('assets/global/img/cross.png')}}" width="6"/></strong></center>
						@endif
					</td>
				</tr>
			@endforeach
			</tbody>
		</table>
		<table class="data-large table-smallfont" style="width: 44.5%;margin-top: 15.5px; margin-left: -2px; position: absolute;">
			<tbody>
			<tr><td colspan="2"><strong>Architectural Services</strong></td></tr>
			@foreach($consultantServices['e6372584-bc15-11e4-81ac-080027dcfac6'] as $consultantService)
				<tr>
					<td>
						{{$consultantService->Name}} ({{$consultantService->Code}})
					</td>
					<td style="width: 10px;">
						@if(in_array($consultantService->Code,$services))
							<center><strong><img src="{{asset('assets/global/img/tick.png')}}" width="10"/></strong></center>
						@else
							<center><strong><img src="{{asset('assets/global/img/cross.png')}}" width="6"/></strong></center>
						@endif
					</td>
				</tr>
			@endforeach
			</tbody>
		</table>
		<table class="data-large table-smallfont" style="width: 85%;float: right; margin-top: 15px; margin-left: 260px;">
			<tbody>
			<tr><td colspan="2"><strong>Surveying Services</strong></td></tr>
			@foreach($consultantServices['2adfae00-be66-11e9-9ac2-0026b988eaa8'] as $consultantService)
				<tr>
					<td>
						{{$consultantService->Name}} ({{$consultantService->Code}})
					</td>
					<td style="width: 10px;">
						@if(in_array($consultantService->Code,$services))
							<center><strong><img src="{{asset('assets/global/img/tick.png')}}" width="10"/></strong></center>
						@else
							<center><strong><img src="{{asset('assets/global/img/cross.png')}}" width="6"/></strong></center>
						@endif
					</td>
				</tr>
			@endforeach
			</tbody>
		</table>
<br>
</p>


{{--<p class="heading">ELECTRICAL ENGINEERING SERVICES</p>--}}
{{--<table class="data-large" style="width: 50%;">--}}
	{{--<tbody>--}}
		{{--@foreach($electricalEngineeringServices as $electricalEngineeringService)--}}
		{{--<tr>--}}
			{{--<td>--}}
				{{--{{$electricalEngineeringService->Code}}--}}
			{{--</td>--}}
			{{--<td>--}}
				{{--{{$electricalEngineeringService->Name}}--}}
			{{--</td>--}}
			{{--<td>--}}
				{{--@if((bool)$electricalEngineeringService->CmnApprovedServiceId!=NULL)--}}
				{{--<input type="checkbox" checked="checked" />--}}
				{{--@else --}}
				{{-----}}
				{{--@endif--}}
			{{--</td>--}}
		{{--</tr>--}}
		{{--@endforeach--}}
	{{--</tbody>--}}
{{--</table>--}}
{{--<p class="heading">ARCHITECTURAL SERVICES</p>--}}
{{--<table class="data-large">--}}
	{{--<tbody>--}}
		{{--@foreach($architecturalServices as $architecturalService)--}}
		{{--<tr>--}}
			{{--<td>--}}
				{{--{{$architecturalService->Code}}--}}
			{{--</td>--}}
			{{--<td>--}}
				{{--{{$architecturalService->Name}}--}}
			{{--</td>--}}
			{{--<td>--}}
				{{--@if((bool)$architecturalService->CmnApprovedServiceId!=NULL)--}}
				{{--<input type="checkbox" checked="checked" />--}}
				{{--@else --}}
				{{-----}}
				{{--@endif--}}
			{{--</td>--}}
		{{--</tr>--}}
		{{--@endforeach--}}
	{{--</tbody>--}}
{{--</table>--}}

@stop