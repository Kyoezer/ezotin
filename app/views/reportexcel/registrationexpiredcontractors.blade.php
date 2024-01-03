<html>
    <body>
        <table>
            <thead>
            <tr>
                <th colspan="13">List of Contractors whose Registration has expired</th>
            </tr>
                @if($fromDate != '')
                    <tr>
                    <th><i>From Date:</i>&nbsp;{{$fromDate}}</th>
                        </tr>
                @endif
                @if($dzongkhag != '')
                    <tr>
                    <th><i>Dzongkhag:</i>&nbsp;{{$dzongkhag}}</th>
                        </tr>
                @endif
                <tr>
                    <th><i>{{$limit?$limit:20}} Records</i></th>
                </tr>
            <tr><th colspan="13"></th></tr>
            <tr>
                <th>Sl.No.</th>
                <th>
                    CDB No.
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
                    W1
                </th>
                <th class="">
                    W2
                </th>
                <th class="">
                    W3
                </th>
                <th class="">
                    W4
                </th>
                <th>
                    Expiry Date
                </th>
            </tr>
            </thead>
            <tbody>
            <?php $count = 1; ?>
            @forelse($contractorsList as $contractor)
                <tr>
                    <td>{{$count}}</td>
                    <td>{{$contractor->CDBNo}}</td>
                    <td>{{$contractor->NameOfFirm}}</td>
                    <td>{{$contractor->Address}}</td>
                    <td>{{$contractor->Country}}</td>
                    <td>{{$contractor->Dzongkhag}}</td>
                    <td>{{$contractor->TelephoneNo}}</td>
                    <td>{{$contractor->MobileNo}}</td>
                    <td>{{$contractor->Classification1}}</td>
                    <td>{{$contractor->Classification2}}</td>
                    <td>{{$contractor->Classification3}}</td>
                    <td>{{$contractor->Classification4}}</td>
                    <td>{{$contractor->ExpiryDate}}</td>
                </tr>
                <?php $count++; ?>
            @empty
                <tr>
                    <td colspan="13" class="text-center font-red">No data to display!</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </body>
</html>
