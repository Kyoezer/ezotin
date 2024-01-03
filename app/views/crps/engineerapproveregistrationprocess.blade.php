@extends('master')
@section('pagescripts')
{{ HTML::script('assets/global/scripts/engineer.js') }}
@stop
@section('lastcdbno')
<div class="page-toolbar">
	<div id="dashboard-report-range" class="pull-right tooltips btn btn-fit-height grey-cascade" data-placement="top" data-original-title="Last used Engineer CDB No.">
		<span class="thin visible-lg-inline-block">Last used Engineer No.: <span class="bold">{{lastUsedEngineerNo();}}</span></span>
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
			<input type="hidden" name="RedirectUrl" value="engineer/approveregistration"/>
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
	<div id="hrcheckmodal" class="modal fade in" role="dialog" aria-labelledby="modalmessagebox" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
					<h3 id="modalmessageheader" class="modal-title font-red-intense">HR Check</h3>
				</div>
				<div class="modal-body">
				</div>
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
<div id="rejectregistration" class="modal fade" role="dialog" aria-labelledby="rejectregistration" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			{{ Form::open(array('url' => 'engineer/mrejectregistration','role'=>'form','id'=>'rejectregistrationengineer'))}}
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h3 class="modal-title font-red">Reject Registration</h3>
			</div>
			<div class="modal-body">
				<input type="hidden" name="RedirectRoute" value="verifyregistration">
				<p class="bold">Are you sure you want to reject this application?</p>
				<input type="hidden" name="EngineerReference" value="{{$engineerInformations[0]->Id}}" />
				<input type="hidden" name="RejectedDate" value="{{date('Y-m-d G:i:s')}}" />
				<div class="form-group">
					<label>Remarks</label>
					<textarea name="RemarksByRejector" class="form-control remarksbyrejector" rows="3"></textarea>
				</div>
			</div>
			<div class="modal-footer">
				<button id="rejectengineerregistration" type="submit" class="btn green">Reject</button>
				<button class="btn red" data-dismiss="modal" aria-hidden="true">Cancel</button>
			</div>
			{{Form::close()}}
		</div>
	</div>
</div>
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-gift"></i>
			<span class="caption-subject">Approve Engineer Registration</span>
		</div>
		<div class="actions">
			<a href="{{URL::to('engineer/approveregistration')}}" class="btn btn-default btn-sm">Back to List</a>
		</div>
	</div>
	<div class="portlet-body form">
		<div class="form-body">
			<div class="note note-info">
				<p>If the engineer is of type Govertment, he/she need not have to pay the registration fees. His/Her application will be dirctly approved and certificate will be generated. Incase of private architects the application will be forwarded for payment. The certificate will be generated and activated when the registration fees are received by CDB.</p>
			</div>
			<div class="row">
				<div class="col-md-6">
					<h5 class="font-blue-madison bold">Fee Structure</h5>
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
							@foreach($feeDetails as $feeDetail)
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
				</div>	
			</div>
			<div class="row">
				@foreach($engineerInformations as $engineerInformation)
				<div class="col-md-6">
					<h5 class="font-blue-madison bold">Registration Details</h5>
					<table class="table table-bordered table-striped table-condensed">
						<tbody>
							<tr>
								<td><strong>Type of Engineer </strong></td>
								<td>{{$engineerInformation->EngineerType}}</td>
							</tr>
							<tr>
								<td><strong>CDB No.</strong></td>
								<td>{{$engineerInformation->CDBNo}}</td>
							</tr>
							<tr>
								<td><strong>Trade</strong></td>
								<td>{{$engineerInformation->Trade}}</td>
							</tr>
							<tr>
								<td><strong>Name</strong></td>
								<td>{{$engineerInformation->Salutation.' '.$engineerInformation->Name}}</td>
							</tr>
							<tr>
								<td><strong>CID No./Work Permit No.</strong></td>
								<td>{{$engineerInformation->CIDNo}}&nbsp;&nbsp;&nbsp;<button type="button" data-cid="{{trim($engineerInformation->CIDNo)}}" class="btn blue btn-sm btn-small checkhrdbandwebservice">Check</button></td>
							</tr>
							<tr>
								<td><strong>Country</strong></td>
								<td>{{$engineerInformation->Country}}</td>
							</tr>
							<tr>
								<td><strong>Dzongkhag</strong></td>
								<td>{{$engineerInformation->Dzongkhag}}</td>
							</tr>
							<tr>
								<td><strong>Gewog</strong></td>
								<td>{{$engineerInformation->Gewog}}</td>
							</tr>
							<tr>
								<td><strong>Village</strong></td>
								<td>{{$engineerInformation->Village}}</td>
							</tr>
							<tr>
								<td><strong>Email</strong></td>
								<td>{{$engineerInformation->Email}}</td>
							</tr>
							<tr>
								<td><strong>Mobile No.</strong></td>
								<td>{{$engineerInformation->MobileNo}}</td>
							</tr>
							<tr>
								<td><strong>Employer Name</strong></td>
								<td>{{'M/s.'.$engineerInformation->EmployerName}}</td>
							</tr>
							<tr>
								<td><strong>Employer Address</strong></td>
								<td>{{$engineerInformation->EmployerAddress}}</td>
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
								<td>{{$engineerInformation->Qualification}}</td>
							</tr>
							<tr>
								<td><strong>Year of Graduation</strong></td>
								<td>{{$engineerInformation->GraduationYear}}</td>
							</tr>
							<tr>
								<td><strong>Name of University</strong></td>
								<td>{{$engineerInformation->NameOfUniversity}}</td>
							</tr>
							<tr>
								<td><strong>University Country</strong></td>
								<td>{{$engineerInformation->UniversityCountry}}</td>
							</tr>
						</tbody>	
					</table>
				</div>
				@endforeach
				<div class="col-md-6">
					<h5 class="font-blue-madison bold">Attachments</h5>
					<table class="table table-bordered table-striped table-condensed">
						<thead>
							<tr>
								<th>Document Name</th>
							</tr>
						</thead>
						<tbody>
							@foreach($engineerAttachments as $engineerAttachment)
							<tr>
								<td>
									<a href="{{URL::to($engineerAttachment->DocumentPath)}}" target="_blank">{{$engineerAttachment->DocumentName}}</a>
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
			<div class="row">
                <div class="col-md-12">
                	<a href="{{URL::to('engineer/editregistrationinfo/'.$engineerId.'?redirectUrl=engineer/approveregistrationprocess')}}" class="btn blue-madison editaction"><i class="fa fa-edit"></i> Edit</a>
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
		<input type="hidden" name="SysLockedByUserId" value="" />
		<input type="hidden" name="EngineerReference" value="{{$engineerId}}">
		<input type="hidden" name="SysApproverUserId" value="{{Auth::user()->Id}}" />
		@if($engineerServiceSectorType==CONST_CMN_SERVICESECTOR_GOVT)
		<input type="hidden" name="CmnApplicationRegistrationStatusId" value="{{CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED}}" />
		@else
		<input type="hidden" name="CmnApplicationRegistrationStatusId" value="{{CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVEDFORPAYMENT}}" />
		@endif
		<div class="form-body">
			<div class="note note-success">
				<h4 class="">Verified by {{$engineerInformations[0]->Verifier}} on {{convertDateToClientFormat($engineerInformations[0]->VerifiedDate)}}<small><i class="font-red">{{showDateTimeDuration($engineerInformations[0]->VerifiedDate)}}</i></small></h4>
				<p>
					{{$engineerInformations[0]->RemarksByVerifier}}
				</p>
			</div>
			<div class="form-group">
				<label>Remarks</label>
				<textarea name="RemarksByApprover" class="form-control" rows="3"></textarea>
			</div>
		</div>
		<div class="form-actions">
			<div class="btn-set">
				<a href="#approve" data-toggle="modal" class="btn green">
					@if($engineerServiceSectorType==CONST_CMN_SERVICESECTOR_GOVT)
						{{'Approve & Generate Certificate'}}
					@else
						{{'Approve for Payment'}}
					@endif
				</a>
				<a href="#rejectregistration" data-toggle="modal" class="btn red">Reject</a>
				<a href="#sendback" data-toggle="modal" class="btn purple">Send back to Verifier</a>
			</div>
			<button type="button" class="btn blue-ebonyclay pull-right">Next</button>
		</div>
		{{Form::close()}}
	</div>
</div>
@stop