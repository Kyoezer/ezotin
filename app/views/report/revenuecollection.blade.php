@extends('reportsmaster')
@section('pagescripts')
    {{HTML::script('assets/global/scripts/report.js')}}
@stop
@section('content')
    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption">
                <?php $parameters = Input::all(); $parameters['export']='excel'; if((int)$summary == 1): $route ="rpt.revenuecollectionreport"; else: $route="rpt.revenuecollectiondetailed"; endif; ?>
                <i class="fa fa-cogs"></i>Revenue Collection Report {{$reportAppend}}&nbsp;&nbsp;@if(!Input::has('export')) <a href="{{route($route,$parameters)}}" class="btn btn-sm green"><i class="fa fa-file-excel-o"></i> Export to Excel</a>    <?php $parameters['export'] = 'print'; ?><a href="{{route($route,$parameters)}}" class="btn btn-sm purple" target="_blank"><i class="fa fa-print"></i> Print</a>@endif
            </div>
        </div>
        <div class="portlet-body flip-scroll">
            @if(Input::get('export') != 'print')
                {{Form::open(array('url'=>Request::url(),'method'=>'get'))}}
                <div class="form-body">
                    <div class="row">
                        @if((int)$summary == 0)
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Receipt No.</label>
                                    <input type="text" name="ReceiptNo" class="form-control input-sm" value="{{Input::get('ReceiptNo')}}">
                                </div>
                            </div>
                        @endif
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Date Between</label>
                                <div class="input-group input-large date-picker input-daterange">
                                    <input type="text" name="FromDate" class="input-sm form-control datepicker" value="{{Input::get("FromDate")}}" />
                                        <span class="input-group-addon input-sm">
                                        to </span>
                                    <input type="text" name="ToDate" class="input-sm form-control datepicker" value="{{Input::get("ToDate")}}" />
                                </div>
                            </div>
                        </div>
                        @if((int)$summary == 0)
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Applicant Type</label>
                                    {{Form::select("Type",array(''=>'All','1'=>'Contractor','2'=>'Consultant','3'=>'Architect','4'=>'Specialized Firm','5'=>'Surveyor','6'=>'Engineer','7'=>'Specialized Trade',),Input::get('Type'),array('class'=>'form-control input-sm'))}}
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Service Type</label>
                                    <select class="form-control input-sm" name="ServiceId">
                                        <option value="">---SELECT ONE---</option>
                                        @foreach($serviceTypes as $serviceType)
                                            <option value="{{$serviceType->Id}}" @if($serviceType->Id == Input::get('ServiceId'))selected="selected"@endif>{{$serviceType->Name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif
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
                    <table class="table table-bordered table-striped table-condensed flip-content" id="">
                        <thead class="flip-content">
                            <tr>
                                <th>Sl#</th>
                                <th>Applicant Type</th>
                                @if((int)$summary == 0)
                                    <th>Name/CDB No.</th>
                                @endif
                                <th>Service Type</th>
                                @if((int)$summary == 0)
                                    <th>Date Collected</th>
                                    <th>Receipt No.</th>
                                @endif
                                <th>Amount Collected (Nu.)</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $count = 1; $lastApplicantType = '--'; $totalAmount = 0; ?>
                        @forelse($reportData as $data)
                            <tr>
                                <td>{{$count++}}</td>
                                @if((int)$summary == 1)
                                    @if($data->Type != $lastApplicantType)
                                        <td>@if((int)$summary == 1)<a href="{{URL::to('rpt/revenuecollectiondetailed')}}?Type={{$data->TypeCode}}">@endif{{$data->Type}}@if((int)$summary == 1)</a>@endif</td>
                                    @else
                                        <td></td>
                                    @endif
                                @else
                                    <td>@if((int)$summary == 1)<a href="{{URL::to('rpt/revenuecollectiondetailed')}}?Type={{$data->TypeCode}}">@endif{{$data->Type}}@if((int)$summary == 1)</a>@endif</td>
                                @endif
                                    @if((int)$summary == 0)
                                        <td>{{$data->Name}}</td>
                                    @endif
                                    
                                <td>@if((int)$summary == 1)<a href="{{URL::to('rpt/revenuecollectiondetailed')}}?ServiceId={{$data->ServiceId}}">@endif{{$data->ServiceType}}@if((int)$summary == 1)</a>@endif</td>
                                @if((int)$summary == 0)
                                    <td class="text-center">{{convertDateToClientFormat($data->PaymentDate)}}</td>
                                    <td class="text-center">{{$data->PaymentReceiptNo}}</td>
                                @endif
                                <td class="text-right">{{number_format($data->Amount,2)}}<?php $totalAmount+=(int)$data->Amount; ?></td>
                                <?php $lastApplicantType = $data->Type; ?>
                            </tr>
                        @empty

                            <tr><td colspan="@if((int)$summary == 1){{'4'}}@else{{'6'}}@endif" class="font-red text-center">No data to display</td></tr>
                        @endforelse
                        @if(count($reportData)>0)
                            <tr>
                                <td colspan="@if((int)$summary == 1){{'3'}}@else{{'6'}}@endif" class="text-right bold">Total:</td>
                                <td class="text-right">{{number_format($totalAmount,2)}}</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            <div class="clearfix"></div>
        </div>
    </div>
@stop