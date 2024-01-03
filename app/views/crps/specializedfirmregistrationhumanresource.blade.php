@extends('homepagemaster')
@if(!empty($humanResourceEdit[0]->Id))
@section('pagescripts')
	<script>
		$('#addhumanresource').modal('show');
	</script>
@stop
@endif
@section('content')
	<div id="hrcheckmodal" class="modal fade in" role="dialog" aria-labelledby="modalmessagebox" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
					<h3 id="modalmessageheader" class="modal-title font-red-intense">HR Check</h3>
				</div>
				<div class="modal-body">
				</div>
			</div>
		</div>
	</div>

	<div id="eqcheckmodal" class="modal fade in" role="dialog" aria-labelledby="modalmessagebox" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
					<h3 id="modalmessageheader" class="modal-title font-red-intense">Equipment Check</h3>
				</div>
				<div class="modal-body">
				</div>
			</div>
		</div>
	</div>
<div id="addhumanresource" class="modal fade bs-modal-lg" role="dialog" aria-labelledby="addhumanresource" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        	{{ Form::open(array('url' => 'specializedfirm/mspecializedtradehumanresource','role'=>'form','files'=>true))}}
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title font-green-seagreen bold">Add Human Resource</h4>
              </div>
              @foreach($humanResourceEdit as $humanResourceEditRecord)
              <div class="modal-body">
              	<div class="row">
              		<div class="col-md-3">
						<div class="form-group">
							<label class="control-label">CID/Work Permit No.</label>
							<input type="text" name="CIDNo" value="@if(!empty($humanResourceEditRecord->CIDNo)){{$humanResourceEditRecord->CIDNo}}@else{{Input::old('CIDNo')}}@endif" class="form-control input-sm required cidforwebservicemodal {{--check-hr-registration--}}" placeholder="CID/Work Permit No.">
						</div>
					</div>
              		<div class="col-md-3">
						<div class="form-group">
							<input type="hidden" name="Id" value="{{$humanResourceEditRecord->Id}}" />
							<input type="hidden" name="CrpSpecializedtradeId" value="{{$specializedtradeId}}" />
							<label class="control-label">Salutation</label>
							<select name="CmnSalutationId" id="" class="form-control input-sm required">
								<option value="">---SELECT---</option>
								@foreach($salutations as $salutation)
								<option value="{{$salutation->Id}}" @if($salutation->Id==Input::old('CmnSalutationId') || $salutation->Id==$humanResourceEditRecord->CmnSalutationId)selected="selected"@endif>{{$salutation->Name}}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label class="control-label">Name</label>
							<input type="text" name="Name" value="@if(!empty($humanResourceEditRecord->Name)){{$humanResourceEditRecord->Name}}@else{{Input::old('Name')}}@endif" class="form-control input-sm required namefromwebservice" placeholder="Name">
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label class="control-label">Sex</label>
							<select name="Sex" id="" class="form-control input-sm required sexfromwebservice">
								<option value="">---SELECT ONE---</option>
								<option value="M"  @if($humanResourceEditRecord->Sex=="M")selected="selected"@endif>Male</option>
								<option value="F"  @if($humanResourceEditRecord->Sex=="F")selected="selected"@endif>Female</option>
							</select>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-3">
						<div class="form-group">
							<label class="control-label">Nationality</label>
							<select name="CmnCountryId" id="" class="form-control input-sm select2me required countryfromwebservice">
								<option value="">---SELECT ONE---</option>
								@foreach($countries as $country)
									<option value="{{$country->Id}}" @if($country->Id==Input::old('CmnCountryId') || $country->Id==$humanResourceEditRecord->CmnCountryId || $country->Nationality=="Bhutanese")selected="selected"@endif>{{$country->Nationality}}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label class="control-label">Designation</label>
							<select name="CmnDesignationId" id="" class="form-control input-sm select2me required">
								<option value="">---SELECT ONE---</option>
								@foreach($designations as $designation)
									<option value="{{$designation->Id}}" @if($designation->Id==Input::old('CmnDesignationId') || $designation->Id==$humanResourceEditRecord->CmnDesignationId)selected="selected"@endif>{{$designation->Name}}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label class="control-label">Qualification</label>
							<select name="CmnQualificationId" id="" class="form-control input-sm select2me required">
								<option value="">---SELECT ONE---</option>
								@foreach($qualifications as $qualification)
								<option value="{{$qualification->Id}}" @if($qualification->Id==Input::old('CmnQualificationId') || $qualification->Id==$humanResourceEditRecord->CmnQualificationId)selected="selected"@endif>{{$qualification->Name}}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label class="control-label">Trade/Field</label>
							<select name="CmnTradeId" id="" class="form-control input-sm select2me required">
								<option value="">---SELECT ONE---</option>
								@foreach($trades as $trade)
									<option value="{{$trade->Id}}" @if($trade->Id==Input::old('CmnTradeId') || $trade->Id==$humanResourceEditRecord->CmnTradeId)selected="selected"@endif>{{$trade->Name}}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label class="control-label">Service Type</label>
							<select name="CmnServiceTypeId" id="" class="form-control input-sm required">
								<option value="">---SELECT ONE---</option>
								@foreach($serviceTypes as $serviceType)
									<option value="{{$serviceType->Id}}" @if($serviceType->Id==Input::old('CmnServiceTypeId') || $serviceType->Id==$humanResourceEditRecord->CmnServiceTypeId)selected="selected"@endif>{{$serviceType->Name}}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label class="control-label">Joining Date</label>
							<input type="text" name="JoiningDate" value="@if(!empty($humanResourceEditRecord->JoiningDate)){{convertDateToClientFormat($humanResourceEditRecord->JoiningDate)}}@else{{convertDateToClientFormat(Input::old('JoiningDate'))}}@endif" class="form-control datepicker input-sm required" placeholder="Joining Date">
						</div>
					</div>
				</div>
				@endforeach
				@if(!empty($humanResourceEditAttachments))
				<div class="row">
					<div class="col-md-12 table-responsive">
						<h5 class="font-blue-madison bold">List of Uploaded Documents</h5>
						<table id="" class="table table-bordered table-striped table-condensed">
							<thead>
								<tr>
									<th>Document Name</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								@foreach($humanResourceEditAttachments as $humanResourceEditAttachment)
								<tr>
									<td>
										<a href="{{URL::to($humanResourceEditAttachment->DocumentPath)}}" class="editaction">{{$humanResourceEditAttachment->DocumentName}}</a>
									</td>
									<td>
										<a href="#" class="deleteaction">Delete</a>
									</td>
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
				@endif
				<div class="row">
					<div class="col-md-12 table-responsive">
						<h5 class="font-blue-madison bold">Attach Documents (CV/Undertaking/Certificate/CID/ Work Permit)</h5>
						<table id="specializedtradehumanresource" class="table table-bordered table-striped table-condensed">
							<thead>
								<tr>
									<th></th>
									<th>Document Name</th>
									<th>Upload File</th>
								</tr>
							</thead>
							<tbody>
								<tr>

									<td></td>
									<td>
										<input type="text" name="DocumentName[]" value="CID Copy" class="form-control input-sm">
									</td>
									<td>
										<input type="file" name="attachments[]" class="input-sm" multiple="multiple" />
									</td>
								</tr>
								<tr>

									<td></td>
									<td>
										<input type="text" name="DocumentName[]" value="Undertaking" class="form-control input-sm">
									</td>
									<td>
										<input type="file" name="attachments[]" class="input-sm" multiple="multiple" />
									</td>
								</tr>
								<tr>
									<td></td>
									<td>
										<input type="text" name="DocumentName[]" value="Academic Transcripts" class="form-control input-sm">
									</td>
									<td>
										<input type="file" name="attachments[]" class="input-sm" multiple="multiple" />
									</td>
								</tr>
								<tr>

									<td>
										<button type="button" class="deletetablerow"><i class="fa fa-times fa-lg"></i></button>
									</td>
									<td>
										<input type="text" name="DocumentName[]" value="Additional Document" class="form-control input-sm">
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
								{{--END--}}
								{{--<tr>--}}
									{{--<td>--}}
										{{--<button type="button" class="deletetablerow"><i class="fa fa-times fa-lg"></i></button>--}}
									{{--</td>--}}
									{{--<td>--}}
										{{--<input type="text" name="DocumentName[]" value="HR Document" class="form-control input-sm @if(empty($humanResourceEdit[0]->Id)){{'required'}}@endif">--}}
									{{--</td>--}}
									{{--<td>--}}
										{{--<input type="file" name="attachments[]" class="input-sm @if(empty($humanResourceEdit[0]->Id)){{'required'}}@endif" multiple="multiple" />--}}
									{{--</td>--}}
								{{--</tr>--}}
								{{--<tr class="notremovefornew">--}}
									{{--<td>--}}
										{{--<button type="button" class="addtablerow"><i class="fa fa-plus fa-lg"></i></button>--}}
									{{--</td>--}}
									{{--<td colspan="2"></td>--}}
								{{--</tr>--}}
							</tbody>
						</table>
					</div>
				</div>
              </div>
              <div class="modal-footer">
                <button type="submit" class="btn green">Save</button>
				<button type="button" class="btn red" data-dismiss="modal" aria-hidden="true">Cancel</button>
              </div>
              {{Form::close()}}
        </div>
    </div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="portlet light bordered" id="form_wizard_1">
			<div class="portlet-title">
				<div class="caption font-green-seagreen">
					<span class="caption-subject">Specializedfirm Registration (New Application) </span> - <span class="step-title">Step 3 of 4 </span>
				</div>
			</div>
			<div class="portlet-body form">
				<div class="form-wizard">
					<div class="form-body">
						<ul class="nav nav-pills nav-justified steps">
							<li class="done">
								<a href="#tab1" data-toggle="tab" class="step">
								<span class="number">
								1 </span>
								<span class="desc">
								<i class="fa fa-check"></i> General Information </span>
								</a>
							</li>
							<li class="done">
								<a href="#tab2" data-toggle="tab" class="step">
								<span class="number">
								2 </span>
								<span class="desc">
								<i class="fa fa-check"></i> Work Classification </span>
								</a>
							</li>
							<li class="active">
								<a href="#tab3" data-toggle="tab" class="step">
								<span class="number">
								3 </span>
								<span class="desc">
								<i class="fa fa-check"></i> Human Resource </span>
								</a>
							</li>
							<li>
								<a href="#tab4" data-toggle="tab" class="step">
								<span class="number">
								4 </span>
								<span class="desc">
								<i class="fa fa-check"></i> Equipment </span>
								</a>
							</li>
						</ul>
						<div class="portlet box blue-madison">
							<div class="portlet-title">
								<div class="caption">
									<i class="fa fa-check"></i>Requirements for different Category and Classification
								</div>
							</div>
							<div class="portlet-body">
								<p class="bold font-red">*Scroll to View More</p>
								<div class="scroller" style="height: 280px;">
						            <div class="row">
										<div class="col-md-4">
											<div class="table-responsive">
												<table class="table table-bordered table-striped table-condensed">
													<thead class="">
														<tr>
															<th class="bold">Note</th>
														</tr>
													</thead>
													<tbody>
														
														<tr>
															<td>
																CID/ Work Permit copy for all Personnel including his/her academic transcripts(For engineers Provisional Certificate will not be accepted if the engineer has graduated more than six months ago) & undertaking letter with legal stamp.
															</td>
														</tr>
													</tbody>
												</table>
											</div>
										</div>
										
									
									</div>
								</div>
							</div>	
						</div>		
						 <div class="table-toolbar">
		                    <div class="row">
		                        <div class="col-md-6">
		                            <div class="btn-group">
		                                <a href="#addhumanresource" data-toggle="modal" class="btn green btn-sm"> <i class="fa fa-plus"></i> Add Human Resource</a>
		                            </div>
		                        </div>
		                    </div>
		                </div>
						<div class="table-scrollable">
							<table class="table table-bordered table-striped table-condensed flip-content">
								{{Form::hidden('Model','SpecializedfirmHumanResourceModel',array('class'=>'delete-model'))}}
								<thead>
									<tr>
										<th scope="col">
											 Name
										</th>
										<th scope="col">
											 CID/Work Permit No.
										</th>
										<th scope="col" width="5%">
											 Sex
										</th>
										<th scope="col" class="">
											 Country
										</th>
										<th scope="col" class="">
											Designation
										</th>
										<th scope="col">
											 Qualification
										</th>
										<th>
											Service Type
										</th>
										<th scope="col" class="">
											Joining Date
										</th>
										<th scope="col" class="">
											Trade/Fields
										</th>
										<th>
											Attachments(CV/UT/AT)
										</th>
										<th scope="col">
											Action
										</th>
									</tr>
								</thead>
								<tbody>
									@forelse($specializedtradeHumanResources as $specializedtradeHumanResource)
									<tr>
										{{Form::hidden('Id',$specializedtradeHumanResource->Id,array('class'=>'rowreference'))}}
										<td>{{$specializedtradeHumanResource->Salutation.' '.$specializedtradeHumanResource->Name}}</td>
										<td>{{$specializedtradeHumanResource->CIDNo}}</td>
										<td>{{$specializedtradeHumanResource->Sex}}</td>
										<td>{{$specializedtradeHumanResource->Country}}</td>
										<td>{{$specializedtradeHumanResource->Designation}}</td>
										<td>{{$specializedtradeHumanResource->Qualification}}</td>
										<td>{{$specializedtradeHumanResource->ServiceType}}</td>
										<td>{{convertDateToClientFormat($specializedtradeHumanResource->JoiningDate)}}</td>
										<td>{{$specializedtradeHumanResource->Trade}}</td>
										<td>
											@foreach($humanResourcesAttachments as $humanResourcesAttachment)
											@if($specializedtradeHumanResource->Id==$humanResourcesAttachment->CrpSpecializedtradeHumanResourceId)
												<i class="fa fa-check"></i> <a href="{{URL::to($humanResourcesAttachment->DocumentPath)}}" target="_blank" class="font-blue">{{$humanResourcesAttachment->DocumentName}}</a><br />
											@endif
											@endforeach
										</td>
										<td>
											@if((bool)$isEdit!=null)
											<a href="{{URL::to('specializedfirm/humanresourceregistration/'.$isEdit.'?humanresourceid='.$specializedtradeHumanResource->Id)}}" class="editaction">Edit</a>&nbsp;|&nbsp;
											@endif
											<a href="#" class="deletedbrow">
												Delete
											</a>
										</td>
									</tr>
									@empty
									<tr>
										<td class="font-red" colspan="11">Please click on Add Human Resource button to start adding your human resource.</td>
									</tr>
									@endforelse
								</tbody>
							</table>
						</div>
						<div class="form-actions">
							<div class="btn-set">
								<a href="{{URL::to('specializedfirm/equipmentregistration')}}" class="btn blue button-next">
									Continue <i class="m-icon-swapright m-icon-white"></i>
								</a>
								@if((bool)$isEdit!=null)
									<a href="{{URL::to('specializedfirm/confirmregistration')}}" class="btn blue button-next">
										Back to Confirmation <i class="m-icon-swapright m-icon-white"></i>
									</a>
								@endif
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@stop