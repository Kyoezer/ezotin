<html>
    <body>
        <table>
            <thead>
            <tr>
                <th colspan="12">List of Architect nearing expiry</th>
            </tr>
                @if($arNo != '')
                    <tr>
                    <th><i>AR No.:</i>&nbsp;{{$arNo}}</th>
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
            <tr><th colspan="12"></th></tr>
            <tr>
                <th>
                    Sl.No.
                </th>
                <th>
                    AR No.
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
            @forelse($architectsList as $architect)
                <tr>
                    <td>{{$count}}</td>
                    <td>{{$architect->ARNo}}</td>
                    <td>{{$architect->ArchitectName}}</td>
                    <td>{{$architect->CIDNo}}</td>
                    <td>{{$architect->Sector}}</td>
                    <td>{{$architect->Country}}</td>
                    <td>{{$architect->Dzongkhag}}</td>
                    <td>{{$architect->Gewog}}</td>
                    <td>{{$architect->Village}}</td>
                    <td>{{$architect->Email}}</td>
                    <td>{{$architect->MobileNo}}</td>
                    <td>{{$architect->ExpiryDate}}</td>
                    <td>{{$architect->Status}}</td>
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
