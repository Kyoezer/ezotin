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
                <i class="fa fa-cogs"></i>Work Distribution by different Agencies
            </div>
        </div>
        <div class="portlet-body flip-scroll">
            {{Form::open(array('url'=>Request::url(),'method'=>'get'))}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">Agency</label>
                            <select class="form-control select2me" name="Agency">
                                <option value="">---SELECT ONE---</option>
                                @foreach($dzongkhagsAgencies as $value)
                                    <option value="{{$value->Id}}" @if($value->Id == Input::get('Agency'))selected @endif>{{$value->Name}}</option>
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
                        <label class="control-label"></label>
                        <div class="btn-set">
                            <button type="submit" class="btn blue-hoki btn-sm">Search</button>
                            <a href="{{Request::url()}}" class="btn grey-cascade btn-sm">Clear</a>
                        </div>
                    </div>
                </div>
            </div>
            {{Form::close()}}
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-condensed flip-content">
                    <thead class="flip-content">
                    <tr>
                        <th>Sl.#</th>
                        <th>Agency</th>
                        <th>No. of works</th>
                        <th>Contract Amount</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $count = 1; $sum1 = $sum2 = 0; ?>
                    @foreach($reportData as $data)
                        @if($data->Code != NULL)
                        <tr>
                            <td>{{$count}}</td>
                            <td>{{$data->Code}}</td>
                            <td class="text-right">{{number_format($data->NoOfWorks)}}<?php $sum1+=$data->NoOfWorks; ?></td>
                            <td class="text-right">{{number_format($data->ContractAmount,2)}}<?php $sum2+=$data->ContractAmount; ?></td>
                        </tr>
                        <?php $count++; ?>
                        @endif
                    @endforeach
                    <tr>
                        <td class="bold text-right" colspan="2">Total</td>
                        <td class="bold text-right">{{$sum1}}</td>
                        <td class="bold text-right">{{number_format($sum2,2)}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop