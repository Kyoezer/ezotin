@extends('master')
@if(!empty($equipmentEdit[0]->Id))
@section('pagescripts')
	<script>
		$('#addequipments').modal('show');
	</script>
@stop
@endif
@if((int)$serviceByConsultant==1)
	@section('pagescripts')
		{{ HTML::script('assets/global/scripts/contractor.js') }}
		<script>
			//$('#equipmentservice').modal('show');
			//$('.addequipmentbutton').addClass('hide');
		</script>
	@stop
@endif
@section('content')
{{ Form::open(array('url' => 'consultant/mconsultantequipments','role'=>'form','files'=>true))}}
<input type="hidden" name="NewEquipmentSave" value="{{$newEquipmentSave}}">
@if(isset($afterSaveRedirect) && (int)$afterSaveRedirect==1)
<input type="hidden" name="HasCDBEdit" value="{{$afterSaveRedirect}}">
@endif
@if((int)$serviceByConsultant==1)
	<input type="hidden" name="ServiceByConsultant" value="{{$serviceByConsultant}}">
@endif
<div id="addequipments" class="modal fade bs-modal-lg" role="dialog" aria-labelledby="addequipments" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        		<input type="hidden" value="1" name="EditByCdb">
				<input type="hidden" name="PostBackUrl" value="{{$redirectUrl}}">
				<input type="hidden" name="EditPage" value="{{$editPage}}">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title font-green-seagreen bold">Add Equipment</h4>
              </div>
              @foreach($equipmentEdit as $equipmentEditRecord)
              <div class="modal-body">
              	<div class="row">
					<div class="col-md-12 hide" id="equipment-details">
						<h4>Equipment Details</h4>
						<b>Registered to:</b> <span id="equipment-owner"></span> <br>
						<b>Registration No.:</b> <span id="equipment-regno"></span> <br>
						<b>Vehicle Type:</b> <span id="equipment-vehicletype"></span> <br>
						<b>Region:</b> <span id="equipment-region"></span> <br> <br>
					</div>
              		<div class="col-md-3">
						<div class="form-group">
							<input type="hidden" name="Id" value="{{$equipmentEditRecord->Id}}" />
							<input type="hidden" name="CrpConsultantId" value="{{$consultantId}}" />
							<label class="control-label">Equipment</label>
							<select name="CmnEquipmentId" id="" class="form-control input-sm equipmentforwebservicemodal equipmentselectregisteredtype select2me">
								<option value="">---SELECT---</option>
								@foreach($equipments as $equipment)
								<option value="{{$equipment->Id}}" data-vehicletype="{{$equipment->VehicleType}}" data-registered="{{$equipment->IsRegistered}}" @if($equipment->Id==Input::old('CmnEquipmentId') || $equipment->Id==$equipmentEditRecord->CmnEquipmentId)selected="selected"@endif>{{$equipment->Name}}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label class="control-label">Registration No.</label>
							<input type="text" name="RegistrationNo" value="@if(!empty($equipmentEditRecord->RegistrationNo)){{$equipmentEditRecord->RegistrationNo}}@else{{Input::old('RegistrationNo')}}@endif" class="form-control regnoforwebservicemodal {{--checkeqdbandwebservicemodal--}} isregisteredequipment" placeholder="Registration No.">
						</div>
					</div>
					{{--<div class="col-md-3">--}}
						{{--<div class="form-group">--}}
							{{--<label class="control-label">Serial No.</label>--}}
							{{--<input type="text" name="SerialNo" value="@if(!empty($equipmentEditRecord->SerialNo)){{$equipmentEditRecord->SerialNo}}@else{{Input::old('SerialNo')}}@endif" class="form-control" placeholder="SerialNo">--}}
						{{--</div>--}}
					{{--</div>--}}
					{{--<div class="col-md-3">--}}
						{{--<div class="form-group">--}}
							{{--<label class="control-label">Model No.</label>--}}
							{{--<input type="text" name="ModelNo" value="@if(!empty($equipmentEditRecord->ModelNo)){{$equipmentEditRecord->ModelNo}}@else{{Input::old('ModelNo')}}@endif" class="form-control required" placeholder="ModelNo">--}}
						{{--</div>--}}
					{{--</div>--}}
				</div>
				{{--<div class="row">--}}
					{{--<div class="col-md-3">--}}
						{{--<div class="form-group">--}}
							{{--<label class="control-label">Quantity</label>--}}
							{{--<input type="text" name="Quantity" value="@if(!empty($equipmentEditRecord->Quantity)){{$equipmentEditRecord->Quantity}}@else{{Input::old('Quantity')}}@endif" class="form-control disableifregisteredequipment number" placeholder="Quantity">--}}
						{{--</div>--}}
					{{--</div>--}}
				{{--</div>--}}
				@endforeach
				@if(!empty($equipmentAttachments))
				<div class="row">
					<div class="col-md-12 table-responsive">
						<h5 class="font-blue-madison bold">List of Uploaded Documents</h5>
						<table id="consultanthumanresource" class="table table-bordered table-striped table-condensed">
							{{Form::hidden('Model','ConsultantEquipmentAttachmentFinalModel',array('class'=>'delete-model'))}}
							<thead>
								<tr>
									<th>Document Name</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								@foreach($equipmentAttachments as $equipmentAttachment)
								<tr>
									<input type="hidden" value="{{$equipmentAttachment->Id}}" class="rowreference"/>
									<td>
										<a href="{{URL::to($equipmentAttachment->DocumentPath)}}" target="_blank">{{$equipmentAttachment->DocumentName}}</a>
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
						<h5 class="font-blue-madison bold">Attach Documents</h5>
						<table id="consultantequipment" class="table table-bordered table-striped table-condensed">
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
										<input type="text" name="DocumentName[]" value="Equipment Document" class="form-control input-sm @if(empty($equipmentEdit[0]->Id)){{'required'}}@endif">
									</td>
									<td>
										<input type="file" name="attachments[]" class="input-sm @if(empty($equipmentEdit[0]->Id)){{'required'}}@endif" multiple="multiple" />
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
{{Form::close()}}
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-cogs"></i>Equipments
		</div>
	</div>
	<div class="portlet-body">
		<div class="table-toolbar">
            <div class="row">
                <div class="col-md-6">
                    <div class="btn-group addequipmentbutton">
                        <a href="#addequipments" data-toggle="modal" class="btn green btn-sm"> <i class="fa fa-plus"></i> Add Equipment</a>
                    </div>
                </div>
            </div>
        </div>
		<div class="table-responsive">
			@if((int)$serviceByConsultant==1)
			<h5 class="font-blue-madison bold">Existing Equipments</h5>
			<table id="Equipment" class="table table-bordered table-striped table-condensed flip-content">
				<thead>
					<tr>
						<th>
							Equipment Name
						</th>
						<th scope="col" class="">
							 Registration No
						</th>
						<th scope="col" class="">
							Equipment Model
						</th>
						<th>
							Quantity
						</th>
						<th>
							Attachment
						</th>
						<th>
							Delete Request
						</th>
						<th>
							Action
						</th>
					</tr>
				</thead>
				<tbody>
					@foreach($consultantEquipmentsFinal as $consultantEquipmentFinal)
					<?php $randomKey=randomString(); ?>
					<tr>
						<td>{{$consultantEquipmentFinal->Name}}</td>
						<td>{{$consultantEquipmentFinal->RegistrationNo}}</td>
						<td>{{$consultantEquipmentFinal->ModelNo}}</td>
						<td>{{$consultantEquipmentFinal->Quantity}}</td>
						<td>
							@foreach($consultantEquipmentAttachmentsFinal as $consultantEquipmentAttachmentFinal)
								@if($consultantEquipmentAttachmentFinal->CrpConsultantEquipmentFinalId==$consultantEquipmentFinal->Id)
								<i class="fa fa-check"></i><a href="{{URL::to($consultantEquipmentAttachmentFinal->DocumentPath)}}" target="_blank">{{$consultantEquipmentAttachmentFinal->DocumentName}}</a><br />
								@endif
							@endforeach
						</td>
						<td>
							<center><input type="checkbox" class="senddeleterequest" data-type="2" value="{{$consultantEquipmentFinal->Id}}"></center>
						</td>
						<td>
							<input type="hidden" value="{{$consultantEquipmentFinal->Id}}" class="recordreference"/>
							{{Form::hidden('Model','ConsultantEquipmentFinalModel',array('class'=>'recordreferencemodel'))}}
							@if((bool)$isEdit!=null)
								@if(!empty($isEditByCDBUser))
									<a href="{{URL::to($equipmentEditRoute.'/'.$isEdit.'?equipmentid='.$consultantEquipmentFinal->Id.'&redirectUrl='.$redirectUrl.'&usercdb=edit')}}" class="editaction">Edit</a>&nbsp;
								@else
									<a href="{{URL::to($equipmentEditRoute.'/'.$isEdit.'?equipmentid='.$consultantEquipmentFinal->Id.'&redirectUrl='.$redirectUrl)}}" class="editaction">Edit</a>&nbsp;
								@endif
							@endif
							{{--<a href="#" class="removerecorddb">--}}
								{{--Delete--}}
							{{--</a>--}}
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
			@endif
			<h5 class="font-blue-madison bold">Equipments</h5>
			<table class="table table-bordered table-striped table-condensed flip-content">
				<?php if(!isset($changeModel)): $changeModel = false; endif; ?>
				{{Form::hidden('Model',$changeModel?'ConsultantEquipmentFinalModel':'ConsultantEquipmentModel',array('class'=>'delete-model'))}}
				<thead class="flip-content">
					<tr>
						<th>
							Equipment Name
						</th>
						<th scope="col" class="">
							 Registration No
						</th>
						<th scope="col" class="">
							Equipment Model
						</th>
						<th>
							Quantity
						</th>
						<th>
							Attachment
						</th>
{{--						@if((int)$serviceByConsultant==0)--}}
						<th>
							Action
						</th>
						{{--@endif--}}
					</tr>
				</thead>
				<tbody>
					@forelse($consultantEquipments as $consultantEquipment)
					<tr>
						<td>
							{{Form::hidden('Id',$consultantEquipment->Id,array('class'=>'rowreference'))}}
							{{$consultantEquipment->Name}}
						</td>
						<td>{{$consultantEquipment->RegistrationNo}}</td>
						<td>{{$consultantEquipment->ModelNo}}</td>
						<td>
							@if((int)$consultantEquipment->Quantity!=0)
							{{$consultantEquipment->Quantity}}
							@endif
						</td>
						<td>
							@foreach($equipmentsAttachments as $equipmentsAttachment)
							@if($consultantEquipment->Id==$equipmentsAttachment->CrpConsultantEquipmentId)
							<i class="fa fa-check"></i><a href="{{URL::to($equipmentsAttachment->DocumentPath)}}" target="_blank">{{$equipmentsAttachment->DocumentName}}</a><br />
							@endif
							@endforeach
						</td>
{{--						@if((int)$serviceByConsultant==0)--}}
						<td>
							<input type="hidden" class="rowreference" value="{{$consultantEquipment->Id}}">
{{--							@if((bool)$isEdit!=null)--}}
{{--								@if(!empty($isEditByCDBUser))--}}
								{{--<a href="{{URL::to($equipmentEditRoute.'/'.$isEdit.'?equipmentid='.$consultantEquipment->Id.'&redirectUrl='.$redirectUrl.'&usercdb=edit')}}" class="editaction">Edit</a>&nbsp;|&nbsp;--}}
								{{--@else--}}
								<a href="{{URL::to($equipmentEditRoute.'/'.$isEdit.'?equipmentid='.$consultantEquipment->Id.'&redirectUrl='.$redirectUrl.'&initial=true')}}" class="editaction">Edit</a>&nbsp;|&nbsp;
								{{--@endif--}}
							{{--@endif--}}
							<a href="#" class="deletedbrow">
								Delete
							</a>
						</td>
						{{--@endif--}}
					</tr>
					@empty
					<tr>
						<td class="font-red" colspan="5">Plese click on Add Equipment button to start adding your equipments.</td>
					</tr>
					@endforelse
				</tbody>
			</table>
		</div>
		<div class="form-actions">
			<div class="btn-set">
				<a href="{{URL::to($redirectUrl.'/'.$consultantId)}}" class="btn blue button-next">
					Done <i class="m-icon-swapright m-icon-white"></i>
				</a>
			</div>
		</div>
	</div>
</div>
<!-- End of Equipments -->
@stop