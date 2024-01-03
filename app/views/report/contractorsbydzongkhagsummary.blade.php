@extends('reportsmaster')
@section('pagescripts')
    {{HTML::script('assets/cdb/morris/raphael-min.js')}}
    {{HTML::script('assets/cdb/morris/modernizr-2.5.3-respond-1.1.0.min.js')}}
    {{HTML::script('assets/cdb/morris/morris.min.js')}}
    {{HTML::script('assets/global/scripts/report.js')}}
@stop
@section('pagestyles')
    {{HTML::style('assets/cdb/morris/morris.css')}}
@stop
@section('content')
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
            <?php $parameters = Input::all(); $parameters['export']='excel'; ?>
			<i class="fa fa-cogs"></i>Number of Contractors by Dzongkhag and Class &nbsp;&nbsp;@if(!Input::has('export')) <a href="{{route('contractorrpt.contractorsbydzongkhagsummary',$parameters)}}" class="btn btn-sm green"><i class="fa fa-file-excel-o"></i> Export to Excel</a>    <?php $parameters['export'] = 'print'; ?><a href="{{route('contractorrpt.contractorsbydzongkhagsummary',$parameters)}}" class="btn btn-sm purple" target="_blank"><i class="fa fa-print"></i> Print</a>@endif
        </div>
	</div>
	<div class="portlet-body flip-scroll">
        <div class="table-responsive col-md-6">
		    <table class="table table-bordered table-striped table-condensed flip-content" id="contractorsdzongkhag-table">
			<thead class="flip-content">
                <tr>
                    <th>Dzongkhag</th>
                    <th class="text-right">Large</th>
                    <th class="text-right">Medium</th>
                    <th class="text-right">Small</th>
                    <th class="text-right">Registered</th>
                    <th class="text-right">Total</th>
                </tr>
			</thead>
			<tbody>
            <?php $largeTotal = $mediumTotal = $smallTotal = $registeredTotal = $rowTotal = $grandTotal = 0; ?>
            @foreach($reportData as $data)
                <tr>
                    <td>{{$data->Dzongkhag}}</td>
                    <td class="text-right">{{$data->Large}}</td><?php $largeTotal+=(int)$data->Large; ?>
                    <td class="text-right">{{$data->Medium}}</td><?php $mediumTotal+=(int)$data->Medium; ?>
                    <td class="text-right">{{$data->Small}}</td><?php $smallTotal+=(int)$data->Small; ?>
                    <td class="text-right">{{$data->Registered}}</td><?php $registeredTotal+=(int)$data->Registered; ?>
                    <?php $rowTotal = (int)$data->Large+ (int)$data->Medium+ (int)$data->Small+(int)$data->Registered; ?>
                    <td class="text-right">{{$rowTotal}}</td>
                </tr>
                <?php $grandTotal += $rowTotal; $rowTotal = 0; ?>
            @endforeach
            <tr>
                <td class="bold">Total</td>
                <td class="text-right bold large-total">{{$largeTotal}}</td>
                <td class="text-right bold medium-total">{{$mediumTotal}}</td>
                <td class="text-right bold small-total">{{$smallTotal}}</td>
                <td class="text-right bold registered-total">{{$registeredTotal}}</td>
                <td class="text-right bold ">{{$grandTotal}}</td
            </tr>
			</tbody>
		</table>
        </div>
        <hr/>
        <br/><br/><br/>
        <div class="clearfix"></div>
        <h4>Distribution of Contractors by Dzongkhag (%)</h4>
        <div id="contractorsbydzongkhag" style="width: 100%;"></div>
        <hr/>
        <h4>Contractors by Class</h4>
        <div id="contractorsbyclass" style="width: 60%;"></div>
	</div>
</div>
@stop