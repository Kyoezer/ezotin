<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8"/>
	<title>WEB-Construction Development Board</title>
	<link rel="shortcut icon" href="{{asset('assets/cdb/layout/img/favicon.png')}}">
	{{--<link href="//vjs.zencdn.net/5.4.6/video-js.min.css" rel="stylesheet">--}}
	{{--<script src="//vjs.zencdn.net/5.4.6/video.min.js"></script>--}}
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8">
	<meta content="" name="description"/>
	<meta content="" name="author"/>
	<!-- BEGIN GLOBAL MANDATORY STYLES -->
	{{HTML::style('http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all')}}
	{{ HTML::style('assets/global/plugins/font-awesome/css/font-awesome.min.css') }}
	{{ HTML::style('assets/cdb/layout/css/theme.min.css') }}
	<!-- END GLOBAL MANDATORY STYLES -->
	{{ HTML::style('assets/global/plugins/select2/select2.css') }}
	{{ HTML::style('assets/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.css') }}
	{{ HTML::style('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css') }}
	{{ HTML::style('assets/global/plugins/bootstrap-datetimepicker/css/datetimepicker.css') }}
	<!-- BEGIN THEME STYLES -->
	{{ HTML::style('assets/global/css/plugins.css') }}
	{{ HTML::style('assets/cdb/layout/css/layout.css') }}
	{{ HTML::style('assets/cdb/layout/css/custom.css') }}
	{{ HTML::style('assets/cdb/layout/css/nanogallery.css')}}
	{{ HTML::style('assets/cdb/layout/css/nanogallery_clean.css')}}
	<style type="text/css">
		span.angrytext {
			font-size:16px;
			color:#4794D3;
			width:300px;
			margin:90px auto;

			-webkit-animation: change-color 2s ease 0s infinite normal ;
			animation: change-color 2s ease 0s infinite normal ;
		}

		@-webkit-keyframes change-color {
			0%{
				color:green;
			}
			100%{
				color:#0063dc;
			}
		}

		@keyframes change-color {
			0%{
				color:green;
			}
			100%{
				color:#0063dc;
			}
		}
		p{
			text-align: justify;
		}
		.hr{
		  display: block;
		  height: 0;
		  overflow: hidden;
		  font-size: 0;
		  border-top: 2px solid #2f5376;
		  margin: 12px 0;
		}
		.hrwhite{
		  display: block;
		  height: 0;
		  overflow: hidden;
		  font-size: 0;
		  border-top: 2px solid #fff;
		  margin: 12px 0;
		}
		.bg-footer{
			background:#2f5376;
			padding-top:10px;
			color:#fff !important;
			margin-top:10px;
		}
		.bg-footer a{
			color:#fff;
		}
		.separate{
			margin-top:5px;
		}
		@media (max-width: 991px) {
			.slider-size {
				height: 319px;  /* This is your slider height */
			}
			.board-slider{
				height:300px;
			}
			.carousel {
				width:100%; 
				margin:0 auto; /* center your carousel if other than 100% */ 
			}
		}
		@media (min-width: 768px) {
			.slider-size{
				height: 190px; /* This is your slider height */
			}
			.board-slider{
				height:300px;
			}
			.carousel {
				width:100%; 
				margin:0 auto; /* center your carousel if other than 100% */ 
			}
			.sliderwrapper{
				margin-top:40px;
			}
		}
        .marquee{
            width: 100%;
            overflow: hidden;
        }

        .marquee a{
            color: #E4840C;
        }
	</style>
</head>
<body>
<div id="fb-root"></div>
<script>(function(d, s, id) {
		var js, fjs = d.getElementsByTagName(s)[0];
		if (d.getElementById(id)) return;
		js = d.createElement(s); js.id = id;
		js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.5";
		fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));

</script>
<div class="container">
	<div class="row">
		<div class="col-md-12 text-center">
			<center><img src="{{asset('assets/cdb/layout/img/dzongkhaorg.png')}}" alt="logo" class="img-responsive visible-xs visible-sm"/></center>
			<img src="{{asset($pageBanner)}}" alt="logo" class="img-responsive hidden-xs hidden-sm" />
			<h3 class="companynamecolor visible-xs visible-sm bold" style="color:#2F5376">Construction Development Board</h3>
		</div>
	</div>
