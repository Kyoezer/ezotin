@extends('master')
@section('pagescripts')
{{ HTML::script('assets/global/scripts/engineer.js') }}
@stop
@section('lastcdbno')
<div class="page-toolbar">
	<div id="dashboard-report-range" class="pull-right tooltips btn btn-fit-height grey-cascade" data-placement="top" data-original-title="Last used Engineer No.">
		<span class="thin visible-lg-inline-block">Last used Engineer No.: <span class="bold">{{'AR'.lastUsedEngineerNo();}}</span></span>
	</div>
</div>
@stop
@section('content')
<div id="sendback" class="modal fade" role="dialog" aria-labelledby="sendback" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			{{Form::open(array('url'=>'sendapplicationback','role'=>'form'))}}
			<input type="hidden" name="Id" value="{{$engineerInformations[0]->Id}}" />
			<input type="hidden" name="Model" value="EngineerModel"/>
			<input type="hidden" name="Status" value="{{CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW}}"/>
			<input type="hidden" name="RedirectUrl" value="engineer/approveserviceapplicationlist"/>
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
<div id="approve" class="modal fade" role="dialog" aria-labelledby="approve" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h3 class="modal-title font-green-seagreen">Approve Registration</h3>
			</div>
			<div class="modal-body">
				<p class="bold">Are you sure you want to approve this application?</p>
			</div>
			<div class="modal-footer">
				<button id="approveengineerregistration" type="button" class="btn green">Approve</button>
				<button class="btn red" data-dismiss="modal" aria-hidden="true">Cancel</button>
			</div>
		</div>
	</div>
