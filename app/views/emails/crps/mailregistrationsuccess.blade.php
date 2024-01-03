@extends('emailmaster')
@section('emailcontent')
<p><strong>{{$applicantName}}</strong></p>
<p class="lead">Application No:<i>{{$applicationNo}}</i> Dt.<i>{{$applicationDate}}</i></p>
<p>{{$mailMessage}}</p>
<!-- Callout Panel -->                           
@stop
@if((bool)$mailIntendedTo!=NULL)
@section('feestructure')
	<h3>Fee Structure</h3>
	@if((int)$mailIntendedTo==1)
		@if(!isset($applicationType))
		<table class="data-large" style="border-collapse: collapse; width: 100%; background-color: #fff; font-size: 10pt;" bgcolor="#fff">
			<thead>
				<tr style="background-color: #ccc;" bgcolor="#ccc">
					<th style="background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2">Category</th>
					<th style="background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2">Class</th>
					<th style="text-align: right;background-color: #d7e5f2; border: 1px solid #000;" align="right" bgcolor="#d7e5f2">Fee (Nu.)</th>
				</tr>
			</thead>
			<tbody>
				<?php $totalFee=0; ?>
				@foreach($feeStructures as $feeStructure)
				<tr style="background-color: #ccc;" bgcolor="#ccc">
					<td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">
						{{$feeStructure->CategoryCode.' ('.$feeStructure->Category.')'}}
					</td>
					<td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">
						{{$feeStructure->AppliedClassificationCode.' ('.$feeStructure->AppliedClassification.')'}}
					</td>
					<td style="text-align: right;font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff" align="right">
						<?php $feeAmount=$feeStructure->AppliedRegistrationFee; ?>
						{{number_format($feeStructure->AppliedRegistrationFee,2)}}
					</td>
					<?php $totalFee+=$feeAmount;?>
				</tr>
				@endforeach
				<tr style="font-weight: bold;background-color: #ccc; text-align: right;" bgcolor="#ccc" align="right">
					<td colspan="2" style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">Total</td>
					<td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">{{{number_format($totalFee,2)}}}</td>
				</tr>
			</tbody>
		</table>
		@else
			<table class="data-large" style="border-collapse: collapse; width: 100%; background-color: #fff; font-size: 10pt;" bgcolor="#fff">
				<thead>
				<tr>
					<th style="background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2">Service(s) Applied</th>
					<th style="background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2">Details</th>
					<th style="background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2" width="10%" class="text-right">Amount (Nu.)</th>
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
									<table class="data-large" style="border-collapse: collapse; width: 100%; background-color: #fff; font-size: 10pt;" bgcolor="#fff">
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
															<?php $categoryClassificationFeeTotal+=$hasCategoryClassificationFee->AppliedRenewalFee; ?>
															{{number_format($hasCategoryClassificationFee->AppliedRenewalFee,2)}}
															@else
																-
															@endif
														@else
															<?php $categoryClassificationFeeTotal+=$hasCategoryClassificationFee->AppliedRegistrationFee; ?>
															{{number_format($hasCategoryClassificationFee->AppliedRegistrationFee,2)}}
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
								<table class="data-large" style="border-collapse: collapse; width: 100%; background-color: #fff; font-size: 10pt;" bgcolor="#fff">
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
											{{number_format((int)$lateFeeAfterGracePeriod*(int)$hasLateFeeAmount[0]->PenaltyLateFeeAmount,2)}}
										</td>
									</tr>
									<tr class="bold">
										<td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff" colspan="3">Total</td>
										<td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">
											<?php $appliedService->ContractorAmount=$lateFeeAmount;?>
											{{number_format($lateFeeAmount,2)}}
										</td>
									</tr>
									</tbody>
								</table>
							</td>
						@endif
						<td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff" class="text-right">
							@if((bool)$appliedService->ContractorAmount!=NULL)
								<?php $totalFeeApplicable+=$appliedService->ContractorAmount; ?>
								{{{number_format($appliedService->ContractorAmount,2)}}}
							@else
								-
							@endif
						</td>
					</tr>
				@endforeach
				<tr class="text-right bold">
					<td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff" colspan="2">Total Amount Payable</td>
					<td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">{{{number_format($totalFeeApplicable,2)}}}</td>
				</tr>
				</tbody>
			</table>
			<b>Total Amount Payable: Nu. {{number_format($totalFeeApplicable,2)}}</b>
		@endif
	@elseif((int)$mailIntendedTo==2)
		<table class="data-large" style="border-collapse: collapse; width: 100%; background-color: #fff; font-size: 10pt;" bgcolor="#fff">
			<thead>
				<tr style="background-color: #ccc;" bgcolor="#ccc">
					<th style="background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2">Category</th>
					<th style="background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2">Applied</th>
					<th style="text-align: center;background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2" align="center">No. of Service (Applied) X Fee</th>
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
									<?php $noOfServicePerCategory+=1; ?>
									{{$appliedServiceFee->ServiceCode}},
									<span class="font-green-seagreen tooltips" data-placement="top" data-original-title="{{$appliedServiceFee->ServiceName}}"><i class="fa fa-question-circle"></i></span>
								@endif
							@endforeach
						</td>
						<td style="text-align: center;font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff" align="center">
							{{$noOfServicePerCategory.' X '.number_format($feeStructures[0]->ConsultantAmount,2)}}
						</td>
						<td style="text-align: right;font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff" align="right">
							<?php $curTotalAmount=$noOfServicePerCategory*3000;$overAllTotalAmount+=$curTotalAmount; ?>
							{{number_format($noOfServicePerCategory*$feeStructures[0]->ConsultantAmount,2)}}
						</td>
					</tr>
					<?php $noOfServicePerCategory=0; ?>
					@endforeach
					<tr style="font-weight: bold;background-color: #ccc; text-align: right;" bgcolor="#ccc" align="right">
						<td colspan="3" style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff"><span style="font-weight: bold;color:#db4437;text-align: left;" align="left">*Nu {{number_format($feeStructures[0]->ConsultantAmount,2)}} is applicable for each service applied under the category</span> Total</td>
						<td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">{{number_format($overAllTotalAmount,2)}}</td>
					</tr>
			</tbody>
		</table>
	@elseif((int)$mailIntendedTo==3 || (int)$mailIntendedTo==4)
		 <table class="data-large" style="border-collapse: collapse; width: 100%; background-color: #fff; font-size: 10pt;" bgcolor="#fff">
			<thead>
				<tr style="background-color: #ccc;" bgcolor="#ccc">
					<th style="background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2">Type</th>
					<th style="text-align: center;background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2" align="center">Validity (yrs)</th>
					<th style="text-align: right;background-color: #d7e5f2; border: 1px solid #000;" bgcolor="#d7e5f2" align="right">Fee (Nu.)</th>
				</tr>
			</thead>
			<tbody>
				<?php $totalFeesApplicable=0; ?>
				@foreach($feeDetails as $feeDetail)
				<tr style="background-color: #ccc;" bgcolor="#ccc">
					<td style="font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff">{{$feeDetail->SectorType}}</td>
					<td style="text-align: center;font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff" align="center">
						@if(empty($feeDetail->RegistrationValidity))
							-
						@else
						{{$feeDetail->RegistrationValidity}}
						@endif
					</td>
					<td style="text-align: right;font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff" align="right">
						@if(empty($feeDetail->NewRegistrationFee))
							-
						@else
						{{number_format($feeDetail->NewRegistrationFee,2)}}
						@endif
					</td>
					<?php $totalFeesApplicable+=$feeDetail->NewRegistrationFee; ?>
				</tr>
				@endforeach
				<tr style="font-weight: bold;background-color: #ccc; text-align: right;" bgcolor="#ccc" align="right">
					<td colspan="2" style="font-weight: bold;text-align: right;font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff" align="right">
						Total
					</td>
					<td style="font-weight: bold;text-align: right;font-size: 10pt; background-color: #fff; padding: 2px; border: 1px solid #000;" bgcolor="#fff" align="right">
						{{number_format($totalFeesApplicable,2)}}
					</td>
				</tr>
			</tbody>
		</table>
	@endif
@stop
@endif
@section('notes')
<table style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6; width: 100%; margin: 0; padding: 0;"><tr style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6; margin: 0; padding: 0;"><td style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6; margin: 0; padding: 10px 0;">&#13;
		<p style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6; font-weight: normal; margin: 0 0 10px; padding: 0;"><a href="http://www.cdb.gov.bt" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 2; color: #FFF; text-decoration: none; font-weight: bold; text-align: center; cursor: pointer; display: inline-block; border-radius: 25px; background-color: #348eda; margin: 0 10px 0 0; padding: 0; border-color: #348eda; border-style: solid; border-width: 10px 20px;">Track Your Application Here</a></p>&#13;
	</td>&#13;
</tr></table>
@stop