</div>
<div class="separate"></div>
<div class="container">
	<nav class="navbar navbar-default navbar-static-top">
		    <!-- Brand and toggle get grouped for better mobile display -->
		    <div class="navbar-header">
		      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
		        <span class="sr-only">Toggle navigation</span>
		        <span class="icon-bar"></span>
		        <span class="icon-bar"></span>
		        <span class="icon-bar"></span>
		      </button>
		    </div>
		    <!-- Collect the nav links, forms, and other content for toggling -->
		    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
		      <ul class="nav navbar-nav">
				@foreach($mainMenus as $mainMenu)
					<li class="dropdown" @if($mainMenu->ReferenceNo == 1)data-rel="tooltip" data-original-title="Ezotin is composure of online Services rendered by CDB Secretariat. It has the following applications incorporated with it:
CRPS,
eTool,
CiNET and Registration Services" data-placement="left"@endif>
						@if($mainMenu->MenuRoute==NULL)
						<a href="{{URL::to($mainMenu->MenuRoute)}}" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">@if($mainMenu->ReferenceNo == 1)<img src="{{asset('assets/cdb/layout/img/logo.png')}}" alt="logo" class="logo-default" width="75" height="25"/>@else{{$mainMenu->Title}}@endif</a>
						@else
						<a href="{{URL::to($mainMenu->MenuRoute)}}">{{$mainMenu->Title}}</a>
						@endif
						@foreach($subMenuExists as $hasChildMenu)
							@if($mainMenu->Id == $hasChildMenu->ParentId)
								<ul class="dropdown-menu">
									@foreach($subMenus as $subMenu)
										@if($subMenu->ParentId == $mainMenu->Id)
											@if($subMenu->MenuRoute != NULL)
												<li><a href="{{URL::to($subMenu->MenuRoute)}}">
													{{$subMenu->Title}}
												</a></li>
											@else
												<li><a href="{{URL::to('web/pagedetails/'.$subMenu->Id)}}">
													{{$subMenu->Title}}
												</a></li>
											@endif
										@endif
									@endforeach
								</ul>
							@endif
						@endforeach
					</li>
				@endforeach
		      </ul>
		    </div><!-- /.navbar-collapse -->
	</nav>
</div><!-- /.container-fluid -->
<div class="container">
	@if($errors->has())
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
            <h4 class="font-red">You have the following ERRORS!</h4>
            {{HTML::ul($errors->all());}}
        </div>
    @endif
	@if(Session::has('savedsuccessmessage'))
	<div class="alert alert-success savedsuccessmessage">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
		{{Session::get('savedsuccessmessage')}}
	</div>
	@endif
	@if(Session::has('customerrormessage'))
	<div class="alert alert-danger">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
		{{Session::get('customerrormessage')}}
	</div>
    @endif
    @if($marqueeEnabled == 1 && Request::segment(1) == 'web' && Request::segment(2)=='index')
    <div class="row">
        <div class="col-md-7">
            <h4 class="text-primary">{{$marqueeHeading}}</h4>
            <div class="marquee hide col-md-7">
                @foreach($recentTrainings as $recentTraining)
					@if($recentTraining->Type == 'Advertisement')
						<?php $append = '?type=adv'; $url = "web/advertisementdetails/"; ?>
						<?php $desc = substr($recentTraining->TrainingDescription,0,60); ?>
					@else
						<?php $append = '?type=training'; $url = "web/viewtrainingdetails/" ?>
						<?php $desc = $recentTraining->TrainingDescription; ?>
					@endif
                    <a href="{{URL::to($url.$recentTraining->Id.$append)}}">@if($recentTraining->Title == 'xx'){{substr(strip_tags(html_entity_decode($recentTraining->TrainingDescription)),0,120)}}... @else{{$recentTraining->Title.' - '.substr(strip_tags(html_entity_decode($recentTraining->TrainingDescription)),0,50)}}...@endif</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                @endforeach
            </div>
        </div>
    </div>
    @endif
	@if(Session::has('ArbitratorUserName'))
		<div class="row">
			<div class="col-md-3 pull-right text-right">
				<strong>Welcome, {{Session::get('ArbitratorUserName')}}</strong><br>
			</div>
		</div>
	@endif
	<div class="row" id="arbitration-menu">
		<a href="{{URL::to('web/forumforarbitrators')}}">
			<div class="col-md-1">
				<i class="fa fa-home"></i> Home
			</div>
		</a>
		@if(Session::get('IsAdmin') == 1)
		<a href="{{URL::to("web/arbforumnewtopic")}}">
			<div class="col-md-2">
				<i class="fa fa-plus"></i> Create a topic
			</div>
		</a>
		<a href="{{URL::to("web/arbforumnewcategory")}}">
			<div class="col-md-2">
				<i class="fa fa-plus"></i> Create a category
			</div>
		</a>
		@else
			<a href="{{URL::to("web/arbforumnewtopic")}}">
				<div class="col-md-2">
					<i class="fa fa-plus"></i> Topic for Discussion
				</div>
			</a>
		@endif
		@if(Session::has('ArbitratorUserName'))
			<div class="col-md-2 pull-right" style="margin-right:15px;">
				<a href="{{URL::to('web/arbitratorlogout')}}">
					<i class="fa fa-power-off"></i> Log Out </a>
			</div>
			<div class="col-md-2 pull-right">
				<a href="#changepasswordarb" data-toggle="modal">
					<i class="fa fa-edit"></i> Change Password </a>
			</div>

		@endif
	</div>
	@yield('main-content')

