<?php
class Survey extends CrpsController{

	public function registration($survey=null){
		$isRejectedApp=0;
		$isServiceBySurvey=0;
		$isEditByCDB=0;
		$newGeneralInfoSave=1;
		$redirectUrl=Input::get('redirectUrl');
		$view="crps.surveyregistration";
		$surveyRegistration=array(new SurveyModel());
		$surveyRegistrationAttachments=array();
		if((bool)$survey!=null && !Input::has('usercdb')){
			if(Input::has('editbyapplicant')){
				$view="crps.surveyregistration";
				if(Input::has('rejectedapplicationreapply')){
					$isRejectedApp=1;
				}
			}else{
				$view="crps.surveyeditregistrationinfo";
			}
			$surveyRegistration=SurveyModel::surveyHardList($survey)->get();
			$surveyRegistrationAttachments=SurveyAttachmentModel::attachment($survey)->get(array('Id','DocumentName','DocumentPath'));
		}
		if((bool)$survey!=null && Input::has('usercdb')){
			$isEditByCDB=1;
			$newGeneralInfoSave=0;
			$view="crps.surveyeditregistrationinfo";
			$surveyRegistration=SurveyFinalModel::surveyHardList($survey)->get();
			$surveyRegistrationAttachments=SurveyAttachmentFinalModel::attachment($survey)->get(array('Id','DocumentName','DocumentPath'));
		}
		$applicationReferenceNo=$this->tableTransactionNo('SurveyModel','ReferenceNo');
		$serviceSectorTypes=CmnListItemModel::serviceSectorType()->get(array('Id','Name'));
		$country=CountryModel::country()->get(array('Id','Name'));
		$dzongkhag=DzongkhagModel::dzongkhag()->get(array('Id','NameEn'));
		$salutation=CmnListItemModel::salutation()->get(array('Id','Name'));
		$qualification=CmnListItemModel::qualification()->get(array('Id','Name'));
		return View::make($view)
					->with('isRejectedApp',$isRejectedApp)
					->with('isEditByCDB',$isEditByCDB)
					->with('newGeneralInfoSave',$newGeneralInfoSave)
					->with('redirectUrl',$redirectUrl)
					->with('isServiceBySurvey',$isServiceBySurvey)
					->with('surveyId',$survey)
					->with('applicationReferenceNo',$applicationReferenceNo)
					->with('serviceSectorTypes',$serviceSectorTypes)
					->with('countries',$country)
					->with('dzongkhags',$dzongkhag)
					->with('salutations',$salutation)
					->with('qualifications',$qualification)
					->with('surveyRegistrations',$surveyRegistration)
					->with('surveyRegistrationAttachments',$surveyRegistrationAttachments);
	}
	public function save(){
		$save=true;
		$postedValues=Input::all();
		$isRejectedApp=Input::get('ApplicationRejectedReapply');
		$newGeneralInfoSave=Input::get('NewGeneralInfoSave');
		$isEditByCdb=Input::get('EditByCdb');
		$redirectTo=Input::get('PostBackUrl');
		$serviceBySurvey=Input::get('IsServiceBySurvey');
		$surveyId=Input::get('CrpSurveyId');
		$multiAttachments=array();
		$uuid=DB::select("select uuid() as Id");
        $generatedId=$uuid[0]->Id;
		$validation = new SurveyModel;
		if(!($validation->validate($postedValues))){
		    $errors = $validation->errors();
		    if((int)$serviceBySurvey==1){
		    	return Redirect::to('surveyor/applyrenewalregistrationdetails/'.$postedValues['CrpSurveyId'])->withInput()->withErrors($errors);
		    }
		    if(empty($postedValues["Id"])){
		    	return Redirect::to('surveyor/registration')->withInput()->withErrors($errors);
			}else{
				return Redirect::to('surveyor/registration/'.$postedValues['CrpSurveyId'])->withInput()->withErrors($errors);
			}
		}
		DB::beginTransaction();
		try{
			if(empty($postedValues["Id"])){
				$postedValues["Id"]=$generatedId;
				SurveyModel::create($postedValues);
				$appliedServiceCount=SurveyAppliedServiceModel::where('CrpSurveyId',$surveyId)->where('CmnServiceTypeId',CONST_SERVICETYPE_RENEWAL)->count();
				if((int)$serviceBySurvey==1 && $appliedServiceCount==0){
					$appliedServiceRenewal = new SurveyAppliedServiceModel;
	        		$appliedServiceRenewal->CrpSurveyId=$generatedId;
				    $appliedServiceRenewal->CmnServiceTypeId = CONST_SERVICETYPE_RENEWAL;
				    $appliedServiceRenewal->save();
				    if($postedValues['CmnServiceSectorTypeId']==CONST_CMN_SERVICESECTOR_PVT){
				    	$lateRenewalExpiryDate=SurveyFinalModel::surveyHardList($postedValues['CrpSurveyId'])->pluck('RegistrationExpiryDate');
					    $lateRenewalExpiryDate=strtotime($lateRenewalExpiryDate);
					    $currentDate=strtotime(date('Y-m-d'));
					    if($currentDate>$lateRenewalExpiryDate){
					    	$appliedServiceRenewalLateFee = new SurveyAppliedServiceModel;
					    	$appliedServiceRenewalLateFee->CrpSurveyId=$generatedId;
					    	$appliedServiceRenewalLateFee->CmnServiceTypeId = CONST_SERVICETYPE_LATEFEE;
					    	$appliedServiceRenewalLateFee->save();
					    }
					}
				}else{
					$cidNo = $postedValues['CIDNo'];
					$hasApplied = DB::table('crpsurvey')
									->where(DB::raw('coalesce(RegistrationStatus,0)'),'=',1)
									->whereRaw("coalesce(CmnApplicationRegistrationStatusId,'xx') <> ?",array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_REJECTED))
									->where('CIDNo',$cidNo)
									->count();
					$exists = DB::table('crpsurveyfinal')
								->where('CIDNo',$cidNo)
								->whereRaw("coalesce(CmnApplicationRegistrationStatusId,'xx') <> ?",array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_REJECTED))
								->count();
					$total = $hasApplied + $exists;
					if($total > 0){
						DB::rollBack();
						return Redirect::to('surveyor/registration')->with('customerrormessage','<strong>ERROR!</strong> You have already applied!');
					}
				}

			}else{
				$save=false;
				if(isset($isEditByCdb) && (int)$isEditByCdb==1 && $newGeneralInfoSave!=null && (int)$newGeneralInfoSave==0){
					$surveyReference= new SurveyFinalModel();
				}else{
					$surveyReference= new SurveyModel();
				}
				$instance=$surveyReference::find($postedValues['Id']);
				$instance->fill($postedValues);
				$instance->update();
			}
			if(Input::hasFile('attachments')){
				$count = 0;
				foreach(Input::file('attachments') as $attachment){
					$documentName = Input::get("DocumentName");
					if((bool)$attachment){
						$attachmentType=$attachment->getMimeType();
						$attachmentFileName=$attachment->getClientOriginalName();
						$attachmentName=str_random(6) . '_' . $attachment->getClientOriginalName();
						$destination=public_path().'/uploads/surveys';
						$destinationDB='uploads/surveys/'.$attachmentName;
						$multiAttachments1["DocumentName"]=(isset($documentName[$count]) && !empty($documentName[$count]))?$documentName[$count]:"Document";


						//CHECK IF IMAGE
						if(strpos($attachment->getClientMimeType(),'image/')>-1){
							$img = Image::make($attachment)->encode('jpg');
							$destinationDB = "uploads/surveys/".str_random(15) . '_min_' .".jpg";
							$img->save($destinationDB,45);
							$attachmentType = "image/jpeg";
						}else{
							$uploadAttachments=$attachment->move($destination, $attachmentName);
						}
						//
						$multiAttachments1["DocumentPath"]=$destinationDB;
						$multiAttachments1["FileType"]=$attachmentType;
						array_push($multiAttachments, $multiAttachments1);
					}
					$count++;

				}
				foreach($multiAttachments as $k=>$v){
					if(empty($postedValues['Id'])){
						if(isset($isEditByCdb) && (int)$isEditByCdb==1 && $newGeneralInfoSave!=null  && (int)$newGeneralInfoSave==0){
							$multiAttachments[$k]["CrpSurveyFinalId"]=$generatedId;
						}else{
							$multiAttachments[$k]["CrpSurveyId"]=$generatedId;
						}
					}else{
						if(isset($isEditByCdb) && (int)$isEditByCdb==1 && $newGeneralInfoSave!=null  && (int)$newGeneralInfoSave==0){
							$multiAttachments[$k]["CrpSurveyFinalId"]=$postedValues['Id'];
						}else{
							$multiAttachments[$k]["CrpSurveyId"]=$postedValues['Id'];
						}
					}
					if(isset($isEditByCdb) && (int)$isEditByCdb==1 && $newGeneralInfoSave!=null  && (int)$newGeneralInfoSave==0){
						$saveUploads=new SurveyAttachmentFinalModel($multiAttachments[$k]);
					}else{	
						$saveUploads=new SurveyAttachmentModel($multiAttachments[$k]);
					}
					$saveUploads->save();
				}
			}
		}catch(Exception $e){
			DB::rollback();
        	throw $e;
		}
		DB::commit();
		if(isset($isEditByCdb) && (int)$isEditByCdb==1){
			if((int)$serviceBySurvey==1){
				return Redirect::to('surveyor/applyrenewalconfirmation/'.$postedValues["Id"]);			
			}
			return Redirect::to($redirectTo.'/'.$postedValues["Id"])->with('savedsuccessmessage','Registration Information has been successfully updated.');
		}
		if($save){
			Session::put('SurveyRegistrationId',$generatedId);
			return Redirect::to('surveyor/confirmregistration');
		}else{
			if(isset($isRejectedApp) && (int)$isRejectedApp==1){
				Session::put('SurveyRegistrationId',$postedValues["Id"]);
				return Redirect::to('surveyor/confirmregistration');
			}
			return Redirect::to('surveyor/confirmregistration')->with('savedsuccessmessage','Registration Information has been successfully updated.');
		}
	}



	public function surveyList(){
		$parameters=array();
		$linkText='Edit';
		$link='surveyor/editdetails/';
		$surveyId=Input::get('CrpSurveyId');
		$ARNo=Input::get('ARNo');
		$surveyType=Input::get('SurveyType');

		$limit=Input::get('Limit');
		if((bool)$limit){
            if($limit != 'All'){
                $limit=" limit $limit";
            }else{
            	$limit="";
            }
        }else{
            $limit.=" limit 20";
        }

		$query="select T1.Id,T1.ReferenceNo,T1.RegistrationExpiryDate,Z.Name as Status,Z.ReferenceNo as StatusReference,T1.ARNo,T1.ApplicationDate,T1.CIDNo,T1.Name as SurveyName,T1.EmployerName,T1.Email,T1.MobileNo,T2.Name as Country,T3.NameEn as Dzongkhag,T4.Name as Salutation,T5.Id as SurveyTypeId,T5.Name as SurveyType from crpsurveyfinal T1  left join cmnlistitem Z on Z.Id = T1.CmnApplicationRegistrationStatusId  join cmncountry T2 on T1.CmnCountryId=T2.Id join cmnlistitem T4 on T1.CmnSalutationId=T4.Id join cmnlistitem T5 on T1.CmnServiceSectorTypeId=T5.Id left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id where 1=1";
		if(Route::current()->getUri()=="surveyor/viewprintlist"){
			$linkText='View/Print';
			$link='surveyor/viewprintdetails/';
			$query.=" and T1.CmnApplicationRegistrationStatusId is not null";
		}elseif(Route::current()->getUri()=="surveyor/newcomments"){
			$linkText='Add';
			$link='surveyor/newcommentsadverserecords/';
			$query.=" and (T1.CmnApplicationRegistrationStatusId!=? or T1.CmnApplicationRegistrationStatusId!=?)";
			array_push($parameters,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW);
			array_push($parameters,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_REJECTED);
		}elseif(Route::current()->getUri()=="surveyor/editcomments"){
			$linkText='View';
			$link='surveyor/editcommentsadverserecords/';
			$query.=" and (T1.CmnApplicationRegistrationStatusId!=? or T1.CmnApplicationRegistrationStatusId!=?)";
			array_push($parameters,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW);
			array_push($parameters,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_REJECTED);
		}
		if((bool)$surveyId!=NULL || (bool)$ARNo!=NULL || (bool)$surveyType!=NULL){
			if((bool)$surveyId!=NULL){
				$query.=" and T1.Id=?";
				array_push($parameters,$surveyId);
			}
			if((bool)$ARNo!=NULL){
				$query.=" and T1.ARNo=?";
	            array_push($parameters,$ARNo);
			}
			if((bool)$surveyType!=NULL){
				$query.=" and T1.CmnServiceSectorTypeId=?";
	            array_push($parameters,$surveyType);
			}
		}

	
		$surveyServiceSectorTypes=CmnListItemModel::serviceSectorType()->get(array('Id','Name'));
		$surveys=SurveyFinalModel::SurveyHardListAll()->get(array('Id','Name'));
		$surveyLists=DB::select($query." order by ARNo,SurveyName".$limit,$parameters);
		return View::make('crps.surveylist')
					->with('pageTitle','List of Surveyor')
					->with('link',$link)
					->with('linkText',$linkText)
					->with('ARNo',$ARNo)
					->with('surveyType',$surveyType)
					->with('surveyServiceSectorTypes',$surveyServiceSectorTypes)
					->with('surveys',$surveys)
					->with('surveyId',$surveyId)
					->with('surveyLists',$surveyLists);
	}


	public function editDetails($surveyId){
        $loggedInUser = Auth::user()->Id;
        $userRoles=DB::table('sysuserrolemap')->where('SysUserId',$loggedInUser)->lists('SysRoleId');
        $isAdmin = false;
        if(in_array(CONST_ROLE_ADMINISTRATOR,$userRoles)){
            $isAdmin = true;
        }
		
		$userSurvey=0;
		$surveyServiceSectorType = CONST_CMN_SERVICESECTOR_GOVT;
		$surveyInformations=SurveyFinalModel::survey($surveyId)->get(array('crpsurveyfinal.Id','crpsurveyfinal.ARNo','crpsurveyfinal.DeRegisteredDate','crpsurveyfinal.BlacklistedDate','crpsurveyfinal.RegistrationExpiryDate','crpsurveyfinal.CIDNo','crpsurveyfinal.Name','crpsurveyfinal.Gewog','crpsurveyfinal.Village','crpsurveyfinal.Email','crpsurveyfinal.MobileNo','crpsurveyfinal.EmployerName','crpsurveyfinal.EmployerAddress','crpsurveyfinal.CmnApplicationRegistrationStatusId','crpsurveyfinal.GraduationYear','crpsurveyfinal.NameOfUniversity','T1.Name as SurveyType','T2.Name as Salutation','T7.Name as Status','T3.Name as Country','T4.NameEn as Dzongkhag','T5.Name as Qualification','T6.Name as UniversityCountry','T8.Name as Trade'));
		$surveyAttachments=SurveyAttachmentFinalModel::attachment($surveyId)->get(array('Id','DocumentName','DocumentPath'));
		return View::make('crps.surveyinformation')
					->with('isAdmin',$isAdmin)
					->with('surveyServiceSectorType',$surveyServiceSectorType)
					->with('surveyId',$surveyId)
					->with('surveyInformations',$surveyInformations)
					->with('surveyAttachments',$surveyAttachments)
					
					->with('userSurvey',$userSurvey);
	}

	public function printDetails($surveyId){
		if(Route::current()->getUri()=="surveyor/viewprintdetails/{surveyid}"){
			$data['isFinalPrint']=1;
			$surveyInformations=SurveyFinalModel::survey($surveyId)->get(array('crpsurveyfinal.ARNo','crpsurveyfinal.CIDNo','crpsurveyfinal.Name','crpsurveyfinal.Gewog','crpsurveyfinal.Village','crpsurveyfinal.Email','crpsurveyfinal.MobileNo','crpsurveyfinal.EmployerName','crpsurveyfinal.EmployerAddress','crpsurveyfinal.GraduationYear','crpsurveyfinal.NameOfUniversity','T1.Name as SurveyType','T2.Name as Salutation','T3.Name as Country','T4.NameEn as Dzongkhag','T5.Name as Qualification','T6.Name as UniversityCountry','T7.Name as CurrentStatus'));
		}else{
			$data['isFinalPrint']=0;
			$surveyServiceSectorType=SurveyModel::where('Id',$surveyId)->pluck('CmnServiceSectorTypeId');
			if($surveyServiceSectorType==CONST_CMN_SERVICESECTOR_PVT){
				$feeDetails=DB::select("select 'Private' as SectorType,SurveyPvtAmount as NewRegistrationFee,SurveyPvtValidity as RegistrationValidity from crpservice where Id=? limit 1",array(CONST_SERVICETYPE_NEW));
			}else{
				$feeDetails=DB::select("select 'Goverment' as SectorType,SurveyGovtAmount as NewRegistrationFee,SurveyGovtValidity as RegistrationValidity from crpservice where Id=? limit 1",array(CONST_SERVICETYPE_NEW));
			}
			$data['feeDetails']=$feeDetails;
			$surveyInformations=SurveyModel::survey($surveyId)->get(array('crpsurvey.ARNo','crpsurvey.CIDNo','crpsurvey.Name','crpsurvey.Gewog','crpsurvey.Village','crpsurvey.Email','crpsurvey.MobileNo','crpsurvey.EmployerName','crpsurvey.EmployerAddress','crpsurvey.GraduationYear','crpsurvey.NameOfUniversity','crpsurvey.RemarksByVerifier','crpsurvey.RemarksByApprover','RemarksByPaymentApprover','T1.Name as SurveyType','T2.Name as Salutation','T3.Name as Country','T4.NameEn as Dzongkhag','T5.Name as Qualification','T6.Name as UniversityCountry','T7.Name as CurrentStatus'));
		}
		$data['printTitle']='Survey Information';
		$data['surveyInformations']=$surveyInformations;
		$pdf = App::make('dompdf');
		$pdf->loadView('printpages.surveyviewprintinformation',$data)->setPaper('a4')->setOrientation('potrait');
		return $pdf->stream();
	}

	public function newCommentAdverseRecord($surveyId){
		$survey=SurveyFinalModel::SurveyHardList($surveyId)->get(array('Id','ARNo','Name'));
		return View::make('crps.surveynewadverserecordsandcomments')
					->with('surveyId',$surveyId)
					->with('survey',$survey);	
	}

	public function editCommentAdverseRecord($surveyId){
		$survey=SurveyFinalModel::SurveyHardList($surveyId)->get(array('Id','ARNo','Name'));
		$commentsAdverseRecords=SurveyCommentAdverseRecordModel::commentAdverseRecordList($surveyId)->get(array('Id','Date','Remarks','Type'));
		return View::make('crps.surveyeditadverserecordscomments')
					->with('survey',$survey)
					->with('commentsAdverseRecords',$commentsAdverseRecords);
	}
	public function saveCommentAdverseRecord(){
		$postedValues=Input::all();
		$date=$this->convertDate(Input::get('Date'));
		$postedValues['Date']=$date;
		$validation = new SurveyCommentAdverseRecordModel;
		if(!($validation->validate($postedValues))){
		    $errors = $validation->errors();
		    return Redirect::to('surveyor/newcomments/'.$postedValues['CrpSurveyId'])->withErrors($errors)->withInput();
		}
		SurveyCommentAdverseRecordModel::create($postedValues);
		return Redirect::to('surveyor/newcomments')->with('savedsuccessmessage','Comment/Adverse Record sucessfully added.');
	}

	public function updateCommentAdverseRecord(){
		$postedValues=Input::all();
		$date=$this->convertDate(Input::get('Date'));
		$postedValues['Date']=$date;
		$instance=SurveyCommentAdverseRecordModel::find($postedValues['Id']);
		$instance->fill($postedValues);
		$instance->update();
		return Redirect::to('surveyor/editcommentsadverserecords/'.$postedValues['CrpSurveyId'])->with('savedsuccessmessage','Record has been successfully updated');;
	}

	public function deleteCommentAdverseRecord(){
    	$id = Input::get('id');
    	try{
    		DB::table('crpsurveycommentsadverserecord')->where('Id',$id)->delete();	
    		return 1;
    	}catch(Exception $e){
    		return 0;
    	}
    }
	public function blacklistDeregisterList(){
		$reRegistration=1;
		$type=3;
		$parameters=array();
		$surveyId=Input::get('CrpSurveyId');
		$ARNo=Input::get('ARNo');
		$query="select T1.Id,T1.ARNo,T1.CIDNo,T1.Name as SurveyName,T1.EmployerName,T1.Email,T1.MobileNo,T2.Name as Country,T3.NameEn as Dzongkhag,T4.Name as Salutation from crpsurveyfinal T1 join cmncountry T2 on T1.CmnCountryId=T2.Id left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id join cmnlistitem T4 on T1.CmnSalutationId=T4.Id where 1=1";
		if(Request::path()=="surveyor/deregister"){
			$type=1;
			$reRegistration=0;
			$captionHelper="Registered";
			$captionSubject="Deregistration of Surveyors";
			$query.=" and T1.CmnApplicationRegistrationStatusId=?";
			array_push($parameters,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED);
		}elseif(Request::path()=="surveyor/suspend"){
			$type=2;
			$reRegistration=0;
			$captionHelper="Registered";
			$captionSubject="Blacklisting of Surveyors";
			$query.=" and T1.CmnApplicationRegistrationStatusId=?";
			array_push($parameters,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED);
		}
		elseif(Request::path()=="surveyor/reregistration"){
			$captionHelper="Deregistered or Blacklisted";
			$captionSubject="Re-registration of Surveyors";
			$query.=" and (T1.CmnApplicationRegistrationStatusId=? or T1.CmnApplicationRegistrationStatusId=?)";
			array_push($parameters,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_DEREGISTERED);
			array_push($parameters,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_BLACKLISTED);
		}else{
			App::abort('404');
		}
		$limit=Input::get('Limit');
		if((bool)$limit){
            if($limit != 'All'){
                $limit=" limit $limit";
            }else{
            	$limit="";
            }
        }else{
            $limit.=" limit 20";
        }
		if((bool)$surveyId!=NULL || (bool)$ARNo!=NULL){
			if((bool)$surveyId!=NULL){
				$query.=" and T1.Id=?";
				array_push($parameters,$surveyId);
			}
			if((bool)$ARNo!=NULL){
				$query.=" and T1.ARNo=?";
	            array_push($parameters,$ARNo);
			}
		}
		$surveys=SurveyFinalModel::SurveyHardListAll()->get(array('Id','Name'));
		$surveyLists=DB::select($query." order by SurveyName".$limit,$parameters);
		return View::make('crps.surveyderegistrationlist')
					->with('ARNo',$ARNo)
					->with('type',$type)
					->with('surveyId',$surveyId)
					->with('captionHelper',$captionHelper)
					->with('captionSubject',$captionSubject)
					->with('reRegistration',$reRegistration)
					->with('surveys',$surveys)
					->with('surveyLists',$surveyLists);
	}
	public function deregisterBlackListRegistration(){
		$postedValues=Input::all();
		$surveyReference=$postedValues['CrpSurveyId'];
		$surveyUserId=SurveyFinalModel::where('Id',$surveyReference)->pluck('SysUserId');

		DB::beginTransaction();
		try{
			if(Input::has('DeRegisteredDate')){
				$postedValues['DeRegisteredDate']=$this->convertDate($postedValues['DeRegisteredDate']);
			}elseif(Input::has('BlacklistedDate')){
				$postedValues['BlacklistedDate']=$this->convertDate($postedValues['BlacklistedDate']);
			}else{
				$postedValues['ReRegistrationDate']=$this->convertDate($postedValues['ReRegistrationDate']);
			}
			$instance=SurveyFinalModel::find($surveyReference);
			$instance->fill($postedValues);
			$instance->update();
			$userInstance=User::find($surveyUserId);

			if($postedValues['CmnApplicationRegistrationStatusId']==CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED){
				$redirectRoute="reregistration";
				if((bool)$userInstance){
					$userInstance->Status=1;
					$userInstance->save();
				}

			}else{
				if(Input::has('BlacklistedRemarks')){
					$redirectRoute="suspend";
				}else{
					$redirectRoute="deregister";
				}
				if((bool)$userInstance){
					$userInstance->Status=0;
					$userInstance->save();
				}
				/*---Insert Adverse Record i.e the remarks if the consultant is deregistered/blacklisted*/
				if(Input::has('BlacklistedRemarks')){
					$surveyAdverserecordInstance = new SurveyCommentAdverseRecordModel;
					$surveyAdverserecordInstance->CrpSurveyFinalId = $surveyReference;
					$surveyAdverserecordInstance->Date=date('Y-m-d');
					$surveyAdverserecordInstance->Remarks=Input::get('BlacklistedRemarks');
					$surveyAdverserecordInstance->Type=2;
					$surveyAdverserecordInstance->save();
				}else{
					$surveyAdverserecordInstance = new SurveyCommentAdverseRecordModel;
					$surveyAdverserecordInstance->CrpSurveyFinalId = $surveyReference;
					$surveyAdverserecordInstance->Date=date('Y-m-d');
					$surveyAdverserecordInstance->Remarks=Input::get('DeregisteredRemarks');
					$surveyAdverserecordInstance->Type=2;
					$surveyAdverserecordInstance->save();
				}	
			}
		}catch(Exception $e){
			DB::rollback();
        	throw $e;
		}
		DB::commit();
		
		return Redirect::to('surveyor/'.$redirectRoute)->with('savedsuccessmessage','Successfully updated');
	}
	

   
}