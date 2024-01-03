@extends('reportsmaster')
@section('pagescripts')
    {{HTML::script('assets/global/scripts/report.js')}}
@stop
@section('content')
    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption">
                <?php $parameters = Input::all(); $parameters['export']='excel'; ?>
                <i class="fa fa-cogs"></i>@if((int)$type == 1)<?php $route = "etoolrpt.apscontractor"; ?>{{"APS"}}@elseif((int)$type ==2)<?php $route = "etoolrpt.apscontractorontime"; ?>{{"Ontime Completion score"}}@else<?php $route = "etoolrpt.apscontractorquality"; ?>{{"Quality of Execution score"}}@endif of contractors &nbsp;&nbsp;@if(!Input::has('export')) <a href="{{route($route,$parameters)}}" class="btn btn-sm green"><i class="fa fa-file-excel-o"></i> Export to Excel</a>    <?php $parameters['export'] = 'print'; ?><a href="{{route($route,$parameters)}}" class="btn btn-sm purple" target="_blank"><i class="fa fa-print"></i> Print</a>@endif
            </div>
        </div>
        <div class="portlet-body flip-scroll">
            @if(Input::get('export') != 'print')
            {{Form::open(array('url'=>Request::url(),'method'=>'get'))}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="control-label">CDB No.</label>
                            <input type="text" name="CDBNo" class="number form-control" value="{{Input::get('CDBNo')}}">
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
                        <div class="form-group">
                            <label class="control-label">Classification</label>
                            <select class="form-control select2me" name="Class">
                                <option value="">---SELECT ONE---</option>
                                @foreach($classes as $classification)
                                    <option value="{{$classification->Code}}" @if($classification->Code == Input::get('Class'))selected @endif>{{$classification->Code}}</option>
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
                                    <option value="{{$category->Code}}" @if($category->Code == Input::get('Category'))selected @endif>{{$category->Code}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-1">
                        <div class="form-group">
                            <label class="control-label">From</label>
                            <input type="text" name="From" class="required number form-control" value="{{Input::get('From')}}">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label class="control-label">To</label>
                            <input type="text" name="To" class="required number form-control" value="{{Input::get('To')}}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        {{--<label class="control-label">&nbsp;</label>--}}
                        <div class="btn-set">
                            <button type="submit" class="btn blue-hoki btn-sm">Search</button>
                            <a href="{{Request::url()}}" class="btn grey-cascade btn-sm">Clear</a>
                        </div>
                    </div>
                </div>
            </div>
            {{Form::close()}}
            @else
                <b>From Date: {{Input::get('FromDate')}}</b> <br/>
                <b>To Date: {{Input::get('ToDate')}}</b>
                <b>APS Score From: {{Input::get('From')}}</b>
                <b>To: {{Input::get('To')}}</b>
            @endif
            @if(Input::has('From'))
                <div class="col-md-12">
                    <table class="table table-bordered table-striped table-condensed flip-content" id="">
                        <thead class="flip-content">
                        <tr>
                            <th>Sl.No.</th>
                            <th>CDB No.</th>
                            <th>Contractor</th>
                            <th>Year</th>
                            <th>Agency</th>
                            <th>Work Id</th>
                            <th>Work Name</th>
                            <th>Category</th>
                            <th>Awarded Amount</th>
                            <th>Final Amount</th>
                            <th>Dzongkhag</th>
                            <th>Status</th>
                            @if((int)$type == 1 || (int)$type == 2)
                                <th>Ontime Completion</th>
                            @endif
                            @if((int)$type == 1 || (int)$type == 3)
                                <th>Quality of Execution</th>
                            @endif
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($reportData as $workDetail)
                            <tr>
                            <td>{{$start++}}</td>
                            <td>{{$workDetail->CDBNo}}</td>
                            <td>{{$workDetail->Contractor}}</td>
                            <td>{{$workDetail->Year}}</td>
                            <td>{{$workDetail->Agency}}</td>
                            <td>{{$workDetail->WorkId}}</td>
                            <td>{{strip_tags($workDetail->NameOfWork)}}</td>
                            <td>{{$workDetail->Category}}</td>
                            <td>{{$workDetail->AwardedAmount}}</td>
                            <td>{{$workDetail->FinalAmount}}</td>
                            <td>{{$workDetail->Dzongkhag}}</td>
                            <td>{{$workDetail->Status}}</td>
                            @if((int)$type == 1 || (int)$type == 2)
                            <td>
                                {{$workDetail->OntimeCompletionScore}}
                            </td>
                            @endif
                            @if((int)$type == 1 || (int)$type == 3)
                            <td>{{$workDetail->QualityOfExecutionScore}}</td>
                            @endif
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <?php pagination($noOfPages,Input::all(),Input::get('page'),$route); ?>
                </div><div class="clearfix"></div>
            @endif
        </div>
    </div>
@stop