@extends('horizontalmenumaster')
@section('content')
    {{--<div id="deletecommitee" class="modal fade" role="dialog" aria-labelledby="deletecommitee" aria-hidden="true">--}}
    {{--<div class="modal-dialog">--}}
    {{--<div class="modal-content">--}}
    {{--<div class="modal-header bg-warning">--}}
    {{--<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>--}}
    {{--<h4 class="modal-title bold font-green-seagreen">Delete Evaluation Committee</h4>--}}
    {{--</div>--}}
    {{--<div class="modal-body">--}}
    {{--<h4 class="bold">Are you sure you want to delete this committee member?</h4>--}}
    {{--<form action="#" class="" role="form">--}}
    {{--<div class="form-group">--}}
    {{--<label>Remarks</label>--}}
    {{--<textarea class="form-control" rows="3"></textarea>--}}
    {{--</div>--}}
    {{--</form>--}}
    {{--</div>--}}
    {{--<div class="modal-footer">--}}
    {{--<button class="btn green" data-dismiss="modal">Delete</button>--}}
    {{--<button class="btn red" data-dismiss="modal" aria-hidden="true">Cancel</button>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <a href="#">E-TOOL</a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <a href="#">Tender Committee</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-cogs"></i>Tender Committee
                    </div>
                </div>
                <div class="portlet-body">
                    {{Form::open(array('url'=>'newEtl/etlsavecommittee'))}}
                    <div class="form-body">

                        {{Form::hidden('EtlTenderId',$tenderId)}}
                        {{Form::hidden('Type',2)}}
                        @foreach($tenders as $tender)
                            <input type="hidden" name="HiddenWorkId" value="{{$tender->WorkId}}">
                            <h4 class="bold">Work Id : <span class="font-green-seagreen ">{{$tender->WorkId}}</span></h4>
                            <p><span class="bold">Name : </span>{{$tender->NameOfWork}}</p>
                            <p><span class="bold">Description : </span> {{$tender->DescriptionOfWork}}</p>
                        @endforeach
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-bordered" id="addevaluationcommittee">
                                <thead>
                                <tr>
                                    <th width="2%"></th>
                                    <th>
                                        Name
                                    </th>
                                    <th>
                                        Designation
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($committee as $awardCommittee)
                                    <?php $randomKey = randomString(); ?>
                                    <tr>
                                        <td>
                                            <button type="button" class="deletetablerow"><i class="fa fa-times fa-lg"></i></button>
                                        </td>
                                        <td>
                                            <input type="hidden" class="form-control input-sm resetKeyForNew" name="EtlTenderCommitteeModel[{{$randomKey}}][Id]" value="{{$awardCommittee->Id}}" />
                                            <input type="hidden" class="form-control input-sm resetKeyForNew notclearfornew" name="EtlTenderCommitteeModel[{{$randomKey}}][Type]" value="2" />
                                            <input type="hidden" class="form-control input-sm resetKeyForNew notclearfornew" name="EtlTenderCommitteeModel[{{$randomKey}}][EtlTenderId]" value="{{$tenderId}}" />
                                            <input type="text" class="form-control input-sm resetKeyForNew required" name="EtlTenderCommitteeModel[{{$randomKey}}][Name]" value="{{$awardCommittee->Name}}" />
                                        </td>
                                        <td>
                                            <input type="text" class="form-control input-sm resetKeyForNew required" name="EtlTenderCommitteeModel[{{$randomKey}}][Designation]" value="{{$awardCommittee->Designation}}" />
                                        </td>
                                    </tr>
                                @endforeach
                                <tr class="notremovefornew">
                                    <td>
                                        <button type="button" class="addtablerow"><i class="fa fa-plus fa-lg"></i></button>
                                    </td>
                                    <td colspan="2"></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="form-actions">
                        <div class="btn-set">
                            <a href="{{URL::to('newEtl/evaluationetool')}}" class="btn blue"><i class="m-icon-swapleft m-icon-white"></i>&nbsp;Back</a>
                            <button type="submit" class="btn green">Save</button>
                            <a href="{{URL::to('newEtl/evaluationetool')}}" class="btn red">Cancel</a>
                        </div>
                    </div>
                    {{Form::close()}}
                </div>
            </div>
        </div>
    </div>
@stop