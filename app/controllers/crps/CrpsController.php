<?php
class CrpsController extends BaseController{
	public function checkEmailAvailability(){
		$flagEmail=true;
		$email=Input::get('Email');
		$emailCountContractor=ContractorModel::contractorHardListAll()->where('Email',$email)->count('Id');
		/*--------------------------------------------------------------------------------------------------------*/
		$emailCountConsultant=ConsultantModel::consultantHardListAll()->where('Email',$email)->count('Id');
		/*--------------------------------------------------------------------------------------------------------*/
		//$emailCountArchitect=ArchitectModel::architectHardListAll()->where('Email',$email)->count();
		$emailCountArchitect=DB::table('crparchitect')->where('Email',$email)->count('Id');
		/*---------------------------------------------------------------------------------------------------------*/
		$emailCountEngineer=EngineerModel::engineerHardListAll()->where('Email',$email)->count('Id');
		/*---------------------------------------------------------------------------------------------------------*/
		$emailCountSpecializedTrade=SpecializedTradeModel::specializedTradeHardListAll()->where('Email',$email)->count('Id');
		$usernameCountUser=User::where('username',$email)->count();
		$emailCountUser=User::where('Email',$email)->count();
		if($emailCountUser>0 || $usernameCountUser>0 || $emailCountContractor>0 || $emailCountConsultant>0 || $emailCountArchitect>0 || $emailCountEngineer>0 || $emailCountSpecializedTrade>0){
			$flagEmail=false;
		}
		return json_encode(array(
//    		'valid' => $flagEmail,
			'valid' => true
		));
	}
	public function changeExpiryDateApplicants(){
		$id = Input::get('Id');
		$model = Input::get('Model');
		$postedValues['RegistrationExpiryDate'] = $this->convertDate(Input::get('RegistrationExpiryDate'));
		DB::beginTransaction();
		try{
			$object = $model::find($id);
			$object->fill($postedValues);
			$object->update();
		}catch(Exception $e){
			DB::rollBack();
			return Redirect::to(URL::to(Input::get('RedirectUrl')))->with('customerrormessage',$e->getMessage());
		}
		DB::commit();
		return Redirect::to(URL::to(Input::get('RedirectUrl')))->with('savedsuccessmessage',"Record has been updated");
	}
	public function sendDeleteRequest(){
		$id = Input::get('id');
		$type = Input::get('type');
		$deleted = Input::get('deleted');
		switch((int)$type){
			case 1:
				DB::table("crpcontractorhumanresourcefinal")->where('Id',$id)->update(array('DeleteRequest'=>(int)$deleted));
				DB::table("crpconsultanthumanresourcefinal")->where('Id',$id)->update(array('DeleteRequest'=>(int)$deleted));
				break;
			case 2:
				DB::table("crpcontractorequipmentfinal")->where('Id',$id)->update(array('DeleteRequest'=>(int)$deleted));
				DB::table("crpconsultantequipmentfinal")->where('Id',$id)->update(array('DeleteRequest'=>(int)$deleted));
				break;
			default:
				break;
		}
	}

