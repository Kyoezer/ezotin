@extends('master')
@section('pagescripts')
{{ HTML::script('assets/global/scripts/specializedtrade.js') }}
@stop
@section('lastcdbno')
<div class="page-toolbar">
	<div id="dashboard-report-range" class="pull-right tooltips btn btn-fit-height grey-cascade" data-placement="top" data-original-title="Last used Specialized Trade CDB No.">
		<span class="thin visible-lg-inline-block">Last used SP No.: <span class="bold">{{lastUsedSpecializedTradeNo();}}</span></span>
	</div>
</div>
@stop
@section('content')
<div id="sendback" class="modal fade" role="dialog" aria-labelledby="sendback" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			{{Form::open(array('url'=>'sendapplicationback','role'=>'form'))}}
			<input type="hidden" name="Id" value="{{$specializedTradeInformations[0]->Id}}" />
			<input type="hidden" name="Model" value="SpecializedTradeModel"/>
			<input type="hidden" name="Status" value="{{CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW}}"/>
			<input type="hidden" name="RedirectUrl" value="specializedtrade/approveregistration"/>
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
				<button id="approvespecializedtraderegistration" type="button" class="btn green">Approve</button>
				<button class="btn red" data-dismiss="modal" aria-hidden="true">Cancel</button>
			</div>
		</div>
	</div>
