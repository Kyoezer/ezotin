@extends('master')
@section('pagescripts')
{{ HTML::script('assets/global/scripts/specializedtrade.js') }}
@stop
@section('content')
<div id="sendback" class="modal fade" role="dialog" aria-labelledby="sendback" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			{{Form::open(array('url'=>'sendapplicationback','role'=>'form'))}}
			<input type="hidden" name="Id" value="{{$specializedTradeId}}" />
			<input type="hidden" name="Model" value="SpecializedTradeModel"/>
			<input type="hidden" name="Status" value="{{CONST_CMN_APPLICATIONREGISTRATIONSTATUS_VERIFIED}}"/>
			<input type="hidden" name="RedirectUrl" value="specializedtrade/approveserviceapplicationfeepaymentlist"/>
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
				<button id="approvespecializedtradepaymentregistration" type="button" class="btn green">Approve</button>
				<button class="btn red" data-dismiss="modal" aria-hidden="true">Cancel</button>
			</div>
		</div>
	</div>
</div>
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-gift"></i>
			<span class="caption-subject">Approve Payment</span>
		</div>
	</div>
	<div class="portlet-body form">
		<div class="form-body">
			<div class="note note-danger">
	          	<p>If any detail(s) submitted by the applicant is different from the CDB record then the field will be marked in red. To view the current record with CDB, please hover over <i class="font-blue">[old]</i> next to the field.</p>
	        </div>
	        <div class="row">
	        	<div class="col-md-6">
					<h5 class="font-blue-madison bold">Fee Structure</h5>
					<table class="table table-bordered table-striped table-condensed flip-content">
						<thead>
							<tr>
								<th>Application Type</th>
								<th>Remarks</th>
								<th class="text-right">Amount</th>
							</tr>
						</thead>
						<tbody>
							<?php $totalFeesApplicable=0;?>
							@foreach($appliedServices as $appliedService)
							<tr>
								<td>
									{{$appliedService->ServiceName}}
								</td>
								<td class="text-center">
									@if($appliedService->Id==CONST_SERVICETYPE_LATEFEE)
										<table class="table table-hover table-condensed">
											<thead>
												<tr class="danger">
													<th>No. of Days Late (Actual)</th>
													<th>No. of Days Late After Grace Period</th>
													<th>Penalty per Day (Nu.)</th>
													<th>Total Amount (Nu.)</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td>
														{{$hasLateFeeAmount[0]->PenaltyNoOfDays-1}}<br />
														<small><i class="font-red">* 30 days is grace period.</i></small><br />
														<small><i class="font-red">Registration Expired on {{convertDateToClientFormat($hasLateFeeAmount[0]->RegistrationExpiryDate)}}</i></small><br />
														<small><i class="font-red">* Application for Renewal on {{convertDateToClientFormat($hasLateFeeAmount[0]->ApplicationDate)}}</i></small>
													</td>
													<td>
														@if(($hasLateFeeAmount[0]->PenaltyNoOfDays-1)>30)
															<?php $lateFeeAfterGracePeriod=$hasLateFeeAmount[0]->PenaltyNoOfDays-30-1; ?>
														@else
															<?php $lateFeeAfterGracePeriod = 0; ?>
														@endif
														@if($lateFeeAfterGracePeriod>0)
														{{$lateFeeAfterGracePeriod}}
														@endif
													</td>
													<td>
														{{$hasLateFeeAmount[0]->PenaltyLateFeeAmount}}
													</td>
													<td>
														<?php $lateFeeAmount=(int)$lateFeeAfterGracePeriod*(int)$hasLateFeeAmount[0]->PenaltyLateFeeAmount; ?>
														@if($lateFeeAmount > 3000)
															<?php $lateFeeAmount = 3000; ?>
														@endif
														{{number_format($lateFeeAmount,2)}}
													</td>
												</tr>
												<tr class="bold">
													<td colspan="3">Total</td>
													<td>
														<?php $appliedService->EngineerPvtAmount=$lateFeeAmount;?>
														{{number_format($lateFeeAmount,2)}}
													</td>
												</tr>
											</tbody>
										</table>
									@endif
								</td>
								<td class="text-right">
									<?php $totalFeesApplicable+=$appliedService->ServiceFee; ?>
									{{number_format($appliedService->ServiceFee,2)}}
								</td>
							</tr>
							@endforeach
							<tr class="bold">
								<td class="text-right" colspan="2">Total</td>
								<td class="text-right">{{number_format($totalFeesApplicable,2)}}</td>
							</tr>
						</tbody>
					</table>
				</div>
	        </div>
			<div class="row">
			@foreach($specializedTradeInformations as $specializedTradeInformation)
				<div class="col-md-6">
					<h5 class="font-blue-madison bold">Registration Details</h5>
					<table class="table table-bordered table-striped table-condensed">
						<tbody>
							<tr>
								<td><strong>SP No.</strong></td>
								<td>
									{{$specializedTradeInformationsFinal[0]->SPNo}}
								</td>
							</tr>
							<tr>
								<td><strong>Name</strong></td>
								<td>
									@if($specializedTradeInformation->Salutation==$specializedTradeInformationsFinal[0]->Salutation && $specializedTradeInformation->Name==$specializedTradeInformationsFinal[0]->Name)
										{{$specializedTradeInformation->Salutation.' '.$specializedTradeInformation->Name}}
									@else
										<span class="font-red">{{$specializedTradeInformation->Salutation.' '.$specializedTradeInformation->Name}}</span>
										<a href="#" class="tooltips" data-placement="top" data-original-title="{{$specializedTradeInformationsFinal[0]->Salutation.' '.$specializedTradeInformationsFinal[0]->Name}}"><i class="font-blue">[old]</i></a>
									@endif
								</td>
							</tr>
							<tr>
								<td><strong>CID No.</strong></td>
								<td>
									@if($specializedTradeInformation->CIDNo==$specializedTradeInformationsFinal[0]->CIDNo)
										{{$specializedTradeInformation->CIDNo}}
									@else
										<span class="font-red">{{$specializedTradeInformation->CIDNo}}</span>
										<a href="#" class="tooltips" data-placement="top" data-original-title="{{$specializedTradeInformationsFinal[0]->CIDNo}}"><i class="font-blue">[old]</i></a>
									@endif
								</td>
							</tr>
							<tr>
								<td><strong>Dzongkhag</strong></td>
								<td>
									@if($specializedTradeInformation->Dzongkhag==$specializedTradeInformationsFinal[0]->Dzongkhag)
										{{$specializedTradeInformation->Dzongkhag}}
									@else
										<span class="font-red">{{$specializedTradeInformation->Dzongkhag}}</span>
										<a href="#" class="tooltips" data-placement="top" data-original-title="{{$specializedTradeInformationsFinal[0]->Dzongkhag}}"><i class="font-blue">[old]</i></a>
									@endif
								</td>
							</tr>
							<tr>
								<td><strong>Gewog</strong></td>
								<td>
									@if($specializedTradeInformation->Gewog==$specializedTradeInformationsFinal[0]->Gewog)
										{{$specializedTradeInformation->Gewog}}
									@else
										<span class="font-red">{{$specializedTradeInformation->Gewog}}</span>
										<a href="#" class="tooltips" data-placement="top" data-original-title="{{$specializedTradeInformationsFinal[0]->Gewog}}"><i class="font-blue">[old]</i></a>
									@endif
								</td>
							</tr>
							<tr>
								<td><strong>Village</strong></td>
								<td>
									@if($specializedTradeInformation->Village==$specializedTradeInformationsFinal[0]->Village)
										{{$specializedTradeInformation->Village}}
									@else
										<span class="font-red">{{$specializedTradeInformation->Village}}</span>
										<a href="#" class="tooltips" data-placement="top" data-original-title="{{$specializedTradeInformationsFinal[0]->Village}}"><i class="font-blue">[old]</i></a>
									@endif
								</td>
							</tr>
							<tr>
								<td><strong>Email</strong></td>
								<td>
									@if($specializedTradeInformation->Email==$specializedTradeInformationsFinal[0]->Email)
										{{$specializedTradeInformation->Email}}
									@else
										<span class="font-red">{{$specializedTradeInformation->Email}}</span>
										<a href="#" class="tooltips" data-placement="top" data-original-title="{{$specializedTradeInformationsFinal[0]->Email}}"><i class="font-blue">[old]</i></a>
									@endif
								</td>
							</tr>
							<tr>
								<td><strong>Mobile No.</strong></td>
								<td>
									@if($specializedTradeInformation->MobileNo==$specializedTradeInformationsFinal[0]->MobileNo)
										{{$specializedTradeInformation->MobileNo}}
									@else
										<span class="font-red">{{$specializedTradeInformation->MobileNo}}</span>
										<a href="#" class="tooltips" data-placement="top" data-original-title="{{$specializedTradeInformationsFinal[0]->MobileNo}}"><i class="font-blue">[old]</i></a>
									@endif
								</td>
							</tr>
							<tr>
								<td><strong>Employer Name</strong></td>
								<td>
									@if($specializedTradeInformation->EmployerName==$specializedTradeInformationsFinal[0]->EmployerName)
										{{$specializedTradeInformation->EmployerName}}
									@else
										<span class="font-red">{{$specializedTradeInformation->EmployerName}}</span>
										<a href="#" class="tooltips" data-placement="top" data-original-title="{{$specializedTradeInformationsFinal[0]->EmployerName}}"><i class="font-blue">[old]</i></a>
									@endif
								</td>
							</tr>
							<tr>
								<td><strong>Employer Address</strong></td>
								<td>
									@if($specializedTradeInformation->EmployerAddress==$specializedTradeInformationsFinal[0]->EmployerAddress)
										{{$specializedTradeInformation->EmployerAddress}}
									@else
										<span class="font-red">{{$specializedTradeInformation->EmployerAddress}}</span>
										<a href="#" class="tooltips" data-placement="top" data-original-title="{{$specializedTradeInformationsFinal[0]->EmployerAddress}}"><i class="font-blue">[old]</i></a>
									@endif
								</td>
							</tr>
							<tr>
								<td><strong>Previous Validity</strong></td>
								<td>
									{{convertDateToClientFormat($specializedTradeInformationsFinal[0]->RegistrationApprovedDate)}} to {{convertDateToClientFormat($specializedTradeInformationsFinal[0]->RegistrationExpiryDate)}}
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				@endforeach
				<div class="col-md-6">
					<h5 class="font-blue-madison bold">Category</h5>
					<table class="table table-bordered table-striped table-condensed">
						<thead>
							<tr>
								<th>Category</th>
								<th width="5%" class="table-checkbox">Current</th>
								<th width="5%" class="table-checkbox">Applied</th>
								<th width="5%" class="table-checkbox">Verified</th>
								<th width="5%" class="table-checkbox">Approved</th>
							</tr>
						</thead>
						<tbody>
							@foreach($workClasssifications as $workClasssification)
								<tr>
									<td>
										{{$workClasssification->Code.' ('.$workClasssification->Name.')'}}
									</td>
									<td class="text-center">
										@if((bool)$workClasssification->CmnApprovedCategoryId!=NULL)
										<i class="fa fa-check font-green-seagreen"></i>
										@else
										<i class="fa fa-times font-red"></i>
										@endif
									</td>
									<td class="text-center">
										@if((bool)$workClasssification->CmnAppliedCategoryId!=NULL)
										<i class="fa fa-check font-green-seagreen"></i>
										@else
										<i class="fa fa-times font-red"></i>
										@endif
									</td>
									<td class="text-center">
										@if((bool)$workClasssification->CmnVerifiedCategoryId!=NULL)
										<i class="fa fa-check font-green-seagreen"></i>
										@else
										<i class="fa fa-times font-red"></i>
										@endif
									</td>
									<td class="text-center">
										@if((bool)$workClasssification->ApplicationApprovedCategoryId!=NULL)
										<i class="fa fa-check font-green-seagreen"></i>
										@else
										<i class="fa fa-times font-red"></i>
										@endif
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<h5 class="font-blue-madison bold">Existing Attachments</h5>
					<table class="table table-bordered table-striped table-condensed">
						<thead>
							<tr>
								<th>Document Name</th>
							</tr>
						</thead>
						<tbody>
							@foreach($specializedTradeAttachmentsFinal as $specializedTradeAttachmentFinal)
							<tr>
								<td>
									<a href="{{URL::to($specializedTradeAttachmentFinal->DocumentPath)}}" target="_blank">{{$specializedTradeAttachmentFinal->DocumentName}}</a>
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>
				<div class="col-md-6">
					<h5 class="font-blue-madison bold">New Attachments</h5>
					<table class="table table-bordered table-striped table-condensed">
						<thead>
							<tr>
								<th>Document Name</th>
							</tr>
						</thead>
						<tbody>
							@forelse($specializedTradeAttachments as $specializedTradeAttachment)
							<tr>
								<td>
									<a href="{{URL::to($specializedTradeAttachment->DocumentPath)}}" target="_blank">{{$specializedTradeAttachment->DocumentName}}</a>
								</td>
							</tr>
							@empty
							<tr>
								<td class="font-red text-center">No New Attachments</td>
							</tr>
							@endforelse
						</tbody>
					</table>
				</div>
			</div>
			<div class="row">
                <div class="col-md-12">
                   <a href="{{URL::to('specializedtrade/editregistrationinfo/'.$specializedTradeId.'?redirectUrl=specializedtrade/approveserviceapplicationpaymentprocess')}}" class="btn blue-madison editaction"><i class="fa fa-edit"></i> Edit</a>
                </div>
            </div>
		</div>
		@include('crps.applicationhistory')
	</div>
