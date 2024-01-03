@extends('master')
@section('pagescripts')
{{ HTML::script('assets/global/scripts/architect.js') }}
@stop
@section('lastcdbno')
<div class="page-toolbar">
	<div id="dashboard-report-range" class="pull-right tooltips btn btn-fit-height grey-cascade" data-placement="top" data-original-title="Last used Architect No.">
		<span class="thin visible-lg-inline-block">Last used Ar No.: <span class="bold">
		<?php
			$prepend = "";
			$lastNo = lastUsedArchitectNo($architectInformations[0]->Country,$architectServiceSectorType);
			$newNo = (int)(trim($lastNo));
			if($architectInformations[0]->Country != "Bhutan"){
				$prepend = "NB-";
			}else{
				$prepend = 'BA-';
			}
			if($architectServiceSectorType == CONST_CMN_SERVICESECTOR_GOVT){
                $newNo = "$prepend$newNo (G)";
			}else{
                $newNo = "$prepend$newNo (P)";
			}

		?>
				{{lastUsedArchitectNo($architectInformations[0]->Country,$architectServiceSectorType)}}</span></span>
	</div>
</div>
@stop
@section('content')
<div id="sendback" class="modal fade" role="dialog" aria-labelledby="sendback" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			{{Form::open(array('url'=>'sendapplicationback','role'=>'form'))}}
			<input type="hidden" name="Id" value="{{$architectId}}" />
			<input type="hidden" name="Model" value="ArchitectModel"/>
			<input type="hidden" name="Status" value="{{CONST_CMN_APPLICATIONREGISTRATIONSTATUS_VERIFIED}}"/>
			<input type="hidden" name="RedirectUrl" value="architect/approvefeepayment"/>
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h3 class="modal-title font-green-seagreen">Send Application back</h3>
			</div>
			<div class="modal-body">
				<p class="bold">Are you sure you want to send this application back?</p>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn green">Send Back</button>
				<button class="btn red" data-dismiss="modal" aria-hidden="true">Cancel</button>
			</div>
			{{Form::close()}}
		</div>
	</div>
