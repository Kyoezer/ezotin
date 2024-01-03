@extends('reportsmaster')
@section('pagescripts')
@stop
@section('content')
    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption">
                <?php $parameters = Input::all(); $parameters['export']='excel'; ?>
                <i class="fa fa-cogs"></i>Evaluation Track&nbsp;&nbsp;@if(!Input::has('export')) <a href="{{route('etoolrpt.'.Request::segment(2),$parameters)}}" class="btn btn-sm green"><i class="fa fa-file-excel-o"></i> Export to Excel</a>    <?php $parameters['export'] = 'print'; ?><a href="{{route('etoolrpt.'.Request::segment(2),$parameters)}}" class="btn btn-sm purple" target="_blank"><i class="fa fa-print"></i> Print</a>@endif
            </div>
        </div>
        <div class="portlet-body flip-scroll">
            @if(Input::get('export') != 'print')
            {{Form::open(array('url'=>Request::url(),'method'=>'get'))}}
            <div class="form-body">
                {{--Start--}}
                <div class="row">
             
                  

                    <div class="col-md-1">
                        <div class="form-group">
                            <label class="control-label">Status</label>
                            <select class="form-control select" name="Status">
                                <option value="">All</option>
                                <option value="Award">Award</option>
                                <option value="Completed">Completed</option>
                                <option value="Completion">Completion</option>
                                <option value="Process">Process</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="control-label">From Date</label><div class="clearfix"></div>
                            <div class="input-icon right">
                                <i class="fa fa-calendar"></i>
                                <input type="text" name="FromDate" class="form-control datepicker" value="{{Input::get('FromDate')}}" readonly="readonly" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
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
                {{--END--}}
            {{Form::close()}}
            @else
                <b>Procuring Agency: {{Input::has('Agency')?Input::get('Agency'):'All'}}</b><br/>
                <b>From Date: {{Input::get('FromDate')}}</b><br/>
                <b>To Date: {{Input::has('ToDate')?Input::get('ToDate'):date('d-M-Y')}}</b><br/>
                <b>Source: {{(Input::get('TenderSource') == 0)?"All":((Input::get('TenderSource') == 1)?'Etool':'CiNet')}}</b>
            <br/>
            @endif
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-condensed flip-content" id="">
                    <thead class="flip-content">
                        <tr>
                            <th>Sl. No.</th>
                            <th>Work Id</th>
                      
                 
                            <th>Operation Time</th>
                            <th>Operation</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $count = 1; $totalAmount = 0; ?>
                    @forelse($reportData as $data)
                        <tr>
                            <td>{{$count}}</td>
                            <td>{{$data->WorkId}}</td>
                     
                            <td>{{$data->OperationTime}}</td>
                            <td>{{$data->Operation}}</td>
                          
                      
                        </tr>
                        <?php $count++; ?>
                    @empty
                        <tr><td colspan="11" class="font-red text-center">No data to display</td></tr>
                    @endforelse
                        {{--<tr><td colspan="7" class="bold text-right">Total</td><td>{{number_format($totalAmount,2)}}</td><td colspan="3"></td></tr>--}}
                    </tbody>
                </table>
                <?php pagination($noOfPages,Input::all(),Input::get('page'),"etoolrpt.evaluationtrack"); ?>
            </div>
        </div>
    </div>
@stop