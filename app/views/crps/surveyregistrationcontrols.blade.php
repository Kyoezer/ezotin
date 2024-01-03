@if(isset($isRejectedApp) && (int)$isRejectedApp==1)
<input type="hidden" value="1" name="ApplicationRejectedReapply">
@endif
<!-- BEGIN FORM-->
<div class="form-body">
	@foreach($surveyRegistrations as $surveyRegistration)
	<div class="row">
		<div class="col-md-9">
			<div class="form-group">
				<input type="hidden" value="{{$surveyRegistration->Id}}" name="Id" />
				<input type="hidden" value="{{$newGeneralInfoSave}}" name="NewGeneralInfoSave">
				@if((int)$isServiceBySurvey==1)
				<input type="hidden" value="{{$surveyFinalTableId}}" name="CrpSurveyId" />
				@endif
				<label class="bold">Application No.</label>
				<p class="form-control-static">
					@if(!empty($surveyRegistration->ReferenceNo) || !empty($applicationReferenceNo))
						@if(!empty($surveyRegistration->ReferenceNo))
							{{$surveyRegistration->ReferenceNo}}
						@else
							{{$applicationReferenceNo}}
						@endif
					@else
						{{Input::old('ReferenceNo')}}
					@endif
				</p>
				<input type="hidden" name="ReferenceNo" value="@if(!empty($surveyRegistration->ReferenceNo) || !empty($applicationReferenceNo))@if(!empty($surveyRegistration->ReferenceNo)){{$surveyRegistration->ReferenceNo}}@else{{$applicationReferenceNo}}@endif@else{{Input::old('ReferenceNo')}}@endif" />
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				<label class="bold">Application Date</label>
				<p class="form-control-static">@if(!empty($surveyRegistration->ApplicationDate)){{convertDateToClientFormat($surveyRegistration->ApplicationDate)}}@else{{convertDateToClientFormat(date('Y-m-d G:i:s'))}}@endif</p>
				<input type="hidden" name="ApplicationDate" value="@if(!empty($surveyRegistration->ApplicationDate)){{$surveyRegistration->ApplicationDate}}@else{{date('Y-m-d G:i:s')}}@endif" />
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12"><h5 class="font-blue-madison bold">Registration Detail</h5></div>
		<div class="col-md-3">
			<div class="form-group">
				<label for="CmnServiceSectorTypeId">Type of Surveyor <span class="font-red">*</span></label>
				<select name="CmnServiceSectorTypeId" id="" class="form-control input-sm required">
					<option value="">---SELECT ONE---</option>
					@foreach($serviceSectorTypes as $serviceSectorType)
					<option value="{{$serviceSectorType->Id}}" @if($serviceSectorType->Id==Input::old('CmnServiceSectorTypeId') || $serviceSectorType->Id==$surveyRegistration->CmnServiceSectorTypeId)selected="selected"@endif>{{$serviceSectorType->Name}}</option>
					@endforeach
				</select>
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				<label for="CIDNo">Salutation<span class="font-red">*</span></label>
				<select name="CmnSalutationId" id="" class="form-control input-sm required">
					<option value="">---SELECT ONE---</option>
					@foreach($salutations as $salutation)
					<option value="{{$salutation->Id}}" @if($salutation->Id==Input::old('CmnSalutationId') || $salutation->Id==$surveyRegistration->CmnSalutationId)selected="selected"@endif>{{$salutation->Name}}</option>
					@endforeach
				</select>
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				<label for="CIDNo">CID/Work Permit No. <span class="font-red">*</span></label>				
				<input type="text" name="CIDNo" value="@if(!empty($surveyRegistration->CIDNo)){{$surveyRegistration->CIDNo}}@else{{Input::old('CIDNo')}}@endif" id="architect-cid" class="form-control input-sm required cidforwebservice" />
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				<label for="CmnSalutationId">Name <span class="font-red">*</span></label>
				<input type="text" name="Name" value="@if(!empty($surveyRegistration->Name)){{$surveyRegistration->Name}}@else{{Input::old('Name')}}@endif" class="form-control input-sm required namefromwebservice" placeholder="">
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-3">
			<div class="form-group">
				<label for="CmnCountryId">Country <span class="font-red">*</span></label>
				<select name="CmnCountryId" class="form-control input-sm select2me required countryselect">
					<option value="">---SELECT ONE---</option>
					@foreach($countries as $country)
						<option value="{{$country->Id}}"@if($country->Id==Input::old('CmnCountryId') || $country->Id==$surveyRegistration->CmnCountryId || $country->Name=="Bhutan")selected="selected"@endif>{{$country->Name}}</option>
					@endforeach
				</select>
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				<label for="CmnDzongkhagId">Dzongkhag <span class="font-red">*</span></label>			
				<select name="CmnDzongkhagId" class="form-control input-sm select2me isbhutanese required dzongkhagfromwebservice">
					<option value="">---SELECT ONE---</option>
					@foreach($dzongkhags as $dzongkhag)
						<option value="{{$dzongkhag->Id}}" @if($dzongkhag->Id==Input::old('CmnDzongkhagId') || $dzongkhag->Id==$surveyRegistration->CmnDzongkhagId)selected="selected"@endif>{{$dzongkhag->NameEn}}</option>
					@endforeach
				</select>
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				<label for="Gewog">Gewog <span class="font-red">*</span></label>
				<input type="text" name="Gewog" value="@if(!empty($surveyRegistration->Gewog)){{$surveyRegistration->Gewog}}@else{{Input::old('Gewog')}}@endif" class="form-control input-sm isbhutanese required gewogfromwebservice" />
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				<label for="">Village <span class="font-red">*</span></label>
				<input type="text" name="Village" value="@if(!empty($surveyRegistration->Village)){{$surveyRegistration->Village}}@else{{Input::old('Village')}}@endif" class="form-control input-sm isbhutanese required villagefromwebservice" />
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-3">
			<div class="form-group">
				<label for="Email">Email <span class="font-red">*</span><a role="button" tabindex="0" data-toggle="popover" data-container="body" data-trigger="focus" class="popoverdefaultopen" data-placement="top" title="<span class='font-green-seagreen'>Email</span>" data-content="<p class='text-justify'>This Email address will be used as username for your account and to receive email from Construction Development Board (CDB). Please provide a valid Email address inorder not to lose important messages from CDB.</p>"><i class="fa fa-question-circle"></i></a></label>
				<input type="text" name="Email" value="@if(!empty($surveyRegistration->Email)){{$surveyRegistration->Email}}@else{{Input::old('Email')}}@endif" class="form-control email input-sm required" />
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				<label for="MobileNo">Mobile No. <span class="font-red">*</span><a role="button" tabindex="0" data-toggle="popover" data-container="body" data-trigger="focus" class="popoverdefaultopen" data-placement="top" title="<span class='font-green-seagreen'>Mobile No.</span>" data-content="<p class='text-justify'>This Mobile No. will be used to receive sms alerts from Construction Development Board (CDB). Please provide a valid Mobile No. registered with Tashi Cell/B-Mobile inorder not to lose important messages from CDB.</p>"><i class="fa fa-question-circle"></i></a></label>
				<input type="text" name="MobileNo" value="@if(!empty($surveyRegistration->MobileNo)){{$surveyRegistration->MobileNo}}@else{{Input::old('MobileNo')}}@endif" data-fixedlength="8" class="form-control fixedlengthvalidate input-sm required" />
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				<label for="EmployerName">Employer Name</label>
				<input type="text" name="EmployerName" value="@if(!empty($surveyRegistration->EmployerName)){{$surveyRegistration->EmployerName}}@else{{Input::old('EmployerName')}}@endif" class="form-control input-sm" />
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				<label for="EmployerAddress">Employer Address</label>
				<input type="text" name="EmployerAddress" value="@if(!empty($surveyRegistration->EmployerAddress)){{$surveyRegistration->EmployerAddress}}@else{{Input::old('EmployerAddress')}}@endif" class="form-control input-sm">
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				<label for="EmployerAddress">TPN</label>
				<input type="text" name="TPN" value="@if(!empty($surveyRegistration->TPN)){{$surveyRegistration->TPN}}@else{{Input::old('TPN')}}@endif" class="form-control input-sm">
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12"><h5 class="font-blue-madison bold">Professional Qualification</h5></div>
		<div class="col-md-3">
			<div class="form-group">
				<label for="CmnQualificationId">Qualification <span class="font-red">*</span></label>
				<select name="CmnQualificationId" class="form-control select2me required">
					<option value="">---SELECT ONE---</option>
					@foreach($qualifications as $qualification)
					<option value="{{$qualification->Id}}" @if($qualification->Id==Input::old('CmnQualificationId') || $qualification->Id==$surveyRegistration->CmnQualificationId)selected="selected"@endif>{{$qualification->Name}}</option>
					@endforeach
				</select>
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				<label for="GraduationYear">Year of Graduation <span class="font-red">*</span></label>
				<div class="input-icon right">
					<i class="fa fa-calendar"></i>
					<input type="text" name="GraduationYear" class="form-control yearonly required" value="@if(!empty($surveyRegistration->GraduationYear)){{$surveyRegistration->GraduationYear}}@else{{Input::old('GraduationYear')}}@endif" placeholder="" readonly>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				<label for="NameOfUniversity">Name of University <span class="font-red">*</span></label>
				<input type="text" name="NameOfUniversity" value="@if(!empty($surveyRegistration->NameOfUniversity)){{$surveyRegistration->NameOfUniversity}}@else{{Input::old('NameOfUniversity')}}@endif" class="form-control required" />
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				<label for="CmnUniversityCountryId">Location (Country) <span class="font-red">*</span></label>
				<select name="CmnUniversityCountryId" class="form-control select2me required">
					<option value="">---SELECT ONE---</option>
					@foreach($countries as $country)
						<option value="{{$country->Id}}" @if($country->Id==Input::old('CmnUniversityCountryId') || $country->Id==$surveyRegistration->CmnUniversityCountryId)selected="selected"@endif>{{$country->Name}}</option>
					@endforeach
				</select>
			</div>
		</div>
	</div>
	@endforeach
	<div class="row">
	@if(isset($oldUploads) && !empty($oldUploads))
		<div class="col-md-6 table-responsive">
			<h5 class="font-blue-madison bold">Existing Documents</h5>
			<table class="table table-bordered table-striped table-condensed">
				<thead>
					<tr>
						<th>Document Name</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					@foreach($oldUploads as $oldUpload)
					<tr>
						<td>
							{{$oldUpload->DocumentName}}
						</td>
						<td>
							<a href="{{URL::to($oldUpload->DocumentPath)}}" target="_blank">View</a>|
							<a href="#" class="">Delete</a>
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
		@endif
		@if(!empty($surveyRegistrationAttachments))
		<div class="col-md-6 table-responsive">
			<h5 class="font-blue-madison bold">@if(isset($oldUploads) && empty($oldUploads)){{'Existing Documents'}}@else{{'New Uploaded Files'}}@endif</h5>
			<table class="table table-bordered table-striped table-condensed">
				<thead>
					<tr>
						<th>Document Name</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					@forelse($surveyRegistrationAttachments as $surveyRegistrationAttachment)
					<tr>
						<td>
							{{$surveyRegistrationAttachment->DocumentName}}
						</td>
						<td>
							<a href="{{URL::to($surveyRegistrationAttachment->DocumentPath)}}" target="_blank">View</a>|
							<a href="#" class="">Delete</a>
						</td>
					</tr>
					@empty
					<tr>
						<td colspan="2" class="font-red text-center">No New Uploads</td>
					</tr>
					@endforelse
				</tbody>
			</table>
		</div>
		@endif
		<div class="col-md-6 table-responsive">
			<h5 class="font-blue-madison bold">Attach Degree Certificate,Academic Certificate and other relevant documents if any.</h5>
			<table id="surveyattachments" class="table table-bordered table-striped table-condensed">
				<thead>
					<tr>
						<th></th>
						<th>Document Name</th>
						<th>Upload File</th>
					</tr>
				</thead>
				<tbody>
					<tr class="notcountforclone">
						<td>
						</td>
						<td>
							<input type="text" name="DocumentName[]" value="Degree Certificate" class="form-control input-sm @if(empty($surveyRegistration->Id) && $isServiceBySurvey==0){{'required'}}@endif">
						</td>
						<td>
							<input type="file" name="attachments[]" class="input-sm @if(empty($surveyRegistration->Id) && $isServiceBySurvey==0){{'required'}}@endif" multiple="multiple" />
						</td>
					</tr>
					<tr class="notcountforclone">
						<td>
						</td>
						<td>
							<input type="text" name="DocumentName[]" value="Academic Transcripts" class="form-control input-sm @if(empty($surveyRegistration->Id) && $isServiceBySurvey==0){{'required'}}@endif">
						</td>
						<td>
							<input type="file" name="attachments[]" class="input-sm @if(empty($surveyRegistration->Id) && $isServiceBySurvey==0){{'required'}}@endif" multiple="multiple" />
						</td>
					</tr>
					<tr>
						<td>
							<button type="button" class="deletetablerow"><i class="fa fa-times fa-lg"></i></button>
						</td>
						<td>
							<input type="text" name="DocumentName[]" value="" placeholder="Optional" class="form-control input-sm">
						</td>
						<td>
							<input type="file" name="attachments[]" class="input-sm" multiple="multiple" />
						</td>
					</tr>
					<tr class="notremovefornew">
						<td>
							<button type="button" class="addtablerow"><i class="fa fa-plus fa-lg"></i></button>
						</td>
						<td colspan="2"></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>
<div class="form-actions">
	<div class="btn-set">
		@if((bool)$surveyId==null)
		<button type="submit" class="btn green">Register <i class="fa fa-arrow-circle-o-right"></i></button>
		<a href="{{URL::to('ezhotin/home/'.Session::get('UserViewerType'))}}" class="btn red">Cancel <i class="fa fa-times"></i></a>
		@else
		<button type="submit" class="btn green">Update <i class="fa fa-arrow-circle-o-right"></i></button>
			@if(!empty($redirectUrl))
				<a href="{{URL::to($redirectUrl.'/'.$surveyId)}}" class="btn red">Cancel <i class="fa fa-times"></i></a>
			
			@endif
		@endif
	</div>
</div>
<!-- END FORM-->