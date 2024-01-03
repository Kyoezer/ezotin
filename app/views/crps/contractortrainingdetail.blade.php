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
                        <span class="caption-subject">Training Details</span>
                        {{--<a href="{{URL::to('contractor/addtraining')}}" class="btn btn-xs green"><i class="fa fa-plus"></i> Add New</a>--}}
                        {{--<a href="{{URL::to('contractor/deletetraining')}}" class="btn btn-xs purple"><i class="fa fa-times"></i> Delete</a>--}}
                    </div>
                </div>
                <div class="portlet-body">
                    <strong>Training Type: </strong> {{$training[0]->Training}} <br>
                    @if((int)$training[0]->TrainingReference == 1602)
                        <strong>Module: </strong> {{$training[0]->Module}} <br/>
                    @endif
                    <strong>Training Dates: </strong> {{$training[0]->TrainingFromDate}} to {{$training[0]->TrainingToDate}} <br/>
                    <br>
                <div class="table-responsive">
                    <table class="table table-bordered table-condensed table-striped">
                        <thead>
                            <tr>
                                <th>Sl #</th>
                                @if((int)$training[0]->TrainingReference == 1602)
                                    <th>CDB No.</th>
                                @endif
                                <th>Participant</th>
                                <th>CID No</th>
                                @if((int)$training[0]->TrainingReference == 1602)
                                    <th>Designation</th>
                                @endif
                                <th>Gender</th>
                                <th>Qualification</th>
                                <th>Contact No.</th>
                                <th class="text-center" style="width: 15%;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $count = 1; ?>
                            @forelse($trainingDetails as $trainingDetail)
                                <tr>
                                    <td>{{$count++}}</td>
                                    @if((int)$training[0]->TrainingReference == 1602)
                                        <td>{{$trainingDetail->CDBNo}}</td>
                                    @endif
                                    <td>{{$trainingDetail->Participant}}</td>
                                    <td>{{$trainingDetail->CIDNo}}</td>
                                    @if((int)$training[0]->TrainingReference == 1602)
                                        <td>{{$trainingDetail->Designation}}</td>
                                    @endif
                                    <td>{{$trainingDetail->Gender}}</td>
                                    <td>{{$trainingDetail->Qualification}}</td>
                                    <td>{{$trainingDetail->ContactNo}}</td>
                                    <td class="text-center">
                                        <a href="{{URL::to('contractor/edittrainingparticipant/'.$training[0]->TrainingReference.'/'.$trainingDetail->Id)}}" class="btn btn-xs green editaction"><i class="fa fa-edit"></i> Edit</a>
                                        <a href="{{URL::to('contractor/deletetrainingparticipant/'.$training[0]->Id.'/'.$trainingDetail->Id)}}" class="btn btn-xs red deleteaction"><i class="fa fa-times"></i> Delete</a>
                                    </td>
                                </tr>

                            @empty
                                <tr>
                                    <td colspan="@if((int)$training[0]->TrainingReference == 1602){{'6'}}@else{{'4'}}@endif" class="text-center font-red">No data to display!</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <a href="{{URL::to("contractor/training")}}" class="btn btn-sm purple"><i class="m-icon-swapleft m-icon-white"></i> Back</a>
            </div>
        </div>
    </div>
    </div>
@stop