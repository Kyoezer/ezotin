<html>
    <body>
        <table>
            <thead>
            <tr>
                <th colspan="13">List of Expired Certified Builder</th>
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

<th>Owner Name</th>
<th>Gender</th>
  <th>
                    Email
                </th>
                <th class="">
                    Address
                </th>
                <th>
                    Country
                </th>
                <th class="">
                    Dzongkhag
                </th>
                <th class="">
                    Tel. No.
                </th>
                <th class="">
                    Mobile No.
                </th>
                <th class="">
                    Category
                </th>
               
                <th>
                    Expiry Date
                </th>
                <th>Status</th>
            </tr>
            </thead>
            <tbody>
            <?php $count=1; ?>
            @forelse($certifiedbuilderList as $specializedfirm)
                <tr>
                    <td>{{$count}}</td>
                    <td>{{$specializedfirm->CDBNo}}</td>
                    <td>{{$specializedfirm->NameOfFirm}}</td>
 <td>{{$specializedfirm->Name}}</td>
                     <td>{{$specializedfirm->Sex}}</td>
 <td>{{$specializedfirm->Email}}</td>
                    <td>{{$specializedfirm->Address}}</td>
                    <td>{{$specializedfirm->Country}}</td>
                    <td>{{$specializedfirm->Dzongkhag}}</td>
                    <td>{{$specializedfirm->TelephoneNo}}</td>
                    <td>{{$specializedfirm->MobileNo}}</td>
                    <td>{{$specializedfirm->Category}}</td>
                    <td>{{$specializedfirm->ExpiryDate}}</td>
                   			<td >
					
							@if($specializedfirm->ExpiryDate<=date('Y-m-d G:i:s'))
							<p class="font-red bold warning">Expired</p>
							@else
							{{convertDateToClientFormat($specializedfirm->ExpiryDate)}}
							@endif
					
					</td>
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
