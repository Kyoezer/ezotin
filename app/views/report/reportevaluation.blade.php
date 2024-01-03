@extends('master')
@section('pagescripts')
    {{HTML::script('assets/global/scripts/report.js')}}
@stop
@section('content')
    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption">
                <?php $parameters = Input::all(); $parameters['export']='excel'; ?>
                <i class="fa fa-cogs"></i>Evaluation (Report)&nbsp;&nbsp;@if(!Input::has('export')) <a href="{{route('etoolrpt.'.Request::segment(2),$parameters)}}" class="btn btn-sm green"><i class="fa fa-file-excel-o"></i> Export to Excel</a>    <?php $parameters['export'] = 'print'; ?><a href="{{route('etoolrpt.'.Request::segment(2),$parameters)}}" class="btn btn-sm purple" target="_blank"><i class="fa fa-print"></i> Print</a>@endif
            </div>
        </div>
        <div class="portlet-body flip-scroll">
            @if(Input::get('export') != 'print')
            {{Form::open(array('url'=>Request::url(),'method'=>'get'))}}
            <div class="form-body">
                {{--Start--}}
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">Procuring Agency</label>
                            <select class="form-control select2me" name="Agency">
                                <option value="">All</option>
                                @foreach($procuringAgencies as $procuringAgency)
                                    <option value="{{$procuringAgency->Name}}" @if($procuringAgency->Name == Input::get('Agency'))selected @endif>{{$procuringAgency->Name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label class="control-label">Class</label>
                            <select class="form-control select2me" name="Class">
                                <option value="">All</option>
                                @foreach($classes as $class)
                                    <option value="{{$class->Name}}" @if($class->Name == Input::get('Class'))selected @endif>{{$class->Code}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label class="control-label">Category</label>
                            <select class="form-control select2me" name="Category">
                                <option value="">All</option>
                                @foreach($categories as $category)
                                    <option value="{{$category->Name}}" @if($category->Name == Input::get('Category'))selected @endif>{{$category->Code}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-1">
                        <div class="form-group">
                            <label class="control-label">Status</label>
                            <select class="form-control select" name="Status">
                                <option value="">All</option>
                                @foreach($statuses as $status)
                                <option value="{{$status->Id}}" @if($status->Id == Input::get('Id'))selected @endif>{{$status->Name}}</option>
                                @endforeach
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
                            <th>Name of Work</th>
                            <th>Agency</th>
                            <th>CDB No.</th>
                            <th>Class</th>
                            <th>Category</th>
                            <th>Awarded Amount</th>
                            <th width="7%">Start Date</th>
                            <th width="7%">End Date</th>
                            <th>Operation Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $count = 1; $totalAmount = 0; ?>
                    @forelse($reportData as $data)
                        <tr>
                            <td>{{$count}}</td>
                            <td>{{$data->WorkId}}</td>
                            <td>{{strip_tags($data->NameOfWork, '<br><br/><p><ul><li><ol>');}}</td>
                            <td>{{$data->Agency}}</td>
                            <td>{{$data->CDBNo}}</td>
                            <td>{{$data->Class}}</td>
                            <td>{{$data->Category}}</td>
                            <td>{{$data->AwardedAmount?number_format($data->AwardedAmount,2):''}}<?php $totalAmount+=$data->AwardedAmount?$data->AwardedAmount:0; ?></td>
                            <td>{{convertDateToClientFormat($data->ActualStartDate)}}</td>
                            <td>{{convertDateToClientFormat($data->ActualEndDate)}}</td>
                            <td>{{$data->CommencementDateFinal}}</td>
                            <td>{{$data->Status}}</td>
                        </tr>
                        <?php $count++; ?>
                    @empty
                        <tr><td colspan="11" class="font-red text-center">No data to display</td></tr>
                    @endforelse
                        {{--<tr><td colspan="7" class="bold text-right">Total</td><td>{{number_format($totalAmount,2)}}</td><td colspan="3"></td></tr>--}}
                    </tbody>
                </table>
                <?php pagination($noOfPages,Input::all(),Input::get('page'),"etoolrpt.reportevaluation"); ?>
            </div>
        </div>
    </div>
@stop