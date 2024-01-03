@extends('master')
@section('pagescripts')
    {{ HTML::script('assets/global/scripts/etool.js') }}
@stop
@section('content')
    <input type="hidden" name="URL" value="{{CONST_APACHESITELINK}}"/>
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-gift"></i>Set Organizational Strength Parameters
                    </div>
                </div>
                <div class="portlet-body form">
                    <!-- BEGIN FORM-->
                    {{Form::open(array('url' => "newEtl/savebidevaluationparameters",'role'=>'form','class'=>'form-horizontal form-row-seperated'))}}
                    <div class="form-body">

                        <div class="portlet box green-meadow">
                            <div class="portlet-title">
                                <div class="caption"><i class="fa fa-gift"></i> Organizational Strength</div>
                            </div>
                            <div class="portlet-body">
                                @foreach($organizationalStrength as $os)
                                    <?php $randomKey = randomString(); ?>
                                    <div class="form-group">
                                        <label class="control-label col-md-6">{{$os->Name}} <span class="font-red">*</span></label>
                                        <div class="col-md-2">
                                            <input type="hidden" name="EtlBidEvaluationParameters[{{$randomKey}}][Id]" value="{{$os->Id}}" />
                                            <input type="text" name="EtlBidEvaluationParameters[{{$randomKey}}][Points]" value="{{$os->Points}}" class="form-control required number input-sm" />
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="portlet box blue-madison">
                            <div class="portlet-title">
                                <div class="caption"><i class="fa fa-gift"></i> Employment of VTI Graduate/Local Skilled Labour</div>
                            </div>
                            <div class="portlet-body">
                                @foreach($employmentOfVTI as $vti)
                                    <?php $randomKey = randomString(); ?>
                                    <div class="form-group">
                                        <label class="control-label col-md-6">{{$vti->Name}} <span class="font-red">*</span></label>
                                        <div class="col-md-2">
                                            <input type="hidden" name="EtlBidEvaluationParameters[{{$randomKey}}][Id]" value="{{$vti->Id}}" />
                                            <input type="text" name="EtlBidEvaluationParameters[{{$randomKey}}][Points]" value="{{$vti->Points}}" class="form-control required number input-sm" />
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="portlet box purple-medium">
                            <div class="portlet-title">
                                <div class="caption"><i class="fa fa-gift"></i> Commitment of Internships to VTI Graduate </div>
                            </div>
                            <div class="portlet-body">
                                @foreach($commitmentOfInternship as $internship)
                                    <?php $randomKey = randomString(); ?>
                                    <div class="form-group">
                                        <label class="control-label col-md-6">{{$internship->Name}} <span class="font-red">*</span></label>
                                        <div class="col-md-2">
                                            <input type="hidden" name="EtlBidEvaluationParameters[{{$randomKey}}][Id]" value="{{$internship->Id}}" />
                                            <input type="text" name="EtlBidEvaluationParameters[{{$randomKey}}][Points]" value="{{$internship->Points}}" class="form-control required number input-sm" />
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="form-actions">
                        <div class="btn-set">
                            <button type="submit" class="btn green">Save</button>
                            <a href="{{Request::url()}}" class="btn red">Cancel</a>
                        </div>
                    </div>
                    {{Form::close()}}
                    <!-- END FORM-->
                </div>
            </div>
        </div>
    </div>
@stop