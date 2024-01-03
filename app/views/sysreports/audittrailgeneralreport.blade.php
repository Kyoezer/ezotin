@extends('reportsmaster')
@section('content')
    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-cogs"></i>Audit Trail for Data Manupulation
            </div>
        </div>
        <div class="portlet-body">
            {{Form::open(array('url'=>Request::url(),'method'=>'get'))}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-1">
                        <div class="form-group">
                            <label class="control-label">Action <i class="font-red bold">*</i></label>
                            <select name="UserAction" class="form-control select2me">
                                <option value="">------</option>
                                <option value="U" @if(Input::get('UserAction')=="U")selected="selected"@endif>Edit</option>
                                <option value="D" @if(Input::get('UserAction')=="D")selected="selected"@endif>Delete</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label class="control-label">Table</label>
                            <select name="DBTableName" class="form-control select2me">
                                <option value="">---SELECT ONE---</option>
                                @foreach($dbtableNames as $dbtableName)
                                <option value="{{$dbtableName->TableName}}" @if(Input::get('DBTableName')==$dbtableName->TableName)selected="selected"@endif>{{$dbtableName->TableName.' ('.$dbtableName->TableComments.')'}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="control-label">Work Id.</label>
                            <input type="text" name="WorkId" value="{{Input::get("WorkId")}}" class="form-control"/>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">User</label>
                            <select name="SysUserId" class="form-control select2me">
                                <option value="">---SELECT ONE---</option>
                                @foreach($users as $user)
                                <option value="{{$user->Id}}" @if(Input::get('SysUserId')==$user->Id)selected="selected"@endif>{{$user->FullName.' ('.$user->username.')'}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label class="control-label">No. of Rows</label>
                            {{Form::select('Limit',array(20=>20,50=>50,100=>100,200=>200,500=>500,'All'=>'All'),Input::has('Limit')?Input::get('Limit'):50,array('class'=>'form-control'))}}
                        </div>
                    </div>
                    <div class="col-md-2">
                        {{--<label class="control-label"></label>--}}
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
                <table class="table table-bordered table-striped table-condensed">
                    <thead>
                    <tr>
                        <th>Date and Time</th>
                        <th>Table Name</th>
                        <th>Row Id</th>
                        <th>User Full Name</th>
                        <th>User Name</th>
                        <th>Column Name</th>
                        <th>Old Value</th>
                        <th>New Value</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php $curDateTime="";$cur ?>
                        @forelse($auditTrails as $auditTrail)
                        <tr>
                            <td>
                                {{convertDateTimeToClientFormat($auditTrail->ActionDate)}}
                            </td>
                            <td>
                                {{$auditTrail->TableName}}
                            </td>
                            <td>
                                {{$auditTrail->RowId}}
                            </td>
                            <td>
                                {{$auditTrail->UserName}}
                            </td>
                            <td>
                                {{$auditTrail->UserEmail}}
                            </td>
                            <td>
                                {{$auditTrail->ColumnName}}
                            </td>
                            <td>
                                {{$auditTrail->OldValue}}
                            </td>
                            <td>
                                {{$auditTrail->NewValue}}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="font-red text-center"><i>No data to display</i></td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop