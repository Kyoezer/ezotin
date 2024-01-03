@extends('horizontalmenumaster')
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
                        <span class="caption-subject">Request Replace Release</span>
                    </div>
                </div>
                <div class="portlet-body form">
                    <input type="hidden" id="module" value="{{Request::segment(1)}}">
                    @if(!Input::has('type'))
                        <ol>
                            <li><a href="{{Request::url()}}?type=101&module={{Request::segment(1)}}">Replace Human Resource</a></li>
                            <li><a href="{{Request::url()}}?type=102&module={{Request::segment(1)}}">Release Human Resource</a></li>
                            <li><a href="{{Request::url()}}?type=201&module={{Request::segment(1)}}">Replace Equipment</a></li>
                            <li><a href="{{Request::url()}}?type=202&module={{Request::segment(1)}}">Release Equipment</a></li>
                        </ol>
                    @endif
                </div>
                @if(Input::has('type'))
                    @if(Input::get('type') == 101)
                        {{Form::open(array('url'=>URL::to('etl/saverrrequest'),'files'=>true))}}
                        {{Form::hidden('RequestType',101)}}
                    {{Form::hidden("Module",Request::segment(1))}}
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <h4><strong>Replace HR</strong></h4>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="control-label">Work Id</label>
                                        <select name="EtlTenderId" data-fetch="1" class="form-control required input-sm work-id-rr">
                                            <option value="">SELECT</option>
                                            @foreach($works as $work)
                                                <option value="{{$work->Id}}">{{$work->WorkId}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="control-label">CID No.</label>
                                        <select name="Number" class="form-control input-sm required cid-rr">
                                            <option value="">SELECT</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="control-label">New CID No.</label>
                                        <input type="text" name="NewNumber" class="form-control input-sm required" name="CIDNo" />
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="control-label">Request Letter No.</label>
                                        <input type="text" class="form-control input-sm required" name="RequestLetterNo" />
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="control-label">Request Letter</label>
                                        <input type="file" class="form-control input-sm required" name="RequestLetter" />
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <label class="control-label">&nbsp;</label>
                                    <div class="btn-set">
                                        <button type="submit" class="btn blue-hoki btn-sm">Send Replace Request</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{Form::close()}}
                    @endif

                    @if(Input::get('type') == 102)
                        {{Form::open(array('url'=>URL::to('etl/saverrrequest'),'files'=>true))}}
                        {{Form::hidden('RequestType',102)}}
                        {{Form::hidden("Module",Request::segment(1))}}
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <h4><strong>Release HR</strong></h4>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="control-label">Work Id</label>
                                        <select name="EtlTenderId" data-fetch="1" class="form-control required input-sm work-id-rr">
                                            <option value="">SELECT</option>
                                            @foreach($works as $work)
                                                <option value="{{$work->Id}}">{{$work->WorkId}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="control-label">CID No.</label>
                                        <select name="Number" class="form-control required input-sm cid-rr">
                                            <option value="">SELECT</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="control-label">Request Letter No.</label>
                                        <input type="text" class="form-control input-sm required" name="RequestLetterNo" />
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="control-label">Request Letter</label>
                                        <input type="file" class="form-control input-sm required" name="RequestLetter" />
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <label class="control-label">&nbsp;</label>
                                    <div class="btn-set">
                                        <button type="submit" class="btn blue-hoki btn-sm">Send Release Request</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{Form::close()}}
                    @endif

                    @if(Input::get('type') == 201)
                            {{Form::open(array('url'=>URL::to('etl/saverrrequest'),'files'=>true))}}
                            {{Form::hidden('RequestType',201)}}
                        {{Form::hidden("Module",Request::segment(1))}}
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h4><strong>Replace Equipment</strong></h4>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label">Work Id</label>
                                            <select name="EtlTenderId" data-fetch="2" class="form-control required input-sm work-id-rr">
                                                <option value="">SELECT</option>
                                                @foreach($works as $work)
                                                    <option value="{{$work->Id}}">{{$work->WorkId}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label">Registration No</label>
                                            <select name="Number" class="form-control input-sm regno-rr required">
                                                <option value="">SELECT</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label">New Registration No</label>
                                            <input type="text" name="NewNumber" class="form-control input-sm required" name="RegistrationNo" />
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label">Request Letter No.</label>
                                            <input type="text" class="form-control input-sm required" name="RequestLetterNo" />
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label">Request Letter</label>
                                            <input type="file" class="form-control input-sm required" name="RequestLetter" />
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="control-label">&nbsp;</label>
                                        <div class="btn-set">
                                            <button type="submit" class="btn blue-hoki btn-sm">Send Replace Request</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{Form::close()}}
                    @endif

                    @if(Input::get('type') == 202)
                        {{Form::open(array('url'=>URL::to('etl/saverrrequest'),'files'=>true))}}
                        {{Form::hidden('RequestType',202)}}
                        {{Form::hidden("Module",Request::segment(1))}}
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <h4><strong>Release Equipment</strong></h4>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="control-label">Work Id</label>
                                        <select name="EtlTenderId" data-fetch="2" class="form-control input-sm work-id-rr required">
                                            <option value="">SELECT</option>
                                            @foreach($works as $work)
                                                <option value="{{$work->Id}}">{{$work->WorkId}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="control-label">Registration No</label>
                                        <select name="Number" class="form-control required input-sm regno-rr">
                                            <option value="">SELECT</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="control-label">Request Letter No.</label>
                                        <input type="text" class="form-control input-sm required" name="RequestLetterNo" />
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="control-label">Request Letter</label>
                                        <input type="file" class="form-control input-sm required" name="RequestLetter" />
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <label class="control-label">&nbsp;</label>
                                    <div class="btn-set">
                                        <button type="submit" class="btn blue-hoki btn-sm">Send Release Request</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{Form::close()}}
                    @endif

                    <a href="{{Request::url()}}" class="btn btn-sm blue"><i class="m-icon-swapleft m-icon-white"></i> Back</a>
                @endif
            </div>
        </div>
    </div>
@stop