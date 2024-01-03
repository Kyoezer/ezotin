<html>
    <body>
        <table>
            <thead>
            <tr>
                <th colspan="13">List of Engineer nearing expiry</th>
            </tr>
                @if($cdbNo != '')
                    <tr>
                    <th><i>CDB No.:</i>&nbsp;{{$cdbNo}}</th>
                        </tr>
                @endif
                @if($fromDate != '')
                    <tr>
                    <th><i>From Date:</i>&nbsp;{{$fromDate}}</th>
                        </tr>
                @endif
                @if($toDate != '')
                    <tr>
                    <th><i>To Date:</i>&nbsp;{{$toDate}}</th>
                        </tr>
                @endif
                @if($country != '')
                    <tr>
                    <th><i>Country:</i>&nbsp;{{$country}}</th>
                        </tr>
                @endif
                @if($dzongkhag != '')
                    <tr>
                    <th><i>Dzongkhag:</i>&nbsp;{{$dzongkhag}}</th>
                        </tr>
                @endif
                @if($trade != '')
                <tr>
                    <th><i>Trade:</i>&nbsp;{{$trade}}</th>
                </tr>
                @endif
                @if($sector != '')
                <tr>
                    <th><i>Sector:</i>&nbsp;{{$sector}}</th>
                </tr>
                @endif
                @if($status != '')
                    <tr>
                    <th><i>Status:</i>&nbsp;{{$status}}</th>
                        </tr>
                @endif
                <tr>
                    <th><i>First {{$limit?$limit:20}} Records</i></th>
                </tr>
            <tr><th colspan="13"></th></tr>
            <tr>
                <th>
                    Sl.No.
                </th>
                <th>
                    CDB No.
                </th>
                <th>
                    Name
                </th>
                <th class="">
                    CID No.
                </th>
                <th class="">
                    Sector
                </th>
                <th>
                    Trade
                </th>
                <th>
                    Country
                </th>
                <th class="">
                    Dzongkhag
                </th>
                <th class="">
                    Gewog
                </th>
                <th class="">
                    Village
                </th>
                <th class="">
                    Email
                </th>
                <th class="">
                    Mobile No.
                </th>
                <th>
                    Expiry Date
                </th>
                <th>Status</th>
            </tr>
            </thead>
            <tbody>
            <?php $count = 1; ?>
            @forelse($engineersList as $engineer)
                <tr>
                    <td>{{$count}}</td>
                    <td>{{$engineer->CDBNo}}</td>
                    <td>{{$engineer->EngineerName}}</td>
                    <td>{{$engineer->CIDNo}}</td>
                    <td>{{$engineer->Sector}}</td>
                    <td>{{$engineer->Trade}}</td>
                    <td>{{$engineer->Country}}</td>
                    <td>{{$engineer->Dzongkhag}}</td>
                    <td>{{$engineer->Gewog}}</td>
                    <td>{{$engineer->Village}}</td>
                    <td>{{$engineer->Email}}</td>
                    <td>{{$engineer->MobileNo}}</td>
                    <td>{{$engineer->ExpiryDate}}</td>
                    <td>{{$engineer->Status}}</td>
                </tr>
                <?php $count++; ?>
            @empty
                <tr>
                    <td colspan="14" class="font-red text-center">No data to display!</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </body>
</html>
