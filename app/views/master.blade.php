<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
<meta charset="utf-8"/>
<title>Construction Development Board</title>
<link rel="shortcut icon" href="{{asset('assets/cdb/layout/img/favicon.png')}}">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<meta http-equiv="Content-type" content="text/html; charset=utf-8">
<meta content="" name="description"/>
<meta content="" name="author"/>
<!-- BEGIN GLOBAL MANDATORY STYLES -->
{{HTML::style('assets/global/css/googlefonts.css')}}
{{ HTML::style('assets/global/plugins/font-awesome/css/font-awesome.min.css') }}
{{ HTML::style('assets/global/plugins/bootstrap/css/bootstrap.min.css') }}
{{ HTML::style('assets/global/plugins/uniform/css/uniform.default.css') }}
{{ HTML::style('assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css') }}
<!-- END GLOBAL MANDATORY STYLES -->
{{ HTML::style('assets/global/plugins/select2/select2.css') }}
{{ HTML::style('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css') }}
{{ HTML::style('assets/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.css') }}
{{ HTML::style('assets/global/plugins/bootstrap-markdown/css/bootstrap-markdown.min.css') }}
{{ HTML::style('assets/global/plugins/bootstrap-summernote/summernote.css') }}
{{ HTML::style('assets/global/plugins/bootstrap-datepicker/css/datepicker3.css') }}
{{ HTML::style('assets/global/plugins/bootstrap-datetimepicker/css/datetimepicker.css') }}
{{ HTML::style('assets/global/plugins/pace/themes/pace-theme-barber-shop.css') }}
{{ HTML::style('assets/global/plugins/jquery-ui/jquery-ui-1.10.3.custom.min.css') }}
@yield('pagestyles')
<!-- BEGIN THEME STYLES -->
{{ HTML::style('assets/global/css/components.css?ver='.randomString()) }}
{{ HTML::style('assets/global/css/plugins.css') }}
{{ HTML::style('assets/global/css/bootstrapValidator.min.css')}}
{{ HTML::style('assets/cdb/layout/css/layout.css') }}
{{ HTML::style('assets/cdb/layout/css/themes/darkblue.css') }}
{{ HTML::style('assets/cdb/layout/css/custom.css') }}
<!-- END THEME STYLES -->
</head>
<body class="page-header-fixed page-quick-sidebar-over-content">
<input type="hidden" name="URL" value="{{CONST_APACHESITELINK}}" id="apache-sitelink"/>
<input type="hidden" id="loggeduser-id" name="LoggedInUserId" value="{{Auth::user()->Id}}"/>
<!-- BEGIN HEADER -->
<div class="page-header navbar navbar-fixed-top">
	<!-- BEGIN HEADER INNER -->
	<div class="page-header-inner">
		<!-- BEGIN LOGO -->
		<div class="page-logo">
			<a href="{{URL::to('/')}}">
			<img src="{{asset('assets/cdb/layout/img/logo.png')}}" width="110" height="30" alt="logo" class="logo-default"/>
			</a>
			<div class="menu-toggler sidebar-toggler">
			</div>
		</div>
		<!-- END LOGO -->
		<!-- BEGIN RESPONSIVE MENU TOGGLER -->
		<a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse"></a>
		<!-- END RESPONSIVE MENU TOGGLER -->
		<!-- BEGIN TOP NAVIGATION MENU -->
		<div class="top-menu">
			<ul class="nav navbar-nav pull-right">
				<!-- BEGIN NOTIFICATION DROPDOWN -->
				 <li class="dropdown dropdown-extended dropdown-notification" id="header_notification_bar">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
					<i class="fa fa-bell" style="color: #d4bc03;"></i>
					<span class="badge badge-default" id="notification-badge">
					</span>
					</a>
					<ul class="dropdown-menu" style="max-width: 500px!important;width: 500px!important;">
						<li>
							<p>
								 You have <span class="notification-count"></span> new notifications
							</p>
						</li>
						<li>
							<ul class="dropdown-menu-list scroller" style="height: 250px;" id="notification-list">

							</ul>
						</li>
						<li class="external">
							<a href="#">
								<strong>Scroll down to see all notifications</strong>
							</a>
						</li>
					</ul>
				</li>
				<!-- END NOTIFICATION DROPDOWN -->
				<!-- BEGIN USER LOGIN DROPDOWN -->
				@if(in_array(CONST_ROLE_ADMINISTRATOR,$userRoles,true))
				<li class="dropdown dropdown-user">
					<a href="{{URL::to('ezhotin/adminnavoptions')}}" class="font-red-intense bold">Admin Dashboard</a>
				</li>
				@endif	
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
							<a href="#editprofilemodal" data-toggle="modal"><i class="fa fa-edit"></i>Edit Profile</a>
						</li>
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
		<!-- END TOP NAVIGATION MENU -->
	</div>
	<!-- END HEADER INNER -->
