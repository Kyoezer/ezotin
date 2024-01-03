@extends('homepagemaster')
@section('content')
<div class="portlet light bordered">
	<div class="portlet-body">
		<div class="row">
            <div class="col-md-12">
                <div class="note note-info">
                    Please enter your username and click submit. The password will be reset and will be emailed to you.
                </div>
            </div>
			<div class="col-md-5">

				{{ Form::open(array('url' => 'auth/mresetandsendpassword','role'=>'form'))}}
			        <h4 class="form-title font-green-seagreen">Enter your username</h4>
			        <div class="form-group">
			            <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
			            <label class="control-label visible-ie8 visible-ie9">Username</label>
			            <div class="input-icon">
			                <i class="fa fa-user"></i>
			                <input class="form-control required input-xlarge placeholder-no-fix" type="text" placeholder="Username" name="username"/>
			            </div>
			        </div>

			        <div class="form-actions">

			            <button type="submit" class="btn green">Submit <i class="m-icon-swapright m-icon-white"></i></button><br />
                    </div>
			    {{Form::close()}}
			</div>
		</div>
	</div>
</div>
@stop