@extends('reportsmaster')
@section('pagescripts')
    {{HTML::script('assets/global/scripts/report.js')}}
@stop
@section('content')
    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption">
                <?php $parameters = Input::all(); $parameters['export']='excel'; ?>
                <i class="fa fa-cogs"></i>Dzongkhag Wise Work Awarded and Completed &nbsp;&nbsp;@if(!Input::has('export')) <a href="{{route('etoolrpt.dzongkhagwisework',$parameters)}}" class="btn btn-sm green"><i class="fa fa-file-excel-o"></i> Export to Excel</a>    <?php $parameters['export'] = 'print'; ?><a href="{{route('etoolrpt.dzongkhagwisework',$parameters)}}" class="btn btn-sm purple" target="_blank"><i class="fa fa-print"></i> Print</a>@endif
            </div>
        </div>
        <div class="portlet-body flip-scroll">
            @if(Input::get('export') != 'print')
            {{Form::open(array('url'=>Request::url(),'method'=>'get'))}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">From Date</label><div class="clearfix"></div>
                            <div class="input-icon right">
                                <i class="fa fa-calendar"></i>
                                <input type="text" name="FromDate" class="form-control datepicker" value="{{Input::get('FromDate')}}" readonly="readonly" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">To Date</label><div class="clearfix"></div>
                            <div class="input-icon right">
                                <i class="fa fa-calendar"></i>
                                <input type="text" name="ToDate" class="form-control datepicker" value="{{Input::has('ToDate')?Input::get('ToDate'):date('d-m-Y')}}" readonly="readonly" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label class="control-label">&nbsp;</label>
                        <div class="btn-set">
                            <button type="submit" class="btn blue-hoki btn-sm">Search</button>
                            <a href="{{Request::url()}}" class="btn grey-cascade btn-sm">Clear</a>
                        </div>
                    </div>
                </div>
            </div>
            {{Form::close()}}
            @else
                <b>From Date: {{Input::get('FromDate')}}</b> <br/>
                <b>To Date: {{Input::get('ToDate')}}</b>
            @endif
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-condensed flip-content" id="">
                    <thead class="flip-content">
                    <tr>
                        <th>Sl#</th>
                        <th>Dzongkhag (Site)</th>
                        <th>No. of Awarded Works</th>
                        <th>No. of Completed Works</th>
                        <th>Total</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $count = 1; $noAwarded = $noCompleted = $total = 0; ?>
                    @forelse($reportData as $data)
                        <tr>
                            <td>{{$count++}}</td>
                            <td>{{$data->Dzongkhag}}</td>
                            <td class="text-right">{{$data->NoAwarded}}<?php $noAwarded += $data->NoAwarded;?></td>
                            <td class="text-right">{{$data->NoCompleted}}<?php $noCompleted += $data->NoCompleted;?></td>
                            <td class="text-right">{{$data->NoAwarded+$data->NoCompleted}}<?php $total += $data->NoAwarded+$data->NoCompleted;?></td>
                        </tr>
                    @empty

                        <tr><td colspan="5" class="font-red text-center">No data to display</td></tr>
                    @endforelse
                    @if(!empty($reportData))
                        <tr>
                            <td class="bold text-right" colspan="2">Total</td>
                            <td class="text-right">{{$noAwarded}}</td>
                            <td class="text-right">{{$noCompleted}}</td>
                            <td class="text-right">{{$total}}</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop