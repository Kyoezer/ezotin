@if((int)$userApprovePrivilege>0)
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <a class="dashboard-stat dashboard-stat-light blue-soft" href="#">
            <div class="visual">
                <i class="fa fa-briefcase"></i>
            </div>
            <div class="details">
                <div class="number">
                    <p class="text-center" style="font-size: 16pt; padding-top: 5px;"><strong>&nbsp;&nbsp;&nbsp;&nbsp;Approve Users</strong></p>
                    <div class="col-md-6 col-sm-6 col-lg-6 col-xs-6">
                    </div>
                    <div class="col-md-6 col-sm-6 col-lg-6 col-xs-6">
                        No. of pending applications: {{$userApproveCount}}
                    </div>
                </div>
            </div>
        </a>
    </div>
@endif
@if(count($applicantVerifyPrivilegeCount)>0)
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <a class="dashboard-stat dashboard-stat-light red-soft" href="#">
            <div class="visual">
                <i class="fa fa-calendar-o"></i>
            </div>
            <div class="details">
                <div class="number">
                    <p class="text-center" style="font-size: 16pt; padding-top: 5px;"><strong>&nbsp;&nbsp;&nbsp;&nbsp;New Verification</strong></p>
                    @if(count($applicantVerifyRegistration)>0)
                        <div class="col-md-6 col-sm-6 col-lg-6 col-xs-6">
                            @foreach($applicantVerifyRegistration as $verifyRegistration)
                                {{$verifyRegistration->Type}}: {{$verifyRegistration->ApplicationCount}} <br>
                            @endforeach
                        </div>
                    @endif

                    @if(count($applicantVerifyService)>0)
                        <div class="col-md-6 col-sm-6 col-lg-6 col-xs-6">
                            @foreach($applicantVerifyService as $verifyService)
                                &nbsp;{{$verifyService->Type}}: {{$verifyService->ApplicationCount}} <br>
                            @endforeach
                        </div>
                    @endif


                </div>
            </div>
        </a>
    </div>
@endif
@if(count($applicantApprovePrivilegeCount)>0)
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <a class="dashboard-stat dashboard-stat-light green-soft" href="#">
            <div class="visual">
                <i class="fa fa-bar-chart-o"></i>
            </div>
            <div class="details">
                <div class="number">
                    <p class="text-center" style="font-size: 16pt; padding-top: 5px;"><strong>&nbsp;&nbsp;&nbsp;&nbsp;New Approval</strong></p>
                    @if(count($applicantApproveRegistration)>0)
                        <div class="col-md-6 col-sm-6 col-lg-6 col-xs-6">
                            @foreach($applicantApproveRegistration as $approveRegistration)
                                {{$approveRegistration->Type}}: {{$approveRegistration->ApplicationCount}} <br>
                            @endforeach
                        </div>
                    @endif

                    @if(count($applicantApproveService)>0)
                        <div class="col-md-6 col-sm-6 col-lg-6 col-xs-6">
                            @foreach($applicantApproveService as $approveService)
                                &nbsp;{{$approveService->Type}}: {{$approveService->ApplicationCount}} <br>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </a>
    </div>
@endif
@if(count($applicantApprovePaymentPrivilegeCount)>0)
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <a class="dashboard-stat dashboard-stat-light purple-soft" href="#">
            <div class="visual">
                <i class="fa fa-envelope-o"></i>
            </div>
            <div class="details">
                <div class="number">
                    <p class="text-center" style="font-size: 16pt; padding-top: 5px;"><strong>&nbsp;&nbsp;&nbsp;&nbsp;New Payment Approval</strong></p>
                    @if(count($applicantApprovePaymentRegistration)>0)
                        <div class="col-md-6 col-sm-6 col-lg-6 col-xs-6">
                            @foreach($applicantApprovePaymentRegistration as $approvePaymentRegistration)
                                {{$approvePaymentRegistration->Type}}: {{$approvePaymentRegistration->ApplicationCount}} <br>
                            @endforeach
                        </div>
                    @endif

                    @if(count($applicantApprovePaymentService)>0)
                        <div class="col-md-6 col-sm-6 col-lg-6 col-xs-6">
                            @foreach($applicantApprovePaymentService as $approvePaymentService)
                                &nbsp;{{$approvePaymentService->Type}}: {{$approvePaymentService->ApplicationCount}} <br>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </a>
    </div>
@endif