<html>
    <body>
        <table>
            <thead>
            <tr>
                <th colspan="13">List of Specialized Firm with service avail</th>
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
                    Name of Firm
                </th>
                <th>
                    Application Date
                </th>
                <th class="">
                    Application Approved Date
                </th>
                <th class="">
                    Service Type
                </th>
                      
            </tr>
            </thead>
            <tbody>
            <?php $count = 1; ?>
            @forelse($specializedfirmLists as $specializedfirm)
            <tr>
                    <td>{{$count}}</td>
                    <td>{{$specializedfirm->SPNo}}</td>
                    <td>{{$specializedfirm->Name}}</td>
                    <td>{{$specializedfirm->ApplicationDate}}</td>
                    <td>{{$specializedfirm->RegistrationApprovedDate}}</td>
                    <td>{{$specializedfirm->ServiceType}}</td>
       
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
