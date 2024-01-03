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
						<?php $xFees = $noOfDaysAfterGracePeriod*$hasLateFeeAmount[0]->PenaltyLateFeeAmount; ?>
						@if((int)$maxClassification == 998 || (int)$maxClassification == 997)
							@if($xFees > 3000)
								<?php $xFees = 3000; ?>
							@endif
						@endif
					<p>Total number of days late after grace period is {{$noOfDaysAfterGracePeriod}}. Total of Nu. {{number_format($xFees)}} will be imposed as penalty for late renewal of your CDB certificate.</p>
					@endif
		        </div>
		        @endif
				<div class="col-md-12 table-responsive">
					<h4 class="font-green-seagreen">Fee Structure for Renewal of CDB Certificate</h4>
					<table class="table table-bordered table-striped table-condensed">
						<thead>
							<tr class="info">
								<th colspan="4">
									<i>Fees (Nu) per Category</i>
								</th>
							</tr>
							<tr>
								<th>Classification</th>
								<th width="20%">Validity (Years)</th>
								<th class="text-right">Renewal Fee (Nu.)</th>
								<th class="text-right">Upgrade/Downgrade Fee (Nu.)</th>
							</tr>
						</thead>
						<tbody>
							@foreach($feeStructures as $feeStructure)
							<tr>
								<td>
									{{$feeStructure->Name}}
								</td>
								<td class="text-center">
									{{$registrationValidityYears}}
								</td>
								<td class="text-right">
									{{number_format($feeStructure->RenewalFee,2)}}
								</td>
								<td class="text-right">
									{{number_format($feeStructure->RegistrationFee,2)}}
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>	
				<div class="form-actions">
					<div class="row">
						<div class="col-md-12">
							<a href="{{URL::to('contractor/applyservicegeneralinfo/'.$contractorId.'?srenew=1')}}" class="btn green">Proceed <i class="fa fa-arrow-circle-o-right"></i></a>
							<a href="{{URL::to('contractor/mydashboard')}}" class="btn red">Cancel <i class="fa fa-times"></i></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@stop