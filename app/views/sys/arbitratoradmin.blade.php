@extends('master')
@section('pagescripts')
{{ HTML::script('assets/global/plugins/jstree/dist/jstree.min.js') }}
{{ HTML::script('assets/cdb/pages/scripts/ui-tree.js') }}
{{ HTML::script('assets/global/scripts/sys.js') }}
<script>
	UITree.init();
</script>
@if(empty($users[0]->Id))
<script>
	 $('#userregistration').bootstrapValidator({
        fields: {
            username: {
                validators: {
                    remote: {
                        message: 'Username already taken',
                        url: "<?php echo CONST_SITELINK.'arbitratorusernameavalibility'?>",
                        delay: 2000
                    }
                }
            }
        }
    });
</script>
@endif
@stop
@section('pagestyles')
	{{ HTML::style('assets/global/plugins/jstree/dist/themes/default/style.min.css') }}
@stop
@section('content')
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption font-green-seagreen">
			<i class="fa fa-cogs"></i>
			<span class="caption-subject">User</span> &nbsp;<a href="{{URL::to('sys/managearbitrationforum')}}" class="btn blue">Back</a>
		</div>
	</div>
	{{Form::open(array('url' => URL::to('sys/savearbitrationadmin'),'role'=>'form','id'=>'userregistration'))}}
	<div class="portlet-body form">
		<div class="note note-info">
			<strong>NOTE:</strong> The arbitration admin is the Administrator for the Arbitrator Forum who will have the privilege of adding New Categories and Topics. Upon creating an admin from this form, the current admin will not be deleted, but his admin rights will be revoked.
		</div>
		<div class="form-body">
			<div class="row">
				<div class="col-md-4 col-sm-12 col-xs-12">
					{{Form::hidden("Id")}}
					<div class="form-group">
						<label for="username">
							User name (will be used for logging in)
						</label>
						{{Form::text('username','',array('class'=>'form-control required','id'=>'username'))}}
					</div>
					<div class="form-group">
						<label for="FullName">
							Full Name
						</label>
						{{Form::text('FullName','',array('class'=>'form-control required','id'=>'FullName'))}}
					</div>
						<div class="form-group">
							{{ Form::label('password', 'Password') }}
							<input name="password" id="password" type="password" class="form-control required password" />
						</div>
						<div class="form-group">
							{{ Form::label('password_confirmation', 'Re-type Password') }}
							<input name="password_confirmation" id="password_confirmation" type="password" class="form-control required confirmpassword" />
						</div>
				</div>
				<div class="col-md-6 col-sm-12 col-xs-12">
					<div class="table-responsive">
						<h4>Current Admin for Arbitration Forum</h4>
						<table class="table table-condensed table-striped table-bordered">
							<thead>
								<tr>
									<th>Full Name</th>
									<th>Username</th>
								</tr>
							</thead>
							<tbody>
								@forelse($currentAdmin as $admin)
									<tr>
										<td>{{$admin->FullName}}</td>
										<td>{{$admin->username}}</td>
									</tr>
								@empty
									<tr>
										<td colspan="2" class="text-center font-red">No data to display!</td>
									</tr>
								@endforelse
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div class="form-actions">
			<div class="btn-set">
				<button type="submit" class="btn green">Save</button>
				<a href="{{URL::to('sys/userforarbitrationforum')}}" class="btn red">Cancel</a>
			</div>
		</div>
	</div>
	{{Form::close()}}
</div>
@stop