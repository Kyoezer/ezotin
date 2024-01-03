<html>
    <body>
        <table>
            <thead>
            <tr>
                <th colspan="13">Surveyor Application Details</th>
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
						AR No.
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
            @forelse($surveyLists as $surveyor)
                    <tr>
        
                    <td>{{$count}}</td>
                    <td>{{$surveyor->ReferenceNo}} <br> {{$surveyor->ApplicationDate}}</td>
                    <td>{{$surveyor->Name}} </td>
                    <td>{{$surveyor->ARNo}} </td>
                    <td>{{$surveyor->ServiceType}}</td>
                    <td>{{$surveyor->CurrentStatus}}</td>
              
                    <td>{{$surveyor->VerifiedBy}} <br> {{$surveyor->RegistrationVerifiedDate}}</td>
                    <td>{{$surveyor->AprroverBy}} <br> {{$surveyor->RegistrationApprovedDate}}</td>
                    <td>{{$surveyor->PaymentApprover}} <br> {{$surveyor->RegistrationPaymentApprovedDate}}</td>
                    <td>{{$surveyor->RejectedBy}} <br> {{$surveyor->RejectedDate}}</td>
                    <td>{{$surveyor->RemarksByRejector}}</td>
                    <td>{{$surveyor->CurrentlyPickedBy}}</td>
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
