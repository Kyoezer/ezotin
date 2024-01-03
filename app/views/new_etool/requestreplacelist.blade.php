@extends('master')
@section('pagescripts')
    {{ HTML::script('assets/global/scripts/sys.js') }}
@stop
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption font-green-seagreen">
                        <i class="fa fa-cogs"></i>
                        <span class="caption-subject">Requests for Replace Release </span>
                    </div>
                </div>
                <div class="portlet-body form">
                    <div class="form-body">
                        {{ Form::open(array('url' =>Request::url(),'role'=>'form','method'=>'get','class'=>'novalidate')) }}
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Request Type</label>
                                    {{Form::select('RequestType',array(''=>'SELECT','101'=>'Replace HR','102'=>'Release HR','201'=>'Replace Equipment','202'=>'Release Equipment'),Input::get('RequestType'),array('class'=>'form-control input-sm input-medium'))}}
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Work Id</label>
                                    <input name="WorkId" value="{{Input::get('WorkId')}}" class="form-control input-sm input-medium"/>
                                </div>
                            </div><div class="clearfix"></div>
                            <div class="col-md-2">
                                <div class="btn-set">
                                    <input type="hidden" name="Submit" value="1"/>
                                    <button type="submit" class="btn blue-hoki btn-sm">Search</button>
                                    <a href="{{Request::Url()}}" class="btn grey-cascade btn-sm">Clear</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{Form::close()}}
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-condensed table-striped">
                        <thead>
                            <tr>
                                <th>Sl #</th>
                                <th>Type</th>
                                <th>Request Type</th>
                                <th>Work Id</th>
                                <th>Agency</th>
                                <th>CDB No.</th>
                                <th>Firm Name</th>
                                <th>Requested By User</th>
                                <th>Request Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $count = 1; ?>
                        @forelse($requests as $request)
                            <tr>
                                <td>{{$start++}}</td>
                                <td>@if(($request->RequestType == 101) || ($request->RequestType == 102)){{"HR"}}@else{{"Equipment"}}@endif</td>
                                <td>@if(($request->RequestType == 101) || ($request->RequestType == 201)){{"Replace"}}@else{{"Release"}}@endif</td>
                                <td>{{$request->WorkId}}</td>
                                <td>{{$request->Agency}}</td>
                                <td>{{$request->CDBNo}}</td>
                                <td>{{$request->NameOfFirm}}</td>
                                <td>{{$request->FullName.' ('.$request->username.')'}}</td>
                                <td>{{convertDateToClientFormat($request->RequestDate)}}</td>
                                <td><a href="{{URL::to("etoolsysadm/processrrrequest/$request->Id")}}" class="btn btn-xs green"><i class="fa fa-edit"></i> Process</a></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center font-red">No requests!</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>

                </div>
                <?php pagination($noOfPages,Input::all(),Input::get('page'),"replacereleaselist"); ?>
            </div>

        </div>
    </div>
    </div>
@stop