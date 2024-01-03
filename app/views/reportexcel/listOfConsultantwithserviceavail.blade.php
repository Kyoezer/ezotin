<html>
    <body>
        <table>
            <thead>
            <tr>
                <th colspan="13">List of Consultant with service avail</th>
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
                CDB No.
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
            @forelse($consultantLists as $consultant)
            <tr>
                    <td>{{$count}}</td>
                    <td>{{$consultant->CDBNo}}</td>
                    <td>{{$consultant->NameOfFirm}}</td>
                    <td>{{$consultant->ApplicationDate}}</td>
                    <td>{{$consultant->RegistrationApprovedDate}}</td>
                    <td>{{$consultant->ServiceType}}</td>
       
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
