<?php
class WebTrainingController extends BaseController{
	public function addTrainingForm($id = NULL){
		if((bool)$id==NULL){
			$trainingDetails= array(new WebTrainingModel());
		}else{
			$trainingDetails=DB::select("select Id,TrainingTypeId,MaxParticipants,ContractorsExpiryDate,TrainingTitle,TrainingDescription,StartDate,EndDate,TrainingVenue,TrainingTime,ContactPerson,Hotline,LastDateForRegistration,ShowInMarquee from webtrainingdetails where Id=?",array($id));
		}
		$trainingType = DB::select("select Id,TrainingType,ReferenceNo from webtrainingtype order by TrainingType");
		return View::make('website.addtraining')
					->with('trainingType',$trainingType)
					->with('trainingDetails',$trainingDetails);
	}
	public function addTrainingDetails(){
		$postedValues=Input::all();
		$postedValues['StartDate']=$this->convertDate($postedValues['StartDate']);
		$postedValues['EndDate']=$this->convertDate($postedValues['EndDate']);
		$postedValues['LastDateForRegistration']=$this->convertDate($postedValues['LastDateForRegistration']);
		if(Input::has('ContractorsExpiryDate'))
		    $postedValues['ContractorsExpiryDate']=$this->convertDate($postedValues['ContractorsExpiryDate']);
		$validation = new WebTrainingModel;
		if(!($validation->validate($postedValues))){
		    $errors = $validation->errors();
		    if(empty($postedValues['Id'])){
		    	return Redirect::to('web/addtrainingform')->withInput()->withErrors($errors);
			}else{
				return Redirect::to('web/addtrainingform/'.$postedValues['Id'])->withInput()->withErrors($errors);
			}
		}
        DB::beginTransaction();
		try{
			if(empty($postedValues['Id'])){
				$uuid=DB::select("select uuid() as Id");
        		$generatedId=$uuid[0]->Id;
				$postedValues["Id"]=$generatedId;
				WebTrainingModel::create($postedValues);
				$message="Training Details successfully saved.";
			}else{
				$instance=WebTrainingModel::find($postedValues['Id']);
				$instance->fill($postedValues);
				$instance->update();
				$message="Training Details successfully updated.";
			}
		}catch(Exception $e){
			DB::rollback();
        	throw $e;
		}
		DB::commit();
		return Redirect::to('web/addtrainingform')->with('savedsuccessmessage',$message);	
	}
	public function viewTrainings(){
		$slno=1;
		$parameters = array();
		$listOfTraining = "select T1.Id as TrainingId,T1.ShowInMarquee,T1.MaxParticipants,T1.TrainingTitle,T1.TrainingDescription, T1.StartDate, T1.EndDate, T1.LastDateForRegistration, T2.TrainingType,T2.ReferenceNo from webtrainingdetails T1 join webtrainingtype T2 on T1.TrainingTypeId = T2.Id";
		$listOfTraining = DB::select("$listOfTraining order by T1.StartDate asc",$parameters);
		return View::make('website.viewtrainings')
					->with('listOfTraining',$listOfTraining)
					->with('slno',$slno);
	}

	public function editTrainingDetails($id){
		$listOfTraining = DB::select("select T1.Id as TrainingId, T1.TrainingTypeId, T1.TrainingTitle, T1.StartDate, T1.EndDate, T1.LastDateForRegistration, T1.ContactPerson, T1.Hotline, T1.TrainingVenue, T1.TrainingTime, T2.TrainingType from webtrainingdetails T1 join webtrainingtype T2 on T1.TrainingTypeId = T2.Id where T1.Id = ?",array($id));
		$trainingType = DB::select("select * from webtrainingtype order by TrainingType asc");

		return View::make('website.edittrainingdetails')
					->with('listOfTraining',$listOfTraining)
					->with('trainingType',$trainingType);
	}

