<html>
    <body>
        <table>
            <thead>
            <tr>
                <th colspan="13">List of Revoked/Suspended/Debarred Specializedfirms</th>
            </tr>
            @foreach($parametersForPrint as $key=>$value)
                <tr>
                    <th>{{$key}}:</th><th> {{$value}}</th>
                </tr>
            @endforeach
            <tr><th colspan="13"></th></tr>
            <tr>
                <th>
                    Sl.No.
                </th>
                <th class="order">
                    SP No.
                </th>
                <th>
                    Firm/Name
                </th>
                <th class="">
                    Address
                </th>
                <th class="">
                    Country
                </th>
                <th>
                    Dzongkhag
                </th>
                <th>
                    Tel.No.
                </th>
                <th class="">
                    Mobile No.
                </th>
                <th class="">
                    Category
                </th>
          
                <th>
                    Expiry Date
                </th>
                <th>Status</th>
            </tr>
            </thead>
            <tbody>
            <?php $count = 1; ?>
            @forelse($specializedfirmLists as $specializedfirm)
                <tr>
                    <td>{{$count}}</td>
                    <td>{{$specializedfirm->SPNo}}</td>
                    <td>{{$specializedfirm->NameOfFirm}}</td>
                    <td>{{$specializedfirm->Address}}</td>
                    <td>{{$specializedfirm->Country}}</td>
                    <td>{{$specializedfirm->Dzongkhag}}</td>
                    <td>{{$specializedfirm->TelephoneNo}}</td>
                    <td>{{$specializedfirm->MobileNo}}</td>
                    <td>{{$specializedfirm->Category}}</td>
                
                    <td>{{convertDateToClientFormat($specializedfirm->ExpiryDate)}}</td>
                    <td>{{$specializedfirm->Status}}</td>
                </tr>
                <?php $count++; ?>
            @empty
                <tr>
                    <td colspan="14" class="text-center font-red">No data to display!</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </body>
</html>
