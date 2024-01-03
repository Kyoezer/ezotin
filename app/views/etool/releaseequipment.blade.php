@extends('master')
@section('content')
    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-cogs"></i>Release Equipment For WorkId: {{$workId}}
            </div>
        </div>
        <div class="portlet-body flip-scroll">
            @if(Input::get('export')!='print')
            {{Form::open(array('url'=>'etoolsysadm/releaseequipment','method'=>'post'))}}
            <div class="form-body">
                <div class="row">
                    {{Form::hidden('Id',$Id)}}
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">Registration No</label>
                            <input type="text" name="RegistrationNo" class="form-control input-sm" value="{{$registrationNo}}"/>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">Request Letter No</label>
                            <input type="text" class="form-control input-sm required" name="RequestLetterNo" />
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label class="control-label">&nbsp;</label>
                        <div class="btn-set">
                            <button type="submit" class="btn blue-hoki btn-sm updateaction">Release</button>
                        </div>
                    </div>
                </div>
            </div>
            {{Form::close()}}
            @endif
        </div>
    </div>
@stop