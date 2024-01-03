@extends('master')
@section('content')
    <div class="portlet light bordered">
        @foreach($hrDetails as $details)
         
        <div class="portlet-body flip-scroll">
            @if(Input::get('export')!='print')
            {{Form::open(array('url'=>'certifiedbuilder/releaseCinetHR','method'=>'post'))}}
            <div class="form-body">
                <div class="row">
                    {{Form::hidden('Id',$details->Id)}}
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">CID Number</label>
                            <input type="text" class="form-control input-sm" value="{{$details->CIDNo}}"/>
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
                            <button type="submit" class="btn blue-hoki btn-sm updateaction">Replace</button>
                        </div>
                    </div>
                </div>
            </div>
            {{Form::close()}}
            @endif
        </div>
        @endforeach
    </div>
@stop