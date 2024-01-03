@extends('websitemaster')
@section('main-content')
<div class="row">
	@if(!empty($applicationDetails))
    <div class="col-md-12">
        <div class="alert alert-info">
            @if($applicationDetails[0]->StatusId == CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW)
                Your application is under process, you have to wait till it gets verified or approved
            @endif
            @if($applicationDetails[0]->StatusId == CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVEDFORPAYMENT)
                Your application is approved for payment
            @endif
            @if($applicationDetails[0]->StatusId == CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED)
                Your application has been approved
            @endif
        </div>
    </div>
	<div class="col-md-6 table-responsive">
		<h4 class="text-primary">Application Details</h4>
		<table class="table table-condensed table-striped table-bordered">
			<tbody>
				<tr>
					<td><strong>Application For</strong></td>
					<td>{{$applicationDetails[0]->ApplicationType}}</td>
				</tr>
				<tr>
					<td><strong>Application No.</strong></td>
					<td>{{$applicationDetails[0]->ReferenceNo}}</td>
				</tr>
				<tr>
					<td><strong>Application Date</strong></td>
					<td>{{convertDateToClientFormat($applicationDetails[0]->ApplicationDate)}}</td>
				</tr>
				<tr>
					<td><strong>Application Status</strong></td>
					<td>{{$applicationDetails[0]->ApplicationStatus}}</td>
				</tr>
				<tr>
					<td><strong>Name of Firm</strong></td>
					<td>{{$applicationDetails[0]->NameOfFirm}}</td>
				</tr>
				<tr>
					<td><strong>Registration Approved Date</strong></td>
					<td>
						@if(!empty($applicationDetails[0]->PaymentApprovedDate))
							{{convertDateToClientFormat($applicationDetails[0]->PaymentApprovedDate)}}
						@else
						-
						@endif
					</td>
				</tr>
				<tr>
					<td><strong>Registration Expiry Date</strong></td>
					<td>
						@if(!empty($applicationDetails[0]->RegistrationExpiryDate))
						{{convertDateToClientFormat($applicationDetails[0]->RegistrationExpiryDate)}}
						@else
						-
						@endif
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	@if(isset($feeStructures) && !empty($feeStructures) || ($hasLateFee || $hasLateFeeAmount || $hasRenewal || $hasChangeInCategoryClassification || !empty($appliedServices)))
		<div class="col-md-10">
		<h4 class="text-primary">Fee Structure</h4>
		@if($applicantType == 1)
			@if((bool)$applicationType)
				@if($applicationType == 1)

					<div class="table-responsive">
					<table class="table table-condensed table-bordered" style="border-collapse: collapse; width: 70%; background-color: #fff; font-size: 10pt;" bgcolor="#fff">
						<thead>
						<tr>
							<th style="text-align: center;background-color: #d7e5f2; border: 1px solid #000;" align="center" bgcolor="#d7e5f2"></th>
							<th colspan="2" style="text-align: center;background-color: #d7e5f2; border: 1px solid #000;" align="center" bgcolor="#d7e5f2">Applied</th>
							<th colspan="2" style="text-align: center;background-color: #d7e5f2; border: 1px solid #000;" align="center" bgcolor="#d7e5f2">Verified</th>
							<th colspan="2" style="text-align: center;background-color: #d7e5f2; border: 1px solid #000;" align="center" bgcolor="#d7e5f2">Approved</th>
						</tr>
						<tr>
							<th style="background-color: #d7e5f2; padding: 0 8px 0 8px; border: 1px solid #000;" bgcolor="#d7e5f2">Catgeory</th>
							<th style="background-color: #d7e5f2; padding: 0 8px 0 8px; border: 1px solid #000;" bgcolor="#d7e5f2">Class</th>
							<th style="background-color: #d7e5f2; padding: 0 8px 0 8px; border: 1px solid #000;" bgcolor="#d7e5f2">Amount (Nu.)</th>
							<th style="background-color: #d7e5f2; padding: 0 8px 0 8px; border: 1px solid #000;" bgcolor="#d7e5f2">Class</th>
							<th style="background-color: #d7e5f2; padding: 0 8px 0 8px; border: 1px solid #000;" bgcolor="#d7e5f2">Amount (Nu.)</th>
							<th style="background-color: #d7e5f2; padding: 0 8px 0 8px; border: 1px solid #000;" bgcolor="#d7e5f2">Class</th>
							<th style="background-color: #d7e5f2; padding: 0 8px 0 8px; border: 1px solid #000;" bgcolor="#d7e5f2">Amount (Nu.)</th>
						</tr>
						</thead>
						<tbody>
						<?php $totalFeeApplied=0;$totalFeeVerified=0;$totalFeeApproved=0; ?>
						@foreach($feeStructures as $feeStructure)
							<tr style="background-color: #ccc;" bgcolor="#ccc">
								<td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">
									{{{$feeStructure->CategoryCode.' ('.$feeStructure->Category.')'}}}
								</td>
								<td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">
									{{{$feeStructure->AppliedClassification}}}
								</td>
								<td style="text-align: right;font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff" align="right">
									{{{number_format($feeStructure->AppliedRegistrationFee,2)}}}
								</td>
								<td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">
									{{{$feeStructure->VerifiedClassificationCode.' ('.$feeStructure->VerifiedClassification.')'}}}
								</td>
								<td style="text-align: right;font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff" align="right">
									{{{number_format($feeStructure->VerifiedRegistrationFee,2)}}}
								</td>
								<td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">
									{{{$feeStructure->ApprovedClassificationCode.' ('.$feeStructure->ApprovedClassification.')'}}}
								</td>
								<td style="text-align: right;font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff" align="right">
									{{{number_format($feeStructure->ApprovedRegistrationFee,2)}}}
								</td>
							</tr>
							<?php
							$totalFeeApplied+=$feeStructure->AppliedRegistrationFee;
							$totalFeeVerified+=$feeStructure->VerifiedRegistrationFee;
							$totalFeeApproved+=$feeStructure->ApprovedRegistrationFee;
							?>
						@endforeach
						<tr style="font-weight: bold;background-color: #ccc; text-align: right;" bgcolor="#ccc" align="right">
							<td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">Total</td>
							<td colspan="2" style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">{{number_format($totalFeeApplied,2)}}</td>
							<td colspan="2" style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">{{number_format($totalFeeVerified,2)}}</td>
							<td colspan="2" style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">{{number_format($totalFeeApproved,2)}}</td>
						</tr>
						</tbody>
					</table>
					</div>
					<b>Total Amount Payable: Nu. {{number_format($totalFeeApproved,2)}}</b>

				@else
						<h5>Fee Details for Applied Services</h5>
						<table class="data-large" style="border-collapse: collapse; width: 70%; background-color: #fff; font-size: 10pt;" bgcolor="#fff">
							<thead>
							<tr>
								<th style="background-color: #d7e5f2; padding: 0 8px 0 8px; border: 1px solid #000;" bgcolor="#d7e5f2">Service(s) Applied</th>
								<th style="background-color: #d7e5f2; padding: 0 8px 0 8px; border: 1px solid #000;" bgcolor="#d7e5f2">Details</th>
								<th style="background-color: #d7e5f2; padding: 0 8px 0 8px; border: 1px solid #000;" bgcolor="#d7e5f2" width="10%" class="text-right">Amount (Nu.)</th>
							</tr>
							</thead>
							<tbody>
							<?php $totalFeeApplicable=0; $categoryClassificationFeeTotal=0;?>
							@foreach($appliedServices as $appliedService)
								<tr>
									<td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff" @if($appliedService->Id!=CONST_SERVICETYPE_RENEWAL && $appliedService->Id!=CONST_SERVICETYPE_LATEFEE && $appliedService->Id!=CONST_SERVICETYPE_CHANGEOFCATEGORYCLASSIFICATION)colspan="2"@endif>
										{{{$appliedService->ServiceName}}}
									</td>
									@if($appliedService->Id==CONST_SERVICETYPE_RENEWAL || $appliedService->Id==CONST_SERVICETYPE_CHANGEOFCATEGORYCLASSIFICATION)
										<td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">
											@if($hasRenewal && $hasChangeInCategoryClassification)
												@if($appliedService->Id==CONST_SERVICETYPE_RENEWAL)
													<table class="table table-hover table-condensed">
														<thead>
														<tr class="success">
															<th style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">Category</th>
															<th style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">Existing</th>
															<th style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">Applied</th>
															<th style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">Verified</th>
															<th style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">Approved</th>
															<th style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">Amount</th>
														</tr>
														</thead>
														<tbody>
														@foreach($hasCategoryClassificationsFee as $hasCategoryClassificationFee)
															<tr>
																<td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">{{{$hasCategoryClassificationFee->MasterCategoryCode}}}</td>
																<td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">
																	@if((bool)$hasCategoryClassificationFee->FinalApprovedClassification!=NULL)
																		{{{$hasCategoryClassificationFee->FinalApprovedClassification}}}
																	@else
																		-
																	@endif
																</td>
																<td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">
																	@if((bool)$hasCategoryClassificationFee->AppliedClassification!=NULL)
																		{{{$hasCategoryClassificationFee->AppliedClassification}}}
																		@if($hasCategoryClassificationFee->AppliedClassificationPriority<$hasCategoryClassificationFee->FinalClassificationPriority)
																			<i class="font-red"><small> (Downgraded)</small></i>
																		@elseif(!empty($hasCategoryClassificationFee->FinalClassificationPriority) && $hasCategoryClassificationFee->AppliedClassificationPriority>$hasCategoryClassificationFee->FinalClassificationPriority)
																			<i class="font-red"><small> (Upgraded)</small></i>
																		@elseif(empty($hasCategoryClassificationFee->FinalClassificationPriority))
																			<i class="font-red"><small>(New)</small></i>
																		@endif
																	@else
																		-
																	@endif
																</td>
																<td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">
																	@if((bool)$hasCategoryClassificationFee->VerifiedClassification!=NULL)
																		{{{$hasCategoryClassificationFee->VerifiedClassification}}}
																		@if($hasCategoryClassificationFee->VerifiedClassificationPriority<$hasCategoryClassificationFee->FinalClassificationPriority)
																			<i class="font-red"><small> (Downgraded)</small></i>
																		@elseif(!empty($hasCategoryClassificationFee->FinalClassificationPriority) && $hasCategoryClassificationFee->VerifiedClassificationPriority>$hasCategoryClassificationFee->FinalClassificationPriority)
																			<i class="font-red"><small> (Upgraded)</small></i>
																		@elseif(empty($hasCategoryClassificationFee->FinalClassificationPriority))
																			<i class="font-red"><small>(New)</small></i>
																		@endif
																	@else
																		-
																	@endif
																</td>
																<td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">
																	@if((bool)$hasCategoryClassificationFee->ApprovedClassification!=NULL)
																		{{{$hasCategoryClassificationFee->ApprovedClassification}}}
																		@if($hasCategoryClassificationFee->ApprovedClassificationPriority<$hasCategoryClassificationFee->FinalClassificationPriority)
																			<i class="font-red"><small> (Downgraded)</small></i>
																		@elseif(!empty($hasCategoryClassificationFee->FinalClassificationPriority) && $hasCategoryClassificationFee->ApprovedClassificationPriority>$hasCategoryClassificationFee->FinalClassificationPriority)
																			<i class="font-red"><small> (Upgraded)</small></i>
																		@elseif(empty($hasCategoryClassificationFee->FinalClassificationPriority))
																			<i class="font-red"><small>(New)</small></i>
																		@endif
																	@else
																		-
																	@endif
																</td>
																<td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">
																	@if((bool)$hasCategoryClassificationFee->ApprovedClassification!=NULL)
																		@if($hasCategoryClassificationFee->ApprovedClassificationId==$hasCategoryClassificationFee->FinalApprovedClassificationId)
																			<?php $categoryClassificationFeeTotal+=$hasCategoryClassificationFee->ApprovedRenewalFee; ?>
																			{{number_format($hasCategoryClassificationFee->ApprovedRenewalFee,2)}}
																		@else
																			<?php $categoryClassificationFeeTotal+=$hasCategoryClassificationFee->ApprovedRegistrationFee; ?>
																			{{number_format($hasCategoryClassificationFee->ApprovedRegistrationFee,2)}}
																		@endif
																	@else
																		-
																	@endif
																</td>
															</tr>
														@endforeach
														<tr class="bold">
															<td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff" colspan="5">Total</td>
															<td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">
																<?php $appliedService->ContractorAmount=$categoryClassificationFeeTotal; ?>
																{{number_format($categoryClassificationFeeTotal,2)}}
															</td>
														</tr>
														</tbody>
													</table>
												@else
													<span class="font-red">*Fee details has been already displayed aganist Renewal of CDB Certificate.</span>
												@endif
											@else
												<table class="data-large" style="border-collapse: collapse; width: 70%; background-color: #fff; font-size: 10pt;" bgcolor="#fff">
													<thead>
													<tr class="success">
														<th style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">Category</th>
														<th style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">Existing</th>
														<th style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">Applied</th>
														<th style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">Verified</th>
														<th style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">Approved</th>
														<th style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">Amount</th>
													</tr>
													</thead>
													<tbody>
													@foreach($hasCategoryClassificationsFee as $hasCategoryClassificationFee)
														<tr>
															<td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">{{{$hasCategoryClassificationFee->MasterCategoryCode}}}</td>
															<td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">
																@if((bool)$hasCategoryClassificationFee->FinalApprovedClassification!=NULL)
																	{{{$hasCategoryClassificationFee->FinalApprovedClassification}}}
																@else
																	-
																@endif
															</td>
															<td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">
																@if((bool)$hasCategoryClassificationFee->AppliedClassification!=NULL)
																	{{{$hasCategoryClassificationFee->AppliedClassification}}}
																	@if($hasCategoryClassificationFee->AppliedClassificationPriority<$hasCategoryClassificationFee->FinalClassificationPriority)
																		<i class="font-red"><small> (Downgraded)</small></i>
																	@elseif(!empty($hasCategoryClassificationFee->FinalClassificationPriority) && $hasCategoryClassificationFee->AppliedClassificationPriority>$hasCategoryClassificationFee->FinalClassificationPriority)
																		<i class="font-red"><small> (Upgraded)</small></i>
																	@elseif(empty($hasCategoryClassificationFee->FinalClassificationPriority))
																		<i class="font-red"><small>(New)</small></i>
																	@endif
																@else
																	-
																@endif
															</td>
															<td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">
																@if((bool)$hasCategoryClassificationFee->VerifiedClassification!=NULL)
																	{{{$hasCategoryClassificationFee->VerifiedClassification}}}
																	@if($hasCategoryClassificationFee->VerifiedClassificationPriority<$hasCategoryClassificationFee->FinalClassificationPriority)
																		<i class="font-red"><small> (Downgraded)</small></i>
																	@elseif(!empty($hasCategoryClassificationFee->FinalClassificationPriority) && $hasCategoryClassificationFee->VerifiedClassificationPriority>$hasCategoryClassificationFee->FinalClassificationPriority)
																		<i class="font-red"><small> (Upgraded)</small></i>
																	@elseif(empty($hasCategoryClassificationFee->FinalClassificationPriority))
																		<i class="font-red"><small>(New)</small></i>
																	@endif
																@else
																	-
																@endif
															</td>
															<td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">
																@if((bool)$hasCategoryClassificationFee->ApprovedClassification!=NULL)
																	{{{$hasCategoryClassificationFee->ApprovedClassification}}}
																	@if($hasCategoryClassificationFee->ApprovedClassificationPriority<$hasCategoryClassificationFee->FinalClassificationPriority)
																		<i class="font-red"><small> (Downgraded)</small></i>
																	@elseif(!empty($hasCategoryClassificationFee->FinalClassificationPriority) && $hasCategoryClassificationFee->ApprovedClassificationPriority>$hasCategoryClassificationFee->FinalClassificationPriority)
																		<i class="font-red"><small> (Upgraded)</small></i>
																	@elseif(empty($hasCategoryClassificationFee->FinalClassificationPriority))
																		<i class="font-red"><small>(New)</small></i>
																	@endif
																@else
																	-
																@endif
															</td>
															<td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">
																@if((bool)$hasCategoryClassificationFee->ApprovedClassification!=NULL)
																	@if($hasCategoryClassificationFee->ApprovedClassificationId==$hasCategoryClassificationFee->FinalApprovedClassificationId)
																		@if($hasRenewal)
																			<?php $categoryClassificationFeeTotal+=$hasCategoryClassificationFee->ApprovedRenewalFee; ?>
																			{{number_format($hasCategoryClassificationFee->ApprovedRenewalFee,2)}}
																		@else
																			-
																		@endif
																	@else
																		<?php $categoryClassificationFeeTotal+=$hasCategoryClassificationFee->ApprovedRegistrationFee; ?>
																		{{number_format($hasCategoryClassificationFee->ApprovedRegistrationFee,2)}}
																	@endif
																@else
																	-
																@endif
															</td>
														</tr>
													@endforeach
													<tr class="bold">
														<td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff" colspan="5">Total</td>
														<td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">
															<?php $appliedService->ContractorAmount=$categoryClassificationFeeTotal; ?>
															{{number_format($categoryClassificationFeeTotal,2)}}
														</td>
													</tr>
													</tbody>
												</table>
											@endif
										</td>
									@elseif($appliedService->Id==CONST_SERVICETYPE_LATEFEE)
										<td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">
											<table class="data-large" style="border-collapse: collapse; width: 70%; background-color: #fff; font-size: 10pt;" bgcolor="#fff">
												<thead>
												<tr class="danger">
													<th style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">No. of Days Late (Actual)</th>
													<th style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">No. of Days Late After Grace Period</th>
													<th style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">Penalty per Day (Nu.)</th>
													<th style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">Total Amount (Nu.)</th>
												</tr>
												</thead>
												<tbody>
												<tr>
													<td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">
														{{$hasLateFeeAmount[0]->PenaltyNoOfDays-1}}<br />
														<small><i class="font-red">* 30 days is grace period.</i></small><br />
														<small><i class="font-red">* Registration Expired on {{convertDateToClientFormat($hasLateFeeAmount[0]->RegistrationExpiryDate)}}</i></small><br />
														<small><i class="font-red">* Application for Renewal on {{convertDateToClientFormat($hasLateFeeAmount[0]->ApplicationDate)}}</i></small>
													</td>
													<td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">
														<?php $lateFeeAfterGracePeriod=$hasLateFeeAmount[0]->PenaltyNoOfDays-30-1; ?>
														@if(($hasLateFeeAmount[0]->PenaltyNoOfDays-1)>30)
															<?php $lateFeeAfterGracePeriod=$hasLateFeeAmount[0]->PenaltyNoOfDays-30-1; ?>
														@else
															<?php $lateFeeAfterGracePeriod = 0; ?>
														@endif
														@if($lateFeeAfterGracePeriod>0)
															{{$lateFeeAfterGracePeriod}}
														@endif
													</td>
													<td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">
														{{number_format($hasLateFeeAmount[0]->PenaltyLateFeeAmount,2)}}
													</td>
													<td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">
														<?php $lateFeeAmount=(int)$lateFeeAfterGracePeriod*(int)$hasLateFeeAmount[0]->PenaltyLateFeeAmount; ?>
															@if((int)$maxClassification == 998 || (int)$maxClassification == 997)
															@if($lateFeeAmount > 3000)
																<?php $lateFeeAmount = 3000; ?>
															@endif
														@endif
														{{number_format($lateFeeAmount,2)}}
													</td>
												</tr>
												<tr class="bold">
													<td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff" colspan="3">Total</td>
													<td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">
														<?php $appliedService->ContractorAmount=$lateFeeAmount;?>
															@if((int)$hasWaiver == 1)
																<span style="text-decoration: line-through;">
															@endif
															{{number_format($lateFeeAmount,2)}}
															@if((int)$hasWaiver == 1)
																</span>
																&nbsp;
																{{{$newLateFeeAmount}}}
															@endif
													</td>
												</tr>
												</tbody>
											</table>
										</td>
									@endif
									<td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff" class="text-right">
										@if((bool)$appliedService->ContractorAmount!=NULL)
										<?php $totalFeeApplicable+=$appliedService->ContractorAmount; ?>
										@if($appliedService->Id==CONST_SERVICETYPE_LATEFEE)
											@if((int)$hasWaiver == 1)
												<span style="text-decoration: line-through;">
											@endif
										@endif
										{{{number_format($appliedService->ContractorAmount,2)}}}
										@if($appliedService->Id==CONST_SERVICETYPE_LATEFEE)
											@if((int)$hasWaiver == 1)
												</span>
												&nbsp;
												{{{$newLateFeeAmount}}}
											@endif
										@endif
										@else
											-
										@endif
									</td>
								</tr>
							@endforeach
							<tr class="text-right bold">
								<td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff" colspan="2">Total Amount Payable</td>
								<td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">
									{{--{{{number_format($totalFeeApplicable,2)}}}--}}
									@if((int)$hasWaiver == 1)
										<span style="text-decoration: line-through;">
									@endif
									{{{number_format($totalFeeApplicable,2)}}}
									@if((int)$hasWaiver == 1)
										</span>
										<br>
										{{{number_format((int)$totalFeeApplicable-((int)$lateFeeAmount-(int)$newLateFeeAmount),2)}}}
									@endif
								</td>
							</tr>
							</tbody>
						</table>
						<b>Total Amount Payable: Nu.
							@if((int)$hasWaiver == 1)
								{{{number_format((int)$totalFeeApplicable-((int)$lateFeeAmount-(int)$newLateFeeAmount),2)}}}
							@else
								{{{number_format($totalFeeApplicable,2)}}}
							@endif</b>
				@endif
			@endif
		@elseif($applicantType == 2)

				{{--Consultant--}}
				@if((bool)$applicationType)
					@if($applicationType == 1)
							<table class="data-large" style="border-collapse: collapse; width: 70%; background-color: #fff; font-size: 10pt;" bgcolor="#fff">
								<thead>
								<tr style="background-color: #ccc;" bgcolor="#ccc">
									<th style="background-color: #d7e5f2; padding: 0 8px 0 8px; border: 1px solid #000;" bgcolor="#d7e5f2">Category</th>
									<th style="background-color: #d7e5f2; padding: 0 8px 0 8px; border: 1px solid #000;" bgcolor="#d7e5f2">Applied</th>
									<th style="background-color: #d7e5f2; padding: 0 8px 0 8px; border: 1px solid #000;" bgcolor="#d7e5f2">Verified</th>
									<th style="background-color: #d7e5f2; padding: 0 8px 0 8px; border: 1px solid #000;" bgcolor="#d7e5f2">Approved</th>
									<th style="text-align: center;background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2" align="center">No. of Service (Approved) X Fee.</th>
									<th style="text-align: right;background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2" align="right">Total</th>
								</tr>
								</thead>
								<tbody>
								<?php $noOfServicePerCategory=0;$overAllTotalAmount=0; ?>
								@foreach($serviceCategories as $serviceCategory)
									<tr style="background-color: #ccc;" bgcolor="#ccc">
										<td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">{{$serviceCategory->Name}}</td>
										<td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">
											@foreach($appliedCategoryServices as $appliedServiceFee)
												@if($appliedServiceFee->CmnConsultantServiceCategoryId==$serviceCategory->Id)
													<span class="font-green-seagreen tooltips" data-placement="top" data-original-title="{{$appliedServiceFee->ServiceName}}"><i class="fa fa-question-circle"></i></span>
													{{$appliedServiceFee->ServiceCode}}
												@endif
											@endforeach
										</td>
										<td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">
											@foreach($verifiedCategoryServices as $verifiedServiceFee)
												@if($verifiedServiceFee->CmnConsultantServiceCategoryId==$serviceCategory->Id)
													<span class="font-green-seagreen tooltips" data-placement="top" data-original-title="{{$verifiedServiceFee->ServiceName}}"><i class="fa fa-question-circle"></i></span>
													{{$verifiedServiceFee->ServiceCode}}
												@endif
											@endforeach
										</td>
										<td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">
											@foreach($approvedCategoryServices as $approvedServiceFee)
												@if($approvedServiceFee->CmnConsultantServiceCategoryId==$serviceCategory->Id)
													<?php $noOfServicePerCategory+=1; ?>
													<span class="font-green-seagreen tooltips" data-placement="top" data-original-title="{{$approvedServiceFee->ServiceName}}"><i class="fa fa-question-circle"></i></span>
													{{$approvedServiceFee->ServiceCode}}
												@endif
											@endforeach
										</td>
										<td style="text-align: center;font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff" align="center">
											{{$noOfServicePerCategory.' X '.number_format($feeStructures[0]->ConsultantAmount,2)}}
										</td>
										<td style="text-align: right;font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff" align="right">
											<?php $curTotalAmount=$noOfServicePerCategory*$feeStructures[0]->ConsultantAmount;$overAllTotalAmount+=$curTotalAmount; ?>
											{{number_format($noOfServicePerCategory*$feeStructures[0]->ConsultantAmount,2)}}
										</td>
									</tr>
									<?php $noOfServicePerCategory=0; ?>
								@endforeach
								<tr style="font-weight: bold;background-color: #ccc; text-align: right;" bgcolor="#ccc" align="right">
									<td colspan="5" style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff"><span style="font-weight: bold;color:#db4437;text-align: left;" align="left">*Nu {{number_format($feeStructures[0]->ConsultantAmount,2)}} is applicable for each service under the category</span> Total</td>
									<td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">{{number_format($overAllTotalAmount,2)}}</td>
								</tr>
								</tbody>
							</table>
							<b>Total Amount Payable: Nu. {{number_format($overAllTotalAmount,2)}}</b>
					@else
						{{--For Service application--}}
						<table class="table table-bordered table-striped table-condensed ">
							<thead>
							<tr>
								<th>Service(s) Applied</th>
								<th>Details</th>
								<th width="10%" class="text-right">Fee (Nu.)</th>
							</tr>
							</thead>
							<tbody>
							<?php $totalFeeApplicable=0; $categoryClassificationFeeTotal=0;$lateFeeAmount=0;?>
							@foreach($appliedServices as $appliedService)
								<tr>
									<td @if($appliedService->Id!=CONST_SERVICETYPE_RENEWAL && $appliedService->Id!=CONST_SERVICETYPE_LATEFEE && $appliedService->Id!=CONST_SERVICETYPE_CHANGEOFCATEGORYCLASSIFICATION)colspan="2"@endif>
										{{{$appliedService->ServiceName}}}
									</td>
									@if($appliedService->Id==CONST_SERVICETYPE_RENEWAL || $appliedService->Id==CONST_SERVICETYPE_CHANGEOFCATEGORYCLASSIFICATION)
										<td>
											@if($hasRenewal && $hasChangeInCategoryClassification)
												@if($appliedService->Id==CONST_SERVICETYPE_RENEWAL)
													<table class="table table-bordered table-hover table-condensed">
														<thead>
														<tr class="success">
															<th>Category</th>
															<th>Applied</th>
															<th>Verified</th>
															<th>Approved</th>
															<th>No. of Service (Approved) X Fee</th>
															<th class="text-right">Total</th>
														</tr>
														</thead>
														<tbody>
														@foreach($hasCategoryClassificationsFee as $hasCategoryClassificationFee)
															<?php $approvedServiceCount = 0; ?>
															<tr>
																<td>{{{$hasCategoryClassificationFee->ServiceCategoryName}}}</td>
																<td>
																	@if((bool)$hasCategoryClassificationFee->AppliedService!=NULL)
																		{{{$hasCategoryClassificationFee->AppliedService}}}
																	@else
																		-
																	@endif
																</td>
																<td>
																	@if((bool)$hasCategoryClassificationFee->VerifiedService!=NULL)
																		{{{$hasCategoryClassificationFee->VerifiedService}}}
																	@else
																		-
																	@endif
																</td>
																<td>
																	@if((bool)$hasCategoryClassificationFee->ApprovedService!=NULL)
																		{{{$hasCategoryClassificationFee->ApprovedService}}}
																	@else
																		-
																	@endif
																	@foreach($approvedCategoryServicesArray[$hasCategoryClassificationFee->ServiceCategoryId] as $approvedServiceIndividual)
																		{{--@if(!in_array($approvedServiceIndividual,$existingCategoryServicesArray[$hasCategoryClassificationFee->ServiceCategoryId]))--}}
																			<?php $approvedServiceCount++; ?>
																		{{--@endif--}}
																	@endforeach
																</td>
																<td class="text-center">
																	{{$approvedServiceCount .' X '.number_format($feeAmount[0]->ConsultantAmount)}}
																</td>
																<td class="text-right">
																	<?php $categoryClassificationFeeTotal+=$approvedServiceCount * $feeAmount[0]->ConsultantAmount;?>
																	{{number_format($approvedServiceCount * $feeAmount[0]->ConsultantAmount)}}
																</td>
															</tr>
														@endforeach
														<tr class="bold">
															<td colspan="5">Total</td>
															<td class="text-right">
																<?php $appliedService->ConsultantAmount=$categoryClassificationFeeTotal; ?>
																{{number_format($categoryClassificationFeeTotal,2)}}
															</td>
														</tr>
														</tbody>
													</table>
												@else
													<span class="font-red">*Fee details has been already displayed aganist Renewal of CDB Certificate.</span>
												@endif
											@else
												<table class="table table-bordered table-hover table-condensed">
													<thead>
													<tr class="success">
														<th>Category</th>
														<th>Applied</th>
														<th>Verified</th>
														<th>Approved</th>
														<th>No. of Service (Approved) X Fee</th>
														<th class="text-right">Total</th>
													</tr>
													</thead>
													<tbody>
													@foreach($hasCategoryClassificationsFee as $hasCategoryClassificationFee)
													<?php $approvedServiceCount = 0; ?>
														<tr>
															<td>{{{$hasCategoryClassificationFee->ServiceCategoryName}}}</td>
															<td>
																@if((bool)$hasCategoryClassificationFee->AppliedService!=NULL)
																	{{{$hasCategoryClassificationFee->AppliedService}}}
																@else
																	-
																@endif
															</td>
															<td>
																@if((bool)$hasCategoryClassificationFee->VerifiedService!=NULL)
																	{{{$hasCategoryClassificationFee->VerifiedService}}}
																@else
																	-
																@endif
															</td>
															<td>
																@if((bool)$hasCategoryClassificationFee->ApprovedService!=NULL)
																	{{{$hasCategoryClassificationFee->ApprovedService}}}
																@else
																	-
																@endif
																@if($appliedService->Id==CONST_SERVICETYPE_RENEWAL)
																	@foreach($approvedCategoryServicesArray[$hasCategoryClassificationFee->ServiceCategoryId] as $approvedServiceIndividual)
{{--																		@if(!in_array($approvedServiceIndividual,$existingCategoryServicesArray[$hasCategoryClassificationFee->ServiceCategoryId]))--}}
																			<?php $approvedServiceCount++; ?>
																		{{--@endif--}}
																	@endforeach
																@else
																	@foreach($approvedCategoryServicesArray[$hasCategoryClassificationFee->ServiceCategoryId] as $approvedServiceIndividual)
																		@if(!in_array($approvedServiceIndividual,$existingCategoryServicesArray[$hasCategoryClassificationFee->ServiceCategoryId]))
																			<?php $approvedServiceCount++; ?>
																		@endif
																	@endforeach
																@endif
															</td>
															<td class="text-center">
																{{$approvedServiceCount.' X '.number_format($feeAmount[0]->ConsultantAmount)}}
															</td>
															<td class="text-right">
																<?php $categoryClassificationFeeTotal+=$approvedServiceCount* $feeAmount[0]->ConsultantAmount;?>
																{{number_format($approvedServiceCount* $feeAmount[0]->ConsultantAmount)}}
															</td>
														</tr>
													@endforeach
													<tr class="bold">
														<td colspan="5">Total</td>
														<td class="text-right">
															<?php $appliedService->ConsultantAmount=$categoryClassificationFeeTotal; ?>
															{{number_format($categoryClassificationFeeTotal,2)}}
														</td>
													</tr>
													</tbody>
												</table>
											@endif
										</td>
									@elseif($appliedService->Id==CONST_SERVICETYPE_LATEFEE)
										<td>
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
														{{$hasLateFeeAmount[0]->PenaltyNoOfDays}}<br />
														<small><i class="font-red">* 30 days is grace period.</i></small><br />
														<small><i class="font-red">Registration Expired on {{convertDateToClientFormat($hasLateFeeAmount[0]->RegistrationExpiryDate)}}</i></small><br />
														<small><i class="font-red">* Application for Renewal on {{convertDateToClientFormat($hasLateFeeAmount[0]->ApplicationDate)}}</i></small>
													</td>
													<td>
														<?php $lateFeeAfterGracePeriod=$hasLateFeeAmount[0]->PenaltyNoOfDays-30-1; ?>
														@if($lateFeeAfterGracePeriod>0)
															{{$lateFeeAfterGracePeriod}}
														@endif
													</td>
													<td>
														{{number_format($hasLateFeeAmount[0]->PenaltyLateFeeAmount,2)}}
													</td>
													<td>
														<?php $lateFeeAmount=(int)$lateFeeAfterGracePeriod*(int)$hasLateFeeAmount[0]->PenaltyLateFeeAmount; ?>
														{{number_format((int)$lateFeeAfterGracePeriod*(int)$hasLateFeeAmount[0]->PenaltyLateFeeAmount,2)}}
													</td>
												</tr>
												<tr class="bold">
													<td colspan="2">Total</td>
													<td>
														<?php $appliedService->ConsultantAmount=$lateFeeAmount;?>
														@if((int)$hasWaiver == 1)
															<span style="text-decoration: line-through;">
														@endif
														{{number_format($lateFeeAmount,2)}}
														@if((int)$hasWaiver == 1)
															</span>
															&nbsp;
															{{{number_format($newLateFeeAmount,2)}}}
														@endif
													</td>
												</tr>
												</tbody>
											</table>
										</td>
									@endif
									<td class="text-right">
										@if((bool)$appliedService->ConsultantAmount!=NULL)
											<?php $totalFeeApplicable+=$appliedService->ConsultantAmount; ?>
											@if($appliedService->Id==CONST_SERVICETYPE_LATEFEE)
												@if((int)$hasWaiver == 1)
													<span style="text-decoration: line-through;">
												@endif
											@endif
												{{{number_format($appliedService->ConsultantAmount,2)}}}
											@if($appliedService->Id==CONST_SERVICETYPE_LATEFEE)
												@if((int)$hasWaiver == 1)
													</span>
													&nbsp;
													{{{number_format($newLateFeeAmount,2)}}}
												@endif
											@endif
										@else
											-
										@endif
									</td>
								</tr>
							@endforeach
							<tr class="text-right bold">
								<td colspan="2">Total Amount Payable</td>
								<td>
									@if((int)$hasWaiver == 1)
										<span style="text-decoration: line-through;">
									@endif
										{{{number_format($totalFeeApplicable,2)}}}
									@if((int)$hasWaiver == 1)
										</span>
										<br>
									{{{number_format((int)$totalFeeApplicable-((int)$lateFeeAmount-(int)$newLateFeeAmount),2)}}}
									@endif
								</td>
							</tr>
							</tbody>
						</table>
						<b>Total Amount Payable: Nu. @if((int)$hasWaiver == 1){{{number_format((int)$totalFeeApplicable-((int)$lateFeeAmount-(int)$newLateFeeAmount),2)}}}
							@else
								{{{number_format($totalFeeApplicable,2)}}}
							@endif
						</b>
						{{--End Service Application--}}
					@endif
				@endif

				{{--END Consultant--}}
			{{--@endif--}}
		@elseif($applicantType == 3)
			{{--Architect--}}
			@if((bool)$applicationType)
				@if($applicationType == 1)
					{{--Registration--}}
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
							@foreach($feeStructures as $feeDetail)
								<tr>
									@if($architectServiceSectorType==CONST_CMN_SERVICESECTOR_GOVT)
										<td>Goverment</td>
									@else
										<td>Private</td>
									@endif
									<td>New Registration</td>
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
						<b>Total Amount Payable: Nu. {{number_format($totalFeesApplicable,2)}}</b>
				@else
					{{--Services--}}
						<table class="table table-bordered table-striped table-condensed">
							<thead>
							<tr>
								<th>Service Applied</th>
								<th>Remarks</th>
								<th class="text-right">Amount</th>
							</tr>
							</thead>
							<tbody>
							<?php $totalFeesApplicable=0;$lateFeeAmount=0; ?>
							@foreach($appliedServices as $appliedService)
								<tr>
									@if($architectServiceSectorType==CONST_CMN_SERVICESECTOR_GOVT)
										<td>
											{{{$appliedService->ServiceName}}}
										</td>
										<td class="text-right">
											<?php $totalFeesApplicable+=$appliedService->ArchitectGovtAmount; ?>
											{{{number_format($appliedService->ArchitectGovtAmount,2)}}}
										</td>
									@else
										<td>
											{{$appliedService->ServiceName}}
										</td>
										<td>
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
															{{$hasLateFeeAmount[0]->PenaltyNoOfDays}}<br />
															<small><i class="font-red">* 30 days is grace period.</i></small><br />
															<small><i class="font-red">Registration Expired on {{convertDateToClientFormat($hasLateFeeAmount[0]->RegistrationExpiryDate)}}</i></small><br />
															<small><i class="font-red">* Application for Renewal on {{convertDateToClientFormat($hasLateFeeAmount[0]->ApplicationDate)}}</i></small>
														</td>
														<td>
															<?php $lateFeeAfterGracePeriod=$hasLateFeeAmount[0]->PenaltyNoOfDays-30-1; ?>
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
															{{number_format($hasLateFeeAmount[0]->PenaltyLateFeeAmount,2)}}
														</td>
														<td>
															<?php $lateFeeAmount=(int)$lateFeeAfterGracePeriod*(int)$hasLateFeeAmount[0]->PenaltyLateFeeAmount; ?>
															{{number_format((int)$lateFeeAfterGracePeriod*(int)$hasLateFeeAmount[0]->PenaltyLateFeeAmount,2)}}
															<?php $appliedService->ArchitectPvtAmount=$lateFeeAmount;?>
															@if((int)$hasWaiver == 1)
																<span style="text-decoration: line-through;">
															@endif
																{{number_format($lateFeeAmount,2)}}
															@if((int)$hasWaiver == 1)
																</span>
																&nbsp;
																{{{number_format($newLateFeeAmount,2)}}}
															@endif

														</td>
													</tr>
													<tr class="bold">
														<td colspan="3">Total</td>
														<td>
															<?php $appliedService->ArchitectPvtAmount=$lateFeeAmount;?>
															{{number_format($lateFeeAmount,2)}}
														</td>
													</tr>
													</tbody>
												</table>
											@endif
										</td>
										<td class="text-right">
											<?php $totalFeesApplicable+=$appliedService->ArchitectPvtAmount; ?>
											@if($appliedService->Id==CONST_SERVICETYPE_LATEFEE)
												@if((int)$hasWaiver == 1)
													<span style="text-decoration: line-through;">
												@endif
											@endif
											{{{number_format($appliedService->ArchitectPvtAmount,2)}}}
											@if($appliedService->Id==CONST_SERVICETYPE_LATEFEE)
												@if((int)$hasWaiver == 1)
												</span>
													&nbsp;
													{{{number_format($newLateFeeAmount,2)}}}
												@endif
											@endif
										</td>
									@endif
								</tr>
							@endforeach
							<tr>
								<td colspan="2" class="bold text-right">
									Total
								</td>
								<td class="text-right bold">
									@if((int)$hasWaiver == 1)
										<span style="text-decoration: line-through;">
									@endif
									{{{number_format($totalFeesApplicable,2)}}}
									@if((int)$hasWaiver == 1)
										</span>
										<br>
										{{{number_format((int)$totalFeesApplicable-((int)$lateFeeAmount-(int)$newLateFeeAmount),2)}}}
									@endif
								</td>
							</tr>
							</tbody>
						</table>
						<b>Total Amount Payable: Nu. @if((int)$hasWaiver == 1)
							{{{number_format((int)$totalFeesApplicable-((int)$lateFeeAmount-(int)$newLateFeeAmount),2)}}}
							@else
							{{{number_format($totalFeesApplicable,2)}}}
							@endif </b>
					{{--End Services--}}

				@endif
			@endif
		@elseif($applicantType == 4)
			@if((bool)$applicationType)
				@if($applicationType == 1)
						<table class="table table-bordered table-striped table-condensed">
							<thead>
							<tr>
								<th>Engineer Type</th>
								<th>Application Type</th>
								<th width="20%" class="text-center">Validity (yrs)</th>
								<th class="text-right">Amount</th>
							</tr>
							</thead>
							<tbody>
							<?php $totalFeesApplicable=0; ?>
							@foreach($feeStructures as $feeDetail)
								<tr>
									@if($engineerServiceSectorType==CONST_CMN_SERVICESECTOR_GOVT)
										<td>Goverment</td>
									@else
										<td>Private</td>
									@endif
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
					<b>Total Amount Payable: Nu. {{number_format($totalFeesApplicable,2)}}</b>
				@else
						<table class="table table-bordered table-striped table-condensed">
							<thead>
							<tr>
								<th>Service Applied</th>
								<th>Remarks</th>
								<th class="text-right">Amount</th>
							</tr>
							</thead>
							<tbody>
							<?php $totalFeesApplicable=0;$lateFeeAmount=0; ?>
							@foreach($appliedServices as $appliedService)
								<tr>
									@if($engineerServiceSectorType==CONST_CMN_SERVICESECTOR_GOVT)
										<td>
											{{$appliedService->ServiceName}}
										</td>
										<td>
											-
										</td>
										<td class="text-right">
											@if(!empty($appliedService->EngineeerGovtAmount))
												<?php $totalFeesApplicable+=$appliedService->EngineerGovtAmount; ?>
												{{number_format($appliedService->EngineeerGovtAmount,2)}}
											@else
												-
											@endif
										</td>
									@else
										<td>
											{{$appliedService->ServiceName}}
										</td>
										<td>
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
															{{$hasLateFeeAmount[0]->PenaltyNoOfDays}}<br />
															<small><i class="font-red">* 30 days is grace period.</i></small><br />
															<small><i class="font-red">Registration Expired on {{convertDateToClientFormat($hasLateFeeAmount[0]->RegistrationExpiryDate)}}</i></small><br />
															<small><i class="font-red">* Application for Renewal on {{convertDateToClientFormat($hasLateFeeAmount[0]->ApplicationDate)}}</i></small>
														</td>
														<td>
															<?php $lateFeeAfterGracePeriod=$hasLateFeeAmount[0]->PenaltyNoOfDays-30; ?>
															@if($lateFeeAfterGracePeriod>0)
																{{$lateFeeAfterGracePeriod}}
															@endif
														</td>
														<td>
															{{$hasLateFeeAmount[0]->PenaltyLateFeeAmount}}
														</td>
														<td>
															<?php $lateFeeAmount=(int)$lateFeeAfterGracePeriod*(int)$hasLateFeeAmount[0]->PenaltyLateFeeAmount; ?>
															{{number_format((int)$lateFeeAfterGracePeriod*(int)$hasLateFeeAmount[0]->PenaltyLateFeeAmount,2)}}
														</td>
													</tr>
													<tr class="bold">
														<td colspan="3">Total</td>
														<td>
															<?php $appliedService->EngineerPvtAmount=$lateFeeAmount;?>
															@if((int)$hasWaiver == 1)
																<span style="text-decoration: line-through;">
															@endif
															{{number_format($lateFeeAmount,2)}}
															@if((int)$hasWaiver == 1)
																</span>
																&nbsp;
																{{{number_format($newLateFeeAmount,2)}}}
															@endif
														</td>
													</tr>
													</tbody>
												</table>
											@endif
										</td>
										<td class="text-right">
											<?php $totalFeesApplicable+=$appliedService->EngineerPvtAmount; ?>
											@if($appliedService->Id==CONST_SERVICETYPE_LATEFEE)
												@if((int)$hasWaiver == 1)
													<span style="text-decoration: line-through;">
												@endif
											@endif
											{{{number_format($appliedService->EngineerPvtAmount,2)}}}
											@if($appliedService->Id==CONST_SERVICETYPE_LATEFEE)
												@if((int)$hasWaiver == 1)
													</span>
													&nbsp;
													{{{number_format($newLateFeeAmount,2)}}}
												@endif
											@endif
										</td>
									@endif
								</tr>
							@endforeach
							<tr>
								<td colspan="2" class="bold text-right">
									Total
								</td>
								<td class="text-right bold">
									@if((int)$hasWaiver == 1)
										<span style="text-decoration: line-through;">
									@endif
									{{{number_format($totalFeesApplicable,2)}}}
									@if((int)$hasWaiver == 1)
									</span>
										<br>
										{{{number_format((int)$totalFeesApplicable-((int)$lateFeeAmount-(int)$newLateFeeAmount),2)}}}
									@endif
								</td>
							</tr>
							</tbody>
						</table>
					<b>Total Amount Payable: Nu. @if((int)$hasWaiver == 1)
							{{{number_format((int)$totalFeesApplicable-((int)$lateFeeAmount-(int)$newLateFeeAmount),2)}}}
						@else
							{{{number_format($totalFeesApplicable,2)}}}
						@endif</b>
				@endif
			@endif
		@endif
		</div>
	@endif
