<html>
    <body>
        <table>
            <thead>
            <tr>
                <th colspan="9">List of Terminated/Cancelled Contractors</th>
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
                @if($status != '')
                    <tr>
                    <th><i>Status:</i>&nbsp;{{$status}}</th></tr>
                @endif
            <tr><th colspan="9"></th></tr>
            <tr>
                <th>Sl.No.</th>
                <th>Procuring Agency</th>
                <th>Work Id</th>
                <th>Name of Work</th>
                <th>Contract Period</th>
                <th>Contractors</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Status</th>
            </tr>
            </thead>
            <tbody>
            <?php $count = 1; ?>
            @forelse($reportData as $data)
                <tr>
                    <td>{{$count}}</td>
                    <td>{{$data->ProcuringAgency}}</td>
                    <td>{{$data->WorkId}}</td>
                    <td>{{$data->NameOfWork}}</td>
                    <td>{{$data->ContractPeriod}}</td>
                    <td>{{$data->Contractors}}</td>
                    <td>{{$data->StartDate}}</td>
                    <td>{{$data->EndDate}}</td>
                    <td>{{$data->Status}}</td>
                </tr>
                <?php $count++ ?>
            @empty
                <tr><td colspan="9" class="font-red text-center">No data to display</td></tr>
            @endforelse
            </tbody>
        </table>
    </body>
</html>
