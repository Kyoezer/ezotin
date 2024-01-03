<html>
    <body>
        <div>
            <table>
                <thead>
                <tr>
                    <th colspan="2">@if((int)$type == 1){{"APS"}}@elseif((int)$type ==2){{"Ontime Completion score"}}@else{{"Quality of Execution score"}}@endif of Completed Contractor</th>
                </tr>
                @if($fromDate != '')
                    <tr>
                        <th><i>From Date:</i>&nbsp;{{$fromDate}}</th>
                    </tr>
                @endif
                @if($toDate != '')
                    <tr>
                        <th><i>To Date:</i>&nbsp;{{$toDate}}</th>
                    </tr>
                @endif
                <tr>
                    <th colspan="4"></th>
                </tr>
                <tr>
                    <th>Sl.No.</th>
                    <th>CDB No.</th>
                    <th>Contractor</th>
                    <th>Year</th>
                    <th>Agency</th>
                    <th>Work Id</th>
                    <th>Work Name</th>
                    <th>Category</th>
                    <th>Awarded Amount</th>
                    <th>Final Amount</th>
                    <th>Dzongkhag</th>
                    <th>Status</th>
 <th>Work Start Date</th>
 <th>Work Completion Date</th>
                    @if((int)$type == 1 || (int)$type == 2)
                        <th>Ontime Completion</th>
                    @endif
                    @if((int)$type == 1 || (int)$type == 3)
                        <th>Quality of Execution</th>
                    @endif
                </tr>
                </thead>
                <tbody>
                <?php $start = 1; ?>
                @foreach($reportData as $workDetail)
                    <tr>
                        <td>{{$start++}}</td>
                        <td>{{$workDetail->CDBNo}}</td>
                        <td>{{$workDetail->Contractor}}</td>
                        <td>{{$workDetail->Year}}</td>
                        <td>{{$workDetail->Agency}}</td>
                        <td>{{$workDetail->WorkId}}</td>
                        <td>{{strip_tags($workDetail->NameOfWork)}}</td>
                        <td>{{$workDetail->Category}}</td>
                        <td>{{$workDetail->AwardedAmount}}</td>
                        <td>{{$workDetail->FinalAmount}}</td>
                        <td>{{$workDetail->Dzongkhag}}</td>
                        <td>{{$workDetail->Status}}</td>
<td>{{$workDetail->WorkStartDate}}</td>
<td>{{$workDetail->WorkCompletionDate}}</td>
                        @if((int)$type == 1 || (int)$type == 2)
                            <td>
                                {{$workDetail->OntimeCompletionScore}}
                            </td>
                        @endif
                        @if((int)$type == 1 || (int)$type == 3)
                            <td>{{$workDetail->QualityOfExecutionScore}}</td>
                        @endif
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </body>
</html>
