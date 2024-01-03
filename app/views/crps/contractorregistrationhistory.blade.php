@extends('master')
@section('content')

<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-cogs"></i>
			<span class="caption-subject">Contractor Registration History</span>
			<?php $parameters = Input::all(); $parameters['export']='excel'; $route = 'contractor.contractorregistrationhistory'; ?>
			@if(!Input::has('export'))
				<i class="fa fa-cogs"></i>List of Contractors &nbsp;&nbsp;@if(!Input::has('export')) <?php $parameters['export'] = 'print'; ?><a href="{{URL::to('contractor/contractorregistrationhistory'.'/c94fa538-8431-11e5-8233-5cf9dd5fc4f1?export=print')}}" class="btn btn-sm purple" target="_blank"><i class="fa fa-print"></i> Print</a>@endif
			@endif
		</div>
	</div>

	<div class="portlet-body flip-scroll">
		<h3 class="font-blue-madison">{{$contractor[0]->NameOfFirm.' (CDB No.'.$contractor[0]->CDBNo.')'}}</h3>
		<table class="table table-bordered table-striped table-condensed flip-content">
			<thead class="flip-content">
				<tr>
					<th>
						Sl. No.
					</th>
					<th>
						Date
					</th>
					<th>
						Action by User
					</th>
					<th>
						Status
					</th>
					<th>
						Remarks
					</th>
				</tr>
			</thead>
			<tbody>
				<?php $count = 1; ?>
				@forelse($registrationDetails as $detail)
				<tr>
					<td>
						 {{$count}}
					</td>
					<td>
						 {{convertDateTimeToClientFormat($detail->CreatedOn)}}
					</td>
					<td>
						{{$detail->FullName}}
					</td>
					<td>
						{{$detail->Status}}
					</td>
					<td>
						{{$detail->Remarks}}
					</td>
				</tr>
				<?php $count++; ?>
				@empty
				<tr>
					<td class="font-red text-center" colspan="5">No data to display</td>
				</tr>
				@endforelse
			</tbody>
		</table>

	</div>
	@if(Input::get('export') != 'print')
	<a href="{{URL::to('contractor/contractorregistrationhistorylist')}}" class="btn blue"><i class="m-icon-swapleft m-icon-white"></i>&nbsp;Back</a>
	@endif
</div>
@stop