@extends('reportsmaster')
@section('pagescripts')
    {{HTML::script('assets/global/scripts/report.js')}}
@stop
@section('content')
    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption">
                <?php $parameters = Input::all(); $parameters['export']='excel'; ?>
                <i class="fa fa-cogs"></i>Replace/Release Report &nbsp;&nbsp;@if(!Input::has('export')) <a href="{{route('contractorrpt.constructionpersonnel',$parameters)}}" class="btn btn-sm green"><i class="fa fa-file-excel-o"></i> Export to Excel</a>    <?php $parameters['export'] = 'print'; ?><a href="{{route('contractorrpt.constructionpersonnel',$parameters)}}" class="btn btn-sm purple" target="_blank"><i class="fa fa-print"></i> Print</a>@endif
            </div>
        </div>
        <div class="portlet-body dont-flip">
            {{Form::open(array('url'=>Request::url(),'method'=>'get'))}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="control-label">Work Id</label>
                            <input type="text" class="form-control input-sm" value="{{Input::get('WorkId')}}" name="WorkId"/>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="control-label">CDB No</label>
                            <input type="text" class="form-control input-sm" value="{{Input::get('CDBNo')}}" name="CDBNo"/>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label class="control-label">From</label>
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
                        <div class="form-group">
                            <label class="control-label">Operation</label>
                            {{Form::select('Type',array(''=>'All','Replace'=>'Replace','Release'=>'Release'),Input::get('Type'),array('class'=>'form-control input-sm'))}}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="control-label">User</label>
                            <select name="User" class="form-control input-sm select2me">
                                <option value="">All</option>
                                @foreach($users as $user)
                                    <option value="{{$user->User}}" @if($user->User == Input::get('User'))selected="selected"@endif>{{$user->User}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @if(!Input::has('export'))
                        <div class="col-md-2">
                            <div class="btn-set">
                                <button type="submit" class="btn blue-hoki btn-sm">Search</button>
                                <a href="{{Request::url()}}" class="btn grey-cascade btn-sm">Clear</a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            {{Form::close()}}
            <br>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-condensed dont-flip" id="">
                    <thead class="dont-flip">
                    <tr>
                        <th>Sl #</th>
                        <th>User</th>
                        <th>Work Id</th>
                        <th>CDB No</th>
                        <th>Old CID No/Registration No</th>
                        <th>New CID No/Registration No</th>
                        <th>Operation</th>
                        <th>Date</th>
                        <th>Reference Doc</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $count = 1;?>
                    @forelse($reportData as $data)
                        <tr>
                            <td>{{$count++}}</td>
                            <td>{{$data->User}}</td>
                            <td>{{$data->WorkId}}</td>
                            <td>{{$data->CDBNo}}</td>
                            <td>{{$data->HrEqOldId}}</td>
                            <td>{{$data->HrEqNewId}}</td>
                            <td>{{$data->Operation}}</td>
                            <td>{{convertDateTimeToClientFormat($data->Date)}}</td>
                            <td>{{$data->RefDoc}}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center font-red">No data to display</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
                <?php pagination($noOfPages,Input::all(),Input::get('page'),"etoolrpt.replacereleasereport"); ?>
            </div>
        </div>

    </div>
@stop