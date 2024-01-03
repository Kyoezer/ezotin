@extends('emailmaster')
@section('emailcontent')
    <p><strong>{{$applicantName}} @if(isset($cdbNo))(CDB No.: {{$cdbNo}}) @endif</strong></p>
    @if(isset($tpn))
        <b>TPN: {{$tpn}}</b>
    @endif
    <p class="lead">Application No:<i>{{$applicationNo}}</i> Dt.<i>{{$applicationDate}}</i></p>
    <p>{{$mailMessage}}</p>
    <!-- Callout Panel -->
@stop
@if((bool)$mailIntendedTo!=NULL)
@section('feestructure')
    <h3>Fee Structure</h3>
    @if((int)$mailIntendedTo==1)
            <h5>Fee Details for Applied Services</h5>
            <table class="data-large" style="border-collapse: collapse; width: 100%; background-color: #fff; font-size: 10pt;" bgcolor="#fff">
                <thead>
                <tr>
                    <th style="background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2">Service(s) Applied</th>
                    <th style="background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2">Details</th>
                    <th style="background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2" width="10%" class="text-right">Amount (Nu.)</th>
                </tr>
                </thead>
                <tbody>
                <?php $totalFeeApplicable=0; $categoryClassificationFeeTotal=0;?>
                @foreach($appliedServices as $appliedService)
                    <tr>
                        <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff" @if($appliedService->Id!=CONST_SERVICETYPE_RENEWAL && $appliedService->Id!=CONST_SERVICETYPE_LATEFEE && $appliedService->Id!=CONST_SERVICETYPE_CHANGEOFCATEGORYCLASSIFICATION)colspan="2"@endif>
                            {{{$appliedService->ServiceName}}}
                        </td>
                        @if($appliedService->Id==CONST_SERVICETYPE_RENEWAL || $appliedService->Id==CONST_SERVICETYPE_CHANGEOFCATEGORYCLASSIFICATION)
                            <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">
                                @if($hasRenewal && $hasChangeInCategoryClassification)
                                    @if($appliedService->Id==CONST_SERVICETYPE_RENEWAL)
                                        <table class="table table-hover table-condensed">
                                            <thead>
                                            <tr class="success">
                                                <th style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">Category</th>
                                                <th style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">Existing</th>
                                                <th style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">Applied</th>
                                                <th style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">Verified</th>
                                                <th style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">Approved</th>
                                                <th style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">Amount</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($hasCategoryClassificationsFee as $hasCategoryClassificationFee)
                                                <tr>
                                                    <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">{{{$hasCategoryClassificationFee->MasterCategoryCode}}}</td>
                                                    <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">
                                                        @if((bool)$hasCategoryClassificationFee->FinalApprovedClassification!=NULL)
                                                            {{{$hasCategoryClassificationFee->FinalApprovedClassification}}}
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">
                                                        @if((bool)$hasCategoryClassificationFee->AppliedClassification!=NULL)
                                                            {{{$hasCategoryClassificationFee->AppliedClassification}}}
                                                            @if($hasCategoryClassificationFee->AppliedClassificationPriority<$hasCategoryClassificationFee->FinalClassificationPriority)
                                                                <i class="font-red"><small> (Downgraded)</small></i>
                                                            @elseif(!empty($hasCategoryClassificationFee->FinalClassificationPriority) && $hasCategoryClassificationFee->AppliedClassificationPriority>$hasCategoryClassificationFee->FinalClassificationPriority)
                                                                <i class="font-red"><small> (Upgraded)</small></i>
                                                            @elseif(empty($hasCategoryClassificationFee->FinalClassificationPriority))
                                                                <i class="font-red"><small>(New)</small></i>
                                                            @endif
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">
                                                        @if((bool)$hasCategoryClassificationFee->VerifiedClassification!=NULL)
                                                            {{{$hasCategoryClassificationFee->VerifiedClassification}}}
                                                            @if($hasCategoryClassificationFee->VerifiedClassificationPriority<$hasCategoryClassificationFee->FinalClassificationPriority)
                                                                <i class="font-red"><small> (Downgraded)</small></i>
                                                            @elseif(!empty($hasCategoryClassificationFee->FinalClassificationPriority) && $hasCategoryClassificationFee->VerifiedClassificationPriority>$hasCategoryClassificationFee->FinalClassificationPriority)
                                                                <i class="font-red"><small> (Upgraded)</small></i>
                                                            @elseif(empty($hasCategoryClassificationFee->FinalClassificationPriority))
                                                                <i class="font-red"><small>(New)</small></i>
                                                            @endif
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">
                                                        @if((bool)$hasCategoryClassificationFee->ApprovedClassification!=NULL)
                                                            {{{$hasCategoryClassificationFee->ApprovedClassification}}}
                                                            @if($hasCategoryClassificationFee->ApprovedClassificationPriority<$hasCategoryClassificationFee->FinalClassificationPriority)
                                                                <i class="font-red"><small> (Downgraded)</small></i>
                                                            @elseif(!empty($hasCategoryClassificationFee->FinalClassificationPriority) && $hasCategoryClassificationFee->ApprovedClassificationPriority>$hasCategoryClassificationFee->FinalClassificationPriority)
                                                                <i class="font-red"><small> (Upgraded)</small></i>
                                                            @elseif(empty($hasCategoryClassificationFee->FinalClassificationPriority))
                                                                <i class="font-red"><small>(New)</small></i>
                                                            @endif
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">
                                                        @if((bool)$hasCategoryClassificationFee->ApprovedClassification!=NULL)
                                                            @if($hasCategoryClassificationFee->ApprovedClassificationId==$hasCategoryClassificationFee->FinalApprovedClassificationId)
                                                                <?php $categoryClassificationFeeTotal+=$hasCategoryClassificationFee->ApprovedRenewalFee; ?>
                                                                {{number_format($hasCategoryClassificationFee->ApprovedRenewalFee,2)}}
                                                            @else
                                                                <?php $categoryClassificationFeeTotal+=$hasCategoryClassificationFee->ApprovedRegistrationFee; ?>
                                                                {{number_format($hasCategoryClassificationFee->ApprovedRegistrationFee,2)}}
                                                            @endif
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                            <tr class="bold">
                                                <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff" colspan="5">Total</td>
                                                <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">
                                                    <?php $appliedService->ContractorAmount=$categoryClassificationFeeTotal; ?>
                                                    {{number_format($categoryClassificationFeeTotal,2)}}
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    @else
                                        <span class="font-red">*Fee details has been already displayed aganist Renewal of CDB Certificate.</span>
                                    @endif
                                @else
                                    <table class="data-large" style="border-collapse: collapse; width: 100%; background-color: #fff; font-size: 10pt;" bgcolor="#fff">
                                        <thead>
                                        <tr class="success">
                                            <th style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">Category</th>
                                            <th style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">Existing</th>
                                            <th style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">Applied</th>
                                            <th style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">Verified</th>
                                            <th style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">Approved</th>
                                            <th style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">Amount</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($hasCategoryClassificationsFee as $hasCategoryClassificationFee)
                                            <tr>
                                                <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">{{{$hasCategoryClassificationFee->MasterCategoryCode}}}</td>
                                                <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">
                                                    @if((bool)$hasCategoryClassificationFee->FinalApprovedClassification!=NULL)
                                                        {{{$hasCategoryClassificationFee->FinalApprovedClassification}}}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">
                                                    @if((bool)$hasCategoryClassificationFee->AppliedClassification!=NULL)
                                                        {{{$hasCategoryClassificationFee->AppliedClassification}}}
                                                        @if($hasCategoryClassificationFee->AppliedClassificationPriority<$hasCategoryClassificationFee->FinalClassificationPriority)
                                                            <i class="font-red"><small> (Downgraded)</small></i>
                                                        @elseif(!empty($hasCategoryClassificationFee->FinalClassificationPriority) && $hasCategoryClassificationFee->AppliedClassificationPriority>$hasCategoryClassificationFee->FinalClassificationPriority)
                                                            <i class="font-red"><small> (Upgraded)</small></i>
                                                        @elseif(empty($hasCategoryClassificationFee->FinalClassificationPriority))
                                                            <i class="font-red"><small>(New)</small></i>
                                                        @endif
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">
                                                    @if((bool)$hasCategoryClassificationFee->VerifiedClassification!=NULL)
                                                        {{{$hasCategoryClassificationFee->VerifiedClassification}}}
                                                        @if($hasCategoryClassificationFee->VerifiedClassificationPriority<$hasCategoryClassificationFee->FinalClassificationPriority)
                                                            <i class="font-red"><small> (Downgraded)</small></i>
                                                        @elseif(!empty($hasCategoryClassificationFee->FinalClassificationPriority) && $hasCategoryClassificationFee->VerifiedClassificationPriority>$hasCategoryClassificationFee->FinalClassificationPriority)
                                                            <i class="font-red"><small> (Upgraded)</small></i>
                                                        @elseif(empty($hasCategoryClassificationFee->FinalClassificationPriority))
                                                            <i class="font-red"><small>(New)</small></i>
                                                        @endif
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">
                                                    @if((bool)$hasCategoryClassificationFee->ApprovedClassification!=NULL)
                                                        {{{$hasCategoryClassificationFee->ApprovedClassification}}}
                                                        @if($hasCategoryClassificationFee->ApprovedClassificationPriority<$hasCategoryClassificationFee->FinalClassificationPriority)
                                                            <i class="font-red"><small> (Downgraded)</small></i>
                                                        @elseif(!empty($hasCategoryClassificationFee->FinalClassificationPriority) && $hasCategoryClassificationFee->ApprovedClassificationPriority>$hasCategoryClassificationFee->FinalClassificationPriority)
                                                            <i class="font-red"><small> (Upgraded)</small></i>
                                                        @elseif(empty($hasCategoryClassificationFee->FinalClassificationPriority))
                                                            <i class="font-red"><small>(New)</small></i>
                                                        @endif
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">
                                                    @if((bool)$hasCategoryClassificationFee->ApprovedClassification!=NULL)
                                                        @if($hasCategoryClassificationFee->ApprovedClassificationId==$hasCategoryClassificationFee->FinalApprovedClassificationId)
                                                            @if($hasRenewal)
                                                                <?php $categoryClassificationFeeTotal+=$hasCategoryClassificationFee->ApprovedRenewalFee; ?>
                                                                {{number_format($hasCategoryClassificationFee->ApprovedRenewalFee,2)}}
                                                            @else
                                                                -
                                                            @endif
                                                            <?php //$categoryClassificationFeeTotal+=$hasCategoryClassificationFee->ApprovedRenewalFee; ?>
{{--                                                            {{number_format($hasCategoryClassificationFee->ApprovedRenewalFee,2)}}--}}
                                                        @else
                                                            <?php $categoryClassificationFeeTotal+=$hasCategoryClassificationFee->ApprovedRegistrationFee; ?>
                                                            {{number_format($hasCategoryClassificationFee->ApprovedRegistrationFee,2)}}
                                                        @endif
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                        <tr class="bold">
                                            <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff" colspan="5">Total</td>
                                            <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">
                                                <?php $appliedService->ContractorAmount=$categoryClassificationFeeTotal; ?>
                                                {{number_format($categoryClassificationFeeTotal,2)}}
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                @endif
                            </td>
                        @elseif($appliedService->Id==CONST_SERVICETYPE_LATEFEE)
                            <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">
                                <table class="data-large" style="border-collapse: collapse; width: 100%; background-color: #fff; font-size: 10pt;" bgcolor="#fff">
                                    <thead>
                                    <tr class="danger">
                                        <th style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">No. of Days Late (Actual)</th>
                                        <th style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">No. of Days Late After Grace Period</th>
                                        <th style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">Penalty per Day (Nu.)</th>
                                        <th style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">Total Amount (Nu.)</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">
                                            {{$hasLateFeeAmount[0]->PenaltyNoOfDays-1}}<br />
                                            <small><i class="font-red">* 30 days is grace period.</i></small><br />
                                            <small><i class="font-red">* Registration Expired on {{convertDateToClientFormat($hasLateFeeAmount[0]->RegistrationExpiryDate)}}</i></small><br />
                                            <small><i class="font-red">* Application for Renewal on {{convertDateToClientFormat($hasLateFeeAmount[0]->ApplicationDate)}}</i></small>
                                        </td>
                                        <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">
                                            @if(($hasLateFeeAmount[0]->PenaltyNoOfDays-1)>30)
                                                <?php $lateFeeAfterGracePeriod=$hasLateFeeAmount[0]->PenaltyNoOfDays-30-1; ?>
                                            @else
                                                <?php $lateFeeAfterGracePeriod = 0; ?>
                                            @endif
                                            @if($lateFeeAfterGracePeriod>0)
                                                {{$lateFeeAfterGracePeriod}}
                                            @endif
                                        </td>
                                        <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">
                                            {{number_format($hasLateFeeAmount[0]->PenaltyLateFeeAmount,2)}}
                                        </td>
                                        <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">
                                            <?php $lateFeeAmount=(int)$lateFeeAfterGracePeriod*(int)$hasLateFeeAmount[0]->PenaltyLateFeeAmount; ?>
                                                @if((int)$maxClassification == 998 || (int)$maxClassification == 997)
                                                    @if($lateFeeAmount > 3000)
                                                        <?php $lateFeeAmount = 3000; ?>
                                                    @endif
                                                @endif
                                                {{number_format($lateFeeAmount,2)}}
                                        </td>
                                    </tr>
                                    <tr class="bold">
                                        <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff" colspan="3">Total</td>
                                        <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">
                                            <?php $appliedService->ContractorAmount=$lateFeeAmount;?>
{{--                                            {{number_format($lateFeeAmount,2)}}--}}
                                            @if((int)$hasWaiver == 1)
                                                <span style="text-decoration: line-through;">
                                            @endif
                                                    {{number_format($lateFeeAmount,2)}}
                                                    @if((int)$hasWaiver== 1)
                                            </span>
                                                &nbsp;
                                                {{{number_format($newLateFeeAmount,20)}}}
                                            @endif
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </td>
                        @endif
                        <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff" class="text-right">
                            {{--@if((bool)$appliedService->ContractorAmount!=NULL)--}}
                                <?php //$totalFeeApplicable+=$appliedService->ContractorAmount; ?>
                                {{--{{{number_format($appliedService->ContractorAmount,2)}}}--}}
                            {{--@else--}}
                                {{-----}}
                            {{--@endif--}}

                                @if((bool)$appliedService->ContractorAmount!=NULL)
                                    <?php $totalFeeApplicable+=$appliedService->ContractorAmount; ?>
                                    @if($appliedService->Id==CONST_SERVICETYPE_LATEFEE)
                                        @if((int)$hasWaiver== 1)
                                            <span style="text-decoration: line-through;">
                                @endif
                                                @endif
                                                {{{number_format($appliedService->ContractorAmount,2)}}}
                                                @if($appliedService->Id==CONST_SERVICETYPE_LATEFEE)
                                                    @if((int)$hasWaiver== 1)
                                    </span>
                                            &nbsp;
                                            {{{number_format($newLateFeeAmount,2)}}}
                                        @endif
                                    @endif
                                @else
                                    -
                                @endif
                        </td>
                    </tr>
                @endforeach
                <tr class="text-right bold">
                    <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff" colspan="2">Total Amount Payable</td>
                    <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">
                        @if((int)$hasWaiver == 1)
                            <span style="text-decoration: line-through;">
                        @endif
                            {{{number_format($totalFeeApplicable,2)}}}
                        @if((int)$hasWaiver == 1)
                                </span>
                            <br>
                            {{{number_format((int)$totalFeeApplicable-((int)$lateFeeAmount-(int)$newLateFeeAmount),2)}}}
                        @endif
                    </td>
                </tr>
                </tbody>
            </table>
        <b>Total Amount Payable: Nu.
            @if((int)$hasWaiver == 1)
                {{{number_format((int)$totalFeeApplicable-((int)$lateFeeAmount-(int)$newLateFeeAmount),2)}}}
            @else
                {{{number_format($totalFeeApplicable,2)}}}
            @endif
        </b>
    @elseif((int)$mailIntendedTo==2)
        <h5 class="font-blue-madison bold">Fee Details for Applied Services</h5>
        <table class="data-large" style="border-collapse: collapse; width: 100%; background-color: #fff; font-size: 10pt;" bgcolor="#fff">
            <thead>
            <tr>
                <th style="background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2" style="background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2" style="background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2" style="background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2">Service(s) Applied</th>
                <th style="background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2" style="background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2" style="background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2" style="background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2">Details</th>
                <th style="background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2" style="background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2" style="background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2" style="background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2" width="10%" class="text-right">Fee (Nu.)</th>
            </tr>
            </thead>
            <tbody>
            <?php $totalFeeApplicable=0; $categoryClassificationFeeTotal=0;$lateFeeAmount=0;?>
            @foreach($appliedServices as $appliedService)
                <tr>
                    <td  style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff" @if($appliedService->Id!=CONST_SERVICETYPE_RENEWAL && $appliedService->Id!=CONST_SERVICETYPE_LATEFEE && $appliedService->Id!=CONST_SERVICETYPE_CHANGEOFCATEGORYCLASSIFICATION)colspan="2"@endif>
                        {{{$appliedService->ServiceName}}}
                    </td>
                    @if($appliedService->Id==CONST_SERVICETYPE_RENEWAL || $appliedService->Id==CONST_SERVICETYPE_CHANGEOFCATEGORYCLASSIFICATION)
                        <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">
                            @if($hasRenewal && $hasChangeInCategoryClassification)
                                @if($appliedService->Id==CONST_SERVICETYPE_RENEWAL)
                                    <table class="table table-bordered table-hover table-condensed">
                                        <thead>
                                        <tr class="success">
                                            <th style="background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2" style="background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2" style="background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2">Category</th>
                                            <th style="background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2" style="background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2" style="background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2">Applied</th>
                                            <th style="background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2" style="background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2" style="background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2">Verified</th>
                                            <th style="background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2" style="background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2" style="background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2">Approved</th>
                                            <th style="background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2" style="background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2" style="background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2">No. of Service @if(isset($applicationStage)){{"(Applied)"}}@else{{"(Approved)"}}@endif X Fee</th>
                                            <th style="background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2" style="background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2" style="background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2" class="text-right">Total</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($hasCategoryClassificationsFee as $hasCategoryClassificationFee)
                                        <?php $approvedServiceCount = $newServiceCount = $oldServiceCount = 0; $feeString = "";  ?>
                                            <tr>
                                                <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">{{{$hasCategoryClassificationFee->ServiceCategoryName}}}</td>
                                                <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">
                                                    @if((bool)$hasCategoryClassificationFee->AppliedService!=NULL)
                                                        {{{$hasCategoryClassificationFee->AppliedService}}}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">
                                                    @if((bool)$hasCategoryClassificationFee->VerifiedService!=NULL)
                                                        {{{$hasCategoryClassificationFee->VerifiedService}}}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">
                                                    @if((bool)$hasCategoryClassificationFee->ApprovedService!=NULL)
                                                        {{{$hasCategoryClassificationFee->ApprovedService}}}
                                                    @else
                                                        -
                                                    @endif

                                                    @if($appliedService->Id==CONST_SERVICETYPE_RENEWAL)
                                                        @foreach($approvedCategoryServicesArray[$hasCategoryClassificationFee->ServiceCategoryId] as $approvedServiceIndividual)
                                                            <?php $approvedServiceCount++; ?>
                                                            @if(!in_array($approvedServiceIndividual,$existingCategoryServicesArray[$hasCategoryClassificationFee->ServiceCategoryId]))
                                                                <?php $newServiceCount++; ?>
                                                            @else
                                                                <?php $oldServiceCount++; ?>
                                                            @endif
                                                        @endforeach
                                                    @else
                                                        @foreach($approvedCategoryServicesArray[$hasCategoryClassificationFee->ServiceCategoryId] as $approvedServiceIndividual)
                                                            @if(!in_array($approvedServiceIndividual,$existingCategoryServicesArray[$hasCategoryClassificationFee->ServiceCategoryId]))
                                                                <?php $approvedServiceCount++; ?>
                                                            @endif
                                                        @endforeach
                                                    @endif

                                                </td>
                                                <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff" class="text-center">
                                                    @if($appliedService->Id==CONST_SERVICETYPE_RENEWAL)
                                                        @if((int)$newServiceCount>0)
                                                            {{$newServiceCount.' X '.number_format($newRegistrationAmount)}}
                                                            <?php $feeString.=$newServiceCount.' X '.number_format($newRegistrationAmount); ?>
                                                        @endif
                                                        @if((int)$oldServiceCount>0)
                                                            @if($feeString!="")
                                                                <?php
                                                                echo ", ";
                                                                $feeString.=", ";
                                                                ?>
                                                            @endif
                                                            {{$oldServiceCount.' X '.number_format($feeAmount[0]->ConsultantAmount)}}
                                                            <?php $feeString.=$oldServiceCount.' X '.number_format($feeAmount[0]->ConsultantAmount); ?>
                                                        @endif
                                                    @else
                                                        {{$approvedServiceCount.' X '.number_format($feeAmount[0]->ConsultantAmount)}}
                                                    @endif
{{--                                                    {{$approvedServiceCount.' X '.number_format($feeAmount[0]->ConsultantAmount)}}--}}
                                                </td>
                                                <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff" class="text-right">
                                                    <?php //$categoryClassificationFeeTotal+=$approvedServiceCount* $feeAmount[0]->ConsultantAmount;?>
{{--                                                    {{number_format($approvedServiceCount* $feeAmount[0]->ConsultantAmount)}}--}}
                                                        @if($appliedService->Id==CONST_SERVICETYPE_RENEWAL)
                                                            <?php $categoryClassificationFeeTotal+=$oldServiceCount* $feeAmount[0]->ConsultantAmount;?>
                                                            <?php $categoryClassificationFeeTotal+=$newServiceCount* $newRegistrationAmount;?>
                                                            {{number_format(($oldServiceCount* $feeAmount[0]->ConsultantAmount)+($newServiceCount* $newRegistrationAmount))}}
                                                        @else
                                                            <?php $categoryClassificationFeeTotal+=$approvedServiceCount* $feeAmount[0]->ConsultantAmount;?>
                                                            {{number_format($approvedServiceCount* $feeAmount[0]->ConsultantAmount)}}
                                                        @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                        <tr class="bold">
                                            <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff" colspan="5">Total</td>
                                            <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff" class="text-right">
                                                <?php $appliedService->ConsultantAmount=$categoryClassificationFeeTotal; ?>
                                                {{number_format($categoryClassificationFeeTotal,2)}}
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                @else
                                    <span class="font-red">*Fee details has been already displayed aganist Renewal of CDB Certificate.</span>
                                @endif
                            @else
                                <table class="data-large" style="border-collapse: collapse; width: 100%; background-color: #fff; font-size: 10pt;" bgcolor="#fff">
                                    <thead>
                                    <tr class="success">
                                        <th style="background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2" style="background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2">Category</th>
                                        <th style="background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2" style="background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2">Applied</th>
                                        <th style="background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2" style="background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2">Verified</th>
                                        <th style="background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2" style="background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2">Approved</th>
                                        <th style="background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2" style="background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2">@if(isset($applicationStage)){{"(Applied)"}}@else{{"(Approved)"}}@endif X Fee</th>
                                        <th style="background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2" style="background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2" class="text-right">Total</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($hasCategoryClassificationsFee as $hasCategoryClassificationFee)
                                    <?php $approvedServiceCount = $newServiceCount = $oldServiceCount = 0; $feeString = ""; ?>
                                        <tr>
                                            <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">{{{$hasCategoryClassificationFee->ServiceCategoryName}}}</td>
                                            <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">
                                                @if((bool)$hasCategoryClassificationFee->AppliedService!=NULL)
                                                    {{{$hasCategoryClassificationFee->AppliedService}}}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">
                                                @if((bool)$hasCategoryClassificationFee->VerifiedService!=NULL)
                                                    {{{$hasCategoryClassificationFee->VerifiedService}}}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">
                                                @if((bool)$hasCategoryClassificationFee->ApprovedService!=NULL)
                                                    {{{$hasCategoryClassificationFee->ApprovedService}}}
                                                @else
                                                    -
                                                @endif
                                                @if($appliedService->Id==CONST_SERVICETYPE_RENEWAL || $appliedService->Id==CONST_SERVICETYPE_CHANGEOFCATEGORYCLASSIFICATION)
                                                    @foreach($approvedCategoryServicesArray[$hasCategoryClassificationFee->ServiceCategoryId] as $approvedServiceIndividual)
                                                        <?php $approvedServiceCount++; ?>
                                                        @if(!in_array($approvedServiceIndividual,$existingCategoryServicesArray[$hasCategoryClassificationFee->ServiceCategoryId]))
                                                            <?php $newServiceCount++; ?>
                                                        @else
                                                            @if($appliedService->Id == CONST_SERVICETYPE_RENEWAL) <?php $oldServiceCount++; ?> @endif
                                                        @endif
                                                    @endforeach
                                                @else
                                                    @foreach($approvedCategoryServicesArray[$hasCategoryClassificationFee->ServiceCategoryId] as $approvedServiceIndividual)
                                                        @if(!in_array($approvedServiceIndividual,$existingCategoryServicesArray[$hasCategoryClassificationFee->ServiceCategoryId]))
                                                            <?php $approvedServiceCount++; ?>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </td>
                                            <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff" class="text-center">
                                                @if($appliedService->Id==CONST_SERVICETYPE_RENEWAL || $appliedService->Id==CONST_SERVICETYPE_CHANGEOFCATEGORYCLASSIFICATION)
                                                    @if((int)$newServiceCount>0)
                                                        {{$newServiceCount.' X '.number_format($newRegistrationAmount)}}
                                                        <?php $feeString.=$newServiceCount.' X '.number_format($newRegistrationAmount); ?>
                                                    @endif
                                                    @if((int)$oldServiceCount>0)
                                                        @if($feeString!="")
                                                            <?php
                                                            echo ", ";
                                                            $feeString.=", ";
                                                            ?>
                                                        @endif
                                                        {{$oldServiceCount.' X '.number_format($feeAmount[0]->ConsultantAmount)}}
                                                        <?php $feeString.=$oldServiceCount.' X '.number_format($feeAmount[0]->ConsultantAmount); ?>
                                                    @else 0
                                                    @endif
                                                @else
                                                    {{$approvedServiceCount.' X '.number_format($feeAmount[0]->ConsultantAmount)}}
                                                @endif
                                            </td>
                                            <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff" class="text-right">
                                                <?php //$categoryClassificationFeeTotal+=$approvedServiceCount* $feeAmount[0]->ConsultantAmount;?>
{{--                                                {{number_format($approvedServiceCount* $feeAmount[0]->ConsultantAmount)}}--}}

                                                @if($appliedService->Id==CONST_SERVICETYPE_RENEWAL || $appliedService->Id==CONST_SERVICETYPE_CHANGEOFCATEGORYCLASSIFICATION)
                                                    <?php $categoryClassificationFeeTotal+=$oldServiceCount* $feeAmount[0]->ConsultantAmount;?>
                                                    <?php $categoryClassificationFeeTotal+=$newServiceCount* $newRegistrationAmount;?>
                                                    {{number_format(($oldServiceCount* $feeAmount[0]->ConsultantAmount)+($newServiceCount* $newRegistrationAmount))}}
                                                @else
                                                    <?php $categoryClassificationFeeTotal+=$approvedServiceCount* $feeAmount[0]->ConsultantAmount;?>
                                                    {{number_format($approvedServiceCount* $feeAmount[0]->ConsultantAmount)}}
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr class="bold">
                                        <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff" colspan="5">Total</td>
                                        <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff" class="text-right">
                                            <?php $appliedService->ConsultantAmount=$categoryClassificationFeeTotal; ?>
                                            {{number_format($categoryClassificationFeeTotal,2)}}
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            @endif
                        </td>
                    @elseif($appliedService->Id==CONST_SERVICETYPE_LATEFEE)
                        <td>
                            <table class="data-large" style="border-collapse: collapse; width: 100%; background-color: #fff; font-size: 10pt;" bgcolor="#fff">
                                <thead>
                                <tr class="danger">
                                    <th style="background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2">No. of Days Late (Actual)</th>
                                    <th style="background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2">No. of Days Late After Grace Period</th>
                                    <th style="background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2">Penalty per Day (Nu.)</th>
                                    <th style="background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2">Total Amount (Nu.)</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">
                                        {{$hasLateFeeAmount[0]->PenaltyNoOfDays-1}}<br />
                                        <small><i class="font-red">* 30 days is grace period.</i></small><br />
                                        <small><i class="font-red">Registration Expired on {{convertDateToClientFormat($hasLateFeeAmount[0]->RegistrationExpiryDate)}}</i></small><br />
                                        <small><i class="font-red">* Application for Renewal on {{convertDateToClientFormat($hasLateFeeAmount[0]->ApplicationDate)}}</i></small>
                                    </td>
                                    <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">
                                        @if(($hasLateFeeAmount[0]->PenaltyNoOfDays-1)>30)
                                            <?php $lateFeeAfterGracePeriod=$hasLateFeeAmount[0]->PenaltyNoOfDays-30-1; ?>
                                        @else
                                            <?php $lateFeeAfterGracePeriod = 0; ?>
                                        @endif
                                        @if($lateFeeAfterGracePeriod>0)
                                            {{$lateFeeAfterGracePeriod}}
                                        @endif
                                    </td>
                                    <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">
                                        {{number_format($hasLateFeeAmount[0]->PenaltyLateFeeAmount,2)}}
                                    </td>
                                    <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">
                                        <?php $lateFeeAmount=(int)$lateFeeAfterGracePeriod*(int)$hasLateFeeAmount[0]->PenaltyLateFeeAmount; ?>
                                        {{number_format((int)$lateFeeAfterGracePeriod*(int)$hasLateFeeAmount[0]->PenaltyLateFeeAmount,2)}}
                                    </td>
                                </tr>
                                <tr class="bold">
                                    <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff" colspan="2">Total</td>
                                    <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">
                                        <?php $appliedService->ConsultantAmount=$lateFeeAmount;?>
                                        @if((int)$hasWaiver == 1)
                                            <span style="text-decoration: line-through;">
                                            @endif
                                                {{number_format($lateFeeAmount,2)}}
                                                @if((int)$hasWaiver == 1)
                                            </span>
                                            &nbsp;
                                            {{{number_format($newLateFeeAmount,2)}}}
                                        @endif
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </td>
                    @endif
                    <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff" class="text-right">
                        @if((bool)$appliedService->ConsultantAmount!=NULL)
                            @if($appliedService->Id==CONST_SERVICETYPE_CHANGEOFCATEGORYCLASSIFICATION)
                                @if(!$hasRenewal)
                                    <?php $totalFeeApplicable+=$appliedService->ConsultantAmount; ?>
                                @endif
                            @else
                                <?php $totalFeeApplicable+=$appliedService->ConsultantAmount; ?>
                            @endif
                            @if($appliedService->Id==CONST_SERVICETYPE_LATEFEE)
                                @if((int)$hasWaiver == 1)
                                    <span style="text-decoration: line-through;">
                                @endif
                            @endif

                            @if($appliedService->Id==CONST_SERVICETYPE_CHANGEOFCATEGORYCLASSIFICATION)
                                @if(!$hasRenewal)
                                    {{{number_format($appliedService->ConsultantAmount,2)}}}
                                @endif
                            @else
                                {{{number_format($appliedService->ConsultantAmount,2)}}}
                            @endif


                            @if($appliedService->Id==CONST_SERVICETYPE_LATEFEE)
                                @if((int)$hasWaiver == 1)
                                </span>
                                &nbsp;
                                {{{number_format($newLateFeeAmount,2)}}}
                                @endif
                            @endif
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @endforeach
            <tr class="text-right bold">
                <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff" colspan="2">Total Amount Payable</td>
                <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">
                    @if((int)$hasWaiver == 1)
                        <span style="text-decoration: line-through;">
                    @endif
                    {{{number_format($totalFeeApplicable,2)}}}
                    @if((int)$hasWaiver == 1)
                        </span>
                        <br>
                        {{{number_format((int)$totalFeeApplicable-((int)$lateFeeAmount-(int)$newLateFeeAmount),2)}}}
                    @endif
                </td>
            </tr>
            </tbody>
        </table>
    @elseif((int)$mailIntendedTo == 3)

        {{--Architect--}}
        <table class="data-large" style="border-collapse: collapse; width: 100%; background-color: #fff; font-size: 10pt;" bgcolor="#fff">
            <thead>
            <tr>
                <th style="background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2">Service Applied</th>
                <th style="background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2">Remarks</th>
                <th style="background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2" class="text-right">Amount</th>
            </tr>
            </thead>
            <tbody>
            <?php $totalFeesApplicable=0;$lateFeeAmount=0; ?>
            @foreach($appliedServices as $appliedService)
                <tr>
                    @if($architectServiceSectorType==CONST_CMN_SERVICESECTOR_GOVT)
                        <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">
                            {{{$appliedService->ServiceName}}}
                        </td>
                        <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff" class="text-right">
                            <?php $totalFeesApplicable+=$appliedService->ArchitectGovtAmount; ?>
                            {{{number_format($appliedService->ArchitectGovtAmount,2)}}}
                        </td>
                    @else
                        <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">
                            {{$appliedService->ServiceName}}
                        </td>
                        <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">
                            @if($appliedService->Id==CONST_SERVICETYPE_LATEFEE)
                                <table class="table table-hover table-condensed">
                                    <thead>
                                    <tr class="danger">
                                        <th style="background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2">No. of Days Late (Actual)</th>
                                        <th style="background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2">No. of Days Late After Grace Period</th>
                                        <th style="background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2">Penalty per Day (Nu.)</th>
                                        <th style="background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2">Total Amount (Nu.)</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">
                                            {{$hasLateFeeAmount[0]->PenaltyNoOfDays-1}}<br />
                                            <small><i class="font-red">* 30 days is grace period.</i></small><br />
                                            <small><i class="font-red">Registration Expired on {{convertDateToClientFormat($hasLateFeeAmount[0]->RegistrationExpiryDate)}}</i></small><br />
                                            <small><i class="font-red">* Application for Renewal on {{convertDateToClientFormat($hasLateFeeAmount[0]->ApplicationDate)}}</i></small>
                                        </td>
                                        <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">
                                            @if(($hasLateFeeAmount[0]->PenaltyNoOfDays-1)>30)
                                                <?php $lateFeeAfterGracePeriod=$hasLateFeeAmount[0]->PenaltyNoOfDays-30-1; ?>
                                            @else
                                                <?php $lateFeeAfterGracePeriod = 0; ?>
                                            @endif
                                            @if($lateFeeAfterGracePeriod>0)
                                                {{$lateFeeAfterGracePeriod}}
                                            @endif
                                        </td>
                                        <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">
                                            {{$hasLateFeeAmount[0]->PenaltyLateFeeAmount}}
                                        </td>
                                        <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">
                                            <?php $lateFeeAmount=(int)$lateFeeAfterGracePeriod*(int)$hasLateFeeAmount[0]->PenaltyLateFeeAmount; ?>
                                                @if($lateFeeAmount > 3000)
                                                    <?php $lateFeeAmount = 3000; ?>
                                                @endif
                                                {{number_format($lateFeeAmount,2)}}
                                        </td>
                                    </tr>
                                    <tr class="bold">
                                        <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff" colspan="3">Total</td>
                                        <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">
                                            <?php $appliedService->ArchitectPvtAmount=$lateFeeAmount;?>
                                            @if((int)$hasWaiver == 1)
                                                <span style="text-decoration: line-through;">
                                                    @endif
                                                    {{number_format($lateFeeAmount,2)}}
                                                    @if((int)$hasWaiver == 1)
                                                        </span>
                                                    &nbsp;
                                                {{{number_format($newLateFeeAmount,2)}}}
                                            @endif
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            @endif
                        </td>
                        <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff" class="text-right">

                            <?php $totalFeesApplicable+=$appliedService->ArchitectPvtAmount; ?>
                            @if($appliedService->Id==CONST_SERVICETYPE_LATEFEE)
                                @if((int)$hasWaiver == 1)
                                    <span style="text-decoration: line-through;">
                                @endif
                            @endif
                            {{{number_format($appliedService->ArchitectPvtAmount,2)}}}
                            @if($appliedService->Id==CONST_SERVICETYPE_LATEFEE)
                                @if((int)$hasWaiver == 1)
                                    </span>
                                    &nbsp;
                                    {{{number_format($newLateFeeAmount,2)}}}
                                @endif
                            @endif
                        </td>
                    @endif
                </tr>
            @endforeach
            <tr>
                <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff" colspan="2" class="bold text-right">
                    Total
                </td>
                <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff" class="text-right bold">
                    @if((int)$hasWaiver == 1)
                        <span style="text-decoration: line-through;">
									@endif
                            {{{number_format($totalFeesApplicable,2)}}}
                            @if((int)$hasWaiver == 1)
										</span>
                        <br>
                        {{{number_format((int)$totalFeesApplicable-((int)$lateFeeAmount-(int)$newLateFeeAmount),2)}}}
                    @endif
                </td>
            </tr>
            </tbody>
        </table>
        {{--End Architect--}}

    @elseif((int)$mailIntendedTo==4)
        <table class="data-large" style="border-collapse: collapse; width: 100%; background-color: #fff; font-size: 10pt;" bgcolor="#fff">
            <thead>
            <tr>
                <th style="background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2">Service Applied</th>
                <th style="background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2">Remarks</th>
                <th style="background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2" class="text-right">Amount</th>
            </tr>
            </thead>
            <tbody>
            <?php $totalFeesApplicable=0;$lateFeeAmount=0; ?>
            @foreach($appliedServices as $appliedService)
                <tr>
                    @if($engineerServiceSectorType==CONST_CMN_SERVICESECTOR_GOVT)
                        <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">
                            {{$appliedService->ServiceName}}
                        </td>
                        <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">
                            -
                        </td>
                        <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff" class="text-right">
                            @if(!empty($appliedService->EngineeerGovtAmount))
                                <?php $totalFeesApplicable+=$appliedService->EngineerGovtAmount; ?>
                                {{number_format($appliedService->EngineeerGovtAmount,2)}}
                            @else
                                -
                            @endif
                        </td>
                    @else
                        <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">
                            {{$appliedService->ServiceName}}
                        </td>
                        <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">
                            @if($appliedService->Id==CONST_SERVICETYPE_LATEFEE)
                                <table class="table table-hover table-condensed">
                                    <thead>
                                    <tr class="danger">
                                        <th style="background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2">No. of Days Late (Actual)</th>
                                        <th style="background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2">No. of Days Late After Grace Period</th>
                                        <th style="background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2">Penalty per Day (Nu.)</th>
                                        <th style="background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2">Total Amount (Nu.)</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">
                                            {{$hasLateFeeAmount[0]->PenaltyNoOfDays-1}}<br />
                                            <small><i class="font-red">* 30 days is grace period.</i></small><br />
                                            <small><i class="font-red">Registration Expired on {{convertDateToClientFormat($hasLateFeeAmount[0]->RegistrationExpiryDate)}}</i></small><br />
                                            <small><i class="font-red">* Application for Renewal on {{convertDateToClientFormat($hasLateFeeAmount[0]->ApplicationDate)}}</i></small>
                                        </td>
                                        <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">
                                            @if(($hasLateFeeAmount[0]->PenaltyNoOfDays-1)>30)
                                                <?php $lateFeeAfterGracePeriod=$hasLateFeeAmount[0]->PenaltyNoOfDays-30-1; ?>
                                            @else
                                                <?php $lateFeeAfterGracePeriod = 0; ?>
                                            @endif
                                            @if($lateFeeAfterGracePeriod>0)
                                                {{$lateFeeAfterGracePeriod}}
                                            @endif
                                        </td>
                                        <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">
                                            {{$hasLateFeeAmount[0]->PenaltyLateFeeAmount}}
                                        </td>
                                        <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">
                                            <?php $lateFeeAmount=(int)$lateFeeAfterGracePeriod*(int)$hasLateFeeAmount[0]->PenaltyLateFeeAmount; ?>
                                            {{number_format((int)$lateFeeAfterGracePeriod*(int)$hasLateFeeAmount[0]->PenaltyLateFeeAmount,2)}}
                                        </td>
                                    </tr>
                                    <tr class="bold">
                                        <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff" colspan="3">Total</td>
                                        <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">
                                            <?php $appliedService->EngineerPvtAmount=$lateFeeAmount;?>
                                            {{number_format($lateFeeAmount,2)}}
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            @endif
                        </td>
                        <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff" class="text-right">
                            @if(!empty($appliedService->EngineerPvtAmount))
                                <?php $totalFeesApplicable+=$appliedService->EngineerPvtAmount; ?>
                                {{number_format($appliedService->EngineerPvtAmount,2)}}
                            @else
                                -
                            @endif
                        </td>
                    @endif
                </tr>
            @endforeach
            <tr>
                <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff" colspan="2" class="bold text-right">
                    Total
                </td>
                <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff" class="text-right bold">
                    @if((int)$totalFeesApplicable!=0)
                        {{number_format($totalFeesApplicable,2)}}
                    @else
                        -
                    @endif
                </td>
            </tr>
            </tbody>
        </table>
        <b>Total Amount Payable: Nu. {{number_format($totalFeesApplicable,2)}}</b>
    @endif
@stop
@endif
@section('notes')
    <table style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6; width: 100%; margin: 0; padding: 0;"><tr style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6; margin: 0; padding: 0;"><td style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6; margin: 0; padding: 10px 0;">&#13;
                <p style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6; font-weight: normal; margin: 0 0 10px; padding: 0;"><a href="http://www.cdb.gov.bt" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 2; color: #FFF; text-decoration: none; font-weight: bold; text-align: center; cursor: pointer; display: inline-block; border-radius: 25px; background-color: #348eda; margin: 0 10px 0 0; padding: 0; border-color: #348eda; border-style: solid; border-width: 10px 20px;">CDB Website</a></p>&#13;
            </td>&#13;
        </tr></table>
@stop