	public function updateTrainingDetails(){
		$trainingId = Input::get('TrainingIdOld');

		if(Input::get('TrainingType') != "---SELECT ONE---") {
			$typeId = Input::get('TrainingType');
		}
		else{
			$typeId = Input::get('TypeIdOld');
		}

		if(Input::get('TrainingTitle') != NULL) {
			$title = Input::get('TrainingTitle');
		}
		else{
			$title = Input::get('TitleOld');
		}

		if(Input::get('TrainingStartDate') != NULL) {
			$startDate = Input::get('TrainingStartDate');
			$startDate = date("Y-m-d", strtotime($startDate));
		}
		else{
			$startDate = Input::get('StartDateOld');
			
		}

		if(Input::get('TrainingEndDate') != NULL) {
			$endDate = Input::get('TrainingEndDate');
			$endDate = date("Y-m-d", strtotime($endDate));
		}
		else{
			$endDate = Input::get('EndDateOld');
			$endDate = date("Y-m-d", strtotime($endDate));
		}

		if(Input::get('TrainingVenue') != NULL) {
			$venue = Input::get('TrainingVenue');
		}
		else{
			$venue = Input::get('venueOld');
		}

		if(Input::get('TrainingTime') != NULL) {
			$time = Input::get('TrainingTime');
		}
		else{
			$time = Input::get('TimeOld');
		}

		if(Input::get('ContactPerson') != NULL) {
			$contactPerson = Input::get('ContactPerson');
		}
		else{
			$contactPerson = Input::get('ContactPersonOld');
		}

		if(Input::get('Hotline') != NULL) {
			$hotline = Input::get('Hotline');
		}
		else{
			$hotline = Input::get('HotlineOld');
		}

		if(Input::get('LastDateForRegistration') != NULL) {
			$lastDateForRegistration = Input::get('LastDateForRegistration');
			$lastDateForRegistration = date("Y-m-d", strtotime($lastDateForRegistration));
		}
		else{
			$lastDateForRegistration = Input::get('LastDateForRegistrationOld');
			$lastDateForRegistration = date("Y-m-d", strtotime($lastDateForRegistration));
		}

		$updateCheck = DB::update("update webtrainingdetails set TrainingTypeId = ?, TrainingTitle = ?, StartDate = ?, EndDate = ?, TrainingVenue = ?, TrainingTime = ?, ContactPerson = ?, Hotline = ?, LastDateForRegistration = ? where Id = ?", array($typeId, $title, $startDate, $endDate, $venue, $time, $contactPerson, $hotline, $lastDateForRegistration, $trainingId));

		return Redirect::to('web/viewedittrainings');
	}


	public function listOfTrainings(){
		$slno=1;
		$listOfTraining = DB::select("select T1.Id as TrainingId, T1.TrainingTitle,T1.TrainingDescription, T1.StartDate, T1.EndDate, T1.LastDateForRegistration, T2.TrainingType from webtrainingdetails T1 join webtrainingtype T2 on T1.TrainingTypeId = T2.Id order by T1.CreatedOn desc");		return View::make('website.listoftrainings')
					->with('listOfTraining',$listOfTraining)
					->with('slno',$slno);
	}

	public function viewAdminTrainingDetails($id){
		$listOfTraining = DB::select("select T1.Id as TrainingId, T1.TrainingTypeId, T1.TrainingTitle, T1.StartDate, T1.EndDate, T1.LastDateForRegistration, T1.ContactPerson, T1.Hotline, T1.TrainingVenue, T1.TrainingTime, T2.TrainingType from webtrainingdetails T1 join webtrainingtype T2 on T1.TrainingTypeId = T2.Id where T1.Id = ?",array($id));
		return View::make('website.viewadmintrainingdetails')
					->with('listOfTraining',$listOfTraining);
	}

