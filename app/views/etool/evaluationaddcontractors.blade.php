@extends('horizontalmenumaster')
@section('content')
@section('pagescripts')
    {{ HTML::script('assets/global/scripts/etool.js?ver='.randomString()) }}
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
<?php 
		$contractorCDBno="";
        $tradeLicenseNo="";
        $taxTpnNo="";
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
            <div class="modal-body">
                @if(Session::has('customeetlsaveaddcontractorrrormessage'))
                    <div class="note note-danger">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                        {{Session::get('customerrormessage')}}
                    </div>
                @endif
               
                @if(empty($tenders))
                    <?php $contractorArray = $addContractors; ?>
                @else
                    <?php  $contractorArray = $tenders; ?>
                @endif
                <?php $contractorArray = $addContractors; ?>
                @foreach($contractorArray as $tender)
					{{Form::hidden('ContractorType','LARGE')}}
                    {{Form::hidden('Id',$edit?$tender->Id:'',array('id'=>'editId'))}}
                    {{Form::hidden('EtlTenderId',$tenderId,array('class'=>'etltenderid'))}}
                    {{Form::hidden('CurrentTab',Input::has('currentTab')?'#'.Input::get('currentTab'):'#contractorhumanresource')}}
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Joint Venture <span class="font-red">*</span></label>
                                <div class="radio-list" id="jointventure-toggle">
                                    <label class="radio-inline">
                                        <input type="radio" name="JointVenture" id="optionsRadios23" value="0" @if($edit)@if($tender->JointVenture == 0)checked @endif @else checked @endif> No </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="JointVenture" id="optionsRadios22" value="1" @if($edit)@if($tender->JointVenture == 1)checked @endif @endif> Yes </label>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered table-striped table-condensed flip-content" id="ContractorAdd">
                                <thead class="flip-content">
                                <tr>
                                    <th class="to-hide @if($edit && $tender->JointVenture == 0)hide @endif @if(!$edit)hide @endif"></th>
                                    <th style="width: 10px!important;">Sl. No.</th>
                                    <th>CDB No.</th>
                                    <th>Name of Firm</th>
                                    <th class="to-hide @if($edit && $tender->JointVenture == 0)hide @endif @if(!$edit)hide @endif@endif">Stake</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($contractorList as $contList)
                                <?php 	$contractorCDBno = $contList->CDBNo;
                                        $contractorId = $contList->CrpContractorFinalId;
                                        $tradeLicenseNo = $contList->TradeLicenseNo;
                                        $taxTpnNo =  $contList->TPN;
                                ?>
                                    <?php $randomKey = randomString(); ?>
                                    <tr>
                                        <td class="to-hide @if($edit && $tender->JointVenture == 0)hide @endif @if(!$edit)hide @endif">
                                            <button type="button" class="deletetablerow"><i class="fa fa-times fa-lg"></i></button>
                                        </td>
                                        <td><input type="text" name="Contractor[{{$randomKey}}][Sequence]" class="input-sm input-xsmall resetKeyForNew form-control increment notclearfornew" readonly value="{{$contList->Sequence or 1}}" /></td>
                                        <td>
                                            <input type="text" name="Contractor[{{$randomKey}}][CDBNo]" class="form-control input-sm cdbno required resetKeyForNew" value="{{$contList->CDBNo}}" placeholder="CDB No.">
                                        </td>
                                        <td>
                                            <input type="hidden" name="Contractor[{{$randomKey}}][CrpContractorFinalId]" value="{{$contList->CrpContractorFinalId}}" class="contractor-id resetKeyForNew"/>
                                            <input type="text" class="contractorName required form-control input-sm contractor-name" value="{{$contList->NameOfFirm}}" readonly="readonly"/>
                                        </td>
                                        <td class="to-hide @if($edit && $tender->JointVenture == 0)hide @endif @if(!$edit)hide @endif">
                                            <input type="text" class="form-control input-sm required resetKeyForNew stake number" data-max="100" value="{{$contList->Stake or 100}}" name="Contractor[{{$randomKey}}][Stake]"/>
                                        </td>
                                    </tr>
                                @endforeach
                                <tr class="notremovefornew @if($edit && $tender->JointVenture == 0)hide @endif @if(!$edit)hide @endif">
                                    <td>
                                        <button type="button" class="addtablerow"><i class="fa fa-plus fa-lg"></i></button>
                                    </td>
                                    <td colspan="4"></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Financial Bid Quoted <span class="font-red">*</span></label>
                                <input type="number" name="FinancialBidQuoted" value="{{$edit?$tender->FinancialBidQuoted:''}}" class="form-control number required input-sm" placeholder="Financial Bid Quoted">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-md-offset-1" style="border: 2px solid #000;">
                            <?php $count = 1; ?>
                            @foreach($totalContractors as $totalContractor)
                                <div class="to-clone">
                                    <h5 class="font-green-seagreen bold">Capacity - Credit Line Available (Contractor <span class="contractor-no">{{$count}}</span>)</h5>
                                    <table class="table table-bordered table-striped table-condensed flip-content" id="capacitytable_{{$count}}">
                                        <thead class="flip-content">
                                        <tr>
                                            <th>
                                                Name of Bank
                                            </th>
                                            <th class="">
                                                Amount
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php if(!isset($totalContractor->Sequence)){
                                            $bankLoop = $cmnBanks;
                                            }else{
                                                $bankLoop=$cmnBanks[$totalContractor->Sequence];} ?>
                                        @foreach($bankLoop as $cmnBank)
                                            <?php $randomKey = randomString(); ?>
                                            <tr>
                                                <td>
                                                    {{$cmnBank->Name}}
                                                </td>
                                                <td>
                                                    <input type="hidden" name="EtlContractorCapacity[{{$randomKey}}][Sequence]" class="sequence" value="{{$cmnBank->Sequence or 1}}"/>
                                                    <input type="hidden" name="EtlContractorCapacity[{{$randomKey}}][CmnBankId]" value="{{$cmnBank->Id}}"/>
                                                    <input type="text" name="EtlContractorCapacity[{{$randomKey}}][Amount]" value="{{$edit?$cmnBank->Amount:''}}" class="form-control number input-sm" />
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <?php $count++; ?>
                            @endforeach
                        </div>

                        <div class="col-md-5 col-md-offset-1" style="border: 2px solid #000;">
                            <h5 class="font-green-seagreen bold">Price Preference</h5>
                            <div class="form-group">
                                <label>Ownership Type <span class="font-red">*</span></label>
                                <select name="CmnOwnershipTypeId" id="" class="form-control input-sm required">
                                    <option value="">---SELECT ONE---</option>
                                    @foreach($cmnOwnershipTypes as $cmnOwnershipType)
                                        <option value="{{$cmnOwnershipType->Points}}" @if($edit && ($cmnOwnershipType->Points == $tender->CmnOwnershipTypeId))selected="selected"@endif>{{$cmnOwnershipType->Name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Bhutanese Employment <span class="font-red">*</span></label>
                                <select name="BhutaneseEmployement" id="" class="form-control input-sm required">
                                    <option value="">---SELECT ONE---</option>
                                    @foreach($bhutaneseEmployment as $bhtEmp)
                                        <option value="{{$bhtEmp->Points}}" @if($edit && ($bhtEmp->Points == $tender->BhutaneseEmployement))selected="selected"@endif>{{$bhtEmp->Name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            {{-- <div class="form-group">
                                <label>Employment of VTI Graduate/Local Skilled Labour <span class="font-red">*</span></label>
                                <select name="EmploymentOfVTI" id="" class="form-control input-sm required">
                                    <option value="">---SELECT ONE---</option>
                                    @foreach($vtiEmployment as $vtiEmp)
                                        <option value="{{$vtiEmp->Points}}" @if($edit && ($vtiEmp->Points == $tender->EmploymentOfVTI))selected="selected"@endif>{{$vtiEmp->Name}}</option>
                                    @endforeach
                                    <option value="0">None</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Commitment of Internships to VTI Graduate <span class="font-red">*</span></label>
                                <select name="CommitmentOfInternship" id="" class="form-control input-sm required">
                                    <option value="">---SELECT ONE---</option>
                                    @foreach($vtiInternship as $vtiInt)
                                        <option value="{{$vtiInt->Points}}" @if($edit && ($vtiInt->Points == $tender->CommitmentOfInternship))selected="selected"@endif>{{$vtiInt->Name}}</option>
                                    @endforeach
                                    <option value="0">None</option>
                                </select>
                            </div> --}}
                        </div>
                    </div>
                @endforeach
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
                {{Form::hidden('ContractorType','LARGE')}}
                {{Form::hidden('MaxHrPoints',$maxHrPoints)}}
                {{Form::hidden('MaxEqPoints',$maxEqPoints)}}
                {{Form::hidden('CurrentTab',Input::has('currentTab')?'#'.Input::get('currentTab'):'#contractorhumanresource')}}
					<div class="form-body">
                        @if(empty($tenders))
                            <?php $contractorArray = $addContractors; ?>
                        @else
                            <?php $contractorArray = $tenders; ?>
                        @endif
                        <?php $count = 1;?>
                        @foreach($contractorArray as $tender)
                            <?php if($count==2)
                            {   
                                break;
                            }
                            ?>
                            {{Form::hidden('Id',$edit?$tender->Id:'')}}
                            {{Form::hidden('EtlTenderId',$tenderId)}}
                            <h4 class="bold">Work Id : <span class="font-green-seagreen ">{{$tender->WorkId}}</span></h4>
                            <p><span class="bold">Work Name : </span>{{$tender->NameOfWork}}</p>
                            <p><span class="bold">Description : </span>{{$tender->DescriptionOfWork}}</p>
                            <p><span class="bold">Dzongkhag : </span>{{$tender->Dzongkhag}}</p>
                            @if(empty($tenders))
                            <p><span class="bold">CDB No. : </span>{{$tender->CDBNo}} </p>
                            <p><span class="bold">Name of Bidder : </span>{{$tender->NameOfFirm}}</p><br/>
                            @endif
                        {{--Modal content was here--}}
                        <?php  $count = 2;?>
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
														<th>
															 Tier
														</th>
														<th class="">
															 Designation
														</th>
														<th class="">
															 Qualification
														</th>
														<th class="">
															 Point
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
															{{$hr->Tier}}
														</td>
														<td>
															{{$hr->Designation}}
														</td>
														<td>
															{{$hr->Qualification}}
														</td>
														<td>
															{{$hr->Points}}
														</td>
													</tr>
                                                    <?php $count++; ?>
                                                    @empty
                                                        <tr><td colspan="5" class="text-center font-red">No data to display</td></tr>
                                                    @endforelse
												</tbody>
											</table>
                                            <a href="#addcontractormodal" data-toggle="modal" class="btn purple">@if($edit)Edit Contractor's Quoted Amount/ Credit Line/ Preference Score @else Add Contractor @endif</a>
                                            @if($edit)
                                            <a href="#" onclick="checkTradeLicense('{{$tradeLicenseNo}}')" class="btn blue">Check Trade License</a>
                                            <a onclick="checkTaxClearance('{{$tradeLicenseNo}}','{{$taxTpnNo}}')" class="btn green">Check Tax Clearance</a>
                                            <a target="_blank" href="{{URL::to('etl/seekclarification/'.$tenderId.'/'.$contractorCDBno.'/'.$contractorId)}}" class="btn default bg-green-haze">
                                                Seek clarification
                                            </a>
                                            @endif
                                            <br><br>
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
														<th>
															 Tier
														</th>
														<th class="">
															 Designation
														</th>
														<th class="">
															 Qualification
														</th>
														<th class="">
															 Point
														</th>	
														<th class=""></th>								
													</tr>
												</thead>
												<tbody>
                                                <?php $hrTotal = 0; 
                                                        $row = 0;
                                                ?>
                                                    @foreach($contractorHR as $contHR)
                                                    <?php $randomKey = randomString(); ?>
													<tr class="criteriaTr">
                                                        <td>
                                                            <button type="button" class="deletetablerow deleterowfromdb"><i class="fa fa-times fa-lg"></i></button>
                                                        </td>
														<td>
															<div class="input-group">
                                                                <input type="hidden" name="EtlContractorHumanResource[{{$randomKey}}][Id]" value="{{$contHR->Id}}" data-table="etlcontractorhumanresource" class="row-id not-required resetKeyForNew"/>
																<input id="newpassword" class="form-control {{--checkhrtr--}} cidforwebservicetr required resetKeyForNew input-sm input-large etool-hr checkHumanResource" type="text" name="EtlContractorHumanResource[{{$randomKey}}][CIDNo]" placeholder="CID No." value="{{$contHR->CIDNo}}">
															</div>
														</td>
                                                        <td>
                                                            <div class="input-group">
                                                                <input class="form-control required resetKeyForNew input-sm input-large hr-name namefromwebservice" type="text" name="EtlContractorHumanResource[{{$randomKey}}][Name]" placeholder="Name" value="{{$contHR->Name}}">
                                                            </div>
                                                        </td>
														<td>
															<select name="EtlContractorHumanResource[{{$randomKey}}][EtlTierId]" id=""class="form-control input-sm EtlHrTierId resetKeyForNew">
																<option value="">---SELECT ONE---</option>
                                                                @foreach($hrTiers as $hrTier)
                                                                    <option value="{{$hrTier->Id}}" @if($contHR->EtlTierId == $hrTier->Id)selected="selected"@endif>{{$hrTier->Name}}</option>
                                                                @endforeach
															</select>
														</td>
														<td>
															<select name="EtlContractorHumanResource[{{$randomKey}}][CmnDesignationId]" id="designation" class="form-control input-sm EtlHrDesignationId resetKeyForNew">
																<option value="">---SELECT ONE---</option>
                                                                @foreach($hrDesignations as $hrDesignation)
                                                                    <option value="{{$hrDesignation->Id}}" @if($edit)<?php if($hrDesignation->EtlTierId != $contHR->EtlTierId): ?>class="hide" disabled="disabled"<?php endif; ?>@endif  data-criteriaHRId="{{$hrDesignation->EtlCriteriaHumanResourceId}}" @if(($contHR->CmnDesignationId == $hrDesignation->Id)&&($hrDesignation->EtlTierId == $contHR->EtlTierId)) selected="selected" @endif @if(!$edit)class="hide"@endif data-tierid="{{$hrDesignation->EtlTierId}}" @if(!$edit)disabled="disabled"@endif>{{$hrDesignation->Designation}}</option>
                                                                @endforeach
															</select>
														</td>
														<td>
															<select name="EtlContractorHumanResource[{{$randomKey}}][Qualification]" id="" class="form-control input-sm EtlHrQualificationId resetKeyForNew etool-total">
																<option value="">---SELECT ONE---</option>
                                                                @foreach($hrQualifications as $hrQualification)
                                                                    <option value="{{htmlspecialchars_decode($hrQualification->Qualification)}}" @if($edit)<?php if($hrQualification->CmnDesignationId != $contHR->CmnDesignationId): ?>class="hide" disabled="disabled"<?php endif; ?>@endif @if((htmlspecialchars_decode($contHR->Qualification) == htmlspecialchars_decode($hrQualification->Qualification)) && ($hrQualification->EtlTierId == $contHR->EtlTierId) && ($hrQualification->CmnDesignationId == $contHR->CmnDesignationId))selected="selected"@endif @if(!$edit)class="hide"@endif data-tierid="{{$hrQualification->EtlTierId}}" data-designationid="{{$hrQualification->CmnDesignationId}}" data-points="{{$hrQualification->Points}}" @if(!$edit)disabled="disabled"@endif>{{$hrQualification->Qualification}}</option>
                                                                @endforeach
															</select>
														</td>
														<td>
                                                            <input type="hidden" name="EtlContractorHumanResource[{{$randomKey}}][Points]" value="{{$contHR->Points}}" readonly class="form-control not-required input-sm EtlHrPoints resetKeyForNew" />
															<input type="text" name="EtlContractorHumanResource[{{$randomKey}}][XX]" value="{{$contHR->Points}}" readonly class="form-control not-required text-right to-total input-sm EtlHrPointsReduced resetKeyForNew" />
														    <?php $hrTotal += ($contHR->Points); ?>
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-primary btn-sm checkHRbtn">Check</button>
                                                        </td>
													@endforeach
                                                    <tr class="notremovefornew">
                                                        <td>
                                                            <button type="button" class="addtablerow"><i class="fa fa-plus fa-lg"></i></button>
                                                        </td>
                                                        <td colspan="5" class="bold text-right">Total:</td>
                                                        <td><input type="text" readonly class="total form-control text-right not-required input-sm" value="{{($maxHrPoints!=0)?number_format((($hrTotal/.25)/$maxHrPoints * 25),3):''}}"/></td>
                                                    </tr>
												</tbody>
											</table>
										</div>
										<div class="tab-pane" id="contractorequipment">
											<h5 class="bold">Equipment Criteria</h5>
											<table class="table table-bordered table-striped table-condensed flip-content">
												<thead class="flip-content">
													<tr>
														<th>
															Sl No.
														</th>
														<th>
															 Tier
														</th>
														<th class="">
															 Name
														</th>
														<th class="">
															 Quantity
														</th>
														<th class="">
															 Point
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
															{{$eq->Tier}}
														</td>
														<td>
															{{$eq->Equipment}}
														</td>
														<td>
															{{$eq->Quantity}}
														</td>
														<td>
															{{$eq->Points}}
														</td>
													</tr>
                                                    <?php $count++; ?>
                                                    @empty
                                                        <tr><td colspan="5" class="text-center font-red">No data to display</td></tr>
                                                    @endforelse
												</tbody>
											</table>
                                            <a href="#addcontractormodal" data-toggle="modal" class="btn purple">@if($edit)Edit Contractor's Quoted Amount/ Credit Line/ Preference Score @else Add Contractor @endif</a>
                                            <br><br>
                                            <h5 class="bold">Equipments for {{$tender->WorkId}} @if($contractorCDBNos != '')(CDB No.: {{$contractorCDBNos[0]->CDBNo}})@endif</h5>
											<table class="table table-bordered table-striped table-condensed flip-content table-togglecontrols" id="addContractorEquipments">
												<thead class="flip-content">
													<tr>
														<th></th>

														<th>
															 Tier
														</th>
														<th class="">
															 Name
														</th>
														<th class="">
															 Owned/Hired
														</th>
                                                        <th width="20%">
                                                            Registration No.
                                                        </th>
                                                        <th>Points</th>
                                                        <th></th>
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
															<select name="EtlContractorEquipment[{{$randomKey}}][EtlTierId]" id="" class="form-control input-sm EtlEqTierId resetKeyForNew">
																<option value="">---SELECT ONE---</option>
                                                                @foreach($eqTiers as $eqTier)
                                                                    <option value="{{$eqTier->Id}}" @if($contEquipment->EtlTierId == $eqTier->Id)selected="selected"@endif>{{$eqTier->Name}}</option>
                                                                @endforeach
															</select>
														</td>
														<td>
															<select name="EtlContractorEquipment[{{$randomKey}}][CmnEquipmentId]" id="" class="form-control equipment equipmentforwebservicetr input-sm EtlEqEquipmentId resetKeyForNew">
																<option value="">---SELECT ONE---</option>
																@foreach($eqEquipments as $eqEquipment)
                                                                    <option value="{{$eqEquipment->Id}}" data-isregistered="{{$eqEquipment->IsRegistered}}" data-vehicletype="{{$eqEquipment->VehicleType}}" data-quantity="{{$eqEquipment->Quantity}}" data-criteriaeqid="{{$eqEquipment->EtlCriteriaEQId}}" @if($edit)<?php if($eqEquipment->EtlTierId != $contEquipment->EtlTierId): ?>disabled="disabled" class="hide"<?php endif; ?>@endif @if(($contEquipment->CmnEquipmentId == $eqEquipment->Id) && ($eqEquipment->EtlTierId == $contEquipment->EtlTierId))selected="selected"@else disabled="disabled" class="hide"@endif data-tierid="{{$eqEquipment->EtlTierId}}" data-points="{{number_format(round($eqEquipment->Points,3),3)}}" >{{$eqEquipment->Equipment}}</option>
                                                                @endforeach
															</select>
														</td>
														<td>
															<input type="hidden" name="EtlContractorEquipment[{{$randomKey}}][Quantity]" value="1" class="form-control not-required number resetKeyForNew input-sm" value="" />
														    <select name="EtlContractorEquipment[{{$randomKey}}][OwnedOrHired]" autocomplete="off" class="form-control input-sm resetKeyForNew etool-total">
                                                                <option value="">---SELECT ONE---</option>
                                                                <option value="1" @if($contEquipment->OwnedOrHired == 1)selected="selected"@endif>Owned</option>
                                                                <option value="2" @if($contEquipment->OwnedOrHired == 2)selected="selected"@endif>Hired</option>
                                                            </select>
                                                            <input type="hidden" name="EtlContractorEquipment[{{$randomKey}}][Points]" value="@if($contEquipment->OwnedOrHired == 1){{$contEquipment->Points}}@endif<?php if($contEquipment->OwnedOrHired == 2): ?>{{$contEquipment->Points}}<?php endif; ?>" class="form-control resetKeyForNew not-required input-sm calculatedpoints" value="" />
                                                        </td>
                                                        <td>
                                                            <div class="input-group">

                                                                <input id="newpassword" class="form-control input-sm input-large resetKeyForNew registration-no checkEquipment not-required" value="{{$contEquipment->RegistrationNo}}" type="text" name="EtlContractorEquipment[{{$randomKey}}][RegistrationNo]" placeholder="Registration No.">
                                                            </div>
                                                        </td>
                                                        <?php $eqTotal += $contEquipment->Points; ?>
                                                        <td><input type="text" class="calculatedpoints not-required to-total text-right input-sm form-control" readonly value="{{$contEquipment->Points}}"/></td>
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
                                                        <td><input readonly="readonly" class="text-right total form-control input-sm not-required" type="text" value="{{($maxEqPoints!=0)?number_format((($eqTotal/0.25)/$maxEqPoints * 25),3):''}}"/>
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
					<div class="form-actions">
						<div class="btn-set">
                            <a href="{{URL::to('etl/workevaluationdetails/'.$tenderId)}}" class="btn blue"><i class="m-icon-swapleft m-icon-white"></i>&nbsp;Back</a>
                            <button type="submit" class="btn green" id="saveAddContractor">Save</button>
							<a href="{{URL::to('etl/workevaluationdetails/'.$tenderId)}}" class="btn red">Cancel</a>
						</div>
					</div>
				{{Form::close()}}
				<!-- END FORM-->
			</div>
		</div>
	</div>
</div>
<div id="checkTaxClearance" class="modal fade in" role="dialog" aria-labelledby="modalmessagebox" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h3 id="" class="modal-title font-red-intense">Tax Clerance Details</h3>
            </div>
            <div class="modal-body">
                <div>
                    <div>Business Name : <label id="businessName"></label></div>
                    <div>License No. : <label id="licenseNo"></label></div>
                    <div>Status : <label id="status"></label></div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function checkTaxClearance(licenseNo,tpnNo)
    {
        var accessTokenData = "";
        $.ajax({
        type: "GET",
        dataType: "json",
        url: "http://119.2.104.81:8181/DNPDatahub/api/getToken",
        error: function(jqXHR, textStatus, errorThrown){
        },
            success: function(data){ 
                accessTokenData = data[0].split(":");
                accessTokenData = accessTokenData[1].split(",");
                accessTokenData = accessTokenData[0].split('"');
                accessTokenData = accessTokenData[1];
          



                    $.ajax({
                        type: "GET",
                        dataType: "json",   
                        url: "http://119.2.104.81:8181/DNPDatahub/api/getTaxInformation?licenseNo="+licenseNo+"&tpnNo="+tpnNo+"&token="+accessTokenData,
                        error: function(jqXHR, textStatus, errorThrown){
                        $(".egpTenderIdErrorMessage").text("Something went wrong while trying to pull data from e-GP");
                        $(".egpTenderIdErrorMessage").show();
                        },
                        success: function(data){ 
                            for(var i =0;i<data.length;i++)
                            {  
                                var businessName = data[i].businessName;
                                var licenseNo = data[i].licenseNo;
                                var status = data[i].status;
                                $("#businessName").text(businessName);
                                $("#licenseNo").text(licenseNo);
                                $("#status").text(status);
                                $("#checkTaxClearance").modal("show");


                            }
                        }
                    });
            }
        });
    }

    function checkTradeLicense(licenseNo)
    {
        var accessTokenData ="52b0b1ac-fe95-38cb-bfa0-3da197d9afe2";

        $.ajax({
        type: "GET",
        dataType: "json",
        url: "http://119.2.104.81:8181/DNPDatahub/api/getToken",
        error: function(jqXHR, textStatus, errorThrown){
        },
            success: function(data){ 
                accessTokenData = data[0].split(":");
                accessTokenData = accessTokenData[1].split(",");
                accessTokenData = accessTokenData[0].split('"');
                accessTokenData = accessTokenData[1];
          

        $.ajax({
            type: "GET",
            dataType: "json",   
            url: "http://119.2.104.81:8181/DNPDatahub/api/getLicenseDetails?licenseNo="+licenseNo+"&token="+accessTokenData,
            error: function(jqXHR, textStatus, errorThrown){
            $(".egpTenderIdErrorMessage").text("Something went wrong while trying to pull data from e-GP");
            $(".egpTenderIdErrorMessage").show();
            },
            success: function(data){ 
                for(var i =0;i<data.length;i++)
                {
                    var businessName = data[i].businessName;
                    var dateOfExpiry = data[i].dateOfExpiry;

                }
            }
        });

        }
        });
    }



    
</script>
@stop