</div>
<div id="rejectregistration" class="modal fade" role="dialog" aria-labelledby="rejectregistration" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			{{ Form::open(array('url' => 'specializedtrade/mrejectregistration','role'=>'form','id'=>'rejectregistrationspecializedtrade'))}}
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h3 class="modal-title font-red">Reject Registration</h3>
			</div>
			<div class="modal-body">
				<input type="hidden" name="RedirectRoute" value="verifyregistration">
				<p class="bold">Are you sure you want to reject this application?</p>
				<input type="hidden" name="SpecializedTradeReference" value="{{$specializedTradeInformations[0]->Id}}" />
				<input type="hidden" name="RejectedDate" value="{{date('Y-m-d G:i:s')}}" />
				<div class="form-group">
					<label>Remarks</label>
					<textarea name="RemarksByRejector" class="form-control remarksbyrejector" rows="3"></textarea>
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
<div class="row">
	<div class="col-md-12">
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-gift"></i>
					<span class="caption-subject">Approve Specialized Trade Registration</span>
				</div>
				<div class="actions">
					<a href="{{URL::to('specializedtrade/approveregistration')}}" class="btn btn-sm btn-default">Back to List</a>
				</div>
			</div>
			<div class="portlet-body form">
				{{ Form::open(array('url' => $modelPost,'role'=>'form','id'=>'approveregistrationspecializedtrade'))}}
				<div class="form-body">
					@foreach($specializedTradeInformations as $specializedTradeInformation)
					<div class="row">
						<div class="note note-info">
							<p>Registration of specialized trade is free. No registration fee is applicable.</p>
						</div>
						<div class="col-md-6">
							<h5 class="font-blue-madison bold">General Information</h5>
							<table class="table table-bordered table-striped table-condensed">
								<tbody>
									<tr>
										<td><strong>Application No. (Date)</strong></td>
										<td>{{$specializedTradeInformation->ReferenceNo.' ('.convertDateToClientFormat($specializedTradeInformation->ApplicationDate).')'}}</td>
									</tr>
									<tr>
										<td><strong>Name</strong></td>
										<td>{{$specializedTradeInformation->Salutation.' '.$specializedTradeInformation->Name}}</td>
									</tr>
									<tr>
										<td><strong>CID No.</strong></td>
										<td>{{$specializedTradeInformation->CIDNo}}&nbsp;&nbsp;&nbsp;<button type="button" data-cid="{{trim($specializedTradeInformation->CIDNo)}}" class="btn blue btn-sm btn-small checkhrdbandwebservice">Check</button></td>
									</tr>
									<tr>
										<td><strong>Dzongkhag</strong></td>
										<td>{{$specializedTradeInformation->Dzongkhag}}</td>
									</tr>
									<tr>
										<td><strong>Gewog</strong></td>
										<td>{{$specializedTradeInformation->Gewog}}</td>
									</tr>
									<tr>
										<td><strong>Village</strong></td>
										<td>{{$specializedTradeInformation->Village}}</td>
									</tr>
									<tr>
										<td><strong>Email</strong></td>
										<td>{{$specializedTradeInformation->Email}}</td>
									</tr>
									<tr>
										<td><strong>Mobile No.</strong></td>
										<td>{{$specializedTradeInformation->MobileNo}}</td>
									</tr>
									<tr>
										<td><strong>Telephone No.</strong></td>
										<td>{{$specializedTradeInformation->MobileNo}}</td>
									</tr>
									<tr>
										<td><strong>Employer Name</strong></td>
										<td>{{'M/s.'.$specializedTradeInformation->EmployerName}}</td>
									</tr>
									<tr>
										<td><strong>Employer Address</strong></td>
										<td>{{$specializedTradeInformation->EmployerAddress}}</td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="col-md-6">
							<h5 class="font-blue-madison bold">Category Information</h5>
							<table class="table table-bordered table-striped table-condensed">
								<thead>
									<tr>
										<th>Category</th>
										<th width="5%" class="text-center">Applied</th>
										<th width="5%" class="text-center">Verify</th>
										<th width="5%" class="text-center">Approve</th>
									</tr>
								</thead>
								<tbody>
									@foreach($workCategories as $workCategory)
										<tr>
											<td>
												<input type="hidden" name="WorkCategoryTableId[]" value="{{$workCategory->WorkClassificationId}}" class="tablerowcontrol" @if($workCategory->CategoryId!=$workCategory->CmnVerifiedCategoryId) disabled="disabled" @endif/>
												{{$workCategory->Code.'-'.$workCategory->Name}}
											</td>
											<td class="text-center">
												@if($workCategory->CategoryId==$workCategory->CmnAppliedCategoryId)
												<i class="fa fa-check font-green-seagreen"></i>
												@else
												<i class="fa fa-times font-red"></i>
												@endif
											</td>
											<td class="text-center">
												@if($workCategory->CategoryId==$workCategory->CmnVerifiedCategoryId)
												<i class="fa fa-check font-green-seagreen"></i>
												@else
												<i class="fa fa-times font-red"></i>
												@endif
											</td>
											<td class="text-center">
												@if($workCategory->CategoryId==$workCategory->CmnVerifiedCategoryId)
												<input type="checkbox" class="tablerowcheckbox" value="1" @if($workCategory->CategoryId==$workCategory->CmnVerifiedCategoryId) {{'checked'}} @endif />
												@endif
												<input type="hidden" name="CmnApprovedCategoryId[]" value="{{$workCategory->CategoryId}}" class="tablerowcontrol" @if($workCategory->CategoryId!=$workCategory->CmnVerifiedCategoryId) disabled="disabled" @endif />
											</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
						<div class="col-md-6">
							<h5 class="font-blue-madison bold">Attachments</h5>
							<table class="table table-bordered table-striped table-condensed">
								<thead>
									<tr>
										<th>Document Name</th>
									</tr>
								</thead>
								<tbody>
									@foreach($specializedTradeAttachments as $specializedTradeAttachment)
									<tr>
										<td>
											<a href="{{URL::to($specializedTradeAttachment->DocumentPath)}}" target="_blank">{{$specializedTradeAttachment->DocumentName}}</a>
										</td>
									</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>
					@endforeach
					<div class="row">
                        <div class="col-md-12">
                           <a href="{{URL::to('specializedtrade/editregistrationinfo/'.$specializedTradeId.'?redirectUrl=specializedtrade/approveregistrationprocess')}}" class="btn blue-madison editaction"><i class="fa fa-edit"></i> Edit Information</a>
                        </div>
                    </div>
				</div>
				@include('crps.applicationhistory')
				<div class="row">
						<div class="col-md-12">
							<h5 class="font-red bold">*Validity of registration for specialized trade is {{$registrationValidityYears}} years.</h5>
						</div>
						<?php 
							$numberPartOfRegNo = "";
							$lastNo = lastUsedSpecializedTradeNo(); 
							for($i = 0; $i<strlen($lastNo); $i++){
								if((int)$lastNo[$i] > 0 || $lastNo[$i] == '0'){
									$numberPartOfRegNo.=$lastNo[$i];
								}
							}
							$newNo = str_replace($numberPartOfRegNo,(int)$numberPartOfRegNo+1,$lastNo);
						?>
						<div class="col-md-2">
							<div class="form-group">
								<input type="hidden" value="checkspnospecializedtrade" class="cdbnocheckurl">
								<label class="control-label">SP No.</label>
								<input type="text" name="SPNo" value="{{$newNo}}" class="form-control required checkcdbno" value="{{Input::old('SPNo')}}"/>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label class="control-label">Approved Date</label>
								<div class="input-icon right">
									<i class="fa fa-calendar"></i>
									<input type="text" name="RegistrationApprovedDate" value="@if(empty(Input::old('RegistrationApprovedDate'))){{convertDateToClientFormat(date('Y-m-d G:i:s'))}}@else{{Input::old('RegistrationApprovedDate')}}@endif" class="form-control required" placeholder="" readonly/>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label class="control-label">Expiry Date</label>
								<div class="input-icon right">
									<i class="fa fa-calendar"></i>
									<input type="text" name="RegistrationExpiryDate" class="form-control required" placeholder="" readonly value="@if(!empty(Input::old('RegistrationExpiryDate'))){{Input::old('RegistrationExpiryDate')}}@else{{registrationExpiryDateCalculator($registrationValidityYears)}}@endif"/>
								</div>
							</div>
						</div>
					</div>
				<div class="form-group">
					<input type="hidden" name="SysLockedByUserId" value="" />
					<input type="hidden" name="SysApproverUserId" value="{{Auth::user()->Id}}" />
					<input type="hidden" name="SpecializedTradeReference" value="{{$specializedTradeId}}">
					<input type="hidden" name="CmnApplicationRegistrationStatusId" value="{{CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED}}">
					<label>Remarks</label>
					<textarea name="RemarksByApprover" class="form-control" rows="3">{{Input::old('RemarksByApprover')}}</textarea>
				</div>
				<div class="form-actions">
					<div class="btn-set">
						<a href="#approve" data-toggle="modal" class="btn green">Approve</a>
						<a href="#rejectregistration" data-toggle="modal" class="btn red">Reject</a>
						<a href="#sendback" data-toggle="modal" class="btn purple">Send back to Verifier</a>
					</div>
					<button type="button" class="btn blue-ebonyclay pull-right">Next</button>
				</div>
			{{Form::close()}}
			</div>
		</div>
	</div>
</div>
@stop