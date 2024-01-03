@extends('reportsmaster')
@section('pagescripts')
    {{HTML::script('assets/global/scripts/report.js')}}
@stop
@section('content')
    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption">
                <?php $parameters = Input::all(); $parameters['export']='excel'; ?>
                <i class="fa fa-cogs"></i>List of Terminated/Cancelled &nbsp;&nbsp;@if(!Input::has('export')) <a href="{{route('etoolrpt.listofterminatedcancelled',$parameters)}}" class="btn btn-sm green"><i class="fa fa-file-excel-o"></i> Export to Excel</a>    <?php $parameters['export'] = 'print'; ?><a href="{{route('etoolrpt.listofterminatedcancelled',$parameters)}}" class="btn btn-sm purple" target="_blank"><i class="fa fa-print"></i> Print</a>@endif
            </div>
        </div>
        <div class="portlet-body flip-scroll">
            @if(Input::get('export') != 'print')
            {{Form::open(array('url'=>Request::url(),'method'=>'get'))}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">Status</label>
                            <select class="form-control select2me" name="Status">
                                <option value="">---SELECT ONE---</option>
                                @foreach($statuses as $status)
                                    <option value="{{$status->Name}}" @if($status->Name == Input::get('Status'))selected @endif>{{$status->Name}}</option>
                                @endforeach
                            </select>
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
            @else
                <b>Status: {{Input::get('Status')}}</b> <br/>
                <b>From Date: {{Input::get('FromDate')}}</b><br/>
                <b>To Date: {{Input::get('ToDate')}}</b>
            @endif
                <table class="table table-bordered table-striped table-condensed flip-content" id="">
                    <thead class="flip-content">
                        <tr>
                            <th>Sl.No.</th>
                            <th>Procuring Agency</th>
                            <th>Work Id</th>
                            <th>Name of Work</th>
                            <th>Contract Period</th>
                            <th>Contractors</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($reportData as $data)
                        <tr>
                            <td>{{$start++}}</td>
                            <td>{{$data->ProcuringAgency}}</td>
                            <td>{{$data->WorkId}}</td>
                            <td>{{$data->NameOfWork}}</td>
                            <td>{{$data->ContractPeriod}}</td>
                            <td>{{$data->Contractors}}</td>
                            <td>{{$data->StartDate}}</td>
                            <td>{{$data->EndDate}}</td>
                            <td>{{$data->Status}}</td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="font-red text-center">No data to display</td></tr>
                    @endforelse
                    </tbody>
                </table>
                <?php pagination($noOfPages,Input::all(),Input::get('page'),"etoolrpt.listofterminatedcancelled"); ?>
        </div>
    </div>
@stop