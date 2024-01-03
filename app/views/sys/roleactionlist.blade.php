@extends('master')
@section('content')
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption font-green-seagreen">
			<i class="fa fa-cogs"></i>
			<span class="caption-subject">Edit/Delete Role</span>
		</div>
	</div>
	<div class="portlet-body">
		<div class="row">
			<div class="table-responsive col-md-12 col-sm-12 col-xs-12">
				<table id="tablefilters_1" class="table table-condensed table-striped table-bordered table-hover table-responsive">
					<thead>
						<tr>
							<th>Sl #</th>
							<th class="order">Role</th>
							<th>Description</th>
							<th width="15%">Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php $slNo = 1; ?>
						@foreach($roleLists as $roleList)
						<tr>
							<td>{{$slNo++}}</td>
							<td>{{$roleList->Name}}</td>
							<td>{{$roleList->Description}}</td>
							<td>
								<a href="{{URL::to('sys/role/'.$roleList->Id)}}" class="resetuserpassword btn default btn-xs green-seagreen"><i class="fa fa-edit"></i>Edit</a>|
								<a href="{{URL::to('sys/deleterole/'.$roleList->Id)}}" class="deleteaction btn default btn-xs red"><i class="fa fa-times"></i>Delete</a>
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
@stop