</div>
<!-- END HEADER -->
<div class="clearfix">
</div>
<!-- BEGIN CONTAINER -->
<div class="page-container">
	<!-- BEGIN SIDEBAR -->
	<div class="page-sidebar navbar-collapse collapse">
        @if(Input::get('export')!='print')
		<ul class="page-sidebar-menu" data-auto-scroll="true" data-slide-speed="200">
			<li><a href="{{URL::to('ezhotin/dashboard')}}">
				<i class="fa fa-dashboard"></i>
				<span class="title">Dashboard</span>
				</a>
			</li>
			<li><a href="{{URL::to('rpt/dashboard')}}" target="_blank">
				<i class="fa fa-file-text"></i>
				<span class="title">Reports</span>
				</a>
			</li>
			 <?php $lastMenuGroupTitle=null;$count=1;?>
             @foreach($menus as $menu)
                @if($menu->MenuGroupTitle!=$lastMenuGroupTitle)
                    @if((bool)$lastMenuGroupTitle!=null)
                        </ul></li>
                    @endif
                    <li class="@if($menu->MenuGroupTitle==$menuGroupTitle){{'active open'}}@endif">
                    <a href="javascript:;">
                        <i class="{{$menu->Icon}}"></i>
                        <span class="title">{{$menu->MenuGroupTitle}}</span>
                        @if($menu->MenuGroupTitle==$menuGroupTitle || (int)$count==1)
                        	<span class="selected"></span>
                        @endif
                        <span class="arrow @if($menu->MenuGroupTitle==$menuGroupTitle){{'open'}}@endif"></span>
                    </a>
                    <ul class="sub-menu">
                @endif
                <li class="tooltips @if($menu->MenuRoute==$currentRoute){{'active'}}@endif" data-container="body" data-placement="right" data-html="true" data-original-title="{{$menu->PageDescription}}">
                	<a href="{{URL::to($menu->MenuRoute)}}"><span class="title">{{$menu->MenuTitle}}</span></a>
                </li>
                <?php $lastMenuGroupTitle=$menu->MenuGroupTitle; $count=0;?>
            @endforeach
            </ul>
            </li>
		</ul>
        @endif
	</div>
	<!-- END SIDEBAR -->
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
			<div class="note note-success savedsuccessmessage">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
				{{Session::get('savedsuccessmessage')}}
			</div>
			@endif
			@if(Session::has('customerrormessage'))
			<div class="note note-danger">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
				{{Session::get('customerrormessage')}}
			</div>
			@endif
			<div class="page-bar">
				@yield('searchbutton')
				@if((bool)$searchId!=null)
					<div class="page-toolbar pull-left">
						<a href="{{URL::to('search/'.$searchRoute.'/'.$searchId.'?redirectUrl='.Request::url())}}" class="btn btn-fit-height purple-plum dropdown-toggle"><i class="fa fa-search"></i> Search</a>
					</div>
				@endif
				@if((bool)$menuGroupTitle!=null && (bool)$subMenuTitle!=null)
					<ul class="page-breadcrumb">
						<li><a href="#"><i class="{{$menuGroupIcon}}"></i>{{$menuGroupTitle}}</a><i class="fa fa-angle-right"></i></li>
						<li><a href="#"><i class="fa fa-keyboard-o"></i>&nbsp;{{$subMenuTitle}}</a></li>
					</ul>
				@endif
				@yield('lastcdbno')
			</div>
			@if(count($applications)>0)
				<div class="col-md-12">
					<div class="note note-danger">
						<h4><strong>Reminders for replacement of  deleted Human Resource</strong></h4>
						@foreach($applications as $application)
							<span style="text-decoration: underline;">{{$application->NameOfFirm}} ({{$application->CDBNo}})</span> has deleted a Human Resource: {{$application->Name}} ({{$application->Designation}}) bearing CID No./Work Permit {{$application->CIDNo}} on {{convertDateToClientFormat($application->DeletedOn)}} &nbsp;<a href="{{URL::to('all/updatedeletedhumanresourcenotification')}}/{{$application->Id}}?redirect={{Request::url()}}" class="btn btn-xs btn-success"><i class="fa fa-check"></i> Click here if Replaced</a> <br/>
						@endforeach
					</div>
				</div>
			@endif
			@yield('content')

            @if(Input::get('export') == 'print')
                Printed by {{Auth::user()->FullName}} on {{date('Y-m-d G:i:s')}}
            @endif
		</div>
	</div>
	<!-- END CONTENT -->
</div>
<div class="scroll-to-top">
	<i class="fa fa-chevron-circle-up"></i>