</div>
<div class="portlet light bordered" id="">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-gift"></i>
			<span class="caption-subject">Registration Information</span>
		</div>
		@if((int)$userArchitect==1)
		<div class="actions">
			<a href="{{URL::to('architect/viewprintdetails/'.$architectId)}}" class="btn btn-small blue-madison" target="_blank"><i class="fa fa-print"></i> Print Information</a>
			<a href="{{URL::to('architect/certificate/'.$architectId)}}" class="btn btn-small green-seagreen" target="_blank"><i class="fa fa-print"></i> Print Certificate</a>
		</div>
		@endif
	</div>

	<div class="row form-froup">
                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                    <img src='https://www.citizenservices.gov.bt/BtImgWS/ImageServlet?type=PH&cidNo=${App_Details.cidNo}'  width='200px'  height='200px' class='pull-right'/>
                </div>
                <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                    <div class="table-responsive">
					@foreach($architectInformations as $architectInformation)
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td colspan="2" class="font-blue-madison bold warning">Personal Information</td>
                                </tr>
                                <tr>
                                    <td>
                                        <table class="table table-condensed">
                                            <tr>
                                                <td><strong>Full Name: </strong></td>
                                                <td>{{$architectInformation->Salutation.' '.$architectInformation->Name}}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>CID Number: </strong></td>
                                                <td>{{$architectInformation->CIDNo}}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Contact Number: </strong></td>
                                                <td>{{$architectInformation->MobileNo}}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Email Address: </strong></td>
                                                <td>{{$architectInformation->Email}}</td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td>
                                        <table class="table table-condensed">
                                            <tr>
                                                <td><strong>Village: </strong></td>
                                                <td>{{$architectInformation->Village}}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Gewog: </strong></td>
                                                <td>{{$architectInformation->Gewog}}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Dzongkhag: </strong></td>
                                                <td>{{$architectInformation->Dzongkhag}}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Country: </strong></td>
                                                <td>{{$architectInformation->Country}}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <hr />
                        <div class="col-md-6 table-responsive">
                            <table class="table">
                                <tbody>
                                <td colspan="1" class="font-blue-madison bold warning">Qualification Information</td>
                                <tr>
                                    <td>
                                        <table class="table table-condensed">
                                            <tr>
                                                <td><strong>Qualification: </strong></td>
                                                <td>{{$architectInformation->Qualification}}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Year of Graduation: </strong></td>
                                                <td>{{$architectInformation->GraduationYear}}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Country of Study: </strong></td>
                                                <td>{{$architectInformation->UniversityCountry}}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>University Name: </strong></td>
                                                <td>{{$architectInformation->NameOfUniversity}}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6 table-responsive">
                            <table class="table">
                                <tbody>
                                <td colspan="1" class="font-blue-madison bold warning">CDB Certificate Information</td>
                                <tr>
                                    <td colspan="1">
                                        <table class="table table-condensed">
                                            <tr>
                                                <td><strong>AR Number: </strong></td>
                                                <td>{{$architectInformation->ARNo}}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Type: </strong></td>
                                                <td>{{$architectInformation->ArchitectType}}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Trade: </strong></td>
                                                <td>{{$architectInformation->Trade}}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Registration Expiry Date</strong></td>
												<td>
                                                @if($architectInformation->RegistrationExpiryDate<=date('Y-m-d G:i:s'))
												<p class="font-red bold warning">	{{convertDateToClientFormat($architectInformation->RegistrationExpiryDate)}} </p>
												@else
												{{convertDateToClientFormat($architectInformation->RegistrationExpiryDate)}}
												@endif
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                </div>
				<hr />
                        <div class="col-md-6 table-responsive">
                            <table class="table">
                                <tbody>
                                <td colspan="1" class="font-blue-madison bold warning">Status Information</td>
                                <tr>
                                    <td>
                                        <table class="table table-condensed">
										@if($architectInformation->CmnApplicationRegistrationStatusId != CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED)
											<tr>
												<td><strong>Status</strong></td>
												<td class="font-yellow bold warning">{{$architectInformation->Status}}</td>
											</tr>

												@if($architectInformation->CmnApplicationRegistrationStatusId == 'b1fa6607-b1dd-11e4-89f3-080027dcfac6')
													<tr>
														<td><strong>Deregistered Date</strong></td>
														<td>{{convertDateToClientFormat($architectInformation->DeRegisteredDate)}}</td>
													</tr>
													<tr>
														<td><strong>Reason</strong></td>
														<td>{{$architectInformation->DeregisteredRemarks}}</td>
													</tr>
												@endif
										@elseif($architectInformation->RegistrationExpiryDate<=date('Y-m-d G:i:s'))
											<tr>
											<td><strong>Status</strong></td>
												<td class="font-red bold warning">Expired</td>
												
											</tr>
										@else
										<tr>
										<td><strong>Status</strong></td>
												<td>{{$architectInformation->Status}}</td>
											</tr>
										
										@endif
                                        </table>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
				@endforeach
				
			</div>

							<div class="col-md-12">
					<h5 class="font-blue-madison bold"> Curriculum Vitae/Employment History</h5>
					<table class="table table-bordered table-striped table-condensed">
						<thead>
							<tr>
								<th colspan='2' style="text-align:center">Date</th>
								<th rowspan='2'>Employer</th>
								<th rowspan='2'>Position in the firm</th>
								<th rowspan='2'>Achievements/work executed</th>
							</tr>
							<tr>
								<th style="border: 1px solid #0000000f;">From</th>
								<th style="border: 1px solid #0000000f;">To</th>
							</tr>
						</thead>
						<tbody>
							@foreach($CV as $cvItem)
							<tr>
								<td>
									{{$cvItem->ActualStartDate}}
								</td>
								<td>
									{{$cvItem->ActualEndDate}}
								</td>
								<td>
								{{$cvItem->NameOfFirm}}
								</td>
								<td>{{$cvItem->designation}}</td>
								<td>
								{{$cvItem->workStatus}}
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>

							<div class="row">
						<div class="col-md-12">
						   <a href="{{URL::to('architect/editregistrationinfo/'.$architectId.'?redirectUrl=architect/editdetails&usercdb=edit')}}" class="btn blue-madison editaction"><i class="fa fa-edit"></i> Edit</a>
						</div>
					</div>
		
		</div>
	</div>

