@extends('homepagemaster')
@if(!empty($equipmentEdit[0]->Id))
@section('pagescripts')
	<script>
		$('#addequipments').modal('show');
	</script>
@stop
@endif
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
<div id="addequipments" class="modal fade bs-modal-lg" role="dialog" aria-labelledby="addequipments" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        	{{ Form::open(array('url' => 'specializedfirm/mspecializedtradeequipments','role'=>'form','files'=>true))}}
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
							<input type="hidden" name="CrpSpecializedtradeId" value="{{$specializedtradeId}}" />
							<label class="control-label">Equipment</label>
							<select name="CmnEquipmentId" id="" class="form-control input-sm select2me equipmentforwebservicemodal equipmentselectregisteredtype required">
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
							{{--<input type="text" name="ModelNo" value="@if(!empty($equipmentEditRecord->ModelNo)){{$equipmentEditRecord->ModelNo}}@else{{Input::old('ModelNo')}}@endif" class="form-control" placeholder="ModelNo">--}}
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
						<table id="" class="table table-bordered table-striped table-condensed">
							<thead>
								<tr>
									<th>Document Name</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								@foreach($equipmentAttachments as $equipmentAttachment)
								<tr>
									<td>
										<a href="{{URL::to($equipmentAttachment->DocumentPath)}}" class="">{{$equipmentAttachment->DocumentName}}</a>
									</td>
									<td>
										<a href="#" class="">Delete</a>	
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
              {{Form::close()}}
        </div>
    </div>
