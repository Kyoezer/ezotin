<html>
    <body>
        <table>
            <thead>
            <tr>
                <th colspan="13">List of Hr Registered with Contractors</th>
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
                Individual Reg No.
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
                    CDB No.
                </th>
                <th class="">
                    Name of Firm
                </th>
                      
            </tr>
            </thead>
            <tbody>
            <?php $count = 1; ?>
            @forelse($contractorLists as $contractor)
            <tr>
                    <td>{{$count}}</td>
                    <td>{{$contractor->Name}}</td>
                    <td>{{$contractor->CIDNo}}</td>
                    <td>{{$contractor->IndividualRegNo}}</td>
                    <td>{{$contractor->Gender}}</td>
                    <td>{{$contractor->Country}}</td>
                    <td>{{$contractor->Qualification}}</td>
                    <td>{{$contractor->Designation}}</td>
                     <td>{{$contractor->JoiningDate}}</td>
                    <td>{{$contractor->CDBNo}}</td>
                    <td>{{$contractor->Firm}}</td>
       
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
