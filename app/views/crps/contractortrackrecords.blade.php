@extends('horizontalmenumaster')
@section('content')
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption font-green-seagreen">
			<i class="fa fa-cogs"></i>
			<span class="caption-subject">Track Record</span>
		</div>
		<div class="actions">
			<a href="{{URL::to('contractor/printtrackrecords')}}" class="btn btn-sm blue-madison" target="_blank"><i class="fa fa-print"></i>Print Track Records</a>
		</div>
	</div>
	<div class="portlet-body">
		<div class="row">
			<div class="col-md-6">
				<table class="table table-bordered table-striped table-condensed flip-content">
					<tbody>
						<tr>
							<td><strong>CDB No.</strong></td>
							<td>{{$generalInformation[0]->CDBNo}}</td>
						</tr>
						<tr>
							<td><strong>Ownership Type</strong></td>
							<td>{{$generalInformation[0]->OwnershipType}}</td>
						</tr>
						<tr>
							<td><strong>Company Name</strong></td>
							<td>{{$generalInformation[0]->NameOfFirm}}</td>
						</tr>
						<tr>
							<td><strong>Country</strong></td>
							<td>{{$generalInformation[0]->Country}}</td>
						</tr>
						<tr>
							<td><strong>Dzongkhag</strong></td>
							<td>{{$generalInformation[0]->Dzongkhag}}</td>
						</tr>
						
					</tbody>
				</table>
			</div>
			<div class="col-md-6">
				<table class="table table-bordered table-striped table-condensed flip-content">
					<tbody>
						<tr>
							<td><strong>Address</strong></td>
							<td>{{$generalInformation[0]->Address}}</td>
						</tr>
						<tr>
							<td><strong>Email</strong></td>
							<td>{{$generalInformation[0]->Email}}</td>
						</tr>
						<tr>
							<td><strong>Telephone No.</strong></td>
							<td>{{$generalInformation[0]->TelephoneNo}}</td>
						</tr>
						<tr>
							<td><strong>Mobile No.</strong></td>
							<td>{{$generalInformation[0]->MobileNo}}</td>
						</tr>
						<tr>
							<td><strong>Fax No.</strong></td>
							<td>{{$generalInformation[0]->FaxNo}}</td>
						</tr>
					</tbody>	
				</table>
			</div>
			<div class="col-md-12 table-responsive">
				<table class="table table-bordered table-striped table-condensed">
					<thead class="flip-content">
					<tr>
						<th>Sl.No.</th>
						<th>Year</th>
						<th>Agency</th>
						<th>Work Id</th>
						<th>Work Name</th>
						<th>Category</th>
						<th>Awarded Amount</th>
						<th>Final Amount</th>
						<th>Dzongkhag</th>
						<th>Status</th>
						<th>APS scoring</th>
						<th>APS (100)</th>
						<th>Remarks</th>
					</tr>
					</thead>
					<tbody>
					<?php $count = 1; $awardedAmount = 0;?>
					@forelse($trackRecords as $workDetail)
						<tr>
							<td>{{$count}}</td>
							<td>{{$workDetail->Year}}</td>
							<td>{{$workDetail->Agency}}</td>
							<td>
								<a href="{{URL::to('all/viewhrandeqforworkid?workid='.$workDetail->WorkId.'&type=1')}}">{{$workDetail->WorkId}}</a>
							</td>
							<td>{{strip_tags($workDetail->NameOfWork)}}</td>
							<td>{{$workDetail->Category}}</td>
							<td>{{$workDetail->AwardedAmount}}</td>
							<td>{{$workDetail->FinalAmount}}</td>
							<td>{{$workDetail->Dzongkhag}}</td>
							<td>{{$workDetail->Status}}<?php $awardedAmount+=($workDetail->ReferenceNo == 3001)?1:0;?></td>
							<td>
								<?php if((int)$workDetail->APS == 100) {
									$points = 10;
								}
								if(((int)$workDetail->APS < 100) && ((int)$workDetail->APS >= 50)) {
									$points = 10 - (ceil((100 - (int)$workDetail->APS) / 5));
								}
								if((int)$workDetail->APS < 50){
									$points = 0;
								}
								?>
								{{$points}}
							</td>
							<td>{{$workDetail->APS}}</td>
							<td>{{$workDetail->Remarks}}</td>
						</tr>
						<?php $count++ ?>
					@empty
						<tr><td colspan="12" class="font-red text-center">No data to display</td></tr>
					@endforelse
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
@stop