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
			<i class="fa fa-cogs"></i>Number of Contractors by Dzongkhag, Class & Category &nbsp;&nbsp;@if(!Input::has('export')) <a href="{{route('contractorrpt.contractorsbydzongkhag',$parameters)}}" class="btn btn-sm green"><i class="fa fa-file-excel-o"></i> Export to Excel</a>    <?php $parameters['export'] = 'print'; ?><a href="{{route('contractorrpt.contractorsbydzongkhag',$parameters)}}" class="btn btn-sm purple" target="_blank"><i class="fa fa-print"></i> Print</a>@endif
        </div>
	</div>
	<div class="portlet-body flip-scroll">
        @if(Input::get('export')!="print")
        {{Form::open(array('url'=>Request::url(),'method'=>'get'))}}
		<div class="form-body">
			<div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">Dzongkhag</label>
                        <select class="form-control select2me" name="DzongkhagId">
                            <option value="">---SELECT ONE---</option>
                            @foreach($dzongkhags as $dzongkhag)
                                <option value="{{$dzongkhag->Id}}" @if($dzongkhag->Id == Input::get('DzongkhagId'))selected @endif>{{$dzongkhag->NameEn}}</option>
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
                    <th colspan="4"><center>W1</center></th>
                    <th><center>W2</center></th>
                    <th colspan="4"><center>W3</center></th>
                    <th colspan="4"><center>W4</center></th>
                    <th>Total</th>
                </tr>
                <tr style="border-bottom: 1px solid #DDDDDD;">
                    <th>L</th>
                    <th>M</th>
                    <th>S</th>
                    <th>Total</th>
                    <th></th>
                    <th>L</th>
                    <th>M</th>
                    <th>S</th>
                    <th>Total</th>
                    <th>L</th>
                    <th>M</th>
                    <th>S</th>
                    <th>Total</th>
                    <th></th>
                </tr>
			</thead>
			<tbody>
            <?php $sum1 = $sum2 = $sum3=$sum4=$sum5=$sum6=$sum7=$sum8=$sum9=$sum10=$sum11=$sum12=$sum13=$sum14 = 0; ?>
            @foreach($reportData as $data)
                <tr>
                    <td>{{$data->NameEn}}</td>
                    <td class="text-right">{{$data->W1L}}<?php $sum1 += $data->W1L; ?></td>
                    <td class="text-right">{{$data->W1M}}<?php $sum2 += $data->W1M; ?></td>
                    <td class="text-right">{{$data->W1S}}<?php $sum3 += $data->W1S; ?></td>
                    <td class="text-right">{{$data->W1Total}}<?php $sum4 += $data->W1Total; ?></td>
                    <td class="text-right">{{$data->W2R}}<?php $sum5 += $data->W2R; ?></td>
                    <td class="text-right">{{$data->W3L}}<?php $sum6 += $data->W3L; ?></td>
                    <td class="text-right">{{$data->W3M}}<?php $sum7 += $data->W3M; ?></td>
                    <td class="text-right">{{$data->W3S}}<?php $sum8 += $data->W3S; ?></td>
                    <td class="text-right">{{$data->W3Total}}<?php $sum9 += $data->W3Total; ?></td>
                    <td class="text-right">{{$data->W4L}}<?php $sum10 +=$data->W4L;  ?></td>
                    <td class="text-right">{{$data->W4M}}<?php $sum11 +=$data->W4M;  ?></td>
                    <td class="text-right">{{$data->W4S}}<?php $sum12 +=$data->W4S;  ?></td>
                    <td class="text-right">{{$data->W4Total}}<?php $sum13 +=$data->W4Total;  ?></td>
                    <td class="text-right">{{($data->W1Total+$data->W3Total+$data->W4Total+$data->W2R)}}<?php $sum14 += ($data->W1Total+$data->W3Total+$data->W4Total+$data->W2R);?></td>
                </tr>
            @endforeach
            <tr>
                <td class="bold">Total</td>
                <td class="text-right bold large-total">{{$sum1}}</td>
                <td class="text-right bold medium-total">{{$sum2}}</td>
                <td class="text-right bold small-total">{{$sum3}}</td>
                <td class="text-right bold">{{$sum4}}</td>
                <td class="text-right bold registered-total">{{$sum5}}</td>
                <td class="text-right bold large-total">{{$sum6}}</td>
                <td class="text-right bold medium-total">{{$sum7}}</td>
                <td class="text-right bold small-total">{{$sum8}}</td>
                <td class="text-right bold">{{$sum9}}</td>
                <td class="text-right bold large-total">{{$sum10}}</td>
                <td class="text-right bold medium-total">{{$sum11}}</td>
                <td class="text-right bold small-total">{{$sum12}}</td>
                <td class="text-right bold">{{$sum13}}</td>
                <td class="text-right bold">{{$sum14}}</td>
            </tr>
			</tbody>
		</table>
        </div>
        <hr/>
        <br/><br/><br/>
        <h4>Distribution of Contractors by Dzongkhag (%)</h4>
        <div id="contractorsbydzongkhag" style="width: 100%;"></div>
        <hr/>
        <h4>Contractors by Class</h4>
        <div id="contractorsbyclass" style="width: 60%;"></div>
	</div>
</div>
@stop