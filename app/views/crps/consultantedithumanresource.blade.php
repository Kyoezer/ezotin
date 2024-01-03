@extends('master')
@section('pagescripts')
	@if(!empty($humanResourceEdit[0]->Id))
		{{ HTML::script('assets/global/scripts/consultant.js') }}
		<script>
			$('#addhumanresource').modal('show');
		</script>
	@endif
	@if((int)$serviceByConsultant==1)
		<script>
			@if(empty($humanResourceEdit[0]->Id))
				//$('#humanresourceservice').modal('show');
//				$('.addhumanresourcebutton').addClass('hide');
			@endif
		</script>
	@endif
@stop
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
{{ Form::open(array('url' => 'consultant/mconsultanthumanresource','role'=>'form','files'=>true))}}
<div id="addhumanresource" class="modal fade bs-modal-lg" role="dialog" aria-labelledby="addhumanresource" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
			<input type="hidden" value="1" name="EditByCdb">
			<input type="hidden" name="PostBackUrl" value="{{$redirectUrl}}">
			<input type="hidden" name="EditPage" value="{{$editPage}}">
			<input type="hidden" name="NewHumanResourceSave" value="{{$newHumanResourceSave}}">
			@if(isset($afterSaveRedirect) && (int)$afterSaveRedirect==1)
			<input type="hidden" name="HasCDBEdit" value="{{$afterSaveRedirect}}">
			@endif
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
            <h4 class="modal-title font-green-seagreen bold">Add Human Resource</h4>
          </div>
          @foreach($humanResourceEdit as $humanResourceEditRecord)
              <div class="modal-body">
              	<div class="row">
              		<div class="col-md-3">
						<div class="form-group">
							<input type="hidden" name="Id" value="{{$humanResourceEditRecord->Id}}" />
							<input type="hidden" name="CrpConsultantId" value="{{$consultantId}}" />
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
							<label class="control-label">CID/Work Permit No.</label>
							<input type="text" name="CIDNo" value="@if(!empty($humanResourceEditRecord->CIDNo)){{$humanResourceEditRecord->CIDNo}}@else{{Input::old('CIDNo')}}@endif" class="form-control input-sm required cidforwebservicemodal" placeholder="CID/Work Permit No.">
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
							<select name="Sex" id="" class="form-control input-sm sexfromwebservice required">
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
							<label class="control-label">Country</label>
							<select name="CmnCountryId" id="" class="form-control input-sm countryfromwebservice required">
								<option value="">---SELECT ONE---</option>
								@foreach($countries as $country)
									<option value="{{$country->Id}}" @if($country->Id==Input::old('CmnCountryId') || $country->Id==$humanResourceEditRecord->CmnCountryId || $country->Name=="Bhutan")selected="selected"@endif>{{$country->Name}}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label class="control-label">Designation</label>
							<select name="CmnDesignationId" id="" class="form-control input-sm required">
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
							<select name="CmnQualificationId" id="" class="form-control input-sm required">
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
							<select name="CmnTradeId" id="" class="form-control input-sm required">
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
							{{Form::hidden('Model','ConsultantHumanResourceAttachmentFinalModel',array('class'=>'delete-model'))}}
							<thead>
								<tr>
									<th>Document Name</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								@foreach($humanResourceEditAttachments as $humanResourceEditAttachment)
								<tr>
									{{Form::hidden('Id',$humanResourceEditAttachment->Id,array('class'=>'rowreference'))}}
									<td>
										<a href="{{URL::to($humanResourceEditAttachment->DocumentPath)}}" target="_blank" class="editaction">{{$humanResourceEditAttachment->DocumentName}}</a>
									</td>
									<td>
										<a href="#" class="deletedbrow">Delete</a>
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
						<table id="consultanthumanresource" class="table table-bordered table-striped table-condensed">
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
										<input type="text" name="DocumentName[]" class="form-control input-sm @if(empty($humanResourceEdit[0]->Id)){{'required'}}@endif">
									</td>
									<td>
										<input type="file" name="attachments[]" class="input-sm @if(empty($humanResourceEdit[0]->Id)){{'required'}}@endif" multiple="multiple" />
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
              <div class="modal-footer">
                <button type="submit" class="btn green">Save</button>
				<button type="button" class="btn red" data-dismiss="modal" aria-hidden="true">Cancel</button>
              </div>
        </div>
    </div>
