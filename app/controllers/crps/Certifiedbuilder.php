<?php

class Certifiedbuilder extends CrpsController{

	public function certifiedbuilderList(){
		$parameters=array();
		$linkText='Edit';
		$link='certifiedbuilder/editdetails/';
		$certifiedbuilderId=Input::get('CrpCertifiedBuilderId');
		$CDBNo=Input::get('CDBNo');
		$registrationStatus=Input::get('RegistrationStatus');
		$tradeLicenseNo = Input::get('TradeLicenseNo');
		$fromDate = Input::get('FromDate');
		$toDate = Input::get('ToDate');

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

		
		$query="select T1.Id,T1.RegistrationExpiryDate,T1.ApplicationDate,T1.CDBNo,T1.MobileNo,Z.Name as Status,Z.ReferenceNo as StatusReference,T1.Email,T1.NameOfFirm,T4.Name as OwnershipType from crpcertifiedbuilderfinal T1 left join cmnlistitem Z on Z.Id = T1.CmnApplicationRegistrationStatusId  join cmnlistitem T4 on T1.CmnOwnershipTypeId=T4.Id  where 1";
		//array_push($parameters,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED);
		if(Route::current()->getUri()=="certifiedbuilder/viewprintlist"){
			$linkText='View/Print';
			$link='certifiedbuilder/viewprintdetails/';
			}elseif(Route::current()->getUri()=="certifiedbuilder/newcommentsadverserecordslist"){
			$linkText='Add';
			$link='certifiedbuilder/newcommentsadverserecords/';
		}elseif(Route::current()->getUri()=="certifiedbuilder/editcommentsadverserecordslist"){
			$linkText='View';
			$link='certifiedbuilder/editcommentsadverserecords/';
		}
		if((bool)$certifiedbuilderId!=NULL || (bool)$CDBNo!=NULL || (bool)$registrationStatus!=NULL){
			if((bool)$certifiedbuilderId!=NULL){
				$query.=" and T1.Id=?";
				array_push($parameters,$certifiedbuilderId);
			}
			if((bool)$CDBNo!=NULL){
				$query.=" and T1.CDBNo=?";
	            array_push($parameters,$CDBNo);
			}
			if((bool)$registrationStatus!=NULL){
				$query.=" and T1.CmnApplicationRegistrationStatusId=?";
	            array_push($parameters,$registrationStatus);
			}
		}
		if((bool)$tradeLicenseNo){
			$query.=" and T1.TradeLicenseNo = ?";
			array_push($parameters,$tradeLicenseNo);
		}
		if((bool)$fromDate){
			$query.=" and T1.RegistrationApprovedDate >= ?";
			array_push($parameters,$this->convertDate($fromDate));
		}
		if((bool)$toDate){
			$query.=" and T1.RegistrationApprovedDate <= ?";
			array_push($parameters,$this->convertDate($toDate));
		}
	
	    $status=CmnListItemModel::registrationStatus()->get(array('Id','Name'));
	    $certifiedbuilderlists=DB::select($query." order by CDBNo,NameOfFirm".$limit,$parameters);
	    return View::make('crps.certifiedbuilderlist')
				->with('pageTitle','List of Certified Builder')
				->with('link',$link)
				->with('linkText',$linkText)
				->with('CDBNo',$CDBNo)
				->with('registrationStatus',$registrationStatus)
				->with('certifiedbuilderId',$certifiedbuilderId)
				->with('certifiedbuilderlists',$certifiedbuilderlists);
    }
	public function printDetails($certifiedbuilderId){
		$isFinalPrint=0;
		if(Route::current()->getUri()=="certifiedbuilder/viewprintdetails/{certifiedbuilderId}"){
		
			$isFinalPrint=1;
			$generalInformation=CertifiedbuilderFinalModel::certifiedbuilder($certifiedbuilderId)->get(array('crpcertifiedbuilderfinal.Id',
			'crpcertifiedbuilderfinal.ReferenceNo','crpcertifiedbuilderfinal.RegistrationExpiryDate','crpcertifiedbuilderfinal.CDBNo',
			'crpcertifiedbuilderfinal.TPN','crpcertifiedbuilderfinal.Gewog','crpcertifiedbuilderfinal.RegisteredAddress',
			'crpcertifiedbuilderfinal.Village','crpcertifiedbuilderfinal.ApplicationDate','crpcertifiedbuilderfinal.NameOfFirm',
			 'crpcertifiedbuilderfinal.TradeLicenseNo','crpcertifiedbuilderfinal.Address','crpcertifiedbuilderfinal.Email',
			 'crpcertifiedbuilderfinal.TelephoneNo','crpcertifiedbuilderfinal.MobileNo','crpcertifiedbuilderfinal.FaxNo',
			 'crpcertifiedbuilderfinal.CmnApplicationRegistrationStatusId','T8.Name as Country','T4.Name as OwnershipType',
			 'T2.NameEn as Dzongkhag','T5.NameEn as RegisteredDzongkhag'));

			$certifiedBuilderInformations=CertifiedbuilderFinalModel::certifiedbuilder($certifiedbuilderId)->get(array('crpcertifiedbuilderfinal.Id',
			'crpcertifiedbuilderfinal.ReferenceNo','crpcertifiedbuilderfinal.ApplicationDate','crpcertifiedbuilderfinal.CDBNo',
			'crpspecilizedtradefinal.CIDNo','crpcertifiedbuilderfinal.Gewog','crpcertifiedbuilderfinal.Village','crpcertifiedbuilderfinal.Email',
			'crpcertifiedbuilderfinal.MobileNo', 'crpcertifiedbuilderfinal.TelephoneNo','T2.NameEn as Dzongkhag','T3.Name as CurrentStatus'));

			$ownerPartnerDetails=CertifiedbuilderHumanResourceFinalModel::certifiedbuilderPartner($certifiedbuilderId)
			->get(array('crpcertifiedbuilderhumanresourcefinal.CIDNo','crpcertifiedbuilderhumanresourcefinal.Name',
			'crpcertifiedbuilderhumanresourcefinal.Sex','crpcertifiedbuilderhumanresourcefinal.JoiningDate',
			'crpcertifiedbuilderhumanresourcefinal.ShowInCertificate','T1.Name as Country','T2.Name as Salutation','T3.Name as Designation'));

			$certifiedbuilderHumanResources=CertifiedbuilderHumanResourceFinalModel::certifiedbuilderHumanResource($certifiedbuilderId)
			->get(array('crpcertifiedbuilderhumanresourcefinal.Name','crpcertifiedbuilderhumanresourcefinal.CIDNo',
			'crpcertifiedbuilderhumanresourcefinal.Sex','crpcertifiedbuilderhumanresourcefinal.JoiningDate',
			'crpcertifiedbuilderhumanresourcefinal.Name','T2.Name as Qualification','T3.Name as Trade','T4.Name as Designation',
			'T5.Name as Country'));

			$certifiedbuilderEquipments=CertifiedbuilderEquipmentFinalModel::certifiedbuilderEquipment($certifiedbuilderId)
			->get(array('crpcertifiedbuilderequipmentfinal.RegistrationNo','crpcertifiedbuilderequipmentfinal.ModelNo',
			'crpcertifiedbuilderequipmentfinal.Quantity','T1.Name'));

		}else{

		
			$generalInformation=CertifiedbuilderFinalModel::certifiedbuilder($certifiedbuilderId)->get(array('crpcertifiedbuilderfinal.Id',
			'crpcertifiedbuilderfinal.ReferenceNo','crpcertifiedbuilderfinal.RegistrationExpiryDate','crpcertifiedbuilderfinal.CDBNo',
			'crpcertifiedbuilderfinal.TPN','crpcertifiedbuilderfinal.Gewog','crpcertifiedbuilderfinal.RegisteredAddress',
			'crpcertifiedbuilderfinal.Village','crpcertifiedbuilderfinal.ApplicationDate','crpcertifiedbuilderfinal.NameOfFirm',
			 'crpcertifiedbuilderfinal.TradeLicenseNo','crpcertifiedbuilderfinal.Address','crpcertifiedbuilderfinal.Email',
			 'crpcertifiedbuilderfinal.TelephoneNo','crpcertifiedbuilderfinal.MobileNo','crpcertifiedbuilderfinal.FaxNo',
			 'crpcertifiedbuilderfinal.CmnApplicationRegistrationStatusId','T8.Name as Country','T4.Name as OwnershipType',
			 'T2.NameEn as Dzongkhag','T5.NameEn as RegisteredDzongkhag'));

			$certifiedBuilderInformations=CertifiedbuilderModel::certifiedbuilder($certifiedbuilderId)->get(array('crpcertifiedbuilder.Id',
			'crpcertifiedbuilder.ReferenceNo','crpcertifiedbuilder.ApplicationDate','crpcertifiedbuilder.CDBNo',
			'crpcertifiedbuilder.NameOfFirm','crpcertifiedbuilder.Gewog','crpcertifiedbuilder.Village','crpcertifiedbuilder.Email',
			'crpcertifiedbuilder.MobileNo','crpcertifiedbuilder.NameOfFirm','crpcertifiedbuilder.RegisteredAddress','crpcertifiedbuilder.TelephoneNo',
			'crpcertifiedbuilder.RemarksByVerifier','T1.Name as Salutation','T2.NameEn as Dzongkhag','T3.Name as CurrentStatus'));

			$ownerPartnerDetails=CertifiedbuilderHumanResourceModel::certifiedbuilderPartner($certifiedbuilderId)
			->get(array('crpcertifiedbuilderhumanresource.CIDNo','crpcertifiedbuilderhumanresource.Name','crpcertifiedbuilderhumanresource.Sex',
			'crpcertifiedbuilderhumanresource.ShowInCertificate','T1.Name as Country','T2.Name as Salutation','T3.Name as Designation'));

			$certifiedbuilderHumanResources=CertifiedbuilderHumanResourceModel::certifiedbuilderHumanResource($certifiedbuilderId)
			->get(array('crpcertifiedbuilderhumanresource.Name','crpcertifiedbuilderhumanresource.CIDNo','crpcertifiedbuilderhumanresource.JoiningDate',
			'crpcertifiedbuilderhumanresource.Sex','crpcertifiedbuilderhumanresource.Name','crpcertifiedbuilderhumanresource.Verified',
			'crpcertifiedbuilderhumanresource.Approved','T1.Name as Salutation','T2.Name as Qualification','T3.Name as Trade','T4.Name as Designation',
			'T5.Name as Country'));

			$certifiedbuilderEquipments=CertifiedbuilderEquipmentModel::certifiedbuilderEquipment($certifiedbuilderId)
			->get(array('crpcertifiedbuilderequipment.RegistrationNo','crpcertifiedbuilderequipment.ModelNo','crpcertifiedbuilderequipment.Quantity',
			'crpcertifiedbuilderequipment.Verified','crpcertifiedbuilderequipment.Approved','T1.Name'));
		}
		$data['isFinalPrint']=$isFinalPrint;
		$data['printTitle']='Certified Builder Information';
		$data['certifiedBuilderInformations']=$certifiedBuilderInformations;
		$data['ownerPartnerDetails']=$ownerPartnerDetails;
		 
		$data['generalInformation']=$generalInformation;
		$data['certifiedbuilderHumanResources']=$certifiedbuilderHumanResources;
		$data['certifiedbuilderEquipments']=$certifiedbuilderEquipments;
		$pdf = App::make('dompdf');
		$pdf->loadView('printpages.certifiedbuilderviewprintinformation',$data)->setPaper('a4')->setOrientation('potrait');
		return $pdf->stream();
	}
	