@include('crps.applicationhistory')
@if($architectInformations[0]->CmnApplicationRegistrationStatusId==CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVEDFORPAYMENT && (int)$registrationApprovedForPayment==1)
<div id="paymentdoneregistration" class="modal fade" role="dialog" aria-labelledby="paymentdoneregistration" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h3 class="modal-title font-green-seagreen">Approve Payment</h3>
			</div>
			<div class="modal-body">
				<p class="bold">Are you sure you want to approve payment for this application?</p>
			</div>
			<div class="modal-footer">
				<button id="approvearchitectpaymentregistration" type="button" class="btn green">Approve</button>
				<button class="btn red" data-dismiss="modal" aria-hidden="true">Cancel</button>
			</div>
		</div>
	</div>
</div>
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-cogs"></i>
			<span class="caption-subject">Payment Details</span>
		</div>
	</div>
	<div class="portlet-body form">
		{{ Form::open(array('url' => 'architect/mapprovepaymentforregistration','role'=>'form','id'=>'registrationpaymentdonearchitect'))}}
		<div class="form-body">
			<input type="hidden" name="PaymentApprovedDate" value="{{date('Y-m-d G:i:s')}}" />
			<input type="hidden" name="SysLockedByUserId" value="" />
			<input type="hidden" name="ArchitectReference" value="{{$architectId}}" />
			<input type="hidden" name="CmnApplicationRegistrationStatusId" value="{{CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED}}" />
			<div class="row">
				<div class="col-md-8">
					<h5 class="font-blue-madison bold">Fee Structure</h5>
			        <table class="table table-bordered table-striped table-condensed">
						<thead>
							<tr>
								<th>Architect Type</th>
								<th>Application Type</th>
								<th width="20%" class="text-center">Validity (yrs)</th>
								<th class="text-right">Amount</th>
							</tr>
						</thead>
						<tbody>
							<?php $totalFeesApplicable=0; ?>
							@foreach($feeDetails as $feeDetail)
							<tr>
								<td>{{$feeDetail->Type}}</td>
								<td>{{$feeDetail->ServiceName}}</td>
								<td class="text-center">
									@if(empty($feeDetail->RegistrationValidity))
										-
									@else
									{{$feeDetail->RegistrationValidity}}
									@endif
								</td>
								<td class="text-right">
									@if(empty($feeDetail->NewRegistrationFee))
										-
									@else
									{{number_format($feeDetail->NewRegistrationFee,2)}}
									@endif
								</td>
								<?php $totalFeesApplicable+=$feeDetail->NewRegistrationFee; ?>
							</tr>
							@endforeach
							<tr>
								<td colspan="3" class="bold text-right">
									Total
								</td>
								<td class="text-right">
									{{number_format($totalFeesApplicable,2)}}
									<input type="hidden" name="Amount" value="{{$totalFeesApplicable}}"/>
								</td>
							</tr>
						</tbody>
					</table>
				</div>	
			</div>
			<div class="row">
				@if($architectServiceSectorType==CONST_CMN_SERVICESECTOR_PVT)
				<?php
                    $prepend = "";
                    $lastNo = lastUsedArchitectNo($architectInformations[0]->Country,$architectServiceSectorType);
                    $newNo = (int)(trim($lastNo)) + 1;
                    if($architectInformations[0]->Country != "Bhutan"){
                        $prepend = "NB-";
                    }else{
                        $prepend = 'BA-';
                    }
					$newNo = "$prepend$newNo (P)";
				?>
				<div class="col-md-2">
					<div class="form-group">
						<input type="hidden" value="checkarnoarchitect" class="cdbnocheckurl">
						<label class="control-label">AR No.</label>
						<input type="text" name="ARNo" class="form-control required checkcdbno" value="{{$newNo}}" class="text-right" />
					</div>
				</div>	
				@endif
				<div class="col-md-4">
					<div class="form-group">
						<label class="control-label">Date</label>
						<div class="input-icon right">
							<i class="fa fa-calendar"></i>
							<input type="text" name="PaymentReceiptDate" class="form-control datepicker required" readonly="readonly" />
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label class="control-label">Receipt No.</label>
						<input type="text" name="PaymentReceiptNo" class="form-control required" />
					</div>
				</div>
				<div class="col-md-12">
					<input type="hidden" name="SysPaymentApproverUserId" value="{{Auth::user()->Id}}">
					<div class="form-group">
						<label>Remarks</label>
						<textarea name="RemarksByPaymentApprover" class="form-control" rows="3"></textarea>
					</div>
				</div>
			</div>
		</div>
		<div class="form-actions">
			<div class="btn-set">
				<a href="#paymentdoneregistration" data-toggle="modal" class="btn green">Approve Payment</a>
				@if($isAdmin)
					<a href="#sendback" data-toggle="modal" class="btn purple">Send back to Approver</a>
				@endif
			</div>
		</div>
		{{Form::close()}}
	</div>