	public function registeredForTraining($id){
		$type=Input::get('ref');
		$exportType=Input::get('export');
		if((int)$type==1){
			$listOfRegisteredTraniees = DB::select("select T1.venue,T1.FullName,T1.CreatedOn,T1.FilePath, T1.CIDNoOfParticipant,T1.Email, T1.ContactNo,T1.Agency, T1.CDBNo,T1.NameOfFirm, T1.Designation, T1.Department, T2.Code as ClassCode, T2.Name as ClassName,T2.Priority, T3.TrainingTitle from webregisteredfortraining T1 join webtrainingdetails T3 on T1.WebTrainingDetailsId = T3.Id join cmncontractorclassification T2 on T1.CmnContractorClassificationId = T2.Id where T1.WebTrainingDetailsId = ? order by T1.CreatedOn",array($id));
		}elseif((int)$type == 8){
			$listOfRegisteredTraniees = DB::select("select T1.venue,T1.FullName,T1.CDBNo,Z.Code as ClassCode,X.NameOfFirm,T1.CreatedOn,T1.FilePath,T1.CIDNoOfParticipant,T1.Qualification,T1.Email, T1.ContactNo,T1.Agency, T1.Designation,T3.TrainingTitle from webregisteredfortraining T1 join (crpcontractorfinal X join viewcontractormaxclassification Y on X.Id = Y.CrpContractorFinalId join cmncontractorclassification Z on Z.Priority = Y.MaxClassificationPriority) on X.CDBNo = T1.CDBNo join webtrainingdetails T3 on T1.WebTrainingDetailsId = T3.Id where T1.WebTrainingDetailsId = ? order by T1.CreatedOn",array($id));
		}else{
			$listOfRegisteredTraniees = DB::select("select T1.venue,T1.FullName,T1.CreatedOn,T1.FilePath,T1.CIDNoOfParticipant,T1.Email, T1.ContactNo,T1.Agency, T1.Designation,T3.TrainingTitle from webregisteredfortraining T1 join webtrainingdetails T3 on T1.WebTrainingDetailsId = T3.Id where T1.WebTrainingDetailsId = ? order by T1.CreatedOn",array($id));
		}
		$tranings=DB::select("select Id,TrainingTitle,TrainingDescription,StartDate,EndDate,TrainingVenue from webtrainingdetails where Id=?",array($id));
		if((bool)$exportType==null){
			return View::make('website.registeredfortraining')
						->with('tranings',$tranings)
						->with('listOfRegisteredTraniees',$listOfRegisteredTraniees);
		}else{
			$data['printTitle']='Registered Trainees';
			$data['listOfRegisteredTraniees']=$listOfRegisteredTraniees;
			$data['tranings']=$tranings;
			$pdf = App::make('dompdf');
			$pdf->loadView('printpages.printregisteredtraniees',$data)->setPaper('a4')->setOrientation('landscape');
			return $pdf->stream();
		}
	}
	public function viewTrainingDetails($id){
		$type = Input::get('type');

		if((string)$type == 'adv'){
			$details = DB::table('webadvertisements')
						->where('Id',$id)
						->get(array('Title','Content','Image'));
			$view = "website.viewadvertisementdetails";
		}else{
			$details = DB::select("select T1.Id as TrainingId,T1.MaxParticipants,ContractorsExpiryDate, T1.TrainingTypeId, 
			T1.TrainingTitle,
			T1.TrainingDescription, T1.StartDate, T1.EndDate, T1.LastDateForRegistration, T1.ContactPerson,
			 T1.Hotline, T1.TrainingVenue, T1.TrainingTime, T2.TrainingType,T2.ReferenceNo from webtrainingdetails T1 join webtrainingtype T2 on T1.TrainingTypeId = T2.Id where T1.Id = ?",array($id));
			$view = "website.viewtrainingdetails";
		}

		$contractorClassificationId = ContractorClassificationModel::classification()->orderBy('Priority')->get(array('Id','Code','Name'));
		return View::make($view)
					->with('details',$details)
					->with('contractorClassificationId',$contractorClassificationId);
	}
	public function registrationForTraining(){
		$postedValues=Input::except('Attachment');
		$attachment = Input::file('Attachment');
		$trainingId = Input::get('WebTrainingDetailsId');
		$count = DB::table('webregisteredfortraining')->where('WebTrainingDetailsId',$trainingId)->count();
		$max = DB::table('webtrainingdetails')->where('Id',$trainingId)->pluck('MaxParticipants');
		if((bool)$max){
		    if($max <= $count){
                return Redirect::to('web/listoftrainings')->with('customerrormessage',"This training is limited to $max participants. $max participants have already registered");
            }
        }
		try{
			$uuid=DB::select("select uuid() as Id");
			$generatedId=$uuid[0]->Id;
			$postedValues["Id"]=$generatedId;

			if($attachment!=NULL){
				$documentName = "Nomination Letter";
				$attachmentType=$attachment->getMimeType();
				$attachmentFileName=$attachment->getClientOriginalName();
				$attachmentName=str_random(6) . '_' . $attachment->getClientOriginalName();
				$destination=public_path().'/uploads/trainings';
				$destinationDB='uploads/trainings/'.$attachmentName;

				//CHECK IF IMAGE
				if(strpos($attachment->getClientMimeType(),'image/')>-1){
					$img = Image::make($attachment)->encode('jpg');
					$destinationDB = "uploads/trainings/".str_random(15) . '_min_' .".jpg";
					$img->save($destinationDB,45);
					$attachmentType = "image/jpeg";
				}else{
					$uploadAttachments=$attachment->move($destination, $attachmentName);
				}
				$postedValues['FilePath'] = $destinationDB;
				//
			}

			RegisteredForTrainingModel::create($postedValues);
		}catch(Exception $e){
		    
			return Redirect::to('web/listoftrainings')->with('customerrormessage','You have already registered for the training.');
		}
		$message = "You have been successfully registered for the training.";
		$mailData=array(
			'mailMessage'=>$message
		);
		$this->sendSms($message,$postedValues['ContactNo']);
		$this->sendEmailMessage('emails.crps.mailnoticebyadministrator',$mailData,"Successfully Registered",$postedValues['Email'],$postedValues['FullName']);
		return Redirect::to('web/listoftrainings')->with('savedsuccessmessage','You have successfully registered for the training. Please check your email address provided along with this registration for more information.');
	}
	public function postCheckCIDNo(){
		$cidNo = Input::get('no');
		$trainingId = Input::get('id');

		$count = DB::table('webregisteredfortraining')->where('CIDNoOfParticipant',trim($cidNo))->where('WebTrainingDetailsId',$trainingId)->count();
		if($count>0){
			return Response::json(array('passed'=>false));
		}else{
			return Response::json(array('passed'=>true));
		}
	}
	public function postCheckCDBNo(){
		$cdbNo = Input::get('no');
		$trainingId = Input::get('id');

		$count = DB::table('webregisteredfortraining')->where('CDBNo',trim($cdbNo))->where('WebTrainingDetailsId',$trainingId)->count();
		if($count>0){
			return Response::json(array('passed'=>false));
		}else{
			return Response::json(array('passed'=>true));
		}
	}
	public function fetchDetails(){
        $cdbNo = Input::get('cdb');
        $trainingId = Input::get('trainingId');
        $trainingExpiryLimit = DB::table('webtrainingdetails')->where('Id',$trainingId)->pluck('ContractorsExpiryDate');
        $details = DB::table('crpcontractorfinal as T1')
                        ->join('viewcontractormaxclassification as T2','T2.CrpContractorFinalId','=','T1.Id')
                        ->join('cmncontractorclassification as T3','T3.Priority','=','T2.MaxClassificationPriority')
                        ->where('T1.CDBNo',trim($cdbNo))
                        ->select(DB::raw("concat(T1.NameOfFirm) as Firm"),'T3.Code as Class','T1.RegistrationExpiryDate')->get();

        $valid = true;
		$message = '';
        if(count($details)>0 && (bool)$trainingExpiryLimit){
            $expiryDate = $details[0]->RegistrationExpiryDate;
            $expiryDate = new DateTime($expiryDate);
            $trainingExpiryLimit = new DateTime($trainingExpiryLimit);
            if($expiryDate>$trainingExpiryLimit){
                $valid = false;
				$message = "You cannot register as the training is only for the contractors whose CDB expire on or before ".convertDateToClientFormat(DB::table('webtrainingdetails')->where('Id',$trainingId)->pluck('ContractorsExpiryDate'));
            }
            $details[0]->Valid = $valid;
			
        }
        if(count($details)==0){
            $details = false;
        }else{
            if(!(bool)$trainingExpiryLimit){
                $details[0]->Valid = $valid;
            }
			$details[0]->message = $message;
        }
        return Response::json($details);
    }
}
