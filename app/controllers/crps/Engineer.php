<?php
class Engineer extends CrpsController{
	public function defaultIndex(){
		$feeDetails=DB::select("select EngineerGovtAmount,EngineerGovtValidity,EngineerPvtAmount,EngineerPvtValidity from crpservice where Id=? limit 1",array(CONST_SERVICETYPE_NEW));
		return View::make('crps.engineerindex')
					->with('feeDetails',$feeDetails);
	}
	public function registration($engineer=null){
		$isRejectedApp=0;
		$isServiceByEngineer=0;
		$isEditByCDB=0;
		$newGeneralInfoSave=1;
		$redirectUrl=Input::get('redirectUrl');
		$view="crps.engineerregistration";
		/*if(Route::current()->getUri()=="engineer/editregistrationinfo/{engineerid}"){
			$view="crps.engineereditregistrationinfo";
			$isEditByCDB=1;
		}*/
		$engineerRegistrations=array(new EngineerModel());
		$engineerRegistrationAttachments=array();
		if((bool)$engineer!=null && !Input::has('usercdb')){
			if(Input::has('editbyapplicant')){
				$view="crps.engineerregistration";
				if(Input::has('rejectedapplicationreapply')){
					$isRejectedApp=1;
				}
			}else{
				$view="crps.engineereditregistrationinfo";
			}
			$engineerRegistrations=EngineerModel::engineerHardList($engineer)->get();
			$engineerRegistrationAttachments=EngineerAttachmentModel::attachment($engineer)->get(array('Id','DocumentName','DocumentPath'));
		}
		if((bool)$engineer!=null && Input::has('usercdb')){
			$isEditByCDB=1;
			$newGeneralInfoSave=0;
			$view="crps.engineereditregistrationinfo";
			$engineerRegistrations=EngineerFinalModel::engineerHardList($engineer)->get();
			$engineerRegistrationAttachments=EngineerAttachmentFinalModel::attachment($engineer)->get(array('Id','DocumentName','DocumentPath'));
		}
		$applicationReferenceNo=$this->tableTransactionNo('EngineerModel','ReferenceNo');
		$serviceSectorTypes=CmnListItemModel::serviceSectorType()->get(array('Id','Name'));
		$country=CountryModel::country()->get(array('Id','Name'));
		$dzongkhag=DzongkhagModel::dzongkhag()->get(array('Id','NameEn'));
		$salutation=CmnListItemModel::salutation()->get(array('Id','Name'));
		$qualification=CmnListItemModel::qualification()->get(array('Id','Name'));
		$trades=CmnListItemModel::trade()->whereIn('ReferenceNo', array(4001,4002,4003))->get(array('Id','Name'));
		return View::make($view)
					->with('isRejectedApp',$isRejectedApp)
					->with('isEditByCDB',$isEditByCDB)
					->with('newGeneralInfoSave',$newGeneralInfoSave)
					->with('redirectUrl',$redirectUrl)
					->with('engineerId',$engineer)
					->with('isServiceByEngineer',$isServiceByEngineer)
					->with('applicationReferenceNo',$applicationReferenceNo)
					->with('serviceSectorTypes',$serviceSectorTypes)
					->with('countries',$country)
					->with('dzongkhags',$dzongkhag)
					->with('salutations',$salutation)
					->with('qualifications',$qualification)
					->with('trades',$trades)
					->with('engineerRegistrations',$engineerRegistrations)
					->with('engineerRegistrationAttachments',$engineerRegistrationAttachments);
	}
	public function save(){
		$save=true;
		$postedValues=Input::all();
		$isRejectedApp=Input::get('ApplicationRejectedReapply');
		$newGeneralInfoSave=Input::get('NewGeneralInfoSave');
		$isEditByCdb=Input::get('EditByCdb');
		$redirectTo=Input::get('PostBackUrl');
		$serviceByEngineer=Input::get('IsServiceByEngineer');
		$engineerId=Input::get('CrpEngineerId');
		$multiAttachments=array();
		$uuid=DB::select("select uuid() as Id");
        $generatedId=$uuid[0]->Id;
		$validation = new EngineerModel;
		if(!($validation->validate($postedValues))){
		    $errors = $validation->errors();
		    if((int)$serviceByEngineer==1){
		    	return Redirect::to('engineer/applyrenewalregistrationdetails/'.$postedValues['CrpEngineerId'])->withInput()->withErrors($errors);
		    }
		    if(empty($postedValues["Id"])){
		    	return Redirect::to('engineer/registration')->withInput()->withErrors($errors);
			}else{
				return Redirect::to('engineer/registration/'.$postedValues['CrpEngineerId'])->withInput()->withErrors($errors);
			}
		}
		DB::beginTransaction();
		try{
			if(empty($postedValues["Id"])){
				$postedValues["Id"]=$generatedId;
				EngineerModel::create($postedValues);
				if((int)$serviceByEngineer==1){
					$appliedServiceRenewal = new EngineerAppliedServiceModel;
	        		$appliedServiceRenewal->CrpEngineerId=$generatedId;
				    $appliedServiceRenewal->CmnServiceTypeId = CONST_SERVICETYPE_RENEWAL;
				    $appliedServiceRenewal->save();
				    if($postedValues['CmnServiceSectorTypeId']==CONST_CMN_SERVICESECTOR_PVT){
				    	$lateRenewalExpiryDate=EngineerFinalModel::engineerHardList($postedValues['CrpEngineerId'])->pluck('RegistrationExpiryDate');
					    $lateRenewalExpiryDate=strtotime($lateRenewalExpiryDate);
					    $currentDate=strtotime(date('Y-m-d'));
					    if($currentDate>$lateRenewalExpiryDate){
					    	$appliedServiceRenewalLateFee = new EngineerAppliedServiceModel;
					    	$appliedServiceRenewalLateFee->CrpEngineerId=$generatedId;
					    	$appliedServiceRenewalLateFee->CmnServiceTypeId = CONST_SERVICETYPE_LATEFEE;
					    	$appliedServiceRenewalLateFee->save();
					    }
					}
				}
			}else{
				$save=false;
				if(isset($isEditByCdb) && (int)$isEditByCdb==1 && $newGeneralInfoSave!=null && (int)$newGeneralInfoSave==0){
					$engineerReference= new EngineerFinalModel();
				}else{
					$engineerReference= new EngineerModel();
				}
				$instance=$engineerReference::find($postedValues['Id']);
				$instance->fill($postedValues);
				$instance->update();
			}
			if(Input::hasFile('attachments')){
				$count=0;
				foreach(Input::file('attachments') as $attachment){
					$documentName = Input::get("DocumentName");
					$attachmentType=$attachment->getMimeType();
					$attachmentFileName=$attachment->getClientOriginalName();
					$attachmentName=str_random(6) . '_' . $attachment->getClientOriginalName();
					$destination=public_path().'/uploads/engineers';
					$destinationDB='/uploads/engineers/'.$attachmentName;
					$multiAttachments1["DocumentName"]=$documentName[$count];
					$multiAttachments1["DocumentPath"]=$destinationDB;
					$multiAttachments1["FileType"]=$attachmentType;
					array_push($multiAttachments, $multiAttachments1);
					$uploadAttachments=$attachment->move($destination, $attachmentName);
					$count++;
				}
				foreach($multiAttachments as $k=>$v){
					if(empty($postedValues['Id'])){
						if(isset($isEditByCdb) && (int)$isEditByCdb==1 && $newGeneralInfoSave!=null  && (int)$newGeneralInfoSave==0){
							$multiAttachments[$k]["CrpEngineerFinalId"]=$generatedId;
						}else{
							$multiAttachments[$k]["CrpEngineerId"]=$generatedId;
						}						
					}else{
						if(isset($isEditByCdb) && (int)$isEditByCdb==1 && $newGeneralInfoSave!=null  && (int)$newGeneralInfoSave==0){
							$multiAttachments[$k]["CrpEngineerFinalId"]=$postedValues['Id'];
						}else{
							$multiAttachments[$k]["CrpEngineerId"]=$postedValues['Id'];
						}
					}
					if(isset($isEditByCdb) && (int)$isEditByCdb==1 && $newGeneralInfoSave!=null  && (int)$newGeneralInfoSave==0){
						$saveUploads=new EngineerAttachmentFinalModel($multiAttachments[$k]);
					}else{	
						$saveUploads=new EngineerAttachmentModel($multiAttachments[$k]);
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
			if((int)$serviceByEngineer==1){
				return Redirect::to('engineer/applyrenewalconfirmation/'.$postedValues["Id"]);			
			}
			return Redirect::to($redirectTo.'/'.$postedValues["Id"])->with('savedsuccessmessage','Registration Information has been successfully updated.');
		}
		if($save){
			Session::put('EngineerRegistrationId',$generatedId);
			return Redirect::to('engineer/confirmregistration');
		}else{
			if(isset($isRejectedApp) && (int)$isRejectedApp==1){
				Session::put('EngineerRegistrationId',$postedValues["Id"]);
				return Redirect::to('engineer/confirmregistration');
			}
			return Redirect::to('engineer/confirmregistration')->with('savedsuccessmessage','Registration Information has been successfully updated.');
		}
	}
	public function confirmRegistration(){
		$isServiceByEngineer=0;
		if(Session::has('EngineerRegistrationId')){
			$engineerId=Session::get('EngineerRegistrationId');
		}else{
			return Redirect::to('engineer/registration');
		}
		$engineerInformations=EngineerModel::engineer($engineerId)->get(array('crpengineer.Id','crpengineer.CIDNo','crpengineer.Name','crpengineer.Gewog','crpengineer.Village','crpengineer.Email','crpengineer.MobileNo','crpengineer.EmployerName','crpengineer.EmployerAddress','crpengineer.GraduationYear','crpengineer.NameOfUniversity','T1.Name as EngineerType','T2.Name as Salutation','T3.Name as Country','T4.NameEn as Dzongkhag','T5.Name as Qualification','T6.Name as UniversityCountry','T8.Name as Trade'));
		$engineerAttachments=EngineerAttachmentModel::attachment($engineerId)->get(array('Id','DocumentName','DocumentPath'));
		return View::make('crps.engineerregistrationconfirmation')
					->with('isServiceByEngineer',$isServiceByEngineer)
					->with('engineerId',$engineerId)
					->with('engineerInformations',$engineerInformations)
					->with('engineerAttachments',$engineerAttachments);
	}
	public function saveConfirmation(){
		$isServiceByEngineer=Input::get('IsServiceByEngineer');
		$engineerId=Input::get('CrpEngineerId');
		$engineer = EngineerModel::find($engineerId);
		$engineer->RegistrationStatus=1;
		$engineer->CmnApplicationRegistrationStatusId=CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW;
		$engineer->save();
		$engineerDetails=EngineerModel::engineerHardList($engineerId)->get(array('Name','Email','ReferenceNo','ApplicationDate','MobileNo'));
		$mailView="emails.crps.mailregistrationsuccess";
		$recipientAddress=$engineerDetails[0]->Email;
		$recipientName=$engineerDetails[0]->Name;
		$referenceNo=$engineerDetails[0]->ReferenceNo;
		$applicationDate=$engineerDetails[0]->ApplicationDate;
		$mobileNo=$engineerDetails[0]->MobileNo;
		if((int)$isServiceByEngineer==0){
			$mailIntendedTo=4;//1=Contractor,2=Consultant,3=Architect,4=Engineer,5=Specialized Trade
			$subject="Acknowledgement: Receipt of Application for Registration with CDB";
			$emailMessage="This is to acknowledge receipt of your application for registration of engineer with Construction Development Board (CDB). Your application will be processed in due course. You can check status of your application using your Citizenship Id No. or Application No. from the <a href=”#”>CDB website</a>. You will also be notified through email when your application is approved.";
			$smsMessage="Your application for engineer registration has been received. You can check status of your application using CID No. or Application No ($referenceNo) from CDB website.";
			$engineerServiceSectorType=EngineerModel::where('Id',$engineerId)->pluck('CmnServiceSectorTypeId');
			if($engineerServiceSectorType==CONST_CMN_SERVICESECTOR_PVT){
				$feeDetails=DB::select("select 'Private' as SectorType,EngineerPvtAmount as NewRegistrationFee,EngineerPvtValidity as RegistrationValidity from crpservice where Id=? limit 1",array(CONST_SERVICETYPE_NEW));
			}else{
				$feeDetails=DB::select("select 'Goverment' as SectorType,EngineerGovtAmount as NewRegistrationFee,EngineerGovtValidity as RegistrationValidity from crpservice where Id=? limit 1",array(CONST_SERVICETYPE_NEW));
			}
		}else{
			$mailIntendedTo=null;
			$feeDetails=array();
			$subject="Acknowledgement: Receipt of Application for CDB Service";
			$emailMessage="This is to acknowledge receipt of your application for Construction Development Board (CDB) services. Your application will be processed in due course. You can check status of your application using your Citizenship Id No. or Application No. from the <a href=”#”>CDB website</a>. You will also be notified through email when your application is approved.";
			$smsMessage="Your application for renewal of registration has been received. You can check status of your application using CID No. or Application No ($referenceNo) from CDB website.";
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
		DB::commit();
		if((int)$isServiceByEngineer==0){
			Session::forget('EngineerRegistrationId');
			return Redirect::route('applicantregistrationsuccess',array('linktoprint'=>'engineer/printregistration','printreference'=>$engineerId,'applicationno'=>$referenceNo));
		}else{
			return Redirect::to('engineer/profile')->with('savedsuccessmessage','Your application was successfully submitted');
		}
	}
	public function engineerList(){
		$parameters=array();
		$serviceSectorTypeId=Input::get('CmnServiceSectorTypeId');
		$tradeId=Input::get('CmnTradeId');
		$engineerId=Input::get('CrpEngineerId');
		$CDBNo=Input::get('CDBNo');
		
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

		$query="select T1.Id,T1.ReferenceNo,T1.ApplicationDate,T1.CIDNo,T1.CDBNo,T1.RegistrationExpiryDate ,Z.Name as Status,Z.ReferenceNo as StatusReference,T1.Name as EngineerName,T1.EmployerName,T1.Email,T1.MobileNo,T2.Name as Country,T3.NameEn as Dzongkhag,T4.Name as Salutation,T5.Name as EngineerType,T6.Name as Trade from crpengineerfinal T1 left join cmnlistitem Z on Z.Id = T1.CmnApplicationRegistrationStatusId   join cmncountry T2 on T1.CmnCountryId=T2.Id left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id join cmnlistitem T4 on T1.CmnSalutationId=T4.Id join cmnlistitem T5 on T1.CmnServiceSectorTypeId=T5.Id join cmnlistitem T6 on T1.CmnTradeId=T6.Id where 1=1";
		if(Route::current()->getUri()=="engineer/editlist"){
			$linkText='Edit';
			$link='engineer/editdetails/';
			$query.=" and T1.CmnApplicationRegistrationStatusId=?";
			array_push($parameters,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED);
		}elseif(Route::current()->getUri()=="engineer/viewprintlist"){
			$linkText='View/Print';
			$link='engineer/viewprintdetails/';
			$query.=" and T1.CmnApplicationRegistrationStatusId is not null";
		}elseif(Route::current()->getUri()=="engineer/newcommentsadverserecordslist"){
			$linkText='Add';
			$link='engineer/newcommentsadverserecords/';
			$query.=" and T1.CmnApplicationRegistrationStatusId=?";
			array_push($parameters,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED);
		}elseif(Route::current()->getUri()=="engineer/editcommentsadverserecordslist"){
			$linkText='View';
			$link='engineer/editcommentsadverserecords/';
			$query.=" and T1.CmnApplicationRegistrationStatusId=?";
			array_push($parameters,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED);
		}else{
			App::abort('404');
		}
		if((bool)$serviceSectorTypeId!=NULL || (bool)$tradeId!=NULL || (bool)$engineerId!=NULL || (bool)$CDBNo!=NULL){
			if((bool)$serviceSectorTypeId!=NULL){
				$query.=" and T1.CmnServiceSectorTypeId=?";
				array_push($parameters,$serviceSectorTypeId);
			}
			if((bool)$tradeId!=NULL){
				$query.=" and T1.CmnTradeId=?";
				array_push($parameters,$tradeId);
			}
			if((bool)$engineerId!=NULL){
				$query.=" and T1.Id=?";
				array_push($parameters,$engineerId);
			}
			if((bool)$CDBNo!=NULL){
				$query.=" and T1.CDBNo=?";
	            array_push($parameters,$CDBNo);
			}
		}
		$engineerLists=DB::select($query." order by CDBNo".$limit,$parameters);
		$serviceSectorTypes=CmnListItemModel::serviceSectorType()->get(array('Id','Name'));
		$trades=CmnListItemModel::trade()->whereIn('ReferenceNo', array(4001,4002,4003))->get(array('Id','Name'));
		return View::make('crps.engineerlist')
					->with('pageTitle','List of Engineer')
					->with('link',$link)
					->with('linkText',$linkText)
					->with('CDBNo',$CDBNo)
					->with('engineerId',$engineerId)
					->with('serviceSectorTypeId',$serviceSectorTypeId)
					->with('tradeId',$tradeId)
					->with('serviceSectorTypes',$serviceSectorTypes)
					->with('trades',$trades)
					->with('engineerLists',$engineerLists);
	}
	public function engineerDetails($engineerId){
		$registrationApprovedForPayment=0;
		$userEngineer=0;
		if(Route::current()->getUri()=="engineer/approveregistrationprocess/{engineerid}"){
			$view="crps.engineerapproveregistrationprocess";
			$modelPost="engineer/mapproveregistration";
		}elseif(Route::current()->getUri()=="engineer/verifyregistrationprocess/{engineerid}"){
			$view="crps.engineerverifyregistrationprocess";
			$modelPost="engineer/mverifyregistration";
		}elseif(Route::current()->getUri()=="engineer/approvepaymentregistrationprocess/{engineerid}"){
			$modelPost="";
			$registrationApprovedForPayment=1;
			$view="crps.engineerinformation";
		}else{
			App::abort('404');
		}
		$engineerServiceSectorType=EngineerModel::where('Id',$engineerId)->pluck('CmnServiceSectorTypeId');
		if($engineerServiceSectorType==CONST_CMN_SERVICESECTOR_PVT){
			$feeDetails=DB::select("select Name as ServiceName,EngineerPvtAmount as NewRegistrationFee,EngineerPvtValidity as RegistrationValidity from crpservice where Id=? limit 1",array(CONST_SERVICETYPE_NEW));
		}else{
			$feeDetails=DB::select("select Name as ServiceName,EngineerGovtAmount as NewRegistrationFee,EngineerGovtValidity as RegistrationValidity from crpservice where Id=? limit 1",array(CONST_SERVICETYPE_NEW));
		}
		$engineerInformations=EngineerModel::engineer($engineerId)->get(array('crpengineer.Id','crpengineer.CIDNo','crpengineer.CDBNo','crpengineer.Name','crpengineer.Gewog','crpengineer.Village','crpengineer.Email','crpengineer.MobileNo','crpengineer.EmployerName','crpengineer.EmployerAddress','crpengineer.GraduationYear','crpengineer.NameOfUniversity','crpengineer.RemarksByVerifier','crpengineer.RemarksByApprover','crpengineer.RemarksByPaymentApprover','crpengineer.VerifiedDate','crpengineer.PaymentApprovedDate','crpengineer.RegistrationApprovedDate','T1.Name as EngineerType','T2.Name as Salutation','T3.Name as Country','T4.NameEn as Dzongkhag','T5.Name as Qualification','T6.Name as UniversityCountry','T8.Name as Trade','T10.FullName as Verifier','T11.FullName as Approver','T12.FullName as PaymentApprover'));
		$engineerAttachments=EngineerAttachmentModel::attachment($engineerId)->get(array('Id','DocumentName','DocumentPath'));
		return View::make($view)
					->with('modelPost',$modelPost)
					->with('engineerId',$engineerId)
					->with('feeDetails',$feeDetails)
					->with('engineerServiceSectorType',$engineerServiceSectorType)
					->with('engineerInformations',$engineerInformations)
					->with('engineerAttachments',$engineerAttachments)
					->with('registrationApprovedForPayment',$registrationApprovedForPayment)
					->with('userEngineer',$userEngineer);
	}
	public function editDetails($engineerId){
        $loggedInUser = Auth::user()->Id;
        $userRoles=DB::table('sysuserrolemap')->where('SysUserId',$loggedInUser)->lists('SysRoleId');
        $isAdmin = false;
        if(in_array(CONST_ROLE_ADMINISTRATOR,$userRoles)){
            $isAdmin = true;
        }
		$registrationApprovedForPayment=0;
		$userEngineer=0;
		$engineerInformations=EngineerFinalModel::engineer($engineerId)->get(array('crpengineerfinal.Id','crpengineerfinal.CIDNo','crpengineerfinal.CDBNo','crpengineerfinal.Name','crpengineerfinal.Gewog','crpengineerfinal.Village','crpengineerfinal.Email','crpengineerfinal.MobileNo','crpengineerfinal.EmployerName','crpengineerfinal.EmployerAddress','crpengineerfinal.RegistrationExpiryDate','crpengineerfinal.GraduationYear','crpengineerfinal.NameOfUniversity','T1.Name as EngineerType','T2.Name as Salutation','T3.Name as Country','T4.NameEn as Dzongkhag','T5.Name as Qualification','T6.Name as UniversityCountry','T7.Name as Status','T8.Name as Trade'));
		$engineerAttachments=EngineerAttachmentFinalModel::attachment($engineerId)->get(array('Id','DocumentName','DocumentPath'));
		$CV = DB::select("SELECT 
				DISTINCT(T3.Id),
				C.`Name` workStatus,T3.`NameOfWork`,
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
				AND `T1`.`CIDNo` =".$engineerInformations[0]->CIDNo."
				GROUP BY `T3`.`Id`"
		);
		return View::make('crps.engineerinformation')
					->with('isAdmin',$isAdmin)
					->with('engineerId',$engineerId)
					->with('engineerInformations',$engineerInformations)
					->with('engineerAttachments',$engineerAttachments)
					->with('registrationApprovedForPayment',$registrationApprovedForPayment)
					->with('CV',$CV)
					->with('userEngineer',$userEngineer);
	}	public function verifyList(){
		$redirectUrl = Request::path();
		$recordLockException=false;
		$applicationPickedByUserFullName=null;
		if(Session::has('ApplicationAlreadyPicked')){
			$applicationPickedByUserFullName=Session::get('ApplicationAlreadyPicked');
			$recordLockException=true;
		}
		
		$serviceSectorTypeIdAll=Input::get('CmnServiceSectorTypeIdAll');
		$tradeIdAll=Input::get('CmnTradeIdAll');
		$engineerIdAll=Input::get('CrpEngineerIdAll');
		$fromDateAll=Input::get('FromDateAll');
		$toDateAll=Input::get('ToDateAll');
		/*-----End of parameters for all engineers------*/
		$serviceSectorTypeIdMyTask=Input::get('CmnServiceSectorTypeIdMyTask');
		$tradeIdMyTask=Input::get('CmnTradeIdMyTask');
		$engineerIdMyTask=Input::get('CrpEngineerIdMyTask');
		$fromDateMyTask=Input::get('FromDateMyTask');
		$toDateMyTask=Input::get('ToDateMyTask');
		/*-----End of parameters for My Task engineers------*/
		$query="select T1.Id,T1.ReferenceNo,T1.ApplicationDate,T1.CIDNo,T1.Name as EngineerName,T1.EmployerName,T1.Email,T1.MobileNo,T2.Name as Country,T3.NameEn as Dzongkhag,T4.Name as Salutation,T5.Name as EngineerType,T6.Name as Trade from crpengineer T1 join cmncountry T2 on T1.CmnCountryId=T2.Id left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id join cmnlistitem T4 on T1.CmnSalutationId=T4.Id join cmnlistitem T5 on T1.CmnServiceSectorTypeId=T5.Id join cmnlistitem T6 on T1.CmnTradeId=T6.Id where T1.CmnApplicationRegistrationStatusId=? and T1.SysLockedByUserId is null";
		$queryMyTaskList="select T1.Id,T1.ReferenceNo,T1.ApplicationDate,T1.CIDNo,T1.Name as EngineerName,T1.EmployerName,T1.Email,T1.MobileNo,T2.Name as Country,T3.NameEn as Dzongkhag,T4.Name as Salutation,T5.Name as EngineerType,T6.Name as Trade from crpengineer T1 join cmncountry T2 on T1.CmnCountryId=T2.Id left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id join cmnlistitem T4 on T1.CmnSalutationId=T4.Id join cmnlistitem T5 on T1.CmnServiceSectorTypeId=T5.Id join cmnlistitem T6 on T1.CmnTradeId=T6.Id where T1.CmnApplicationRegistrationStatusId=? and T1.SysLockedByUserId=?";
		$parameters=array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW);
		$parametersMyTaskList=array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW,Auth::user()->Id);
		if((bool)$serviceSectorTypeIdAll!=NULL || (bool)$tradeIdAll!=NULL || (bool)$engineerIdAll!=NULL || (bool)$fromDateAll!=NULL || (bool)$toDateAll!=NULL || (bool)$serviceSectorTypeIdMyTask!=NULL || (bool)$tradeIdMyTask!=NULL || (bool)$engineerIdMyTask!=NULL || (bool)$fromDateMyTask!=NULL || (bool)$toDateMyTask!=NULL){
			if((bool)$serviceSectorTypeIdAll!=NULL){
				$query.=" and T1.CmnServiceSectorTypeId=?";
				array_push($parameters,$serviceSectorTypeIdAll);
			}
			if((bool)$tradeIdAll!=NULL){
				$query.=" and T1.CmnTradeId=?";
				array_push($parameters,$tradeIdAll);
			}
			if((bool)$engineerIdAll!=NULL){
				$query.=" and T1.Id=?";
				array_push($parameters,$engineerIdAll);
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
			if((bool)$serviceSectorTypeIdAll!=NULL){
				$queryMyTaskList.=" and T1.CmnServiceSectorTypeId=?";
				array_push($parametersMyTaskList,$serviceSectorTypeIdMyTask);
			}
			if((bool)$tradeIdMyTask!=NULL){
				$queryMyTaskList.=" and T1.CmnTradeId=?";
				array_push($parametersMyTaskList,$tradeIdMyTask);
			}
			if((bool)$engineerIdMyTask!=NULL){
				$queryMyTaskList.=" and T1.Id=?";
				array_push($parametersMyTaskList,$engineerIdMyTask);
			}
			if((bool)$fromDateMyTask!=NULL){
				$fromDateMyTask=$this->convertDate($fromDateMyTask);
				$queryMyTaskList.=" and T1.ApplicationDate>=?";
	            array_push($parametersMyTaskList,$fromDateMyTask);
			}
			if((bool)$toDateMyTask!=NULL){
				$toDateMyTask=$this->convertDate($toDateMyTask);
				$queryMyTaskList.=" and T1.ApplicationDate<=?";
	            array_push($parametersMyTaskList,$toDateAll);
			}
		}
		$serviceSectorTypes=CmnListItemModel::serviceSectorType()->get(array('Id','Name'));
		$trades=CmnListItemModel::trade()->whereIn('ReferenceNo', array(4001,4002,4003))->get(array('Id','Name'));
		$engineerLists=DB::select($query." order by ApplicationDate,ReferenceNo,EngineerName",$parameters);
		$engineerMyTaskLists=DB::select($queryMyTaskList." order by ApplicationDate,ReferenceNo,EngineerName",$parametersMyTaskList);
		return View::make('crps.engineerregistrationapplicationprocesslist')
					->with('redirectUrl',$redirectUrl)
					->with('pageTitle',"Verify Engineer Registration")
					->with('recordLockException',$recordLockException)
					->with('applicationPickedByUserFullName',$applicationPickedByUserFullName)
					->with('fromDateAll',$fromDateAll)
					->with('toDateAll',$toDateAll)
					->with('engineerIdAll',$engineerIdAll)
					->with('serviceSectorTypeIdAll',$serviceSectorTypeIdAll)
					->with('tradeIdAll',$tradeIdAll)
					->with('fromDateMyTask',$fromDateMyTask)
					->with('toDateMyTask',$toDateMyTask)
					->with('engineerIdMyTask',$engineerIdMyTask)
					->with('serviceSectorTypeIdMyTask',$serviceSectorTypeIdMyTask)
					->with('tradeIdMyTask',$tradeIdMyTask)
					->with('serviceSectorTypes',$serviceSectorTypes)
					->with('trades',$trades)
					->with('engineerLists',$engineerLists)
					->with('engineerMyTaskLists',$engineerMyTaskLists);
	}
	public function verifyRegistration(){
		$postedValues=Input::all();
		$postedValues['RegistrationApprovedDate']=$this->convertDateTime($postedValues['RegistrationApprovedDate']);
		$postedValues['RegistrationExpiryDate']=$this->convertDateTime($postedValues['RegistrationExpiryDate']);
		$engineerReference= new EngineerModel();
		$instance=$engineerReference::find($postedValues['EngineerReference']);
		$instance->fill($postedValues);
		$instance->update();
		return Redirect::to('engineer/verifyregistration')
						->with('savedsuccessmessage','The application has been successfully verified.');
	}
	public function approveList(){
		$redirectUrl = Request::path();
		$recordLockException=false;
		$applicationPickedByUserFullName=null;
		if(Session::has('ApplicationAlreadyPicked')){
			$applicationPickedByUserFullName=Session::get('ApplicationAlreadyPicked');
			$recordLockException=true;
		}

		$serviceSectorTypeIdAll=Input::get('CmnServiceSectorTypeIdAll');
		$tradeIdAll=Input::get('CmnTradeIdAll');
		$engineerIdAll=Input::get('CrpEngineerIdAll');
		$fromDateAll=Input::get('FromDateAll');
		$toDateAll=Input::get('ToDateAll');
		/*-----End of parameters for all engineers------*/
		$serviceSectorTypeIdMyTask=Input::get('CmnServiceSectorTypeIdMyTask');
		$tradeIdMyTask=Input::get('CmnTradeIdMyTask');
		$engineerIdMyTask=Input::get('CrpEngineerIdMyTask');
		$fromDateMyTask=Input::get('FromDateMyTask');
		$toDateMyTask=Input::get('ToDateMyTask');
		/*-----End of parameters for My Task engineers------*/
		$query="select T1.Id,T1.ReferenceNo,T1.ApplicationDate,T1.CIDNo,T1.Name as EngineerName,T1.EmployerName,T1.Email,T1.MobileNo,T2.Name as Country,T3.NameEn as Dzongkhag,T4.Name as Salutation,T5.Name as EngineerType,T6.Name as Trade from crpengineer T1 join cmncountry T2 on T1.CmnCountryId=T2.Id left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id join cmnlistitem T4 on T1.CmnSalutationId=T4.Id join cmnlistitem T5 on T1.CmnServiceSectorTypeId=T5.Id join cmnlistitem T6 on T1.CmnTradeId=T6.Id where T1.CmnApplicationRegistrationStatusId=? and T1.SysLockedByUserId is null and T1.CrpEngineerId is null";
		$queryMyTaskList="select T1.Id,T1.ReferenceNo,T1.ApplicationDate,T1.CIDNo,T1.Name as EngineerName,T1.EmployerName,T1.Email,T1.MobileNo,T2.Name as Country,T3.NameEn as Dzongkhag,T4.Name as Salutation,T5.Name as EngineerType,T6.Name as Trade from crpengineer T1 join cmncountry T2 on T1.CmnCountryId=T2.Id left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id join cmnlistitem T4 on T1.CmnSalutationId=T4.Id join cmnlistitem T5 on T1.CmnServiceSectorTypeId=T5.Id join cmnlistitem T6 on T1.CmnTradeId=T6.Id where T1.CmnApplicationRegistrationStatusId=? and T1.SysLockedByUserId=? and T1.CrpEngineerId is null";
		if(Request::path()=="engineer/approvefeepayment"){
			$parameters=array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVEDFORPAYMENT);
			$parametersMyTaskList=array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVEDFORPAYMENT,Auth::user()->Id);
		}else{
			$parameters=array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_VERIFIED);
			$parametersMyTaskList=array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_VERIFIED,Auth::user()->Id);
		}
		if((bool)$serviceSectorTypeIdAll!=NULL || (bool)$tradeIdAll!=NULL || (bool)$engineerIdAll!=NULL || (bool)$fromDateAll!=NULL || (bool)$toDateAll!=NULL || (bool)$serviceSectorTypeIdMyTask!=NULL || (bool)$tradeIdMyTask!=NULL || (bool)$engineerIdMyTask!=NULL || (bool)$fromDateMyTask!=NULL || (bool)$toDateMyTask!=NULL){
			if((bool)$serviceSectorTypeIdAll!=NULL){
				$query.=" and T1.CmnServiceSectorTypeId=?";
				array_push($parameters,$serviceSectorTypeIdAll);
			}
			if((bool)$tradeIdAll!=NULL){
				$query.=" and T1.CmnTradeId=?";
				array_push($parameters,$tradeIdAll);
			}
			if((bool)$engineerIdAll!=NULL){
				$query.=" and T1.Id=?";
				array_push($parameters,$engineerIdAll);
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
			if((bool)$serviceSectorTypeIdAll!=NULL){
				$queryMyTaskList.=" and T1.CmnServiceSectorTypeId=?";
				array_push($parametersMyTaskList,$serviceSectorTypeIdMyTask);
			}
			if((bool)$tradeIdMyTask!=NULL){
				$queryMyTaskList.=" and T1.CmnTradeId=?";
				array_push($parametersMyTaskList,$tradeIdMyTask);
			}
			if((bool)$engineerIdMyTask!=NULL){
				$queryMyTaskList.=" and T1.Id=?";
				array_push($parametersMyTaskList,$engineerIdMyTask);
			}
			if((bool)$fromDateMyTask!=NULL){
				$fromDateMyTask=$this->convertDate($fromDateMyTask);
				$queryMyTaskList.=" and T1.ApplicationDate>=?";
	            array_push($parametersMyTaskList,$fromDateMyTask);
			}
			if((bool)$toDateMyTask!=NULL){
				$toDateMyTask=$this->convertDate($toDateMyTask);
				$queryMyTaskList.=" and T1.ApplicationDate<=?";
	            array_push($parametersMyTaskList,$toDateAll);
			}
		}
		$serviceSectorTypes=CmnListItemModel::serviceSectorType()->get(array('Id','Name'));
		$trades=CmnListItemModel::trade()->whereIn('ReferenceNo', array(4001,4002,4003))->get(array('Id','Name'));
		$engineerLists=DB::select($query." order by ApplicationDate,ReferenceNo,EngineerName",$parameters);
		$engineerMyTaskLists=DB::select($queryMyTaskList." order by ApplicationDate,ReferenceNo,EngineerName",$parametersMyTaskList);
		return View::make('crps.engineerregistrationapplicationprocesslist')
					->with('redirectUrl',$redirectUrl)
					->with('pageTitle',"Verify Engineer Registration")
					->with('recordLockException',$recordLockException)
					->with('applicationPickedByUserFullName',$applicationPickedByUserFullName)
					->with('fromDateAll',$fromDateAll)
					->with('toDateAll',$toDateAll)
					->with('engineerIdAll',$engineerIdAll)
					->with('serviceSectorTypeIdAll',$serviceSectorTypeIdAll)
					->with('tradeIdAll',$tradeIdAll)
					->with('fromDateMyTask',$fromDateMyTask)
					->with('toDateMyTask',$toDateMyTask)
					->with('engineerIdMyTask',$engineerIdMyTask)
					->with('serviceSectorTypeIdMyTask',$serviceSectorTypeIdMyTask)
					->with('tradeIdMyTask',$tradeIdMyTask)
					->with('serviceSectorTypes',$serviceSectorTypes)
					->with('trades',$trades)
					->with('engineerLists',$engineerLists)
					->with('engineerMyTaskLists',$engineerMyTaskLists);
	}
	public function approveRegistration(){
		$postedValues=Input::all();
		DB::beginTransaction();
		try{
			$instance=EngineerModel::find($postedValues['EngineerReference']);
			$instance->fill($postedValues);
			$instance->update();
			$uuid=DB::select("select uuid() as Id");
			$generatedId=$uuid[0]->Id;
			$engineerDetails=EngineerModel::engineerHardList($postedValues['EngineerReference'])->get(array('CDBNo','TPN','Name','Email','ReferenceNo','ApplicationDate','MobileNo'));
			$CDBNo=$engineerDetails[0]->CDBNo;
			$recipientAddress=$engineerDetails[0]->Email;
			$recipientName=$engineerDetails[0]->Name;
			$referenceNo=$engineerDetails[0]->ReferenceNo;
			$applicationDate=$engineerDetails[0]->ApplicationDate;
			$mobileNo=$engineerDetails[0]->MobileNo;
			$tpn = $engineerDetails[0]->TPN;
			if($postedValues['CmnApplicationRegistrationStatusId']==CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED){
				$plainPassword=substr(md5(uniqid(mt_rand(), true)), 0, 5);
				$plainPassword.="@#".date('d');
				$password=Hash::make($plainPassword);
		        $userCredentials=array('Id'=>$generatedId,'username'=>$recipientAddress,'password'=>$password,'FullName'=>$recipientName,'Status'=>'1','CreatedBy'=>Auth::user()->Id);
				$roleData=array('SysUserId'=>$generatedId,'SysRoleId'=>CONST_ROLE_ENGINEER,'CreatedBy'=>Auth::user()->Id);

				/*Fee structure */
				$engineerServiceSectorType=EngineerModel::where('Id',Input::get('EngineerReference'))->pluck('CmnServiceSectorTypeId');
				if($engineerServiceSectorType==CONST_CMN_SERVICESECTOR_PVT){
					$feeDetails=DB::select("select 'Private' as SectorType,EngineerPvtAmount as NewRegistrationFee,EngineerPvtValidity as RegistrationValidity from crpservice where Id=? limit 1",array(CONST_SERVICETYPE_NEW));
				}else{
					$feeDetails=DB::select("select 'Goverment' as SectorType,EngineerGovtAmount as NewRegistrationFee,EngineerGovtValidity as RegistrationValidity from crpservice where Id=? limit 1",array(CONST_SERVICETYPE_NEW));
				}
				/* End fee structure */

				User::create($userCredentials);
				RoleUserMapModel::create($roleData);
				DB::statement("call ProCrpEngineerNewRegistrationFinalData(?,?,?,?)",array(Input::get('EngineerReference'),$generatedId,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED,Auth::user()->Id));
				$mailView="emails.crps.mailregistrationpaymentcompletion";
				$subject="Activation of Your CDB Certificate";
				$smsMessage="Your application for engineer registration has been approved by CDB and your certificate has been activated. Your CDB No. is $CDBNo. Your username is $recipientAddress and password is $plainPassword";
				$mailData=array(
					'mailIntendedTo'=>4,
					'feeDetails'=>$feeDetails,
					'applicantName'=>$recipientName,
					'applicationNo'=>$referenceNo,
					'applicationDate'=>$applicationDate,
					'username'=>$recipientAddress,
					'password'=>$plainPassword,
					'tpn' => $tpn,
					'mailMessage'=>"Construction Development Board (CDB) has verified and approved your application for registration of goverment engineer with CDB. Your CDB No. is ".$CDBNo." and your CDB certificate has been activated. You can view and print it by logging in to your account using below given user credentials. Please change your password regularly to make your account secure.",
				);
			}else{
				$mailIntendedTo=4;//1=Contractor,2=Consultant,3=Architect,4=Engineer,5=Specialized Trade
				$engineerServiceSectorType=EngineerModel::where('Id',Input::get('EngineerReference'))->pluck('CmnServiceSectorTypeId');
				if($engineerServiceSectorType==CONST_CMN_SERVICESECTOR_PVT){
					$feeDetails=DB::select("select 'Private' as SectorType,EngineerPvtAmount as NewRegistrationFee,EngineerPvtValidity as RegistrationValidity from crpservice where Id=? limit 1",array(CONST_SERVICETYPE_NEW));
				}else{
					$feeDetails=DB::select("select 'Goverment' as SectorType,EngineerGovtAmount as NewRegistrationFee,EngineerGovtValidity as RegistrationValidity from crpservice where Id=? limit 1",array(CONST_SERVICETYPE_NEW));
				}
				$mailView="emails.crps.mailapplicationapproved";
				$subject="Approval of Your Registration with CDB";
				$smsMessage="Your application for engineer registration has been approved by CDB. Please check your email for detailed information regarding your fees.";
				$mailData=array(
					'mailIntendedTo'=>$mailIntendedTo,
					'feeDetails'=>$feeDetails,
					'applicantName'=>$recipientName,
					'applicationNo'=>$referenceNo,
					'applicationDate'=>$applicationDate,
					'mailMessage'=>'Construction Development Board (CDB) has verified and approved your application for registration of private engineer with CDB.  However, you need to pay your registration fees as per the details given below within three months to the Nearest Regional Revenue and Customs Office (RRCO). We will email you your username and password upon confirmation of your payment by CDB.'
				);
			}
		}catch(Exception $e){
			DB::rollback();
        	throw $e;
		}
		DB::commit();
		$this->sendEmailMessage($mailView,$mailData,$subject,$recipientAddress,$recipientName);
		$this->sendSms($smsMessage,$mobileNo);
		return Redirect::to('engineer/approveregistration')
						->with('savedsuccessmessage','The application has been successfully approved.');
	}
	public function approvePayment(){
		$postedValues=Input::all();
		$postedValues['PaymentReceiptDate']=$this->convertDate($postedValues['PaymentReceiptDate']);
		$postedValues["InitialDate"] = date('Y-m-d');
		DB::beginTransaction();
		try{
			$instance=EngineerModel::find($postedValues['EngineerReference']);
			$instance->fill($postedValues);
			$instance->update();
			$uuid=DB::select("select uuid() as Id");
			$generatedId=$uuid[0]->Id;
			$engineerDetails=EngineerModel::engineerHardList($postedValues['EngineerReference'])->get(array('CDBNo','Name','Email','ReferenceNo','ApplicationDate','MobileNo'));
			$cdbNo=$engineerDetails[0]->CDBNo;
			$recipientAddress=$engineerDetails[0]->Email;
			$recipientName=$engineerDetails[0]->Name;
			$referenceNo=$engineerDetails[0]->ReferenceNo;
			$applicationDate=$engineerDetails[0]->ApplicationDate;
			$mobileNo=$engineerDetails[0]->MobileNo;
			$plainPassword=substr(md5(uniqid(mt_rand(), true)), 0, 5);
			$plainPassword.="@#".date('d');
			$password=Hash::make($plainPassword);
	        $userCredentials=array('Id'=>$generatedId,'username'=>$recipientAddress,'password'=>$password,'FullName'=>$recipientName,'Status'=>'1','CreatedBy'=>Auth::user()->Id);
			$roleData=array('SysUserId'=>$generatedId,'SysRoleId'=>CONST_ROLE_ENGINEER,'CreatedBy'=>Auth::user()->Id);
			$mailView="emails.crps.mailregistrationpaymentcompletion";
			$subject="Activation of Your CDB Certificate";

			/* Fee structure */
			$engineerServiceSectorType=EngineerModel::where('Id',Input::get('EngineerReference'))->pluck('CmnServiceSectorTypeId');
			if($engineerServiceSectorType==CONST_CMN_SERVICESECTOR_PVT){
				$feeDetails=DB::select("select 'Private' as SectorType,EngineerPvtAmount as NewRegistrationFee,EngineerPvtValidity as RegistrationValidity from crpservice where Id=? limit 1",array(CONST_SERVICETYPE_NEW));
			}else{
				$feeDetails=DB::select("select 'Goverment' as SectorType,EngineerGovtAmount as NewRegistrationFee,EngineerGovtValidity as RegistrationValidity from crpservice where Id=? limit 1",array(CONST_SERVICETYPE_NEW));
			}
			/* End */
			$mailData=array(
				'mailIntendedTo' => 4,
				'feeStructures'=>$feeDetails,
				'applicantName'=>$recipientName,
				'applicationNo'=>$referenceNo,
				'applicationDate'=>$applicationDate,
				'username'=>$recipientAddress,
				'password'=>$plainPassword,
				'mailMessage'=>"This is to acknowledge receipt of your payment vide Receipt No. ".Input::get('PaymentReceiptNo')." dated ".Input::get('PaymentReceiptDate')." for engineer registration with Construction Development Board (CDB). Your CDB No. is ".$cdbNo." and your CDB certificate has been activated. You can view and print it by logging in to your account using below given user credentials. Please change your password regularly to make your account secure.",
			);
			User::create($userCredentials);
			RoleUserMapModel::create($roleData);
			DB::statement("call ProCrpEngineerNewRegistrationFinalData(?,?,?,?)",array(Input::get('EngineerReference'),$generatedId,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED,Auth::user()->Id));
		}catch(Exception $e){
			DB::rollback();
        	throw $e;
		}
		$smsMessage="Your registration fees for engineer registration has been received by CDB and your certificate has been activated. Your CDB No. is $cdbNo. Your username is $recipientAddress and password is $plainPassword";
		$this->sendEmailMessage($mailView,$mailData,$subject,$recipientAddress,$recipientName);
		$this->sendSms($smsMessage,$mobileNo);
		DB::commit();
		return Redirect::to('engineer/approvefeepayment')->with('savedsuccessmessage','Payment aganist the registration successfully recorded.');
	}
	public function rejectRegistration(){
		DB::beginTransaction();
		try{
			$rejectionCode=str_random(30);
			$engineerId=Input::get('EngineerReference');
			$engineer = EngineerModel::find($engineerId);
			$engineer->CmnApplicationRegistrationStatusId=CONST_CMN_APPLICATIONREGISTRATIONSTATUS_REJECTED;
			$engineer->RemarksByRejector=Input::get('RemarksByRejector');
			$engineer->RejectedDate=Input::get('RejectedDate');
			$engineer->SysRejectorUserId=Auth::user()->Id;
			$engineer->SysLockedByUserId=NULL;
			$engineer->SysRejectionCode=$rejectionCode;
			$engineer->save();
		}catch(Exception $e){
			DB::rollback();
        	throw $e;
		}
		DB::commit();
		$engineerDetails=EngineerModel::engineerHardList(Input::get('EngineerReference'))->get(array('Name','Email','ReferenceNo','ApplicationDate','RemarksByRejector','SysRejectionCode','MobileNo'));
		/*----------------------Contractor Email Details and New Details------------------*/
		$recipientAddress=$engineerDetails[0]->Email;
		$recipientName=$engineerDetails[0]->NameOfFirm;
		$applicationNo=$engineerDetails[0]->ReferenceNo;
		$applicationDate=$engineerDetails[0]->ApplicationDate;
		$remarksByRejector=$engineerDetails[0]->RemarksByRejector;
		$rejectionSysCode=$engineerDetails[0]->SysRejectionCode;
		$mobileNo=$engineerDetails[0]->MobileNo;
		$mailView="emails.crps.mailapplicationrejected";
		$subject="Rejection of Your Registration with CDB";
		$mailData=array(
			'prefix'=>'engineer',
			'applicantName'=>$recipientName,
			'applicationNo'=>$applicationNo,
			'applicationDate'=>$applicationDate,
			'remarksByRejector'=>$remarksByRejector,
			'referenceApplicant'=>Input::get('EngineerReference'),
			'rejectionSysCode'=>$rejectionSysCode,
			'mailMessage'=>"Construction Development Board (CDB) has rejected your application for registration of engineer with CDB. Please read the reason for rejection given below and reapply by making the necessary corrections.",
		);
		$smsMessage="Your application for engineer registration has been rejected. Please check your email ($recipientAddress) to view the reason for rejection.";
		$this->sendEmailMessage($mailView,$mailData,$subject,$recipientAddress,$recipientName);
		$this->sendSms($smsMessage,$mobileNo);
		return Redirect::to('engineer/'.Input::get('RedirectRoute'))->with('savedsuccessmessage','The application has been rejected.');
	}
	public function checkRejectedSecurityCode($engineerReference,$securityCode){
		if(strlen($engineerReference)==36 && strlen($securityCode)==30){
			$checkEngineerReference=EngineerModel::where('SysRejectionCode',$securityCode)->pluck('Id');
			$currentStatus=EngineerModel::where('Id',$checkEngineerReference)->pluck('CmnApplicationRegistrationStatusId');
			$rejectedDate=EngineerModel::where('Id',$checkEngineerReference)->pluck('RejectedDate');
			$rejectedDate=new DateTime($rejectedDate);
			$currentDate=new DateTime(date('Y-m-d'));
			$noOfDays=$rejectedDate->diff($currentDate);
			if($checkEngineerReference==$engineerReference && $currentStatus==CONST_CMN_APPLICATIONREGISTRATIONSTATUS_REJECTED && (int)$noOfDays->d < 31){
				DB::table('crpengineer')->where('Id',$engineerReference)->update(array('ApplicationDate'=>date('Y-m-d')));
				return Redirect::to('engineer/registration/'.$engineerReference.'?editbyapplicant=true&rejectedapplicationreapply=true');	
			}else{
				return Redirect::to('ezhotin/rejectedapplicationmessage');
			}
		}else{
			App::abort('404');
		}
	}
	public function setRecordLock($engineerId){
		$pickerByUserFullName=null;
		$redirectUrl=Input::get('redirectUrl');
		$notification = Input::get('notification');
		if((bool)$notification){
			DB::table('sysapplicationnotification')->where('ApplicationId',$engineerId)->update(array('IsRead'=>1));
		}
		$hasBeenPicked=EngineerModel::engineerHardList($engineerId)->pluck('SysLockedByUserId');
		if((bool)$hasBeenPicked!=null){
			$pickerByUserFullName=User::where('Id',$hasBeenPicked)->pluck('FullName');
		}else{
			$engineer=EngineerModel::find($engineerId);
			$engineer->SysLockedByUserId=Auth::user()->Id;
			$engineer->save();
		}
		return Redirect::to($redirectUrl)->with('ApplicationAlreadyPicked',$pickerByUserFullName);
	}
	public function newCommentAdverseRecord($engineerId){
		$engineer=EngineerFinalModel::engineerHardList($engineerId)->get(array('Id','CDBNo','Name'));
		return View::make('crps.engineernewadverserecordsandcomments')
					->with('engineerId',$engineerId)
					->with('engineer',$engineer);	
	}
	public function editCommentAdverseRecord($engineerId){
		$engineer=EngineerFinalModel::engineerHardList($engineerId)->get(array('Id','CDBNo','Name'));
		$commentsAdverseRecords=EngineerCommentAdverseRecordModel::commentAdverseRecordList($engineerId)->get(array('Id','Date','Remarks','Type'));
		return View::make('crps.engineereditadverserecordscomments')
					->with('engineer',$engineer)
					->with('commentsAdverseRecords',$commentsAdverseRecords);
	}
	public function saveCommentAdverseRecord(){
		$postedValues=Input::all();
		$date=$this->convertDate(Input::get('Date'));
		$postedValues['Date']=$date;
		$validation = new EngineerCommentAdverseRecordModel;
		if(!($validation->validate($postedValues))){
		    $errors = $validation->errors();
		    return Redirect::to('engineer/newcommentsadverserecords/'.$postedValues['CrpEngineerFinalId'])->withErrors($errors)->withInput();
		}
		EngineerCommentAdverseRecordModel::create($postedValues);
		return Redirect::to('engineer/newcommentsadverserecordslist')->with('savedsuccessmessage','Comment/Adverse Record sucessfully added.');
	}
	public function updateCommentAdverseRecord(){
		$postedValues=Input::all();
		$date=$this->convertDate(Input::get('Date'));
		$postedValues['Date']=$date;
		$instance=EngineerCommentAdverseRecordModel::find($postedValues['Id']);
		$instance->fill($postedValues);
		$instance->update();
		return Redirect::to('engineer/editcommentsadverserecords/'.$postedValues['CrpEngineerFinalId'])->with('savedsuccessmessage','Record has been successfully updated');;
	}
	public function blacklistDeregisterList(){
		$reRegistration=1;
		$type=3;
		$parameters=array();
		$serviceSectorTypeId=Input::get('CmnServiceSectorTypeId');
		$tradeId=Input::get('CmnTradeId');
		$engineerId=Input::get('CrpEngineerId');
		$CDBNo=Input::get('CDBNo');
		$query="select T1.Id,T1.ReferenceNo,T1.ApplicationDate,T1.CDBNo,T1.CIDNo,T1.Name as EngineerName,T1.EmployerName,T1.Email,T1.MobileNo,T2.Name as Country,T3.NameEn as Dzongkhag,T4.Name as Salutation,T5.Name as EngineerType,T6.Name as Trade from crpengineerfinal T1 join cmncountry T2 on T1.CmnCountryId=T2.Id left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id join cmnlistitem T4 on T1.CmnSalutationId=T4.Id join cmnlistitem T5 on T1.CmnServiceSectorTypeId=T5.Id join cmnlistitem T6 on T1.CmnTradeId=T6.Id where 1=1";
		if(Request::path()=="engineer/deregister"){
			$type=1;
			$reRegistration=0;
			$captionHelper="Registered";
			$captionSubject="Deregistration of Engineer";
			$query.=" and T1.CmnApplicationRegistrationStatusId=?";
			array_push($parameters,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED);
		}elseif(Request::path()=="engineer/blacklist"){
			$type=2;
			$reRegistration=0;
			$captionHelper="Registered";
			$captionSubject="Blacklisting of Engineer";
			$query.=" and T1.CmnApplicationRegistrationStatusId=?";
			array_push($parameters,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED);
		}elseif(Request::path()=="engineer/reregistration"){
			$captionHelper="Deregistered or Blacklisted";
			$captionSubject="Re-registration of Engineer";
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
		if((bool)$serviceSectorTypeId!=NULL || (bool)$tradeId!=NULL || (bool)$engineerId!=NULL || (bool)$CDBNo!=NULL){
			if((bool)$serviceSectorTypeId!=NULL){
				$query.=" and T1.CmnServiceSectorTypeId=?";
				array_push($parameters,$serviceSectorTypeId);
			}
			if((bool)$tradeId!=NULL){
				$query.=" and T1.CmnTradeId=?";
				array_push($parameters,$tradeId);
			}
			if((bool)$engineerId!=NULL){
				$query.=" and T1.Id=?";
				array_push($parameters,$engineerId);
			}
			if((bool)$CDBNo!=NULL){
				$query.=" and T1.CDBNo=?";
	            array_push($parameters,$CDBNo);
			}
		}
		$engineers=EngineerFinalModel::engineerHardListAll()->get(array('Id','Name'));
		$serviceSectorTypes=CmnListItemModel::serviceSectorType()->get(array('Id','Name'));
		$trades=CmnListItemModel::trade()->whereIn('ReferenceNo', array(4001,4002,4003))->get(array('Id','Name'));
		$engineerLists=DB::select($query." order by EngineerName".$limit,$parameters);
		return View::make('crps.engineerderegistrationlist')
					->with('reRegistration',$reRegistration)
					->with('CDBNo',$CDBNo)
					->with('type',$type)
					->with('engineerId',$engineerId)
					->with('serviceSectorTypeId',$serviceSectorTypeId)
					->with('tradeId',$tradeId)
					->with('serviceSectorTypes',$serviceSectorTypes)
					->with('trades',$trades)
					->with('engineerLists',$engineerLists)
					->with('engineers',$engineers)
					->with('captionHelper',$captionHelper)
					->with('captionSubject',$captionSubject);
	}
	public function deregisterBlackListRegistration(){
		$postedValues=Input::all();
		$engineerReference=$postedValues['CrpEngineerId'];
		$engineerUserId=EngineerFinalModel::where('Id',$engineerReference)->pluck('SysUserId');

		DB::beginTransaction();
		try{
			if(Input::has('DeRegisteredDate')){
				$postedValues['DeRegisteredDate']=$this->convertDate($postedValues['DeRegisteredDate']);
			}elseif(Input::has('BlacklistedDate')){
				$postedValues['BlacklistedDate']=$this->convertDate($postedValues['BlacklistedDate']);
			}else{
				$postedValues['ReRegistrationDate']=$this->convertDate($postedValues['ReRegistrationDate']);
			}
			$instance=EngineerFinalModel::find($postedValues['CrpEngineerId']);
			$instance->fill($postedValues);
			$instance->update();
			$userInstance=User::find($engineerUserId);
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
					$engineerAdverserecordInstance = new EngineerCommentAdverseRecordModel;
					$engineerAdverserecordInstance->CrpEngineerFinalId = $engineerReference;
					$engineerAdverserecordInstance->Date=date('Y-m-d');
					$engineerAdverserecordInstance->Remarks=Input::get('BlacklistedRemarks');
					$engineerAdverserecordInstance->Type=2;
					$engineerAdverserecordInstance->save();
				}else{
					$engineerAdverserecordInstance = new EngineerCommentAdverseRecordModel;
					$engineerAdverserecordInstance->CrpEngineerFinalId = $engineerReference;
					$engineerAdverserecordInstance->Date=date('Y-m-d');
					$engineerAdverserecordInstance->Remarks=Input::get('DeregisteredRemarks');
					$engineerAdverserecordInstance->Type=2;
					$engineerAdverserecordInstance->save();
				}	
			}
		}catch(Exception $e){
			DB::rollback();
        	throw $e;
		}
		DB::commit();
		return Redirect::to('engineer/'.$redirectRoute)->with('savedsuccessmessage','Successfully updated');
	}
	public function printDetails($engineerId){
		if(Route::current()->getUri()=="engineer/viewprintdetails/{engineerid}"){
			$data['isFinalPrint']=1;
			$engineerInformations=EngineerFinalModel::engineer($engineerId)->get(array('crpengineerfinal.CDBNo','crpengineerfinal.CIDNo','crpengineerfinal.Name','crpengineerfinal.Gewog','crpengineerfinal.Village','crpengineerfinal.Email','crpengineerfinal.MobileNo','crpengineerfinal.EmployerName','crpengineerfinal.EmployerAddress','crpengineerfinal.GraduationYear','crpengineerfinal.NameOfUniversity','T1.Name as EngineerType','T2.Name as Salutation','T3.Name as Country','T4.NameEn as Dzongkhag','T5.Name as Qualification','T6.Name as UniversityCountry','T7.Name as CurrentStatus','T8.Name as Trade'));
		}else{
			$data['isFinalPrint']=0;
			$engineerServiceSectorType=EngineerModel::where('Id',$engineerId)->pluck('CmnServiceSectorTypeId');
			if($engineerServiceSectorType==CONST_CMN_SERVICESECTOR_PVT){
				$feeDetails=DB::select("select 'Private' as SectorType,EngineerPvtAmount as NewRegistrationFee,EngineerPvtValidity as RegistrationValidity from crpservice where Id=? limit 1",array(CONST_SERVICETYPE_NEW));
			}else{
				$feeDetails=DB::select("select 'Goverment' as SectorType,EngineerGovtAmount as NewRegistrationFee,EngineerGovtValidity as RegistrationValidity from crpservice where Id=? limit 1",array(CONST_SERVICETYPE_NEW));
			}
			$data['feeDetails']=$feeDetails;
			$engineerInformations=EngineerModel::engineer($engineerId)->get(array('crpengineer.CDBNo','crpengineer.CIDNo','crpengineer.Name','crpengineer.Gewog','crpengineer.Village','crpengineer.Email','crpengineer.MobileNo','crpengineer.EmployerName','crpengineer.EmployerAddress','crpengineer.GraduationYear','crpengineer.NameOfUniversity','T1.Name as EngineerType','T2.Name as Salutation','T3.Name as Country','T4.NameEn as Dzongkhag','T5.Name as Qualification','T6.Name as UniversityCountry','T7.Name as CurrentStatus','T8.Name as Trade'));
		}
		$data['printTitle']='Engineer Information';
		$data['engineerInformations']=$engineerInformations;
		$pdf = App::make('dompdf');
		$pdf->loadView('printpages.engineerviewprintinformation',$data)->setPaper('a4')->setOrientation('potrait');
		return $pdf->stream();
	}
	public function checkCDBNo(){
		$inputCDBNo=Input::get('inputCDBNo');
		$cdbNoFinalCount=EngineerFinalModel::engineerHardListAll()->where('CDBNo',$inputCDBNo)->count();
		$cdbNoCount=EngineerModel::engineerHardListAll()->where('CDBNo',$inputCDBNo)->whereIn('CmnApplicationRegistrationStatusId',array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVEDFORPAYMENT,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_VERIFIED))->count();
		if((int)$cdbNoCount>0 || (int)$cdbNoFinalCount>0){
			return 0;
		}
		return 1;
	}
    public function fetchEngineersJSON(){
        $term = Input::get('term');
        $engineers = DB::table('crpengineerfinal')->where(DB::raw('TRIM(Name)'),DB::raw('like'),"%$term%")->get(array('Id',DB::raw('TRIM(Name) as Name')));
        $engineersJSON = array();
        foreach($engineers as $engineer){
            array_push($engineersJSON,array('id'=>$engineer->Id,'value'=>trim($engineer->Name)));
        }
        return Response::json($engineersJSON);
    }
    public function deleteCommentAdverseRecord(){
    	$id = Input::get('id');
    	try{
    		DB::table('crpengineercommentsadverserecord')->where('Id',$id)->delete();	
    		return 1;
    	}catch(Exception $e){
    		return 0;
    	}
    }
}