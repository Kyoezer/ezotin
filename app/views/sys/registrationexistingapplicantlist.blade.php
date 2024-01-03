@extends('master')
@section('content')
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption font-green-seagreen">
			<i class="fa fa-cogs"></i>
			<span class="caption-subject">List of Applicants</span>
		</div>
	</div>
	<div class="portlet-body">
		<div class="row">
			<div class="table-responsive col-md-12 col-sm-12 col-xs-12">
				<table id="tablefilters_1" class="table table-condensed table-striped table-bordered table-hover table-responsive">
					<thead>
						<tr>
							<th>Registration Type</th>
							<th>CDB No</th>
							<th>Firm/Name</th>
							<th>User Full Name</th>
							<th>Username/Email</th>
							<th>Contact No</th>
							<th>Applied On</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						@forelse($applicantList as $applicant)
						<tr>
							<td>
								{{$applicant->RegistrationType}}
							</td>
							<td>{{$applicant->CDBNo}}</td>
							<td>{{$applicant->Name}}</td>
							<td>{{$applicant->FullName}}</td>
							<td>{{$applicant->username}}</td>
							<td>{{$applicant->ContactNo}}</td>
							<td>{{convertDateTimeToClientFormat($applicant->AppliedOn)}}</td>
							<td>
								<a href="{{URL::to('sys/approveregistrationapplication/'.$applicant->Id)}}" class="editaction btn default btn-xs green-seagreen"><i class="fa fa-edit"></i>Approve</a>|
								<a href="{{URL::to('sys/deleteregistrationapplication/'.$applicant->Id)}}" class="deleteaction btn default btn-xs red"><i class="fa fa-times"></i>Delete</a>
							</td>
						</tr>
						@empty
							<tr>
								<td colspan="8" class="font-red text-center">No applicants to display!</td>
							</tr>
						@endforelse
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
@stop