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
                        <span class="caption-subject">Delete Training</span>
                    </div>
                </div>
                <div class="portlet-body form">
                    <div class="note note-info">
                        <strong>Select the training you want to delete, and click the delete button.</strong>
                    </div>
                    <div class="form-body">
                        {{ Form::open(array('url' =>'contractor/deletetraining','role'=>'form','method'=>'post','files'=>'true')) }}
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Trainings so far</label>
                                    <select name="CrpContractorTrainingId" class="form-control select2me input-sm required" autocomplete="off">
                                        <option value="">---SELECT---</option>
                                        @foreach($trainings as $training)
                                            <option value="{{$training->Id}}">{{$training->Training}} @if((bool)$training->Module){{" ($training->Module)"}}@endif [{{$training->TrainingFromDate}} to {{$training->TrainingToDate}}]</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label>&nbsp;</label>
                                <div class="btn-set">
                                    <button type="submit" class="btn red btn-sm deleteaction">Delete</button>
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