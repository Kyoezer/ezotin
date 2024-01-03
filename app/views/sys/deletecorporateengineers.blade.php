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
                        <span class="caption-subject">Delete Corporate Engineers</span>
                    </div>
                </div>
                <div class="portlet-body form">
                    <div class="note note-info">
                        <strong>Select an agency whose engineers you want to delete, and click the delete button.</strong>
                    </div>
                    <div class="form-body">
                        {{ Form::open(array('url' =>'etoolsysadm/deletecorporateengineer','role'=>'form','method'=>'post','files'=>'true')) }}
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Agency</label>
                                    <select name="Agency" class="form-control input-sm required" autocomplete="off">
                                        <option value="">---SELECT---</option>
                                        @foreach($agencies as $agency)
                                            <option value="{{$agency->Agency}}">{{$agency->Agency}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label>&nbsp;</label>
                                <div class="btn-set">
                                    <button type="submit" class="btn red btn-sm">Delete</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{Form::close()}}
                    <a href="{{URL::to("etoolsysadm/manageengprofile")}}" class="btn purple btn-sm"><i class="m-icon-swapleft m-icon-white"></i> Back</a>
                </div>
            </div>
        </div>
    </div>
    </div>
@stop