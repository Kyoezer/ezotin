<html>
    <body>
        <table>
            <thead>
            <tr>
                <th colspan="13">Consultant Application Details</th>
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
						Firm/Name
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
            @forelse($consultantLists as $consultant)
            <tr>       		
                    <td>{{$count}}</td>
                    <td>{{$consultant->ReferenceNo}} <br> {{$consultant->ApplicationDate}}</td>
                    <td>{{$consultant->NameOfFirm}} </td>
                    <td>{{$consultant->CDBNo}} </td>
                    <td>{{$consultant->ServiceType}}</td>
                    <td>{{$consultant->CurrentStatus}}</td>
              
                    <td>{{$consultant->VerifiedBy}}</td> <br> {{$consultant->RegistrationVerifiedDate}}</td>
                    <td>{{$consultant->AprroverBy}}</td> <br> {{$consultant->RegistrationApprovedDate}}</td>
                    <td>{{$consultant->PaymentApprover}} </td> <br> {{$consultant->ApplicationDate}}</td>
                    <td>{{$consultant->RejectedBy}}</td> <br> {{$consultant->RejectedDate}}</td>
                    <td>{{$consultant->RemarksByRejector}}</td>
                    <td>{{$consultant->CurrentlyPickedBy}}</td>
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
