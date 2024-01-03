@extends('websitemaster')
@section('main-content')
<h4 class="text-info"><strong>Training Details</strong></h4>
<div class="row">
	<div class="col-md-12">
		<table class="table table-bordered table-striped table-condensed table-hover table-responsive">
			@foreach($details as $trainingDetails)
				<tr>
					<td><b>Training Title:</b></td>
					<td>{{ $trainingDetails->TrainingTitle }} <iframe src="https://www.facebook.com/plugins/share_button.php?href={{Request::url()}}&layout=button_count&size=small&mobile_iframe=true&width=88&height=20&appId" width="88" height="20" style="border:none;overflow:hidden; margin-bottom:-4px;" scrolling="no" frameborder="0" allowTransparency="true"></iframe></td>
				</tr>
				<tr>
					<td><b>Training Description:</b></td>
					<td>{{html_entity_decode($trainingDetails->TrainingDescription)}}</td>
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
					<td><div id="tdVenue"></div></td>
				</tr>
				
				<tr>
					<td><b>Contact Person:</b></td>
					<td>{{ $trainingDetails->ContactPerson }}</td>
				</tr>
				<tr>
					<td><b>Hotline:</b></td>
					<td>{{ $trainingDetails->Hotline }}</td>
				</tr>
				@if(isset($trainingDetails->MaxParticipants))
					<tr>
						<td><strong>Max Participants:</strong></td>
						<td>{{(bool)$trainingDetails->MaxParticipants?$trainingDetails->MaxParticipants:'No limit'}}</td>
					</tr>
				@endif
				<tr>
					@if($trainingDetails->LastDateForRegistration >= date('Y-m-d G:i:s'))
						@if((int)$trainingDetails->ReferenceNo==1)
							<td colspan="2"><a href="#registerContractorConsultant" role="button" class="btn btn-primary btn-sm" data-toggle="modal">Register for Training</a></td>
						@elseif((int)$trainingDetails->ReferenceNo == 6)
							<td colspan="2"><a href="#registerOfficialTraining" onclick="showVenueSelectOff()" role="button" class="btn btn-primary btn-sm" data-toggle="modal">Register for Training</a></td>
						@elseif((int)$trainingDetails->ReferenceNo == 7)
							<td colspan="2"><a href="#registerInductionCourse" onclick="showVenueSelectInd()" role="button" class="btn btn-primary btn-sm" data-toggle="modal">Register for Training</a></td>
						@elseif((int)$trainingDetails->ReferenceNo == 8)
							<td colspan="2"><a href="#registerRefresherCourse" onclick="showVenueSelectRef()" role="button" class="btn btn-primary btn-sm" data-toggle="modal">Register for Training</a></td>
						@else
							<td colspan="2"><a href="#registerOther" role="button" class="btn btn-primary btn-sm" data-toggle="modal">Register for Training</a></td>
						@endif
					@else
						<td colspan="2" style="color: red;"><strong>Last Date for Registration has already Passed.</strong></td>
					@endif
				</tr>
			@endforeach
		</table>
	</div>	
</div>

<script>
		

		var venue = '<?=$trainingDetails->TrainingVenue?>';
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
		
		
		function showVenueSelectOff()
		{
			document.getElementById("selectVenueOff").innerHTML = "";
			var selectVenue = document.getElementById("selectVenueOff");

				var option = document.createElement("option");
				option.text = "Select Venue";
				option.val = "";
				selectVenue.appendChild(option);

			for(var i =1;i<venuList.length;i++)
			{
				var option = document.createElement("option");
				option.text = venuList[i];
				option.value = venuList[i];
				selectVenue.appendChild(option);
			}
		}
			
		function showVenueSelectInd()
		{
			document.getElementById("selectVenueInd").innerHTML = "";
			var selectVenue = document.getElementById("selectVenueInd");

				var option = document.createElement("option");
				option.text = "Select Venue";
				option.val = "";
				selectVenue.appendChild(option);

			for(var i =1;i<venuList.length;i++)
			{
				var option = document.createElement("option");
				option.text = venuList[i];
				option.value = venuList[i];
				selectVenue.appendChild(option);
			}
		}
			
		function showVenueSelectRef()
		{
			document.getElementById("selectVenueRef").innerHTML = "";
			var selectVenue = document.getElementById("selectVenueRef");

				var option = document.createElement("option");
				option.text = "Select Venue";
				option.val = "";
				selectVenue.appendChild(option);

			for(var i =1;i<venuList.length;i++)
			{
				var option = document.createElement("option");
				option.text = venuList[i];
				option.value = venuList[i];
				selectVenue.appendChild(option);
			}
		}
			
</script>

