<html>
    <body>
        <div>
            <table>
                <thead class="flip-content">
                <tr>
                    <th>CDB No</th>
                    <th>Equipment Count in New DB</th>
                    <th>Equipment Count in Old DB</th>
                </tr>
                </thead>
                <tbody>
                @foreach($reportData as $data)
                    <tr>
                        <td>
                            {{$data->CDBNo}}
                        </td>
                        <td>{{$data->EquipmentsInNewDB}}</td>
                        <td>{{$data->EquipmentsInOldDB}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </body>
</html>
