@if(isset($isRejectedApp) && (int)$isRejectedApp==1)
<input type="hidden" value="1" name="ApplicationRejectedReapply">
@endif
@foreach($consultantGeneralInfo as $consultantGeneralInfo)
<div class="form-body">
	<div class="row">
		<div class="col-md-6">
			<div class="form-group">
				<label class="bold">Application No.</label>
				<p class="form-control-static">
					@if(!empty($consultantGeneralInfo->ReferenceNo) || !empty($applicationReferenceNo))
						@if(!empty($consultantGeneralInfo->ReferenceNo))
							{{$consultantGeneralInfo->ReferenceNo}}
						@else
							{{$applicationReferenceNo}}
						@endif
					@else
					{{Input::old('ReferenceNo')}}
					@endif
				</p>
				<input type="hidden" name="ReferenceNo" value="@if(!empty($consultantGeneralInfo->ReferenceNo) || !empty($applicationReferenceNo))@if(!empty($consultantGeneralInfo->ReferenceNo)) {{$consultantGeneralInfo->ReferenceNo}}@else{{$applicationReferenceNo}}@endif @else {{Input::old('ReferenceNo')}} @endif" />
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				<label class="control-label bold">Application Date</label>
				<p class="form-control-static">@if(!empty($consultantGeneralInfo->ApplicationDate)){{convertDateToClientFormat($consultantGeneralInfo->ApplicationDate)}}@else{{convertDateToClientFormat(date('Y-m-d G:i:s'))}}@endif</p>
				<input type="hidden" name="ApplicationDate" value="@if(!empty($consultantGeneralInfo->ApplicationDate)){{$consultantGeneralInfo->ApplicationDate}}@else{{date('Y-m-d G:i:s')}}@endif" />
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<h5 class="font-blue-madison bold">General Information</h5>
		</div>
		<input type="hidden" value="{{$consultantGeneralInfo->Id}}" name="Id" />
		<div class="col-md-3">
			<div class="form-group">
				<label class="control-label">Ownership Type <span class="font-red">*</span></label>
				<select name="CmnOwnershipTypeId" class="changeofowner select2me form-control required companyownershiptype">
					<option value="">---SELECT ONE---</option>
					@foreach($ownershipTypes as $ownershipType)
						<option data-reference="{{$ownershipType->ReferenceNo}}" value="{{$ownershipType->Id}}" @if($ownershipType->Id==Input::old('CmnOwnershipTypeId') || $ownershipType->Id==$consultantGeneralInfo->CmnOwnershipTypeId)selected="selected"@endif>{{$ownershipType->Name}}</option>
					@endforeach
				</select>
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				<label class="control-label">Proposed Name <span class="font-red">* </span><a role="button" tabindex="0" data-toggle="popover" data-container="body" data-trigger="focus" class="popoverdefaultopen" data-placement="top" title="<span class='font-green-seagreen'>Proposed Name</span>" data-content="<p class='text-justify'>If the Name of the Firm that you have proposed for consultant registration is already registered with CDB you cannot proceed with registration. Try using a different Name in such cases.</p>"><i class="fa fa-question-circle"></i></a></label>
				<input type="text" name="NameOfFirm" value="@if(!empty($consultantGeneralInfo->NameOfFirm)){{$consultantGeneralInfo->NameOfFirm}}@else{{Input::old('NameOfFirm')}}@endif" class="form-control required changeoffirmname" placeholder="Proposed Name of the Firm">
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				<label class="control-label">Country <span class="font-red">*</span></label>
				<select name="CmnCountryId" class="form-control select2me input-sm required countryselect changeoflocation">
					<option value="">---SELECT ONE---</option>
					@foreach($countries as $country)
						<option value="{{$country->Id}}" @if($country->Id==Input::old('CmnCountryId') || $country->Id==$consultantGeneralInfo->CmnCountryId || $country->Name=="Bhutan")selected="selected"@endif>{{$country->Name}}</option>
					@endforeach
				</select>
			</div>
		</div><div class="clearfix"></div>
		<div class="col-md-3">	
			<div class="form-group">
				<label class="control-label">Trade License No. </label>
				<input type="text" name="TradeLicenseNo" value="@if(!empty($consultantGeneralInfo->TradeLicenseNo)){{$consultantGeneralInfo->TradeLicenseNo}}@else{{Input::old('TradeLicenseNo')}}@endif" class="form-control dontdisable" placeholder="Trade License No.">
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				<label class="control-label">TPN </label>
				<input type="text" name="TPN" value="@if(!empty($consultantGeneralInfo->TPN)){{$consultantGeneralInfo->TPN}}@else{{Input::old('TPN')}}@endif" class="form-control dontdisable" placeholder="TPN">
			</div>
		</div>
	</div>
	<div class="row">
		<div class="companyownershiptypeattachmentcontrol @if(empty($consultantGeneralInfo->Id) || $consultantGeneralInfo->CmnOwnershipTypeId=='1e243ef0-c652-11e4-b574-080027dcfac6' || $serviceByConsultant==1){{'hide'}}@endif">
			<div class="col-md-6 table-responsive">
				<h5 class="font-blue-madison bold">Attach Certificate of Incorporation</h5>
				<table id="certificateofincorporation" class="table table-bordered table-striped table-condensed">
					<thead>
						<tr>
							<th></th>
							<th>Document Name</th>
							<th>Upload File</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>
								<button type="button" class="deletetablerow"><i class="fa fa-times fa-lg"></i></button>
							</td>
							<td>
								<input type="text" name="DocumentName[]" value="Certificate of Incorporation" class="dontdisable form-control input-sm @if(empty($consultantGeneralInfo->Id) && $serviceByConsultant==0){{'required'}}@endif">
							</td>
							<td>
								<input type="file" name="attachments[]" class="dontdisable input-sm @if(empty($consultantGeneralInfo->Id) && $serviceByConsultant==0){{'required'}}@endif" multiple="multiple" />
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
		@if((int)$serviceByConsultant==1)
		<div class="changeofownerattachmentcontrol hide">
			<div class="col-md-6 table-responsive">
				<h5 class="font-blue-madison bold">Attach marriage certificate if ownership is transferred to spouse or census if transferred to children</h5>
				<table id="changeofownershiptable" class="table table-bordered table-striped table-condensed">
					<thead>
						<tr>
							<th></th>
							<th>Document Name</th>
							<th>Upload File</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>
								<button type="button" class="deletetablerow"><i class="fa fa-times fa-lg"></i></button>
							</td>
							<td>
								<input type="text" name="DocumentNameOwnerShipChange[]" value="Ownership change document" class="dontdisable form-control input-sm">
							</td>
							<td>
								<input type="file" name="attachmentsownershipchange[]" class="dontdisable input-sm" multiple="multiple" />
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
		<div class="changeoffirmnameattachmentcontrol hide">
			<div class="col-md-6 table-responsive">
				<h5 class="font-blue-madison bold">Attach a copy of notification in print media if change in Name of Firm</h5>
				<table id="changeoffirmnametable" class="table table-bordered table-striped table-condensed">
					<thead>
						<tr>
							<th></th>
							<th>Document Name</th>
							<th>Upload File</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>
								<button type="button" class="deletetablerow"><i class="fa fa-times fa-lg"></i></button>
							</td>
							<td>
								<input type="text" name="DocumentNameFirmNameChange[]" value="Document for change of Firm Name" class="dontdisable form-control input-sm">
							</td>
							<td>
								<input type="file" name="attachmentsfirmnamechange[]" class="dontdisable input-sm" multiple="multiple" />
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
		@endif
	</div>
	<div class="row">
		<div class="col-md-12">
			<h5 class="font-blue-madison bold">Permanent Address</h5>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				<label class="control-label">Dzongkhag <span class="font-red">*</span></label>
				<select name="CmnDzongkhagId" class="form-control select2me isbhutanese required changeoflocation">
					<option value="">---SELECT ONE---</option>
					@foreach($dzongkhags as $dzongkhag)
						<option value="{{$dzongkhag->Id}}" @if($dzongkhag->Id==Input::old('CmnDzongkhagId') || $dzongkhag->Id==$consultantGeneralInfo->CmnDzongkhagId)selected="selected"@endif>{{$dzongkhag->NameEn}}</option>
					@endforeach
				</select>
			</div>
		</div>
		<div class="col-md-3">	
			<div class="form-group">
				<label class="control-label">Gewog <span class="font-red">*</span></label>
				<input type="text" class="changeoflocation form-control isbhutanese" value="@if(!empty($consultantGeneralInfo->Gewog)){{$consultantGeneralInfo->Gewog}}@else{{Input::old('Gewog')}}@endif" name="Gewog">
			</div>
		</div>
		<div class="col-md-3">	
			<div class="form-group">
				<label class="control-label">Village <span class="font-red">*</span></label>
				<input type="text" class="changeoflocation form-control isbhutanese	" value="@if(!empty($consultantGeneralInfo->Village)){{$consultantGeneralInfo->Village}}@else{{Input::old('Village')}}@endif" name="Village">
			</div>
		</div>	
		{{--<div class="col-md-3">	
			<div class="form-group">
				<label class="control-label">Address <span class="font-red">*</span></label>
				<textarea class="form-control required changeoflocation" name="RegisteredAddress">@if(!empty($consultantGeneralInfo->RegisteredAddress)){{$consultantGeneralInfo->RegisteredAddress}}@else{{Input::old('RegisteredAddress')}}@endif</textarea>
			</div>
		</div>--}}
	</div>
	<div class="row">
		<div class="col-md-12">
			<h5 class="font-blue-madison bold">Correspondence Address</h5>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				<label class="control-label">Dzongkhag <span class="font-red">*</span></label>
				<select name="CmnRegisteredDzongkhagId" class="form-control select2me isbhutanese required changeoflocation">
					<option value="">---SELECT ONE---</option>
					@foreach($dzongkhags as $dzongkhag)
						<option value="{{$dzongkhag->Id}}" @if($dzongkhag->Id==Input::old('CmnRegisteredDzongkhagId') || $dzongkhag->Id==$consultantGeneralInfo->CmnRegisteredDzongkhagId)selected="selected"@endif>{{$dzongkhag->NameEn}}</option>
					@endforeach
				</select>
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				<label class="control-label">Office Establishment Address <span class="font-red">*</span></label>
				<input type="text" class="form-control required changeoflocation" name="Address" value="@if(!empty($consultantGeneralInfo->Address)){{$consultantGeneralInfo->Address}}@else{{Input::old('Address')}}@endif" />
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				<label class="control-label">Email <span class="font-red">* </span><a role="button" tabindex="0" data-toggle="popover" data-container="body" data-trigger="focus" class="popoverdefaultopen" data-placement="top" title="<span class='font-green-seagreen'>Email</span>" data-content="<p class='text-justify'>This Email address will be used as username for your account and to receive email from Construction Development Board (CDB). Please provide a valid Email address inorder not to lose important messages from CDB.</p>"><i class="fa fa-question-circle"></i></a></label>
				<input type="text" name="Email" value="@if(!empty($consultantGeneralInfo->Email)){{$consultantGeneralInfo->Email}}@else{{Input::old('Email')}}@endif" class="form-control required" placeholder="Email">
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				<label class="control-label">Mobile No. <span class="font-red">* </span><a role="button" tabindex="0" data-toggle="popover" data-container="body" data-trigger="focus" class="popoverdefaultopen" data-placement="top" title="<span class='font-green-seagreen'>Mobile No.</span>" data-content="<p class='text-justify'>This Mobile No. will be used to receive sms alerts from Construction Development Board (CDB). Please provide a valid Mobile No. registered with Tashi Cell/B-Mobile inorder not to lose important messages from CDB.</p>"><i class="fa fa-question-circle"></i></a></label>	
				<input type="text" name="MobileNo" value="@if(!empty($consultantGeneralInfo->MobileNo)){{$consultantGeneralInfo->MobileNo}}@else{{Input::old('MobileNo')}}@endif" class="form-control number fixedlengthvalidate changeoflocation
