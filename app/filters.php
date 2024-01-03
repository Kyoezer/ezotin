<?php
session_start();
/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/
App::before(function($request){
	BaseModel::globalXssClean();
});
App::after(function($request, $response){
	
});
App::missing(function($exception){
	return View::make('sys.404');
});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

Route::filter('auth', function()
{
	if (Auth::guest())
	{
		if (Request::ajax())
		{
			return Response::make('Unauthorized', 401);
		}
		return Redirect::guest('/');
		//return Redirect::guest('ezhotin/home/'.Session::get('UserViewerType'));
	}
});
Route::filter('captchacheck',function(){
	$answer = Input::get('CaptchaAnswer');
	if(isset($_SESSION['captcha'])){
		$code = $_SESSION['captcha']['code'];
		if(strtoupper($answer)!= strtoupper($code)){
		    $messageVar = 'customerrormessage';
		    if(Request::segment(1) == 'registrationexistingapplicants' || Request::segment(2)=='mauthenticate'){
		        $messageVar = 'extramessage';
            }
			return Redirect::back()->with($messageVar,'Failed! Please answer the security question correctly.');
		}
	}
});
Route::filter('auth.basic', function(){
	return Auth::basic();
});
Route::filter('newstatuscheck',function($route, $request, $value=null){
	$routePrefix=Request::segment(1);
	switch ($routePrefix) {
		case 'contractor':
			$referencedId = $route->getParameter('contractorid');
			$checkStatus=ContractorModel::where('Id',$referencedId)->pluck('CmnApplicationRegistrationStatusId');
			break;
		case 'consultant':
			$referencedId = $route->getParameter('consultantid');
			$checkStatus=ConsultantModel::where('Id',$referencedId)->pluck('CmnApplicationRegistrationStatusId');
			break;
		case 'architect':
			$referencedId = $route->getParameter('architectid');
			$checkStatus=ArchitectModel::where('Id',$referencedId)->pluck('CmnApplicationRegistrationStatusId');
			break;
		case 'engineer':
			$referencedId = $route->getParameter('engineerid');
			$checkStatus=EngineerModel::where('Id',$referencedId)->pluck('CmnApplicationRegistrationStatusId');
			break;
		case 'specializedtrade':
			$referencedId = $route->getParameter('specializedtradeid');
			$checkStatus=SpecializedTradeModel::where('Id',$referencedId)->pluck('CmnApplicationRegistrationStatusId');
			break;				
		default:
			App::abort('404');
			break;
	}
	if($checkStatus == NULL){
		$checkStatus = CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW;
	}
	if($checkStatus!=CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW){
		return Redirect::to($routePrefix.'/verifyregistration')->with('customerrormessage','ERROR! The application is already VERIFIED or it has not reached the verification stage');
	}
});
Route::filter('verifiedstatuscheck',function($route, $request, $value=null){
	$routePrefix=Request::segment(1);
	switch ($routePrefix) {
		case 'contractor':
			$referencedId = $route->getParameter('contractorid');
			$checkStatus=ContractorModel::where('Id',$referencedId)->pluck('CmnApplicationRegistrationStatusId');
			break;
		case 'consultant':
			$referencedId = $route->getParameter('consultantid');
			$checkStatus=ConsultantModel::where('Id',$referencedId)->pluck('CmnApplicationRegistrationStatusId');
			break;
		case 'architect':
			$referencedId = $route->getParameter('architectid');
			$checkStatus=ArchitectModel::where('Id',$referencedId)->pluck('CmnApplicationRegistrationStatusId');
			break;
		case 'engineer':
			$referencedId = $route->getParameter('engineerid');
			$checkStatus=EngineerModel::where('Id',$referencedId)->pluck('CmnApplicationRegistrationStatusId');
			break;
		case 'specializedtrade':
			$referencedId = $route->getParameter('specializedtradeid');
			$checkStatus=SpecializedTradeModel::where('Id',$referencedId)->pluck('CmnApplicationRegistrationStatusId');
			break;				
		default:
			App::abort('404');
			break;
	}
	if($checkStatus!=CONST_CMN_APPLICATIONREGISTRATIONSTATUS_VERIFIED){
		return Redirect::to($routePrefix.'/approveregistration')->with('customerrormessage','ERROR! The application is already APPROVED FOR PAYMENT or it has not reached the approval for payment stage.');
	}
});
Route::filter('approvedforpaymentstatuscheck',function($route, $request, $value=null){
	$routePrefix=Request::segment(1);
	switch ($routePrefix) {
		case 'contractor':
			$referencedId = $route->getParameter('contractorid');
			$checkStatus=ContractorModel::where('Id',$referencedId)->pluck('CmnApplicationRegistrationStatusId');
			break;
		case 'consultant':
			$referencedId = $route->getParameter('consultantid');
			$checkStatus=ConsultantModel::where('Id',$referencedId)->pluck('CmnApplicationRegistrationStatusId');
			break;
		case 'architect':
			$referencedId = $route->getParameter('architectid');
			$checkStatus=ArchitectModel::where('Id',$referencedId)->pluck('CmnApplicationRegistrationStatusId');
			break;
		case 'engineer':
			$referencedId = $route->getParameter('engineerid');
			$checkStatus=EngineerModel::where('Id',$referencedId)->pluck('CmnApplicationRegistrationStatusId');
			break;
		case 'specializedtrade':
			$referencedId = $route->getParameter('specializedtradeid');
			$checkStatus=SpecializedTradeModel::where('Id',$referencedId)->pluck('CmnApplicationRegistrationStatusId');
			break;				
		default:
			App::abort('404');
			break;
	}
	if($checkStatus!=CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVEDFORPAYMENT){
		return Redirect::to($routePrefix.'/approvefeepayment')->with('customerrormessage','ERROR! The application is already APPROVED or it has not reached the approval stage.');
	}
});
/*------------------------------------------------check status before saving-------------------------------------------*/
Route::filter('statuscheckbeforeverifying',function($route, $request, $value=null){
	$routePrefix=Request::segment(1);
	switch ($routePrefix) {
		case 'contractor':
			$referencedId = Input::get('ContractorReference');
			$checkStatus=ContractorModel::where('Id',$referencedId)->pluck('CmnApplicationRegistrationStatusId');
			break;
		case 'consultant':
			$referencedId = Input::get('ConsultantReference');
			$checkStatus=ConsultantModel::where('Id',$referencedId)->pluck('CmnApplicationRegistrationStatusId');
			break;
		case 'architect':
			$referencedId = Input::get('ArchitectReference');
			$checkStatus=ArchitectModel::where('Id',$referencedId)->pluck('CmnApplicationRegistrationStatusId');
			break;
		case 'engineer':
			$referencedId = Input::get('EngineerReference');
			$checkStatus=EngineerModel::where('Id',$referencedId)->pluck('CmnApplicationRegistrationStatusId');
			break;
		case 'specializedtrade':
			$referencedId = Input::get('SpecializedTradeReference');
			$checkStatus=SpecializedTradeModel::where('Id',$referencedId)->pluck('CmnApplicationRegistrationStatusId');
			break;				
		default:
			App::abort('404');
			break;
	}
	if($checkStatus!=CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW){
		return Redirect::to($routePrefix.'/verifyregistration')->with('customerrormessage','ERROR! The application is already VERIFIED or it has not reached the verification stage');
	}
});
Route::filter('statuscheckbeforeapprovingforpayment',function($route, $request, $value=null){
	$routePrefix=Request::segment(1);
	switch ($routePrefix) {
		case 'contractor':
			$referencedId = Input::get('ContractorReference');
			$checkStatus=ContractorModel::where('Id',$referencedId)->pluck('CmnApplicationRegistrationStatusId');
			break;
		case 'consultant':
			$referencedId = Input::get('ConsultantReference');
			$checkStatus=ConsultantModel::where('Id',$referencedId)->pluck('CmnApplicationRegistrationStatusId');
			break;
		case 'architect':
			$referencedId = Input::get('ArchitectReference');
			$checkStatus=ArchitectModel::where('Id',$referencedId)->pluck('CmnApplicationRegistrationStatusId');
			break;
		case 'engineer':
			$referencedId = Input::get('EngineerReference');
			$checkStatus=EngineerModel::where('Id',$referencedId)->pluck('CmnApplicationRegistrationStatusId');
			break;
		case 'specializedtrade':
			$referencedId = Input::get('SpecializedTradeReference');
			$checkStatus=SpecializedTradeModel::where('Id',$referencedId)->pluck('CmnApplicationRegistrationStatusId');
			break;				
		default:
			App::abort('404');
			break;
	}
	if($checkStatus!=CONST_CMN_APPLICATIONREGISTRATIONSTATUS_VERIFIED){
		return Redirect::to($routePrefix.'/verifyregistration')->with('customerrormessage','ERROR! The application is already VERIFIED or it has not reached the verification stage');
	}
});
Route::filter('statuscheckbeforeapproving',function($route, $request, $value=null){
	$routePrefix=Request::segment(1);
	switch ($routePrefix) {
		case 'contractor':
			$referencedId = Input::get('ContractorReference');
			$checkStatus=ContractorModel::where('Id',$referencedId)->pluck('CmnApplicationRegistrationStatusId');
			break;
		case 'consultant':
			$referencedId = Input::get('ConsultantReference');
			$checkStatus=ConsultantModel::where('Id',$referencedId)->pluck('CmnApplicationRegistrationStatusId');
			break;
		case 'architect':
			$referencedId = Input::get('ArchitectReference');
			$checkStatus=ArchitectModel::where('Id',$referencedId)->pluck('CmnApplicationRegistrationStatusId');
			break;
		case 'engineer':
			$referencedId = Input::get('EngineerReference');
			$checkStatus=EngineerModel::where('Id',$referencedId)->pluck('CmnApplicationRegistrationStatusId');
			break;
		case 'specializedtrade':
			$referencedId = Input::get('SpecializedTradeReference');
			$checkStatus=SpecializedTradeModel::where('Id',$referencedId)->pluck('CmnApplicationRegistrationStatusId');
			break;				
		default:
			App::abort('404');
			break;
	}
	if($checkStatus!=CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVEDFORPAYMENT){
		return Redirect::to($routePrefix.'/verifyregistration')->with('customerrormessage','ERROR! The application is already APPROVED or it has not reached the approval stage');
	}
});

