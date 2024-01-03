<?php

/*
|--------------------------------------------------------------------------
| Register The Laravel Class Loader
|--------------------------------------------------------------------------
|
| In addition to using Composer, you may use the Laravel class loader to
| load your controllers and models. This is useful for keeping all of
| your classes in the "global" namespace without Composer updating.
|
*/

ClassLoader::addDirectories(array(

	app_path().'/commands',
	app_path().'/controllers',
	app_path().'/controllers/etool',
	app_path().'/controllers/crps',
	app_path().'/controllers/cinet',
	app_path().'/controllers/system',
	app_path().'/controllers/contractor',
	app_path().'/controllers/report',
	app_path().'/controllers/website',
	app_path().'/models',
	app_path().'/models/etool',
	app_path().'/models/crps',
	app_path().'/models/cinet',
	app_path().'/models/sys',
	app_path().'/models/contractor',
	app_path().'/models/website',
	app_path().'/database/seeds',

));

/*
|--------------------------------------------------------------------------
| Application Error Logger
|--------------------------------------------------------------------------
|
| Here we will configure the error logger setup for the application which
| is built on top of the wonderful Monolog library. By default we will
| build a basic log file setup which creates a single file for logs.
|
*/

Log::useFiles(storage_path().'/logs/laravel.log');

/*
|--------------------------------------------------------------------------
| Application Error Handler
|--------------------------------------------------------------------------
|
| Here you may handle any errors that occur in your application, including
| logging them or displaying custom views for specific errors. You may
| even register several error handlers to handle different types of
| exceptions. If nothing is returned, the default error view is
| shown, which includes a detailed stack trace during debug.
|
*/
App::error(function(Exception $exception, $code)
{
	Log::error($exception);
	//return Response::make(View::make('errors.500'), 500);
});
/*
|--------------------------------------------------------------------------
| Maintenance Mode Handler
|--------------------------------------------------------------------------
|
| The "down" Artisan command gives you the ability to put an application
| into maintenance mode. Here, you will define what is displayed back
| to the user if maintenance mode is in effect for the application.
|
*/

App::down(function()
{
	return Response::make("Be right back!", 503);
});

/*
|--------------------------------------------------------------------------
| Require The Filters File
|--------------------------------------------------------------------------
|
| Next we will load the filters file for the application. This gives us
| a nice separate location to store our route and application filter
| definitions instead of putting them all in the main routes file.
|
*/

require app_path().'/filters.php';
require app_path().'/helpers.php';//all my common helper functions are defined in this file
require app_path().'/composers.php';//all my View::composer are defined in this file

$host = URL::to('/');

define("CONST_SITELINK","$host/");
define("CONST_APACHESITELINK", "$host");

define("CONST_CMN_REFERENCE_DESIGNATION","599fbfdc-a250-11e4-b4d2-080027dcfac6");
define("CONST_CMN_REFERENCE_QUALIFICATION","ff4e55ee-a254-11e4-b4d2-080027dcfac6");
define("CONST_CMN_REFERENCE_WORKCOMPLETIONSTATUS", "f988f644-a255-11e4-b4d2-080027dcfac6");
define("CONST_CMN_REFERENCE_TRADE", "bf4b32e8-a256-11e4-b4d2-080027dcfac6");
define("CONST_CMN_REFERENCE_MINISTRY", "ffc90e78-a256-11e4-b4d2-080027dcfac6");
define("CONST_CMN_REFERENCE_DIVISION", "c86bcaa2-21bb-11e6-936b-9c2a70cc8e06");
define("CONST_CMN_REFERENCE_CONTRACTORPROJECTCATEGORY", "91cd04e4-a2b6-11e4-b4d2-080027dcfac6");
define("CONST_CMN_REFERENCE_EQUIPMENT", "b6fc13e0-a2bc-11e4-b4d2-080027dcfac6");
define("CONST_CMN_REFERENCE_FINANCIALINTITUTION", "42069f51-a2be-11e4-b4d2-080027dcfac6");
define("CONST_CMN_REFERENCE_SALUTATION", "f237fdb8-a5ef-11e4-8ab5-080027dcfac6");
define("CONST_CMN_REFERENCE_REGISTRATIONSTATUS", "e70f06a6-adbc-11e4-99d7-080027dcfac6");
define("CONST_CMN_REFERENCE_WORKEXECUTIONSTATUS", "f988f644-a255-11e4-b4d2-080027dcfac6");
define("CONST_CMN_REFERENCE_SERVICESECTORTYPE", "8d6e1df8-bea7-11e4-9757-080027dcfac6");
define("CONST_CMN_REFERENCE_OWNERSHIPTYPE", "08dada52-c651-11e4-b574-080027dcfac6");
define("CONST_CMN_REFERENCE_SERVICETYPE", "08dada52-c651-11e4-b574-080027dcfee6");
define("CONST_CMN_REFERENCE_TRAININGTYPE", "558bdd4d-03e6-11e7-b2ae-9c2a70cc8e06");
define("CONST_CMN_REFERENCE_TRAININGMODULE", "6e95b9d3-03e6-11e7-b2ae-9c2a70cc8e06");


