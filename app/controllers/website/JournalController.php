<?php
use Illuminate\Support\Str;
class JournalController extends \BaseController {

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
		$content = DB::table('webregistrationpagecontent')->where('Type',16)->pluck('Content');
		return View::make('website.journalwelcome')
		->with('pageBanner',$pageBanner)
		->with('marqueeEnabled',$marqueeEnabled)
		->with('marqueeHeading',$marqueeHeading)
		->with('query1',$query1)
		->with('recentTrainings',$recentTrainings)
		->with('content',$content)
		->with('data',$data);
	}

	public function journalverification()
	{
	     $content = DB::table('webjournaluserapplication')->where('Status','SUBMITTED')->paginate(5);
		 return View::make('website.journalverification')
		 	->with('content',$content);
	}
	public function journalcoordinatorverification()
	{
		$grouptask = DB::select("SELECT * FROM `webjournalmanuscriptapplication` 
		LEFT JOIN `webjournaltasklist` ON `webjournalmanuscriptapplication`.`Application_No` = `webjournaltasklist`.`Application_No` 
		LEFT JOIN `webjournaltaskstatus` ON `webjournaltasklist`.`Task_status_Id` = `webjournaltaskstatus`.`Id` 
		WHERE (`Task_Status_Id` = 1 OR `Task_Status_Id` = 6 OR `Task_Status_Id` = 5 OR `Task_Status_Id` = 9
		 OR `Task_Status_Id` = 10 OR `Task_Status_Id` = 17 OR `Task_Status_Id` = 19 OR `Task_Status_Id` = 20 OR `Task_Status_Id` = 22 OR `Task_Status_Id` = 24
		 OR `Task_Status_Id` = 26 OR `Task_Status_Id` = 14 OR `Task_Status_Id` = 27 OR (`Task_Status_Id` = 11 AND `Task_Status_Id2` = '11'))
		AND webjournaltasklist.`Assigned_To` IS NULL"
		);
		
		return View::make('website.journalcoordinatorverification')
		->with('grouptask',$grouptask);

	}
	public function journalcoordinatormytask()
	{
		$userId = Auth::user()->Id;
		$mytask = DB::select("SELECT * FROM `webjournalmanuscriptapplication` 
		LEFT JOIN `webjournaltasklist` ON `webjournalmanuscriptapplication`.`Application_No` = `webjournaltasklist`.`Application_No` 
		LEFT JOIN `webjournaltaskstatus` ON `webjournaltasklist`.`Task_status_Id` = `webjournaltaskstatus`.`Id` 
		WHERE (`Task_Status_Id` = 1 OR `Task_Status_Id` = 6 OR `Task_Status_Id` = 5 OR `Task_Status_Id` = 9
		 OR `Task_Status_Id` = 10 OR `Task_Status_Id` = 17 OR `Task_Status_Id` = 19 OR `Task_Status_Id` = 20 OR `Task_Status_Id` = 22 OR `Task_Status_Id` = 24
		 OR `Task_Status_Id` = 26 OR `Task_Status_Id` = 14 OR `Task_Status_Id` = 27 OR `Task_Status_Id` = 11) 
		AND webjournaltasklist.`Assigned_To` ='".$userId."'"
		);

		return View::make('website.journalcoordinatormytask')
		->with('mytask',$mytask);
	}
	public function journalclaimapplication($Application_No)
	{
		if($userId = Auth::user()->Id)
		{
			$values = array('Assigned_To' => $userId);
	 	    DB::table('webjournaltasklist')->where('Application_No',$Application_No)->update($values);
		    return Redirect::to('web/journalcoordinatormytask')->with('jcclaim','Application number ' . $Application_No . ' is Claimed Successfully!');
		}else{
			return Redirect::to('web/journalcoordinatorverification')->with('jcalreadyclaim','Application number ' . $Application_No . ' is already Claimed!');
		}
	}
	public function journalunclaimapplication($Application_No)
	{ 
		$userId = Auth::user()->Id;
		$values = array('Assigned_To' => NULL);
	 	DB::table('webjournaltasklist')->where('Application_No',$Application_No)->update($values);
		 return Redirect::to('web/journalcoordinatorverification');
	}
	public function journaleditorialverification()
	{
		$grouptask = DB::select("SELECT * FROM `webjournalmanuscriptapplication` 
		LEFT JOIN `webjournaltasklist` ON `webjournalmanuscriptapplication`.`Application_No` = `webjournaltasklist`.`Application_No` 
		LEFT JOIN `webjournaltaskstatus` ON `webjournaltasklist`.`Task_status_Id` = `webjournaltaskstatus`.`Id` 
		WHERE (`Task_Status_Id` = 2 OR `Task_Status_Id` = 18 OR `Task_Status_Id` = 23) AND webjournaltasklist.`Assigned_To` IS NULL"
		);
		// $jc_remark = DB::table('webjournalmanuscriptapplication')->where('Remark_By_JC',$Remark_By_JC)->get();

		return View::make('website.journaleditorialverification')
		// ->with('jc_remark',$jc_remark)
		->with('grouptask',$grouptask);
	}
	public function journaleditorialmytask()
	{
		$userId = Auth::user()->Id;
		$mytask = DB::select("SELECT * FROM `webjournalmanuscriptapplication` LEFT JOIN `webjournaltasklist` 
		ON `webjournalmanuscriptapplication`.`Application_No` = `webjournaltasklist`.`Application_No` 
		LEFT JOIN `webjournaltaskstatus` ON `webjournaltasklist`.`Task_status_Id` = `webjournaltaskstatus`.`Id` 
		WHERE (`Task_Status_Id` = 2 OR `Task_Status_Id` = 18 OR `Task_Status_Id` = 23) AND webjournaltasklist.`Assigned_To` ='".$userId."'"
			);

		return View::make('website.journaleditorialmytask')
		->with('mytask',$mytask);
	}
	public function journalclaimapplicationbyeditorial($Application_No)
	{
		$userId = Auth::user()->Id;
		$values = array('Assigned_To' => $userId);
	 	DB::table('webjournaltasklist')->where('Application_No',$Application_No)->update($values);
		 return Redirect::to('web/journaleditorialmytask');
	}
	public function journalunclaimapplicationbyeditorial($Application_No)
	{ 
		$userId = Auth::user()->Id;
		$values = array('Assigned_To' => NULL);
	 	DB::table('webjournaltasklist')->where('Application_No',$Application_No)->update($values);
		 return Redirect::to('web/journaleditorialverification');
	}
	public function journalreviewerverification()
	{
	$reviewreId = Input::get('reviewerId');
	$reviewer2Id = Input::get('reviewer2Id');

		$userId = Auth::user()->Id;
		$mytask = DB::select("SELECT * FROM `webjournalmanuscriptapplication` LEFT JOIN `webjournaltasklist` 
		ON `webjournalmanuscriptapplication`.`Application_No` = `webjournaltasklist`.`Application_No` 
		LEFT JOIN `webjournaltaskstatus` ON `webjournaltasklist`.`Task_status_Id` = `webjournaltaskstatus`.`Id` 
		WHERE (`Task_Status_Id` = 3) AND webjournaltasklist.`Assigned_To` ='".$userId."'"
			);
	$mytask2 = DB::select("SELECT * FROM `webjournalmanuscriptapplication` LEFT JOIN `webjournaltasklist` 
		ON `webjournalmanuscriptapplication`.`Application_No` = `webjournaltasklist`.`Application_No` 
		LEFT JOIN `webjournaltaskstatus` ON `webjournaltasklist`.`Task_status_Id2` = `webjournaltaskstatus`.`Id` 
		WHERE (`Task_Status_Id2` = 3) AND webjournaltasklist.`Assigned_To_Reviewer2` ='".$userId."'"
		);

		return View::make('website.journalreviewerverification')
		->with('mytask2',$mytask2)
		->with('mytask',$mytask);
	}
	public function journalclaimapplicationbyreviewer($Application_No)
	{
		$userId = Auth::user()->Id;
		$values = array('Assigned_To' => $userId);
	 	DB::table('webjournaltasklist')->where('Application_No',$Application_No)->update($values);
		 return Redirect::to('web/journalreviewerverification');
	}
	public function journalunclaimapplicationbyreviewer($Application_No)
	{ 
		$userId = Auth::user()->Id;
		$values = array('Assigned_To' => NULL);
	 	DB::table('webjournaltasklist')->where('Application_No',$Application_No)->update($values);
		 return Redirect::to('web/journalreviewerverification');
	}
	public function journalchiefverification()
	{
		$grouptask = DB::select("SELECT * FROM `webjournalmanuscriptapplication` LEFT JOIN `webjournaltasklist` 
		ON `webjournalmanuscriptapplication`.`Application_No` = `webjournaltasklist`.`Application_No` 
		LEFT JOIN `webjournaltaskstatus` ON `webjournaltasklist`.`Task_status_Id` = `webjournaltaskstatus`.`Id` 
		WHERE (`Task_Status_Id` = 4 OR `Task_Status_Id` = 25) AND webjournaltasklist.`Assigned_To` IS NULL"
			);	
			
			return View::make('website.journalchiefverification')
			->with('grouptask',$grouptask);	
		}
	public function journalchiefmytask()
	{		
		$userId = Auth::user()->Id;
		$mytask = DB::select("SELECT * FROM `webjournalmanuscriptapplication` LEFT JOIN `webjournaltasklist` 
		ON `webjournalmanuscriptapplication`.`Application_No` = `webjournaltasklist`.`Application_No` 
		LEFT JOIN `webjournaltaskstatus` ON `webjournaltasklist`.`Task_status_Id` = `webjournaltaskstatus`.`Id` 
		WHERE (`Task_Status_Id` = 4 OR `Task_Status_Id` = 25) AND webjournaltasklist.`Assigned_To` ='".$userId."'"
			);

		return View::make('website.journalchiefmytask')
		->with('mytask',$mytask);
	}
	public function journalclaimapplicationbychief($Application_No)
	{
		$userId = Auth::user()->Id;
		$values = array('Assigned_To' => $userId);
	 	DB::table('webjournaltasklist')->where('Application_No',$Application_No)->update($values);
		 return Redirect::to('web/journalchiefmytask');
	}
	public function journalunclaimapplicationbychief($Application_No)
	{ 
		$userId = Auth::user()->Id;
		$values = array('Assigned_To' => NULL);
	 	DB::table('webjournaltasklist')->where('Application_No',$Application_No)->update($values);
		 return Redirect::to('web/journalchiefverification');
	}
	 public function journallistfromchief()
	 {
	 	$content =DB::table('webjournalmanuscriptapplication')
             ->leftJoin('webjournaltasklist', 'webjournalmanuscriptapplication.Application_No', '=', 'webjournaltasklist.Application_No')
             ->where('Task_Status_Id','5')
	 		->get();
	 	return View::make('website.journallistfromchief')
	 	 	->with('content',$content);
			  
	 }
	// public function journallistfromreviewer()
	// {
	// 	$content =DB::table('webjournalmanuscriptapplication')
    //         ->leftJoin('webjournaltasklist', 'webjournalmanuscriptapplication.Application_No', '=', 'webjournaltasklist.Application_No')
    //         ->where('Task_Status_Id','7')
	//  		->get();
	//  	return View::make('website.journallistfromreviewer')
	//  	 	->with('content',$content);
	// }
	public function journalmanuscript($Id)
	{
		$name=DB::select("SELECT a.Id,b.Name FROM `webjournalmanuscriptapplication` a LEFT JOIN `webjournaluser` b ON b.`Id` = a.`Id` WHERE a.`Id` = b.`Name`");
		$pageBanner=DB::table('webpagebanner')->pluck('ImageSource');
		$marqueeEnabled = DB::table('webmarqueesetting')->pluck('EnableMarquee');
		$marqueeHeading = DB::table('webmarqueesetting')->pluck('MarqueeHeading');
		$query1 = DB::table('webtrainingdetails')->where('ShowInMarquee',1)->orderBy('CreatedOn','DESC')->select('Id',DB::raw("'xx' as Title"),'TrainingDescription',DB::raw('"Training" as Type'));
		$recentTrainings = DB::table('webadvertisements')->where('ShowInMarquee',1)->unionAll($query1)->get(array('Id','Title','Content as TrainingDescription',DB::raw('"Advertisement" as Type')));
		$content = DB::table('webjournaluser')
			->where('webjournaluser.Id', $Id)->get();
		$data=DB::select("SELECT a.Id AS menuId,a.`Title` AS menuTitle,a.`Has_Submenu`,b.`Title` 
		AS submenu,a.`Route` AS MenuRoute,b.`Route` AS submenuroute 
		FROM webjournalmenu a
		LEFT JOIN webjournalsubmenu b ON a.`Id`=b.`Menu_Id` ORDER BY a.`Id`");
		return View::make('website.journalmanuscript')
		->with('pageBanner',$pageBanner)
		->with('marqueeEnabled',$marqueeEnabled)
		->with('marqueeHeading',$marqueeHeading)
		->with('query1',$query1)
		->with('recentTrainings',$recentTrainings)
		->with('content', $content)
		->with('name',$name)
		->with('data',$data);
	}
	public function journaldetailsendtoauthor()
	{
		$Application_No=Input::get('Application_No');
		$name=DB::select("SELECT a.Id,b.Name FROM `webjournalmanuscriptapplication` a LEFT JOIN `webjournaluser` b ON b.`Id` = a.`Id` WHERE b.`Name`");
		$pageBanner=DB::table('webpagebanner')->pluck('ImageSource');
		$marqueeEnabled = DB::table('webmarqueesetting')->pluck('EnableMarquee');
		$marqueeHeading = DB::table('webmarqueesetting')->pluck('MarqueeHeading');
		$query1 = DB::table('webtrainingdetails')->where('ShowInMarquee',1)->orderBy('CreatedOn','DESC')->select('Id',DB::raw("'xx' as Title"),'TrainingDescription',DB::raw('"Training" as Type'));
		$recentTrainings = DB::table('webadvertisements')->where('ShowInMarquee',1)->unionAll($query1)->get(array('Id','Title','Content as TrainingDescription',DB::raw('"Advertisement" as Type')));
		$content = DB::table('webjournalmanuscriptapplication')
		->leftJoin('webjournaltasklist', 'webjournalmanuscriptapplication.Application_No', '=', 'webjournaltasklist.Application_No')
		->where('webjournalmanuscriptapplication.Application_No',$Application_No)->get();
		$data=DB::select("SELECT a.Id AS menuId,a.`Title` AS menuTitle,a.`Has_Submenu`,b.`Title` 
		AS submenu,a.`Route` AS MenuRoute,b.`Route` AS submenuroute 
		FROM webjournalmenu a
		LEFT JOIN webjournalsubmenu b ON a.`Id`=b.`Menu_Id` ORDER BY a.`Id`");
		return View::make('website.journalsendtoauthor')
		->with('pageBanner',$pageBanner)
		->with('marqueeEnabled',$marqueeEnabled)
		->with('marqueeHeading',$marqueeHeading)
		->with('query1',$query1)
		->with('recentTrainings',$recentTrainings)
		->with('content',$content)
		->with('name',$name)
		->with('data',$data);
	}
	public function submitmanuscript()
	{
		$Id = Input::get('Id');
		$name_of_title = Input::get('name_of_title');
	
		$applicationNo = DB::select("SELECT CONCAT('CDB_',DATE_FORMAT(CURRENT_DATE,'%y'),
					IF((SUBSTRING(Application_No,9)+1)<10,CONCAT('000',SUBSTRING(Application_No,9)+1),
					IF((SUBSTRING(Application_No,9)+1)<100,CONCAT('00',SUBSTRING(Application_No,9)+1),
					IF((SUBSTRING(Application_No,9)+1)<1000,CONCAT('0',SUBSTRING(Application_No,9)+1),
					SUBSTRING(Application_No,9)+1)
					))) newApplicationNo
					FROM `webjournalmanuscriptapplication` WHERE 
					DATE_FORMAT(createdOn,'%y') = (DATE_FORMAT(CURRENT_DATE,'%y'))
					ORDER BY Application_No DESC LIMIT 1"
			 );
		    $rules = array(
			'file' => 'required|mimes:docx'
		);
		$validator = Validator::make(Input::all(),$rules);

		$newApplicationNo = "";
		if(count($applicationNo)==0)
		{
			$year = date("Y");
			$lastTwoDigitYear = substr($year, -2);
			$newApplicationNo = "CDB_".$lastTwoDigitYear."0001";
		}
		else{
			$newApplicationNo = $applicationNo[0]->newApplicationNo;
		}


	    if ($validator->fails()) {
			$messages=$validator->messages();
			return Redirect::to('web/journalmanuscript/' . $Id)->withInput()->withErrors($validator);
		} else if ($validator->passes()) {
			if (Input::hasFile('file')) {
				$fileExtension = File::extension(Input::file('file')->getClientOriginalName());
				$fileDestination = "/uploads/journal/manuscript/" . $newApplicationNo . '.' . $fileExtension;

				$data = array('Name_of_Title' => $name_of_title, 'File' => $fileDestination,'UserId'=> $Id, 'Application_No' => $newApplicationNo);

				JournalManuscriptModel::insert($data);

				$fileName = Input::file('file')->getClientOriginalName();
				$destinationPath = public_path() . sprintf("/uploads/journal/manuscript/");
				Input::file('file')->move($destinationPath, $newApplicationNo . '.' . $fileExtension);

				$data = array('Task_Status_Id' => '1', 'Application_No' => $newApplicationNo);
				JournalManuscripttasklistModel::insert($data);

				$email = DB::table('sysrole')
				->leftJoin('sysuserrolemap','sysrole.Id','=','sysuserrolemap.SysRoleId')
				->leftJoin('sysuser','sysuserrolemap.SysUserId','=','sysuser.Id')
				->where('sysrole.Id','02bac449-d5cd-11ec-b257-bc305be5439f')->pluck('Email');

				$message = "New Journal Manuscript For Your Group";
				$mailData = array(
					'mailMessage' => $message
				);
				$this->sendEmailMessage('emails.crps.mailnoticebyadministrator',$mailData,"Message From CDB",$email,'Journal Managing Editor');

				$email = DB::table('sysrole')
				->leftJoin('sysuserrolemap','sysrole.Id','=','sysuserrolemap.SysRoleId')
				->leftJoin('sysuser','sysuserrolemap.SysUserId','=','sysuser.Id')
				->where('sysrole.Id','02bac449-d5cd-11ec-b257-bc305be5439f')->pluck('Email');

				$message = "New Journal Manuscript For Your Group";
				$mailData = array(
					'mailMessage' => $message
				);
				$this->sendEmailMessage('emails.crps.mailnoticebyadministrator',$mailData,"Message From CDB",$email,'Journal Managing Editor');

				$useremail = DB::table('webjournaluser')
				->leftJoin('webjournalmanuscriptapplication','webjournaluser.Id','=','webjournalmanuscriptapplication.UserId')
				->leftJoin('webjournaltasklist','webjournalmanuscriptapplication.Id','=','webjournaltasklist.Id')
				->leftJoin('webjournaltaskstatus','webjournaltasklist.Task_Status_Id','=','webjournaltaskstatus.Id')
				->where('webjournalmanuscriptapplication.Application_No',$newApplicationNo)->pluck('webjournaluser.Email');
				$username = DB::table('webjournaluser')
				->leftJoin('webjournalmanuscriptapplication','webjournaluser.Id','=','webjournalmanuscriptapplication.UserId')
				->leftJoin('webjournaltasklist','webjournalmanuscriptapplication.Id','=','webjournaltasklist.Id')
				->leftJoin('webjournaltaskstatus','webjournaltasklist.Task_Status_Id','=','webjournaltaskstatus.Id')
				->where('webjournalmanuscriptapplication.Application_No',$newApplicationNo)->pluck('webjournaluser.Name');
				
				$message = "Dear $username , <br/> Your journal manuscript has been successfully submitted with application number: $newApplicationNo .
				<br/> Please keep record of the  application number assigned to track your manuscript.";
				$mailData = array(
					'mailMessage' => $message
				);
				$this->sendEmailMessage('emails.crps.mailnoticebyadministrator',$mailData,"Message From CDB",$useremail,$username);

				return Redirect::to('web/journalmanuscript/' . $Id)->with('successful', 'Your file is successfully uploaded. Your application number is ' . $newApplicationNo);
			}
		}

		
	}
	public function journalregistrationsubmitted()
	{
	     $content = DB::table('webjournaluser')->get();
		 return View::make('website.journalregistrationsubmitted')
		 	->with('content',$content);
	}
	public function registrationviewdetails($Id)
	{
		$content = DB::select("SELECT * FROM `webjournaluser` a LEFT JOIN `webjournalmanuscriptapplication` b ON b.`UserId` = a.`Id` LEFT JOIN `webjournaltasklist` c ON c.`Id`=b.`Id` LEFT JOIN `webjournaltaskstatus` d ON d.`Id`=c.`Task_Status_Id` WHERE a.`Id`='".$Id."'");
		return View::make('website.journalregistrationviewdetails')
		    ->with('content',$content);
	}
	public function journalcoordinatordetails($Application_No)
	{
		$reviewerId = Input::get('reviewerId');	
		$reviewer2Id = Input::get('reviewer2Id');
		$userId = Session::get('userId');
		$authorname = DB::select("SELECT * FROM `webjournalmanuscriptapplication` a LEFT JOIN `webjournaluser` b ON b.`Id` = a.`UserId` WHERE a.`Application_No` ='".$Application_No."'");
		// $checklist = DB::table('webjournalverifiedchecklist')->where('Application_No',$Application_No)->get();
		$reviewerlist = DB::table('sysrole')
		->leftJoin('sysuserrolemap', 'sysrole.Id', '=', 'sysuserrolemap.SysRoleId')
		->leftJoin('sysuser', 'sysuserrolemap.SysUserId', '=', 'sysuser.Id')
		->where('sysrole.Id','2b9a2784-d5cd-11ec-b257-bc305be5439f')->get();
		$content = DB::table('webjournalmanuscriptapplication')
		->leftJoin('webjournaltasklist', 'webjournalmanuscriptapplication.Application_No', '=', 'webjournaltasklist.Application_No')
		->where('webjournalmanuscriptapplication.Application_No',$Application_No)->get();


		return View::make('website.journalcoordinatordetails')
		->with('reviewerId',$reviewerId)
		->with('authorname',$authorname)
		->with('reviewerlist',$reviewerlist)
		// ->with('checklist',$checklist)
		->with('content',$content);
	}
	public function journalcoordinatordetailstoselectreviewer($Application_No)
	{
		$userId = Session::get('userId');
		$authorname = DB::select("SELECT * FROM `webjournalmanuscriptapplication` a LEFT JOIN `webjournaluser` b ON b.`Id` = a.`UserId` WHERE a.`Application_No` ='".$Application_No."'");
		// $checklist = DB::table('webjournalverifiedchecklist')->where('Application_No',$Application_No)->get();
		$reviewerlist = DB::table('sysrole')
		->leftJoin('sysuserrolemap', 'sysrole.Id', '=', 'sysuserrolemap.SysRoleId')
		->leftJoin('sysuser', 'sysuserrolemap.SysUserId', '=', 'sysuser.Id')
		->where('sysrole.Id','2b9a2784-d5cd-11ec-b257-bc305be5439f')->get();
		$content = DB::table('webjournalmanuscriptapplication')
		->leftJoin('webjournaltasklist', 'webjournalmanuscriptapplication.Application_No', '=', 'webjournaltasklist.Application_No')
		->where('webjournalmanuscriptapplication.Application_No',$Application_No)->get();

		$username1 = DB::select("SELECT a.`FullName` FROM `sysuser` a LEFT JOIN webjournalmanuscriptapplication s ON s.`Editorial_Assigned_Reviewer1`=a.`Id` WHERE s.`Application_No`='".$Application_No."'");
		$username2 = DB::select("SELECT a.`FullName` FROM `sysuser` a LEFT JOIN webjournalmanuscriptapplication s ON s.`Editorial_Assigned_Reviewer2`=a.`Id` WHERE s.`Application_No`='".$Application_No."'");

		return View::make('website.journalcoordinatordetailstoselectreviewer')
		->with('authorname',$authorname)
		// ->with('checklist',$checklist)
		->with('reviewerlist',$reviewerlist)
		->with('username1',$username1)
		->with('username2',$username2)
		->with('content',$content);
	}
	public function journalcoordinatordetailsforpublication($Application_No)
	{
		$authorname = DB::select("SELECT * FROM `webjournalmanuscriptapplication` a LEFT JOIN `webjournaluser` b ON b.`Id` = a.`UserId` WHERE a.`Application_No` ='".$Application_No."'");
		$content = DB::table('webjournalmanuscriptapplication')
		->leftJoin('webjournaltasklist', 'webjournalmanuscriptapplication.Application_No', '=', 'webjournaltasklist.Application_No')
		->where('webjournalmanuscriptapplication.Application_No',$Application_No)->get();
		$data = DB::table('webjournalmanuscript')->where('Application_No',$Application_No)->get();

		return View::make('website.journalcoordinatordetailsforpublication')
		->with('authorname',$authorname)
		->with('data',$data)
		->with('content',$content);
	}
	public function journalcoordinatordetailstosendbacktoauthor($Application_No)
	{
		$userId = Session::get('userId');
		$authorname = DB::select("SELECT * FROM `webjournalmanuscriptapplication` a LEFT JOIN `webjournaluser` b ON b.`Id` = a.`UserId` WHERE a.`Application_No` ='".$Application_No."'");
		$content = DB::table('webjournalmanuscriptapplication')
		->leftJoin('webjournaltasklist', 'webjournalmanuscriptapplication.Application_No', '=', 'webjournaltasklist.Application_No')
		->where('webjournalmanuscriptapplication.Application_No',$Application_No)->get();

		return View::make('website.journalcoordinatordetailstosendbacktoauthor')
		->with('authorname',$authorname)
		->with('content',$content);
	}
	public function journaltemplate()
	{
		return View::make('website.journaltemplateupload');
	}
	public function journaltemplateupload()
	{
		$Id=Input::get('Id');
		$rules = array(
			'articletemplate' => 'required|mimes:docx,pdf'
		);
		$validator = Validator::make(Input::all(), $rules);
		if ($validator->fails()) {
			return Redirect::to('web/journaltemplateupload' . '/' . $Id)->withErrors($validator)->withInput();
		} else if ($validator->passes()) {
			if (Input::hasFile('articletemplate')) {
				$fileExtension = File::extension(Input::file('articletemplate')->getClientOriginalName());
				$fileDestination = "/uploads/journal/filearticletemplate/" . $Id . '.' . $fileExtension;

				$data = array('Article_Template' => $fileDestination);
				DB::table('webjournaltemplate')->where('Id', $Id)->update($data);
				// JournalManuscriptModel::insert($data);

				$fileName = Input::file('articletemplate')->getClientOriginalName();
				$destinationPath = public_path() . sprintf("/uploads/journal/filearticletemplate/");
				Input::file('articletemplate')->move($destinationPath, $Id . '.' . $fileExtension);
			}
		}
		return Redirect::to('web/journaltemplate')->with('successfully', 'Files are successfully uploaded');
		
	}
	public function journalReviewerChecklistUpload()
	{
		$Id=Input::get('Id');
		$rules = array(
			'reviewerchecklist' => 'required|mimes:docx,pdf'
		);
		$validator = Validator::make(Input::all(), $rules);
		if ($validator->fails()) {
			return Redirect::to('web/journaltemplateupload' . '/' . $Id)->withErrors($validator)->withInput();
		} else if ($validator->passes()) {
			if (Input::hasFile('reviewerchecklist')) {
				$fileExtension = File::extension(Input::file('reviewerchecklist')->getClientOriginalName());
				$fileDestination = "/uploads/journal/filereviewerchecklist/" . $Id . '.' . $fileExtension;

				$data = array('Reviewer_Checklist' => $fileDestination);
				DB::table('webjournaltemplate')->where('Id', $Id)->update($data);
				// JournalManuscriptModel::insert($data);

				$fileName = Input::file('reviewerchecklist')->getClientOriginalName();
				$destinationPath = public_path() . sprintf("/uploads/journal/filereviewerchecklist/");
				Input::file('reviewerchecklist')->move($destinationPath, $Id . '.' . $fileExtension);
			}
		}
		return Redirect::to('web/journaltemplate')->with('successfully', 'Files are successfully uploaded');
		
	}
	public function journalcoordinatorreviseforwardtoeditorteam($Application_No)
	{
		$userId = Session::get('userId');
		$authorname = DB::select("SELECT * FROM `webjournalmanuscriptapplication` a LEFT JOIN `webjournaluser` b ON b.`Id` = a.`UserId` WHERE a.`Application_No` ='".$Application_No."'");
		$content = DB::table('webjournalmanuscriptapplication')
		->leftJoin('webjournaltasklist', 'webjournalmanuscriptapplication.Application_No', '=', 'webjournaltasklist.Application_No')
		->where('webjournalmanuscriptapplication.Application_No',$Application_No)->get();

		return View::make('website.journalcoordinatorreviseforwardtoeditorteam')
		->with('authorname',$authorname)
		->with('content',$content);
	}
	public function journalcoordinatorreviseforwardagaintoeditorteam($Application_No)
	{
		$userId = Session::get('userId');
		$authorname = DB::select("SELECT * FROM `webjournalmanuscriptapplication` a LEFT JOIN `webjournaluser` b ON b.`Id` = a.`UserId` WHERE a.`Application_No` ='" . $Application_No . "'");
		$content = DB::table('webjournalmanuscriptapplication')
			->leftJoin('webjournaltasklist', 'webjournalmanuscriptapplication.Application_No', '=', 'webjournaltasklist.Application_No')
			->where('webjournalmanuscriptapplication.Application_No', $Application_No)->get();

		return View::make('website.journalcoordinatorreviseforwardagaintoeditorteam')
			->with('authorname', $authorname)
			->with('content', $content);
	}
	public function journaleditorialreviseforwardtojc($Application_No)
	{
		$userId = Session::get('userId');
		$authorname = DB::select("SELECT * FROM `webjournalmanuscriptapplication` a LEFT JOIN `webjournaluser` b ON b.`Id` = a.`UserId` WHERE a.`Application_No` ='".$Application_No."'");
		$content = DB::table('webjournalmanuscriptapplication')
		->leftJoin('webjournaltasklist', 'webjournalmanuscriptapplication.Application_No', '=', 'webjournaltasklist.Application_No')
		->where('webjournalmanuscriptapplication.Application_No',$Application_No)->get();

		return View::make('website.journaleditorialreviseforwardtojc')
		->with('authorname',$authorname)
		->with('content',$content);
	}
	public function journaleditorialreviseagainforwardtojc($Application_No)
	{
		$userId = Session::get('userId');
		$authorname = DB::select("SELECT * FROM `webjournalmanuscriptapplication` a LEFT JOIN `webjournaluser` b ON b.`Id` = a.`UserId` WHERE a.`Application_No` ='" . $Application_No . "'");
		$content = DB::table('webjournalmanuscriptapplication')
			->leftJoin('webjournaltasklist', 'webjournalmanuscriptapplication.Application_No', '=', 'webjournaltasklist.Application_No')
			->where('webjournalmanuscriptapplication.Application_No', $Application_No)->get();

		return View::make('website.journaleditorialreviseagainforwardtojc')
			->with('authorname', $authorname)
			->with('content', $content);
	}
	public function journalcoordinatorreviseforwardfromeditorteam($Application_No)
	{
		$userId = Session::get('userId');
		$authorname = DB::select("SELECT * FROM `webjournalmanuscriptapplication` a LEFT JOIN `webjournaluser` b ON b.`Id` = a.`UserId` WHERE a.`Application_No` ='" . $Application_No . "'");
		$content = DB::table('webjournalmanuscriptapplication')
			->leftJoin('webjournaltasklist', 'webjournalmanuscriptapplication.Application_No', '=', 'webjournaltasklist.Application_No')
			->where('webjournalmanuscriptapplication.Application_No', $Application_No)->get();

		return View::make('website.journalcoordinatorreviseforwardfromeditorteam')
			->with('authorname', $authorname)
			->with('content', $content);
	}
	public function journalcoordinatorreviseagainforwardfromeditorteam($Application_No)
	{
		$userId = Session::get('userId');
		$authorname = DB::select("SELECT * FROM `webjournalmanuscriptapplication` a LEFT JOIN `webjournaluser` b ON b.`Id` = a.`UserId` WHERE a.`Application_No` ='" . $Application_No . "'");
		$content = DB::table('webjournalmanuscriptapplication')
			->leftJoin('webjournaltasklist', 'webjournalmanuscriptapplication.Application_No', '=', 'webjournaltasklist.Application_No')
			->where('webjournalmanuscriptapplication.Application_No', $Application_No)->get();

		return View::make('website.journalcoordinatorreviseagainforwardfromeditorteam')
			->with('authorname', $authorname)
			->with('content', $content);
	}
	public function journalcoordinatorreviserejectedfromeditorteam($Application_No)
	{
		$userId = Session::get('userId');
		$authorname = DB::select("SELECT * FROM `webjournalmanuscriptapplication` a LEFT JOIN `webjournaluser` b ON b.`Id` = a.`UserId` WHERE a.`Application_No` ='" . $Application_No . "'");
		$content = DB::table('webjournalmanuscriptapplication')
			->leftJoin('webjournaltasklist', 'webjournalmanuscriptapplication.Application_No', '=', 'webjournaltasklist.Application_No')
			->where('webjournalmanuscriptapplication.Application_No', $Application_No)->get();

		return View::make('website.journalcoordinatorreviserejectedfromeditorteam')
			->with('authorname', $authorname)
			->with('content', $content);
	}
	public function journaleditorialdetails($Application_No)
	{
		$userId = Session::get('userId');
		$authorname = DB::select("SELECT * FROM `webjournalmanuscriptapplication` a LEFT JOIN `webjournaluser` b ON b.`Id` = a.`UserId` WHERE a.`Application_No` ='".$Application_No."'");
		$reviewerlist = DB::table('sysrole')
		->leftJoin('sysuserrolemap', 'sysrole.Id', '=', 'sysuserrolemap.SysRoleId')
		->leftJoin('sysuser', 'sysuserrolemap.SysUserId', '=', 'sysuser.Id')
		->where('sysrole.Id','2b9a2784-d5cd-11ec-b257-bc305be5439f')->get();
		$content = DB::table('webjournalmanuscriptapplication')->where('Application_No',$Application_No)->get();
		return View::make('website.journaleditorialdetails')
		    ->with('content',$content)
			->with('authorname',$authorname)
			->with('userId',$userId)
			->with('reviewerlist',$reviewerlist);
	}
	public function journalreviewerdetails($Application_No)
	{
		$userId = Session::get('userId');
		$authorname = DB::select("SELECT * FROM `webjournalmanuscriptapplication` a LEFT JOIN `webjournaluser` b ON b.`Id` = a.`UserId` WHERE a.`Application_No` ='".$Application_No."'");
		$checklist = DB::select("SELECT a.title,a.Id AS groupId,b.Id AS checklist_Id,b.name AS checklistname,b.type AS checklisttype,
		c.Id AS subchecklist_Id,c.name AS subchecklistname,c.type AS subchecklisttype FROM webjournalchecklistgroup a
		LEFT JOIN webjournalchecklist b ON a.id=b.Checklistgroup_Id
		LEFT JOIN webjournalsubchecklist c ON b.id=c.Parent_Id
		ORDER BY a.Id,b.Id,c.Id ASC"
		);	 
		$content = DB::table('webjournalmanuscriptapplication')->where('Application_No',$Application_No)->get();
		return View::make('website.journalreviewerdetails')
		    ->with('content',$content)
			->with('authorname',$authorname)
			->with('userId',$userId)
			->with('checklist',$checklist);
	}
	public function journalchiefdetails($Application_No)
	{
		$userId = Session::get('userId');
		$authorname = DB::select("SELECT * FROM `webjournalmanuscriptapplication` a LEFT JOIN `webjournaluser` b ON b.`Id` = a.`UserId` WHERE a.`Application_No` ='".$Application_No."'");
		$content = DB::table('webjournalmanuscriptapplication')->where('Application_No',$Application_No)->get();
		return View::make('website.journalchiefdetails')
		->with('authorname',$authorname)
		->with('userId',$userId)
		    ->with('content',$content);
	}
	public function journalchiefeditordetails($Application_No)
	{
		$userId = Session::get('userId');
		$authorname = DB::select("SELECT * FROM `webjournalmanuscriptapplication` a LEFT JOIN `webjournaluser` b ON b.`Id` = a.`UserId` WHERE a.`Application_No` ='" . $Application_No . "'");
		$content = DB::table('webjournalmanuscriptapplication')->where('Application_No', $Application_No)->get();

		return View::make('website.journalchiefeditordetails')
			->with('authorname', $authorname)
			->with('userId', $userId)
			->with('content', $content);
	}
	public function getDownload($Application_No)
	{
		$content = DB::table('webjournalmanuscriptapplication')->where('Application_No',$Application_No)->get();
		return View::make('website.journaldownloadfile')
		    ->with('content',$content);
	}
	public function journalforwardtoeditorial()
	{
		$Application_No = Input::get('Application_No');
        
		$jc_remark = DB::table('webjournalmanuscriptapplication')
		            ->select('Remark_By_JC')
					->where('Application_No','=',$Application_No)
					->first();
		$jc_remark = Input::get('remark');
		$data=array('Remark_By_JC' => $jc_remark);
		DB::table('webjournalmanuscriptapplication')->where('Application_No',$Application_No)->update($data);


		$content = DB::table('webjournaltasklist')
		            ->select('Task_Status_Id')
					->where('Application_No','=',$Application_No)
					->first();
		$Task_Status_Id = '2';			
		$values = array('Task_Status_Id' => $Task_Status_Id,'Assigned_To' => NULL);
		DB::table('webjournaltasklist')->where('Application_No',$Application_No)->update($values);
		$email = DB::table('sysrole')
				->leftJoin('sysuserrolemap','sysrole.Id','=','sysuserrolemap.SysRoleId')
				->leftJoin('sysuser','sysuserrolemap.SysUserId','=','sysuser.Id')
				->where('sysrole.Id','4dd4f28b-d5cd-11ec-b257-bc305be5439f')->pluck('Email');

				$message = "Dear Editorial Team, <br/>
				            A submission has been forwarded to the editorial team. 
							Please login to the Construction Journal website for necessary action.";
				$mailData = array(
					'mailMessage' => $message
				);
				$this->sendEmailMessage('emails.crps.mailnoticebyadministrator',$mailData,"Message From CDB",$email,'Journal Editorial Team');
		return Redirect::to('web/journalcoordinatorverification')->with('successfully','Application number ' . $Application_No . ' of author is successsfully forwarded to editorial Team.');
	}
	public function journalforwardtojcbyeditorial()
	{
		$Application_No = Input::get('Application_No');

		$editorial_remark = Input::get('editorialremark');
		
		$Task_Status_Id = '14';
		// $Task_Status_Id_Reviewer2 = '3';

		$reviewerId = Input::get('reviewerId');	
		$reviewer2Id = Input::get('reviewer2Id');	

		// $values = array('Task_Status_Id' => $Task_Status_Id,'Task_Status_Id2' => $Task_Status_Id_Reviewer2,'Assigned_To' => $reviewerId ,
		// 'Assigned_To_Reviewer2' => $reviewer2Id,'Assigned_Date' => date("Y-m-d"),'Assigned_Date_For_Reviewer2' => date("Y-m-d"),'Created_On' => date("Y-m-d"));
		// DB::table('webjournaltasklist')->where('Application_No',$Application_No)->update($values);

		$values = array('Task_Status_Id' => $Task_Status_Id,'Assigned_To' => NULL);
		DB::table('webjournaltasklist')->where('Application_No',$Application_No)->update($values);
		$data=array('Remark_By_Editorial' => $editorial_remark,'Editorial_Assigned_Reviewer1'=>$reviewerId,'Editorial_Assigned_Reviewer2'=>$reviewer2Id);
		DB::table('webjournalmanuscriptapplication')->where('Application_No',$Application_No)->update($data);
		return Redirect::to('web/journaleditorialverification')->with('successeditorial','Application number ' . $Application_No . ' of author is successsfully forwarded to Journal Coordinator.');
	}
	public function journalforwardtoreviewer()
	{
		$Application_No = Input::get('Application_No');
		$jc_remark_to_reviewer = Input::get('jcremarktoreviewer');
		$data=array('Jc_Remark_to_Reviewer' => $jc_remark_to_reviewer);
		DB::table('webjournalmanuscriptapplication')->where('Application_No',$Application_No)->update($data);
		$rules = array(
			'file_forwarded_to_reviewer' => 'required|mimes:docx'
		);
		$validator = Validator::make(Input::all(),$rules);
		if ($validator->fails()) {
			return Redirect::to('web/journalcoordinatordetailstoselectreviewer'.'/'.$Application_No)->withErrors($validator)->withInput();
		} else if($validator->passes()){
		if(Input::hasFile('file_forwarded_to_reviewer'))
		{
			$fileExtension = File::extension(Input::file('file_forwarded_to_reviewer')->getClientOriginalName());
			$fileDestination = "/uploads/journal/fileforwardedtoreviewer/".$Application_No.'.'.$fileExtension;
			
			$data=array('File_Forwarded_to_Reviewer' => $fileDestination); 
			DB::table('webjournalmanuscriptapplication')->where('Application_No',$Application_No)->update($data);
			// JournalManuscriptModel::insert($data);

			$fileName = Input::file('file_forwarded_to_reviewer')->getClientOriginalName();
			$destinationPath =public_path().sprintf("/uploads/journal/fileforwardedtoreviewer/");
			Input::file('file_forwarded_to_reviewer')->move($destinationPath,$Application_No.'.'.$fileExtension);

		}
		}

		$Task_Status_Id = '3';
		$Task_Status_Id_Reviewer2 = '3';

		$reviewerId = Input::get('reviewerId');	
		$reviewer2Id = Input::get('reviewer2Id');

		$values = array('Task_Status_Id' => $Task_Status_Id,'Task_Status_Id2' => $Task_Status_Id_Reviewer2,'Assigned_To' => $reviewerId ,
		'Assigned_To_Reviewer2' => $reviewer2Id,'Assigned_Date' => date("Y-m-d"),'Assigned_Date_For_Reviewer2' => date("Y-m-d"),'Created_On' => date("Y-m-d"));
		DB::table('webjournaltasklist')->where('Application_No',$Application_No)->update($values);

		$reviewer1email = DB::table('sysrole')
			 ->leftJoin('sysuserrolemap','sysrole.Id','=','sysuserrolemap.SysRoleId')
			 ->leftJoin('sysuser','sysuserrolemap.SysUserId','=','sysuser.Id')
			 ->where('sysuser.Id',$reviewerId)->pluck('Email');


			 $message = "Dear Sir/ Madam, <br/>
			 A manuscript has been submitted for review. Please login and view for necessary action.";
			 $mailData = array(
				 'mailMessage' => $message
			 );
			 $this->sendEmailMessage('emails.crps.mailnoticebyadministrator',$mailData,"Message From CDB",$reviewer1email,'Journal Reviewer');

			 $reviewer2email = DB::table('sysrole')
			 ->leftJoin('sysuserrolemap','sysrole.Id','=','sysuserrolemap.SysRoleId')
			 ->leftJoin('sysuser','sysuserrolemap.SysUserId','=','sysuser.Id')
			 ->where('sysuser.Id',$reviewer2Id)->pluck('Email');


			 $message = "Dear Sir/ Madam,<br/>
			 A manuscript has been submitted for review. Please login and view for necessary action.";
			 $mailData = array(
				 'mailMessage' => $message
			 );
			 $this->sendEmailMessage('emails.crps.mailnoticebyadministrator',$mailData,"Message From CDB",$reviewer2email,'Journal Reviewer');

		return Redirect::to('web/journalcoordinatorverification')->with('successreviewer','Application number ' . $Application_No . ' of author is successsfully forwarded to reviewer.');

	}
	public function journalforwardtoauhtor()
	{
		$Application_No = Input::get('Application_No');
        
		$jc_remark_toAuthor = Input::get('remarkByJctoAuhtor');
		$data=array('Remark_By_JC_toAuthor' => $jc_remark_toAuthor);
		DB::table('webjournalmanuscriptapplication')->where('Application_No',$Application_No)->update($data);

		$rules = array(
			'reviewer1_checklist_edition' => 'required|mimes:docx'
		);
		$validator = Validator::make(Input::all(), $rules);
		if ($validator->fails()) {
			return Redirect::to('web/journalcoordinatordetailstosendbacktoauthor' . '/' . $Application_No)->withErrors($validator)->withInput();
		} else if ($validator->passes()) {
			if (Input::hasFile('reviewer1_checklist_edition')) {
				$fileExtension = File::extension(Input::file('reviewer1_checklist_edition')->getClientOriginalName());
				$fileDestination = "/uploads/journal/reviewer1_checklist_edition/" . $Application_No . '.' . $fileExtension;

				$data = array('Reviewer1_Checklist_Edition' => $fileDestination);
				DB::table('webjournalmanuscriptapplication')->where('Application_No', $Application_No)->update($data);
				// JournalManuscriptModel::insert($data);

				$fileName = Input::file('reviewer1_checklist_edition')->getClientOriginalName();
				$destinationPath = public_path() . sprintf("/uploads/journal/reviewer1_checklist_edition/");
				Input::file('reviewer1_checklist_edition')->move($destinationPath, $Application_No . '.' . $fileExtension);
			}
		}
		$rules = array(
			'reviewer1_commented_edition' => 'required|mimes:docx'
		);
		$validator = Validator::make(Input::all(), $rules);
		if ($validator->fails()) {
			return Redirect::to('web/journalcoordinatordetailstosendbacktoauthor' . '/' . $Application_No)->withErrors($validator)->withInput();
		} else if ($validator->passes()) {
			if (Input::hasFile('reviewer1_commented_edition')) {
				$fileExtension = File::extension(Input::file('reviewer1_commented_edition')->getClientOriginalName());
				$fileDestination = "/uploads/journal/reviewer1_commented_edition/" . $Application_No . '.' . $fileExtension;

				$data = array('Reviewer1_Commented_Edition' => $fileDestination);
				DB::table('webjournalmanuscriptapplication')->where('Application_No', $Application_No)->update($data);
				// JournalManuscriptModel::insert($data);

				$fileName = Input::file('reviewer1_commented_edition')->getClientOriginalName();
				$destinationPath = public_path() . sprintf("/uploads/journal/reviewer1_commented_edition/");
				Input::file('reviewer1_commented_edition')->move($destinationPath, $Application_No . '.' . $fileExtension);
			}
		}
		$rules = array(
			'reviewer2_checklist_edition' => 'required|mimes:docx'
		);
		$validator = Validator::make(Input::all(), $rules);
		if ($validator->fails()) {
			return Redirect::to('web/journalcoordinatordetailstosendbacktoauthor' . '/' . $Application_No)->withErrors($validator)->withInput();
		} else if ($validator->passes()) {
			if (Input::hasFile('reviewer2_checklist_edition')) {
				$fileExtension = File::extension(Input::file('reviewer2_checklist_edition')->getClientOriginalName());
				$fileDestination = "/uploads/journal/reviewer2_checklist_edition/" . $Application_No . '.' . $fileExtension;

				$data = array('Reviewer2_Checklist_Edition' => $fileDestination);
				DB::table('webjournalmanuscriptapplication')->where('Application_No', $Application_No)->update($data);
				// JournalManuscriptModel::insert($data);

				$fileName = Input::file('reviewer2_checklist_edition')->getClientOriginalName();
				$destinationPath = public_path() . sprintf("/uploads/journal/reviewer2_checklist_edition/");
				Input::file('reviewer2_checklist_edition')->move($destinationPath, $Application_No . '.' . $fileExtension);
			}
		}
		$rules = array(
			'reviewer2_commented_edition' => 'required|mimes:docx'
		);
		$validator = Validator::make(Input::all(), $rules);
		if ($validator->fails()) {
			return Redirect::to('web/journalcoordinatordetailstosendbacktoauthor' . '/' . $Application_No)->withErrors($validator)->withInput();
		} else if ($validator->passes()) {
			if (Input::hasFile('reviewer2_commented_edition')) {
				$fileExtension = File::extension(Input::file('reviewer2_commented_edition')->getClientOriginalName());
				$fileDestination = "/uploads/journal/reviewer2_commented_edition/" . $Application_No . '.' . $fileExtension;

				$data = array('Reviewer2_Commented_Edition' => $fileDestination);
				DB::table('webjournalmanuscriptapplication')->where('Application_No', $Application_No)->update($data);
				// JournalManuscriptModel::insert($data);

				$fileName = Input::file('reviewer2_commented_edition')->getClientOriginalName();
				$destinationPath = public_path() . sprintf("/uploads/journal/reviewer2_commented_edition/");
				Input::file('reviewer2_commented_edition')->move($destinationPath, $Application_No . '.' . $fileExtension);
			}
		}

		$Task_Status_Id = '16';	
		$Task_Status_Id2 = NULL;			
		$values = array('Task_Status_Id' => $Task_Status_Id,'Assigned_To' => NULL,'Task_Status_Id2' => $Task_Status_Id2, 'Assigned_To_Reviewer2' => NULL);
		DB::table('webjournaltasklist')->where('Application_No',$Application_No)->update($values);
		
		$useremail = DB::table('webjournaluser')
				->leftJoin('webjournalmanuscriptapplication','webjournaluser.Id','=','webjournalmanuscriptapplication.UserId')
				->leftJoin('webjournaltasklist','webjournalmanuscriptapplication.Id','=','webjournaltasklist.Id')
				->leftJoin('webjournaltaskstatus','webjournaltasklist.Task_Status_Id','=','webjournaltaskstatus.Id')
				->where('webjournalmanuscriptapplication.Application_No',$Application_No)->pluck('webjournaluser.Email');
				$username = DB::table('webjournaluser')
				->leftJoin('webjournalmanuscriptapplication','webjournaluser.Id','=','webjournalmanuscriptapplication.UserId')
				->leftJoin('webjournaltasklist','webjournalmanuscriptapplication.Id','=','webjournaltasklist.Id')
				->leftJoin('webjournaltaskstatus','webjournaltasklist.Task_Status_Id','=','webjournaltaskstatus.Id')
				->where('webjournalmanuscriptapplication.Application_No',$Application_No)->pluck('webjournaluser.Name');
				
				$message = "Dear $username , <br/> Your manuscript has undergone review. Please login to view and submit.";
				$mailData = array(
					'mailMessage' => $message
				);
				$this->sendEmailMessage('emails.crps.mailnoticebyadministrator',$mailData,"Message From CDB",$useremail,$username);
		return Redirect::to('web/journalcoordinatorverification')->with('successfully','Application number ' . $Application_No . ' of author is successsfully forwarded to Author.');
	}
	public function journalforwardagaintoauhtor()
	{
		$Application_No = Input::get('Application_No');

		$jc_remark_toAuthor = Input::get('Rejected_Remark');
		$data = array('JCremark_After_revised_Rejected' => $jc_remark_toAuthor);
		DB::table('webjournalmanuscriptapplication')->where('Application_No', $Application_No)->update($data);
		$rules = array(
			'attachments' => 'mimes:docx'
		);
		$validator = Validator::make(Input::all(), $rules);
		if ($validator->fails()) {
			return Redirect::to('web/journalcoordinatorreviserejectedfromeditorteam' . '/' . $Application_No)->withErrors($validator)->withInput();
		} else if ($validator->passes()) {
			if (Input::hasFile('attachments')) {
				$fileExtension = File::extension(Input::file('attachments')->getClientOriginalName());
				$fileDestination = "/uploads/journal/attachmentsreturn/" . $Application_No . '.' . $fileExtension;

				$data = array('File_Attachment' => $fileDestination);
				DB::table('webjournalmanuscriptapplication')->where('Application_No', $Application_No)->update($data);
				// JournalManuscriptModel::insert($data);

				$fileName = Input::file('attachments')->getClientOriginalName();
				$destinationPath = public_path() . sprintf("/uploads/journal/attachmentsreturn/");
				Input::file('attachments')->move($destinationPath, $Application_No . '.' . $fileExtension);
			}
		}
		$Task_Status_Id = '21';
		$Task_Status_Id2 = NULL;
		$values = array('Task_Status_Id' => $Task_Status_Id, 'Assigned_To' => NULL);
		DB::table('webjournaltasklist')->where('Application_No', $Application_No)->update($values);
		
		$useremail = DB::table('webjournaluser')
				->leftJoin('webjournalmanuscriptapplication','webjournaluser.Id','=','webjournalmanuscriptapplication.UserId')
				->leftJoin('webjournaltasklist','webjournalmanuscriptapplication.Id','=','webjournaltasklist.Id')
				->leftJoin('webjournaltaskstatus','webjournaltasklist.Task_Status_Id','=','webjournaltaskstatus.Id')
				->where('webjournalmanuscriptapplication.Application_No',$Application_No)->pluck('webjournaluser.Email');
				$username = DB::table('webjournaluser')
				->leftJoin('webjournalmanuscriptapplication','webjournaluser.Id','=','webjournalmanuscriptapplication.UserId')
				->leftJoin('webjournaltasklist','webjournalmanuscriptapplication.Id','=','webjournaltasklist.Id')
				->leftJoin('webjournaltaskstatus','webjournaltasklist.Task_Status_Id','=','webjournaltaskstatus.Id')
				->where('webjournalmanuscriptapplication.Application_No',$Application_No)->pluck('webjournaluser.Name');
				
				$message = "Dear $username , <br/> Your revised manuscript has been further reviewed. Please login to view and submit.";
				$mailData = array(
					'mailMessage' => $message
				);
				$this->sendEmailMessage('emails.crps.mailnoticebyadministrator',$mailData,"Message From CDB",$useremail,$username);
		return Redirect::to('web/journalcoordinatorverification')->with('successfully', 'Application number ' . $Application_No . ' of author is successsfully forwarded to Author.');
	}
	public function journalforwardtojcbyauthor()
	{
		$Application_No = Input::get('Application_No');
		$chief_remark = Input::get('chief_remark');
		
		$datas = array('Remark_By_Chief' => $chief_remark);
		DB::table('webjournalmanuscriptapplication')->where('Application_No', $Application_No)->update($datas);

		$Task_Status_Id = '17';
		$values = array('Task_Status_Id' => $Task_Status_Id, 'Assigned_To' => NULL);
		DB::table('webjournaltasklist')
			->where('Application_No', $Application_No)
			->update($values);

		$rules = array(
			'file_forwarded_to_jc_by_author' => 'required|mimes:docx'
		);
		$validator = Validator::make(Input::all(), $rules);
		if ($validator->fails()) {
			return Redirect::to('web/journaldetailsendtoauthor' . '/' . $Application_No)->withErrors($validator)->withInput();
		} else if ($validator->passes()) {
		if (Input::hasFile('file_forwarded_to_jc_by_author')) {
			$fileExtension = File::extension(Input::file('file_forwarded_to_jc_by_author')->getClientOriginalName());
			$fileDestination = "/uploads/journal/filereviseforwardedtojcbyauthor/" . $Application_No . '.' . $fileExtension;
			
			$data = array('File_Forwarded_to_JC_by_Author' => $fileDestination);
			DB::table('webjournalmanuscriptapplication')->where('Application_No', $Application_No)->update($data);
			// JournalManuscriptModel::insert($data);
			
			$fileName = Input::file('file_forwarded_to_jc_by_author')->getClientOriginalName();
			$destinationPath = public_path() . sprintf("/uploads/journal/filereviseforwardedtojcbyauthor/");
			Input::file('file_forwarded_to_jc_by_author')->move($destinationPath, $Application_No . '.' . $fileExtension);
		}
	}	
		return Redirect::to('web/journalauthordashboard')
			->with('resubmit', 'Your manuscript has been successfully submitted.');
	}
	public function journalforwardagaintojcbyauthor()
	{
		$Application_No = Input::get('Application_No');
		$chief_remark = Input::get('chief_remark');
		$Task_Status_Id = '22';
		
		$datas = array('Remark_By_Chief' => $chief_remark);
		DB::table('webjournalmanuscriptapplication')->where('Application_No', $Application_No)->update($datas);
	
		if (Input::hasFile('file_forwarded_again_to_jc_by_author')) {
			$fileExtension = File::extension(Input::file('file_forwarded_again_to_jc_by_author')->getClientOriginalName());
			$fileDestination = "/uploads/journal/filereviseforwardagainedtojcbyauthor/" . $Application_No . '.' . $fileExtension;

			$data = array('File_Forwarded_again_to_JC_by_Author' => $fileDestination);
			DB::table('webjournalmanuscriptapplication')->where('Application_No', $Application_No)->update($data);
			// JournalManuscriptModel::insert($data);

			$fileName = Input::file('file_forwarded_again_to_jc_by_author')->getClientOriginalName();
			$destinationPath = public_path() . sprintf("/uploads/journal/filereviseforwardagainedtojcbyauthor/");
			Input::file('file_forwarded_again_to_jc_by_author')->move($destinationPath, $Application_No . '.' . $fileExtension);
		}
	
		
		$values = array('Task_Status_Id' => $Task_Status_Id, 'Assigned_To' => NULL);

		DB::table('webjournaltasklist')
			->where('Application_No', $Application_No)
			->update($values);

		return Redirect::to('web/journalauthordashboard')
			->with('resubmit', 'Your manuscript has been successfully submitted.');
	}
	public function journalforwardtojcafterrejecting()
	{
		$Application_No = Input::get('Application_No');
		
		if (Input::hasFile('file_forwarded_to_jc_after_rejecting')) {
			$fileExtension = File::extension(Input::file('file_forwarded_to_jc_after_rejecting')->getClientOriginalName());
			$fileDestination = "/uploads/journal/filereviseforwardtojcafterrejecting/" . $Application_No . '.' . $fileExtension;

			$data = array('File' => $fileDestination);
			DB::table('webjournalmanuscriptapplication')->where('Application_No', $Application_No)->update($data);
			// JournalManuscriptModel::insert($data);

			$fileName = Input::file('file_forwarded_to_jc_after_rejecting')->getClientOriginalName();
			$destinationPath = public_path() . sprintf("/uploads/journal/filereviseforwardtojcafterrejecting/");
			Input::file('file_forwarded_to_jc_after_rejecting')->move($destinationPath, $Application_No . '.' . $fileExtension);
		}
	
		$Task_Status_Id = '27';
		$values = array('Task_Status_Id' => $Task_Status_Id, 'Assigned_To' => NULL);

		DB::table('webjournaltasklist')
			->where('Application_No', $Application_No)
			->update($values);

		return Redirect::to('web/journalauthordashboard')
			->with('resubmit', 'Your manuscript has been successfully submitted.');
	}
	public function journalforwardtoeditorteam()
	{
		$Application_No = Input::get('Application_No');
        
		$jc_remark_toEditorteam = Input::get('remarkByJctoEditorteam');
		$data=array('Remark_By_JC_toEditorteam' => $jc_remark_toEditorteam);
		DB::table('webjournalmanuscriptapplication')->where('Application_No',$Application_No)->update($data);

		$Task_Status_Id = '18';			
		$values = array('Task_Status_Id' => $Task_Status_Id,'Assigned_To' => NULL);
		DB::table('webjournaltasklist')->where('Application_No',$Application_No)->update($values);
		return Redirect::to('web/journalcoordinatorverification')->with('successfully','Application number ' . $Application_No . ' of author is successsfully forwarded to Editorial Team.');
	}
	public function journalagainforwardtoeditorteam()
	{
		$Application_No = Input::get('Application_No');

		$jc_remark_toEditorteam = Input::get('remarkByJctoEditorteam');
		$data = array('Remark_By_JC_toEditorteam' => $jc_remark_toEditorteam);
		DB::table('webjournalmanuscriptapplication')->where('Application_No', $Application_No)->update($data);

		$Task_Status_Id = '23';
		$values = array('Task_Status_Id' => $Task_Status_Id, 'Assigned_To' => NULL);
		DB::table('webjournaltasklist')->where('Application_No', $Application_No)->update($values);
		return Redirect::to('web/journalcoordinatorverification')->with('successfully', 'Application number ' . $Application_No . ' of author is successsfully forwarded to Editorial Team.');
	}
	public function journalforwardtojcbyeditorteam()
	{
		$Application_No = Input::get('Application_No');
        
		$Editorteam_remark_toJc = Input::get('remarkByEditorteamtoJc');
		$data=array('Remark_By_Editorteam_toJc' => $Editorteam_remark_toJc);
		DB::table('webjournalmanuscriptapplication')->where('Application_No',$Application_No)->update($data);
		$rules = array(
			'file_forwarded_to_jc_byeditorialteam' => 'required|mimes:docx'
		);
		$validator = Validator::make(Input::all(), $rules);
		if ($validator->fails()) {
			return Redirect::to('web/journaleditorialreviseforwardtojc' . '/' . $Application_No)->withErrors($validator)->withInput();
		} else if ($validator->passes()) {
		if (Input::hasFile('file_forwarded_to_jc_byeditorialteam')) {
			$fileExtension = File::extension(Input::file('file_forwarded_to_jc_byeditorialteam')->getClientOriginalName());
			$fileDestination = "/uploads/journal/revisefileforwardedtojcbyeditorial/" . $Application_No . '.' . $fileExtension;

			$data = array('File_Forwarded_to_JC_by_EditorialTeam' => $fileDestination);
			DB::table('webjournalmanuscriptapplication')->where('Application_No', $Application_No)->update($data);
			// JournalManuscriptModel::insert($data);

			$fileName = Input::file('file_forwarded_to_jc_byeditorialteam')->getClientOriginalName();
			$destinationPath = public_path() . sprintf("/uploads/journal/revisefileforwardedtojcbyeditorial/");
			Input::file('file_forwarded_to_jc_byeditorialteam')->move($destinationPath, $Application_No . '.' . $fileExtension);
			}
		}		
		$Task_Status_Id = '19';			
		$values = array('Task_Status_Id' => $Task_Status_Id,'Assigned_To' => NULL);
		DB::table('webjournaltasklist')->where('Application_No',$Application_No)->update($values);
		return Redirect::to('web/journaleditorialverification')->with('successeditorial','Application number ' . $Application_No . ' of author is successsfully forwarded to Journal Coordinator.');
	}
	public function journalagainforwardtojcbyeditorteam()
	{
		$Application_No = Input::get('Application_No');

		$Editorteam_remark_toJc = Input::get('remarkByEditorteamtoJc');
		$data = array('Remark_By_Editorteam_toJc' => $Editorteam_remark_toJc);
		DB::table('webjournalmanuscriptapplication')->where('Application_No', $Application_No)->update($data);
		$rules = array(
			'file_forwarded_to_jc_byeditorialteam' => 'required|mimes:docx'
		);
		$validator = Validator::make(Input::all(), $rules);
		if ($validator->fails()) {
			return Redirect::to('web/journaleditorialreviseforwardtojc' . '/' . $Application_No)->withErrors($validator)->withInput();
		} else if ($validator->passes()) {
		if (Input::hasFile('file_forwarded_to_jc_byeditorialteam')) {
			$fileExtension = File::extension(Input::file('file_forwarded_to_jc_byeditorialteam')->getClientOriginalName());
			$fileDestination = "/uploads/journal/revisefileforwardedtojcbyeditorial/" . $Application_No . '.' . $fileExtension;

			$data = array('File_Forwarded_to_JC_by_EditorialTeam' => $fileDestination);
			DB::table('webjournalmanuscriptapplication')->where('Application_No', $Application_No)->update($data);
			// JournalManuscriptModel::insert($data);

			$fileName = Input::file('file_forwarded_to_jc_byeditorialteam')->getClientOriginalName();
			$destinationPath = public_path() . sprintf("/uploads/journal/revisefileforwardedtojcbyeditorial/");
			Input::file('file_forwarded_to_jc_byeditorialteam')->move($destinationPath, $Application_No . '.' . $fileExtension);
		}
	}
		$Task_Status_Id = '24';
		$values = array('Task_Status_Id' => $Task_Status_Id, 'Assigned_To' => NULL);
		DB::table('webjournaltasklist')->where('Application_No', $Application_No)->update($values);
		return Redirect::to('web/journaleditorialverification')->with('successeditorial', 'Application number ' . $Application_No . ' of author is successsfully forwarded to Journal Coordinator.');
	}
	public function reviewerforwardtojc()
	{
		$Application_No = Input::get('Application_No');

		$userId = Auth::user()->Id;
		$checkReviewer=DB::select("SELECT a.`Assigned_To`,a.`Assigned_To_Reviewer2` FROM `webjournaltasklist` a WHERE a.`Application_No`='".$Application_No."'");

		$Task_Status_Id = '11';
		if($checkReviewer[0]->Assigned_To ==$userId)
		{
			$values = array('Task_Status_Id' => $Task_Status_Id,'Assigned_To' => NULL);
			DB::table('webjournaltasklist')->where('Application_No',$Application_No)->update($values);
			
		}
		else if($checkReviewer[0]->Assigned_To_Reviewer2 ==$userId)
		{

			$values = array('Task_Status_Id2' => $Task_Status_Id,'Assigned_To_Reviewer2' => NULL);
			DB::table('webjournaltasklist')->where('Application_No',$Application_No)->update($values);
		}
		if ($checkReviewer[0]->Assigned_To == $userId) {
			$rules = array(
				'file' => 'required|mimes:docx'
			);
			$validator = Validator::make(Input::all(), $rules);
			if ($validator->fails()) {
				return Redirect::to('web/journalreviewerdetails' . '/' . $Application_No)->withErrors($validator)->withInput();
			} else if ($validator->passes()) {
				if (Input::hasFile('file')) {
					$fileExtension = File::extension(Input::file('file')->getClientOriginalName());
					$fileDestination = "/uploads/journal/reviewer1_checklist/" . $Application_No . '.' . $fileExtension;

					$data = array('Reviewer1_Checklist' => $fileDestination);
					DB::table('webjournalmanuscriptapplication')->where('Application_No', $Application_No)->update($data);
					// JournalManuscriptModel::insert($data);

					$fileName = Input::file('file')->getClientOriginalName();
					$destinationPath = public_path() . sprintf("/uploads/journal/reviewer1_checklist/");
					Input::file('file')->move($destinationPath, $Application_No . '.' . $fileExtension);
				}
			}
			$rules = array(
				'commented_file' => 'required|mimes:docx'
			);
			$validator = Validator::make(Input::all(), $rules);
			if ($validator->fails()) {
				return Redirect::to('web/journalreviewerdetails' . '/' . $Application_No)->withErrors($validator)->withInput();
			} else if ($validator->passes()) {
				if (Input::hasFile('commented_file')) {
					$fileExtension = File::extension(Input::file('commented_file')->getClientOriginalName());
					$fileDestination = "/uploads/journal/reviewer1_commented/" . $Application_No . '.' . $fileExtension;

					$data = array('Reviewer1_Commented' => $fileDestination);
					DB::table('webjournalmanuscriptapplication')->where('Application_No', $Application_No)->update($data);
					// JournalManuscriptModel::insert($data);

					$fileName = Input::file('commented_file')->getClientOriginalName();
					$destinationPath = public_path() . sprintf("/uploads/journal/reviewer1_commented/");
					Input::file('commented_file')->move($destinationPath, $Application_No . '.' . $fileExtension);
				}
			}
		} else {
			$rules = array(
				'file' => 'required|mimes:docx'
			);
			$validator = Validator::make(Input::all(), $rules);
			if ($validator->fails()) {
				return Redirect::to('web/journalreviewerdetails' . '/' . $Application_No)->withErrors($validator)->withInput();
			} else if ($validator->passes()) {
				if (Input::hasFile('file')) {
					$fileExtension = File::extension(Input::file('file')->getClientOriginalName());
					$fileDestination = "/uploads/journal/reviewer2_checklist/" . $Application_No . '.' . $fileExtension;

					$data = array('Reviewer2_Checklist' => $fileDestination);
					DB::table('webjournalmanuscriptapplication')->where('Application_No', $Application_No)->update($data);
					// JournalManuscriptModel::insert($data);

					$fileName = Input::file('file')->getClientOriginalName();
					$destinationPath = public_path() . sprintf("/uploads/journal/reviewer2_checklist/");
					Input::file('file')->move($destinationPath, $Application_No . '.' . $fileExtension);
				}
			}
			$rules = array(
				'commented_file' => 'required|mimes:docx'
			);
			$validator = Validator::make(Input::all(), $rules);
			if ($validator->fails()) {
				return Redirect::to('web/journalreviewerdetails' . '/' . $Application_No)->withErrors($validator)->withInput();
			} else if ($validator->passes()) {
				if (Input::hasFile('commented_file')) {
					$fileExtension = File::extension(Input::file('commented_file')->getClientOriginalName());
					$fileDestination = "/uploads/journal/reviewer2_commented/" . $Application_No . '.' . $fileExtension;

					$data = array('Reviewer2_Commented' => $fileDestination);
					DB::table('webjournalmanuscriptapplication')->where('Application_No', $Application_No)->update($data);
					// JournalManuscriptModel::insert($data);

					$fileName = Input::file('commented_file')->getClientOriginalName();
					$destinationPath = public_path() . sprintf("/uploads/journal/reviewer2_commented/");
					Input::file('commented_file')->move($destinationPath, $Application_No . '.' . $fileExtension);
				}
			}
		}
		return Redirect::to('web/journalreviewerverification')
			->with('successreviewer','Application number ' . $Application_No . ' of author is successsfully forwarded to Journal Coordinator.');
		
		}
	public function journalforwardtocf()
	{
		$Application_No = Input::get('Application_No');
		
		$jc_remark_toChief = Input::get('remarkByJctoEditorInChief');
		$data = array('Remark_By_Jc_toChief' => $jc_remark_toChief);
		DB::table('webjournalmanuscriptapplication')->where('Application_No', $Application_No)->update($data);

		$Task_Status_Id = '4';			
		$values = array('Task_Status_Id' => $Task_Status_Id,'Assigned_To' => NULL);
		DB::table('webjournaltasklist')->where('Application_No',$Application_No)->update($values);
		$email = DB::table('sysrole')
				->leftJoin('sysuserrolemap','sysrole.Id','=','sysuserrolemap.SysRoleId')
				->leftJoin('sysuser','sysuserrolemap.SysUserId','=','sysuser.Id')
				->where('sysrole.Id','7b1b60ac-d5cd-11ec-b257-bc305be5439f')->pluck('Email');

				$message = "Dear Chief Editor, <br/>
				A submission has been forwarded to you. Please login to the Construction Journal website for necessary action.";
				$mailData = array(
					'mailMessage' => $message
				);
				$this->sendEmailMessage('emails.crps.mailnoticebyadministrator',$mailData,"Message From CDB",$email,'Journal Editor In-Chief');
		return Redirect::to('web/journalcoordinatorverification')->with('successfully','Application number ' . $Application_No . ' of author is successsfully forwarded to Journal Editor In-Chief.');
	}
	public function journalforwardtochief()
	{
		$Application_No = Input::get('Application_No');

		$jc_remark_toChief = Input::get('remarkByJctoEditorInChief');
		$data = array('Remark_By_Jc_toChief' => $jc_remark_toChief);
		DB::table('webjournalmanuscriptapplication')->where('Application_No', $Application_No)->update($data);
		
		$Task_Status_Id = '25';
		$values = array('Task_Status_Id' => $Task_Status_Id, 'Assigned_To' => NULL);
		DB::table('webjournaltasklist')->where('Application_No', $Application_No)->update($values);
		return Redirect::to('web/journalcoordinatorverification')->with('successfully', 'Application number ' . $Application_No . ' of author is successsfully forwarded to Journal Editor In-Chief.');
	}
	public function journalforwardtojc()
	{
		$Application_No = Input::get('Application_No');
		$chief_remark = Input::get('chief_remark');
		$Task_Status_Id = '5';	

		$datas=array('Remark_By_Chief' => $chief_remark);
		DB::table('webjournalmanuscriptapplication')->where('Application_No',$Application_No)->update($datas);
		$rules = array(
			'file_forwarded_to_jc_by_chief' => 'required|mimes:docx'
		);
		$validator = Validator::make(Input::all(), $rules);
		if ($validator->fails()) {
			return Redirect::to('web/journalchiefdetails' . '/' . $Application_No)->withErrors($validator)->withInput();
		} else if ($validator->passes()) {
        	if(Input::hasFile('file_forwarded_to_jc_by_chief'))
		{
			$fileExtension = File::extension(Input::file('file_forwarded_to_jc_by_chief')->getClientOriginalName());
			$fileDestination = "/uploads/journal/fileforwardedtojcbycheif/".$Application_No.'.'.$fileExtension;
			
			$data=array('File_Forwarded_to_JC_by_Chief' => $fileDestination); 
			DB::table('webjournalmanuscriptapplication')->where('Application_No',$Application_No)->update($data);
			// JournalManuscriptModel::insert($data);

			$fileName = Input::file('file_forwarded_to_jc_by_chief')->getClientOriginalName();
			$destinationPath = public_path().sprintf("/uploads/journal/fileforwardedtojcbycheif/");
			Input::file('file_forwarded_to_jc_by_chief')->move($destinationPath,$Application_No.'.'.$fileExtension);
		}
		} 
		
		$values = array('Task_Status_Id' => $Task_Status_Id,'Task_Status_Id2' => NULL,
						'Assigned_To' => NULL);

		DB::table('webjournaltasklist')
			->where('Application_No',$Application_No)
			->update($values);
		
		return Redirect::to('web/journalchiefverification')
		->with('successchief','Application number ' . $Application_No . ' of author is successsfully forwarded to Journal Coordinator.');
		
	}
	public function journalforwardchieftojc()
	{
		$Application_No = Input::get('Application_No');
		$chief_remark = Input::get('chief_remark');
		
		$datas = array('Remark_By_Chief' => $chief_remark);
		DB::table('webjournalmanuscriptapplication')->where('Application_No', $Application_No)->update($datas);

		$Task_Status_Id = '26';
		$values = array(
			'Task_Status_Id' => $Task_Status_Id, 'Task_Status_Id2' => NULL,
			'Assigned_To' => NULL
		);

		DB::table('webjournaltasklist')
			->where('Application_No', $Application_No)
			->update($values);

		$rules = array(
			'file_forwarded_to_jc_by_chief' => 'required|mimes:docx'
		);
		$validator = Validator::make(Input::all(), $rules);
		if ($validator->fails()) {
			return Redirect::to('web/journalchiefdetails' . '/' . $Application_No)->withErrors($validator)->withInput();
		} else if ($validator->passes()) {
		if (Input::hasFile('file_forwarded_to_jc_by_chief')) {
			$fileExtension = File::extension(Input::file('file_forwarded_to_jc_by_chief')->getClientOriginalName());
			$fileDestination = "/uploads/journal/fileforwardedtojcbycheif/" . $Application_No . '.' . $fileExtension;

			$data = array('File_Forwarded_to_JC_by_Chief' => $fileDestination);
			DB::table('webjournalmanuscriptapplication')->where('Application_No', $Application_No)->update($data);
			// JournalManuscriptModel::insert($data);

			$fileName = Input::file('file_forwarded_to_jc_by_chief')->getClientOriginalName();
			$destinationPath = public_path() . sprintf("/uploads/journal/fileforwardedtojcbycheif/");
			Input::file('file_forwarded_to_jc_by_chief')->move($destinationPath, $Application_No . '.' . $fileExtension);
		}
	}

		return Redirect::to('web/journalchiefverification')
			->with('successchief', 'Application number ' . $Application_No . ' of author is successsfully forwarded to Journal Coordinator.');
	}
	public function journalreviewerforwardtojc($Application_No)
	{
		$content = DB::table('webjournaltasklist')
		            ->select('Task_Status_Id')
					->where('Application_No','=',$Application_No)
					->first();
		$Task_Status_Id = '6';			
		$values = array('Task_Status_Id' => $Task_Status_Id,'Assigned_To' => NULL);
		DB::table('webjournaltasklist')->where('Application_No',$Application_No)->update($values);
		return Redirect::to('web/journalreviewerverification')->with('successchief','Application number ' . $Application_No . ' of author is successsfully forwarded to Journal Coordinator.');
	}
	public function journalsendbacktoauthor()
	{
		$Id = Input::get('Id');
		DB::update("ALTER TABLE webjournalrejected AUTO_INCREMENT = 1;");
		$Application_No = Input::get('Application_No');
		$Rejected_Remark= Input::get('Rejected_Remark');
		
		$data = array('Rejected_Remark_from_JC' => $Rejected_Remark);
		DB::table('webjournalmanuscriptapplication')->where('Application_No', $Application_No)->update($data);

		$authorname = DB::select("SELECT b.Email FROM `webjournalmanuscriptapplication` a LEFT JOIN `webjournaluser` b ON b.`Id` = a.`Id` WHERE a.`Application_No` ='" . $Application_No . "'");

		$Task_Status_Id = '7';
		$values = array('Task_Status_Id' => $Task_Status_Id);
		DB::table('webjournaltasklist')->where('Application_No', $Application_No)->update($values);

		$rules = array(
			'attachments' => 'mimes:docx,pdf'
		);
		$validator = Validator::make(Input::all(), $rules);
		if ($validator->fails()) {
			return Redirect::to('web/journalcoordinatordetails' . '/' . $Application_No)->withErrors($validator)->withInput();
		} else if ($validator->passes()) {
		if (Input::hasFile('attachments')) {
			$fileExtension = File::extension(Input::file('attachments')->getClientOriginalName());
			$fileDestination = "/uploads/journal/fileturnitinreport/" . $Application_No . '.' . $fileExtension;
		
			$data = array('File_Turnitin_Report' => $fileDestination);
			DB::table('webjournalmanuscriptapplication')->where('Application_No', $Application_No)->update($data);
			// JournalManuscriptModel::insert($data);

			$fileName = Input::file('attachments')->getClientOriginalName();
			$destinationPath = public_path() . sprintf("/uploads/journal/fileturnitinreport/");
			Input::file('attachments')->move($destinationPath, $Application_No . '.' . $fileExtension);
		}
	}

		$journalRejected = DB::table('webjournalmanuscriptapplication')->where('Application_No', $Application_No)->get();
		$manuscriptrejected = new JournalManuscriptRejectedModel();
		$manuscriptrejected->Application_No = $journalRejected[0]->Application_No;
		$manuscriptrejected->Name = $journalRejected[0]->Name;
		$manuscriptrejected->File = $journalRejected[0]->File_Ready_to_Publish;
		$manuscriptrejected->Name_of_Title = $journalRejected[0]->Name_of_Title;
		$manuscriptrejected->save();

		$email = DB::table('webjournaluser')
				->leftJoin('webjournalmanuscriptapplication','webjournaluser.Id','=','webjournalmanuscriptapplication.UserId')
				->leftJoin('webjournaltasklist','webjournalmanuscriptapplication.Id','=','webjournaltasklist.Id')
				->leftJoin('webjournaltaskstatus','webjournaltasklist.Task_Status_Id','=','webjournaltaskstatus.Id')
				->where('webjournalmanuscriptapplication.Application_No',$Application_No)->pluck('webjournaluser.Email');
				// $name = DB::table('webjournaluser')
				// ->leftJoin('webjournalmanuscriptapplication','webjournaluser.Id','=','webjournalmanuscriptapplication.UserId')
				// ->leftJoin('webjournaltasklist','webjournalmanuscriptapplication.Id','=','webjournaltasklist.Id')
				// ->leftJoin('webjournaltaskstatus','webjournaltasklist.Task_Status_Id','=','webjournaltaskstatus.Id')
				// ->where('webjournalmanuscriptapplication.Application_No',$Application_No)->pluck('webjournaluser.Name');
				
				$message = "Your manuscript with the application number $Application_No has been returned with recommendations. <br/> Please login to view and resubmit";
				$mailData = array(
					'mailMessage' => $message
				);
				$this->sendEmailMessage('emails.crps.mailnoticebyadministrator',$mailData,"Message From CDB",$email,'Author');		
		return Redirect::to('web/journalcoordinatorverification')->with('rejection', 'Application number ' . $Application_No . ' of author has been RETURNED.');
	}	
	public function journalsendbacktoeditorial($Application_No)
	{
		$content = DB::table('webjournaltasklist')
		            ->select('Task_Status_Id')
					->where('Application_No','=',$Application_No)
					->first();
		$Task_Status_Id = '2';			
		$values = array('Task_Status_Id' => $Task_Status_Id,'Assigned_To' => NULL);
		DB::table('webjournaltasklist')->where('Application_No',$Application_No)->update($values);
		return Redirect::to('web/journalcoordinatorverification')->with('rejection','Application number ' . $Application_No . ' of author is again forwarded to Journal Editorial .');
	}
	public function journalreturnbacktojcfromeditorialteam()
	{
		$Application_No = Input::get('Application_No');
		$Editorteam_remark_toJc = Input::get('remarkfromeditorialteam');
		$data = array('Remark_From_Editorteam_toJc_for_revised' => $Editorteam_remark_toJc);
		DB::table('webjournalmanuscriptapplication')->where('Application_No', $Application_No)->update($data);

		$rules = array(
			'file_return_to_jc_byeditorialteam' => 'mimes:docx,pdf'
		);
		$validator = Validator::make(Input::all(), $rules);
		if ($validator->fails()) {
			return Redirect::to('web/journaleditorialreviseforwardtojc' . '/' . $Application_No)->withErrors($validator)->withInput();
		} else if ($validator->passes()) {
		if (Input::hasFile('file_return_to_jc_byeditorialteam')) {
			$fileExtension = File::extension(Input::file('file_return_to_jc_byeditorialteam')->getClientOriginalName());
			$fileDestination = "/uploads/journal/filereturnfromeditorialafterrevise/" . $Application_No . '.' . $fileExtension;
		
			$data = array('File_Return_Manuscript_After_Revised' => $fileDestination);
			DB::table('webjournalmanuscriptapplication')->where('Application_No', $Application_No)->update($data);
			// JournalManuscriptModel::insert($data);

			$fileName = Input::file('file_return_to_jc_byeditorialteam')->getClientOriginalName();
			$destinationPath = public_path() . sprintf("/uploads/journal/filereturnfromeditorialafterrevise/");
			Input::file('file_return_to_jc_byeditorialteam')->move($destinationPath, $Application_No . '.' . $fileExtension);
		}
	}
		$Task_Status_Id = '20';
		$values = array('Task_Status_Id' => $Task_Status_Id, 'Assigned_To' => NULL);
		DB::table('webjournaltasklist')->where('Application_No', $Application_No)->update($values);
		return Redirect::to('web/journaleditorialverification')->with('editorialrejection', 'Application number ' . $Application_No . ' is forwarded to Journal Coordinator.');
	}
	public function journalrejectedbychief($Application_No)
	{
		$content = DB::table('webjournaltasklist')
		            ->select('Task_Status_Id')
					->where('Application_No','=',$Application_No)
					->first();
		$Task_Status_Id = '10';			
		$values = array('Task_Status_Id' => $Task_Status_Id,'Assigned_To' => NULL);
		DB::table('webjournaltasklist')->where('Application_No',$Application_No)->update($values);
		return Redirect::to('web/journalchiefverification')->with('chiefrejection','Application number ' . $Application_No . ' is forwarded to Journal Coordinator.');
	}
	 public function journalapprovedbyjc()
	{
		//DB::update("ALTER TABLE webjournalmanuscript AUTO_INCREMENT = 1; ASC");
		$Application_No = Input::get('Application_No');
		$Final_Title = Input::get('final_title');
		$Final_Name = Input::get('final_author_name');
		$Abstract = Input::get('abstract');
		$data = array('Title_of_Journal' => $Final_Title,'Authors_Name' => $Final_Name,'Landing_Remarks' => $Abstract);
		DB::table('webjournalmanuscriptapplication')->where('Application_No', $Application_No)->update($data);
			 	
		$Task_Status_Id = '8';			
	 	$values = array('Task_Status_Id' => $Task_Status_Id,'Assigned_To' => NULL);
	 	DB::table('webjournaltasklist')->where('Application_No',$Application_No)->update($values);
		
		$rules = array(
			'file_publish' => 'required|mimes:pdf'
		);
		$validator = Validator::make(Input::all(), $rules);
		if ($validator->fails()) {
			return Redirect::to('web/journalcoordinatordetailsforpublication' . '/' . $Application_No)->withErrors($validator)->withInput();
		} else if ($validator->passes()) {
		if(Input::hasFile('file_publish'))
		 {
			 $fileExtension = File::extension(Input::file('file_publish')->getClientOriginalName());
			 $fileDestination = "/uploads/journal/filepublication/".$Application_No.'.'.$fileExtension;
			 
			 $data=array('File_Ready_to_Publish' => $fileDestination); 
			 DB::table('webjournalmanuscriptapplication')->where('Application_No',$Application_No)->update($data);
			 // JournalManuscriptModel::insert($data);
 
			 $fileName = Input::file('file_publish')->getClientOriginalName();
			 $destinationPath = public_path().sprintf("/uploads/journal/filepublication/");
			 Input::file('file_publish')->move($destinationPath,$Application_No.'.'.$fileExtension);
		 }
		} 
		
		$journalapprovel = DB::table('webjournalmanuscriptapplication')->where('Application_No',$Application_No)->get();
		$manu=new JournalManuscriptApprovedModel();
		$manu->Application_No = $journalapprovel[0]->Application_No;
		$manu->Name = $journalapprovel[0]->Authors_Name;
		$manu->file = $journalapprovel[0]->File_Ready_to_Publish;
		$manu->Name_of_Title = $journalapprovel[0]->Title_of_Journal;
		$manu->Landing_Remarks = $journalapprovel[0]->Landing_Remarks;
		$manu->save();

				$useremail = DB::table('webjournaluser')
				->leftJoin('webjournalmanuscriptapplication','webjournaluser.Id','=','webjournalmanuscriptapplication.UserId')
				->leftJoin('webjournaltasklist','webjournalmanuscriptapplication.Id','=','webjournaltasklist.Id')
				->leftJoin('webjournaltaskstatus','webjournaltasklist.Task_Status_Id','=','webjournaltaskstatus.Id')
				->where('webjournalmanuscriptapplication.Application_No',$Application_No)->pluck('webjournaluser.Email');
				$username = DB::table('webjournaluser')
				->leftJoin('webjournalmanuscriptapplication','webjournaluser.Id','=','webjournalmanuscriptapplication.UserId')
				->leftJoin('webjournaltasklist','webjournalmanuscriptapplication.Id','=','webjournaltasklist.Id')
				->leftJoin('webjournaltaskstatus','webjournaltasklist.Task_Status_Id','=','webjournaltaskstatus.Id')
				->where('webjournalmanuscriptapplication.Application_No',$Application_No)->pluck('webjournaluser.Name');
				
				$message = "Dear $username , <br/>Your journal submission has been published on the Construction Journal of Bhutan website.";
				$mailData = array(
					'mailMessage' => $message
				);
				$this->sendEmailMessage('emails.crps.mailnoticebyadministrator',$mailData,"Message From CDB",$useremail,$username);

	 	return Redirect::to('web/journalcoordinatorverification')->with('approved','The manuscript has been successfully published. The author has been notified through email.');
	}
	public function registrationsubmitteddetails($Id)
	{
		$content = DB::table('webjournaluserapplication')->where('Id',$Id)->get();
		return View::make('website.journalregistrationsubmitteddetails')
		    ->with('content',$content);
	}
	public function registrationapproved($Id)
	{
		$content = DB::table('webjournaluserapplication')
		            ->select('Status')
					->where('Id','=',$Id)
					->first();
		//check status
		$Status = 'APPROVED';
		$userId=Auth::user()->Id;			
		//update status
		$values = array('Status' => $Status , 'ApprovedBy' => $userId , 'ApprovedDate' => date("Y-m-d"));
		DB::table('webjournaluserapplication')->where('Id',$Id)->update($values);

		$journaldetails = DB::table('webjournaluserapplication')->where('Id',$Id)->get();
		$user=new JournalUserModel();
		$user->Id = $journaldetails[0]->RegistrationId;
		$user->name=$journaldetails[0]->Name;
		$user->email=$journaldetails[0]->Email;
		$user->password=$journaldetails[0]->Password;
		$user->contact=$journaldetails[0]->Contact;
		$user->save();

		$this->emailtoauthor($user->email,$user->name,'Registration is Approved! Please login now');

		$content = DB::table('webjournaluserapplication')->where('Status','SUBMITTED')->get();
	    return Redirect::to('web/journalverification');
	}
	public function login()
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
		return View::make('website.journallogin')
		->with('pageBanner',$pageBanner)
		->with('marqueeEnabled',$marqueeEnabled)
		->with('marqueeHeading',$marqueeHeading)
		->with('query1',$query1)
		->with('recentTrainings',$recentTrainings)
		->with('data',$data);
	}
	public function register()
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
		return View::make('website.journalregistration')
		->with('pageBanner',$pageBanner)
		->with('marqueeEnabled',$marqueeEnabled)
		->with('marqueeHeading',$marqueeHeading)
		->with('query1',$query1)
		->with('recentTrainings',$recentTrainings)
		->with('data',$data);
	 	
	 }
	public function author()
	{
	 	return View::make('website.journalauthor');
	}
	public function success()
	{
		return View::make('website.journalsuccess');
	}
	
	public function emailtoauthor($emailId,$name,$message)
	{

		$mailData = array(
	 	'mailMessage' => $message
		);
		$this->sendEmailMessage('emails.crps.mailnoticebyadministrator',$mailData,
		"Message From CDB",$emailId,$name);
	}
	public function emailrejecttoauthor($emailId, $name, $message, $attachment)
	{

		$mailData = array(
			'mailMessage' => $message
		);
		$this->sendEmailMessage(
			'emails.crps.mailnoticebyadministrator',
			$mailData,
			"Message From CDB",
			$emailId,
			$name,
			$attachment
		);
	}
	public function journalregister()
	{
	$validation=Validator::make(Input::all(),
        array(
          'name'=>'required',
		  'email'=>'required|email|unique:webjournaluser',
          'password'=>'required|min:6',
		  'confirm_password'=>'required|same:password',
		  'contact'=>'required',
        ));
        if($validation->fails())
            return Redirect::to('web/journalregistration')->withErrors($validation)->withInput(Input::except('password'));
        else{
            $user=new JournalUserModel();
            $user->Name=Input::get('name');
            $user->Email=Input::get('email');
		    $user->Password=Input::get('password');
		    $user->Contact=Input::get('contact');
			$user->Status='APPROVED';
            $user->save();
			$this->emailtoauthor($user->Email,$user->Name,'Congratulation!! You have successfully registered in Construction Journal Of Bhutan.
			 Please login now.');
			$email = DB::table('sysrole')
			 ->leftJoin('sysuserrolemap','sysrole.Id','=','sysuserrolemap.SysRoleId')
			 ->leftJoin('sysuser','sysuserrolemap.SysUserId','=','sysuser.Id')
			 ->where('sysrole.Id','02bac449-d5cd-11ec-b257-bc305be5439f')->pluck('Email');

			 $message = "New Author has registered in Construction Journal of Bhutan";
			 $mailData = array(
				 'mailMessage' => $message
			 );
			 $this->sendEmailMessage('emails.crps.mailnoticebyadministrator',$mailData,"Message From CDB",$email,'Journal Managing Editor');

			return Redirect::to('web/journallogin')->with('success','Registration Successful! Please Login now.');
        }
        return View::make('web/journalregistration');
    }
    public function journallogin()
    {
	// DB::select("ALTER TABLE webjournaluser AUTO_INCREMENT = 1;");
	
        $rules = ['email' => '','password'=>'',];
			$data=DB::select("select * from webjournaluser a where a.Email='".
		    Input::get('email')."' and a.Password='".Input::get('password')."'");
		
			$username=$data;

			if($username){

			Session::put('email',Input::get('email'));
			Session::put('userName',$username[0]->Name);
			Session::put('userId', $username[0]->Id);
		
			}else{
				return Redirect::back()->withErrors($rules)->withInput()
			->with('failure','Username or Password is Invalid!');
			}
		if(count($data)>0){
				
				return Redirect::to('web/journalauthordashboard')
				    ->with('message','Login Successful! ');	
				}
				
		return Redirect::back()->withErrors($rules)->withInput()
			->with('failure','Username or Password is Invalid!');
    }
	public function journalauthordashboard()
	{
		$Id = Session::get('userId', Input::get('userId'));
		$name = DB::select("SELECT a.Id,b.Name FROM `webjournalmanuscriptapplication` a LEFT JOIN `webjournaluser` b ON b.`Id` = a.`Id` WHERE b.`Name`");
		$pageBanner = DB::table('webpagebanner')->pluck('ImageSource');
		$marqueeEnabled = DB::table('webmarqueesetting')->pluck('EnableMarquee');
		$marqueeHeading = DB::table('webmarqueesetting')->pluck('MarqueeHeading');
		$query1 = DB::table('webtrainingdetails')->where('ShowInMarquee', 1)->orderBy('CreatedOn', 'DESC')->select('Id', DB::raw("'xx' as Title"), 'TrainingDescription', DB::raw('"Training" as Type'));
		$recentTrainings = DB::table('webadvertisements')->where('ShowInMarquee', 1)->unionAll($query1)->get(array('Id', 'Title', 'Content as TrainingDescription', DB::raw('"Advertisement" as Type')));
		$content = DB::select("SELECT * FROM `webjournaluser` WHERE	Id='".$Id."'");
		$data = DB::select("SELECT a.Id AS menuId,a.`Title` AS menuTitle,a.`Has_Submenu`,b.`Title` 
		AS submenu,a.`Route` AS MenuRoute,b.`Route` AS submenuroute 
		FROM webjournalmenu a
		LEFT JOIN webjournalsubmenu b ON a.`Id`=b.`Menu_Id` ORDER BY a.`Id`");
		return View::make('website.journalauthordashboard')
			->with('pageBanner', $pageBanner)
			->with('marqueeEnabled', $marqueeEnabled)
			->with('marqueeHeading', $marqueeHeading)
			->with('query1', $query1)
			->with('recentTrainings', $recentTrainings)
			->with('content', $content)
			->with('name', $name)
			->with('data', $data);
	}
	public function journalresubmission($Id)
	{
		$name = DB::select("SELECT a.Id,b.Name FROM `webjournalmanuscriptapplication` a LEFT JOIN `webjournaluser` b ON b.`Id` = a.`Id` WHERE b.`Name`");
		$pageBanner = DB::table('webpagebanner')->pluck('ImageSource');
		$marqueeEnabled = DB::table('webmarqueesetting')->pluck('EnableMarquee');
		$marqueeHeading = DB::table('webmarqueesetting')->pluck('MarqueeHeading');
		$query1 = DB::table('webtrainingdetails')->where('ShowInMarquee', 1)->orderBy('CreatedOn', 'DESC')->select('Id', DB::raw("'xx' as Title"), 'TrainingDescription', DB::raw('"Training" as Type'));
		$recentTrainings = DB::table('webadvertisements')->where('ShowInMarquee', 1)->unionAll($query1)->get(array('Id', 'Title', 'Content as TrainingDescription', DB::raw('"Advertisement" as Type')));
		//	echo "<pre>"; dd($recentTrainings);
		/* END */
		// $file = public_path()."/CDB_2200001.pdf"
		// $content = DB::select("SELECT * FROM `webjournalmanuscriptapplication` a LEFT JOIN `webjournaluser` b ON b.`Id` = a.`Id` WHERE a.`Application_No` ='" . $Application_No . "'");

		$content = DB::table('webjournaluser')
			->where('webjournaluser.Id', $Id)->get();
		$data = DB::select("SELECT a.Id AS menuId,a.`Title` AS menuTitle,a.`Has_Submenu`,b.`Title` 
		AS submenu,a.`Route` AS MenuRoute,b.`Route` AS submenuroute 
		FROM webjournalmenu a
		LEFT JOIN webjournalsubmenu b ON a.`Id`=b.`Menu_Id` ORDER BY a.`Id`");
		return View::make('website.journalresubmission')
			->with('pageBanner', $pageBanner)
			->with('marqueeEnabled', $marqueeEnabled)
			->with('marqueeHeading', $marqueeHeading)
			->with('query1', $query1)
			->with('recentTrainings', $recentTrainings)
			->with('content', $content)
			->with('name', $name)
			->with('data', $data);
	}
	public function journaleditprofile($Id)
	{
		$name = DB::select("SELECT a.Id,b.Name FROM `webjournalmanuscriptapplication` a LEFT JOIN `webjournaluser` b ON b.`Id` = a.`Id` WHERE b.`Name`");
		$pageBanner = DB::table('webpagebanner')->pluck('ImageSource');
		$marqueeEnabled = DB::table('webmarqueesetting')->pluck('EnableMarquee');
		$marqueeHeading = DB::table('webmarqueesetting')->pluck('MarqueeHeading');
		$query1 = DB::table('webtrainingdetails')->where('ShowInMarquee', 1)->orderBy('CreatedOn', 'DESC')->select('Id', DB::raw("'xx' as Title"), 'TrainingDescription', DB::raw('"Training" as Type'));
		$recentTrainings = DB::table('webadvertisements')->where('ShowInMarquee', 1)->unionAll($query1)->get(array('Id', 'Title', 'Content as TrainingDescription', DB::raw('"Advertisement" as Type')));
		//	echo "<pre>"; dd($recentTrainings);
		/* END */
		// $file = public_path()."/CDB_2200001.pdf"
		// $content = DB::select("SELECT * FROM `webjournalmanuscriptapplication` a LEFT JOIN `webjournaluser` b ON b.`Id` = a.`UserId` WHERE a.`Application_No` ='" . $Application_No . "'");

		$content = DB::table('webjournaluser')
			->where('webjournaluser.Id', $Id)->get();
		$data = DB::select("SELECT a.Id AS menuId,a.`Title` AS menuTitle,a.`Has_Submenu`,b.`Title` 
		AS submenu,a.`Route` AS MenuRoute,b.`Route` AS submenuroute 
		FROM webjournalmenu a
		LEFT JOIN webjournalsubmenu b ON a.`Id`=b.`Menu_Id` ORDER BY a.`Id`");
		return View::make('website.journalusereditprofile')
			->with('pageBanner', $pageBanner)
			->with('marqueeEnabled', $marqueeEnabled)
			->with('marqueeHeading', $marqueeHeading)
			->with('query1', $query1)
			->with('recentTrainings', $recentTrainings)
			->with('content', $content)
			->with('name', $name)
			->with('data', $data);	
	}
	public function journalusereditprofile()
	{
		$Id=Input::get('Id');
		$name=Input::get('name');
		$email=Input::get('email');
		$contact=Input::get('contact');
		
		$validation = Validator::make(
			Input::all(),
			array(
				'name' => 'required',
				'email' => 'required|email',
				'contact' => 'required',
			)
		);
		if ($validation->fails())
			return Redirect::to('web/journaleditprofile/' . $Id)->withErrors($validation)->withInput(Input::except('password'));
		else {

		$values = array('Name' => $name, 'Email' => $email, 'Contact' => $contact);
		DB::table('webjournaluser')->where('Id', $Id)->update($values);
			
	}
	return Redirect::to('web/journaleditprofile/' . $Id)->with('success', 'Profile Successfully Updated!');
}
	public function getForgotPassword(){
		$pageBanner=DB::table('webpagebanner')->pluck('ImageSource');
		$marqueeEnabled = DB::table('webmarqueesetting')->pluck('EnableMarquee');
		$marqueeHeading = DB::table('webmarqueesetting')->pluck('MarqueeHeading');
		$query1 = DB::table('webtrainingdetails')->where('ShowInMarquee',1)->orderBy('CreatedOn','DESC')->select('Id',DB::raw("'xx' as Title"),'TrainingDescription',DB::raw('"Training" as Type'));
		$recentTrainings = DB::table('webadvertisements')->where('ShowInMarquee',1)->unionAll($query1)->get(array('Id','Title','Content as TrainingDescription',DB::raw('"Advertisement" as Type')));
        $data=DB::select("SELECT a.Id AS menuId,a.`Title` AS menuTitle,a.`Has_Submenu`,b.`Title` 
		AS submenu,a.`Route` AS MenuRoute,b.`Route` AS submenuroute 
		FROM webjournalmenu a
		LEFT JOIN webjournalsubmenu b ON a.`Id`=b.`Menu_Id`
		ORDER BY a.`Id`");
		return View::make('website.journalforgotpassword')
		->with('pageBanner',$pageBanner)
		->with('marqueeEnabled',$marqueeEnabled)
		->with('marqueeHeading',$marqueeHeading)
		->with('query1',$query1)
		->with('recentTrainings',$recentTrainings)
		->with('data',$data);
    }
	public function resetAndSendPassword(){
        $username = Input::get('email');
        $isUser = DB::table('webjournaluser')->where('email',$username)->count();
        if($isUser == 0){
            return Redirect::to('')->with('customerrormessage','This username is not registered with any username');
        }
        $email = DB::table('webjournaluser')->where('email',$username)->pluck('Email');
        if(!$email){
            $email = $username;
        }
        if($email){
            $fullName = DB::table('webjournaluser')->where('email',$username)->pluck('Name');
            $newPassword = randomString().randomString();
            $newHashedPassword = Hash::make($newPassword);
            DB::table('webjournaluser')->where('email',$username)->update(array('password'=>$newHashedPassword));
            $message = "Your password has been successfully reset. Your new password is:<br/> $newPassword<br/> Please login with this and change it if you wish to";
            $mailData=array(
                'mailMessage'=>$message
            );
            $this->sendEmailMessage('emails.crps.mailnoticebyadministrator',$mailData,"Password Reset Successfully",$email,$fullName);
            return Redirect::to('/')->with('savedsuccessmessage','Your password has been reset successfully. Please check the email address associated with your account for the new password');
        }else{
            return Redirect::to('/')->with('customerrormessage','You do not have an email associated with your account. Please contact administrator');
        }
    }

	public function logout()
	{
	 	// if(Auth::check()){
	 	// 	Auth::logout();
			Session::flush();
	 	// }
	 	return Redirect::to('web/journallogin');
	}
	// public function localization($locale)
    // {
    //     App::setLocale($locale);
    //     // store the locale in session so that the middleware can register it
    //     session()->put('locale', $locale);
    //     return redirect()->back();
    // }

	public function journalsearch(){
	    $results = array();
	    $term = Input::get('Term');
	    $term = strtolower($term);
	    $term = ucwords($term);
	    if((bool)$term && strpos($term,'=')==false){
            if(strpos($term,'Board') > -1 || strpos($term,'Member')>-1){
                $boardMembers = DB::table('cmncountry')
                    ->take(1)
                    ->selectRaw("'Board Members' as Name, 'Info' as Detail1Name, 'Construction Development Board' as Detail1, NULL as Detail2Name, NULL as Detail2,'web/boardmembers' as Link1, NULL as Link2")
                    ->get();
            }
            $secretariatDetails = DB::table('webcdbsecretariat as T1')
                ->leftJoin('webcdbdesignations as T2','T2.Id','=','T1.DesignationId')
                ->whereRaw("T1.FullName like '%$term%'")
                ->selectRaw("'CDB Secretariat' as Name, 'Name' as Detail1Name, T1.FullName as Detail1,'Designation' as Detail2Name,T2.DesignationName as Detail2, NULL as Link1, NULL as Link2")
                ->get();

            $results = array_merge($results,$secretariatDetails);

            if(strpos($term,'About') > -1 || strpos($term, 'About us') > -1 || strpos($term,'Cdb')>-1){
                $aboutUs = DB::table('cmncountry')
                    ->take(1)
                    ->selectRaw("'About Us' as Name, NULL as Detail1Name, NULL as Detail1, NULL as Detail2Name, NULL as Detail2,'web/aboutus' as Link1, NULL as Link2")
                    ->get();
            }
            if(strpos($term,'Performance') > -1 || strpos($term,'Annual Performance Target') > -1 || strpos($term,'Performance Target') > -1 || strpos($term, 'Target') > -1 || strpos($term,'Apt')>-1){
                $apt = DB::table('cmncountry')
                    ->take(1)
                    ->selectRaw("'Annual Performance Target' as Name, 'Info' as Detail1Name, 'CDBs Annual Performance Target' as Detail1, NULL as Detail2Name, NULL as Detail2,'web/apt' as Link1, NULL as Link2")
                    ->get();
            }
            if(strpos($term,'Arbitration') > -1 || strpos($term,'Arbitration Forum') > -1 || strpos($term,'Cafc')>-1 || strpos($term,'Arbitr') > -1){
                $arbitration1 = DB::table('cmncountry')
                    ->take(1)
                    ->selectRaw("'Arbitration' as Name, 'Info' as Detail1Name, 'Arbitration service' as Detail1, NULL as Detail2Name, NULL as Detail2,'web/arbitration' as Link1, NULL as Link2")
                    ->get();
                $arbitration2 = DB::table('cmncountry')
                    ->take(1)
                    ->selectRaw("'Arbitration Forum' as Name, 'Info' as Detail1Name, 'Forum for Arbitrators' as Detail1, NULL as Detail2Name, NULL as Detail2,'web/arbitrationforum' as Link1, NULL as Link2")
                    ->get();
                $arbitration3 = DB::table('cmncountry')
                    ->take(1)
                    ->selectRaw("'Arbitrators' as Name, 'Info' as Detail1Name, 'List of Arbitrators' as Detail1, NULL as Detail2Name, NULL as Detail2,'web/listofarbitrators' as Link1, NULL as Link2")
                    ->get();
            }
            $arbitratorDetails = DB::table('webarbitrators as T1')
                ->whereRaw("(T1.Name like '%$term%' or T1.RegNo like '%$term%') AND coalesce(T1.IsDeleted,0)<>1")
                ->selectRaw("'Arbitrator' as Name, 'Name' as Detail1Name, T1.Name as Detail1,'Designation' as Detail2Name,T1.Designation as Detail2, 'web/listofarbitrators' as Link1, NULL as Link2")
                ->get();
            $results = array_merge($results,$arbitratorDetails);

            $trainingDetails = DB::table('webtrainingdetails as T1')
                ->join('webtrainingtype as T2','T2.Id','=','T1.TrainingTypeId')
                ->whereRaw("(T2.TrainingType like '%$term%' or T1.TrainingTitle like '%$term%')")
                ->selectRaw("'Training' as Name,'Type' as Detail1Name, T2.TrainingType as Detail1,'Title' as Detail2Name,T1.TrainingTitle as Detail2, NULL as Link1, NULL as Link2")
                ->get();
            $results = array_merge($results,$trainingDetails);

            if(strpos($term,'Contractor')>-1){
                $contractorReg = DB::table('cmncountry')
                    ->take(1)
                    ->selectRaw("'Contractor Registration' as Name, 'Info' as Detail1Name, 'Information about contractor registration' as Detail1, NULL as Detail2Name, NULL as Detail2,'web/contractorregistrationdetails' as Link1, NULL as Link2")
                    ->get();
                if(strpos($term,'Registration')==false){
                    $contractorList = DB::table('cmncountry')
                        ->take(1)
                        ->selectRaw("'List of Contractors' as Name, 'Info' as Detail1Name, 'Contractors registered with CDB' as Detail1, NULL as Detail2Name, NULL as Detail2,'web/listofcontractors' as Link1, NULL as Link2")
                        ->get();
                }
            }
            if(strpos($term,'Consultant')>-1){
                $consultantReg = DB::table('cmncountry')
                    ->take(1)
                    ->selectRaw("'Consultant Registration' as Name, 'Info' as Detail1Name, 'Information about consultant registration' as Detail1, NULL as Detail2Name, NULL as Detail2,'web/consultantregistrationdetails' as Link1, NULL as Link2")
                    ->get();
                if(strpos($term,'Registration')==false){
                    $consultantList = DB::table('cmncountry')
                        ->take(1)
                        ->selectRaw("'List of Consultants' as Name, 'Info' as Detail1Name, 'Consultants registered with CDB' as Detail1, NULL as Detail2Name, NULL as Detail2,'web/listofconsultants' as Link1, NULL as Link2")
                        ->get();
                }
            }
            if(strpos($term,'Architect')>-1){
                $architectReg = DB::table('cmncountry')
                    ->take(1)
                    ->selectRaw("'Architect Registration' as Name, 'Info' as Detail1Name, 'Information about architect registration' as Detail1, NULL as Detail2Name, NULL as Detail2,'web/architectregistrationdetails' as Link1, NULL as Link2")
                    ->get();
                if(strpos($term,'Registration')==false){
                    $architectList = DB::table('cmncountry')
                        ->take(1)
                        ->selectRaw("'List of Architects' as Name, 'Info' as Detail1Name, 'Architects registered with CDB' as Detail1, NULL as Detail2Name, NULL as Detail2,'web/listofarchitects' as Link1, NULL as Link2")
                        ->get();
                }
            }
            if(strpos($term,'Specialized Trade')>-1){
                $spReg = DB::table('cmncountry')
                    ->take(1)
                    ->selectRaw("'Specialized Trade Registration' as Name, 'Info' as Detail1Name, 'Information about Specialized Trade registration' as Detail1, NULL as Detail2Name, NULL as Detail2,'web/specializedtraderegistrationdetails' as Link1, NULL as Link2")
                    ->get();
                if(strpos($term,'Registration')==false){
                    $spList = DB::table('cmncountry')
                        ->take(1)
                        ->selectRaw("'List of Specialized Traders' as Name, 'Info' as Detail1Name, 'Specialized Traders registered with CDB' as Detail1, NULL as Detail2Name, NULL as Detail2,'web/listofspecializedtrades' as Link1, NULL as Link2")
                        ->get();
                }
            }

            if(strpos($term,'Registration')>-1 && (strpos($term,'Specialized Trade')==false  &&  strpos($term,'Architect')==false && strpos($term,'Contractor')==false && strpos($term,'Consultant')==false)){
                $contractorReg = DB::table('cmncountry')
                    ->take(1)
                    ->selectRaw("'Contractor Registration' as Name, 'Info' as Detail1Name, 'Information about contractor registration' as Detail1, NULL as Detail2Name, NULL as Detail2,'web/contractorregistrationdetails' as Link1, NULL as Link2")
                    ->get();
                $consultantReg = DB::table('cmncountry')
                    ->take(1)
                    ->selectRaw("'Consultant Registration' as Name, 'Info' as Detail1Name, 'Information about consultant registration' as Detail1, NULL as Detail2Name, NULL as Detail2,'web/consultantregistrationdetails' as Link1, NULL as Link2")
                    ->get();
                $architectReg = DB::table('cmncountry')
                    ->take(1)
                    ->selectRaw("'Architect Registration' as Name, 'Info' as Detail1Name, 'Information about architect registration' as Detail1, NULL as Detail2Name, NULL as Detail2,'web/architectregistrationdetails' as Link1, NULL as Link2")
                    ->get();
                $spReg = DB::table('cmncountry')
                    ->take(1)
                    ->selectRaw("'Specialized Trade Registration' as Name, 'Info' as Detail1Name, 'Information about Specialized Trade registration' as Detail1, NULL as Detail2Name, NULL as Detail2,'web/specializedtraderegistrationdetails' as Link1, NULL as Link2")
                    ->get();
            }
            $webDownloadDetails = DB::table('webdownload as T1')
                ->join('webdownloadcategory as T2','T1.CategoryId','=','T2.Id')
                ->whereRaw("(T2.CategoryName like '%$term%' or T1.FileName like '%$term%')")
                ->selectRaw("'File Downloads' as Name, 'Category' as Detail1Name, T2.CategoryName as Detail1, 'File Name' as Detail2Name, T1.FileName as Detail2,'web/downloads' as Link1, NULL as Link2")
                ->get();
            $results = array_merge($results,$webDownloadDetails);

            if(strpos($term,'Feedback')>-1){
                $feedbackDetails = DB::table('cmncountry')
                    ->take(1)
                    ->selectRaw("'Feedback Form' as Name, 'Info' as Detail1Name, 'Submit your feedback' as Detail1, NULL as Detail2Name, NULL as Detail2,'web/feedback' as Link1, NULL as Link2")
                    ->get();
            }
            if(strpos($term,'Faq')>-1 || strpos($term,'Frequently Asked Question')!=false){
                $faqDetails = DB::table('cmncountry')
                    ->take(1)
                    ->selectRaw("'FAQs' as Name, 'Info' as Detail1Name, 'FAQ page' as Detail1, NULL as Detail2Name, NULL as Detail2,'web/faq' as Link1, NULL as Link2")
                    ->get();
            }
            if(strpos($term,'Contact')>-1){
                $contactDetails = DB::table('cmncountry')
                    ->take(1)
                    ->selectRaw("'Contact Us' as Name, 'Info' as Detail1Name, 'Contact us regarding any queries' as Detail1, NULL as Detail2Name, NULL as Detail2,'web/contactus' as Link1, NULL as Link2")
                    ->get();
            }
            if(strpos($term,'E-Zotin')>-1 || strpos($term,'E-zotin')>-1 || strpos($term,'Ezotin')>-1 || strpos($term,'E-tool')>-1 || strpos($term,'Etool')>-1 || strpos($term,'Cinet')>-1){
                $ezotinDetails = DB::table('cmncountry')
                    ->take(1)
                    ->selectRaw("'Ezotin' as Name, 'Info' as Detail1Name, 'Login to Ezotin (E-tool/CiNET) or to Contractor/Consultant/Architect/Specialized Trade Account' as Detail1, NULL as Detail2Name, NULL as Detail2,'ezhotin/home/1' as Link1, NULL as Link2")
                    ->get();
            }
            if(strpos($term,'Forum')>-1){
                $forumDetails1 = DB::table('cmncountry')
                    ->take(1)
                    ->selectRaw("'Forum' as Name, 'Info' as Detail1Name, 'Forum' as Detail1, NULL as Detail2Name, NULL as Detail2,'web/viewforum' as Link1, NULL as Link2")
                    ->get();
                $forumDetails2 = DB::table('cmncountry')
                    ->take(1)
                    ->selectRaw("'Arbitration Forum' as Name, 'Info' as Detail1Name, 'Forum for Arbitrators' as Detail1, NULL as Detail2Name, NULL as Detail2,'web/arbitrationforum' as Link1, NULL as Link2")
                    ->get();
            }
            if(strpos($term,'Tv Spot')>-1 || strpos($term,'Video')>-1 || strpos($term,'Tv')>-1){
                $tvSpotDetails = DB::table('cmncountry')
                    ->take(1)
                    ->selectRaw("'TV Spot' as Name, 'Info' as Detail1Name, 'CDB TV Spot' as Detail1, NULL as Detail2Name, NULL as Detail2,'web/allvideos' as Link1, NULL as Link2")
                    ->get();
            }
            $advertisementDetails = DB::table('webadvertisements as T1')
                ->whereRaw("(T1.Title like '%$term%' OR T1.Content like '%$term%')")
                ->selectRaw("'Advertisement' as Name, 'Title' as Detail1Name, T1.Title as Detail1, 'Content' as Detail2Name, T1.Content as Detail2,concat('web/advertisementdetails/',T1.Id) as Link1, NULL as Link2")
                ->get();
            $results = array_merge($results,$advertisementDetails);
            if(strpos($term,'Advertisement')>-1){
                $adList = DB::table('cmncountry')
                    ->take(1)
                    ->selectRaw("'Advertisements' as Name, 'Info' as Detail1Name, 'Advertisements by CDB' as Detail1, NULL as Detail2Name, NULL as Detail2,'web/alladvertisements' as Link1, NULL as Link2")
                    ->get();
            }
            if(strpos($term,'Tender')>-1){
                $tenderList = DB::table('cmncountry')
                    ->take(1)
                    ->selectRaw("'Tenders' as Name, 'Info' as Detail1Name, 'Tenders uploaded by Agencies' as Detail1, NULL as Detail2Name, NULL as Detail2,'web/tenderlist' as Link1, NULL as Link2")
                    ->get();
            }

            if(isset($boardMembers)){
                $results = array_merge($results,$boardMembers);
            }
            if(isset($aboutUs)){
                $results = array_merge($results,$aboutUs);
            }
            if(isset($apt)){
                $results = array_merge($results,$apt);
            }
            if(isset($arbitration1)){
                $results = array_merge($results,$arbitration1);
                $results = array_merge($results,$arbitration2);
                $results = array_merge($results,$arbitration3);
            }
            if(isset($contractorReg)){
                $results = array_merge($results,$contractorReg);
            }
            if(isset($contractorList)){
                $results = array_merge($results,$contractorList);
            }
            if(isset($consultantReg)){
                $results = array_merge($results,$consultantReg);
            }
            if(isset($consultantList)){
                $results = array_merge($results,$consultantList);
            }
            if(isset($architectReg)){
                $results = array_merge($results,$architectReg);
            }
            if(isset($architectList)){
                $results = array_merge($results,$architectList);
            }
            if(isset($spReg)){
                $results = array_merge($results,$spReg);
            }
            if(isset($spList)){
                $results = array_merge($results,$spList);
            }
            if(isset($feedbackDetails)){
                $results = array_merge($results,$feedbackDetails);
            }
            if(isset($faqDetails)){
                $results = array_merge($results,$faqDetails);
            }
            if(isset($ezotinDetails)){
                $results = array_merge($results,$ezotinDetails);
            }
            if(isset($forumDetails1)){
                $results = array_merge($results,$forumDetails1);
                $results = array_merge($results,$forumDetails2);
            }
            if(isset($tvSpotDetails)){
                $results = array_merge($results,$tvSpotDetails);
            }
            if(isset($adList)){
                $results = array_merge($results,$adList);
            }
            if(isset($tenderList)){
                $results = array_merge($results,$tenderList);
            }
            if(isset($contactDetails)){
                $results = array_merge($results,$contactDetails);
            }
        }

		$pageBanner=DB::table('webpagebanner')->pluck('ImageSource');
		$marqueeEnabled = DB::table('webmarqueesetting')->pluck('EnableMarquee');
		$marqueeHeading = DB::table('webmarqueesetting')->pluck('MarqueeHeading');
		$query1 = DB::table('webtrainingdetails')->where('ShowInMarquee',1)->orderBy('CreatedOn','DESC')->select('Id',DB::raw("'xx' as Title"),'TrainingDescription',DB::raw('"Training" as Type'));
		$recentTrainings = DB::table('webadvertisements')->where('ShowInMarquee',1)->unionAll($query1)->get(array('Id','Title','Content as TrainingDescription',DB::raw('"Advertisement" as Type')));

		$data=DB::select("SELECT a.Id AS menuId,a.`Title` AS menuTitle,a.`Has_Submenu`,b.`Title` 
		AS submenu,a.`Route` AS MenuRoute,b.`Route` AS submenuroute 
		FROM webjournalmenu a
		LEFT JOIN webjournalsubmenu b ON a.`Id`=b.`Menu_Id`
		ORDER BY a.`Id`");

        return View::make('website.journalsearchresult')
		->with('pageBanner',$pageBanner)
		->with('marqueeEnabled',$marqueeEnabled)
		->with('marqueeHeading',$marqueeHeading)
		->with('query1',$query1)
		->with('recentTrainings',$recentTrainings)
		->with('data',$data)
                    ->with('results',$results);
    }

	public function journallistjc()
	{
		return View::make('website.journallistjc');
	}
	public function journallisteditorial()
	{
		return View::make('website.journallisteditorial');
	}
	public function journallistreviewer()
	{
		return View::make('website.journallistreviewer');
	}
	public function journallistcheif()
	{
		return View::make('website.journallistchief');
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
