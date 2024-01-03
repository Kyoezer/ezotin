@extends('master')
@section('pagescripts')
    {{HTML::script('assets/global/scripts/report.js')}}
@stop
@section('content')
    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption">
                <?php $parameters = Input::all(); $parameters['export']='excel'; ?>
                <i class="fa fa-cogs"></i>Tender Uploaded &nbsp;&nbsp;@if(!Input::has('export')) <a href="{{route('etoolrpt.'.Request::segment(2),$parameters)}}" class="btn btn-sm green"><i class="fa fa-file-excel-o"></i> Export to Excel</a>    <?php $parameters['export'] = 'print'; ?><a href="{{route('etoolrpt.'.Request::segment(2),$parameters)}}" class="btn btn-sm purple" target="_blank"><i class="fa fa-print"></i> Print</a>@endif
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
                                <option value="">---SELECT ONE---</option>
                                @foreach($procuringAgencies as $procuringAgency)
                                    <option value="{{$procuringAgency->Name}}" @if($procuringAgency->Name == Input::get('Agency'))selected @endif>{{$procuringAgency->Name}}</option>
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
                        <div class="form-group">
                            <label class="control-label">Tender Source</label>
                            {{Form::select('TenderSource',array('0'=>'All','1'=>'Etool','2'=>'CiNET'),Input::get('TenderSource'),array('class'=>'form-control'))}}
                        </div>
                    </div>
                    <div class="col-md-2">
                        {{--<label class="control-label">&nbsp;</label>--}}
                        <div class="btn-set">
                            <button type="submit" class="btn blue-hoki btn-sm">Search</button>
                            <a href="{{Request::url()}}" class="btn grey-cascade btn-sm">Clear</a>
                        </div>
                    </div>
                    <br>
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
                <table class="table table-bordered table-striped table-condensed flip-content" id="">
                    <thead class="flip-content">
                        <tr>
                            <th>Sl. No.</th>
                            <th>Name of Work</th>
                            <th>Agency</th>
                            <th>Uploaded Date</th>
                            <th>Last Date of Submission</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($reportData as $data)
                        <tr>
                            <td>{{$start++}}</td>
                            <td>{{strip_tags($data->NameOfWork, '<br><br/><p><ul><li><ol>');}}</td>
                            <td>{{$data->Agency}}</td>
                            <td>{{convertDateToClientFormat($data->UploadedDate)}}</td>
                            <td>{{convertDateToClientFormat($data->LastDateOfSubmission)}}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="font-red text-center">No data to display</td></tr>
                    @endforelse
                    </tbody>
                </table>
                <?php pagination($noOfPages,Input::all(),Input::get('page'),"etoolrpt.tenderuploaded"); ?>
        </div>
    </div>
@stop