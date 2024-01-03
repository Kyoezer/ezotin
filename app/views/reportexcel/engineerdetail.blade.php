<html>
    <body>
        <table>
            <thead>
            <tr>
                <th colspan="13">Engineer Application Details</th>
            </tr>
            @foreach($parametersForPrint as $key=>$value)
                <tr>
                    <th>{{$key}}:</th><th> {{$value}}</th>
                </tr>
            @endforeach
            <tr><th colspan="13"></th></tr>
            <tr>
                    <th>Sl #</th>
                    <th>Application</th>
					<th>
						Name
					</th>
                    <th>
						CDB No.
					</th>
					<th class="">
						 Type
					</th>
                    <th>Current Status</th>
                    <th class="">
                        Verified by
                    </th>
                    <th class="">
                        Approved by
                    </th>
                    <th>Payment Approved by</th>
                    <th>Rejected by</th>
                    <th>Remarks By Rejector</th>
                    <th>Currently Picked By</th>
				</tr>
            </thead>
            <tbody>
            <?php $count = 1; ?>
            @forelse($engineerLists as $engineer)
            <tr>
        
                    <td>{{$count}}</td>
                    <td>{{$engineer->ReferenceNo}} <br> {{$engineer->ApplicationDate}}</td>
                    <td>{{$engineer->Name}} </td>
                    <td>{{$engineer->CDBNo}} </td>
                    <td>{{$engineer->ServiceType}}</td>
                    <td>{{$engineer->CurrentStatus}}</td>
              
                    <td>{{$engineer->VerifiedBy}}</td>
                    <td>{{$engineer->AprroverBy}}</td>
                    <td>{{$engineer->PaymentApprover}} </td>
                    <td>{{$engineer->RejectedBy}}</td>
                    <td>{{$engineer->RemarksByRejector}}</td>
                    <td>{{$engineer->CurrentlyPickedBy}}</td>
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
