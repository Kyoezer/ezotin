@extends('master')
@section('pagescripts')

	@if(isset($equipmentEdit[0]->Id))
		@if(!empty($equipmentEdit[0]->Id))
			<script>
				$('#addequipments').modal('show');
			</script>
		@endif
	@endif
	@if((int)$serviceByCertifiedbuilder==1)
		<script>
			@if(isset($equipmentEdit[0]->Id))
				@if(empty($equipmentEdit[0]->Id))

				@endif
			@endif
		</script>
	@endif
@stop
@section('content')

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
{{ Form::open(array('url' => 'specializedfirm/mspecializedfirmequipments','role'=>'form','files'=>true))}}
<input type="hidden" name="NewEquipmentSave" value="{{$newEquipmentSave}}">
@if(isset($afterSaveRedirect) && (int)$afterSaveRedirect==1)
<input type="hidden" name="HasCDBEdit" value="{{$afterSaveRedirect}}">
@endif
@if((int)$serviceByCertifiedbuilder==1)
	<input type="hidden" name="serviceByCertifiedbuilder" value="{{$serviceByCertifiedbuilder}}">
@endif
<div id="addequipments" class="modal fade bs-modal-lg" role="dialog" aria-labelledby="addequipments" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        		<input type="hidden" value="1" name="EditByCDB">
				<input type="hidden" name="PostBackUrl" value="{{$redirectUrl}}">
				<input type="hidden" name="EditPage" value="{{$editPage}}">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title font-green-seagreen bold">Add Equipment</h4>
              </div>
              @foreach($equipmentEdit as $equipmentEditReocrd)
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
							<input type="hidden" name="Id" value="{{$equipmentEditReocrd->Id}}" />
							<input type="hidden" name="CrpSpecializedtradeId" value="{{$certifiedbuilderId}}" />
							<label class="control-label">Equipment</label>
							<select name="CmnEquipmentId" id="" class="form-control input-sm equipmentforwebservicemodal equipmentselectregisteredtype required">
								<option value="">---SELECT---</option>
								@foreach($equipments as $equipment)
								<option value="{{$equipment->Id}}" data-vehicletype="{{$equipment->VehicleType}}" data-registered="{{$equipment->IsRegistered}}" @if($equipment->Id==Input::old('CmnEquipmentId') || $equipment->Id==$equipmentEditReocrd->CmnEquipmentId)selected="selected"@endif>{{$equipment->Name}}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label class="control-label">Registration No.</label>
							<input type="text" name="RegistrationNo" value="@if(!empty($equipmentEditReocrd->RegistrationNo)){{$equipmentEditReocrd->RegistrationNo}}@else{{Input::old('RegistrationNo')}}@endif" class="form-control regnoforwebservicemodal {{--equipmentforwebservicemodal--}} isregisteredequipment" placeholder="Registration No.">
						</div>
					</div>
					{{--<div class="col-md-3">--}}
						{{--<div class="form-group">--}}
							{{--<label class="control-label">Serial No.</label>--}}
							{{--<input type="text" name="SerialNo" value="@if(!empty($equipmentEditReocrd->SerialNo)){{$equipmentEditReocrd->SerialNo}}@else{{Input::old('SerialNo')}}@endif" class="form-control" placeholder="SerialNo">--}}
						{{--</div>--}}
					{{--</div>--}}
					{{--<div class="col-md-3">--}}
						{{--<div class="form-group">--}}
							{{--<label class="control-label">Model No.</label>--}}
							{{--<input type="text" name="ModelNo" value="@if(!empty($equipmentEditReocrd->ModelNo)){{$equipmentEditReocrd->ModelNo}}@else{{Input::old('ModelNo')}}@endif" class="form-control required" placeholder="ModelNo">--}}
						{{--</div>--}}
					{{--</div>--}}
				</div>
				<div class="row">
					<div class="col-md-3">
						<div class="form-group">
							<label class="control-label">Quantity</label>
							<input type="text" name="Quantity" value="@if(!empty($equipmentEditReocrd->Quantity)){{$equipmentEditReocrd->Quantity}}@else{{Input::old('Quantity')}}@endif" class="form-control" placeholder="Quantity">
						</div>
						</div>
						</div>
				
				@endforeach
				@if(!empty($equipmentAttachments))
				<div class="row">
					<div class="col-md-12 table-responsive">
						<div class="note note-danger" style="text-transform: uppercase;">
							<strong>MESSAGE: </strong> Please delete the documents that have been rejected / that you don't need from the list below, so that it will be easier and faster for us to verify the documents. That way, your application will be processed quicker.
						</div>
						<h5 class="font-blue-madison bold">List of Uploaded Documents</h5>
						<table id="" class="table table-bordered table-striped table-condensed">
							{{Form::hidden('Model','SpecializedfirmEquipmentAttachmentFinalModel',array('class'=>'delete-model'))}}
							<thead>
								<tr>
									<th style="width:700px;">Document Name</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								@foreach($equipmentAttachments as $equipmentAttachment)
								<tr>
									<input type="hidden" class="rowreference" value="{{$equipmentAttachment->Id}}"/>
									<td>
										<a href="{{URL::to($equipmentAttachment->DocumentPath)}}" target="_blank">{{$equipmentAttachment->DocumentName}}</a>
									</td>
									<td>
										<center><a href="#" class="deletedbrow btn btn-xs purple"><i class="fa fa-times"></i> Delete</a></center>
									</td>
								</tr>
								@endforeach
							</tbody>
						</table>
						@if(count($equipmentAttachments)==0)
							<table class="table table-bordered table-striped table-condensed">
								{{Form::hidden('Model','SpecializedfirmEquipmentAttachmentModel',array('class'=>'delete-model'))}}
								<tbody>
								@foreach($equipmentFinalAttachments as $equipmentFinalAttachment)
									<tr>
										<input type="hidden" value="{{$equipmentFinalAttachment->Id}}" class="rowreference"/>
										<td style="width:700px;">
											<a href="{{URL::to($equipmentFinalAttachment->DocumentPath)}}" target="_blank">{{$equipmentFinalAttachment->DocumentName}}</a>
										</td>
										<td>
											<center><a href="#" class="deletedbrow btn btn-xs purple"><i class="fa fa-times"></i> Delete</a></center>
										</td>
									</tr>
								@endforeach
								</tbody>
							</table>
						@endif
					</div>
				</div>
				@endif
				<div class="row">
					<div class="col-md-12 table-responsive">
						<h5 class="font-blue-madison bold">Attach Documents</h5>
						<table id="specializedtradeequipment" class="table table-bordered table-striped table-condensed">
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
										<input type="text" name="DocumentName[]" value="Equipment Document" class="form-control input-sm">
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
		{{--@if((int)$serviceByCertifiedbuilder==1)--}}
			{{--<button id="reselectequipmentservice" type="button" class="btn green-seagreen btn-sm pull-right">Re-select Service</button>--}}
		{{--@endif--}}
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
			@if((int)$serviceByCertifiedbuilder==1)
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
					@foreach($certifiedbuilderEquipmentsFinal as $specializedtradeEquipmentFinal)
					<?php $randomKey=randomString(); ?>
					<tr>
						<td>
							{{Form::hidden('Id',$specializedtradeEquipmentFinal->Id,array('class'=>'rowreference'))}}
							{{$specializedtradeEquipmentFinal->Name}}
						</td>
						<td>{{$specializedtradeEquipmentFinal->RegistrationNo}}</td>
					
						<td>{{$specializedtradeEquipmentFinal->Quantity}}</td>
						<td>
							@foreach($specializedtradeEquipmentAttachmentsFinal as $specializedtradeEquipmentAttachmentFinal)
								@if($specializedtradeEquipmentAttachmentFinal->CrpSpecializedtradeEquipmentFinalId==$specializedtradeEquipmentFinal->Id)
								<i class="fa fa-check"></i><a href="{{URL::to($specializedtradeEquipmentAttachmentFinal->DocumentPath)}}" target="_blank">{{$specializedtradeEquipmentAttachmentFinal->DocumentName}}</a><br />
								@endif
							@endforeach
						</td>
						<td>
							<center><input type="checkbox" class="senddeleterequest" data-type="2" value="{{$specializedtradeEquipmentFinal->Id}}"></center>
						</td>
						<td>
							<input type="hidden" value="{{$specializedtradeEquipmentFinal->Id}}" class="recordreference"/>
							{{Form::hidden('Model','SpecializedfirmEquipmentFinalModel',array('class'=>'recordreferencemodel'))}}
							@if((bool)$isEdit!=null)
								@if(!empty($isEditByCDBUser))
									<a href="{{URL::to($equipmentEditRoute.'/'.$isEdit.'?equipmentid='.$specializedtradeEquipmentFinal->Id.'&redirectUrl='.$redirectUrl.'&usercdb=edit')}}" class="editaction">Edit</a>&nbsp;|&nbsp;
								@else
									<a href="{{URL::to($equipmentEditRoute.'/'.$isEdit.'?equipmentid='.$specializedtradeEquipmentFinal->Id.'&redirectUrl='.$redirectUrl)}}" class="editaction">Edit</a>&nbsp;|&nbsp;
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
				{{Form::hidden('Model',$changeModel?'SpecializedfirmEquipmentFinalModel':'SpecializedfirmEquipmentModel',array('class'=>'delete-model'))}}
				<thead>
					<tr>
						<th>
							Equipment Name
						</th>
						<th scope="col" class="">
							 Registration No
						</th>
					
						<th>
							Quantity
						</th>
						<th>
							Attachment
						</th>
						{{--@if((int)$serviceByCertifiedbuilder==0)--}}
							<th>Action</th>
						{{--@endif--}}
					</tr>
				</thead>
				<tbody>
					@forelse($certifiedbuilderEquipments as $specializedtradeEquipment)
					<tr>
						<td>
							{{$specializedtradeEquipment->Name}}
						</td>
						<td>{{$specializedtradeEquipment->RegistrationNo}}</td>
					
						<td>				
							{{$specializedtradeEquipment->Quantity}}
				
						</td>
						<td>
							@foreach($equipmentsAttachments as $equipmentsAttachment)
							@if($specializedtradeEquipment->Id==$equipmentsAttachment->CrpSpecializedtradeEquipmentId)
							<i class="fa fa-check"></i> <a href="{{URL::to($equipmentsAttachment->DocumentPath)}}" target="_blank">{{$equipmentsAttachment->DocumentName}}</a><br />
							@endif
							@endforeach
						</td>
{{--						@if((int)$serviceByCertifiedbuilder==0)--}}

						{{--@endif--}}
						{{--@if((int)$serviceByCertifiedbuilder==0)--}}
							<td>
								<input type="hidden" class="rowreference" value="{{$specializedtradeEquipment->Id}}">
								{{--@if((bool)$isEdit!=null)--}}
									{{--@if(!empty($isEditByCDBUser))--}}
										{{--<a href="{{URL::to($equipmentEditRoute.'/'.$isEdit.'?equipmentid='.$specializedtradeEquipment->Id.'&redirectUrl='.$redirectUrl.'&usercdb=edit')}}" class="editaction">Edit</a>&nbsp;|&nbsp;--}}
									{{--@else--}}
										<a href="{{URL::to($equipmentEditRoute.'/'.$isEdit.'?equipmentid='.$specializedtradeEquipment->Id.'&redirectUrl='.$redirectUrl.'&initial=true')}}" class="editaction">Edit</a>&nbsp;|&nbsp;
									{{--@endif--}}
									<a href="#" class="deletedbrow">
										Delete
									</a>
								{{--@endif--}}
							</td>
						{{--@endif--}}
					</tr>
					@empty
					<tr>
						<td class="font-red" colspan="6">Please click on Add Equipment button to start adding your equipments.</td>
					</tr>
					@endforelse
				</tbody>
			</table>
		</div>
		<div class="form-actions">
			<div class="btn-set">
				<a href="{{URL::to($redirectUrl.'/'.$certifiedbuilderId)}}" class="btn blue button-next">
					Done <i class="m-icon-swapright m-icon-white"></i>
				</a>
			</div>
		</div>
	</div>
</div>
<!-- End of Equipments -->
@stop