</div>
@if((int)$serviceByConsultant==1)
	<input type="hidden" name="ServiceByConsultant" value="{{$serviceByConsultant}}">
@endif
{{Form::close()}}
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-cogs"></i>Human Resource
		</div>

	</div>
	<div class="portlet-body">
		<div class="table-toolbar">
            <div class="row">
                <div class="col-md-6">
                    <div class="btn-group addhumanresourcebutton">
                        <a href="#addhumanresource" data-toggle="modal" class="btn green btn-sm"> <i class="fa fa-plus"></i> Add Human Resource</a>
                    </div>
                </div>
            </div>
        </div>
        @if((int)$serviceByConsultant==1)
		    <div class="table-responsive">
		    	<h5 class="font-blue-madison bold">Existing Human Resource</h5>
				<table class="table table-bordered table-striped table-condensed flip-content">
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
							<th scope="col">
								 Qualification
							</th>
							<th scope="col" class="">
								 Designation
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
							<th>
								Delete Request
							</th>
							<th scope="col">
								Action
							</th>
						</tr>
					</thead>
					<tbody>
						@forelse($consultantHumanResourcesFinal as $consultantHumanResourceFinal)
						<tr>
							<td>{{$consultantHumanResourceFinal->Salutation.' '.$consultantHumanResourceFinal->Name}}</td>
							<td>{{$consultantHumanResourceFinal->CIDNo}}</td>
							<td>{{$consultantHumanResourceFinal->Sex}}</td>
							<td>{{$consultantHumanResourceFinal->Country}}</td>
							<td>{{$consultantHumanResourceFinal->Qualification}}</td>
							<td>{{$consultantHumanResourceFinal->Designation}}</td>
							
							<td>{{$consultantHumanResourceFinal->JoiningDate}}</td>
							<td>{{$consultantHumanResourceFinal->Trade}}</td>
							<td>
								@foreach($humanResourcesAttachmentsFinal as $humanResourcesAttachmentFinal)
								@if($consultantHumanResourceFinal->Id==$humanResourcesAttachmentFinal->CrpConsultantHumanResourceFinalId)
								<i class="fa fa-check"></i> <a href="{{URL::to($humanResourcesAttachmentFinal->DocumentPath)}}" target="_blank">{{$humanResourcesAttachmentFinal->DocumentName}}</a><br />
								@endif
								@endforeach
							</td>
							</td>
							<td>
								<center><input type="checkbox" class="senddeleterequest" data-type="1" value="{{$consultantHumanResourceFinal->Id}}"></center>
							</td>
							{{--@if((int)$serviceByContractor==0)--}}
							<td>
								@if((bool)$isEdit!=null)
									@if(!empty($isEditByCDBUser))
										<a href="{{URL::to($humanResourceEditRoute.'/'.$isEdit.'?humanresourceid='.$consultantHumanResourceFinal->Id.'&redirectUrl='.$redirectUrl.'&usercdb=edit')}}" class="editaction">Edit</a>
									@else
										@if($consultantHumanResourceFinal->DesignationReference != 101)
											<a href="{{URL::to($humanResourceEditRoute.'/'.$isEdit.'?humanresourceid='.$consultantHumanResourceFinal->Id.'&redirectUrl='.$redirectUrl)}}" class="editaction">Edit</a>
										@endif
									@endif
									{{--<a href="#" class="deletedbrow">--}}
									{{--Delete--}}
									{{--</a>--}}
								@endif
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
		    @endif
		<div class="table-responsive">
		<h5 class="font-blue-madison bold">Human Resource</h5>
		<table class="table table-bordered table-striped table-condensed flip-content">
			<?php if(!isset($changeModel)): $changeModel = false; endif; ?>
			{{Form::hidden('Model',$changeModel?'ConsultantHumanResourceFinalModel':'ConsultantHumanResourceModel',array('class'=>'delete-model'))}}
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
					<th scope="col">
						 Qualification
					</th>
					<th scope="col" class="">
						 Designation
					</th>
					
					<th>Joining Date</th>
					<th scope="col" class="">
						Trade/Fields
					</th>
					<th>
						Attachments(CV/UT/AT)
					</th>
{{--					@if((int)$serviceByConsultant==0)--}}
					<th scope="col">
						Action
					</th>
					{{--@endif--}}
				</tr>
			</thead>
			<tbody>
				@forelse($consultantHumanResources as $consultantHumanResource)
				<tr>
					<td>
						{{Form::hidden('Id',$consultantHumanResource->Id,array('class'=>'rowreference'))}}
						{{$consultantHumanResource->Name}}
					</td>
					<td>{{$consultantHumanResource->CIDNo}}</td>
					<td>{{$consultantHumanResource->Sex}}</td>
					<td>{{$consultantHumanResource->Country}}</td>
					<td>{{$consultantHumanResource->Qualification}}</td>
					<td>{{$consultantHumanResource->Designation}}</td>
					
					<td>{{convertDateToClientFormat($consultantHumanResource->JoiningDate)}}</td>
					<td>{{$consultantHumanResource->Trade}}</td>
					<td>
						@foreach($humanResourcesAttachments as $humanResourcesAttachment)
						@if($consultantHumanResource->Id==$humanResourcesAttachment->CrpConsultantHumanResourceId)
							<i class="fa fa-check"></i> <a href="{{URL::to($humanResourcesAttachment->DocumentPath)}}" target="_blank">{{$humanResourcesAttachment->DocumentName}}</a><br />
						@endif
						@endforeach
					</td>
{{--					@if((int)$serviceByConsultant==0)--}}
					<td>
						<input type="hidden" value="{{$consultantHumanResource->Id}}" class="recordreference">
						{{Form::hidden('Model',"ConsultantHumanResourceFinalModel",array('class'=>'recordreferencemodel'))}}
						@if((bool)$isEdit!=null)
{{--							@if(!empty($isEditByCDBUser))--}}
						@if($isAdmin)
							<a href="{{URL::to($humanResourceEditRoute.'/'.$isEdit.'?humanresourceid='.$consultantHumanResource->Id.'&redirectUrl='.$redirectUrl.'&usercdb=edit')}}" class="editaction">Edit</a>&nbsp;|&nbsp;
						@else
							<a href="{{URL::to($humanResourceEditRoute.'/'.$isEdit.'?humanresourceid='.$consultantHumanResource->Id.'&redirectUrl='.$redirectUrl.'&usercdb=edit&initial=true')}}" class="editaction">Edit</a>&nbsp;|&nbsp;
						@endif
							{{--@else--}}
							{{--<a href="{{URL::to($humanResourceEditRoute.'/'.$isEdit.'?humanresourceid='.$consultantHumanResource->Id.'&redirectUrl='.$redirectUrl)}}" class="editaction">Edit</a>&nbsp;|&nbsp;--}}
							{{--@endif--}}
							<a href="#" class="deletedbrow">
								Delete
							</a>
						@endif
					</td>
					{{--@endif--}}
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
			@if(isset($serviceAvailCancel) && (int)$serviceAvailCancel==1)
			<a href="{{URL::to($nextUrl.$consultantId)}}" class="btn blue button-next">
				Done <i class="m-icon-swapright m-icon-white"></i>
			</a>
			@else
			<a href="{{URL::to($redirectUrl.'/'.$consultantId)}}" class="btn blue button-next">
				Done <i class="m-icon-swapright m-icon-white"></i>
			</a>
			@endif
		</div>
	</div>
</div>
@stop