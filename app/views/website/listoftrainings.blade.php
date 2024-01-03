@extends('websitemaster')
@section('main-content')
<div class="row">
	<div class="col-md-12">
	<h4 class="text-primary"><strong>List of Trainings</strong></h4>
	<table class="table table-bordered table-striped table-condensed table-hover table-responsive">
		<thead>
			<tr class="success">
				<th>Sl#</th>
				<th>Title</th>
				<th>Description</th>
				<th>For</th>
				<th>Start Dt.</th>
				<th>End Dt.</th>
				<th>Last Dt. of Registration</th>
				<th class="text-center">Action</th>
			</tr>
		</thead>
		<tbody>
			@forelse($listOfTraining as $trainingDetails)
				<tr>
					<td>{{ $slno++ }}</td>
					<td>{{ $trainingDetails->TrainingTitle }}</td>
					<td>{{ html_entity_decode(substr($trainingDetails->TrainingDescription,0,200).'<b>.....</b>')}}</td>
					<td>{{ $trainingDetails->TrainingType }}</td>
					<td>{{ convertDateToClientFormat($trainingDetails->StartDate) }}</td>
					<td>{{ convertDateToClientFormat($trainingDetails->EndDate) }}</td>
					<td>{{ convertDateToClientFormat($trainingDetails->LastDateForRegistration) }}</td>
					<td class="text-center">
						<a href="{{ URL::to('web/viewtrainingdetails/'.$trainingDetails->TrainingId) }}" class="btn btn-info btn-xs">View</a>
					</td>
				</tr>
			@empty
				<tr>
					<td colspan="8" class="text-center font-red">No data to display</td>
				</tr>
			@endforelse
		</tbody>
	</table>
	</div>
@stop