	public function viewHRandEqForWorkid(){
		$type = Input::get('type');
		if($type == 1){
			$contractorId = contractorFinalId();
			$workId = Input::get('workid');
			$tenderDetails = array();
			if(Input::has('workid')) {
				$tenderDetails = DB::table('etltender as T1')
					->join('cmncontractorclassification as T2', 'T1.CmnContractorClassificationId', '=', 'T2.Id')
					->join('cmncontractorworkcategory as T3', 'T1.CmnContractorCategoryId', '=', 'T3.Id')
					->join('cmnlistitem as A', 'A.Id', '=', 'T1.CmnWorkExecutionStatusId')
					->join('cmnprocuringagency as T5', 'T5.Id', '=', 'T1.CmnProcuringAgencyId')
					->leftJoin('cmndzongkhag as T7', 'T1.CmnDzongkhagId', '=', 'T7.Id')
//					->where(DB::raw("concat(T5.Code,'/',year(T1.DateOfClosingSaleOfTender),'/',T1.WorkId)"), $workId)
					->whereRaw("case when T1.migratedworkid is null then concat(T5.Code,'/',year(T1.UploadedDate),'/',T1.WorkId) else T1.migratedworkid end = '$workId'")
					->whereIn('T2.ReferenceNo', array(1, 2))
					->where('A.ReferenceNo', 3001)
					->limit(1)
					->get(array(DB::raw('distinct(T1.Id) as TenderId'), DB::raw("concat(T5.Code,'/',DATE_FORMAT(T1.DateOfSaleOfTender,'%Y'),'/',T1.WorkId) as WorkId"), "T1.NameOfWork", "T2.Code as Classification", "T3.Code as Category", "T7.NameEn as Dzongkhag", "T1.ProjectEstimateCost", "T1.ContractPeriod",'T5.Name as ProcuringAgency','A.Name as Status','T1.CommencementDateFinal','T1.CompletionDateFinal'));
			}
			if(!$tenderDetails){
				return Redirect::to('contractor/mytrackrecords')->with('customerrormessage','This work has not been awarded yet or is already completed');
			}else{
				$tenderId = $tenderDetails[0]->TenderId;
				$qualifyingScore = DB::table('etlqualifyingscore')->pluck('QualifyingScore');
				$lowestBid = DB::table('etltenderbiddercontractor as T1')
					->join('etlevaluationscore as T2','T2.EtlTenderBidderContractorId','=','T1.Id')
					->where('T1.EtlTenderId','=',$tenderId)
					->min('T1.FinancialBidQuoted');
				$bidContractors = DB::table('etltenderbiddercontractor as T1')
					->join('etltenderbiddercontractordetail as T2','T2.EtlTenderBidderContractorId','=','T1.Id')
					->join('crpcontractorfinal as T3','T3.Id','=','T2.CrpContractorFinalId')
					->join('etlevaluationscore as T4','T4.EtlTenderBidderContractorId','=','T1.Id')
					->where('T1.EtlTenderId',$tenderId)
					->whereNotNull('T1.ActualStartDate')
					->orderBy(DB::raw('coalesce(T4.Score10,0)'),'DESC')
					->get(array(DB::raw('distinct T1.Id'),'T1.JointVenture'));
				$cdbNos = array();
				$contractorEquipments = array();
				$contractorHRs = array();
				$contractorScores = array();
				$contractorAmounts = array();
				foreach($bidContractors as $bidContractor):
					$cdbNos[$bidContractor->Id] = DB::table('crpcontractorfinal as T1')
						->join('etltenderbiddercontractordetail as T2','T2.CrpContractorFinalId','=','T1.Id')
						->where('T2.EtlTenderBidderContractorId',$bidContractor->Id)
						->get(array(DB::raw('group_concat(T1.CDBNo SEPARATOR ", ") as CDBNo')));
					$contractorEquipments[$bidContractor->Id] = DB::table('etlcontractorequipment as T1')
						->join('cmnequipment as T2','T2.Id','=','T1.CmnEquipmentId')
						->join('etltier as T3','T3.Id','=','T1.EtlTierId')
						->where('T1.EtlTenderBidderContractorId',$bidContractor->Id)
						->orderBy('T3.MaxPoints')
						->orderBy('T2.Name')
						->orderBy('T1.Points','DESC')
						->get(array('T1.Id','T1.RegistrationNo','T3.Name as Tier', 'T2.Name as Equipment','T1.Points','T1.OwnedOrHired'));
					$contractorHRs[$bidContractor->Id] = DB::table('etlcontractorhumanresource as T1')
						->join('cmnlistitem as T2','T2.Id','=','T1.CmnDesignationId')
						->join('etltier as T3','T3.Id','=','T1.EtlTierId')
						->leftJoin('crpcontractorhumanresourcefinal as A','A.CIDNo','=','T1.CIDNo')
						->where('T1.EtlTenderBidderContractorId',$bidContractor->Id)
						->orderBy('T3.MaxPoints')
						->orderBy('T2.Name')
						->orderBy('T1.Points','DESC')
						->get(array('T1.Id','T2.Name as Designation','T1.CIDNo','A.Name','T1.Qualification','T3.Name as Tier','T1.Points'));
					$contractorScores[$bidContractor->Id] = DB::table('etlevaluationscore')
						->where('EtlTenderBidderContractorId',$bidContractor->Id)
						->get(array('Score1','Score2','Score3','Score4','Score5','Score6','Score7','Score8','Score9','Score10'));

					$contractorAmounts[$bidContractor->Id] = DB::table('etltenderbiddercontractor as T1')
						->join('etltender as T2','T2.Id','=','T1.EtlTenderId')
						->where('T1.Id',$bidContractor->Id)
						->get(array('T1.FinancialBidQuoted',DB::raw('coalesce(T2.ContractPriceFinal,0) as ContractPriceFinal'),DB::raw('coalesce(T2.ContractPriceFinal,FinancialBidQuoted) as Amount')));
					$contractorStatuses[$bidContractor->Id] = DB::table('etltenderbiddercontractor as T1')
						->join('etltender as T2','T2.Id','=','T1.EtlTenderId')
						->join('cmnlistitem as T4','T4.Id','=','T2.CmnWorkExecutionStatusId')
						->where('T1.Id',$bidContractor->Id)
						->get(array('T1.AwardedAmount'));
				endforeach;
			}
//			echo "<pre>"; dd($contractorHRs);
			return View::make('crps.hrandeqdetails')
				->with('qualifyingScore',$qualifyingScore)
				->with('lowestBid',$lowestBid)
				->with('tenderDetails',$tenderDetails)
				->with('bidContractors',$bidContractors)
				->with('cdbNos',$cdbNos)
				->with('contractorEquipments',$contractorEquipments)
				->with('contractorHRs',$contractorHRs)
				->with('contractorScores',$contractorScores)
				->with('contractorAmounts',$contractorAmounts)
				->with('contractorStatuses',$contractorStatuses);
		}
	}
	public function sendApplicationBack(){
		$id = Input::get('Id');
		$model = Input::get('Model');
		$status = Input::get('Status');
		$redirectUrl = Input::get('RedirectUrl');
		$model::find($id)->update(array('CmnApplicationRegistrationStatusId'=>$status,'SysLockedByUserId'=>NULL));
		return Redirect::to($redirectUrl)->with('savedsuccessmessage','Application has been sent back to previous stage');
	}
	public function dropApplication(){
		$id = Input::get('id');
		$model = Input::get('model');
		$redirectUrl = Input::get('redirectUrl');

		$modelsWithNotification = array("ContractorModel","ConsultantModel","ArchitectModel","EngineerModel","SpecializedTradeModel");
		if(in_array($model,$modelsWithNotification)){
			$object = new $model;
			$table = $object->table;
			DB::table($table)->where('Id',$id)->update(array('SysLockedByUserId'=>NULL,'HasNotification'=>0));
		}else{
			$model::find($id)->update(array("SysLockedByUserId"=>NULL));
		}
		if(strpos($model,"Cancel")>-1){
			$statusId = $model::where('Id',$id)->pluck('CmnApplicationStatusId');
		}else{
			$statusId = $model::where('Id',$id)->pluck('CmnApplicationRegistrationStatusId');
		}


		DB::table('sysapplicationnotification')->where('ApplicationId',$id)->where('CmnApplicationStatusId',$statusId)->delete();
		return Redirect::to($redirectUrl)->with('savedsuccessmessage','Application has been successfully dropped!');
	}
	public function getUnpaid($date){
		$contractorNewRegistration = DB::select("SELECT Id,NameOfFirm,CDBNo FROM `crpcontractor` WHERE CrpContractorId is null and `CmnApplicationRegistrationStatusId` LIKE '6195664d-c3c5-11e4-af9f-080027dcfac6' AND `RegistrationApprovedDate` <= '$date' ORDER BY `ApplicationDate` DESC");
		$contractorService = DB::select("SELECT Id,NameOfFirm,CDBNo FROM `crpcontractor` WHERE CrpContractorId is not null and `CmnApplicationRegistrationStatusId` LIKE '6195664d-c3c5-11e4-af9f-080027dcfac6' AND `RegistrationApprovedDate` <= '$date' ORDER BY `ApplicationDate` DESC");

		$consultantNewRegistration = DB::select("SELECT Id,NameOfFirm,CDBNo FROM `crpconsultant` WHERE CrpConsultantId is null and `CmnApplicationRegistrationStatusId` LIKE '6195664d-c3c5-11e4-af9f-080027dcfac6' AND `RegistrationApprovedDate` <= '$date' ORDER BY `ApplicationDate` DESC");
		$consultantService = DB::select("SELECT Id,NameOfFirm,CDBNo FROM `crpconsultant` WHERE CrpConsultantId is not null and `CmnApplicationRegistrationStatusId` LIKE '6195664d-c3c5-11e4-af9f-080027dcfac6' AND `RegistrationApprovedDate` <= '$date' ORDER BY `ApplicationDate` DESC");

		$architectNewRegistration = DB::select("SELECT Id,Name,ARNo FROM `crparchitect` WHERE CrpArchitectId is null and `CmnApplicationRegistrationStatusId` LIKE '6195664d-c3c5-11e4-af9f-080027dcfac6' AND `RegistrationApprovedDate` <= '$date' ORDER BY `ApplicationDate` DESC");
		$architectService = DB::select("SELECT Id,Name,ArNo FROM `crparchitect` WHERE CrpArchitectId is not null and `CmnApplicationRegistrationStatusId` LIKE '6195664d-c3c5-11e4-af9f-080027dcfac6' AND `RegistrationApprovedDate` <= '$date' ORDER BY `ApplicationDate` DESC");
		$generalInformationConsultant = array();
		$hasFeeConsultant = array();
		$hasLateFeeConsultant = array();
		$hasLatefeeAmountConsultant = array();
		$hasRenewalConsultant = array();
		$existingCategoryServicesArray = array();
		$appliedCategoryServicesArray = array();
		$verifiedCategoryServicesArray = array();
		$approvedCategoryServicesArray = array();
		$hasChangeInCategoryClassification = array();
		//CONTRACTOR REGISTRATION

		foreach($contractorNewRegistration as $contractorNew):
			$feeStructuresContractorNew[$contractorNew->Id]=DB::select("select T1.Code as CategoryCode,T1.Id as CategoryId,T1.Name as Category,T2.Id as AppliedClassificationId,T2.Code as AppliedClassificationCode,T2.Name as AppliedClassification,T2.RegistrationFee as AppliedRegistrationFee,T3.Id as VerifiedClassificationId,T3.Code as VerifiedClassificationCode,T3.Name as VerifiedClassification,T3.RegistrationFee as VerifiedRegistrationFee,T4.Id as ApprovedClassificationId,T4.Code as ApprovedClassificationCode,T4.Name as ApprovedClassification,T4.RegistrationFee as ApprovedRegistrationFee from cmncontractorworkcategory T1 join crpcontractorworkclassification X on T1.Id=X.CmnProjectCategoryId join cmncontractorclassification T2 on T2.Id=X.CmnAppliedClassificationId left join cmncontractorclassification T3 on T3.Id=X.CmnVerifiedClassificationId left join cmncontractorclassification T4 on T4.Id=X.CmnApprovedClassificationId where X.CrpContractorId=? order by T1.Code,T1.Name",array($contractorNew->Id));
			$registrationValidityYears[$contractorNew->Id]=CrpService::registrationValidityYear(CONST_SERVICETYPE_NEW)->pluck('ContractorValidity');
		endforeach;
		//END
		//CONTRACTOR SERVICE
		foreach($contractorService as $contractorServ):
			$class[$contractorServ->Id]=ContractorClassificationModel::classification()->select(DB::raw('Id,Name,coalesce(ReferenceNo,88888888) as ReferenceNo'))->get();
			$hasFee[$contractorServ->Id]=false;
			$hasRenewal[$contractorServ->Id]=false;
			$hasLateFee[$contractorServ->Id]=false;
			$hasChangeInCategoryClassification[$contractorServ->Id]=false;
			$hasCategoryClassificationsFee[$contractorServ->Id]=array();
			$hasLateFeeAmount[$contractorServ->Id]=array();
			$contractorFinalTableId=contractorModelContractorId($contractorServ->Id);
			$maxClassification[$contractorServ->Id] = DB::table('viewcontractormaxclassification')->where('CrpContractorFInalId',$contractorFinalTableId)->pluck('MaxClassificationPriority');
			$appliedServices[$contractorServ->Id]=ContractorAppliedServiceModel::appliedService($contractorServ->Id)->get(array('T1.Id','T1.Name as ServiceName','T1.HasFee','T1.ContractorAmount'));

			$generalInformation[$contractorServ->Id]=ContractorModel::contractor($contractorServ->Id)->get(array('crpcontractor.Id',DB::raw('coalesce(crpcontractor.WaiveOffLateFee,0) as WaiveOffLateFee'),'crpcontractor.NewLateFeeAmount','crpcontractor.PaymentReceiptNo', 'crpcontractor.PaymentReceiptDate','crpcontractor.Id','crpcontractor.ReferenceNo','crpcontractor.ApplicationDate','crpcontractor.CDBNo','crpcontractor.NameOfFirm','crpcontractor.RegisteredAddress','crpcontractor.Village','crpcontractor.Gewog','crpcontractor.Address','crpcontractor.Email','crpcontractor.TelephoneNo','crpcontractor.MobileNo','crpcontractor.FaxNo','crpcontractor.CmnApplicationRegistrationStatusId','crpcontractor.RegistrationVerifiedDate','crpcontractor.ChangeOfOwnershipRemarks','crpcontractor.RemarksByVerifier','crpcontractor.RemarksByApprover','crpcontractor.RegistrationApprovedDate','crpcontractor.RemarksByPaymentApprover','crpcontractor.RegistrationPaymentApprovedDate','T1.Name as Country','T2.NameEn as Dzongkhag','T4.FullName as Verifier','T5.FullName as Approver','T6.FullName as PaymentApprover','T7.Name as OwnershipType','T8.NameEn as RegisteredDzongkhag'));
			$generalInformationFinal[$contractorServ->Id]=ContractorFinalModel::contractor($contractorFinalTableId)->get(array('crpcontractorfinal.Id','crpcontractorfinal.RegistrationApprovedDate','crpcontractorfinal.RegistrationExpiryDate','crpcontractorfinal.CDBNo','crpcontractorfinal.NameOfFirm','crpcontractorfinal.RegisteredAddress','crpcontractorfinal.Village','crpcontractorfinal.Gewog','crpcontractorfinal.Address','crpcontractorfinal.Email','crpcontractorfinal.TelephoneNo','crpcontractorfinal.MobileNo','crpcontractorfinal.FaxNo','crpcontractorfinal.CmnApplicationRegistrationStatusId','crpcontractorfinal.RegistrationExpiryDate','T1.Name as Country','T2.NameEn as Dzongkhag','T3.Name as CurrentStatus','T4.Name as OwnershipType','T5.NameEn as RegisteredDzongkhag'));

			foreach($appliedServices[$contractorServ->Id] as $appliedService){
				if((int)$appliedService->HasFee==1){
					if($appliedService->Id == CONST_SERVICETYPE_INCORPORATION):
						if($generalInformation[$contractorServ->Id][0]->OwnershipType != $generalInformationFinal[$contractorServ->Id][0]->OwnershipType):
							$hasFee[$contractorServ->Id]=true;
						endif;
					else:
						$hasFee[$contractorServ->Id]=true;
					endif;

				}
				if((int)$appliedService->Id==CONST_SERVICETYPE_RENEWAL){
					$hasRenewal[$contractorServ->Id]=true;
				}
				if((int)$appliedService->Id==CONST_SERVICETYPE_LATEFEE){
					$hasLateFee[$contractorServ->Id]=true;
				}
				if((int)$appliedService->Id==CONST_SERVICETYPE_CHANGEOFCATEGORYCLASSIFICATION){
					$hasChangeInCategoryClassification[$contractorServ->Id]=true;
				}
			}
			if($hasRenewal || $hasChangeInCategoryClassification){
				$hasCategoryClassificationsFee[$contractorServ->Id]=DB::select("select T1.Id as MasterCategoryId,T1.Code as MasterCategoryCode,T1.Name as MasterCategoryName,T2.Id as AppliedClassificationId,T2.Code as AppliedClassification,T2.RegistrationFee as AppliedRegistrationFee,T2.RenewalFee as AppliedRenewalFee,T2.Priority as AppliedClassificationPriority,T4.Id as VerifiedClassificationId,T4.Code as VerifiedClassification,T4.RegistrationFee as VerifiedRegistrationFee,T4.RenewalFee as VerifiedRenewalFee,T4.Priority as VerifiedClassificationPriority,T5.Id as ApprovedClassificationId,T5.Code as ApprovedClassification,T5.RegistrationFee as ApprovedRegistrationFee,T5.RenewalFee as ApprovedRenewalFee,T5.Priority as ApprovedClassificationPriority,T7.Id as FinalApprovedClassificationId,T7.Code as FinalApprovedClassification,T7.Priority as FinalClassificationPriority from cmncontractorworkcategory T1 left join crpcontractorworkclassification T3 on T1.Id=T3.CmnProjectCategoryId and T3.CrpContractorId=? left join crpcontractorworkclassificationfinal T6 on T1.Id=T6.CmnProjectCategoryId and T6.CrpContractorFinalId=? left join cmncontractorclassification T2 on T2.Id=T3.CmnAppliedClassificationId and T3.CrpContractorId=? left join cmncontractorclassification T4 on T4.Id=T3.CmnVerifiedClassificationId and T3.CrpContractorId=? left join cmncontractorclassification T5 on T5.Id=T3.CmnApprovedClassificationId and T3.CrpContractorId=? left join cmncontractorclassification T7 on T7.Id=T6.CmnApprovedClassificationId and T6.CrpContractorFinalId=? order by MasterCategoryCode",array($contractorServ->Id,$contractorFinalTableId,$contractorServ->Id,$contractorServ->Id,$contractorServ->Id,$contractorFinalTableId));
			}
			if($hasLateFee){
				$hasLateFeeAmount[$contractorServ->Id]=DB::select("select T1.ApplicationDate,T2.RegistrationExpiryDate,DATEDIFF(T1.ApplicationDate,T2.RegistrationExpiryDate) as PenaltyNoOfDays,100 as PenaltyLateFeeAmount from crpcontractor T1 join crpcontractorfinal T2 on T1.CrpContractorId=T2.Id where T1.Id=? LIMIT 1",array($contractorServ->Id));
			}
			$registrationValidityYears[$contractorServ->Id]=CrpService::registrationValidityYear(CONST_SERVICETYPE_NEW)->pluck('ContractorValidity');
		endforeach;
		//END

		//CONTRACTOR REGISTRATION

		foreach($consultantNewRegistration as $consultantNew):
			$serviceCategories=ConsultantServiceCategoryModel::category()->get(array('Id','Name'));
			$appliedCategoryServices[$consultantNew->Id]=DB::select("select T1.Id as ServiceId,T1.Code as ServiceCode,T1.Name as ServiceName,T1.CmnConsultantServiceCategoryId,T2.Id,T2.CmnAppliedServiceId from cmnconsultantservicecategory X join cmnconsultantservice T1 on X.Id=T1.CmnConsultantServiceCategoryId join crpconsultantworkclassification T2 on T1.Id=T2.CmnAppliedServiceId where T2.CrpConsultantId=? order by T1.Code",array($consultantNew->Id));
			$verifiedCategoryServices[$consultantNew->Id]=DB::select("select T1.Id as ServiceId,T1.Code as ServiceCode,T1.Name as ServiceName,T1.CmnConsultantServiceCategoryId,T2.Id,T2.CmnAppliedServiceId from cmnconsultantservicecategory X join cmnconsultantservice T1 on X.Id=T1.CmnConsultantServiceCategoryId join crpconsultantworkclassification T2 on T1.Id=T2.CmnVerifiedServiceId where T2.CrpConsultantId=? order by T1.Code",array($consultantNew->Id));
			$approvedCategoryServices[$consultantNew->Id]=DB::select("select T1.Id as ServiceId,T1.Code as ServiceCode,T1.Name as ServiceName,T1.CmnConsultantServiceCategoryId,T2.Id,T2.CmnAppliedServiceId from cmnconsultantservicecategory X join cmnconsultantservice T1 on X.Id=T1.CmnConsultantServiceCategoryId join crpconsultantworkclassification T2 on T1.Id=T2.CmnApprovedServiceId where T2.CrpConsultantId=? order by T1.Code",array($consultantNew->Id));
		endforeach;
		//END

		//CONSULTANT SERVICE
		//CONSULTANT SERVICE
		foreach($consultantService as $consultantServ):
			$serviceApplicationApprovedForPayment[$consultantServ->Id]=0;
			$serviceApplicationApproved[$consultantServ->Id] = 0;
			$hasFeeConsultant[$consultantServ->Id]=false;
			$hasRenewalConsultant[$consultantServ->Id]=false;
			$hasLateFeeConsultant[$consultantServ->Id]=false;
			$hasChangeInCategoryClassificationConsultant[$consultantServ->Id]=false;
			$hasCategoryClassificationsFeeConsultant[$consultantServ->Id]=array();
			$hasLatefeeAmountConsultant[$consultantServ->Id]=array();
			$existingCategoryServicesArray[$consultantServ->Id]  = array();
			$appliedCategoryServicesArray[$consultantServ->Id]  = array();
			$verifiedCategoryServicesArray[$consultantServ->Id]  = array();
			$approvedCategoryServicesArray[$consultantServ->Id]  = array();
			$consultantFinalTableId[$consultantServ->Id]=consultantModelConsultantId($consultantServ->Id);
			/*-----------------------------------------------------------------------------------------------------*/
			$feeAmountConsultant[$consultantServ->Id]=CrpService::serviceDetails(CONST_SERVICETYPE_RENEWAL)->take(1)->get(array('ConsultantAmount','ConsultantValidity'));
			$appliedServicesConsultant[$consultantServ->Id]=ConsultantAppliedServiceModel::appliedService($consultantServ->Id)->get(array('T1.Id','T1.Name as ServiceName','T1.HasFee','T1.ConsultantAmount'));
			foreach($appliedServicesConsultant[$consultantServ->Id] as $appliedService){
				if((int)$appliedService->hasFeeConsultant==1){
					$hasFeeConsultant[$consultantServ->Id]=true;
				}
				if((int)$appliedService->Id==CONST_SERVICETYPE_RENEWAL){
					$hasRenewalConsultant[$consultantServ->Id]=true;
				}
				if((int)$appliedService->Id==CONST_SERVICETYPE_LATEFEE){
					$hasLateFeeConsultant[$consultantServ->Id]=true;
				}
				if((int)$appliedService->Id==CONST_SERVICETYPE_CHANGEOFCATEGORYCLASSIFICATION){
					$hasChangeInCategoryClassificationConsultant[$consultantServ->Id]=true;
				}
			}
			if($hasRenewalConsultant[$consultantServ->Id] || $hasChangeInCategoryClassificationConsultant[$consultantServ->Id]){
				$hasCategoryClassificationsFeeConsultant[$consultantServ->Id]=DB::select("select T1.Name as ServiceCategoryName,T1.Id as ServiceCategoryId,count(T4.Id) as AppliedServiceCount,group_concat(distinct T4.Code order by T4.Code separator ',') as AppliedService,count(T6.Id) as VerifiedServiceCount,group_concat(distinct T6.Code order by T6.Code separator  ',') as VerifiedService,count(T7.Id) as ApprovedServiceCount,group_concat(distinct T7.Code order by T7.Code separator  ',') as ApprovedService from cmnconsultantservicecategory T1 join crpconsultantworkclassification T2 on T1.Id=T2.CmnServiceCategoryId join cmnconsultantservice T4 on T4.Id=T2.CmnAppliedServiceId and T2.CrpConsultantId=? left join cmnconsultantservice T6 on T6.Id=T2.CmnVerifiedServiceId and T2.CrpConsultantId=? left join cmnconsultantservice T7 on T7.Id=T2.CmnApprovedServiceId and T2.CrpConsultantId=? group by T1.Id order by T1.Name",array($consultantServ->Id,$consultantServ->Id,$consultantServ->Id));
			}
			if($hasLateFeeConsultant[$consultantServ->Id]){
				$hasLatefeeAmountConsultant[$consultantServ->Id]=DB::select("select T1.ApplicationDate,T2.RegistrationExpiryDate,DATEDIFF(T1.ApplicationDate,T2.RegistrationExpiryDate) as PenaltyNoOfDays,100 as PenaltyLatefeeAmountConsultant from crpconsultant T1 join crpconsultantfinal T2 on T1.CrpConsultantId=T2.Id where T1.Id=? LIMIT 1",array($consultantServ->Id));
			}
			/*---*/
			foreach($hasCategoryClassificationsFeeConsultant[$consultantServ->Id] as $singleCategory):
				$existingCategoryServicesArray[$consultantServ->Id][$singleCategory->ServiceCategoryId] = DB::table('crpconsultantworkclassificationfinal')->where('CmnServiceCategoryId',$singleCategory->ServiceCategoryId)->where('CrpConsultantFinalId',$consultantFinalTableId[$consultantServ->Id])->groupBy('CmnApprovedServiceId')->distinct()->whereNotNull('CmnApprovedServiceId')->lists('CmnApprovedServiceId');
				$appliedCategoryServicesArray[$consultantServ->Id][$singleCategory->ServiceCategoryId] = DB::table('crpconsultantworkclassification')->where('CmnServiceCategoryId',$singleCategory->ServiceCategoryId)->where('CrpConsultantId',$consultantServ->Id)->groupBy('CmnAppliedServiceId')->distinct()->whereNotNull('CmnAppliedServiceId')->lists('CmnAppliedServiceId');
				$verifiedCategoryServicesArray[$consultantServ->Id][$singleCategory->ServiceCategoryId] = DB::table('crpconsultantworkclassification')->where('CmnServiceCategoryId',$singleCategory->ServiceCategoryId)->where('CrpConsultantId',$consultantServ->Id)->groupBy('CmnVerifiedServiceId')->distinct()->whereNotNull('CmnVerifiedServiceId')->lists('CmnVerifiedServiceId');
				$approvedCategoryServicesArray[$consultantServ->Id][$singleCategory->ServiceCategoryId] = DB::table('crpconsultantworkclassification')->where('CmnServiceCategoryId',$singleCategory->ServiceCategoryId)->where('CrpConsultantId',$consultantServ->Id)->groupBy('CmnApprovedServiceId')->distinct()->whereNotNull('CmnApprovedServiceId')->lists('CmnApprovedServiceId');
			endforeach;
			/*---*/
			$consultantServices=ConsultantServiceModel::service()->get(array('Id','Code','Name','CmnConsultantServiceCategoryId'));
			$appliedCategories=ConsultantWorkClassificationModel::serviceCategory($consultantServ->Id)->get(array('T1.Id','T1.Name as Category'));
			$appliedCategoryServices=ConsultantWorkClassificationModel::appliedService($consultantServ->Id)->get(array(DB::raw('distinct T1.Id as ServiceId'),'crpconsultantworkclassification.Id','crpconsultantworkclassification.CmnServiceCategoryId','T1.Code as ServiceCode','T1.Name as ServiceName'));
			$verifiedCategoryServices=ConsultantWorkClassificationModel::verifiedService($consultantServ->Id)->get(array('crpconsultantworkclassification.Id','crpconsultantworkclassification.CmnServiceCategoryId','T2.Id as ServiceId','T2.Code as ServiceCode','T2.Name as ServiceName'));
			$currentServiceClassifications=ConsultantWorkClassificationFinalModel::services($consultantFinalTableId[$consultantServ->Id])->select(DB::raw("T1.Name as Category,group_concat(distinct T4.Code order by T4.Code separator ',') as ApprovedService"))->get();
			$generalInformationConsultant[$consultantServ->Id]=ConsultantModel::consultant($consultantServ->Id)->get(array('crpconsultant.Id','crpconsultant.PaymentReceiptNo', 'crpconsultant.PaymentReceiptDate','crpconsultant.ReferenceNo','crpconsultant.ApplicationDate','crpconsultant.CDBNo','crpconsultant.NameOfFirm','crpconsultant.RegisteredAddress','crpconsultant.Village','crpconsultant.Gewog','crpconsultant.Address','crpconsultant.Email','crpconsultant.TelephoneNo','crpconsultant.MobileNo','crpconsultant.FaxNo','crpconsultant.CmnApplicationRegistrationStatusId','crpconsultant.VerifiedDate','crpconsultant.RemarksByVerifier','crpconsultant.RemarksByApprover','crpconsultant.RegistrationApprovedDate','crpconsultant.RemarksByPaymentApprover','crpconsultant.RegistrationPaymentApprovedDate','T1.Name as Country','T2.NameEn as Dzongkhag','T4.FullName as Verifier','T5.FullName as Approver','T6.FullName as PaymentApprover','T7.Name as OwnershipType','T8.NameEn as RegisteredDzongkhag','crpconsultant.WaiveOffLateFee','crpconsultant.NewLateFeeAmount',));
		endforeach;
		//END
//		$spNewRegistration = DB::select("SELECT * FROM `crpspecializedtrade` WHERE CrpSpecializedTradeId is null and `CmnApplicationRegistrationStatusId` LIKE '6195664d-c3c5-11e4-af9f-080027dcfac6' AND `RegistrationApprovedDate` <= '2016-10-30' ORDER BY `ApplicationDate` DESC");
//		$spService = DB::select("SELECT * FROM `crpspecializedtrade` WHERE CrpSpecializedTradeId is not null and `CmnApplicationRegistrationStatusId` LIKE '6195664d-c3c5-11e4-af9f-080027dcfac6' AND `RegistrationApprovedDate` <= '2016-10-30' ORDER BY `ApplicationDate` DESC");
//		echo "<pre>"; dd($hasRenewalConsultant,$hasCategoryClassificationsFeeConsultant);
		return View::make('report.unpaidapplications')
				->with('date',$date)
				->with('hasFee',$hasFee)
				->with('generalInformation',$generalInformation)
				->with('generalInformationFinal',$generalInformationFinal)
				->with('generalInformationConsultant',$generalInformationConsultant)
				->with('maxClassification',$maxClassification)
				->with('hasLateFee',$hasLateFee)
				->with('hasLateFeeAmount',$hasLateFeeAmount)
				->with('registrationValidityYears',$registrationValidityYears)
				->with('hasRenewal',$hasRenewal)
				->with('hasChangeInCategoryClassification',$hasChangeInCategoryClassification)
				->with('classes',$class)
				->with('appliedServices',$appliedServices)
				->with('hasCategoryClassificationsFee',$hasCategoryClassificationsFee)
				->with('feeStructuresContractorNew',$feeStructuresContractorNew)
				->with('registrationValidityYears',$registrationValidityYears)
				->with('contractorNewRegistration',$contractorNewRegistration)
				->with('contractorService',$contractorService)
				->with('consultantNewRegistration',$consultantNewRegistration)
				->with('consultantService',$consultantService)
				->with('architectNewRegistration',$architectNewRegistration)
			->with('hasFeeConsultant',$hasFeeConsultant)
			->with('hasLateFeeConsultant',$hasLateFeeConsultant)
			->with('hasLatefeeAmountConsultant',$hasLatefeeAmountConsultant)
			->with('hasRenewalConsultant',$hasRenewalConsultant)
			->with('existingCategoryServicesArray',$existingCategoryServicesArray)
			->with('appliedCategoryServicesArray',$appliedCategoryServicesArray)
			->with('verifiedCategoryServicesArray',$verifiedCategoryServicesArray)
			->with('approvedCategoryServicesArray',$approvedCategoryServicesArray)
			->with('hasChangeInCategoryClassification',$hasChangeInCategoryClassification)
				->with('architectService',$architectService);
	}
	public function getUnpaidConsultants($date){
		$consultantService = DB::select("SELECT Id,NameOfFirm,CDBNo FROM `crpconsultant` WHERE CrpConsultantId is not null and `CmnApplicationRegistrationStatusId` LIKE '6195664d-c3c5-11e4-af9f-080027dcfac6' AND `RegistrationApprovedDate` <= '$date' ORDER BY `ApplicationDate` DESC");
		foreach($consultantService as $consultantServ):
			$serviceApplicationApprovedForPayment[$consultantServ->Id]=0;
			$serviceApplicationApproved[$consultantServ->Id] = 0;
			$hasFee[$consultantServ->Id]=false;
			$hasRenewal[$consultantServ->Id]=false;
			$hasLateFee[$consultantServ->Id]=false;
			$hasChangeInCategoryClassification[$consultantServ->Id]=false;
			$hasCategoryClassificationsFee[$consultantServ->Id]=array();
			$hasLateFeeAmount[$consultantServ->Id]=array();
			$existingCategoryServicesArray[$consultantServ->Id]  = array();
			$appliedCategoryServicesArray[$consultantServ->Id]  = array();
			$verifiedCategoryServicesArray[$consultantServ->Id]  = array();
			$approvedCategoryServicesArray[$consultantServ->Id]  = array();
			$consultantFinalTableId[$consultantServ->Id]=consultantModelConsultantId($consultantServ->Id);
			/*-----------------------------------------------------------------------------------------------------*/
			$feeAmount[$consultantServ->Id]=CrpService::serviceDetails(CONST_SERVICETYPE_RENEWAL)->take(1)->get(array('ConsultantAmount','ConsultantValidity'));
			$appliedServices[$consultantServ->Id]=ConsultantAppliedServiceModel::appliedService($consultantServ->Id)->get(array('T1.Id','T1.Name as ServiceName','T1.HasFee','T1.ConsultantAmount'));
			foreach($appliedServices[$consultantServ->Id] as $appliedService){
				if((int)$appliedService->HasFee==1){
					$hasFee[$consultantServ->Id]=true;
				}
				if((int)$appliedService->Id==CONST_SERVICETYPE_RENEWAL){
					$hasRenewal[$consultantServ->Id]=true;
				}
				if((int)$appliedService->Id==CONST_SERVICETYPE_LATEFEE){
					$hasLateFee[$consultantServ->Id]=true;
				}
				if((int)$appliedService->Id==CONST_SERVICETYPE_CHANGEOFCATEGORYCLASSIFICATION){
					$hasChangeInCategoryClassification[$consultantServ->Id]=true;
				}
			}
			if($hasRenewal[$consultantServ->Id] || $hasChangeInCategoryClassification[$consultantServ->Id]){
				$hasCategoryClassificationsFee[$consultantServ->Id]=DB::select("select T1.Name as ServiceCategoryName,T1.Id as ServiceCategoryId,count(T4.Id) as AppliedServiceCount,group_concat(distinct T4.Code order by T4.Code separator ',') as AppliedService,count(T6.Id) as VerifiedServiceCount,group_concat(distinct T6.Code order by T6.Code separator  ',') as VerifiedService,count(T7.Id) as ApprovedServiceCount,group_concat(distinct T7.Code order by T7.Code separator  ',') as ApprovedService from cmnconsultantservicecategory T1 join crpconsultantworkclassification T2 on T1.Id=T2.CmnServiceCategoryId join cmnconsultantservice T4 on T4.Id=T2.CmnAppliedServiceId and T2.CrpConsultantId=? left join cmnconsultantservice T6 on T6.Id=T2.CmnVerifiedServiceId and T2.CrpConsultantId=? left join cmnconsultantservice T7 on T7.Id=T2.CmnApprovedServiceId and T2.CrpConsultantId=? group by T1.Id order by T1.Name",array($consultantServ->Id,$consultantServ->Id,$consultantServ->Id));
			}
			if($hasLateFee){
				$hasLateFeeAmount[$consultantServ->Id]=DB::select("select T1.ApplicationDate,T2.RegistrationExpiryDate,DATEDIFF(T1.ApplicationDate,T2.RegistrationExpiryDate) as PenaltyNoOfDays,100 as PenaltyLateFeeAmount from crpconsultant T1 join crpconsultantfinal T2 on T1.CrpConsultantId=T2.Id where T1.Id=? LIMIT 1",array($consultantServ->Id));
			}
			/*------------------------------------------------------------------------------------------------------*/

			/*---*/
			foreach($hasCategoryClassificationsFee[$consultantServ->Id] as $singleCategory):
				$existingCategoryServicesArray[$consultantServ->Id][$singleCategory->ServiceCategoryId] = DB::table('crpconsultantworkclassificationfinal')->where('CmnServiceCategoryId',$singleCategory->ServiceCategoryId)->where('CrpConsultantFinalId',$consultantFinalTableId)->groupBy('CmnApprovedServiceId')->distinct()->whereNotNull('CmnApprovedServiceId')->lists('CmnApprovedServiceId');
				$appliedCategoryServicesArray[$consultantServ->Id][$singleCategory->ServiceCategoryId] = DB::table('crpconsultantworkclassification')->where('CmnServiceCategoryId',$singleCategory->ServiceCategoryId)->where('CrpConsultantId',$consultantServ->Id)->groupBy('CmnAppliedServiceId')->distinct()->whereNotNull('CmnAppliedServiceId')->lists('CmnAppliedServiceId');
				$verifiedCategoryServicesArray[$consultantServ->Id][$singleCategory->ServiceCategoryId] = DB::table('crpconsultantworkclassification')->where('CmnServiceCategoryId',$singleCategory->ServiceCategoryId)->where('CrpConsultantId',$consultantServ->Id)->groupBy('CmnVerifiedServiceId')->distinct()->whereNotNull('CmnVerifiedServiceId')->lists('CmnVerifiedServiceId');
				$approvedCategoryServicesArray[$consultantServ->Id][$singleCategory->ServiceCategoryId] = DB::table('crpconsultantworkclassification')->where('CmnServiceCategoryId',$singleCategory->ServiceCategoryId)->where('CrpConsultantId',$consultantServ->Id)->groupBy('CmnApprovedServiceId')->distinct()->whereNotNull('CmnApprovedServiceId')->lists('CmnApprovedServiceId');
			endforeach;
			/*---*/
			$consultantServices[$consultantServ->Id]=ConsultantServiceModel::service()->get(array('Id','Code','Name','CmnConsultantServiceCategoryId'));
			$appliedCategories[$consultantServ->Id]=ConsultantWorkClassificationModel::serviceCategory($consultantServ->Id)->get(array('T1.Id','T1.Name as Category'));
			$appliedCategoryServices[$consultantServ->Id]=ConsultantWorkClassificationModel::appliedService($consultantServ->Id)->get(array(DB::raw('distinct T1.Id as ServiceId'),'crpconsultantworkclassification.Id','crpconsultantworkclassification.CmnServiceCategoryId','T1.Code as ServiceCode','T1.Name as ServiceName'));
			$verifiedCategoryServices[$consultantServ->Id]=ConsultantWorkClassificationModel::verifiedService($consultantServ->Id)->get(array('crpconsultantworkclassification.Id','crpconsultantworkclassification.CmnServiceCategoryId','T2.Id as ServiceId','T2.Code as ServiceCode','T2.Name as ServiceName'));
			$currentServiceClassifications[$consultantServ->Id]=ConsultantWorkClassificationFinalModel::services($consultantFinalTableId)->select(DB::raw("T1.Name as Category,group_concat(distinct T4.Code order by T4.Code separator ',') as ApprovedService"))->get();
			$generalInformation[$consultantServ->Id]=ConsultantModel::consultant($consultantServ->Id)->get(array('crpconsultant.Id','crpconsultant.PaymentReceiptNo', 'crpconsultant.PaymentReceiptDate','crpconsultant.ReferenceNo','crpconsultant.ApplicationDate','crpconsultant.CDBNo','crpconsultant.NameOfFirm','crpconsultant.RegisteredAddress','crpconsultant.Village','crpconsultant.Gewog','crpconsultant.Address','crpconsultant.Email','crpconsultant.TelephoneNo','crpconsultant.MobileNo','crpconsultant.FaxNo','crpconsultant.CmnApplicationRegistrationStatusId','crpconsultant.VerifiedDate','crpconsultant.RemarksByVerifier','crpconsultant.RemarksByApprover','crpconsultant.RegistrationApprovedDate','crpconsultant.RemarksByPaymentApprover','crpconsultant.RegistrationPaymentApprovedDate','T1.Name as Country','T2.NameEn as Dzongkhag','T4.FullName as Verifier','T5.FullName as Approver','T6.FullName as PaymentApprover','T7.Name as OwnershipType','T8.NameEn as RegisteredDzongkhag','crpconsultant.WaiveOffLateFee','crpconsultant.NewLateFeeAmount',));
			/*------------------------------End of record applied by the applicant----------------------------*/
			$generalInformationFinal[$consultantServ->Id]=ConsultantFinalModel::consultant($consultantFinalTableId)->get(array('crpconsultantfinal.Id','crpconsultantfinal.RegistrationApprovedDate','crpconsultantfinal.RegistrationExpiryDate','crpconsultantfinal.CDBNo','crpconsultantfinal.NameOfFirm','crpconsultantfinal.RegisteredAddress','crpconsultantfinal.Village','crpconsultantfinal.Gewog','crpconsultantfinal.Address','crpconsultantfinal.Email','crpconsultantfinal.TelephoneNo','crpconsultantfinal.MobileNo','crpconsultantfinal.FaxNo','crpconsultantfinal.CmnApplicationRegistrationStatusId','crpconsultantfinal.RegistrationExpiryDate','T1.Name as Country','T2.NameEn as Dzongkhag','T3.Name as CurrentStatus','T4.Name as OwnershipType','T5.NameEn as RegisteredDzongkhag'));
		endforeach;

		return View::make('report.unpaidconsultants')
			->with('consultantsService',$consultantService)
			->with('date',$date)
			->with('hasFee',$hasFee)
			->with('hasLateFee',$hasLateFee)
			->with('hasLateFeeAmount',$hasLateFeeAmount)
			->with('hasRenewal',$hasRenewal)
			->with('existingCategoryServicesArray',$existingCategoryServicesArray)
			->with('appliedCategoryServicesArray',$appliedCategoryServicesArray)
			->with('verifiedCategoryServicesArray',$verifiedCategoryServicesArray)
			->with('approvedCategoryServicesArray',$approvedCategoryServicesArray)
			->with('hasChangeInCategoryClassification',$hasChangeInCategoryClassification)
			->with('hasCategoryClassificationsFee',$hasCategoryClassificationsFee)
			->with('feeAmount',$feeAmount)
			->with('appliedCategories',$appliedCategories)
			->with('consultantServices',$consultantServices)
			->with('appliedCategoryServices',$appliedCategoryServices)
			->with('verifiedCategoryServices',$verifiedCategoryServices)
			->with('appliedServices',$appliedServices)
			->with('serviceApplicationApprovedForPayment',$serviceApplicationApprovedForPayment)
			->with('serviceApplicationApproved',$serviceApplicationApproved)
			->with('currentServiceClassifications',$currentServiceClassifications)
			->with('generalInformation',$generalInformation)
			->with('generalInformationFinal',$generalInformationFinal);
	}
}