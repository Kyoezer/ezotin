@extends('websitemaster')
@section('main-content')

<div class="row">
	<div class="col-md-4 col-md-offset-4 well">
		{{ Form::open(array('action'=>'WebsiteUserController@doLogin', 'method'=>'POST')) }}
			<h4 class="head-title">Login to your account</h4>
			<div class="form-group">
				<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
				<label class="control-label visible-ie8 visible-ie9">Username</label>
				<input class="form-control input-xlarge placeholder-no-fix" type="text" placeholder="Username" name="username"/>
			</div>

			<div class="form-group">
				<label class="control-label visible-ie8 visible-ie9">Password</label>
				<input class="form-control input-xlarge placeholder-no-fix" type="password" autocomplete="off" placeholder="Password" name="password"/>
			</div>

			<input type="submit" value="Login" class="btn btn-success">
			<input type="reset" value="Clear" class="btn btn-danger">
		{{ Form::close() }}
	</div>
</div>

@stop