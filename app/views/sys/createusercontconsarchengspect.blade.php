@extends('master')
@section('pagescripts')
{{ HTML::script('assets/global/scripts/sys.js') }}
<script>
	 {{--$('#userregistration').bootstrapValidator({--}}
        {{--fields: {--}}
            {{--username: {--}}
                {{--validators: {--}}
                    {{--remote: {--}}
                        {{--message: 'Email already taken',--}}
                        {{--url: "<?php echo CONST_SITELINK.'usernameavalibility'?>",--}}
                        {{--delay: 2000--}}
                    {{--}--}}
                {{--}--}}
            {{--}--}}
        {{--}--}}
    {{--});--}}
</script>
@stop
@section('content')
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption font-green-seagreen">
			<i class="fa fa-cogs"></i>
			<span class="caption-subject">User</span>
		</div>
	</div>
	{{Form::open(array('url' => 'sys/muser','role'=>'form','id'=>'userregistration'))}}
	<div class="portlet-body form">
		<div class="form-body">
			<div class="row">
				<div class="col-md-4 col-sm-12 col-xs-12">
					@foreach($users as $user)
						 <div class="form-group">
			                {{Form::hidden('Id',$user->Id)}}
			                {{Form::label('FullName','Name of User')}}
			                {{Form::text('FullName',$user->FullName,array('class'=>'form-control required'))}}
			            </div>
			    		<div class="form-group">
			    			<label for="username">Email (<i class="font-red">Used for signing in to the system</i>)</label>
			    		    {{Form::text('username',$user->username,array('class'=>'form-control email required'))}}
			    		</div>
			    		@if(empty($user->Id))
			    			<input type="hidden" name="IsInsert" value="1" />
			                <div class="form-group">
			        		    {{ Form::label('password', 'Password') }}
			        	        <input name="password" id="password" type="password" class="form-control required password" />
			        		</div>
			        		<div class="form-group">
			        			{{ Form::label('password_confirmation', 'Re-type Password') }}
			        	        <input name="password_confirmation" type="password" class="form-control required confirmpassword" />
			        		</div>
			            @endif
			            <div class="form-group">
			            	<label>Status</label>
							<div class="radio-list">
								<label class="radio-inline">
								<input type="radio" name="Status" id="optionsRadios4" value="1"  @if($user->Status=="1" || empty($user->Status))checked="checked"@endif> Active </label>
								<label class="radio-inline">
								<input type="radio" name="Status" id="optionsRadios5" value="0" @if($user->Status=="0")checked="checked"@endif> In-Active </label>
							</div>
			            </div>
					@endforeach
				</div>
				<div class="col-md-8 col-sm-12 col-xs-12">
					<div class="table-responsive">
						<h5 class="font-blue-madison bold">Assign a role to the user by ticking on the checkbox</h5>
						<table id="systemrolemenumap" class="table table-condensed table-striped table-bordered table-hover table-responsive">
							<thead>
								<tr>
									<th width="5%"></th>
									<th>Role</th>
									<th>User For</th>
								</tr>
							</thead>
							<tbody>
								@foreach($roles as $role)
								<?php $randomKey=randomString();?>
				                <tr>
				                    <td class="text-center">
				                        <input type="checkbox" name="sauserrolemap[{{$randomKey}}][SysRoleId]" value="{{$role->Id}}" class="addrowcheckboxsinglecheck" @if($role->Selected==1)checked="checked"@endif/>
				                    </td>
				                    <td>
				                    	{{$role->Name}}
				                    </td>
				                    <td>
				                    	@if((int)$role->ReferenceNo==2)
				                    	<select id="userfor-{{$role->ReferenceNo}}" name="CrpContractorId" class="form-control select2me tablerowcontrol" disabled="disabled">
				                    		<option value="">---SELECT ONE---</option>
				                    		@foreach($contractors as $contractor)
				                    		<option value="{{$contractor->Id}}">{{$contractor->NameOfFirm}}</option>
				                    		@endforeach
				                    	</select>

                                        {{--<div class="ui-widget">--}}
                                            {{--<input type="hidden" class="contractor-id" name="CrpContractorId" />--}}
                                            {{--<input id="userfor-{{$role->ReferenceNo}}" type="text" name="Contractor" placeholder="Type Contractor Name" class="form-control tablerowcontrol contractor-autocomplete" disabled="disabled"/>--}}
                                        {{--</div>--}}
				                    	@endif
				                    	@if((int)$role->ReferenceNo==3)
				                    	<select id="userfor-{{$role->ReferenceNo}}" name="CrpConsultantId" class="form-control select2me tablerowcontrol" disabled="disabled">
				                    		<option value="">---SELECT ONE---</option>
				                    		@foreach($consultants as $consultant)
				                    		<option value="{{$consultant->Id}}">{{$consultant->NameOfFirm}}</option>
				                    		@endforeach
				                    	</select>
                                        {{--<div class="ui-widget">--}}
                                            {{--<input type="hidden" class="consultant-id" name="CrpConsultantId"/>--}}
                                            {{--<input id="userfor-{{$role->ReferenceNo}}" type="text" name="Consultant" placeholder="Type Consultant Name" class="form-control tablerowcontrol consultant-autocomplete" disabled="disabled"/>--}}
                                        {{--</div>--}}
				                    	@endif
				                    	@if((int)$role->ReferenceNo==4)
				                    	<select id="userfor-{{$role->ReferenceNo}}" name="CrpArchitectId" class="form-control select2me tablerowcontrol" disabled="disabled">
				                    		<option value="">---SELECT ONE---</option>
				                    		@foreach($architects as $architect)
				                    		<option value="{{$architect->Id}}">{{$architect->Name}}</option>
				                    		@endforeach
				                    	</select>
				                    	@endif
				                    	@if((int)$role->ReferenceNo==5)
				                    	<select id="userfor-{{$role->ReferenceNo}}" name="CrpSpecializedTradeId" class="form-control select2me tablerowcontrol" disabled="disabled">
				                    		<option value="">---SELECT ONE---</option>
				                    		@foreach($specializedTrades as $specializedTrade)
				                    		<option value="{{$specializedTrade->Id}}">{{$specializedTrade->Name}}</option>
				                    		@endforeach
				                    	</select>
				                    	@endif
				                    	@if((int)$role->ReferenceNo==6)
				                    	<select id="userfor-{{$role->ReferenceNo}}" name="CrpEngineerId" class="form-control select2me tablerowcontrol" disabled="disabled">
				                    		<option value="">---SELECT ONE---</option>
				                    		@foreach($engineers as $engineers)
				                    		<option value="{{$engineers->Id}}">{{$engineers->Name}}</option>
				                    		@endforeach
				                    	</select>
				                    	@endif
				                
										@if((int)$role->ReferenceNo==10)
				                    	<select id="userfor-{{$role->ReferenceNo}}" name="CrpSurveyId" class="form-control select2me tablerowcontrol" disabled="disabled">
				                    		<option value="">---SELECT ONE---</option>
				                    		@foreach($surveyors as $surveyor)
				                    		<option value="{{$surveyor->Id}}">{{$surveyor->Name}}</option>
				                    		@endforeach
				                    	</select>
				                    	@endif
								@if((int)$role->ReferenceNo==9)
								<select id="userfor-{{$role->ReferenceNo}}" name="CrpSpecializedTradeId" class="form-control select2me tablerowcontrol" disabled="disabled">
				                    		<option value="">---SELECT ONE---</option>
				                    		@foreach($specializedfirms as $specializedfirm)
				                    		<option value="{{$specializedfirm->Id}}">{{$specializedfirm->NameOfFirm}}</option>
				                    		@endforeach
				                    	</select>
				                    	@endif
				                    </td>
				                </tr>
				                @endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div class="form-actions">
			<div class="btn-set">
				<button type="submit" class="btn green">Save</button>
				<a href="{{Request::url()}}" class="btn red">Cancel</a>
			</div>
		</div>
	</div>
	{{Form::close()}}
</div>
@stop