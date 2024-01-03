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
                <i class="fa fa-cogs"></i>Work Distribution by Dzongkhag and Class
            </div>
        </div>
        <div class="portlet-body flip-scroll">
            {{Form::open(array('url'=>Request::url(),'method'=>'get'))}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">Dzongkhag</label>
                            <select class="form-control select2me" name="Dzongkhag">
                                <option value="">---SELECT ONE---</option>
                                @foreach($dzongkhagsAgencies as $value)
                                    <option value="{{$value->Code}}" @if($value->Code == Input::get('Dzongkhag'))selected @endif>{{$value->Code}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">Type</label>
                            {{Form::select('Type',array(''=>'---SELECT---',3=>'Etool',2=>'CiNet',1=>'CRPS'),Input::get('Type'),array('class'=>'form-control'))}}
                        </div>
                    </div>
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
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-condensed flip-content" id="workdistributionbyclass">
                    <thead class="flip-content">
                    <tr>
                        <th>Class</th>
                        <th>No. of works</th>
                        <th>Contract Amount</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $sum1 = $sum2 = 0; ?>
                    @foreach($reportData as $data)
                        <tr>
                            <td>{{$data->Code}}</td>
                            <td class="text-right">{{number_format($data->NoOfWorks)}}<?php $sum1+=$data->NoOfWorks; ?></td>
                            <td class="text-right"><input type="hidden" name="amount" value="{{$data->ContractAmount}}"/>{{number_format($data->ContractAmount,2)}}<?php $sum2+=$data->ContractAmount; ?></td>
                        </tr>
                    @endforeach
                    <tr>
                        <td class="bold text-right">Total</td>
                        <td class="bold text-right">{{$sum1}}</td>
                        <td class="bold text-right">{{number_format($sum2,2)}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <hr/>
            <h4><i>Works awarded by class {{Input::has('FromDate')?" between ".Input::get('FromDate')." and ".(Input::has('ToDate')?Input::get('ToDate'):date('d-m-Y')):" as of ".(Input::has('ToDate')?Input::get('ToDate'):date('d-m-Y'))}}</i></h4>
            <div id="graph" style="width: 80%;"></div>
        </div>
    </div>
@stop