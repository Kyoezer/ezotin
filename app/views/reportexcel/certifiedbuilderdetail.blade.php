<html>
    <body>
        <table>
            <thead>
            <tr>
                <th colspan="13">Certified Builder Application Details</th>
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
						Name of Firm
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
            @forelse($certifiedbuilderLists as $specializedfirm)
                       <tr>
        
                    <td>{{$count}}</td>
                    <td>{{$specializedfirm->ReferenceNo}} <br> {{$specializedfirm->ApplicationDate}}</td>
                    <td>{{$specializedfirm->NameOfFirm}} </td>
                    <td>{{$specializedfirm->CDBNo}} </td>
                    <td>{{$specializedfirm->ServiceType}}</td>
                    <td>{{$specializedfirm->CurrentStatus}}</td>
              
                    <td>{{$specializedfirm->VerifiedBy}} <br> {{$specializedfirm->RegistrationVerifiedDate}}</td>
                    <td>{{$specializedfirm->AprroverBy}} <br> {{$specializedfirm->RegistrationApprovedDate}}</td>
                    <td>{{$specializedfirm->PaymentApprover}} <br> {{$specializedfirm->RegistrationPaymentApprovedDate}}</td>
                    <td>{{$specializedfirm->RejectedBy}} <br> {{$specializedfirm->RejectedDate}}</td>
                    <td>{{$specializedfirm->RemarksByRejector}}</td>
                    <td>{{$specializedfirm->CurrentlyPickedBy}}</td>
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
