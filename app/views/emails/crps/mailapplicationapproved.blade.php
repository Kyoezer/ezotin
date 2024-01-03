@extends('emailmaster')
@section('emailcontent')
    <p><strong>{{$applicantName}} @if(isset($cdbNo))(CDB No.: {{$cdbNo}}) @endif</strong></p>
    <p class="lead">Application No:<i>{{$applicationNo}}</i> Dt.<i>{{$applicationDate}}</i></p>
    <p>{{$mailMessage}}</p>
    <!-- Callout Panel -->
@stop
@if((bool)$mailIntendedTo!=NULL)
@section('feestructure')
    <h3>Fee Structure</h3>
    @if((int)$mailIntendedTo==1)
        <table class="data-large" style="border-collapse: collapse; width: 100%; background-color: #fff; font-size: 10pt;" bgcolor="#fff">
            <thead>
            <tr>
                <th style="text-align: center;background-color: #d7e5f2; border: 1px solid #000;" align="center" bgcolor="#d7e5f2"></th>
                <th colspan="2" style="text-align: center;background-color: #d7e5f2; border: 1px solid #000;" align="center" bgcolor="#d7e5f2">Applied</th>
                <th colspan="2" style="text-align: center;background-color: #d7e5f2; border: 1px solid #000;" align="center" bgcolor="#d7e5f2">Verified</th>
                <th colspan="2" style="text-align: center;background-color: #d7e5f2; border: 1px solid #000;" align="center" bgcolor="#d7e5f2">Approved</th>
            </tr>
            <tr>
                <th style="background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2">Catgeory</th>
                <th style="background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2">Class</th>
                <th style="background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2">Amount (Nu.)</th>
                <th style="background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2">Class</th>
                <th style="background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2">Amount (Nu.)</th>
                <th style="background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2">Class</th>
                <th style="background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2">Amount (Nu.)</th>
            </tr>
            </thead>
            <tbody>
            <?php $totalFeeApplied=0;$totalFeeVerified=0;$totalFeeApproved=0; ?>
            @foreach($feeStructures as $feeStructure)
                <tr style="background-color: #ccc;" bgcolor="#ccc">
                    <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">
                        {{{$feeStructure->CategoryCode.' ('.$feeStructure->Category.')'}}}
                    </td>
                    <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">
                        {{{$feeStructure->AppliedClassification}}}
                    </td>
                    <td style="text-align: right;font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff" align="right">
                        {{{number_format($feeStructure->AppliedRegistrationFee,2)}}}
                    </td>
                    <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">
                        {{{$feeStructure->VerifiedClassificationCode.' ('.$feeStructure->VerifiedClassification.')'}}}
                    </td>
                    <td style="text-align: right;font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff" align="right">
                        {{{number_format($feeStructure->VerifiedRegistrationFee,2)}}}
                    </td>
                    <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">
                        {{{$feeStructure->ApprovedClassificationCode.' ('.$feeStructure->ApprovedClassification.')'}}}
                    </td>
                    <td style="text-align: right;font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff" align="right">
                        {{{number_format($feeStructure->ApprovedRegistrationFee,2)}}}
                    </td>
                </tr>
                <?php
                $totalFeeApplied+=$feeStructure->AppliedRegistrationFee;
                $totalFeeVerified+=$feeStructure->VerifiedRegistrationFee;
                $totalFeeApproved+=$feeStructure->ApprovedRegistrationFee;
                ?>
            @endforeach
            <tr style="font-weight: bold;background-color: #ccc; text-align: right;" bgcolor="#ccc" align="right">
                <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">Total</td>
                <td colspan="2" style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">{{number_format($totalFeeApplied,2)}}</td>
                <td colspan="2" style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">{{number_format($totalFeeVerified,2)}}</td>
                <td colspan="2" style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">{{number_format($totalFeeApproved,2)}}</td>
            </tr>
            </tbody>
        </table>
        <b>Total Amount Payable: Nu. {{number_format($totalFeeApproved,2)}}</b>
    @elseif((int)$mailIntendedTo==2)
        <table class="data-large" style="border-collapse: collapse; width: 100%; background-color: #fff; font-size: 10pt;" bgcolor="#fff">
            <thead>
            <tr style="background-color: #ccc;" bgcolor="#ccc">
                <th style="background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2">Category</th>
                <th style="background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2">Applied</th>
                <th style="background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2">Verified</th>
                <th style="background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2">Approved</th>
                <th style="text-align: center;background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2" align="center">No. of Service (Approved) X Fee.</th>
                <th style="text-align: right;background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2" align="right">Total</th>
            </tr>
            </thead>
            <tbody>
            <?php $noOfServicePerCategory=0;$overAllTotalAmount=0; ?>
            @foreach($serviceCategories as $serviceCategory)
                <tr style="background-color: #ccc;" bgcolor="#ccc">
                    <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">{{$serviceCategory->Name}}</td>
                    <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">
                        @foreach($appliedCategoryServices as $appliedServiceFee)
                            @if($appliedServiceFee->CmnConsultantServiceCategoryId==$serviceCategory->Id)
                                <span class="font-green-seagreen tooltips" data-placement="top" data-original-title="{{$appliedServiceFee->ServiceName}}"><i class="fa fa-question-circle"></i></span>
                                {{$appliedServiceFee->ServiceCode}}
                            @endif
                        @endforeach
                    </td>
                    <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">
                        @foreach($verifiedCategoryServices as $verifiedServiceFee)
                            @if($verifiedServiceFee->CmnConsultantServiceCategoryId==$serviceCategory->Id)
                                <span class="font-green-seagreen tooltips" data-placement="top" data-original-title="{{$verifiedServiceFee->ServiceName}}"><i class="fa fa-question-circle"></i></span>
                                {{$verifiedServiceFee->ServiceCode}}
                            @endif
                        @endforeach
                    </td>
                    <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">
                        @foreach($approvedCategoryServices as $approvedServiceFee)
                            @if($approvedServiceFee->CmnConsultantServiceCategoryId==$serviceCategory->Id)
                                <?php $noOfServicePerCategory+=1; ?>
                                <span class="font-green-seagreen tooltips" data-placement="top" data-original-title="{{$approvedServiceFee->ServiceName}}"><i class="fa fa-question-circle"></i></span>
                                {{$approvedServiceFee->ServiceCode}}
                            @endif
                        @endforeach
                    </td>
                    <td style="text-align: center;font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff" align="center">
                        {{$noOfServicePerCategory.' X '.number_format($feeStructures[0]->ConsultantAmount,2)}}
                    </td>
                    <td style="text-align: right;font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff" align="right">
                        <?php $curTotalAmount=$noOfServicePerCategory*$feeStructures[0]->ConsultantAmount;$overAllTotalAmount+=$curTotalAmount; ?>
                        {{number_format($noOfServicePerCategory*$feeStructures[0]->ConsultantAmount,2)}}
                    </td>
                </tr>
                <?php $noOfServicePerCategory=0; ?>
            @endforeach
            <tr style="font-weight: bold;background-color: #ccc; text-align: right;" bgcolor="#ccc" align="right">
                <td colspan="5" style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff"><span style="font-weight: bold;color:#db4437;text-align: left;" align="left">*Nu {{number_format($feeStructures[0]->ConsultantAmount,2)}} is applicable for each service under the category</span> Total</td>
                <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">{{number_format($overAllTotalAmount,2)}}</td>
            </tr>
            </tbody>
        </table>
        <b>Total Amount Payable: Nu. {{number_format($overAllTotalAmount,2)}}</b>
    @elseif((int)$mailIntendedTo==3 || (int)$mailIntendedTo==4)
        <table class="data-large" style="border-collapse: collapse; width: 100%; background-color: #fff; font-size: 10pt;" bgcolor="#fff">
            <thead>
            <tr style="background-color: #ccc;" bgcolor="#ccc">
                <th style="background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2">Type</th>
                <th style="text-align: center;background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2" align="center">Validity (yrs)</th>
                <th style="text-align: right;background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2" align="right">Fee (Nu.)</th>
            </tr>
            </thead>
            <tbody>
            <?php $totalFeesApplicable=0; ?>
            @foreach($feeDetails as $feeDetail)
                <tr style="background-color: #ccc;" bgcolor="#ccc">
                    <td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">{{$feeDetail->SectorType}}</td>
                    <td style="text-align: center;font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff" align="center">
                        @if(empty($feeDetail->RegistrationValidity))
                            -
                        @else
                            {{$feeDetail->RegistrationValidity}}
                        @endif
                    </td>
                    <td style="text-align: right;font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff" align="right">
                        @if(empty($feeDetail->NewRegistrationFee))
                            -
                        @else
                            {{number_format($feeDetail->NewRegistrationFee,2)}}
                        @endif
                    </td>
                    <?php $totalFeesApplicable+=$feeDetail->NewRegistrationFee; ?>
                </tr>
            @endforeach
            <tr>
                <td colspan="2" style="font-weight: bold;text-align: right;font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff" align="right">
                    Total
                </td>
                <td style="font-weight: bold;text-align: right;font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff" align="right">
                    {{number_format($totalFeesApplicable,2)}}
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