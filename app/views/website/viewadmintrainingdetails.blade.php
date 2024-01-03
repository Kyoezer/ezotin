@extends('master')
@section('content')

<h4 class="head-title">Training Details</h4>

<table class="table-bordered table-striped table-condensed table-hover table-responsive">
	@foreach($listOfTraining as $trainingDetails)
		<tr>
			<td><b>Training Title:</b></td>
			<td>{{ $trainingDetails->TrainingTitle }}</td>
		</tr>

		<tr>
			<td><b>Training Type:</b></td>
			<td>{{ $trainingDetails->TrainingType }}</td>
		</tr>

		<tr>
			<td><b>Start Date:</b></td>
			<td>{{ convertDateToClientFormat($trainingDetails->StartDate) }}</td>
		</tr>

		<tr>
			<td><b>End Date:</b></td>
			<td>{{ convertDateToClientFormat($trainingDetails->EndDate) }}</td>
		</tr>

		<tr>
			<td><b>Last Date of Registration:</b></td>
			<td>{{ convertDateToClientFormat($trainingDetails->LastDateForRegistration) }}</td>
		</tr>

		<tr>
			<td><b>Time:</b></td>
			<td>{{ $trainingDetails->TrainingTime }}</td>
		</tr>

		<tr>
			<td><b>Venue:</b></td>
			<td>{{ $trainingDetails->TrainingVenue }}</td>
		</tr>

		<tr>
			<td><b>Contact Person:</b></td>
			<td>{{ $trainingDetails->ContactPerson }}</td>
		</tr>

		<tr>
			<td><b>Hotline:</b></td>
			<td>{{ $trainingDetails->Hotline }}</td>
		</tr>

		<tr>
			<td><b>Action:</b></td>
			<td>
				<a href="{{ URL::to('web/edittrainingdetails/'.$trainingDetails->TrainingId) }}" class="btn btn-primary btn-xs">Edit <i class="fa fa-pencil-square-o"></i></a>
				<a href="{{ URL::to('web/registeredfortraining/'.$trainingDetails->TrainingId) }}" class="btn btn-success btn-xs">View Registered</a>
			</td>
		</tr>
	@endforeach
</table>

<div class="clear2"></div>

@stop