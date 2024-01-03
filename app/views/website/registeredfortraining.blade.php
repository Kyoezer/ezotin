@extends('master')
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-gift"></i>
					<span class="caption-subject font-green-seagreen">Registered Traniees</span>
				</div>
			</div>
			<div class="portlet-body form">
				<div class="form-body table-responsive">
					<table class="table table-bordered table-condensed dont-flip">
						<tbody>
							@foreach($tranings as $training)
							<?php $traningId=$training->Id; ?>
							<tr>
								<td class="bold" width="20%">Training Title</td>
								<td>{{$training->TrainingTitle}}</td>
							</tr>
							<tr>
								<td class="bold" width="20%">Training Description</td>
								<td>{{html_entity_decode($training->TrainingDescription)}}</td>
							</tr>
							<tr>
								<td class="bold">Venue</td>
								<td><div id="tdVenue"></div></td>
							</tr>
							<tr>
								<td class="bold">Start Date</td>
								<td>{{$training->StartDate}}</td>
							</tr>
							<tr>
								<td class="bold">End Date</td>
								<td>{{$training->EndDate}}</td>
							</tr>
							@endforeach
						</tbody>
						<script>
							var venue = '<?=$training->TrainingVenue?>';
							var venuList = venue.split("~");
							for(var i =1;i<venuList.length;i++)
							{
								if(i==1)
								{
									venue = venuList[i];
								}
								else
								{
									venue = venue+", "+venuList[i];
								}
							}
							document.getElementById("tdVenue").innerHTML =venue;
						</script>
					</table>
					@if((int)Input::get('ref')==1 || (int)Input::get('ref') == 8)
					<table class="table table-condensed table-striped table-bordered dont-flip">
						<thead>
							<tr class="success">
								<th>Sl#</th>
								<th>CDB No.</th>
								<th>Name of Firm</th>
								<th>Class</th>
								<th>Name of Participant</th>
								<th>CID/Work Permit No. of Participant</th>
								<th>Designation</th>
								<th>Qualification</th>
								<th>Email</th>
								<th>Contact No.</th>
								<th>Registered On</th>
							</tr>	
						</thead>
						<tbody>
							<?php $sl=1; ?>
							@forelse($listOfRegisteredTraniees as $listOfRegisteredTraniee)
							<tr>
								<td>{{$sl}}</td>
								<td>{{$listOfRegisteredTraniee->CDBNo}}</td>
								<td>{{$listOfRegisteredTraniee->NameOfFirm}}</td>
								<td>{{$listOfRegisteredTraniee->ClassCode}}</td>
								<td>{{$listOfRegisteredTraniee->FullName}}</td>
								<td>{{$listOfRegisteredTraniee->CIDNoOfParticipant}}</td>
								<td>{{$listOfRegisteredTraniee->Designation}}</td>
								<td>{{$listOfRegisteredTraniee->Qualification}}</td>
								<td>{{$listOfRegisteredTraniee->Email}}</td>
								<td>{{$listOfRegisteredTraniee->ContactNo}}</td>
								<td>{{convertDateTimeToClientFormat($listOfRegisteredTraniee->CreatedOn)}}</td>
							</tr>
							<?php $sl++; ?>
							@empty
							<tr>
								<td colspan="11" class="text-center font-red">No registered traniees</td>
							</tr>
							@endforelse
						</tbody>
					</table>
					@else
					<table class="table table-condensed table-striped table-bordered dont-flip">
						<thead>
							<tr class="success">
								<th>Sl#</th>
								<th>Name of Participant</th>
								<th>CID/Work Permit No. of Participant</th>
								<th>Agency</th>
								<th>Designation</th>
								<th>Email</th>
								<th>Contact No.</th>
								<th>Registered On</th>
								<th>Venue</th>
								<th>Attachments</th>
							</tr>	
						</thead>
						<tbody>
							<?php $sl=1; ?>
							@forelse($listOfRegisteredTraniees as $listOfRegisteredTraniee)
							<tr>
								<td>{{$sl}}</td>
								<td>{{$listOfRegisteredTraniee->FullName}}</td>
								<td>{{$listOfRegisteredTraniee->CIDNoOfParticipant}}</td>
								<td>{{$listOfRegisteredTraniee->Agency}}</td>
								<td>{{$listOfRegisteredTraniee->Designation}}</td>
								<td>{{$listOfRegisteredTraniee->Email}}</td>
								<td>{{$listOfRegisteredTraniee->ContactNo}}</td>
								<td>{{convertDateTimeToClientFormat($listOfRegisteredTraniee->CreatedOn)}}</td>
								<td>{{$listOfRegisteredTraniee->venue}}</td>
								<td>
									@if((bool)$listOfRegisteredTraniee->FilePath)
										<a target="_blank" href="{{asset($listOfRegisteredTraniee->FilePath)}}">Attachment</a>
									@else
										--
									@endif
								</td>
							</tr>
							<?php $sl++; ?>
							@empty
							<tr>
								<td colspan="11" class="text-center font-red">No registered traniees</td>
							</tr>
							@endforelse
						</tbody>
					</table>
					@endif
				</div>
				<div class="form-controls">
					<div class="btn-set">
						<a href="{{URL::to('web/registeredfortraining/'.$traningId.'?ref='.Input::get('ref').'&export=print')}}" class="btn blue btn-sm" target="_blank"><i class="fa fa-print"></i> Print Registered Traniees</a>
					</div>
				</div>	
			</div>
		</div>	
	</div>
</div>
@stop
