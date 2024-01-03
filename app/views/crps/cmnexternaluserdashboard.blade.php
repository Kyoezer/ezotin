@extends('master')
@section('pagescripts')
	@if(Session::has('extramessage'))
		<div id="extramessagemodal" class="modal fade" role="dialog" aria-labelledby="extramessagemodal" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
						<h3 class="modal-title font-red-intense">ERROR</h3>
					</div>
					<div class="modal-body">
						<p>{{Session::get('extramessage')}}</p>
					</div>
					<div class="modal-footer">
						<button class="btn green-seagreen" data-dismiss="modal" aria-hidden="true">Ok</button>
					</div>
				</div>
			</div>
		</div>
		<script>
			$("#extramessagemodal").modal('show');
		</script>
		<?php Session::forget('extramessage'); ?>
	@endif
@stop
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-comments"></i>News/Notices
				</div>
			</div>
			<div class="portlet-body" id="chats">
				<ul class="chats">
					@forelse($newsAndNotifications as $newsAndNotification)
					<li class="in">
						<img class="avatar" alt="" src="{{asset('assets/cdb/layout/img/avatar.png')}}"/>
						<div class="message">
							<span class="arrow">
							</span>
							<a href="#" class="name">
							Administrator </a>
							<span class="datetime">
							on {{convertDateToClientFormat($newsAndNotification->Date)}} </span>
							<span class="body">
							{{html_entity_decode($newsAndNotification->Message)}}
						</div>
					</li>
					@empty
						<li class="font-red">No News/Notices to display</li>
					@endforelse
					<?php $applicationCount = 1; $count = 1; ?>
					@if(isset($applicationHistory))
						@foreach($applicationHistory as $application)
							@if($application->Id != NULL)
								@if($applicationCount == 1)
									<h4>Application's Status</h4>
								@endif
							<li class="in">
								<div class="message">
									<span class="arrow">
									</span>Applied for
									<a href="#" class="name" style="text-decoration:none;">
										<span style="background:#f43ff4; color:#fff; font-weight:bold;"> &nbsp;{{$application->Service}} &nbsp;</span> </a>
									<span class="datetime">
									on {{convertDateToClientFormat($application->ApplicationDate)}} (Application #{{isset($application->ReferenceNo)?$application->ReferenceNo:""}}) </span>
									<span class="body">
									Current Status of Application: {{($application->Status == "New")?"Pending":$application->Status}} on
										@if($application->CmnApplicationRegistrationStatusId == CONST_CMN_APPLICATIONREGISTRATIONSTATUS_REJECTED)
											{{convertDateToClientFormat($application->RejectedDate)}}
										@endif
										@if($application->CmnApplicationRegistrationStatusId == CONST_CMN_APPLICATIONREGISTRATIONSTATUS_VERIFIED)
											{{convertDateToClientFormat($application->RegistrationVerifiedDate)}}
										@endif
										@if($application->CmnApplicationRegistrationStatusId == CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVEDFORPAYMENT)
											{{convertDateToClientFormat($application->RegistrationApprovedDate)}}
										@endif
										@if($application->CmnApplicationRegistrationStatusId == CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED)
											{{convertDateToClientFormat($application->PaymentApprovedDate)}}
										@endif
										@if($application->CmnApplicationRegistrationStatusId == CONST_CMN_APPLICATIONREGISTRATIONSTATUS_REJECTED)
											@if(isset($application->RemarksByRejector) && !empty($application->RemarksByRejector))
											<br>
											<strong>Remarks: </strong>{{$application->RemarksByRejector}}
											@endif
												<br>

											@if(isset($type))

												@if($type == 1)
													@if($applicationCount == 1)
															Please Reapply
														<br>
														@if(strpos($application->Service,'Renewal')>-1)
															<strong><a href="{{URL::to('contractor/applyservicegeneralinfo')}}/{{$application->CrpContractorId}}?srenew=1&oldapplicationid={{$application->Id}}&confedit=1&edit=true">Reapply here</a></strong>
														@else
															<strong><a href="{{URL::to('contractor/applyservicegeneralinfo')}}/{{$application->CrpContractorId}}?oldapplicationid={{$application->Id}}&confedit=1&edit=true">Reapply here</a></strong>
														@endif
													@endif
												@elseif($type==2)
													@if($applicationCount == 1)
															Please Reapply
														<br>
														<strong><a href="http://www.cdb.gov.bt/{{$application->prefix.'/apprejected/'.$application->Id.'/'.$application->SysRejectionCode}}" >Click Here to Reapply</a> </p></strong>
													@endif
												@endif

											@endif
											<?php $count++; ?>
										@endif
										<br>
										{{--@if(isset($rejectionDetails[$application->Id]))--}}
											{{--@if(!empty($rejectionDetails[$application->Id]))--}}
												{{--<a target="_blank" href="{{URL::to($rejectionDetails[$application->Id]['prefix'].'/apprejected/'.$rejectionDetails[$application->Id]['referenceApplicant'].'/'.$rejectionDetails[$application->Id]['rejectionSysCode'])}}" >Click Here to Reapply</a>--}}
											{{--@endif--}}
										{{--@endif--}}

									</span>
									</div>

								</li>
							<?php $applicationCount++; ?>

							@endif
						@endforeach
					@endif
				</ul>
			</div>
		</div>
		<!-- END PORTLET-->
	</div>
</div>
@stop