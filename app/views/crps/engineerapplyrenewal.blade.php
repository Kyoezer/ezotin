@extends('horizontalmenumaster')
@section('content')
<div class="row">
	<div class="col-md-offset-2 col-md-8">
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption font-green-seagreen">
					<i class="fa fa-cogs"></i>
					<span class="caption-subject">Application for Renewal</span>
				</div>
			</div>
			<div class="portlet-body">
				<blockquote>
					<p class="text-justify">
						While renewing your certificate you can also change other information.
					</p>
				</blockquote>
				@if($hasLateRenewal)
				<div class="note note-danger">
		          	<p>Seems like your registration already expired on {{convertDateToClientFormat($hasLateFeeAmount[0]->RegistrationExpiryDate)}}. The total number of days late is {{$hasLateFeeAmount[0]->PenaltyNoOfDays}} days. However 30 days is considered as grace period which means the late fee that would have been imposed is waived. Penalty amount is {{$hasLateFeeAmount[0]->PenaltyLateFeeAmount}} per day.</p>
					<?php $noOfDaysAfterGracePeriod=$hasLateFeeAmount[0]->PenaltyNoOfDays-30; ?>
					@if($noOfDaysAfterGracePeriod>0)
					<p>Total number of days late after grace period is {{$noOfDaysAfterGracePeriod}}. Total of Nu. {{number_format($noOfDaysAfterGracePeriod*$hasLateFeeAmount[0]->PenaltyLateFeeAmount)}} will be imposed as penalty for late renewal of your CDB certificate.</p>
					@endif
		        </div>
		        @endif
				<div class="col-md-12">
					<h4 class="font-green-seagreen">Renewal of CDB Certificate Fee Structure</h4>
					<table class="table table-bordered table-striped table-condensed">
						<thead>
							<tr>
								<th>Engineer Type</th>
								<th>Application Type</th>
								<th class="text-right">Amount</th>
								<th width="20%">Validity (yrs)</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								@if($serviceSectorType==CONST_CMN_SERVICESECTOR_GOVT)
									<td>Goverment</td>
								@else
									<td>Private</td>
								@endif
								<td>Renewal of CDB Certificate</td>
								<td class="text-right">
									@if(empty($feeDetails[0]->RenewalFee))
										-
									@else
									{{number_format($feeDetails[0]->RenewalFee,2)}}
									@endif
								</td>
								<td class="text-center">
									@if(empty($feeDetails[0]->RenewalValidity))
										-
									@else
									{{$feeDetails[0]->RenewalValidity}}
									@endif
								</td>
							</tr>
						</tbody>
					</table>
				</div>	
				<div class="form-actions">
					<div class="row">
						<div class="col-md-12">
							<a href="{{URL::to('engineer/applyrenewalregistrationdetails/'.$engineerId.'?redirectUrl=engineer/profile')}}" class="btn green">Proceed <i class="fa fa-arrow-circle-o-right"></i></a>
							<a href="{{URL::to('engineer/profile')}}" class="btn red">Cancel <i class="fa fa-times"></i></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@stop