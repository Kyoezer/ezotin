<html>
    <body>
        <table>
            <thead>
            <tr>
                <th colspan="13">Contractor Application Details</th>
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
            <?php $count= 1; ?>
            @forelse($contractorLists as $contractor)
            <tr>
        
                      <td>{{$count++}}</td>
                <td>{{$contractor->ReferenceNo}}<br>{{$contractor->ApplicationDate}}</td>
                <td>{{$contractor->NameOfFirm}} </td>
                <td>{{$contractor->CDBNo}} </td>
                <td>{{$contractor->ServiceType}}</td>
                <td>{{$contractor->CurrentStatus}}</td>
                <td>{{$contractor->VerifiedBy}}<br> {{$contractor->RegistrationVerifiedDate}}</td>
                <td>{{$contractor->AprroverBy}} <br> {{$contractor->RegistrationApprovedDate}}</td>
                <td>{{$contractor->PaymentApprover}}<br> {{$contractor->RegistrationPaymentApprovedDate}}</td>
                <td>{{$contractor->RejectedBy}} <br> {{$contractor->RejectedDate}}</td>
                <td>{{$contractor->RemarksByRejector}}</td>
                <td>{{$contractor->CurrentlyPickedBy}}</td>
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
