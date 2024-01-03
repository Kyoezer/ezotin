<html>
    <body>
        <table>
            <thead>
            <tr>
                <th colspan="13">List of Deregistered Consultant</th>
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
<th>Owner Name</th>
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
                    A
                </th>
                <th class="">
                    C
                </th>
                <th class="">
                    E
                </th>
                <th class="">
                    S
                </th>
                <th>
                    Expiry Date
                </th>
                <th>Status</th>
            </tr>
            </thead>
            <tbody>
            <?php $count = 1; ?>
            @forelse($consultantLists as $consultant)
            <tr>
                    <td>{{$count++}}</td>
                    <td>{{$consultant->CDBNo}}</td>
<td>{{$consultant->Name}}</td>
                    <td>{{$consultant->NameOfFirm}}</td>
                    <td>{{$consultant->Address}}</td>
                    <td>{{$consultant->Country}}</td>
                    <td>{{$consultant->Dzongkhag}}</td>
                    <td>{{$consultant->TelephoneNo}}</td>
                    <td>{{$consultant->MobileNo}}</td>
                    <td>{{$consultant->CategoryA}}</td>
                    <td>{{$consultant->CategoryC}}</td>
                    <td>{{$consultant->CategoryE}}</td>
                    <td>{{$consultant->CategoryS}}</td>
                    <td>{{convertDateToClientFormat($consultant->ExpiryDate)}}</td>
                    <td>{{$consultant->STATUS}}</td>
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
