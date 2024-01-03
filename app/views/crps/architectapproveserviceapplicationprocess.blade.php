@extends('master')
@section('pagescripts')
{{ HTML::script('assets/global/scripts/architect.js') }}
@stop
@section('lastcdbno')
<div class="page-toolbar">
	<div id="dashboard-report-range" class="pull-right tooltips btn btn-fit-height grey-cascade" data-placement="top" data-original-title="Last used Architect No.">
		<span class="thin visible-lg-inline-block">Last used Ar No.: <span class="bold">{{lastUsedArchitectNo();}}</span></span>
	</div>
</div>
@stop
@section('content')
<div id="sendback" class="modal fade" role="dialog" aria-labelledby="sendback" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			{{Form::open(array('url'=>'sendapplicationback','role'=>'form'))}}
			<input type="hidden" name="Id" value="{{$architectInformations[0]->Id}}" />
			<input type="hidden" name="Model" value="ArchitectModel"/>
			<input type="hidden" name="Status" value="{{CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW}}"/>
			<input type="hidden" name="RedirectUrl" value="architect/approveserviceapplicationlist"/>
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
				<button id="approvearchitectregistration" type="button" class="btn green">Approve</button>
				<button class="btn red" data-dismiss="modal" aria-hidden="true">Cancel</button>
			</div>
		</div>
	</div>
