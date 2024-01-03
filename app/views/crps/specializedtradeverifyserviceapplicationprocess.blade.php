@extends('master')
@section('pagescripts')
{{ HTML::script('assets/global/scripts/specializedtrade.js') }}
@stop
@section('content')
<div id="verify" class="modal fade" role="dialog" aria-labelledby="verify" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h3 class="modal-title font-green-seagreen">Verify Registration</h3>
			</div>
			<div class="modal-body">
				<p class="bold">Are you sure you want to verify this application?</p>
			</div>
			<div class="modal-footer">
				<button id="verifyspecializedtraderegistration" type="button" class="btn green">Verify</button>
				<button class="btn red" data-dismiss="modal" aria-hidden="true">Cancel</button>
			</div>
		</div>
	</div>
</div>
<div id="reject" class="modal fade" role="dialog" aria-labelledby="reject" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			{{ Form::open(array('url' => 'specializedtrade/mrejectregistration','role'=>'form'))}}
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h3 class="modal-title font-green-seagreen">Reject Registration</h3>
			</div>
			<div class="modal-body">
				<input type="hidden" name="RedirectRoute" value="verifyregistration">
				<p class="bold">Are you sure you want to reject this application?</p>
				<input type="hidden" name="SpecializedTradeReference" value="{{$specializedTradeInformations[0]->Id}}" />
				<input type="hidden" name="RejectedDate" value="{{date('Y-m-d G:i:s')}}" />
				<div class="form-group">
					<label>Remarks</label>
					<textarea name="RemarksByRejector" class="form-control" rows="3"></textarea>
				</div>
			</div>
			<div class="modal-footer">
				<button id="rejectspecializedtraderegistration" type="submit" class="btn green">Reject</button>
				<button class="btn red" data-dismiss="modal" aria-hidden="true">Cancel</button>
			</div>
			{{Form::close()}}
		</div>
	</div>
</div>
<div class="portlet light bordered" id="form_wizard_1">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-gift"></i>
			<span class="caption-subject">Verify Specialized Trade Registration</span>
		</div>
		<div class="actions">
			<a href="{{URL::to('specializedtrade/verifyserviceapplicationlist')}}" class="btn btn-sm btn-default">Back to List</a>
		</div>
	</div>
	<div class="portlet-body form">
		{{ Form::open(array('url' => $modelPost,'role'=>'form','id'=>'verifyregistrationspecializedtrade'))}}
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
							<?php $totalFeesApplicable=0;$lateFeeAmount=0;?>
							@foreach($appliedServices as $appliedService)
							<tr>
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
				<div class="col-md-6">
				@foreach($specializedTradeInformations as $specializedTradeInformation)
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
					<h5 class="font-blue-madison bold">Category Details</h5>
					<table class="table table-bordered table-striped table-condensed">
						<thead>
							<tr>
								<th>Category</th>
								<th width="5%" class="table-checkbox">Current</th>
								<th width="5%" class="table-checkbox">Applied</th>
								<th width="5%" class="table-checkbox">Verify</th>
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
										<input type="hidden" name="WorkCategoryTableId[]" value="{{$workClasssification->AppliedTableId}}" class="tablerowcontrol" @if((bool)$workClasssification->CmnAppliedCategoryId==NULL) disabled="disabled" @endif/>
										<input type="checkbox" class="tablerowcheckbox" value="1" @if((bool)$workClasssification->CmnAppliedCategoryId!=NULL)checked="checked"@endif/>
										<input type="hidden" name="CmnVerifiedCategoryId[]" value="{{$workClasssification->CategoryId}}" class="tablerowcontrol" @if((bool)$workClasssification->CmnAppliedCategoryId==NULL) disabled="disabled @endif" />
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
                   <a href="{{URL::to('specializedtrade/editregistrationinfo/'.$specializedTradeId.'?redirectUrl=specializedtrade/verifyserviceapplicationprocess')}}" class="btn blue-madison editaction"><i class="fa fa-edit"></i> Edit</a>
                </div>
            </div>
		</div>
		@include('crps.applicationhistory')
		<div class="form-group">
			<input type="hidden" name="VerifiedDate" value="{{date('Y-m-d G:i:s')}}" />
			<input type="hidden" name="SysLockedByUserId" value="" />
			<input type="hidden" name="SpecializedTradeReference" value="{{$specializedTradeId}}">
			<input type="hidden" name="CmnApplicationRegistrationStatusId" value="{{CONST_CMN_APPLICATIONREGISTRATIONSTATUS_VERIFIED}}">
			<input type="hidden" name="SysVerifierUserId" value="{{Auth::user()->Id}}" />
			<label>Remarks</label>
			<textarea name="RemarksByVerifier" class="form-control" rows="3"></textarea>
		</div>
		<div class="form-actions">
			<div class="btn-set">
				<a href="#verify" data-toggle="modal" class="btn green">Verify</a>
				<a href="#reject" data-toggle="modal" class="btn red">Reject</a>
			</div>
			<button type="button" class="btn blue-ebonyclay pull-right">Next</button>
		</div>
		{{Form::close()}}
	</div>
</div>
@stop