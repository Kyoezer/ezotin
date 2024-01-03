<html>
    <body>
        <table>
            <thead>
            <tr>
                <th colspan="13">Architect Application Details</th>
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
            @forelse($architectLists as $architect)
            <tr>
        
                      <td>{{$count}}</td>
                    <td>{{$architect->ReferenceNo}} <br> {{$architect->ApplicationDate}}</td>
                    <td>{{$architect->Name}} </td>
                    <td>{{$architect->ARNo}} </td>
                    <td>{{$architect->ServiceType}}</td>
                    <td>{{$architect->CurrentStatus}}</td>
              
                    <td>{{$architect->VerifiedBy}}  <br> {{$architect->RegistrationVerifiedDate}}</td>
                    <td>{{$architect->AprroverBy}}  <br> {{$architect->RegistrationApprovedDate}}</td>
                    <td>{{$architect->PaymentApprover}}  <br> {{$architect->RegistrationPaymentApprovedDate}}</td>
                    <td>{{$architect->RejectedBy}}  <br> {{$architect->RejectedDate}}</td>
                    <td>{{$architect->RemarksByRejector}}</td>
                    <td>{{$architect->CurrentlyPickedBy}}</td>
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