</div>
<div class="bg-footer footer">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12 text-center">
				<h4 class=""><i class="fa fa-link text-danger"></i><i class="fa fa-link text-danger"></i><i class="fa fa-link text-danger"></i> Quick Links <i class="fa fa-link text-danger"></i><i class="fa fa-link text-danger"></i><i class="fa fa-link text-danger"></i></h4>
				<div class="hrwhite"></div>
			</div>
			<div class="col-md-3 col-sm-6 col-xs-6">
				<ul class="list-unstyled">
					<li><i class="fa fa-key"></i>&nbsp;<a href="{{URL::to('web/eventcalendar')}}">Events</a></li>
				</ul>
			</div>
			<div class="col-md-3 col-sm-6 col-xs-6">
				<ul class="list-unstyled">
					<li><i class="fa fa-key"></i>&nbsp;<a href="{{URL::to('web/viewforum')}}">Forum</a></li>
					<li><i class="fa fa-key"></i>&nbsp;<a href="{{URL::to('web/arbitrationforum')}}">Arbitration Forum</a></li>
				</ul>
			</div>
			<div class="col-md-3 col-sm-6 col-xs-6">
				<ul class="list-unstyled">
					<li><i class="fa fa-key"></i>&nbsp;<a href="{{URL::to('web/allvideos')}}">TV Spot</a></li>
				</ul>
			</div>
			<div class="col-md-3 col-sm-6 col-xs-6">
				<ul class="list-unstyled">
					<li><i class="fa fa-key"></i>&nbsp;<a href="{{URL::to('web/alladvertisements')}}">Advertisements</a></li>
				</ul>
			</div>
			<div class="col-md-12 col-sm-12 col-xs-12 text-center">
				<ul class="list-unstyled">
					<li><i class="fa fa-key"></i>&nbsp;<a href="{{URL::to('web/sitemap')}}">Site Map</a></li>
				</ul>
			</div>
		</div>
		<div class="hrwhite"></div>
		<div class="row">
			<div class="col-md-4 col-sm-4 col-xs-4">
				<p>Visitors Count: <i>{{$noOfVisitors}}</i></p>
			</div>
			<div class="col-md-4 col-sm-4 col-xs-4 text-center">
				<a href="https://www.facebook.com/CDBBHUTAN"><span class="fa fa-facebook-square fa-2x"></span></a>
				<a href="#"><span class="fa fa-google-plus-square fa-2x text-danger"></span></a>
				<a href="#"><span class="fa fa-twitter-square fa-2x text-primary"></span></a>
			</div>
			<div class="col-md-4 col-sm-4 col-xs-4">
				<p class="pull-right">| <a href="#disclaimer" role="button" data-toggle="modal">Disclaimer</a> |</p>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<p class="text-center">&copy;&nbsp;Construction Development Board</p>
				<p class="text-center"><i>{{date('Y')}} All Rights Reserved</i></p>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="changepasswordarb" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			{{Form::open(array('url' => 'web/changearbpassword','role'=>'form','id'=>'resetarb')) }}
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h3 class="modal-title font-green-seagreen">Change Password</h3>
			</div>
			<div class="modal-body">
				<div class="note note-warning">
					Enter your new password
				</div>
				<div class="form-group">
					{{ Form::label('password', 'New Password: ') }}
					<input name="password" id="password" type="password" class="form-control password required" />
				</div>
				<div class="form-group">
					{{ Form::label('password_confirmation', 'Re-type Password: ') }}
					<input name="password_confirmation" type="password" class="form-control confirmpassword required" />
				</div>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn green">Reset</button>
				<button class="btn red" data-dismiss="modal" aria-hidden="true">Cancel</button>
			</div>
		</div>
		{{Form::close()}}
	</div>
