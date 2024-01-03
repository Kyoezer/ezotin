@extends('homepagemaster')
@if(empty($specializedtradeRegistrations[0]->Id) && $isServiceBySpecializedtrade==0)
@section('pagescripts')
<script>
	 $('#specializedtraderegistrationform').bootstrapValidator({
	    fields: {
	        Email: {
	            validators: {
	                remote: {
	                    message: 'The email address is already registered with CDB.',
	                    url: "<?php echo CONST_SITELINK.'checkemailavailabilityapplicants'?>",
	                    delay: 2000
	                }
	            }
	        }
	    }
	});
	</script>
@stop
@endif
@section('content')
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-gift"></i>
			<span class="caption-subject">Specialized Firm Registration</span>
		</div>
	</div>
	<div class="portlet-body form">
		{{ Form::open(array('url' => 'specializedtrade/mregistration','role'=>'form','files'=>true,'id'=>'specializedtraderegistrationform'))}}
		@include('crps.specializedfirmregistrationcontrols');
		{{Form::close()}}
	</div>
</div>
@stop