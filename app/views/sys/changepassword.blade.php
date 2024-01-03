@extends('master')
@section('content')
<div class="row">
	<div class="col-md-6">
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption font-green-seagreen">
					<i class="fa fa-cogs"></i>
					<span class="caption-subject">Change Password</span>
				</div>
			</div>
			{{Form::open(array('url' => 'sys/mchangepassword','role'=>'form','id'=>'userchangepassword')) }}
			<div class="portlet-body form">
				<div class="form-body">
					<div class="form-group">
						<input type="hidden" name="IsInsert" value="1" />
					    {{ Form::label('OldPassword', 'Old Password: ') }}
				        <input name="OldPassword" id="OldPassword" type="password" class="form-control required" />
					</div>
					<div class="form-group">
					    {{ Form::label('password', 'New Password: ') }}
				        <input name="password" id="password" type="password" class="form-control password required" />
					</div>
					<div class="form-group">
						{{ Form::label('password_confirmation', 'Re-type Password') }}
				        <input name="password_confirmation" type="password" class="form-control confirmpassword required" />
					</div>
				</div>
				<div class="form-actions">
					<div class="btn-set">
						<button type="submit" class="btn green">Change</button>
						<a href="{{Request::url()}}" class="btn red">Cancel</a>
					</div>
				</div>
			{{Form::close()}}
			</div>
		</div>
	</div>	
</div>	
@stop
@section('pagescripts')
	<script>
		 $('#userchangepassword').bootstrapValidator({
	        fields: {
	            OldPassword: {
	                validators: {
	                    remote: {
	                        message: 'Your old password didnot match.',
	                        url: "<?php echo CONST_SITELINK.'checkoldpassword'?>",
	                        delay: 2000
	                    }
	                }
	            }
	        }
	    });
	</script>
@stop