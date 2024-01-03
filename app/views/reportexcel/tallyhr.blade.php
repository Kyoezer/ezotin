<html>
    <body>
        <div>
            <table>
                <thead class="flip-content">
                <tr>
                    <th>CDB No</th>
                    <th>Partner Count in New DB</th>
                    <th>Partner Count in Old DB</th>
                    <th>Employee Count in Old DB</th>
                    <th>Employee Count in New DB</th>
                </tr>
                </thead>
                <tbody>
                @foreach($reportData as $data)
                    <tr>
                        <td>
                            {{$data->CDBNo}}
                        </td>
                        <td>{{$data->PartnerCountInNewDB}}</td>
                        <td>{{$data->PartnerCountInOldDB}}</td>
                        <td>{{$data->EmployeeCountInNewDB}}</td>
                        <td>{{$data->EmployeeCountInOldDB}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </body>
</html>
