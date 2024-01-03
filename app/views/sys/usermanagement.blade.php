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
                        url: "<?php echo CONST_SITELINK.'usernameavalibility'?>",
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
                            <label for="username">
                                User name
                            </label>
                            {{Form::text('username',$user->username,array('class'=>'form-control required','id'=>'username'))}}
                        </div>
			    		<div class="form-group">
			    			<label for="email">Email </label>
			    		    {{Form::text('Email',$user->Email,array('class'=>'form-control email','id'=>'email'))}}
			    		</div>
                        <div class="form-group">
                            <label for="contactno">Contact No. </label>
                            {{Form::text('ContactNo',$user->ContactNo,array('class'=>'form-control number','id'=>'contactno'))}}
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
								<input type="radio" name="Status" id="optionsRadios4" value="1"  @if($user->Status=="1" || empty($user->Status))checked="checked"@endif> Active </label>
								<label class="radio-inline">
								<input type="radio" name="Status" id="optionsRadios5" value="0" @if($user->Status=="0")checked="checked"@endif> In-Active </label>
							</div>
			            </div>
					@endforeach
				</div>
				@if(!(in_array(CONST_ROLE_CONTRACTOR,$oldRole) || in_array(CONST_ROLE_CONSULTANT,$oldRole) || in_array(CONST_ROLE_SPECIALIZEDTRADE,$oldRole) || in_array(CONST_ROLE_ENGINEER,$oldRole) || in_array(CONST_ROLE_ARCHITECT,$oldRole)))
				<div class="col-md-8 col-sm-12 col-xs-12">
					<div class="table-responsive">
						<h5 class="font-blue-madison bold">Assign a role to the user by ticking the checkbox</h5>
						<table id="systemrolemenumap" class="table table-condensed table-striped table-bordered table-hover table-responsive">
							<thead>
								<tr>
									<th width="5%"></th>
									<th>Role</th>
									<th>Procuring Agency</th>
								</tr>
							</thead>
							<tbody>
								@foreach($roles as $role)
								<?php $randomKey=randomString();?>
				                <tr>
				                    <td class="text-center">
				                        <input type="checkbox" name="sauserrolemap[{{$randomKey}}][SysRoleId]" value="{{$role->Id}}" class="tablerowcheckbox" @if((int)$role->Selected==1)checked="checked"@endif/>
				                    </td>
				                    <td>
				                    	{{$role->Name}}
				                    </td>
				                    <td>
				                    	@if((int)$role->ReferenceNo==7 || (int)$role->ReferenceNo==8)
				                    	{{--<select id="procuringagen-{{$role->ReferenceNo}}" name="CmnProcuringAgencyId" class="form-control select2me tablerowcontrol" @if((int)$role->Selected!=1)disabled="disabled"@endif>--}}
				                    		{{--<option value="">---SELECT ONE---</option>--}}
				                    		{{--@foreach($procuringAgencies as $procuringAgency)--}}
				                    		{{--<option value="{{$procuringAgency->Id}}" @if($role->CmnProcuringAgencyId==$procuringAgency->Id)selected="selected"@endif>{{$procuringAgency->Name}}</option>--}}
				                    		{{--@endforeach--}}
				                    	{{--</select>--}}
											<input type="hidden" name="CmnProcuringAgencyId" value="{{$role->CmnProcuringAgencyId}}" class="idtoset tablerowcontrol" @if((int)$role->Selected!=1)disabled="disabled"@endif/>
											@if($role->CmnProcuringAgencyId)
												<strong>Current Procuring Agency: {{$role->ProcuringAgency}}</strong>
											@endif
											<div class="tree-demo tree_1">
												<ul>
													<li data-jstree='{ "opened" : false }'> Procuring Agencies
														<ul>
															@foreach($firstLevelProcuringAgencies as $firstLevel)
																<li data-id="{{$firstLevel->Id}}" class="clickable-treeitem" data-jstree='{ "icon" : "fa fa-folder icon-state-info " }'> {{$firstLevel->Name}}
																@if(!empty($secondLevelProcuringAgencies[$firstLevel->Id]))
																	<ul>
																	@foreach($secondLevelProcuringAgencies[$firstLevel->Id] as $secondLevel)
																		<li data-id="{{$secondLevel->Id}}" class="clickable-treeitemchild" data-jstree='{ "icon" : "fa fa-file icon-state-success " }'> {{$secondLevel->Name}}
																			@if(!empty($thirdLevelProcuringAgencies[$secondLevel->Id]))
																			<ul>
																				@foreach($thirdLevelProcuringAgencies[$secondLevel->Id] as $thirdLevel)
																				<li data-id="{{$thirdLevel->Id}}" class="clickable-treeitemchild" data-jstree='{ "icon" : "fa fa-file icon-state-success " }'> {{$thirdLevel->Name}}</li>
																				@endforeach
																			</ul>
																			@endif
																		</li>
																	@endforeach
																	</ul>
																@endif
																</li>
															@endforeach
														</ul>
													</li>
												</ul>
											</div>

				                    	@endif
				                    </td>
				                </tr>
				                @endforeach
							</tbody>
						</table>
					</div>
				</div>
				@endif
			</div>
		</div>
		<div class="form-actions">
			<div class="btn-set">
				<button type="submit" class="btn green">Save</button>
				<a href="{{URL::to('sys/actionsuser')}}" class="btn red">Cancel</a>
			</div>
		</div>
	</div>
	{{Form::close()}}
</div>
@stop