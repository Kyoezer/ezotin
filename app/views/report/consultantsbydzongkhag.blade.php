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
			<i class="fa fa-cogs"></i>Number of Consultants by Dzongkhag, Class & Category &nbsp;&nbsp;@if(!Input::has('export')) <a href="{{route('consultantrpt.consultantsbydzongkhag',$parameters)}}" class="btn btn-sm green"><i class="fa fa-file-excel-o"></i> Export to Excel</a>    <?php $parameters['export'] = 'print'; ?><a href="{{route('consultantrpt.consultantsbydzongkhag',$parameters)}}" class="btn btn-sm purple" target="_blank"><i class="fa fa-print"></i> Print</a>@endif
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
		    <table class="table table-bordered table-striped table-condensed flip-content" id="contractorsdzongkhag-table">
			<thead class="flip-content">
                <tr>
                    <th rowspan="4" style="border-right: 1px solid #DDDDDD; vertical-align: middle;">Dzongkhag</th>
                </tr>
                <tr style="border-top: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD;">
                    <th colspan="4"><center>A</center></th>
                    <th colspan="8"><center>C</center></th>
                    <th colspan="8"><center>E</center></th>
                    <th colspan="8"><center>S</center></th>
                    <th>Total</th>
                </tr>
                <tr style="border-bottom: 1px solid #DDDDDD;">
                    <th>A1</th>
                    <th>A2</th>
                    <th>A3</th>
                    <th>Total</th>
                    <th>C1</th>
                    <th>C2</th>
                    <th>C3</th>
                    <th>C4</th>
                    <th>C5</th>
                    <th>C6</th>
                    <th>C7</th>
                    <th>Total</th>
                    <th>E1</th>
                    <th>E2</th>
                    <th>E3</th>
                    <th>E4</th>
                    <th>E5</th>
                    <th>E6</th>
                    <th>E7</th>
                    <th>Total</th>
                    <th>S1</th>
                    <th>S2</th>
                    <th>S3</th>
                    <th>S4</th>
                    <th>S5</th>
                    <th>S6</th>
                    <th>S7</th>
                    <th>Total</th>
                    <th></th>
                </tr>
			</thead>
			<tbody>
            <?php $sum1 = $sum2 = $sum3 = $sum4 = $sum5 = $sum6 = $sum7 = $sum8 = $sum9 = $sum10 = $sum11 = 0; ?>
            <?php $sum12 = $sum13 = $sum14 = $sum15 = $sum16 = $sum17 = $sum18 = $sum19 = $sum20 = $sum21 = 0; ?>
            <?php $sum22 = $sum23 = $sum24 = $sum25 = $sum26 = $sum27 = $sum28 = $sum29 = 0; ?>
            @foreach($reportData as $data)
                <tr>
                    <td>{{$data->Dzongkhag}}</td>
                    <td class="text-right">{{$data->CountA1}}<?php $sum1 += $data->CountA1; ?></td>
                    <td class="text-right">{{$data->CountA2}}<?php $sum2 += $data->CountA2; ?></td>
                    <td class="text-right">{{$data->CountA3}}<?php $sum3 += $data->CountA3; ?></td>
                    <td class="text-right">{{$data->CountA1+$data->CountA2+$data->CountA3}}<?php $sum4 += ($data->CountA1+$data->CountA2+$data->CountA3); ?></td>
                    <td class="text-right">{{$data->CountC1}}<?php $sum5 += $data->CountC1; ?></td>
                    <td class="text-right">{{$data->CountC2}}<?php $sum6 += $data->CountC2; ?></td>
                    <td class="text-right">{{$data->CountC3}}<?php $sum7 += $data->CountC3; ?></td>
                    <td class="text-right">{{$data->CountC4}}<?php $sum8 += $data->CountC4; ?></td>
                    <td class="text-right">{{$data->CountC5}}<?php $sum9 += $data->CountC5; ?></td>
                    <td class="text-right">{{$data->CountC6}}<?php $sum10 += $data->CountC6; ?></td>
                    <td class="text-right">{{$data->CountC7}}<?php $sum11 += $data->CountC7; ?></td>
                    <td class="text-right">{{$data->CountC1+$data->CountC2+$data->CountC3+$data->CountC4+$data->CountC5+$data->CountC6+$data->CountC7}}<?php $sum12 += ($data->CountC1+$data->CountC2+$data->CountC3+$data->CountC4+$data->CountC5+$data->CountC6+$data->CountC7); ?></td>
                    <td class="text-right">{{$data->CountE1}}<?php $sum13 += $data->CountE1; ?></td>
                    <td class="text-right">{{$data->CountE2}}<?php $sum14 += $data->CountE2; ?></td>
                    <td class="text-right">{{$data->CountE3}}<?php $sum15 += $data->CountE3; ?></td>
                    <td class="text-right">{{$data->CountE4}}<?php $sum16 += $data->CountE4; ?></td>
                    <td class="text-right">{{$data->CountE5}}<?php $sum17 += $data->CountE5; ?></td>
                    <td class="text-right">{{$data->CountE6}}<?php $sum18 += $data->CountE6; ?></td>
                    <td class="text-right">{{$data->CountE7}}<?php $sum19 += $data->CountE7; ?></td>
                    <td class="text-right">{{$data->CountE1+$data->CountE2+$data->CountE3+$data->CountE4+$data->CountE5+$data->CountE6+$data->CountE7}}<?php $sum20 += ($data->CountE1+$data->CountE2+$data->CountE3+$data->CountE4+$data->CountE5+$data->CountE6+$data->CountE7); ?></td>
                    <td class="text-right">{{$data->CountS1}}<?php $sum21 += $data->CountS1; ?></td>
                    <td class="text-right">{{$data->CountS2}}<?php $sum22 += $data->CountS2; ?></td>
                    <td class="text-right">{{$data->CountS3}}<?php $sum23 += $data->CountS3; ?></td>
                    <td class="text-right">{{$data->CountS4}}<?php $sum24 += $data->CountS4; ?></td>
                    <td class="text-right">{{$data->CountS5}}<?php $sum25 += $data->CountS5; ?></td>
                    <td class="text-right">{{$data->CountS6}}<?php $sum26 += $data->CountS6; ?></td>
                    <td class="text-right">{{$data->CountS7}}<?php $sum27 += $data->CountS7; ?></td>
                    <td class="text-right">{{$data->CountS1+$data->CountS2+$data->CountS3+$data->CountS4+$data->CountS5+$data->CountS6+$data->CountS7}}<?php $sum28 += ($data->CountS1+$data->CountS2+$data->CountS3+$data->CountS4+$data->CountS5+$data->CountS6+$data->CountS7); ?></td>
                    <td class="text-right">{{$data->CountA1+$data->CountA2+$data->CountA3+$data->CountC1+$data->CountC2+$data->CountC3+$data->CountC4+$data->CountC5+$data->CountC6+$data->CountC7+$data->CountE1+$data->CountE2+$data->CountE3+$data->CountE4+$data->CountE5+$data->CountE6+$data->CountE7+$data->CountS1+$data->CountS2+$data->CountS3+$data->CountS4+$data->CountS5+$data->CountS6+$data->CountS7}}<?php $sum29 += ($data->CountA1+$data->CountA2+$data->CountA3+$data->CountC1+$data->CountC2+$data->CountC3+$data->CountC4+$data->CountC5+$data->CountC6+$data->CountC7+$data->CountE1+$data->CountE2+$data->CountE3+$data->CountE4+$data->CountE5+$data->CountE6+$data->CountE7+$data->CountS1+$data->CountS2+$data->CountS3+$data->CountS4+$data->CountS5+$data->CountS6+$data->CountS7); ?></td>
                </tr>
            @endforeach
            <tr>
                <td class="bold">Total</td>
                <td class="text-right bold">{{$sum1}}</td>
                <td class="text-right bold">{{$sum2}}</td>
                <td class="text-right bold">{{$sum3}}</td>
                <td class="text-right bold">{{$sum4}}</td>
                <td class="text-right bold">{{$sum5}}</td>
                <td class="text-right bold">{{$sum6}}</td>
                <td class="text-right bold">{{$sum7}}</td>
                <td class="text-right bold">{{$sum8}}</td>
                <td class="text-right bold">{{$sum9}}</td>
                <td class="text-right bold">{{$sum10}}</td>
                <td class="text-right bold">{{$sum11}}</td>
                <td class="text-right bold">{{$sum12}}</td>
                <td class="text-right bold">{{$sum13}}</td>
                <td class="text-right bold">{{$sum14}}</td>
                <td class="text-right bold">{{$sum15}}</td>
                <td class="text-right bold">{{$sum16}}</td>
                <td class="text-right bold">{{$sum17}}</td>
                <td class="text-right bold">{{$sum18}}</td>
                <td class="text-right bold">{{$sum19}}</td>
                <td class="text-right bold">{{$sum20}}</td>
                <td class="text-right bold">{{$sum21}}</td>
                <td class="text-right bold">{{$sum22}}</td>
                <td class="text-right bold">{{$sum23}}</td>
                <td class="text-right bold">{{$sum24}}</td>
                <td class="text-right bold">{{$sum25}}</td>
                <td class="text-right bold">{{$sum26}}</td>
                <td class="text-right bold">{{$sum27}}</td>
                <td class="text-right bold">{{$sum28}}</td>
                <td class="text-right bold">{{$sum29}}</td>
            </tr>
			</tbody>
		</table>
        </div>
        <hr/>
        <h4>Distribution of Consultants by Dzongkhag (%)</h4>
        <div id="contractorsbydzongkhag" style="width: 100%;"></div>
        <hr/>
            {{--<h4>Contractors by Class</h4>--}}
            {{--<div id="contractorsbyclass" style="width: 60%;"></div>--}}
	</div>
</div>
@stop