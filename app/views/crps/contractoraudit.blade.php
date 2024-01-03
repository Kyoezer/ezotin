@extends('master')
@section('pagescripts')
    {{ HTML::script('assets/global/scripts/contractor.js') }}
@stop
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption font-green-seagreen">
                        <i class="fa fa-cogs"></i>
                        <span class="caption-subject">Import Contractors/Consultants Audit Details</span>
                    </div>
                </div>
                <div class="portlet-body form">
                    <div class="note note-info">
                        <strong>Download template below: <br>

                        <a href="{{asset('uploads/AuditClearance_sample.xlsx')}}" class="btn btn-xs green"><i class="fa fa-download"></i> Template</a>
                    </div>
                    <div class="form-body">
                        {{ Form::open(array('url' =>'contractor/saveaudit','role'=>'form','method'=>'post','files'=>'true')) }}
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Upload file (xlsx or xls format)</label>
                                    <input type="file" name="Excel" value="{{Input::get('CIDNo')}}" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" class="form-control input-sm"/>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label>&nbsp;</label>
                                <div class="btn-set">
                                    <button type="submit" class="btn blue-hoki btn-sm">Upload</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{Form::close()}}
                    <a href="{{URL::to("contractor/auditmemo")}}" class="btn purple btn-sm"><i class="m-icon-swapleft m-icon-white"></i> Back</a>
                </div>
            </div>
        </div>
    </div>
    </div>
@stop