</div>
<div id="reject" class="modal fade" role="dialog" aria-labelledby="reject" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			{{ Form::open(array('url' => 'architect/mrejectregistration','role'=>'form'))}}
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h3 class="modal-title font-green-seagreen">Reject Registration</h3>
			</div>
			<div class="modal-body">
				<input type="hidden" name="RedirectRoute" value="approveregistration">
				<p class="bold">Are you sure you want to reject this application?</p>
				<input type="hidden" name="ArchitectReference" value="{{$architectInformations[0]->Id}}" />
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
			<span class="caption-subject">Approve Architect Registration</span>
		</div>
		<div class="actions">
			<a href="{{URL::to('architect/approveserviceapplicationlist')}}" class="btn btn-sm btn-default">Back to List</a>
		</div>
	</div>
	<div class="portlet-body form">
		{{ Form::open(array('url' => $modelPost,'role'=>'form','id'=>'approveregistrationarchitect'))}}
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
								@if($architectServiceSectorType==CONST_CMN_SERVICESECTOR_GOVT)
									<td>
										{{{$appliedService->ServiceName}}}
									</td>
									<td>-</td>
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
														{{$hasLateFeeAmount[0]->PenaltyNoOfDays-1}}<br />
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
												<tr>
													<td colspan="1">
														<div class="form-group pull-right">
															<label for="">
																Waiver Late Fee
																<input type="checkbox" name="WaiveOffLateFee" @if($architectInformations[0]->WaiveOffLateFee == 1)checked="checked"@endif value="1" id="waiver">
															</label>
														</div>
													</td>
													<td colspan="3">
														<div class="form-group">
															<input type="text" class="form-control number input-sm input-medium" name="NewLateFeeAmount" placeholder="New Late Fees Amount" disabled>
														</div>
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
										{{{number_format($appliedService->ArchitectPvtAmount,2)}}}
									</td>
								@endif
							</tr>
							@endforeach
							<tr>
								<td colspan="2" class="bold text-right">
									Total
								</td>
								<td class="text-right bold">
									{{{number_format($totalFeesApplicable,2)}}}
								</td>
							</tr>
						</tbody>
					</table>
				</div>
	        </div>
			@foreach($architectInformations as $architectInformation)
			<div class="row">
				<div class="col-md-6">
					<h5 class="font-blue-madison bold">Registration Details</h5>
					<table class="table table-bordered table-striped table-condensed">
						<tbody>
							<tr>
								<td><strong>Type of Architect </strong></td>
								<td>
									@if($architectInformation->ArchitectType==$architectInformationsFinal[0]->ArchitectType)
										{{{$architectInformation->ArchitectType}}}
									@else
										<span class="font-red">{{{$architectInformation->ArchitectType}}}</span>
										<a href="#" class="tooltips" data-placement="top" data-original-title="{{$architectInformationsFinal[0]->ArchitectType}}"><i class="font-blue">[old]</i></a>
									@endif
								</td>
							</tr>
							<tr>
								<td><strong>Ar No.</strong></td>
								<td>
									{{{$architectInformationsFinal[0]->ARNo}}}
								</td>
							</tr>
							<tr>
								<td><strong>Name</strong></td>
								<td>
									@if($architectInformation->Salutation==$architectInformationsFinal[0]->Salutation && $architectInformation->Name==$architectInformationsFinal[0]->Name)
										{{{$architectInformation->Salutation.' '.$architectInformation->Name}}}
									@else
										<span class="font-red">{{{$architectInformation->Salutation.' '.$architectInformation->Name}}}</span>
										<a href="#" class="tooltips" data-placement="top" data-original-title="{{{$architectInformationsFinal[0]->Salutation.' '.$architectInformationsFinal[0]->Name}}}"><i class="font-blue">[old]</i></a>
									@endif
								</td>
							</tr>
							<tr>
								<td><strong>CID No./Work Permit No.</strong></td>
								<td>
									@if($architectInformation->CIDNo==$architectInformationsFinal[0]->CIDNo)
										{{{$architectInformation->CIDNo}}}
									@else
										<span class="font-red">{{{$architectInformation->CIDNo}}}</span>
										<a href="#" class="tooltips" data-placement="top" data-original-title="{{{$architectInformationsFinal[0]->CIDNo}}}"><i class="font-blue">[old]</i></a>
									@endif
								</td>
							</tr>
							<tr>
								<td><strong>Country</strong></td>
								<td>
									@if($architectInformation->Country==$architectInformationsFinal[0]->Country)
										{{{$architectInformation->Country}}}
									@else
										<span class="font-red">{{{$architectInformation->Country}}}</span>
										<a href="#" class="tooltips" data-placement="top" data-original-title="{{{$architectInformationsFinal[0]->Country}}}"><i class="font-blue">[old]</i></a>
									@endif
								</td>
							</tr>
							<tr>
								<td><strong>Dzongkhag</strong></td>
								<td>
									@if($architectInformation->Dzongkhag==$architectInformationsFinal[0]->Dzongkhag)
										{{{$architectInformation->Dzongkhag}}}
									@else
										<span class="font-red">{{{$architectInformation->Dzongkhag}}}</span>
										<a href="#" class="tooltips" data-placement="top" data-original-title="{{{$architectInformationsFinal[0]->Dzongkhag}}}"><i class="font-blue">[old]</i></a>
									@endif
								</td>
							</tr>
							<tr>
								<td><strong>Gewog</strong></td>
								<td>
									@if($architectInformation->Gewog==$architectInformationsFinal[0]->Gewog)
										{{{$architectInformation->Gewog}}}
									@else
										<span class="font-red">{{{$architectInformation->Gewog}}}</span>
										<a href="#" class="tooltips" data-placement="top" data-original-title="{{{$architectInformationsFinal[0]->Gewog}}}"><i class="font-blue">[old]</i></a>
									@endif
								</td>
							</tr>
							<tr>
								<td><strong>Village</strong></td>
								<td>
									@if($architectInformation->Village==$architectInformationsFinal[0]->Village)
										{{{$architectInformation->Village}}}
									@else
										<span class="font-red">{{{$architectInformation->Village}}}</span>
										<a href="#" class="tooltips" data-placement="top" data-original-title="{{{$architectInformationsFinal[0]->Village}}}"><i class="font-blue">[old]</i></a>
									@endif
								</td>
							</tr>
							<tr>
								<td><strong>Email</strong></td>
								<td>
									@if($architectInformation->Email==$architectInformationsFinal[0]->Email)
										{{{$architectInformation->Email}}}
									@else
										<span class="font-red">{{{$architectInformation->Email}}}</span>
										<a href="#" class="tooltips" data-placement="top" data-original-title="{{{$architectInformationsFinal[0]->Email}}}"><i class="font-blue">[old]</i></a>
									@endif
								</td>
							</tr>
							<tr>
								<td><strong>Mobile No.</strong></td>
								<td>
									@if($architectInformation->MobileNo==$architectInformationsFinal[0]->MobileNo)
										{{{$architectInformation->MobileNo}}}
									@else
										<span class="font-red">{{{$architectInformation->MobileNo}}}</span>
										<a href="#" class="tooltips" data-placement="top" data-original-title="{{{$architectInformationsFinal[0]->MobileNo}}}"><i class="font-blue">[old]</i></a>
									@endif
								</td>
							</tr>
							<tr>
								<td><strong>Employer Name</strong></td>
								<td>
									@if($architectInformation->EmployerName==$architectInformationsFinal[0]->EmployerName)
										{{{$architectInformation->EmployerName}}}
									@else
										<span class="font-red">{{{$architectInformation->EmployerName}}}</span>
										<a href="#" class="tooltips" data-placement="top" data-original-title="{{{$architectInformationsFinal[0]->EmployerName}}}"><i class="font-blue">[old]</i></a>
									@endif
								</td>
							</tr>
							<tr>
								<td><strong>Employer Address</strong></td>
								<td>
									@if($architectInformation->EmployerAddress==$architectInformationsFinal[0]->EmployerAddress)
										{{{$architectInformation->EmployerAddress}}}
									@else
										<span class="font-red">{{{$architectInformation->EmployerAddress}}}</span>
										<a href="#" class="tooltips" data-placement="top" data-original-title="{{{$architectInformationsFinal[0]->EmployerAddress}}}"><i class="font-blue">[old]</i></a>
									@endif
								</td>
							</tr>
							<tr>
								<td><strong>Previous Validity</strong></td>
								<td>
									{{convertDateToClientFormat($architectInformationsFinal[0]->RegistrationApprovedDate)}} to {{convertDateToClientFormat($architectInformationsFinal[0]->RegistrationExpiryDate)}}
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
									@if($architectInformation->Qualification==$architectInformationsFinal[0]->Qualification)
										{{{$architectInformation->Qualification}}}
									@else
										<span class="font-red">{{{$architectInformation->Qualification}}}</span>
										<a href="#" class="tooltips" data-placement="top" data-original-title="{{{$architectInformationsFinal[0]->Qualification}}}"><i class="font-blue">[old]</i></a>
									@endif
								</td>
							</tr>
							<tr>
								<td><strong>Year of Graduation</strong></td>
								<td>
									@if($architectInformation->GraduationYear==$architectInformationsFinal[0]->GraduationYear)
										{{{$architectInformation->GraduationYear}}}
									@else
										<span class="font-red">{{{$architectInformation->GraduationYear}}}</span>
										<a href="#" class="tooltips" data-placement="top" data-original-title="{{{$architectInformationsFinal[0]->GraduationYear}}}"><i class="font-blue">[old]</i></a>
									@endif
								</td>
							</tr>
							<tr>
								<td><strong>Name of University</strong></td>
								<td>
									@if($architectInformation->NameOfUniversity==$architectInformationsFinal[0]->NameOfUniversity)
										{{{$architectInformation->NameOfUniversity}}}
									@else
										<span class="font-red">{{{$architectInformation->NameOfUniversity}}}</span>
										<a href="#" class="tooltips" data-placement="top" data-original-title="{{{$architectInformationsFinal[0]->NameOfUniversity}}}"><i class="font-blue">[old]</i></a>
									@endif
								</td>
							</tr>
							<tr>
								<td><strong>University (Location)</strong></td>
								<td>
									@if($architectInformation->UniversityCountry==$architectInformationsFinal[0]->UniversityCountry)
										{{{$architectInformation->UniversityCountry}}}
									@else
										<span class="font-red">{{{$architectInformation->UniversityCountry}}}</span>
										<a href="#" class="tooltips" data-placement="top" data-original-title="{{$architectInformationsFinal[0]->UniversityCountry}}"><i class="font-blue">[old]</i></a>
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
							@foreach($architectAttachmentsFinal as $architectAttachmentFinal)
							<tr>
								<td>
									<a href="{{URL::to($architectAttachmentFinal->DocumentPath)}}" target="_blank">{{$architectAttachmentFinal->DocumentName}}</a>
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
							@forelse($architectAttachments as $architectAttachment)
							<tr>
								<td>
									<a href="{{URL::to($architectAttachment->DocumentPath)}}" target="_blank">{{$architectAttachment->DocumentName}}</a>
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
                   <a href="{{URL::to('architect/editregistrationinfo/'.$architectId.'?redirectUrl=architect/verifyregistrationprocess')}}" class="btn blue-madison editaction"><i class="fa fa-edit"></i> Edit</a>
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
				{{ Form::open(array('url' => $modelPost,'role'=>'form','id'=>'approveregistrationarchitect'))}}
				<div class="form-body">
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label class="control-label">Approved Date</label>
								<div class="input-icon right">
									<i class="fa fa-calendar"></i>
									<input type="text" name="RegistrationApprovedDate" class="form-control required" placeholder="" value="{{date('d-m-Y')}}" readonly="readonly"/>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label class="control-label">Expiry Date</label>
								<div class="input-icon right">
									<i class="fa fa-calendar"></i>
									<input type="text" name="RegistrationExpiryDate" class="form-control datepicker required" placeholder="" readonly="readonly" value="{{registrationExpiryDateCalculator($validityYears)}}"/>
								</div>
							</div>
						</div>
					</div>
					@include('crps.applicationhistory')
					<div class="form-group">
						<input type="hidden" name="SysLockedByUserId" value="" />
						<input type="hidden" name="RedirectRoute" value="verifyserviceapplicationlist">
						<input type="hidden" name="ArchitectReference" value="{{$architectId}}" />
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
						<a href="#approve" data-toggle="modal" class="btn green">@if($hasFee && $architectServiceSectorType==CONST_CMN_SERVICESECTOR_PVT){{'Approve for Payment'}}@else{{'Approve & Update Certificate'}}@endif</a>
						<a href="#sendback" data-toggle="modal" class="btn purple">Send back to Verifier</a>
						<a href="#reject" data-toggle="modal" class="btn red">Reject</a>
					</div>
					<button type="button" class="btn blue-ebonyclay pull-right">Next</button>
				</div>
				{{Form::close()}}
			</div>
		</div>
	</div>
</div>
@stop