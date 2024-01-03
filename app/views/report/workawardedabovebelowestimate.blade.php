@extends('reportsmaster')
@section('pagescripts')
@stop
@section('content')
<div class="portlet light bordered">
<?php 
    $route="etoolrpt.workawardedabovebelowestimate";
?>
   
	<div class="portlet-title">
		<div class="caption">
            <?php $parameters = Input::all(); $parameters['export']='excel'; ?>
			<i class="fa fa-cogs"></i>No. of work awarded and completed in same year  &nbsp;&nbsp;@if(!Input::has('export')) <a href="{{route($route,$parameters)}}" class="btn btn-sm green"><i class="fa fa-file-excel-o"></i> Export to Excel</a>    <?php $parameters['export'] = 'print'; ?><a href="{{route($route,$parameters)}}" class="btn btn-sm purple" target="_blank"><i class="fa fa-print"></i> Print</a>@endif
		</div>
	</div>
	<div class="portlet-body flip-scroll">
        @if(Input::get('export') != 'print')
            <?php 
                $url = 'etoolrpt/workawardedabovebelowestimate'; 
                $route="etoolrpt.workawardedabovebelowestimate";
            ?>
       
        @else
            @foreach(Input::all() as $key=>$value)
                @if($key != 'export')
                    <b>{{$key}}: {{$value}}</b><br>
                @endif
            @endforeach
            <br/>
        @endif
		<table class="table table-bordered table-striped table-condensed flip-content" id="contractorhumanresource">
			<thead class="flip-content">
				<tr>
                    <th>Sl.No.</th>
					<th>Name of Work</th>
					<th>Reference No.</th>
                    <th>Name of Firm</th>
                    <th>CDB No.</th>
                    <th>Project Estimated Cost</th>
                    <th>Awarded Amount</th>
                    <th>Status</th>
                    
                    
                    
				</tr>
			</thead>
			<tbody>
            @forelse($workList as $work)
				<tr>
                    <td>{{$start++}}</td>
                    <td>{{$work->NameOfWork}}</td>
                    <td>{{$work->ReferenceNo}}</td>
                    <td>{{$work->NameOfFirm}}</td>
                    <td>{{$work->CDBNo}}</td>
                    <td>{{$work->ProjectEstimateCost}}</td>
                    <td>{{$work->AwardedAmount}}</td>
                    <td>{{$work->estimatedStatus}}</td>
				</tr>
                @empty
                <tr>
                    <td colspan="14" class="font-red text-center">No data to display!</td>
                </tr>
                @endforelse
			</tbody>
		</table>
        <?php pagination($noOfPages,Input::all(),Input::get('page'),$route); ?>
	</div>
</div>
@stop