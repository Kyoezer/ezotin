<html>
    <body>
        <div>
            <table>
                <thead>
                <tr>
                    <th colspan="2">Applications cancelled after Non Payment (Dropped)</th>
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
                    <th>Applicant Name / Name of Firm</th>
                    <th>CDB No.</th>
                    <th>Application No.</th>
                    <th>Payment Approved On</th>
                    <th>Application Cancelled On</th>
                </tr>
                </thead>
                <tbody>
                @forelse($reportData as $data)
                    <tr>
                        <td>{{$data->Applicant}} <br/>
                            @if((int)$data->TypeCode == 1)
                                (Contractor)
                            @elseif((int)$data->TypeCode == 2)
                                (Consultant)
                            @elseif((int)$data->TypeCode == 3)
                                (Architect)
                            @else
                                (Specialized Trade)
                            @endif
                        </td>
                        <td>{{$data->CDBNo}}</td>
                        <td>{{$data->ReferenceNo}}</td>
                        <td>{{convertDateToClientFormat(strtotime($data->DateOfNotification." - 15 days"))}}</td>
                        <td>{{convertDateToClientFormat(strtotime($data->DateOfNotification." + 15 days"))}}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center font-red">No data to display</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </body>
</html>
