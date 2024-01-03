@extends('master')
@section('pagescripts')
    {{ HTML::script('assets/global/scripts/etool.js') }}
@stop
@section('content')
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <a href="#"><i class="fa fa-gavel"></i>E-Tool System Adm.</a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <a href="#"><i class="fa fa-keyboard-o"></i>Reset Result for Work Id {{$workId}}</a>
            </li>
        </ul>
    </div>
    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-cogs"></i>Change Awarded
            </div>
            <div class="tools">
                <a href="javascript:;" class="collapse"></a>
            </div>
        </div>
        <div class="portlet-body">
            @forelse($tender as $value)
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-bordered table-striped table-condensed flip-content">
                            <tbody>
                            <tr>
                                <td width="30%"><strong>Work Id</strong></td>
                                <td>
                                    {{Input::get('WorkId')}}
                                </td>
                            </tr>
                            <tr>
                                <td width="30%"><strong>Project Cost Estimate</strong></td>
                                <td>{{$value->ProjectEstimateCost}}</td>
                            </tr>
                            <tr>
                                <td width="30%"><strong>Name of Work</strong></td>
                                <td>{{$value->NameOfWork}}</td>
                            </tr>
                            <tr>
                                <td width="30%"><strong>Tentative Start Date</strong></td>
                                <td>{{convertDateToClientFormat($value->TentativeStartDate)}}</td>
                            </tr>
                            <tr>
                                <td width="30%"><strong>Awarded To</strong></td>
                                <td>{{$awardedTo[0]->Contractor}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-bordered table-striped table-condensed flip-content">
                            <tbody>
                            <tr>
                                <td width="30%"><strong>Class, Category</strong></td>
                                <td>{{$value->Class.", ".$value->Category}}</td>
                            </tr>
                            <tr>
                                <td width="30%"><strong>Dzongkhag</strong></td>
                                <td>{{$value->Dzongkhag}}</td>
                            </tr>
                            <tr>
                                <td width="30%"><strong>Contract Description</strong></td>
                                <td>{{html_entity_decode($value->DescriptionOfWork)}}</td>
                            </tr>
                            <tr>
                                <td width="30%"><strong>Tentative End Date</strong></td>
                                <td>{{convertDateToClientFormat($value->TentativeEndDate)}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            @empty
            @endforelse
            <a href="{{URL::to('etoolsysadm/resetworkresult/'.$value->Id)}}" class="btn btn-sm red updateaction"><i class="fa fa-refresh"></i> Reset Result</a>
            @if($value->CmnWorkExecutionStatusId == CONST_CMN_WORKEXECUTIONSTATUS_COMPLETED)
                <a href="{{URL::to('etoolsysadm/resettoawarded/'.$value->Id)}}" class="btn btn-sm purple updateaction"><i class="fa fa-refresh"></i> Reset to Awarded</a>
            @endif
        </div>
    </div>
@stop