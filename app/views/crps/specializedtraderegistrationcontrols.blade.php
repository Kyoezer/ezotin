@if(isset($isRejectedApp) && (int)$isRejectedApp==1)
<input type="hidden" value="1" name="ApplicationRejectedReapply">
@endif
 <!-- BEGIN FORM-->
<div class="form-body">
	@foreach($specializedtradeRegistrations as $SpecializedTradeRegistration)
	<div class="row">
		<div class="col-md-9">
			<div class="form-group">
				<input type="hidden" value="{{$SpecializedTradeRegistration->Id}}" name="Id" />
				<input type="hidden" value="{{$newGeneralInfoSave}}" name="NewGeneralInfoSave">
				@if((int)$isServiceBySpecializedTrade==1)
				<input type="hidden" value="{{$specializedTradeFinalTableId}}" name="CrpSpecializedTradeId" />
				@endif
				<label class="bold">Application No.</label>
				<p class="form-control-static">
					@if(!empty($SpecializedTradeRegistration->ReferenceNo) || !empty($applicationReferenceNo))
						@if(!empty($SpecializedTradeRegistration->ReferenceNo))
							{{$SpecializedTradeRegistration->ReferenceNo}}
						@else
							{{$applicationReferenceNo}}
						@endif
					@else
						{{Input::old('ReferenceNo')}}
					@endif
				</p>
				<input type="hidden" name="ReferenceNo" value="@if(!empty($SpecializedTradeRegistration->ReferenceNo) || !empty($applicationReferenceNo))@if(!empty($SpecializedTradeRegistration->ReferenceNo)){{$SpecializedTradeRegistration->ReferenceNo}}@else{{$applicationReferenceNo}}@endif@else{{Input::old('ReferenceNo')}}@endif" />
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				<label class="bold">Application Date</label>
				<p class="form-control-static">@if(!empty($SpecializedTradeRegistration->ApplicationDate)){{convertDateToClientFormat($SpecializedTradeRegistration->ApplicationDate)}}@else{{convertDateToClientFormat(date('Y-m-d G:i:s'))}}@endif</p>
				<input type="hidden" name="ApplicationDate" value="@if(!empty($SpecializedTradeRegistration->ApplicationDate)){{$SpecializedTradeRegistration->ApplicationDate}}@else{{date('Y-m-d G:i:s')}}@endif" />
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-8">
			<div class="row">
				<div class="col-md-12"><h5 class="font-blue-madison bold">Registration Detail</h5></div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="CIDNo">CID No.<span class="font-red">*</span></label>				
						<input type="text" name="CIDNo" value="@if(!empty($SpecializedTradeRegistration->CIDNo)){{$SpecializedTradeRegistration->CIDNo}}@else{{Input::old('CIDNo')}}@endif" class="form-control input-sm required cidforwebservice" />
					</div>
					<div class="form-group">	
						<label for="CmnSalutationId">Salutation<span class="font-red">*</span></label>
						<select name="CmnSalutationId" id="" class="form-control input-sm required">
							<option value="">---SELECT ONE---</option>
							@foreach($salutations as $salutation)
							<option value="{{$salutation->Id}}" @if($salutation->Id==Input::old('CmnSalutationId') || $salutation->Id==$SpecializedTradeRegistration->CmnSalutationId)selected="selected"@endif>{{$salutation->Name}}</option>
							@endforeach
						</select>
					</div>
					<div class="form-group">
						<label for="CmnSalutationId">Name <span class="font-red">*</span></label>
						<input type="text" name="Name" value="@if(!empty($SpecializedTradeRegistration->Name)){{$SpecializedTradeRegistration->Name}}@else{{Input::old('Name')}}@endif" class="form-control input-sm required namefromwebservice" placeholder="">
					</div>
					<div class="form-group">	
						<label for="CmnDzongkhagId">Dzongkhag <span class="font-red">*</span></label>			
						<select name="CmnDzongkhagId" class="form-control input-sm required dzongkhagfromwebservice">
							<option value="">---SELECT ONE---</option>
							@foreach($dzongkhags as $dzongkhag)
								<option value="{{$dzongkhag->Id}}" @if($dzongkhag->Id==Input::old('CmnDzongkhagId') || $dzongkhag->Id==$SpecializedTradeRegistration->CmnDzongkhagId)selected="selected"@endif>{{$dzongkhag->NameEn}}</option>
							@endforeach
						</select>
					</div>
					<div class="form-group">
						<label for="Gewog">Gewog <span class="font-red">*</span></label>
						<input type="text" name="Gewog" value="@if(!empty($SpecializedTradeRegistration->Gewog)){{$SpecializedTradeRegistration->Gewog}}@else{{Input::old('Gewog')}}@endif" class="form-control input-sm required gewogfromwebservice" />
					</div>
					<div class="form-group">	
						<label for="">Village <span class="font-red">*</span></label>
						<input type="text" name="Village" value="@if(!empty($SpecializedTradeRegistration->Village)){{$SpecializedTradeRegistration->Village}}@else{{Input::old('Village')}}@endif" class="form-control input-sm required villagefromwebservice" />
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="Email">Email <span class="font-red">*</span><a role="button" tabindex="0" data-toggle="popover" data-container="body" data-trigger="focus" class="popoverdefaultopen" data-placement="top" title="<span class='font-green-seagreen'>Email</span>" data-content="<p class='text-justify'>This Email address will be used as username for your account and to receive email from Construction Development Board (CDB). Please provide a valid Email address inorder not to lose important messages from CDB.</p>"><i class="fa fa-question-circle"></i></a></label>
						<input type="text" name="Email" value="@if(!empty($SpecializedTradeRegistration->Email)){{$SpecializedTradeRegistration->Email}}@else{{Input::old('Email')}}@endif" class="form-control input-sm required" />
					</div>
					<div class="form-group">	
						<label for="MobileNo">Mobile No. <span class="font-red">*</span><a role="button" tabindex="0" data-toggle="popover" data-container="body" data-trigger="focus" class="popoverdefaultopen" data-placement="top" title="<span class='font-green-seagreen'>Mobile No.</span>" data-content="<p class='text-justify'>This Mobile No. will be used to receive sms alerts from Construction Development Board (CDB). Please provide a valid Mobile No. registered with Tashi Cell/B-Mobile inorder not to lose important messages from CDB.</p>"><i class="fa fa-question-circle"></i></a></label>
						<input type="text" name="MobileNo" value="@if(!empty($SpecializedTradeRegistration->MobileNo)){{$SpecializedTradeRegistration->MobileNo}}@else{{Input::old('MobileNo')}}@endif" class="form-control input-sm required" />
					</div>
					<div class="form-group">	
						<label for="MobileNo">Telephone No. </label>
						<input type="text" name="TelephoneNo" value="@if(!empty($SpecializedTradeRegistration->TelephoneNo)){{$SpecializedTradeRegistration->TelephoneNo}}@else{{Input::old('TelephoneNo')}}@endif" class="form-control input-sm" />
					</div>
					<div class="form-group">	
						<label for="EmployerName">Employer Name</label>
						<input type="text" name="EmployerName" value="@if(!empty($SpecializedTradeRegistration->EmployerName)){{$SpecializedTradeRegistration->EmployerName}}@else{{Input::old('EmployerName')}}@endif" class="form-control input-sm" />
					</div>
					<div class="form-group">	
						<label for="EmployerAddress">Employer Address</label>
						<input type="text" name="EmployerAddress" value="@if(!empty($SpecializedTradeRegistration->EmployerAddress)){{$SpecializedTradeRegistration->EmployerAddress}}@else{{Input::old('EmployerAddress')}}@endif" class="form-control input-sm">
					</div>
					<div class="form-group">
						<label for="TPN">TPN</label>
						<input type="text" name="TPN" value="@if(!empty($SpecializedTradeRegistration->TPN)){{$SpecializedTradeRegistration->TPN}}@else{{Input::old('TPN')}}@endif" class="form-control input-sm">
					</div>
				</div>
			</div>
		</div>
		@endforeach
		@if(empty($editWorkClassificationsByCDB))
		<div class="col-md-4">
			<h5 class="font-blue-madison bold">Category Information</h5>
			<label class="">Please tick the checkbox to select a category</label>				
			<table class="table table-bordered table-striped table-condensed">
				<thead>
					<tr>
						<th width="5%" class="table-checkbox"></th>
						<th>Category</th>
					</tr>
				</thead>
				<tbody>
					@foreach($categories as $category)
						<tr>
							<td>
								<input type="checkbox" class="tablerowcheckbox" value="1" @if($category->CategoryId==$category->CmnAppliedCategoryId)checked="checked"@endif/>
								<input type="hidden" name="CmnAppliedCategoryId[]" value="{{$category->CategoryId}}" class="tablerowcontrol" @if($category->CategoryId!=$category->CmnAppliedCategoryId)disabled="disabled"@endif/>
							</td>
							<td>
								{{$category->Code.'-'.$category->Name}}
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
		@else 
		<div class="col-md-4 table-responsive">
			<h5 class="font-blue-madison bold">Edit Category Information</h5>
			<input type="hidden" name="CmnEditCategoryByCDB" value="1">
			<table class="table table-bordered table-striped table-condensed">
				<thead>
					<tr>
						<th width="5%" class="table-checkbox"></th>
						<th>Category</th>
					</tr>
				</thead>
				<tbody>
					@foreach($editWorkClassificationsByCDB as $editWorkClassificationByCDB)
						<tr>
							<td>
								<input type="checkbox" class="tablerowcheckbox" value="1" @if($editWorkClassificationByCDB->CategoryId==$editWorkClassificationByCDB->CmnApprovedCategoryId)checked="checked"@endif/>
								<input type="hidden" name="CmnAppliedCategoryId[]" value="{{$editWorkClassificationByCDB->CategoryId}}" class="tablerowcontrol" @if($editWorkClassificationByCDB->CategoryId!=$editWorkClassificationByCDB->CmnApprovedCategoryId)disabled="disabled"@endif/>
								<input type="hidden" name="CmnVerifiedCategoryId[]" value="{{$editWorkClassificationByCDB->CategoryId}}" class="tablerowcontrol" @if($editWorkClassificationByCDB->CategoryId!=$editWorkClassificationByCDB->CmnApprovedCategoryId)disabled="disabled"@endif/>
								<input type="hidden" name="CmnApprovedCategoryId[]" value="{{$editWorkClassificationByCDB->CategoryId}}" class="tablerowcontrol" @if($editWorkClassificationByCDB->CategoryId!=$editWorkClassificationByCDB->CmnApprovedCategoryId)disabled="disabled"@endif/>
							</td>
							<td>
								{{$editWorkClassificationByCDB->Code.'-'.$editWorkClassificationByCDB->Name}}
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
		@endif
	</div>
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
					@forelse($oldUploads as $oldUpload)
					<tr>
						<td>
							{{$oldUpload->DocumentName}}
						</td>
						<td>
							<a href="{{URL::to($oldUpload->DocumentPath)}}" target="_blank">View</a>|
							<a href="#" class="">Delete</a>
						</td>
					</tr>
					@empty
					<tr>
						<td colspan="2">No new documents attached</td>
					</tr>
					@endforelse
				</tbody>
			</table>
		</div>
		@endif
		@if(!empty($specializedtradeRegistrationAttachments))
		<div class="col-md-6 table-responsive">
			<h5 class="font-blue-madison bold">Uploaded Files</h5>
			<table class="table table-bordered table-striped table-condensed">
				<thead>
					<tr>
						<th>Document Name</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					@forelse($specializedtradeRegistrationAttachments as $specializedtradeRegistrationAttachment)
					<tr>
						<td>
							{{$specializedtradeRegistrationAttachment->DocumentName}}
						</td>
						<td>
							<a href="{{URL::to($specializedtradeRegistrationAttachment->DocumentPath)}}" target="_blank">View</a> |
							<a href="#" class="">Delete</a>
						</td>
					</tr>
					@empty
					<tr>
						<td colspan="2" class="font-red text-center">No data to display</td>
					</tr>
					@endforelse
				</tbody>
			</table>
		</div>
		@endif
		<div class="col-md-6 table-responsive">
			<h5 class="font-blue-madison bold">Attach Academic Certificate and other relevant documents if any.</h5>
			<table id="engineerattachments" class="table table-bordered table-striped table-condensed">
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
							<input type="text" name="DocumentName[]" value="Document" class="form-control input-sm @if(empty($specializedtradeRegistrations[0]->Id) && $isServiceBySpecializedTrade==0){{'required'}}@endif">
						</td>
						<td>
							<input type="file" name="attachments[]" class="input-sm @if(empty($specializedtradeRegistrations[0]->Id) && $isServiceBySpecializedTrade==0){{'required'}}@endif" multiple="multiple" />
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
		@if((bool)$specializedTradeId==null)
		<button type="submit" class="btn green">Register <i class="fa fa-arrow-circle-o-right"></i></button>
		<a href="{{URL::to('ezhotin/home/'.Session::get('UserViewerType'))}}" class="btn red">Cancel <i class="fa fa-times"></i></a>
		@else
		<button type="submit" class="btn green">Update <i class="fa fa-arrow-circle-o-right"></i></button>
			@if(!empty($redirectUrl))
			<a href="{{URL::to($redirectUrl.'/'.$specializedTradeId)}}" class="btn red">Cancel <i class="fa fa-times"></i></a>
			@else
			<a href="{{URL::to('specializedtrade/confirmregistration')}}" class="btn red">Cancel <i class="fa fa-times"></i></a>
			@endif
		@endif
	</div>
</div>
<!-- END FORM-->
