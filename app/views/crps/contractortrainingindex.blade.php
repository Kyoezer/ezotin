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
                        <span class="caption-subject">Contractor Training</span>
                        <a href="{{URL::to('contractor/addtraining')}}" class="btn btn-xs green"><i class="fa fa-plus"></i> Add New</a>
                        <a href="{{URL::to('contractor/deletetraining')}}" class="btn btn-xs purple"><i class="fa fa-times"></i> Delete</a>
                    </div>
                </div>
                <div class="portlet-body form">
                    <div class="form-body">
                        {{ Form::open(array('url' =>'contractor/training','role'=>'form','method'=>'get','class'=>'novalidate')) }}
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Training Type</label>
                                    <select name="CmnTrainingTypeId" class="form-control input-sm input-medium">
                                        <option value="">SELECT</option>
                                        @foreach($trainingTypes as $trainingType)
                                            <option value="{{$trainingType->Id}}" @if($trainingType->Id == Input::get('CmnTrainingTypeId'))selected="selected"@endif data-reference="{{$trainingType->ReferenceNo}}">{{$trainingType->Name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Modules</label>
                                    <select name="CmnTrainingModuleId" class="form-control input-sm input-medium">
                                        <option value="">SELECT</option>
                                        @foreach($trainingModules as $trainingModule)
                                            <option value="{{$trainingModule->Id}}" @if($trainingModule->Id == Input::get('CmnTrainingModuleId'))selected="selected"@endif>{{$trainingModule->Name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Year</label>
                                    <select class="form-control input-sm input-medium" name="Year">
                                        <option value="">SELECT</option>
                                        @if(count($trainingYears)>0)
                                            <?php $firstYear = $trainingYears[0] - 1; ?>
                                            <option value="{{$firstYear}}-07-01 to {{$trainingYears[0]}}-06-30" @if("$firstYear-07-01 to ".($trainingYears[0])."-06-30" == Input::get('Year'))selected="selected"@endif>FY {{$firstYear}} to {{$trainingYears[0]}}</option>
                                        @endif
                                        @foreach($trainingYears as $year)
                                            <option value="{{$year}}-07-01 to {{$year+1}}-06-30" @if("$year-07-01 to ".($year+1)."-06-30" == Input::get('Year'))selected="selected"@endif>FY {{$year}} to {{$year+1}}</option>
                                        @endforeach
                                    </select>
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
                @if(!empty($trainings))
                <div class="table-responsive">
                    <table class="table table-bordered table-condensed table-striped">
                        <thead>
                            <tr>
                                <th>Sl #</th>
                                <th>Training Type</th>
                                <th>Module</th>
                                <th>Training Date</th>
                                <th>No. of Participants</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $count = 1; ?>
                            @forelse($trainings as $training)
                                <tr>
                                    <td>{{$count++}}</td>
                                    <td>{{$training->Training}}</td>
                                    <td>{{$training->Module}}</td>
                                    <td>{{$training->TrainingFromDate." to ".$training->TrainingToDate}}</td>
                                    <td>{{$training->ParticipantCount}}</td>
                                    <td class="text-center"><a href="{{URL::to('contractor/trainingdetails/'.$training->Id)}}" class="btn btn-xs green"><i class="fa fa-eye"></i> View</a></td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="font-red text-center" colspan="6">No data to display!</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <?php pagination($noOfPages,Input::all(),Input::get('page'),"contractor.training"); ?>
                @endif
            </div>
        </div>
    </div>
    </div>
@stop