changeoflocation" data-fixedlength="8" placeholder="Mobile No.">
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				<label class="control-label">Telephone No. <span class="font-red">*</span></label>
				<input type="text" name="TelephoneNo" value="@if(!empty($consultantGeneralInfo->TelephoneNo)){{$consultantGeneralInfo->TelephoneNo}}@else{{Input::old('TelephoneNo')}}@endif" class="form-control required changeoflocation
changeoflocation" placeholder="Telephone">
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				<label class="control-label">Fax No.</label>
				<input type="text" name="FaxNo" value="@if(!empty($consultantGeneralInfo->FaxNo)){{$consultantGeneralInfo->FaxNo}}@else{{Input::old('FaxNo')}}@endif" class="form-control changeoflocation
changeoflocation" placeholder="Fax No.">
			</div>
		</div>
	</div>
</div>
@endforeach
<div class="row">
	<div class="col-md-12">
		<div class="form-body">
			<h5 class="font-blue-madison bold">Name of Owner, Partners and/or others with Controlling Intrest</h5>
			<div class="table-responsive">
				<table id="ownerpartnerdetails" class="table table-bordered table-striped table-condensed">
					<thead>
						<tr>
							<th>
							</th>
							<th class="">
								 CID/Work Permit No.
							</th>
							<th width="10%">
								Salutation
							</th>
							<th>
								 Name
							</th>
							<th width="10%">
								 Sex
							</th>
							<th class="">
								Country
							</th>
							<th>
								Designation
							</th>
							<th>
								Show in Certificate
							</th>
						</tr>
					</thead>
					<tbody>
						<?php $count=1; ?>
						@forelse($consultantPartnerDetails as $consultantPartnerDetail)
						<?php $randomKey=randomString();?>
						<tr>
							<td>
								<button type="button" class="deletetablerow"><i class="fa fa-times"></i></button>
							</td>
							<td>
								<input type="text" name="ConsultantHumanResourceModel[{{$randomKey}}][CIDNo]" value="{{$consultantPartnerDetail->CIDNo}}" class="form-control input-sm resetKeyForNew required changeofowner {{--checkhrtr--}} cidforwebservicetr">
							</td>
							<td>
								<input type="hidden" name="ConsultantHumanResourceModel[{{$randomKey}}][Id]" value="{{$consultantPartnerDetail->Id}}" class="resetKeyForNew"/>
								<select name="ConsultantHumanResourceModel[{{$randomKey}}][CmnSalutationId]" class="form-control input-sm resetKeyForNew required changeofowner">
									<option value="">---SELECT---</option>
									@foreach($salutations as $salutation)
									<option value="{{$salutation->Id}}" @if($salutation->Id==$consultantPartnerDetail->CmnSalutationId)selected="selected"@endif>{{$salutation->Name}}</option>
									@endforeach
								</select>
							</td>
							<td>
								<input type="text" name="ConsultantHumanResourceModel[{{$randomKey}}][Name]" value="{{$consultantPartnerDetail->Name}}" class="form-control input-sm resetKeyForNew required changeofowner namefromwebservice">
							</td>
							<td>
								<select name="ConsultantHumanResourceModel[{{$randomKey}}][Sex]" class="form-control input-sm resetKeyForNew required changeofowner sexfromwebservice">
									<option value="">---SELECT ONE---</option>
									<option value="M"  @if($consultantPartnerDetail->Sex=="M")selected="selected"@endif>Male</option>
									<option value="F"  @if($consultantPartnerDetail->Sex=="F")selected="selected"@endif>Female</option>
								</select>
							</td>
							<td>
								<select name="ConsultantHumanResourceModel[{{$randomKey}}][CmnCountryId]" class="form-control input-sm resetKeyForNew required changeofowner notclearfornew countryfromwebservice">
									<option value="">---SELECT ONE---</option>
									@foreach($countries as $country)
										<option value="{{$country->Id}}" @if($consultantPartnerDetail->CmnCountryId==$country->Id || $country->Name=="Bhutan")selected="selected"@endif>{{$country->Name}}</option>
									@endforeach
								</select>
							</td>
							<td>
								<select name="ConsultantHumanResourceModel[{{$randomKey}}][CmnDesignationId]" class="form-control input-sm resetKeyForNew changeofowner">
									<option value="">---SELECT ONE---</option>
									@foreach($designations as $designation)
										<option value="{{$designation->Id}}" @if($consultantPartnerDetail->CmnDesignationId==$designation->Id)selected="selected"@endif>{{$designation->Name}}</option>
									@endforeach
								</select>
							</td>
							<td>
								<input type="checkbox" name="ConsultantHumanResourceModel[{{$randomKey}}][ShowInCertificate]" class="checkboxes resetKeyForNew notclearfornew addrowcheckboxsinglecheck changeofowner" value="1" @if((int)$consultantPartnerDetail->ShowInCertificate==1 || (empty($consultantPartnerDetail->ShowInCertificate) && $count==1))checked="checked"@endif/>
								<input type="hidden" name="ConsultantHumanResourceModel[{{$randomKey}}][IsPartnerOrOwner]" class="resetKeyForNew notclearfornew changeofowner" value="1"/>
							</td>
						</tr>
						<?php $count++; ?>
						@empty
							<?php $randomKey = randomString(); ?>
							<tr>
								<td>
									<button type="button" class="deletetablerow"><i class="fa fa-times"></i></button>
								</td>
								<td>
									<input type="text" name="ConsultantHumanResourceModel[{{$randomKey}}][CIDNo]" value="" class="form-control input-sm resetKeyForNew required {{--checkhrtr--}} changeofowner cidforwebservicetr">
								</td>
								<td>
									<input type="hidden" name="ConsultantHumanResourceModel[{{$randomKey}}][Id]" value="" class="resetKeyForNew"/>
									<select name="ConsultantHumanResourceModel[{{$randomKey}}][CmnSalutationId]" class="form-control input-sm resetKeyForNew required changeofowner">
										<option value="">---SELECT---</option>
										@foreach($salutations as $salutation)
											<option value="{{$salutation->Id}}" >{{$salutation->Name}}</option>
										@endforeach
									</select>
								</td>
								<td>
									<input type="text" name="ConsultantHumanResourceModel[{{$randomKey}}][Name]" value="" class="form-control input-sm resetKeyForNew required changeofowner namefromwebservice">
								</td>
								<td>
									<select name="ConsultantHumanResourceModel[{{$randomKey}}][Sex]" class="form-control input-sm resetKeyForNew required changeofowner sexfromwebservice">
										<option value="">---SELECT ONE---</option>
										<option value="M">Male</option>
										<option value="F">Female</option>
									</select>
								</td>
								<td>
									<select name="ConsultantHumanResourceModel[{{$randomKey}}][CmnCountryId]" class="form-control input-sm resetKeyForNew required changeofowner notclearfornew countryfromwebservice">
										<option value="">---SELECT ONE---</option>
										@foreach($countries as $country)
											<option value="{{$country->Id}}" @if($country->Name=="Bhutan")selected="selected"@endif>{{$country->Name}}</option>
										@endforeach
									</select>
								</td>
								<td>
									<select name="ConsultantHumanResourceModel[{{$randomKey}}][CmnDesignationId]" class="form-control input-sm resetKeyForNew changeofowner">
										<option value="">---SELECT ONE---</option>
										@foreach($designations as $designation)
											<option value="{{$designation->Id}}" >{{$designation->Name}}</option>
										@endforeach
									</select>
								</td>
								<td>
									<input type="checkbox" name="ConsultantHumanResourceModel[{{$randomKey}}][ShowInCertificate]" class="checkboxes resetKeyForNew notclearfornew addrowcheckboxsinglecheck changeofowner" value="1" checked="checked"/>
									<input type="hidden" name="ConsultantHumanResourceModel[{{$randomKey}}][IsPartnerOrOwner]" class="resetKeyForNew notclearfornew changeofowner" value="1"/>
								</td>
							</tr>
						@endforelse
						<tr class="notremovefornew">
							<td>
								<button type="button" class="addtablerow"><i class="fa fa-plus fa-lg"></i></button>
							</td>
							<td colspan="7"></td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="form-actions">
				<div class="btn-set">
					@if((bool)$isEdit==null)
					<button type="submit" class="btn blue">Continue <i class="fa fa-arrow-circle-o-right"></i></button>
					<a href="{{URL::to('ezhotin/home/'.Session::get('UserViewerType'))}}" class="btn red">Cancel <i class="fa fa-times"></i></a>
					@else
					<button type="submit" class="btn blue">Update <i class="fa fa-arrow-circle-o-right"></i></button>
						@if(!empty($redirectUrl))
							<a href="{{URL::to($redirectUrl.'/'.$isEdit)}}" class="btn red">Cancel <i class="fa fa-times"></i></a>
						@else
							<a href="{{URL::to('consultant/confirmregistration')}}" class="btn red">Cancel <i class="fa fa-times"></i></a>
						@endif
					@endif
				</div>
			</div>
		</div>
	</div>
</div>