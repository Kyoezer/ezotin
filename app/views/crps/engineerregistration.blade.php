@extends('homepagemaster')
@if(empty($engineerRegistration->Id) && $isServiceByEngineer==0)
@section('pagescripts')
	
@stop
@endif
@section('content')
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-gift"></i>
			<span class="caption-subject">Engineer Registration</span>
		</div>
	</div>
	<div class="portlet-body form">
		{{ Form::open(array('url' => 'engineer/mregistration','role'=>'form','files'=>true,'id'=>'engineerregistrationform'))}}
		@include('crps.engineerregistrationcontrols')
		{{Form::close()}}
	</div>
</div>
@stop