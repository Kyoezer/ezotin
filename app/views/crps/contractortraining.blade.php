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
                        <span class="caption-subject">Import Contractors who have attended Training</span>
                    </div>
                </div>
                <div class="portlet-body form">
                    <div class="note note-info">
                        <br><strong>USE THE TEMPLATES BELOW</strong>
                        <br><br>
                        <a href="{{asset('uploads/Refreshers_sample.xlsx')}}" class="btn btn-xs green"><i class="fa fa-download"></i> Template for Refresher's Course</a>
                        &nbsp;
                        <a href="{{asset('uploads/Induction_sample.xlsx')}}" class="btn btn-xs purple"><i class="fa fa-download"></i> Template for Induction's Course</a>
                    </div>
                    <div class="form-body">
                        {{ Form::open(array('url' =>'contractor/savetraining','role'=>'form','method'=>'post','files'=>'true')) }}
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Training Type</label>
                                    <select name="CmnTrainingTypeId" id="training-type" class="form-control input-sm input-medium required">
                                        <option value="">SELECT</option>
                                        @foreach($trainingTypes as $trainingType)
                                            <option value="{{$trainingType->Id}}" data-reference="{{$trainingType->ReferenceNo}}">{{$trainingType->Name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Modules</label>
                                    <select name="CmnTrainingModuleId" id="training-module" disabled="disabled" class="form-control input-sm input-medium">
                                        <option value="">SELECT</option>
                                        @foreach($trainingModules as $trainingModule)
                                            <option value="{{$trainingModule->Id}}">{{$trainingModule->Name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-3" style="padding-left: 0; padding-right: 0;">
                                    <label>Training Dates</label>
                                    <div class="input-group input-large date-picker input-daterange" data-date-format="mm/dd/yyyy">
                                        <input type="text" class="form-control input-sm required" name="TrainingFromDate">
                                        <span class="input-group-addon input-sm"> to </span>
                                        <input type="text" class="form-control input-sm required" name="TrainingToDate">
                                    </div>
                                </div>
                            </div>
                        </div>
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
                    <a href="{{URL::to("contractor/training")}}" class="btn purple btn-sm"><i class="m-icon-swapleft m-icon-white"></i> Back</a>
                </div>
            </div>
        </div>
    </div>
    </div>
@stop