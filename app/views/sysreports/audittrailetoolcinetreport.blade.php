@extends('reportsmaster')
@section('pagescripts')
    {{HTML::script('assets/global/scripts/report.js')}}
@stop
@section('content')
    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-cogs"></i>Audit Trail for User Activity
            </div>
        </div>
        <div class="portlet-body flip-scroll">
            {{Form::open(array('url'=>Request::url(),'method'=>'get'))}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="control-label">User</label>
                            <select class="form-control select2me" name="User">
                                <option value="">---SELECT ONE---</option>
                                @foreach($users as $user)
                                    <option value="{{$user->Id}}" @if($user->Id == Input::get('User'))selected @endif>{{$user->FullName.' ('.$user->username.')'}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">Procuring Agency</label>
                            <select class="form-control select2me" name="ProcuringAgency">
                                <option value="">---SELECT ONE---</option>
                                @foreach($procuringAgencies as $procuringAgency)
                                    <option value="{{$procuringAgency->Id}}" @if($procuringAgency->Id == Input::get('ProcuringAgency'))selected @endif>{{$procuringAgency->Name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="control-label">Work Id</label>
                            <input type="text" name="WorkId" value="{{Input::get('WorkId')}}" class="form-control"/>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="control-label">From Date</label><div class="clearfix"></div>
                            <div class="input-icon right">
                                <i class="fa fa-calendar"></i>
                                 <?php
                                    $date = date_create(date('Y-m-d G:i:s'));
                                    date_sub($date, date_interval_create_from_date_string('7 days'));
                                ?>
                                <input type="text" name="FromDate" class="form-control datepicker" value="{{Input::has('FromDate')?Input::get('FromDate'):date_format($date,'d-m-Y')}}" readonly="readonly" placeholder="">
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
                    <div class="col-md-1">
                        <div class="form-group">
                            <label class="control-label">No. of Rows</label>
                            {{Form::select('Limit',array(20=>20,50=>50,100=>100,200=>200,500=>500,'All'=>'All'),Input::has('Limit')?Input::get('Limit'):50,array('class'=>'form-control'))}}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <!-- <label class="control-label">|</label> -->
                        <div class="btn-set">
                            <button type="submit" class="btn blue-hoki btn-sm">Search</button>
                            <a href="{{Request::url()}}" class="btn grey-cascade btn-sm">Clear</a>
                        </div>
                    </div>
                </div>

            </div>
            {{Form::close()}}  <br/>
            <p class="font-red"><i>Date and Time are in 24 hour format.</i></p>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-condensed" id="">
                    <thead>
                    <tr>
                        <th>Date and Time</th>
                        <th>User</th>
                        <th>Agency</th>
                        <th>Work Id</th>
                        <th>Action</th>
                        <th>Message Displayed</th>
                        <th>Remarks</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($reportData as $data)
                        <tr>
                            <td>{{convertDateTimeToClientFormat($data->ActionDate)}}</td>
                            <td>{{$data->User.' ('.$data->UserEmail.')'}}</td>
                            <td>{{$data->Agency}}</td>
                            <td>{{$data->WorkId}}</td>
                            <td>{{$data->IndexAction}}</td>
                            <td>
                                {{$data->MessageDisplayed or '-'}}
                            </td>
                            <td>
                                {{$data->Remarks or '-'}}
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="font-red text-center">No data to display</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop