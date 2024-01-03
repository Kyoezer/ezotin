@extends('reportexcelmaster')
@section('content')
<div>
    <table>
        <thead>
        <tr>
            <th colspan="12">Master Report</th>
        </tr>
        <tr>
            <th colspan="12"></th>
        </tr>
        @foreach($parametersForPrint as $key=>$value)
            <tr>
                <th colspan="6"><i>{{$key}}:</i>&nbsp;{{$value}}</th>
            </tr>
        @endforeach
        <tr><th colspan="13"></th></tr>
        <tr>
            <th>Sl.No.</th>
            <th>Year</th>
            <th>
                Firm
            </th>
            <th>Agency</th>
            <th>Work Id</th>
            <th>Work Name</th>
            <th>Category</th>
            <th>Class</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Awarded Amount (Nu.)</th>
            <th>Final Amount (Nu.)</th>
            <th>Dzongkhag</th>
            <th>Status</th>
            <th>APS scoring</th>
            <th>APS (100)</th>
            <th>LD</th>
            <th>Hindrance</th>
            <th>Remarks</th>
        </tr>
        </thead>
        <tbody>
        <?php $count = 1; $awardedAmount = 0; $totalCostOverrun = 0;?>
        @forelse($workDetails as $workDetail)
            <tr>
                <td>{{$count}}</td>
                <td>{{$workDetail->Year}}</td>
                <td>{{$workDetail->Contractor}} ({{$workDetail->CDBNo}})</td>
                <td>{{$workDetail->Agency}}</td>
                <td>{{$workDetail->WorkId}}</td>
                <td>{{strip_tags($workDetail->NameOfWork)}}</td>
                <td>{{$workDetail->Category}}</td>
                <td>{{$workDetail->Classification}}</td>
                <td>{{convertDateToClientFormat($workDetail->WorkStartDate)}}</td>
                <td>{{convertDateToClientFormat($workDetail->CompletionDateFinal)}}</td>
                <td class="text-right">{{number_format($workDetail->AwardedAmount,2)}}</td>
                <td class="text-right">{{number_format($workDetail->FinalAmount,2)}}</td>
                <td>{{$workDetail->Dzongkhag}}</td>
                <td>{{$workDetail->Status}}</td>
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
            </tr>
            <?php $count++ ?>
        @empty
            <tr><td colspan="19" class="font-red text-center">No data to display</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@stop