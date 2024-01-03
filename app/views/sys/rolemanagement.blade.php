@extends('master')
@section('pagescripts')
{{ HTML::script('assets/global/scripts/sys.js') }}
@stop
@section('content')
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption font-green-seagreen">
			<i class="fa fa-cogs"></i>
			<span class="caption-subject">Role</span>
		</div>
	</div>
	{{Form::open(array('url' => 'sys/mrole','role'=>'form','id'=>'roleregistration'))}}
	<div class="portlet-body form">
		<div class="form-body">
			<div class="row">
				@foreach($roles as $role)
				<div class="col-md-4">
					{{Form::hidden('Id',$role->Id)}}
					<div class="form-group">
						{{Form::label('Name','Name')}}
					    {{Form::text('Name',$role->Name,array('class'=>'form-control required'))}}
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						{{Form::label('Description','Description')}}
					     <input type="text" class="form-control" name="Description" value="{{$role->Description}}">
					</div>
				</div>
				@endforeach
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="table-responsive">
					<h5 class="font-blue-hoki bold">Pages</h5>
						<table id="sysrolemenumap" class="table table-condensed table-striped table-bordered table-hover table-responsive">
							<thead>
								<tr>
									<th>Module</th>
									<th>Page</th>
									<th width="5%">View</th>
								</tr>
							</thead>
							<tbody>
								<?php $lastMenuTitle="";$lastMenuDisplayType="";?>
								@foreach($roleMenuMaps as $roleMenuMap)
									@if((int)$roleMenuMap->MenuDisplayType==1)
									<tr @if($roleMenuMap->MenuGroupTitle!=$lastMenuTitle) class="success" @endif>
										<td>
											@if($roleMenuMap->MenuGroupTitle!=$lastMenuTitle)
					        					<b class="text-info"><i class="{{$roleMenuMap->Icon}}"></i>&nbsp;{{$roleMenuMap->MenuGroupTitle}}</b>
					        				@endif
						        		</td>
						        		<td>
						                    {{$roleMenuMap->MenuTitle}}
						                    <input type="hidden" class="menuid" name="systemrolemenumap[{{$roleMenuMap->Id}}][SysMenuId]" value="{{$roleMenuMap->Id}}" @if($roleMenuMap->PageView!=1)disabled="disabled"@endif />
						                </td>
						        		<td class="text-center"><input type="checkbox" name="systemrolemenumap[{{$roleMenuMap->Id}}][PageView]" value="1" class="checkall" @if($roleMenuMap->PageView==1)checked="checked"@endif class="checkinput"/></td>
					        		</tr>
					        		<?php $lastMenuTitle=$roleMenuMap->MenuGroupTitle;$lastMenuDisplayType=$roleMenuMap->MenuDisplayType; ?>
				        			@endif
				        		@endforeach
							</tbody>
						</table>
					</div>
				</div>
				<div class="col-md-6">
					<div class="table-responsive">
					<h5 class="font-blue-hoki bold">Reports</h5>
						<table id="sysrolemenumap" class="table table-condensed table-striped table-bordered table-hover table-responsive">
							<thead>
								<tr>
									<th>Module</th>
									<th>Report</th>
									<th width="5%">View</th>
								</tr>
							</thead>
							<tbody>
								<?php $lastMenuTitle="";$lastMenuDisplayType="";?>
								@foreach($roleMenuMaps as $roleMenuMap)
									@if((int)$roleMenuMap->MenuDisplayType==2)
									<tr @if($roleMenuMap->MenuGroupTitle!=$lastMenuTitle) class="success" @endif>
										<td>
											@if($roleMenuMap->MenuGroupTitle!=$lastMenuTitle)
					        					<b class="text-info"><i class="{{$roleMenuMap->Icon}}"></i>&nbsp;{{$roleMenuMap->MenuGroupTitle}}</b>
					        				@endif
						        		</td>
						        		<td>
						                    {{$roleMenuMap->MenuTitle}}
						                    <input type="hidden" class="menuid" name="systemrolemenumap[{{$roleMenuMap->Id}}][SysMenuId]" value="{{$roleMenuMap->Id}}" @if($roleMenuMap->PageView!=1)disabled="disabled"@endif />
						                </td>
						        		<td class="text-center"><input type="checkbox" name="systemrolemenumap[{{$roleMenuMap->Id}}][PageView]" value="1" class="checkall" @if($roleMenuMap->PageView==1)checked="checked"@endif class="checkinput"/></td>
					        		</tr>
					        		<?php $lastMenuTitle=$roleMenuMap->MenuGroupTitle;$lastMenuDisplayType=$roleMenuMap->MenuDisplayType; ?>
				        			@endif
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
				<a href="{{URL::to('sys/role')}}" class="btn red">Cancel</a>
			</div>
		</div>
	</div>
	{{Form::close()}}
</div>
@stop