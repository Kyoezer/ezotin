<html>
    <body>
        <table>
            <thead>
            <tr>
                <th colspan="12">List of Surveyors</th>
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
            @forelse($surveyList as $survey)
                <tr>
                    <td>{{$count}}</td>
                    <td>{{$survey->ARNo}}</td>
                    <td>{{$survey->SurveyName}}</td>
                    <td>{{$survey->CIDNo}}</td>
                    <td>{{$survey->Sector}}</td>
                    <td>{{$survey->Country}}</td>
                    <td>{{$survey->Dzongkhag}}</td>
                    <td>{{$survey->Gewog}}</td>
                    <td>{{$survey->Village}}</td>
                    <td>{{$survey->Email}}</td>
                    <td>{{$survey->MobileNo}}</td>
                    <td>{{$survey->ExpiryDate}}</td>
                    <td>{{$survey->Status}}</td>
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
