<html>
    <body>
        <table>
            <thead>
            <tr>
                <th colspan="13">List of Architect with service avail</th>
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
                AR No.
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
            @forelse($architectLists as $architect)
            <tr>
                    <td>{{$count}}</td>
                    <td>{{$architect->ARNo}}</td>
                    <td>{{$architect->Name}}</td>
                    <td>{{$architect->ApplicationDate}}</td>
                    <td>{{$architect->RegistrationApprovedDate}}</td>
                    <td>{{$architect->ServiceType}}</td>
       
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