@if(isset($applicationHistories) && !empty($applicationHistories))
	<div class="col-md-6">
		<h4 class="text-primary">Application History</h4>
		@foreach($applicationHistories as $applicationHistory)
			<table class="table table-condensed table-striped table-bordered">
				<tbody>
					<tr>
						<td><strong>Application For</strong></td>
						<td>{{$applicationHistory->ApplicationType}}</td>
					</tr>
					<tr>
						<td><strong>Application No.</strong></td>
						<td>{{$applicationHistory->ReferenceNo}}</td>
					</tr>
					<tr>
						<td><strong>Application Date</strong></td>
						<td>{{convertDateToClientFormat($applicationHistory->ApplicationDate)}}</td>
					</tr>
					<tr>
						<td><strong>Application Status</strong></td>
						<td>{{$applicationHistory->ApplicationStatus}}</td>
					</tr>
					<tr>
						<td><strong>Name of Firm</strong></td>
						<td>{{$applicationHistory->NameOfFirm}}</td>
					</tr>
					<tr>
						<td><strong>Registration Approved Date</strong></td>
						<td>
								{{convertDateToClientFormat($applicationHistory->PaymentApprovedDate)}}						
						</td>
					</tr>
					<tr>
						<td><strong>Registration Expiry Date</strong></td>
						<td>
							{{convertDateToClientFormat($applicationHistory->RegistrationExpiryDate)}}
						</td>
					</tr>
				</tbody>
			</table>
		@endforeach
	</div>

	@endif
</div>
@else
	<div class="col-md-12">
		<p><strong>No applicant with this Application No/CID No found!</strong></p>
	</div>
@endif
@stop