</div>
<!-- End of Equipments -->
<div class="row">
	<div class="col-md-12">
		<div class="portlet light bordered" id="form_wizard_1">
			<div class="portlet-title">
				<div class="caption font-green-seagreen">
					<i class="fa fa-gift"></i> 
					<span class="caption-subject">Specializedfirm Registration (New Application) </span> - <span class="step-title">Step 4 of 4 </span>
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
							<li class="done">
								<a href="#tab3" data-toggle="tab" class="step">
								<span class="number">
								3 </span>
								<span class="desc">
								<i class="fa fa-check"></i> Human Resource </span>
								</a>
							</li>
							<li class="active">
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
									<i class="fa fa-check"></i>Requirements for Different Category and Classification
								</div>
							</div>
							<div class="portlet-body">
								<p class="bold font-red">Scroll to View More</p>
								<p class="bold font-red">*Minimum Requirement</p>
								<div class="scroller" style="height: 280px;">
									<div class="row">
										<div class="col-md-6">
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
																No equipment requirement for small class specializedfirms.
															</td>
														</tr>
														<tr>
															<td>
																Submit blue book copies supported by Route Permits and insurances for all RSTA registered equipments.
															</td>
														</tr>
														<tr>
															<td>
																Submit equipments verification reports duly endorsed by a Govt. Engineer (not less than the rank of AE) for those equipment, which are not dealt by RSTA.
															</td>
														</tr>
													</tbody>
												</table>
											</div>
										</div>
										<div class="col-md-6">
											<div class="table-responsive">
												<table class="table table-bordered table-striped table-condensed">
													<thead class="">
														<tr>
															<th colspan="4" class="bold">Note for Medium Class (M)</th>
														</tr>
														<tr>
															<th>Equipment</th>
															<th>W1</th>
															<th>W3</th>
															<th>W4</th>
														</tr>
													</thead>
													<tbody>
														<tr>
															<td>Truck</td>
															<td>1*</td>
															<td>1*</td>
															<td>1*</td>
														</tr>
														<tr>
															<td>Survey Equipment</td>
															<td>1*</td>
															<td>1*</td>
															<td>1*</td>
														</tr>
														<tr>
															<td>Construction Mixture</td>
															<td>1</td>
															<td>1</td>
															<td></td>
														</tr>
														<tr>
															<td>Vibrator</td>
															<td>1*</td>
															<td>1*</td>
															<td>1*</td>
														</tr>
														<tr>
															<td>Steel Shuttering (sft)</td>
															<td></td>
															<td>200</td>
														</tr>
														<tr>
															<td>Water Pump/Multi Mixer</td>
															<td></td>
															<td>1</td>
															<td>1</td>
														</tr>
														<tr>
															<td>Meggar</td>
															<td></td>
															<td></td>
															<td>1</td>
														</tr>
													</tbody>
												</table>
											</div>
										</div>
										<div class="col-md-6">
											<div class="table-responsive">
												<table class="table table-bordered table-striped table-condensed">
													<thead class="">
														<tr>
															<th colspan="4" class="bold">Note for Large Class (L)</th>
														</tr>
														<tr>
															<th>Equipments</th>
															<th>W1</th>
															<th>W3</th>
															<th>W4</th>
														</tr>
													</thead>
													<tbody>
														<tr>
															<td>Excavator</td>
															<td>1</td>
															<td>1</td>
															<td></td>
														</tr>
														<tr>
															<td>Road Roller</td>
															<td>1</td>
															<td></td>
															<td></td>
														</tr>
														<tr>
															<td>Truck</td>
															<td>1</td>
															<td>1</td>
															<td>1</td>
														</tr>
														<tr>
															<td>Air Compressor</td>
															<td>1</td>
															<td>1</td>
															<td>1</td>
														</tr>
														<tr>
															<td>Survey Equipment</td>
															<td>1*</td>
															<td>1*</td>
															<td>1*</td>
														</tr>
														<tr>
															<td>Construction Mixer</td>
															<td></td>
															<td>1</td>
															<td>1</td>
														</tr>
														<tr>
															<td>Vibrator</td>
															<td></td>
															<td>1*</td>
															<td>1*</td>
														</tr>
														<tr>
															<td>Crane Truck</td>
															<td></td>
															<td>1</td>
															<td>1</td>
														</tr>
														<tr>
															<td>Steel Shuttering (sft)</td>
															<td></td>
															<td>5000</td>
															<td></td>
														</tr>
														<tr>
															<td>Water Pump/Multi Meter</td>
															<td></td>
															<td></td>
															<td>1</td>
														</tr>
														<tr>
															<td>Meggar</td>
															<td></td>
															<td></td>
															<td>1</td>
														</tr>
														<tr>
															<td>Max Puller</td>
															<td></td>
															<td></td>
															<td>1</td>
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
		                                <a href="#addequipments" data-toggle="modal" class="btn green btn-sm"> <i class="fa fa-plus"></i> Add Equipment</a>
		                            </div>
		                        </div>
		                    </div>
		                </div>
						<div class="table-scrollable">
							<table class="table table-bordered table-striped table-condensed flip-content">
								{{Form::hidden('Model','SpecializedfirmEquipmentModel',array('class'=>'delete-model'))}}
								<thead class="flip-content">
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
										<th></th>
									</tr>
								</thead>
								<tbody>
									@forelse($specializedtradeEquipments as $specializedtradeEquipment)
									<tr>
										{{Form::hidden('Id',$specializedtradeEquipment->Id,array('class'=>'rowreference'))}}
										<td>{{$specializedtradeEquipment->Name}}</td>
										<td>{{$specializedtradeEquipment->RegistrationNo or '-'}}</td>
										<td>
											@if((int)$specializedtradeEquipment->Quantity!=0)
											{{$specializedtradeEquipment->Quantity}}
											@endif
										</td>
										<td>
											@foreach($equipmentsAttachments as $equipmentsAttachment)
											@if($specializedtradeEquipment->Id==$equipmentsAttachment->CrpSpecializedtradeEquipmentId)
											<i class="fa fa-check"></i> <a href="{{URL::to($equipmentsAttachment->DocumentPath)}}" target="_blank" class="font-blue">{{$equipmentsAttachment->DocumentName}}</a><br/>
											@endif
											@endforeach
										</td>
										<td>
											@if((bool)$isEdit!=null)
											<a href="{{URL::to('specializedfirm/equipmentregistration/'.$isEdit.'?equipmentid='.$specializedtradeEquipment->Id)}}" class="editaction">Edit</a>&nbsp;|&nbsp;
											@endif
											<a href="#" class="deletedbrow">
												Delete
											</a>
										</td>
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
								<a href="{{URL::to('specializedfirm/confirmregistration')}}" class="btn blue button-next">
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