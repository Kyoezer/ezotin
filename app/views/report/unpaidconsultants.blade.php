@extends('reportsmaster')
@section('pagescripts')
@stop
@section('content')
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
            <?php $parameters = Input::all(); $parameters['export']='excel'; if(isset($overrunReport)): if($overrunReport == 1):$route = "rpt.costoverrunreport";else:$route="rpt.costoverrunreportsummary"; endif; else: $route="contractorrpt.trackrecord"; endif; ?>
			<i class="fa fa-cogs"></i>@if(isset($overrunReport)){{"Cost overrun report"}}@else{{"Contractor's Track Record"}}@endif &nbsp;&nbsp;@if(!Input::has('export')) <a href="{{route($route,$parameters)}}" class="btn btn-sm green"><i class="fa fa-file-excel-o"></i> Export to Excel</a>    <?php $parameters['export'] = 'print'; ?><a href="{{route($route,$parameters)}}" class="btn btn-sm purple" target="_blank"><i class="fa fa-print"></i> Print</a>@endif
		</div>
	</div>
	<div class="portlet-body flip-scroll">
        DATE: {{$date}}
        <div class="table-responsive">
            <h3>CONSULTANTS</h3>
            <table class="table table-bordered table-striped table-condensed" id="">
                <thead class="flip-content">
                    <tr>
                        <th>Sl #</th>
                        <th>Name of Applicant</th>
                        <th>Type</th>
                        <th>Unpaid Amount</th>
                    </tr>
                </thead>
                <tbody>
                <?php $count = 1; ?>
                @foreach($consultantsService as $consultantServ)
                    <tr>
                        <td>{{$count++}}</td>
                        <td>{{$consultantServ->NameOfFirm.' ('.$consultantServ->CDBNo.')'}}</td>
                        <td>Service</td>
                <?php $totalFeeApplicable=0; $categoryClassificationFeeTotal=0;$lateFeeAmount=0;?>
                @foreach($appliedServices[$consultantServ->Id] as $appliedService)
                    <?php $randomKey = randomString(); ?>
                        @if($appliedService->Id==CONST_SERVICETYPE_RENEWAL || $appliedService->Id==CONST_SERVICETYPE_CHANGEOFCATEGORYCLASSIFICATION)
                                @if($hasRenewal[$consultantServ->Id] && $hasChangeInCategoryClassification[$consultantServ->Id])
                                    @if($appliedService->Id==CONST_SERVICETYPE_RENEWAL)
                                            @foreach($hasCategoryClassificationsFee[$consultantServ->Id] as $hasCategoryClassificationFee)
                                                <?php $approvedServiceCount = 0; $randomKey3 = randomString(); ?>
                                                        @if((bool)$hasCategoryClassificationFee->AppliedService!=NULL)
                                                        @else
                                                        @endif
                                                        @if((bool)$hasCategoryClassificationFee->VerifiedService!=NULL)
                                                        @else
                                                        @endif
                                                        @if((bool)$hasCategoryClassificationFee->ApprovedService!=NULL)
                                                        @else
                                                        @endif
                                                        @foreach($approvedCategoryServicesArray[$consultantServ->Id][$hasCategoryClassificationFee->ServiceCategoryId] as $approvedServiceIndividual)
                                                            {{--@if(!in_array($approvedServiceIndividual,$existingCategoryServicesArray[$consultantServ->Id][$hasCategoryClassificationFee->ServiceCategoryId]))--}}
                                                            <?php $approvedServiceCount++; ?>
                                                            {{--@endif--}}
                                                        @endforeach
                                                        <?php $categoryClassificationFeeTotal+=$approvedServiceCount * $feeAmount[$consultantServ->Id][0]->ConsultantAmount;?>

                                            @endforeach
                                                    <?php $appliedService->ConsultantAmount=$categoryClassificationFeeTotal; ?>

                                    @else
                                    @endif
                                @else
                                        @foreach($hasCategoryClassificationsFee[$consultantServ->Id] as $hasCategoryClassificationFee)
                                            <?php $randomKey2 = randomString(); ?>
                                            <?php $approvedServiceCount = 0; ?>
                                                    @if((bool)$hasCategoryClassificationFee->VerifiedService!=NULL)
                                                    @else
                                                    @endif
                                                    @if((bool)$hasCategoryClassificationFee->ApprovedService!=NULL)
                                                    @else
                                                    @endif
                                                    @if($appliedService->Id==CONST_SERVICETYPE_RENEWAL)
                                                        @foreach($approvedCategoryServicesArray[$consultantServ->Id][$hasCategoryClassificationFee->ServiceCategoryId] as $approvedServiceIndividual)
                                                            {{--@if(!in_array($approvedServiceIndividual,$existingCategoryServicesArray[$consultantServ->Id][$hasCategoryClassificationFee->ServiceCategoryId]))--}}
                                                            <?php $approvedServiceCount++; ?>
                                                            {{--@endif--}}
                                                        @endforeach
                                                    @else
                                                        @foreach($approvedCategoryServicesArray[$consultantServ->Id] as $approvedServiceIndividual)
                                                            @if(!in_array($approvedServiceIndividual,$existingCategoryServicesArray[$consultantServ->Id][$hasCategoryClassificationFee->ServiceCategoryId]))
                                                                <?php $approvedServiceCount++; ?>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                    <?php $categoryClassificationFeeTotal+=$approvedServiceCount* $feeAmount[$consultantServ->Id][0]->ConsultantAmount;?>
                                        @endforeach
                                                <?php $appliedService->ConsultantAmount=$categoryClassificationFeeTotal; ?>

                                @endif
                        @elseif($appliedService->Id==CONST_SERVICETYPE_LATEFEE)

                                            <?php $lateFeeAfterGracePeriod=$hasLateFeeAmount[$consultantServ->Id][0]->PenaltyNoOfDays-30-1; ?>
                                            {{--ADDED--}}
                                            @if(($hasLateFeeAmount[$consultantServ->Id][0]->PenaltyNoOfDays-1)>30)
                                                <?php $lateFeeAfterGracePeriod=$hasLateFeeAmount[$consultantServ->Id][0]->PenaltyNoOfDays-30-1; ?>
                                            @else
                                                <?php $lateFeeAfterGracePeriod = 0; ?>
                                            @endif
                                            {{--Added--}}
                                            @if($lateFeeAfterGracePeriod>0)
                                            @endif
                                            <?php $lateFeeAmount=(int)$lateFeeAfterGracePeriod*(int)$hasLateFeeAmount[$consultantServ->Id][0]->PenaltyLateFeeAmount; ?>
                                            <?php $appliedService->ConsultantAmount=$lateFeeAmount;?>
                                            @if($generalInformation[$consultantServ->Id][0]->WaiveOffLateFee == 1)
												@endif
                                                    @if($generalInformation[$consultantServ->Id][0]->WaiveOffLateFee == 1)
                                            @endif

                        @endif
                            @if((bool)$appliedService->ConsultantAmount!=NULL)
                                <?php $totalFeeApplicable+=$appliedService->ConsultantAmount; ?>
                                @if($appliedService->Id==CONST_SERVICETYPE_LATEFEE)
                                    @if($generalInformation[$consultantServ->Id][0]->WaiveOffLateFee == 1)
											@endif
                                            @endif
                                            @if($appliedService->Id==CONST_SERVICETYPE_LATEFEE)
                                                @if($generalInformation[$consultantServ->Id][0]->WaiveOffLateFee == 1)

                                        &nbsp;
                                        <input type="hidden" name="crpconsultantservicepayment[{{$randomKey}}][NewLateFeeAmount]" value="{{$generalInformation[$consultantServ->Id][0]->NewLateFeeAmount}}"/>
                                    @endif
                                @endif
                            @else
                            @endif
                @endforeach
                    <td>
                        @if((int)$generalInformation[$consultantServ->Id][0]->WaiveOffLateFee == 1)
                            {{{number_format((int)$totalFeeApplicable-((int)$lateFeeAmount-(int)$generalInformation[$consultantServ->Id][0]->NewLateFeeAmount),2)}}}
                        @else
                            {{{number_format($totalFeeApplicable,2)}}}
                        @endif
                    </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
	</div>
</div>
@stop