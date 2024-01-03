@extends('horizontalmenumaster')
@section('content')
<div class="row">
	<div class="col-md-offset-2 col-md-8">
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption font-green-seagreen">
					<i class="fa fa-cogs"></i>
					<span class="caption-subject">Application for Cancellation</span>
				</div>
			</div>
			<div class="portlet-body">
				@if((int)$hasAlreadyRequestedForCancellation==0)
					{{ Form::open(array('url' => 'specializedtrade/mcancelcertificate','role'=>'form','files'=>true))}}
					<blockquote>
						<div class="row">
							<div class="col-md-6">
								<h4><span class="bold font-green-seagreen">Application No.</span> {{$applicationNo}}</h4>
								<input type="hidden" value="{{$applicationNo}}" name="ReferenceNo" />
								<input type="hidden" value="{{CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW}}" name="CmnApplicationStatusId" />
							</div>
							<div class="col-md-6 text-right">
								<h4><span class="bold font-green-seagreen">Application Date</span> {{date('d-m-Y')}}</h4>
								<input type="hidden" value="{{date('Y-m-d G:i:s')}}" name="ApplicationDate" />
							</div>
						</div>
						<p class="text-justify">You are about to cancel your registration with Construction Development Board. Do you wish to proceed? Upon cancellation, your account will be inaccessible.</p>
					</blockquote>
					<div class="col-md-4">
						<div class="form-group">
							<label for="cancellationattachment" class="control-label">Your Application</label>
							<input type="file" name="Attachment" id="cancellationattachment" class="form-control"/>
						</div>
					</div>
					<div class="col-md-5">
						<div class="form-group">
							<label for="remarks" class="control-label">Reason for Cancellation</label>
							<textarea class="form-control" name="ReasonForCancellation" id="remarks"></textarea>
						</div>
					</div>
					<div class="form-actions">
						<div class="row">
							<div class="col-md-12">
								<input type="hidden" value="{{$specializedTradeId}}" name="CrpSpecializedTradeFinalId" />
								<button type="submit" class="btn green">Submit <i class="fa fa-arrow-circle-o-right"></i></button>
								<a href="{{URL::to('specializedtrade/profile')}}" class="btn red">Cancel <i class="fa fa-times"></i></a>
							</div>
						</div>
					</div>
					{{Form::close()}}
				@else
					<blockquote>
						<p class="text-justify">You have already requested for cancellation of your CDB Certificate.</p>
					</blockquote>	
				@endif
			</div>
		</div>
	</div>
</div>
@stop