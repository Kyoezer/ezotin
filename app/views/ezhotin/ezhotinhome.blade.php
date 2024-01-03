@extends('homepagemaster')
@section('content')
<?php
    require public_path()."/captcha/simple-php-captcha.php";
    ?>
    <?php
    $_SESSION['captcha'] = simple_php_captcha(
        array(
            'min_font_size' => 24,
            'max_font_size' => 24,
        )
    );
    $imgSrc = $_SESSION['captcha']['image_src'];

    $indexOfQuestionMark = strpos($imgSrc,'?');
    $captchaUrl = substr($imgSrc,$indexOfQuestionMark+1,strlen($imgSrc));
?>
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
			<div id="extramessagemodal" class="modal fade" role="dialog" aria-labelledby="extramessagemodal" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
							<h3 class="modal-title font-red-intense">ERROR</h3>
						</div>
						<div class="modal-body">
							<p>Your application could not be submitted!</p>
						</div>
						<div class="modal-footer">
							<button class="btn green-seagreen" data-dismiss="modal" aria-hidden="true">Ok</button>
						</div>
					</div>
				</div>
			</div>
			<script>
				$("#extramessagemodal").modal('show');
			</script>
		@endif
	@endif
	@if(Session::has('extramessage'))
		<div id="extramessagemodal" class="modal fade" role="dialog" aria-labelledby="extramessagemodal" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
						<h3 class="modal-title font-red-intense">ERROR</h3>
					</div>
					<div class="modal-body">
						<p>{{Session::get('extramessage')}}</p>
					</div>
					<div class="modal-footer">
						<button class="btn green-seagreen" data-dismiss="modal" aria-hidden="true">Ok</button>
					</div>
				</div>
			</div>
		</div>
		<script>
			$("#extramessagemodal").modal('show');
		</script>
	@endif
@stop
	<div id="register-user" class="modal fade bs-modal-lg" role="dialog" aria-labelledby="addhumanresource" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
					<h4 class="modal-title font-green-seagreen bold">Create User for already registered applicants</h4>
				</div>
					<div class="col-md-12">
						<div class="note note-info">
							<p>Fill in the form and submit the application. You will get intimation from CDB at your email address and mobile no. <strong>Please answer the security question at the end for security reasons.</strong></p>
						</div>
					</div>
					{{Form::open(array('url'=>'registrationexistingapplicants','id'=>'userregistration'))}}
					<div class="modal-body">
						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<label class="control-label">Registration Type</label>
									<select name="RegistrationType" id="RegistrationType" class="form-control input-sm required">
										<option value="">---SELECT---</option>
										<option value="1">Contractor</option>
										<option value="2">Consultant</option>
										<option value="3">Architect</option>
										<option value="4">Engineer</option>
										<option value="5">Specialized Trade</option>
									</select>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label class="control-label">CDB No.</label>
									<input type="text" name="CDBNo" id="cdbnoforreg" class="form-control input-sm required" placeholder="CDB No.">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label class="control-label">Firm Name/Name</label>
									<input type="text" id="FirmName" readonly class="form-control input-sm required" placeholder="Firm Name">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label class="control-label">Email</label>
									<input type="text" id="ApplicantEmail" name="username" class="form-control email input-sm required" placeholder="Email">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label class="control-label">Mobile No</label>
									<input type="text" name="ContactNo" data-fixedlength="8" class="form-control number fixedlengthvalidate input-sm" placeholder="Mobile No.">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label class="control-label">Applicant Name</label>
									<input type="text" name="FullName" value="" class="form-control input-sm required" placeholder="Applicant Name">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label class="control-label">Password</label>
									<input type="password" class="form-control input-sm password required" placeholder="Password">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label class="control-label">Confirm Password</label>
									<input type="password" name="password" class="form-control input-sm required confirmpassword" placeholder="Repeat Password">
								</div>
							</div>
							{{--<div class="col-md-9">--}}
								{{--<label class="control-label">Please answer</label><br>--}}
								{{--<div id="captcha-bg" class="col-md-2 text-center">--}}
									{{--<span id="first-no">{{rand(1,9)}}</span>&nbsp;+&nbsp;<span id="second-no">{{rand(10,18)}}</span>--}}
								{{--</div>--}}
								{{--<div class="form-group">--}}
									{{--<div class="col-md-4">--}}
										{{--<input type="text" id="captcha-answer" placeholder="Answer" class="form-control captcha">--}}
									{{--</div>--}}
								{{--</div>--}}
								{{--<div class="g-recaptcha" data-sitekey="6LfmmicTAAAAAIRSLwmzalZF8W6vsi0E_KhhoT07"></div>--}}
							{{--</div>--}}
							<div class="col-md-3 text-left">
								<img src="{{URL::to('/')."/captcha/simple-php-captcha.php?".$captchaUrl}}"/>
							</div><div class="clearfix"></div><br/>
							<div class="form-group">
								<div class="col-md-3">
									<input type="text" name="CaptchaAnswer" placeholder="Answer" class="form-control input-sm">
								</div>
							</div><div class="clearfix"></div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn green">Save</button>
						<button type="button" class="btn red" data-dismiss="modal" aria-hidden="true">Cancel</button>
					</div>
					{{Form::close()}}
			</div>
		</div>
	</div>
