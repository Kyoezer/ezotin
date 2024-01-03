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
			<i class="fa fa-cogs"></i>List of Certifiedbuilder having comments &nbsp;&nbsp;@if(!Input::has('export'))  <?php $parameters['export'] = 'print'; ?><a href="{{route('cbrpt.certifiedbuilderwithcomments',$parameters)}}" class="btn btn-sm purple" target="_blank"><i class="fa fa-print"></i> Print</a>@endif
		</div>
	</div>
	<div class="portlet-body  flip-scroll">
        @if(Input::get('export')!='print')
        {{Form::open(array('url'=>Request::url(),'method'=>'get'))}}
		<div class="form-body">
			<div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">Date Between</label>
                        <div class="input-group input-large date-picker input-daterange">
                            <input type="text" name="FromDate" class="form-control datepickerfrom" value="{{Input::get('FromDate')}}" />
						<span class="input-group-addon">
						to </span>
                            <input type="text" name="ToDate" class="form-control datepickerto" value="{{Input::get('ToDate')}}" />
                        </div>
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
            @if(Input::has('FromDate'))
                <b>From Date: {{Input::get('FromDate')}}</b> <br/>
            @endif
            @if(Input::has('ToDate'))
                <b>To Date: {{Input::get('ToDate')}}</b> <br/>
            @endif
        @endif
            <table class="table table-bordered table-striped table-condensed flip-content" >
                <thead class="flip-content">
                    <tr>
                        <th class="text-center">Sl.No.</th>
                        <th class="text-center">CDB No.</th>
                        <th class="text-center">Name</th>
                        <th class="text-center col-md-1">Date</th>
                        <th class="text-center">Comments</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reportData as $data)
                        <tr>
                        <td>{{$start++}}</td>
                        <td>{{$data->CDBNo}}</td>
                        <td>{{$data->NameOfFirm}}</td>
                        <td>{{convertDateToClientFormat($data->Date)}}</td>
                        <td>{{html_entity_decode($data->Comments)}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <?php pagination($noOfPages,Input::all(),Input::get('page'),"cbrpt.certifiedbuilderwithcomments"); ?>
        </div>
</div>
@stop