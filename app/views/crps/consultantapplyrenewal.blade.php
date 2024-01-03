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
						While renewing your certificate you can also upgrade/downgrade work classification or change other information. Relevant fees will be applicable.
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
				<div class="col-md-12 table-responsive">
					<h4 class="font-green-seagreen">Service Fee Structure</h4>
					<table class="table table-bordered table-striped table-condensed">
						<thead>
							<tr>
								<th>Service</th>
								<th class="text-center">Validity (years)</th>
								<th>Fee</th>
							</tr>
						</thead>
						<tbody>
							@foreach($feeStructures as $feeStructure)
							<tr @if($feeStructure->Id==CONST_SERVICETYPE_RENEWAL) class="success" @endif>
								<td>{{$feeStructure->Name}}</td>
								<td class="text-center">
									@if($feeStructure->Id==CONST_SERVICETYPE_RENEWAL)
										{{$feeStructure->ConsultantValidity}}
									@else 
										-
									@endif
								</td>
								<td>
									@if((bool)$feeStructure->ConsultantAmount!=null)
										{{number_format($feeStructure->ConsultantAmount,2)}}
									@else
										- 
									@endif
									@if($feeStructure->Id==CONST_SERVICETYPE_RENEWAL)
										<i class="font-red">per category</i>
									@endif
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>	
				<div class="form-actions">
					<div class="row">
						<div class="col-md-12">
							<a href="{{URL::to('consultant/applyservicegeneralinfo/'.$consultantId.'?srenew=1')}}" class="btn green">Proceed <i class="fa fa-arrow-circle-o-right"></i></a>
							<a href="{{URL::to('consultant/profile')}}" class="btn red">Cancel <i class="fa fa-times"></i></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@stop