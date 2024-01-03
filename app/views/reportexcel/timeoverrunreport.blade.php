<html>
    <body>
        <div>
            <table>
                <thead>
                <tr>
                    <th colspan="2">Time overrun Report</th>
                </tr>
                @foreach($parametersForPrint as $key=>$value)
                    <tr>
                        <th><i>{{$key}}:</i>&nbsp;{{$value}}</th>
                    </tr>
                @endforeach
                <tr>
                    <th colspan="3"></th>
                </tr>
                <tr>
                    <th>Sl.No.</th>
                    <th>Year</th>
                    @if(!$summary)
                        <th>Firm</th>
                    @endif
                    <th>Agency</th>
                    <th>Work Id</th>
                    <th>Work Name</th>
                    <th>Category</th>
                    <th>Class</th>
                    <th>Awarded Amount (Nu.)</th>
                    <th>Final Amount (Nu.)</th>
                    @if(!$summary)
                        <th width="85">Awarded Date</th>
                        <th width="85">Final Date</th>
                    @endif
                    <th>Time overrun (days)</th>
                    @if(!$summary)
                        <th>LD</th>
                        <th>Hindrance</th>
                        <th>Remarks</th>
                        <th>Dzongkhag</th>
                        <th>Status</th>
                    @endif
                </tr>
                </thead>
                <tbody>
                <?php $count = 1; $awardedAmount = 0; $totalTimeOverrun = 0; $totalAwardedAmount = 0; $totalFinalAmount = 0;?>
                @forelse($workDetails as $workDetail)
                    <tr>
                        <td>{{$count}}</td>
                        <td>{{$workDetail->Year}}</td>
                        @if(!$summary)
                            <td>{{$workDetail->Contractor}} ({{$workDetail->CDBNo}})</td>
                        @endif
                        <td>{{$workDetail->Agency}}</td>
                        <td>{{$workDetail->WorkId}}</td>
                        <td>{{strip_tags($workDetail->NameOfWork)}}</td>
                        <td>{{$workDetail->Category}}</td>
                        <td>{{$workDetail->Classification}}</td>
                        <td class="text-right">{{number_format($workDetail->AwardedAmount,2)}}<?php $totalAwardedAmount+=doubleval($workDetail->AwardedAmount); ?></td>
                        <td class="text-right">{{number_format($workDetail->FinalAmount,2)}}<?php $totalFinalAmount+=doubleval($workDetail->FinalAmount); ?></td>
                        @if(!$summary)
                            <td class="text-right">{{convertDateToClientFormat($workDetail->ActualEndDate)}}</td>
                            <td class="text-right">{{convertDateToClientFormat($workDetail->CompletionDateFinal)}}</td>
                        @endif
                        <?php $totalTimeOverrun+=(int)$workDetail->DateDiff; ?>
                        <td class="text-right">{{number_format($workDetail->DateDiff)}}</td>
                        @if(!$summary)
                            <td>
                                @if((int)$workDetail->LDImposed == 1)
                                    {{$workDetail->LDNoOfDays}}, {{$workDetail->LDAmount}}
                                @else
                                    ---
                                @endif
                            </td>
                            <td>
                                @if((int)$workDetail->Hindrance == 1)
                                    {{$workDetail->HindranceNoOfDays}}
                                @else
                                    ---
                                @endif
                            </td>
                            <td>{{$workDetail->Remarks}}</td>
                            <td>{{$workDetail->Dzongkhag}}</td>
                            <td>{{$workDetail->Status}}<?php $awardedAmount+=($workDetail->ReferenceNo == 3001)?1:0;?></td>
                        @endif
                    </tr>
                    <?php $count++ ?>
                @empty
                    <tr><td colspan="@if($summary){{"10"}}@else{{"18"}}@endif" class="font-red text-center">No data to display</td></tr>
                @endforelse
                @if(count($workDetails)>0)
                    <tr>
                        <td colspan="@if($summary){{"7"}}@else{{"8"}}@endif class="text-right bold">Total:</td>
                        <td class="text-right">{{number_format($totalAwardedAmount,2)}}</td>
                        <td class="text-right">{{number_format($totalFinalAmount,2)}}</td>
                        @if(!$summary)
                            <td></td>
                            <td></td>
                        @endif
                        <td class="text-right">{{number_format($totalTimeOverrun)}}</td>
                        @if(!$summary)
                            <td colspan="5"></td>
                        @endif
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
    </body>
</html>
