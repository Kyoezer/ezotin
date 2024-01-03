<?php

class JournalMenuController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$pageBanner=DB::table('webpagebanner')->pluck('ImageSource');
		$marqueeEnabled = DB::table('webmarqueesetting')->pluck('EnableMarquee');
		$marqueeHeading = DB::table('webmarqueesetting')->pluck('MarqueeHeading');
		$query1 = DB::table('webtrainingdetails')->where('ShowInMarquee',1)->orderBy('CreatedOn','DESC')->select('Id',DB::raw("'xx' as Title"),'TrainingDescription',DB::raw('"Training" as Type'));
		$recentTrainings = DB::table('webadvertisements')->where('ShowInMarquee',1)->unionAll($query1)->get(array('Id','Title','Content as TrainingDescription',DB::raw('"Advertisement" as Type')));
	//	echo "<pre>"; dd($recentTrainings);
		/* END */
		$data=DB::select("SELECT a.Id AS menuId,a.`Title` AS menuTitle,a.`Has_Submenu`,b.`Title` 
		AS submenu,a.`Route` AS MenuRoute,b.`Route` AS submenuroute 
		FROM webjournalmenu a
		LEFT JOIN webjournalsubmenu b ON a.`Id`=b.`Menu_Id`
		ORDER BY a.`Id`");
        $openaccess = DB::table('webregistrationpagecontent')->where('Type',17)->pluck('Content');
		$information = DB::table('webregistrationpagecontent')->where('Type',18)->pluck('Content');
		$content = DB::select("SELECT DATE_FORMAT(a.`CreatedOn`,'%d-%m-%Y') publishedYear,a.* FROM `webjournalmanuscript` a
							WHERE DATE_FORMAT(a.`CreatedOn`,'%Y') = DATE_FORMAT(CURRENT_TIMESTAMP,'%Y')");
		$contentmenu = DB::table('webregistrationpagecontent')->where('Type',14)->pluck('Content');


		// $content =DB::table('webjournalmanuscriptapplication')
		// ->leftJoin('webjournaltasklist', 'webjournalmanuscriptapplication.Application_No', '=', 'webjournaltasklist.Application_No')
		// ->leftJoin('webjournalmanuscript', 'webjournalmanuscriptapplication.Id', '=', 'webjournalmanuscript.Name')
		// ->where('Task_Status_Id','8') 
		// ->where('Task_Status_Id','8') 
		//  ->get(); 
		return View::make('website.journalpage')
		->with('pageBanner',$pageBanner)
		->with('marqueeEnabled',$marqueeEnabled)
		->with('marqueeHeading',$marqueeHeading)
		->with('query1',$query1)
		->with('recentTrainings',$recentTrainings)
		->with('contentmenu',$contentmenu)
		->with('data',$data)
		->with('openaccess',$openaccess)
		->with('information',$information)
		->with('content',$content);
		
	}
	public function landingpage()
	{
		$journallandingpage = DB::table("webjournalmanuscript")->select('Application_No')->get();
		return View::make('website.journallanding')
		->with('journallandingpage',$journallandingpage);
	}
	public function journalapplicationno()
	{
		$Application_No=Input::get('Application_No');		
		if($Application_No){
		$data = DB::table('webjournalmanuscript')->where('Application_No',$Application_No)->get();
		
		return View::make('website.landingeditpage')
		->with('data',$data);
		}
	}
	public function editlandingremarks()
	{
		$Id = Input::get('Id');
		// $landingRemarks = Input::get('Landing_Remarks');
        $inputs = Input::get('Landing_Remarks');
        DB::beginTransaction();
        try{
            if(empty($Id)){
                $append = "saved";
                $inputs['Id'] = $this->UUID();
                JournalManuscriptApprovedModel::create($inputs);
            }else{
                $append = "updated";
                $data=array('Landing_Remarks' => $inputs);
		        DB::table('webjournalmanuscript')->where('Id',$Id)->update($data);
            }
        }catch(Exception $e){
            DB::rollback();
            return Redirect::to('web/landingpage/')->with('customerrormessage',$e->getMessage());
        }
        DB::commit();
        return Redirect::to('web/landingpage/')->with('savedsuccessmessage',"Page has been $append");

    }


	// 	$Id=Input::get('Id');
	// 	$landingRemarks = Input::get('Landing_Remarks');
	// 	$data=array('Landing_Remarks' => $landingRemarks);
	// 	DB::table('webjournalmanuscript')->where('Id',$Id)->update($data);

	// 	return Redirect::to('web/landingpage');
	// }

	public function journallandingpage($Id)
	{
		$pageBanner=DB::table('webpagebanner')->pluck('ImageSource');
		$marqueeEnabled = DB::table('webmarqueesetting')->pluck('EnableMarquee');
		$marqueeHeading = DB::table('webmarqueesetting')->pluck('MarqueeHeading');
		$query1 = DB::table('webtrainingdetails')->where('ShowInMarquee',1)->orderBy('CreatedOn','DESC')->select('Id',DB::raw("'xx' as Title"),'TrainingDescription',DB::raw('"Training" as Type'));
		$recentTrainings = DB::table('webadvertisements')->where('ShowInMarquee',1)->unionAll($query1)->get(array('Id','Title','Content as TrainingDescription',DB::raw('"Advertisement" as Type')));
		$landingPage = DB::table('webjournalmanuscript')->where('Id',$Id)->get();
		$authorinformation = DB::table('webregistrationpagecontent')->where('Type',20)->pluck('Content');
		//	echo "<pre>"; dd($recentTrainings);
		/* END */
		$data=DB::select("SELECT a.Id AS menuId,a.`Title` AS menuTitle,a.`Has_Submenu`,b.`Title` 
		AS submenu,a.`Route` AS MenuRoute,b.`Route` AS submenuroute 
		FROM webjournalmenu a
		LEFT JOIN webjournalsubmenu b ON a.`Id`=b.`Menu_Id`
		ORDER BY a.`Id`");
        $openaccess = DB::table('webregistrationpagecontent')->where('Type',17)->pluck('Content');
		$information = DB::table('webregistrationpagecontent')->where('Type',18)->pluck('Content');
		$content = DB::select("SELECT * FROM webjournalmanuscript WHERE Id=".$Id);
		$contentmenu = DB::table('webregistrationpagecontent')->where('Type',14)->pluck('Content');

		return View::make('website.journallandingpage')
		->with('pageBanner',$pageBanner)
		->with('marqueeEnabled',$marqueeEnabled)
		->with('marqueeHeading',$marqueeHeading)
		->with('query1',$query1)
		->with('recentTrainings',$recentTrainings)
		->with('contentmenu',$contentmenu)
		->with('data',$data)
		->with('openaccess',$openaccess)
		->with('information',$information)
		->with('landingPage',$landingPage)
		->with('authorinformation',$authorinformation)
		->with('content',$content);
	}



	public function aboutthejournal()
	{
	 	//  $lang = Input::get('lang');
        //   if (!empty($lang)) {
        //   App::setLocale($lang);
        //  }
        //   $message = Lang::get('messages.greeting');
        // return $message;
		$pageBanner=DB::table('webpagebanner')->pluck('ImageSource');
		$marqueeEnabled = DB::table('webmarqueesetting')->pluck('EnableMarquee');
		$marqueeHeading = DB::table('webmarqueesetting')->pluck('MarqueeHeading');
		$query1 = DB::table('webtrainingdetails')->where('ShowInMarquee',1)->orderBy('CreatedOn','DESC')->select('Id',DB::raw("'xx' as Title"),'TrainingDescription',DB::raw('"Training" as Type'));
		$recentTrainings = DB::table('webadvertisements')->where('ShowInMarquee',1)->unionAll($query1)->get(array('Id','Title','Content as TrainingDescription',DB::raw('"Advertisement" as Type')));
	//	echo "<pre>"; dd($recentTrainings);
		/* END */
		$data=DB::select("SELECT a.Id AS menuId,a.`Title` AS menuTitle,a.`Has_Submenu`,b.`Title` 
		AS submenu,a.`Route` AS MenuRoute,b.`Route` AS submenuroute 
		FROM webjournalmenu a
		LEFT JOIN webjournalsubmenu b ON a.`Id`=b.`Menu_Id`
		ORDER BY a.`Id`");
		$openaccess = DB::table('webregistrationpagecontent')->where('Type',17)->pluck('Content');
		$information = DB::table('webregistrationpagecontent')->where('Type',18)->pluck('Content');
		$content = DB::table('webregistrationpagecontent')->where('Type',5)->pluck('Content');
		return View::make('website.journalabout')
		// ->with('message',$message)
		->with('pageBanner',$pageBanner)
		->with('marqueeEnabled',$marqueeEnabled)
		->with('marqueeHeading',$marqueeHeading)
		->with('query1',$query1)
		->with('recentTrainings',$recentTrainings)
		->with('data',$data)
		->with('openaccess',$openaccess)
		->with('information',$information)
		->with('content',$content);
	}
	public function journalaimsandscope()
	{
		$pageBanner=DB::table('webpagebanner')->pluck('ImageSource');
		$marqueeEnabled = DB::table('webmarqueesetting')->pluck('EnableMarquee');
		$marqueeHeading = DB::table('webmarqueesetting')->pluck('MarqueeHeading');
		$query1 = DB::table('webtrainingdetails')->where('ShowInMarquee',1)->orderBy('CreatedOn','DESC')->select('Id',DB::raw("'xx' as Title"),'TrainingDescription',DB::raw('"Training" as Type'));
		$recentTrainings = DB::table('webadvertisements')->where('ShowInMarquee',1)->unionAll($query1)->get(array('Id','Title','Content as TrainingDescription',DB::raw('"Advertisement" as Type')));
	//	echo "<pre>"; dd($recentTrainings);
		/* END */
		$data=DB::select("SELECT a.Id AS menuId,a.`Title` AS menuTitle,a.`Has_Submenu`,b.`Title` 
		AS submenu,a.`Route` AS MenuRoute,b.`Route` AS submenuroute 
		FROM webjournalmenu a
		LEFT JOIN webjournalsubmenu b ON a.`Id`=b.`Menu_Id`
		ORDER BY a.`Id`");
		$openaccess = DB::table('webregistrationpagecontent')->where('Type',17)->pluck('Content');
		$information = DB::table('webregistrationpagecontent')->where('Type',18)->pluck('Content');
		$content = DB::table('webregistrationpagecontent')->where('Type',6)->pluck('Content');
		return View::make('website.journalaims')
		->with('pageBanner',$pageBanner)
		->with('marqueeEnabled',$marqueeEnabled)
		->with('marqueeHeading',$marqueeHeading)
		->with('query1',$query1)
		->with('recentTrainings',$recentTrainings)
		->with('data',$data)
		->with('openaccess',$openaccess)
		->with('information',$information)
		->with('content',$content);
	}
	public function peerreviewjournal()
	{
		$pageBanner=DB::table('webpagebanner')->pluck('ImageSource');
		$marqueeEnabled = DB::table('webmarqueesetting')->pluck('EnableMarquee');
		$marqueeHeading = DB::table('webmarqueesetting')->pluck('MarqueeHeading');
		$query1 = DB::table('webtrainingdetails')->where('ShowInMarquee',1)->orderBy('CreatedOn','DESC')->select('Id',DB::raw("'xx' as Title"),'TrainingDescription',DB::raw('"Training" as Type'));
		$recentTrainings = DB::table('webadvertisements')->where('ShowInMarquee',1)->unionAll($query1)->get(array('Id','Title','Content as TrainingDescription',DB::raw('"Advertisement" as Type')));
	//	echo "<pre>"; dd($recentTrainings);
		/* END */
		$data=DB::select("SELECT a.Id AS menuId,a.`Title` AS menuTitle,a.`Has_Submenu`,b.`Title` 
		AS submenu,a.`Route` AS MenuRoute,b.`Route` AS submenuroute 
		FROM webjournalmenu a
		LEFT JOIN webjournalsubmenu b ON a.`Id`=b.`Menu_Id`
		ORDER BY a.`Id`");
		$openaccess = DB::table('webregistrationpagecontent')->where('Type',17)->pluck('Content');
		$information = DB::table('webregistrationpagecontent')->where('Type',18)->pluck('Content');
		$content = DB::table('webregistrationpagecontent')->where('Type',7)->pluck('Content');
		return View::make('website.peerreviewjournal')
		->with('pageBanner',$pageBanner)
		->with('marqueeEnabled',$marqueeEnabled)
		->with('marqueeHeading',$marqueeHeading)
		->with('query1',$query1)
		->with('recentTrainings',$recentTrainings)
		->with('data',$data)
		->with('openaccess',$openaccess)
		->with('information',$information)
		->with('content',$content);
	}
	public function conflictofinterestjournal()
	{
		$pageBanner=DB::table('webpagebanner')->pluck('ImageSource');
		$marqueeEnabled = DB::table('webmarqueesetting')->pluck('EnableMarquee');
		$marqueeHeading = DB::table('webmarqueesetting')->pluck('MarqueeHeading');
		$query1 = DB::table('webtrainingdetails')->where('ShowInMarquee',1)->orderBy('CreatedOn','DESC')->select('Id',DB::raw("'xx' as Title"),'TrainingDescription',DB::raw('"Training" as Type'));
		$recentTrainings = DB::table('webadvertisements')->where('ShowInMarquee',1)->unionAll($query1)->get(array('Id','Title','Content as TrainingDescription',DB::raw('"Advertisement" as Type')));
	//	echo "<pre>"; dd($recentTrainings);
		/* END */
		$data=DB::select("SELECT a.Id AS menuId,a.`Title` AS menuTitle,a.`Has_Submenu`,b.`Title` 
		AS submenu,a.`Route` AS MenuRoute,b.`Route` AS submenuroute 
		FROM webjournalmenu a
		LEFT JOIN webjournalsubmenu b ON a.`Id`=b.`Menu_Id`
		ORDER BY a.`Id`");
		$openaccess = DB::table('webregistrationpagecontent')->where('Type',17)->pluck('Content');
		$information = DB::table('webregistrationpagecontent')->where('Type',18)->pluck('Content');
	    $content = DB::table('webregistrationpagecontent')->where('Type',8)->pluck('Content');
		return View::make('website.journalconflictofinterest')
		->with('pageBanner',$pageBanner)
		->with('marqueeEnabled',$marqueeEnabled)
		->with('marqueeHeading',$marqueeHeading)
		->with('query1',$query1)
		->with('recentTrainings',$recentTrainings)
		->with('data',$data)
		->with('openaccess',$openaccess)
		->with('information',$information)
		->with('content',$content);
	}
	public function contactjournal()
	{
		$pageBanner=DB::table('webpagebanner')->pluck('ImageSource');
		$marqueeEnabled = DB::table('webmarqueesetting')->pluck('EnableMarquee');
		$marqueeHeading = DB::table('webmarqueesetting')->pluck('MarqueeHeading');
		$query1 = DB::table('webtrainingdetails')->where('ShowInMarquee',1)->orderBy('CreatedOn','DESC')->select('Id',DB::raw("'xx' as Title"),'TrainingDescription',DB::raw('"Training" as Type'));
		$recentTrainings = DB::table('webadvertisements')->where('ShowInMarquee',1)->unionAll($query1)->get(array('Id','Title','Content as TrainingDescription',DB::raw('"Advertisement" as Type')));
	//	echo "<pre>"; dd($recentTrainings);
		/* END */
		$data=DB::select("SELECT a.Id AS menuId,a.`Title` AS menuTitle,a.`Has_Submenu`,b.`Title` 
		AS submenu,a.`Route` AS MenuRoute,b.`Route` AS submenuroute 
		FROM webjournalmenu a
		LEFT JOIN webjournalsubmenu b ON a.`Id`=b.`Menu_Id`
		ORDER BY a.`Id`");
		$openaccess = DB::table('webregistrationpagecontent')->where('Type',17)->pluck('Content');
		$information = DB::table('webregistrationpagecontent')->where('Type',18)->pluck('Content');
		$content = DB::table('webregistrationpagecontent')->where('Type',9)->pluck('Content');
		return View::make('website.journalcontact')
		->with('pageBanner',$pageBanner)
		->with('marqueeEnabled',$marqueeEnabled)
		->with('marqueeHeading',$marqueeHeading)
		->with('query1',$query1)
		->with('recentTrainings',$recentTrainings)
		->with('data',$data)
		->with('openaccess',$openaccess)
		->with('information',$information)
		->with('content',$content);
	}
	public function editorialpoliciesjournal()
	{
		$pageBanner=DB::table('webpagebanner')->pluck('ImageSource');
		$marqueeEnabled = DB::table('webmarqueesetting')->pluck('EnableMarquee');
		$marqueeHeading = DB::table('webmarqueesetting')->pluck('MarqueeHeading');
		$query1 = DB::table('webtrainingdetails')->where('ShowInMarquee',1)->orderBy('CreatedOn','DESC')->select('Id',DB::raw("'xx' as Title"),'TrainingDescription',DB::raw('"Training" as Type'));
		$recentTrainings = DB::table('webadvertisements')->where('ShowInMarquee',1)->unionAll($query1)->get(array('Id','Title','Content as TrainingDescription',DB::raw('"Advertisement" as Type')));
	//	echo "<pre>"; dd($recentTrainings);
		/* END */
		$data=DB::select("SELECT a.Id AS menuId,a.`Title` AS menuTitle,a.`Has_Submenu`,b.`Title` 
		AS submenu,a.`Route` AS MenuRoute,b.`Route` AS submenuroute 
		FROM webjournalmenu a
		LEFT JOIN webjournalsubmenu b ON a.`Id`=b.`Menu_Id`
		ORDER BY a.`Id`");
		$openaccess = DB::table('webregistrationpagecontent')->where('Type',17)->pluck('Content');
		$information = DB::table('webregistrationpagecontent')->where('Type',18)->pluck('Content');
		$content = DB::table('webregistrationpagecontent')->where('Type',10)->pluck('Content');
		return View::make('website.journaleditorialpolicies')
		->with('pageBanner',$pageBanner)
		->with('marqueeEnabled',$marqueeEnabled)
		->with('marqueeHeading',$marqueeHeading)
		->with('query1',$query1)
		->with('recentTrainings',$recentTrainings)
		->with('data',$data)
		->with('openaccess',$openaccess)
		->with('information',$information)
		->with('content',$content);
	}
	public function editorialteamjournal()
	{
		$pageBanner=DB::table('webpagebanner')->pluck('ImageSource');
		$marqueeEnabled = DB::table('webmarqueesetting')->pluck('EnableMarquee');
		$marqueeHeading = DB::table('webmarqueesetting')->pluck('MarqueeHeading');
		$query1 = DB::table('webtrainingdetails')->where('ShowInMarquee',1)->orderBy('CreatedOn','DESC')->select('Id',DB::raw("'xx' as Title"),'TrainingDescription',DB::raw('"Training" as Type'));
		$recentTrainings = DB::table('webadvertisements')->where('ShowInMarquee',1)->unionAll($query1)->get(array('Id','Title','Content as TrainingDescription',DB::raw('"Advertisement" as Type')));
	//	echo "<pre>"; dd($recentTrainings);
		/* END */
		$data=DB::select("SELECT a.Id AS menuId,a.`Title` AS menuTitle,a.`Has_Submenu`,b.`Title` 
		AS submenu,a.`Route` AS MenuRoute,b.`Route` AS submenuroute 
		FROM webjournalmenu a
		LEFT JOIN webjournalsubmenu b ON a.`Id`=b.`Menu_Id`
		ORDER BY a.`Id`");
		$openaccess = DB::table('webregistrationpagecontent')->where('Type',17)->pluck('Content');
		$information = DB::table('webregistrationpagecontent')->where('Type',18)->pluck('Content');
		$content = DB::table('webregistrationpagecontent')->where('Type',11)->pluck('Content');
		return View::make('website.journaleditorialteam')
		->with('pageBanner',$pageBanner)
		->with('marqueeEnabled',$marqueeEnabled)
		->with('marqueeHeading',$marqueeHeading)
		->with('query1',$query1)
		->with('recentTrainings',$recentTrainings)
		->with('data',$data)
		->with('openaccess',$openaccess)
		->with('information',$information)
		->with('content',$content);
	}
	public function submissionchecklist()
	{
		$pageBanner=DB::table('webpagebanner')->pluck('ImageSource');
		$marqueeEnabled = DB::table('webmarqueesetting')->pluck('EnableMarquee');
		$marqueeHeading = DB::table('webmarqueesetting')->pluck('MarqueeHeading');
		$query1 = DB::table('webtrainingdetails')->where('ShowInMarquee',1)->orderBy('CreatedOn','DESC')->select('Id',DB::raw("'xx' as Title"),'TrainingDescription',DB::raw('"Training" as Type'));
		$recentTrainings = DB::table('webadvertisements')->where('ShowInMarquee',1)->unionAll($query1)->get(array('Id','Title','Content as TrainingDescription',DB::raw('"Advertisement" as Type')));
	//	echo "<pre>"; dd($recentTrainings);
		/* END */
		$data=DB::select("SELECT a.Id AS menuId,a.`Title` AS menuTitle,a.`Has_Submenu`,b.`Title` 
		AS submenu,a.`Route` AS MenuRoute,b.`Route` AS submenuroute 
		FROM webjournalmenu a
		LEFT JOIN webjournalsubmenu b ON a.`Id`=b.`Menu_Id`
		ORDER BY a.`Id`");
		$openaccess = DB::table('webregistrationpagecontent')->where('Type',17)->pluck('Content');
		$information = DB::table('webregistrationpagecontent')->where('Type',18)->pluck('Content');
		$content = DB::table('webregistrationpagecontent')->where('Type',12)->pluck('Content');
		return View::make('website.journalsubmissionchecklist')
		->with('pageBanner',$pageBanner)
		->with('marqueeEnabled',$marqueeEnabled)
		->with('marqueeHeading',$marqueeHeading)
		->with('query1',$query1)
		->with('recentTrainings',$recentTrainings)
		->with('data',$data)
		->with('openaccess',$openaccess)
		->with('information',$information)
		->with('content',$content);
	}
	public function manuscriptguideline()
	{
		$pageBanner=DB::table('webpagebanner')->pluck('ImageSource');
		$marqueeEnabled = DB::table('webmarqueesetting')->pluck('EnableMarquee');
		$marqueeHeading = DB::table('webmarqueesetting')->pluck('MarqueeHeading');
		$query1 = DB::table('webtrainingdetails')->where('ShowInMarquee',1)->orderBy('CreatedOn','DESC')->select('Id',DB::raw("'xx' as Title"),'TrainingDescription',DB::raw('"Training" as Type'));
		$recentTrainings = DB::table('webadvertisements')->where('ShowInMarquee',1)->unionAll($query1)->get(array('Id','Title','Content as TrainingDescription',DB::raw('"Advertisement" as Type')));
	//	echo "<pre>"; dd($recentTrainings);
		/* END */
		$data=DB::select("SELECT a.Id AS menuId,a.`Title` AS menuTitle,a.`Has_Submenu`,b.`Title` 
		AS submenu,a.`Route` AS MenuRoute,b.`Route` AS submenuroute 
		FROM webjournalmenu a
		LEFT JOIN webjournalsubmenu b ON a.`Id`=b.`Menu_Id`
		ORDER BY a.`Id`");
		$openaccess = DB::table('webregistrationpagecontent')->where('Type',17)->pluck('Content');
		$information = DB::table('webregistrationpagecontent')->where('Type',18)->pluck('Content');
		$content = DB::table('webregistrationpagecontent')->where('Type',13)->pluck('Content');
		return View::make('website.journalmanuscriptguideline')
		->with('pageBanner',$pageBanner)
		->with('marqueeEnabled',$marqueeEnabled)
		->with('marqueeHeading',$marqueeHeading)
		->with('query1',$query1)
		->with('recentTrainings',$recentTrainings)
		->with('data',$data)
		->with('openaccess',$openaccess)
		->with('information',$information)
		->with('content',$content);
	}
	public function journalachieve()
	{
		$pageBanner=DB::table('webpagebanner')->pluck('ImageSource');
		$marqueeEnabled = DB::table('webmarqueesetting')->pluck('EnableMarquee');
		$marqueeHeading = DB::table('webmarqueesetting')->pluck('MarqueeHeading');
		$query1 = DB::table('webtrainingdetails')->where('ShowInMarquee',1)->orderBy('CreatedOn','DESC')->select('Id',DB::raw("'xx' as Title"),'TrainingDescription',DB::raw('"Training" as Type'));
		$recentTrainings = DB::table('webadvertisements')->where('ShowInMarquee',1)->unionAll($query1)->get(array('Id','Title','Content as TrainingDescription',DB::raw('"Advertisement" as Type')));
	//	echo "<pre>"; dd($recentTrainings);
		/* END */
		$data=DB::select("SELECT a.Id AS menuId,a.`Title` AS menuTitle,a.`Has_Submenu`,b.`Title` 
		AS submenu,a.`Route` AS MenuRoute,b.`Route` AS submenuroute 
		FROM webjournalmenu a
		LEFT JOIN webjournalsubmenu b ON a.`Id`=b.`Menu_Id`
		ORDER BY a.`Id`");
		$openaccess = DB::table('webregistrationpagecontent')->where('Type',17)->pluck('Content');
		$information = DB::table('webregistrationpagecontent')->where('Type',18)->pluck('Content');
		// $currentyear = date('Y', strtotime('-1 year'));
		// $achieve = JournalManuscriptModel::where('CreatedOn', date('Y', strtotime('-1 year')))->get();
		//  $achieve =DB::table('webjournalmanuscriptapplication')
		//  ->whereYear('CreatedOn','<',$currentyear)
		//  ->get(); 
		$content = DB::table('webregistrationpagecontent')->where('Type',15)->pluck('Content');

		// $achieve = DB::select("SELECT DATE_FORMAT(a.`CreatedOn`,'%d-%m-%Y') publishedYear,a.* FROM `webjournalmanuscript` a
		// 					WHERE DATE_FORMAT(a.`CreatedOn`,'%Y') = DATE_FORMAT(CURRENT_TIMESTAMP,'2020')");
		// $achieve_one = DB::select("SELECT DATE_FORMAT(a.`CreatedOn`,'%d-%m-%Y') publishedYear,a.* FROM `webjournalmanuscript` a
		// 					WHERE DATE_FORMAT(a.`CreatedOn`,'%Y') = DATE_FORMAT(CURRENT_TIMESTAMP,'2021')");
		// $achieve_two = DB::select("SELECT DATE_FORMAT(a.`CreatedOn`,'%d-%m-%Y') publishedYear,a.* FROM `webjournalmanuscript` a
		// 					WHERE DATE_FORMAT(a.`CreatedOn`,'%Y') = DATE_FORMAT(CURRENT_TIMESTAMP,'2022')");
		// $currentYear = date('Y');

		// Get the current year
		$currentYear = date('Y');

		// Array to store the results for each year
		$achievements = [];

		// Loop through the years starting from 2020 up to the previous year
			for ($year = 2020; $year < $currentYear; $year++) {
				// Fetch data for the current year
				$achievements[$year] = DB::select("SELECT DATE_FORMAT(a.`CreatedOn`,'%d-%m-%Y') publishedYear,a.* FROM `webjournalmanuscript` a
					WHERE DATE_FORMAT(a.`CreatedOn`,'%Y') = DATE_FORMAT(CURRENT_TIMESTAMP, '$year')");
			}


		return View::make('website.journalachieve')
		->with('pageBanner',$pageBanner)
		->with('marqueeEnabled',$marqueeEnabled)
		->with('marqueeHeading',$marqueeHeading)
		->with('query1',$query1)
		->with('recentTrainings',$recentTrainings)
		->with('achievements',$achievements)
		// ->with('achieve',$achieve)
		// ->with('achieve_one',$achieve_one)
		// ->with('achieve_two',$achieve_two)
		->with('data',$data)
		->with('openaccess',$openaccess)
		->with('information',$information)
		->with('content',$content);
	}
	// public function downloads(){
	// 	$downloadDetails = DB::select("select T1.CategoryName as CategoryName,T2.FileName as FileName,T2.Word as Word,T2.PDF as PDF,T2.Other as Other from webdownloadcategory T1 join webdownload T2 on T1.Id = T2.CategoryId order by T1.CategoryName,T2.FileName");
	// 	$slno=1;
	// 	return View::make('website.downloads')
	// 					->with('downloadTitle','All Downloads')
	// 					->with('downloadDetails',$downloadDetails)
	// 					->with('slno',$slno);
	// }
	
	public function journalpublishview($Application_No)
	{
		$pageBanner=DB::table('webpagebanner')->pluck('ImageSource');
		$marqueeEnabled = DB::table('webmarqueesetting')->pluck('EnableMarquee');
		$marqueeHeading = DB::table('webmarqueesetting')->pluck('MarqueeHeading');
		$query1 = DB::table('webtrainingdetails')->where('ShowInMarquee',1)->orderBy('CreatedOn','DESC')->select('Id',DB::raw("'xx' as Title"),'TrainingDescription',DB::raw('"Training" as Type'));
		$recentTrainings = DB::table('webadvertisements')->where('ShowInMarquee',1)->unionAll($query1)->get(array('Id','Title','Content as TrainingDescription',DB::raw('"Advertisement" as Type')));
	//	echo "<pre>"; dd($recentTrainings);
		/* END */
		$data=DB::select("SELECT a.Id AS menuId,a.`Title` AS menuTitle,a.`Has_Submenu`,b.`Title` 
		AS submenu,a.`Route` AS MenuRoute,b.`Route` AS submenuroute 
		FROM webjournalmenu a
		LEFT JOIN webjournalsubmenu b ON a.`Id`=b.`Menu_Id`
		ORDER BY a.`Id`");
		$content = DB::table('webjournalmanuscriptapplication')
		->leftJoin('webjournaltasklist', 'webjournalmanuscriptapplication.Application_No', '=', 'webjournaltasklist.Application_No')
		->where('webjournalmanuscriptapplication.Application_No',$Application_No)->get();
		return View::make('website.journalpublishview')
		->with('pageBanner',$pageBanner)
		->with('marqueeEnabled',$marqueeEnabled)
		->with('marqueeHeading',$marqueeHeading)
		->with('query1',$query1)
		->with('recentTrainings',$recentTrainings)
		->with('data',$data)
		    ->with('content',$content);
	}
	

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}


}
