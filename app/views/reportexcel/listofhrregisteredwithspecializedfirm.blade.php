<html>
    <body>
        <table>
            <thead>
            <tr>
                <th colspan="13">List of Hr Registered with Specialized Firm</th>
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
                Name
                </th>
                 <th>
                    CIDNo
                </th>
                <th>
                   Individual Reg No
                </th>
   <th>
                    Gender
                </th>
<th>Country</th>
                <th>
                    Qualification
                </th>
                <th>
                    Designation
                </th>
   <th>
                    Joining Date
                </th>
                <th class="">
                    SP No.
                </th>
                <th class="">
                    Name of Firm
                </th>
                      
            </tr>
            </thead>
            <tbody>
            <?php $count = 1; ?>
            @forelse($specializedfirmLists as $specializedfirm)
            <tr>
                    <td>{{$count}}</td>
                    <td>{{$specializedfirm->Name}}</td>
                    <td>{{$specializedfirm->CIDNo}}</td>
                    <td>{{$specializedfirm->IndividualRegNo}}</td>
 <td>{{$specializedfirm->Gender}}</td>
 <td>{{$specializedfirm->Country}}</td>
                    <td>{{$specializedfirm->Qualification}}</td>
                    <td>{{$specializedfirm->Designation}}</td>
 <td>{{$specializedfirm->JoiningDate}}</td>
                    <td>{{$specializedfirm ->SPNo}}</td>
                    <td>{{$specializedfirm->Firm}}</td>
       
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
