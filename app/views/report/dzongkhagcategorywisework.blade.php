@extends('reportsmaster')
@section('pagescripts')
    {{HTML::script('assets/global/scripts/report.js')}}
@stop
@section('content')
    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption">
                <?php $parameters = Input::all(); $parameters['export']='excel'; ?>
                <i class="fa fa-cogs"></i>Dzongkhag Wise Category Wise Work Awarded and Completed &nbsp;&nbsp;@if(!Input::has('export'))     <?php $parameters['export'] = 'print'; ?><a href="{{route('etoolrpt.dzongkhagcategorywisework',$parameters)}}" class="btn btn-sm purple" target="_blank"><i class="fa fa-print"></i> Print</a>@endif
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
                            <th rowspan="4">Sl#</th>
                            <th rowspan="4" style="border-right: 1px solid #DDDDDD; vertical-align: middle;">Dzongkhag</th>
                        </tr>
                        <tr style="border-top: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD;">
                            <th colspan="4"><center>Awarded</center></th>
                            <th colspan="4"><center>Completed</center></th>
                            <th>Total</th>
                        </tr>
                        <tr style="border-bottom: 1px solid #DDDDDD;">
                            <th>W1</th>
                            <th>W2</th>
                            <th>W3</th>
                            <th>W4</th>
                            <th>W1</th>
                            <th>W2</th>
                            <th>W3</th>
                            <th>W4</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $rowCount = 1;$rowTotal = $total1 = $total2 = $total3 = $total4 = $total5 = $total6 = $total7 = $total8 = 0; $count = 0; ?>
                        @foreach($reportData as $data)
                            <tr>
                                <td>{{$rowCount++}}</td>
                                <td>{{$data->Dzongkhag}}</td>
                                <td class="text-right">{{$data->W1Awarded}}<?php $total1 += $data->W1Awarded; ?></td>
                                <td class="text-right">{{$data->W2Awarded}}<?php $total2 += $data->W2Awarded; ?></td>
                                <td class="text-right">{{$data->W3Awarded}}<?php $total3 += $data->W3Awarded; ?></td>
                                <td class="text-right">{{$data->W4Awarded}}<?php $total4 += $data->W4Awarded; ?></td>
                                <td class="text-right">{{$data->W1Completed}}<?php $total5 += $data->W1Completed; ?></td>
                                <td class="text-right">{{$data->W2Completed}}<?php $total6 += $data->W2Completed; ?></td>
                                <td class="text-right">{{$data->W3Completed}}<?php $total7 += $data->W3Completed; ?></td>
                                <td class="text-right">{{$data->W4Completed}}<?php $total8 += $data->W4Completed; ?></td>
                                <td class="text-right"><?php $rowTotal+=$data->W1Awarded+$data->W2Awarded+$data->W3Awarded+$data->W4Awarded+$data->W1Completed+$data->W2Completed+$data->W3Completed+$data->W4Completed; ?>{{$data->W1Awarded+$data->W2Awarded+$data->W3Awarded+$data->W4Awarded+$data->W1Completed+$data->W2Completed+$data->W3Completed+$data->W4Completed}}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td class="bold text-right" colspan="2">Total</td>
                            <td class="text-right">{{$total1}}</td>
                            <td class="text-right">{{$total2}}</td>
                            <td class="text-right">{{$total3}}</td>
                            <td class="text-right">{{$total4}}</td>
                            <td class="text-right">{{$total5}}</td>
                            <td class="text-right">{{$total6}}</td>
                            <td class="text-right">{{$total7}}</td>
                            <td class="text-right">{{$total8}}</td>
                            <td class="text-right">{{$rowTotal}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
        </div>
    </div>
@stop