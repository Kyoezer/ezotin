@extends('master')
@section('pagescripts')
{{ HTML::script('assets/global/scripts/architect.js') }}
@stop
@section('lastcdbno')
<div class="page-toolbar">
	<div id="dashboard-report-range" class="pull-right tooltips btn btn-fit-height grey-cascade" data-placement="top" data-original-title="Last used Architect No.">
		<span class="thin visible-lg-inline-block">Last used Ar No.: <span class="bold">{{lastUsedArchitectNo($architectInformations[0]->Country,$architectServiceSectorType);}}</span></span>
	</div>
</div>
@stop
@section('content')
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
				<button id="verifyarchitectregistration" type="button" class="btn green">Verify</button>
				<button class="btn red" data-dismiss="modal" aria-hidden="true">Cancel</button>
			</div>
		</div>
	</div>
</div>
<div id="rejectregistration" class="modal fade" role="dialog" aria-labelledby="rejectregistration" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			{{ Form::open(array('url' => 'architect/mrejectregistration','role'=>'form','id'=>'rejectregistrationarchitect'))}}
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h3 class="modal-title font-red">Reject Registration</h3>
			</div>
			<div class="modal-body">
				<input type="hidden" name="RedirectRoute" value="verifyregistration">
				<p class="bold">Are you sure you want to reject this application?</p>
				<input type="hidden" name="ArchitectReference" value="{{$architectInformations[0]->Id}}" />
				<input type="hidden" name="RejectedDate" value="{{date('Y-m-d G:i:s')}}" />
				<div class="form-group">
					<label>Remarks</label>
					<textarea name="RemarksByRejector" class="form-control remarksbyrejector" rows="3"></textarea>
				</div>
			</div>
			<div class="modal-footer">
				<button id="rejectarchitectregistration" type="submit" class="btn green">Reject</button>
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
			<span class="caption-subject">Verify Architect Registration</span>
		</div>
		<div class="actions">
			<a href="{{URL::to('architect/verifyregistration')}}" class="btn btn-default btn-sm">Back to List</a>
		</div>
	</div>
	<div class="portlet-body form">
		<div class="form-body">
			<div class="note note-info">
				<p>If the architect is a Govertment architect, he/she doesnot have to pay the registration fees. The application will be directly approved and certificate will be generated. Incase of private architects the application will be forwarded for payment. The certificate will be generated and activated when the registration fees is received by CDB.</p>
			</div>
			<div class="row">
				<div class="col-md-6">
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
								@if($architectServiceSectorType==CONST_CMN_SERVICESECTOR_GOVT)
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
			@foreach($architectInformations as $architectInformation)
			<div class="row">
				<div class="col-md-6">
					<h5 class="font-blue-madison bold">Registration Details</h5>
					<table class="table table-bordered table-striped table-condensed">
						<tbody>
							<tr>
								<td><strong>Type of Architect </strong></td>
								<td>{{$architectInformation->ArchitectType}}</td>
							</tr>
							<tr>
								<td><strong>AR No.</strong></td>
								<td>{{$architectInformation->ARNo}}</td>
							</tr>

							<tr>
								<td><strong>Name</strong></td>
								<td>{{$architectInformation->Salutation.' '.$architectInformation->Name}}</td>
							</tr>
							<tr>
								<td><strong>CID No./Work Permit No.</strong></td>
								<td>{{$architectInformation->CIDNo}}&nbsp;&nbsp;&nbsp;<button type="button" data-cid="{{trim($architectInformation->CIDNo)}}" class="btn blue btn-sm btn-small checkhrdbandwebservice">Check</button></td>
							</tr>
							<tr>
								<td><strong>Country</strong></td>
								<td>{{$architectInformation->Country}}</td>
							</tr>
							<tr>
								<td><strong>Dzongkhag</strong></td>
								<td>{{$architectInformation->Dzongkhag}}</td>
							</tr>
							<tr>
								<td><strong>Gewog</strong></td>
								<td>{{$architectInformation->Gewog}}</td>
							</tr>
							<tr>
								<td><strong>Village</strong></td>
								<td>{{$architectInformation->Village}}</td>
							</tr>
							<tr>
								<td><strong>Email</strong></td>
								<td>{{$architectInformation->Email}}</td>
							</tr>
							<tr>
								<td><strong>Mobile No.</strong></td>
								<td>{{$architectInformation->MobileNo}}</td>
							</tr>
							<tr>
								<td><strong>Employer Name</strong></td>
								<td>{{'M/s.'.$architectInformation->EmployerName}}</td>
							</tr>
							<tr>
								<td><strong>Employer Address</strong></td>
								<td>{{$architectInformation->EmployerAddress}}</td>
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
								<td>{{$architectInformation->Qualification}}</td>
							</tr>
							<tr>
								<td><strong>Year of Graduation</strong></td>
								<td>{{$architectInformation->GraduationYear}}</td>
							</tr>
							<tr>
								<td><strong>Name of University</strong></td>
								<td>{{$architectInformation->NameOfUniversity}}</td>
							</tr>
							<tr>
								<td><strong>University Country</strong></td>
								<td>{{$architectInformation->UniversityCountry}}</td>
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
							@foreach($architectAttachments as $architectAttachment)
							<tr>
								<td>
									<a href="{{URL::to($architectAttachment->DocumentPath)}}" target="_blank">{{$architectAttachment->DocumentName}}</a>
								</td>
							</tr>
							@endforeach
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
		@include('crps.applicationhistory')
		{{ Form::open(array('url' => $modelPost,'role'=>'form','id'=>'verifyregistrationarchitect'))}}
		<input type="hidden" name="VerifiedDate" value="{{date('Y-m-d G:i:s')}}" />
		<input type="hidden" name="SysLockedByUserId" value="" />
		<input type="hidden" name="ArchitectReference" value="{{$architectId}}">
		<input type="hidden" name="CmnApplicationRegistrationStatusId" value="{{CONST_CMN_APPLICATIONREGISTRATIONSTATUS_VERIFIED}}">
		<input type="hidden" name="SysVerifierUserId" value="{{Auth::user()->Id}}" />
		<div class="form-body">
			<div class="form-group">
				<label>Remarks</label>
				<textarea name="RemarksByVerifier" class="form-control" rows="3"></textarea>
			</div>
		</div>
		<div class="form-actions">
			<div class="btn-set">
				<a href="#verify" data-toggle="modal" class="btn green">Verify</a>
				<a href="#rejectregistration" data-toggle="modal" class="btn red">Reject</a>
			</div>
			<button type="button" class="btn blue-ebonyclay pull-right">Next</button>
		</div>
		{{Form::close()}}
	</div>
</div>
@stop