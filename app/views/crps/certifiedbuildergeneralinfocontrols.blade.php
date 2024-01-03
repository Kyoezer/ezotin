@if(isset($isRejectedApp) && (int)$isRejectedApp==1)
<input type="hidden" value="1" name="ApplicationRejectedReapply">
@endif
<?php $ownerEngineer = false; ?>
@foreach($certifiedbuilderGeneralInfo as $generalInfo)
<div class="form-body">
	<div class="row">
		<div class="col-md-6">
			<div class="form-group">
				<label class="bold">Application No.</label>
				<p class="form-control-static">
					@if(!empty($generalInfo->ReferenceNo) || !empty($applicationReferenceNo))
						@if(!empty($generalInfo->ReferenceNo))
							{{$generalInfo->ReferenceNo}}
						@else
							{{$applicationReferenceNo}}
						@endif
					@else
					{{Input::old('ReferenceNo')}}
					@endif
				</p>
				<input type="hidden" name="ReferenceNo" value="@if(!empty($generalInfo->ReferenceNo) || !empty($applicationReferenceNo)) @if(!empty($generalInfo->ReferenceNo)){{$generalInfo->ReferenceNo}}@else{{$applicationReferenceNo}}@endif @else{{Input::old('ReferenceNo')}} @endif" />
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				<label class="control-label bold">Application Date</label>
				<p class="form-control-static">@if(!empty($generalInfo->ApplicationDate)){{convertDateToClientFormat($generalInfo->ApplicationDate)}}@else{{convertDateToClientFormat(date('Y-m-d G:i:s'))}}@endif</p>
				<input type="hidden" name="ApplicationDate" value="@if(!empty($generalInfo->ApplicationDate)){{$generalInfo->ApplicationDate}}@else{{date('Y-m-d G:i:s')}}@endif" />
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<h5 class="font-blue-madison bold">General Information</h5>
		</div>
		<div class="col-md-3">
			<input type="hidden" value="{{$generalInfo->Id}}" name="Id" />
			<div class="form-group">
				<label class="control-label">Ownership Type <span class="font-red">*</span></label>
				<select name="CmnOwnershipTypeId" id="CmnOwnershipTypeId" class="changeofowner select2me form-control required companyownershiptype">
					<option value="">---SELECT ONE---</option>
					@foreach($ownershipTypes as $ownershipType)
						<option data-reference="{{$ownershipType->ReferenceNo}}" value="{{$ownershipType->Id}}" @if($ownershipType->Id==Input::old('CmnOwnershipTypeId') || $ownershipType->Id==$generalInfo->CmnOwnershipTypeId)selected="selected"@endif>{{$ownershipType->Name}}</option>
					@endforeach
				</select>
			</div>
		</div>
		<div class="col-md-3">	
			<div class="form-group">
				<label class="control-label">Proposed Firm/Company Name <span class="font-red">* </span> <a role="button" tabindex="0" data-toggle="popover" data-container="body" data-trigger="focus" class="popoverdefaultopen" data-placement="top" title="<span class='font-green-seagreen'>Proposed Name</span>" data-content="<p class='text-justify'>If the Name of the Firm that you have proposed for certifiedbuilder registration is already registered with CDB you cannot proceed with registration. Try using a different Name in such cases.</p>"><i class="fa fa-question-circle"></i></a></label>
				<input type="text" name="NameOfFirm" value='@if(!empty($generalInfo->NameOfFirm)){{$generalInfo->NameOfFirm}}@else{{Input::old('NameOfFirm')}}@endif' class="form-control required changeoffirmname" placeholder="Proposed Name of the Firm">
				<span class="help-block bold info-block" style="color:#3c763d">For Sole Proprietorship, please include "Construction" or "Builders" in the name</span>
			</div>
		</div>
		<?php
			$hasOldCountryId = false;
			$oldCountryId = Input::old('CmnCountryId');
			if(!(bool)$oldCountryId){
				$oldCountryId = $generalInfo->CmnCountryId;
				if((bool)$oldCountryId){
					$hasOldCountryId = true;
				}
			}
		?>
		<div class="col-md-3">	
			<div class="form-group">		
				<label class="control-label">Country <span class="font-red">*</span></label>
				<select name="CmnCountryId" id="CmnCountryId" class="form-control select2me input-sm required countryselect">
					<option value="">---SELECT ONE---</option>
					@foreach($countries as $country)
						<option value="{{$country->Id}}" @if($hasOldCountryId)@if($country->Id==$oldCountryId)selected="selected"@endif @else @if($country->Code == 10)selected="selected"@endif @endif>{{$country->Name}}</option>
					@endforeach
				</select>
			</div>
		</div>
		<div class="clearfix"></div>
		<div class="col-md-3">	
			<div class="form-group">
				<label class="control-label">Trade License No. </label>
				<input type="text" name="TradeLicenseNo" value="@if(!empty($generalInfo->TradeLicenseNo)){{$generalInfo->TradeLicenseNo}}@else{{Input::old('TradeLicenseNo')}}@endif" class="form-control dontdisable changeoftradelicense" placeholder="Trade License No.">
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				<label class="control-label">TPN </label>
				<input type="text" name="TPN" value="@if(!empty($generalInfo->TPN)){{$generalInfo->TPN}}@else{{Input::old('TPN')}}@endif" class="form-control dontdisable" placeholder="TPN">
			</div>
		</div>
		{{--<div class="clearfix"></div>--}}
		
		@if((int)$serviceByCertifiedbuilder==1)
		<div class="changeofownerattachmentcontrol hide">
			<div class="col-md-6 table-responsive">
				<h5 class="font-blue-madison bold">Attach marriage certificate if ownership is transferred to spouse or census if transferred to children</h5>
				<table id="changeofownershiptable" class="table table-bordered table-striped table-condensed">
				
					<tbody>
						<tr>
							<td>
								<button type="button" class="deletetablerow"><i class="fa fa-times fa-lg"></i></button>
							</td>
							<td>
								<input type="text" name="DocumentNameOwnerShipChange[]" value="MC scanned" class="dontdisable form-control input-sm">
							</td>
							<td>
								<input type="file" name="attachmentsownershipchange[]" class="dontdisable input-sm" multiple="multiple" />
							</td>
						</tr>
						<tr>
							<td>
								<button type="button" class="deletetablerow"><i class="fa fa-times fa-lg"></i></button>
							</td>
							<td>
								<input type="text" name="DocumentNameOwnerShipChange[]" value="Census" class="dontdisable form-control input-sm">
							</td>
							<td>
								<input type="file" name="attachmentsownershipchange[]" class="dontdisable input-sm" multiple="multiple" />
							</td>
						</tr>
						<tr>
							<td>
								<button type="button" class="deletetablerow"><i class="fa fa-times fa-lg"></i></button>
							</td>
							<td>
								<input type="text" name="DocumentNameOwnerShipChange[]" value="CID copy of transferee" class="dontdisable form-control input-sm">
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
				{{Form::label('Reason','Reason for change of ownership',array('class'=>'control-label'))}}
				<input type="text" name="ChangeOfOwnershipRemarks" class="form-control dontdisable"/>
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
								<input type="text" name="DocumentNameFirmNameChange[]" value="Notification in print media" class="dontdisable form-control input-sm">
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
				<select name="CmnDzongkhagId" class="form-control <?php if($hasOldCountryId): ?><?php if($oldCountryId == CONST_COUNTRY_BHUTAN): ?>{{"required "}}<?php endif; ?><?php else: ?>{{"required "}}<?php endif;?>select2me isbhutanese changeoflocation" <?php if($hasOldCountryId): ?><?php if($oldCountryId != CONST_COUNTRY_BHUTAN): ?>disabled="disabled"<?php endif; ?><?php endif;?>>
					<option value="">---SELECT ONE---</option>
					@foreach($dzongkhags as $dzongkhag)
						<option value="{{$dzongkhag->Id}}" @if($dzongkhag->Id==Input::old('CmnDzongkhagId') || $dzongkhag->Id==$generalInfo->CmnDzongkhagId)selected="selected"@endif>{{$dzongkhag->NameEn}}</option>
					@endforeach
				</select>
			</div>
		</div>
		<div class="col-md-3">	
			<div class="form-group">
				<label class="control-label">Gewog
				<input type="text" class="form-control isbhutanese changeoflocation" value="@if(!empty($generalInfo->Gewog)){{$generalInfo->Gewog}}@else{{Input::old('Gewog')}}@endif" name="Gewog" <?php if($hasOldCountryId): ?><?php if($oldCountryId != CONST_COUNTRY_BHUTAN): ?>disabled="disabled"<?php endif; ?><?php endif;?>>
			</div>
		</div>
		<div class="col-md-3">	
			<div class="form-group">
				<label class="control-label">Village
				<input type="text" class="form-control isbhutanese changeoflocation" <?php if($hasOldCountryId): ?><?php if($oldCountryId != CONST_COUNTRY_BHUTAN): ?>disabled="disabled"<?php endif; ?><?php endif;?> value="@if(!empty($generalInfo->Village)){{$generalInfo->Village}}@else{{Input::old('Village')}}@endif" name="Village">
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<h5 class="font-blue-madison bold">Establishment Address</h5>
			<span class="help-block bold info-block" style="color:red;">(This address will be displayed in certificate)</span>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				<label class="control-label">Dzongkhag <span class="font-red">*</span></label>
				<select name="CmnRegisteredDzongkhagId" class="form-control <?php if($hasOldCountryId): ?><?php if($oldCountryId == CONST_COUNTRY_BHUTAN): ?>{{"required "}}<?php endif; ?><?php else: ?>{{"required "}}<?php endif;?>select2me isbhutanese changeoflocation" <?php if($hasOldCountryId): ?><?php if($oldCountryId != CONST_COUNTRY_BHUTAN): ?>disabled="disabled"<?php endif; ?><?php endif;?>>
					<option value="">---SELECT ONE---</option>
					@foreach($dzongkhags as $dzongkhag)
						<option value="{{$dzongkhag->Id}}" @if($dzongkhag->Id==Input::old('CmnRegisteredDzongkhagId') || $dzongkhag->Id==$generalInfo->CmnRegisteredDzongkhagId)selected="selected"@endif>{{$dzongkhag->NameEn}}</option>
					@endforeach
				</select>
			</div>
		</div>
		<div class="col-md-3">	
			<div class="form-group">
				<label class="control-label">Email <span class="font-red">* </span><a role="button" tabindex="0" data-toggle="popover" data-container="body" data-trigger="focus" class="popoverdefaultopen" data-placement="top" title="<span class='font-green-seagreen'>Email</span>" data-content="<p class='text-justify'>This Email address will be used as username for your account and to receive email from Construction Development Board (CDB). Please provide a valid Email address inorder not to lose important messages from CDB.</p>"><i class="fa fa-question-circle"></i></a></label>
				<input type="text" name="Email" value="@if(!empty($generalInfo->Email)){{$generalInfo->Email}}@else{{Input::old('Email')}}@endif" class="form-control dontdisable required email" placeholder="Email">
			</div>
		</div>
		<div class="col-md-3">	
			<div class="form-group">	
				<label class="control-label">Mobile No. <a role="button" tabindex="0" data-toggle="popover" data-container="body" data-trigger="focus" class="popoverdefaultopen" data-placement="top" title="<span class='font-green-seagreen'>Mobile No.</span>" data-content="<p class='text-justify'>This Mobile No. will be used to receive sms alerts from Construction Development Board (CDB). Please provide a valid Mobile No. registered with Tashi Cell/B-Mobile inorder not to lose important messages from CDB.</p>"><i class="fa fa-question-circle"></i></a></label>
				<input type="text" name="MobileNo" value="@if(!empty($generalInfo->MobileNo)){{$generalInfo->MobileNo}}@else{{Input::old('MobileNo')}}@endif" class="form-control changeoflocation" data-fixedlength="8" placeholder="Mobile No.">
			</div>
		</div><div class="clearfix"></div>
		<div class="col-md-3">	
			<div class="form-group">	
				<label class="control-label">Telephone No.
				<input type="text" name="TelephoneNo" value="@if(!empty($generalInfo->TelephoneNo)){{$generalInfo->TelephoneNo}}@else{{Input::old('TelephoneNo')}}@endif" class="form-control changeoflocation" placeholder="Telephone">
			</div>
		</div>
		<div class="col-md-3">	
			<div class="form-group">	
				<label class="control-label">Fax No.</label>
				<input type="text" name="FaxNo" value="@if(!empty($generalInfo->FaxNo)){{$generalInfo->FaxNo}}@else{{Input::old('FaxNo')}}@endif" class="form-control changeoflocation" placeholder="Fax No.">
			</div>
		</div>
		<div class="col-md-3">	
			<div class="form-group">
				<label class="control-label">Address </label>
				<textarea class="form-control changeoflocation isnonbhutanese" name="Address">@if(!empty($generalInfo->Address)){{$generalInfo->Address}}@else{{Input::old('Address')}}@endif</textarea>
				<span class="help-block bold info-block" style="color:red;">(Office setup details)</span>
			</div>
		</div>
	</div>