/*COUNTRY*/
define("CONST_COUNTRY_BHUTAN","8f897032-c6e6-11e4-b574-080027dcfac6");
/*END*/
/*------------------------------Start of Service Sector (Govt/Pvt)---------------------------*/
define("CONST_CMN_SERVICESECTOR_GOVT", "6e1cd096-bea8-11e4-9757-080027dcfac6");
define("CONST_CMN_SERVICESECTOR_PVT", "dacae294-bea7-11e4-9757-080027dcfac6");

/*------------------------------Start of Application Status for all types of registration---------------------------*/
define("CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW","262a3f11-adbd-11e4-99d7-080027dcfac6");
define("CONST_CMN_APPLICATIONREGISTRATIONSTATUS_VERIFIED","36f9627a-adbd-11e4-99d7-080027dcfac6");
define("CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED","463c2d4c-adbd-11e4-99d7-080027dcfac6");
define("CONST_CMN_APPLICATIONREGISTRATIONSTATUS_REJECTED","de662a61-b049-11e4-89f3-080027dcfac6");
define("CONST_CMN_APPLICATIONREGISTRATIONSTATUS_DEREGISTERED","b1fa6607-b1dd-11e4-89f3-080027dcfac6");
define("CONST_CMN_APPLICATIONREGISTRATIONSTATUS_BLACKLISTED","f89a6f55-b1dd-11e4-89f3-080027dcfac6");
define("CONST_CMN_APPLICATIONREGISTRATIONSTATUS_SUSPENDED","f89a6f55-b1dd-xvid-89f3-080027dcfac6");
define("CONST_CMN_APPLICATIONREGISTRATIONSTATUS_REVOKED","f89a6f55-b1dd-aac4-89f3-080027dcfac6");
define("CONST_CMN_APPLICATIONREGISTRATIONSTATUS_DEBARRED","nomedia5-b1dd-xvid-89f3-080027dcfac6");
define("CONST_CMN_APPLICATIONREGISTRATIONSTATUS_SURRENDERED","nomedia5-b1dd-xvid-divx-080027dcfac6");
define("CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVEDFORPAYMENT","6195664d-c3c5-11e4-af9f-080027dcfac6");
define("CONST_CMN_APPLICATIONREGISTRATIONSTATUS_FORWARDEDTODG","nomedia5-b1dd-xvid-89f3-080027dcmpeg");
/*------------------------------Start of Work Execution Status for all types of work---------------------------*/
define("CONST_CMN_WORKEXECUTIONSTATUS_UNDERPROCESS","97154138-b5a8-11e4-81ac-080027dcfac6");
define("CONST_CMN_WORKEXECUTIONSTATUS_AWARDED","1ec69344-a256-11e4-b4d2-080027dcfac6");
define("CONST_CMN_WORKEXECUTIONSTATUS_COMPLETED","a13c5d39-b5a8-11e4-81ac-080027dcfac6");
define("CONST_CMN_WORKEXECUTIONSTATUS_TERMINATED","9cc4dab5-b5a8-11e4-81ac-080027dcfac6");
define("CONST_CMN_WORKEXECUTIONSTATUS_CANCELLED","a98f434b-d8ee-11e4-afa1-9c2a70cc8e06");

