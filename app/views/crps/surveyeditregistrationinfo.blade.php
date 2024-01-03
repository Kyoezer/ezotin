@extends('master')
@section('content')
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-gift"></i>
			<span class="caption-subject">Surveyor Registration</span>
		</div>
	</div>
	<div class="portlet-body form">
		{{ Form::open(array('url' => 'surveyor/mregistration','role'=>'form','files'=>true))}}
		<input type="hidden" value="1" name="EditByCdb">
		<input type="hidden" name="PostBackUrl" value="{{$redirectUrl}}">
		<input type="hidden" name="IsServiceBySurvey" value="{{$isServiceBySurvey}}">
		@include('crps.surveyregistrationcontrols');
		{{Form::close()}}
	</div>
</div>
@stop