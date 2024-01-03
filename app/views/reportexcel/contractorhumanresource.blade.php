<html>
    <body>
        <div>
            <table>
                <thead>
                <tr>
                    <th colspan="6">Contractor's Key Personnel</th></tr>
                <tr>
                    <th colspan="6">Contractor: @if(isset($contractor[0]->NameOfFirm)){{$contractor[0]->NameOfFirm." (CDB No.: ".$contractor[0]->CDBNo.")"}}@endif </th>
                </tr>
                <tr>
                    <th colspan="7"></th>
                </tr>
                <tr>
                    <th>Sl.No.</th>
                    <th>Name</th>
                    <th>Designation</th>
                    <th>CID No.</th>
                    <th>Qualification</th>
                    <th>Trade</th>
                    <th>Nationality</th>
                </tr>
                </thead>
                <tbody>
                    <?php $count = 1; ?>
                    @foreach($reportData as $data)
                        <tr>
                            <td>{{$count}}</td>
                            <td>{{$data->Name}}</td>
                            <td>{{$data->Designation}}</td>
                            <td>{{$data->CIDNo}}</td>
                            <td>{{$data->Qualification}}</td>
                            <td>{{$data->Trade}}</td>
                            <td>{{$data->Country}}</td>
                        </tr>
                        <?php $count++; ?>
                    @endforeach
                </tbody>
            </table>
        </div>
    </body>
</html>
