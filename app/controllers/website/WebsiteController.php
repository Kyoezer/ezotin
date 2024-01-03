<?php
class WebsiteController extends BaseController{
	public function index(){
        Session::put('language', 'english');
		$listOfTenders=TenderModel::where('CmnWorkExecutionStatusId',NULL)
									->where('PublishInWebsite',1)
									->where('LastDateAndTimeOfSubmission','>',date('Y-m-d G:i:s'))
									->whereRaw("coalesce(DeleteStatus,'N') <> 'Y'")
									->orderBy('CreatedOn','desc')
									->limit(5)
									->get(array('Id','NameOfWork'));

		$listOfNews=WebCircularModel::where('CircularTypeId','590ad1bf-2adf-11e5-8bff-080027dcfac6')
										->orderBy('DisplayOrder','DESC')
										->limit(4)
										->where(DB::raw('coalesce(Display,0)'),1)
										->get(array('Id','Content','Title','CreatedOn'));

		$listOfNotifications=WebCircularModel::where('CircularTypeId','5f9a54e6-2adf-11e5-8bff-080027dcfac6')
										->orderBy('DisplayOrder', 'DESC')
										->limit(4)
										->where(DB::raw('coalesce(Display,0)'),1)
										->get(array('Id','Content','Title','CreatedOn'));

		$listOfAdvertisements =  DB::table('webadvertisements')
									->orderBy('DisplayOrder','DESC')
									->limit(4)
									->get(array('Id','Content','Title','CreatedOn'));

		$sliderImages=WebCircularModel::where('CircularTypeId','64f61cff-2adf-11e5-8bff-080027dcfac6')
										->where('Featured',1)
										->orderBy('CreatedOn','desc')
										->get(array('Id','Title','ImageUpload'));

		$advertisements=WebAdvertisementsModel::orderBy('CreatedOn','desc')
												->get(array('Id','Title','Content'));
		$video = videomodel::where('DisplayStatus',1)->take(1)->get(array('OggVideoPath',"WebmVideoPath","Mp4VideoPath"));

		$forums = DB::table('forum as T1')
			->leftJoin('comments as T2',function($join){
				$join->on('T2.forum_id','=','T1.id')
					->on('T2.status','=',DB::raw("'1'"));
			})
			->whereRaw("coalesce(T1.ReferenceNo,0) <> 1")
			->groupBy('T1.id')
			->orderBy('T1.CreatedOn', 'DESC')
			->limit(3)
			->get(array(DB::raw('count(T2.id) as CommentCount'),'T1.id','T1.topic','T1.CreatedOn'));
		$bottomToggle = DB::table('syssetting')->where('ReferenceNo',1)->pluck('Value');

		return View::make('website.homepage')
						->with('video',$video)
						->with('listOfTenders',$listOfTenders)
						->with('listOfNews',$listOfNews)
						->with('forums',$forums)
						->with('listOfNotifications',$listOfNotifications)
						->with('listOfAdvertisements',$listOfAdvertisements)
						->with('sliderImages',$sliderImages)
						->with('bottomToggle',$bottomToggle)
						->with('advertisements',$advertisements);
	}

	public function editBanner(){
		return View::make('website.editbanner');
	}

	public function pageDetails($id){
		$page_details = DB::select("select * from webpostpage where id = ?", array($id));
		return View::make('website.pages')
						->with('page_details',$page_details);
	}

	public function boardMembers(){
		return View::make('website.boardmembers');
	}
	public function boardMeeting(){
		return View::make('website.boardmeeting');
	}
	public function aboutUs(){
		return View::make('website.aboutus');
	}

	public function organogram(){
		return View::make('website.organogram');
	}

	public function webLinks(){
		return View::make('website.weblinks');
	}

	public function contractorRegistrationDetails(){
		$content = DB::table('webregistrationpagecontent')->where('Type',1)->pluck('Content');
		return View::make('website.contractorregistrationdetails')
				->with('content',$content);
	}

	public function consultantRegistrationDetails(){
		$content = DB::table('webregistrationpagecontent')->where('Type',2)->pluck('Content');
		return View::make('website.consultantregistrationdetails')
			->with('content',$content);
	}

	public function architectRegistrationDetails(){
		$content = DB::table('webregistrationpagecontent')->where('Type',3)->pluck('Content');
		return View::make('website.architectregistrationdetails')
			->with('content',$content);
	}

	public function specializedTradeRegistrationDetails(){
		$content = DB::table('webregistrationpagecontent')->where('Type',4)->pluck('Content');
		return View::make('website.specializedtraderegistrationdetails')
			->with('content',$content);
	}
	public function certifiedbuilderRegistrationDetails(){
		$content = DB::table('webregistrationpagecontent')->where('Type',21)->pluck('Content');
		return View::make('website.certifiedbuilderregistrationdetails')
			->with('content',$content);
	}


	public function arbitrationCommittee(){
		return View::make('website.arbitrationcommittee');
	}

	public function codeOfEthics(){
		return View::make('website.codeofethics');
	}

	public function aboutArbitration(){
		return View::make('website.aboutarbitration');
	}
	public function arbitration(){
		$arbitrations = DB::table('webcircular as T1')
							->join('webcirculartype as T2','T2.Id','=','T1.CircularTypeId')
							->where('T2.ReferenceNo','=',4)
							->get(array('T1.Title','T1.Content','T1.Attachment','T1.ImageUpload'));
		return View::make('website.arbitration')
					->with('arbitrations',$arbitrations);
	}
	public function webLogin(){
		return View::make('website.weblogin');
	}

	public function eventCalendar(){
		return View::make('website.eventcalendar');
	}

	public function getToggleBottom(){
		$details = DB::table('syssetting')->where('ReferenceNo',1)->get(array('Id','Value'));
		if(!$details){
			$details = array();
		}
		return View::make('website.togglebottom')
				->with('details',$details);
	}
	public function postSaveToggleBottom(){
		$value = Input::get('Value');
		DB::table('syssetting')->where('ReferenceNo',1)->update(array('Value'=>(int)$value));
		return Redirect::to('web/togglebottom')->with('savedsuccessmessage','Record has been saved');
	}
	public function getSiteMap(){
		return View::make('website.sitemap');
	}
	public function search(){
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

        return View::make('website.searchresults')
                    ->with('results',$results);
    }
}
