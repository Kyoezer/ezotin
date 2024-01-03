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
                        <span class="caption-subject">Edit Training Participant</span>
                        {{--<a href="{{URL::to('contractor/addtraining')}}" class="btn btn-xs green"><i class="fa fa-plus"></i> Add New</a>--}}
                        {{--<a href="{{URL::to('contractor/deletetraining')}}" class="btn btn-xs purple"><i class="fa fa-times"></i> Delete</a>--}}
                    </div>
                </div>
                <div class="portlet-body">
                    {{Form::open(array("url"=>URL::to("contractor/saveeditedparticipant")))}}
                <div class="table-responsive">
                    <table class="table table-bordered table-condensed table-striped">
                        <thead>
                            <tr>
                                @if((int)$trainingReference == 1602)
                                    <th style="width: 36%;">Contractor</th>
                                @endif
                                <th>Participant</th>
                                <th>CID No.</th>
                                @if((int)$trainingReference == 1602)
                                    <th>Designation</th>
                                @endif
                                <th>Qualification</th>
                                <th>Contact No</th>
                                <th>Gender</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($details as $detail)
                                <tr>
                                    @if((int)$trainingReference == 1602)
                                        <td>
                                            <div class="ui-widget">
                                                <input type="hidden" class="contractor-id" name="CrpContractorFinalId" value="{{$detail->CrpContractorFinalId}}"/>
                                                <input type="text" value="{{$detail->Contractor}}" class="form-control input-sm contractor-autocomplete"/>
                                            </div>
                                        </td>
                                    @endif
                                    <td>
                                        <input type="hidden" name="Id" value="{{$detail->Id}}"/>
                                        <input type="hidden" name="RedirectUrl" value="{{"contractor/trainingdetails/$detail->CrpContractorTrainingId"}}"/>
                                        <input type="text" class="form-control input-sm" name="Participant" value="{{$detail->Participant}}"/>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control input-sm" name="CIDNo" value="{{$detail->CIDNo}}"/>
                                    </td>
                                    @if((int)$trainingReference == 1602)
                                        <td>
                                            <input type="text" class="form-control input-sm" name="Designation" value="{{$detail->Designation}}"/>
                                        </td>
                                    @endif
                                    <td>
                                        <input type="text" class="form-control input-sm" name="Qualification" value="{{$detail->Qualification}}"/>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control input-sm" name="ContactNo" value="{{$detail->ContactNo}}"/>
                                    </td>
                                    <td>
                                        {{Form::select("Gender",array('F'=>"Female",'M'=>"Male"),$detail->Gender,array("class"=>"form-control input-sm"))}}
                                    </td>
                                </tr>

                            @empty
                                <tr>
                                    <td colspan="@if((int)$trainingReference == 1602){{'5'}}@else{{'5'}}@endif" class="text-center font-red">No data to display!</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                    <button type="submit" class="btn btn-sm green"><i class="fa fa-save"></i> Save</button>
                    <a href="{{URL::to("contractor/trainingdetails/$detail->CrpContractorTrainingId")}}" class="btn btn-sm purple"><i class="m-icon-swapleft m-icon-white"></i> Back</a>
                    {{Form::close()}}
                </div>
        </div>
    </div>
    </div>
@stop