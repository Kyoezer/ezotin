<?php
    Session::put('random','123xxx');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8"/>
<title>Construction Development Board</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<link rel="shortcut icon" href="{{asset('assets/cdb/layout/img/favicon.ico')}}">
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<meta http-equiv="Content-type" content="text/html; charset=utf-8">
<meta content="" name="description"/>
<meta content="" name="author"/>
<!-- BEGIN GLOBAL MANDATORY STYLES -->
{{HTML::style('assets/global/css/googlefonts.css')}}
{{ HTML::style('assets/global/plugins/font-awesome/css/font-awesome.min.css') }}
{{ HTML::style('assets/global/plugins/simple-line-icons/simple-line-icons.min.css') }}
{{ HTML::style('assets/global/plugins/bootstrap/css/bootstrap.min.css') }}
{{ HTML::style('assets/global/plugins/uniform/css/uniform.default.css') }}
{{ HTML::style('assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css') }}
<!-- END GLOBAL MANDATORY STYLES -->
{{ HTML::style('assets/global/plugins/select2/select2.css') }}
{{ HTML::style('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css') }}
<!-- BEGIN THEME STYLES -->
{{ HTML::style('assets/global/css/components.css') }}
{{ HTML::style('assets/global/css/plugins.css') }}
{{ HTML::style('assets/global/css/bootstrapValidator.min.css')}}
{{ HTML::style('assets/cdb/layout/css/layout.css') }}
{{ HTML::style('assets/cdb/layout/css/themes/darkblue.css') }}
{{ HTML::style('assets/cdb/layout/css/custom.css') }}
<script src='https://www.google.com/recaptcha/api.js'></script>
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="page-header-fixed page-quick-sidebar-over-content page-full-width">
<input type="hidden" name="URL" value="{{CONST_APACHESITELINK}}" id="apache-sitelink"/>
<!-- BEGIN HEADER -->
@if(Request::segment(1) == 'ezhotin' && Request::segment(2) == 'home')
    <div class="col-md-12" style="background: #fff; margin-top: 2px; margin-left: 2px;">
        <img src="{{asset('uploads/webbanners/PM1.jpg')}}" alt="logo" class="img-responsive hidden-xs hidden-sm" style="width: 99.2%; padding-bottom: 10px; border-bottom: 5px solid #26425e;"/>
    </div>
@endif
<div class="clearfix"></div>
@if(Request::segment(1).'/'.Request::segment(2) != 'ezhotin/home')
<div class="page-header navbar">
    <!-- BEGIN HEADER INNER -->
    <div class="page-header-inner">
      <div class="page-logo">
        <a href="{{URL::to('/')}}">
          <img src="{{asset('assets/cdb/layout/img/logo.png')}}" width="110" height="30" alt="logo" class="logo-default"/>
        </a>
      </div>

        @if(Auth::check())
        <div class="top-menu">
            <ul class="nav navbar-nav pull-right">
                <li class="dropdown dropdown-user">
                    <a href="{{asset('assets/cdb/usermanual/CRPSManual.pdf')}}" class="dropdown-toggle tooltips font-red-intense" target="_blank">
                        <i class="fa fa-question-circle fa-lg font-red-intense"></i> Help
                        <i>&nbsp;</i>
                    </a>
                </li>
                <li class="dropdown dropdown-user">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                        <i class="fa fa-user fa-lg font-yellow-gold"></i>
                        <i class="fa fa-angle-down font-yellow-gold"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="#" class="bold"><i class="fa fa-user"></i> {{Auth::user()->FullName}}</a>
                        </li>
                        <li class="divider"></li>
                        @if(Session::get('agency'))
                            <li>
                                <a href="#" class="bold"><i class="fa fa-cog"></i> {{Session::get('agency')}}</a>
                            </li>
                        @endif
                        <li>
                            <a href={{URL::to('sys/changepassword/1')}}><i class="fa fa-user"></i>Change Password</a>
                        </li>
                        <li>
                            <a href="{{URL::to('auth/logout')}}">
                                <i class="fa fa-power-off"></i> Log Out </a>
                        </li>
                    </ul>
                </li>
                <!-- END USER LOGIN DROPDOWN -->
            </ul>
        </div>
        @endif
    </div>
    <!-- END HEADER INNER -->
