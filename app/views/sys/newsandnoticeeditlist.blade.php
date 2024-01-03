@extends('master')
@section('content')
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-cogs"></i>
			<span class="caption-subject">Edit News/Notices</span>
			<span class="caption-helper font-red">- Use the filters provided below to customize your search</span>
		</div>
	</div>
	<div class="portlet-body">
		<div id="addnesandnoticebox" class="form-body">
			{{ Form::open(array('url' =>Request::URL(),'role'=>'form','method'=>'get')) }}
			<input type="hidden" class="rowreferencemodel" value="SysNewsAndNotificationModel">
			<div class="row">
				<div class="col-md-2">
					<div class="form-group">
						<label class="control-label">Message For</label>
						<select name="MessageFor" class="form-control required">
							<option value="">---SELECT ONE---</option>
							<option value="1" @if((int)$messageFor==1)selected="selected"@endif>CRPS Users</option>
							<option value="2" @if((int)$messageFor==2)selected="selected"@endif>Etool Users</option>
							<option value="3" @if((int)$messageFor==3)selected="selected"@endif>CiNet Users</option>
							<option value="4" @if((int)$messageFor==4)selected="selected"@endif>Contractors</option>
							<option value="5" @if((int)$messageFor==5)selected="selected"@endif>Consultants</option>
							<option value="6" @if((int)$messageFor==6)selected="selected"@endif>Architects</option>
							<option value="7" @if((int)$messageFor==7)selected="selected"@endif>Engineers</option>
							<option value="8" @if((int)$messageFor==8)selected="selected"@endif>Specialzied Trade</option>
						</select>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<label class="control-label">Message Displayed In</label>
						<select name="DisplayIn" class="form-control required">
							<option value="">---SELECT ONE---</option>
							<option value="1" @if((int)$displayIn==1)selected="selected"@endif>Login Page</option>
							<option value="2" @if((int)$displayIn==2)selected="selected"@endif>Dashboard</option>
						</select>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label class="control-label">Message Date</label>
						<div class="input-group input-large date-picker input-daterange">
							<input type="text" name="MessageDateFrom" class="form-control datepickerfrom" value="{{$messageDateFrom}}" />
							<span class="input-group-addon">
							to </span>
							<input type="text" name="MessageDateTo" class="form-control datepickerto" value="{{$messageDateTo}}" />
						</div>
					</div>
				</div>
				<div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">No. of Rows</label>
                        {{Form::select('Limit',array(10=>10,20=>20,30=>30,'All'=>'All'),Input::has('Limit')?Input::get('Limit'):20,array('class'=>'form-control'))}}
                    </div>
                </div>
				<div class="col-md-2">
					<label>|</label>
					<div class="btn-set">
						<button type="submit" class="btn blue-hoki btn-sm">Search</button>
						<a href="{{Request::Url()}}" class="btn grey-cascade btn-sm">Clear</a>
					</div>
				</div>
			</div>
			{{Form::close()}}
		</div>
		<table class="table table-bordered table-striped table-condensed flip-content">
			<thead class="flip-content">
				<tr>
					<th>Sl#</th>
					<th>
						Message For
					</th>
					<th>
						Displayed In
					</th>
					<th>
						Date
					</th>
					<th width="50%">
						 News/Notices
					</th>
					<th>
						Action
					</th>
				</tr>
			</thead>
			<tbody>
				<?php $slNo = 1; ?>
				@forelse($messages as $message)
				<tr>
					<td>{{$slNo++}}</td>
					<td>
						<input type="hidden" value="{{$message->Id}}" class="rowreference">
						@if((int)$message->MessageFor==1)
						CRPS Users
						@elseif((int)$message->MessageFor==2)
						Etool Users
						@elseif((int)$message->MessageFor==3)
						CiNet Users
						@elseif((int)$message->MessageFor==4)
						Contractors
						@elseif((int)$message->MessageFor==5)
						Consultants
						@elseif((int)$message->MessageFor==6)
						Architects
						@elseif((int)$message->MessageFor==7)
						Engineers
						@elseif((int)$message->MessageFor==8)
						Specialized Trades
						@endif
					</td>
					<td>
						@if((int)$message->DisplayIn==1)
						Login Page
						@else
						Dasboard
						@endif
					</td>
					<td>
						{{convertDateToClientFormat($message->Date)}}
					</td>
					<td>
						 {{html_entity_decode($message->Message)}}
					</td>
					<td>
						<a href="{{URL::to('sys/addnewsnotice/'.$message->Id)}}" class="btn default btn-xs green-seagreen"><i class="fa fa-edit"></i>Edit</a>|
						<button type="button" class="btn default btn-xs red removerowdbconfirmation"><i class="fa fa-times"></i>Delete</button>
					</td>
				</tr>
				@empty
				<tr>
					<td class="font-red text-center" colspan="10">No data to display</td>
				</tr>
				@endforelse
			</tbody>
		</table>
	</div>
</div>
@stop