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
					<i class="fa fa-gift"></i>Set Qualifying
				</div>
			</div>
			<div class="portlet-body form">
                <!-- BEGIN FORM-->
                {{Form::open(array('url' => "newEtl/savequalifyingscore",'role'=>'form','class'=>'form-horizontal'))}}
					<div class="form-body">

                        @foreach($savedScore as $value)
						<div class="form-group">
							<label class="control-label col-md-3">Qualifying Score<span class="font-red">*</span></label>
                            <div class="col-md-1">
                                <input type="hidden" name="Id" value="{{$value->Id}}" />
							    <input type="text" name="QualifyingScore" value="{{$value->QualifyingScore}}" class="form-control required number input-sm" />
                            </div>
						</div>
                        @endforeach
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