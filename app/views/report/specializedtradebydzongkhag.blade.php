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
			<i class="fa fa-cogs"></i>Number of Specialized Trade by Dzongkhag &nbsp;&nbsp;@if(!Input::has('export')) <a href="{{route('specializedtraderpt.specializedtradebydzongkhag',$parameters)}}" class="btn btn-sm green"><i class="fa fa-file-excel-o"></i> Export to Excel</a>    <?php $parameters['export'] = 'print'; ?><a href="{{route('specializedtraderpt.specializedtradebydzongkhag',$parameters)}}" class="btn btn-sm purple" target="_blank"><i class="fa fa-print"></i> Print</a>@endif
		</div>
	</div>
	<div class="portlet-body flip-scroll">
        @if(Input::get('export') != 'print')
        {{Form::open(array('url'=>Request::url(),'method'=>'get'))}}
		<div class="form-body">
			<div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">Dzongkhag</label>
                        <select class="form-control select2me" name="Dzongkhag">
                            <option value="">---SELECT ONE---</option>
                            @foreach($dzongkhags as $dzongkhag)
                                <option value="{{$dzongkhag->NameEn}}" @if($dzongkhag->NameEn == Input::get('Dzongkhag'))selected @endif>{{$dzongkhag->NameEn}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @if(!Input::has('export'))
                    <div class="col-md-2">
                        <label class="control-label">&nbsp;</label>
                        <div class="btn-set">
                            <button type="submit" class="btn blue-hoki btn-sm">Search</button>
                            <a href="{{Request::url()}}" class="btn grey-cascade btn-sm">Clear</a>
                        </div>
                    </div>
                @endif
			</div>
		</div>
        {{Form::close()}}
        @else
            @foreach(Input::all() as $key=>$value)
                @if($key != 'export')
                    <b>{{$key}}: {{$value}}</b><br>
                @endif
            @endforeach
            <br/>
        @endif
        <div class="table-responsive">
            <div class="col-md-4">
                <table class="table table-bordered table-striped table-condensed flip-content" id="contractorsdzongkhag-table">
                    <thead class="flip-content">
                    <tr>
                        <th class="text-center">Dzongkhag</th>
                        <th class="text-center">No. of Specialized Trade</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $sum1 = 0; ?>
                    @foreach($reportData as $data)
                        <tr>
                            <td>{{$data->Dzongkhag}}</td>
                            <td class="text-right">{{$data->NoOfSpecializedtrade}}<?php $sum1+=$data->NoOfSpecializedtrade; ?></td>
                        </tr>
                    @endforeach
                    <tr>
                        <td class="bold">Total</td>
                        <td class="text-right bold large-total">{{$sum1}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>

        </div><div class="clearfix"></div>
        <hr/>
        <h4>Distribution of Specialized Trade by Dzongkhag (%)</h4>
        <div id="contractorsbydzongkhag" style="width: 100%;"></div>
	</div>
</div>
@stop