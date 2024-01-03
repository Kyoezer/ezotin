<html>
    <body>
        <table>
            <thead>
            <tr>
                <th colspan="12">List of Equipment Registered with Consultant</Ri:a></th>
            </tr>
         
            <tr><th colspan="12"></th></tr>
            <tr>
                <th>
                    Sl.No.
                </th>
               
                <th>
                    Name Of Firm
                </th>
                <th>
                    Equipment Name
                </th>
                <th class="">
                    Registration No.
                </th>
                <th class="">
                    CDBNo
                </th>
                      
            </tr>
            </thead>
            <tbody>
            <?php $count = 1; ?>
            @forelse($certifiedbuilderLists as $consultant)
                <tr>
                    <td>{{$count}}</td>
                    <td>{{$consultant->Firm}}</td>
                    <td>{{$consultant->EquimentName}}</td>
                    <td>{{$consultant->RegistrationNo}}</td>
                    <td>{{$consultant->CDBNo}}</td>
                
       
                </tr>
                <?php $count++; ?>
            @empty
                <tr>
                    <td colspan="12" class="text-center font-red">No data to display!</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </body>
</html>
