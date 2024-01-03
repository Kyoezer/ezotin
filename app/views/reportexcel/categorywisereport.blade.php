<html>
    <body>
        <table>
            <thead>
            <tr>
                <th colspan="10">Category Wise Report</th>
            </tr>
            <tr>
                <th colspan="10"></th>
            </tr>
                @foreach($parametersForPrint as $key=>$value)
                    <tr>
                        <th>
                            <i>{{$key}}: </i> {{$value}}
                        </th>
                    </tr>
                @endforeach
            <tr><th colspan="11"></th></tr>
            <tr>
                <th>Sl.No.</th>
                <th>CDB No.</th>
                <th>Firm</th>
                <th>Start Date</th>
                <th>Comp. Date</th>
                <th>Name</th>
                <th>Agency</th>
                <th>Class</th>
                <th>Category</th>
                <th>Work Value</th>
            </tr>
            </thead>
            <tbody>
            <?php $start = 1; ?>
            @forelse($reportData as $value)
                <tr>
                    <td>{{$start++}}</td>
                    <td>{{$value->CDBNo}}</td>
                    <td>{{$value->Contractor}}</td>
                    <td>{{convertDateToClientFormat($value->WorkStartDate)}}</td>
                    <td>{{convertDateToClientFormat($value->WorkCompletionDate)}}</td>
                    <td>{{strip_tags($value->NameOfWork)}}</td>
                    <td>{{$value->ProcuringAgency}}</td>
                    <td>{{$value->classification}}</td>
                    <td>{{$value->ProjectCategory}}</td>
                    <td>{{$value->FinalAmount}}</td>
                </tr>
            @empty
                <tr><td colspan="10" class="text-center font-red">No data to display</td></tr>
            @endforelse
            </tbody>
        </table>
    </body>
</html>
