<html>
    <body>
        <table>
            <thead>
            <tr>
                <th colspan="12">List of Equipment Registered with Specialized Firm</Ri:a></th>
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
                    SPNo
                </th>
                      
            </tr>
            </thead>
            <tbody>
            <?php $count = 1; ?>
            @forelse($specializedfirmLists as $specializedfirm)
                <tr>
                    <td>{{$count}}</td>
                    <td>{{$specializedfirm->Firm}}</td>
                    <td>{{$specializedfirm->EquimentName}}</td>
                    <td>{{$specializedfirm->RegistrationNo}}</td>
                    <td>{{$specializedfirm->SPNo}}</td>
                
       
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
