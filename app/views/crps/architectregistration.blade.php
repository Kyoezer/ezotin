@extends('homepagemaster')
@if(empty($architectRegistration->Id) && $isServiceByArchitect==0)
@section('pagescripts')
	<script>
	 $('#architectregistrationform').bootstrapValidator({
	    fields: {
	        Email: {
	            validators: {
	                remote: {
	                    message: 'The email address is already registered with CDB.',
	                    url: "<?php echo CONST_SITELINK.'checkemailavailabilityapplicants'?>",
	                    delay: 2000
	                }
	            }
	        },
			CIDNo: {
				validators: {
					remote: {
						message: 'You are already a registered Architect with CDB',
						url: "<?php echo CONST_SITELINK.'architect/checkarchitectisregistered'?>",
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
			<span class="caption-subject">Architect Registration</span>
		</div>
	</div>
	<div class="portlet-body form">
		{{ Form::open(array('url' => 'architect/mregistration','role'=>'form','files'=>true,'id'=>'architectregistrationform'))}}
		@include('crps.architectregistrationcontrols');
		{{Form::close()}}
	</div>
</div>
@stop