</div>
<div id="reject" class="modal fade" role="dialog" aria-labelledby="reject" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			{{ Form::open(array('url' => 'engineer/mrejectregistration','role'=>'form'))}}
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h3 class="modal-title font-green-seagreen">Reject Registration</h3>
			</div>
			<div class="modal-body">
				<p class="bold">Are you sure you want to reject this application?</p>
				<input type="hidden" name="EngineerReference" value="{{$engineerInformations[0]->Id}}" />
				<input type="hidden" name="RejectedDate" value="{{date('Y-m-d G:i:s')}}" />
				<div class="form-group">
					<label>Remarks</label>
					<textarea name="RemarksByRejector" class="form-control" rows="3"></textarea>
				</div>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn green">Reject</button>
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
			<span class="caption-subject">Verify Engineer Registration</span>
		</div>
		<div class="actions">
			<a href="{{URL::to('engineer/approveserviceapplicationlist')}}" class="btn btn-default btn-sm">Back to List</a>
		</div>
	</div>
	<div class="portlet-body form">
		<div class="form-body">
			<div class="note note-danger">
	          	<p>If any detail(s) submitted by the applicant is different from the CDB record then the field will be marked in red. To view the current record with CDB, please hover over <i class="font-blue">[old]</i> next to the field.</p>
	        </div>
	        <div class="row">
	        	<div class="col-md-12">
					<h5 class="font-blue-madison bold">Fee Structure</h5>
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
														{{number_format((int)$lateFeeAfterGracePeriod*(int)$hasLateFeeAmount[0]->PenaltyLateFeeAmount,2)}}
													</td>
												</tr>
												<tr class="bold">
													<td colspan="3">Total</td>
													<td>
														<?php $appliedService->EngineerPvtAmount=$lateFeeAmount;?>
														{{number_format($lateFeeAmount,2)}}
													</td>
												</tr>
												<tr>
													<td colspan="1">
														<div class="form-group pull-right">
															<label for="">
																Waiver Late Fee
																<input type="checkbox" name="WaiveOffLateFee" {{--@if($generalInformation[0]->WaiveOffLateFee == 1)checked="checked"@endif--}} value="1" id="waiver">
															</label>
														</div>
													</td>
													<td colspan="3">
														<div class="form-group">
															<input type="text" disabled class="form-control number input-sm input-medium" value="{{--@if($generalInformation[0]->WaiveOffLateFee == 1){{{$generalInformation[0]->NewLateFeeAmount}}}@endif--}}" name="NewLateFeeAmount" placeholder="New Late Fees Amount" {{--@if($generalInformation[0]->WaiveOffLateFee == 0)disabled="disabled"@endif--}}>
														</div>
													</td>
												</tr>
											</tbody>
										</table>
										@endif
									</td>
									<td class="text-right">
										@if(!empty($appliedService->EngineerPvtAmount))
										<?php $totalFeesApplicable+=$appliedService->EngineerPvtAmount; ?>
										{{number_format($appliedService->EngineerPvtAmount,2)}}
										@else
										-
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
									@if((int)$totalFeesApplicable!=0)
									{{number_format($totalFeesApplicable,2)}}
									@else 
									-
									@endif
								</td>
							</tr>
						</tbody>
					</table>
				</div>
	        </div>
			@foreach($engineerInformations as $engineerInformation)
			<div class="row">
				<div class="col-md-6">
					<h5 class="font-blue-madison bold">Registration Details</h5>
					<table class="table table-bordered table-striped table-condensed">
						<tbody>
							<tr>
								<td><strong>CDB No.</strong></td>
								<td>
									{{$engineerInformationsFinal[0]->CDBNo}}
								</td>
							</tr>
							<tr>
								<td><strong>Type of Engineer </strong></td>
								<td>
									@if($engineerInformation->EngineerType==$engineerInformationsFinal[0]->EngineerType)
										{{$engineerInformation->EngineerType}}
									@else
										<span class="font-red">{{$engineerInformation->EngineerType}}</span>
										<a href="#" class="tooltips" data-placement="top" data-original-title="{{$engineerInformationsFinal[0]->EngineerType}}"><i class="font-blue">[old]</i></a>
									@endif
								</td>
							</tr>
							<tr>
								<td><strong>Trade </strong></td>
								<td>
									@if($engineerInformation->Trade==$engineerInformationsFinal[0]->Trade)
										{{$engineerInformation->Trade}}
									@else
										<span class="font-red">{{$engineerInformation->Trade}}</span>
										<a href="#" class="tooltips" data-placement="top" data-original-title="{{$engineerInformationsFinal[0]->Trade}}"><i class="font-blue">[old]</i></a>
									@endif
								</td>
							</tr>
							<tr>
								<td><strong>Name</strong></td>
								<td>
									@if($engineerInformation->Salutation==$engineerInformationsFinal[0]->Salutation && $engineerInformation->Name==$engineerInformationsFinal[0]->Name)
										{{$engineerInformation->Salutation.' '.$engineerInformation->Name}}
									@else
										<span class="font-red">{{$engineerInformation->Salutation.' '.$engineerInformation->Name}}</span>
										<a href="#" class="tooltips" data-placement="top" data-original-title="{{$engineerInformationsFinal[0]->Salutation.' '.$engineerInformationsFinal[0]->Name}}"><i class="font-blue">[old]</i></a>
									@endif
								</td>
							</tr>
							<tr>
								<td><strong>CID No./Work Permit No.</strong></td>
								<td>
									@if($engineerInformation->CIDNo==$engineerInformationsFinal[0]->CIDNo)
										{{$engineerInformation->CIDNo}}
									@else
										<span class="font-red">{{$engineerInformation->CIDNo}}</span>
										<a href="#" class="tooltips" data-placement="top" data-original-title="{{$engineerInformationsFinal[0]->CIDNo}}"><i class="font-blue">[old]</i></a>
									@endif
								</td>
							</tr>
							<tr>
								<td><strong>Country</strong></td>
								<td>
									@if($engineerInformation->Country==$engineerInformationsFinal[0]->Country)
										{{$engineerInformation->Country}}
									@else
										<span class="font-red">{{$engineerInformation->Country}}</span>
										<a href="#" class="tooltips" data-placement="top" data-original-title="{{$engineerInformationsFinal[0]->Country}}"><i class="font-blue">[old]</i></a>
									@endif
								</td>
							</tr>
							<tr>
								<td><strong>Dzongkhag</strong></td>
								<td>
									@if($engineerInformation->Dzongkhag==$engineerInformationsFinal[0]->Dzongkhag)
										{{$engineerInformation->Dzongkhag}}
									@else
										<span class="font-red">{{$engineerInformation->Dzongkhag}}</span>
										<a href="#" class="tooltips" data-placement="top" data-original-title="{{$engineerInformationsFinal[0]->Dzongkhag}}"><i class="font-blue">[old]</i></a>
									@endif
								</td>
							</tr>
							<tr>
								<td><strong>Gewog</strong></td>
								<td>
									@if($engineerInformation->Gewog==$engineerInformationsFinal[0]->Gewog)
										{{$engineerInformation->Gewog}}
									@else
										<span class="font-red">{{$engineerInformation->Gewog}}</span>
										<a href="#" class="tooltips" data-placement="top" data-original-title="{{$engineerInformationsFinal[0]->Gewog}}"><i class="font-blue">[old]</i></a>
									@endif
								</td>
							</tr>
							<tr>
								<td><strong>Village</strong></td>
								<td>
									@if($engineerInformation->Village==$engineerInformationsFinal[0]->Village)
										{{$engineerInformation->Village}}
									@else
										<span class="font-red">{{$engineerInformation->Village}}</span>
										<a href="#" class="tooltips" data-placement="top" data-original-title="{{$engineerInformationsFinal[0]->Village}}"><i class="font-blue">[old]</i></a>
									@endif
								</td>
							</tr>
							<tr>
								<td><strong>Email</strong></td>
								<td>
									@if($engineerInformation->Email==$engineerInformationsFinal[0]->Email)
										{{$engineerInformation->Email}}
									@else
										<span class="font-red">{{$engineerInformation->Email}}</span>
										<a href="#" class="tooltips" data-placement="top" data-original-title="{{$engineerInformationsFinal[0]->Email}}"><i class="font-blue">[old]</i></a>
									@endif
								</td>
							</tr>
							<tr>
								<td><strong>Mobile No.</strong></td>
								<td>
									@if($engineerInformation->MobileNo==$engineerInformationsFinal[0]->MobileNo)
										{{$engineerInformation->MobileNo}}
									@else
										<span class="font-red">{{$engineerInformation->MobileNo}}</span>
										<a href="#" class="tooltips" data-placement="top" data-original-title="{{$engineerInformationsFinal[0]->MobileNo}}"><i class="font-blue">[old]</i></a>
									@endif
								</td>
							</tr>
							<tr>
								<td><strong>Employer Name</strong></td>
								<td>
									@if($engineerInformation->EmployerName==$engineerInformationsFinal[0]->EmployerName)
										{{$engineerInformation->EmployerName}}
									@else
										<span class="font-red">{{$engineerInformation->EmployerName}}</span>
										<a href="#" class="tooltips" data-placement="top" data-original-title="{{$engineerInformationsFinal[0]->EmployerName}}"><i class="font-blue">[old]</i></a>
									@endif
								</td>
							</tr>
							<tr>
								<td><strong>Employer Address</strong></td>
								<td>
									@if($engineerInformation->EmployerAddress==$engineerInformationsFinal[0]->EmployerAddress)
										{{$engineerInformation->EmployerAddress}}
									@else
										<span class="font-red">{{$engineerInformation->EmployerAddress}}</span>
										<a href="#" class="tooltips" data-placement="top" data-original-title="{{$engineerInformationsFinal[0]->EmployerAddress}}"><i class="font-blue">[old]</i></a>
									@endif
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="col-md-6">
					<h5 class="font-blue-madison bold">Professional Qualification</h5>
					<table class="table table-bordered table-striped table-condensed flip-content">
						<tbody>
							<tr>
								<td><strong>Qualification</strong></td>
								<td>
									@if($engineerInformation->Qualification==$engineerInformationsFinal[0]->Qualification)
										{{$engineerInformation->Qualification}}
									@else
										<span class="font-red">{{$engineerInformation->Qualification}}</span>
										<a href="#" class="tooltips" data-placement="top" data-original-title="{{$engineerInformationsFinal[0]->Qualification}}"><i class="font-blue">[old]</i></a>
									@endif
								</td>
							</tr>
							<tr>
								<td><strong>Year of Graduation</strong></td>
								<td>
									@if($engineerInformation->GraduationYear==$engineerInformationsFinal[0]->GraduationYear)
										{{$engineerInformation->GraduationYear}}
									@else
										<span class="font-red">{{$engineerInformation->GraduationYear}}</span>
										<a href="#" class="tooltips" data-placement="top" data-original-title="{{$engineerInformationsFinal[0]->GraduationYear}}"><i class="font-blue">[old]</i></a>
									@endif
								</td>
							</tr>
							<tr>
								<td><strong>Name of University</strong></td>
								<td>
									@if($engineerInformation->NameOfUniversity==$engineerInformationsFinal[0]->NameOfUniversity)
										{{$engineerInformation->NameOfUniversity}}
									@else
										<span class="font-red">{{$engineerInformation->NameOfUniversity}}</span>
										<a href="#" class="tooltips" data-placement="top" data-original-title="{{$engineerInformationsFinal[0]->NameOfUniversity}}"><i class="font-blue">[old]</i></a>
									@endif
								</td>
							</tr>
							<tr>
								<td><strong>University Country</strong></td>
								<td>
									@if($engineerInformation->UniversityCountry==$engineerInformationsFinal[0]->UniversityCountry)
										{{$engineerInformation->UniversityCountry}}
									@else
										<span class="font-red">{{$engineerInformation->UniversityCountry}}</span>
										<a href="#" class="tooltips" data-placement="top" data-original-title="{{$engineerInformationsFinal[0]->UniversityCountry}}"><i class="font-blue">[old]</i></a>
									@endif
								</td>
							</tr>
						</tbody>	
					</table>
				</div>
				@endforeach
				<div class="col-md-6">
					<h5 class="font-blue-madison bold">Existing Attachments</h5>
					<table class="table table-bordered table-striped table-condensed">
						<thead>
							<tr>
								<th>Document Name</th>
							</tr>
						</thead>
						<tbody>
							@foreach($engineerAttachmentsFinal as $engineerAttachmentFinal)
							<tr>
								<td>
									<a href="{{URL::to($engineerAttachmentFinal->DocumentPath)}}" target="_blank">{{$engineerAttachmentFinal->DocumentName}}</a>
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
					<h5 class="font-blue-madison bold">New Attachments</h5>
					<table class="table table-bordered table-striped table-condensed">
						<thead>
							<tr>
								<th>Document Name</th>
							</tr>
						</thead>
						<tbody>
							@forelse($engineerAttachments as $engineerAttachment)
							<tr>
								<td>
									<a href="{{URL::to($engineerAttachment->DocumentPath)}}" target="_blank">{{$engineerAttachment->DocumentName}}</a>
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
                   <a href="{{URL::to('engineer/editregistrationinfo/'.$engineerId.'?redirectUrl=engineer/approveserviceapplicationprocess')}}" class="btn blue-madison editaction"><i class="fa fa-edit"></i> Edit</a>
                </div>
            </div>
		</div>
	</div>
