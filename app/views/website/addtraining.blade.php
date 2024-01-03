@extends('master')
@section('pagescripts')
	{{ HTML::script('ckeditor/ckeditor.js') }}
	<script>
        CKEDITOR.replace( 'editor' ,{
            toolbar: [
                { name: 'document', items: [ 'Print' ] },
//                { name: 'clipboard', items: [ 'Undo', 'Redo' ] },
                { name: 'clipboard', groups: [ 'Clipboard', 'Undo','Redo' ] },
                { name: 'styles', items: [ 'Format', 'Font', 'FontSize' ] },
                { name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline','Subscript','Superscript', 'Strike', 'RemoveFormat', 'CopyFormatting' ] },
                { name: 'colors', items: [ 'TextColor', 'BGColor' ] },
                { name: 'align', items: [ 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ] },
                { name: 'links', items: [ 'Link', 'Unlink' ] },
                { name: 'paragraph', items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote' ] },
                { name: 'insert', items: [ 'Image', 'Table' ] },
                { name: 'tools', items: [ 'Maximize' ] },
                { name: 'editing', items: [ 'Scayt' ] }
            ],
        });
        $("#TrainingTypeId").on('change',function(){
           	var ref=$('option:selected',this).data('reference');
           	if(ref == 8){
                $("#ExpiryDate").find('input').removeAttr('disabled');
                $("#ExpiryDate").removeClass('hide');
			}else{
                $("#ExpiryDate").find('input').val('').attr('disabled','disabled').removeClass('required');
           	    $("#ExpiryDate").addClass('hide');
			}
		});
//        $("#ExpiryDate")
	</script>
@stop
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-gift"></i>
					<span class="caption-subject font-green-seagreen">Add Training</span>
				</div>
			</div>
			<div class="portlet-body form">
				{{ Form::open(array('action'=>'WebTrainingController@addTrainingDetails','class'=>'form-horizontal')) }}
				<div class="form-body">
					@foreach($trainingDetails as $trainingDetail)
					<input type="hidden" value="{{$trainingDetail->Id}}" name="Id" />
					<div class="form-group">
						<label class="control-label col-md-3">Training Title</label>
						<div class="col-md-7">
							<input type="text" name="TrainingTitle" class="form-control required input-sm" placeholder="Training Title" value="@if(!empty($trainingDetail->TrainingTitle)){{$trainingDetail->TrainingTitle}}@else{{Input::old('TrainingTitle')}}@endif"/>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3">Training Description</label>
						<div class="col-md-9">
							<textarea name="TrainingDescription" id="editor" class="form-control summernote required input-sm" rows="3">
								@if(!empty($trainingDetail->TrainingDescription))
									{{$trainingDetail->TrainingDescription}}
								@else
									{{Input::old('TrainingDescription')}}
								@endif
							</textarea>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3">Training Type</label>
						<div class="col-md-7">
							<select class="form-control required input-sm" id="TrainingTypeId" name="TrainingTypeId" onchange="checkTrainingType()">
								<option value="">---SELECT ONE---</option>
								@foreach($trainingType as $trainingTypeDetails)
									<option data-reference="{{$trainingTypeDetails->ReferenceNo}}" value="{{$trainingTypeDetails->Id}}" @if($trainingDetail->TrainingTypeId==$trainingTypeDetails->Id || Input::old('TrainingTypeId')==$trainingTypeDetails->Id)selected="selected"@endif>{{$trainingTypeDetails->TrainingType}}</option>
								@endforeach
							</select>
						</div>	
					</div>
					<script>
						function checkTrainingType(trainingType)
						{
							var trainingType = $("#TrainingTypeId option:selected").text();
							$("#addMoreButton").hide();
							if(trainingType=="Induction Course" || trainingType=="Official Training" || trainingType=="Refresher Course")
							{
								$("#addMoreButton").show();
							}
						}
					</script>
					<div class="form-group">
						<label class="control-label col-md-3">Training Start Date</label>
						<div class="col-md-7">
							<div class="input-icon right">
								<i class="fa fa-calendar"></i>
								<input type="text" class="form-control date datepicker required input-sm" name="StartDate" placeholder="Start Date" value="@if(!empty($trainingDetail->StartDate)){{convertDateToClientFormat($trainingDetail->StartDate)}}@else{{Input::old('StartDate')}}@endif">
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3">Training End Date</label>
						<div class="col-md-7">
							<div class="input-icon right">
								<i class="fa fa-calendar"></i>
								<input type="text" class="form-control date datepicker required input-sm" name="EndDate" placeholder="End Date" value="@if(!empty($trainingDetail->EndDate)){{convertDateToClientFormat($trainingDetail->EndDate)}}@else{{Input::old('EndDate')}}@endif">
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3">Last Date for Registration</label>
						<div class="col-md-7">
							<div class="input-icon right">
								<i class="fa fa-calendar"></i>
								<input type="text" class="form-control date datepicker required input-sm" name="LastDateForRegistration" placeholder="Last Date For Registration" value="@if(!empty($trainingDetail->LastDateForRegistration)){{convertDateToClientFormat($trainingDetail->LastDateForRegistration)}}@else{{Input::old('LastDateForRegistration')}}@endif">
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3">Training Time</label>
						<div class="col-md-7">
							<div class="input-icon right">
								<i class="fa fa-calendar"></i>
								<input type="text" class="form-control required input-sm" name="TrainingTime" placeholder="Training Time" value="@if(!empty($trainingDetail->TrainingTime)){{$trainingDetail->TrainingTime}}@else{{Input::old('TrainingTime')}}@endif">
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="venue">
							<label class="control-label col-md-3">Training Venue</label>
							<div class="col-md-7">
								{{-- <input type="text" name="TrainingVenue" id ='venue_1' class="form-control required input-sm" placeholder="Training Venue" value="@if(!empty($trainingDetail->TrainingVenue)){{$trainingDetail->TrainingVenue}}@else{{Input::old('TrainingVenue')}}@endif"/> --}}
								<input type="text" id ='venue_1' class="form-control required input-sm" placeholder="Training Venue"/>
								<input type="hidden" name="TrainingVenue" id ='mainVenue' >
								<div id='moreVenue'><br></div>
								<div class="" style="padding-top: 12px;">
									<button type="button" class="btn btn-primary" id="addMoreButton" style="display:none" onClick="addMoreVenue()">Add More</button>
								</div>
							</div>
						</div>
						<div id='moreVenue'></div>
					</div>
					<script>
						var countVenue = 1;
						function addMoreVenue()
						{	
							countVenue = countVenue+1;
							var venue = '<div id="div_venue_'+countVenue+'"><div class="col-lg-11 margin-bottom-10 pad" style="padding-left: 0px;"><input type="text" id ="venue_'+countVenue+'" class="form-control required input-sm" placeholder="Training Venue"/></div><div class="col-lg-1"><a class="btn" onclick="deleteVenue('+countVenue+')"><i class="fa fa-times text-danger"></i></a></div></div>';
							$("#moreVenue").append(venue);

						}
						function deleteVenue(venueId)
						{
							$("#div_venue_"+venueId).remove();

						}


					</script>
					<div class="form-group">
						<label class="control-label col-md-3">Contact Person</label>
						<div class="col-md-7">
							<input type="text" name="ContactPerson" class="form-control required input-sm" placeholder="Contact Person" value="@if(!empty($trainingDetail->ContactPerson)){{$trainingDetail->ContactPerson}}@else{{Input::old('ContactPerson')}}@endif"/>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3">Contact No.</label>
						<div class="col-md-7">
							<input type="text" name="Hotline" class="form-control required input-sm" placeholder="Contact No." value="@if(!empty($trainingDetail->Hotline)){{$trainingDetail->Hotline}}@else{{Input::old('Hotline')}}@endif"/>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3">Max. Participants</label>
						<div class="col-md-7">
							<input type="number" name="MaxParticipants" class="form-control number required input-sm" placeholder="Max Participants" value="@if(!empty($trainingDetail->MaxParticipants)){{$trainingDetail->MaxParticipants}}@else{{Input::old('MaxParticipants')}}@endif"/>
						</div>
					</div>
						<div class="form-group @if($trainingDetail->TrainingTypeId != CONST_REFRESHERCOURSE){{'hide'}}@endif" id="ExpiryDate">
							<label class="control-label col-md-3">Contractor's Expiry Date Limit</label>
							<div class="col-md-7">
								<div class="input-icon right">
									<i class="fa fa-calendar"></i>
									<input type="text" id="ExpiryDate" name="ContractorsExpiryDate" class="date-picker form-control input-sm" value="@if(!empty($trainingDetail->ContractorsExpiryDate)){{$trainingDetail->ContractorsExpiryDate}}@else{{Input::old('ContractorsExpiryDate')}}@endif"/>
								</div>
							</div>
						</div>
                    <div class="form-group">
                        <label class="control-label col-md-3">Show in Marquee

                        </label>
                        <div class="col-md-4">
                            <div class="radio-list">
                                <label class="radio-inline">
                                    <input type="radio" name="ShowInMarquee" value="1" @if((empty($trainingDetail->ShowInMarquee) && $trainingDetail->ShowInMarquee != 0) || $trainingDetail->ShowInMarquee == 1)checked="checked"@endif/>
                                    Yes
                                </label>

                                <label class="radio-inline">
                                    <input type="radio" name="ShowInMarquee" value="0" @if($trainingDetail->ShowInMarquee == 0)checked="checked"@endif/>
                                    No
                                </label>
                            </div>
                        </div>
                    </div>
					@endforeach
				</div>
				<div class="form-actions">
					<div class="btn-set">
						<button type="submit" onClick="checkTrainingVenue()" class="btn green">{{empty($trainingDetails[0]->Id)?'Save':'Update'}}</button>
						<a href="{{URL::to('web/addtrainingform')}}" class="btn red">Cancel</a>
					</div>
				</div>
				{{ Form::close() }}
			</div>
			<script>
				function checkTrainingVenue()
				{
					var i = 1;
					var venue="venue";
					for(;i<=countVenue;i++)
					{
						if($("#venue_"+i).val()!="undefined")
						{
							venue = venue + "~" + $("#venue_"+i).val();
						}
					}
					$("#mainVenue").val(venue);

				}
			</script>
		</div>
	</div>	
</div>
@stop