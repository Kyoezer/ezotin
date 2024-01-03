@extends('master')
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="portlet light bordered" id="form_wizard_1">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-gift"></i>
					<span class="caption-subject">Confirmation</span>
				</div>
			</div>
			<div class="portlet-body form">
				<div class="form-body">
					<div class="note note-info">
		              	<p>If you have to edit any information that you have submitted you can click on the edit buttons. After everthing has been finalised you have to agree to <span class="bold">Terms of Services</span> and submit to CDB.</p>
		            </div>
					<div class="row">
						@foreach($specializedTradeInformationsFinal as $specializedTradeInformation)
						<div class="col-md-6">
							<h5 class="font-blue-madison bold">General Information</h5>
							<table class="table table-bordered table-striped table-condensed">
								<tbody>
									<tr>
										<td><strong>Application No. (Dt.)</strong></td>
										<td>{{$specializedTradeInformation->ReferenceNo.' ('.convertDateToClientFormat($specializedTradeInformation->ApplicationDate).')'}}</td>
									</tr>
									<tr>
										<td><strong>Name</strong></td>
										<td>{{$specializedTradeInformation->Salutation.' '.$specializedTradeInformation->Name}}</td>
									</tr>
									<tr>
										<td><strong>CID No.</strong></td>
										<td>{{$specializedTradeInformation->CIDNo}}</td>
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
									<tr>
										<td><strong>Attachment</strong></td>
										<td><a target="_blank" href="{{asset($generalInformation[0]->AttachmentFilePath)}}">Attachment</a></td>
									</tr>
									<tr>
										<td><strong>Reason for Cancellation</strong></td>
										<td>{{$generalInformation[0]->ReasonForCancellation}}</td>
									</tr>
								</tbody>
							</table>
						</div>
						@endforeach
						<div class="col-md-6">
							<h5 class="font-blue-madison bold">Category Information</h5>
							<table class="table table-bordered table-striped table-condensed">
								<thead>
									<tr>
										<th>Category</th>
										<th width="5%" class="table-checkbox">Applied</th>
									</tr>
								</thead>
								<tbody>
									@foreach($workClasssifications as $workClasssification)
										<tr>
											<td>
												{{$workClasssification->Code.' ('.$workClasssification->Name.')'}}
											</td>
											<td class="text-center">
												@if((bool)$workClasssification->CmnAppliedCategoryId!=NULL)
												<i class="fa fa-check"></i>
												@else
												<i class="fa fa-times"></i>
												@endif
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
									@foreach($specializedTradeAttachmentsFinal as $specializedTradeAttachment)
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
					{{ Form::open(array('url' => 'specializedtrade/mapprovecancelcertificate','role'=>'form'))}}
					<div id="approveregistrationcancellation" class="modal fade" role="dialog" aria-labelledby="approvecancellation" aria-hidden="true">
						<div class="modal-dialog modal-lg">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
									<h3 class="modal-title font-green-seagreen">Approve Request</h3>
								</div>
								<div class="modal-body">
									<p class="bold">Are you sure you want to approve cancellation request for this application?</p>
								</div>
								<div class="modal-footer">
									<button type="submit" class="btn green">Approve</button>
									<button class="btn red" data-dismiss="modal" aria-hidden="true">Cancel</button>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label for="">Remarks</label>
						<textarea name="RemarksByApprover" rows="3" class="form-control"></textarea>
					</div>
					<div class="form-actions">
						<div class="row">
							<div class="col-md-12">
								<input type="hidden" value="{{$specializedTradeId}}" name="SpecializedTradeReference" />
								<input type="hidden" value="{{$cancelRequestId}}" name="CancelRequestId" />
								<a href="#approveregistrationcancellation" data-toggle="modal" class="btn green">Approve Cancellation Request</a>
								<a href="{{URL::to('specializedtrade/approvecertificatecancellationrequestlist')}}" class="btn red">Cancel <i class="fa fa-times"></i></a>
							</div>
						</div>
					</div>
					{{Form::close()}}
				</div>
			</div>
		</div>
	</div>
</div>
@stop