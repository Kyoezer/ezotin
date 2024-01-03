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
			<i class="fa fa-cogs"></i>Number of Certifiedbuilder by Dzongkhag,  Category &nbsp;&nbsp;@if(!Input::has('export')) <a href="{{route('cbrpt.listofcertifiedbuilderbydzongkhag',$parameters)}}" class="btn btn-sm green"><i class="fa fa-file-excel-o"></i> Export to Excel</a>    <?php $parameters['export'] = 'print'; ?><a href="{{route('cbrpt.listofcertifiedbuilderbydzongkhag',$parameters)}}" class="btn btn-sm purple" target="_blank"><i class="fa fa-print"></i> Print</a>@endif
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
                 
                </tr>
                <tr style="border-bottom: 1px solid #DDDDDD;">
                    
                    <th>Number of Certified Builder</th>
                    
                </tr>
			</thead>
			<tbody>
            <?php $sum1 =  0; ?>
        
            @foreach($reportData as $data)
                <tr>
                    <td>{{$data->Dzongkhag}}</td>
                   
                    <td>{{$data->CountSF1}}<?php $sum1 += $data->CountSF1; ?></td>
                    
                    
                    
                </tr>
            @endforeach
            <tr>
                <td class="bold">Total</td>
                <td class="bold">{{$sum1}}</td>
                
               
            </tr>
			</tbody>
		</table>
        </div>
        <hr/>
        <h4>Distribution of Certifiedbuilder by Dzongkhag (%)</h4>
        <div id="contractorsbydzongkhag" style="width: 100%;"></div>
        <hr/>
            {{--<h4>certifiedbuilder by Class</h4>--}}
            {{--<div id="contractorsbyclass" style="width: 60%;"></div>--}}
	</div>
</div>
@stop