Route::filter('statuscheckbeforeapprovingdg',function($route, $request, $value=null){
	$routeSegment = Request::segment(1);
	switch($routeSegment){
		case 'contractor':
			$id = Input::get('ContractorReference');
			$currentStatus = DB::table('crpcontractor')->where("Id",$id)->pluck('CmnApplicationRegistrationStatusId');
			break;
		case 'consultant':
			$id = Input::get('ConsultantReference');
			$currentStatus = DB::table('crpconsultant')->where("Id",$id)->pluck('CmnApplicationRegistrationStatusId');
			break;
		case 'architect':
			$id = Input::get('ArchitectReference');
			$currentStatus = DB::table('crparchitect')->where("Id",$id)->pluck('CmnApplicationRegistrationStatusId');
			break;
		case 'engineer':
			$id = Input::get('EngineerReference');
			$currentStatus = DB::table('crpengineer')->where("Id",$id)->pluck('CmnApplicationRegistrationStatusId');
			break;
		case 'specializedtrade':
			$id = Input::get('SpecializedTradeReference');
			$currentStatus = DB::table('crpspecializedtrade')->where("Id",$id)->pluck('CmnApplicationRegistrationStatusId');
			break;
		default:
			App::abort('404');
			break;
	}
	if($currentStatus!=CONST_CMN_APPLICATIONREGISTRATIONSTATUS_FORWARDEDTODG){
		return Redirect::to($routeSegment.'/verifyregistration')->with('customerrormessage','ERROR! The application is already APPROVED BY DG or it has not reached the approval stage');
	}
});
/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest', function()
{
	if (Auth::check()) return Redirect::to('/');
});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function()
{
	if (Session::token() !== Input::get('_token'))
	{
		throw new Illuminate\Session\TokenMismatchException;
	}
});