<!-- Modals Start -->
<div id="registerContractorConsultant" class="modal fade">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			{{ Form::open(array('action'=>'WebTrainingController@registrationForTraining', 'method'=>'POST','class'=>'form-horizontal')) }}
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Registration for Contractors/Consultants</h4>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<div class="col-md-4">
							<label class="form-label">CDB No.:</label>
						</div>
						<div class="col-md-8">
							<input type="text" name="CDBNo" class="form-control required cdb-no-check" placeholder="CDB No">
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-4">
							<label class="form-label">Name of Firm:</label>
						</div> 
						<div class="col-md-8">
							<input type="text" name="NameOfFirm" class="form-control required" placeholder="Name of Firm">
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-4">
							<label class="form-label">Class:</label>
						</div>
						<div class="col-md-8">
							<select name="CmnContractorClassificationId" class="form-control required" >
								<option>---SELECT ONE---</option>
								@foreach($contractorClassificationId as $contractorClassification)
									<option value="{{$contractorClassification->Id}}">{{$contractorClassification->Name.' ('.$contractorClassification->Code.')'}}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-4">
							<label class="form-label">CID/Work Permit No. of Participant:</label>
						</div>
						<div class="col-md-8">
							<input type="text" name="CIDNoOfParticipant" class="form-control required cid-no-check" placeholder="CID No. of Participant">
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-4">
							<label class="form-label">Name of Participant:</label>
						</div>
						<div class="col-md-8">
							<input type="text" name="FullName" class="form-control required" placeholder="Full Name of Participant">
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-4">
							<label class="form-label">Designation:</label>
						</div>
						<div class="col-md-8">
							<input type="text" name="Designation" class="form-control required" placeholder="Designation of Participant">
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-4">
							<label class="form-label">Email:</label>
						</div>
						<div class="col-md-8">
							<input type="text" name="Email" class="form-control required" placeholder="Email">
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-4">
							<label class="form-label">Contact No.:</label>
						</div>
						<div class="col-md-8">
							<input type="text" name="ContactNo" class="form-control required" placeholder="Contact No">
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<input type="hidden" name="WebTrainingDetailsId" value="{{ isset($trainingDetails->TrainingId)?$trainingDetails->TrainingId:'' }}">
					<button type="submit" class="btn btn-success">Register</button>
					<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
				</div>
			{{ Form::close() }}
		</div>
	</div>
</div>
<div id="registerOfficialTraining" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			{{ Form::open(array('action'=>'WebTrainingController@registrationForTraining', 'class'=>'form-horizontal','files'=>true)) }}
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Registration For Official Trainings</h4>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<div class="col-md-4">
							<label class="form-label">CID/Work Permit No. of Participant:</label>
						</div>
						<div class="col-md-8">
							<input type="text" name="CIDNoOfParticipant" class="form-control cid-no-check required" placeholder="CID No. of Participant">
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-4">
							<label class="form-label">Name of Participant:</label>
						</div>
						<div class="col-md-8">
							<input type="text" name="FullName" class="form-control required" placeholder="Full Name of Participant">
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-4">
							<label class="form-label">Designation:</label>
						</div>
						<div class="col-md-8">
							<input type="text" name="Designation" class="form-control required" placeholder="Designation of Participant">
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-4">
							<label class="form-label">Venue :</label>
						</div>
						<div class="col-md-8">
							<select id="selectVenueOff" name="venue" class="form-control">
								<option>Select Venue</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-4">
							<label class="form-label">Agency:</label>
						</div>
						<div class="col-md-8">
							<input type="text" name="Agency" class="form-control required" placeholder="Agency">
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-4">
							<label class="form-label">Email:</label>
						</div>
						<div class="col-md-8">
							<input type="text" name="Email" class="form-control required" placeholder="Email">
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-4">
							<label class="form-label">Contact No.:</label>
						</div>
						<div class="col-md-8">
							<input type="text" name="ContactNo" class="form-control required" placeholder="Contact No">
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-4">
							<label class="form-label">Nomination Letter:</label>
						</div>
						<div class="col-md-8">
							<input type="file" name="Attachment" class="form-control">
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<input type="hidden" name="WebTrainingDetailsId" value="{{ isset($trainingDetails->TrainingId)?$trainingDetails->TrainingId:''}}">
					<button type="submit" class="btn btn-success">Register</button>
					<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
				</div>
			{{ Form::close() }}
		</div>
	</div>
</div>

<div id="registerInductionCourse" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			{{ Form::open(array('action'=>'WebTrainingController@registrationForTraining', 'class'=>'form-horizontal','files'=>true)) }}
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Registration For Induction Course</h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<div class="col-md-4">
						<label class="form-label">CID No. of Participant: <span class="font-red">*</span></label>
					</div>
					<div class="col-md-8">
						<input type="text" name="CIDNoOfParticipant" class="form-control cid-no-check required" placeholder="CID No. of Participant">
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-4">
						<label class="form-label">Name of Participant: *</label>
					</div>
					<div class="col-md-8">
						<input type="text" name="FullName" class="form-control required" placeholder="Full Name of Participant">
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-4">
						<label class="form-label">Qualification:</label>
					</div>
					<div class="col-md-8">
						<input type="text" name="Designation" class="form-control" placeholder="Qualification of Participant">
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-4">
						<label class="form-label">Email:</label>
					</div>
					<div class="col-md-8">
						<input type="text" name="Email" class="form-control" placeholder="Email">
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-4">
						<label class="form-label">Contact No.: *</label>
					</div>
					<div class="col-md-8">
						<input type="text" name="ContactNo" class="form-control required" placeholder="Contact No">
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-4">
						<label class="form-label">Venue :</label>
					</div>
					<div class="col-md-8">
						<select id="selectVenueInd" name="venue" class="form-control">
							<option>Select Venue</option>
						</select>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<input type="hidden" name="WebTrainingDetailsId" value="{{ isset($trainingDetails->TrainingId)?$trainingDetails->TrainingId:''}}">
				<button type="submit" class="btn btn-success">Register</button>
				<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
			</div>
			{{ Form::close() }}
		</div>
	</div>
