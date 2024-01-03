@extends('master')
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-gift"></i>
					<span class="caption-subject font-green-seagreen">Edit FAQ's</span>
				</div>
			</div>
			<div class="portlet-body form">
				{{ Form::open(array('action'=>'PostFaqWebsite@postFaqAnswer', 'method'=>'POST')) }}
				<input type="hidden" name="QuestionReference" value="{{$questionReference}}">
				<div class="form-body">
					<div class="form-group">
						<label><strong>Question</strong></label>
						<input type="text" value="{{$question}}" name="Question" class="form-control required">
					</div>
					<div class="form-group">
						<label><strong>Answer</strong></label>
						<textarea name="Answer" class="form-control required wysihtml5" rows="15">{{html_entity_decode($answer)}}</textarea>
					</div>
				</div>
				<div class="form-actions">
					<div class="btn-set">
						<button type="submit" class="btn green">Save</button>
						<a href="{{URL::to('web/faqquestionlist')}}" class="btn red">Cancel</a>
					</div>
				</div>
				{{Form::close()}}
			</div>
		</div>	
	</div>
</div>
@stop