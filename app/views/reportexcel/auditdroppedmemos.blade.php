<html>
    <body>
        <div>
            <table>
                <thead>
                <tr>
                    <th colspan="2">Audit Memo Report (Dropped)</th>
                </tr>
                @foreach($parametersForPrint as $key=>$value)
                    <tr>
                        <th><i>{{$key}}: </i>&nbsp;{{$value}}</th>
                    </tr>
                @endforeach
                <tr>
                    <th colspan="4"></th>
                </tr>
                <tr>
                    <th>Name of Firm</th>
                    <th>CDB No.</th>
                    <th>Agency</th>
                    <th>Audited Period</th>
                    <th>AIN</th>
                    <th>Para No.</th>
                    <th>Audit Observation</th>
                    <th>Dropped by</th>
                    <th>Dropped on</th>
                    <th>Remarks</th>
                </tr>
                </thead>
                <tbody>
                @forelse($reportData as $data)
                    <tr>
                        <td>{{$data->NameOfFirm}}</td>
                        <td>{{$data->CDBNo}}</td>
                        <td>{{$data->Agency}}</td>
                        <td>{{$data->AuditedPeriod}}</td>
                        <td>{{$data->AIN}}</td>
                        <td>{{$data->ParoNo}}</td>
                        <td>{{$data->AuditObservation}}</td>
                        <td>{{$data->Dropper}}</td>
                        <td>{{convertDateToClientFormat($data->DroppedDate)}}</td>
                        <td>{{$data->Remarks}}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center font-red">No data to display</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </body>
</html>