</div>
<div id="registerOther" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			{{ Form::open(array('action'=>'WebTrainingController@registrationForTraining', 'class'=>'form-horizontal','files'=>true)) }}
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Registration For Induction Course</h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<div class="col-md-4">
						<label class="form-label">CID/Work Permit No. of Participant:</label>
					</div>
					<div class="col-md-8">
						<input type="text" name="CIDNoOfParticipant" class="form-control cid-no-check required" placeholder="CID No. of Participant">
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-4">
						<label class="form-label">Name of Participant:</label>
					</div>
					<div class="col-md-8">
						<input type="text" name="FullName" class="form-control required" placeholder="Full Name of Participant">
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-4">
						<label class="form-label">Email:</label>
					</div>
					<div class="col-md-8">
						<input type="text" name="Email" class="form-control" placeholder="Email">
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-4">
						<label class="form-label">Contact No.:</label>
					</div>
					<div class="col-md-8">
						<input type="text" name="ContactNo" class="form-control required" placeholder="Contact No">
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<input type="hidden" name="WebTrainingDetailsId" value="{{ isset($trainingDetails->TrainingId)?$trainingDetails->TrainingId:''}}">
				<button type="submit" class="btn btn-success">Register</button>
				<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
			</div>
			{{ Form::close() }}
		</div>
	</div>
</div>
<div id="registerRefresherCourse" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			{{ Form::open(array('action'=>'WebTrainingController@registrationForTraining', 'class'=>'form-horizontal','files'=>true)) }}
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Registration For Refresher Course</h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<div class="col-md-4">
						<label class="form-label">CID/Work Permit No. of Participant:</label>
					</div>
					<div class="col-md-8">
						<input type="text" name="CIDNoOfParticipant" class="form-control cid-no-check required" placeholder="CID No. of Participant">
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-4">
						<label class="form-label">CDB No. of Firm:</label>
					</div>
					<div class="col-md-8">
						<input type="text" id="refereshers-cdb" name="CDBNo" class="form-control required" placeholder="CDB No.">
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-4">
						<label class="form-label">Contractor Details:</label>
					</div>
					<div class="col-md-8">
						<input type="text" id="contractor-details" class="form-control" disabled="disabled">
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-4">
						<label class="form-label">Name of Participant:</label>
					</div>
					<div class="col-md-8">
						<input type="text" name="FullName" class="form-control required" placeholder="Full Name of Participant">
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-4">
						<label class="form-label">Designation:</label>
					</div>
					<div class="col-md-8">
						<input type="text" name="Designation" class="form-control" placeholder="Designation of Participant">
					</div>
				</div>
				<div class="form-group">
						<div class="col-md-4">
							<label class="form-label">Venue :</label>
						</div>
						<div class="col-md-8">
							<select id="selectVenueRef" name="venue" class="form-control">
								<option>Select Venue</option>
							</select>
						</div>
					</div>
				<div class="form-group">
					<div class="col-md-4">
						<label class="form-label">Qualification:</label>
					</div>
					<div class="col-md-8">
						<input type="text" name="Qualification" class="form-control" placeholder="Qualification of Participant">
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-4">
						<label class="form-label">Email:</label>
					</div>
					<div class="col-md-8">
						<input type="text" name="Email" class="form-control" placeholder="Email">
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-4">
						<label class="form-label">Contact No.:</label>
					</div>
					<div class="col-md-8">
						<input type="text" name="ContactNo" class="form-control required" placeholder="Contact No">
					</div>
				</div>
				@if((bool)$trainingDetails->ContractorsExpiryDate)
				<strong style="color:red;">Only for Contractors with Expiry date on or before: {{convertDateToClientFormat($trainingDetails->ContractorsExpiryDate)}}</strong>
				@endif
			</div>
			<div class="modal-footer">
				<input type="hidden" name="WebTrainingDetailsId" value="{{ isset($trainingDetails->TrainingId)?$trainingDetails->TrainingId:''}}">
				<button type="submit" class="btn btn-success">Register</button>
				<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
			</div>
			{{ Form::close() }}
		</div>
	</div>
</div>
<!-- Load Facebook SDK for JavaScript -->

{{--<i class="fa fa-facebook-square"></i>--}}
{{--<a href="#" onclick="window.open('https://www.facebook.com/sharer/sharer.php?u='+encodeURIComponent(location.href),'facebook-share-dialog','width=626,height=436');return false;">Share on Facebook</a>--}}
@stop