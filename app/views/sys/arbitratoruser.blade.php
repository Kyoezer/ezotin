@extends('master')
@section('pagescripts')
{{ HTML::script('assets/global/scripts/sys.js') }}
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
<div class="modal fade" id="resetpasswordarb" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			{{Form::open(array('url' => 'sys/resetarbpassword','role'=>'form','id'=>'resetarb')) }}
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h3 class="modal-title font-green-seagreen">Confirmation</h3>
			</div>
			<div class="modal-body">
				{{Form::hidden('Id')}}
				<div class="note note-warning">
					Are you sure you want to reset the password for <span id="user-name"></span>?
				</div>
				<div class="form-group">
					{{ Form::label('password', 'New Password: ') }}
					<input name="password" id="password" type="password" class="form-control password required" />
				</div>
				<div class="form-group">
					{{ Form::label('password_confirmation', 'Re-type Password: ') }}
					<input name="password_confirmation" type="password" class="form-control confirmpassword required" />
				</div>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn green">Reset</button>
				<button class="btn red" data-dismiss="modal" aria-hidden="true">Cancel</button>
			</div>
		</div>
		{{Form::close()}}
	</div>
</div>
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption font-green-seagreen">
			<i class="fa fa-cogs"></i>
			<span class="caption-subject">User</span> &nbsp;<a href="{{URL::to('sys/managearbitrationforum')}}" class="btn blue">Back</a>
		</div>
	</div>

	<div class="portlet-body form">
		<div class="form-body">
			<div class="row">
				<div class="col-md-4 col-sm-12 col-xs-12">
					{{Form::open(array('url' => URL::to('sys/savearbitrationuser'),'role'=>'form','id'=>'userregistration'))}}
					@foreach($users as $user)
						{{Form::hidden("Id",$user->Id)}}
						 <div class="form-group">
			                {{Form::label('FullName','Arbitrator')}}
							 <select name="WebArbitratorId" class="form-control select2me" id="arbitrator-list">
								 <option value="">SELECT</option>
								 @foreach($arbitrators as $arbitrator)
									 <option value="{{$arbitrator->Id}}" @if($user->WebArbitratorId == $arbitrator->Id)selected="selected"@endif data-email="{{$arbitrator->Email}}">{{$arbitrator->Name.' ('.$arbitrator->RegNo.')'}}</option>
								 @endforeach
							 </select>
			            </div>
                        <div class="form-group">
                            <label for="username">
                                Email (used as User name)
                            </label>
                            {{Form::text('username',$user->username,array('class'=>'form-control required','id'=>'username'))}}
                        </div>
			    		@if(empty($user->Id))
			    			<input type="hidden" name="IsInsert" value="1" />
			                <div class="form-group">
			        		    {{ Form::label('password', 'Password') }}
			        	        <input name="password" id="password" type="password" class="form-control required password" />
			        		</div>
			        		<div class="form-group">
			        			{{ Form::label('password_confirmation', 'Re-type Password') }}
			        	        <input name="password_confirmation" id="password_confirmation" type="password" class="form-control required confirmpassword" />
			        		</div>
			            @endif
			            <div class="form-group">
			            	<label>Status</label>
							<div class="radio-list">
								<label class="radio-inline">
								<input type="radio" name="Status" id="optionsRadios4" value="1"  @if((string)$user->Status=='1' || empty($user->Status))checked="checked"@endif> Active </label>
								<label class="radio-inline">
								<input type="radio" name="Status" id="optionsRadios5" value="0" @if((string)$user->Status=='0')checked="checked"@endif> In-Active </label>
							</div>
			            </div>
					@endforeach
						<div class="form-actions">
							<div class="btn-set">
								<button type="submit" class="btn green">Save</button>
								<a href="{{URL::to('sys/userforarbitrationforum')}}" class="btn red">Cancel</a>
							</div>
						</div>
					{{Form::close()}}
				</div>
				<div class="col-md-8">
					<div class="table-responsive">
						<table class="table table-condensed table-striped table-bordered">
							<thead>
								<tr>
									<th>Sl#</th>
									<th>Arbitrator</th>
									<th>Username</th>
									<th>Status</th>
									<th style="width: 29%;">Action</th>
								</tr>
							</thead>
							<tbody>
								<?php $sl=1; ?>
								@forelse($arbitratorUsers as $arbitratorUser)
									<tr>
										<td>{{$sl++}}</td>
										<td>{{$arbitratorUser->Arbitrator}}</td>
										<td>{{$arbitratorUser->username}} @if((int)$arbitratorUser->IsAdmin == 1)<strong>(Admin)</strong>@endif</td>
										<td>{{((int)$arbitratorUser->Status ==1)?'Active':'In-Active'}}</td>
										<td>
											<a href="{{URL::to('sys/userforarbitrationforum/'.$arbitratorUser->Id)}}" class="btn btn-xs blue"><i class="fa fa-edit"></i> Edit</a>
											<a href="#" data-name="{{$arbitratorUser->Arbitrator}}" data-id="{{$arbitratorUser->Id}}" class="btn btn-xs green resetarbpassword"><i class="fa fa-edit"></i> Reset Password</a>
										</td>
									</tr>
								@empty
									<tr>
										<td colspan="4" class="font-red text-center">No data to display!</td>
									</tr>
								@endforelse
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>

	</div>

</div>
@stop