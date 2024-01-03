@extends('horizontalmenumaster')
@section('content')
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption font-green-seagreen">
			<i class="fa fa-cogs"></i>
			<span class="caption-subject">Track Record</span>
		</div>
		<div class="actions">
			<a href="{{URL::to('consultant/printtrackrecords')}}" class="btn btn-sm blue-madison" target="_blank"><i class="fa fa-print"></i>Print Track Records</a>
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
							<td><strong>Proposed Name</strong></td>
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
					<thead>
						<tr>
							<th width="5%">Sl#</th>
							<th>
								Procuring Agency
							</th>
							<th class="">
								Work Order No.
							</th>
							<th class="">
								Name of Work
							</th>
							<th class="">
								Category
							</th>
							<th class="">
								Service
							</th>
							<th>
								Period (Months)
							</th>
							<th>
								Start Date
							</th>
							<th>
								Completion Date
							</th>
							<th>
								Status
							</th>
						</tr>
					</thead>
					<tbody>
						<?php $sl=1; ?>
						@forelse($consultantTrackrecords as $consultantTrackrecord)
						<tr>
							<td>{{$sl}}</td>
							<td>{{$consultantTrackrecord->ProcuringAgency}}</td>
							<td>{{$consultantTrackrecord->WorkOrderNo}}</td>
							<td>{{strip_tags($consultantTrackrecord->NameOfWork)}}</td>
							<td>{{$consultantTrackrecord->ServiceCategory}}</td>
							<td>{{$consultantTrackrecord->ServiceName}}</td>
							<td>{{$consultantTrackrecord->ContractPeriod}}</td>
							<td>{{convertDateToClientFormat($consultantTrackrecord->WorkStartDate)}}</td>
							<td>{{convertDateToClientFormat($consultantTrackrecord->WorkCompletionDate)}}</td>
							<td>{{$consultantTrackrecord->CurrentWorkStatus}}</td>
							<?php $sl+=1; ?>
						</tr>
						@empty
						<tr>
							<td colspan="10" class="text-center font-red">No Track Records till {{date('d-M-Y')}}</td>
						</tr>
						@endforelse
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>	
@stop