</div>
@endforeach

<div class="row">
	<div class="col-md-12">
		<div class="form-body">
			<h5 class="font-blue-madison bold">Name of Owner, Partners and/or others with Controlling Interest</h5>
			<div class="table-responsive">
				<table id="ownerpartnerdetails" class="table table-bordered table-striped table-condensed">
					{{Form::hidden('Model','CertifiedbuilderHumanResourceFinalModel',array('class'=>'delete-model'))}}
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
							<th width="15%">
								Nationality
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
						@forelse($certifiedbuilderPartnerDetails as $certifiedbuilderPartnerDetail)
						<?php $randomKey=randomString();?>
						<tr>
							<td>
								<input type="hidden" class="rowreference" value="{{$certifiedbuilderPartnerDetail->Id}}">
								<button type="button" class="@if(Input::has('final')){{"deletedbrow"}}@else{{"deletetablerow"}}@endif"><i class="fa fa-times"></i></button>
							</td>
							<td>
								<input @if(Input::has('oldapplicationid'))disabled="disabled"@endif type="text" name="{{Input::has('final')?"CertifiedbuilderHumanResourceFinalModel":"CertifiedbuilderHumanResourceModel"}}[{{$randomKey}}][CIDNo]" value="{{$certifiedbuilderPartnerDetail->CIDNo}}" data-id="owner-partner" class="form-control input-sm resetKeyForNew @if(!Input::has('oldapplicationid')){{"required"}}@endif unique changeofowner {{--checkhrtr--}} cidforwebservicetr">
							</td>
							<td>
								<input @if(Input::has('oldapplicationid'))disabled="disabled"@endif type="hidden" name="{{Input::has('final')?"CertifiedbuilderHumanResourceFinalModel":"CertifiedbuilderHumanResourceModel"}}[{{$randomKey}}][Id]" value="{{$certifiedbuilderPartnerDetail->Id}}" class="resetKeyForNew changeofowner"/>
								<select @if(Input::has('oldapplicationid'))disabled="disabled"@endif name="{{Input::has('final')?"CertifiedbuilderHumanResourceFinalModel":"CertifiedbuilderHumanResourceModel"}}[{{$randomKey}}][CmnSalutationId]" class="form-control input-sm resetKeyForNew @if(!Input::has('oldapplicationid')){{"required"}}@endif changeofowner">
									<option value="">---SELECT---</option>
									@foreach($salutations as $salutation)
									<option value="{{$salutation->Id}}" @if($salutation->Id==$certifiedbuilderPartnerDetail->CmnSalutationId)selected="selected"@endif>{{$salutation->Name}}</option>
									@endforeach
								</select>
							</td>
							<td>
								<input @if(Input::has('oldapplicationid'))disabled="disabled"@endif type="text" name="{{Input::has('final')?"CertifiedbuilderHumanResourceFinalModel":"CertifiedbuilderHumanResourceModel"}}[{{$randomKey}}][Name]" value="{{$certifiedbuilderPartnerDetail->Name}}" class="form-control input-sm resetKeyForNew @if(!Input::has('oldapplicationid')){{"required"}}@endif changeofowner namefromwebservice">
							</td>
							<td>
								<select @if(Input::has('oldapplicationid'))disabled="disabled"@endif name="{{Input::has('final')?"CertifiedbuilderHumanResourceFinalModel":"CertifiedbuilderHumanResourceModel"}}[{{$randomKey}}][Sex]" class="form-control input-sm resetKeyForNew @if(!Input::has('oldapplicationid')){{"required"}}@endif changeofowner sexfromwebservice">
									<option value="">---SELECT ONE---</option>
									<option value="M"  @if($certifiedbuilderPartnerDetail->Sex=="M")selected="selected"@endif>Male</option>
									<option value="F"  @if($certifiedbuilderPartnerDetail->Sex=="F")selected="selected"@endif>Female</option>
								</select>
							</td>
							<td>
								<select @if(Input::has('oldapplicationid'))disabled="disabled"@endif name="{{Input::has('final')?"CertifiedbuilderHumanResourceFinalModel":"CertifiedbuilderHumanResourceModel"}}[{{$randomKey}}][CmnCountryId]" class="form-control input-sm resetKeyForNew @if(!Input::has('oldapplicationid')){{"required"}}@endif changeofowner notclearfornew countryfromwebservice">
									<option value="">---SELECT ONE---</option>
									@foreach($countries as $country)
										<option value="{{$country->Id}}" @if($certifiedbuilderPartnerDetail->CmnCountryId==$country->Id || $country->Nationality=="Bhutanese")selected="selected"@endif>{{$country->Nationality}}</option>
									@endforeach
								</select>
							</td>
							<td>
								<select id="registration-hrdesignation" @if(Input::has('oldapplicationid'))disabled="disabled"@endif name="{{Input::has('final')?"CertifiedbuilderHumanResourceFinalModel":"CertifiedbuilderHumanResourceModel"}}[{{$randomKey}}][CmnDesignationId]" class="form-control input-sm resetKeyForNew @if(!Input::has('oldapplicationid')){{"required"}}@endif changeofowner">
									<option value="">---SELECT ONE---</option>
									@foreach($designations as $designation)
										<option value="{{$designation->Id}}" data-reference="{{$designation->ReferenceNo}}" @if($certifiedbuilderPartnerDetail->CmnDesignationId==$designation->Id)selected="selected"<?php if($designation->ReferenceNo == 101): $ownerEngineer = true; ?><?php endif; ?>@endif>{{$designation->Name}}</option>
									@endforeach
								</select>
							</td>
							<td>
								<input @if(Input::has('oldapplicationid'))disabled="disabled"@endif type="checkbox" name="{{Input::has('final')?"CertifiedbuilderHumanResourceFinalModel":"CertifiedbuilderHumanResourceModel"}}[{{$randomKey}}][ShowInCertificate]" class="checkboxes resetKeyForNew notclearfornew addrowcheckboxsinglecheck changeofowner" value="1" @if((int)$certifiedbuilderPartnerDetail->ShowInCertificate==1)checked="checked"@endif/>
								<input @if(Input::has('oldapplicationid'))disabled="disabled"@endif type="hidden" name="{{Input::has('final')?"CertifiedbuilderHumanResourceFinalModel":"CertifiedbuilderHumanResourceModel"}}[{{$randomKey}}][IsPartnerOrOwner]" class="resetKeyForNew notclearfornew changeofowner" value="1"/>
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
									<input type="text" @if(Input::has('oldapplicationid'))disabled="disabled"@endif name="{{Input::has('final')?"CertifiedbuilderHumanResourceFinalModel":"CertifiedbuilderHumanResourceModel"}}[{{$randomKey}}][CIDNo]" value="" data-id="owner-partner" class="form-control input-sm resetKeyForNew @if(!Input::has('oldapplicationid')){{"required"}}@endif unique changeofowner {{--checkhrtr--}} cidforwebservicetr">
								</td>
								<td>
									<input type="hidden" @if(Input::has('oldapplicationid'))disabled="disabled"@endif name="{{Input::has('final')?"CertifiedbuilderHumanResourceFinalModel":"CertifiedbuilderHumanResourceModel"}}[{{$randomKey}}][Id]" value="" class="resetKeyForNew changeofowner"/>
									<select @if(Input::has('oldapplicationid'))disabled="disabled"@endif name="{{Input::has('final')?"CertifiedbuilderHumanResourceFinalModel":"CertifiedbuilderHumanResourceModel"}}[{{$randomKey}}][CmnSalutationId]" class="form-control input-sm resetKeyForNew @if(!Input::has('oldapplicationid')){{"required"}}@endif changeofowner">
										<option value="">---SELECT---</option>
										@foreach($salutations as $salutation)
											<option value="{{$salutation->Id}}">{{$salutation->Name}}</option>
										@endforeach
									</select>
								</td>
								<td>
									<input type="text" @if(Input::has('oldapplicationid'))disabled="disabled"@endif name="{{Input::has('final')?"CertifiedbuilderHumanResourceFinalModel":"CertifiedbuilderHumanResourceModel"}}[{{$randomKey}}][Name]" value="" class="form-control input-sm resetKeyForNew @if(!Input::has('oldapplicationid')){{"required"}}@endif changeofowner namefromwebservice">
								</td>
								<td>
									<select @if(Input::has('oldapplicationid'))disabled="disabled"@endif name="{{Input::has('final')?"CertifiedbuilderHumanResourceFinalModel":"CertifiedbuilderHumanResourceModel"}}[{{$randomKey}}][Sex]" class="form-control input-sm resetKeyForNew @if(!Input::has('oldapplicationid')){{"required"}}@endif changeofowner sexfromwebservice">
										<option value="">---SELECT ONE---</option>
										<option value="M">Male</option>
										<option value="F">Female</option>
									</select>
								</td>
								<td>
									<select @if(Input::has('oldapplicationid'))disabled="disabled"@endif name="{{Input::has('final')?"CertifiedbuilderHumanResourceFinalModel":"CertifiedbuilderHumanResourceModel"}}[{{$randomKey}}][CmnCountryId]" class="form-control input-sm resetKeyForNew @if(!Input::has('oldapplicationid')){{"required"}}@endif changeofowner notclearfornew countryfromwebservice">
										<option value="">---SELECT ONE---</option>
										@foreach($countries as $country)
											<option value="{{$country->Id}}" @if($country->Nationality=="Bhutanese")selected="selected"@endif>{{$country->Nationality}}</option>
										@endforeach
									</select>
								</td>
								<td>
									<select id="registration-hrdesignation" @if(Input::has('oldapplicationid'))disabled="disabled"@endif name="{{Input::has('final')?"CertifiedbuilderHumanResourceFinalModel":"CertifiedbuilderHumanResourceModel"}}[{{$randomKey}}][CmnDesignationId]" class="form-control input-sm resetKeyForNew @if(!Input::has('oldapplicationid')){{"required"}}@endif changeofowner">
										<option value="">---SELECT ONE---</option>
										@foreach($designations as $designation)
											<option value="{{$designation->Id}}" data-reference="{{$designation->ReferenceNo}}">{{$designation->Name}}</option>
										@endforeach
									</select>
								</td>
								<td>
									<input @if(Input::has('oldapplicationid'))disabled="disabled"@endif type="checkbox" name="{{Input::has('final')?"CertifiedbuilderHumanResourceFinalModel":"CertifiedbuilderHumanResourceModel"}}[{{$randomKey}}][ShowInCertificate]" class="checkboxes resetKeyForNew notclearfornew addrowcheckboxsinglecheck changeofowner" value="1" checked="checked"/>
									<input @if(Input::has('oldapplicationid'))disabled="disabled"@endif type="hidden" name="{{Input::has('final')?"CertifiedbuilderHumanResourceFinalModel":"CertifiedbuilderHumanResourceModel"}}[{{$randomKey}}][IsPartnerOrOwner]" class="resetKeyForNew notclearfornew changeofowner" value="1"/>
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

	
			
			@if((bool)$isEdit)
				<div id="reasonforlocationchange">
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="LocationChangeReason" class="control-label">Reason for Change of Owner </label>
								<textarea name="LocationChangeReason" id="LocationChangeReason" class="form-control col-md-3" placeholder="Required only if you have applied for Change of Owner">{{$generalInfo->LocationChangeReason}}</textarea>
							</div>
							<br><br><br>
						</div>

					</div>
				</div>
			@endif
			<div class="form-actions">
				<div class="btn-set">
					@if((int)$serviceByCertifiedbuilder==1)
					<button type="submit" class="btn blue">Continue <i class="fa fa-arrow-circle-o-right"></i></button>
					<a href="{{URL::to('ezhotin/home/'.Session::get('UserViewerType'))}}" class="btn red">Cancel <i class="fa fa-times"></i></a>
					@else
					<button type="submit" class="btn blue">Update <i class="fa fa-arrow-circle-o-right"></i></button>
						@if(!empty($redirectUrl))
						<a href="{{URL::to($redirectUrl.'/'.$isEdit)}}" class="btn red">Cancel <i class="fa fa-times"></i></a>
						@else 
						<a href="{{URL::to('certifiedbuilder/confirmregistration')}}" class="btn red">Cancel <i class="fa fa-times"></i></a>
						@endif
					@endif
				</div>
			</div>
		</div>
	</div>
</div> 