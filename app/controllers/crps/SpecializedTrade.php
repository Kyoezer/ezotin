<?php
class SpecializedTrade extends CrpsController{
	public function defaultIndex(){
		$feeDetails=DB::select("select SpecializedTradeValidity from crpservice where Id=? limit 1",array(CONST_SERVICETYPE_NEW));
		return View::make('crps.specializedtradeindex')
					->with('feeDetails',$feeDetails);
	}
	public function category(){
		$categories=SpecializedTradeCategoryModel::category()->get(array('Id','Code','Name'));
		$categoryId=Input::get('sref');
        if((bool)$categoryId==NULL || empty($categoryId)){
            $editCategories=array(new SpecializedTradeCategoryModel());
        }else{
            $editCategories=SpecializedTradeCategoryModel::category()->where('Id',$categoryId)->get(array('Id','Code','Name'));
        }
		return View::make('crps.specializedtradecategory')
					->with('categories',$categories)
					->with('editCategories',$editCategories);
	}
	public function saveCategory(){
		$postedValues=Input::all();
		$validation = new SpecializedTradeCategoryModel;
		if(!($validation->validate($postedValues))){
		    $errors = $validation->errors();
		    return Redirect::to('master/specializedtradecategory')->withErrors($errors)->withInput();
		}
		DB::beginTransaction();
		try{
			if(empty($postedValues["Id"])){
				SpecializedTradeCategoryModel::create($postedValues);
				$message='Category has been successfully saved';
			}else{
				$instance=SpecializedTradeCategoryModel::find($postedValues['Id']);
				$instance->fill($postedValues);
				$instance->update();
				$message='Category has been successfully updated';
			}
		}catch(Exception $e){
			DB::rollback();
        	throw $e;
        	//return Redirect::to('master/specializedtradecategory')->withErrors($e->getErrors())->withInput();
		}
		DB::commit();
		return Redirect::to('master/specializedtradecategory')->with('savedsuccessmessage',$message);
	}
	public function registration($specializedTrade=null){
		$isRejectedApp=0;
		$isServiceBySpecializedTrade=0;
		$isEditByCDB=0;
		$newGeneralInfoSave=1;
		$redirectUrl=Input::get('redirectUrl');
		$view="crps.specializedtraderegistration";
		$categories=SpecializedTradeCategoryModel::category()->select(DB::raw('Id as CategoryId,Code,Name,NULL as CmnAppliedCategoryId'))->get();
		$specializedtradeRegistration=array(new SpecializedTradeModel());
		$specializedtradeRegistrationAttachments=array();
		$editWorkClassificationsByCDB=array();
		if((bool)$specializedTrade!=null && !Input::has('usercdb')){
			if(Input::has('editbyapplicant')){
				$view="crps.specializedtraderegistration";
				if(Input::has('rejectedapplicationreapply')){
					$isRejectedApp=1;
				}
			}else{
				$editWorkClassificationsByCDB=DB::select("select distinct T1.Id as CategoryId,T1.Code,T1.Name,T2.CmnAppliedCategoryId,T3.CmnVerifiedCategoryId,T4.CmnApprovedCategoryId from cmnspecializedtradecategory T1 left join crpspecializedtradeworkclassification T2 on T1.Id=T2.CmnAppliedCategoryId and T2.CrpSpecializedTradeId=? left join crpspecializedtradeworkclassification T3 on T1.Id=T3.CmnVerifiedCategoryId and T3.CrpSpecializedTradeId=? left join crpspecializedtradeworkclassification T4 on T1.Id=T4.CmnApprovedCategoryId and T4.CrpSpecializedTradeId=? where T1.Code like '%SP%' order by T1.Code,T1.Name",array($specializedTrade,$specializedTrade,$specializedTrade));
				$view="crps.specializedtradeeditregistrationinfo";
			}
			$editWorkClassificationByCDB=array();
			$categories=DB::select("select T1.Id as CategoryId,T1.Code,T1.Name,T2.CmnAppliedCategoryId from cmnspecializedtradecategory T1 left join crpspecializedtradeworkclassification T2 on T1.Id=T2.CmnAppliedCategoryId and T2.CrpSpecializedTradeId=? order by T1.Code,T1.Name",array($specializedTrade));
			$specializedtradeRegistration=SpecializedTradeModel::specializedtradeHardList($specializedTrade)->get();
			$specializedtradeRegistrationAttachments=SpecializedTradeAttachmentModel::attachment($specializedTrade)->get(array('Id','DocumentName','DocumentPath'));
		}
		if((bool)$specializedTrade!=null && Input::has('usercdb')){
			$isEditByCDB=1;
			$newGeneralInfoSave=0;
			$view="crps.specializedtradeeditregistrationinfo";
			$specializedtradeRegistration=SpecializedTradeFinalModel::specializedtradeHardList($specializedTrade)->get();
			$specializedtradeRegistrationAttachments=SpecializedTradeAttachmentFinalModel::attachment($specializedTrade)->get(array('Id','DocumentName','DocumentPath'));
			$editWorkClassificationsByCDB=DB::select("select distinct T1.Id as CategoryId,T1.Code,T1.Name,T2.CmnAppliedCategoryId,T3.CmnVerifiedCategoryId,T4.CmnApprovedCategoryId from cmnspecializedtradecategory T1 left join crpspecializedtradeworkclassificationfinal T2 on T1.Id=T2.CmnAppliedCategoryId and T2.CrpSpecializedTradeFinalId=? left join crpspecializedtradeworkclassificationfinal T3 on T1.Id=T3.CmnVerifiedCategoryId and T3.CrpSpecializedTradeFinalId=? left join crpspecializedtradeworkclassificationfinal T4 on T1.Id=T4.CmnApprovedCategoryId and T4.CrpSpecializedTradeFinalId=? where T1.Code like '%SP%' order by T1.Code,T1.Name",array($specializedTrade,$specializedTrade,$specializedTrade));
		}
		$applicationReferenceNo=$this->tableTransactionNo('SpecializedTradeModel','ReferenceNo');
		$country=CountryModel::country()->get(array('Id','Name'));
		$dzongkhag=DzongkhagModel::dzongkhag()->get(array('Id','NameEn'));
		$salutation=CmnListItemModel::salutation()->get(array('Id','Name'));
		$qualification=CmnListItemModel::qualification()->get(array('Id','Name'));
		return View::make($view)
					->with('isRejectedApp',$isRejectedApp)
					->with('isEditByCDB',$isEditByCDB)
					->with('newGeneralInfoSave',$newGeneralInfoSave)
					->with('isServiceBySpecializedTrade',$isServiceBySpecializedTrade)
					->with('redirectUrl',$redirectUrl)
					->with('specializedTradeId',$specializedTrade)
					->with('applicationReferenceNo',$applicationReferenceNo)
					->with('dzongkhags',$dzongkhag)
					->with('salutations',$salutation)
					->with('qualifications',$qualification)
					->with('specializedtradeRegistrations',$specializedtradeRegistration)
					->with('specializedtradeRegistrationAttachments',$specializedtradeRegistrationAttachments)
					->with('categories',$categories)
					->with('editWorkClassificationsByCDB',$editWorkClassificationsByCDB);
	}
	public function save(){
		$save=true;
		$postedValues=Input::all();
		$isRejectedApp=Input::get('ApplicationRejectedReapply');
		$newGeneralInfoSave=Input::get('NewGeneralInfoSave');
		$serviceBySpecializedTrade=Input::get('IsServiceBySpecializedTrade');
		$isEditByCdb=Input::get('EditByCdb');
		$redirectTo=Input::get('PostBackUrl');
		$specializedTradeId=Input::get('CrpSpecializedTradeId');
		$multiAttachments=array();
		$uuid=DB::select("select uuid() as Id");
        $generatedId=$uuid[0]->Id;
		$validation = new SpecializedTradeModel;
		if(!($validation->validate($postedValues))){
		    $errors = $validation->errors();
		    if((int)$serviceBySpecializedTrade==1){
		    	return Redirect::to('specializedtrade/applyrenewalregistrationdetails/'.$specializedTradeId)->withInput()->withErrors($errors);
		    }
		    if(empty($postedValues["Id"])){
		    	return Redirect::to('specializedtrade/registration')->withInput()->withErrors($errors);
			}else{
				return Redirect::to('specializedtrade/registration/'.$specializedTradeId)->withInput()->withErrors($errors);
			}
		}
		DB::beginTransaction();
		try{
			if(empty($postedValues["Id"])){
				$postedValues["Id"]=$generatedId;
				SpecializedTradeModel::create($postedValues);
				if((int)$serviceBySpecializedTrade==1){
					$appliedServiceRenewal = new SpecializedTradeAppliedServiceModel;
	        		$appliedServiceRenewal->CrpSpecializedTradeId=$generatedId;
				    $appliedServiceRenewal->CmnServiceTypeId = CONST_SERVICETYPE_RENEWAL;
				    $appliedServiceRenewal->save();
				    $countRenewalApplications=SpecializedTradeAppliedServiceModel::serviceRenewalCount($generatedId)->count();
				    if($countRenewalApplications>=1){
				    	$lateRenewalExpiryDate=SpecializedTradeFinalModel::specializedTradeHardList($postedValues['CrpSpecializedTradeId'])->pluck('RegistrationExpiryDate');
					    $lateRenewalExpiryDate=strtotime($lateRenewalExpiryDate);
					    $currentDate=strtotime(date('Y-m-d'));
					    if($currentDate>$lateRenewalExpiryDate){
					    	$appliedServiceRenewalLateFee = new SpecializedTradeAppliedServiceModel;
					    	$appliedServiceRenewalLateFee->CrpSpecializedTradeId=$generatedId;
					    	$appliedServiceRenewalLateFee->CmnServiceTypeId = CONST_SERVICETYPE_LATEFEE;
					    	$appliedServiceRenewalLateFee->save();
					    }
					}
				}
			}else{
				$save=false;
				$generatedId=$postedValues['Id'];
				if(isset($isEditByCdb) && (int)$isEditByCdb==1 && $newGeneralInfoSave!=null && (int)$newGeneralInfoSave==0){
					$specializedTradeReference= new SpecializedTradeFinalModel();
					SpecializedTradeWorkClassificationFinalModel::where('CrpSpecializedTradeFinalId',$postedValues['Id'])->delete();
				}else{
					$specializedTradeReference= new SpecializedTradeModel();
					SpecializedTradeWorkClassificationModel::where('CrpSpecializedTradeId',$postedValues['Id'])->delete();
				}
				$instance=$specializedTradeReference::find($postedValues['Id']);
				$instance->fill($postedValues);
				$instance->update();
			}
			/*------------------------------Saving Work Classification--------------------------------------*/
			if(Input::has('CmnEditCategoryByCDB')){
				$appliedCategory=Input::get('CmnAppliedCategoryId');
				for($idx = 0; $idx < count($appliedCategory); $idx++){
					if(isset($isEditByCdb) && (int)$isEditByCdb==1 && $newGeneralInfoSave!=null && (int)$newGeneralInfoSave==0){
						$category = new SpecializedTradeWorkClassificationFinalModel;
						$category->CrpSpecializedTradeFinalId=$generatedId;
					}else{
						$category = new SpecializedTradeWorkClassificationModel;
						$category->CrpSpecializedTradeId=$generatedId;
					}
				    $category->CmnAppliedCategoryId = $postedValues['CmnAppliedCategoryId'][$idx];
				    $category->CmnVerifiedCategoryId = $postedValues['CmnVerifiedCategoryId'][$idx];
				    $category->CmnApprovedCategoryId = $postedValues['CmnApprovedCategoryId'][$idx];
			    	$category->save();
				}
			}else{
				$appliedCategory=Input::get('CmnAppliedCategoryId');
				for($idx = 0; $idx < count($appliedCategory); $idx++){
				    if(isset($isEditByCdb) && (int)$isEditByCdb==1 && $newGeneralInfoSave!=null && (int)$newGeneralInfoSave==0){
						$category = new SpecializedTradeWorkClassificationFinalModel;
						$category->CrpSpecializedTradeFinalId=$generatedId;
					}else{
						$category = new SpecializedTradeWorkClassificationModel;
						$category->CrpSpecializedTradeId=$generatedId;
					}
				    $category->CmnAppliedCategoryId = $postedValues['CmnAppliedCategoryId'][$idx];
				    $category->save();
				}
			}
			/*---------------------------End of saving work classification---------------------------------*/
			if(Input::hasFile('attachments')){
				$count = 0;
				foreach(Input::file('attachments') as $attachment){
					$documentName = Input::get("DocumentName");
					$attachmentType=$attachment->getMimeType();
					$attachmentFileName=$attachment->getClientOriginalName();
					$attachmentName=str_random(6) . '_' . $attachment->getClientOriginalName();
					$destination=public_path().'/uploads/specializedtrades';
					$destinationDB='uploads/specializedtrades/'.$attachmentName;
					$multiAttachments1["DocumentName"]=$documentName[$count];

					//CHECK IF IMAGE
					if(strpos($attachment->getClientMimeType(),'image/')>-1){
						$img = Image::make($attachment)->encode('jpg');
						$destinationDB = "uploads/specializedtrades/".str_random(15) . '_min_' .".jpg";
						$img->save($destinationDB,45);
						$attachmentType = "image/jpeg";
					}else{
						$uploadAttachments=$attachment->move($destination, $attachmentName);
					}
					//

					$multiAttachments1["DocumentPath"]=$destinationDB;
					$multiAttachments1["FileType"]=$attachmentType;
					array_push($multiAttachments, $multiAttachments1);

					$count++;
				}
				foreach($multiAttachments as $k=>$v){
					if(empty($postedValues['Id'])){
						if(isset($isEditByCdb) && (int)$isEditByCdb==1 && $newGeneralInfoSave!=null  && (int)$newGeneralInfoSave==0){
							$multiAttachments[$k]["CrpSpecializedTradeFinalId"]=$generatedId;
						}else{
							$multiAttachments[$k]["CrpSpecializedTradeId"]=$generatedId;
						}
					}else{
						if(isset($isEditByCdb) && (int)$isEditByCdb==1 && $newGeneralInfoSave!=null  && (int)$newGeneralInfoSave==0){
							$multiAttachments[$k]["CrpSpecializedTradeFinalId"]=$postedValues['Id'];
						}else{
							$multiAttachments[$k]["CrpSpecializedTradeId"]=$postedValues['Id'];
						}
					}
					if(isset($isEditByCdb) && (int)$isEditByCdb==1 && $newGeneralInfoSave!=null  && (int)$newGeneralInfoSave==0){
						$saveUploads=new SpecializedTradeAttachmentFinalModel($multiAttachments[$k]);
					}else{	
						$saveUploads=new SpecializedTradeAttachmentModel($multiAttachments[$k]);
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
			if((int)$serviceBySpecializedTrade==1){
				return Redirect::to('specializedtrade/applyrenewalconfirmation/'.$postedValues["Id"]);			
			}
			return Redirect::to($redirectTo.'/'.$postedValues["Id"])->with('savedsuccessmessage','Registration Information has been successfully updated.');
		}
		if($save){
			Session::put('SpecializedTradeRegistrationId',$generatedId);
			return Redirect::to('specializedtrade/confirmregistration');
		}else{
			if(isset($isRejectedApp) && (int)$isRejectedApp==1){
				Session::put('SpecializedTradeRegistrationId',$postedValues["Id"]);
				return Redirect::to('specializedtrade/confirmregistration');
			}
			return Redirect::to('specializedtrade/confirmregistration')->with('savedsuccessmessage','Registration Information has been successfully updated.');
		}
	}
	public function confirmRegistration(){
		if(Session::has('SpecializedTradeRegistrationId')){
			$specializedTradeId=Session::get('SpecializedTradeRegistrationId');
		}else{
			return Redirect::to('specializedtrade/registration');
		}
		$categories=SpecializedTradeCategoryModel::category()->get(array('Id','Code','Name'));
		$specializedTradeInformations=SpecializedTradeModel::specializedTrade($specializedTradeId)->get(array('crpspecializedtrade.Id','crpspecializedtrade.ReferenceNo','crpspecializedtrade.ApplicationDate','crpspecializedtrade.CIDNo','crpspecializedtrade.Name','crpspecializedtrade.Gewog','crpspecializedtrade.Village','crpspecializedtrade.Email','crpspecializedtrade.MobileNo','crpspecializedtrade.TelephoneNo','crpspecializedtrade.EmployerName','crpspecializedtrade.EmployerAddress','T1.Name as Salutation','T2.NameEn as Dzongkhag'));
		$specializedTradeAttachments=SpecializedTradeAttachmentModel::attachment($specializedTradeId)->get(array('Id','DocumentName','DocumentPath'));
		$specializedTradeWorkClassifications=SpecializedTradeWorkClassificationModel::specializedTradeWorkClassification($specializedTradeId)->get(array('crpspecializedtradeworkclassification.CmnAppliedCategoryId as WorkClassificationId'));
		return View::make('crps.specializedtraderegistrationconfirmation')
					->with('categories',$categories)
					->with('specializedTradeId',$specializedTradeId)
					->with('specializedTradeInformations',$specializedTradeInformations)
					->with('specializedTradeAttachments',$specializedTradeAttachments)
					->with('specializedTradeWorkClassifications',$specializedTradeWorkClassifications);
	}
	public function saveConfirmation(){
		$isServiceBySpecializedTrade=Input::get('IsServiceBySpecializedTrade');
		$specializedTradeId=Input::get('CrpSpecializedTradeId');
		$instance = SpecializedTradeModel::find($specializedTradeId);
		$instance->RegistrationStatus=1;
		$instance->CmnApplicationRegistrationStatusId=CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW;
		$instance->save();
		$specializedTradeDetails=SpecializedTradeModel::specializedTradeHardList($specializedTradeId)->get(array('Name','Email','ReferenceNo','ApplicationDate','MobileNo'));
		$mailIntendedTo=NULL;
		$mailView="emails.crps.mailregistrationsuccess";
		$recipientAddress=$specializedTradeDetails[0]->Email;
		$recipientName=$specializedTradeDetails[0]->Name;
		$referenceNo=$specializedTradeDetails[0]->ReferenceNo;
		$applicationDate=$specializedTradeDetails[0]->ApplicationDate;
		$mobileNo=$specializedTradeDetails[0]->MobileNo;
		if((int)$isServiceBySpecializedTrade==0){
			$subject="Acknowledgement: Receipt of Application for Registration with CDB";
			$emailMessage="This is to acknowledge receipt of your application for registration of specialized trade with Construction Development Board (CDB). Your application will be processed in due course. You can check status of your application using your Citizenship Id No. or Application No. from the <a href=”#”>CDB website</a>. You will also be notified through email when your application is approved.";
			$smsMessage="Your application for specialized trade registration has been received. You can check status of your application using CID No. or Application No ($referenceNo) from CDB website.";
		}else{
			$subject="Acknowledgement: Receipt of Application for CDB Service";
			$emailMessage="This is to acknowledge receipt of your application for Construction Development Board (CDB) services. Your application will be processed in due course. You can check status of your application using your Citizenship Id No. or Application No. from the <a href=”#”>CDB website</a>. You will also be notified through email when your application is approved.";
			$smsMessage="Your application for renewal of registration has been received. You can check status of your application using CID No. or Application No ($referenceNo) from CDB website.";
		}
		$mailData=array(
			'mailIntendedTo'=>$mailIntendedTo,
			'applicantName'=>$recipientName,
			'applicationNo'=>$referenceNo,
			'applicationDate'=>$applicationDate,
			'mailMessage'=>$emailMessage,
		);
		$this->sendEmailMessage($mailView,$mailData,$subject,$recipientAddress,$recipientName);
		$this->sendSms($smsMessage,$mobileNo);
		DB::commit();
		if((int)$isServiceBySpecializedTrade==0){
			Session::forget('SpecializedTradeRegistrationId');
			return Redirect::route('applicantregistrationsuccess',array('linktoprint'=>'specializedtrade/printregistration','printreference'=>$specializedTradeId,'applicationno'=>$referenceNo));
		}else{
			return Redirect::to('specializedtrade/profile')->with('savedsuccessmessage','Your application was successfully submitted');
		}
	}
	public function rejectRegistration(){
		DB::beginTransaction();
		try{
			$rejectionCode=str_random(30);
			$specializedTradeId=Input::get('SpecializedTradeReference');
			$specializedTrade = SpecializedTradeModel::find($specializedTradeId);
			$specializedTrade->CmnApplicationRegistrationStatusId=CONST_CMN_APPLICATIONREGISTRATIONSTATUS_REJECTED;
			$specializedTrade->RemarksByRejector=Input::get('RemarksByRejector');
			$specializedTrade->RejectedDate=Input::get('RejectedDate');
			$specializedTrade->SysRejectorUserId=Auth::user()->Id;
			$specializedTrade->SysLockedByUserId=NULL;
			$specializedTrade->SysRejectionCode=$rejectionCode;
			$specializedTrade->save();
		}catch(Exception $e){
			DB::rollback();
        	throw $e;
		}
		DB::commit();
		$specializedTradeDetails=SpecializedTradeModel::specializedTradeHardList(Input::get('SpecializedTradeReference'))->get(array('Name','Email','ReferenceNo','ApplicationDate','RemarksByRejector','SysRejectionCode','MobileNo'));
		/*----------------------Contractor Email Details and New Details------------------*/
		$recipientAddress=$specializedTradeDetails[0]->Email;
		$recipientName=$specializedTradeDetails[0]->NameOfFirm;
		$applicationNo=$specializedTradeDetails[0]->ReferenceNo;
		$applicationDate=$specializedTradeDetails[0]->ApplicationDate;
		$remarksByRejector=$specializedTradeDetails[0]->RemarksByRejector;
		$rejectionSysCode=$specializedTradeDetails[0]->SysRejectionCode;
		$mobileNo=$specializedTradeDetails[0]->MobileNo;
		$mailView="emails.crps.mailapplicationrejected";
		$subject="Rejection of Your Registration with CDB";
		$mailData=array(
			'prefix'=>'specializedtrade',
			'applicantName'=>$recipientName,
			'applicationNo'=>$applicationNo,
			'applicationDate'=>$applicationDate,
			'remarksByRejector'=>$remarksByRejector,
			'referenceApplicant'=>Input::get('SpecializedTradeReference'),
			'rejectionSysCode'=>$rejectionSysCode,
			'mailMessage'=>"Construction Development Board (CDB) has rejected your application for registration of Specialized Trade with CDB. Please read the reason for rejection given below and reapply by making the necessary corrections.",
		);
		$smsMessage="Your application for specialized trade registration has been rejected. Please check your email ($recipientAddress) to view the reason for rejection.";
		$this->sendEmailMessage($mailView,$mailData,$subject,$recipientAddress,$recipientName);
		$this->sendSms($smsMessage,$mobileNo);
		return Redirect::to('specializedtrade/'.Input::get('RedirectRoute'))->with('savedsuccessmessage','The application has been rejected.');
	}
	public function checkRejectedSecurityCode($specializedTradeReference,$securityCode){
		if(strlen($specializedTradeReference)==36 && strlen($securityCode)==30){
			$checkSpecializedTradeReference=SpecializedTradeModel::where('SysRejectionCode',$securityCode)->pluck('Id');
			$currentStatus=SpecializedTradeModel::where('Id',$checkSpecializedTradeReference)->pluck('CmnApplicationRegistrationStatusId');
			$rejectedDate=SpecializedTradeModel::where('Id',$checkSpecializedTradeReference)->pluck('RejectedDate');
			$rejectedDate=new DateTime($rejectedDate);
			$currentDate=new DateTime(date('Y-m-d'));
			$noOfDays=$rejectedDate->diff($currentDate);
			if($checkSpecializedTradeReference==$specializedTradeReference && $currentStatus==CONST_CMN_APPLICATIONREGISTRATIONSTATUS_REJECTED && (int)$noOfDays->d < 31){
				DB::table('crpspecializedtrade')->where('Id',$specializedTradeReference)->update(array('ApplicationDate'=>date('Y-m-d')));
				return Redirect::to('specializedtrade/registration/'.$specializedTradeReference.'?editbyapplicant=true&rejectedapplicationreapply=true');	
			}else{
				return Redirect::to('ezhotin/rejectedapplicationmessage');
			}
		}else{
			App::abort('404');
		}
	}
	public function setRecordLock($specializedTradeId){
		$pickerByUserFullName=null;
		$redirectUrl=Input::get('redirectUrl');
		$notification = Input::get('notification');
		if((bool)$notification){
			DB::table('sysapplicationnotification')->where('ApplicationId',$specializedTradeId)->update(array('IsRead'=>1));
		}
		$hasBeenPicked=SpecializedTradeModel::specializedTradeHardList($specializedTradeId)->pluck('SysLockedByUserId');
		if((bool)$hasBeenPicked!=null){
			$pickerByUserFullName=User::where('Id',$hasBeenPicked)->pluck('FullName');
		}else{
			$instance=SpecializedTradeModel::find($specializedTradeId);
			$instance->SysLockedByUserId=Auth::user()->Id;
			$instance->save();
		}
		return Redirect::to($redirectUrl)->with('ApplicationAlreadyPicked',$pickerByUserFullName);
	}
	public function specializedTradeList(){
		$parameters=array();
		$specializedTradeId=Input::get('CrpSpecializedTradeId');
		$SPNo=Input::get('SPNo');

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
		
		$query="select T1.Id,T1.ReferenceNo,T1.RegistrationExpiryDate,Z.Name as Status,Z.ReferenceNo as StatusReference,T1.SPNo,T1.ApplicationDate,T1.CIDNo,T1.Name as SpecializedTradeName,T1.EmployerName,T1.Email,T1.MobileNo,T1.TelephoneNo,T2.NameEn as Dzongkhag,T3.Name as Salutation from crpspecializedtradefinal T1 left join cmnlistitem Z on Z.Id = T1.CmnApplicationRegistrationStatusId left join cmndzongkhag T2 on T1.CmnDzongkhagId=T2.Id join cmnlistitem T3 on T1.CmnSalutationId=T3.Id where 1=1 AND SPNo LIKE '%SP%'";
		if(Route::current()->getUri()=="specializedtrade/editlist"){
			$linkText='Edit';
			$link='specializedtrade/editdetails/';
			$query.=" and T1.CmnApplicationRegistrationStatusId=?";
			array_push($parameters,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED);
		}elseif(Route::current()->getUri()=="specializedtrade/viewprintlist"){
			$linkText='View/Print';
			$link='specializedtrade/viewprintdetails/';
			$query.=" and T1.CmnApplicationRegistrationStatusId is not null";
		}elseif(Route::current()->getUri()=="specializedtrade/newcommentsadverserecordslist"){
			$linkText='Add';
			$link='specializedtrade/newcommentsadverserecords/';
			$query.=" and T1.CmnApplicationRegistrationStatusId=?";
			array_push($parameters,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED);
		}elseif(Route::current()->getUri()=="specializedtrade/editcommentsadverserecordslist"){
			$linkText='View';
			$link='specializedtrade/editcommentsadverserecords/';
			$query.=" and T1.CmnApplicationRegistrationStatusId=?";
			array_push($parameters,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED);
		}else{
			App::abort('404');
		}
		if((bool)$specializedTradeId!=NULL || (bool)$SPNo!=NULL){
			if((bool)$specializedTradeId!=NULL){
				$query.=" and T1.Id=?";
				array_push($parameters,$specializedTradeId);
			}
			if((bool)$SPNo!=NULL){
				$query.=" and T1.SPNo=?";
	            array_push($parameters,$SPNo);
			}
		}
		$specializedTradesListsAll=SpecializedTradeFinalModel::specializedTradeHardListAll()->get(array('Id','Name'));
		$specializedTradeLists=DB::select($query." order by SPNo,SpecializedTradeName".$limit,$parameters);
		return View::make('crps.specializedtradelist')
					->with('pageTitle','List of Specialized Trade')
					->with('link',$link)
					->with('linkText',$linkText)
					->with('SPNo',$SPNo)
					->with('specializedTradesListsAll',$specializedTradesListsAll)
					->with('specializedTradeId',$specializedTradeId)
					->with('specializedTradeLists',$specializedTradeLists);
	}
	public function specializedTradeDetails($specializedTradeId){
		$registrationApprovedForPayment=1;
		$userSpecializedTrade=0;
		if(Route::current()->getUri()=="specializedtrade/verifyregistrationprocess/{specializedtradeid}"){
			$view="crps.specializedtradeverifyregistrationprocess";
			$modelPost="specializedtrade/mverifyregistration";
		}elseif(Route::current()->getUri()=="specializedtrade/approveregistrationprocess/{specializedtradeid}"){
			$view="crps.specializedtradeapproveregistrationprocess";
			$modelPost="specializedtrade/mapproveregistration";
		}else{
			App::abort('404');
		}
		$registrationValidityYears=CrpService::registrationValidityYear(CONST_SERVICETYPE_NEW)->pluck('SpecializedTradeValidity');
		$specializedTradeInformations=SpecializedTradeModel::specializedTrade($specializedTradeId)->get(array('crpspecializedtrade.Id','crpspecializedtrade.ReferenceNo','crpspecializedtrade.ApplicationDate','crpspecializedtrade.SPNo','crpspecializedtrade.CIDNo','crpspecializedtrade.Name','crpspecializedtrade.Gewog','crpspecializedtrade.Village','crpspecializedtrade.Email','crpspecializedtrade.MobileNo','crpspecializedtrade.EmployerName','crpspecializedtrade.EmployerAddress','crpspecializedtrade.TelephoneNo','crpspecializedtrade.RemarksByVerifier','crpspecializedtrade.VerifiedDate','T1.Name as Salutation','T2.NameEn as Dzongkhag','T4.FullName as Verifier','T5.FullName as Approver','T6.FullName as PaymentApprover'));
		$specializedTradeAttachments=SpecializedTradeAttachmentModel::attachment($specializedTradeId)->get(array('Id','DocumentName','DocumentPath'));
		$workCategories=DB::select("select T1.Id as CategoryId,T1.Code,T1.Name,T2.Id as WorkClassificationId,T2.CmnAppliedCategoryId,T3.CmnVerifiedCategoryId from cmnspecializedtradecategory T1 left join crpspecializedtradeworkclassification T2 on T1.Id=T2.CmnAppliedCategoryId and T2.CrpSpecializedTradeId=? left join crpspecializedtradeworkclassification T3 on T1.Id=T3.CmnVerifiedCategoryId and T3.CrpSpecializedTradeId=? order by T1.Code,T1.Name",array($specializedTradeId,$specializedTradeId));

		return View::make($view)
					->with('modelPost',$modelPost)
					->with('registrationValidityYears',$registrationValidityYears)
					->with('specializedTradeId',$specializedTradeId)
					->with('specializedTradeInformations',$specializedTradeInformations)
					->with('specializedTradeAttachments',$specializedTradeAttachments)
					->with('workCategories',$workCategories);
	}
	public function editDetails($specializedTradeId){
        $loggedInUser = Auth::user()->Id;
        $userRoles=DB::table('sysuserrolemap')->where('SysUserId',$loggedInUser)->lists('SysRoleId');
        $isAdmin = false;
        if(in_array(CONST_ROLE_ADMINISTRATOR,$userRoles)){
            $isAdmin = true;
        }
		$userSpecializedTrade=0;
		$specializedTradeInformations=SpecializedTradeFinalModel::specializedTrade($specializedTradeId)->get(array('crpspecializedtradefinal.SPNo','crpspecializedtradefinal.CIDNo','crpspecializedtradefinal.RegistrationExpiryDate','crpspecializedtradefinal.Name','crpspecializedtradefinal.Gewog','crpspecializedtradefinal.Village','crpspecializedtradefinal.Email','crpspecializedtradefinal.MobileNo','crpspecializedtradefinal.EmployerName','crpspecializedtradefinal.EmployerAddress','T1.Name as Salutation','T2.NameEn as Dzongkhag','T3.Name as CurrentStatus'));
		$specializedTradeAttachments=SpecializedTradeAttachmentFinalModel::attachment($specializedTradeId)->get(array('Id','DocumentName','DocumentPath'));
		// $workClasssifications=DB::select("select distinct T1.Code,T1.Name,T2.CmnAppliedCategoryId,T3.CmnVerifiedCategoryId,T4.CmnApprovedCategoryId from cmnspecializedtradecategory T1 left join crpspecializedtradeworkclassificationfinal T2 on T1.Id=T2.CmnAppliedCategoryId and T2.CrpSpecializedTradeFinalId=? left join crpspecializedtradeworkclassificationfinal T3 on T1.Id=T3.CmnVerifiedCategoryId and T3.CrpSpecializedTradeFinalId=? left join crpspecializedtradeworkclassificationfinal T4 on T1.Id=T4.CmnVerifiedCategoryId and T4.CrpSpecializedTradeFinalId=? where T1.Code like '%SP%'",array($specializedTradeId,$specializedTradeId,$specializedTradeId));
		$workClasssifications=DB::select("SELECT DISTINCT 
		T1.Code,
		T1.Name,
		T2.CmnAppliedCategoryId,
		T3.CmnVerifiedCategoryId,
		T4.CmnApprovedCategoryId 
	  FROM
		(
		  (
			(
			  cmnspecializedtradecategory T1 
			  LEFT JOIN (
				  crpspecializedtradeworkclassificationfinal T2 
				  JOIN crpspecializedtradefinal a 
					ON (
					  a.CrpSpecializedTradeId = T2.CrpSpecializedTradeFinalId 
					  AND a.Id =?
					)
				) 
				ON (T1.Id = T2.CmnAppliedCategoryId)
			) 
			LEFT JOIN (
				crpspecializedtradeworkclassificationfinal T3 
				JOIN crpspecializedtradefinal b 
				  ON (
					b.CrpSpecializedTradeId = T3.CrpSpecializedTradeFinalId 
					AND b.Id =?
				  )
			  ) 
			  ON (T1.Id = T3.CmnVerifiedCategoryId)
		  ) 
		  LEFT JOIN (
			  crpspecializedtradeworkclassificationfinal T4 
			  JOIN crpspecializedtradefinal c 
				ON (
				  c.CrpSpecializedTradeId = T4.CrpSpecializedTradeFinalId 
				  AND c.Id =?
				)
			) 
			ON (T1.Id = T4.CmnApprovedCategoryId)
		) 
	  WHERE T1.Code LIKE '%SP%' ",array($specializedTradeId,$specializedTradeId,$specializedTradeId));
		
		$CV = DB::select("SELECT 
				DISTINCT(T3.Id),
				C.`Name` workStatus,
				D.`Name` designation,
				DATE_FORMAT(T2.ActualStartDate,'%d-%m-%Y')ActualStartDate,
				DATE_FORMAT(T2.`ActualEndDate`,'%d-%m-%Y') ActualEndDate,
				`T1`.`CIDNo`,
				GROUP_CONCAT(B.CDBNo SEPARATOR ',') AS CDBNo,
				CASE
					WHEN T3.migratedworkid IS NULL
					THEN CONCAT(
					T4.Code,
					'/',
					YEAR (T3.UploadedDate),
					'/',
					T3.WorkId
					)
					ELSE T3.migratedworkid
				END AS WorkId,
				`T4`.`Name` AS `ProcuringAgency`,B.`NameOfFirm`
				FROM
				`etlcontractorhumanresource` AS `T1`
				INNER JOIN `etltenderbiddercontractor` AS `T2`
					ON `T2`.`Id` = `T1`.`EtlTenderBidderContractorId`
				INNER JOIN `etltenderbiddercontractordetail` AS `A`
					ON `A`.`EtlTenderBidderContractorId` = `T2`.`Id`
				INNER JOIN `crpcontractorfinal` AS `B`
					ON `B`.`Id` = `A`.`CrpContractorFinalId`
				INNER JOIN `etltender` AS `T3`
					ON `T3`.`Id` = `T2`.`EtlTenderId`
					INNER JOIN cmnlistitem C ON C.Id=T3.CmnWorkExecutionStatusId
					INNER JOIN cmnlistitem D ON D.Id=T1.`CmnDesignationId`
				INNER JOIN `cmnprocuringagency` AS `T4`
				WHERE `T2`.`ActualStartDate` IS NOT NULL
				AND `T1`.`CIDNo` =".$specializedTradeInformations[0]->CIDNo."
				GROUP BY `T3`.`Id`"
		);
		
		return View::make('crps.specializedtradeinformation')
					->with('isAdmin',$isAdmin)
					->with('specializedTradeId',$specializedTradeId)
					->with('specializedTradeInformations',$specializedTradeInformations)
					->with('specializedTradeAttachments',$specializedTradeAttachments)
					->with('CV',$CV)
					->with('workClasssifications',$workClasssifications)
					->with('userSpecializedTrade',$userSpecializedTrade);
	}
	public function verifyList(){
		$redirectUrl=Request::path();
		$recordLockException=false;
		$applicationPickedByUserFullName=null;
		if(Session::has('ApplicationAlreadyPicked')){
			$applicationPickedByUserFullName=Session::get('ApplicationAlreadyPicked');
			$recordLockException=true;
		}
		$specializedTradeIdMyTask=Input::get('CrpSpecializedTradeIdMyTask');
		$fromDateMyTask=Input::get('FromDateMyTask');
		$toDateMyTask=Input::get('ToDateMyTask');
		$specializedTradeIdAll=Input::get('CrpSpecializedTradeIdAll');
		$fromDateAll=Input::get('FromDateAll');
		$toDateAll=Input::get('ToDateAll');
		$query="select T1.Id,T1.ReferenceNo,T1.ApplicationDate,T1.CIDNo,T1.Name as SpecializedTradeName,T1.EmployerName,T1.Email,T1.MobileNo,T1.TelephoneNo,T2.NameEn as Dzongkhag,T3.Name as Salutation from crpspecializedtrade T1 join cmndzongkhag T2 on T1.CmnDzongkhagId=T2.Id join cmnlistitem T3 on T1.CmnSalutationId=T3.Id where T1.CmnApplicationRegistrationStatusId=? and T1.SysLockedByUserId is null and T1.CrpSpecializedTradeId is null";
		$queryMyTaskList="select T1.Id,T1.ReferenceNo,T1.ApplicationDate,T1.CIDNo,T1.Name as SpecializedTradeName,T1.EmployerName,T1.Email,T1.MobileNo,T1.TelephoneNo,T2.NameEn as Dzongkhag,T3.Name as Salutation from crpspecializedtrade T1 join cmndzongkhag T2 on T1.CmnDzongkhagId=T2.Id join cmnlistitem T3 on T1.CmnSalutationId=T3.Id where T1.CmnApplicationRegistrationStatusId=? and T1.SysLockedByUserId=? and T1.CrpSpecializedTradeId is null";
		$parameters=array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW);
		$parametersMyTaskList=array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW,Auth::user()->Id);
		if((bool)$specializedTradeIdMyTask!=NULL || (bool)$fromDateMyTask!=NULL || (bool)$toDateMyTask!=NULL || (bool)$specializedTradeIdAll!=NULL || (bool)$fromDateAll!=NULL || (bool)$toDateAll!=NULL){
			if((bool)$specializedTradeIdMyTask!=NULL){
				$queryMyTaskList.=" and T1.Id=?";
				$queryMyTaskList.=" and T1.Id=?";
				array_push($parametersMyTaskList,$specializedTradeIdMyTask);
			}
			if((bool)$fromDateMyTask!=NULL){
				$fromDateMyTask=$this->convertDate($fromDateMyTask);
				$queryMyTaskList.=" and T1.ApplicationDate>=?";
	            array_push($parametersMyTaskList,$fromDateMyTask);
			}
			if((bool)$toDateMyTask!=NULL){
				$toDateMyTask=$this->convertDate($toDateMyTask);
				$queryMyTaskList.=" and T1.ApplicationDate<=?";
	            array_push($parametersMyTaskList,$toDateMyTask);
			}
			if((bool)$specializedTradeIdAll!=NULL){
				$query.=" and T1.Id=?";
				array_push($parameters,$specializedTradeIdAll);
			}
			if((bool)$fromDateAll!=NULL){
				$fromDateAll=$this->convertDate($fromDateAll);
				$query.=" and T1.ApplicationDate>=?";
	            array_push($parameters,$fromDateAll);
			}
			if((bool)$toDateAll!=NULL){
				$toDateAll=$this->convertDate($toDateAll);
				$query.=" and T1.ApplicationDate<=?";
	            array_push($parameters,$toDateAll);
			}
		}
		$specializedTradeLists=DB::select($query." order by ApplicationDate,ReferenceNo,SpecializedTradeName",$parameters);
		$specializedTradeMyTaskLists=DB::select($queryMyTaskList." order by ApplicationDate,ReferenceNo,SpecializedTradeName",$parametersMyTaskList);
		return View::make('crps.specializedtraderegistrationapplicationprocesslist')
					->with('redirectUrl',$redirectUrl)
					->with('pageTitle',"Verify Specialized Trade Registration")
					->with('recordLockException',$recordLockException)
					->with('applicationPickedByUserFullName',$applicationPickedByUserFullName)
					->with('fromDateMyTask',$fromDateMyTask)
					->with('toDateMyTask',$toDateMyTask)
					->with('specializedTradeIdMyTask',$specializedTradeIdMyTask)
					->with('fromDateAll',$fromDateAll)
					->with('toDateAll',$toDateAll)
					->with('specializedTradeIdAll',$specializedTradeIdAll)
					->with('specializedTradeLists',$specializedTradeLists)
					->with('specializedTradeMyTaskLists',$specializedTradeMyTaskLists);
	}
	public function verifyRegistration(){
		$postedValues=Input::all();
		// $postedValues['RegistrationApprovedDate']=$this->convertDateTime($postedValues['RegistrationApprovedDate']);
		// $postedValues['RegistrationExpiryDate']=$this->convertDateTime($postedValues['RegistrationExpiryDate']);
		DB::beginTransaction();
		try{
			$verifiedCategory=Input::get('CmnVerifiedCategoryId');
			if(!empty($verifiedCategory)){
				$instance=SpecializedTradeModel::find($postedValues['SpecializedTradeReference']);
				$instance->fill($postedValues);
				$instance->update();
				for($idx = 0; $idx < count($verifiedCategory); $idx++){
				    $instance=SpecializedTradeWorkClassificationModel::find($postedValues['WorkCategoryTableId'][$idx]);
				    $instance->CmnVerifiedCategoryId = $postedValues['CmnVerifiedCategoryId'][$idx];
				    $instance->save();
				}
			}else{
				return Redirect::to('specializedtrade/verifyregistrationprocess/'.$postedValues['SpecializedTradeReference'])->withInputs()->with('customerrormessage','You need to at least verify one category.');
			}
		}catch(Exception $e){
			DB::rollback();
        	throw $e;
		}
		DB::commit();
		return Redirect::to('specializedtrade/verifyregistration')
						->with('savedsuccessmessage','The application has been successfully verified.');
	}
	public function approveList(){
		$recordLockException=false;
		$applicationPickedByUserFullName=null;
		if(Session::has('ApplicationAlreadyPicked')){
			$applicationPickedByUserFullName=Session::get('ApplicationAlreadyPicked');
			$recordLockException=true;
		}
		$specializedTradeIdMyTask=Input::get('CrpSpecializedTradeIdMyTask');
		$fromDateMyTask=Input::get('FromDateMyTask');
		$toDateMyTask=Input::get('ToDateMyTask');
		$specializedTradeIdAll=Input::get('CrpSpecializedTradeIdAll');
		$fromDateAll=Input::get('FromDateAll');
		$toDateAll=Input::get('ToDateAll');
		$query="select T1.Id,T1.ReferenceNo,T1.ApplicationDate,T1.CIDNo,T1.Name as SpecializedTradeName,T1.EmployerName,T1.Email,T1.MobileNo,T1.TelephoneNo,T2.NameEn as Dzongkhag,T3.Name as Salutation from crpspecializedtrade T1 join cmndzongkhag T2 on T1.CmnDzongkhagId=T2.Id join cmnlistitem T3 on T1.CmnSalutationId=T3.Id where T1.CmnApplicationRegistrationStatusId=? and T1.SysLockedByUserId is null";
		$queryMyTaskList="select T1.Id,T1.ReferenceNo,T1.ApplicationDate,T1.CIDNo,T1.Name as SpecializedTradeName,T1.EmployerName,T1.Email,T1.MobileNo,T1.TelephoneNo,T2.NameEn as Dzongkhag,T3.Name as Salutation from crpspecializedtrade T1 join cmndzongkhag T2 on T1.CmnDzongkhagId=T2.Id join cmnlistitem T3 on T1.CmnSalutationId=T3.Id where T1.CmnApplicationRegistrationStatusId=? and T1.SysLockedByUserId=?";
		$parameters=array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_VERIFIED);
		$parametersMyTaskList=array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_VERIFIED,Auth::user()->Id);
		if((bool)$specializedTradeIdMyTask!=NULL || (bool)$fromDateMyTask!=NULL || (bool)$toDateMyTask!=NULL || (bool)$specializedTradeIdAll!=NULL || (bool)$fromDateAll!=NULL || (bool)$toDateAll!=NULL){
			if((bool)$specializedTradeIdMyTask!=NULL){
				$queryMyTaskList.=" and T1.Id=?";
				array_push($parametersMyTaskList,$specializedTradeIdMyTask);
			}
			if((bool)$fromDateMyTask!=NULL){
				$fromDateMyTask=$this->convertDate($fromDateMyTask);
				$queryMyTaskList.=" and T1.ApplicationDate>=?";
	            array_push($parametersMyTaskList,$fromDateMyTask);
			}
			if((bool)$toDateMyTask!=NULL){
				$toDateMyTask=$this->convertDate($toDateMyTask);
				$queryMyTaskList.=" and T1.ApplicationDate<=?";
	            array_push($parametersMyTaskList,$toDateMyTask);
			}
			if((bool)$specializedTradeIdAll!=NULL){
				$query.=" and T1.Id=?";
				array_push($parameters,$specializedTradeIdAll);
			}
			if((bool)$fromDateAll!=NULL){
				$fromDateAll=$this->convertDate($fromDateAll);
				$query.=" and T1.ApplicationDate>=?";
	            array_push($parameters,$fromDateAll);
			}
			if((bool)$toDateAll!=NULL){
				$toDateAll=$this->convertDate($toDateAll);
				$query.=" and T1.ApplicationDate<=?";
	            array_push($parameters,$toDateAll);
			}
		}
		$specializedTradeLists=DB::select($query." order by ApplicationDate,ReferenceNo,SpecializedTradeName",$parameters);
		$specializedTradeMyTaskLists=DB::select($queryMyTaskList." order by ApplicationDate,ReferenceNo,SpecializedTradeName",$parametersMyTaskList);
		return View::make('crps.specializedtraderegistrationapplicationprocesslist')
					->with('pageTitle',"Verify Specialized Trade Registration")
					->with('recordLockException',$recordLockException)
					->with('applicationPickedByUserFullName',$applicationPickedByUserFullName)
					->with('fromDateMyTask',$fromDateMyTask)
					->with('toDateMyTask',$toDateMyTask)
					->with('specializedTradeIdMyTask',$specializedTradeIdMyTask)
					->with('fromDateAll',$fromDateAll)
					->with('toDateAll',$toDateAll)
					->with('specializedTradeIdAll',$specializedTradeIdAll)
					->with('specializedTradeLists',$specializedTradeLists)
					->with('specializedTradeMyTaskLists',$specializedTradeMyTaskLists);
	}
	public function approveRegistration(){
		$postedValues=Input::all();
		$postedValues["InitialDate"] = date('Y-m-d');
		$postedValues['RegistrationApprovedDate']=$this->convertDateTime($postedValues['RegistrationApprovedDate']);
		$postedValues['RegistrationExpiryDate']=$this->convertDateTime($postedValues['RegistrationExpiryDate']);
		DB::beginTransaction();
		try{
			$verifiedCategory=Input::get('CmnApprovedCategoryId');
			if(!empty($verifiedCategory)){
				$instance=SpecializedTradeModel::find($postedValues['SpecializedTradeReference']);
				$instance->fill($postedValues);
				$instance->update();
				for($idx = 0; $idx < count($verifiedCategory); $idx++){
				    $instance=SpecializedTradeWorkClassificationModel::find($postedValues['WorkCategoryTableId'][$idx]);
				    $instance->CmnApprovedCategoryId = $postedValues['CmnApprovedCategoryId'][$idx];
				    $instance->save();
				}
				$uuid=DB::select("select uuid() as Id");
				$generatedId=$uuid[0]->Id;
				$specializedTradeDetails=SpecializedTradeModel::specializedTradeHardList($postedValues['SpecializedTradeReference'])->get(array('SPNo','TPN','Name','Email','ReferenceNo','ApplicationDate','MobileNo','RemarksByVerifier','RemarksByApprover'));
				$SPNo=$specializedTradeDetails[0]->SPNo;
				$recipientAddress=$specializedTradeDetails[0]->Email;
				$recipientName=$specializedTradeDetails[0]->Name;
				$referenceNo=$specializedTradeDetails[0]->ReferenceNo;
				$applicationDate=$specializedTradeDetails[0]->ApplicationDate;
				$mobileNo=$specializedTradeDetails[0]->MobileNo;
				$tpn=$specializedTradeDetails[0]->TPN;
				$remarksByVerifier = $specializedTradeDetails[0]->RemarksByVerifier;
				$remarksByApprover = $specializedTradeDetails[0]->RemarksByApprover;
				$plainPassword=substr(md5(uniqid(mt_rand(), true)), 0, 5);
				$plainPassword.="@#".date('d');
				$password=Hash::make($plainPassword);
		        $userCredentials=array('Id'=>$generatedId,'username'=>$recipientAddress,'password'=>$password,'FullName'=>$recipientName,'Status'=>'1','CreatedBy'=>Auth::user()->Id);
				$roleData=array('SysUserId'=>$generatedId,'SysRoleId'=>CONST_ROLE_SPECIALIZEDTRADE,'CreatedBy'=>Auth::user()->Id);
				User::create($userCredentials);
				RoleUserMapModel::create($roleData);
				DB::statement("call ProCrpSpecializedTradeNewRegistrationFinalData(?,?,?,?)",array(Input::get('SpecializedTradeReference'),$generatedId,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED,Auth::user()->Id));
			}else{
				return Redirect::to('specializedtrade/approveregistrationprocess/'.$postedValues['SpecializedTradeReference'])->withInput()->with('customerrormessage','You need to at least approve one category.');
			}
		}catch(Exception $e){
			DB::rollback();
        	throw $e;
		}
		DB::commit();
		$mailView="emails.crps.mailregistrationpaymentcompletion";
		$subject="Activation of Your CDB Certificate";
		$smsMessage="Your application for specialized trade registration has been approved by CDB and your certificate has been activated. Your SP No. is $SPNo. Your username is $recipientAddress and password is $plainPassword";
		$mailData=array(
			'applicantName'=>$recipientName,
			'applicationNo'=>$referenceNo,
			'applicationDate'=>$applicationDate,
			'username'=>$recipientAddress,
			'password'=>$plainPassword,
			'tpn'=>$tpn,
			'mailMessage'=>"Construction Development Board (CDB) has verified and approved your application for registration of specialized trade with CDB. Your SP No. is ".$SPNo." and your CDB certificate has been activated. You can view and print it by logging in to your account using below given user credentials. Please change your password regularly to make your account secure.<br/>Remarks by Verifier: $remarksByVerifier <br/>Remarks by Approver: $remarksByApprover",
		);
		$this->sendEmailMessage($mailView,$mailData,$subject,$recipientAddress,$recipientName);
		$this->sendSms($smsMessage,$mobileNo);
		return Redirect::to('specializedtrade/approveregistration')
						->with('savedsuccessmessage','The application has been successfully verified.');
	}
	public function newCommentAdverseRecord($specializedTradeId){
		$specializedTrade=SpecializedTradeFinalModel::specializedTradeHardList($specializedTradeId)->get(array('Id','SPNo','Name'));
		return View::make('crps.specializedtradenewadverserecordsandcomments')
					->with('specializedTradeId',$specializedTradeId)
					->with('specializedTrade',$specializedTrade);	
	}
	public function editCommentAdverseRecord($specializedTradeId){
		$specializedTrade=SpecializedTradeFinalModel::specializedTradeHardList($specializedTradeId)->get(array('Id','SPNo','Name'));
		$commentsAdverseRecords=SpecializedTradeCommentAdverseRecordModel::commentAdverseRecordList($specializedTradeId)->get(array('Id','Date','Remarks','Type'));
		return View::make('crps.specializedtradeeditadverserecordscomments')
					->with('specializedTrade',$specializedTrade)
					->with('commentsAdverseRecords',$commentsAdverseRecords);
	}
	public function saveCommentAdverseRecord(){
		$postedValues=Input::all();
		$date=$this->convertDate(Input::get('Date'));
		$postedValues['Date']=$date;
		$validation = new SpecializedTradeCommentAdverseRecordModel;
		if(!($validation->validate($postedValues))){
		    $errors = $validation->errors();
		    return Redirect::to('specializedtrade/newcommentsadverserecords/'.$postedValues['CrpSpecializedTradeId'])->withErrors($errors)->withInput();
		}
		SpecializedTradeCommentAdverseRecordModel::create($postedValues);
		return Redirect::to('specializedtrade/newcommentsadverserecordslist')->with('savedsuccessmessage','Comment/Adverse Record sucessfully added.');
	}
	public function updateCommentAdverseRecord(){
		$postedValues=Input::all();
		$date=$this->convertDate(Input::get('Date'));
		$postedValues['Date']=$date;
		$instance=SpecializedTradeCommentAdverseRecordModel::find($postedValues['Id']);
		$instance->fill($postedValues);
		$instance->update();
		return Redirect::to('specializedtrade/editcommentsadverserecords/'.$postedValues['CrpSpecializedTradeId'])->with('savedsuccessmessage','Record has been successfully updated');;
	}
	public function blacklistDeregisterList(){
		$reRegistration=1;
		$type=3;
		$parameters=array();
		$specializedTradeId=Input::get('CrpSpecializedTradeId');
		$SPNo=Input::get('SPNo');
		$query="select T1.Id,T1.SPNo,T1.CIDNo,T1.Name as SpecializedTradeName,T1.EmployerName,T1.Email,T1.MobileNo,T1.TelephoneNo,T2.NameEn as Dzongkhag,T3.Name as Salutation from crpspecializedtradefinal T1 join cmndzongkhag T2 on T1.CmnDzongkhagId=T2.Id join cmnlistitem T3 on T1.CmnSalutationId=T3.Id where 1=1";
		
		if(Request::path()=="specializedtrade/deregister"){
			$reRegistration=0;
			$type=1;
			$captionHelper="Registered";
			$captionSubject="Deregistration of Specialized Trade";
			$query.=" and T1.CmnApplicationRegistrationStatusId=?";
			array_push($parameters,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED);
		}elseif(Request::path()=="specializedtrade/blacklist"){
			$reRegistration=0;
			$type=2;
			$captionHelper="Registered";
			$captionSubject="Blacklisting of Specialized Trade";
			$query.=" and T1.CmnApplicationRegistrationStatusId=?";
			array_push($parameters,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED);
		}elseif(Request::path()=="specializedtrade/reregistration"){
			$captionHelper="Deregistered or Blacklisted";
			$captionSubject="Re-registration of Specialized Trade";
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
		if((bool)$specializedTradeId!=NULL || (bool)$SPNo!=NULL){
			if((bool)$specializedTradeId!=NULL){
				$query.=" and T1.Id=?";
				array_push($parameters,$specializedTradeId);
			}
			if((bool)$SPNo!=NULL){
				$query.=" and T1.SPNo=?";
	            array_push($parameters,$SPNo);
			}
		}
		$specializedTradeListsAll=SpecializedTradeFinalModel::specializedTradeHardListAll()->get(array('Id','Name'));
		$specializedTradeLists=DB::select($query." order by SPNo,SpecializedTradeName".$limit,$parameters);
		return View::make('crps.specializedtradederegistrationlist')
					->with('SPNo',$SPNo)
					->with('type',$type)
					->with('specializedTradeId',$specializedTradeId)
					->with('captionHelper',$captionHelper)
					->with('captionSubject',$captionSubject)
					->with('reRegistration',$reRegistration)
					->with('specializedTradeListsAll',$specializedTradeListsAll)
					->with('specializedTradeLists',$specializedTradeLists);
	}
	public function deregisterBlackListRegistration(){
		$postedValues=Input::all();
		DB::beginTransaction();
		$specializedTradeReference=$postedValues['CrpSpecializedTradeId'];
		$specializedTradeUserId=SpecializedTradeFinalModel::where('Id',$postedValues['CrpSpecializedTradeId'])->pluck('SysUserId');
		try{
			if(Input::has('DeRegisteredDate')){
				$postedValues['DeRegisteredDate']=$this->convertDate($postedValues['DeRegisteredDate']);
			}elseif(Input::has('BlacklistedDate')){
				$postedValues['BlacklistedDate']=$this->convertDate($postedValues['BlacklistedDate']);
			}else{
				$postedValues['ReRegistrationDate']=$this->convertDate($postedValues['ReRegistrationDate']);
			}
			$instance=SpecializedTradeFinalModel::find($postedValues['CrpSpecializedTradeId']);
			$instance->fill($postedValues);
			$instance->update();
			$userInstance=User::find($specializedTradeUserId);
			if($postedValues['CmnApplicationRegistrationStatusId']==CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED){
				$redirectRoute="reregistration";
				$userInstance->Status=1;
				$userInstance->save();
			}else{
				if(Input::has('BlacklistedRemarks')){
					$redirectRoute="blacklist";
				}else{
					$redirectRoute="deregister";
				}
				$userInstance->Status=0;
				$userInstance->save();
				/*---Insert Adverse Record i.e the remarks if the consultant is deregistered/blacklisted*/
				if(Input::has('BlacklistedRemarks')){
					$specializedTradeAdverserecordInstance = new SpecializedTradeCommentAdverseRecordModel;
					$specializedTradeAdverserecordInstance->CrpSpecializedTradeFinalId = $specializedTradeReference;
					$specializedTradeAdverserecordInstance->Date=date('Y-m-d');
					$specializedTradeAdverserecordInstance->Remarks=Input::get('BlacklistedRemarks');
					$specializedTradeAdverserecordInstance->Type=2;
					$specializedTradeAdverserecordInstance->save();
				}else{
					$specializedTradeAdverserecordInstance = new SpecializedTradeCommentAdverseRecordModel;
					$specializedTradeAdverserecordInstance->CrpSpecializedTradeFinalId = $specializedTradeReference;
					$specializedTradeAdverserecordInstance->Date=date('Y-m-d');
					$specializedTradeAdverserecordInstance->Remarks=Input::get('DeregisteredRemarks');
					$specializedTradeAdverserecordInstance->Type=2;
					$specializedTradeAdverserecordInstance->save();
				}
			}
		}catch(Exception $e){
			DB::rollback();
        	throw $e;
		}
		DB::commit();
		return Redirect::to('specializedtrade/'.$redirectRoute)->with('savedsuccessmessage','Successfully updated');
	}
	public function printDetails($specializedTradeId){
		$isFinalPrint=0;
		if(Route::current()->getUri()=="specializedtrade/viewprintdetails/{specializedtradeid}"){
			$isFinalPrint=1;
			$specializedTradeInformations=SpecializedTradeFinalModel::specializedTrade($specializedTradeId)->get(array('crpspecializedtradefinal.Id','crpspecializedtradefinal.ReferenceNo','crpspecializedtradefinal.ApplicationDate','crpspecializedtradefinal.SPNo','crpspecializedtradefinal.CIDNo','crpspecializedtradefinal.Name','crpspecializedtradefinal.Gewog','crpspecializedtradefinal.Village','crpspecializedtradefinal.Email','crpspecializedtradefinal.MobileNo','crpspecializedtradefinal.EmployerName','crpspecializedtradefinal.EmployerAddress','crpspecializedtradefinal.TelephoneNo','T1.Name as Salutation','T2.NameEn as Dzongkhag','T3.Name as CurrentStatus'));
			$workClasssifications=DB::select("select distinct T1.Code,T1.Name,T2.CmnAppliedCategoryId,T2.CmnVerifiedCategoryId,T2.CmnApprovedCategoryId from cmnspecializedtradecategory T1 left join crpspecializedtradeworkclassificationfinal T2 on T1.Id=T2.CmnApprovedCategoryId and T2.CrpSpecializedTradeFinalId=? where T1.Code like '%SP%' order by T1.Code,T1.Name",array($specializedTradeId));
		}else{
			$specializedTradeInformations=SpecializedTradeModel::specializedTrade($specializedTradeId)->get(array('crpspecializedtrade.Id','crpspecializedtrade.ReferenceNo','crpspecializedtrade.ApplicationDate','crpspecializedtrade.SPNo','crpspecializedtrade.CIDNo','crpspecializedtrade.Name','crpspecializedtrade.Gewog','crpspecializedtrade.Village','crpspecializedtrade.Email','crpspecializedtrade.MobileNo','crpspecializedtrade.EmployerName','crpspecializedtrade.EmployerAddress','crpspecializedtrade.TelephoneNo','crpspecializedtrade.RemarksByVerifier','T1.Name as Salutation','T2.NameEn as Dzongkhag','T3.Name as CurrentStatus'));
			$workClasssifications=DB::select("select distinct T1.Code,T1.Name,T2.CmnAppliedCategoryId from cmnspecializedtradecategory T1 left join crpspecializedtradeworkclassification T2 on T1.Id=T2.CmnAppliedCategoryId and T2.CrpSpecializedTradeId=? where T1.Code like '%SP%' order by T1.Code,T1.Name",array($specializedTradeId));
		}
		$data['isFinalPrint']=$isFinalPrint;
		$data['printTitle']='Specialized Trade Information';
		$data['specializedTradeInformations']=$specializedTradeInformations;
		$data['workClasssifications']=$workClasssifications;
		$pdf = App::make('dompdf');
		$pdf->loadView('printpages.specializedtradeviewprintinformation',$data)->setPaper('a4')->setOrientation('potrait');
		return $pdf->stream();
	}
	public function checkSPNo(){
		$inputSPNo=Input::get('inputCDBNo');
		$spNoFinalCount=SpecializedTradeFinalModel::specializedTradeHardListAll()->where('SPNo',$inputSPNo)->count();
		$spNoCount=SpecializedTradeModel::specializedTradeHardListAll()->whereIn('CmnApplicationRegistrationStatusId',array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVEDFORPAYMENT,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_VERIFIED))->where('SPNo',$inputSPNo)->count();
		if((int)$spNoCount>0 || (int)$spNoFinalCount>0){
			return 0;
		}
		return 1;
	}
    public function fetchSpecializedTradesJSON(){
        $term = Input::get('term');
        $specializedTrades = DB::table('crpspecializedtradefinal')->where(DB::raw('TRIM(Name)'),DB::raw('like'),"%$term%")->get(array('Id',DB::raw('TRIM(Name) as Name')));
        $specializedTradesJSON = array();
        foreach($specializedTrades as $specializedTrade){
            array_push($specializedTradesJSON,array('id'=>$specializedTrade->Id,'value'=>trim($specializedTrade->Name)));
        }
        return Response::json($specializedTradesJSON);
    }
    public function deleteCommentAdverseRecord(){
    	$id = Input::get('id');
    	try{
    		DB::table('crpspecializedtradecommentsadverserecord')->where('Id',$id)->delete();	
    		return 1;
    	}catch(Exception $e){
    		return 0;
    	}
    }
}