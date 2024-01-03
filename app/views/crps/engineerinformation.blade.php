@extends('master')
@section('pagescripts')
{{ HTML::script('assets/global/scripts/engineer.js') }}
@stop
@section('content')
<div id="sendback" class="modal fade" role="dialog" aria-labelledby="sendback" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			{{Form::open(array('url'=>'sendapplicationback','role'=>'form'))}}
			<input type="hidden" name="Id" value="{{$engineerInformations[0]->Id}}" />
			<input type="hidden" name="Model" value="EngineerModel"/>
			<input type="hidden" name="Status" value="{{CONST_CMN_APPLICATIONREGISTRATIONSTATUS_VERIFIED}}"/>
			<input type="hidden" name="RedirectUrl" value="engineer/approvefeepayment"/>
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
<div class="portlet light bordered" id="form_wizard_1">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-gift"></i>
			<span class="caption-subject">Registration Information</span>
		</div>
		@if((int)$userEngineer==1)
		<div class="actions">
			<a href="{{URL::to('engineer/viewprintdetails/'.$engineerId)}}" class="btn btn-small blue-madison" target="_blank"><i class="fa fa-print"></i> Print Information</a>
			<a href="{{URL::to('engineer/certificate/'.$engineerId)}}" class="btn btn-small green-seagreen" target="_blank"><i class="fa fa-print"></i> Print Certificate</a>
		</div>
		@endif
	</div>
	<div class="row form-froup">
                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                    <img src='https://www.citizenservices.gov.bt/BtImgWS/ImageServlet?type=PH&cidNo=${App_Details.cidNo}'  width='200px'  height='200px' class='pull-right'/>
                </div>
                <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                    <div class="table-responsive">
					@foreach($engineerInformations as $engineerInformation)
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
                                                <td>{{$engineerInformation->Salutation.' '.$engineerInformation->Name}}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>CID Number: </strong></td>
                                                <td>{{$engineerInformation->CIDNo}}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Contact Number: </strong></td>
                                                <td>{{$engineerInformation->MobileNo}}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Email Address: </strong></td>
                                                <td>{{$engineerInformation->Email}}</td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td>
                                        <table class="table table-condensed">
                                            <tr>
                                                <td><strong>Village: </strong></td>
                                                <td>{{$engineerInformation->Village}}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Gewog: </strong></td>
                                                <td>{{$engineerInformation->Gewog}}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Dzongkhag: </strong></td>
                                                <td>{{$engineerInformation->Dzongkhag}}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Country: </strong></td>
                                                <td>{{$engineerInformation->Country}}</td>
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
                                                <td>{{$engineerInformation->Qualification}}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Year of Graduation: </strong></td>
                                                <td>{{$engineerInformation->GraduationYear}}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Country of Study: </strong></td>
                                                <td>{{$engineerInformation->UniversityCountry}}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>University Name: </strong></td>
                                                <td>{{$engineerInformation->NameOfUniversity}}</td>
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
                                                <td><strong>CDB Number: </strong></td>
                                                <td>{{$engineerInformation->CDBNo}}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Type: </strong></td>
                                                <td>{{$engineerInformation->EngineerType}}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Trade: </strong></td>
                                                <td>{{$engineerInformation->Trade}}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Registration Expiry Date</strong></td>
												<td>
                                                @if($engineerInformation->RegistrationExpiryDate<=date('Y-m-d G:i:s'))
												<p class="font-red bold warning">	{{convertDateToClientFormat($engineerInformation->RegistrationExpiryDate)}} </p>
												@else
												{{convertDateToClientFormat($engineerInformation->RegistrationExpiryDate)}}
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
                                            @if($engineerInformation->RegistrationExpiryDate<=date('Y-m-d G:i:s'))

						<tr>
							<td><strong>Status</strong></td>
							<td class="font-red bold warning">Expired</td>
						</tr>
											
						@else
						<tr>
						<td><strong>Status</strong></td>
						<td>{{$engineerInformation->Status}}</td>
						</tr>		
						@endif                                       
					 </table>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
				@endforeach
				<div class="col-md-6 table-responsive">
							<table class="table">
                                <tbody>
								
                                <td colspan="1" class="font-blue-madison bold warning">Attachments</td>
                                <tr>
                                    <td>
                                        <table class="table table-condensed">
					@foreach($engineerAttachments as $engineerAttachment)
                                            <tr>
											<td><strong>Document Name: </strong></td>
											<td>
												<a href="{{URL::to($engineerAttachment->DocumentPath)}}" target="_blank">{{$engineerAttachment->DocumentName}}</a>
											</td>
                                            </tr>
					@endforeach
                                        </table>
                                    </td>
                                </tr>
								
                                </tbody>
                            </table>
				</div>


			</div>

			<div class="col-md-12">
					<h5 class="font-blue-madison bold"> Curriculum Vitae/Employment History</h5>
					<table class="table table-bordered table-striped table-condensed">
						<thead>
							<tr>
								<th colspan='2' style="text-align:center">Date</th>
								<th rowspan='2'>CDB No.</th>
								<th rowspan='2'>Employer</th>
								<th rowspan='2'>Position in the firm</th>
								<th rowspan='2'>Engaged</th>
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
								{{$cvItem->CDBNo}}
								</td>
								<td>
								{{$cvItem->NameOfFirm}}
								</td>
								<td>{{$cvItem->designation}}</td>
								<td>
								{{$cvItem->NameOfWork}}
								</td>
								
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			
			<div class="row">
                <div class="col-md-12">
                   <a href="{{URL::to('engineer/editregistrationinfo/'.$engineerId.'?redirectUrl=engineer/editdetails&usercdb=edit')}}" class="btn blue-madison editaction"><i class="fa fa-edit"></i> Edit</a>
                </div>
            </div>
            
		</div>
	</div>
</div>
@if((int)$registrationApprovedForPayment==1)
<div class="note note-success">
	<h4 class="">Verified by {{$engineerInformations[0]->Verifier}} on {{convertDateToClientFormat($engineerInformations[0]->VerifiedDate)}}<small><i class="font-red">{{showDateTimeDuration($engineerInformations[0]->VerifiedDate)}}</i></small></h4>
	<p>
		{{$engineerInformations[0]->RemarksByVerifier}}
	</p>
</div>
<div class="note note-success">
	<h4 class="">Approved by {{$engineerInformations[0]->Approver}} on {{convertDateToClientFormat($engineerInformations[0]->RegistrationApprovedDate)}}<small><i class="font-red">{{showDateTimeDuration($engineerInformations[0]->RegistrationApprovedDate)}}</i></small></h4>
	<p>
		{{$engineerInformations[0]->RemarksByApprover}}
	</p>
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
				<button id="approveengineerpaymentregistration" type="button" class="btn green">Approve</button>
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
		{{ Form::open(array('url' => 'engineer/mapprovepaymentforregistration','role'=>'form','id'=>'registrationpaymentdoneengineer'))}}
		<div class="form-body">
			<input type="hidden" name="PaymentApprovedDate" value="{{date('Y-m-d G:i:s')}}" />
			<input type="hidden" name="SysLockedByUserId" value="" />
			<input type="hidden" name="EngineerReference" value="{{$engineerId}}" />
			<input type="hidden" name="SysPaymentApproverUserId" value="{{Auth::user()->Id}}">
			<input type="hidden" name="CmnApplicationRegistrationStatusId" value="{{CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED}}" />
			<div class="row">
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
				<a href="#sendback" data-toggle="modal" class="btn purple">Send back to Approver</a>
			</div>
		</div>
		{{Form::close()}}
	</div>
</div>
@endif
@stop