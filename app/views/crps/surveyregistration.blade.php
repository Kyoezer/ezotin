@extends('homepagemaster')
@if(empty($surveyRegistration->Id) && $isServiceBySurvey==0)
@section('pagescripts')
	<script>
	 $('#surveyregistrationform').bootstrapValidator({
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
						message: 'You are already a registered Surveyor with CDB',
						url: "<?php echo CONST_SITELINK.'surveyor/checksurveyisregistered'?>",
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
			<span class="caption-subject">Surveyor Registration</span>
		</div>
	</div>
	<div class="portlet-body form">
		{{ Form::open(array('url' => 'surveyor/mregistration','role'=>'form','files'=>true,'id'=>'architectregistrationform'))}}
		@include('crps.surveyregistrationcontrols');
		{{Form::close()}}
	</div>
</div>
@stop