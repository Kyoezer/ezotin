@extends('websitemaster')
@section('main-content')
@foreach($architectInformations as $architectInformation)
<h4 class="text-primary"><strong>{{'Architect '.$architectInformation->Salutation.' '.$architectInformation->Name.' Details'}}</strong></h4>
<div class="row">
	<div class="col-md-6">
		<h5><b>Registration Details</b></h5>
		<table class="table table-bordered table-condensed">
			<tbody>
				<tr>
					<td class="small-medium"><strong>AR No. </strong></td>
					<td>{{$architectInformation->ARNo}}</td>
				</tr>
				<tr>
					<td class="small-medium"><strong>Type of Architect </strong></td>
					<td>{{$architectInformation->ArchitectType}}</td>
				</tr>
				<tr>
					<td class="small-medium"><strong>Name</strong></td>
					<td>{{$architectInformation->Salutation.' '.$architectInformation->Name}}</td>
				</tr>
				<tr>
					<td class="small-medium"><strong>CID No./Work Permit No.</strong></td>
					<td>{{$architectInformation->CIDNo}}</td>
				</tr>
				<tr>
					<td class="small-medium"><strong>Country</strong></td>
					<td>{{$architectInformation->Country}}</td>
				</tr>
				<tr>
					<td class="small-medium"><strong>Dzongkhag</strong></td>
					<td>{{$architectInformation->Dzongkhag}}</td>
				</tr>
				<tr>
					<td class="small-medium"><strong>Gewog</strong></td>
					<td>{{$architectInformation->Gewog}}</td>
				</tr>
				<tr>
					<td class="small-medium"><strong>Village</strong></td>
					<td>{{$architectInformation->Village}}</td>
				</tr>
				<tr>
					<td class="small-medium"><strong>Email</strong></td>
					<td>{{$architectInformation->Email}}</td>
				</tr>
				<tr>
					<td class="small-medium"><strong>Employer Name</strong></td>
					<td>
						@if(!empty($architectInformation->EmployerName))
						{{'M/s.'.$architectInformation->EmployerName}}
						@endif
					</td>
				</tr>
				<tr>
					<td class="small-medium"><strong>Employer Address</strong></td>
					<td>{{$architectInformation->EmployerAddress}}</td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="col-md-6">
		<h5><b>Professional Qualification</b></h5>
		<table class="table table-bordered table-condensed">
			<tbody>
				<tr>
					<td class="small-medium"><strong>Qualification</strong></td>
					<td>{{$architectInformation->Qualification}}</td>
				</tr>
				<tr>
					<td class="small-medium"><strong>Year of Graduation</strong></td>
					<td>{{$architectInformation->GraduationYear}}</td>
				</tr>
				<tr>
					<td class="small-medium"><strong>Name of University</strong></td>
					<td>{{$architectInformation->NameOfUniversity}}</td>
				</tr>
				<tr>
					<td class="small-medium"><strong>University Country</strong></td>
					<td>{{$architectInformation->UniversityCountry}}</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>	
@endforeach	

@stop