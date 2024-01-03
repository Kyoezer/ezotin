<html>
    <body>
        <div>
            <table>
                <thead>
                <tr>
                    <th colspan="6">Contractor's Equipment</th></tr>
                <tr>
                    <th colspan="6">Contractor: @if(isset($contractor[0]->NameOfFirm)){{$contractor[0]->NameOfFirm." (CDB No.: ".$contractor[0]->CDBNo.")"}}@endif </th>
                </tr>
                <tr>
                    <th colspan="7"></th>
                </tr>
                <tr>
                    <th>Sl.No.</th>
                    <th>Equipment Name</th>
                    <th>Registration No.</th>
                    <th>Model No.</th>
                    <th>Quantity</th>
                </tr>
                </thead>
                <tbody>
                    <?php $count = 1; ?>
                    @foreach($reportData as $data)
                        <tr>
                            <td>{{$count}}</td>
                            <td>{{$data->Equipment}}</td>
                            <td>{{$data->RegistrationNo}}</td>
                            <td>{{$data->ModelNo}}</td>
                            <td>{{$data->Quantity}}</td>
                        </tr>
                        <?php $count++; ?>
                    @endforeach
                </tbody>
            </table>
        </div>
    </body>
</html>
