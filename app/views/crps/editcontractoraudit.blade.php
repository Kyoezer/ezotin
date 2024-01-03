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
                        <span class="caption-subject">Edit Audit Memo</span>
                        {{--<a href="{{URL::to('contractor/addtraining')}}" class="btn btn-xs green"><i class="fa fa-plus"></i> Add New</a>--}}
                        {{--<a href="{{URL::to('contractor/deletetraining')}}" class="btn btn-xs purple"><i class="fa fa-times"></i> Delete</a>--}}
                    </div>
                </div>
                <div class="portlet-body">
                    {{Form::open(array("url"=>URL::to("contractor/saveeditedaudit")))}}
                <div class="table-responsive">
                    <table class="table table-bordered table-condensed table-striped">
                        <thead>
                            <tr>
                                <th style="width: 36%;">Firm</th>
                                <th>Agency</th>
                                <th>Audit Period</th>
                                <th>AIN</th>
                                <th>Para No.</th>
                                <th>Audit Observation</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($details as $detail)
                                <tr>
                                    <td>
                                        {{$detail->Contractor}}
                                    </td>
                                    <td>
                                        <input type="hidden" name="Id" value="{{$detail->Id}}"/>
                                        <input type="text" class="form-control input-sm" name="Agency" value="{{$detail->Agency}}"/>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control input-sm" name="AuditedPeriod" value="{{$detail->AuditedPeriod}}"/>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control input-sm" name="AIN" value="{{$detail->AIN}}"/>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control input-sm" name="ParoNo" value="{{$detail->ParoNo}}"/>
                                    </td>
                                    <td>
                                        <textarea class="form-control" name="AuditObservation">{{$detail->AuditObservation}}</textarea>
                                    </td>
                                </tr>

                            @empty
                                <tr>
                                    <td colspan=6" class="text-center font-red">No data to display!</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                    <button type="submit" class="btn btn-sm green"><i class="fa fa-save"></i> Save</button>
                    <a href="{{URL::to("contractor/auditmemo")}}" class="btn btn-sm purple"><i class="m-icon-swapleft m-icon-white"></i> Back</a>
                    {{Form::close()}}
                </div>
        </div>
    </div>
    </div>
@stop