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
                        <span class="caption-subject">Import Corporate Engineers from Excel Sheet</span>
                    </div>
                </div>
                <div class="portlet-body form">
                    <div class="note note-info">
                        <strong>The excel file must be of the following format with no empty rows in between or before the table/list. The first row should be heading.:</strong><br><br>
                        <table class="dont-flip table table-bordered">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>CID No</th>
                                    <th>Position Title</th>
                                    <th>Agency</th>
                                    <th>Qualification</th>
                                    <th>Trade</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th>Dorji</th>
                                    <th>1185949494</th>
                                    <th>Engineer</th>
                                    <th>PHPA</th>
                                    <th>Degree</th>
                                    <th>Civil</th>
                                </tr>
                                <tr>
                                    <th>Pema</th>
                                    <th>11795999382</th>
                                    <th>Engineer</th>
                                    <th>PHPA</th>
                                    <th>Diploma</th>
                                    <th>Electrical</th>
                                </tr>
                            </tbody>
                        </table>
                        There should be only one sheet in the excel file.
                    </div>
                    <div class="form-body">
                        {{ Form::open(array('url' =>'etoolsysadm/savecorporateengineer','role'=>'form','method'=>'post','files'=>'true')) }}
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
                    <a href="{{URL::to("etoolsysadm/manageengprofile")}}" class="btn purple btn-sm"><i class="m-icon-swapleft m-icon-white"></i> Back</a>
                </div>
            </div>
        </div>
    </div>
    </div>
@stop