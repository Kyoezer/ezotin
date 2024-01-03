<html>
    <body>
        <table>
            <thead>
            <tr>
                <th colspan="13">Ezotin Application Details</th>
            </tr>
                @foreach($parametersForPrint as $label=>$value)
                    <tr>
                        <th>
                            <i>{{$label}}:</i> &nbsp; {{$value}}
                        </th>
                    </tr>
                @endforeach
            <tr><th colspan="13"></th></tr>
            <tr>
                <th>Sl #</th>
                <th>Applicant Type</th>
                <th>Application #</th>
                <th>
                    Firm/Name
                </th>
                <th>Mobile No.</th>
                <th>Application Date</th>
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
            <?php $slNo = 1; ?>
            @if(!empty($reportDataContractor))
                @foreach($reportDataContractor as $data)
                    <tr>
                        <td>{{$slNo}}</td>
                        <td>{{$data->ApplicantType}}</td>
                        <td>{{$data->ReferenceNo}}</td>
                        <td>{{$data->NameOfFirm}} ({{$data->CDBNo}})</td>
                        <td>{{$data->MobileNo}}</td>
                        <td>{{convertDateToClientFormat($data->ApplicationDate)}}</td>
                        <td>{{$data->Type}}</td>
                        <td>{{$data->Status}}</td>
                        <td>@if($data->Verifier){{$data->Verifier."<br/> on ".convertDateToClientFormat($data->RegistrationVerifiedDate)}}@endif</td>
                        <td>@if($data->Approver){{$data->Approver."<br/> on ".convertDateToClientFormat($data->RegistrationApprovedDate)}}@endif</td>
                        <td>@if($data->PaymentApprover){{$data->PaymentApprover."<br/> on ".convertDateToClientFormat($data->PaymentApprovedDate)}}@endif</td>
                        <td>@if($data->Rejector){{$data->Rejector."<br/> on ".convertDateToClientFormat($data->RejectedDate)}}@endif</td>
                        <td>{{$data->RemarksByRejector}}</td>
                        <td>{{$data->PickedByUser}}</td>
                    </tr>
                    <?php $slNo++; ?>
                @endforeach
            @endif
            @if(!empty($reportDataConsultant))
                @foreach($reportDataConsultant as $data)
                    <tr>
                        <td>{{$slNo}}</td>
                        <td>{{$data->ApplicantType}}</td>
                        <td>{{$data->ReferenceNo}}</td>
                        <td>{{$data->NameOfFirm}} ({{$data->CDBNo}})</td>
                        <td>{{$data->MobileNo}}</td>
                        <td>{{convertDateToClientFormat($data->ApplicationDate)}}</td>
                        <td>{{$data->Type}}</td>
                        <td>{{$data->Status}}</td>
                        <td>@if($data->Verifier){{$data->Verifier."<br/> on ".convertDateToClientFormat($data->RegistrationVerifiedDate)}}@endif</td>
                        <td>@if($data->Approver){{$data->Approver."<br/> on ".convertDateToClientFormat($data->RegistrationApprovedDate)}}@endif</td>
                        <td>@if($data->PaymentApprover){{$data->PaymentApprover."<br/> on ".convertDateToClientFormat($data->PaymentApprovedDate)}}@endif</td>
                        <td>@if($data->Rejector){{$data->Rejector."<br/> on ".convertDateToClientFormat($data->RejectedDate)}}@endif</td>
                        <td>{{$data->RemarksByRejector}}</td>
                        <td>{{$data->PickedByUser}}</td>
                    </tr>
                    <?php $slNo++; ?>
                @endforeach
            @endif
            @if(!empty($reportDataArchitect))
                @foreach($reportDataArchitect as $data)
                    <tr>
                        <td>{{$slNo}}</td>
                        <td>{{$data->ApplicantType}}</td>
                        <td>{{$data->ReferenceNo}}</td>
                        <td>{{$data->NameOfFirm}} ({{$data->CDBNo}})</td>
                        <td>{{$data->MobileNo}}</td>
                        <td>{{convertDateToClientFormat($data->ApplicationDate)}}</td>
                        <td>{{$data->Type}}</td>
                        <td>{{$data->Status}}</td>
                        <td>@if($data->Verifier){{$data->Verifier."<br/> on ".convertDateToClientFormat($data->RegistrationVerifiedDate)}}@endif</td>
                        <td>@if($data->Approver){{$data->Approver."<br/> on ".convertDateToClientFormat($data->RegistrationApprovedDate)}}@endif</td>
                        <td>@if($data->PaymentApprover){{$data->PaymentApprover."<br/> on ".convertDateToClientFormat($data->PaymentApprovedDate)}}@endif</td>
                        <td>@if($data->Rejector){{$data->Rejector."<br/> on ".convertDateToClientFormat($data->RejectedDate)}}@endif</td>
                        <td>{{$data->RemarksByRejector}}</td>
                        <td>{{$data->PickedByUser}}</td>
                    </tr>
                    <?php $slNo++; ?>
                @endforeach
            @endif
            @if(!empty($reportDataSP))
                @foreach($reportDataSP as $data)
                    <tr>
                        <td>{{$slNo}}</td>
                        <td>{{$data->ApplicantType}}</td>
                        <td>{{$data->ReferenceNo}}</td>
                        <td>{{$data->NameOfFirm}} ({{$data->CDBNo}})</td>
                        <td>{{$data->MobileNo}}</td>
                        <td>{{convertDateToClientFormat($data->ApplicationDate)}}</td>
                        <td>{{$data->Type}}</td>
                        <td>{{$data->Status}}</td>
                        <td>@if($data->Verifier){{$data->Verifier."<br/> on ".convertDateToClientFormat($data->RegistrationVerifiedDate)}}@endif</td>
                        <td>@if($data->Approver){{$data->Approver."<br/> on ".convertDateToClientFormat($data->RegistrationApprovedDate)}}@endif</td>
                        <td>@if($data->PaymentApprover){{$data->PaymentApprover."<br/> on ".convertDateToClientFormat($data->PaymentApprovedDate)}}@endif</td>
                        <td>@if($data->Rejector){{$data->Rejector."<br/> on ".convertDateToClientFormat($data->RejectedDate)}}@endif</td>
                        <td>{{$data->RemarksByRejector}}</td>
                        <td>{{$data->PickedByUser}}</td>
                    </tr>
                    <?php $slNo++; ?>
                @endforeach
            @endif
            @if(!empty($reportDataEngineer))
                @foreach($reportDataEngineer as $data)
                    <tr>
                        <td>{{$slNo}}</td>
                        <td>{{$data->ApplicantType}}</td>
                        <td>{{$data->ReferenceNo}}</td>
                        <td>{{$data->NameOfFirm}} ({{$data->CDBNo}})</td>
                        <td>{{$data->MobileNo}}</td>
                        <td>{{convertDateToClientFormat($data->ApplicationDate)}}</td>
                        <td>{{$data->Type}}</td>
                        <td>{{$data->Status}}</td>
                        <td>@if($data->Verifier){{$data->Verifier."<br/> on ".convertDateToClientFormat($data->RegistrationVerifiedDate)}}@endif</td>
                        <td>@if($data->Approver){{$data->Approver."<br/> on ".convertDateToClientFormat($data->RegistrationApprovedDate)}}@endif</td>
                        <td>@if($data->PaymentApprover){{$data->PaymentApprover."<br/> on ".convertDateToClientFormat($data->PaymentApprovedDate)}}@endif</td>
                        <td>@if($data->Rejector){{$data->Rejector."<br/> on ".convertDateToClientFormat($data->RejectedDate)}}@endif</td>
                        <td>{{$data->RemarksByRejector}}</td>
                        <td>{{$data->PickedByUser}}</td>
                    </tr>
                    <?php $slNo++; ?>
                @endforeach
            @endif
            </tbody>
        </table>
    </body>
</html>
