	@extends('horizontalmenumaster')
	@section('content')
	@section('pagescripts')
		{{ HTML::script('assets/global/scripts/etool.js?ver='.randomString()) }}
		<script>
			if($('input[name="ShowModal"]').val() == 1){
				$("#addcontractorssmall").modal('show');
			}else{
				//$('.cdbno').val('');
				//$('.contractor-id').val('');
				//$('input[name="FinancialBidQuoted"]').val('');
			}
		</script>
		@if(Session::has('savedsuccessmessage'))
			@if(Input::get('currentTab') == 'contractorequipment')
				<?php $append = 'an Equipment'; ?>
			@else
				<?php $append = 'a Human Resource'; ?>
			@endif
			<script>
				//alert("You have successfully added {{$append}}");
			</script>
		@endif
	@stop
	@if(empty($tenders))
		<?php $contractorArray = $addContractors; ?>
	@else
		<?php $contractorArray = $tenders; ?>
	@endif
	<?php 
		$contractorCDBno="";
		$contractorId = "";
	?>
	<input type="hidden" name="URL" id="x" value="{{URL::to('/');}}"/>
	<div id="equipmentrstamodal" class="modal fade in" role="dialog" aria-labelledby="modalmessagebox" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
					<h3 id="" class="modal-title font-red-intense">Equipment Details</h3>
				</div>
				<div class="modal-body">
					
				</div>
			</div>
		</div>
	</div>
	<div id="addcontractormodal" class="modal fade in" role="dialog" aria-labelledby="modalmessagebox" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
					<h3 id="modalmessageheader" class="modal-title font-red-intense">Add Contractor</h3>
				</div>
				{{Form::open(array('url'=>'etl/etlsaveaddcontractor'))}}
					
					@foreach($contractorArray as $tender)

						{{Form::hidden('ContractorType','SMALL')}}
						{{Form::hidden('Id',$edit?$tender->Id:'',array('id'=>'editId'))}}
						{{Form::hidden('EtlTenderId',$tenderId,array('class'=>'etltenderid'))}}
						{{Form::hidden('CurrentTab',Input::has('currentTab')?'#'.Input::get('currentTab'):'#contractorhumanresource')}}
					@endforeach
					
	{{-- 
					{{Form::hidden('Id',(isset($bidContractors[0])?$bidContractors[0]->Id:''))}}
					{{Form::hidden('Source','1')}}
					{{Form::hidden('EtlTenderId',$tenderId)}}
					{{Form::hidden('CurrentTab','XX')}} --}}
				<div class="modal-body">
					<table class="table table-bordered" id="ContractorAdd">
						<thead>
							<tr>
								<th>CDB No.</th>
								<th>Name</th>
							</tr>
						</thead>
						<tbody>
						@foreach($contractorList as $contList)

						<?php 	$contractorCDBno = $contList->CDBNo;
						 		$contractorId = $contList->CrpContractorFinalId;
						 ?>
							<tr> 
								<td class="col-md-4">
									<input type="text" class="form-control input-sm cdbno required" value="{{$contList->CDBNo}}" placeholder="CDB No.">
								</td>
								<td>
									<input type="hidden" name="Contractor[AAAAA][CrpContractorFinalId]" value="{{$contList->CrpContractorFinalId}}" class="contractor-id"/>
									<input type="text" class="contractorName required form-control input-sm contractor-name" value="{{$contList->NameOfFirm}}" readonly="readonly"/>
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label>Financial Bid Quoted <span class="font-red">*</span></label>
								@foreach($contractorArray as $tender)
									<input type="text" name="FinancialBidQuoted" value="{{$edit?$tender->FinancialBidQuoted:''}}" class="form-control required input-sm" placeholder="Financial Bid Quoted">
								@endforeach
								
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<div class="btn-set">
						<button type="submit" class="btn green">Save</button>
						<button type="button" class="btn red" data-dismiss="modal">Cancel</button>
					</div>
				</div>
				{{Form::close()}}
			</div>
		</div>
	</div>

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

	<div class="page-bar">
		<ul class="page-breadcrumb">
			<li>
				<a href="#">e-tool</a>
				<i class="fa fa-angle-right"></i>
			</li>
			<li>
				<a href="#">Add Contractor</a>
			</li>
		</ul>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="portlet light bordered">
				<div class="portlet-title">
					<div class="caption">
						<i class="fa fa-gift"></i>Add Contractor
					</div>
				</div>
				<div class="portlet-body form flip-scroll">
					<!-- BEGIN FORM-->
					{{Form::open(array('url'=>'etl/etlsaveaddcontractor'))}}
					
					{{Form::hidden('ContractorType','SMALL')}}
					{{Form::hidden('MaxHrPoints',$maxHrPoints)}}
					{{Form::hidden('MaxEqPoints',$maxEqPoints)}}
					{{Form::hidden('CurrentTab',Input::has('currentTab')?'#'.Input::get('currentTab'):'#contractorhumanresource')}}
						<div class="form-body">
						
							@foreach($contractorArray as $tender)
								{{Form::hidden('Id',$edit?$tender->Id:'')}}
								{{Form::hidden('EtlTenderId',$tenderId)}}
								<h4 class="bold">Work Id : <span class="font-green-seagreen ">{{$tender->WorkId}}</span></h4>
								
								<p><span class="bold">Name of Work: </span>{{$tender->NameOfWork}}</p>
								<p><span class="bold">Description : </span>{{$tender->DescriptionOfWork}}</p>
								<p><span class="bold">Dzongkhag : </span>{{$tender->Dzongkhag}}</p><br/>
								@if($tender->CDBNo)
								<p><span class="bold">CDB No. : </span>
								
								{{$tender->CDBNo}}
								<a href="#contractorAttachment" data-toggle="modal" class="btn-circle btn-sm btn-warning purple">View Attachment</a>
								</p>
								<p><span class="bold">Name of Firm : </span>{{$tender->NameOfFirm}}</p>
								@endif
							{{--Modal content was here--}}
							@endforeach
							<div class="row">
								<div class="col-md-12">
									<h5 class="font-green-seagreen bold">Add Human Resource/Equipment</h5>
									<div class="tabbable-custom nav-justified">
										<ul class="nav nav-tabs nav-justified" id="add-detail-tab">
											<li class="active">
												<a href="#contractorhumanresource" data-toggle="tab">
												Human Resource</a>
											</li>
											<li>
												<a href="#contractorequipment" data-toggle="tab">
												Equipments</a>
											</li>
										</ul>
										<div class="tab-content">
											<div class="tab-pane active" id="contractorhumanresource">
												<h5 class="bold">Human Resource Criteria</h5>
												<table class="table table-bordered table-striped table-condensed flip-content">
													<thead class="flip-content">
														<tr>
															<th>
																Sl No.
															</th>
															<th class="">
																Designation
															</th>
															<th class="">
																Qualification
															</th>
																							
														</tr>
													</thead>
													<tbody>
														<?php $count = 1; ?>
														@forelse($criteriaHR as $hr)
														<tr>
															<td>
																{{$count}}
															</td>
															<td>
																{{$hr->Designation}}
															</td>
															<td>
																{{$hr->Qualification}}
															</td>
															
														</tr>
														<?php $count++; ?>
														@empty
															<tr><td colspan="5" class="text-center font-red">No data to display</td></tr>
														@endforelse
														<?php $hrCount = $count;?>
													</tbody>
												</table>
												<a href="#addcontractormodal" data-toggle="modal" class="btn purple">@if($edit)Edit Contractor's Quoted Amount @else Add Contractor @endif</a>
												@if($edit)
												<a href="#" data-toggle="modal" class="btn blue">Check Trade License</a>
												<a href="#" data-toggle="modal" class="btn green">Check Tax Clearance</a>
												<a target="_blank" href="{{URL::to('etl/seekclarification/'.$tenderId.'/'.$contractorCDBno.'/'.$contractorId)}}" class="btn default bg-green-haze">
													Seek clarification
												</a>
												@endif
												<br><br>
												<?php if($hrCount>1){?>
												<h5 class="bold">Human Resource for Work Id {{$tender->WorkId}} @if($contractorCDBNos != '')(CDB No.: {{$contractorCDBNos[0]->CDBNo}})@endif</h5>
												<table class="table table-bordered table-striped table-condensed flip-content table-togglecontrols" id="addcontractor-hr">
													<thead class="flip-content">
														<tr>
															<th></th>
															<th width="20%">
																CID No./Work Permit
															</th>
															<th>
																Name
															</th>
															<th class="">
																Designation
															</th>
															<th class="">
																Qualification
															</th>
																					
														</tr>
													</thead>
													<tbody>
													<?php $hrTotal = 0; ?>
														@foreach($contractorHR as $contHR)
														<?php $randomKey = randomString(); ?>
														<tr>
															<td>
																<button type="button" class="deletetablerow deleterowfromdb"><i class="fa fa-times fa-lg"></i></button>
															</td>
															<td>
																<div class="input-group">
																	<input type="hidden" name="EtlContractorHumanResource[{{$randomKey}}][Id]" value="{{$contHR->Id}}" data-table="etlcontractorhumanresource" class="row-id not-required resetKeyForNew"/>
																	<input id="newpassword" class="form-control {{--checkhrtr--}} cidforwebservicetr  resetKeyForNew input-sm input-large etool-hr checkHumanResource" type="text" name="EtlContractorHumanResource[{{$randomKey}}][CIDNo]" placeholder="CID No." value="{{$contHR->CIDNo}}">
																</div>
															</td>
															<td>
																<div class="input-group">
																	<input class="form-control  resetKeyForNew input-sm input-large hr-name namefromwebservice" type="text" name="EtlContractorHumanResource[{{$randomKey}}][Name]" placeholder="Name" value="{{$contHR->Name}}">
																</div>
															</td>
															<td>
																<!--OLD CODE COMMENTED BY PRAMOD 
																<select name="EtlContractorHumanResource[{{$randomKey}}][CmnDesignationId]" id="designation" class="form-control input-sm EtlHrDesignationId resetKeyForNew">
																	<option value="">---SELECT ONE---</option>
																	@foreach($hrDesignations as $hrDesignation)
																		<option value="{{$hrDesignation->Id}}" @if($edit)<?php if($hrDesignation->EtlTierId != $contHR->EtlTierId): ?>class="hide" disabled="disabled"<?php endif; ?>@endif  data-criteriaHRId="{{$hrDesignation->EtlCriteriaHumanResourceId}}" @if(($contHR->CmnDesignationId == $hrDesignation->Id)&&($hrDesignation->EtlTierId == $contHR->EtlTierId)) selected="selected" @endif @if(!$edit)class="hide"@endif data-tierid="{{$hrDesignation->EtlTierId}}" @if(!$edit)disabled="disabled"@endif>{{$hrDesignation->Designation}}</option>
																	@endforeach
																</select>-->
																<select name="EtlContractorHumanResource[{{$randomKey}}][CmnDesignationId]" id="designation" class="form-control input-sm EtlSmallHrDesignationId resetKeyForNew">
																	<option value="">---SELECT ONE---</option>
																	@foreach($hrDesignations as $hrDesignation)
																		<!--<option value="{{$hrDesignation->Id}}" @if($contHR->EtlTierId == $hrDesignation->Id)selected="selected"@endif>{{$hrDesignation->Designation}}</option>-->	
																		<option value="{{$hrDesignation->Id}}" @if($edit)<?php if($hrDesignation->EtlTierId != $contHR->EtlTierId): ?> <?php endif; ?>@endif  data-criteriaHRId="{{$hrDesignation->EtlCriteriaHumanResourceId}}" @if(($contHR->CmnDesignationId == $hrDesignation->Id)) selected="selected" @endif @if(!$edit) @endif data-tierid="{{$hrDesignation->EtlTierId}}" @if(!$edit) @endif>{{$hrDesignation->Designation}}</option>
																	@endforeach
																</select>
															</td>
															<td>
																<select name="EtlContractorHumanResource[{{$randomKey}}][Qualification]" id="" class="form-control input-sm EtlHrQualificationId resetKeyForNew etool-total">
																	<option value="">---SELECT ONE---</option>
																	@foreach($hrQualifications as $hrQualification)
																		<option value="{{htmlspecialchars_decode($hrQualification->Qualification)}}" @if($edit)<?php if($hrQualification->CmnDesignationId != $contHR->CmnDesignationId): ?> <?php endif; ?>@endif @if((htmlspecialchars_decode($contHR->Qualification) == htmlspecialchars_decode($hrQualification->Qualification)) && ($hrQualification->CmnDesignationId == $contHR->CmnDesignationId))selected="selected"@endif @if(!$edit)@endif data-tierid="{{$hrQualification->EtlTierId}}" data-designationid="{{$hrQualification->CmnDesignationId}}" data-points="{{$hrQualification->Points}}" @if(!$edit)@endif>{{$hrQualification->Qualification}}</option>
																	@endforeach
																</select>
															</td>
															<td>
																<button type="button" class="btn btn-primary btn-sm checkHRbtn">Check</button>
															</td>
															</tr>
														@endforeach
															<tr class="notremovefornew">
																<td>
																	<button type="button" class="addtablerow"><i class="fa fa-plus fa-lg"></i></button>
																</td>
															</tr>
														
													</tbody>
												</table>
												<?php }?>
											</div>
											<div class="tab-pane" id="contractorequipment">
												<h5 class="bold">Equipment Criteria</h5>
												<table class="table table-bordered table-striped table-condensed flip-content">
													<thead class="flip-content">
														<tr>
															<th>
																Sl No.
															</th>
															<th class="">
																Name
															</th>
															<th class="">
																Quantity
															</th>
														</tr>
													</thead>
													<tbody>
														<?php $count = 1; ?>
														@forelse($criteriaEquipments as $eq)
														<tr>
															<td>
																{{$count}}
															</td>
															<td>
																{{$eq->Equipment}}
															</td>
															<td>
																{{$eq->Quantity}}
															</td>
														</tr>
														<?php $count++; ?>
														@empty
															<tr><td colspan="5" class="text-center font-red">No data to display</td></tr>
														@endforelse
														<?php $equipmentCount = $count;?>
													</tbody>
												</table>
												<a href="#addcontractormodal" data-toggle="modal" class="btn purple">@if($edit)Edit Contractor's Quoted Amount  @else Add Contractor @endif</a>
												<br><br>
												<?php if($equipmentCount>1){?>
												<h5 class="bold">Equipments for {{$tender->WorkId}} @if($contractorCDBNos != '')(CDB No.: {{$contractorCDBNos[0]->CDBNo}})@endif</h5>
												<table class="table table-bordered table-striped table-condensed flip-content table-togglecontrols" id="addContractorEquipments">
													<thead class="flip-content">
														<tr>
															<th></th>

															<th class="">
																Name
															</th>
															<th class="">
																Owned/Hired
															</th>
															<th width="20%">
																Registration No.
															</th>
														</tr>
													</thead>
													<tbody>
													<?php $eqTotal = 0; ?>
														@foreach($contractorEquipments as $contEquipment)
														<?php $randomKey = randomString(); ?>
														<tr class="eq-container">
															<td>
																<button type="button" class="deletetablerow deleterowfromdb"><i class="fa fa-times fa-lg"></i></button>
															</td>
															<td>
																<input type="hidden" value="{{$contEquipment->Id}}" name="EtlContractorEquipment[{{$randomKey}}][Id]" data-table="etlcontractorequipment" class="row-id not-required resetKeyForNew"/>
																<!--OLD CODE COMMENTED BY PRAMOD
																<select name="EtlContractorEquipment[{{$randomKey}}][CmnEquipmentId]" id="" class="form-control equipment equipmentforwebservicetr input-sm EtlEqEquipmentId resetKeyForNew"> 
																-->

																	<select name="EtlContractorEquipment[{{$randomKey}}][CmnEquipmentId]" id="" class="form-control equipment equipmentforwebservicetr input-sm  resetKeyForNew">
																	<option value="">---SELECT ONE---</option>
																	@foreach($eqEquipments as $eqEquipment)
																		<option value="{{$eqEquipment->Id}}" data-isregistered="{{$eqEquipment->IsRegistered}}" data-vehicletype="{{$eqEquipment->VehicleType}}" data-quantity="{{$eqEquipment->Quantity}}" data-criteriaeqid="{{$eqEquipment->EtlCriteriaEQId}}" @if($edit)<?php if($eqEquipment->EtlTierId != $contEquipment->EtlTierId): ?><?php endif; ?>@endif @if(($contEquipment->CmnEquipmentId == $eqEquipment->Id) )selected="selected"@else @endif data-tierid="{{$eqEquipment->EtlTierId}}" data-points="{{number_format(round($eqEquipment->Points,3),3)}}" >{{$eqEquipment->Equipment}}</option>
																	@endforeach
																</select>
															</td>
															<td>
																<input type="hidden" name="EtlContractorEquipment[{{$randomKey}}][Quantity]" value="1" class="form-control  number resetKeyForNew input-sm" value="" />
																<select name="EtlContractorEquipment[{{$randomKey}}][OwnedOrHired]" autocomplete="off" class="form-control input-sm resetKeyForNew etool-total">
																	<option value="">---SELECT ONE---</option>
																	<option value="1" @if($contEquipment->OwnedOrHired == 1)selected="selected"@endif>Owned</option>
																	<option value="2" @if($contEquipment->OwnedOrHired == 2)selected="selected"@endif>Hired</option>
																</select>
																<input type="hidden" name="EtlContractorEquipment[{{$randomKey}}][Points]" value="@if($contEquipment->OwnedOrHired == 1){{$contEquipment->Points}}@endif<?php if($contEquipment->OwnedOrHired == 2): ?>{{$contEquipment->Points}}<?php endif; ?>" class="form-control resetKeyForNew not-required input-sm calculatedpoints" value="" />
															</td>
															<td>
																<div class="input-group">

																	<input id="newpassword" class="form-control input-sm input-large resetKeyForNew registration-no checkEquipment " value="{{$contEquipment->RegistrationNo}}" type="text" name="EtlContractorEquipment[{{$randomKey}}][RegistrationNo]" placeholder="Registration No.">
																</div>
															</td>
															<td>
																<button type="button" class="btn btn-primary btn-sm checkEQbtn">Check</button>
															</td>
														</tr>
														@endforeach
														<tr class="notremovefornew">
															<td>
																<button type="button" class="addtablerow"><i class="fa fa-plus fa-lg"></i></button>
															</td>
															<td colspan="4" class="text-right bold"></td>
														</tr>
													
													</tbody>
												</table>
												<?php }?>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="form-actions">
							<div class="btn-set">
								<a href="{{URL::to('etl/workevaluationdetailssmallcontractors/'.$tenderId)}}" class="btn blue"><i class="m-icon-swapleft m-icon-white"></i>&nbsp;Back</a>
								<button type="submit" class="btn green" id="saveAddContractor">Save</button>
								<a href="{{URL::to('etl/workevaluationdetailssmallcontractors/'.$tenderId)}}" class="btn red">Cancel</a>
							</div>
						</div>
					{{Form::close()}}
					<!-- END FORM-->
				</div>
			</div>
		</div>
	</div>
	<div id="addSeekClarification" class="modal fade in" role="dialog" aria-labelledby="modalmessagebox" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
					<h3 id="modalmessageheader" class="modal-title font-red-intense">Seek Clarification</h3>
				</div>
				{{Form::open(array('url'=>'etl/eltPostSeekClarification'))}}
				{{Form::hidden('EtlTenderId',$tenderId,array('class'=>'etltenderid'))}}
				{{Form::hidden('contractorCDBno',$contractorCDBno,array('class'=>'contractorCDBno'))}}
				{{Form::hidden('contractorId',$contractorId,array('class'=>'contractorId'))}}
				<div class="modal-body">
					<label>Enquiry</label>
					<textarea name="enquiry" class="form-control"></textarea>
				</div>
				
				<div class="modal-footer">
					    
					<div class="btn-set">
						<button type="submit" class="btn green">Post</button>
						<button type="button" class="btn red" data-dismiss="modal">Cancel</button>
					</div>
				</div>
				{{Form::close()}}
			</div>
		</div>
	</div>

	<div id="contractorAttachment" class="modal fade in" role="dialog" aria-labelledby="modalmessagebox" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
             </div>
            <div class="modal-body">
                <h4 class="bold">Contractor Registration Attachment</h4>
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Document Name</th>
                            <th>Action</th>
                        </tr>

                    </thead>
                    <?php $count=1;?>
                @foreach($contractorDocument as $tender)
                <tr>
                    <td><?=$count++?></td>
                    <td>{{$tender->DocumentName}}</td>
                    <td><a class="btn btn-primary btn-xs" href="{{$tender->DocumentPath}}" target="_blank">View</a></td>
                </tr>    
                @endforeach
                            
                </table>
            </div>
        </div>
    </div>
</div>


	
	@stop