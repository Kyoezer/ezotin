<html>
    <body>
        <table>
            <thead>
            <tr>
                <th colspan="13">List of Consultants</th>
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
                @if($classification != '')
                <tr>
                    <th><i>Classification:</i>&nbsp;{{$classification}}</th>
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
                <th>Sl.No.</th>
                <th>
                    CDB No.
                </th>
                <th>
                    Firm/Name
                </th>
		<th>
                    OwnershipType
                </th>
<th>Owner Name</th>
<th>Gender</th>
                <th class="">
                    Address
                </th>
                <th>
                    Country
                </th>
                <th class="">
                    Dzongkhag
                </th>
<th>
                    Email
                </th>
                <th class="">
                    Tel. No.
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
            <?php $count=1; ?>
            @forelse($consultantsList as $consultant)
                <tr>
                    <td>{{$count}}</td>
                    <td>{{$consultant->CDBNo}}</td>
                    <td>{{$consultant->NameOfFirm}}</td>
		    <td>{{$consultant->OwnershipType}}</td>
		    <td>{{$consultant->Name}}</td>
  		    <td>{{$consultant->Sex}}</td>
                    <td>{{$consultant->Address}}</td>
                    <td>{{$consultant->Country}}</td>
                    <td>{{$consultant->Dzongkhag}}</td>
 		    <td>{{$consultant->Email}}</td>
                    <td>{{$consultant->TelephoneNo}}</td>
                    <td>{{$consultant->MobileNo}}</td>
                    <td>{{$consultant->CategoryA}}</td>
                    <td>{{$consultant->CategoryC}}</td>
                    <td>{{$consultant->CategoryE}}</td>
		    <td>{{$consultant->CategoryS}}</td>
                    <td>{{$consultant->ExpiryDate}}</td>
                    <td>{{$consultant->Status}}</td>
                </tr>
                <?php $count++; ?>
                @empty
                <tr>
                    <td colspan="13" class="font-red text-center">No data to display!</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </body>
</html>
