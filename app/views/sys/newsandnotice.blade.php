@extends('master')
@section('content')
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-cogs"></i>
			<span class="caption-subject">Add News/Notifications</span>
		</div>
	</div>
	<div class="portlet-body form">
		<div class="form-body">
			{{ Form::open(array('url' => 'sys/mnewsandnotifications','role'=>'form'))}}
				@foreach($messages as $message)
				<input type="hidden" value="{{$message->Id}}" name="Id">
				<div class="form-body">
					<div class="col-md-4">
						<div class="form-group">
							<label>Message For</label>
							<select name="MessageFor" class="form-control input-medium required">
								<option value="">---SELECT ONE---</option>
								<option value="1" @if((int)Input::old('MessageFor')==1 || (int)$message->MessageFor==1)selected="selected"@endif>CRPS Users</option>
								<option value="2" @if((int)Input::old('MessageFor')==2 || (int)$message->MessageFor==2)selected="selected"@endif>Etool Users</option>
								<option value="3" @if((int)Input::old('MessageFor')==3 || (int)$message->MessageFor==3)selected="selected"@endif>CiNet Users</option>
								<option value="4" @if((int)Input::old('MessageFor')==4 || (int)$message->MessageFor==4)selected="selected"@endif>Contractors</option>
								<option value="5" @if((int)Input::old('MessageFor')==5 || (int)$message->MessageFor==5)selected="selected"@endif>Consultants</option>
								<option value="6" @if((int)Input::old('MessageFor')==6 || (int)$message->MessageFor==6)selected="selected"@endif>Architects</option>
								<option value="7" @if((int)Input::old('MessageFor')==7 || (int)$message->MessageFor==7)selected="selected"@endif>Engineers</option>
								<option value="8" @if((int)Input::old('MessageFor')==8 || (int)$message->MessageFor==8)selected="selected"@endif>Specialzied Trade</option>
							</select>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label>Display In</label>
							<select name="DisplayIn" class="form-control input-medium required">
								<option value="">---SELECT ONE---</option>
								<option value="1" @if((int)Input::old('DisplayIn')==1 || (int)$message->DisplayIn==1)selected="selected"@endif>Login Page</option>
								<option value="2" @if((int)Input::old('DisplayIn')==2 || (int)$message->DisplayIn==2)selected="selected"@endif>Dashboard</option>
							</select>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label for="Date">Date</label>
							<div class="input-icon right input-medium">
								<i class="fa fa-calendar"></i>
								<input type="text" name="Date" value="{{convertDateToCLientFormat($message->Date)}}" class="form-control datepicker required" placeholder="" readonly>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label>News/Notices</label>
						<textarea name="Message" class="form-control required wysihtml5" rows="10">{{$message->Message or Input::old('Message')}}</textarea>
					</div>
					<div class="form-actions">
						<div class="btn-set">
							<button type="submit" class="btn green">Save</button>
							<a href="{{Request::url()}}" class="btn red">Cancel</a>
						</div>
					</div>
				</div>
				@endforeach
			{{Form::close()}}
		</div>
	</div>
</div>
@stop