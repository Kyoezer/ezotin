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
				{{ Form::open(array('action'=>'savemarqueesetting','class'=>'form-horizontal')) }}
				<div class="form-body">
					<input type="hidden" value="{{$details[0]->Id or ''}}" name="Id" />
                    <div class="form-group">
                        <label class="control-label col-md-3">Enable Marquee
                        </label>
                        <div class="col-md-4">
                            <div class="radio-list">
                                <label class="radio-inline">
                                    <input type="radio" id="show-heading" name="EnableMarquee" value="1" @if(isset($details[0]->EnableMarquee))<?php if(($details[0]->EnableMarquee != 0) || $details[0]->EnableMarquee== 1): ?>checked="checked"<?php endif; ?>@endif/>
                                    Yes
                                </label>

                                <label class="radio-inline">
                                    <input type="radio" id="hide-heading" name="EnableMarquee" value="0" @if(isset($details[0]->EnableMarquee))<?php if($details[0]->EnableMarquee == 0): ?>checked="checked"<?php endif; ?>@endif/>
                                    No
                                </label>
                            </div>
                        </div>
                    </div>


					<div id="marquee-heading" class="form-group @if(isset($details[0]->EnableMarquee))<?php if(($details[0]->EnableMarquee == 0) || $details[0]->EnableMarquee!= 1): ?>hide<?php endif; ?>@endif">
						<label for="" class="control-label col-md-3">
							Marquee Heading
						</label>
						<div class="col-md-8">
							<input type="text" value="{{$details[0]->MarqueeHeading}}" name="MarqueeHeading" class="form-control input-sm required"/>
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