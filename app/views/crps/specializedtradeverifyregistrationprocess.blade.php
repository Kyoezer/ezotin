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
				<button id="verifyspecializedtraderegistration" type="button" class="btn green">Verify</button>
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
{{ Form::open(array('url' => $modelPost,'role'=>'form','id'=>'verifyregistrationspecializedtrade'))}}
<div class="row">
	<div class="col-md-12">
		<div class="portlet light bordered" id="form_wizard_1">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-gift"></i>
					<span class="caption-subject">Verify Specialized Trade Registration</span>
				</div>
				<div class="actions">
					<a href="{{URL::to('specializedtrade/verifyregistration')}}" class="btn btn-default btn-sm">Back to List</a>
				</div>
			</div>
			<div class="portlet-body form">
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
									</tr>
								</thead>
								<tbody>
									@foreach($workCategories as $workCategory)
										<tr>
											<td>
												<input type="hidden" name="WorkCategoryTableId[]" value="{{$workCategory->WorkClassificationId}}" class="tablerowcontrol" @if($workCategory->CategoryId!=$workCategory->CmnAppliedCategoryId) disabled="disabled" @endif/>
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
												@if($workCategory->CategoryId==$workCategory->CmnAppliedCategoryId)
												<input type="checkbox" class="tablerowcheckbox" value="1" @if($workCategory->CategoryId==$workCategory->CmnAppliedCategoryId) {{'checked'}} @endif />
												@endif
												<input type="hidden" name="CmnVerifiedCategoryId[]" value="{{$workCategory->CategoryId}}" class="tablerowcontrol" @if($workCategory->CategoryId!=$workCategory->CmnAppliedCategoryId) disabled="disabled" @endif />
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
                           <a href="{{URL::to('specializedtrade/editregistrationinfo/'.$specializedTradeId.'?redirectUrl=specializedtrade/verifyregistrationprocess')}}" class="btn blue-madison editaction"><i class="fa fa-edit"></i> Edit Information</a>
                        </div>
                    </div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-cogs"></i>
					<span class="caption-subject">Application</span>
				</div>
			</div>
			<div class="portlet-body form">
				@include('crps.applicationhistory')
				<div class="form-body">
					<input type="hidden" name="SysLockedByUserId" value="" />
					<input type="hidden" name="SysVerifierUserId" value="{{Auth::user()->Id}}" />
					<input type="hidden" name="VerifiedDate" value="{{date('Y-m-d G:i:s')}}" />
					<input type="hidden" name="SpecializedTradeReference" value="{{$specializedTradeId}}">
					<input type="hidden" name="CmnApplicationRegistrationStatusId" value="{{CONST_CMN_APPLICATIONREGISTRATIONSTATUS_VERIFIED}}">
					
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
			</div>
		</div>
	</div>
</div>
{{Form::close()}}
@stop