</div>
<!-- END HEADER -->
{{--<div class="clearfix">--}}
{{--</div>--}}
@endif
{{--@if(Request::segment(1) == 'ezhotin' && Request::segment(2) == 'home')--}}
<!-- BEGIN CONTAINER -->
<div class="page-container" style="margin-top: 0!Important;">

    <!-- BEGIN CONTENT -->
    <div class="page-content-wrapper">
        <div class="page-content">
            <script>
                var isChrome = !!window.chrome && !!window.chrome.webstore;
                if(!isChrome) {
                    document.write('<div class="note note-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button><strong>This site is best viewed in Google Chrome!</strong></div>');
                }
            </script>
           @if($errors->has())
              <div class="note note-danger">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                  <h4 class="font-red">You have the following ERRORS!</h4>
                  {{HTML::ul($errors->all());}}
              </div>
            @endif
            @if(Session::has('savedsuccessmessage'))
            <div class="note note-success">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                {{ Session::get('savedsuccessmessage')}}
            </div>
            @endif
           @if(Session::has('customerrormessage'))
               @if(Session::get('customerrormessage') != "---")
                    <div class="note note-danger">
                       <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                       {{ Session::get('customerrormessage')}}
                    </div>
               @endif
           @endif

            @yield('content')
            <div class="clearfix"></div>
        </div>
    </div>
    <!-- END CONTENT -->
</div>
<div id="modalmessagebox" class="modal fade" role="dialog" aria-labelledby="modalmessagebox" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h3 id="modalmessageheader" class="modal-title font-red-intense"></h3>
              </div>
              <div class="modal-body">
                <p id="modaldimessagetext"></p>
              </div>
              <div class="modal-footer">
                <button class="btn green-seagreen" data-dismiss="modal" aria-hidden="true">Ok</button>
              </div>
        </div>
    </div>
</div>
<div id="modalwarningbox" class="modal fade" role="dialog" aria-labelledby="modalwarningbox" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h3 id="modalwarningheader" class="modal-title font-red-intense"></h3>
              </div>
              <div class="modal-body">
                <p id="modalwarningtext"></p>
              </div>
              <div class="modal-footer">
                <button class="btn green-seagreen" data-dismiss="modal" aria-hidden="true">Ok</button>
              </div>
        </div>
    </div>
</div>
<!-- END CONTAINER -->
<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- BEGIN CORE PLUGINS -->
<!--[if lt IE 9]>
<script src="../../assets/global/plugins/respond.min.js"></script>
<script src="../../assets/global/plugins/excanvas.min.js"></script> 
<![endif]-->
{{ HTML::script('assets/global/plugins/jquery-1.11.0.min.js') }}
{{ HTML::script('assets/global/plugins/jquery-migrate-1.2.1.min.js') }}
{{ HTML::script('assets/global/plugins/jquery-ui/jquery-ui-1.10.3.custom.min.js') }}
{{ HTML::script('assets/global/plugins/bootstrap/js/bootstrap.min.js') }}
{{ HTML::script('assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js') }}
{{ HTML::script('assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js') }}
{{ HTML::script('assets/global/plugins/jquery.blockui.min.js') }}
{{ HTML::script('assets/global/plugins/jquery.cokie.min.js') }}
{{ HTML::script('assets/global/plugins/uniform/jquery.uniform.min.js') }}
{{ HTML::script('assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
{{ HTML::script('assets/global/plugins/jquery-validation/js/jquery.validate.min.js') }}
{{ HTML::script('assets/global/plugins/jquery-validation/js/additional-methods.min.js') }}
{{ HTML::script('assets/global/plugins/bootstrap-wizard/jquery.bootstrap.wizard.min.js') }}
{{ HTML::script('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') }}
<!-- END PAGE LEVEL PLUGINS -->
{{ HTML::script('assets/global/plugins/select2/select2.min.js') }}
{{ HTML::script('assets/global/scripts/cdb.js') }}
{{ HTML::script('assets/global/scripts/common.js?ver='.randomString()) }}
{{ HTML::script('assets/global/scripts/bootstrapValidator.min.js') }}
{{ HTML::script('assets/cdb/layout/scripts/layout.js') }}
{{ HTML::script('assets/cdb/layout/scripts/quick-sidebar.js') }}
{{ HTML::script('assets/cdb/layout/scripts/demo.js') }}
<script>
    jQuery(document).ready(function() {    
        Metronic.init(); // init cdb core components
        Layout.init(); // init current layout
        QuickSidebar.init(); // init quick sidebar
        Demo.init(); // init demo features
    });
  </script>

  @yield('pagescripts')
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>