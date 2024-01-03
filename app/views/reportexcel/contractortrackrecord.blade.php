@extends('reportexcelmaster')
@section('content')
<div>
    <table>
        <thead>
        <tr>
            <th colspan="12">@if(isset($overrunReport)){{"Cost overrun report"}}@else{{"Contractor's Track Record"}}@endif</th>
        </tr>
        <tr>
            <th colspan="12"></th>
        </tr>
        @if($cdbNo != '')
            <tr>
                <th colspan="6"><i>CDB No:</i>&nbsp;{{$cdbNo}}</th>
            </tr>
        @endif
        @if($dzongkhag != '')
            <tr>
                <th colspan="6"><i>Dzongkhag:</i>&nbsp;{{$dzongkhag}}</th>
            </tr>
        @endif
        @if($category != '')
            <tr>
                <th colspan="6"><i>Category:</i>&nbsp;{{$category}}</th>
            </tr>
        @endif
        @if($procuringAgency != '')
            <tr>
                <th colspan="6" style="text-align: left;"><i>Procuring Agency:</i>&nbsp;{{$procuringAgency}}</th>
            </tr>
        @endif
        <tr><th colspan="13"></th></tr>
        <tr class="border-column">
            <th>Sl.No.</th>
            @if(!isset($overrunReport))
                <th>Year</th>
            @else
                @if($overrunReport != 2)
                    <th>Year</th>
                @endif
            @endif
            @if(isset($overrunReport) && $overrunReport == 1)
                <th>
                    Firm
                </th>
            @endif
            <th>Agency</th>
            @if(!isset($overrunReport))
                <th>Work Id</th>
                <th>Work Name</th>
                <th>Category</th>
            @else
                @if($overrunReport != 2)
                    <th>Work Id</th>
                    <th>Work Name</th>
                    <th>Category</th>
                @endif
            @endif

            <th>Awarded Amount (Nu.)</th>
            <th>Final Amount (Nu.)</th>
            @if(isset($overrunReport))
                <th>Cost Overrun (Nu.)</th>
            @endif
            @if(!isset($overrunReport))
                <th>Dzongkhag</th>
                <th>Status</th>
                <th>APS scoring</th>
                <th>APS (100)</th>
                <th>Remarks</th>
            @else
                @if($overrunReport != 2)
                    <th>Dzongkhag</th>
                    <th>Status</th>
                    <th>APS scoring</th>
                    <th>APS (100)</th>
                    <th>Remarks</th>
                @endif
            @endif

        </tr>
        </thead>
        <tbody>
        <?php $count = 1; $awardedAmount = 0; $totalCostOverrun = 0;?>
        @forelse($workDetails as $workDetail)
            <tr>
                <td>{{$count}}</td>
                @if(!isset($overrunReport))
                    <td>{{$workDetail->Year}}</td>
                @else
                    @if($overrunReport != 2)
                        <td>{{$workDetail->Year}}</td>
                    @endif
                @endif
                @if(isset($overrunReport) && $overrunReport == 1)
                    <td>
                        {{$workDetail->Contractor}} ({{$workDetail->CDBNo}})
                    </td>
                @endif
                <td>{{$workDetail->Agency}}</td>
                @if(!isset($overrunReport))
                    <td>{{$workDetail->WorkId}}</td>
                    <td>{{strip_tags($workDetail->NameOfWork)}}</td>
                    <td>{{$workDetail->Category}}</td>
                @else
                    @if($overrunReport != 2)
                        <td>{{$workDetail->WorkId}}</td>
                        <td>{{strip_tags($workDetail->NameOfWork)}}</td>
                        <td>{{$workDetail->Category}}</td>
                    @endif
                @endif

                <td class="text-right">{{number_format($workDetail->AwardedAmount,2)}}</td>
                <td class="text-right">{{number_format($workDetail->FinalAmount,2)}}</td>
                @if(isset($overrunReport))
                    <?php $costOverRun = (doubleval($workDetail->FinalAmount)-doubleval($workDetail->AwardedAmount)); $totalCostOverrun+=$costOverRun; ?>
                    <td class="text-right">{{number_format($costOverRun,2)}}</td>
                @endif

                @if(!isset($overrunReport))
                    <td>{{$workDetail->Dzongkhag}}</td>
                    <td>{{$workDetail->Status}}<?php $awardedAmount+=($workDetail->ReferenceNo == 3001)?1:0;?></td>
                    <td>
                        <?php if((int)$workDetail->APS == 100) {
                            $points = 10;
                        }
                        if(((int)$workDetail->APS < 100) && ((int)$workDetail->APS >= 50)) {
                            $points = 10 - (ceil((100 - (int)$workDetail->APS) / 5));
                        }
                        if((int)$workDetail->APS < 50){
                            $points = 0;
                        }
                        ?>
                        {{$points}}
                    </td>
                    <td>{{$workDetail->APS}}</td>
                    <td>{{$workDetail->Remarks}}</td>
                @else
                    @if($overrunReport != 2)
                        <td>{{$workDetail->Dzongkhag}}</td>
                        <td>{{$workDetail->Status}}<?php $awardedAmount+=($workDetail->ReferenceNo == 3001)?1:0;?></td>
                        <td>
                            <?php if((int)$workDetail->APS == 100) {
                                $points = 10;
                            }
                            if(((int)$workDetail->APS < 100) && ((int)$workDetail->APS >= 50)) {
                                $points = 10 - (ceil((100 - (int)$workDetail->APS) / 5));
                            }
                            if((int)$workDetail->APS < 50){
                                $points = 0;
                            }
                            ?>
                            {{$points}}
                        </td>
                        <td>{{$workDetail->APS}}</td>
                        <td>{{$workDetail->Remarks}}</td>
                    @endif
                @endif

            </tr>
            <?php $count++ ?>
        @empty
            <tr><td colspan="@if(isset($overrunReport))@if($overrunReport == 1){{"15"}}@else{{"5"}}@endif @else{{"13"}}}@endif" class="font-red text-center">No data to display</td></tr>
        @endforelse
        @if(isset($overrunReport))
            @if(count($workDetails) > 0)
                <tr>
                    <td colspan="@if(isset($overrunReport))@if($overrunReport == 1){{"9"}}@else{{"4"}}@endif @endif" class="bold text-right">Total:</td>
                    <td class="text-right">{{number_format($totalCostOverrun)}}</td>
                    @if($overrunReport == 1)
                        <td colspan="5"></td>
                    @endif
                </tr>
            @endif
        @endif
        </tbody>
    </table>
</div>
@stop