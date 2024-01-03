@extends('reportsmaster')
@section('pagescripts')
    {{HTML::script('assets/global/scripts/report.js')}}
@stop
@section('content')
    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption">
                <?php $parameters = Input::all(); $parameters['export']='excel'; ?>
                <i class="fa fa-cogs"></i>Audit Memo Report (Dropped) &nbsp;&nbsp;@if(!Input::has('export')) <a href="{{route('ezotinrpt.auditdroppedmemos',$parameters)}}" class="btn btn-sm green"><i class="fa fa-file-excel-o"></i> Export to Excel</a>    <?php $parameters['export'] = 'print'; ?><a href="{{route('ezotinrpt.auditdroppedmemos',$parameters)}}" class="btn btn-sm purple" target="_blank"><i class="fa fa-print"></i> Print</a>@endif
            </div>
        </div>
        <div class="portlet-body flip-scroll">
            @if(Input::get('export') != "print")
            {{Form::open(array('url'=>Request::url(),'method'=>'get'))}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-2">
                        <label class="control-label">Dropped From</label>
                        <div class="input-icon right">
                            <i class="fa fa-calendar"></i>
                            <input type="text" name="FromDate" class="form-control input-sm datepicker" value="{{Input::get('FromDate')}}" readonly="readonly" placeholder="">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label class="control-label">To</label>
                        <div class="input-icon right">
                            <i class="fa fa-calendar"></i>
                            <input type="text" name="ToDate" class="form-control input-sm datepicker" value="{{Input::get('ToDate')}}" readonly="readonly" placeholder="">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label class="control-label">CDB No.</label>
                            <input type="text" name="CDBNo" class="form-control input-sm" value="{{Input::get('CDBNo')}}" placeholder="">
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="control-label" for="Type">Type</label>
                            {{Form::select('Type',array(''=>'All','1'=>'Contractor','2'=>'Consultant'),Input::get('Type'),array('class'=>'form-control input-sm','id'=>'Type'))}}
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
                @foreach($parametersForPrint as $key=>$value)
                    <b>{{$key}}: {{$value}}</b><br>
                @endforeach
                <br/>
            @endif
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-condensed flip-content" id="">
                    <thead class="flip-content">
                    <tr>
                        <th>Name of Firm</th>
                        <th>CDB No.</th>
                        <th>Agency</th>
                        <th>Audited Period</th>
                        <th>AIN</th>
                        <th>Para No.</th>
                        <th>Audit Observation</th>
                        <th>Dropped by</th>
                        <th>Dropped on</th>
                        <th>Remarks</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($reportData as $data)
                        <tr>
                            <td>{{$data->NameOfFirm}}</td>
                            <td>{{$data->CDBNo}}</td>
                            <td>{{$data->Agency}}</td>
                            <td>{{$data->AuditedPeriod}}</td>
                            <td>{{$data->AIN}}</td>
                            <td>{{$data->ParoNo}}</td>
                            <td>{{$data->AuditObservation}}</td>
                            <td>{{$data->Dropper}}</td>
                            <td>{{convertDateToClientFormat($data->DroppedDate)}}</td>
                            <td>{{$data->Remarks}}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center font-red">No data to display</td>
                        </tr>
                    @endforelse
                    </tbody>

                </table>

            </div>
            <?php pagination($noOfPages,Input::all(),Input::get('page'),"ezotinrpt.auditdroppedmemos"); ?>
        </div>
    </div>
@stop