</div>
@endif

@if(isset($registrationApproved))
	@if((int)$registrationApproved == 1)
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-cogs"></i>
					<span class="caption-subject">Payment Details</span>
				</div>
			</div>
			<div class="portlet-body form">
				<div class="form-body">
					{{ Form::open(array('url' => 'architect/msavefinalremarks','role'=>'form','id'=>'registrationpaymentdonecontractor'))}}
					<input type="hidden" name="ArchitectReference" value="{{$architectId}}" />
					<div class="row">
						<div class="col-md-12">
							<h5 class="font-blue-madison bold">Fee Structure</h5>
							<table class="table table-bordered table-striped table-condensed">
								<thead>
								<tr>
									<th>Architect Type</th>
									<th>Application Type</th>
									<th width="20%" class="text-center">Validity (yrs)</th>
									<th class="text-right">Amount</th>
								</tr>
								</thead>
								<tbody>
								<?php $totalFeesApplicable=0; ?>
								@foreach($feeDetails as $feeDetail)
									<tr>
										<td>{{$feeDetail->Type}}</td>
										<td>{{$feeDetail->ServiceName}}</td>
										<td class="text-center">
											@if(empty($feeDetail->RegistrationValidity))
												-
											@else
												{{$feeDetail->RegistrationValidity}}
											@endif
										</td>
										<td class="text-right">
											@if(empty($feeDetail->NewRegistrationFee))
												-
											@else
												{{number_format($feeDetail->NewRegistrationFee,2)}}
											@endif
										</td>
										<?php $totalFeesApplicable+=$feeDetail->NewRegistrationFee; ?>
									</tr>
								@endforeach
								<tr>
									<td colspan="3" class="bold text-right">
										Total
									</td>
									<td class="text-right">
										{{number_format($totalFeesApplicable,2)}}
									</td>
								</tr>
								</tbody>
							</table>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<input type="hidden" name="SysFinalApproverUserId" value="{{Auth::user()->Id}}">
								<input type="hidden" name="SysFinalApprovedDate" value="{{date('Y-m-d G:i:s')}}">
								<strong>Payment Receipt Date: {{convertDateToClientFormat($architectInformations[0]->PaymentReceiptDate)}}</strong> <br>
								<strong>Payment Receipt No.: {{$architectInformations[0]->PaymentReceiptNo}}</strong> <br> <br>
								<label>Any Remarks</label>
								<textarea name="RemarksByFinalApprover" class="form-control" rows="3"></textarea>
							</div>
						</div>
					</div>
				</div>
				<div class="form-actions">
					<div class="btn-set">
						<button type="submit" class="btn green">Save</button>
					</div>
				</div>
				{{Form::close()}}
			</div>
		</div>
	@endif
@endif
@stop