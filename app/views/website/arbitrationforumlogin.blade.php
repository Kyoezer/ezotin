@extends('homepagemaster')
@section('content')
@section('pagescripts')
    <script>
        $('#userregistration').bootstrapValidator({
            fields: {
                username: {
                    validators: {
                        remote: {
                            message: 'Username already taken',
                            url: "<?php echo CONST_SITELINK.'usernameavalibility/regusers'?>",
                            delay: 2000
                        }
                    }
                }
            }
        });
    </script>
    @if(Session::has('customerrormessage'))
        @if(Session::get("customerrormessage") == "---")
            {{"<script>alert('Your application could not be submitted!');</script>"}}
        @endif
    @endif
@stop
<div class="portlet light bordered">
    <div class="portlet-body">
        <div class="row">
            <div class="col-md-7 col-sm-6 col-xs-12">
                    <h4 class="form-title font-green-seagreen">Welcome to Arbitration Forum</h4>
                <p class="text-justify">
                </p>
            </div>
            <div class="col-md-5 col-sm-12 col-xs-12 pull-right">
                @if(Session::has('InvalidCredentials'))
                    <div class="note note-danger">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                        {{ Session::get('InvalidCredentials')}}
                    </div>
                @endif
                {{ Form::open(array('url' => 'web/arbitratorauth','role'=>'form'))}}
                <h4 class="form-title font-green-seagreen">Login to your account</h4>
                <div class="form-group">
                    <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
                    <label class="control-label visible-ie8 visible-ie9">Username</label>
                    <div class="input-icon">
                        <i class="fa fa-user"></i>
                        <input class="form-control input-xlarge placeholder-no-fix required" type="text" placeholder="Username" name="username"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label visible-ie8 visible-ie9">Password</label>
                    <div class="input-icon">
                        <i class="fa fa-lock"></i>
                        <input class="form-control input-xlarge placeholder-no-fix required" type="password" autocomplete="off" placeholder="Password" name="password"/>
                    </div>
                </div>
                <div class="form-actions">
                    <label class="checkbox">
                        <a href="{{URL::to('ezhotin/forgotpassword')}}" >Forgot Password?</a><br><br>
                    <button type="submit" class="btn green">Login <i class="m-icon-swapright m-icon-white"></i></button>
                    <br />
                </div>
                {{Form::close()}}
            </div><div class="clearfix"></div>
        </div>
    </div>
</div>
@stop