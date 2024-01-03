<html>
    <body>
        <table>
            <thead>
            <tr>
                <th colspan="13">List of Specialized Trade with service avail</th>
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
                    Name 
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
            @forelse($specializedtradeLists as $specializedtrade)
            <tr>
                    <td>{{$count}}</td>
                    <td>{{$specializedtrade->SPNo}}</td>
                    <td>{{$specializedtrade->Name}}</td>
                    <td>{{$specializedtrade->ApplicationDate}}</td>
                    <td>{{$specializedtrade->RegistrationApprovedDate}}</td>
                    <td>{{$specializedtrade->ServiceType}}</td>
       
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
