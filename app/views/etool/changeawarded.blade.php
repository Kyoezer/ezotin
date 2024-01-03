@extends('master')
@section('pagescripts')
    {{ HTML::script('assets/global/scripts/etool.js') }}
@stop
@section('content')
    <div id="awardwork" class="modal fade" role="dialog" aria-labelledby="awardwork" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title bold font-green-seagreen">Award Contractor</h4>
                </div>
                <form action="{{URL::to('etl/awardcontractor')}}" method="POST" role="form">
                    <div class="modal-body">
                        <h4 class="bold">Are you sure you want to award this work to selected Contractor?</h4>
                        @foreach($tender as $value)
                            <h5 class="bold">Work Id : <span class="font-green-seagreen ">{{Input::get('WorkId')}}</span></h5>
                            <h5 class="bold">Contractor(s) : <span class="font-green-seagreen contractor-name"></span></h5>
                            <p><span class="bold">Name : </span>{{$value->NameOfWork}}</p>
                        @endforeach
                        {{Form::hidden('Id')}}
                        {{Form::hidden('EtlTenderId',$value->Id)}}
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Actual Start date</label>
                                    <div class="input-group date datepicker" data-date-format="yyyy-mm-dd">
                                        <input type="text" name="ActualStartDate" value="{{date_format(date_create($value->TentativeStartDate),'d-m-Y')}}" class="form-control input-sm" readonly>
									<span class="input-group-btn">
									<button class="btn default input-sm" type="button"><i class="fa fa-calendar"></i></button>
									</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Actual End date</label>
                                    <div class="input-group date datepicker" data-date-format="yyyy-mm-dd">
                                        <input type="text" name="ActualEndDate" value="{{date_format(date_create($value->TentativeEndDate),'d-m-Y')}}" class="form-control input-sm" readonly>
									<span class="input-group-btn">
									<button class="btn default input-sm" type="button"><i class="fa fa-calendar"></i></button>
									</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Awarded Amount</label>
                                    <input type="text" name="AwardedAmount" value="{{$value->ProjectEstimateCost}}" class="form-control input-sm" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Remarks</label>
                            <textarea name="Remarks" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn green" >Award</button>
                        <button class="btn red" data-dismiss="modal" aria-hidden="true">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <a href="#"><i class="fa fa-gavel"></i>E-Tool System Adm.</a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <a href="#"><i class="fa fa-keyboard-o"></i>Change Awarded</a>
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
                                <td width="30%"><strong>Tentative End Date</strong></td>
                                <td>{{convertDateToClientFormat($value->TentativeEndDate)}}</td>
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
                                <td width="30%"><strong>Tentative Start Date</strong></td>
                                <td>{{convertDateToClientFormat($value->TentativeStartDate)}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            @empty
            @endforelse
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-condensed flip-content">
                    <thead class="flip-content">
                        <tr>
                            <th>
                                Contractor
                            </th>
                            <th class="">
                                Financial Bid Quoted
                            </th>
                            <th class="">
                                Points
                            </th>
                            <th class="">
                                Status
                            </th>
                            <th>
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $count = 1; ?>
                        @foreach($tenderEvaluationDetails as $tenderEvaluationDetail)
                        <tr>
                            <td data-id="{{$tenderEvaluationDetail->Id}}">{{$tenderEvaluationDetail->Contractor}}</td>
                            <td>{{$tenderEvaluationDetail->FinancialBidQuoted}}</td>
                            <td>
                                @if($tenderEvaluationDetail->Score10 != NULL)
                                    {{$tenderEvaluationDetail->Score10}} (H{{$count}})
                                    <?php $count++; ?>
                                @else
                                    Not Qualified
                                @endif
                            </td>
                            <td>@if($tenderEvaluationDetail->ActualStartDate){{"Awarded"}}@endif</td>
                            <td>@if(!$tenderEvaluationDetail->ActualStartDate)<a href="#" class="btn btn-xs bg-purple-plum fetchcontractor"><i class="fa fa-edit"></i> Award</a>@endif</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop