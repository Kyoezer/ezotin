<html>
    <body>
        <table>
            <thead>
            <tr>
                <th colspan="13">List of Contractors</th>
            </tr>
      
            <tr>
                <th>
                    Sl.No.
                </th>
                <th class="order">
                    CDB No.
                </th>
<th>Owner Name</th>
		<th>OwnershipType</th>
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
                    Email
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
 <td>{{$contractor->NAME}}</td>
		    <td>{{$contractor->OwnershipType}}</td>
                    <td>{{$contractor->NameOfFirm}}</td>
                    <td>{{$contractor->Address}}</td>
                    <td>{{$contractor->Country}}</td>
                    <td>{{$contractor->Dzongkhag}}</td>
<td>{{$contractor->Email}}</td>
                    <td>{{$contractor->TelephoneNo}}</td>
                    <td>{{$contractor->MobileNo}}</td>
                    <td>{{$contractor->Classification1}}</td>
                    <td>{{$contractor->Classification2}}</td>
                    <td>{{$contractor->Classification3}}</td>
                    <td>{{$contractor->Classification4}}</td>
                    <td>{{convertDateToClientFormat($contractor->ExpiryDate)}}</td>
                  
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
