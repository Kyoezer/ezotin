@extends('reportsmaster')
@section('content')
    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption">
                <?php $parameters = Input::all(); $parameters['export']='excel'; ?>
                <i class="fa fa-cogs"></i>Complete Report For Workid: {{Input::get('WorkId')}} &nbsp;&nbsp;@if(!Input::has('export')) <?php $parameters['export'] = 'print'; ?><a href="{{route('etoolrpt.evaluationdetails',$parameters)}}" class="btn btn-sm purple" target="_blank"><i class="fa fa-print"></i> Print</a>@endif
            </div>
        </div>
        <div class="portlet-body flip-scroll">
            @if(Input::get('export')!='print')
            {{Form::open(array('url'=>Request::url(),'method'=>'get'))}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">Work Id</label>
                            <input type="text" class="form-control input-sm" name="WorkId" value="{{Input::get('WorkId')}}"/>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label class="control-label">&nbsp;</label>
                        <div class="btn-set">
                            <button type="submit" class="btn blue-hoki btn-sm">Search</button>
                            <a href="{{Request::url()}}" class="btn grey-cascade btn-sm">Clear</a>
                        </div>
                    </div>
                </div>
            </div>
            {{Form::close()}}
            @endif
            @if(isset($tenderDetails[0]->TenderId) && $tenderDetails[0]->TenderId)
            <h4 class="bold">Work Details</h4>
            <table class="table table-bordered table-condensed">
                <tbody>
                    @foreach($tenderDetails as $tenderDetail)
                        <tr>
                            <td class="bold text-right">Name of work:</td>
                            <td>{{$tenderDetail->NameOfWork}}</td>
                            <td class="bold text-right">Work ID:</td>
                            <td>{{$tenderDetail->WorkId}}</td>
                            <td class="bold text-right">Procuring Agency:</td>
                            <td>{{$tenderDetail->ProcuringAgency}}</td>
                            <td class="bold text-right">Class & Category:</td>
                            <td>{{$tenderDetail->Classification.", ".$tenderDetail->Category}}</td>
                        </tr>
                        <tr>
                            <td class="bold text-right">Dzongkhag:</td>
                            <td>{{$tenderDetail->Dzongkhag}}</td>
                            <td class="bold text-right">Estimated Project Cost:</td>
                            <td>{{$tenderDetail->ProjectEstimateCost}}</td>
                            <td class="bold text-right">Contract Period:</td>
                            <td>{{$tenderDetail->ContractPeriod." month(s)"}}</td>
                            <td></td>
                            <td></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <h4 class="bold">Equipment Criteria</h4>
            <table class="table table-bordered table-condensed">
                <thead>
                    <tr>
                        <th>Sl.No.</th>
                        <th>Tier</th>
                        <th>Equipment Name</th>
                        <th>No of Equipment</th>
                        <th>Equipment Point</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $count = 1; ?>
                    @foreach($eqCriteria as $equipmentCriteria)
                    <tr>
                        <td>{{$count}}</td>
                        <td>{{$equipmentCriteria->Tier}}</td>
                        <td>{{$equipmentCriteria->Equipment}}</td>
                        <td>{{$equipmentCriteria->Quantity}}</td>
                        <td>{{$equipmentCriteria->Points}}</td>
                    </tr>
                    <?php $count++; ?>
                    @endforeach
                </tbody>
            </table>

            <h4 class="bold">Human Resource Criteria</h4>
            <table class="table table-bordered table-condensed">
                <thead>
                <tr>
                    <th>Sl.No.</th>
                    <th>Tier</th>
                    <th>Name</th>
                    <th>Qualification</th>
                    <th>Point</th>
                </tr>
                </thead>
                <tbody>
                <?php $count = 1; ?>
                @foreach($hrCriteria as $humanResourceCriteria)
                    <tr>
                        <td>{{$count}}</td>
                        <td>{{$humanResourceCriteria->Tier}}</td>
                        <td>{{$humanResourceCriteria->Designation}}</td>
                        <td>{{$humanResourceCriteria->Qualification}}</td>
                        <td>{{$humanResourceCriteria->Points}}</td>
                    </tr>
                    <?php $count++; ?>
                @endforeach
                </tbody>
            </table>

            <h4 class="bold">Individuals Equipment Details</h4>
                @foreach($bidContractors as $bidContractor)
                    <table class="table table-condensed table-bordered">
                        <thead>
                            <tr style="border-bottom: 1px solid #DDDDDD;">
                                <th>CDB No.: </th>
                                <th colspan="5">{{$cdbNos[$bidContractor->Id][0]->CDBNo}} @if($bidContractor->JointVenture == 1){{"(Joint Venture)"}}@endif</th>
                            </tr>
                            <tr>
                                <th>Sl.No.</th>
                                <th>Equipment Name</th>
                                <th>Registration No.</th>
                                <th>Tier</th>
                                <th>Type</th>
                                <th>Point</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $count = 1; ?>
                            @foreach($contractorEquipments[$bidContractor->Id] as $contractorEquipment)
                                <tr>
                                    <td>{{$count}}</td>
                                    <td>{{$contractorEquipment->Equipment}}</td>
                                    <td>{{$contractorEquipment->RegistrationNo}}</td>
                                    <td>{{$contractorEquipment->Tier}}</td>
                                    <td>{{($contractorEquipment->OwnedOrHired == 2)?"Hired":"Owned"}}</td>
                                    <td>{{$contractorEquipment->Points}}</td>
                                </tr>
                                <?php $count++; ?>
                            @endforeach
                        </tbody>
                    </table>
                @endforeach

                <h4 class="bold">Individuals Human Resource Details</h4>
                @foreach($bidContractors as $bidContractor)
                    <table class="table table-condensed table-bordered">
                        <thead>
                        <tr style="border-bottom: 1px solid #DDDDDD;">
                            <th>CDB No.: </th>
                            <th colspan="5">{{$cdbNos[$bidContractor->Id][0]->CDBNo}} @if($bidContractor->JointVenture == 1){{"(Joint Venture)"}}@endif</th>
                        </tr>
                        <tr>
                            <th>Sl.No.</th>
                            <th>Designation</th>
                            <th>HR Name</th>
                            <th>CID No.</th>
                            <th>Qualification</th>
                            <th>Tier</th>
                            <th>Point</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $count = 1; ?>
                        @foreach($contractorHRs[$bidContractor->Id] as $contractorHR)
                            <tr>
                                <td>{{$count}}</td>
                                <td>{{$contractorHR->Designation}}</td>
                                <td>{{$contractorHR->Name}}</td>
                                <td>{{$contractorHR->CIDNo}}</td>
                                <td>{{$contractorHR->Qualification}}</td>
                                <td>{{$contractorHR->Tier}}</td>
                                <td>{{$contractorHR->Points}}</td>
                            </tr>
                            <?php $count++; ?>
                        @endforeach
                        </tbody>
                    </table>
                @endforeach
                <h4 class="bold">Contractor Details</h4>
                <?php $count = 1; ?>
                @foreach($bidContractors as $bidContractor)
                    <?php
                        $technicalScore = $contractorScores[$bidContractor->Id][0]->Score1+$contractorScores[$bidContractor->Id][0]->Score2+$contractorScores[$bidContractor->Id][0]->Score3+$contractorScores[$bidContractor->Id][0]->Score4+$contractorScores[$bidContractor->Id][0]->Score5+$contractorScores[$bidContractor->Id][0]->Score6;
                        $preferenceScore = ($contractorScores[$bidContractor->Id][0]->Score7+$contractorScores[$bidContractor->Id][0]->Score8+$contractorScores[$bidContractor->Id][0]->Score9)/10;
                        $financialScore = ($technicalScore>=$qualifyingScore)?(90 * ($lowestBid/$contractorAmounts[$bidContractor->Id][0]->FinancialBidQuoted)):0;
                        $grandTotal = ($technicalScore>=$qualifyingScore)?($financialScore + $preferenceScore):0;
                    ?>
                    <table class="table table-bordered table-condensed">
                        <tbody>
                            <tr>
                                <td class="bold">CDB No.</td>
                                <td colspan="2" style="font-size: 13pt;">{{$cdbNos[$bidContractor->Id][0]->CDBNo}} @if($bidContractor->JointVenture == 1){{"(Joint Venture)"}}@endif</td>
                            </tr>
                            <tr>
                                <td rowspan="4" class="bold">Capability</td>
                                <td>Similar Work Experience</td>
                                <td>{{$contractorScores[$bidContractor->Id][0]->Score1}}</td>
                            </tr>
                            <tr>
                                <td>Access to adequate equipment</td>
                                <td>{{$contractorScores[$bidContractor->Id][0]->Score2}}</td>
                            </tr>
                            <tr>
                                <td>Availability of skilled manpower</td>
                                <td>{{$contractorScores[$bidContractor->Id][0]->Score3}}</td>
                            </tr>
                            <tr>
                                <td>Average performance score from previous work</td>
                                <td>{{$contractorScores[$bidContractor->Id][0]->Score4}}</td>
                            </tr>

                            <tr>
                                <td rowspan="3" class="bold">Capacity</td>
                                <td>Bid Capacity</td>
                                <td>{{$contractorScores[$bidContractor->Id][0]->Score5}}</td>
                            </tr>
                            <tr>
                                <td>Credit Line avaliable</td>
                                <td>{{$contractorScores[$bidContractor->Id][0]->Score6}}</td>
                            </tr>
                            <tr>
                                <td class="bold text-right">Total</td>
                                <td><b>{{$contractorScores[$bidContractor->Id][0]->Score6+$contractorScores[$bidContractor->Id][0]->Score5+$contractorScores[$bidContractor->Id][0]->Score4+$contractorScores[$bidContractor->Id][0]->Score3+$contractorScores[$bidContractor->Id][0]->Score2+$contractorScores[$bidContractor->Id][0]->Score1}}</b></td>
                            </tr>

                            <tr>
                                <td rowspan="4" class="bold">Preference Score</td>
                                <td>Status(incorporated, proprietorship, JV etc.)</td>
                                <td>{{$contractorScores[$bidContractor->Id][0]->Score7}}</td>
                            </tr>
                            <tr>
                                <td>Employment of VTI Graduates/local skilled labour</td>
                                <td>{{$contractorScores[$bidContractor->Id][0]->Score8}}</td>
                            </tr>
                            <tr>
                                <td>Commitment for internships to VIT graduates</td>
                                <td>{{$contractorScores[$bidContractor->Id][0]->Score9}}</td>
                            </tr>
                            <tr>
                                <td class="bold text-right">Total</td>
                                <td><b>{{$contractorScores[$bidContractor->Id][0]->Score9+$contractorScores[$bidContractor->Id][0]->Score8+$contractorScores[$bidContractor->Id][0]->Score7}}</b> (Out of 10% = <b>{{($contractorScores[$bidContractor->Id][0]->Score9+$contractorScores[$bidContractor->Id][0]->Score8+$contractorScores[$bidContractor->Id][0]->Score7)/10}}</b>)</td>
                            </tr>

                            <tr>
                                <td rowspan="4" class="bold">Financial</td>
                                <td>Quoted Amount</td>
                                <td>{{$contractorAmounts[$bidContractor->Id][0]->FinancialBidQuoted}}</td>
                            </tr>
                            <tr>
                                <td>Negotiated Amount</td>
                                <td>{{$contractorAmounts[$bidContractor->Id][0]->ContractPriceFinal}}</td>
                            </tr>
                            <tr>
                                <td>(+)(-) Estimation %</td>
                                <td>{{round((($contractorAmounts[$bidContractor->Id][0]->Amount-$tenderDetail->ProjectEstimateCost)/$tenderDetail->ProjectEstimateCost * 100),2)}}</td>
                            </tr>
                            <tr>
                                <td class="bold text-right">Financial Score</td>
                                <td class="bold">{{number_format($financialScore,2)}}</td>
                            </tr>
                            <tr>
                                <td rowspan="2" class="bold">Result</td>
                                <td class="bold text-right">Grand Total</td>
                                <td class="bold">{{number_format($grandTotal,2)}}</td>
                            </tr>
                            <tr>
                                <td class="bold text-right">Status</td>
                                <td class="bold">
                                    @if(!empty($contractorStatuses[$bidContractor->Id]))
                                        @if(($contractorStatuses[$bidContractor->Id][0]->AwardedAmount != NULL)&& ($contractorStatuses[$bidContractor->Id][0]->AwardedAmount>0)){{$tenderDetails[0]->Status}}@else @if($contractorScores[$bidContractor->Id][0]->Score10 == 0.00){{"Not Qualified"}}@else{{"H$count"}}@endif @endif
                                    @else
                                        @if($contractorScores[$bidContractor->Id][0]->Score10 == 0.00){{"Not Qualified"}}@else{{"H$count"}}@endif
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <?php $count++; ?>
                @endforeach
            @endif
        </div>
    </div>
@stop