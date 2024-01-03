<html>
    <body>
        <div>
            <table>
                <thead>
                <tr>
                    <th colspan="2">Agency Wise Work</th>
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
                    <th>Agency</th>
                    <th>No. of Awarded Works</th>
                    <th>No. of Completed Works</th>
                    <th>Total</th>
                </tr>
                </thead>
                <tbody>
                <?php $noAwarded = $noCompleted = $total = 0; ?>
                    @forelse($reportData as $data)
                        <tr>
                            <td>{{$data->Agency}}</td>
                            <td>{{$data->NoAwarded}}<?php $noAwarded += $data->NoAwarded;?></td>
                            <td>{{$data->NoCompleted}}<?php $noCompleted += $data->NoCompleted;?></td>
                            <td>{{$data->NoAwarded+$data->NoCompleted}}<?php $total += $data->NoAwarded+$data->NoCompleted;?></td>
                        </tr>
                    @empty

                        <tr><td colspan="4" class="font-red text-center">No data to display</td></tr>
                    @endforelse
                    @if(!empty($reportData))
                        <tr>
                            <td><b>Total</b></td>
                            <td><b>{{$noAwarded}}</b></td>
                            <td><b>{{$noCompleted}}</b></td>
                            <td><b>{{$total}}</b></td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </body>
</html>
