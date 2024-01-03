@extends('master')
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption font-green-seagreen">
					<i class="fa fa-cogs"></i>
					<span class="caption-subject">Approve Cancellation</span>
				</div>
			</div>
			<div class="portlet-body">
				<blockquote>
					<div class="row">
						<div class="col-md-6">
							<h4><span class="font-green-seagreen">Application No.</span> {{$generalInformation[0]->ReferenceNo}}</h4>
						</div>
						<div class="col-md-6 text-right">
							<h4><span class="font-green-seagreen">Application Date</span> {{convertDateToClientFormat($generalInformation[0]->ApplicationDate)}}</h4>
						</div>
					</div>
					<p class="text-justify"> Are you sure you want to cancel the certificate for this architect?</p>
				</blockquote>
				<div class="row">
					<div class="col-md-6">
						<table class="table table-bordered table-striped table-condensed flip-content">
							<tbody>
								<tr>
									<td><strong>AR No.</strong></td>
									<td>{{$generalInformation[0]->ARNo}}</td>
								</tr>
								<tr>
									<td><strong>Name</strong></td>
									<td>{{$generalInformation[0]->ArchitectName}}</td>
								</tr>
								<tr>
									<td><strong>Country</strong></td>
									<td>{{$generalInformation[0]->Country}}</td>
								</tr>
								<tr>
									<td><strong>Dzongkhag</strong></td>
									<td>{{$generalInformation[0]->Dzongkhag}}</td>
								</tr>
								<tr>
									<td><strong>Email</strong></td>
									<td>{{$generalInformation[0]->Email}}</td>
								</tr>
								<tr>
									<td><strong>Mobile No.</strong></td>
									<td>{{$generalInformation[0]->MobileNo}}</td>
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
				</div>
				{{ Form::open(array('url' => 'architect/mapprovecancelcertificate','role'=>'form'))}}
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
					<textarea name="RemarksByApprover" id="remarks" rows="3" class="form-control"></textarea>
				</div>
				<div class="form-actions">
					<div class="row">
						<div class="col-md-12">
							<input type="hidden" value="{{$architectId}}" name="ArchitectReference" />
							<input type="hidden" value="{{$cancelRequestId}}" name="CancelRequestId" />
							<a href="#approveregistrationcancellation" data-toggle="modal" class="btn green">Approve Cancellation Request</a>
							<a href="{{URL::to('architect/rejectcertificatecancellationrequest')}}/{{$cancelRequestId}}" class="rejectaction btn red">Reject Cancellation Request</a>
							<a href="{{URL::to('architect/approvecertificatecancellationrequestlist')}}" class="btn purple">Cancel <i class="fa fa-times"></i></a>
						</div>
					</div>
				</div>
				{{Form::close()}}
			</div>
		</div>
	</div>
</div>
@stop