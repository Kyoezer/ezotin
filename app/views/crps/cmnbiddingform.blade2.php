@extends('master')
@section('content')
@section('pagescripts')
	{{ HTML::script('assets/global/scripts/etool.js?ver='.randomString()) }}
@stop
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
<div class="row">
	<div class="col-md-12">
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-gift"></i>
					<span class="caption-subject font-green-seagreen">Bidding Form</span>
				</div>
			</div>
			<div class="portlet-body form">
				{{ Form::open(array('url' => $model,'role'=>'form','class'=>'form-horizontal'))}}
					<div class="form-body">
						@foreach($contractWorkDetails as $contractWorkDetail)
						<div class="form-group">
							<label class="control-label col-md-3">Reference No.</label>
							<div class="col-md-7">
								<input type="text" name="ReferenceNo" class="form-control input-sm required" placeholder="ReferenceNo" value="@if(!empty($contractWorkDetail->ReferenceNo)){{$contractWorkDetail->ReferenceNo}}@else{{Input::old('ReferenceNo')}}@endif"/>
							</div>
						</div>
						<input type="hidden" name="Id" value="{{$contractWorkDetail->Id}}" />
						<input type="hidden" name="Type" value="{{$type}}">
						<input type="hidden" name="RedirectUrl" value="{{$redirectUrl}}">
						<input type="hidden" name="CmnWorkExecutionStatusId" value="{{CONST_CMN_WORKEXECUTIONSTATUS_AWARDED}}">
						@if((int)$showProcuringAgency==1)
						<div class="form-group">
							<input type="hidden" name="ByCDB" value="1" />
							<label class="control-label col-md-3">Procuring Agency</label>
							<div class="col-md-4">
								<select name="CmnProcuringAgencyId" class="form-control select2me required">
									<option value="">---SELECT ONE---</option>
									@foreach($procuringAgencies as $procuringAgency)
									<option value="{{$procuringAgency->Id}}" @if($procuringAgency->Id==$contractWorkDetail->CmnProcuringAgencyId)selected="selected"@endif>{{$procuringAgency->Name.' ('.$procuringAgency->Code.')'}}</option>
									@endforeach
								</select>
							</div>
						</div>
						@else
							@if((bool)$tenderId!=NULL)
								<input type="hidden" name="EtlTenderId" value="{{$tenderId}}" />
							@endif
							<input type="hidden" name="CmnProcuringAgencyId" value="{{Auth::user()->CmnProcuringAgencyId}}" />
						@endif
						<div class="form-group">
							<label class="control-label col-md-3">Name of the Contract Work</label>
							<div class="col-md-7">
								<input type="text" name="NameOfWork" class="form-control required input-sm" placeholder="Name of the work" value="@if(!empty($contractWorkDetail->NameOfWork)){{$contractWorkDetail->NameOfWork}}@else{{Input::old('NameOfWork')}}@endif"/>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">Contract Description</label>
							<div class="col-md-7">
								<textarea name="DescriptionOfWork" class="form-control wysihtml5 required input-sm" rows="3">
									@if(!empty($contractWorkDetail->DescriptionOfWork))
										{{$contractWorkDetail->DescriptionOfWork}}
									@else
										{{Input::old('DescriptionOfWork')}}
									@endif
								</textarea>
							</div>
						</div>
						@if((int)$type==0)
						<div class="form-group">
							<label class="control-label col-md-3">Category of Work</label>
							<div class="col-md-4">
								<select name="CmnContractorProjectCategoryId" class="form-control required input-sm">
									<option value="">---SELECT ONE---</option>
									@foreach($contractorProjectCategories as $contractorProjectCategory)
									<option value="{{$contractorProjectCategory->Id}}" @if($contractorProjectCategory->Id==$contractWorkDetail->CmnContractorProjectCategoryId || Input::old('CmnContractorProjectCategoryId')==$contractorProjectCategory->Id)selected="selected"@endif>{{$contractorProjectCategory->Code.' ('.$contractorProjectCategory->Name.')'}}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">Classification of Contractors</label>
							<div class="col-md-4">
								<select name="CmnContractorClassificationId" class="form-control required input-sm">
									<option value="">---SELECT ONE---</option>
									@foreach($contractorClass as $contractorClass)
									<option value="{{$contractorClass->Id}}" @if($contractorClass->Id==$contractWorkDetail->CmnContractorClassificationId || Input::old('CmnContractorClassificationId')==$contractorClass->Id)selected="selected"@endif>{{$contractorClass->Name.' ('.$contractorClass->Code.')'}}</option>
									@endforeach
								</select>
							</div>
						</div>
						@else
						<div class="form-group">
							<label class="control-label col-md-3">Service Category</label>
							<div class="col-md-4">
								<select name="CmnConsultantServiceCategoryId" class="form-control required input-sm">
									<option value="">---SELECT ONE---</option>
									@foreach($consultantServiceCategories as $consultantServiceCategory)
									<option value="{{$consultantServiceCategory->Id}}" @if($consultantServiceCategory->Id==$contractWorkDetail->CmnConsultantServiceCategoryId || Input::old('CmnConsultantServiceCategoryId')==$consultantServiceCategory->Id)selected="selected"@endif>{{$consultantServiceCategory->Name}}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">Service Name</label>
							<div class="col-md-4">
								<select name="CmnConsultantServiceId" class="form-control required input-sm">
									<option value="">---SELECT ONE---</option>
									@foreach($consultantServices as $consultantService)
									<option value="{{$consultantService->Id}}" @if($consultantService->Id==$contractWorkDetail->CmnConsultantServiceId || Input::old('CmnConsultantServiceId')==$consultantService->Id)selected="selected"@endif>{{$consultantService->Code.' ('.$consultantService->Name.')'}}</option>
									@endforeach
								</select>
							</div>
						</div>
						@endif
						<div class="form-group">
							<label class="control-label col-md-3">Dzongkhag</label>
							<div class="col-md-4">
								<select name="CmnDzongkhagId" class="form-control required input-sm">
									<option value="">---SELECT ONE---</option>
									@foreach($dzongkhags as $dzongkhag)
									<option value="{{$dzongkhag->Id}}" @if($dzongkhag->Id==$contractWorkDetail->CmnDzongkhagId)selected="selected"@endif>{{$dzongkhag->NameEn}}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">Approved Agency Estimate (Nu.)</label>
							<div class="col-md-4">
								<input type="text" name="ApprovedAgencyEstimate" class="form-control text-right required input-sm" placeholder="Approved Agency Estimate" value="@if(!empty($contractWorkDetail->ApprovedAgencyEstimate)){{$contractWorkDetail->ApprovedAgencyEstimate}}@else{{Input::old('ApprovedAgencyEstimate')}}@endif" />
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">Date of NIT in Media</label>
							<div class="col-md-4">
								<div class="input-icon right">
									<i class="fa fa-calendar"></i>
									<input type="text" name="NitInMediaDate" class="form-control datepicker input-sm" readonly="readonly" placeholder="Date of NIT in Media" value="@if(!empty($contractWorkDetail->NitInMediaDate)){{$contractWorkDetail->NitInMediaDate}}@else{{Input::old('NitInMediaDate')}}@endif" />
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">Date of Bids Closed for Sale</label>
							<div class="col-md-4">
								<div class="input-icon right">
									<i class="fa fa-calendar"></i>
									<input type="text" name="BidSaleClosedDate" class="form-control form_datetime required input-sm" readonly="readonly" placeholder="Date of Bids Closed for Sale" value="@if(!empty($contractWorkDetail->BidSaleClosedDate)){{convertDateTimeToClientFormat($contractWorkDetail->BidSaleClosedDate)}}@else{{Input::old('BidSaleClosedDate')}}@endif" />
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">Bid Opening Date</label>
							<div class="col-md-4">
								<div class="input-icon right">
									<i class="fa fa-calendar"></i>
									<input type="text" name="BidOpeningDate" class="form-control form_datetime required input-sm" readonly="readonly" placeholder="Bid Opening Date" value="@if(!empty($contractWorkDetail->BidOpeningDate)){{convertDateTimeToClientFormat($contractWorkDetail->BidOpeningDate)}}@else{{Input::old('BidOpeningDate')}}@endif" />
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">Date of Letter of Acceptance</label>
							<div class="col-md-4">
								<div class="input-icon right">
									<i class="fa fa-calendar"></i>
									<input type="text" name="AcceptanceDate" class="form-control datepicker required input-sm" readonly="readonly" placeholder="Date of Letter of Acceptance" value="@if(!empty($contractWorkDetail->AcceptanceDate)){{$contractWorkDetail->AcceptanceDate}}@else{{Input::old('AcceptanceDate')}}@endif" />
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">Date of Signing of Contract</label>
							<div class="col-md-4">
								<div class="input-icon right">
									<i class="fa fa-calendar"></i>
									<input type="text" name="ContractSigningDate" class="form-control datepicker required input-sm" readonly="readonly" placeholder="Date of Signing of Contract" value="@if(!empty($contractWorkDetail->ContractSigningDate)){{$contractWorkDetail->ContractSigningDate}}@else{{Input::old('ContractSigningDate')}}@endif" />
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">Work Order No.</label>
							<div class="col-md-4">
								<input type="text" name="WorkOrderNo" class="form-control required input-sm" placeholder="Work Order No" value="@if(!empty($contractWorkDetail->WorkOrderNo)){{$contractWorkDetail->WorkOrderNo}}@else{{Input::old('WorkOrderNo')}}@endif" />
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">Contract Period (In Months)</label>
							<div class="col-md-4">
								<input type="text" name="ContractPeriod" class="form-control durationnumber required number input-sm" data-alertlabel="Contract Period (In Months)" placeholder="Contract Period" value="@if(!empty($contractWorkDetail->ContractPeriod)){{$contractWorkDetail->ContractPeriod}}@else{{Input::old('ContractPeriod')}}@endif" />
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">Start Date (Tentative)</label>
							<div class="col-md-4">
								<div class="input-icon right">
									<i class="fa fa-calendar"></i>
									<input type="text" name="WorkStartDate" class="form-control calculateenddate required input-sm" readonly="readonly" placeholder="Work Start Date" value="@if(!empty($contractWorkDetail->WorkStartDate) && ($contractWorkDetail->WorkStartDate != '0000-00-00')){{convertDateToClientFormat($contractWorkDetail->WorkStartDate)}}@else{{Input::old('WorkStartDate')}}@endif" />
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">Completion Date (Tentative)</label>
							<div class="col-md-4">
								<div class="input-icon right">
									<i class="fa fa-calendar"></i>
									<input type="text" name="WorkCompletionDate" class="form-control durationendresult required input-sm" readonly="readonly" placeholder="Work Completion Date" value="@if(!empty($contractWorkDetail->WorkCompletionDate) && ($contractWorkDetail->WorkCompletionDate!='0000-00-00')){{convertDateToClientFormat($contractWorkDetail->WorkCompletionDate)}}@else{{Input::old('WorkCompletionDate')}}@endif" />
								</div>
							</div>	
						</div>
						@endforeach
						<h5 class="bold font-blue-madison">Name of the @if((int)$type==0){{"Contractors"}}@else{{"Consultants"}}@endif who Submitted Bids</h5>
						<div class="table-responsive">
							<table id="ContractorAdd" class="table table-bordered table-striped table-condensed">
								<thead>
									<tr>
										<th>
										</th>
										<th width="15%">
											CDB No.
										</th>
										<th>
											Name
										</th>
										<th width="20%" class="numeric">
											Bid Amount
										</th>
										<th width="20%" class="numeric">
											Evaluated Amount
										</th>
										<th width="10%">
											Awarded To
										</th>
									</tr>
								</thead>
								<tbody>
									<?php $count=0; ?>
									@foreach($contractBidders as $contractBidder)
									<tr>
										<?php $randomKey=randomString();?>
										<td>
											<button type="button" class="deletetablerow"><i class="fa fa-times"></i></button>
										</td>
										<td>
											<input type="text" class="form-control input-sm cdbno" value="{{$contractBidder->CDBNo}}">
										</td>
										<td>
											@if((int)$type==0)
											<input type="hidden" name="CrpBiddingFormDetailModel[{{$randomKey}}][CrpContractorFinalId]" value="{{$contractBidder->CrpContractorFinalId}}" class="resetKeyForNew contractor-id" />
											<input type="text" class="contractor-name form-control input-sm" value="{{$contractBidder->NameOfFirm}}" readonly="readonly"/>
											@endif
                                            @if((int)$type==1)
                                                <input type="hidden" class="consultant-hidden" value="1"/>
                                                <input type="hidden" name="CrpBiddingFormDetailModel[{{$randomKey}}][CrpConsultantFinalId]" value="{{$contractBidder->CrpContractorFinalId}}" class="resetKeyForNew consultant-id" />
                                                <input type="text" class="consultant-name form-control input-sm" value="{{$contractBidder->NameOfFirm}}" readonly="readonly"/>
                                            @endif
										</td>
										<td>
											<input type="text" name="CrpBiddingFormDetailModel[{{$randomKey}}][BidAmount]" class="form-control input-sm resetKeyForNew text-right required number" value="{{$contractBidder->BidAmount}}">
										</td>
										<td>
											<input type="text" name="CrpBiddingFormDetailModel[{{$randomKey}}][EvaluatedAmount]" class="form-control input-sm resetKeyForNew text-right required number" value="{{$contractBidder->EvaluatedAmount}}">
										</td>
										<td>
											<input type="checkbox" name="CrpBiddingFormDetailModel[{{$randomKey}}][CmnWorkExecutionStatusId]" value="{{CONST_CMN_WORKEXECUTIONSTATUS_AWARDED}}" class="form-control resetKeyForNew notclearfornew addrowcheckbox" @if((bool)$contractBidder->CmnWorkExecutionStatusId!=null)checked="checked"@endif/>
										</td>
									</tr>
									<?php $count++;?>
									@endforeach
									<tr class="notremovefornew">
										<td>
											<button type="button" class="addtablerow"><i class="fa fa-plus fa-lg"></i></button>
										</td>
										<td colspan="5"></td>
									</tr>
								</tbody>
							</table>
						</div>
						@if(isset($isCinet))
						<div class="table-responsive">
							<h5 class="bold font-blue-madison">Human Resource <input type="checkbox" @if((bool)$bidHRs[0]->CIDNo)checked="checked"@endif class="toggle-cinet-table" id="add-hr-cinet"/></h5>
							<table class="table table-striped table-condensed table-bordered" id="cinet-hr-table">
								<thead>
								<tr>
									<th width="2%"></th>
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
								<?php $bidHRCount = count($bidHRs); ?>
								@foreach($bidHRs as $bidHR)
								<?php $randomKey = randomString(); ?>
								<tr>
									<td>
										<button type="button" class="deletetablerow deleterowfromdb"><i class="fa fa-times fa-lg"></i></button>
									</td>
									<td>
										<input type="text" name="cinetHR[{{$randomKey}}][CIDNo]" @if(!(bool)$bidHR->CIDNo)disabled="disabled"@endif value="{{$bidHR->CIDNo}}" class="form-control resetKeyForNew input-sm cidforwebservicetr cinet-hr checkHumanResource"/>
									</td>
									<td>
										<input type="text" name="cinetHR[{{$randomKey}}][Name]" @if(!(bool)$bidHR->CIDNo)disabled="disabled"@endif value="{{$bidHR->Name}}" class="form-control resetKeyForNew input-sm namefromwebservice"/>
									</td>
									<td>
										<select name="cinetHR[{{$randomKey}}][CmnDesignationId]" @if(!(bool)$bidHR->CIDNo)disabled="disabled"@endif class="form-control resetKeyForNew input-sm">
											<option value="">SELECT</option>
											@foreach($designations as $designation)
												<option value="{{$designation->Id}}" @if($bidHR->CmnDesignationId == $designation->Id)selected="selected"@endif>{{$designation->Name}}</option>
											@endforeach
										</select>
									</td>
									<td>
										<select name="cinetHR[{{$randomKey}}][CmnQualificationId]" @if(!(bool)$bidHR->CIDNo)disabled="disabled"@endif class="form-control resetKeyForNew input-sm">
											<option value="">SELECT</option>
											@foreach($qualifications as $qualification)
												<option value="{{$qualification->Id}}" @if($bidHR->CmnQualificationId == $qualification->Id)selected="selected"@endif>{{$qualification->Name}}</option>
											@endforeach
										</select>
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
						</div>
						<div class="table-responsive">
						<h5 class="bold font-blue-madison">Equipment <input type="checkbox" @if((bool)$bidEquipments[0]->CmnEquipmentId)checked="checked"@endif class="toggle-cinet-table" id="add-eq-cinet"/></h5>
						<table class="table table-striped table-condensed table-bordered" id="cinet-eq-table">
							<thead>
								<tr>
									<th style="width: 2%;"></th>
									<th width="30%">
										Equipment
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
							@foreach($bidEquipments as $bidEquipment)
								<tr>
									<td>
										<button type="button" class="deletetablerow deleterowfromdb"><i class="fa fa-times fa-lg"></i></button>
									</td>
									<td>
										<select name="cinetEquipment[{{$randomKey}}][CmnEquipmentId]" @if(!(bool)$bidEquipment->CmnEquipmentId)disabled="disabled"@endif class="form-control resetKeyForNew input-sm equipmentforwebservicetr">
											<option value="">SELECT</option>
											@foreach($equipments as $equipment)
												<option value="{{$equipment->Id}}" @if($bidEquipment->CmnEquipmentId == $equipment->Id)selected="selected"@endif data-isregistered="{{$equipment->IsRegistered}}" data-vehicletype="{{$equipment->VehicleType}}">{{$equipment->Name}}</option>
											@endforeach
										</select>
									</td>
									<td>
										{{Form::select("cinetEquipment[$randomKey][OwnerOrHired]",array(''=>'SELECT','1'=>'Owned','2'=>'Hired'),$bidEquipment->OwnedOrHired,array('class'=>'form-control resetKeyForNew input-sm',(!(bool)$bidEquipment->CmnEquipmentId)?"disabled":'data-x'=>"disabled"))}}
									</td>
									<td>
										<input type="text" value="{{$bidEquipment->RegistrationNo}}" @if(!(bool)$bidEquipment->CmnEquipmentId)disabled="disabled"@endif name="cinetEquipment[{{$randomKey}}][RegistrationNo]" class="form-control resetKeyForNew input-sm notrequired registration-no checkEquipment" />
									</td>
								</tr>
							@endforeach
								<tr class="notremovefornew">
									<td>
										<button type="button" class="addtablerow"><i class="fa fa-plus fa-lg"></i></button>
									</td>
									<td colspan="3" class="text-right bold"></td>
								</tr>
							</tbody>
						</table>
					</div>
						@endif
					</div>
					<div class="form-actions">
						<div class="btn-set">
							<button type="submit" class="btn green">{{empty($contractWorkDetails[0]->Id)?'Save':'Update'}}</button>
							<a href="{{URL::to(Request::url())}}" class="btn red">Cancel</a>
						</div>
					</div>
				{{Form::close()}}
				<!-- END FORM-->
			</div>
		</div>
	</div>
</div>
@stop