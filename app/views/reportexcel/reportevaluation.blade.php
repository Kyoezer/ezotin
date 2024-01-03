<html>
    <body>
        <div>
            <table>
                <thead>
                <tr>
                    <th colspan="2">Evaluation (Report)</th>
                </tr>
                @if($procuringAgency != '')
                    <tr>
                        <th><i>Agency: </i>&nbsp;{{$procuringAgency}}</th>
                    </tr>
                @endif
                @if($class != '')
                    <tr>
                        <th><i>Class: </i>&nbsp;{{$class}}</th>
                    </tr>
                @endif
                @if($category != '')
                    <tr>
                        <th><i>Category: </i>&nbsp;{{$category}}</th>
                    </tr>
                @endif
                @if($fromDate != '')
                    <tr>
                        <th><i>From Date:</i>&nbsp;{{$fromDate}}</th>
                    </tr>
                @endif
                @if($toDate != '')
                    <tr>
                        <th><i>To Date: </i>&nbsp;{{$toDate}}</th>
                    </tr>
                @endif
                <tr>
                    <th colspan="4"></th>
                </tr>
                <tr>
                    <th>Sl. No.</th>
                    <th>Work Id</th>
                    <th>Name of Work</th>
                    <th>Agency</th>
                    <th>CDB No.</th>
                    <th>Class</th>
                    <th>Category</th>
                    <th>Awarded Amount</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody>
                <?php $count = 1; $totalAmount = 0; ?>
                @forelse($reportData as $data)
                    <tr>
                        <td>{{$count}}</td>
                        <td>{{$data->WorkId}}</td>
                        <td>{{strip_tags($data->NameOfWork, '<br><br/><p><ul><li><ol>');}}</td>
                        <td>{{$data->Agency}}</td>
                        <td>{{$data->CDBNo}}</td>
                        <td>{{$data->Class}}</td>
                        <td>{{$data->Category}}</td>
                        <td>{{$data->AwardedAmount?number_format($data->AwardedAmount,2):''}}<?php $totalAmount+=$data->AwardedAmount?$data->AwardedAmount:0; ?></td>
                        <td>{{date_format(date_create($data->ActualStartDate),'d/m/Y')}}</td>
                        <td>{{date_format(date_create($data->ActualEndDate),'d/m/Y')}}</td>
                        <td>{{$data->Status}}</td>
                    </tr>
                    <?php $count++; ?>
                @empty
                    <tr><td colspan="11" class="font-red text-center">No data to display</td></tr>
                @endforelse
                <tr><td colspan="7" class="bold text-right">Total</td><td>{{number_format($totalAmount,2)}}</td><td colspan="3"></td></tr>
                </tbody>
            </table>
        </div>
    </body>
</html>
