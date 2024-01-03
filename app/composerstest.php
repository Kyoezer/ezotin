<?php
/*
Crafted with love and lots of Coffee
Name: Kinley Nidup
Web Name: Zero Cool
email: nidup.kinley@gmail.com
facebook link:https://www.facebook.com/kgyel
*/

/*-------------------Menu Management-----------------------------------------------*/

View::composer("*",function($view){
	$isAdmin = false;
	if(isset(Auth::user()->Id)){
		$loggedInUser=Auth::user()->Id;
		$userRoles=DB::table('sysuserrolemap')->where('SysUserId',$loggedInUser)->lists('SysRoleId');
		if(in_array(CONST_ROLE_ADMINISTRATOR,$userRoles)){
			$isAdmin = true;
		}
	}

	$view->with('isAdmin',$isAdmin);
});
View::composer('master', function($view){
//	dd('here');
	$menuGroupTitle=null;
	$subMenuTitle=null;
	$searchId=null;
	$searchRoute=null;
	$subMenuId=null;
	$menuGroupIcon=null;
   	$currentRoute=Request::path();
   	$currentRoutePrefix=Request::segment(1);
   	$loggedInUser=Auth::user()->Id;
	$isAdmin = false;
	$pageInfo=DB::table('sysmenugroup as T1')->join('sysmenu as T2','T1.Id','=','T2.SysMenuGroupId')->where('T2.MenuRoute',$currentRoute)->take(1)->get(array('T1.MenuGroupTitle','T1.Icon','T2.Id as SubMenuId','T2.CmnSearchId','T2.MenuTitle as SubMenuTitle','T2.SearchRoute'));
	if(!empty($pageInfo)){
		$menuGroupTitle=$pageInfo[0]->MenuGroupTitle;
		$subMenuTitle=$pageInfo[0]->SubMenuTitle;
		$searchId=$pageInfo[0]->CmnSearchId;
		$searchRoute=$pageInfo[0]->SearchRoute;
		$subMenuId=$pageInfo[0]->SubMenuId;
		$menuGroupIcon=$pageInfo[0]->Icon;
	}
	$userRoles=DB::table('sysuserrolemap')->where('SysUserId',$loggedInUser)->lists('SysRoleId');
	if(in_array(CONST_ROLE_ADMINISTRATOR,$userRoles)){
		$isAdmin = true;
	}

	//$menus=DB::select("select T1.MenuGroupTitle,T1.Icon,T1.RoutePrefix,T2.Id,T2.MenuTitle,T2.MenuRoute,T2.Description as PageDescription from sysmenugroup T1 join sysmenu T2 on T1.Id=T2.SysMenuGroupId where coalesce(T2.IsInActive,0)<>1 order by T1.DisplayOrder,T2.DisplayOrder");
	$menus=DB::table('sysmenugroup as T1')->join('sysmenu as T2','T1.Id','=','T2.SysMenuGroupId')->where('T1.MenuType',1)->where('T2.MenuDisplayType',1)->whereRaw('coalesce(T2.IsInactive,0)<>1 and T2.Id in (select SysMenuId from sysrolemenumap as A join sysuserrolemap as B on A.SysRoleId=B.SysRoleId where B.SysUserId=? and A.PageView=1)',array($loggedInUser))->orderBy('T1.DisplayOrder')->orderBy('T2.DisplayOrder')->get(array('T1.MenuGroupTitle','T1.Icon','T1.RoutePrefix','T2.Id','T2.MenuTitle','T2.MenuRoute','T2.Description as PageDescription'));
	//DELETE NOTIFICATIONS
	$thirtyDaysAgo =strtotime(date('Y-m-d').' -30 days');
	$thirtyDaysAgo = date('Y-m-d',$thirtyDaysAgo);
	$applications = DB::select("select T2.Id,T1.CIDNo,T1.Name, B.Name as Designation, A.CDBNo, A.NameOfFirm, T1.DeletedOn from (crpdeletedhumanresource T1 join crpcontractorfinal A on A.Id = T1.ApplicantFinalId join cmnlistitem B on B.Id = T1.CmnDesignationId) join crpnotificationfordeletedhumanresource T2 on T1.ApplicationId = T2.ApplicationId and T1.SysDeletedByUserId = T2.SysUserId where T2.SysUserId= ? and T1.DeletedOn <= ? and T2.IsDeleted = 0 order by A.CDBNo",array(Auth::user()->Id,$thirtyDaysAgo));
	//END DELETE NOTIFICATIONS-


    $userAgencyId = DB::table('sysuser')->where('Id',Auth::user()->Id)->pluck('CmnProcuringAgencyId');
$module=Request::segment(1);
	$userAgencyId = DB::table('sysuser')->where('Id',Auth::user()->Id)->pluck('CmnProcuringAgencyId');
	$totalNotOpenTender = "";
	if($module=="newEtl"){
		$totalNotOpenTender = DB::select("SELECT  
						COUNT(*) totalTender
						FROM
						etltender T1
						LEFT JOIN etlevaluationscore a ON T1.`Id` = a.`EtlTenderId`
						WHERE
						T1.CmnProcuringAgencyId = '".$userAgencyId."'
						AND T1.TenderSource = 1
						AND COALESCE (T1.DeleteStatus, 'N') <> 'Y'
						AND COALESCE (CmnWorkExecutionStatusId, 0) NOT IN (
							'a13c5d39-b5a8-11e4-81ac-080027dcfac6',
							'9cc4dab5-b5a8-11e4-81ac-080027dcfac6',
							'a98f434b-d8ee-11e4-afa1-9c2a70cc8e06'
						)
						AND TIMESTAMPDIFF(MONTH,T1.TenderOpeningDateAndTime,CURRENT_TIMESTAMP)>0
						AND a.`Id` IS  NULL  AND T1.`TenderStatus` NOT IN ('Cancelled')
						AND T1.`IsSPRRTender`='Y'
						");
		$totalNotOpenTender = $totalNotOpenTender[0]->totalTender;
	}
	else if($module=="etl"){
			$totalNotOpenTender = DB::select("SELECT  
							COUNT(*) totalTender
							FROM
							etltender T1
							LEFT JOIN etlevaluationscore a ON T1.`Id` = a.`EtlTenderId`
							WHERE
							T1.CmnProcuringAgencyId = '".$userAgencyId."'
							AND T1.TenderSource = 1
							AND COALESCE (T1.DeleteStatus, 'N') <> 'Y'
							AND COALESCE (CmnWorkExecutionStatusId, 0) NOT IN (
								'a13c5d39-b5a8-11e4-81ac-080027dcfac6',
								'9cc4dab5-b5a8-11e4-81ac-080027dcfac6',
								'a98f434b-d8ee-11e4-afa1-9c2a70cc8e06'
							)
							AND TIMESTAMPDIFF(MONTH,T1.TenderOpeningDateAndTime,CURRENT_TIMESTAMP)>0
							AND a.`Id` IS  NULL  AND T1.`TenderStatus` NOT IN ('Cancelled')
							AND T1.`IsSPRRTender`='N'
							");
			$totalNotOpenTender = $totalNotOpenTender[0]->totalTender;
        }


	$view->with('menuGroupTitle',$menuGroupTitle)
		->with('userRoles',$userRoles)
		->with('isAdmin',$isAdmin)
		->with('applications',$applications)
		->with('subMenuTitle',$subMenuTitle)
		->with('currentRoute',$currentRoute)
		->with('searchId',$searchId)
		->with('searchRoute',$searchRoute)
		->with('subMenuId',$subMenuId)
		->with('menuGroupIcon',$menuGroupIcon)
		->with('currentRoutePrefix',$currentRoutePrefix)
		->with('menus',$menus)
        ->with('totalNotOpenTender',$totalNotOpenTender );
});

/*--------Start of Horzontal Menu for etool,CiNET, Users contractor,consultant,Architect,Engineer and Specialized trade------*/
View::composer('horizontalmenumaster', function($view){
	$dualModuleCheck = false;
	$currentRoute=Request::path();
	$loggedInUser=Auth::user()->Id;
	$userRoles=DB::table('sysuserrolemap')->where('SysUserId',$loggedInUser)->lists('SysRoleId');
	$emailId = "-";
	$totalNotOpenTender = "";
	$userAgencyId = DB::table('sysuser')->where('Id',Auth::user()->Id)->pluck('CmnProcuringAgencyId');
	if(in_array(CONST_ROLE_ADMINISTRATOR,$userRoles,true)){
		$module=Request::segment(1);
		if($module=="cinet"){
            $curUserRole=CONST_ROLE_PROCURINGAGENCYCINET;
        }
		else if($module=="newEtl"){
			$totalNotOpenTender = DB::select("SELECT  
							COUNT(*) totalTender
							FROM
							etltender T1
							LEFT JOIN etlevaluationscore a ON T1.`Id` = a.`EtlTenderId`
							WHERE
							T1.CmnProcuringAgencyId = '".$userAgencyId."'
							AND T1.TenderSource = 1
							AND COALESCE (T1.DeleteStatus, 'N') <> 'Y'
							AND COALESCE (CmnWorkExecutionStatusId, 0) NOT IN (
								'a13c5d39-b5a8-11e4-81ac-080027dcfac6',
								'9cc4dab5-b5a8-11e4-81ac-080027dcfac6',
								'a98f434b-d8ee-11e4-afa1-9c2a70cc8e06'
							)
							AND TIMESTAMPDIFF(MONTH,T1.TenderOpeningDateAndTime,CURRENT_TIMESTAMP)>0
							AND a.`Id` IS  NULL  AND T1.`TenderStatus` NOT IN ('Cancelled')
							AND T1.`IsSPRRTender`='Y'
							");
			$curUserRole="f0ffe82a-c32b-11e4-ac25-080027dcfac1";
			$totalNotOpenTender = $totalNotOpenTender[0]->totalTender;
	
        }
		else if($module=="etl"){
			$totalNotOpenTender = DB::select("SELECT  
							COUNT(*) totalTender
							FROM
							etltender T1
							LEFT JOIN etlevaluationscore a ON T1.`Id` = a.`EtlTenderId`
							WHERE
							T1.CmnProcuringAgencyId = '".$userAgencyId."'
							AND T1.TenderSource = 1
							AND COALESCE (T1.DeleteStatus, 'N') <> 'Y'
							AND COALESCE (CmnWorkExecutionStatusId, 0) NOT IN (
								'a13c5d39-b5a8-11e4-81ac-080027dcfac6',
								'9cc4dab5-b5a8-11e4-81ac-080027dcfac6',
								'a98f434b-d8ee-11e4-afa1-9c2a70cc8e06'
							)
							AND TIMESTAMPDIFF(MONTH,T1.TenderOpeningDateAndTime,CURRENT_TIMESTAMP)>0
							AND a.`Id` IS  NULL  AND T1.`TenderStatus` NOT IN ('Cancelled')
							AND T1.`IsSPRRTender`='N'
							");
							
			$totalNotOpenTender = $totalNotOpenTender[0]->totalTender;
			$curUserRole=CONST_ROLE_PROCURINGAGENCYETOOL;
             }else if($module=="cb"){
			$curUserRole=CONST_ROLE_PROCURINGAGENCYCBUILDER;

        }else{
            $curUserRole=CONST_ROLE_PROCURINGAGENCYETOOL;
        }
		$emailId = DB::table('sysuser')->where('Id',$loggedInUser)->pluck('Email');
		$horizontalMenus=DB::table('sysmenugroup as T1')
		->join('sysmenu as T2','T1.Id','=','T2.SysMenuGroupId')
		->where('T1.MenuType',2)->where('T2.MenuDisplayType',1)
		->whereRaw('coalesce(T2.IsInactive,0)<>1 and T2.Id in (select SysMenuId from sysrolemenumap as A join sysuserrolemap as B on A.SysRoleId=B.SysRoleId where B.SysRoleId=? and A.PageView=1)',array($curUserRole))->orderBy('T1.DisplayOrder')->orderBy('T2.DisplayOrder')->get(array('T2.MenuTitle','T2.MenuRoute'));
	}else{
		if(count($userRoles) == 2){
			if(in_array(CONST_ROLE_PROCURINGAGENCYCINET,$userRoles,true) && in_array(CONST_ROLE_PROCURINGAGENCYETOOL,$userRoles,true)){
				$dualModuleCheck = true;
				$module=Request::segment(1);
				if($module=="cinet"){
					$curUserRole=CONST_ROLE_PROCURINGAGENCYCINET;
                                }else if($module=="cb"){
					$curUserRole=CONST_ROLE_PROCURINGAGENCYCBUILDER;

				}else{
					$curUserRole=CONST_ROLE_PROCURINGAGENCYETOOL;
				}
				$horizontalMenus=DB::table('sysmenugroup as T1')->join('sysmenu as T2','T1.Id','=','T2.SysMenuGroupId')->where('T1.MenuType',2)->where('T2.MenuDisplayType',1)->whereRaw('coalesce(T2.IsInactive,0)<>1 and T2.Id in (select SysMenuId from sysrolemenumap as A join sysuserrolemap as B on A.SysRoleId=B.SysRoleId where B.SysRoleId=? and A.PageView=1)',array($curUserRole))->orderBy('T1.DisplayOrder')->orderBy('T2.DisplayOrder')->get(array('T2.MenuTitle','T2.MenuRoute'));
			}
		}
		if(!$dualModuleCheck){
			$curUserRole=RoleUserMapModel::where('SysUserId',$loggedInUser)->pluck('SysRoleId');
			$horizontalMenus=DB::table('sysmenugroup as T1')->join('sysmenu as T2','T1.Id','=','T2.SysMenuGroupId')->where('T1.MenuType',2)->where('T2.MenuDisplayType',1)->whereRaw('coalesce(T2.IsInactive,0)<>1 and T2.Id in (select SysMenuId from sysrolemenumap as A join sysuserrolemap as B on A.SysRoleId=B.SysRoleId where B.SysUserId=? and A.PageView=1)',array($loggedInUser))->orderBy('T1.DisplayOrder')->orderBy('T2.DisplayOrder')->get(array('T2.MenuTitle','T2.MenuRoute'));
		}
		$emailId = DB::table('sysuser')->where('Id',$loggedInUser)->pluck('Email');
	}
    $module=Request::segment(1);
	$view->with('currentRoute',$currentRoute)
			->with('emailId',$emailId)
			->with('dualModuleCheck',$dualModuleCheck)
                        ->with('module',$module)
			->with('userRoles',$userRoles)
			->with('curUserRole',$curUserRole)
			->with('horizontalMenus',$horizontalMenus)
            ->with('totalNotOpenTender',$totalNotOpenTender);
});
/*--------Start of Horzontal Menu for Reports------*/
View::composer('reportsmaster', function($view){
	$menuGroupTitle=null;
	$currentRoute=Request::path();
	$loggedInUser=Auth::user()->Id;
	$horizontalMenusSubMenuCount = array();
	$pageInfo=DB::table('sysmenugroup as T1')->join('sysmenu as T2','T1.Id','=','T2.SysMenuGroupId')->where('T2.MenuRoute',$currentRoute)->take(1)->get(array('T1.MenuGroupTitle','T1.Icon','T2.Id as SubMenuId'));
	if(!empty($pageInfo)){
		$menuGroupTitle=$pageInfo[0]->MenuGroupTitle;
	}
	$horizontalMenus=DB::table('sysmenugroup as T1')->join('sysmenu as T2','T1.Id','=','T2.SysMenuGroupId')->where('T2.MenuDisplayType',2)->whereRaw('coalesce(T2.IsInactive,0)<>1 and T2.Id in (select SysMenuId from sysrolemenumap as A join sysuserrolemap as B on A.SysRoleId=B.SysRoleId where B.SysUserId=? and A.PageView=1)',array($loggedInUser))->orderBy('T1.DisplayOrder')->orderBy('T2.DisplayOrder')->get(array('T1.Id as MenuGroupId','T1.MenuGroupTitle','T1.Icon','T2.MenuTitle','T2.MenuRoute'));
	foreach($horizontalMenus as $horizontalMenu):
		$horizontalMenusSubMenuCount[$horizontalMenu->MenuGroupId] = DB::table('sysmenugroup as T1')->join('sysmenu as T2','T1.Id','=','T2.SysMenuGroupId')->where('T2.SysMenuGroupId',$horizontalMenu->MenuGroupId)->where('T2.MenuDisplayType',2)->whereRaw('coalesce(T2.IsInactive,0)<>1 and T2.Id in (select SysMenuId from sysrolemenumap as A join sysuserrolemap as B on A.SysRoleId=B.SysRoleId where B.SysUserId=? and A.PageView=1)',array($loggedInUser))->orderBy('T1.DisplayOrder')->orderBy('T2.DisplayOrder')->groupBy('T1.Id')->count();
	endforeach;

    $userRoles=DB::table('sysuserrolemap')->where('SysUserId',$loggedInUser)->lists('SysRoleId');
    $view->with('currentRoute',$currentRoute)
        ->with('userRoles',$userRoles)
		 ->with('menuGroupTitle',$menuGroupTitle)
		 ->with('horizontalMenusSubMenuCount',$horizontalMenusSubMenuCount)
		 ->with('horizontalMenus',$horizontalMenus);
});
/*---Start of Home page master -- */
View::composer('homepagemaster',function($view){
	$pageBanner=DB::table('webpagebanner')->pluck('ImageSource');
	$view->with('pageBanner',$pageBanner);
});

/*--------Start of Website Menu--------*/
/*--------Start of Website Menu--------*/
View::composer('websitemaster',function($view){
	$pageBanner=DB::table('webpagebanner')->pluck('ImageSource');
	$language=Session::get('language');
	if($language == 'english'){
		$mainMenus=WebPostPageModel::where('ParentId',NULL)
								->where('ShowInWebsite',1)
								->orderBy('DisplayOrder','asc')
								->get(array('Id','MenuRoute','Title','DisplayOrder','ParentId','ReferenceNo'));
	}elseif($language == 'dzongkha'){
		$mainMenus=WebPostPageModel::where('ParentId',NULL)
								->where('ShowInWebsite',1)
								->orderBy('DisplayOrder','asc')
								->get(array('Id','MenuRoute','TitleDz','DisplayOrder','ParentId','ReferenceNo'));
	}
else{
	$mainMenus=WebPostPageModel::where('ParentId',NULL)
	->where('ShowInWebsite',1)
	->orderBy('DisplayOrder','asc')
	->get(array('Id','MenuRoute','Title','DisplayOrder','ParentId','ReferenceNo'));
	}
	$subMenus=DB::select("select * from webpostpage where ShowInWebsite = ? order by DisplayOrder asc", array(1));
	$subMenuExists=DB::select("select distinct ParentId from webpostpage");
	$footerLists = WebPostPageModel::where('ShowInFooter',1)
									->orderBy('FooterDisplayOrder','asc')
									->get(array(DB::raw('distinct Id'),'MenuRoute','Title','FooterDisplayOrder'));
									

	/*Visitor Count start*/
	$uuid = DB::select("select UUID() as GUID");
	$generatedId = $uuid[0]->GUID;
	$instance = new WebVisitorsModel;
	$instance->Id = $generatedId;
	$instance->IPAddress = Request::getClientIp();
	$instance->save();
	/*Visitor Count End*/
	//$noOfVisitorsQuery=DB::table('webvisitors')->get(array(DB::raw('distinct IPAddress')));
	$noOfVisitors =0;// count($noOfVisitorsQuery);

    /* Start */
    $marqueeEnabled = DB::table('webmarqueesetting')->pluck('EnableMarquee');
	$marqueeHeading = DB::table('webmarqueesetting')->pluck('MarqueeHeading');
    $query1 = DB::table('webtrainingdetails')->where('ShowInMarquee',1)->orderBy('CreatedOn','DESC')->select('Id',DB::raw("'xx' as Title"),'TrainingDescription',DB::raw('"Training" as Type'));
	$recentTrainings = DB::table('webadvertisements')->where('ShowInMarquee',1)->unionAll($query1)->get(array('Id','Title','Content as TrainingDescription',DB::raw('"Advertisement" as Type')));
//	echo "<pre>"; dd($recentTrainings);
    /* END */
	$view->with('pageBanner',$pageBanner)
		->with('noOfVisitors',$noOfVisitors)
		->with('mainMenus',$mainMenus)
		->with('subMenus',$subMenus)
		->with('subMenuExists',$subMenuExists)
		->with('footerLists',$footerLists)
        ->with('marqueeEnabled',$marqueeEnabled)
        ->with('marqueeHeading',$marqueeHeading)
        ->with('recentTrainings',$recentTrainings);
});
/*--------End of Website Menu--------*/
/*--------End of Website Menu--------*/

View::composer('arbitratorforummaster',function($view){
	if(!Session::has('ArbitratorLoggedIn')){
		return Redirect::to('/')->with('Please login first')->send();
	}
	$pageBanner=DB::table('webpagebanner')->pluck('ImageSource');
	$mainMenus=WebPostPageModel::where('ParentId',NULL)
		->where('ShowInWebsite',1)
		->orderBy('DisplayOrder','asc')
		->get(array('Id','MenuRoute','Title','DisplayOrder','ParentId','ReferenceNo'));
	$subMenus=DB::select("select * from webpostpage where ShowInWebsite = ? order by DisplayOrder asc", array(1));
	$subMenuExists=DB::select("select distinct ParentId from webpostpage");
	$footerLists = WebPostPageModel::where('ShowInFooter',1)
		->orderBy('FooterDisplayOrder','asc')
		->get(array(DB::raw('distinct Id'),'MenuRoute','Title','FooterDisplayOrder'));

	/*Visitor Count start*/
	$uuid = DB::select("select UUID() as GUID");
	$generatedId = $uuid[0]->GUID;
	$instance = new WebVisitorsModel;
	$instance->Id = $generatedId;
	$instance->IPAddress = Request::getClientIp();
	$instance->save();
	/*Visitor Count End*/
	$noOfVisitorsQuery=DB::table('webvisitors')->get(array(DB::raw('distinct IPAddress')));
	$noOfVisitors = count($noOfVisitorsQuery);

	/* Start */
	$marqueeEnabled = DB::table('webmarqueesetting')->pluck('EnableMarquee');
	$marqueeHeading = DB::table('webmarqueesetting')->pluck('MarqueeHeading');
	$query1 = DB::table('webtrainingdetails')->where('ShowInMarquee',1)->orderBy('CreatedOn','DESC')->select('Id',DB::raw("'xx' as Title"),'TrainingDescription',DB::raw('"Training" as Type'));
	$recentTrainings = DB::table('webadvertisements')->where('ShowInMarquee',1)->unionAll($query1)->get(array('Id','Title','Content as TrainingDescription',DB::raw('"Advertisement" as Type')));
//	echo "<pre>"; dd($recentTrainings);
	/* END */
	$view->with('pageBanner',$pageBanner)
		->with('noOfVisitors',$noOfVisitors)
		->with('mainMenus',$mainMenus)
		->with('subMenus',$subMenus)
		->with('subMenuExists',$subMenuExists)
		->with('footerLists',$footerLists)
		->with('marqueeEnabled',$marqueeEnabled)
		->with('marqueeHeading',$marqueeHeading)
		->with('recentTrainings',$recentTrainings);
});
/*--------Start of Website Admin Menu--------*/
View::composer('websiteadminmaster',function($view){
	$pageBanner = DB::select("select * from webpagebanner");

	$view->with('pageBanner',$pageBanner);
});
/*--------End of Website Admin Menu--------*/
View::composer('crps.applicationhistory',function($view){
    $id = Request::segment(3);
    $applicationHistory = DB::select("select GROUP_CONCAT(CONCAT('<em><strong>',W.Name,'</strong></em> by <u>',Y.FullName,'</u> on ',DATE_FORMAT(X.ActionDate,'%d-%m-%Y %H:%i'),'<br/>Remarks: ',X.Remarks) order by X.ActionDate SEPARATOR '<br/><br/>') as HistoryDetails from crpapplicationhistory X join sysuser Y on Y.Id = X.SysActionUserId join cmnlistitem W on W.Id = X.CmnApplicationRegistrationStatusId where X.CrpApplicationId = ? order by STR_TO_DATE(X.ActionDate,'%d-%m-%Y %H:%i') ASC",array($id));
    return $view->with('applicationHistory',$applicationHistory);
});