@extends('master')
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-gift"></i>
					<span class="caption-subject font-green-seagreen">Website Setting</span>
				</div>
			</div>
			<div class="portlet-body form">
				{{ Form::open(array('url'=>'web/savetoggle','class'=>'form-horizontal')) }}
				<div class="form-body">
					<input type="hidden" value="{{$details[0]->Id or ''}}" name="Id" />
                    <div class="form-group">
                        <label class="control-label col-md-3">Hide/Show bottom part of Website
                        </label>
                        <div class="col-md-4">
                            <div class="radio-list">
                                <label class="radio-inline">
                                    <input type="radio" id="show-heading" name="Value" value="1" @if(isset($details[0]->Value))<?php if(($details[0]->Value != 0) || $details[0]->Value== 1): ?>checked="checked"<?php endif; ?>@endif/>
                                    Show
                                </label>

                                <label class="radio-inline">
                                    <input type="radio" id="hide-heading" name="Value" value="0" @if(isset($details[0]->Value))<?php if($details[0]->Value == 0): ?>checked="checked"<?php endif; ?>@endif/>
                                    Hide
                                </label>
                            </div>
                        </div>
                    </div>

				</div>
				<div class="form-actions">
					<div class="btn-set">
						<button type="submit" class="btn green">{{empty($details[0]->Id)?'Save':'Update'}}</button>
						<a href="{{URL::to('web/togglemarquee')}}" class="btn red">Cancel</a>
					</div>
				</div>
				{{ Form::close() }}
			</div>
		</div>
	</div>	
</div>
@stop