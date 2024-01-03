<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>WEB-Construction Development Board</title>
    <link rel="shortcut icon" href="{{ asset('assets/cdb/layout/img/favicon.png') }}">
    {{-- <link href="//vjs.zencdn.net/5.4.6/video-js.min.css" rel="stylesheet"> --}}
    {{-- <script src="//vjs.zencdn.net/5.4.6/video.min.js"></script> --}}
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta http-equiv="Content-type" content="text/html; charset=utf-8">
    <meta content="" name="description" />
    <meta content="" name="author" />

    <meta property="og:url" content="{{ URL::to(Request::url()) }}" />
    @if (Request::segment(2) == 'viewtrainingdetails')
    <meta property="og:type" content="Training" />
    <meta property="og:title" content="{{ $details[0]->TrainingTitle }}" />
    <meta property="og:description" content="Last date of Registration on {{ convertDateToClientFormat($trainingDetails->LastDateForRegistration) }}" />
    <meta property="og:image" content="http://www.cdb.gov.bt/uploads/webbanners/PM1.jpg" />
    @elseif(Request::segment(2) == 'circulardetails')
    <meta property="og:type" content="Circular" />
    <meta property="og:title" content="{{ $circularDetails[0]->Title }}" />
    <meta property="og:description" content="Posted on {{ date_format(date_create($circularDetails[0]->CreatedOn), 'jS F, Y') }}" />
    <meta property="og:image" content="http://www.cdb.gov.bt/uploads/webbanners/PM1.jpg" />
    @elseif(Request::segment(2) == 'advertisementdetails')
    <meta property="og:type" content="Advertisement" />
    <meta property="og:title" content="{{ $advertisementDetails[0]->Title }}" />
    <meta property="og:description" content="Posted on {{ date_format(date_create($advertisementDetails[0]->CreatedOn), 'jS F, Y') }}" />
    <meta property="og:image" content="http://www.cdb.gov.bt/uploads/webbanners/PM1.jpg" />
    @endif
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    {{ HTML::style('http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all') }}
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
    {{ HTML::style('assets/cdb/layout/css/nanogallery.css') }}
    {{ HTML::style('assets/cdb/layout/css/nanogallery_clean.css') }}
    <style type="text/css">
        span.angrytext {
            font-size: 16px;
            color: #4794D3;
            width: 300px;
            margin: 90px auto;

            -webkit-animation: change-color 2s ease 0s infinite normal;
            animation: change-color 2s ease 0s infinite normal;
        }

        @-webkit-keyframes change-color {
            0% {
                color: green;
            }

            100% {
                color: #0063dc;
            }
        }

        @keyframes change-color {
            0% {
                color: green;
            }

            100% {
                color: #0063dc;
            }
        }

        p {
            text-align: justify;
        }

        .hr {
            display: block;
            height: 0;
            overflow: hidden;
            font-size: 0;
            border-top: 2px solid #2f5376;
            margin: 12px 0;
        }

        .hrwhite {
            display: block;
            height: 0;
            overflow: hidden;
            font-size: 0;
            border-top: 2px solid #fff;
            margin: 12px 0;
        }

        .bg-footer {
            background: #2f5376;
            padding-top: 10px;
            color: #fff !important;
            margin-top: 10px;
        }

        .bg-footer a {
            color: #fff;
        }

        .separate {
            margin-top: 5px;
        }

        @media (max-width: 992px) {
            .slider-size {
                height: 194px;
                /*important This is your slider height */
            }
        }

        @media (max-width: 991px) {
            .slider-size {
                height: 284px;
                /* This is your slider height */
            }

            .board-slider {
                height: 300px;
            }

            .carousel {
                width: 100%;
                margin: 0 auto;
                /* center your carousel if other than 100% */
            }
        }

        @media (min-width: 768px) {
            .slider-size {
                height: 500px;
                /* This is your slider height */
            }

            .board-slider {
                height: 300px;
            }

            .carousel {
                width: 100%;
                margin: 0 auto;
                /* center your carousel if other than 100% */
            }

            .sliderwrapper {
                margin-top: 40px;
            }
        }

        .marquee {
            width: 100%;
            overflow: hidden;
        }

        .marquee a {
            color: #E4840C;
        }

        #author {
            border: 1px solid lightgray;
            border-radius: 5mm;
            box-shadow: 0px 0px 5px 0px #00000024;
            margin-left: 2cm;
            padding-bottom: 1cm;
            text-align: center;
        }

        #main {
            margin-bottom: 5%;
        }

        #btn1 {
            margin-bottom: 5mm;
            width: 30%;
            border-radius: 3mm;
        }

        .form-group {
            text-align: center;
            font-family: 'Arial Narrow Bold';
        }

        textarea {
		width: 70%;
            height: 75px;
            padding: 12px 20px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
            background-color: #f8f8f8;
            font-size: 16px;
            resize: none;           
        }

        .text {
            text-align: center;
            padding-top: 1cm;
        }

        label {
            font-size: 110%;
        }

        .error1 {
            color: red;
            text-align: center;
        }

        .nav-item {
            color: #fff;
            padding-top: 3.5mm;
            margin-left: 12.5cm;
        }

        .guide {
            padding-top: 1cm;
            font-size: 12px;
        }

        @media screen and (max-width: 768px) {
            #author {
                margin-left: 0px;
                margin-right: 0px;
                text-align: auto;
            }

            #btn1 {
                margin-top: 1cm;
                margin-bottom: 5mm;
                width: 60%;
                border-radius: 3mm;

            }

            .guide {
                padding-top: 1cm;
                font-size: 12px;
            }

            .text {
                text-align: center;
                padding-top: 1cm;
            }

            #main {
                margin-bottom: 5%;
            }

            .required:after {
                content: " *";
                color: red;
            }

            .required {
                margin-right: 1%
            }

            label {
                font-size: 110%;
            }

            .error1 {
                color: red;
                text-align: center;
            }
        }

        .center-block {
            background: #F3C911;
            padding: 10px;
            margin-top: 5px;
            font-family: Arial;
        }

        .reminder {
            font-family: Arial;
            font-size: 12px;
        }
	::placeholder {
   	    text-align: center; 
	}
    </style>
