@extends('websitemaster')
@section('main-content')

@foreach($engineerInformations as $engineerInformation)
<h4 class="text-primary"><strong>{{$engineerInformation->Trade.' Engineer '.$engineerInformation->Salutation.' '.$engineerInformation->Name.' Details'}}</strong></h4>
<div class="row">
	<div class="col-md-6 table-responsive">
	<h5><strong>Registration Details</strong></h5>
		<table class="table table-bordered table-condensed">
			<tbody>
				<tr>
					<td class="small-medium"><strong>CDB No. </strong></td>
					<td>{{$engineerInformation->CDBNo}}</td>
				</tr>
				<tr>
					<td class="small-medium"><strong>Type of Engineer </strong></td>
					<td>{{$engineerInformation->EngineerType}}</td>
				</tr>
				<tr>
					<td class="small-medium"><strong>Trade </strong></td>
					<td>{{$engineerInformation->Trade}}</td>
				</tr>
				<tr>
					<td class="small-medium"><strong>Name</strong></td>
					<td>{{$engineerInformation->Salutation.' '.$engineerInformation->Name}}</td>
				</tr>
				<tr>
					<td class="small-medium"><strong>CID No./Work Permit No.</strong></td>
					<td>{{$engineerInformation->CIDNo}}</td>
				</tr>
				<tr>
					<td class="small-medium"><strong>Country</strong></td>
					<td>{{$engineerInformation->Country}}</td>
				</tr>
				<tr>
					<td class="small-medium"><strong>Dzongkhag</strong></td>
					<td>{{$engineerInformation->Dzongkhag}}</td>
				</tr>
				<tr>
					<td class="small-medium"><strong>Gewog</strong></td>
					<td>{{$engineerInformation->Gewog}}</td>
				</tr>
				<tr>
					<td class="small-medium"><strong>Village</strong></td>
					<td>{{$engineerInformation->Village}}</td>
				</tr>
				<tr>
					<td class="small-medium"><strong>Email</strong></td>
					<td>{{$engineerInformation->Email}}</td>
				</tr>
				<tr>
					<td class="small-medium"><strong>Employer Name</strong></td>
					<td>
						@if(!empty($engineerInformation->EmployerName))
							{{'M/s.'.$engineerInformation->EmployerName}}
						@endif
					</td>
				</tr>
				<tr>
					<td class="small-medium"><strong>Employer Address</strong></td>
					<td>{{$engineerInformation->EmployerAddress}}</td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="col-md-6 table-responsive">
		<h5><strong>Professional Qualification</strong></h5>
		<table class="table table-bordered table-condensed">
			<tbody>
				<tr>
					<td class="small-medium"><strong>Qualification</strong></td>
					<td>{{$engineerInformation->Qualification}}</td>
				</tr>
				<tr>
					<td class="small-medium"><strong>Year of Graduation</strong></td>
					<td>{{$engineerInformation->GraduationYear}}</td>
				</tr>
				<tr>
					<td class="small-medium"><strong>Name of University</strong></td>
					<td>{{$engineerInformation->NameOfUniversity}}</td>
				</tr>
				<tr>
					<td class="small-medium"><strong>University Country</strong></td>
					<td>{{$engineerInformation->UniversityCountry}}</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>	
@endforeach	
@stop