</div>
<div id="disclaimer" class="modal fade">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Disclaimer</h4>
			</div>

			<div class="modal-body">
				<p>The Construction Development Board shall not be liable for any loss or damage caused by the usage of any information obtained from the website.</p>
			</div>
		</div>
	</div>
</div>
{{ HTML::script('assets/global/plugins/jquery-1.11.0.min.js') }}
{{ HTML::script('assets/global/plugins/jquery-migrate-1.2.1.min.js') }}
{{ HTML::script('assets/global/plugins/jquery-ui/jquery-ui-1.10.3.custom.min.js') }}
{{ HTML::script('assets/global/plugins/bootstrap/js/bootstrap.min.js') }}
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
{{ HTML::script('assets/global/plugins/bootstrap-wysihtml5/wysihtml5-0.3.0.js') }}
{{ HTML::script('assets/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.js') }}

{{ HTML::script('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') }}
{{ HTML::script('assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js')}}
{{ HTML::script('assets/global/plugins/jquery.pause.js')}}
{{ HTML::script('assets/global/plugins/jquery.easing.min.js')}}
{{ HTML::script('assets/global/plugins/jquery.marquee.min.js')}}
<!-- END PAGE LEVEL PLUGINS -->
{{ HTML::script('assets/global/plugins/select2/select2.min.js')}}
{{ HTML::script('assets/global/scripts/cdb.js')}}
{{ HTML::script('assets/global/scripts/common.js?ver='.randomString())}}
{{ HTML::script('assets/cdb/layout/scripts/layout.js')}}
{{ HTML::script('assets/cdb/layout/scripts/jquery.nanogallery.js')}}
{{ HTML::script('assets/cdb/pages/scripts/components-pickers.js')}}
<!--Start of Tawk.to Script-->
<script type="text/javascript">
//	var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
//	(function(){
//		var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
//		s1.async=true;
//		s1.src='https://embed.tawk.to/56b1d21b6cf3877e0c76cda0/default';
//		s1.charset='UTF-8';
//		s1.setAttribute('crossorigin','*');
//		s0.parentNode.insertBefore(s1,s0);
//	})();
</script>
<!--End of Tawk.to Script-->
<script>
    jQuery(document).ready(function() {    
       	Metronic.init(); // init cdb core components
		Layout.init(); // init current layout
//		Demo.init(); // init demo features
        ComponentsPickers.init();
    });
    $('.dropdown').hover(function() {
        $('.navbar-default .navbar-nav > li.dropdown').hover(function () {
	        $('ul.dropdown-menu', this).stop(true, true).delay(200).fadeIn(200);
	        $(this).addClass('open');
	    }, function () {
	        $('ul.dropdown-menu', this).stop(true, true).delay(200).fadeOut(200);
	        $(this).removeClass('open');
	    });
    });
    jQuery(".nanoGallery2").nanoGallery({thumbnailWidth:140,thumbnailHeight:140,
		thumbnailHoverEffect:[{'name':'scaleLabelOverImage','duration':300},{'name':'borderLighter'}],				
		colorScheme:'clean',
		locationHash: false,
		thumbnailLabel:{display:true,position:'overImageOnTop', align:'center'},
		viewerDisplayLogo:true
	});

</script>

</body>
</html>