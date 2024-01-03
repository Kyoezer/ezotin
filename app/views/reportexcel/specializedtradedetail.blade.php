<html>
    <body>
        <table>
            <thead>
            <tr>
                <th colspan="13">Specialized Trade Application Details</th>
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
						SP No.
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
            @forelse($specializedtradeLists as $specializedtrade)
                     <tr>
        
                    <td>{{$count}}</td>
                    <td>{{$specializedtrade->ReferenceNo}} <br> {{$specializedtrade->ApplicationDate}}</td>
                    <td>{{$specializedtrade->Name}} </td>
                    <td>{{$specializedtrade->SPNo}} </td>
                    <td>{{$specializedtrade->ServiceType}}</td>
                    <td>{{$specializedtrade->CurrentStatus}}</td>
              
                    <td>{{$specializedtrade->VerifiedBy}} <br> {{$specializedtrade->RegistrationVerifiedDate}}</td>
                    <td>{{$specializedtrade->AprroverBy}} <br> {{$specializedtrade->RegistrationApprovedDate}}</td>
                    <td>{{$specializedtrade->PaymentApprover}} <br> {{$specializedtrade->RegistrationPaymentApprovedDate}}</td>
                    <td>{{$specializedtrade->RejectedBy}} <br> {{$specializedtrade->RejectedDate}}</td>
                    <td>{{$specializedtrade->RemarksByRejector}}</td>
                    <td>{{$specializedtrade->CurrentlyPickedBy}}</td>
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
