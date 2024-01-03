<?php
class Architect extends CrpsController{
	public function defaultIndex(){
		$feeDetails=DB::select("select ArchitectPvtAmount,ArchitectPvtValidity,ArchitectGovtAmount,ArchitectGovtValidity from crpservice where Id=? limit 1",array(CONST_SERVICETYPE_NEW));
		return View::make('crps.architectindex')
					->with('feeDetails',$feeDetails);
	}
	public function postCheckIsRegistered(){
		$cid = Input::get('CIDNo');
		$isRegistered = DB::table('crparchitectfinal')
	            ->where('CmnApplicationRegistrationStatusId','<>',CONST_CMN_APPLICATIONREGISTRATIONSTATUS_DEREGISTERED)
       	     ->whereRaw("TRIM(CIDNo) = ?",array(TRIM($cid)))
            	     ->count();
		if($isRegistered>0){
			return Response::json(array('valid'=>false));
		}else{
			return Response::json(array('valid'=>true));
		}
	}
	public function registration($architect=null){
		$isRejectedApp=0;
		$isServiceByArchitect=0;
		$isEditByCDB=0;
		$newGeneralInfoSave=1;
		$redirectUrl=Input::get('redirectUrl');
		$view="crps.architectregistration";
		$architectRegistration=array(new ArchitectModel());
		$architectRegistrationAttachments=array();
		if((bool)$architect!=null && !Input::has('usercdb')){
			if(Input::has('editbyapplicant')){
				$view="crps.architectregistration";
				if(Input::has('rejectedapplicationreapply')){
					$isRejectedApp=1;
				}
			}else{
				$view="crps.architecteditregistrationinfo";
			}
			$architectRegistration=ArchitectModel::architectHardList($architect)->get();
			$architectRegistrationAttachments=ArchitectAttachmentModel::attachment($architect)->get(array('Id','DocumentName','DocumentPath'));
		}
		if((bool)$architect!=null && Input::has('usercdb')){
			$isEditByCDB=1;
			$newGeneralInfoSave=0;
			$view="crps.architecteditregistrationinfo";
			$architectRegistration=ArchitectFinalModel::architectHardList($architect)->get();
			$architectRegistrationAttachments=ArchitectAttachmentFinalModel::attachment($architect)->get(array('Id','DocumentName','DocumentPath'));
		}
		$applicationReferenceNo=$this->tableTransactionNo('ArchitectModel','ReferenceNo');
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
					->with('isServiceByArchitect',$isServiceByArchitect)
					->with('architectId',$architect)
					->with('applicationReferenceNo',$applicationReferenceNo)
					->with('serviceSectorTypes',$serviceSectorTypes)
					->with('countries',$country)
					->with('dzongkhags',$dzongkhag)
					->with('salutations',$salutation)
					->with('qualifications',$qualification)
					->with('architectRegistrations',$architectRegistration)
					->with('architectRegistrationAttachments',$architectRegistrationAttachments);
	}
	public function save(){
		$save=true;
		$postedValues=Input::all();
		$isRejectedApp=Input::get('ApplicationRejectedReapply');
		$newGeneralInfoSave=Input::get('NewGeneralInfoSave');
		$isEditByCdb=Input::get('EditByCdb');
		$redirectTo=Input::get('PostBackUrl');
		$serviceByArchitect=Input::get('IsServiceByArchitect');
		$architectId=Input::get('CrpArchitectId');
		$multiAttachments=array();
		$uuid=DB::select("select uuid() as Id");
        $generatedId=$uuid[0]->Id;
		$validation = new ArchitectModel;
		if(!($validation->validate($postedValues))){
		    $errors = $validation->errors();
		    if((int)$serviceByArchitect==1){
		    	return Redirect::to('architect/applyrenewalregistrationdetails/'.$postedValues['CrpArchitectId'])->withInput()->withErrors($errors);
		    }
		    if(empty($postedValues["Id"])){
		    	return Redirect::to('architect/registration')->withInput()->withErrors($errors);
			}else{
				return Redirect::to('architect/registration/'.$postedValues['CrpArchitectId'])->withInput()->withErrors($errors);
			}
		}
		DB::beginTransaction();
		try{
			if(empty($postedValues["Id"])){
				$postedValues["Id"]=$generatedId;
				ArchitectModel::create($postedValues);
				$appliedServiceCount=ArchitectAppliedServiceModel::where('CrpArchitectId',$architectId)->where('CmnServiceTypeId',CONST_SERVICETYPE_RENEWAL)->count();
				if((int)$serviceByArchitect==1 && $appliedServiceCount==0){
					$appliedServiceRenewal = new ArchitectAppliedServiceModel;
	        		$appliedServiceRenewal->CrpArchitectId=$generatedId;
				    $appliedServiceRenewal->CmnServiceTypeId = CONST_SERVICETYPE_RENEWAL;
				    $appliedServiceRenewal->save();
				    if($postedValues['CmnServiceSectorTypeId']==CONST_CMN_SERVICESECTOR_PVT){
				    	$lateRenewalExpiryDate=ArchitectFinalModel::architectHardList($postedValues['CrpArchitectId'])->pluck('RegistrationExpiryDate');
					    $lateRenewalExpiryDate=strtotime($lateRenewalExpiryDate);
					    $currentDate=strtotime(date('Y-m-d'));
					    if($currentDate>$lateRenewalExpiryDate){
					    	$appliedServiceRenewalLateFee = new ArchitectAppliedServiceModel;
					    	$appliedServiceRenewalLateFee->CrpArchitectId=$generatedId;
					    	$appliedServiceRenewalLateFee->CmnServiceTypeId = CONST_SERVICETYPE_LATEFEE;
					    	$appliedServiceRenewalLateFee->save();
					    }
					}
				}else{
					$cidNo = $postedValues['CIDNo'];
					$hasApplied = DB::table('crparchitect')
									->where(DB::raw('coalesce(RegistrationStatus,0)'),'=',1)
									->whereRaw("coalesce(CmnApplicationRegistrationStatusId,'xx') <> ?",array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_REJECTED))
									->where('CIDNo',$cidNo)
									->count();
					$exists = DB::table('crparchitectfinal')
								->where('CIDNo',$cidNo)
								->whereRaw("coalesce(CmnApplicationRegistrationStatusId,'xx') <> ?",array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_REJECTED))
								->count();
					$total = $hasApplied + $exists;
					if($total > 0){
						DB::rollBack();
						return Redirect::to('architect/registration')->with('customerrormessage','<strong>ERROR!</strong> You have already applied!');
					}
				}

			}else{
				$save=false;
				if(isset($isEditByCdb) && (int)$isEditByCdb==1 && $newGeneralInfoSave!=null && (int)$newGeneralInfoSave==0){
					$architectReference= new ArchitectFinalModel();
				}else{
					$architectReference= new ArchitectModel();
				}
				$instance=$architectReference::find($postedValues['Id']);
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
						$destination=public_path().'/uploads/architects';
						$destinationDB='uploads/architects/'.$attachmentName;
						$multiAttachments1["DocumentName"]=(isset($documentName[$count]) && !empty($documentName[$count]))?$documentName[$count]:"Document";


						//CHECK IF IMAGE
						if(strpos($attachment->getClientMimeType(),'image/')>-1){
							$img = Image::make($attachment)->encode('jpg');
							$destinationDB = "uploads/architects/".str_random(15) . '_min_' .".jpg";
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
							$multiAttachments[$k]["CrpArchitectFinalId"]=$generatedId;
						}else{
							$multiAttachments[$k]["CrpArchitectId"]=$generatedId;
						}
					}else{
						if(isset($isEditByCdb) && (int)$isEditByCdb==1 && $newGeneralInfoSave!=null  && (int)$newGeneralInfoSave==0){
							$multiAttachments[$k]["CrpArchitectFinalId"]=$postedValues['Id'];
						}else{
							$multiAttachments[$k]["CrpArchitectId"]=$postedValues['Id'];
						}
					}
					if(isset($isEditByCdb) && (int)$isEditByCdb==1 && $newGeneralInfoSave!=null  && (int)$newGeneralInfoSave==0){
						$saveUploads=new ArchitectAttachmentFinalModel($multiAttachments[$k]);
					}else{	
						$saveUploads=new ArchitectAttachmentModel($multiAttachments[$k]);
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
			if((int)$serviceByArchitect==1){
				return Redirect::to('architect/applyrenewalconfirmation/'.$postedValues["Id"]);			
			}
			return Redirect::to($redirectTo.'/'.$postedValues["Id"])->with('savedsuccessmessage','Registration Information has been successfully updated.');
		}
		if($save){
			Session::put('ArchitectRegistrationId',$generatedId);
			return Redirect::to('architect/confirmregistration');
		}else{
			if(isset($isRejectedApp) && (int)$isRejectedApp==1){
				Session::put('ArchitectRegistrationId',$postedValues["Id"]);
				return Redirect::to('architect/confirmregistration');
			}
			return Redirect::to('architect/confirmregistration')->with('savedsuccessmessage','Registration Information has been successfully updated.');
		}
	}
	public function confirmRegistration(){
		$isServiceByArchitect=0;
		if(Session::has('ArchitectRegistrationId')){
			$architectId=Session::get('ArchitectRegistrationId');
		}else{
			return Redirect::to('architect/registration');
		}
		$architectInformations=ArchitectModel::architect($architectId)->get(array('crparchitect.Id','crparchitect.CIDNo','crparchitect.Name','crparchitect.Gewog','crparchitect.Village','crparchitect.Email','crparchitect.MobileNo','crparchitect.EmployerName','crparchitect.EmployerAddress','crparchitect.GraduationYear','crparchitect.NameOfUniversity','T1.Name as ArchitectType','T2.Name as Salutation','T3.Name as Country','T4.NameEn as Dzongkhag','T5.Name as Qualification','T6.Name as UniversityCountry'));
		$architectAttachments=ArchitectAttachmentModel::attachment($architectId)->get(array('Id','DocumentName','DocumentPath'));
		return View::make('crps.architectregistrationconfirmation')
					->with('architectId',$architectId)
					->with('isServiceByArchitect',$isServiceByArchitect)
					->with('architectInformations',$architectInformations)
					->with('architectAttachments',$architectAttachments);
	}
	public function saveConfirmation(){
		$isServiceByArchitect=Input::get('IsServiceByArchitect');
		$architectId=Input::get('CrpArchitectId');
		DB::beginTransaction();
		try{
			$architect = ArchitectModel::find($architectId);
			$architect->RegistrationStatus=1;
			$architect->CmnApplicationRegistrationStatusId=CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW;
			$architect->save();
		}catch(Exception $e){
			DB::rollback();
        	throw $e;
		}
		$architectDetails=ArchitectModel::architectHardList($architectId)->get(array('Name','Email','ReferenceNo','ApplicationDate','MobileNo'));
		DB::commit();
		$mailView="emails.crps.mailregistrationsuccess";
		$recipientAddress=$architectDetails[0]->Email;
		$recipientName=$architectDetails[0]->Name;
		$referenceNo=$architectDetails[0]->ReferenceNo;
		$applicationDate=$architectDetails[0]->ApplicationDate;
		$mobileNo=$architectDetails[0]->MobileNo;
		if((int)$isServiceByArchitect==0){
			$mailIntendedTo=3;//1=Contractor,2=Consultant,3=Architect,4=Engineer,5=Specialized Trade
			$subject="Acknowledgement: Receipt of Application for Registration with CDB";
			$emailMessage="This is to acknowledge receipt of your application for registration of architect with Construction Development Board (CDB). Your application will be processed in due course. You can check status of your application using your Citizenship Id No. or Application No. from the <a href=”#”>CDB website</a>. You will also be notified through email when your application is approved.";
			$smsMessage="Your application for architect registration has been received. You can check status of your application using CID No. or Application No ($referenceNo) from CDB website.";
			$architectServiceSectorType=ArchitectModel::where('Id',$architectId)->pluck('CmnServiceSectorTypeId');
			if($architectServiceSectorType==CONST_CMN_SERVICESECTOR_PVT){
				$feeDetails=DB::select("select 'Private' as SectorType,ArchitectPvtAmount as NewRegistrationFee,ArchitectPvtValidity as RegistrationValidity from crpservice where Id=? limit 1",array(CONST_SERVICETYPE_NEW));
			}else{
				$feeDetails=DB::select("select 'Goverment' as SectorType,ArchitectGovtAmount as NewRegistrationFee,ArchitectGovtValidity as RegistrationValidity from crpservice where Id=? limit 1",array(CONST_SERVICETYPE_NEW));
			}
		}else{
			$mailIntendedTo=null;
			$feeDetails=array();
			$subject="Acknowledgement: Receipt of Application for CDB Service";
			$smsMessage="Your application for renewal of registration has been received. You can check status of your application using CID No. or Application No ($referenceNo) from CDB website.";
			$emailMessage="This is to acknowledge receipt of your application for Construction Development Board (CDB) services. Your application will be processed in due course. You can check status of your application using your Citizenship Id No. or Application No. from the <a href=”#”>CDB website</a>. You will also be notified through email when your application is approved.";
		}
		$mailData=array(
			'mailIntendedTo'=>$mailIntendedTo,
			'feeDetails'=>$feeDetails,
			'applicantName'=>$recipientName,
			'applicationNo'=>$referenceNo,
			'applicationDate'=>$applicationDate,
			'mailMessage'=>$emailMessage,
		);
		$this->sendEmailMessage($mailView,$mailData,$subject,$recipientAddress,$recipientName);
		$this->sendSms($smsMessage,$mobileNo);
		if((int)$isServiceByArchitect==0){
			Session::forget('ArchitectRegistrationId');
			return Redirect::route('applicantregistrationsuccess',array('linktoprint'=>'architect/printregistration','printreference'=>$architectId,'applicationno'=>$referenceNo));
		}else{
			return Redirect::to('architect/profile')->with('savedsuccessmessage','Your application was successfully submitted');
		}
	}
	public function architectList(){
		$parameters=array();
		$linkText='Edit';
		$link='architect/editdetails/';
		$architectId=Input::get('CrpArchitectId');
		$ARNo=Input::get('ARNo');
		$architectType=Input::get('ArchitectType');

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

		$query="select T1.Id,T1.ReferenceNo,T1.RegistrationExpiryDate,T1.ARNo,T1.ApplicationDate,Z.Name as Status,Z.ReferenceNo as StatusReference,T1.CIDNo,T1.Name as ArchitectName,T1.EmployerName,T1.Email,T1.MobileNo,T2.Name as Country,T3.NameEn as Dzongkhag,T4.Name as Salutation,T5.Id as ArchitectTypeId,T5.Name as ArchitectType from crparchitectfinal T1 left join cmnlistitem Z on Z.Id = T1.CmnApplicationRegistrationStatusId  join cmncountry T2 on T1.CmnCountryId=T2.Id join cmnlistitem T4 on T1.CmnSalutationId=T4.Id join cmnlistitem T5 on T1.CmnServiceSectorTypeId=T5.Id left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id where 1=1";
		if(Route::current()->getUri()=="architect/viewprintlist"){
			$linkText='View/Print';
			$link='architect/viewprintdetails/';
			$query.=" and T1.CmnApplicationRegistrationStatusId is not null";
		}elseif(Route::current()->getUri()=="architect/newcommentsadverserecordslist"){
			$linkText='Add';
			$link='architect/newcommentsadverserecords/';
			$query.=" and (T1.CmnApplicationRegistrationStatusId!=? or T1.CmnApplicationRegistrationStatusId!=?)";
			array_push($parameters,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW);
			array_push($parameters,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_REJECTED);
		}elseif(Route::current()->getUri()=="architect/editcommentsadverserecordslist"){
			$linkText='View';
			$link='architect/editcommentsadverserecords/';
			$query.=" and (T1.CmnApplicationRegistrationStatusId!=? or T1.CmnApplicationRegistrationStatusId!=?)";
			array_push($parameters,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW);
			array_push($parameters,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_REJECTED);
		}
		if((bool)$architectId!=NULL || (bool)$ARNo!=NULL || (bool)$architectType!=NULL){
			if((bool)$architectId!=NULL){
				$query.=" and T1.Id=?";
				array_push($parameters,$architectId);
			}
			if((bool)$ARNo!=NULL){
				$query.=" and T1.ARNo=?";
	            array_push($parameters,$ARNo);
			}
			if((bool)$architectType!=NULL){
				$query.=" and T1.CmnServiceSectorTypeId=?";
	            array_push($parameters,$architectType);
			}
		}
		$architectServiceSectorTypes=CmnListItemModel::serviceSectorType()->get(array('Id','Name'));
		$architects=ArchitectFinalModel::architectHardListAll()->get(array('Id','Name'));
		$architectLists=DB::select($query." order by ARNo,ArchitectName".$limit,$parameters);
		return View::make('crps.architectlist')
					->with('pageTitle','List of Architect')
					->with('link',$link)
					->with('linkText',$linkText)
					->with('ARNo',$ARNo)
					->with('architectType',$architectType)
					->with('architectServiceSectorTypes',$architectServiceSectorTypes)
					->with('architects',$architects)
					->with('architectId',$architectId)
					->with('architectLists',$architectLists);
	}
	public function architectDetails($architectId,$forReport = false){
		$registrationApprovedForPayment=0;
		$registrationApproved=0;
		$userArchitect=0;
		if(Route::current()->getUri()=="architect/verifyregistrationprocess/{architectid}"){
			$view="crps.architectverifyregistrationprocess";
			$modelPost="architect/mverifyregistration";
		}elseif(Route::current()->getUri()=="architect/approveregistrationprocess/{architectid}"){
			$view="crps.architectapproveregistrationprocess";
			$modelPost="architect/mapproveregistration";
		}elseif(Route::current()->getUri()=="architect/viewregistrationprocess/{architectid}"){
			$registrationApprovedForPayment=1;
			$registrationApproved=1;
			$view="crps.architectinformation";
			$modelPost=null;
		}elseif(Route::current()->getUri()=="architect/approvepaymentregistrationprocess/{architectid}"){
			$modelPost="";
			$registrationApprovedForPayment=1;
			$view="crps.architectinformation";
		}else{
		    if(!$forReport)
			    App::abort('404');
		}
		$architectServiceSectorType=ArchitectModel::where('Id',$architectId)->pluck('CmnServiceSectorTypeId');
		if($architectServiceSectorType==CONST_CMN_SERVICESECTOR_PVT){
			$feeDetails=DB::select("select Name as ServiceName,'Private' as Type,ArchitectPvtAmount as NewRegistrationFee,ArchitectPvtValidity as RegistrationValidity from crpservice where Id=? limit 1",array(CONST_SERVICETYPE_NEW));
		}else{
			$feeDetails=DB::select("select Name as ServiceName,'Government' as Type,ArchitectGovtAmount as NewRegistrationFee,ArchitectGovtValidity as RegistrationValidity from crpservice where Id=? limit 1",array(CONST_SERVICETYPE_NEW));
		}
		if($forReport){
		    return $feeDetails;
        }
		$architectInformations=ArchitectModel::architect($architectId)->get(array('crparchitect.Id','crparchitect.PaymentReceiptNo', 'crparchitect.PaymentReceiptDate','crparchitect.CmnApplicationRegistrationStatusId','crparchitect.ARNo','crparchitect.CIDNo','crparchitect.Name','crparchitect.Gewog','crparchitect.Village','crparchitect.Email','crparchitect.MobileNo','crparchitect.EmployerName','crparchitect.EmployerAddress','crparchitect.GraduationYear','crparchitect.NameOfUniversity','crparchitect.RemarksByVerifier','crparchitect.RemarksByApprover','crparchitect.RemarksByPaymentApprover','crparchitect.VerifiedDate','crparchitect.PaymentApprovedDate','crparchitect.RegistrationApprovedDate','T1.Name as ArchitectType','T2.Name as Salutation','T3.Name as Country','T4.NameEn as Dzongkhag','T5.Name as Qualification','T6.Name as UniversityCountry','T8.FullName as Verifier','T9.FullName as Approver','T10.FullName as PaymentApprover'));
		$architectAttachments=ArchitectAttachmentModel::attachment($architectId)->get(array('Id','DocumentName','DocumentPath'));
		return View::make($view)
					->with('modelPost',$modelPost)
					->with('architectId',$architectId)
					->with('architectServiceSectorType',$architectServiceSectorType)
					->with('feeDetails',$feeDetails)
					->with('architectInformations',$architectInformations)
					->with('architectAttachments',$architectAttachments)
					->with('registrationApprovedForPayment',$registrationApprovedForPayment)
					->with('registrationApproved',$registrationApproved)
					->with('userArchitect',$userArchitect);
	}
	public function editDetails($architectId){
        $loggedInUser = Auth::user()->Id;
        $userRoles=DB::table('sysuserrolemap')->where('SysUserId',$loggedInUser)->lists('SysRoleId');
        $isAdmin = false;
        if(in_array(CONST_ROLE_ADMINISTRATOR,$userRoles)){
            $isAdmin = true;
        }
		$registrationApprovedForPayment=0;
		$userArchitect=0;
		$architectServiceSectorType = CONST_CMN_SERVICESECTOR_GOVT;
		$architectInformations=ArchitectFinalModel::architect($architectId)->get(array('crparchitectfinal.Id','crparchitectfinal.ARNo','crparchitectfinal.CIDNo','crparchitectfinal.CmnApplicationRegistrationStatusId','crparchitectfinal.DeRegisteredDate','crparchitectfinal.BlacklistedDate','crparchitectfinal.DeregisteredRemarks','crparchitectfinal.BlacklistedRemarks','crparchitectfinal.Name','crparchitectfinal.Gewog','crparchitectfinal.Village','crparchitectfinal.Email','crparchitectfinal.MobileNo','crparchitectfinal.EmployerName','crparchitectfinal.RegistrationExpiryDate','crparchitectfinal.EmployerAddress','crparchitectfinal.GraduationYear','crparchitectfinal.NameOfUniversity','T1.Name as ArchitectType','T2.Name as Salutation','T3.Name as Country','T4.NameEn as Dzongkhag','T7.Name as Status','T5.Name as Qualification','T6.Name as UniversityCountry','T8.Name as Trade'));
		$architectAttachments=ArchitectAttachmentFinalModel::attachment($architectId)->get(array('Id','DocumentName','DocumentPath'));

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
					ON `T4`.`Id` = `T3`.`CmnProcuringAgencyId`
				WHERE `T2`.`ActualStartDate` IS NOT NULL
				AND `T1`.`CIDNo` =".$architectInformations[0]->CIDNo."
				GROUP BY `T3`.`Id`"
		);
		
		return View::make('crps.architectinformation')
					->with('isAdmin',$isAdmin)
					->with('architectServiceSectorType',$architectServiceSectorType)
					->with('architectId',$architectId)
					->with('architectInformations',$architectInformations)
					->with('CV',$CV)
					->with('architectAttachments',$architectAttachments)
					->with('registrationApprovedForPayment',$registrationApprovedForPayment)
					->with('userArchitect',$userArchitect);
	}
	public function verifyList(){
		$redirectUrl = Request::path();
		$recordLockException=false;
		$applicationPickedByUserFullName=null;
		if(Session::has('ApplicationAlreadyPicked')){
			$applicationPickedByUserFullName=Session::get('ApplicationAlreadyPicked');
			$recordLockException=true;
		}

		$serviceSectorTypeIdAll=Input::get('CmnServiceSectorTypeIdAll');
		$architectIdAll=Input::get('CrpArchitectIdAll');
		$fromDateAll=Input::get('FromDateAll');
		$toDateAll=Input::get('ToDateAll');
		/*--End of Parametrs for all the application-------*/
		$serviceSectorTypeIdMyTask=Input::get('CmnServiceSectorTypeIdMyTask');
		$architectIdMyTask=Input::get('CrpArchitectIdMyTask');
		$fromDateMyTask=Input::get('FromDateMyTask');
		$toDateMyTask=Input::get('ToDateMyTask');
		/*-----End of parameters for my task list----------*/
		$query="select T1.Id,T1.ReferenceNo,T1.ApplicationDate,T1.CIDNo,T1.Name as ArchitectName,T1.EmployerName,T1.Email,T1.MobileNo,T2.Name as Country,T3.NameEn as Dzongkhag,T4.Name as Salutation,T5.Name as ArchitectType from crparchitect T1 join cmncountry T2 on T1.CmnCountryId=T2.Id join cmnlistitem T4 on T1.CmnSalutationId=T4.Id join cmnlistitem T5 on T1.CmnServiceSectorTypeId=T5.Id left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id where T1.CmnApplicationRegistrationStatusId=? and T1.SysLockedByUserId is null and T1.CrpArchitectId is null";
		$queryMyTaskList="select T1.Id,T1.ReferenceNo,T1.ApplicationDate,T1.CIDNo,T1.Name as ArchitectName,T1.EmployerName,T1.Email,T1.MobileNo,T2.Name as Country,T3.NameEn as Dzongkhag,T4.Name as Salutation,T5.Name as ArchitectType from crparchitect T1 join cmncountry T2 on T1.CmnCountryId=T2.Id join cmnlistitem T4 on T1.CmnSalutationId=T4.Id join cmnlistitem T5 on T1.CmnServiceSectorTypeId=T5.Id left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id where T1.CmnApplicationRegistrationStatusId=? and T1.SysLockedByUserId=? and T1.CrpArchitectId is null";
		$parameters=array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW);
		$parametersMyTaskList=array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW,Auth::user()->Id);
		if((bool)$serviceSectorTypeIdAll!=NULL || (bool)$architectIdAll!=NULL || (bool)$fromDateAll!=NULL || (bool)$toDateAll!=NULL || (bool)$serviceSectorTypeIdMyTask!=NULL || (bool)$architectIdMyTask!=NULL || (bool)$fromDateMyTask!=NULL || (bool)$toDateMyTask!=NULL){
			if((bool)$serviceSectorTypeIdAll!=NULL){
				$query.=" and T1.CmnServiceSectorTypeId=?";
				array_push($parameters,$serviceSectorTypeIdAll);
			}
			if((bool)$architectIdAll!=NULL){
				$query.=" and T1.Id=?";
				array_push($parameters,$architectIdAll);
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
			if((bool)$serviceSectorTypeIdMyTask!=NULL){
				$queryMyTaskList.=" and T1.CmnServiceSectorTypeId=?";
				array_push($parametersMyTaskList,$serviceSectorTypeIdMyTask);
			}
			if((bool)$architectIdMyTask!=NULL){
				$queryMyTaskList.=" and T1.Id=?";
				array_push($parametersMyTaskList,$architectIdMyTask);
			}
			if((bool)$fromDateMyTask!=NULL){
				$fromDateAll=$this->convertDate($fromDateMyTask);
				$queryMyTaskList.=" and T1.ApplicationDate>=?";
	            array_push($parametersMyTaskList,$fromDateMyTask);
			}
			if((bool)$toDateMyTask!=NULL){
				$toDateAll=$this->convertDate($toDateMyTask);
				$queryMyTaskList.=" and T1.ApplicationDate<=?";
	            array_push($parametersMyTaskList,$toDateMyTask);
			}
		}
		$serviceSectorTypes=CmnListItemModel::serviceSectorType()->get(array('Id','Name'));
		$architectLists=DB::select($query." order by ApplicationDate,ReferenceNo,ArchitectName",$parameters);
		$architectMyTaskLists=DB::select($queryMyTaskList." order by ApplicationDate,ReferenceNo,ArchitectName",$parametersMyTaskList);
		return View::make('crps.architectregistrationapplicationprocesslist')
					->with('redirectUrl',$redirectUrl)
					->with('pageTitle',"Verify Architect Registration")
					->with('recordLockException',$recordLockException)
					->with('applicationPickedByUserFullName',$applicationPickedByUserFullName)
					->with('fromDateAll',$fromDateAll)
					->with('toDateAll',$toDateAll)
					->with('architectIdAll',$architectIdAll)
					->with('serviceSectorTypeIdAll',$serviceSectorTypeIdAll)
					->with('fromDateMyTask',$fromDateMyTask)
					->with('toDateMyTask',$toDateMyTask)
					->with('architectIdMyTask',$architectIdMyTask)
					->with('serviceSectorTypeIdMyTask',$serviceSectorTypeIdMyTask)
					->with('serviceSectorTypes',$serviceSectorTypes)
					->with('architectLists',$architectLists)
					->with('architectMyTaskLists',$architectMyTaskLists);
	}
	public function verifyRegistration(){
		$postedValues=Input::all();
		
		$instance=ArchitectModel::find($postedValues['ArchitectReference']);
		$instance->fill($postedValues);
		$instance->update();
		return Redirect::to('architect/verifyregistration')
						->with('savedsuccessmessage','The application has been successfully verified.');
	}
	public function approveList(){
		$recordLockException=false;
		$applicationPickedByUserFullName=null;
		if(Session::has('ApplicationAlreadyPicked')){
			$applicationPickedByUserFullName=Session::get('ApplicationAlreadyPicked');
			$recordLockException=true;
		}
		$serviceSectorTypeIdAll=Input::get('CmnServiceSectorTypeIdAll');
		$architectIdAll=Input::get('CrpArchitectIdAll');
		$fromDateAll=Input::get('FromDateAll');
		$toDateAll=Input::get('ToDateAll');
		/*--End of Parametrs for all the application-------*/
		$serviceSectorTypeIdMyTask=Input::get('CmnServiceSectorTypeIdMyTask');
		$architectIdMyTask=Input::get('CrpArchitectIdMyTask');
		$fromDateMyTask=Input::get('FromDateMyTask');
		$toDateMyTask=Input::get('ToDateMyTask');
		/*-----End of parameters for my task list----------*/
		$query="select T1.Id,T1.ReferenceNo,T1.ApplicationDate,T1.CIDNo,T1.Name as ArchitectName,T1.EmployerName,T1.Email,T1.MobileNo,T2.Name as Country,T3.NameEn as Dzongkhag,T4.Name as Salutation,T5.Name as ArchitectType from crparchitect T1 join cmncountry T2 on T1.CmnCountryId=T2.Id left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id join cmnlistitem T4 on T1.CmnSalutationId=T4.Id join cmnlistitem T5 on T1.CmnServiceSectorTypeId=T5.Id where T1.CmnApplicationRegistrationStatusId=? and T1.SysLockedByUserId is null and T1.CrpArchitectId is null";
		$queryMyTaskList="select T1.Id,T1.ReferenceNo,T1.ApplicationDate,T1.CIDNo,T1.Name as ArchitectName,T1.EmployerName,T1.Email,T1.MobileNo,T2.Name as Country,T3.NameEn as Dzongkhag,T4.Name as Salutation,T5.Name as ArchitectType from crparchitect T1 join cmncountry T2 on T1.CmnCountryId=T2.Id left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id join cmnlistitem T4 on T1.CmnSalutationId=T4.Id join cmnlistitem T5 on T1.CmnServiceSectorTypeId=T5.Id where T1.CmnApplicationRegistrationStatusId=? and T1.SysLockedByUserId=? and T1.CrpArchitectId is null";
		if(Request::path()=="architect/approvefeepayment"){
            $query.=" and case when T1.ApplicationDate <= '2017-08-02' then DATEDIFF(NOW(),T1.RegistrationApprovedDate)<=90 else DATEDIFF(NOW(),T1.RegistrationApprovedDate)<=30 end";
            $queryMyTaskList.=" and case when T1.ApplicationDate <= '2017-08-02' then DATEDIFF(NOW(),T1.RegistrationApprovedDate)<=90 else DATEDIFF(NOW(),T1.RegistrationApprovedDate)<=30 end";
			$parameters=array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVEDFORPAYMENT);
			$parametersMyTaskList=array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVEDFORPAYMENT,Auth::user()->Id);
		}else{
			$parameters=array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_VERIFIED);
			$parametersMyTaskList=array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_VERIFIED,Auth::user()->Id);
		}
		if((bool)$serviceSectorTypeIdAll!=NULL || (bool)$architectIdAll!=NULL || (bool)$fromDateAll!=NULL || (bool)$toDateAll!=NULL || (bool)$serviceSectorTypeIdMyTask!=NULL || (bool)$architectIdMyTask!=NULL || (bool)$fromDateMyTask!=NULL || (bool)$toDateMyTask!=NULL){
			if((bool)$serviceSectorTypeIdAll!=NULL){
				$query.=" and T1.CmnServiceSectorTypeId=?";
				array_push($parameters,$serviceSectorTypeIdAll);
			}
			if((bool)$architectIdAll!=NULL){
				$query.=" and T1.Id=?";
				array_push($parameters,$architectIdAll);
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
			if((bool)$serviceSectorTypeIdMyTask!=NULL){
				$queryMyTaskList.=" and T1.CmnServiceSectorTypeId=?";
				array_push($parametersMyTaskList,$serviceSectorTypeIdMyTask);
			}
			if((bool)$architectIdMyTask!=NULL){
				$queryMyTaskList.=" and T1.Id=?";
				array_push($parametersMyTaskList,$architectIdMyTask);
			}
			if((bool)$fromDateMyTask!=NULL){
				$fromDateAll=$this->convertDate($fromDateMyTask);
				$queryMyTaskList.=" and T1.ApplicationDate>=?";
	            array_push($parametersMyTaskList,$fromDateMyTask);
			}
			if((bool)$toDateMyTask!=NULL){
				$toDateAll=$this->convertDate($toDateMyTask);
				$queryMyTaskList.=" and T1.ApplicationDate<=?";
	            array_push($parametersMyTaskList,$toDateMyTask);
			}
		}
		$serviceSectorTypes=CmnListItemModel::serviceSectorType()->get(array('Id','Name'));
		$architectLists=DB::select($query." order by ApplicationDate,ReferenceNo,ArchitectName",$parameters);
		$architectMyTaskLists=DB::select($queryMyTaskList." order by ApplicationDate,ReferenceNo,ArchitectName",$parametersMyTaskList);
		return View::make('crps.architectregistrationapplicationprocesslist')
					->with('pageTitle',"Approve Architect Registration")
					->with('recordLockException',$recordLockException)
					->with('applicationPickedByUserFullName',$applicationPickedByUserFullName)
					->with('fromDateAll',$fromDateAll)
					->with('toDateAll',$toDateAll)
					->with('architectIdAll',$architectIdAll)
					->with('serviceSectorTypeIdAll',$serviceSectorTypeIdAll)
					->with('fromDateMyTask',$fromDateMyTask)
					->with('toDateMyTask',$toDateMyTask)
					->with('architectIdMyTask',$architectIdMyTask)
					->with('serviceSectorTypeIdMyTask',$serviceSectorTypeIdMyTask)
					->with('serviceSectorTypes',$serviceSectorTypes)
					->with('architectLists',$architectLists)
					->with('architectMyTaskLists',$architectMyTaskLists);
	}
	public function approveRegistration(){
		$postedValues=Input::all();
//		$postedValues['ARNo']=lastUsedArchitectNo()+1;
		$postedValues['RegistrationApprovedDate']=$this->convertDateTime($postedValues['RegistrationApprovedDate']);
		$postedValues['RegistrationExpiryDate']=$this->convertDateTime($postedValues['RegistrationExpiryDate']);
		DB::beginTransaction();
		try{
			$instance=ArchitectModel::find($postedValues['ArchitectReference']);
			$instance->fill($postedValues);
			$instance->update();
			$uuid=DB::select("select uuid() as Id");
			$generatedId=$uuid[0]->Id;
			$architectDetails=ArchitectModel::architectHardList($postedValues['ArchitectReference'])->get(array('ARNo','TPN','Name','Email','ReferenceNo','ApplicationDate','MobileNo','RemarksByVerifier','RemarksByApprover'));
			$ArNo=$architectDetails[0]->ArNo;
			$recipientAddress=$architectDetails[0]->Email;
			$recipientName=$architectDetails[0]->Name;
			$referenceNo=$architectDetails[0]->ReferenceNo;
			$applicationDate=$architectDetails[0]->ApplicationDate;
			$mobileNo=$architectDetails[0]->MobileNo;
			$tpn=$architectDetails[0]->TPN;
			$remarksByVerifier = $architectDetails[0]->RemarksByVerifier;
			$remarksByApprover = $architectDetails[0]->RemarksByApprover;
			if($postedValues['CmnApplicationRegistrationStatusId']==CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED){
				$plainPassword=substr(md5(uniqid(mt_rand(), true)), 0, 5);
				$plainPassword.="@#".date('d');
				$password=Hash::make($plainPassword);
		        $userCredentials=array('Id'=>$generatedId,'username'=>$recipientAddress,'password'=>$password,'FullName'=>$recipientName,'Status'=>'1','CreatedBy'=>Auth::user()->Id);
				$roleData=array('SysUserId'=>$generatedId,'SysRoleId'=>CONST_ROLE_ARCHITECT,'CreatedBy'=>Auth::user()->Id);
				User::create($userCredentials);
				RoleUserMapModel::create($roleData);
				DB::statement("call ProCrpArchitectNewRegistrationFinalData(?,?,?,?)",array(Input::get('ArchitectReference'),$generatedId,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED,Auth::user()->Id));
				$mailView="emails.crps.mailregistrationpaymentcompletion";
				$subject="Activation of Your CDB Certificate";
				$smsMessage="Your application for architect registration has been approved by CDB and your certificate has been activated. Your AR No. is $ArNo. Your username is $recipientAddress and password is $plainPassword";
				$mailData=array(
					'applicantName'=>$recipientName,
					'applicationNo'=>$referenceNo,
					'applicationDate'=>$applicationDate,
					'username'=>$recipientAddress,
					'password'=>$plainPassword,
					'tpn'=>$tpn,
					'mailMessage'=>"Construction Development Board (CDB) has verified and approved your application for registration of goverment architect with CDB. Your AR No. is ".$ArNo." and your CDB certificate has been activated. You can view and print it by logging in to your account using below given user credentials. Please change your password regularly to make your account secure.<br/>Remarks by Verifier: $remarksByVerifier <br/>Remarks by Approver: $remarksByApprover",
				);
			}else{
				$mailIntendedTo=3;//1=Contractor,2=Consultant,3=Architect,4=Engineer,5=Specialized Trade
				$mailView="emails.crps.mailapplicationapproved";
				$subject="Approval of Your Registration with CDB";
				$smsMessage="Your application for architect registration has been approved by CDB. Please check your email for detailed information regarding your fees.";
				$architectServiceSectorType=ArchitectModel::where('Id',Input::get('ArchitectReference'))->pluck('CmnServiceSectorTypeId');
				if($architectServiceSectorType==CONST_CMN_SERVICESECTOR_PVT){
					$feeDetails=DB::select("select 'Private' as SectorType,ArchitectPvtAmount as NewRegistrationFee,ArchitectPvtValidity as RegistrationValidity from crpservice where Id=? limit 1",array(CONST_SERVICETYPE_NEW));
				}else{
					$feeDetails=DB::select("select 'Goverment' as SectorType,ArchitectGovtAmount as NewRegistrationFee,ArchitectGovtValidity as RegistrationValidity from crpservice where Id=? limit 1",array(CONST_SERVICETYPE_NEW));
				}
				$mailData=array(
					'mailIntendedTo'=>$mailIntendedTo,
					'feeDetails'=>$feeDetails,
					'applicantName'=>$recipientName,
					'applicationNo'=>$referenceNo,
					'applicationDate'=>$applicationDate,
					'mailMessage'=>"Construction Development Board (CDB) has verified and approved your application for registration of private architect with CDB.  However, you need to pay your registration fees as per the details given below within one month (30 days) to the CDB Office or the Nearest Regional Revenue and Customs Office (RRCO). We will email you your username and password upon confirmation of your payment by CDB. Upon payment to the RRCO, email money receipt to Accountant@cdb.gov.bt or registration@cdb.gov.bt.<br/>Remarks by Verifier: $remarksByVerifier <br/>Remarks by Approver: $remarksByApprover"
				);
			}
		}catch(Exception $e){
			DB::rollback();
        	throw $e;
		}
		DB::commit();
		$this->sendEmailMessage($mailView,$mailData,$subject,$recipientAddress,$recipientName);
		$this->sendSms($smsMessage,$mobileNo);
		return Redirect::to('architect/approveregistration')
						->with('savedsuccessmessage','The application has been successfully approved.');
	}
	public function approvePayment(){
		$postedValues=Input::except('Amount');
		$postedValues['PaymentReceiptDate']=$this->convertDate($postedValues['PaymentReceiptDate']);
		$postedValues["InitialDate"] = date('Y-m-d');
		DB::beginTransaction();
		try{
			$instance=ArchitectModel::find($postedValues['ArchitectReference']);
			$instance->fill($postedValues);
			$instance->update();
			$uuid=DB::select("select uuid() as Id");
			$generatedId=$uuid[0]->Id;
			$architectDetails=ArchitectModel::architectHardList($postedValues['ArchitectReference'])->get(array('ARNo','CmnServiceSectorTypeId','Name','Email','ReferenceNo','ApplicationDate','MobileNo','RemarksByVerifier','RemarksByApprover'));
			$ArNo=$architectDetails[0]->ArNo;
			$recipientAddress=$architectDetails[0]->Email;
			$recipientName=$architectDetails[0]->Name;
			$referenceNo=$architectDetails[0]->ReferenceNo;
			$applicationDate=$architectDetails[0]->ApplicationDate;
			$mobileNo=$architectDetails[0]->MobileNo;
			$remarksByVerifier = $architectDetails[0]->RemarksByVerifier;
			$remarksByApprover = $architectDetails[0]->RemarksByApprover;
			$plainPassword=substr(md5(uniqid(mt_rand(), true)), 0, 5);
			$plainPassword.="@#".date('d');
			$password=Hash::make($plainPassword);
	        $userCredentials=array('Id'=>$generatedId,'username'=>$recipientAddress,'password'=>$password,'FullName'=>$recipientName,'Status'=>'1','CreatedBy'=>Auth::user()->Id);
			$roleData=array('SysUserId'=>$generatedId,'SysRoleId'=>CONST_ROLE_ARCHITECT,'CreatedBy'=>Auth::user()->Id);
			/*Fee Structure*/
			if($architectDetails[0]->CmnServiceSectorTypeId==CONST_CMN_SERVICESECTOR_PVT){
				$feeDetails=DB::select("select 'Private' as SectorType,ArchitectPvtAmount as NewRegistrationFee,ArchitectPvtValidity as RegistrationValidity from crpservice where Id=? limit 1",array(CONST_SERVICETYPE_NEW));
			}else{
				$feeDetails=DB::select("select 'Goverment' as SectorType,ArchitectGovtAmount as NewRegistrationFee,ArchitectGovtValidity as RegistrationValidity from crpservice where Id=? limit 1",array(CONST_SERVICETYPE_NEW));
			}
			/*ENd fee structure*/

			User::create($userCredentials);
			RoleUserMapModel::create($roleData);
			DB::statement("call ProCrpArchitectNewRegistrationFinalData(?,?,?,?)",array(Input::get('ArchitectReference'),$generatedId,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED,Auth::user()->Id));

			/*FOR REVENUE COLLECTION*/
			$revenueCollectionArray['Amount'] = Input::get('Amount');
			$revenueCollectionArray['Id'] = $this->UUID();
			$revenueCollectionArray['CrpArchitectFinalId'] = Input::get('ArchitectReference');
			ArchitectRegistrationPayment::create($revenueCollectionArray);
			/*END REVENUE COLLECTION*/
		}catch(Exception $e){
			DB::rollback();
        	throw $e;
		}
		DB::commit();
		$mailView="emails.crps.mailregistrationpaymentcompletion";
		$subject="Activation of Your CDB Certificate";
		$mailData=array(
			'mailIntendedTo' => 3,
			'applicantName'=>$recipientName,
			'applicationNo'=>$referenceNo,
			'applicationDate'=>$applicationDate,
			'username'=>$recipientAddress,
			'password'=>$plainPassword,
			'feeStructures'=>$feeDetails,
			'mailMessage'=>"This is to acknowledge receipt of your payment vide Receipt No. ".Input::get('PaymentReceiptNo')." dated ".Input::get('PaymentReceiptDate')." for architect registration with Construction Development Board (CDB). Your AR No. is ".$ArNo." and your CDB certificate has been activated. You can view and print it by logging in to your account using below given user credentials. Please change your password regularly to make your account secure.<br/>Remarks by Verifier: $remarksByVerifier <br/>Remarks by Approver: $remarksByApprover",
		);
		$smsMessage="Your registration fees for architect registration has been received by CDB and your certificate has been activated. Your AR No. is $ArNo. Your username is $recipientAddress and password is $plainPassword";
		$this->sendEmailMessage($mailView,$mailData,$subject,$recipientAddress,$recipientName);
		$this->sendSms($smsMessage,$mobileNo);
		return Redirect::to('architect/approvefeepayment')->with('savedsuccessmessage','Payment aganist the registration successfully recorded.');
	}
	public function rejectRegistration(){
		DB::beginTransaction();
		try{
			$rejectionCode=str_random(30);
			$architectId=Input::get('ArchitectReference');
			$architect = ArchitectModel::find($architectId);
			$architect->CmnApplicationRegistrationStatusId=CONST_CMN_APPLICATIONREGISTRATIONSTATUS_REJECTED;
			$architect->RemarksByRejector=Input::get('RemarksByRejector');
			$architect->RejectedDate=Input::get('RejectedDate');
			$architect->SysRejectorUserId=Auth::user()->Id;
			$architect->SysLockedByUserId=NULL;
			$architect->SysRejectionCode=$rejectionCode;
			$architect->save();
		}catch(Exception $e){
			DB::rollback();
        	throw $e;
		}
		DB::commit();
		$architectDetails=ArchitectModel::architectHardList(Input::get('ArchitectReference'))->get(array('Name','Email','ReferenceNo','ApplicationDate','RemarksByRejector','SysRejectionCode','MobileNo'));
		/*----------------------Contractor Email Details and New Details------------------*/
		$recipientAddress=$architectDetails[0]->Email;
		$recipientName=$architectDetails[0]->NameOfFirm;
		$applicationNo=$architectDetails[0]->ReferenceNo;
		$applicationDate=$architectDetails[0]->ApplicationDate;
		$remarksByRejector=$architectDetails[0]->RemarksByRejector;
		$rejectionSysCode=$architectDetails[0]->SysRejectionCode;
		$mobileNo=$architectDetails[0]->MobileNo;
		$mailView="emails.crps.mailapplicationrejected";
		$subject="Rejection of Your Registration with CDB";
		$mailData=array(
			'prefix'=>'architect',
			'applicantName'=>$recipientName,
			'applicationNo'=>$applicationNo,
			'applicationDate'=>$applicationDate,
			'remarksByRejector'=>$remarksByRejector,
			'referenceApplicant'=>Input::get('ArchitectReference'),
			'rejectionSysCode'=>$rejectionSysCode,
			'mailMessage'=>"Construction Development Board (CDB) has rejected your application for registration of architect with CDB. Please read the reason for rejection given below and reapply by making the necessary corrections.",
		);
		$smsMessage="Your application for architect registration has been rejected. Please check your email ($recipientAddress) to view the reason for rejection.";
		$this->sendEmailMessage($mailView,$mailData,$subject,$recipientAddress,$recipientName);
		$this->sendSms($smsMessage,$mobileNo);
		return Redirect::to('architect/'.Input::get('RedirectRoute'))->with('savedsuccessmessage','The application has been rejected.');
	}
	public function checkRejectedSecurityCode($architectReference,$securityCode){
		if(strlen($architectReference)==36 && strlen($securityCode)==30){
			$checkArchitectReference=ArchitectModel::where('SysRejectionCode',$securityCode)->pluck('Id');
			$currentStatus=ArchitectModel::where('Id',$checkArchitectReference)->pluck('CmnApplicationRegistrationStatusId');
			$rejectedDate=ArchitectModel::where('Id',$checkArchitectReference)->pluck('RejectedDate');
			$rejectedDate=new DateTime($rejectedDate);
			$currentDate=new DateTime(date('Y-m-d'));
			$noOfDays=$rejectedDate->diff($currentDate);
			if($checkArchitectReference==$architectReference && $currentStatus==CONST_CMN_APPLICATIONREGISTRATIONSTATUS_REJECTED && (int)$noOfDays->d < 31){
				DB::table('crparchitect')->where('Id',$architectReference)->update(array('ApplicationDate'=>date('Y-m-d')));
				return Redirect::to('architect/registration/'.$architectReference.'?editbyapplicant=true&rejectedapplicationreapply=true');	
			}else{
				return Redirect::to('ezhotin/rejectedapplicationmessage');
			}
		}else{
			App::abort('404');
		}
	}
	public function setRecordLock($architectId){
		$pickerByUserFullName=null;
		$redirectUrl=Input::get('redirectUrl');
		$notification = Input::get('notification');
		if((bool)$notification){
			DB::table('sysapplicationnotification')->where('ApplicationId',$architectId)->update(array('IsRead'=>1));
		}
		$hasBeenPicked=ArchitectModel::architectHardList($architectId)->pluck('SysLockedByUserId');
		if((bool)$hasBeenPicked!=null){
			$pickerByUserFullName=User::where('Id',$hasBeenPicked)->pluck('FullName');
		}else{
			$architect=ArchitectModel::find($architectId);
			$architect->SysLockedByUserId=Auth::user()->Id;
			$architect->save();
		}
		return Redirect::to($redirectUrl)->with('ApplicationAlreadyPicked',$pickerByUserFullName);
	}
	public function newCommentAdverseRecord($architectId){
		$architect=ArchitectFinalModel::architectHardList($architectId)->get(array('Id','ARNo','Name'));
		return View::make('crps.architectnewadverserecordsandcomments')
					->with('architectId',$architectId)
					->with('architect',$architect);	
	}
	public function editCommentAdverseRecord($architectId){
		$architect=ArchitectFinalModel::architectHardList($architectId)->get(array('Id','ARNo','Name'));
		$commentsAdverseRecords=ArchitectCommentAdverseRecordModel::commentAdverseRecordList($architectId)->get(array('Id','Date','Remarks','Type'));
		return View::make('crps.architecteditadverserecordscomments')
					->with('architect',$architect)
					->with('commentsAdverseRecords',$commentsAdverseRecords);
	}
	public function saveCommentAdverseRecord(){
		$postedValues=Input::all();
		$date=$this->convertDate(Input::get('Date'));
		$postedValues['Date']=$date;
		$validation = new ArchitectCommentAdverseRecordModel;
		if(!($validation->validate($postedValues))){
		    $errors = $validation->errors();
		    return Redirect::to('architect/newcommentsadverserecords/'.$postedValues['CrpArchitectId'])->withErrors($errors)->withInput();
		}
		ArchitectCommentAdverseRecordModel::create($postedValues);
		return Redirect::to('architect/newcommentsadverserecordslist')->with('savedsuccessmessage','Comment/Adverse Record sucessfully added.');
	}
	public function updateCommentAdverseRecord(){
		$postedValues=Input::all();
		$date=$this->convertDate(Input::get('Date'));
		$postedValues['Date']=$date;
		$instance=ArchitectCommentAdverseRecordModel::find($postedValues['Id']);
		$instance->fill($postedValues);
		$instance->update();
		return Redirect::to('architect/editcommentsadverserecords/'.$postedValues['CrpArchitectId'])->with('savedsuccessmessage','Record has been successfully updated');;
	}
	public function blacklistDeregisterList(){
		$reRegistration=1;
		$type=3;
		$parameters=array();
		$architectId=Input::get('CrpArchitectId');
		$ARNo=Input::get('ARNo');
		$query="select T1.Id,T1.ARNo,T1.CIDNo,T1.Name as ArchitectName,T1.EmployerName,T1.Email,T1.MobileNo,T2.Name as Country,T3.NameEn as Dzongkhag,T4.Name as Salutation from crparchitectfinal T1 join cmncountry T2 on T1.CmnCountryId=T2.Id left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id join cmnlistitem T4 on T1.CmnSalutationId=T4.Id where 1=1";
		if(Request::path()=="architect/deregister"){
			$type=1;
			$reRegistration=0;
			$captionHelper="Registered";
			$captionSubject="Deregistration of Architects";
			$query.=" and T1.CmnApplicationRegistrationStatusId=?";
			array_push($parameters,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED);
		}elseif(Request::path()=="architect/blacklist"){
			$type=1;
			$reRegistration=0;
			$captionHelper="Registered";
			$captionSubject="Blacklisting of Architects";
			$query.=" and T1.CmnApplicationRegistrationStatusId=?";
			array_push($parameters,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED);
		}
		elseif(Request::path()=="architect/reregistration"){
			$captionHelper="Deregistered or Blacklisted";
			$captionSubject="Re-registration of Architect";
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
		if((bool)$architectId!=NULL || (bool)$ARNo!=NULL){
			if((bool)$architectId!=NULL){
				$query.=" and T1.Id=?";
				array_push($parameters,$architectId);
			}
			if((bool)$ARNo!=NULL){
				$query.=" and T1.ARNo=?";
	            array_push($parameters,$ARNo);
			}
		}
		$architects=ArchitectFinalModel::architectHardListAll()->get(array('Id','Name'));
		$architectLists=DB::select($query." order by ArchitectName".$limit,$parameters);
		return View::make('crps.architectderegistrationlist')
					->with('ARNo',$ARNo)
					->with('type',$type)
					->with('architectId',$architectId)
					->with('captionHelper',$captionHelper)
					->with('captionSubject',$captionSubject)
					->with('reRegistration',$reRegistration)
					->with('architects',$architects)
					->with('architectLists',$architectLists);
	}
	public function deregisterBlackListRegistration(){
		$postedValues=Input::all();
		$architectReference=$postedValues['CrpArchitectId'];
		$architectUserId=ArchitectFinalModel::where('Id',$architectReference)->pluck('SysUserId');

		DB::beginTransaction();
		try{
			if(Input::has('DeRegisteredDate')){
				$postedValues['DeRegisteredDate']=$this->convertDate($postedValues['DeRegisteredDate']);
			}elseif(Input::has('BlacklistedDate')){
				$postedValues['BlacklistedDate']=$this->convertDate($postedValues['BlacklistedDate']);
			}else{
				$postedValues['ReRegistrationDate']=$this->convertDate($postedValues['ReRegistrationDate']);
			}
			$instance=ArchitectFinalModel::find($architectReference);
			$instance->fill($postedValues);
			$instance->update();
			$userInstance=User::find($architectUserId);

			if($postedValues['CmnApplicationRegistrationStatusId']==CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED){
				$redirectRoute="reregistration";
				if((bool)$userInstance){
					$userInstance->Status=1;
					$userInstance->save();
				}

			}else{
				if(Input::has('BlacklistedRemarks')){
					$redirectRoute="blacklist";
				}else{
					$redirectRoute="deregister";
				}
				if((bool)$userInstance){
					$userInstance->Status=0;
					$userInstance->save();
				}
				/*---Insert Adverse Record i.e the remarks if the consultant is deregistered/blacklisted*/
				if(Input::has('BlacklistedRemarks')){
					$architectAdverserecordInstance = new ArchitectCommentAdverseRecordModel;
					$architectAdverserecordInstance->CrpArchitectFinalId = $architectReference;
					$architectAdverserecordInstance->Date=date('Y-m-d');
					$architectAdverserecordInstance->Remarks=Input::get('BlacklistedRemarks');
					$architectAdverserecordInstance->Type=2;
					$architectAdverserecordInstance->save();
				}else{
					$architectAdverserecordInstance = new ArchitectCommentAdverseRecordModel;
					$architectAdverserecordInstance->CrpArchitectFinalId = $architectReference;
					$architectAdverserecordInstance->Date=date('Y-m-d');
					$architectAdverserecordInstance->Remarks=Input::get('DeregisteredRemarks');
					$architectAdverserecordInstance->Type=2;
					$architectAdverserecordInstance->save();
				}	
			}
		}catch(Exception $e){
			DB::rollback();
        	throw $e;
		}
		DB::commit();
		return Redirect::to('architect/blacklist')->with('savedsuccessmessage','Successfully updated');
	}
	public function printDetails($architectId){
		if(Route::current()->getUri()=="architect/viewprintdetails/{architectid}"){
			$data['isFinalPrint']=1;
			$architectInformations=ArchitectFinalModel::architect($architectId)->get(array('crparchitectfinal.ARNo','crparchitectfinal.CIDNo','crparchitectfinal.Name','crparchitectfinal.Gewog','crparchitectfinal.Village','crparchitectfinal.Email','crparchitectfinal.MobileNo','crparchitectfinal.EmployerName','crparchitectfinal.EmployerAddress','crparchitectfinal.GraduationYear','crparchitectfinal.NameOfUniversity','T1.Name as ArchitectType','T2.Name as Salutation','T3.Name as Country','T4.NameEn as Dzongkhag','T5.Name as Qualification','T6.Name as UniversityCountry','T7.Name as CurrentStatus'));
		}else{
			$data['isFinalPrint']=0;
			$architectServiceSectorType=ArchitectModel::where('Id',$architectId)->pluck('CmnServiceSectorTypeId');
			if($architectServiceSectorType==CONST_CMN_SERVICESECTOR_PVT){
				$feeDetails=DB::select("select 'Private' as SectorType,ArchitectPvtAmount as NewRegistrationFee,ArchitectPvtValidity as RegistrationValidity from crpservice where Id=? limit 1",array(CONST_SERVICETYPE_NEW));
			}else{
				$feeDetails=DB::select("select 'Goverment' as SectorType,ArchitectGovtAmount as NewRegistrationFee,ArchitectGovtValidity as RegistrationValidity from crpservice where Id=? limit 1",array(CONST_SERVICETYPE_NEW));
			}
			$data['feeDetails']=$feeDetails;
			$architectInformations=ArchitectModel::architect($architectId)->get(array('crparchitect.ARNo','crparchitect.CIDNo','crparchitect.Name','crparchitect.Gewog','crparchitect.Village','crparchitect.Email','crparchitect.MobileNo','crparchitect.EmployerName','crparchitect.EmployerAddress','crparchitect.GraduationYear','crparchitect.NameOfUniversity','crparchitect.RemarksByVerifier','crparchitect.RemarksByApprover','RemarksByPaymentApprover','T1.Name as ArchitectType','T2.Name as Salutation','T3.Name as Country','T4.NameEn as Dzongkhag','T5.Name as Qualification','T6.Name as UniversityCountry','T7.Name as CurrentStatus'));
		}
		$data['printTitle']='Architect Information';
		$data['architectInformations']=$architectInformations;
		$pdf = App::make('dompdf');
		$pdf->loadView('printpages.architectviewprintinformation',$data)->setPaper('a4')->setOrientation('potrait');
		return $pdf->stream();
	}
	public function checkARNo(){
		$inputARNo=Input::get('inputCDBNo');
		$ARNoFinalCount=ArchitectFinalModel::architectHardListAll()->where('ARNo',$inputARNo)->count();
		$ARNoCount=ArchitectModel::architectHardListAll()->where('ARNo',$inputARNo)->whereIn('CmnApplicationRegistrationStatusId',array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVEDFORPAYMENT,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_VERIFIED))->count();
		if((int)$ARNoCount>0 || (int)$ARNoFinalCount>0){
			return 0;
		}
		return 1;
	}
    public function fetchArchitectsJSON(){
        $term = Input::get('term');
        $architects = DB::table('crparchitectfinal')->where(DB::raw('TRIM(Name)'),DB::raw('like'),"%$term%")->get(array('Id',DB::raw('TRIM(Name) as Name')));
        $architectsJSON = array();
        foreach($architects as $architect){
            array_push($architectsJSON,array('id'=>$architect->Id,'value'=>trim($architect->Name)));
        }
        return Response::json($architectsJSON);
    }
    public function deleteCommentAdverseRecord(){
    	$id = Input::get('id');
    	try{
    		DB::table('crparchitectcommentsadverserecord')->where('Id',$id)->delete();	
    		return 1;
    	}catch(Exception $e){
    		return 0;
    	}
    }
	public function viewRegistrationList(){
		$serviceSectorTypeIdMyTask=Input::get('CmnServiceSectorTypeIdMyTask');
		$architectIdMyTask=Input::get('CrpArchitectIdMyTask');
		$fromDateMyTask='2016-06-01';
		$toDateMyTask=Input::get('ToDateMyTask');
		$parametersMyTaskList = array();
		$serviceSectorTypes=CmnListItemModel::serviceSectorType()->get(array('Id','Name'));
		$query="select T1.Id,T1.ReferenceNo,T1.ApplicationDate,T1.CIDNo,T1.Name as ArchitectName,T1.EmployerName,T1.Email,T1.MobileNo,T2.Name as Country,T3.NameEn as Dzongkhag,T4.Name as Salutation,T5.Name as ArchitectType from (crparchitect T1 join crparchitectfinal X on X.Id = T1.Id) join cmncountry T2 on T1.CmnCountryId=T2.Id join cmnlistitem T4 on T1.CmnSalutationId=T4.Id join cmnlistitem T5 on T1.CmnServiceSectorTypeId=T5.Id left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id where T1.CmnApplicationRegistrationStatusId=? and T1.SysLockedByUserId is null and T1.CrpArchitectId is null";
		$parametersMyTaskList=array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED);
		if((bool)$serviceSectorTypeIdMyTask!=NULL || (bool)$architectIdMyTask!=NULL || (bool)$fromDateMyTask!=NULL || (bool)$toDateMyTask!=NULL){
			if((bool)$serviceSectorTypeIdMyTask!=NULL){
				$query.=" and T1.CmnServiceSectorTypeId=?";
				array_push($parametersMyTaskList,$serviceSectorTypeIdMyTask);
			}
			if((bool)$architectIdMyTask!=NULL){
				$query.=" and T1.Id=?";
				array_push($parametersMyTaskList,$architectIdMyTask);
			}
			if((bool)$fromDateMyTask!=NULL){
				$fromDateAll=$this->convertDate($fromDateMyTask);
				$query.=" and T1.ApplicationDate>=?";
				array_push($parametersMyTaskList,$fromDateMyTask);
			}
			if((bool)$toDateMyTask!=NULL){
				$toDateAll=$this->convertDate($toDateMyTask);
				$query.=" and T1.ApplicationDate<=?";
				array_push($parametersMyTaskList,$toDateMyTask);
			}
		}
		$architectMyTaskLists=DB::select($query." and X.SysFinalApproverUserId is null order by ApplicationDate,T1.Name",$parametersMyTaskList);
		return View::make('crps.architectregistrationviewlist')
			->with('pageTitle',"Approve Architect's Registration")
			->with('fromDateMyTask',$fromDateMyTask)
			->with('toDateMyTask',$toDateMyTask)
			->with('architectIdMyTask',$architectIdMyTask)
			->with('serviceSectorTypeIdMyTask',$serviceSectorTypeIdMyTask)
			->with('serviceSectorTypes',$serviceSectorTypes)
			->with('architectMyTaskLists',$architectMyTaskLists);
	}
	public function saveFinalRemarks(){
		DB::beginTransaction();
		$redirectRoute = 'architect/viewapprovedapplications';
		try{
			if(Input::has('IsServiceApplication')){
				$redirectRoute = 'architect/viewserviceapplication';
				$object = ArchitectModel::find(Input::get('ArchitectReference'));
			}else{
				$redirectRoute = 'architect/viewapprovedapplications';
				$object = ArchitectFinalModel::find(Input::get('ArchitectReference'));
			}
			$object->SysFinalApproverUserId = Input::get('SysFinalApproverUserId');
			$object->SysFinalApprovedDate = Input::get('SysFinalApprovedDate');
			$object->RemarksByFinalApprover = Input::get('RemarksByFinalApprover');
			$object->update();
		}catch(Exception $e){
			DB::rollBack();
			return Redirect::to($redirectRoute)->with('customerrormessage','Something went wrong!');
		}
		DB::commit();
		return Redirect::to($redirectRoute)->with('savedsuccessmessage','Record has been updated!');
	}
}