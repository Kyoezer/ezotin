@extends('horizontalmenumaster')
@section('content')
    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-cogs"></i>Contractors Scoring
            </div>
        </div>
        <div class="portlet-body flip-scroll" style="overflow-x: scroll;">
            <div class="col-md-10">
            <table class="table table-bordered table-condensed">
                <tbody>
                    @foreach($tenderDetails as $tenderDetail)
                    <tr>
                        <td class="bold">Work ID:</td>
                        <td>{{$tenderDetail->WorkId}}</td>
                        <td class="bold">Class & Category</td>
                        <td>{{$tenderDetail->Classification.", ".$tenderDetail->Category}}</td>
                        <td class="bold">Estimated Project Cost</td>
                        <td>{{$tenderDetail->ProjectEstimateCost}}</td>
                        <td class="bold">Name of work</td>
                        <td>{{$tenderDetail->NameOfWork}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <?php $rankCount = 1; $grandTotalArray = array(); $IsBhutaneseEmp = 0;?>
            @foreach($contractorScores as $contractorScore)
                    <?php
                        // $technicalScore = $contractorScore->Score1+$contractorScore->Score2+$contractorScore->Score3+$contractorScore->Score4+$contractorScore->Score5+$contractorScore->Score6+$contractorScore->Score_Works;
                        // $preferenceScore = ($contractorScore->Score7+$contractorScore->Score8+$contractorScore->Score9+$contractorScore->Score11)/10;
                        // $financialScore = ($technicalScore>=$qualifyingScore)?(90 * ($lowestBid/$contractorScore->FinancialBidQuoted)):0;
                        // $grandTotal = ($technicalScore>=$qualifyingScore)?($financialScore + $preferenceScore):0;
                        
                        
                        $technicalScore = $contractorScore->Score1+$contractorScore->Score2+$contractorScore->Score3+$contractorScore->Score4+$contractorScore->Score5+$contractorScore->Score6+$contractorScore->Score_Works;
                        $preferenceScore = ($contractorScore->Score7+$contractorScore->Score8+$contractorScore->Score9+$contractorScore->Score11)/10;
                        $thirtyPercentTechincalScore = number_format($technicalScore*0.30,2);
                        $financialScore = ($technicalScore>=$qualifyingScore)?(70 * ($lowestBid/$contractorScore->FinancialBidQuoted)):0;
                        $grandTotal = ($technicalScore>=$qualifyingScore)?($financialScore + $thirtyPercentTechincalScore):0;
                        
                        
                        
                        array_push($grandTotalArray,$grandTotal);
                        $IsBhutaneseEmp = $contractorScore->IsBhutaneseEmp;

                    ?>
                @endforeach
            <table class="table table-bordered table-condensed table-small-font" id="table-print-small">
                <thead>
                    <tr>
                        <th style=""></th>
                        <th colspan="8" style="background: #22cfc6;" class="text-center">Stage 1 (Technical)</th>
                        <th colspan="<?php if($IsBhutaneseEmp=='Y')echo '5';else echo '5';?>" style="background: #B1A9EE;" class="text-center">Stage 2 (Financial)</th>
                        <th colspan="2" style="background: #DCE8AB;" class="text-center"></th>
                    </tr>
                    <tr>
                        <th style="border-top: 1px solid #DDDDDD; border-right: 1px solid #DDDDDD;"></th>
                        <th colspan="5" class="text-center" style="background: #22cfc6; border-top: 1px solid #DDDDDD; border-right: 1px solid #DDDDDD;">CAPABILITY</th>
                        <th colspan="2" class="text-center" style="background: #22cfc6; border-top: 1px solid #DDDDDD; border-right: 1px solid #DDDDDD;">CAPACITY</th>
                        <th class="text-center" style="background: #22cfc6;border-top: 1px solid #DDDDDD; border-right: 1px solid #DDDDDD;">Technical Score</th>
                        <th class="text-center" style="background: #B1A9EE; border-top: 1px solid #DDDDDD; border-right: 1px solid #DDDDDD;">Technical Score</th>


                        <!-- <th class="text-center" colspan="<?php if($IsBhutaneseEmp=='Y')echo '3';else echo '4';?>" style="background: #B1A9EE; border-top: 1px solid #DDDDDD; border-right: 1px solid #DDDDDD;">
                            PREFERENCE SCORE
                        </th> -->
                        
                        <th colspan="3" style="background: #B1A9EE; border-top: 1px solid #DDDDDD; border-right: 1px solid #DDDDDD;"></th>
                        <th class="text-center" style="background: #B1A9EE; border-top: 1px solid #DDDDDD; border-right: 1px solid #DDDDDD;">Financial Score</th>


                        <th style="background: #DCE8AB; border-top: 1px solid #DDDDDD; border-right: 1px solid #DDDDDD;"></th>
                        <th style="background: #DCE8AB; border-top: 1px solid #DDDDDD; border-right: 1px solid #DDDDDD;"></th>
                    </tr>
                    <tr>
                        <th class="text-center" style="border-top: 1px solid #DDDDDD; border-right: 1px solid #DDDDDD;">CDB No</th>
                        <th class="text-center" style="border-top: 1px solid #DDDDDD; border-right: 1px solid #DDDDDD;">Similar Work</th>
                        <th class="text-center" style="border-top: 1px solid #DDDDDD; border-right: 1px solid #DDDDDD;">Equipment Score</th>
                        <th class="text-center" style="border-top: 1px solid #DDDDDD; border-right: 1px solid #DDDDDD;">HR Score</th>
                        <th class="text-center" style="border-top: 1px solid #DDDDDD; border-right: 1px solid #DDDDDD;">APS</th>
                        <th class="text-center" style="border-top: 1px solid #DDDDDD; border-right: 1px solid #DDDDDD;">Works (any category) completed</th>
                        <th class="text-center" style="border-top: 1px solid #DDDDDD; border-right: 1px solid #DDDDDD;">Bid Capacity</th>
                        <th class="text-center" style="border-top: 1px solid #DDDDDD; border-right: 1px solid #DDDDDD;">Credit Line</th>
                        <th class="text-center" style="border-top: 1px solid #DDDDDD; border-right: 1px solid #DDDDDD;">100%</th>
                        <!-- <th class="text-center" style="border-top: 1px solid #DDDDDD; border-right: 1px solid #DDDDDD;">
                            Ownership Type
                        </th>
                        @if($IsBhutaneseEmp=='Y')
                            <th class="text-center" style="border-top: 1px solid #DDDDDD; border-right: 1px solid #DDDDDD;">Bhutanese Employment</th>
                        @else
                            <th class="text-center" style="border-top: 1px solid #DDDDDD; border-right: 1px solid #DDDDDD;">Employment of VTI</th>
                            <th class="text-center" style="border-top: 1px solid #DDDDDD; border-right: 1px solid #DDDDDD;">Committment for Internships to VTI graduates</th>
                        @endif
                        <th class="text-center" style="border-top: 1px solid #DDDDDD; border-right: 1px solid #DDDDDD;">
                            10%
                        </th> -->
                        <th class="text-center" style="border-top: 1px solid #DDDDDD; border-right: 1px solid #DDDDDD;">30%</th>
                        <th class="text-center" style="border-top: 1px solid #DDDDDD; border-right: 1px solid #DDDDDD;">Quoted Amount</th>
                        <th class="text-center" style="border-top: 1px solid #DDDDDD; border-right: 1px solid #DDDDDD;">Negotiated Amount</th>
                        <th class="text-center" style="border-top: 1px solid #DDDDDD; border-right: 1px solid #DDDDDD;">(+)(-) Estimation %</th>
                        <th class="text-center" style="border-top: 1px solid #DDDDDD; border-right: 1px solid #DDDDDD;">70%</th>
                        <th class="text-center" style="border-top: 1px solid #DDDDDD; border-right: 1px solid #DDDDDD;">Grand Total</th>
                        <th class="text-center" style="border-top: 1px solid #DDDDDD; border-right: 1px solid #DDDDDD;">Status</th>
                    </tr>
                </thead>
                <tbody>
               
                <?php rsort($grandTotalArray);  ?>
                @foreach($contractorScores as $contractorScore)
                    <?php
                        $technicalScore = $contractorScore->Score1+$contractorScore->Score2+$contractorScore->Score3+$contractorScore->Score4+$contractorScore->Score5+$contractorScore->Score6+$contractorScore->Score_Works;
                        $preferenceScore = ($contractorScore->Score7+$contractorScore->Score8+$contractorScore->Score9+$contractorScore->Score11)/10;
                        $thirtyPercentTechincalScore = number_format($technicalScore*0.30,2);
                        $financialScore = ($technicalScore>=$qualifyingScore)?(70 * ($lowestBid/$contractorScore->FinancialBidQuoted)):0;
                        $grandTotal = ($technicalScore>=$qualifyingScore)?($financialScore + $thirtyPercentTechincalScore):0;
                    ?>
                    <tr>
                        <td>{{$contractorScore->CDBNo}}</td>
                        <td>{{number_format($contractorScore->Score1,2)}}</td>
                        <td>{{number_format($contractorScore->Score2,2)}}</td>
                        <td>{{number_format($contractorScore->Score3,2)}}</td>
                        <td>{{number_format($contractorScore->Score4,2)}}</td>
                        <td>{{number_format($contractorScore->Score_Works,2)}}</td>
                        <td>{{number_format($contractorScore->Score5,2)}}</td>
                        <td>{{number_format($contractorScore->Score6,2)}}</td>
                        <td>{{number_format($technicalScore,2)}}</td>
                        <td>{{$thirtyPercentTechincalScore}}</td>
                        <!-- <td>{{$contractorScore->Score7?number_format($contractorScore->Score7,2):0}}qq</td>
                        
                        
                        @if($IsBhutaneseEmp=='Y')
                            <td>{{$contractorScore->Score11?number_format($contractorScore->Score11,2):0}}ww</td>
                        @else
                            <td>{{$contractorScore->Score8?number_format($contractorScore->Score8,2):0}}</td>
                            <td>{{$contractorScore->Score9?number_format($contractorScore->Score9,2):0}}</td>
                        @endif


                        <td>{{number_format($preferenceScore,2)}}cc</td> -->
                        <td>{{number_format($contractorScore->FinancialBidQuoted,2)}}</td>
                        <td></td>
                        <td>{{round((($contractorScore->FinancialBidQuoted-$tenderDetails[0]->ProjectEstimateCost)/$tenderDetails[0]->ProjectEstimateCost * 100),2)}}</td>
                        <td>{{number_format($financialScore,2)}}</td>
                        <td>{{number_format($grandTotal,2)}}</td>
                        <td>
                            @if(isset($contractorScore->Score10) && doubleval($technicalScore)>=doubleval($qualifyingScore))
                                @if($contractorScore->Score10 != NULL  && $contractorScore->Score10 != 0)
                                    H{{array_search($grandTotal,$grandTotalArray) + 1 }}
                                    @if($tenderDetails[0]->Status != NULL && $contractorScore->ActualStartDate != NULL)
                                        ({{$tenderDetails[0]->Status}})
                                    @endif
                                @else
                                    <?php unset($grandTotalArray[array_search($grandTotal,$grandTotalArray)]); ?>
                                    Not Qualified
                                @endif
                            @else
                                <?php unset($grandTotalArray[array_search($grandTotal,$grandTotalArray)]); ?>
                                @if(!empty($contractorScore->ScoreId))
                                    Not Qualified
                                @else
                                    Process
                                @endif
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="clearfix"></div>

            <h5>Evaluation Committee:</h5>
            <table class="table table-bordered table-condensed">
                <thead>
                <tr>
                    <th>Sl.No</th>
                    <th>Name</th>
                    <th>Designation</th>
                    <th>Signature</th>
                </tr>
                </thead>
                <tbody>
                <?php $count = 1; ?>
                @forelse($evaluationCommittee as $member)
                <tr>
                    <td>{{$count}}</td>
                    <td>{{$member->Name}}</td>
                    <td>{{$member->Designation}}</td>
                    <td></td>
                </tr>
                    <?php $count++; ?>
                @empty
                    <tr>
                        <td colspan="4" class="font-red text-center">No data to display</td>
                    </tr>
                @endforelse
                <tr><td>Remarks:</td>
                    <td colspan="3"></td>
                </tr>
                </tbody>
            </table>

            <h5>Tender Committee:</h5>
            <table class="table table-bordered table-condensed">
                <thead>
                <tr>
                    <th>Sl.No</th>
                    <th>Name</th>
                    <th>Designation</th>
                    <th>Signature</th>
                </tr>
                </thead>
                <tbody>
                <?php $count = 1; ?>
                @forelse($tenderCommittee as $member2)
                    <tr>
                        <td>{{$count}}</td>
                        <td>{{$member2->Name}}</td>
                        <td>{{$member2->Designation}}</td>
                        <td></td>
                    </tr>
                    <?php $count++; ?>
                @empty
                    <tr>
                        <td colspan="4" class="font-red text-center">No data to display</td>
                    </tr>
                @endforelse
                <tr><td>Remarks:</td>
                    <td colspan="3"></td>
                </tr>
                </tbody>
            </table>
            </div>
        </div>
    </div>
@stop