<div class="portlet light bordered">
	<div class="portlet-body">
		<div class="row">
			<div class="col-md-7 col-sm-6 col-xs-12">
				@if((int)$type==1 || (int)$type==4)
				<h4 class="form-title font-green-seagreen">Welcome to E-Zotin System</h4>
				@elseif((int)$type==2)
				<h4 class="form-title font-green-seagreen">Welcome to E-Tool System</h4>
				@elseif((int)$type==3)
				<h4 class="form-title font-green-seagreen">Welcome to CiNet System</h4>
				@endif
				<p class="text-justify">
				@foreach($newsAndNotification as $newsAndNotifications)
				<a href="#" class="name">Administrator </a><span class="datetime">on {{convertDateToClientFormat($newsAndNotifications->Date)}}</span><br />
				{{html_entity_decode($newsAndNotifications->Message)}}
				<hr />
				@endforeach
				</p>

				@if((int)$type == 1)
				<strong>Ezotin</strong> is composure of online Services rendered by CDB Secretariat. It has the following applications incorporated with it: <br/> <br>
<strong>1. Contractor Registration and Performance System -</strong> for managing contractors,conultants,architects and specialized trade. <br/>
<strong>2. eTool -</strong> Online evaluation tool for procurement of civil works carried out by government procuring agencies. <br/>
<strong>3. CiNET(Construction Industry Information System) -</strong> Interface for corporates,NGOs and donor funded projects to update the work information. <br/>
<strong>4. Registration Services -</strong> Online registration services provided by the Secretariat for Contractors,Consultants,Architects and Specialized Trades. <br/>
				@endif
				@if((int)$type == 3)
						<strong>CiNET(Construction Industry Information System) -</strong> Interface for corporates,NGOs and donor funded projects to update the work information. <br/>
				@endif
				@if((int)$type == 2)
						Online evaluation tool for procurement of civil works carried out by government procuring agencies. <br/>
				@endif
				@if((int)$type == 4)
					<h5><strong>Registration System</strong></h5>
					<p>The Construction Development Board (CDB) has developed a Contractors Registration System based upon a classification of Contractors and Categorization of Works.</p>
					<p>
						The registration of Contractors, re-registration, up-gradation, and any matters related to Contractors registration shall be carried out strictly in accordance with the specified principles and procedures.</p>
					<p>
					<p>CDB Registration requirement henceforth shall apply to JVs (amongst National Contractors/Consultants or with Foreign Contractors/Consultants) and also to independent Foreign Construction or Consultancy Firms if they wish to participate in contract/consultancy works in Bhutan.</p>

					<p>All Ministries/Departments/Agencies (government corporate agencies) concerned in the public sector shall use these Registered Contractors/Consultants according to their classifications and categories in the execution of infrastructure projects. The private sectors & the NGOs are also encouraged to use the same.</p>

				@endif
			</div>
			<div class="col-md-5 col-sm-12 col-xs-12 pull-right">
				@if(Session::has('InvalidCredentials'))
	            <div class="note note-danger">
	              <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
	                {{ Session::get('InvalidCredentials')}}
	            </div>
	            @endif
				<div class="col-md-6 col-md-offset-3">
					<a href="{{URL::to('/')}}">
						<img src="{{asset('assets/cdb/layout/img/logo.png')}}" width="110" height="30" alt="logo" class="logo-default"/>
					</a>

				</div><div class="clearfix"></div>
				{{ Form::open(array('url' => 'auth/mauthenticate','role'=>'form'))}}
			        <h4 class="form-title font-green-seagreen">Login to your account</h4>
			        <div class="form-group">
			            <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
			            <label class="control-label visible-ie8 visible-ie9">Username</label>
			            <div class="input-icon">
			                <i class="fa fa-user"></i>
			                <input class="form-control input-xlarge placeholder-no-fix" type="text" placeholder="Username" name="username"/>
			            </div>
			        </div>
			        <div class="form-group">
			            <label class="control-label visible-ie8 visible-ie9">Password</label>
			            <div class="input-icon">
			                <i class="fa fa-lock"></i>
			                <input class="form-control input-xlarge placeholder-no-fix" type="password" autocomplete="off" placeholder="Password" name="password"/>
			            </div>
			        </div>
			        <div class="form-actions">
			            <label class="checkbox">
			            <input type="checkbox" name="RememberMe" value="1"/> Remember me &nbsp;&nbsp; <a href="{{URL::to('ezhotin/forgotpassword')}}" >Forgot Password?</a> </label>
			            <button type="submit" class="btn green">Login <i class="m-icon-swapright m-icon-white"></i></button><br />
                    </div>
			    {{Form::close()}}
				@if(Request::segment(3) == 4)
						<br>
					<a href="#register-user" data-toggle="modal" class="btn blue">Click here to Create user for already registered
						<br>Contractors/Consultants/Architects/Specialized Traders</a>
				@endif

					<div class="col-md-12">
						@if((int)$type==4)
							<hr>
							<h4 class="form-title font-green-seagreen">New Registration</h4>
							<a href="{{URL::to('contractor/default')}}" class="icon-btn col-md-2 col-sm-12 col-xs-12">
								<img src="{{asset('assets/global/img/certificateicon.png')}}" width="20"/>
								<div>
									Contractor
								</div>
							</a>
							<a  href="{{URL::to('consultant/default')}}" class="icon-btn col-md-2 col-sm-12 col-xs-12">
								<img src="{{asset('assets/global/img/certificateicon.png')}}" width="20"/>
								<div>
									Consultant
								</div>
							</a>
							<a  href="{{URL::to('architect/default')}}" class="icon-btn col-md-2 col-sm-12 col-xs-12">
								<img src="{{asset('assets/global/img/certificateicon.png')}}" width="20"/>
								<div>
									Architect
								</div>
							</a>
							{{--<a  href="{{URL::to('engineer/default')}}" class="icon-btn col-md-2 col-sm-12 col-xs-12">--}}
							{{--<img src="{{asset('assets/global/img/certificateicon.png')}}" width="20"/>--}}
							{{--<div>--}}
							{{--Engineer--}}
							{{--</div>--}}
							{{--</a>--}}
							<a  href="{{URL::to('specializedtrade/default')}}" class="icon-btn col-md-2 col-sm-12 col-xs-12">
								<img src="{{asset('assets/global/img/certificateicon.png')}}" width="20"/>
								<div>
									Specialized Trade
								</div>
							</a>
						@endif
					</div>
			</div><div class="clearfix"></div>

		</div>
	</div>
</div>
<!--
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<script>
  (adsbygoogle = window.adsbygoogle || []).push({
    google_ad_client: "ca-pub-7956024561680209",
    enable_page_level_ads: true
  });
</script>-->
@stop

