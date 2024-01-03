@extends('master')
@section('pagescripts')
    {{ HTML::script('assets/global/scripts/etool.js') }}
@stop
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption font-green-seagreen">
                        <i class="fa fa-cogs"></i>
                        <span class="caption-subject">
                            <?php $numberLabel = ''; ?>
                            @if((int)$details[0]->RequestType == 101)
                                {{"Replace HR"}}
                                <?php $numberLabel = "CID No."; ?>
                            @elseif((int)$details[0]->RequestType == 102)
                                {{"Release HR"}}
                                <?php $numberLabel = "CID No."; ?>
                            @elseif((int)$details[0]->RequestType == 201)
                                {{"Replace Equipment"}}
                                <?php $numberLabel = "Registration No."; ?>
                            @else
                                {{"Release Equipment"}}
                                <?php $numberLabel = "Registration No."; ?>
                            @endif
                        </span>
                    </div>
                </div>
                <div class="portlet-body form">
                    {{Form::open(array('url'=>URL::to('etoolsysadm/postapproverequest')))}}
                    @foreach($details as $detail)
                        <input type="hidden" name="Id" value="{{$detail->Id}}"/>
                        <input type="hidden" name="Module" value="{{Request::segment(1)}}"/>
                        <div class="col-md-6">
                            <table class="table table-bordered table-condensed">
                                <tbody>
                                    <tr><td><strong>Work Id: </strong></td><td>{{$detail->WorkId}}</td></tr>
                                    <tr><td><strong>Request Letter: </strong></td><td>{{$detail->RequestLetterNo}}&nbsp;&nbsp;&nbsp; <a href="{{asset($detail->DocumentPath)}}">Link to Request Letter</a></td></tr>
                                    <tr><td><strong>{{$numberLabel}}:</strong></td><td>{{$detail->Number}}</td></tr>
                                    @if((bool)$detail->NewNumber)
                                        <tr><td><strong>New {{$numberLabel}}:</strong></td><td>{{$detail->NewNumber}}</td></tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    @endforeach
                    <div class="clearfix"></div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-sm green"><i class="fa fa-save"></i> Approve</button>
                        <a href="{{URL::to("etoolsysadm/rejectrrrequest/$detail->Id")}}" class="btn btn-sm red"><i class="fa fa-times"></i> Reject</a>
                        <a href="{{URL::to("etoolsysadm/releasereplacerequestlist")}}" class="btn btn-sm purple">Back</a>
                    </div><div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
@stop