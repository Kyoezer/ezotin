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
						While renewing your certificate you can also make changes to your registration details maintained with Construction Development Board.
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
		        @else
		        <div class="note note-info">
		          	<p>First Renewal is free. No fees are associated with it.</p>
		        </div>
		        @endif
				<div class="col-md-6">
					<h4 class="font-green-seagreen">Renewal of CDB Certificate Fee Structure</h4>
					<table class="table table-bordered table-striped table-condensed">
						<thead>
							<tr>
								<th>Application Type</th>
								<th class="text-right">Amount</th>
								<th width="20%">Validity (yrs)</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>Renewal of CDB Certificate</td>
								<td class="text-right">
									{{number_format($feeDetails[0]->RenewalFee,2)}}
								</td>
								<td class="text-center">
									{{$feeDetails[0]->RenewalValidity}}
								</td>
							</tr>
						</tbody>
					</table>
				</div>	
				<div class="form-actions">
					<div class="row">
						<div class="col-md-12">
							<a href="{{URL::to('specializedtrade/applyrenewalregistrationdetails/'.$specializedTradeId.'?redirectUrl=specializedtrade/profile')}}" class="btn green">Proceed <i class="fa fa-arrow-circle-o-right"></i></a>
							<a href="{{URL::to('specializedtrade/profile')}}" class="btn red">Cancel <i class="fa fa-times"></i></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>	
@stop