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
			<i class="fa fa-cogs"></i>Category Wise Report &nbsp;&nbsp;@if(!Input::has('export'))<a href="{{route("contractorrpt.categorywisereport",$parameters)}}" class="btn btn-sm green"><i class="fa fa-file-excel-o"></i> Export to Excel</a>    <?php $parameters['export'] = 'print'; ?><a href="{{route('contractorrpt.categorywisereport',$parameters)}}" class="btn btn-sm purple" target="_blank"><i class="fa fa-print"></i> Print</a>@endif
		</div>
	</div>
	<div class="portlet-body flip-scroll">
        @if(Input::get('export') != 'print')
        {{Form::open(array('url'=>Request::url(),'method'=>'get'))}}
		<div class="form-body">
            <div class="col-md-2">
                <label class="control-label">From</label>
                <div class="input-icon right">
                    <i class="fa fa-calendar"></i>
                    <input type="text" name="FromDate" class="form-control datepicker" value="{{Input::get('FromDate')}}" readonly="readonly" placeholder="">
                </div>
            </div>
            <div class="col-md-2">
                <label class="control-label">To</label>
                <div class="input-icon right">
                    <i class="fa fa-calendar"></i>
                    <input type="text" name="ToDate" class="form-control datepicker" value="{{Input::has('ToDate')?Input::get('ToDate'):date('d-m-Y')}}" readonly="readonly" placeholder="">
                </div>
            </div>
			<div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">Dzongkhag</label>
                        <select class="form-control select2me" name="Dzongkhag">
                            <option value="">---SELECT ONE---</option>
                            @foreach($dzongkhags as $dzongkhag)
                                <option value="{{$dzongkhag->Dzongkhag}}" @if($dzongkhag->Dzongkhag == Input::get('Dzongkhag'))selected @endif>{{$dzongkhag->Dzongkhag}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">Procuring Agency</label>
                        <select class="form-control select2me" name="Agency">
                            <option value="">---SELECT ONE---</option>
                            @foreach($agencies as $agency)
                                <option value="{{$agency->ProcuringAgency}}" @if($agency->ProcuringAgency == Input::get('Agency'))selected @endif>{{$agency->ProcuringAgency}} ({{$agency->ProcuringAgencyCode}})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">Classification</label>
                        <select class="form-control select2me" name="Classification">
                            <option value="">---SELECT ONE---</option>
                            @foreach($classifications as $classification)
                                <option value="{{$classification->Classification}}" @if($classification->Classification == Input::get('Classification'))selected @endif>{{$classification->Classification}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">Category</label>
                        <select class="form-control select2me" name="Category">
                            <option value="">---SELECT ONE---</option>
                            @foreach($categories as $category)
                                <option value="{{$category->Category}}" @if($category->Category == Input::get('Category'))selected @endif>{{$category->Category}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">Status</label>
                        <select class="form-control select2me" name="Status">
                            <option value="">---SELECT ONE---</option>
                            @foreach($statuses as $status)
                                <option value="{{$status->ReferenceNo}}" @if($status->ReferenceNo == Input::get('Status'))selected @endif>{{$status->Name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">No. of Rows</label>
                        {{Form::select('Limit',array(10=>10,20=>20,30=>30,'All'=>'All'),Input::has('Limit')?Input::get('Limit'):20,array('class'=>'form-control'))}}
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
		    <table class="table table-bordered table-striped table-condensed flip-content" id="contractorsdzongkhag-table">
			<thead class="flip-content">
                <tr>
                    <th>Sl.No.</th>
                    <th>CDB No.</th>
                    <th>Firm</th>
                    <th>Start Date</th>
                    <th>Comp. Date</th>
                    <th>Name</th>
                    <th>Agency</th>
                    <th>Class</th>
                    <th>Category</th>
                    <th>Work Value</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reportData as $value)
                    <tr>
                        <td>{{$start++}}</td>
                        <td>{{$value->CDBNo}}</td>
                        <td>{{$value->Contractor}}</td>
                        <td>{{convertDateToClientFormat($value->WorkStartDate)}}</td>
                        <td>{{convertDateToClientFormat($value->WorkCompletionDate)}}</td>
                        <td>{{strip_tags($value->NameOfWork)}}</td>
                        <td>{{$value->ProcuringAgency}}</td>
                        <td>{{$value->classification}}</td>
                        <td>{{$value->ProjectCategory}}</td>
                        <td>{{$value->FinalAmount}}</td>
                    </tr>
                @empty
                    <tr><td colspan="10" class="text-center font-red">No data to display</td></tr>
                @endforelse
			</tbody>
		</table>
        <?php pagination($noOfPages,Input::all(),Input::get('page'),"contractorrpt.categorywisereport"); ?>
	</div>
</div>
@stop