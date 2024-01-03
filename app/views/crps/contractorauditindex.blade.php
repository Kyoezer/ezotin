@extends('master')
@section('pagescripts')
    {{ HTML::script('assets/global/scripts/contractor.js') }}
@stop
@section('content')
    <div id="audit-resolved-modal" class="modal fade" role="dialog" aria-labelledby="audit-resolved-modal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h3 class="modal-title font-red-intense">Resolve Audit</h3>
                </div>
                {{Form::open(array('url'=>'contractor/saveeditedaudit'))}}
                <div class="modal-body">
                    <p id="audit-details"></p>
                    <input type="hidden" name="Id" id="audit-id"/>
                    <input type="hidden" name="Dropped" value="1"/>
                    <input type="hidden" name="DroppedDate" value="{{date('Y-m-d G:i:s')}}"/>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" for="DroppedDate">Date:</label>
                                <input type="text" name="DroppedDate" id="DroppedDate" class="form-control datepicker" value="{{date('d-m-Y')}}"/>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group">
                        <label class="control-label" for="Remarks">Remarks:</label>
                        <textarea rows="4" class="form-control" name="Remarks"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn green">Update</button>
                    <button class="btn green-seagreen" data-dismiss="modal" aria-hidden="true">Cancel</button>
                </div>
                {{Form::close()}}
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption font-green-seagreen">
                        <i class="fa fa-cogs"></i>
                        <span class="caption-subject">Contractor/Consultant Audit Clearance</span>
                        <a href="{{URL::to('contractor/addauditrecord')}}" class="btn btn-xs green"><i class="fa fa-plus"></i> Add New</a>
                    </div>
                </div>
                <div class="portlet-body form">
                    <div class="form-body">
                        {{ Form::open(array('url' =>Request::url(),'role'=>'form','method'=>'get','class'=>'novalidate')) }}
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">CDB No.</label>
                                    <input type="text" name="CDBNo" class="form-control" value="{{Input::get('CDBNo')}}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Type</label>
                                    {{Form::select('Type',array(''=>'All','1'=>'Contractor','2'=>'Consultant'),Input::get('Type'),array('class'=>"form-control"))}}
                                </div>
                            </div>
                            <div class="clearfix"></div>
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
                @if(!empty($records))
                <div class="table-responsive">
                    <table class="table table-bordered table-condensed table-striped">
                        <thead>
                            <tr>
                                <th>Sl #</th>
                                <th>CDB No.</th>
                                <th>Name of Firm</th>
                                <th>Agency</th>
                                <th>Audited Period</th>
                                <th>AIN</th>
                                <th>Para No</th>
                                <th>Audit Observation</th>
                                <th style="width:90px;">Date</th>
                                <th width="300" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $count = 1; ?>
                            @forelse($records as $record)
                                <tr>
                                    <td>{{$count++}}</td>
                                    <td>{{$record->CDBNo}}</td>
                                    <td>{{$record->NameOfFirm}}</td>
                                    <td>{{$record->Agency}}</td>
                                    <td>{{$record->AuditedPeriod}}</td>
                                    <td>{{$record->AIN}}</td>
                                    <td>{{$record->ParoNo}}</td>
                                    <td>{{nl2br($record->AuditObservation)}}</td>
                                    <td>{{$record->CreatedOn}}</td>
                                    <td class="text-center">
                                    <a href="{{URL::to("contractor/editaudit/$record->Id")}}" class="btn btn-xs blue"><i class="fa fa-edit"></i> Edit</a>
                                    <a href="{{URL::to("contractor/deleteaudit/$record->Id")}}" class="btn btn-xs red deleteaction"><i class="fa fa-times"></i> Delete</a>
                                    <a data-id="{{$record->Id}}" href="#" class="btn btn-xs purple resolve-audit"><i class="fa fa-edit"></i> Drop</a></td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="font-red text-center" colspan="10">No data to display!</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <?php pagination($noOfPages,Input::all(),Input::get('page'),"contractor.auditmemo"); ?>
                @endif
            </div>
        </div>
    </div>
    </div>
@stop