</head>

<body>
    <div id="fb-root"></div>
    <script>
        (function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s);
            js.id = id;
            js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.5";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    </script>
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <center><img src="{{ asset('assets/cdb/layout/img/dzongkhaorg.png') }}" alt="logo" class="img-responsive visible-xs visible-sm" /></center>
                <img src="{{ asset('assets/cdb/layout/img/journal.png') }}" alt="logo" class="img-responsive hidden-xs hidden-sm" />
                <h3 class="companynamecolor visible-xs visible-sm bold" style="color:#2F5376">Construction Development
                    Board</h3>
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

                    <?php $menuId = 0;
                    $submenuCount = 0; ?>
                    @foreach ($data as $item)
                    <?php if ($item->menuId != $menuId) { ?>
                        <?php if ($menuId != 0) {
                            if ($submenuCount > 0) { ?>
                </ul>
            <?php } ?>
            </li>
        <?php } ?>
        <li class="dropdown">
            @if ($item->MenuRoute != null)
            <a href="{{ URL::to($item->MenuRoute) }}">{{ $item->menuTitle }}</a>
            @else
            <a href="#">{{ $item->menuTitle }}</a>
            @endif

            <?php if ($item->Has_Submenu == 1) { ?>
                <ul class="dropdown-menu">
                    <li><a href="{{ URL::to($item->submenuroute) }}">
                            {{ $item->submenu }}
                        </a></li>
                <?php } ?>

            <?php $menuId = $item->menuId;
                        $submenuCount = 0;
                    } else { ?>
                <li><a href="{{ URL::to($item->submenuroute) }}">
                        {{ $item->submenu }}
                    </a></li>

            <?php $submenuCount++;
                    } ?>
            @endforeach

                </ul>

        <li>
            <a class="nav-item">Welcome , <?php $userName = Session::get('userName'); ?>{{ $userName }}</a>
        </li>
        <li>
            <a class="nav-link" href="{{ URL::to('web/logout') }}">Logout </a>
        </li>


            </div><!-- /.navbar-collapse -->
        </nav>
        {{-- </div><!-- /.container-fluid --> --}}
        @if (Session::get('successful'))
        <div class="alert alert-success alert-block text-center">
            <button type="button" class="close" data-dismiss="alert">x</button>
            <strong>{{ Session::get('successful') }}</strong>
        </div>
        @endif
        @if (Session::get('message'))
        <div class="alert alert-success alert-block text-center">
            <button type="button" class="close" data-dismiss="alert">x</button>
            <strong>{{ Session::get('message') }}</strong>
        </div>
        @endif
       
        <div class="container" id="main">
            <div class="row">
                <div class="col-md-10" id="author">
                    <div class="col-md-offset-2 col-md-8">
                        <h4 class="text"><b> Please Upload Your Manuscript Here.</b></h4>
			<hr>
                        {{ Form::open(['url' => 'web/submitmanuscript','method'=>'post','files'=>true]) }}
                            <div class="form-group">
                                <label class="required">Title</label><br>
                                <textarea name="name_of_title" placeholder="Please give the title of your journal.. "></textarea>
				 @foreach ($content as $item)
                                <input type="hidden" name="Id" value="{{$item->Id}}">
                                @endforeach
                            </div>
                            <div class="form-group">
                                <label class="required">Upload File</label>
                                <input class="center-block" type="file" name="file"> <br>
                    
                                @if ($errors->has('file'))
                                <p class="error1">{{ $errors->first('file') }}</p>
                                @endif
                            </div>
                            <button type="submit" class="btn btn-primary" id="btn1">Submit</button>
                        {{ Form::close() }}
                        <div class="guide">
                            To know about the Manuscript preparation guidelines to the Author
                            <a href="">Download here..</a>
                        </div>
                    </div>
                </div>
            </div>



            {{-- @yield('main-content') --}}

        </div>
        <input name="URL" type="hidden" value="{{ URL::to('/') }}" />
        <div class="bg-footer footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <h4 class=""><i class="fa fa-link text-danger"></i><i class="fa fa-link text-danger"></i><i class="fa fa-link text-danger"></i> Quick Links <i class="fa fa-link text-danger"></i><i class="fa fa-link text-danger"></i><i class="fa fa-link text-danger"></i></h4>
                        <div class="hrwhite"></div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-6">
                        <ul class="list-unstyled">
                            <li><i class="fa fa-key"></i>&nbsp;<a href="{{ URL::to('web/eventcalendar') }}">Events</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-6">
                        <ul class="list-unstyled">
                            <li><i class="fa fa-key"></i>&nbsp;<a href="{{ URL::to('web/viewforum') }}">Forum</a>
                            </li>
                            <li><i class="fa fa-key"></i>&nbsp;<a href="{{ URL::to('web/arbitrationforum') }}">Arbitration Forum</a></li>
                        </ul>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-6">
                        <ul class="list-unstyled">
                            <li><i class="fa fa-key"></i>&nbsp;<a href="{{ URL::to('web/allvideos') }}">TV Spot</a>
                            </li>
                            <li><i class="fa fa-key"></i>&nbsp;<a href="{{ URL::to('web/sitemap') }}">Site Map</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-6">
                        <ul class="list-unstyled">
                            <li><i class="fa fa-key"></i>&nbsp;<a href="{{ URL::to('web/alladvertisements') }}">Advertisements</a></li>
                        </ul>
                    </div>
                </div>
                <div class="hrwhite"></div>
                <div class="row">

                    <div class="col-md-4 col-sm-4 col-xs-4 text-center">
                        <a href="https://www.facebook.com/CDBBHUTAN"><span class="fa fa-facebook-square fa-2x"></span></a>
                        <a href="https://plus.google.com/u/1/communities/108711630567342289499"><span class="fa fa-google-plus-square fa-2x text-danger"></span></a>
                        <!--<a href="#"><span class="fa fa-twitter-square fa-2x text-primary"></span></a>-->


                    </div>
                    <div class="col-md-3 col-sm-4 col-xs-4">

                        <p class="pull-right">
                            <!--<a href="https://docs.google.com/forms/d/1QWKZipKocfU3FSkMxvR7_KIgUg0U3FooU31X0dnMx1w/edit?ts=59ef1645" target="_blank">CDB Help Desk</a>-->
                            |<a href="mailto:webmaster@cdb.gov.bt">Webmaster</a>| <a href="#disclaimer" role="button" data-toggle="modal">Disclaimer</a> |
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <p class="text-center">&copy;&nbsp;Construction Development Board</p>
                        <p class="text-center"><i>{{ date('Y') }} All Rights Reserved</i></p>
                    </div>
                </div>
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
                        <p>The Construction Development Board shall not be liable for any loss or damage caused by the usage
                            of any information obtained from the website.</p>

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
        {{ HTML::script('assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js') }}
        {{ HTML::script('assets/global/plugins/jquery.pause.js') }}
        {{ HTML::script('assets/global/plugins/jquery.easing.min.js') }}
        {{ HTML::script('assets/global/plugins/jquery.marquee.min.js') }}
        <!-- END PAGE LEVEL PLUGINS -->
        {{ HTML::script('assets/global/plugins/select2/select2.min.js') }}
        {{ HTML::script('assets/global/scripts/cdb.js') }}
        {{ HTML::script('assets/global/scripts/common.js?ver=' . randomString()) }}
        {{ HTML::script('assets/cdb/layout/scripts/layout.js') }}
        {{ HTML::script('assets/cdb/layout/scripts/jquery.nanogallery.js') }}
        {{ HTML::script('assets/cdb/pages/scripts/components-pickers.js') }}
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
                $('.navbar-default .navbar-nav > li.dropdown').hover(function() {
                    $('ul.dropdown-menu', this).stop(true, true).delay(200).fadeIn(200);
                    $(this).addClass('open');
                }, function() {
                    $('ul.dropdown-menu', this).stop(true, true).delay(200).fadeOut(200);
                    $(this).removeClass('open');
                });
            });
            jQuery(".nanoGallery2").nanoGallery({
                thumbnailWidth: 140,
                thumbnailHeight: 140,
                thumbnailHoverEffect: [{
                    'name': 'scaleLabelOverImage',
                    'duration': 300
                }, {
                    'name': 'borderLighter'
                }],
                colorScheme: 'clean',
                locationHash: false,
                thumbnailLabel: {
                    display: true,
                    position: 'overImageOnTop',
                    align: 'center'
                },
                viewerDisplayLogo: true
            });
        </script>
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-83968552-1"></script>
        <script>
            window.dataLayer = window.dataLayer || [];

            function gtag() {
                dataLayer.push(arguments);
            }
            gtag('js', new Date());

            gtag('config', 'UA-83968552-1');
        </script>
        <!--<a title="Web Statistics" href="http://clicky.com/101116779"><img alt="Web Statistics" src="//static.getclicky.com/media/links/badge.gif" border="0" /></a>
<script src="//static.getclicky.com/js" type="text/javascript"></script>
<script type="text/javascript">
    try {
        clicky.init(101116779);
    } catch (e) {}
</script>
<noscript><p><img alt="Clicky" width="1" height="1" src="//in.getclicky.com/101116779ns.gif" /></p></noscript>-->
        <!--
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<script>
    (adsbygoogle = window.adsbygoogle || []).push({
        google_ad_client: "ca-pub-7956024561680209",
        enable_page_level_ads: true
    });
</script>-->

</body>

</html>