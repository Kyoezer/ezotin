@extends('master')
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-gift"></i>
					<span class="caption-subject font-green-seagreen">View/Edit Training</span>
				</div>
			</div>
			<div class="portlet-body">
				<div class="table-responsive">
					<table class="table table-striped table-condensed table-hover table-bordered" data-table="webtrainingdetails">
						<thead>
							<tr class="success">
								<th>Sl#</th>
								<th>Title</th>
								<th width="30%">Description</th>
								<th>Training For</th>
								<th width="10%">Start Dt.</th>
								<th width="10%">End Dt.</th>
								<th>Last Dt. of Registration</th>
								<th>Max Participants</th>
								<th>Show in Marquee</th>
								<th>Action</th>
							</tr>
						</thead>

						<tbody>
							@forelse($listOfTraining as $trainingDetails)
								<tr>
									<td>{{ $slno++ }}</td>
									<td>{{ $trainingDetails->TrainingTitle }}</td>
									<td>{{ substr(strip_tags($trainingDetails->TrainingDescription),0,150)}}</td>
									<td>{{ $trainingDetails->TrainingType }}</td>
									<td>{{ convertDateToClientFormat($trainingDetails->StartDate) }}</td>
									<td>{{ convertDateToClientFormat($trainingDetails->EndDate) }}</td>
									<td>{{ convertDateToClientFormat($trainingDetails->LastDateForRegistration) }}</td>
									<td>{{(bool)$trainingDetails->MaxParticipants?$trainingDetails->MaxParticipants:'--'}}</td>
									<td><?php echo ($trainingDetails->ShowInMarquee == 1)?"Yes":"No"; ?></td>
									<td>
										<a href="{{ URL::to('web/registeredfortraining/'.$trainingDetails->TrainingId.'?ref='.$trainingDetails->ReferenceNo) }}" class="btn green-seagreen btn-xs">Registered Trainees <i class="fa fa-eye"></i></a>
										<a href="{{ URL::to('web/addtrainingform/'.$trainingDetails->TrainingId) }}" class="btn blue-hoki btn-xs">Edit <i class="fa fa-pencil-square-o"></i></a>
										<a data-id="{{$trainingDetails->TrainingId}}" href="#" class="delete-websiteentry btn green btn-xs">Delete&nbsp;<i class="fa fa-times"></i></a>
									</td>
								</tr>
							@empty
								<tr>
									<td colspan="9" class="text-center" style="color:#FE0000">No data to display</td>
								</tr>
							@endforelse
						</tbody>
					</table>
				</div>	
			</div>
		</div>
	</div>
</div> 	
@stop