</div>
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-gift"></i>
			<span class="caption-subject">Payment Details</span>
		</div>
	</div>
	<div class="portlet-body form">
		<div class="form-body">
			{{ Form::open(array('url' => $modelPost,'role'=>'form','id'=>'registrationpaymentdonespecializedtrade'))}}
			<input type="hidden" name="PaymentApprovedDate" value="{{date('Y-m-d G:i:s')}}" />
			<input type="hidden" name="SysLockedByUserId" value="" />
			<input type="hidden" name="SpecializedTradeReference" value="{{$specializedTradeId}}">
			<input type="hidden" name="SysPaymentApproverUserId" value="{{Auth::user()->Id}}">
			<input type="hidden" name="CmnApplicationRegistrationStatusId" value="{{CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED}}" />
			<div class="row">
				<div class="col-md-3">
					<div class="form-group">
						<label class="control-label">Receipt Date</label>
						<div class="input-icon right">
							<i class="fa fa-calendar"></i>
							<input type="text" name="PaymentReceiptDate" class="form-control datepicker required" placeholder="" readonly="readonly"/>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label class="control-label">Receipt No.</label>
						<input type="text" name="PaymentReceiptNo" class="form-control required" class="text-right" />
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group">
						<label>Remarks</label>
						<textarea name="RemarksByPaymentApprover" class="form-control" rows="3"></textarea>
					</div>
				</div>
			</div>
			<div class="form-actions">
				<div class="btn-set">
					<a href="#paymentdoneregistration" data-toggle="modal" class="btn green">Approve & Update Certificate</a>
					@if($isAdmin)
						<a href="#sendback" data-toggle="modal" class="btn purple">Send back to Approver</a>
					@endif
				</div>
			</div>
			{{Form::close()}}
		</div>
	</div>
</div>
@stop