<html>
    <body>
        <div>
            <table>
                <thead>
                <tr>
                    <th colspan="2">Dzongkhag Wise Work Awarded and Completed </th>
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
                    <th>Dzongkhag (Site)</th>
                    <th>No. of Awarded Works</th>
                    <th>No. of Completed Works</th>
                    <th>Total</th>
                </tr>
                </thead>
                <tbody>
                <?php $noAwarded = $noCompleted = $total = 0; ?>
                @forelse($reportData as $data)
                    <tr>
                        <td>{{$data->Dzongkhag}}</td>
                        <td class="text-right">{{$data->NoAwarded}}<?php $noAwarded += $data->NoAwarded;?></td>
                        <td class="text-right">{{$data->NoCompleted}}<?php $noCompleted += $data->NoCompleted;?></td>
                        <td class="text-right">{{$data->NoAwarded+$data->NoCompleted}}<?php $total += $data->NoAwarded+$data->NoCompleted;?></td>
                    </tr>
                @empty

                    <tr><td colspan="4" class="font-red text-center">No data to display</td></tr>
                @endforelse
                @if(!empty($reportData))
                    <tr>
                        <td class="bold text-right"><b>Total</b></td>
                        <td class="text-right">{{$noAwarded}}</td>
                        <td class="text-right">{{$noCompleted}}</td>
                        <td class="text-right">{{$total}}</td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
    </body>
</html>
