<?php
class Specializedfirm extends CrpsController{

	public function specializedfirmList(){
		$parameters=array();
		$linkText='Edit';
		$link='specializedfirm/editdetails/';
		$specializedtradeId=Input::get('CrpSpecializedTradeId');
		$SPNo=Input::get('SPNo');
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

		
		$query="select T1.Id,T1.RegistrationExpiryDate,T1.ApplicationDate,T1.SPNo,T1.MobileNo,Z.Name as Status,Z.ReferenceNo as StatusReference,T1.Email,T1.NameOfFirm,T4.Name as OwnershipType from crpspecializedfirmfinal T1 left join cmnlistitem Z on Z.Id = T1.CmnApplicationRegistrationStatusId  join cmnlistitem T4 on T1.CmnOwnershipTypeId=T4.Id  where 1";
		//array_push($parameters,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED);
		if(Route::current()->getUri()=="specializedfirm/viewprintlist"){
			$linkText='View/Print';
			$link='specializedfirm/viewprintdetails/';
			}elseif(Route::current()->getUri()=="specializedfirm/newcommentsadverserecordslist"){
			$linkText='Add';
			$link='specializedfirm/newcommentsadverserecords/';
		}elseif(Route::current()->getUri()=="specializedfirm/editcommentsadverserecordslist"){
			$linkText='View';
			$link='specializedfirm/editcommentsadverserecords/';
		}
		if((bool)$specializedtradeId!=NULL || (bool)$SPNo!=NULL || (bool)$registrationStatus!=NULL){
			if((bool)$specializedtradeId!=NULL){
				$query.=" and T1.Id=?";
				array_push($parameters,$specializedtradeId);
			}
			if((bool)$SPNo!=NULL){
				$query.=" and T1.SPNo=?";
	            array_push($parameters,$SPNo);
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
	$specializedfirmlists=DB::select($query." order by SPNo,NameOfFirm".$limit,$parameters);
	return View::make('crps.specializedfirmlist')
				->with('pageTitle','List of Specialized Firm')
				->with('link',$link)
				->with('linkText',$linkText)
				->with('SPNo',$SPNo)
				->with('registrationStatus',$registrationStatus)
				->with('specializedtradeId',$specializedtradeId)
				->with('specializedfirmlists',$specializedfirmlists);
}

public function printDetails($specializedtradeId){
	$isFinalPrint=0;
	if(Route::current()->getUri()=="specializedfirm/viewprintdetails/{specializedtradeid}"){
		$isFinalPrint=1;
		$generalInformation=SpecializedfirmFinalModel::specializedtrade($specializedtradeId)->get(array('crpspecializedfirmfinal.Id','crpspecializedfirmfinal.ReferenceNo','crpspecializedfirmfinal.RegistrationExpiryDate','crpspecializedfirmfinal.SPNo','crpspecializedfirmfinal.TPN','crpspecializedfirmfinal.Gewog','crpspecializedfirmfinal.RegisteredAddress','crpspecializedfirmfinal.Village','crpspecializedfirmfinal.ApplicationDate','crpspecializedfirmfinal.NameOfFirm', 'crpspecializedfirmfinal.TradeLicenseNo','crpspecializedfirmfinal.Address','crpspecializedfirmfinal.Email','crpspecializedfirmfinal.TelephoneNo','crpspecializedfirmfinal.MobileNo','crpspecializedfirmfinal.FaxNo','crpspecializedfirmfinal.CmnApplicationRegistrationStatusId','T8.Name as Country','T4.Name as OwnershipType','T2.NameEn as Dzongkhag','T5.NameEn as RegisteredDzongkhag'));
		$specializedTradeInformations=SpecializedfirmFinalModel::specializedtrade($specializedtradeId)->get(array('crpspecializedfirmfinal.Id','crpspecializedfirmfinal.ReferenceNo','crpspecializedfirmfinal.ApplicationDate','crpspecializedfirmfinal.SPNo','crpspecializedfirmfinal.CIDNo','crpspecializedfirmfinal.Gewog','crpspecializedfirmfinal.Village','crpspecializedfirmfinal.Email','crpspecializedfirmfinal.MobileNo', 'crpspecializedfirmfinal.TelephoneNo','T2.NameEn as Dzongkhag','T3.Name as CurrentStatus'));
		$ownerPartnerDetails=SpecializedfirmHumanResourceFinalModel::specializedtradePartner($specializedtradeId)->get(array('crpspecializedtradehumanresourcefinal.CIDNo','crpspecializedtradehumanresourcefinal.Name','crpspecializedtradehumanresourcefinal.Sex','crpspecializedtradehumanresourcefinal.JoiningDate','crpspecializedtradehumanresourcefinal.ShowInCertificate','T1.Name as Country','T2.Name as Salutation','T3.Name as Designation'));
		$specializedtradeHumanResources=SpecializedfirmHumanResourceFinalModel::specializedtradeHumanResource($specializedtradeId)->get(array('crpspecializedtradehumanresourcefinal.Name','crpspecializedtradehumanresourcefinal.CIDNo','crpspecializedtradehumanresourcefinal.Sex','crpspecializedtradehumanresourcefinal.JoiningDate','crpspecializedtradehumanresourcefinal.Name','T2.Name as Qualification','T3.Name as Trade','T4.Name as Designation','T5.Name as Country'));
		$specializedtradeEquipments=SpecializedfirmEquipmentFinalModel::specializedtradeEquipment($specializedtradeId)->get(array('crpspecializedtradeequipmentfinal.RegistrationNo','crpspecializedtradeequipmentfinal.ModelNo','crpspecializedtradeequipmentfinal.Quantity','T1.Name'));
		$workClasssifications=DB::select("select distinct T1.Code,T1.Name,T2.CmnAppliedCategoryId,T2.CmnVerifiedCategoryId,T2.CmnApprovedCategoryId from cmnspecializedtradecategory T1 left join crpspecializedfirmworkclassificationfinal T2 on T1.Id=T2.CmnApprovedCategoryId and T2.CrpSpecializedTradeFinalId=?  where T1.Code like '%SF%' order by T1.Code,T1.Name",array($specializedtradeId));
	}else{
		$generalInformation=SpecializedfirmFinalModel::specializedtrade($specializedtradeId)->get(array('crpspecializedfirmfinal.Id','crpspecializedfirmfinal.ReferenceNo','crpspecializedfirmfinal.RegistrationExpiryDate','crpspecializedfirmfinal.SPNo','crpspecializedfirmfinal.TPN','crpspecializedfirmfinal.Gewog','crpspecializedfirmfinal.RegisteredAddress','crpspecializedfirmfinal.Village','crpspecializedfirmfinal.ApplicationDate','crpspecializedfirmfinal.NameOfFirm', 'crpspecializedfirmfinal.TradeLicenseNo','crpspecializedfirmfinal.Address','crpspecializedfirmfinal.Email','crpspecializedfirmfinal.TelephoneNo','crpspecializedfirmfinal.MobileNo','crpspecializedfirmfinal.FaxNo','crpspecializedfirmfinal.CmnApplicationRegistrationStatusId','T8.Name as Country','T4.Name as OwnershipType','T2.NameEn as Dzongkhag','T5.NameEn as RegisteredDzongkhag'));
		$specializedTradeInformations=SpecializedfirmModel::specializedtrade($specializedtradeId)->get(array('crpspecializedfirm.Id','crpspecializedfirm.ReferenceNo','crpspecializedfirm.ApplicationDate','crpspecializedfirm.SPNo','crpspecializedfirm.CIDNo','crpspecializedfirm.Name','crpspecializedfirm.Gewog','crpspecializedfirm.Village','crpspecializedfirm.Email','crpspecializedfirm.MobileNo','crpspecializedfirm.EmployerName','crpspecializedfirm.EmployerAddress','crpspecializedfirm.TelephoneNo','crpspecializedfirm.RemarksByVerifier','T1.Name as Salutation','T2.NameEn as Dzongkhag','T3.Name as CurrentStatus'));
		$ownerPartnerDetails=SpecializedfirmHumanResourceModel::specializedtradePartner($specializedtradeId)->get(array('crpspecializedtradehumanresource.CIDNo','crpspecializedtradehumanresource.Name','crpspecializedtradehumanresource.Sex','crpspecializedtradehumanresource.ShowInCertificate','T1.Name as Country','T2.Name as Salutation','T3.Name as Designation'));
		$specializedtradeHumanResources=SpecializedfirmHumanResourceModel::specializedtradeHumanResource($specializedtradeId)->get(array('crpspecializedtradehumanresource.Name','crpspecializedtradehumanresource.CIDNo','crpspecializedtradehumanresource.JoiningDate','crpspecializedtradehumanresource.Sex','crpspecializedtradehumanresource.Name','crpspecializedtradehumanresource.Verified','crpspecializedtradehumanresource.Approved','T1.Name as Salutation','T2.Name as Qualification','T3.Name as Trade','T4.Name as Designation','T5.Name as Country'));
		$specializedtradeEquipments=SpecializedfirmEquipmentModel::specializedtradeEquipment($specializedtradeId)->get(array('crpspecializedtradeequipment.RegistrationNo','crpspecializedtradeequipment.ModelNo','crpspecializedtradeequipment.Quantity','crpspecializedtradeequipment.Verified','crpspecializedtradeequipment.Approved','T1.Name'));
		$workClasssifications=DB::select("select distinct T1.Code,T1.Name,T2.CmnAppliedCategoryId from cmnspecializedtradecategory T1 left join crpspecializedtradeworkclassification T2 on T1.Id=T2.CmnAppliedCategoryId and T2.CrpSpecializedTradeId=? where T1.Code like '%SF%' order by T1.Code,T1.Name",array($specializedtradeId));
	}
	$data['isFinalPrint']=$isFinalPrint;
	$data['printTitle']='Specialized Firm Information';
	$data['specializedTradeInformations']=$specializedTradeInformations;
	$data['workClasssifications']=$workClasssifications;
	$data['ownerPartnerDetails']=$ownerPartnerDetails;
	 
	$data['generalInformation']=$generalInformation;
	$data['specializedtradeHumanResources']=$specializedtradeHumanResources;
	$data['specializedtradeEquipments']=$specializedtradeEquipments;
	$pdf = App::make('dompdf');
	$pdf->loadView('printpages.specializedfirmviewprintinformation',$data)->setPaper('a4')->setOrientation('potrait');
	return $pdf->stream();
}

//Deregister & suspend & Reregistration begins from here




public function deregisterBlackListRegistration(){
	
	$postedValues=Input::all();
	DB::beginTransaction();
	$SpecializedTradeReference=$postedValues['CrpSpecializedTradeId'];
	$specializedTradeUserId=SpecializedfirmFinalModel::where('Id',$postedValues['CrpSpecializedTradeId'])->pluck('SysUserId');
	try{
		if(Input::has('DeRegisteredDate')){
			$postedValues['DeRegisteredDate']=$this->convertDate($postedValues['DeRegisteredDate']);
		}elseif(Input::has('BlacklistedDate')){
			$postedValues['BlacklistedDate']=$this->convertDate($postedValues['BlacklistedDate']);
		}else{
			$postedValues['ReRegistrationDate']=$this->convertDate($postedValues['ReRegistrationDate']);
		}
		$instance=SpecializedfirmFinalModel::find($postedValues['CrpSpecializedTradeId']);
		$instance->fill($postedValues);
		$instance->update();
		
		$userInstance=User::find($specializedTradeUserId);
		if($postedValues['CmnApplicationRegistrationStatusId']==CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED){
			$redirectRoute="reregistration";
			if((bool)$specializedTradeUserId){
				$userInstance=User::find($specializedTradeUserId);
				$userInstance->Status=1;
				$userInstance->save();
			}

			$specializedtradeAdverserecordInstance = new SpecializedTradeCommentAdverseRecordModel;
			$specializedtradeAdverserecordInstance->CrpSpecializedTradeFinalId = $SpecializedTradeReference;
			$specializedtradeAdverserecordInstance->Date=date('Y-m-d');
			
			$specializedtradeAdverserecordInstance->Remarks=Input::get('ReRegistrationRemarks');
			$specializedtradeAdverserecordInstance->Type=1;
			$specializedtradeAdverserecordInstance->save();
		}else{
			//for suspension
			if(Input::has('BlacklistedRemarks')){
				$redirectRoute="suspend";
			}else{
				$redirectRoute="deregister";
			}
			if((bool)$specializedTradeUserId){
				$userInstance=User::find($specializedTradeUserId);
				$userInstance->Status=0;
				$userInstance->save();
			}
			/*---Insert Adverse Record i.e the remarks if the contractor is deregistered/blacklisted*/
			if(Input::has('BlacklistedRemarks')){
				$specializedtradeAdverserecordInstance = new SpecializedTradeCommentAdverseRecordModel;
				$specializedtradeAdverserecordInstance->CrpSpecializedTradeFinalId = $SpecializedTradeReference;
				$specializedtradeAdverserecordInstance->Date=date('Y-m-d');
				$specializedtradeAdverserecordInstance->Remarks=Input::get('BlacklistedRemarks');
				$specializedtradeAdverserecordInstance->Type=1;
				$specializedtradeAdverserecordInstance->save();
			}else{
				if(Input::has('RevokedRemarks')){
					$specializedtradeAdverserecordInstance = new SpecializedTradeCommentAdverseRecordModel;
					$specializedtradeAdverserecordInstance->CrpSpecializedTradeFinalId = $SpecializedTradeReference;
					$specializedtradeAdverserecordInstance->Date=date('Y-m-d');
					
					$specializedtradeAdverserecordInstance->Remarks=Input::get('RevokedRemarks');
					$specializedtradeAdverserecordInstance->Type=1;
					$specializedtradeAdverserecordInstance->save();
				}else{
					$specializedtradeAdverserecordInstance = new SpecializedTradeCommentAdverseRecordModel;
					$specializedtradeAdverserecordInstance->CrpSpecializedTradeFinalId = $SpecializedTradeReference;
					$specializedtradeAdverserecordInstance->Date=date('Y-m-d');
					
					$specializedtradeAdverserecordInstance->Type=1;
					$specializedtradeAdverserecordInstance->save();
				}

			}
		}
	}catch(Exception $e){
		DB::rollback();
		throw $e;
	}
	DB::commit();
	return Redirect::to('specializedfirm/'.$redirectRoute)->with('savedsuccessmessage','Successfully updated');
}



public function blacklistDeregisterList(){
	$type=3;
	$reRegistration=1;
	$parameters=array();
	$specializedTradeId=Input::get('CrpSpecializedTradeId');
	$SPNo=Input::get('SPNo');
	$tradeLicenseNo = Input::get('TradeLicenseNo');
	$fromDate = Input::get('FromDate');
	$toDate = Input::get('ToDate');
	
	$query="select T1.Id,T1.ApplicationDate,T1.SPNo,T1.MobileNo,T1.Email,T1.NameOfFirm from crpspecializedfirmfinal T1  where  SPNo LIKE '%SF%'";
	if(Request::path()=="specializedfirm/deregister"){
		$reRegistration=0;
		$type=1;
		$captionHelper="Registered";
		$captionSubject="Deregistration of Specialized Firm";
		$query.=" and T1.CmnApplicationRegistrationStatusId=?";
		array_push($parameters,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED);
	}elseif(Request::path()=="specializedfirm/suspend"){
		$reRegistration=0;
		$type=2;
		$captionHelper="Registered";
		$captionSubject="Blacklisting of Specialized Firm";
		$query.=" and T1.CmnApplicationRegistrationStatusId=?";
		array_push($parameters,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED);
	}elseif(Request::path()=="specializedfirm/reregistration"){
		$captionHelper="Deregistered or Blacklisted";
		$captionSubject="Re-registration of Specialized Firm";
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
	$hasParams = false;
	if((bool)$specializedTradeId!=NULL || (bool)$SPNo!=NULL){
		$hasParams =true;
		if((bool)$specializedTradeId!=NULL){
			$query.=" and T1.Id=?";
			array_push($parameters,$specializedTradeId);
		}
		if((bool)$SPNo!=NULL){
			$query.=" and T1.SPNo=?";
			array_push($parameters,$SPNo);
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
	$specializedTradeListsAll=SpecializedfirmFinalModel::specializedTradeHardListAll()->get(array('Id','NameOfFirm'));
	$specializedfirmLists=DB::select($query." order by SPNo,NameOfFirm".$limit,$parameters);
	return View::make('crps.specializedfirmderegisterationlist')
		->with('SPNo',$SPNo)
		->with('type',$type)
		->with('captionHelper',$captionHelper)
		->with('captionSubject',$captionSubject)
		->with('reRegistration',$reRegistration)
		->with('specializedTradeId',$specializedTradeId)
		->with('specializedfirmLists',$specializedfirmLists)
		->with('specializedTradeListsAll',$specializedTradeListsAll);
}



	/* Specializedfirm edit begins from here */

	public function editDetails($specializedtradeId){
		$registrationApprovedForPayment=0;
		$userSpecializedtrade=0;
		$loggedInUser = Auth::user()->Id;
		$userRoles=DB::table('sysuserrolemap')->where('SysUserId',$loggedInUser)->lists('SysRoleId');
		$isAdmin = false;
		if(in_array(CONST_ROLE_ADMINISTRATOR,$userRoles)){
			$isAdmin = true;
		}



		$generalInformation=SpecializedfirmFinalModel::specializedtrade($specializedtradeId)->get(array('crpspecializedfirmfinal.Id','crpspecializedfirmfinal.ReferenceNo','crpspecializedfirmfinal.RegistrationExpiryDate','crpspecializedfirmfinal.SPNo','crpspecializedfirmfinal.TPN','crpspecializedfirmfinal.Gewog','crpspecializedfirmfinal.RegisteredAddress','crpspecializedfirmfinal.Village','crpspecializedfirmfinal.DeRegisteredDate','crpspecializedfirmfinal.BlacklistedDate','crpspecializedfirmfinal.DeregisteredRemarks','crpspecializedfirmfinal.BlacklistedRemarks','crpspecializedfirmfinal.ApplicationDate','crpspecializedfirmfinal.NameOfFirm', 'crpspecializedfirmfinal.TradeLicenseNo','crpspecializedfirmfinal.Address','crpspecializedfirmfinal.Email','crpspecializedfirmfinal.TelephoneNo','crpspecializedfirmfinal.MobileNo','crpspecializedfirmfinal.FaxNo','crpspecializedfirmfinal.CmnApplicationRegistrationStatusId','T8.Name as Country','T4.Name as OwnershipType','T3.Name as Status','T2.NameEn as Dzongkhag','T5.NameEn as RegisteredDzongkhag'));
		$ownerPartnerDetails=SpecializedfirmHumanResourceFinalModel::specializedtradePartner($specializedtradeId)->get(array('crpspecializedtradehumanresourcefinal.Id','crpspecializedtradehumanresourcefinal.CIDNo','crpspecializedtradehumanresourcefinal.Name','crpspecializedtradehumanresourcefinal.Sex','crpspecializedtradehumanresourcefinal.JoiningDate','crpspecializedtradehumanresourcefinal.ShowInCertificate','T1.Nationality as Country','T2.Name as Salutation','T3.Name as Designation'));
		$specializedtradeWorkClassifications=DB::select("select distinct T1.Code,T1.Name as Category,T2.CmnAppliedCategoryId,T3.CmnVerifiedCategoryId,T4.CmnApprovedCategoryId from cmnspecializedtradecategory T1 left join crpspecializedfirmworkclassificationfinal T2 on T1.Id=T2.CmnAppliedCategoryId and T2.CrpSpecializedTradeFinalId=? left join crpspecializedfirmworkclassificationfinal T3 on T1.Id=T3.CmnVerifiedCategoryId and T3.CrpSpecializedTradeFinalId=? left join crpspecializedfirmworkclassificationfinal T4 on T1.Id=T4.CmnVerifiedCategoryId and T4.CrpSpecializedTradeFinalId=? where T1.Code like '%SF%'  order by T1.Code,T1.Name",array($specializedtradeId,$specializedtradeId,$specializedtradeId));
		$specializedtradeHumanResources=SpecializedfirmHumanResourceFinalModel::specializedtradeHumanResource($specializedtradeId)->get(array('crpspecializedtradehumanresourcefinal.Id','crpspecializedtradehumanresourcefinal.Name','crpspecializedtradehumanresourcefinal.CIDNo','crpspecializedtradehumanresourcefinal.EditedOn','crpspecializedtradehumanresourcefinal.CmnServiceTypeId','crpspecializedtradehumanresourcefinal.Sex','crpspecializedtradehumanresourcefinal.JoiningDate','crpspecializedtradehumanresourcefinal.Name','T1.Name as Salutation','T2.Name as Qualification','T3.Name as Trade','T4.Name as Designation','T5.Nationality as Country','T10.CDBNO as CDBNo1', 'T11.ARNo as CDBNo2', 'T12.ARNo as CDBNo3',  'T13.SPNo as CDBNo4'));
		$specializedtradeEquipments=SpecializedfirmEquipmentFinalModel::specializedtradeEquipment($specializedtradeId)->get(array('crpspecializedtradeequipmentfinal.Id','crpspecializedtradeequipmentfinal.RegistrationNo','crpspecializedtradeequipmentfinal.ModelNo','crpspecializedtradeequipmentfinal.Quantity','crpspecializedtradeequipmentfinal.EditedOn','T1.Name'));
		$specializedtradeTrackrecords =CrpBiddingFormModel::specializedtradeTrackrecords($specializedtradeId)->get(array('crpbiddingform.WorkOrderNo','crpbiddingform.OntimeCompletionScore','crpbiddingform.QualityOfExecutionScore','crpbiddingform.NameOfWork','crpbiddingform.DescriptionOfWork','crpbiddingform.ContractPeriod','crpbiddingform.ApprovedAgencyEstimate','crpbiddingform.WorkStartDate','crpbiddingform.Remarks','crpbiddingform.WorkCompletionDate','T2.Name as Agency', 'T3.Name as Category', 'T5.NameEn as Dzongkhag' ,'T6.Name as WorkExecutionStatus'));
		$specializedtradeHumanResourceAttachments=SpecializedfirmHumanResourceAttachmentFinalModel::singleSpecializedtradeHumanResourceAllAttachments($specializedtradeId)->get(array('crpspecializedtradehumanresourceattachmentfinal.DocumentName','crpspecializedtradehumanresourceattachmentfinal.DocumentPath','crpspecializedtradehumanresourceattachmentfinal.CrpSpecializedtradeHumanResourceFinalId as CrpSpecializedtradeHumanResourceId'));
		$specializedtradeEquipmentAttachments=SpecializedfirmEquipmentAttachmentFinalModel::singleSpecializedtradeEquipmentAllAttachments($specializedtradeId)->get(array('crpspecializedtradeequipmentattachmentfinal.DocumentName','crpspecializedtradeequipmentattachmentfinal.DocumentPath','crpspecializedtradeequipmentattachmentfinal.CrpSpecializedtradeEquipmentFinalId as CrpSpecializedtradeEquipmentId'));
		$specializedtradeComments = SpecializedfirmCommentAdverseRecordModel::commentList($specializedtradeId)->get(array('Id','Date','Remarks'));
		$specializedtradeAdverseRecords = SpecializedfirmCommentAdverseRecordModel::adverseRecordList($specializedtradeId)->get(array('Id','Date','Remarks'));
		$specializedtradeAttachments=DB::table('crpspecializedtradeattachmentfinal')->where('CrpSpecializedtradeFinalId',$specializedtradeId)->get(array('DocumentName','DocumentPath'));
		$specializedtradeEmployeesIds = DB::table('crpspecializedtradehumanresourcefinal')->where('CrpSpecializedtradeFinalId',$specializedtradeId)->select(DB::raw("TRIM(CIDNo) as EmpCIDNo"))->lists('EmpCIDNo');
		

		return View::make('crps.specializedfirminformation')
			->with('isAdmin',$isAdmin)
			->with('registrationApprovedForPayment',$registrationApprovedForPayment)
		
			->with('specializedtradeAttachments',$specializedtradeAttachments)
			->with('userSpecializedtrade',$userSpecializedtrade)
			->with('specializedtradeId',$specializedtradeId)
			->with('generalInformation',$generalInformation)
			->with('ownerPartnerDetails',$ownerPartnerDetails)
			->with('specializedtradeWorkClassifications',$specializedtradeWorkClassifications)
			->with('specializedtradeHumanResources',$specializedtradeHumanResources)
			->with('specializedtradeEquipments',$specializedtradeEquipments)
			->with('specializedtradeTrackrecords',$specializedtradeTrackrecords)
			->with('specializedtradeHumanResourceAttachments',$specializedtradeHumanResourceAttachments)
			->with('specializedtradeEquipmentAttachments',$specializedtradeEquipmentAttachments)
			->with('specializedtradeComments',$specializedtradeComments)
			->with('specializedtradeAdverseRecords',$specializedtradeAdverseRecords);
					
	}

	//General Information Edit Information Begins from Here

	public function generalInfoRegistration($specializedtrade=null){
		$isRejectedApp=0;
		$serviceBySpecializedtrade=0;
		$isRenewalService=0;
		$newGeneralInfoSave=1;
		$editByCDB = false;
		$view="crps.specializedfirmregistrationgeneralinfo";
		$postRouteReference='specializedfirm/mspecializedtradegeneralinfo';
		$redirectUrl=Input::get('redirectUrl');
		$specializedtradeGeneralInfo=array(new SpecializedfirmModel());
		$specializedtradePartnerDetail=array(new SpecializedfirmHumanResourceModel());
		
		if((bool)$specializedtrade!=null && !Input::has('usercdb')){
			if(Input::has('editbyapplicant')){
				if(Input::has('rejectedapplicationreapply')){
					$isRejectedApp=1; //HAS REAPPLIED
					
				}
				$view="crps.specializedfirmregistrationgeneralinfo";
			}else{
				$view="crps.specializedfirmeditgeneralinfo";
			}
			$specializedtradeGeneralInfo=SpecializedfirmModel::specializedtradeHardList($specializedtrade)->get(array('Id','ReferenceNo','ApplicationDate','CmnOwnershipTypeId','NameOfFirm','TPN','TradeLicenseNo','TelephoneNo','Gewog','Village','Address','Email','TelephoneNo','MobileNo','FaxNo','CmnCountryId','CmnDzongkhagId'));
			$specializedtradePartnerDetail=SpecializedfirmHumanResourceModel::specializedtradePartnerHardList($specializedtrade)->get(array('Id','CIDNo','Name','Sex','JoiningDate','ShowInCertificate','CmnCountryId','CmnSalutationId','CmnDesignationId'));
		}
		if((bool)$specializedtrade!=null && Input::has('usercdb')){
			$editByCDB = true;
			$view="crps.specializedfirmeditgeneralinfo";
			$newGeneralInfoSave=0;
			$specializedtradeGeneralInfo=SpecializedfirmFinalModel::specializedtradeHardList($specializedtrade)->get(array('Id','ReferenceNo','TPN','TradeLicenseNo','ApplicationDate','NameOfFirm','CmnRegisteredDzongkhagId','Gewog','Village','RegisteredAddress','Address','Email','TelephoneNo','MobileNo','FaxNo','CmnCountryId','CmnDzongkhagId','CmnOwnershipTypeId'));
			$specializedtradePartnerDetail=SpecializedfirmHumanResourceFinalModel::specializedtradePartnerHardList($specializedtrade)->get(array('Id','CIDNo','Name','Sex','JoiningDate','ShowInCertificate','CmnCountryId','CmnSalutationId','CmnDesignationId'));
		}
		$applicationReferenceNo=$this->tableTransactionNo('SpecializedfirmModel','ReferenceNo');
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
			->with('isEdit',$specializedtrade)
			->with('postRouteReference',$postRouteReference)
			->with('serviceBySpecializedtrade',$serviceBySpecializedtrade)
			->with('newGeneralInfoSave',$newGeneralInfoSave)
			->with('isServiceBySpecializedtrade','')
			->with('applicationReferenceNo',$applicationReferenceNo)
			->with('specializedtradeGeneralInfo',$specializedtradeGeneralInfo)
			->with('specializedtradePartnerDetails',$specializedtradePartnerDetail)
			->with('countries',$country)
			->with('dzongkhags',$dzongkhag)
			->with('designations',$designation)
			->with('salutations',$salutation)
			->with('ownershipTypes',$ownershipTypes);
	}

	public function saveGeneralInfo(){
		$postedValues=Input::except('ChangeOfLocationOwner','OtherServices','attachments','DocumentName','DocumentNameOwnerShipChange','attachmentsownershipchange','attachmentsfirmnamechange','DocumentNameFirmNameChange');
		$newGeneralInfoSave=Input::get('NewGeneralInfoSave');
		$isServiceBySpecializedtrade=Input::get('ServiceBySpecializedtrade');
		$validation = new SpecializedfirmModel;
		if(!($validation->validate($postedValues))){
			$errors = $validation->errors();
			if((int)$isServiceBySpecializedtrade!=1){
				return Redirect::to('specializedfirm/generalinforegistration')->withInput()->withErrors($errors);
			}else{
				return Redirect::to('specializedfirm/applyservicegeneralinfo/'.Input::get('CrpSpecializedtradeId'))->withInput()->withErrors($errors);
			}
		}

		/*To check if already applied */
		if(!Input::has('OldApplicationId')){
			$isFinalContractor = DB::table('crpspecializedfirmfinal')->where('Id',Input::get('CrpSpecializedtradeId'))->count();
			if($isFinalContractor==0){
				$finalTableId = DB::table('crpspecializedfirm')->where('Id',Input::get('CrpSpecializedtradeId'))->pluck('CrpSpecializedtradeId');
			}else{
				$finalTableId = Input::get('CrpSpecializedtradeId');
			}
			$previousApplications = DB::table('crpspecializedfirm')->whereNotNull('CrpSpecializedtradeId')->where('CrpSpecializedtradeId',$finalTableId)->where(DB::raw('coalesce(RegistrationStatus,0)'),1)->whereNotIn('CmnApplicationRegistrationStatusId',array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_REJECTED))->count();
			if($previousApplications>0){
				$previousApplicationDetails = DB::table('crpspecializedfirm')->where('CrpSpecializedtradeId',$finalTableId)->where(DB::raw('coalesce(RegistrationStatus,0)'),1)->whereNotIn('CmnApplicationRegistrationStatusId',array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_REJECTED))->select(DB::raw("GROUP_CONCAT(CONCAT('Application No. ',ReferenceNo,' dt. ',ApplicationDate) SEPARATOR '<br/>') as Applications"))->pluck('Applications');
				return Redirect::to('specializedfirm/mydashboard')->with("customerrormessage","<h4><strong> MESSAGE! You have following pending application(s) with CDB: </strong></h4><ol>$previousApplicationDetails</ol><strong>Please wait for us to process your previous application before submitting a new one!</strong> ");
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
					$destination=public_path().'/uploads/specializedfirm';
					$destinationDB='uploads/specializedfirm/'.$attachmentName;
					$multiAttachments1["DocumentName"]=(count($documentName)>1)?$documentName[$count]:$documentName[0];

					//CHECK IF IMAGE
					if(strpos($attachment->getClientMimeType(),'image/')>-1){
						$img = Image::make($attachment)->encode('jpg');
						$destinationDB = "uploads/specializedfirm/".str_random(15) . '_min_' .".jpg";
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
					$destination=public_path().'/uploads/specializedfirm';
					$destinationDB='uploads/specializedfirm/'.$attachmentName;
					if(isset($documentName[$countownershipchange])){
						$multiAttachmentsownershipchange1["DocumentName"]=$documentName[$countownershipchange];

						//CHECK IF IMAGE
						if(strpos($attachmentownership->getClientMimeType(),'image/')>-1){
							$img = Image::make($attachmentownership)->encode('jpg');
							$destinationDB = "uploads/specializedfirm/".str_random(15) . '_min_' .".jpg";
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
					$destination=public_path().'/uploads/specializedfirm';
					$destinationDB='uploads/specializedfirm/'.$attachmentName;
					$multiAttachmentsownershipchange1["DocumentName"]=$documentName[$countfirmnamechange];

					//CHECK IF IMAGE
					if(strpos($attachmentfirmnamechange->getClientMimeType(),'image/')>-1){
						$img = Image::make($attachmentfirmnamechange)->encode('jpg');
						$destinationDB = "uploads/specializedfirm/".str_random(15) . '_min_' .".jpg";
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
			$postedValues["ReferenceNo"]=$this->tableTransactionNo('SpecializedfirmModel','ReferenceNo');
			DB::beginTransaction();
			try{
				if(Input::has('ChangeOfLocationOwner')):
					$changeOfOwnerLocation=Input::get('ChangeOfLocationOwner');
					if($changeOfOwnerLocation){
						foreach($changeOfOwnerLocation as $xService):
							if($xService == CONST_SERVICETYPE_CHANGEOWNER){
								$ownerPartnerInputs = Input::get('SpecializedfirmHumanResourceModel');
								$oldOwnerPartners = DB::table('crpspecializedtradehumanresourcefinal')
									->where('CrpSpecializedtradeFinalId',Input::get('CrpSpecializedtradeId'))
									->where(DB::raw('coalesce(IsPartnerOrOwner,0)'),1)
									->get(array('CIDNo','Name','CmnCountryId','CmnDesignationId'));
								foreach($oldOwnerPartners as $oldOwnerPartner):
									$cidNo = $oldOwnerPartner->CIDNo;
									$name = $oldOwnerPartner->Name;
									$cmnCountryId = $oldOwnerPartner->CmnCountryId;
									$cmnDesignationId = $oldOwnerPartner->CmnDesignationId;
									DB::table('crpspecializedtradehrtrack')->insert(array('CIDNo'=>$cidNo,'ReferenceNo'=>$postedValues["ReferenceNo"],'Date'=>date('Y-m-d G:i:s'),'CrpContractorFinalId'=>Input::get('CrpSpecializedtradeId'),'Name'=>$name,'CmnCountryId'=>$cmnCountryId,'CmnDesignationId'=>$cmnDesignationId));
								endforeach;
							}
						endforeach;
					}
				endif;

				if(Input::has('OldApplicationId')){
					$generatedId = Input::get('OldApplicationId');
					$instanceOfClass = SpecializedfirmModel::find($generatedId);
					$instanceOfClass->fill($postedValues);
					$instanceOfClass->update();
				}else{
					SpecializedfirmModel::create($postedValues);
				}
				if(Input::hasFile('attachments')){
					foreach($multiAttachments as $k=>$v){
						if(isset($isEditByCDB) && (int)$isEditByCDB==1 && $newGeneralInfoSave!=null  && (int)$newGeneralInfoSave==0){
							$multiAttachments[$k]["CrpSpecializedtradeFinalId"]=$generatedId;
							$saveUploads=new SpecializedfirmAttachmentFinalModel($multiAttachments[$k]);
						}else{
							$multiAttachments[$k]["CrpSpecializedtradeId"]=$generatedId;
							$saveUploads=new SpecializedfirmAttachmentModel($multiAttachments[$k]);
						}
						$saveUploads->save();
					}
				}
				if(Input::hasFile('attachmentsownershipchange')){
					foreach($multiAttachmentsownershipchange as $k=>$v){
						if(isset($isEditByCDB) && (int)$isEditByCDB==1 && $newGeneralInfoSave!=null  && (int)$newGeneralInfoSave==0){
							$multiAttachmentsownershipchange[$k]["CrpSpecializedtradeFinalId"]=$generatedId;
							$saveUploads=new SpecializedfirmAttachmentFinalModel($multiAttachmentsownershipchange[$k]);
						}else{
							$multiAttachmentsownershipchange[$k]["CrpSpecializedtradeId"]=$generatedId;
							$saveUploads=new SpecializedfirmAttachmentModel($multiAttachmentsownershipchange[$k]);
						}
						$saveUploads->save();
					}
				}
			
				if(Input::hasFile('attachmentsfirmnamechange')){
					foreach($multiAttachmentsFirmNameChange as $k=>$v){
						if(isset($isEditByCDB) && (int)$isEditByCDB==1 && $newGeneralInfoSave!=null  && (int)$newGeneralInfoSave==0){
							$multiAttachmentsFirmNameChange[$k]["CrpSpecializedtradeFinalId"]=$generatedId;
							$saveUploads=new SpecializedfirmAttachmentFinalModel($multiAttachmentsFirmNameChange[$k]);
						}else{
							$multiAttachmentsFirmNameChange[$k]["CrpSpecializedtradeId"]=$generatedId;
							$saveUploads=new SpecializedfirmAttachmentModel($multiAttachmentsFirmNameChange[$k]);
						}
						$saveUploads->save();
					}
				}
				if(Input::has('RenewalService') && (int)$isServiceBySpecializedtrade==1){
					$lateRenewalExpiryDate=SpecializedfirmFinalModel::specializedtradeHardList($postedValues['CrpSpecializedtradeId'])->pluck('RegistrationExpiryDate');
					$lateRenewalExpiryDate=strtotime($lateRenewalExpiryDate);
					$currentDate=strtotime(date('Y-m-d'));
					$appliedServiceRenewal = new SpecializedfirmAppliedServiceModel;
					$appliedServiceRenewal->CrpSpecializedtradeId=$generatedId;
					$appliedServiceRenewal->CmnServiceTypeId = Input::get('RenewalService');
					$appliedServiceRenewal->save();
					if($currentDate>$lateRenewalExpiryDate){
						$appliedServiceRenewalLateFee = new SpecializedfirmAppliedServiceModel;
						$appliedServiceRenewalLateFee->CrpSpecializedtradeId=$generatedId;
						$appliedServiceRenewalLateFee->CmnServiceTypeId = CONST_SERVICETYPE_LATEFEE;
						$appliedServiceRenewalLateFee->save();
					}
				}
				if(Input::has('ChangeOfLocationOwner') && (int)$isServiceBySpecializedtrade==1){
					$changeOfOwnerLocation=Input::get('ChangeOfLocationOwner');
					for($idx = 0; $idx < count($changeOfOwnerLocation); $idx++){
						$appliedService = new SpecializedfirmAppliedServiceModel;
						$appliedService->CrpSpecializedtradeId=$generatedId;
						$appliedService->CmnServiceTypeId = $changeOfOwnerLocation[$idx];
						$appliedService->save();
					}
				}

				if(Input::has('OtherServices') && (int)$isServiceBySpecializedtrade==1){
					$otherServices=Input::get('OtherServices');
					for($idx = 0; $idx < count($otherServices); $idx++){
						$appliedService = new SpecializedfirmAppliedServiceModel;
						$appliedService->CrpSpecializedtradeId=$generatedId;
						$appliedService->CmnServiceTypeId = $otherServices[$idx];
						$appliedService->save();
					}
				}
				foreach($postedValues as $key=>$value){
					if(gettype($value)=='array'){
						foreach($value as $key1=>$value1){
							$value1['CrpSpecializedtradeId']=$generatedId;
							$childTable= new SpecializedfirmHumanResourceModel($value1);
							$a=$childTable->save();
						}
					}
				}

			}catch(Exception $e){
				DB::rollback();
				throw $e;
			}
			DB::commit();
			if((int)$isServiceBySpecializedtrade=1){
		
				if(Input::has('OldApplicationId')){
					$servicesAppliedBySpecializedtrade = DB::table('crpspecializedtradeappliedservice')->where('CrpSpecializedtradeId',Input::get("OldApplicationId"))->lists('CmnServiceTypeId');
				}else{
					$servicesAppliedBySpecializedtrade = DB::table('crpspecializedtradeappliedservice')->where('CrpSpecializedtradeId',$generatedId)->lists('CmnServiceTypeId');
				}


				if(in_array(CONST_SERVICETYPE_CHANGEOFCATEGORYCLASSIFICATION,$servicesAppliedBySpecializedtrade)):
					return Redirect::to('specializedfirm/applyserviceworkclassification/'.$generatedId);
				endif;
				if(in_array(CONST_SERVICETYPE_RENEWAL,$servicesAppliedBySpecializedtrade)):
					return Redirect::to('specializedfirm/applyserviceworkclassification/'.$generatedId);
				endif;
				if(in_array(CONST_SERVICETYPE_UPDATEHUMANRESOURCE,$isServiceBySpecializedtrade)):
					$isEditByCDB=Input::get('EditByCDB');
					$redirectTo=Input::get('PostBackUrl');
					if(isset($isEditByCDB) && (int)$isEditByCDB==1){
						return Redirect::to('specializedfirm/applyservicehumanresource'.'/'.$generatedId.'?redirectUrl='.$redirectTo)->with('savedsuccessmessage','Work Classification has been successfully updated.');
					}
				endif;
				if(in_array(CONST_SERVICETYPE_UPDATEEQUIPMENT,$servicesAppliedBySpecializedtrade)):
					return Redirect::to('specializedfirm/applyserviceequipment/'.$generatedId);
				endif;
				return Redirect::to('specializedfirm/applyserviceconfirmation/'.$generatedId);
			}else{
				Session::put('SpecializedtradeRegistrationId',$generatedId);
				return Redirect::to('specializedfirm/workclassificationregistration');
			}
		}else{
			$isEditByCDB=Input::get('EditByCDB');
			$redirectTo=Input::get('PostBackUrl');
			$isRejectedApp=Input::get('ApplicationRejectedReapply');
			DB::beginTransaction();
			try{
				if(isset($isEditByCDB) && (int)$isEditByCDB==1 && $newGeneralInfoSave!=null && (int)$newGeneralInfoSave==0){
					$instance=SpecializedfirmFinalModel::find($postedValues['Id']);
				}else{
					$instance=SpecializedfirmModel::find($postedValues['Id']);
				}
				$instance->fill($postedValues);
				$instance->update();
				if(Input::hasFile('attachments')){
					foreach($multiAttachments as $k=>$v){
						if(isset($isEditByCDB) && (int)$isEditByCDB==1 && $newGeneralInfoSave!=null  && (int)$newGeneralInfoSave==0){
							$multiAttachments[$k]["CrpSpecializedtradeFinalId"]=$postedValues['Id'];
							$saveUploads=new SpecializedfirmAttachmentFinalModel($multiAttachments[$k]);
						}else{
							$multiAttachments[$k]["CrpSpecializedtradeId"]=$postedValues['Id'];
							$saveUploads=new SpecializedfirmAttachmentModel($multiAttachments[$k]);
						}
						$saveUploads->save();
					}
				}
				if(Input::hasFile('attachmentsownershipchange')){
					foreach($multiAttachmentsownershipchange as $k=>$v){
						if(isset($isEditByCDB) && (int)$isEditByCDB==1 && $newGeneralInfoSave!=null  && (int)$newGeneralInfoSave==0){
							$multiAttachmentsownershipchange[$k]["CrpSpecializedtradeFinalId"]=$postedValues['Id'];
							$saveUploads=new SpecializedfirmAttachmentFinalModel($multiAttachmentsownershipchange[$k]);
						}else{
							$multiAttachmentsownershipchange[$k]["CrpSpecializedtradeId"]=$postedValues['Id'];
							$saveUploads=new SpecializedfirmAttachmentModel($multiAttachmentsownershipchange[$k]);
						}
						$saveUploads->save();
					}
				}
				if(Input::hasFile('attachmentsfirmnamechange')){
					foreach($multiAttachmentsFirmNameChange as $k=>$v){
						if(isset($isEditByCDB) && (int)$isEditByCDB==1 && $newGeneralInfoSave!=null  && (int)$newGeneralInfoSave==0){
							$multiAttachmentsFirmNameChange[$k]["CrpSpecializedtradeFinalId"]=$postedValues['Id'];
							$saveUploads=new SpecializedfirmAttachmentFinalModel($multiAttachmentsFirmNameChange[$k]);
						}else{
							$multiAttachmentsFirmNameChange[$k]["CrpSpecializedtradeId"]=$postedValues['Id'];
							$saveUploads=new SpecializedfirmAttachmentModel($multiAttachmentsFirmNameChange[$k]);
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
									$value1['CrpSpecializedtradeFinalId']=$postedValues['Id'];
									$childTable= new SpecializedfirmHumanResourceFinalModel($value1);
								}else{
									$value1['CrpSpecializedtradeId']=$postedValues['Id'];
									$childTable= new SpecializedfirmHumanResourceModel($value1);
								}
								$a=$childTable->save();
							}else{
								if(isset($isEditByCDB) && (int)$isEditByCDB==1 && $newGeneralInfoSave!=null && (int)$newGeneralInfoSave==0){
									//ADDED BY SWM ON 7th June
									if(!isset($value1['ShowInCertificate'])) {
										$value1['ShowInCertificate'] = 0;
									}
									//ADDED BY SWM on 7th June
									$childTable1=SpecializedfirmHumanResourceFinalModel::find($value1['Id']);
								}else{
									//ADDED BY SWM ON 7th June
									if(!isset($value1['ShowInCertificate'])) {
										$value1['ShowInCertificate'] = 0;
									}
									//ADDED BY SWM on 7th June
									$childTable1=SpecializedfirmHumanResourceModel::find($value1['Id']);
								}
								$childTable1->fill($value1);
								$childTable1->update();
							}
						}
					}
				}
				DB::commit();
				$isNewRegistration = DB::table('crpspecializedfirm')->where('Id',$postedValues['Id'])->pluck('CrpSpecializedtradeId');
				if(isset($isRejectedApp) && (int)$isRejectedApp==1){
					if(!(bool)$isNewRegistration){
						Session::put('SpecializedtradeRegistrationId',$postedValues["Id"]);
						return Redirect::to('specializedfirm/workclassificationregistration?rejectedapplicationreapply=true');
					}
				}

				if(isset($isEditByCDB) && (int)$isEditByCDB==1){
					if((bool)$redirectTo){
						return Redirect::to($redirectTo.'/'.$postedValues["Id"])->with('savedsuccessmessage','General Information has been successfully updated.');
					}else{
						Session::put('SpecializedtradeRegistrationId',$postedValues['Id']);
						if(Input::has('OldApplicationId')){
							$currentServicesApplied = array_merge(Input::has('ChangeOfLocationOwner')?Input::get('ChangeOfLocationOwner'):array(),Input::has('OtherServices')?Input::get('OtherServices'):array());
							$servicesAppliedBySpecializedtrade= DB::table('crpspecializedtradeappliedservice')->where('CrpSpecializedtradeId',$postedValues["Id"])->lists('CmnServiceTypeId');
							foreach($currentServicesApplied as $currentService):
								if(!in_array($currentService,$servicesAppliedBySpecializedtrade)){
									$appliedService = new SpecializedfirmAppliedServiceModel;
									$appliedService->CrpSpecializedtradeId=$postedValues["Id"];
									$appliedService->CmnServiceTypeId = $currentService;
									$appliedService->save();
								}
							endforeach;
							if(in_array(CONST_SERVICETYPE_CHANGEOFCATEGORYCLASSIFICATION,$servicesAppliedBySpecializedtrade)):
								return Redirect::to('specializedfirm/applyserviceworkclassification/'.$postedValues["Id"]);
							endif;
							if(in_array(CONST_SERVICETYPE_RENEWAL,$servicesAppliedBySpecializedtrade)):
								return Redirect::to('specializedfirm/applyserviceworkclassification/'.$postedValues["Id"]);
							endif;
							if(in_array(CONST_SERVICETYPE_UPDATEHUMANRESOURCE,$servicesAppliedBySpecializedtrade)):
								$isEditByCDB=Input::get('EditByCDB');
								$redirectTo=Input::get('PostBackUrl');
								if(isset($isEditByCDB) && (int)$isEditByCDB==1){
									return Redirect::to('specializedfirm/applyservicehumanresource'.'/'.$postedValues["Id"].'?redirectUrl='.$redirectTo)->with('savedsuccessmessage','Work Classification has been successfully updated.');
								}
							endif;
							if(in_array(CONST_SERVICETYPE_UPDATEEQUIPMENT,$servicesAppliedByContractor)):
								return Redirect::to('specializedfirm/applyserviceequipment/'.$postedValues["Id"]);
							endif;
							return Redirect::to('specializedfirm/applyserviceconfirmation/'.$postedValues["Id"]);
						}
						return Redirect::to('specializedfirm/applyservicehumanresource/'.$postedValues["Id"])->with('savedsuccessmessage','General Information has been successfully updated.');
					}
				}

				return Redirect::to('specializedfirm/confirmregistration')->with('savedsuccessmessage','General Information has been successfully updated.');
			}catch(Exception $e){
				DB::rollback();
				throw $e;
			}
		}
	}


	// Human Resource Edit and new register Begins from here

	
	public function humanResourceRegistrationEdit($specializedtrade=null){
		$afterSaveRedirect=1;
		$serviceBySpecializedtrade=0;
		$newHumanResourceSave=0;
		$isEditByCDBUser=Input::get('usercdb');
		$humanResourceEditRoute='specializedfirm/edithumanresource';
		$redirectUrl=Input::get('redirectUrl');
		$editPage='specializedfirm/edithumanresource';
		$humanResourceEdit=array(new SpecializedfirmHumanResourceModel());
		$humanResourceEditAttachments=array();
		$humanResourceId=Input::get('humanresourceid');
		$specializedtradeHumanResources = array();
		$humanResourcesAttachments = array();
		$humanResourceEditFinalAttachments=array();
		if(!empty($humanResourceId) && strlen($humanResourceId)==36){
			$humanResourceEditFinalAttachments=SpecializedfirmHumanResourceAttachmentFinalModel::humanResourceAttachment($humanResourceId)->get(array('Id','DocumentName','DocumentPath'));
			if(!Input::has('usercdb')){
				$humanResourceEdit=SpecializedfirmHumanResourceModel::specializedtradeHumanResourceHardListSingle($humanResourceId)->get(array('Id','Name','CIDNo','Sex','CmnSalutationId','CmnQualificationId','CmnTradeId','CmnDesignationId','JoiningDate', 'CmnCountryId','CmnServiceTypeId'));
				$humanResourceEditAttachments=SpecializedfirmHumanResourceAttachmentModel::humanResourceAttachment($humanResourceId)->get(array('Id','DocumentName','DocumentPath'));
			}
			if(Input::has('usercdb')){
				$humanResourceEdit=SpecializedfirmHumanResourceFinalModel::specializedtradeHumanResourceHardListSingle($humanResourceId)->get(array('Id','Name','CIDNo','Sex','CmnSalutationId','CmnQualificationId','CmnTradeId','CmnDesignationId','JoiningDate', 'CmnCountryId','CmnServiceTypeId'));
				$humanResourceEditFinalAttachments=SpecializedfirmHumanResourceAttachmentFinalModel::humanResourceAttachment($humanResourceId)->get(array('Id','DocumentName','DocumentPath'));
				$humanResourceEditAttachments=SpecializedfirmHumanResourceAttachmentModel::humanResourceAttachment($humanResourceId)->get(array('Id','DocumentName','DocumentPath'));
				if(count($humanResourceEdit) == 0){
					$humanResourceEdit=SpecializedfirmHumanResourceModel::specializedtradeHumanResourceHardListSingle($humanResourceId)->get(array('Id','Name','CIDNo','Sex','CmnSalutationId','CmnQualificationId','CmnTradeId','CmnDesignationId','JoiningDate', 'CmnCountryId','CmnServiceTypeId'));
					$humanResourceEditAttachments=SpecializedfirmHumanResourceAttachmentModel::humanResourceAttachment($humanResourceId)->get(array('Id','DocumentName','DocumentPath'));
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
		if((bool)$specializedtrade!=null && !Input::has('usercdb')){
			$changeModel = false;
			$specializedtradeHumanResources=SpecializedfirmHumanResourceModel::SpecializedfirmHumanResource($specializedtrade)->get(array('crpspecializedtradehumanresource.Id', DB::raw('coalesce(T4.ReferenceNo,0) as DesignationReference'), 'crpspecializedtradehumanresource.Name','crpspecializedtradehumanresource.CIDNo','crpspecializedtradehumanresource.Sex','crpspecializedtradehumanresource.JoiningDate','crpspecializedtradehumanresource.Name','T1.Name as Salutation','T2.Name as Qualification','T3.Name as Trade','T4.Name as Designation','T5.Nationality as Country'));
			$humanResourcesAttachments=SpecializedfirmHumanResourceModel::humanResourceAttachments($specializedtrade)->get(array('T1.Id','T1.CrpSpecializedtradeHumanResourceId','T1.DocumentName','T1.DocumentPath'));
		}
		if((bool)$specializedtrade!=null && Input::has('usercdb')){
			$changeModel = true;
			$specializedtradeFinalHumanResources=SpecializedfirmHumanResourceFinalModel::SpecializedtradeHumanResource($specializedtrade)->get(array('crpspecializedtradehumanresourcefinal.Id',DB::raw('coalesce(T4.ReferenceNo,0) as DesignationReference'),'crpspecializedtradehumanresourcefinal.Name','crpspecializedtradehumanresourcefinal.CIDNo','crpspecializedtradehumanresourcefinal.Sex','crpspecializedtradehumanresourcefinal.CmnServiceTypeId','crpspecializedtradehumanresourcefinal.JoiningDate','crpspecializedtradehumanresourcefinal.Name','T1.Name as Salutation','T2.Name as Qualification','T3.Name as Trade','T4.Name as Designation','T5.Nationality as Country'));
			$humanResourcesFinalAttachments=SpecializedfirmHumanResourceAttachmentFinalModel::singleSpecializedtradeHumanResourceAllAttachments($specializedtrade)->get(array('crpspecializedtradehumanresourceattachmentfinal.DocumentName','crpspecializedtradehumanresourceattachmentfinal.DocumentPath','crpspecializedtradehumanresourceattachmentfinal.CrpSpecializedtradeHumanResourceFinalId as CrpSpecializedtradeHumanResourceId'));
			$specializedtradeInFinalTable = DB::table('crpspecializedfirmfinal')->where('Id',$specializedtrade)->count();
			if($specializedtradeInFinalTable == 0){
				$specializedtradeHumanResources=SpecializedfirmHumanResourceModel::SpecializedtradeHumanResource($specializedtrade)->get(array('crpspecializedtradehumanresource.Id', DB::raw('coalesce(T4.ReferenceNo,0) as DesignationReference'), 'crpspecializedtradehumanresource.Name','crpspecializedtradehumanresource.CIDNo','crpspecializedtradehumanresource.Sex','crpspecializedtradehumanresource.JoiningDate','crpspecializedtradehumanresource.CmnServiceTypeId','crpspecializedtradehumanresource.Name','T1.Name as Salutation','T2.Name as Qualification','T3.Name as Trade','T4.Name as Designation','T5.Nationality as Country'));
				$humanResourcesAttachments=SpecializedfirmHumanResourceModel::humanResourceAttachments($specializedtrade)->get(array('T1.Id','T1.CrpSpecializedtradeHumanResourceId','T1.DocumentName','T1.DocumentPath'));
			}else{
				$specializedtradeHumanResources=SpecializedfirmHumanResourceFinalModel::SpecializedtradeHumanResource($specializedtrade)->get(array('crpspecializedtradehumanresourcefinal.Id',DB::raw('coalesce(T4.ReferenceNo,0) as DesignationReference'),'crpspecializedtradehumanresourcefinal.Name','crpspecializedtradehumanresourcefinal.CIDNo','crpspecializedtradehumanresourcefinal.Sex','crpspecializedtradehumanresourcefinal.CmnServiceTypeId','crpspecializedtradehumanresourcefinal.JoiningDate','crpspecializedtradehumanresourcefinal.Name','T1.Name as Salutation','T2.Name as Qualification','T3.Name as Trade','T4.Name as Designation','T5.Nationality as Country'));
				$humanResourcesAttachments=SpecializedfirmHumanResourceAttachmentFinalModel::singleSpecializedtradeHumanResourceAllAttachments($specializedtrade)->get(array('crpspecializedtradehumanresourceattachmentfinal.DocumentName','crpspecializedtradehumanresourceattachmentfinal.DocumentPath','crpspecializedtradehumanresourceattachmentfinal.CrpSpecializedtradeHumanResourceFinalId as CrpSpecializedtradeHumanResourceId'));
			}
		}

		return View::make('crps.specializedfirmedithumanresource')
			->with('changeModel',$changeModel)
			->with('serviceTypes',$serviceTypes)
			->with('afterSaveRedirect',$afterSaveRedirect)
			->with('serviceBySpecializedtrade',$serviceBySpecializedtrade)
			->with('newHumanResourceSave',$newHumanResourceSave)
			->with('humanResourceEditFinalAttachments',$humanResourceEditFinalAttachments)
			->with('isEditByCDBUser',$isEditByCDBUser)
			->with('humanResourceEditRoute',$humanResourceEditRoute)
			->with('redirectUrl',$redirectUrl)
			->with('isEdit',$specializedtrade)
			->with('editPage',$editPage)
			->with('specializedtradeId',$specializedtrade)
			->with('countries',$country)
			->with('salutations',$salutation)
			->with('qualifications',$qualification)
			->with('designations',$designation)
			->with('trades',$trades)
			->with('specializedtradeHumanResources',$specializedtradeHumanResources)
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
		$specializedtradeId=Input::get('CrpSpecializedtradeId');
		$applicationDate = DB::table('crpspecializedfirm')->where('Id',$specializedtradeId)->pluck('ApplicationDate');
		//if(!empty($postedValues['Id']))
		//	DB::table('crpcontractorhumanresourceattachment')->where('CrpContractorHumanResourceId',$postedValues['Id'])->where('CreatedOn','<',$applicationDate)->delete();
		$isServiceBySpecializedtrade=Input::get('ServiceBySpecializedtrade');
		$newHumanResourceSave=Input::get('NewHumanResourceSave');
		$isEditByCDB=Input::get('EditByCDB');
		$redirectTo=Input::get('PostBackUrl');
		$multiAttachments=array();
		$uuid=DB::select("select uuid() as Id");
		$generatedId=$uuid[0]->Id;
		$validation = new SpecializedfirmHumanResourceModel;
		$redirectToEdit = false;
		if(!($validation->validate($postedValues))){
			$errors = $validation->errors();
			if(empty($postedValues["Id"])){
				return Redirect::to('specializedfirm/humanresourceregistration')->withInput()->withErrors($errors);
			}else{
				return Redirect::to('specializedfirm/humanresourceregistration/'.$postedValues['CrpSpecializedtradeId'])->withInput()->withErrors($errors);
			}
		}
		DB::beginTransaction();
		try{
			if(empty($postedValues["Id"])){
				$postedValues["Id"]=$generatedId;
				if(isset($isEditByCDB) && (int)$isEditByCDB==1 && $newHumanResourceSave!=null && (int)$newHumanResourceSave==0){
					$postedValues["CrpSpecializedtradeFinalId"]=$specializedtradeId;
					$instance = SpecializedfirmFinalModel::find($specializedtradeId);
					if(!(bool)$instance){
						SpecializedfirmHumanResourceModel::create($postedValues);
					}else{
						SpecializedfirmHumanResourceFinalModel::create($postedValues);
					}

				}else{
					SpecializedfirmHumanResourceModel::create($postedValues);
				}
				$appliedServiceCount=SpecializedfirmAppliedServiceModel::where('CrpSpecializedtradeId',$postedValues['CrpSpecializedtradeId'])->where('CmnServiceTypeId',CONST_SERVICETYPE_UPDATEHUMANRESOURCE)->count();
				if($appliedServiceCount==0){
					if(Input::has('UpdateHumanResourceService') && (int)$isServiceBySpecializedtrade==1){
						$appliedService = new SpecializedfirmAppliedServiceModel;
						$appliedService->CrpSpecializedtradeId=$specializedtradeId;
						$appliedService->CmnServiceTypeId = Input::get('UpdateHumanResourceService');
						$appliedService->save();
					}
				}
			}else{
				$save=false;
				if(isset($isEditByCDB) && (int)$isEditByCDB==1 && $newHumanResourceSave!=null && (int)$newHumanResourceSave==0){
					$instance=SpecializedfirmHumanResourceFinalModel::find($postedValues['Id']);
					if(!(bool)$instance){
						$redirectToEdit = true;
						$instance=SpecializedfirmHumanResourceModel::find($postedValues['Id']);
					}
				}else{
					$instance=SpecializedfirmHumanResourceModel::find($postedValues['Id']);
				}
				if($instance){
					$instance->fill($postedValues);
					$instance->update();
				}else{
					SpecializedfirmHumanResourceModel::create($postedValues);
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
							$destination=public_path().'/uploads/specializedfirm';
							$destinationDB='uploads/specializedfirm/'.$attachmentName;
							$multiAttachments1["DocumentName"]=isset($documentName[$count])?$documentName[$count]:'Specializedfirm Document';

							//CHECK IF IMAGE
							if(strpos($attachment->getClientMimeType(),'image/')>-1){
								$img = Image::make($attachment)->encode('jpg');
								$destinationDB = "uploads/specializedfirm/".str_random(15) . '_min_' .".jpg";
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
							$multiAttachments[$k]["CrpSpecializedtradeHumanResourceFinalId"]=$generatedId;
						}else{
							$multiAttachments[$k]["CrpSpecializedtradeHumanResourceId"]=$generatedId;
						}
					}else{
						if(isset($isEditByCDB) && (int)$isEditByCDB==1 && $newHumanResourceSave!=null  && (int)$newHumanResourceSave==0){
							$instance = SpecializedfirmFinalModel::find($specializedtradeId);
							if(!(bool)$instance){
								$multiAttachments[$k]["CrpSpecializedtradeHumanResourceId"]=$postedValues['Id'];
							}else{
								$multiAttachments[$k]["CrpSpecializedtradeHumanResourceFinalId"]=$postedValues['Id'];
							}
						}else{
							$multiAttachments[$k]["CrpSpecializedtradeHumanResourceId"]=$postedValues['Id'];
						}
					}

					//END
					if(isset($isEditByCDB) && (int)$isEditByCDB==1 && $newHumanResourceSave!=null && (int)$newHumanResourceSave==0){
						$instance = SpecializedfirmFinalModel::find($specializedtradeId);
						if(!(bool)$instance){
							$saveUploads=new SpecializedfirmHumanResourceAttachmentModel($multiAttachments[$k]);
						}else{
							$saveUploads=new SpecializedfirmHumanResourceAttachmentFinalModel($multiAttachments[$k]);
						}

					}else{

						$saveUploads=new SpecializedfirmHumanResourceAttachmentModel($multiAttachments[$k]);
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
					return Redirect::to($humanResourceEditPage.'/'.$postedValues["CrpSpecializedtradeId"].'?redirectUrl='.$redirectTo)->with('savedsuccessmessage','Human Resource has been successfully updated.');
				}
				return Redirect::to($humanResourceEditPage.'/'.$postedValues["CrpSpecializedtradeId"].'?redirectUrl='.$redirectTo.'&usercdb=edit')->with('savedsuccessmessage','Human Resource has been successfully updated.');
			}else{
				return Redirect::to($humanResourceEditPage.'/'.$postedValues["CrpSpecializedtradeId"].'?redirectUrl='.$redirectTo)->with('savedsuccessmessage','Human Resource has been successfully updated.');
			}
		}
		if($save){
			return Redirect::to('specializedfirm/humanresourceregistration');
		}else{
			return Redirect::to('specializedfirm/humanresourceregistration/'.$postedValues['CrpSpecializedtradeId'])->with('savedsuccessmessage','Human Resource has been successfully updated.');;
		}
	}
	

	//Equipment edit and register begins from here

	public function equipmentRegistrationEdit($specializedtrade=null){
		$afterSaveRedirect=1;
		$serviceBySpecializedtrade=0;
		$newEquipmentSave=0;
		$isEditByCDBUser=Input::get('usercdb');
		$editPage='specializedfirm/editequipment';
		$redirectUrl=Input::get('redirectUrl');
		$equipmentEditRoute='specializedfirm/editequipment';
		$equipmentEdit=array(new SpecializedfirmEquipmentModel());
		$equipmentAttachments=array();
		$equipmentId=Input::get('equipmentid');
		if(!empty($equipmentId) && strlen($equipmentId)==36){
			if(!Input::has('usercdb')){
				$equipmentEdit=SpecializedfirmEquipmentModel::specializedtradeEquipmentHardListSingle($equipmentId)->get(array('Id','CmnEquipmentId','RegistrationNo','ModelNo','Quantity'));
				$equipmentAttachments=SpecializedfirmEquipmentAttachmentModel::equipmentAttachment($equipmentId)->get(array('Id','DocumentName','DocumentPath'));
			}
			if(Input::has('usercdb')){
				$equipmentEdit=SpecializedfirmEquipmentFinalModel::specializedtradeEquipmentHardListSingle($equipmentId)->get(array('Id','CmnEquipmentId','RegistrationNo','ModelNo','Quantity'));
				$equipmentAttachments=SpecializedfirmEquipmentAttachmentFinalModel::equipmentAttachment($equipmentId)->get(array('Id','DocumentName','DocumentPath'));
			}
		}
		$equipments=CmnEquipmentModel::equipment()->whereNotNull('Code')->get(array('Id','Name','Code','IsRegistered','VehicleType'));
		if((bool)$specializedtrade!=null && !Input::has('usercdb')){
			$changeModel = false;
			$specializedtradeEquipments=SpecializedfirmEquipmentModel::specializedtradeEquipment($specializedtrade)->get(array('crpspecializedtradeequipment.Id','crpspecializedtradeequipment.RegistrationNo','crpspecializedtradeequipment.ModelNo','crpspecializedtradeequipment.Quantity','T1.Name'));
			$equipmentsAttachments=SpecializedfirmEquipmentModel::equipmentAttachments($specializedtrade)->get(array('T1.Id','T1.CrpSpecializedtradeEquipmentId','T1.DocumentName','T1.DocumentPath'));
		}
		if((bool)$specializedtrade!=null && Input::has('usercdb')){
			$changeModel = true;
			$specializedtradeEquipments=SpecializedfirmEquipmentFinalModel::specializedtradeEquipment($specializedtrade)->get(array('crpspecializedtradeequipmentfinal.Id','crpspecializedtradeequipmentfinal.RegistrationNo','crpspecializedtradeequipmentfinal.ModelNo','crpspecializedtradeequipmentfinal.Quantity','T1.Name'));
			$equipmentsAttachments=SpecializedfirmEquipmentAttachmentFinalModel::singleSpecializedtradeEquipmentAllAttachments($specializedtrade)->get(array('crpspecializedtradeequipmentattachmentfinal.DocumentName','crpspecializedtradeequipmentattachmentfinal.DocumentPath','crpspecializedtradeequipmentattachmentfinal.CrpSpecializedtradeEquipmentFinalId as CrpSpecializedtradeEquipmentId'));
			$specializedtradeInFinalTable = DB::table('crpspecializedfirmfinal')->where('Id',$specializedtrade)->count();
			if($specializedtradeInFinalTable == 0){
				$specializedtradeEquipments=SpecializedfirmEquipmentModel::specializedtradeEquipment($specializedtrade)->get(array('crpspecializedtradeequipment.Id','crpspecializedtradeequipment.RegistrationNo','crpspecializedtradeequipment.ModelNo','crpspecializedtradeequipment.Quantity','T1.Name'));
				$equipmentsAttachments=SpecializedfirmEquipmentModel::equipmentAttachments($specializedtrade)->get(array('T1.Id','T1.CrpSpecializedtradeEquipmentId','T1.DocumentName','T1.DocumentPath'));
			}
		}
		return View::make('crps.specializedfirmeditequipment')
			->with('changeModel',$changeModel)
			->with('afterSaveRedirect',$afterSaveRedirect)
			->with('serviceBySpecializedtrade',$serviceBySpecializedtrade)
			->with('newEquipmentSave',$newEquipmentSave)
			->with('isEditByCDBUser',$isEditByCDBUser)
			->with('equipmentEditRoute',$equipmentEditRoute)
			->with('redirectUrl',$redirectUrl)
			->with('isEdit',$specializedtrade)
			->with('editPage',$editPage)
			->with('specializedtradeId',$specializedtrade)
			->with('equipments',$equipments)
			->with('specializedtradeEquipments',$specializedtradeEquipments)
			->with('equipmentsAttachments',$equipmentsAttachments)
			->with('equipmentEdit',$equipmentEdit)
			->with('equipmentAttachments',$equipmentAttachments);
	}


	public function saveEquipment(){
		$save=true;
		$postedValues=Input::all();
		$hasCDBEdit=Input::get('HasCDBEdit');
		$specializedtradeId=Input::get('CrpSpecializedtradeId');
		$applicationDate = DB::table('crpspecializedfirm')->where('Id',$specializedtradeId)->pluck('ApplicationDate');
		//if(!empty($postedValues['Id']))
		//	DB::table('crpcspecializedtradeequipmentattachment')->where('CrpSpecializedtradeEquipmentId',$postedValues['Id'])->where('CreatedOn','<',$applicationDate)->delete();
		$isServiceBySpecializedtrade=Input::get('ServiceBySpecializedtrade');
		$newEquipmentSave=Input::get('NewEquipmentSave');
		$isEditByCDB=Input::get('EditByCDB');
		$redirectTo=Input::get('PostBackUrl');
		$multiAttachments=array();
		$uuid=DB::select("select uuid() as Id");
		$generatedId=$uuid[0]->Id;
		$validation = new SpecializedfirmEquipmentModel;
		$redirectToEdit = false;
		if(!($validation->validate($postedValues))){
			$errors = $validation->errors();
			if(empty($postedValues["Id"])){
				return Redirect::to('specializedfirm/equipmentregistration')->withInput()->withErrors($errors);
			}else{
				return Redirect::to('specializedfirm/equipmentregistration/'.$postedValues['CrpSpecializedtradeId'])->withInput()->withErrors($errors);
			}
		}
		DB::beginTransaction();
		try{
			if(empty($postedValues["Id"])){
				$postedValues["Id"]=$generatedId;
				if(isset($isEditByCDB) && (int)$isEditByCDB==1 && $newEquipmentSave!=null && (int)$newEquipmentSave==0){
					$initialTableCount = DB::table('crpspecializedfirm')->where('Id',$specializedtradeId)->count();
					$finalTableCount = DB::table('crpspecializedfirmfinal')->where('Id',$specializedtradeId)->count();

					if($initialTableCount==1 && $finalTableCount == 0){
						$postedValues["CrpSpecializedtradeId"]=$specializedtradeId;
						SpecializedfirmEquipmentModel::create($postedValues);
					}else{
						$postedValues["CrpSpecializedtradeFinalId"]=$specializedtradeId;
						SpecializedfirmEquipmentFinalModel::create($postedValues);
					}

				}else{
					SpecializedfirmEquipmentModel::create($postedValues);
				}
				$appliedServiceCount=SpecializedfirmAppliedServiceModel::where('CrpSpecializedtradeId',$postedValues['CrpSpecializedtradeId'])->where('CmnServiceTypeId',CONST_SERVICETYPE_UPDATEEQUIPMENT)->count();
				if($appliedServiceCount==0){
					if(Input::has('UpdateEquipmentService') && (int)$isServiceBySpecializedtrade==1){
						$appliedService = new SpecializedfirmAppliedServiceModel;
						$appliedService->CrpSpecializedtradeId=$specializedtradeId;
						$appliedService->CmnServiceTypeId = Input::get('UpdateEquipmentService');
						$appliedService->save();
					}
				}
			}else{
				$save=false;
				if(isset($isEditByCDB) && (int)$isEditByCDB==1 && $newEquipmentSave!=null && (int)$newEquipmentSave==0){
					$instance=SpecializedfirmEquipmentFinalModel::find($postedValues['Id']);
					if(!(bool)$instance){
						$redirectToEdit = true;
						$instance=SpecializedfirmEquipmentModel::find($postedValues['Id']);
					}
				}else{
					$instance=SpecializedfirmEquipmentModel::find($postedValues['Id']);
				}
				if($instance){
					$instance->fill($postedValues);
					$instance->update();
				}else{
					SpecializedfirmEquipmentModel::create($postedValues);
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
						$destination=public_path().'/uploads/specializedfirm';
						$destinationDB='uploads/specializedfirm/'.$attachmentName;
						$multiAttachments1["DocumentName"]=$documentName[$count];

						//CHECK IF IMAGE
						if(strpos($attachment->getClientMimeType(),'image/')>-1){
							$img = Image::make($attachment)->encode('jpg');
							$destinationDB = "uploads/specializedfirm/".str_random(15) . '_min_' .".jpg";
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
							$multiAttachments[$k]["CrpSpecializedtradeEquipmentFinalId"]=$generatedId;
						}else{
							$multiAttachments[$k]["CrpSpecializedtradeEquipmentId"]=$generatedId;
						}
					}else{
						if(isset($isEditByCDB) && (int)$isEditByCDB==1 && $newEquipmentSave!=null && (int)$newEquipmentSave==0){
							$multiAttachments[$k]["CrpSpecializedtradeEquipmentFinalId"]=$postedValues['Id'];
						}else{
							$multiAttachments[$k]["CrpSpecializedtradeEquipmentId"]=$postedValues['Id'];
						}
					}
					if(isset($isEditByCDB) && (int)$isEditByCDB==1 && $newEquipmentSave!=null && (int)$newEquipmentSave==0){
						$finalTableCount = DB::table('crpspecializedfirmfinal')->where('Id',$specializedtradeId)->count();
						$initialTableCount = DB::table('crpspecializedfirm')->where('Id',$specializedtradeId)->count();
						if($initialTableCount==1 && $finalTableCount == 0){
							$multiAttachments[$k]["CrpSpecializedtradeEquipmentId"]=$postedValues['Id'];
							unset($multiAttachments[$k]["CrpSpecializedtradeEquipmentFinalId"]);
							$saveUploads=new SpecializedfirmEquipmentAttachmentModel($multiAttachments[$k]);
						}else{
							$multiAttachments[$k]["CrpSpecializedtradeEquipmentId"]=$postedValues['Id'];
							$saveUploads=new SpecializedfirmEquipmentAttachmentFinalModel($multiAttachments[$k]);
						}
					}else{
						$saveUploads=new SpecializedfirmEquipmentAttachmentModel($multiAttachments[$k]);
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
					return Redirect::to($equipmentEditPage.'/'.$postedValues["CrpSpecializedtradeId"].'?redirectUrl='.$redirectTo)->with('savedsuccessmessage','Equipment has been successfully updated.');
				}
				return Redirect::to($equipmentEditPage.'/'.$postedValues["CrpSpecializedtradeId"].'?redirectUrl='.$redirectTo.'&usercdb=edit')->with('savedsuccessmessage','Equipment has been successfully updated.');
			}else{
				return Redirect::to($equipmentEditPage.'/'.$postedValues["CrpSpecializedtradeId"].'?redirectUrl='.$redirectTo)->with('savedsuccessmessage','Equipment has been successfully updated.');
			}
		}
		if($save){
			return Redirect::to('specializedfirm/equipmentregistration');
		}else{
			return Redirect::to('specializedfirm/equipmentregistration/'.$postedValues['CrpSpecializedtradeId'])->with('savedsuccessmessage','Equipment has been successfully updated.');
		}
	}

	//work classification begins from here

	public function workClassificationRegistration($specializedTrade=null){
		$isRejectedApp=0;
		$isServiceBySpecializedTrade=0;
		$isEditByCDB=0;
		$newGeneralInfoSave=1;
		$redirectUrl=Input::get('redirectUrl');
		$view="crps.specializedfirmregistration";
		$categories=SpecializedTradeCategoryModel::category()->select(DB::raw('Id as CategoryId,Code,Name,NULL as CmnAppliedCategoryId'))->get();
		$specializedtradeRegistration=array(new SpecializedfirmModel());
		$specializedtradeRegistrationAttachments=array();
		$editWorkClassificationsByCDB=array();
		if((bool)$specializedTrade!=null && !Input::has('usercdb')){
			if(Input::has('editbyapplicant')){
				$view="crps.specializedfirmregistration";
				if(Input::has('rejectedapplicationreapply')){
					$isRejectedApp=1;
				}
			}else{
				$editWorkClassificationsByCDB=DB::select("select distinct T1.Id as CategoryId,T1.Code,T1.Name,T2.CmnAppliedCategoryId,T3.CmnVerifiedCategoryId,T4.CmnApprovedCategoryId from cmnspecializedtradecategory T1 left join crpspecializedfirmworkclassification T2 on T1.Id=T2.CmnAppliedCategoryId and T2.CrpSpecializedTradeId=? left join crpspecializedfirmworkclassification T3 on T1.Id=T3.CmnVerifiedCategoryId and T3.CrpSpecializedTradeId=? left join crpspecializedfirmworkclassification T4 on T1.Id=T4.CmnApprovedCategoryId and T4.CrpSpecializedTradeId=? where T1.Code like '%SF%' order by T1.Code,T1.Name",array($specializedTrade,$specializedTrade,$specializedTrade));
				$view="crps.specializedfirmeditregistrationinfo";
			}
			$editWorkClassificationByCDB=array();
			$categories=DB::select("select T1.Id as CategoryId,T1.Code,T1.Name,T2.CmnAppliedCategoryId from cmnspecializedtradecategory T1 left join crpspecializedfirmworkclassification T2 on T1.Id=T2.CmnAppliedCategoryId and T2.CrpSpecializedTradeId=? order by T1.Code,T1.Name",array($specializedTrade));
			$specializedtradeRegistration=SpecializedfirmModel::specializedtradeHardList($specializedTrade)->get();
			$specializedtradeRegistrationAttachments=SpecializedTradeAttachmentModel::attachment($specializedTrade)->get(array('Id','DocumentName','DocumentPath'));
		}
		if((bool)$specializedTrade!=null && Input::has('usercdb')){
			$isEditByCDB=1;
			$newGeneralInfoSave=0;
			$view="crps.specializedfirmeditregistrationinfo";
			$specializedtradeRegistration=SpecializedfirmFinalModel::specializedtradeHardList($specializedTrade)->get();
			$specializedtradeRegistrationAttachments=SpecializedTradeAttachmentFinalModel::attachment($specializedTrade)->get(array('Id','DocumentName','DocumentPath'));
			$editWorkClassificationsByCDB=DB::select("select distinct T1.Id as CategoryId,T1.Code,T1.Name,T2.CmnAppliedCategoryId,T3.CmnVerifiedCategoryId,T4.CmnApprovedCategoryId from cmnspecializedtradecategory T1 left join crpspecializedfirmworkclassificationfinal T2 on T1.Id=T2.CmnAppliedCategoryId and T2.CrpSpecializedTradeFinalId=? left join crpspecializedfirmworkclassificationfinal T3 on T1.Id=T3.CmnVerifiedCategoryId and T3.CrpSpecializedTradeFinalId=? left join crpspecializedfirmworkclassificationfinal T4 on T1.Id=T4.CmnApprovedCategoryId and T4.CrpSpecializedTradeFinalId=? where T1.Code like '%SF%' order by T1.Code,T1.Name",array($specializedTrade,$specializedTrade,$specializedTrade));
		}
		$applicationReferenceNo=$this->tableTransactionNo('SpecializedfirmModel','ReferenceNo');
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
	public function saveWorkClassification(){
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
	
		DB::beginTransaction();
		try{
			if(empty($postedValues["Id"])){
				$postedValues["Id"]=$generatedId;
				SpecializedTradeModel::create($postedValues);
				if((int)$serviceBySpecializedTrade==1){
					$appliedServiceRenewal = new SpecializedfirmAppliedServiceModel;
	        		$appliedServiceRenewal->CrpSpecializedTradeId=$generatedId;
				    $appliedServiceRenewal->CmnServiceTypeId = CONST_SERVICETYPE_RENEWAL;
				    $appliedServiceRenewal->save();
				    $countRenewalApplications=SpecializedfirmAppliedServiceModel::serviceRenewalCount($generatedId)->count();
				    if($countRenewalApplications>=1){
				    	$lateRenewalExpiryDate=SpecializedfirmFinalModel::specializedTradeHardList($postedValues['CrpSpecializedTradeId'])->pluck('RegistrationExpiryDate');
					    $lateRenewalExpiryDate=strtotime($lateRenewalExpiryDate);
					    $currentDate=strtotime(date('Y-m-d'));
					    if($currentDate>$lateRenewalExpiryDate){
					    	$appliedServiceRenewalLateFee = new SpecializedfirmAppliedServiceModel;
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
					$specializedTradeReference= new SpecializedfirmFinalModel();
					SpecializedfirmWorkClassificationFinalModel::where('CrpSpecializedTradeFinalId',$postedValues['Id'])->delete();
				}else{
					$specializedTradeReference= new SpecializedfirmModel();
					SpecializedfirmWorkClassificationModel::where('CrpSpecializedTradeId',$postedValues['Id'])->delete();
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
						$category = new SpecializedfirmWorkClassificationFinalModel;
						$category->CrpSpecializedTradeFinalId=$generatedId;
					}else{
						$category = new SpecializedfirmWorkClassificationModel;
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
						$category = new SpecializedfirmWorkClassificationFinalModel;
						$category->CrpSpecializedTradeFinalId=$generatedId;
					}else{
						$category = new SpecializedfirmWorkClassificationModel;
						$category->CrpSpecializedTradeId=$generatedId;
					}
				    $category->CmnAppliedCategoryId = $postedValues['CmnAppliedCategoryId'][$idx];
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
			if((int)$serviceBySpecializedTrade==1){
				return Redirect::to('specializedfirm/applyrenewalconfirmation/'.$postedValues["Id"]);			
			}
			return Redirect::to($redirectTo.'/'.$postedValues["Id"])->with('savedsuccessmessage','Registration Information has been successfully updated.');
		}
		if($save){
			Session::put('SpecializedTradeRegistrationId',$generatedId);
			return Redirect::to('specializedfirm/confirmregistration');
		}else{
			if(isset($isRejectedApp) && (int)$isRejectedApp==1){
				Session::put('SpecializedTradeRegistrationId',$postedValues["Id"]);
				return Redirect::to('specializedfirm/confirmregistration');
			}
			return Redirect::to('specializedfirm/confirmregistration')->with('savedsuccessmessage','Registration Information has been successfully updated.');
		}
	}
	

	//comments and adverse begins from here

	public function newCommentAdverseRecord($specializedtradeId){
		$specializedtrade=SpecializedfirmFinalModel::specializedtradeHardList($specializedtradeId)->get(array('Id','SPNo','NameOfFirm'));
		return View::make('crps.specializedfirmnewadverserecordsandcomments')
					->with('specializedtradeId',$specializedtradeId)
					->with('specializedtrade',$specializedtrade);	
	}
	public function editCommentAdverseRecord($specializedtradeId){
		$specializedtrade=SpecializedfirmFinalModel::specializedtradeHardList($specializedtradeId)->get(array('Id','SPNo','NameOfFirm'));
		$commentsAdverseRecords=SpecializedfirmCommentAdverseRecordModel::commentAdverseRecordList($specializedtradeId)->get(array('Id','Date','Remarks','Type'));
		return View::make('crps.specializedfirmeditadverserecordscomments')
					->with('specializedtrade',$specializedtrade)
					->with('commentsAdverseRecords',$commentsAdverseRecords);
	}
	public function saveCommentAdverseRecord(){
		$postedValues=Input::all();
		$date=$this->convertDate(Input::get('Date'));
		$postedValues['Date']=$date;
		$postedValues['CreatedBy'] = Auth::user()->Id;
		$validation = new SpecializedfirmCommentAdverseRecordModel;
		if(!($validation->validate($postedValues))){
		    $errors = $validation->errors();
		    return Redirect::to('specializedfirm/editdetails/'.$postedValues['CrpSpecializedtradeFinalId'].'#commentsadverserecords')->withErrors($errors)->withInput();
		}
		SpecializedfirmCommentAdverseRecordModel::create($postedValues);
		return Redirect::to('specializedfirm/editdetails/'.$postedValues['CrpSpecializedtradeFinalId'].'#commentsadverserecords')->with('savedsuccessmessage','Record sucessfully added.');
	}
	public function updateCommentAdverseRecord(){
		$postedValues=Input::all();
		$date=$this->convertDate(Input::get('Date'));
		$postedValues['Date']=$date;
		$instance=SpecializedfirmCommentAdverseRecordModel::find($postedValues['Id']);
		$instance->fill($postedValues);
		$instance->update();
		return Redirect::to('specializedfirm/editdetails/'.$postedValues['CrpSpecializedtradeFinalId'].'#commentsadverserecords')->with('savedsuccessmessage','Record has been successfully updated');
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
		$cdbNo=Input::get('SPNo');
		$query="select T1.Id,T1.NameOfWork,B.SPNo,B.NameOfFirm,T1.WorkOrderNo,T1.ContractPeriod,T1.WorkStartDate,T1.WorkCompletionDate,T2.Name as ProcuringAgency,T3.Name as WorkCategory,T5.Name as WorkExecutionStatus from crpbiddingform T1 left join (crpbiddingformdetail A join crpspecializedfirmfinal B on B.Id = A.CrpSpecializedtradeFinalId) on A.CrpBiddingFormId = T1.Id and A.CmnWorkExecutionStatusId = ? join cmnprocuringagency T2 on T1.CmnProcuringAgencyId=T2.Id join cmnspecializedtradecategory T3 on T1.CmnSpecializedfirmCategoryId=T3.Id  join cmnlistitem T5 on T1.CmnWorkExecutionStatusId=T5.Id where Type=2";
		array_push($parameters,CONST_CMN_WORKEXECUTIONSTATUS_AWARDED);
		if(Request::path()=="specializedfirm/editcompletedworklist"){
			$query.=" and (T1.CmnWorkExecutionStatusId=? or T1.CmnWorkExecutionStatusId=?)";
			array_push($parameters,CONST_CMN_WORKEXECUTIONSTATUS_COMPLETED);
			array_push($parameters,CONST_CMN_WORKEXECUTIONSTATUS_TERMINATED);
		}elseif(Request::path()=="specializedfirm/worklist" || Request::path()=="specializedfirm/editbiddingformlist"){
			$underProcess=1;
			$query.=" and (T1.CmnWorkExecutionStatusId=? or T1.CmnWorkExecutionStatusId = ?)";
			array_push($parameters,CONST_CMN_WORKEXECUTIONSTATUS_UNDERPROCESS);
			array_push($parameters,CONST_CMN_WORKEXECUTIONSTATUS_AWARDED);
		}else{
			App::abort('404');
		}
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
				$query.=" and B.SPNo = ?";
				array_push($parameters,$cdbNo);
			}
		}
		$listOfWorks=DB::select($query." order by ProcuringAgency,T1.WorkStartDate",$parameters);
		$procuringAgencies=ProcuringAgencyModel::procuringAgencyHardList()->get(array('Id','Name'));
		$workExecutionStatus=CmnListItemModel::workExecutionStatus()->whereRaw('(ReferenceNo=? or ReferenceNo=?)',array(3003,3004))->get(array('Id','Name'));
		return View::make('crps.specializedfirmlistofworks')
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
		$model="all/mworkcompletionform";
		$workExecutionStatus=CmnListItemModel::workExecutionStatus()->whereRaw('(ReferenceNo=? or ReferenceNo=? or ReferenceNo=?)',array(3003,3004,3005))->get(array('Id','Name','ReferenceNo'));
		$detailsOfCompletedWorks=CrpBiddingFormModel::workCompletionDetails($bidId)
			->get(array('ContractPriceInitial','ContractPriceFinal','CommencementDateOffcial','CommencementDateFinal','CompletionDateOffcial','CompletionDateFinal','OntimeCompletionScore','QualityOfExecutionScore','CmnWorkExecutionStatusId','Remarks'));
		$redirectRoute='specializedfirm/worklist';
		if(!empty($detailsOfCompletedWorks[0]->OntimeCompletionScore)){
			$redirectRoute='specializedfirm/editcompletedworklist';
		}
		$contractDetails=CrpBiddingFormModel::biddingFormSpecializedfirmCdbAll()
			->where('crpbiddingform.Id',$bidId)
//								->where('crpbiddingform.ByCDB',1)
			->get(array('crpbiddingform.Id','crpbiddingform.WorkOrderNo','crpbiddingform.NameOfWork','crpbiddingform.DescriptionOfWork','crpbiddingform.ContractPeriod','crpbiddingform.WorkStartDate','crpbiddingform.WorkCompletionDate','crpbiddingform.ApprovedAgencyEstimate','T1.Name as ProcuringAgency','T2.Name as ProjectCategory','T4.NameEn as Dzongkhag'));
		$workAwardedSpecializedfirm=CrpBiddingFormDetailModel::biddingFormSpecializedfirmContractBidders($bidId)
			->where('crpbiddingformdetail.CmnWorkExecutionStatusId',CONST_CMN_WORKEXECUTIONSTATUS_AWARDED)
			->get(array('T1.SPNo','crpbiddingformdetail.Id','crpbiddingformdetail.BidAmount','crpbiddingformdetail.EvaluatedAmount','T1.NameOfFirm'));
		return View::make('crps.specializedfirmworkcompletionform')
			->with('model',$model)
			->with('redirectRoute',$redirectRoute)
			->with('detailsOfCompletedWorks',$detailsOfCompletedWorks)
			->with('workExecutionStatus',$workExecutionStatus)
			->with('contractDetails',$contractDetails)
			->with('workAwardedSpecializedfirm',$workAwardedSpecializedfirm);
	}
}
