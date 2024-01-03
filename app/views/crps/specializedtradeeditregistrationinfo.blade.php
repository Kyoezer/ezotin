@extends('master')
@section('content')
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-gift"></i>
			<span class="caption-subject">Specialized Trade Registration</span>
		</div>
	</div>
	<div class="portlet-body form">
		{{ Form::open(array('url' => 'specializedtrade/mregistration','role'=>'form','files'=>true))}}
		<input type="hidden" value="1" name="EditByCdb">
		<input type="hidden" name="PostBackUrl" value="{{$redirectUrl}}">
		<input type="hidden" name="IsServiceBySpecializedTrade" value="{{$isServiceBySpecializedTrade}}">
		@include('crps.specializedtraderegistrationcontrols');
		{{Form::close()}}
	</div>
</div>
@stop