	//Deregister & suspend & Reregistration begins from here

public function deregisterBlackListRegistration(){

	$postedValues=Input::all();
	$CertifiedBuilderReference=$postedValues['CrpCertifiedBuilderId'];
	$certifiedbuilderUserId=CertifiedbuilderFinalModel::where('Id',$CertifiedBuilderReference)->pluck('SysUserId');
	DB::beginTransaction();
	
	try{
		if(Input::has('DeRegisteredDate')){
			$postedValues['DeRegisteredDate']=$this->convertDate($postedValues['DeRegisteredDate']);
		}elseif(Input::has('BlacklistedDate')){
			$postedValues['BlacklistedDate']=$this->convertDate($postedValues['BlacklistedDate']);
		}else{
			$postedValues['ReRegistrationDate']=$this->convertDate($postedValues['ReRegistrationDate']);
		}
		$instance=CertifiedbuilderFinalModel::find($CertifiedBuilderReference);
		$instance->fill($postedValues);
		$instance->update();
		
		$userInstance=User::find($certifiedbuilderUserId);
		if($postedValues['CmnApplicationRegistrationStatusId']==CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED){
			$redirectRoute="reregistration";
			if((bool)$certifiedbuilderUserId){
				$userInstance=User::find($certifiedbuilderUserId);
				$userInstance->Status=1;
				$userInstance->save();
			}

			$certifiedbuilderAdverserecordInstance = new CertifiedbuilderCommentAdverseRecordModel;
			$certifiedbuilderAdverserecordInstance->CrpCertifiedbuilderFinalId = $CertifiedBuilderReference;
			$certifiedbuilderAdverserecordInstance->Date=date('Y-m-d');
			
			$certifiedbuilderAdverserecordInstance->Remarks=Input::get('ReRegistrationRemarks');
			$certifiedbuilderAdverserecordInstance->Type=1;
			$certifiedbuilderAdverserecordInstance->save();
		}else{
			//for suspension
			if(Input::has('BlacklistedRemarks')){
				$redirectRoute="suspend";
			}else{
				$redirectRoute="deregister";
			}
			if((bool)$certifiedbuilderUserId){
				$userInstance=User::find($certifiedbuilderUserId);
				$userInstance->Status=0;
				$userInstance->save();
			}
			/*---Insert Adverse Record i.e the remarks if the contractor is deregistered/blacklisted*/
			if(Input::has('BlacklistedRemarks')){
				$certifiedbuilderAdverserecordInstance = new CertifiedbuilderCommentAdverseRecordModel;
				$certifiedbuilderAdverserecordInstance->CrpCertifiedbuilderFinalId = $CertifiedBuilderReference;
				$certifiedbuilderAdverserecordInstance->Date=date('Y-m-d');
				$certifiedbuilderAdverserecordInstance->Remarks=Input::get('BlacklistedRemarks');
				$certifiedbuilderAdverserecordInstance->Type=1;
				$certifiedbuilderAdverserecordInstance->save();
			}else{
				if(Input::has('RevokedRemarks')){
					$certifiedbuilderAdverserecordInstance = new CertifiedbuilderCommentAdverseRecordModel;
					$certifiedbuilderAdverserecordInstance->CrpCertifiedbuilderFinalId = $CertifiedBuilderReference;
					$certifiedbuilderAdverserecordInstance->Date=date('Y-m-d');
					
					$certifiedbuilderAdverserecordInstance->Remarks=Input::get('RevokedRemarks');
					$certifiedbuilderAdverserecordInstance->Type=1;
					$certifiedbuilderAdverserecordInstance->save();
				}else{
					$certifiedbuilderAdverserecordInstance = new CertifiedbuilderCommentAdverseRecordModel;
					$certifiedbuilderAdverserecordInstance->CrpCertifiedbuilderFinalId = $CertifiedBuilderReference;
					$certifiedbuilderAdverserecordInstance->Date=date('Y-m-d');
					
					$certifiedbuilderAdverserecordInstance->Type=1;
					$certifiedbuilderAdverserecordInstance->save();
				}

			}
		}
	}catch(Exception $e){
		DB::rollback();
		throw $e;
	}
	DB::commit();
	return Redirect::to('certifiedbuilder/'.$redirectRoute)->with('savedsuccessmessage','Successfully updated');
}



public function blacklistDeregisterList(){
	$type=3;
	$reRegistration=1;
	$parameters=array();
	$certifiedbuilderId=Input::get('CrpCertifiedBuilderId');
	$CDBNo=Input::get('CDBNo');
	$tradeLicenseNo = Input::get('TradeLicenseNo');
	$fromDate = Input::get('FromDate');
	$toDate = Input::get('ToDate');
	$statuses = DB::table('cmnlistitem')->whereIn('ReferenceNo',array(12008,12009,12010))->get(array('Id','Name'));
	
	$query="select T1.Id,T1.ApplicationDate,T1.CDBNo,T1.MobileNo,T1.Email,T1.NameOfFirm from crpcertifiedbuilderfinal T1  where CDBNo LIKE '%CB%'";
	if(Request::path()=="certifiedbuilder/deregister"){
		$reRegistration=0;
		$type=1;
		$captionHelper="Registered";
		$captionSubject="Deregistration of Certified Builder";
		$query.=" and T1.CmnApplicationRegistrationStatusId=?";
		array_push($parameters,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED);
	}elseif(Request::path()=="certifiedbuilder/suspend"){
		$reRegistration=0;
		$type=2;
		$captionHelper="Registered";
		$captionSubject="Blacklisting of Certified Builder";
		$query.=" and T1.CmnApplicationRegistrationStatusId=?";
		array_push($parameters,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED);
	}else if(Request::path()=="certifiedbuilder/revoke"){
		$reRegistration=0;
		$type=2;
		$captionHelper="Registered";
		$captionSubject="Revoke/Suspend/Debar of Certified Builder";
		$query.=" and T1.CmnApplicationRegistrationStatusId=?";
		array_push($parameters,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED);
	}elseif(Request::path()=="certifiedbuilder/reregistration"){
		$captionHelper="Deregistered or Blacklisted";
		$captionSubject="Re-registration of Certified Builder";
		$query.=" and (T1.CmnApplicationRegistrationStatusId in (?,?,?,?))";
		array_push($parameters,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_DEREGISTERED);
		array_push($parameters,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_BLACKLISTED);
		array_push($parameters, CONST_CMN_APPLICATIONREGISTRATIONSTATUS_REVOKED);
		array_push($parameters, CONST_CMN_APPLICATIONREGISTRATIONSTATUS_DEBARRED);
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
	$hasParams = false;
	if((bool)$certifiedbuilderId!=NULL || (bool)$CDBNo!=NULL){
		$hasParams =true;
		if((bool)$certifiedbuilderId!=NULL){
			$query.=" and T1.Id=?";
			array_push($parameters,$certifiedbuilderId);
		}
		if((bool)$CDBNo!=NULL){
			$query.=" and T1.CDBNo=?";
			array_push($parameters,$CDBNo);
		}
	}
	if((bool)$tradeLicenseNo){
		$hasParams =true;
		$query.=" and T1.TradeLicenseNo = ?";
		array_push($parameters,$tradeLicenseNo);
	}
	if((bool)$fromDate){
		$hasParams =true;
		$query.=" and T1.RegistrationApprovedDate >= ?";
		array_push($parameters,$this->convertDate($fromDate));
	}
	if((bool)$toDate){
		$hasParams =true;
		$query.=" and T1.RegistrationApprovedDate <= ?";
		array_push($parameters,$this->convertDate($toDate));
	}
	$certifiedbuilderListsAll=CertifiedbuilderFinalModel::certifiedbuilderHardListAll()->get(array('Id','NameOfFirm'));
	$certifiedbuilderLists=DB::select($query." order by CDBNo,NameOfFirm".$limit,$parameters);
	return View::make('crps.certifiedbuilderderegisterationlist')
		->with('CDBNo',$CDBNo)
		->with('type',$type)
		->with('statuses',$statuses)
		->with('captionHelper',$captionHelper)
		->with('captionSubject',$captionSubject)
		->with('reRegistration',$reRegistration)
		->with('certifiedbuilderId',$certifiedbuilderId)
		->with('certifiedbuilderLists',$certifiedbuilderLists)
		->with('certifiedbuilderListsAll',$certifiedbuilderListsAll);
}



	/* Certifiedbuilder edit begins from here */

	public function editDetails($certifiedbuilderId){
		$registrationApprovedForPayment=0;
		$userCertifiedbuilder=0;
		$loggedInUser = Auth::user()->Id;
		$userRoles=DB::table('sysuserrolemap')->where('SysUserId',$loggedInUser)->lists('SysRoleId');
		$isAdmin = false;
		if(in_array(CONST_ROLE_ADMINISTRATOR,$userRoles)){
			$isAdmin = true;
		}
		$parameters=array();
		
		$generalInformation=CertifiedbuilderFinalModel::certifiedbuilder($certifiedbuilderId)->get(array('crpcertifiedbuilderfinal.Id','crpcertifiedbuilderfinal.ReferenceNo','crpcertifiedbuilderfinal.RegistrationExpiryDate','crpcertifiedbuilderfinal.CDBNo','crpcertifiedbuilderfinal.TPN','crpcertifiedbuilderfinal.Gewog','crpcertifiedbuilderfinal.RegisteredAddress','crpcertifiedbuilderfinal.Village','crpcertifiedbuilderfinal.DeRegisteredDate','crpcertifiedbuilderfinal.BlacklistedDate','crpcertifiedbuilderfinal.DeregisteredRemarks','crpcertifiedbuilderfinal.BlacklistedRemarks','crpcertifiedbuilderfinal.ApplicationDate','crpcertifiedbuilderfinal.NameOfFirm', 'crpcertifiedbuilderfinal.TradeLicenseNo','crpcertifiedbuilderfinal.Address','crpcertifiedbuilderfinal.Email','crpcertifiedbuilderfinal.TelephoneNo','crpcertifiedbuilderfinal.MobileNo','crpcertifiedbuilderfinal.FaxNo','crpcertifiedbuilderfinal.CmnApplicationRegistrationStatusId','T8.Name as Country','T4.Name as OwnershipType','T3.Name as Status','T2.NameEn as Dzongkhag','T5.NameEn as RegisteredDzongkhag'));
		$ownerPartnerDetails=CertifiedbuilderHumanResourceFinalModel::certifiedbuilderPartner($certifiedbuilderId)->get(array('crpcertifiedbuilderhumanresourcefinal.Id','crpcertifiedbuilderhumanresourcefinal.CIDNo','crpcertifiedbuilderhumanresourcefinal.Name','crpcertifiedbuilderhumanresourcefinal.Sex','crpcertifiedbuilderhumanresourcefinal.JoiningDate','crpcertifiedbuilderhumanresourcefinal.ShowInCertificate','T1.Nationality as Country','T2.Name as Salutation','T3.Name as Designation'));
		$certifiedbuilderHumanResources=CertifiedbuilderHumanResourceFinalModel::certifiedbuilderHumanResource($certifiedbuilderId)->get(array('crpcertifiedbuilderhumanresourcefinal.Id','crpcertifiedbuilderhumanresourcefinal.Name','crpcertifiedbuilderhumanresourcefinal.CIDNo','crpcertifiedbuilderhumanresourcefinal.EditedOn','crpcertifiedbuilderhumanresourcefinal.CmnServiceTypeId','crpcertifiedbuilderhumanresourcefinal.Sex','crpcertifiedbuilderhumanresourcefinal.JoiningDate','crpcertifiedbuilderhumanresourcefinal.Name','T1.Name as Salutation','T2.Name as Qualification','T3.Name as Trade','T4.Name as Designation','T5.Nationality as Country','T10.CDBNO as CDBNo1', 'T11.ARNo as CDBNo2', 'T12.ARNo as CDBNo3', 'T13.CIDNo as CDBNo4'));
		$certifiedbuilderEquipments=CertifiedbuilderEquipmentFinalModel::certifiedbuilderEquipment($certifiedbuilderId)->get(array('crpcertifiedbuilderequipmentfinal.Id','crpcertifiedbuilderequipmentfinal.RegistrationNo','crpcertifiedbuilderequipmentfinal.ModelNo','crpcertifiedbuilderequipmentfinal.Quantity','crpcertifiedbuilderequipmentfinal.EditedOn','T1.Name'));
		$query= "SELECT T1.Id,T1.NameOfClient,T1.NameOfWork,T1.WorkId,T1.WorkOrderNo,T1.CmnDzongkhagId,T1.ContractPeriod,T1.WorkStartDate,
		T1.WorkCompletionDate,T6.BidAmount,T6.EvaluatedAmount,T2.Name AS ProcuringAgency,
		COALESCE(T5.Name,'New') AS WorkExecutionStatus FROM cbbiddingform 
		T1 LEFT JOIN cmnprocuringagency T2 ON T1.CmnProcuringAgencyId=T2.Id LEFT JOIN 
		cmnlistitem T5 ON T1.CmnWorkExecutionStatusId=T5.Id LEFT JOIN cbbiddingformdetail T6 ON T1.`Id`=T6.`CrpBiddingFormId` WHERE ByCDB=0";

		$certifiedbuilderTrackrecords =DB::select($query." order by T1.CreatedOn desc",$parameters);
		$certifiedbuilderHumanResourceAttachments=CertifiedbuilderHumanResourceAttachmentFinalModel::singleCertifiedbuilderHumanResourceAllAttachments($certifiedbuilderId)->get(array('crpcertifiedbuilderhumanresourceattachmentfinal.DocumentName','crpcertifiedbuilderhumanresourceattachmentfinal.DocumentPath','crpcertifiedbuilderhumanresourceattachmentfinal.CrpCertifiedbuilderHumanResourceFinalId as CrpCertifiedbuilderHumanResourceId'));
		$certifiedbuilderEquipmentAttachments=CertifiedbuilderEquipmentAttachmentFinalModel::singleCertifiedbuilderEquipmentAllAttachments($certifiedbuilderId)->get(array('crpcertifiedbuilderequipmentattachmentfinal.DocumentName','crpcertifiedbuilderequipmentattachmentfinal.DocumentPath','crpcertifiedbuilderequipmentattachmentfinal.CrpCertifiedbuilderEquipmentFinalId as CrpCertifiedbuilderEquipmentId'));
		$certifiedbuilderComments = CertifiedbuilderCommentAdverseRecordModel::commentList($certifiedbuilderId)->get(array('Id','Date','Remarks'));
		$certifiedbuilderAdverseRecords = CertifiedbuilderCommentAdverseRecordModel::adverseRecordList($certifiedbuilderId)->get(array('Id','Date','Remarks'));
		$certifiedbuilderAttachments=DB::table('crpcertifiedbuilderattachmentfinal')->where('CrpCertifiedbuilderFinalId',$certifiedbuilderId)->get(array('DocumentName','DocumentPath'));
		$certifiedbuilderEmployeesIds = DB::table('crpcertifiedbuilderhumanresourcefinal')->where('CrpCertifiedbuilderFinalId',$certifiedbuilderId)->select(DB::raw("TRIM(CIDNo) as EmpCIDNo"))->lists('EmpCIDNo');
		

		return View::make('crps.certifiedbuilderinformation')
			->with('isAdmin',$isAdmin)
			->with('registrationApprovedForPayment',$registrationApprovedForPayment)
			->with('certifiedbuilderAttachments',$certifiedbuilderAttachments)
			->with('userCertifiedbuilder',$userCertifiedbuilder)
			->with('certifiedbuilderId',$certifiedbuilderId)
			->with('generalInformation',$generalInformation)
			->with('ownerPartnerDetails',$ownerPartnerDetails)
			->with('certifiedbuilderHumanResources',$certifiedbuilderHumanResources)
			->with('certifiedbuilderEquipments',$certifiedbuilderEquipments)
			->with('certifiedbuilderTrackrecords',$certifiedbuilderTrackrecords)
			->with('certifiedbuilderHumanResourceAttachments',$certifiedbuilderHumanResourceAttachments)
			->with('certifiedbuilderEquipmentAttachments',$certifiedbuilderEquipmentAttachments)
			->with('certifiedbuilderComments',$certifiedbuilderComments)
			->with('certifiedbuilderAdverseRecords',$certifiedbuilderAdverseRecords)
			;
					
	}

	//General Information Edit Information Begins from Here

	public function generalInfoRegistration($certifiedbuilder=null){
		$isRejectedApp=0;
		$serviceByCertifiedbuilder=0;
		$isRenewalService=0;
		$newGeneralInfoSave=1;
		$editByCDB = false;
		$view="crps.certifiedbuilderregistrationgeneralinfo";
		$postRouteReference='certifiedbuilder/mcertifiedbuildergeneralinfo';
		$redirectUrl=Input::get('redirectUrl');
		$certifiedbuilderGeneralInfo=array(new CertifiedbuilderModel());
		$certifiedbuilderPartnerDetail=array(new CertifiedbuilderHumanResourceModel());
		
		if((bool)$certifiedbuilder!=null && !Input::has('usercdb')){
			if(Input::has('editbyapplicant')){
				if(Input::has('rejectedapplicationreapply')){
					$isRejectedApp=1; //HAS REAPPLIED
					
				}
				$view="crps.certifiedbuilderregistrationgeneralinfo";
			}else{
				$view="crps.certifiedbuildereditgeneralinfo";
			}
			$certifiedbuilderGeneralInfo=CertifiedbuilderModel::certifiedbuilderHardList($certifiedbuilder)->get(array('Id','ReferenceNo','ApplicationDate','CmnOwnershipTypeId','NameOfFirm','TPN','TradeLicenseNo','TelephoneNo','Gewog','Village','Address','Email','TelephoneNo','MobileNo','FaxNo','CmnCountryId','CmnDzongkhagId'));
			$certifiedbuilderPartnerDetail=CertifiedbuilderHumanResourceModel::certifiedbuilderPartnerHardList($certifiedbuilder)->get(array('Id','CIDNo','Name','Sex','JoiningDate','ShowInCertificate','CmnCountryId','CmnSalutationId','CmnDesignationId'));
		}
		if((bool)$certifiedbuilder!=null && Input::has('usercdb')){
			$editByCDB = true;
			$view="crps.certifiedbuildereditgeneralinfo";
			$newGeneralInfoSave=0;
			$certifiedbuilderGeneralInfo=CertifiedbuilderFinalModel::certifiedbuilderHardList($certifiedbuilder)->get(array('Id','ReferenceNo','TPN','TradeLicenseNo','ApplicationDate','NameOfFirm','CmnRegisteredDzongkhagId','Gewog','Village','RegisteredAddress','Address','Email','TelephoneNo','MobileNo','FaxNo','CmnCountryId','CmnDzongkhagId','CmnOwnershipTypeId'));
			$certifiedbuilderPartnerDetail=CertifiedbuilderHumanResourceFinalModel::certifiedbuilderPartnerHardList($certifiedbuilder)->get(array('Id','CIDNo','Name','Sex','JoiningDate','ShowInCertificate','CmnCountryId','CmnSalutationId','CmnDesignationId'));
		}
		$applicationReferenceNo=$this->tableTransactionNo('CertifiedbuilderModel','ReferenceNo');
		$country=CountryModel::country()->get(array('Id','Nationality','Name','Code'));
		$dzongkhag=DzongkhagModel::dzongkhag()->get(array('Id','NameEn'));
		$designation=CmnListItemModel::designation()->get(array('Id','Name',DB::raw('coalesce(ReferenceNo,22) as ReferenceNo')));
		$salutation=CmnListItemModel::salutation()->get(array('Id','Name'));
		$ownershipTypes=CmnListItemModel::ownershipType()->get(array('Id','ReferenceNo','Name'));
		return View::make($view)
			
			->with('editByCDB',$editByCDB)
			->with('isRejectedApp',$isRejectedApp)
			->with('redirectUrl',$redirectUrl)
			->with('isRenewalService',$isRenewalService)
			->with('isEdit',$certifiedbuilder)
			->with('postRouteReference',$postRouteReference)
			->with('serviceByCertifiedbuilder',$serviceByCertifiedbuilder)
			->with('newGeneralInfoSave',$newGeneralInfoSave)
			->with('isServiceByCertifiedbuilder','')
			->with('applicationReferenceNo',$applicationReferenceNo)
			->with('certifiedbuilderGeneralInfo',$certifiedbuilderGeneralInfo)
			->with('certifiedbuilderPartnerDetails',$certifiedbuilderPartnerDetail)
			->with('countries',$country)
			->with('dzongkhags',$dzongkhag)
			->with('designations',$designation)
			->with('salutations',$salutation)
			->with('ownershipTypes',$ownershipTypes);
	}

	public function saveGeneralInfo(){
		$postedValues=Input::except('ChangeOfLocationOwner','OtherServices','attachments','DocumentName','DocumentNameOwnerShipChange','attachmentsownershipchange','attachmentsfirmnamechange','DocumentNameFirmNameChange');
		$newGeneralInfoSave=Input::get('NewGeneralInfoSave');
		$isServiceByCertifiedbuilder=Input::get('ServiceByCertifiedbuilder');
		$validation = new CertifiedbuilderModel;
		if(!($validation->validate($postedValues))){
			$errors = $validation->errors();
			if((int)$isServiceByCertifiedbuilder!=1){
			return Redirect::to('certifiedbuilder/generalinforegistration')->withInput()->withErrors($errors);
			}else{
				return Redirect::to('certifiedbuilder/applyservicegeneralinfo/'.Input::get('CrpCertifiedBuilderId'))->withInput()->withErrors($errors);
			}
		}

		/*To check if already applied */
		if(!Input::has('OldApplicationId')){
			$isFinalContractor = DB::table('crpcertifiedbuilderfinal')->where('Id',Input::get('CrpCertifiedBuilderId'))->count();
			if($isFinalContractor==0){
				$finalTableId = DB::table('crpcertifiedbuilder')->where('Id',Input::get('CrpCertifiedBuilderId'))->pluck('CrpCertifiedBuilderId');
			}else{
				$finalTableId = Input::get('CrpCertifiedBuilderId');
			}
			$previousApplications = DB::table('crpcertifiedbuilder')->whereNotNull('CrpCertifiedBuilderId')->where('CrpCertifiedBuilderId',$finalTableId)->where(DB::raw('coalesce(RegistrationStatus,0)'),1)->whereNotIn('CmnApplicationRegistrationStatusId',array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_REJECTED))->count();
			if($previousApplications>0){
				$previousApplicationDetails = DB::table('crpcertifiedbuilder')->where('CrpCertifiedBuilderId',$finalTableId)->where(DB::raw('coalesce(RegistrationStatus,0)'),1)->whereNotIn('CmnApplicationRegistrationStatusId',array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_REJECTED))->select(DB::raw("GROUP_CONCAT(CONCAT('Application No. ',ReferenceNo,' dt. ',ApplicationDate) SEPARATOR '<br/>') as Applications"))->pluck('Applications');
				return Redirect::to('certifiedbuilder/mydashboard')->with("customerrormessage","<h4><strong> MESSAGE! You have following pending application(s) with CDB: </strong></h4><ol>$previousApplicationDetails</ol><strong>Please wait for us to process your previous application before submitting a new one!</strong> ");
			}
		}
		/* END */
		if(Input::hasFile('attachments')){
			$count = 0;
			$multiAttachments=array();
			foreach(Input::file('attachments') as $attachment){
				if($attachment!=NULL){
					$documentName = Input::get("DocumentName");
					$attachmentType=$attachment->getMimeType();
					$attachmentFileName=$attachment->getClientOriginalName();
					$attachmentName=str_random(6) . '_' . $attachment->getClientOriginalName();
					$destination=public_path().'/uploads/certifiedbuilder';
					$destinationDB='uploads/certifiedbuilder/'.$attachmentName;
					$multiAttachments1["DocumentName"]=(count($documentName)>1)?$documentName[$count]:$documentName[0];

					//CHECK IF IMAGE
					if(strpos($attachment->getClientMimeType(),'image/')>-1){
						$img = Image::make($attachment)->encode('jpg');
						$destinationDB = "uploads/certifiedbuilder/".str_random(15) . '_min_' .".jpg";
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
		}

		if(Input::hasFile('attachmentsownershipchange')){
			$countownershipchange = 0;
			$multiAttachmentsownershipchange=array();
			foreach(Input::file('attachmentsownershipchange') as $attachmentownership){
				if((bool)$attachmentownership){
					$documentName = Input::get("DocumentNameOwnerShipChange");
					$attachmentType=$attachmentownership->getMimeType();
					$attachmentFileName=$attachmentownership->getClientOriginalName();
					$attachmentName=str_random(6) . '_' . $attachmentownership->getClientOriginalName();
					$destination=public_path().'/uploads/certifiedbuilder';
					$destinationDB='uploads/certifiedbuilder/'.$attachmentName;
					if(isset($documentName[$countownershipchange])){
						$multiAttachmentsownershipchange1["DocumentName"]=$documentName[$countownershipchange];

						//CHECK IF IMAGE
						if(strpos($attachmentownership->getClientMimeType(),'image/')>-1){
							$img = Image::make($attachmentownership)->encode('jpg');
							$destinationDB = "uploads/certifiedbuilder/".str_random(15) . '_min_' .".jpg";
							$img->save($destinationDB,45);
							$attachmentType = "image/jpeg";
						}else{
							$uploadAttachments=$attachmentownership->move($destination, $attachmentName);
						}
						//

						$multiAttachmentsownershipchange1["DocumentPath"]=$destinationDB;
						$multiAttachmentsownershipchange1["FileType"]=$attachmentType;
						array_push($multiAttachmentsownershipchange, $multiAttachmentsownershipchange1);
						$countownershipchange++;
					}
				}
			}
		}

		if(Input::hasFile('attachmentsfirmnamechange')){
			$countfirmnamechange = 0;
			$multiAttachmentsFirmNameChange=array();
			foreach(Input::file('attachmentsfirmnamechange') as $attachmentfirmnamechange){
				if((bool)$attachmentfirmnamechange){
					$documentName = Input::get("DocumentNameFirmNameChange");
					$attachmentType=$attachmentfirmnamechange->getMimeType();
					$attachmentFileName=$attachmentfirmnamechange->getClientOriginalName();
					$attachmentName=str_random(6) . '_' . $attachmentfirmnamechange->getClientOriginalName();
					$destination=public_path().'/uploads/certifiedbuilder';
					$destinationDB='uploads/certifiedbuilder/'.$attachmentName;
					$multiAttachmentsownershipchange1["DocumentName"]=$documentName[$countfirmnamechange];

					//CHECK IF IMAGE
					if(strpos($attachmentfirmnamechange->getClientMimeType(),'image/')>-1){
						$img = Image::make($attachmentfirmnamechange)->encode('jpg');
						$destinationDB = "uploads/certifiedbuilder/".str_random(15) . '_min_' .".jpg";
						$img->save($destinationDB,45);
						$attachmentType = "image/jpeg";
					}else{
						$uploadAttachments=$attachmentfirmnamechange->move($destination, $attachmentName);
					}
					//

					$multiAttachmentsownershipchange1["DocumentPath"]=$destinationDB;
					$multiAttachmentsownershipchange1["FileType"]=$attachmentType;
					array_push($multiAttachmentsFirmNameChange, $multiAttachmentsownershipchange1);

					$countfirmnamechange++;
				}

			}
		}

		if(empty($postedValues["Id"])){
			$uuid=DB::select("select uuid() as Id");
			if(Input::has('OldApplicationId')){
				$generatedId = Input::get('OldApplicationId');
			}else{
				$generatedId=$uuid[0]->Id;
			}
			$postedValues["Id"]=$generatedId;
			$postedValues["ReferenceNo"]=$this->tableTransactionNo('CertifiedbuilderModel','ReferenceNo');
			DB::beginTransaction();
			try{
				if(Input::has('ChangeOfLocationOwner')):
					$changeOfOwnerLocation=Input::get('ChangeOfLocationOwner');
					if($changeOfOwnerLocation){
						foreach($changeOfOwnerLocation as $xService):
							if($xService == CONST_SERVICETYPE_CHANGEOWNER){
								$ownerPartnerInputs = Input::get('CertifiedbuilderHumanResourceModel');
								$oldOwnerPartners = DB::table('crpcertifiedbuilderhumanresourcefinal')
									->where('CrpCertifiedbuilderFinalId',Input::get('CrpCertifiedBuilderId'))
									->where(DB::raw('coalesce(IsPartnerOrOwner,0)'),1)
									->get(array('CIDNo','Name','CmnCountryId','CmnDesignationId'));
								foreach($oldOwnerPartners as $oldOwnerPartner):
									$cidNo = $oldOwnerPartner->CIDNo;
									$name = $oldOwnerPartner->Name;
									$cmnCountryId = $oldOwnerPartner->CmnCountryId;
									$cmnDesignationId = $oldOwnerPartner->CmnDesignationId;
									DB::table('crpcertifiedbuilderhrtrack')->insert(array('CIDNo'=>$cidNo,'ReferenceNo'=>$postedValues["ReferenceNo"],'Date'=>date('Y-m-d G:i:s'),'CrpContractorFinalId'=>Input::get('CrpCertifiedBuilderId'),'Name'=>$name,'CmnCountryId'=>$cmnCountryId,'CmnDesignationId'=>$cmnDesignationId));
								endforeach;
							}
						endforeach;
					}
				endif;

				if(Input::has('OldApplicationId')){
					$generatedId = Input::get('OldApplicationId');
					$instanceOfClass = CertifiedbuilderModel::find($generatedId);
					$instanceOfClass->fill($postedValues);
					$instanceOfClass->update();
				}else{
					CertifiedbuilderModel::create($postedValues);
				}
				if(Input::hasFile('attachments')){
					foreach($multiAttachments as $k=>$v){
						if(isset($isEditByCDB) && (int)$isEditByCDB==1 && $newGeneralInfoSave!=null  && (int)$newGeneralInfoSave==0){
							$multiAttachments[$k]["CrpCertifiedbuilderFinalId"]=$generatedId;
							$saveUploads=new CertifiedbuilderAttachmentFinalModel($multiAttachments[$k]);
						}else{
							$multiAttachments[$k]["CrpCertifiedBuilderId"]=$generatedId;
							$saveUploads=new CertifiedbuilderAttachmentModel($multiAttachments[$k]);
						}
						$saveUploads->save();
					}
				}
				if(Input::hasFile('attachmentsownershipchange')){
					foreach($multiAttachmentsownershipchange as $k=>$v){
						if(isset($isEditByCDB) && (int)$isEditByCDB==1 && $newGeneralInfoSave!=null  && (int)$newGeneralInfoSave==0){
							$multiAttachmentsownershipchange[$k]["CrpCertifiedbuilderFinalId"]=$generatedId;
							$saveUploads=new CertifiedbuilderAttachmentFinalModel($multiAttachmentsownershipchange[$k]);
						}else{
							$multiAttachmentsownershipchange[$k]["CrpCertifiedBuilderId"]=$generatedId;
							$saveUploads=new CertifiedbuilderAttachmentModel($multiAttachmentsownershipchange[$k]);
						}
						$saveUploads->save();
					}
				}
			
				if(Input::hasFile('attachmentsfirmnamechange')){
					foreach($multiAttachmentsFirmNameChange as $k=>$v){
						if(isset($isEditByCDB) && (int)$isEditByCDB==1 && $newGeneralInfoSave!=null  && (int)$newGeneralInfoSave==0){
							$multiAttachmentsFirmNameChange[$k]["CrpCertifiedbuilderFinalId"]=$generatedId;
							$saveUploads=new CertifiedbuilderAttachmentFinalModel($multiAttachmentsFirmNameChange[$k]);
						}else{
							$multiAttachmentsFirmNameChange[$k]["CrpCertifiedBuilderId"]=$generatedId;
							$saveUploads=new CertifiedbuilderAttachmentModel($multiAttachmentsFirmNameChange[$k]);
						}
						$saveUploads->save();
					}
				}
				if(Input::has('RenewalService') && (int)$isServiceByCertifiedbuilder==1){
					$lateRenewalExpiryDate=CertifiedbuilderFinalModel::certifiedbuilderHardList($postedValues['CrpCertifiedBuilderId'])->pluck('RegistrationExpiryDate');
					$lateRenewalExpiryDate=strtotime($lateRenewalExpiryDate);
					$currentDate=strtotime(date('Y-m-d'));
					$appliedServiceRenewal = new CertifiedbuilderAppliedServiceModel;
					$appliedServiceRenewal->CrpCertifiedBuilderId=$generatedId;
					$appliedServiceRenewal->CmnServiceTypeId = Input::get('RenewalService');
					$appliedServiceRenewal->save();
					if($currentDate>$lateRenewalExpiryDate){
						$appliedServiceRenewalLateFee = new CertifiedbuilderAppliedServiceModel;
						$appliedServiceRenewalLateFee->CrpCertifiedBuilderId=$generatedId;
						$appliedServiceRenewalLateFee->CmnServiceTypeId = CONST_SERVICETYPE_LATEFEE;
						$appliedServiceRenewalLateFee->save();
					}
				}
				if(Input::has('ChangeOfLocationOwner') && (int)$isServiceByCertifiedbuilder==1){
					$changeOfOwnerLocation=Input::get('ChangeOfLocationOwner');
					for($idx = 0; $idx < count($changeOfOwnerLocation); $idx++){
						$appliedService = new CertifiedbuilderAppliedServiceModel;
						$appliedService->CrpCertifiedBuilderId=$generatedId;
						$appliedService->CmnServiceTypeId = $changeOfOwnerLocation[$idx];
						$appliedService->save();
					}
				}

				if(Input::has('OtherServices') && (int)$isServiceByCertifiedbuilder==1){
					$otherServices=Input::get('OtherServices');
					for($idx = 0; $idx < count($otherServices); $idx++){
						$appliedService = new CertifiedbuilderAppliedServiceModel;
						$appliedService->CrpCertifiedBuilderId=$generatedId;
						$appliedService->CmnServiceTypeId = $otherServices[$idx];
						$appliedService->save();
					}
				}
				foreach($postedValues as $key=>$value){
					if(gettype($value)=='array'){
						foreach($value as $key1=>$value1){
							$value1['CrpCertifiedBuilderId']=$generatedId;
							$childTable= new CertifiedbuilderHumanResourceModel($value1);
							$a=$childTable->save();
						}
					}
				}

			}catch(Exception $e){
				DB::rollback();
				throw $e;
			}
			DB::commit();
			if((int)$isServiceByCertifiedbuilder=1){
		
				if(Input::has('OldApplicationId')){
					$servicesAppliedByCertifiedbuilder = DB::table('crpcertifiedbuilderappliedservice')->where('CrpCertifiedBuilderId',Input::get("OldApplicationId"))->lists('CmnServiceTypeId');
				}else{
					$servicesAppliedByCertifiedbuilder = DB::table('crpcertifiedbuilderappliedservice')->where('CrpCertifiedBuilderId',$generatedId)->lists('CmnServiceTypeId');
				}


				if(in_array(CONST_SERVICETYPE_CHANGEOFCATEGORYCLASSIFICATION,$servicesAppliedByCertifiedbuilder)):
					return Redirect::to('certifiedbuilder/applyserviceworkclassification/'.$generatedId);
				endif;
				if(in_array(CONST_SERVICETYPE_RENEWAL,$servicesAppliedByCertifiedbuilder)):
					return Redirect::to('certifiedbuilder/applyserviceworkclassification/'.$generatedId);
				endif;
				if(in_array(CONST_SERVICETYPE_UPDATEHUMANRESOURCE,$isServiceByCertifiedbuilder)):
					$isEditByCDB=Input::get('EditByCDB');
					$redirectTo=Input::get('PostBackUrl');
					if(isset($isEditByCDB) && (int)$isEditByCDB==1){
						return Redirect::to('certifiedbuilder/applyservicehumanresource'.'/'.$generatedId.'?redirectUrl='.$redirectTo)->with('savedsuccessmessage','Work Classification has been successfully updated.');
					}
				endif;
				if(in_array(CONST_SERVICETYPE_UPDATEEQUIPMENT,$servicesAppliedByCertifiedbuilder)):
					return Redirect::to('certifiedbuilder/applyserviceequipment/'.$generatedId);
				endif;
				return Redirect::to('certifiedbuilder/applyserviceconfirmation/'.$generatedId);
			}else{
				Session::put('CertifiedbuilderRegistrationId',$generatedId);
				return Redirect::to('certifiedbuilder/workclassificationregistration');
			}
		}else{
			$isEditByCDB=Input::get('EditByCDB');
			$redirectTo=Input::get('PostBackUrl');
			$isRejectedApp=Input::get('ApplicationRejectedReapply');
			DB::beginTransaction();
			try{
				if(isset($isEditByCDB) && (int)$isEditByCDB==1 && $newGeneralInfoSave!=null && (int)$newGeneralInfoSave==0){
					$instance=CertifiedbuilderFinalModel::find($postedValues['Id']);
				}else{
					$instance=CertifiedbuilderModel::find($postedValues['Id']);
				}
				$instance->fill($postedValues);
				$instance->update();
				if(Input::hasFile('attachments')){
					foreach($multiAttachments as $k=>$v){
						if(isset($isEditByCDB) && (int)$isEditByCDB==1 && $newGeneralInfoSave!=null  && (int)$newGeneralInfoSave==0){
							$multiAttachments[$k]["CrpCertifiedbuilderFinalId"]=$postedValues['Id'];
							$saveUploads=new CertifiedbuilderAttachmentFinalModel($multiAttachments[$k]);
						}else{
							$multiAttachments[$k]["CrpCertifiedBuilderId"]=$postedValues['Id'];
							$saveUploads=new CertifiedbuilderAttachmentModel($multiAttachments[$k]);
						}
						$saveUploads->save();
					}
				}
				if(Input::hasFile('attachmentsownershipchange')){
					foreach($multiAttachmentsownershipchange as $k=>$v){
						if(isset($isEditByCDB) && (int)$isEditByCDB==1 && $newGeneralInfoSave!=null  && (int)$newGeneralInfoSave==0){
							$multiAttachmentsownershipchange[$k]["CrpCertifiedbuilderFinalId"]=$postedValues['Id'];
							$saveUploads=new CertifiedbuilderAttachmentFinalModel($multiAttachmentsownershipchange[$k]);
						}else{
							$multiAttachmentsownershipchange[$k]["CrpCertifiedBuilderId"]=$postedValues['Id'];
							$saveUploads=new CertifiedbuilderAttachmentModel($multiAttachmentsownershipchange[$k]);
						}
						$saveUploads->save();
					}
				}
				if(Input::hasFile('attachmentsfirmnamechange')){
					foreach($multiAttachmentsFirmNameChange as $k=>$v){
						if(isset($isEditByCDB) && (int)$isEditByCDB==1 && $newGeneralInfoSave!=null  && (int)$newGeneralInfoSave==0){
							$multiAttachmentsFirmNameChange[$k]["CrpCertifiedbuilderFinalId"]=$postedValues['Id'];
							$saveUploads=new CertifiedbuilderAttachmentFinalModel($multiAttachmentsFirmNameChange[$k]);
						}else{
							$multiAttachmentsFirmNameChange[$k]["CrpCertifiedBuilderId"]=$postedValues['Id'];
							$saveUploads=new CertifiedbuilderAttachmentModel($multiAttachmentsFirmNameChange[$k]);
						}
						$saveUploads->save();
					}
				}
				
				foreach($postedValues as $key=>$value){
					if(gettype($value)=='array'){
						foreach($value as $key1=>$value1){
							foreach($value1 as $key2=>$value2){
								$val1=trim($value2);
								if(strlen($val1)==0){
									$value1[$key2]=null;
								}
							}
							if(isset($value1['JoiningDate'])){
								$value1['JoiningDate'] = $this->convertDate($value1['JoiningDate']);
							}
							if(!isset($value1['Id']) && empty($value1['Id'])){

								if(isset($isEditByCDB) && (int)$isEditByCDB==1 && $newGeneralInfoSave!=null && (int)$newGeneralInfoSave==0){
									$value1['CrpCertifiedbuilderFinalId']=$postedValues['Id'];
									$childTable= new CertifiedbuilderHumanResourceFinalModel($value1);
								}else{
									$value1['CrpCertifiedBuilderId']=$postedValues['Id'];
									$childTable= new CertifiedbuilderHumanResourceModel($value1);
								}
								$a=$childTable->save();
							}else{
								if(isset($isEditByCDB) && (int)$isEditByCDB==1 && $newGeneralInfoSave!=null && (int)$newGeneralInfoSave==0){
									//ADDED BY SWM ON 7th June
									if(!isset($value1['ShowInCertificate'])) {
										$value1['ShowInCertificate'] = 0;
									}
									//ADDED BY SWM on 7th June
									$childTable1=CertifiedbuilderHumanResourceFinalModel::find($value1['Id']);
								}else{
									//ADDED BY SWM ON 7th June
									if(!isset($value1['ShowInCertificate'])) {
										$value1['ShowInCertificate'] = 0;
									}
									//ADDED BY SWM on 7th June
									$childTable1=CertifiedbuilderHumanResourceModel::find($value1['Id']);
								}
								$childTable1->fill($value1);
								$childTable1->update();
							}
						}
					}
				}
				DB::commit();
				$isNewRegistration = DB::table('crpcertifiedbuilder')->where('Id',$postedValues['Id'])->pluck('CrpCertifiedBuilderId');
				if(isset($isRejectedApp) && (int)$isRejectedApp==1){
					if(!(bool)$isNewRegistration){
						Session::put('CertifiedbuilderRegistrationId',$postedValues["Id"]);
						return Redirect::to('certifiedbuilder/workclassificationregistration?rejectedapplicationreapply=true');
					}
				}

				if(isset($isEditByCDB) && (int)$isEditByCDB==1){
					if((bool)$redirectTo){
						return Redirect::to($redirectTo.'/'.$postedValues["Id"])->with('savedsuccessmessage','General Information has been successfully updated.');
					}else{
						Session::put('CertifiedbuilderRegistrationId',$postedValues['Id']);
						if(Input::has('OldApplicationId')){
							$currentServicesApplied = array_merge(Input::has('ChangeOfLocationOwner')?Input::get('ChangeOfLocationOwner'):array(),Input::has('OtherServices')?Input::get('OtherServices'):array());
							$servicesAppliedByCertifiedbuilder= DB::table('crpcertifiedbuilderappliedservice')->where('CrpCertifiedBuilderId',$postedValues["Id"])->lists('CmnServiceTypeId');
							foreach($currentServicesApplied as $currentService):
								if(!in_array($currentService,$servicesAppliedByCertifiedbuilder)){
									$appliedService = new certifiedbuilderAppliedServiceModel;
									$appliedService->CrpCertifiedBuilderId=$postedValues["Id"];
									$appliedService->CmnServiceTypeId = $currentService;
									$appliedService->save();
								}
							endforeach;
							if(in_array(CONST_SERVICETYPE_CHANGEOFCATEGORYCLASSIFICATION,$servicesAppliedByCertifiedbuilder)):
								return Redirect::to('certifiedbuilder/applyserviceworkclassification/'.$postedValues["Id"]);
							endif;
							if(in_array(CONST_SERVICETYPE_RENEWAL,$servicesAppliedByCertifiedbuilder)):
								return Redirect::to('certifiedbuilder/applyserviceworkclassification/'.$postedValues["Id"]);
							endif;
							if(in_array(CONST_SERVICETYPE_UPDATEHUMANRESOURCE,$servicesAppliedByCertifiedbuilder)):
								$isEditByCDB=Input::get('EditByCDB');
								$redirectTo=Input::get('PostBackUrl');
								if(isset($isEditByCDB) && (int)$isEditByCDB==1){
									return Redirect::to('certifiedbuilder/applyservicehumanresource'.'/'.$postedValues["Id"].'?redirectUrl='.$redirectTo)->with('savedsuccessmessage','Work Classification has been successfully updated.');
								}
							endif;
							if(in_array(CONST_SERVICETYPE_UPDATEEQUIPMENT,$servicesAppliedByContractor)):
								return Redirect::to('certifiedbuilder/applyserviceequipment/'.$postedValues["Id"]);
							endif;
							return Redirect::to('certifiedbuilder/applyserviceconfirmation/'.$postedValues["Id"]);
						}
						return Redirect::to('certifiedbuilder/applyservicehumanresource/'.$postedValues["Id"])->with('savedsuccessmessage','General Information has been successfully updated.');
					}
				}

				return Redirect::to('certifiedbuilder/confirmregistration')->with('savedsuccessmessage','General Information has been successfully updated.');
			}catch(Exception $e){
				DB::rollback();
				throw $e;
			}
		}
	}


	// Human Resource Edit and new register Begins from here

	
	public function humanResourceRegistrationEdit($certifiedbuilder=null){
		$afterSaveRedirect=1;
		$serviceByCertifiedbuilder=0;
		$newHumanResourceSave=0;
		$isEditByCDBUser=Input::get('usercdb');
		$humanResourceEditRoute='certifiedbuilder/edithumanresource';
		$redirectUrl=Input::get('redirectUrl');
		$editPage='certifiedbuilder/edithumanresource';
		$humanResourceEdit=array(new CertifiedbuilderHumanResourceModel());
		$humanResourceEditAttachments=array();
		$humanResourceId=Input::get('humanresourceid');
		$certifiedbuilderHumanResources = array();
		$humanResourcesAttachments = array();
		$humanResourceEditFinalAttachments=array();
		if(!empty($humanResourceId) && strlen($humanResourceId)==36){
			$humanResourceEditFinalAttachments=CertifiedbuilderHumanResourceAttachmentFinalModel::humanResourceAttachment($humanResourceId)->get(array('Id','DocumentName','DocumentPath'));
			if(!Input::has('usercdb')){
				$humanResourceEdit=CertifiedbuilderHumanResourceModel::certifiedbuilderHumanResourceHardListSingle($humanResourceId)->get(array('Id','Name','CIDNo','Sex','CmnSalutationId','CmnQualificationId','CmnTradeId','CmnDesignationId','JoiningDate', 'CmnCountryId','CmnServiceTypeId'));
				$humanResourceEditAttachments=CertifiedbuilderHumanResourceAttachmentModel::humanResourceAttachment($humanResourceId)->get(array('Id','DocumentName','DocumentPath'));
			}
			if(Input::has('usercdb')){
				$humanResourceEdit=CertifiedbuilderHumanResourceFinalModel::certifiedbuilderHumanResourceHardListSingle($humanResourceId)->get(array('Id','Name','CIDNo','Sex','CmnSalutationId','CmnQualificationId','CmnTradeId','CmnDesignationId','JoiningDate', 'CmnCountryId','CmnServiceTypeId'));
				$humanResourceEditFinalAttachments=CertifiedbuilderHumanResourceAttachmentFinalModel::humanResourceAttachment($humanResourceId)->get(array('Id','DocumentName','DocumentPath'));
				$humanResourceEditAttachments=CertifiedbuilderHumanResourceAttachmentModel::humanResourceAttachment($humanResourceId)->get(array('Id','DocumentName','DocumentPath'));
				if(count($humanResourceEdit) == 0){
					$humanResourceEdit=CertifiedbuilderHumanResourceModel::certifiedbuilderHumanResourceHardListSingle($humanResourceId)->get(array('Id','Name','CIDNo','Sex','CmnSalutationId','CmnQualificationId','CmnTradeId','CmnDesignationId','JoiningDate', 'CmnCountryId','CmnServiceTypeId'));
					$humanResourceEditAttachments=CertifiedbuilderHumanResourceAttachmentModel::humanResourceAttachment($humanResourceId)->get(array('Id','DocumentName','DocumentPath'));
				}
			}
		}
		$salutation=CmnListItemModel::salutation()->get(array('Id','Name'));
		$country=CountryModel::country()->get(array('Id','Nationality','Name'));
		$designation=CmnListItemModel::designation()->get(array('Id','Name'));
		$qualification=CmnListItemModel::qualification()->get(array('Id','Name'));
		$trades=CmnListItemModel::trade()->get(array('Id','Name'));
		$serviceTypes=CmnListItemModel::serviceType()->get(array('Id','Name'));
		$changeModel = false;
		if((bool)$certifiedbuilder!=null && !Input::has('usercdb')){
			$changeModel = false;
			$certifiedbuilderHumanResources=CertifiedbuilderHumanResourceModel::CertifiedbuilderHumanResource($certifiedbuilder)->get(array('crpcertifiedbuilderhumanresource.Id', DB::raw('coalesce(T4.ReferenceNo,0) as DesignationReference'), 'crpcertifiedbuilderhumanresource.Name','crpcertifiedbuilderhumanresource.CIDNo','crpcertifiedbuilderhumanresource.Sex','crpcertifiedbuilderhumanresource.JoiningDate','crpcertifiedbuilderhumanresource.Name','T1.Name as Salutation','T2.Name as Qualification','T3.Name as Trade','T4.Name as Designation','T5.Nationality as Country'));
			$humanResourcesAttachments=CertifiedbuilderHumanResourceModel::humanResourceAttachments($certifiedbuilder)->get(array('T1.Id','T1.CrpCertifiedbuilderHumanResourceId','T1.DocumentName','T1.DocumentPath'));
		}
		if((bool)$certifiedbuilder!=null && Input::has('usercdb')){
			$changeModel = true;
			$certifiedbuilderFinalHumanResources=CertifiedbuilderHumanResourceFinalModel::certifiedbuilderHumanResource($certifiedbuilder)->get(array('crpcertifiedbuilderhumanresourcefinal.Id',DB::raw('coalesce(T4.ReferenceNo,0) as DesignationReference'),'crpcertifiedbuilderhumanresourcefinal.Name','crpcertifiedbuilderhumanresourcefinal.CIDNo','crpcertifiedbuilderhumanresourcefinal.Sex','crpcertifiedbuilderhumanresourcefinal.CmnServiceTypeId','crpcertifiedbuilderhumanresourcefinal.JoiningDate','crpcertifiedbuilderhumanresourcefinal.Name','T1.Name as Salutation','T2.Name as Qualification','T3.Name as Trade','T4.Name as Designation','T5.Nationality as Country'));
			$humanResourcesFinalAttachments=CertifiedbuilderHumanResourceAttachmentFinalModel::singleCertifiedbuilderHumanResourceAllAttachments($certifiedbuilder)->get(array('crpcertifiedbuilderhumanresourceattachmentfinal.DocumentName','crpcertifiedbuilderhumanresourceattachmentfinal.DocumentPath','crpcertifiedbuilderhumanresourceattachmentfinal.CrpCertifiedbuilderHumanResourceFinalId as CrpCertifiedbuilderHumanResourceId'));
			$certifiedbuilderInFinalTable = DB::table('crpcertifiedbuilderfinal')->where('Id',$certifiedbuilder)->count();
			if($certifiedbuilderInFinalTable == 0){
				$certifiedbuilderHumanResources=CertifiedbuilderHumanResourceModel::CertifiedbuilderHumanResource($certifiedbuilder)->get(array('crpcertifiedbuilderhumanresource.Id', DB::raw('coalesce(T4.ReferenceNo,0) as DesignationReference'), 'crpcertifiedbuilderhumanresource.Name','crpcertifiedbuilderhumanresource.CIDNo','crpcertifiedbuilderhumanresource.Sex','crpcertifiedbuilderhumanresource.JoiningDate','crpcertifiedbuilderhumanresource.CmnServiceTypeId','crpcertifiedbuilderhumanresource.Name','T1.Name as Salutation','T2.Name as Qualification','T3.Name as Trade','T4.Name as Designation','T5.Nationality as Country'));
				$humanResourcesAttachments=CertifiedbuilderHumanResourceModel::humanResourceAttachments($certifiedbuilder)->get(array('T1.Id','T1.CrpCertifiedbuilderHumanResourceId','T1.DocumentName','T1.DocumentPath'));
			}else{
				$certifiedbuilderHumanResources=CertifiedbuilderHumanResourceFinalModel::CertifiedbuilderHumanResource($certifiedbuilder)->get(array('crpcertifiedbuilderhumanresourcefinal.Id',DB::raw('coalesce(T4.ReferenceNo,0) as DesignationReference'),'crpcertifiedbuilderhumanresourcefinal.Name','crpcertifiedbuilderhumanresourcefinal.CIDNo','crpcertifiedbuilderhumanresourcefinal.Sex','crpcertifiedbuilderhumanresourcefinal.CmnServiceTypeId','crpcertifiedbuilderhumanresourcefinal.JoiningDate','crpcertifiedbuilderhumanresourcefinal.Name','T1.Name as Salutation','T2.Name as Qualification','T3.Name as Trade','T4.Name as Designation','T5.Nationality as Country'));
				$humanResourcesAttachments=CertifiedbuilderHumanResourceAttachmentFinalModel::singleCertifiedbuilderHumanResourceAllAttachments($certifiedbuilder)->get(array('crpcertifiedbuilderhumanresourceattachmentfinal.DocumentName','crpcertifiedbuilderhumanresourceattachmentfinal.DocumentPath','crpcertifiedbuilderhumanresourceattachmentfinal.CrpCertifiedbuilderHumanResourceFinalId as CrpCertifiedbuilderHumanResourceId'));
			}
		}

		return View::make('crps.certifiedbuilderedithumanresource')
			->with('changeModel',$changeModel)
			->with('serviceTypes',$serviceTypes)
			->with('afterSaveRedirect',$afterSaveRedirect)
			->with('serviceByCertifiedbuilder',$serviceByCertifiedbuilder)
			->with('newHumanResourceSave',$newHumanResourceSave)
			->with('humanResourceEditFinalAttachments',$humanResourceEditFinalAttachments)
			->with('isEditByCDBUser',$isEditByCDBUser)
			->with('humanResourceEditRoute',$humanResourceEditRoute)
			->with('redirectUrl',$redirectUrl)
			->with('isEdit',$certifiedbuilder)
			->with('editPage',$editPage)
			->with('certifiedbuilderId',$certifiedbuilder)
			->with('countries',$country)
			->with('salutations',$salutation)
			->with('qualifications',$qualification)
			->with('designations',$designation)
			->with('trades',$trades)
			->with('certifiedbuilderHumanResources',$certifiedbuilderHumanResources)
			->with('humanResourcesAttachments',$humanResourcesAttachments)
			->with('humanResourceEdit',$humanResourceEdit)
			->with('humanResourceEditAttachments',$humanResourceEditAttachments);

	}
	
	public function saveHumanResource(){
		$save=true;
		$postedValues=Input::all();
		if(isset($postedValues['JoiningDate']))
			$postedValues['JoiningDate'] = $this->convertDate($postedValues['JoiningDate']);
		$hasCDBEdit=Input::get('HasCDBEdit');
		$certifiedbuilderId=Input::get('CrpCertifiedBuilderId');
		$applicationDate = DB::table('crpcertifiedbuilder')->where('Id',$certifiedbuilderId)->pluck('ApplicationDate');
		//if(!empty($postedValues['Id']))
		//	DB::table('crpcontractorhumanresourceattachment')->where('CrpContractorHumanResourceId',$postedValues['Id'])->where('CreatedOn','<',$applicationDate)->delete();
		$isServiceByCertifiedbuilder=Input::get('ServiceByCertifiedbuilder');
		$newHumanResourceSave=Input::get('NewHumanResourceSave');
		$isEditByCDB=Input::get('EditByCDB');
		$redirectTo=Input::get('PostBackUrl');
		$multiAttachments=array();
		$uuid=DB::select("select uuid() as Id");
		$generatedId=$uuid[0]->Id;
		$validation = new CertifiedbuilderHumanResourceModel;
		$redirectToEdit = false;
		if(!($validation->validate($postedValues))){
			$errors = $validation->errors();
			if(empty($postedValues["Id"])){
				return Redirect::to('certifiedbuilder/humanresourceregistration')->withInput()->withErrors($errors);
			}else{
				return Redirect::to('certifiedbuilder/humanresourceregistration/'.$postedValues['CrpCertifiedBuilderId'])->withInput()->withErrors($errors);
			}
		}
		DB::beginTransaction();
		try{
			if(empty($postedValues["Id"])){
				$postedValues["Id"]=$generatedId;
				if(isset($isEditByCDB) && (int)$isEditByCDB==1 && $newHumanResourceSave!=null && (int)$newHumanResourceSave==0){
					$postedValues["CrpCertifiedbuilderFinalId"]=$certifiedbuilderId;
					$instance = CertifiedbuilderFinalModel::find($certifiedbuilderId);
					if(!(bool)$instance){
						CertifiedbuilderHumanResourceModel::create($postedValues);
					}else{
						CertifiedbuilderHumanResourceFinalModel::create($postedValues);
					}

				}else{
					CertifiedbuilderHumanResourceModel::create($postedValues);
				}
				$appliedServiceCount=CertifiedbuilderAppliedServiceModel::where('CrpCertifiedBuilderId',$postedValues['CrpCertifiedBuilderId'])->where('CmnServiceTypeId',CONST_SERVICETYPE_UPDATEHUMANRESOURCE)->count();
				if($appliedServiceCount==0){
					if(Input::has('UpdateHumanResourceService') && (int)$isServiceByCertifiedbuilder==1){
						$appliedService = new CertifiedbuilderAppliedServiceModel;
						$appliedService->CrpCertifiedBuilderId=$certifiedbuilderId;
						$appliedService->CmnServiceTypeId = Input::get('UpdateHumanResourceService');
						$appliedService->save();
					}
				}
			}else{
				$save=false;
				if(isset($isEditByCDB) && (int)$isEditByCDB==1 && $newHumanResourceSave!=null && (int)$newHumanResourceSave==0){
					$instance=CertifiedbuilderHumanResourceFinalModel::find($postedValues['Id']);
					if(!(bool)$instance){
						$redirectToEdit = true;
						$instance=CertifiedbuilderHumanResourceModel::find($postedValues['Id']);
					}
				}else{
					$instance=CertifiedbuilderHumanResourceModel::find($postedValues['Id']);
				}
				if($instance){
					$instance->fill($postedValues);
					$instance->update();
				}else{
					CertifiedbuilderHumanResourceModel::create($postedValues);
				}
			}
			if(Input::hasFile('attachments')){
				$count = 0;
				foreach(Input::file('attachments') as $attachment){
					if($attachment!=NULL){
						$attachmentFileName=$attachment->getClientOriginalName();
						if((bool)$attachmentFileName){
							$documentName = Input::get("DocumentName");
							$attachmentType=$attachment->getMimeType();

							$attachmentName=str_random(6) . '_' . $attachment->getClientOriginalName();
							$destination=public_path().'/uploads/certifiedbuilder';
							$destinationDB='uploads/certifiedbuilder/'.$attachmentName;
							$multiAttachments1["DocumentName"]=isset($documentName[$count])?$documentName[$count]:'Certifiedbuilder Document';

							//CHECK IF IMAGE
							if(strpos($attachment->getClientMimeType(),'image/')>-1){
								$img = Image::make($attachment)->encode('jpg');
								$destinationDB = "uploads/certifiedbuilder/".str_random(15) . '_min_' .".jpg";
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

					}

				}
				foreach($multiAttachments as $k=>$v){
					if(empty($postedValues['Id'])){
						if(isset($isEditByCDB) && (int)$isEditByCDB==1 && $newHumanResourceSave!=null  && (int)$newHumanResourceSave==0){
							$multiAttachments[$k]["CrpCertifiedbuilderHumanResourceFinalId"]=$generatedId;
						}else{
							$multiAttachments[$k]["CrpCertifiedbuilderHumanResourceId"]=$generatedId;
						}
					}else{
						if(isset($isEditByCDB) && (int)$isEditByCDB==1 && $newHumanResourceSave!=null  && (int)$newHumanResourceSave==0){
							$instance = CertifiedbuilderFinalModel::find($certifiedbuilderId);
							if(!(bool)$instance){
								$multiAttachments[$k]["CrpCertifiedbuilderHumanResourceId"]=$postedValues['Id'];
							}else{
								$multiAttachments[$k]["CrpCertifiedbuilderHumanResourceFinalId"]=$postedValues['Id'];
							}
						}else{
							$multiAttachments[$k]["CrpCertifiedbuilderHumanResourceId"]=$postedValues['Id'];
						}
					}

					//END
					if(isset($isEditByCDB) && (int)$isEditByCDB==1 && $newHumanResourceSave!=null && (int)$newHumanResourceSave==0){
						$instance = CertifiedbuilderFinalModel::find($certifiedbuilderId);
						if(!(bool)$instance){
							$saveUploads=new CertifiedbuilderHumanResourceAttachmentModel($multiAttachments[$k]);
						}else{
							$saveUploads=new CertifiedbuilderHumanResourceAttachmentFinalModel($multiAttachments[$k]);
						}

					}else{

						$saveUploads=new CertifiedbuilderHumanResourceAttachmentModel($multiAttachments[$k]);
					}
					$saveUploads->save();
				}
			}
		}catch(Exception $e){
			DB::rollback();
			throw $e;
		}
		DB::commit();
		$humanResourceEditPage=Input::get('EditPage');
		if(isset($isEditByCDB) && (int)$isEditByCDB==1){
			if(!empty($hasCDBEdit)){
				if($redirectToEdit){
					return Redirect::to($humanResourceEditPage.'/'.$postedValues["CrpCertifiedBuilderId"].'?redirectUrl='.$redirectTo)->with('savedsuccessmessage','Human Resource has been successfully updated.');
				}
				return Redirect::to($humanResourceEditPage.'/'.$postedValues["CrpSpecializedtradeId"].'?redirectUrl='.$redirectTo.'&usercdb=edit')->with('savedsuccessmessage','Human Resource has been successfully updated.');
			}else{
				return Redirect::to($humanResourceEditPage.'/'.$postedValues["CrpCertifiedBuilderId"].'?redirectUrl='.$redirectTo)->with('savedsuccessmessage','Human Resource has been successfully updated.');
			}
		}
		if($save){
			return Redirect::to('certifiedbuilder/humanresourceregistration');
		}else{
			return Redirect::to('certifiedbuilder/humanresourceregistration/'.$postedValues['CrpCertifiedBuilderId'])->with('savedsuccessmessage','Human Resource has been successfully updated.');;
		}
	}
	

	//Equipment edit and register begins from here

	public function equipmentRegistrationEdit($certifiedbuilder=null){
		$afterSaveRedirect=1;
		$serviceByCertifiedbuilder=0;
		$newEquipmentSave=0;
		$isEditByCDBUser=Input::get('usercdb');
		$editPage='certifiedbuilder/editequipment';
		$redirectUrl=Input::get('redirectUrl');
		$equipmentEditRoute='certifiedbuilder/editequipment';
		$equipmentEdit=array(new CertifiedbuilderEquipmentModel());
		$equipmentAttachments=array();
		$equipmentId=Input::get('equipmentid');
		if(!empty($equipmentId) && strlen($equipmentId)==36){
			if(!Input::has('usercdb')){
				$equipmentEdit=CertifiedbuilderEquipmentModel::certifiedbuilderEquipmentHardListSingle($equipmentId)->get(array('Id','CmnEquipmentId','RegistrationNo','ModelNo','Quantity'));
				$equipmentAttachments=CertifiedbuilderEquipmentAttachmentModel::equipmentAttachment($equipmentId)->get(array('Id','DocumentName','DocumentPath'));
			}
			if(Input::has('usercdb')){
				$equipmentEdit=CertifiedbuilderEquipmentFinalModel::certifiedbuilderEquipmentHardListSingle($equipmentId)->get(array('Id','CmnEquipmentId','RegistrationNo','ModelNo','Quantity'));
				$equipmentAttachments=CertifiedbuilderEquipmentAtachmentFinalModel::equipmentAttachment($equipmentId)->get(array('Id','DocumentName','DocumentPath'));
			}
		}
		$equipments=CmnEquipmentModel::equipment()->whereNotNull('Code')->get(array('Id','Name','Code','IsRegistered','VehicleType'));
		if((bool)$certifiedbuilder!=null && !Input::has('usercdb')){
			$changeModel = false;
			$certifiedbuilderEquipments=CertifiedbuilderEquipmentModel::certifiedbuilderEquipment($certifiedbuilder)->get(array('crpcertifiedbuilderequipment.Id','crpcertifiedbuilderequipment.RegistrationNo','crpcertifiedbuilderequipment.ModelNo','crpcertifiedbuilderequipment.Quantity','T1.Name'));
			$equipmentsAttachments=CertifiedbuilderEquipmentModel::equipmentAttachments($certifiedbuilder)->get(array('T1.Id','T1.CrpCertifiedBuilderEquipmentId','T1.DocumentName','T1.DocumentPath'));
		}
		if((bool)$certifiedbuilder!=null && Input::has('usercdb')){
			$changeModel = true;
			$certifiedbuilderEquipments=CertifiedbuilderEquipmentFinalModel::certifiedbuilderEquipment($certifiedbuilder)->get(array('crpcertifiedbuilderequipmentfinal.Id','crpcertifiedbuilderequipmentfinal.RegistrationNo','crpcertifiedbuilderequipmentfinal.ModelNo','crpcertifiedbuilderequipmentfinal.Quantity','T1.Name'));
			$equipmentsAttachments=CertifiedbuilderEquipmentAttachmentFinalModel::singleCertifiedbuilderEquipmentAllAttachments($certifiedbuilder)->get(array('crpcertifiedbuilderequipmentattachmentfinal.DocumentName','crpcertifiedbuilderequipmentattachmentfinal.DocumentPath','crpcertifiedbuilderequipmentattachmentfinal.CrpCertifiedBuilderEquipmentFinalId as CrpCertifiedBuilderEquipmentId'));
			$certifiedbuilderInFinalTable = DB::table('crpcertifiedbuilderfinal')->where('Id',$certifiedbuilder)->count();
			if($certifiedbuilderInFinalTable == 0){
				$certifiedbuilderEquipments=CertifiedbuilderEquipmentModel::certifiedbuilderEquipment($certifiedbuilder)->get(array('crpcertifiedbuilderequipment.Id','crpcertifiedbuilderequipment.RegistrationNo','crpcertifiedbuilderequipment.ModelNo','crpcertifiedbuilderequipment.Quantity','T1.Name'));
				$equipmentsAttachments=CertifiedbuilderEquipmentModel::equipmentAttachments($certifiedbuilder)->get(array('T1.Id','T1.CrpCertifiedBuilderEquipmentId','T1.DocumentName','T1.DocumentPath'));
			}
		}
		return View::make('crps.certifiedbuildereditequipment')
			->with('changeModel',$changeModel)
			->with('afterSaveRedirect',$afterSaveRedirect)
			->with('serviceByCertifiedbuilder',$serviceByCertifiedbuilder)
			->with('newEquipmentSave',$newEquipmentSave)
			->with('isEditByCDBUser',$isEditByCDBUser)
			->with('equipmentEditRoute',$equipmentEditRoute)
			->with('redirectUrl',$redirectUrl)
			->with('isEdit',$certifiedbuilder)
			->with('editPage',$editPage)
			->with('certifiedbuilderId',$certifiedbuilder)
			->with('equipments',$equipments)
			->with('certifiedbuilderEquipments',$certifiedbuilderEquipments)
			->with('equipmentsAttachments',$equipmentsAttachments)
			->with('equipmentEdit',$equipmentEdit)
			->with('equipmentAttachments',$equipmentAttachments);
	}


	public function saveEquipment(){
		$save=true;
		$postedValues=Input::all();
		$hasCDBEdit=Input::get('HasCDBEdit');
		$certifiedbuilderId=Input::get('CrpCertifiedBuilderId');
		$applicationDate = DB::table('crpcertifiedbuilder')->where('Id',$certifiedbuilderId)->pluck('ApplicationDate');
		//if(!empty($postedValues['Id']))
		//	DB::table('crpccertifiedbuilderequipmentattachment')->where('CrpCertifiedbuilderEquipmentId',$postedValues['Id'])->where('CreatedOn','<',$applicationDate)->delete();
		$isServiceByCertifiedbuilder=Input::get('ServiceByCertifiedbuilder');
		$newEquipmentSave=Input::get('NewEquipmentSave');
		$isEditByCDB=Input::get('EditByCDB');
		$redirectTo=Input::get('PostBackUrl');
		$multiAttachments=array();
		$uuid=DB::select("select uuid() as Id");
		$generatedId=$uuid[0]->Id;
		$validation = new CertifiedbuilderEquipmentModel;
		$redirectToEdit = false;
		if(!($validation->validate($postedValues))){
			$errors = $validation->errors();
			if(empty($postedValues["Id"])){
				return Redirect::to('certifiedbuilder/equipmentregistration')->withInput()->withErrors($errors);
			}else{
				return Redirect::to('certifiedbuilder/equipmentregistration/'.$postedValues['CrpCertifiedBuilderId'])->withInput()->withErrors($errors);
			}
		}
		DB::beginTransaction();
		try{
			if(empty($postedValues["Id"])){
				$postedValues["Id"]=$generatedId;
				if(isset($isEditByCDB) && (int)$isEditByCDB==1 && $newEquipmentSave!=null && (int)$newEquipmentSave==0){
					$initialTableCount = DB::table('crpcertifiedbuilder')->where('Id',$certifiedbuilderId)->count();
					$finalTableCount = DB::table('crpcertifiedbuilderfinal')->where('Id',$certifiedbuilderId)->count();

					if($initialTableCount==1 && $finalTableCount == 0){
						$postedValues["CrpCertifiedBuilderId"]=$certifiedbuilderId;
						CertifiedbuilderEquipmentModel::create($postedValues);
					}else{
						$postedValues["CrpCertifiedbuilderFinalId"]=$certifiedbuilderId;
						CertifiedbuilderEquipmentFinalModel::create($postedValues);
					}

				}else{
					CertifiedbuilderEquipmentModel::create($postedValues);
				}
				$appliedServiceCount=CertifiedbuilderAppliedServiceModel::where('CrpCertifiedBuilderId',$postedValues['CrpCertifiedBuilderId'])->where('CmnServiceTypeId',CONST_SERVICETYPE_UPDATEEQUIPMENT)->count();
				if($appliedServiceCount==0){
					if(Input::has('UpdateEquipmentService') && (int)$isServiceByCertifiedbuilder==1){
						$appliedService = new CertifiedbuilderAppliedServiceModel;
						$appliedService->CrpCertifiedBuilderId=$certifiedbuilderId;
						$appliedService->CmnServiceTypeId = Input::get('UpdateEquipmentService');
						$appliedService->save();
					}
				}
			}else{
				$save=false;
				if(isset($isEditByCDB) && (int)$isEditByCDB==1 && $newEquipmentSave!=null && (int)$newEquipmentSave==0){
					$instance=CertifiedbuilderEquipmentFinalModel::find($postedValues['Id']);
					if(!(bool)$instance){
						$redirectToEdit = true;
						$instance=CertifiedbuilderEquipmentModel::find($postedValues['Id']);
					}
				}else{
					$instance=CertifiedbuilderEquipmentModel::find($postedValues['Id']);
				}
				if($instance){
					$instance->fill($postedValues);
					$instance->update();
				}else{
					CertifiedbuilderEquipmentModel::create($postedValues);
				}

			}
			if(Input::hasFile('attachments')){
				$count=0;
				foreach(Input::file('attachments') as $attachment){
					$documentName = Input::get("DocumentName");
					if($attachment!=NULL && isset($documentName[$count])){
						$attachmentType=$attachment->getMimeType();
						$attachmentFileName=$attachment->getClientOriginalName();
						$attachmentName=str_random(6) . '_' . $attachment->getClientOriginalName();
						$destination=public_path().'/uploads/certifiedbuilder';
						$destinationDB='uploads/certifiedbuilder/'.$attachmentName;
						$multiAttachments1["DocumentName"]=$documentName[$count];

						//CHECK IF IMAGE
						if(strpos($attachment->getClientMimeType(),'image/')>-1){
							$img = Image::make($attachment)->encode('jpg');
							$destinationDB = "uploads/certifiedbuilder/".str_random(15) . '_min_' .".jpg";
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
				}
				foreach($multiAttachments as $k=>$v){
					if(empty($postedValues['Id'])){
						if(isset($isEditByCDB) && (int)$isEditByCDB==1 && $newEquipmentSave!=null && (int)$newEquipmentSave==0){
							$multiAttachments[$k]["CrpCertifiedbuilderEquipmentFinalId"]=$generatedId;
						}else{
							$multiAttachments[$k]["CrpCertifiedbuilderEquipmentId"]=$generatedId;
						}
					}else{
						if(isset($isEditByCDB) && (int)$isEditByCDB==1 && $newEquipmentSave!=null && (int)$newEquipmentSave==0){
							$multiAttachments[$k]["CrpCertifiedbuilderEquipmentFinalId"]=$postedValues['Id'];
						}else{
							$multiAttachments[$k]["CrpCertifiedbuilderEquipmentId"]=$postedValues['Id'];
						}
					}
					if(isset($isEditByCDB) && (int)$isEditByCDB==1 && $newEquipmentSave!=null && (int)$newEquipmentSave==0){
						$finalTableCount = DB::table('crpcertifiedbuilderfinal')->where('Id',$certifiedbuilderId)->count();
						$initialTableCount = DB::table('crpcertifiedbuilder')->where('Id',$certifiedbuilderId)->count();
						if($initialTableCount==1 && $finalTableCount == 0){
							$multiAttachments[$k]["CrpCertifiedbuilderEquipmentId"]=$postedValues['Id'];
							unset($multiAttachments[$k]["CrpCertifiedbuilderEquipmentFinalId"]);
							$saveUploads=new CertifiedbuilderEquipmentAttachmentModel($multiAttachments[$k]);
						}else{
							$multiAttachments[$k]["CrpCertifiedbuilderEquipmentId"]=$postedValues['Id'];
							$saveUploads=new CertifiedbuilderEquipmentAttachmentFinalModel($multiAttachments[$k]);
						}
					}else{
						$saveUploads=new CertifiedbuilderEquipmentAttachmentModel($multiAttachments[$k]);
					}
					$saveUploads->save();
				}
			}
		}catch(Exception $e){
			DB::rollback();
			throw $e;
		}
		DB::commit();
		$equipmentEditPage=Input::get('EditPage');
		if(isset($isEditByCDB) && (int)$isEditByCDB==1){
			if(!empty($hasCDBEdit)){
				if($redirectToEdit){
					return Redirect::to($equipmentEditPage.'/'.$postedValues["CrpCertifiedBuilderId"].'?redirectUrl='.$redirectTo)->with('savedsuccessmessage','Equipment has been successfully updated.');
				}
				return Redirect::to($equipmentEditPage.'/'.$postedValues["CrpCertifiedBuilderId"].'?redirectUrl='.$redirectTo.'&usercdb=edit')->with('savedsuccessmessage','Equipment has been successfully updated.');
			}else{
				return Redirect::to($equipmentEditPage.'/'.$postedValues["CrpCertifiedBuilderId"].'?redirectUrl='.$redirectTo)->with('savedsuccessmessage','Equipment has been successfully updated.');
			}
		}
		if($save){
			return Redirect::to('certifiedbuilder/equipmentregistration');
		}else{
			return Redirect::to('certifiedbuilder/equipmentregistration/'.$postedValues['CrpCertifiedBuilderId'])->with('savedsuccessmessage','Equipment has been successfully updated.');
		}
	}

	//work classification begins from here

	public function workClassificationRegistration($specializedTrade=null){
		$isRejectedApp=0;
		$isServiceByCertifiedBuilder=0;
		$isEditByCDB=0;
		$newGeneralInfoSave=1;
		$redirectUrl=Input::get('redirectUrl');
		$view="crps.certifiedbuilderregistration";
		$categories=CertifiedBuilderCategoryModel::category()->select(DB::raw('Id as CategoryId,Code,Name,NULL as CmnAppliedClassificationId'))->get();
		$certifiedbuilderRegistration=array(new CertifiedbuilderModel());
		$certifiedbuilderRegistrationAttachments=array();
		$editWorkClassificationsByCDB=array();
		if((bool)$specializedTrade!=null && !Input::has('usercdb')){
			if(Input::has('editbyapplicant')){
				$view="crps.certifiedbuilderregistration";
				if(Input::has('rejectedapplicationreapply')){
					$isRejectedApp=1;
				}
			}else{
				$editWorkClassificationsByCDB=DB::select("select distinct T1.Id as CategoryId,T1.Code,T1.Name,T2.CmnAppliedClassificationId,T3.CmnVerifiedCategoryId,T4.CmnApprovedCategoryId from cmnspecializedtradecategory T1 left join crpcertifiedbuilderworkclassification T2 on T1.Id=T2.CmnAppliedClassificationId and T2.CrpCertifiedBuilderId=? left join crpcertifiedbuilderworkclassification T3 on T1.Id=T3.CmnVerifiedCategoryId and T3.CrpCertifiedBuilderId=? left join crpcertifiedbuilderworkclassification T4 on T1.Id=T4.CmnApprovedCategoryId and T4.CrpCertifiedBuilderId=? where T1.Code like '%CB%' order by T1.Code,T1.Name",array($specializedTrade,$specializedTrade,$specializedTrade));
				$view="crps.certifiedbuildereditregistrationinfo";
			}
			$editWorkClassificationByCDB=array();
			$categories=DB::select("select T1.Id as CategoryId,T1.Code,T1.Name,T2.CmnAppliedClassificationId from cmnspecializedtradecategory T1 left join crpcertifiedbuilderworkclassification T2 on T1.Id=T2.CmnAppliedClassificationId and T2.CrpCertifiedBuilderId=? order by T1.Code,T1.Name",array($specializedTrade));
			$certifiedbuilderRegistration=CertifiedbuilderModel::certifiedbuilderHardList($specializedTrade)->get();
			$certifiedbuilderRegistrationAttachments=CertifiedBuilderAttachmentModel::attachment($specializedTrade)->get(array('Id','DocumentName','DocumentPath'));
		}
		if((bool)$specializedTrade!=null && Input::has('usercdb')){
			$isEditByCDB=1;
			$newGeneralInfoSave=0;
			$view="crps.certifiedbuildereditregistrationinfo";
			$certifiedbuilderRegistration=CertifiedbuilderFinalModel::certifiedbuilderHardList($specializedTrade)->get();
			$certifiedbuilderRegistrationAttachments=CertifiedBuilderAttachmentFinalModel::attachment($specializedTrade)->get(array('Id','DocumentName','DocumentPath'));
			$editWorkClassificationsByCDB=DB::select("select distinct T1.Id as CategoryId,T1.Code,T1.Name,T2.CmnAppliedClassificationId,T3.CmnVerifiedCategoryId,T4.CmnApprovedCategoryId from cmnspecializedtradecategory T1 left join crpcertifiedbuilderworkclassificationfinal T2 on T1.Id=T2.CmnAppliedClassificationId and T2.CrpCertifiedBuilderFinalId=? left join crpcertifiedbuilderworkclassificationfinal T3 on T1.Id=T3.CmnVerifiedCategoryId and T3.CrpCertifiedBuilderFinalId=? left join crpcertifiedbuilderworkclassificationfinal T4 on T1.Id=T4.CmnApprovedCategoryId and T4.CrpCertifiedBuilderFinalId=? where T1.Code like '%CB%' order by T1.Code,T1.Name",array($specializedTrade,$specializedTrade,$specializedTrade));
		}
		$applicationReferenceNo=$this->tableTransactionNo('CertifiedbuilderModel','ReferenceNo');
		$country=CountryModel::country()->get(array('Id','Name'));
		$dzongkhag=DzongkhagModel::dzongkhag()->get(array('Id','NameEn'));
		$salutation=CmnListItemModel::salutation()->get(array('Id','Name'));
		$qualification=CmnListItemModel::qualification()->get(array('Id','Name'));
		return View::make($view)
					->with('isRejectedApp',$isRejectedApp)
					->with('isEditByCDB',$isEditByCDB)
					->with('newGeneralInfoSave',$newGeneralInfoSave)
					->with('isServiceByCertifiedBuilder',$isServiceByCertifiedBuilder)
					->with('redirectUrl',$redirectUrl)
					->with('specializedTradeId',$specializedTrade)
					->with('applicationReferenceNo',$applicationReferenceNo)
					->with('dzongkhags',$dzongkhag)
					->with('salutations',$salutation)
					->with('qualifications',$qualification)
					->with('certifiedbuilderRegistrations',$certifiedbuilderRegistration)
					->with('certifiedbuilderRegistrationAttachments',$certifiedbuilderRegistrationAttachments)
					->with('categories',$categories)
					->with('editWorkClassificationsByCDB',$editWorkClassificationsByCDB);
	}
	public function saveWorkClassification(){
		$save=true;
		$postedValues=Input::all();
		$isRejectedApp=Input::get('ApplicationRejectedReapply');
		$newGeneralInfoSave=Input::get('NewGeneralInfoSave');
		$serviceByCertifiedBuilder=Input::get('IsServiceByCertifiedBuilder');
		$isEditByCdb=Input::get('EditByCdb');
		$redirectTo=Input::get('PostBackUrl');
		$specializedTradeId=Input::get('CrpCertifiedBuilderId');
		$multiAttachments=array();
		$uuid=DB::select("select uuid() as Id");
        $generatedId=$uuid[0]->Id;
	
		DB::beginTransaction();
		try{
			if(empty($postedValues["Id"])){
				$postedValues["Id"]=$generatedId;
				CertifiedBuilderModel::create($postedValues);
				if((int)$serviceByCertifiedBuilder==1){
					$appliedServiceRenewal = new CertifiedbuilderAppliedServiceModel;
	        		$appliedServiceRenewal->CrpCertifiedBuilderId=$generatedId;
				    $appliedServiceRenewal->CmnServiceTypeId = CONST_SERVICETYPE_RENEWAL;
				    $appliedServiceRenewal->save();
				    $countRenewalApplications=CertifiedbuilderAppliedServiceModel::serviceRenewalCount($generatedId)->count();
				    if($countRenewalApplications>=1){
				    	$lateRenewalExpiryDate=CertifiedbuilderFinalModel::specializedTradeHardList($postedValues['CrpCertifiedBuilderId'])->pluck('RegistrationExpiryDate');
					    $lateRenewalExpiryDate=strtotime($lateRenewalExpiryDate);
					    $currentDate=strtotime(date('Y-m-d'));
					    if($currentDate>$lateRenewalExpiryDate){
					    	$appliedServiceRenewalLateFee = new CertifiedbuilderAppliedServiceModel;
					    	$appliedServiceRenewalLateFee->CrpCertifiedBuilderId=$generatedId;
					    	$appliedServiceRenewalLateFee->CmnServiceTypeId = CONST_SERVICETYPE_LATEFEE;
					    	$appliedServiceRenewalLateFee->save();
					    }
					}
				}
			}else{
				$save=false;
				$generatedId=$postedValues['Id'];
				if(isset($isEditByCdb) && (int)$isEditByCdb==1 && $newGeneralInfoSave!=null && (int)$newGeneralInfoSave==0){
					$specializedTradeReference= new CertifiedbuilderFinalModel();
					CertifiedbuilderWorkClassificationFinalModel::where('CrpCertifiedBuilderFinalId',$postedValues['Id'])->delete();
				}else{
					$specializedTradeReference= new CertifiedbuilderModel();
					CertifiedbuilderWorkClassificationModel::where('CrpCertifiedBuilderId',$postedValues['Id'])->delete();
				}
				$instance=$specializedTradeReference::find($postedValues['Id']);
				$instance->fill($postedValues);
				$instance->update();
			}
			/*------------------------------Saving Work Classification--------------------------------------*/
			if(Input::has('CmnEditCategoryByCDB')){
				$appliedCategory=Input::get('CmnAppliedClassificationId');
				for($idx = 0; $idx < count($appliedCategory); $idx++){
					if(isset($isEditByCdb) && (int)$isEditByCdb==1 && $newGeneralInfoSave!=null && (int)$newGeneralInfoSave==0){
						$category = new CertifiedbuilderWorkClassificationFinalModel;
						$category->CrpCertifiedBuilderFinalId=$generatedId;
					}else{
						$category = new CertifiedbuilderWorkClassificationModel;
						$category->CrpCertifiedBuilderId=$generatedId;
					}
				    $category->CmnAppliedClassificationId = $postedValues['CmnAppliedClassificationId'][$idx];
				    $category->CmnVerifiedCategoryId = $postedValues['CmnVerifiedCategoryId'][$idx];
				    $category->CmnApprovedCategoryId = $postedValues['CmnApprovedCategoryId'][$idx];
			    	$category->save();
				}
			}else{
				$appliedCategory=Input::get('CmnAppliedClassificationId');
				for($idx = 0; $idx < count($appliedCategory); $idx++){
				    if(isset($isEditByCdb) && (int)$isEditByCdb==1 && $newGeneralInfoSave!=null && (int)$newGeneralInfoSave==0){
						$category = new CertifiedbuilderWorkClassificationFinalModel;
						$category->CrpCertifiedBuilderFinalId=$generatedId;
					}else{
						$category = new CertifiedbuilderWorkClassificationModel;
						$category->CrpCertifiedBuilderId=$generatedId;
					}
				    $category->CmnAppliedClassificationId = $postedValues['CmnAppliedClassificationId'][$idx];
				    $category->save();
				}
			}
			/*---------------------------End of saving work classification---------------------------------*/
			
		}catch(Exception $e){
			DB::rollback();
        	throw $e;
		}
		DB::commit();
		if(isset($isEditByCdb) && (int)$isEditByCdb==1){
			if((int)$serviceByCertifiedBuilder==1){
				return Redirect::to('certifiedbuilder/applyrenewalconfirmation/'.$postedValues["Id"]);			
			}
			return Redirect::to($redirectTo.'/'.$postedValues["Id"])->with('savedsuccessmessage','Registration Information has been successfully updated.');
		}
		if($save){
			Session::put('CertifiedBuilderRegistrationId',$generatedId);
			return Redirect::to('certifiedbuilder/confirmregistration');
		}else{
			if(isset($isRejectedApp) && (int)$isRejectedApp==1){
				Session::put('CertifiedBuilderRegistrationId',$postedValues["Id"]);
				return Redirect::to('certifiedbuilder/confirmregistration');
			}
			return Redirect::to('certifiedbuilder/confirmregistration')->with('savedsuccessmessage','Registration Information has been successfully updated.');
		}
	}
	

	//comments and adverse begins from here

	public function newCommentAdverseRecord($certifiedbuilderId){
		$certifiedbuilder=CertifiedbuilderFinalModel::certifiedbuilderHardList($certifiedbuilderId)->get(array('Id','CDBNo','NameOfFirm'));
		return View::make('crps.certifiedbuildernewadverserecordsandcomments')
					->with('certifiedbuilderId',$certifiedbuilderId)
					->with('certifiedbuilder',$certifiedbuilder);	
	}
	public function editCommentAdverseRecord($certifiedbuilderId){
		$certifiedbuilder=CertifiedbuilderFinalModel::certifiedbuilderHardList($certifiedbuilderId)->get(array('Id','CDBNo','NameOfFirm'));
		$commentsAdverseRecords=CertifiedbuilderCommentAdverseRecordModel::commentAdverseRecordList($certifiedbuilderId)->get(array('Id','Date','Remarks','Type'));
		return View::make('crps.certifiedbuildereditadverserecordscomments')
					->with('certifiedbuilder',$certifiedbuilder)
					->with('commentsAdverseRecords',$commentsAdverseRecords);
	}
	public function saveCommentAdverseRecord(){
		$postedValues=Input::all();
		$date=$this->convertDate(Input::get('Date'));
		$postedValues['Date']=$date;
		$postedValues['CreatedBy'] = Auth::user()->Id;
		$validation = new CertifiedbuilderCommentAdverseRecordModel;
		if(!($validation->validate($postedValues))){
		    $errors = $validation->errors();
		    return Redirect::to('certifiedbuilder/editdetails/'.$postedValues['CrpCertifiedbuilderFinalId'].'#commentsadverserecords')->withErrors($errors)->withInput();
		}
		CertifiedbuilderCommentAdverseRecordModel::create($postedValues);
		return Redirect::to('certifiedbuilder/editdetails/'.$postedValues['CrpCertifiedbuilderFinalId'].'#commentsadverserecords')->with('savedsuccessmessage','Record sucessfully added.');
	}
	public function updateCommentAdverseRecord(){
		$postedValues=Input::all();
		$date=$this->convertDate(Input::get('Date'));
		$postedValues['Date']=$date;
		$instance=CertifiedbuilderCommentAdverseRecordModel::find($postedValues['Id']);
		$instance->fill($postedValues);
		$instance->update();
		return Redirect::to('certifiedbuilder/editdetails/'.$postedValues['CrpCertifiedbuilderFinalId'].'#commentsadverserecords')->with('savedsuccessmessage','Record has been successfully updated');
	}

	//editing bidding begins from here

	public function listOfWorks(){
		$parameters=array();
		$underProcess=0;
		$procuringAgencyId=Input::get('ProcuringAgency');
		$workStartDateFrom=Input::get('WorkStartDateFrom');
		$workStartDateTo=Input::get('WorkStartDateTo');
		$workOrderNo=Input::get('WorkOrderNo');
		$workStatus=Input::get('WorkExecutionStatus');
		$cdbNo=Input::get('CDBNo');

		$query="select T1.Id,T1.NameOfClient,T1.NameOfWork,B.CDBNo,B.NameOfFirm,T1.WorkOrderNo,T1.ContractPeriod,T1.WorkStartDate,
		T1.WorkCompletionDate,T2.Name as ProcuringAgency,T5.Name as WorkExecutionStatus from cbbiddingform 
		T1 left join (cbbiddingformdetail A join crpcertifiedbuilderfinal B on B.Id = A.CrpCertifiedBuilderFinalId) on 
		A.CrpBiddingFormId = T1.Id  left join cmnprocuringagency T2 on T1.CmnProcuringAgencyId=T2.Id left join cmnlistitem T5 on T1.CmnWorkExecutionStatusId=T5.Id where Type=2";
		

		// array_push($parameters,CONST_CMN_WORKEXECUTIONSTATUS_AWARDED);
		if(Request::path()=="certifiedbuilder/editcompletedworklist"){
			$query.=" and (T1.CmnWorkExecutionStatusId=? or T1.CmnWorkExecutionStatusId=?)";
			array_push($parameters,CONST_CMN_WORKEXECUTIONSTATUS_COMPLETED);
			array_push($parameters,CONST_CMN_WORKEXECUTIONSTATUS_TERMINATED);
		}elseif(Request::path()=="certifiedbuilder/worklist" || Request::path()=="certifiedbuilder/editbiddingformlist"){
			$underProcess=1;
			$query.=" and (T1.CmnWorkExecutionStatusId=? or T1.CmnWorkExecutionStatusId = ?)";
			array_push($parameters,CONST_CMN_WORKEXECUTIONSTATUS_UNDERPROCESS);
			array_push($parameters,CONST_CMN_WORKEXECUTIONSTATUS_AWARDED);
		}else{
			App::abort('404');
		}

		// $queryForDistinctYears = "select distinct case when T1.migratedworkid is null then year(T1.UploadedDate) else SUBSTRING_INDEX(SUBSTRING_INDEX(T1.migratedworkid,'/',-2),'/',1) end as Year from etltender T1 join (cmnprocuringagency A join sysuser B on A.Id = B.CmnProcuringAgencyId) on A.Id = T1.CmnProcuringAgencyId where T1.CmnProcuringAgencyId = ? and T1.TenderSource = 1 and coalesce(T1.DeleteStatus,'N') <> 'Y'";

		if((bool)$procuringAgencyId!=NULL || (bool)$workOrderNo!=NULL || (bool)$cdbNo!=NULL || (bool)$workStartDateFrom!=NULL || (bool)$workStartDateTo!=NULL || (bool)$workStatus!=NULL){
			if((bool)$procuringAgencyId!=NULL){
				$query.=" and T1.CmnProcuringAgencyId=?";
				array_push($parameters,$procuringAgencyId);
			}
			if((bool)$workOrderNo!=NULL){
				$query.=" and T1.WorkOrderNo=?";
				array_push($parameters,$workOrderNo);
			}
			if((bool)$workStartDateFrom!=NULL){
				$workStartDateFrom=$this->convertDate($workStartDateFrom);
				$query.=" and T1.WorkStartDate>=?";
				array_push($parameters,$workStartDateFrom);
			}
			if((bool)$workStartDateTo!=NULL){
				$workStartDateTo=$this->convertDate($workStartDateTo);
				$query.=" and T1.WorkStartDate<=?";
				array_push($parameters,$workStartDateTo);
			}
			if((bool)$workStatus!=NULL){
				$query.=" and T1.CmnWorkExecutionStatusId=?";
				array_push($parameters,$workStatus);
			}
			if((bool)$cdbNo){
				$query.=" and B.CDBNo = ?";
				array_push($parameters,$cdbNo);
			}
		}
		$listOfWorks=DB::select($query." order by ProcuringAgency,T1.CreatedOn desc",$parameters);
		$procuringAgencies=ProcuringAgencyModel::procuringAgencyHardList()->get(array('Id','Name'));
		$workExecutionStatus=CmnListItemModel::workExecutionStatus()->whereRaw('(ReferenceNo=? or ReferenceNo=?)',array(3003,3004))->get(array('Id','Name'));
		return View::make('crps.certifiedbuilderlistofworks')
			->with('underProcess',$underProcess)
			->with('workExecutionStatus',$workExecutionStatus)
			->with('procuringAgencyId',$procuringAgencyId)
			->with('workStartDateFrom',$workStartDateFrom)
			->with('workStartDateTo',$workStartDateTo)
			->with('workStatus',$workStatus)
			->with('workOrderNo',$workOrderNo)
			->with('procuringAgencies',$procuringAgencies)
			->with('listOfWorks',$listOfWorks);
	}

		
	public function workCompletionForm($bidId){
		
		$model="all/cbworkcompletionform";
		$workExecutionStatus=CmnListItemModel::workExecutionStatus()->whereRaw('(ReferenceNo=? or ReferenceNo=? or ReferenceNo=?)',array(3003,3004,3005))->get(array('Id','Name','ReferenceNo'));

		$detailsOfCompletedWorks=CBBiddingformModel::workCompletionDetails($bidId)
								->get(array('ContractPriceInitial','ContractPriceFinal','CommencementDateOffcial','CommencementDateFinal','CompletionDateOffcial','CompletionDateFinal','OntimeCompletionScore','QualityOfExecutionScore','CmnWorkExecutionStatusId','Remarks'));
								
								$redirectRoute='certifiedbuilder/worklist';
		if(!empty($detailsOfCompletedWorks[0]->OntimeCompletionScore)){
			$redirectRoute='certifiedbuilder/editcompletedworklist';
		}else{
			$detailsOfCompletedWorks=DB::select('select T1.WorkStartDate as CommencementDateOffcial,NULL as CommencementDateFinal,T1.WorkCompletionDate as CompletionDateOffcial,NULL as CompletionDateFinal,T2.EvaluatedAmount as ContractPriceInitial,Null as ContractPriceFinal,NULL as OntimeCompletionScore,NULL as QualityOfExecutionScore,T1.CmnWorkExecutionStatusId,NULL as Remarks from cbbiddingform T1 join cbbiddingformdetail T2 on T1.Id=T2.CrpBiddingFormId where T1.Id=? Limit 1',array($bidId));
		}
		$contractDetails=CBBiddingformModel::biddingFormCertifiedBuilderCdbAll()
								->where('cbbiddingform.Id',$bidId)
								->where('cbbiddingform.ByCDB',2)
								->get(array('cbbiddingform.Id','cbbiddingform.NameOfClient','cbbiddingform.WorkId','cbbiddingform.NameOfWork','cbbiddingform.DescriptionOfWork','cbbiddingform.ContractPeriod','cbbiddingform.WorkStartDate','cbbiddingform.WorkCompletionDate','cbbiddingform.ApprovedAgencyEstimate','T1.Name as ProcuringAgency','T2.Name as ProjectCategory','T4.NameEn as Dzongkhag'));
								
		
		$workAwardedContractor=CBBiddingFormDetailModel::biddingFormCertifiedBuilderContractBidders($bidId)
								->where('cbbiddingformdetail.CmnWorkExecutionStatusId',CONST_CMN_WORKEXECUTIONSTATUS_AWARDED)
								->get(array('cbbiddingformdetail.Id','cbbiddingformdetail.BidAmount','cbbiddingformdetail.EvaluatedAmount','T1.CDBNo','T1.NameOfFirm'));
			return View::make('crps.certifiedbuilderworkcompletionform')
			->with('model',$model)
			->with('redirectRoute',$redirectRoute)
			->with('detailsOfCompletedWorks',$detailsOfCompletedWorks)
			->with('workExecutionStatus',$workExecutionStatus)
			->with('contractDetails',$contractDetails)
			->with('workAwardedContractor',$workAwardedContractor);
	}




	// 	$model="all/cbworkcompletionform";
	// 	$workExecutionStatus=CmnListItemModel::workExecutionStatus()->whereRaw('(ReferenceNo=? or ReferenceNo=? or ReferenceNo=?)',array(3003,3004,3005))->get(array('Id','Name','ReferenceNo'));
	// 	$detailsOfCompletedWorks=CBBiddingformModel::workCompletionDetails($bidId)
	// 		                                        ->get(array('ContractPriceInitial','ContractPriceFinal','CommencementDateOffcial','CommencementDateFinal','CompletionDateOffcial','CompletionDateFinal','OntimeCompletionScore','QualityOfExecutionScore','CmnWorkExecutionStatusId','Remarks'));
	// 	$redirectRoute='certifiedbuilder/worklist';

	// 	if(!empty($detailsOfCompletedWorks[0]->OntimeCompletionScore)){
	// 		$redirectRoute='cb/editcompletedworklist';
	// 	}else{
	// 		$detailsOfCompletedWorks=DB::select('select T1.WorkStartDate as CommencementDateOffcial,NULL as CommencementDateFinal,T1.WorkCompletionDate as CompletionDateOffcial,NULL as CompletionDateFinal,T2.EvaluatedAmount as ContractPriceInitial,Null as ContractPriceFinal,NULL as OntimeCompletionScore,NULL as QualityOfExecutionScore,T1.CmnWorkExecutionStatusId,NULL as Remarks from cbbiddingform T1 join cbbiddingformdetail T2 on T1.Id=T2.CrpBiddingFormId where T1.Id=? Limit 1',array($bidId));
	// 	}
	// $contractDetails=CBBiddingformModel::biddingFormContractorCdbAll()
	// 	->where('cbbiddingform.Id',$bidId)
	// 	->where('cbbiddingform.ByCDB',0)
	// 	->get(array('cbbiddingform.Id','cbbiddingform.WorkOrderNo','cbbiddingform.NameOfWork','cbbiddingform.DescriptionOfWork','cbbiddingform.ContractPeriod','cbbiddingform.WorkStartDate','cbbiddingform.WorkCompletionDate','cbbiddingform.ApprovedAgencyEstimate','T1.Name as ProcuringAgency','T2.Name as ProjectCategory','T3.Name as ContractorClass','T4.NameEn as Dzongkhag'));

	// $workAwardedCertifiedbuilder=CBBiddingFormDetailModel::biddingFormCertifiedBuilderContractBidders($bidId)
	// ->where('cbbiddingformdetail.CmnWorkExecutionStatusId',CONST_CMN_WORKEXECUTIONSTATUS_AWARDED)
	// ->get(array('cbbiddingformdetail.Id','cbbiddingformdetail.BidAmount','cbbiddingformdetail.EvaluatedAmount','T1.CDBNo','T1.NameOfFirm'));

	//     return View::make('crps.certifiedbuilderworkcompletionform')
	// 		->with('model',$model)
	// 		->with('redirectRoute',$redirectRoute)
	// 		->with('detailsOfCompletedWorks',$detailsOfCompletedWorks)
	// 		->with('workExecutionStatus',$workExecutionStatus)
	// 		->with('contractDetails',$contractDetails)
	// 		->with('workAwardedCertifiedbuilder',$workAwardedCertifiedbuilder);
	// }

}