</div>
<div id="sessionexpiredmodal" class="modal fade"  data-keyboard="false" data-backdrop="static" role="dialog" aria-labelledby="modalmessagebox" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h3 class="modal-title font-red-intense">Login!</h3>
			</div>
			<div class="modal-body">
				<p>Please login again to continue</p>
			</div>
			<div class="modal-footer">
				<a class="btn green-seagreen" href="{{URL::to('/')}}">Login</a>
			</div>
		</div>
	</div>
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
<div id="deleteconfirmation" class="modal fade" role="dialog" aria-labelledby="deleteconfirmation" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h3 id="deleteconfirmationheader" class="modal-title font-red-intense">Confirmation</h3>
              </div>
              <div class="modal-body">
              	<p id="deletesuccessmessage" class="hide font-green"></p>
                <p id="deleteconfirmationtext"><h4>Are you sure you want to delete this record?</h4></p>
              </div>
              <div class="modal-footer">
              	<input type="hidden" class="recordreference">
              	<input type="hidden" class="recordreferencemodel">
                <button type="button" class="btn red-intense removerecorddb" data-dismiss="modal" aria-hidden="true">Delete</button>
                <button type="button" class="btn green" data-dismiss="modal" aria-hidden="true">Cancel</button>
              </div>
        </div>
    </div>
</div>
@if(isset($recordLockException) && $recordLockException)
<div id="applicationalreadypicked" class="modal fade" role="dialog" aria-labelledby="applicationalreadypicked" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h3 class="modal-title font-red">Alert!</h3>
			</div>
			<div class="modal-body">
				<p class="bold">The application you tried to pick has already been picked by {{$applicationPickedByUserFullName}}.</p>
			</div>
			<div class="modal-footer">
				<button class="btn blue" data-dismiss="modal" aria-hidden="true">Ok</button>
			</div>
		</div>
	</div>
</div>
@endif
<div id="editprofilemodal" class="modal fade" role="dialog" aria-labelledby="modalmessagebox" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h3 class="modal-title font-red-intense">
					Edit your profile
				</h3>
			</div>
			{{Form::open(array('url'=>'sys/editprofile','id'=>'editprofileform'))}}
			{{Form::hidden("RedirectUrl",Request::path())}}
			<div class="modal-body">
				<div class="row">
					<div class="col-md-3">
						<div class="form-body">
							<div class="form-group">
								<label for="username" class="control-label">Username (used for login):</label>
								<input type="text" id="username" name="username" value="{{Auth::user()->username}}" class="form-control input-sm">
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-body">
							<div class="form-group">
								<label for="FullName" class="control-label">Name:</label>
								<input type="text" id="FullName" name="FullName" value="{{Auth::user()->FullName}}" class="form-control input-sm">
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-body">
							<div class="form-group">
								<label for="Email" class="control-label">Email:</label>
								<input type="text" id="Email" name="Email" value="{{Auth::user()->Email}}" class="form-control email required input-sm">
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-body">
							<div class="form-group">
								<label for="cno" class="control-label">Contact no.:</label>
								<input type="text" id="cno" name="ContactNo" value="{{Auth::user()->ContactNo}}" class="form-control required input-sm">
							</div>
						</div>
					</div>
				</div>
			</div><div class="clearfix"></div>
			<div class="modal-footer">
				<button class="btn blue" type="submit">Update</button>
				<button class="btn green-seagreen" data-dismiss="modal" aria-hidden="true">Cancel</button>
			</div>
			{{Form::close()}}
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

{{ HTML::script('assets/global/plugins/bootstrap-wysihtml5/wysihtml5-0.3.0.js') }}
{{ HTML::script('assets/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.js') }}
{{ HTML::script('assets/global/plugins/bootstrap-markdown/lib/markdown.js') }}
{{ HTML::script('assets/global/plugins/bootstrap-markdown/js/bootstrap-markdown.js') }}
{{ HTML::script('assets/global/plugins/bootstrap-summernote/summernote.min.js') }}
{{ HTML::script('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') }}
{{ HTML::script('assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js')}}
<!-- END PAGE LEVEL PLUGINS -->
{{ HTML::script('assets/global/plugins/pace/pace.min.js') }}
{{ HTML::script('assets/global/plugins/select2/select2.min.js') }}
{{ HTML::script('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js') }}
{{ HTML::script('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js') }}
{{ HTML::script('assets/global/scripts/cdb.js') }}
{{ HTML::script('assets/global/scripts/common.js?ver='.randomString()) }}
{{ HTML::script('assets/global/scripts/bootstrapValidator.min.js') }}
{{ HTML::script('assets/cdb/layout/scripts/layout.js') }}
{{ HTML::script('assets/cdb/layout/scripts/quick-sidebar.js') }}
{{ HTML::script('assets/cdb/layout/scripts/demo.js') }}
{{ HTML::script('assets/cdb/pages/scripts/components-pickers.js')}}
{{ HTML::script('assets/cdb/pages/scripts/components-editors.js') }}
<script>
    jQuery(document).ready(function() {    
       	Metronic.init(); // init cdb core components
		Layout.init(); // init current layout
		QuickSidebar.init(); // init quick sidebar
		Demo.init(); // init demo features
		ComponentsEditors.init();
		ComponentsPickers.init();
    });
  </script>
<!-- END JAVASCRIPTS -->
@if(isset($recordLockException) && $recordLockException)
<script>
	$('#applicationalreadypicked').modal('show');
</script>
@endif
@if(Input::get('export') == 'print')
    <script>
        window.print();
    </script>
@endif
@yield('pagescripts')
</body>
<!-- END BODY -->
</html>