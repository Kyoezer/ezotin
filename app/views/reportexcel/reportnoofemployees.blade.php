<html>
    <body>
        <table>
            <thead>
            <tr>
                <th colspan="13">No. of employees</th>
            </tr>
                @if($dzongkhag != '')
                    <tr>
                    <th><i>Dzongkhag:</i>&nbsp;{{$dzongkhag}}</th>
                        </tr>
                @endif
                @if($country != '')
                    <tr>
                        <th><i>Country:</i>&nbsp;{{$country}}</th>
                    </tr>
                @endif
                @if($gender != '')
                    <tr>
                    <th><i>Gender:</i>&nbsp;{{$gender}}</th>
                        </tr>
                @endif
                @if($fromDate != '')
                    <tr>
                        <th><i>Joining Date from:</i>&nbsp;{{$fromDate}}</th>
                    </tr>
                @endif
                @if($toDate != '')
                    <tr>
                        <th><i>Joining Date to:</i>&nbsp;{{$toDate}}</th>
                    </tr>
                @endif
                @if($type != '')
                    <tr>
                    <th><i>Type:</i>&nbsp;{{$type}}</th>
                        </tr>
                @endif
                <tr>
                    <th><i>First {{$limit?$limit:20}} Records</i></th>
                </tr>
            <tr><th colspan="10"></th></tr>
            <tr>
                <th>
                    Sl.No.
                </th>
                <th class="order">
                    Firm Name
                </th>
                <th>
                    Name
                </th>
                <th class="">
                    Dzongkhag
                </th>
                <th class="">
                    CID No.
                </th>
                <th>
                    Sex
                </th>
                <th>
                    Joining Date
                </th>
                <th>
                    Country
                </th>
                <th class="">
                    Qualification
                </th>
                <th class="">
                    Designation
                </th>
                <th class="">
                    Trade
                </th>
            </tr>
            </thead>
            <tbody>
            <?php $count = 1; ?>
            @forelse($reportData as $value)
                <tr>
                    <td>{{$count}}</td>
                    <td>{{$value->FirmName}}</td>
                    <td>{{$value->Name}}</td>
                    <td>{{$value->Dzongkhag}}</td>
                    <td>{{$value->CIDNo}}</td>
                    <td>{{$value->Sex}}</td>
                    <td>{{convertDateToClientFormat($value->JoiningDate)}}</td>
                    <td>{{$value->Country}}</td>
                    <td>{{$value->Qualification}}</td>
                    <td>{{$value->Designation}}</td>
                    <td>{{$value->Trade}}</td>
                </tr>
                <?php $count++; ?>
            @empty
                <tr>
                    <td colspan="10" class="text-center font-red">No data to display!</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </body>
</html>
