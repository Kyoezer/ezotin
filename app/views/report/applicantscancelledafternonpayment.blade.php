@extends('reportsmaster')
@section('pagescripts')
    {{HTML::script('assets/global/scripts/report.js')}}
@stop
@section('content')
    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption">
                <?php $parameters = Input::all(); $parameters['export']='excel'; ?>
                <i class="fa fa-cogs"></i>Applications cancelled after Non Payment &nbsp;&nbsp;@if(!Input::has('export')) <a href="{{route('ezotinrpt.applicantscancelledafternonpayment',$parameters)}}" class="btn btn-sm green"><i class="fa fa-file-excel-o"></i> Export to Excel</a>    <?php $parameters['export'] = 'print'; ?><a href="{{route('ezotinrpt.applicantscancelledafternonpayment',$parameters)}}" class="btn btn-sm purple" target="_blank"><i class="fa fa-print"></i> Print</a>@endif
            </div>
        </div>
        <div class="portlet-body flip-scroll">
            @if(Input::get('export') != "print")
            {{Form::open(array('url'=>Request::url(),'method'=>'get'))}}
            <div class="form-body">
                <div class="row">
                    {{--<div class="col-md-2">--}}
                        {{--<label class="control-label">Dropped From</label>--}}
                        {{--<div class="input-icon right">--}}
                            {{--<i class="fa fa-calendar"></i>--}}
                            {{--<input type="text" name="FromDate" class="form-control input-sm datepicker" value="{{Input::get('FromDate')}}" readonly="readonly" placeholder="">--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="col-md-2">--}}
                        {{--<label class="control-label">To</label>--}}
                        {{--<div class="input-icon right">--}}
                            {{--<i class="fa fa-calendar"></i>--}}
                            {{--<input type="text" name="ToDate" class="form-control input-sm datepicker" value="{{Input::get('ToDate')}}" readonly="readonly" placeholder="">--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    <div class="col-md-2">
                        <label class="control-label">CDB No.</label>
                            <input type="text" name="CDBNo" class="form-control input-sm" value="{{Input::get('CDBNo')}}" placeholder="">
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="control-label" for="Type">Type</label>
                            {{Form::select('Type',array(''=>'All','1'=>'Contractor','2'=>'Consultant','3'=>'Architect','5'=>'Engineer','4'=>'Specialized Trade'),Input::get('Type'),array('class'=>'form-control input-sm','id'=>'Type'))}}
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
                        <th>Sl #</th>
                        <th>Applicant Name / Name of Firm</th>
                        <th>CDB No.</th>
                        <th>Application No.</th>
                        <th>Payment Approved On</th>
                        <th>Application Cancelled On</th>
                        <th>Notification</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($reportData as $data)
                        <tr>
                            <td>{{$start++}}</td>
                            <td>{{$data->Applicant}} <br/>
                                @if((int)$data->TypeCode == 1)
                                    (Contractor)
                                @elseif((int)$data->TypeCode == 2)
                                    (Consultant)
                                @elseif((int)$data->TypeCode == 3)
                                    (Architect)
				@elseif((int)$data->TypeCode == 5)
                                    (Engineer)
                                @else
                                    (Specialized Trade)
                                @endif
                            </td>                           </td>
                            <td>{{$data->CDBNo}}</td>
                            <td>{{$data->ReferenceNo}}</td>
                            <?php
                                $date = $data->DateOfNotification;
                                $NewDate = new DateTime($date);
                                $NewDate->modify("-15 days");
                                $oldDate = new DateTime($date);
                                $oldDate->modify("+15 days");

                            ?>
                            <td>{{$data->PaymentApprovedDate}}</td>
                            <td>{{$oldDate->format('Y-m-d')}}</td>
                            <td>{{$data->DateOfNotification}}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center font-red">No data to display</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <?php pagination($noOfPages,Input::all(),Input::get('page'),"ezotinrpt.applicantscancelledafternonpayment"); ?>
        </div>
    </div>
@stop