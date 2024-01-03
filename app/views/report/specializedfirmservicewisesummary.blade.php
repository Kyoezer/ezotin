@extends('reportsmaster')
@section('pagescripts')
    {{HTML::script('assets/global/scripts/report.js')}}
@stop
@section('content')
    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption">
                <?php $parameters = Input::all(); $parameters['export']='excel'; ?>
                <i class="fa fa-cogs"></i>Specialized Firm Service Wise Summary &nbsp;&nbsp;@if(!Input::has('export')) <a href="{{route('specializedfirmrpt.listofspecializedfimbycategory',$parameters)}}" class="btn btn-sm green"><i class="fa fa-file-excel-o"></i> Export to Excel</a>    <?php $parameters['export'] = 'print'; ?><a href="{{route('specializedfirmrpt.listofspecializedfimbycategory',$parameters)}}" class="btn btn-sm purple" target="_blank"><i class="fa fa-print"></i> Print</a>@endif
            </div>
        </div>
        <div class="portlet-body flip-scroll">
            <div class="table-responsive col-md-4">
                <table class="table table-bordered table-striped table-condensed flip-content" id="">
                    <thead class="flip-content">
                        <tr>
                            <th>Specialized Firm Service</th>
                            <th>Number of Specialized Firm</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $total = 0; ?>
                    @forelse($reportData as $data)
                        <tr>
                            <td>{{$data->Code}}-{{$data->Name}}</td>
                            <td class="text-right">{{$data->NoOfSpecializedfirm}}<?php $total += $data->NoOfSpecializedfirm;?></td>
                        </tr>
                    @empty
                        <tr><td colspan="2" class="font-red text-center">No data to display</td></tr>
                    @endforelse
                    @if(!empty($reportData))
                        <tr>
                            <td class="bold text-right">Total</td>
                            <td class="text-right">{{$total}}</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
@stop