</div>
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-cogs"></i>
			<span class="caption-subject">Application</span>
		</div>
	</div>
	<div class="portlet-body form">
		{{ Form::open(array('url' => $modelPost,'role'=>'form','id'=>'approveregistrationengineer'))}}
		<div class="form-body">
			<div class="row">
				<div class="col-md-4">
					<div class="form-group">
						<label class="control-label">Approved Date</label>
						<div class="input-icon right">
							<i class="fa fa-calendar"></i>
							<input type="text" name="RegistrationApprovedDate" class="form-control required" placeholder="" value="{{date('d-m-Y G:i:s')}}" readonly="readonly"/>
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label class="control-label">Expiry Date</label>
						<div class="input-icon right">
							<i class="fa fa-calendar"></i>
							<input type="text" name="RegistrationExpiryDate" class="form-control required required" placeholder="" readonly="readonly" value="{{registrationExpiryDateCalculator($validityYears)}}"/>
						</div>
					</div>
				</div>
			</div>
			<div class="note note-success">
				<h4 class="">Verified by {{$engineerInformations[0]->Verifier}} on {{convertDateToClientFormat($engineerInformations[0]->VerifiedDate)}}<small><i class="font-red">{{showDateTimeDuration($engineerInformations[0]->VerifiedDate)}}</i></small></h4>
				<p>
					{{$engineerInformations[0]->RemarksByVerifier}}
				</p>
			</div>
			<div class="form-group">
				<input type="hidden" name="SysLockedByUserId" value="" />
				<input type="hidden" name="RedirectRoute" value="verifyserviceapplicationlist">
				<input type="hidden" name="EngineerReference" value="{{$engineerId}}" />
				<input type="hidden" name="SysApproverUserId" value="{{Auth::user()->Id}}" />
				@if($hasFee)
					<input type="hidden" name="CmnApplicationRegistrationStatusId" value="{{CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVEDFORPAYMENT}}">
				@else
					<input type="hidden" name="CmnApplicationRegistrationStatusId" value="{{CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED}}">
				@endif
				<label>Remarks</label>
				<textarea name="RemarksByApprover" class="form-control" rows="3"></textarea>
			</div>
		</div>
		<div class="form-actions">
			<div class="btn-set">
				<a href="#approve" data-toggle="modal" class="btn green">@if($hasFee && $engineerServiceSectorType==CONST_CMN_SERVICESECTOR_PVT){{'Approve'}}@else{{'Approve & Update Certificate'}}@endif</a>
				<a href="#reject" data-toggle="modal" class="btn red">Reject</a>
				<a href="#sendback" data-toggle="modal" class="btn purple">Send back to Verifier</a>
			</div>
			<button type="button" class="btn blue-ebonyclay pull-right">Next</button>
		</div>
		{{Form::close()}}
	</div>
</div>
@stop