/*---------------------------CATEGORY CONSTANTS---------------------------------------*/
define("CONST_CATEGORY_W1","6cd737d4-a2b7-11e4-b4d2-080027dcfac6");
define("CONST_CATEGORY_W2","8176bd2d-a2b7-11e4-b4d2-080027dcfac6");
define("CONST_CATEGORY_W3","8afc0568-a2b7-11e4-b4d2-080027dcfac6");
define("CONST_CATEGORY_W4","9090a82a-a2b7-11e4-b4d2-080027dcfac6");
/*---------------------------Role COnstants-------------------------------------------*/
define("CONST_ROLE_ADMINISTRATOR","25852c50-c31a-11e4-ac25-080027dcfac6");
define("CONST_ROLE_CONTRACTOR","2e32b57d-c628-11e4-b574-080027dcfac6");
define("CONST_ROLE_CONSULTANT","3c828e19-c628-11e4-b574-080027dcfac6");
define("CONST_ROLE_SPECIALIZEDTRADE","8ac46b14-c890-11e4-b249-080027dcfac6");
define("CONST_ROLE_ENGINEER","ae115172-c890-11e4-b249-080027dcfac6");
define("CONST_ROLE_ARCHITECT","f1aba5dd-c88f-11e4-b249-080027dcfac6");
define("CONST_ROLE_PROCURINGAGENCYETOOL","f0ffe82a-c32b-11e4-ac25-080027dcfac6");
define("CONST_ROLE_PROCURINGAGENCYCINET","ba645079-f565-11e4-8c24-080027dcfac6");
define("CONST_ROLE_PROCURINGAGENCYCBUILDER","7c7b1cd1-3eb3-11e7-8ce7-c81f66edb959");

/*---------------------------Role Application Type-------------------------------------------*/
define("CONST_SERVICETYPE_NEW","55a922e1-cbbf-11e4-83fb-080027dcfac6");
define("CONST_SERVICETYPE_RENEWAL","45bc628b-cbbe-11e4-83fb-080027dcfac6");
define("CONST_SERVICETYPE_GENERALINFORMATION","60f2eb68-cbd1-11e4-83fb-080027dcfac6");
define("CONST_SERVICETYPE_CHANGELOCATION","61e9b186-cbbe-11e4-83fb-080027dcfac6");
define("CONST_SERVICETYPE_CHANGEOWNER","140df628-cbcd-11e4-83fb-080027dcfac6");
define("CONST_SERVICETYPE_CHANGEOFCATEGORYCLASSIFICATION","79599a41-cbbe-11e4-83fb-080027dcfac6");
define("CONST_SERVICETYPE_UPDATEHUMANRESOURCE","923a39a3-cbbe-11e4-83fb-080027dcfac6");
define("CONST_SERVICETYPE_UPDATEEQUIPMENT","9c3e3bf1-cbbe-11e4-83fb-080027dcfac6");
define("CONST_SERVICETYPE_CANCELREGISTRATION","acf4b324-cbbe-11e4-83fb-080027dcfac6");
define("CONST_SERVICETYPE_LATEFEE","21b6caa9-ff97-11e4-9b95-080027dcfac6");
define("CONST_SERVICETYPE_CHANGEOFFIRMNAME","d9ee5798-0f5c-11e5-9436-080027dcfac6");
define("CONST_SERVICETYPE_INCORPORATION","abd5a5b0-4b5a-11e6-94d0-5cf9dd5fc4f1");
/*Bid Evaluation Parameters Constants*/
define("CONST_ETLPARAMETER_SWE1","2607b407-5ae5-11e5-b473-5cf9dd5fc4f1");
define("CONST_ETLPARAMETER_SWE2","70133839-5ae5-11e5-b473-5cf9dd5fc4f1");
define("CONST_ETLPARAMETER_APS","7e313057-5aed-11e5-b473-5cf9dd5fc4f1");
define("CONST_ETLPARAMETER_BC","edbea5d7-5aed-11e5-b473-5cf9dd5fc4f1");
define("CONST_ETLPARAMETER_CL","0172d8ce-5aee-11e5-b473-5cf9dd5fc4f1");
/*---------------------------SYSTEM Roles-------------------------------------------*/
define("CONST_TRAININGTYPE_REFRESHER","db355bba-03e6-11e7-b2ae-9c2a70cc8e06");
define("CONST_REFRESHERCOURSE","a5bd57ae-8247-11e7-9a21-020054746872");
