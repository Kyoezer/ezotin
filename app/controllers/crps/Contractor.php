<?php
class Contractor extends CrpsController{
	public function defaultIndex(){
		$feeStructures=ContractorClassificationModel::classification()->get(array('Code','Name','RegistrationFee'));
		$registrationValidityYears=CrpService::registrationValidityYear(CONST_SERVICETYPE_NEW)->pluck('ContractorValidity');
		return View::make('crps.contractorindex')
			->with('feeStructures',$feeStructures)
			->with('registrationValidityYears',$registrationValidityYears);
	}
	public function projectCategory(){
		$categories=ContractorWorkCategoryModel::contractorProjectCategory()->get(array('Id','Code','Name'));
		$projectCategoryId=Input::get('sref');
		if((bool)$projectCategoryId==NULL || empty($projectCategoryId)){
			$workCategoriesEdit=array(new ContractorWorkCategoryModel());
		}else{
			$workCategoriesEdit=ContractorWorkCategoryModel::contractorProjectCategory()->where('Id',$projectCategoryId)->get(array('Id','Code','Name'));
		}
		return View::make('crps.contractorworkcategory')
			->with('workCategoriesEdit',$workCategoriesEdit)
			->with('categories',$categories);
	}
	public function classification(){
		$classifications=ContractorClassificationModel::classification()->get(array('Id','Code','Name','RegistrationFee','RenewalFee'));
		$classificationId=Input::get('sref');
		if((bool)$classificationId==NULL || empty($classificationId)){
			$editClassifications=array(new ContractorClassificationModel());
		}else{
			$editClassifications=ContractorClassificationModel::classification()->where('Id',$classificationId)->get(array('Id','Code','Name','RegistrationFee','RenewalFee'));
		}
		return View::make('crps.contractorclassification')
			->with('classifications',$classifications)
			->with('editClassifications',$editClassifications);
	}
	public function saveClassification(){
		$postedValues=Input::all();
		$validation = new ContractorClassificationModel;
		if(!($validation->validate($postedValues))){
			$errors = $validation->errors();
			return Redirect::to('contractor/classification')->withErrors($errors)->withInput();
		}
		if(empty($postedValues["Id"])){
			ContractorClassificationModel::create($postedValues);
			return Redirect::to('contractor/classification')->with('savedsuccessmessage','Contractor classification has been successfully added');
		}else{
			$classificationificationModel= new ContractorClassificationModel();
			$instance=ContractorClassificationModel::find($postedValues['Id']);
			$instance->fill($postedValues);
			$instance->update();
			return Redirect::to('contractor/classification')->with('savedsuccessmessage','Contractor classification has been successfully updated');
		}
	}
	public function saveCategory(){
		$postedValues=Input::all();
		$validation = new ContractorWorkCategoryModel;
		if(!($validation->validate($postedValues))){
			$errors = $validation->errors();
			return Redirect::to('master/contractorprojectcategory')->withErrors($errors)->withInput();
		}
		if(empty($postedValues["Id"])){
			ContractorWorkCategoryModel::create($postedValues);
			return Redirect::to('master/contractorprojectcategory')->with('savedsuccessmessage','Contractor Work Category has been successfully added');
		}else{
			$instance=ContractorWorkCategoryModel::find($postedValues['Id']);
			$instance->fill($postedValues);
			$instance->update();
			return Redirect::to('master/contractorprojectcategory')->with('savedsuccessmessage','Contractor Work Category has been successfully updated');
		}
	}
	public function checkProposedName(){
		$flagFirmName=true;
		$proposedName=Input::get('NameOfFirm');
		$firmNameCount=ContractorModel::contractorHardListAll()->where('CmnApplicationRegistrationStatusId','<>',CONST_CMN_APPLICATIONREGISTRATIONSTATUS_REJECTED)->where('NameOfFirm',$proposedName)->count();
		$firmNameCountFinal=ContractorFinalModel::contractorHardListAll()->where('NameOfFirm',$proposedName)->count();
		if($firmNameCount>0 || $firmNameCountFinal>0){
			$flagFirmName=false;
		}
		return json_encode(array(
			'valid' => $flagFirmName,
		));
	}
	public function generalInfoRegistration($contractor=null){
		$isRejectedApp=0;
		$serviceByContractor=0;
		$isRenewalService=0;
		$newGeneralInfoSave=1;
		$editByCDB = false;
		$view="crps.contractorregistrationgeneralinfo";
		$postRouteReference='contractor/mcontractorgeneralinfo';
		$redirectUrl=Input::get('redirectUrl');
		$contractorGeneralInfo=array(new ContractorModel());
		$contractorPartnerDetail=array(new ContractorHumanResourceModel());
		$refreshersCourseCertificate = '';
		if((bool)$contractor!=null && !Input::has('usercdb')){
			if(Input::has('editbyapplicant')){
				if(Input::has('rejectedapplicationreapply')){
					$isRejectedApp=1; //HAS REAPPLIED
					$refreshersCourseCertificate = DB::table('crpcontractorattachment')->where('CrpContractorId',$contractor)->where('DocumentName',"Refresher Course Certificate")->pluck('DocumentPath');
				}
				$view="crps.contractorregistrationgeneralinfo";
			}else{
				$view="crps.contractoreditgeneralinfo";
			}
			$contractorGeneralInfo=ContractorModel::contractorHardList($contractor)->get(array('Id','ReferenceNo','TPN','TradeLicenseNo','ApplicationDate','NameOfFirm','CmnRegisteredDzongkhagId','RegisteredAddress','Address','Email','TelephoneNo','Gewog','Village','MobileNo','FaxNo','CmnCountryId','CmnDzongkhagId','CmnOwnershipTypeId'));
			$contractorPartnerDetail=ContractorHumanResourceModel::contractorPartnerHardList($contractor)->get(array('Id','CIDNo','Name','Sex','JoiningDate','ShowInCertificate','CmnCountryId','CmnSalutationId','CmnDesignationId'));
		}
		if((bool)$contractor!=null && Input::has('usercdb')){
			$editByCDB = true;
			$view="crps.contractoreditgeneralinfo";
			$newGeneralInfoSave=0;
			$contractorGeneralInfo=ContractorFinalModel::contractorHardList($contractor)->get(array('Id','ReferenceNo','TPN','TradeLicenseNo','ApplicationDate','NameOfFirm','CmnRegisteredDzongkhagId','RegisteredAddress','Address','Email','TelephoneNo','Gewog','Village','MobileNo','FaxNo','CmnCountryId','CmnDzongkhagId','CmnOwnershipTypeId'));
			$contractorPartnerDetail=ContractorHumanResourceFinalModel::contractorPartnerHardList($contractor)->get(array('Id','CIDNo','Name','Sex','JoiningDate','ShowInCertificate','CmnCountryId','CmnSalutationId','CmnDesignationId'));
		}
		$applicationReferenceNo=$this->tableTransactionNo('ContractorModel','ReferenceNo');
		$country=CountryModel::country()->get(array('Id','Nationality','Name','Code'));
		$dzongkhag=DzongkhagModel::dzongkhag()->get(array('Id','NameEn'));
		$designation=CmnListItemModel::designation()->get(array('Id','Name',DB::raw('coalesce(ReferenceNo,22) as ReferenceNo')));
		$salutation=CmnListItemModel::salutation()->get(array('Id','Name'));
		$ownershipTypes=CmnListItemModel::ownershipType()->get(array('Id','ReferenceNo','Name'));
		return View::make($view)
			->with('refreshersCourseCertificate',$refreshersCourseCertificate)
			->with('editByCDB',$editByCDB)
			->with('isRejectedApp',$isRejectedApp)
			->with('redirectUrl',$redirectUrl)
			->with('isRenewalService',$isRenewalService)
			->with('isEdit',$contractor)
			->with('postRouteReference',$postRouteReference)
			->with('serviceByContractor',$serviceByContractor)
			->with('newGeneralInfoSave',$newGeneralInfoSave)
			->with('isServiceByContractor','')
			->with('applicationReferenceNo',$applicationReferenceNo)
			->with('contractorGeneralInfo',$contractorGeneralInfo)
			->with('contractorPartnerDetails',$contractorPartnerDetail)
			->with('countries',$country)
			->with('dzongkhags',$dzongkhag)
			->with('designations',$designation)
			->with('salutations',$salutation)
			->with('ownershipTypes',$ownershipTypes);
	}
	public function workClassificationRegistration($contractor=null){
		$serviceByContractor=0;
		$redirectUrl=Input::get('redirectUrl');
		$contractorId=$contractor;
		$final = false;
		$view="crps.contractorregistrationworkclassification";
		if(Route::current()->getUri()=="contractor/editworkclassification/{contractorid}"){
			$view="crps.contractoreditworkclassification";
		}
		if((bool)$redirectUrl==NULL){
			if(Session::has('ContractorRegistrationId')){
				$contractorId=Session::get('ContractorRegistrationId');
			}else{
				return Redirect::to('contractor/generalinforegistration')->withInput();
			}
		}
		$class=ContractorClassificationModel::classification()->select(DB::raw('Id,Name,coalesce(ReferenceNo,999999) as ReferenceNo'))->get();

		if((bool)$contractor==null){
			if(Input::has('rejectedapplicationreapply')){
				$projectCategory=DB::select("select T1.Id,concat(coalesce(T1.Code,''),'-',T1.Name) as Name,coalesce(T1.ReferenceNo,999999) as ReferenceNo,T2.CmnAppliedClassificationId from cmncontractorworkcategory T1 left join crpcontractorworkclassification T2 on T1.Id=T2.CmnProjectCategoryId and T2.CrpContractorId=? order by T1.Code,T1.Name",array($contractorId));
			}else{
				$projectCategory=ContractorWorkCategoryModel::contractorProjectCategory()->select(DB::raw("Id,concat(coalesce(Code,''),'-',Name) as Name,coalesce(ReferenceNo,999999) as ReferenceNo,NULL as CmnAppliedClassificationId"))->get();
			}
		}else{
			if(Input::has('final')){
				$finalCount = DB::table('crpcontractorfinal')->where('Id',$contractorId)->count();
				if($finalCount > 0){
					$final = true;
					$projectCategory=DB::select("select T1.Id,concat(coalesce(T1.Code,''),'-',T1.Name) as Name,coalesce(T1.ReferenceNo,999999) as ReferenceNo,T2.CmnAppliedClassificationId from cmncontractorworkcategory T1 left join crpcontractorworkclassificationfinal T2 on T1.Id=T2.CmnProjectCategoryId and T2.CrpContractorFinalId=? order by T1.Code,T1.Name",array($contractorId));
				}else{
					$projectCategory=DB::select("select T1.Id,concat(coalesce(T1.Code,''),'-',T1.Name) as Name,coalesce(T1.ReferenceNo,999999) as ReferenceNo,T2.CmnAppliedClassificationId from cmncontractorworkcategory T1 left join crpcontractorworkclassification T2 on T1.Id=T2.CmnProjectCategoryId and T2.CrpContractorId=? order by T1.Code,T1.Name",array($contractorId));
				}
			}else{
				$projectCategory=DB::select("select T1.Id,concat(coalesce(T1.Code,''),'-',T1.Name) as Name,coalesce(T1.ReferenceNo,999999) as ReferenceNo,T2.CmnAppliedClassificationId from cmncontractorworkcategory T1 left join crpcontractorworkclassification T2 on T1.Id=T2.CmnProjectCategoryId and T2.CrpContractorId=? order by T1.Code,T1.Name",array($contractorId));
			}

		}
		return View::make($view)
			->with('serviceByContractor',$serviceByContractor)
			->with('final',$final)
			->with('redirectUrl',$redirectUrl)
			->with('contractorId',$contractorId)
			->with('isEdit',$contractor)
			->with('classes',$class)
			->with('projectCategories',$projectCategory);
	}
	public function humanResourceRegistration($contractor=null){
		$serviceByContractor=0;
		$newHumanResourceSave=1;
		$editPage='contractor/edithumanresource';
		$humanResourceEditRoute='contractor/applyservicehumanresourceedit';
		if(Session::has('ContractorRegistrationId')){
			$contractorId=Session::get('ContractorRegistrationId');
		}else{
			return Redirect::to('contractor/generalinforegistration')->withInput();
		}
		$humanResourceEdit=array(new ContractorHumanResourceModel());
		$humanResourceEditAttachments=array();
		$humanResourceId=Input::get('humanresourceid');
		if(!empty($humanResourceId) && strlen($humanResourceId)==36){
			$humanResourceEdit=ContractorHumanResourceModel::contractorHumanResourceHardListSingle($humanResourceId)->get(array('Id','Name','CIDNo','Sex','CmnSalutationId','CmnQualificationId','CmnTradeId','CmnDesignationId','CmnServiceTypeId','JoiningDate', 'CmnCountryId'));
			$humanResourceEditAttachments=ContractorHumanResourceAttachmentModel::humanResourceAttachment($humanResourceId)->get(array('Id','DocumentName','DocumentPath'));
		}
		if((bool)$contractor!=null){
			$newHumanResourceSave=0;
		}
		$salutation=CmnListItemModel::salutation()->get(array('Id','Name'));
		$country=CountryModel::country()->get(array('Id','Nationality', 'Name'));
		$designation=CmnListItemModel::designation()->get(array('Id','Name'));
		$qualification=CmnListItemModel::qualification()->get(array('Id','Name'));
		$trades=CmnListItemModel::trade()->get(array('Id','Name'));
		$serviceTypes=CmnListItemModel::serviceType()->get(array('Id','Name'));
		$contractorHumanResources=ContractorHumanResourceModel::ContractorHumanResource($contractorId)->get(array('crpcontractorhumanresource.Id','crpcontractorhumanresource.Name','crpcontractorhumanresource.CIDNo','crpcontractorhumanresource.Sex','crpcontractorhumanresource.JoiningDate', 'crpcontractorhumanresource.Name','T1.Name as Salutation','T2.Name as Qualification','T3.Name as Trade','T4.Name as Designation','T5.Nationality as Country','T6.Name as ServiceType'));
		$humanResourcesAttachments=ContractorHumanResourceModel::humanResourceAttachments($contractorId)->get(array('T1.Id','T1.CrpContractorHumanResourceId','T1.DocumentName','T1.DocumentPath'));
		return View::make('crps.contractorregistrationhumanresource')
			->with('serviceByContractor',$serviceByContractor)
			->with('newHumanResourceSave',$newHumanResourceSave)
			->with('humanResourceEditRoute',$humanResourceEditRoute)
			->with('isEdit',$contractor)
			->with('editPage',$editPage)
			->with('contractorId',$contractorId)
			->with('countries',$country)
			->with('salutations',$salutation)
			->with('qualifications',$qualification)
			->with('serviceTypes',$serviceTypes)
			->with('designations',$designation)
			->with('trades',$trades)
			->with('contractorHumanResources',$contractorHumanResources)
			->with('humanResourcesAttachments',$humanResourcesAttachments)
			->with('humanResourceEdit',$humanResourceEdit)
			->with('humanResourceEditAttachments',$humanResourceEditAttachments);

	}
	public function humanResourceRegistrationEdit($contractor=null){
		$afterSaveRedirect=1;
		$serviceByContractor=0;
		$newHumanResourceSave=0;
		$isEditByCDBUser=Input::get('usercdb');
		$humanResourceEditRoute='contractor/edithumanresource';
		$redirectUrl=Input::get('redirectUrl');
		$editPage='contractor/edithumanresource';
		$humanResourceEdit=array(new ContractorHumanResourceModel());
		$humanResourceEditAttachments=array();
		$humanResourceId=Input::get('humanresourceid');
		$contractorHumanResources = array();
		$humanResourcesAttachments = array();
		$humanResourceEditFinalAttachments=array();
		if(!empty($humanResourceId) && strlen($humanResourceId)==36){
			$humanResourceEditFinalAttachments=ContractorHumanResourceAttachmentFinalModel::humanResourceAttachment($humanResourceId)->get(array('Id','DocumentName','DocumentPath'));
			if(!Input::has('usercdb')){
				$humanResourceEdit=ContractorHumanResourceModel::contractorHumanResourceHardListSingle($humanResourceId)->get(array('Id','Name','CIDNo','Sex','CmnSalutationId','CmnQualificationId','CmnTradeId','CmnDesignationId','JoiningDate', 'CmnCountryId','CmnServiceTypeId'));
				$humanResourceEditAttachments=ContractorHumanResourceAttachmentFinalModel::humanResourceAttachment($humanResourceId)->get(array('Id','DocumentName','DocumentPath'));
			}
			if(Input::has('usercdb')){
				$humanResourceEdit=ContractorHumanResourceFinalModel::contractorHumanResourceHardListSingle($humanResourceId)->get(array('Id','Name','CIDNo','Sex','CmnSalutationId','CmnQualificationId','CmnTradeId','CmnDesignationId','JoiningDate', 'CmnCountryId','CmnServiceTypeId'));
				$humanResourceEditFinalAttachments=ContractorHumanResourceAttachmentFinalModel::humanResourceAttachment($humanResourceId)->get(array('Id','DocumentName','DocumentPath'));
				$humanResourceEditAttachments=ContractorHumanResourceAttachmentFinalModel::humanResourceAttachment($humanResourceId)->get(array('Id','DocumentName','DocumentPath'));
				if(count($humanResourceEdit) == 0){
					$humanResourceEdit=ContractorHumanResourceModel::contractorHumanResourceHardListSingle($humanResourceId)->get(array('Id','Name','CIDNo','Sex','CmnSalutationId','CmnQualificationId','CmnTradeId','CmnDesignationId','JoiningDate', 'CmnCountryId','CmnServiceTypeId'));
					$humanResourceEditAttachments=ContractorHumanResourceAttachmentFinalModel::humanResourceAttachment($humanResourceId)->get(array('Id','DocumentName','DocumentPath'));
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
		if((bool)$contractor!=null && !Input::has('usercdb')){
			$changeModel = false;
			$contractorHumanResources=ContractorHumanResourceModel::ContractorHumanResource($contractor)->get(array('crpcontractorhumanresource.Id', DB::raw('coalesce(T4.ReferenceNo,0) as DesignationReference'), 'crpcontractorhumanresource.Name','crpcontractorhumanresource.CIDNo','crpcontractorhumanresource.Sex','crpcontractorhumanresource.JoiningDate','crpcontractorhumanresource.Name','T1.Name as Salutation','T2.Name as Qualification','T3.Name as Trade','T4.Name as Designation','T5.Nationality as Country'));
			$humanResourcesAttachments=ContractorHumanResourceModel::humanResourceAttachments($contractor)->get(array('T1.Id','T1.CrpContractorHumanResourceId','T1.DocumentName','T1.DocumentPath'));
		}
		if((bool)$contractor!=null && Input::has('usercdb')){
			$changeModel = true;
			$contractorFinalHumanResources=ContractorHumanResourceFinalModel::ContractorHumanResource($contractor)->get(array('crpcontractorhumanresourcefinal.Id',DB::raw('coalesce(T4.ReferenceNo,0) as DesignationReference'),'crpcontractorhumanresourcefinal.Name','crpcontractorhumanresourcefinal.CIDNo','crpcontractorhumanresourcefinal.Sex','crpcontractorhumanresourcefinal.CmnServiceTypeId','crpcontractorhumanresourcefinal.JoiningDate','crpcontractorhumanresourcefinal.Name','T1.Name as Salutation','T2.Name as Qualification','T3.Name as Trade','T4.Name as Designation','T5.Nationality as Country'));
			$humanResourcesFinalAttachments=ContractorHumanResourceAttachmentFinalModel::singleContractorHumanResourceAllAttachments($contractor)->get(array('crpcontractorhumanresourceattachmentfinal.DocumentName','crpcontractorhumanresourceattachmentfinal.DocumentPath','crpcontractorhumanresourceattachmentfinal.CrpContractorHumanResourceFinalId as CrpContractorHumanResourceId'));
			$contractorInFinalTable = DB::table('crpcontractorfinal')->where('Id',$contractor)->count();
			if($contractorInFinalTable == 0){
				$contractorHumanResources=ContractorHumanResourceModel::ContractorHumanResource($contractor)->get(array('crpcontractorhumanresource.Id', DB::raw('coalesce(T4.ReferenceNo,0) as DesignationReference'), 'crpcontractorhumanresource.Name','crpcontractorhumanresource.CIDNo','crpcontractorhumanresource.Sex','crpcontractorhumanresource.JoiningDate','crpcontractorhumanresource.CmnServiceTypeId','crpcontractorhumanresource.Name','T1.Name as Salutation','T2.Name as Qualification','T3.Name as Trade','T4.Name as Designation','T5.Nationality as Country'));
				$humanResourcesAttachments=ContractorHumanResourceModel::humanResourceAttachments($contractor)->get(array('T1.Id','T1.CrpContractorHumanResourceId','T1.DocumentName','T1.DocumentPath'));
			}else{
				$contractorHumanResources=ContractorHumanResourceFinalModel::ContractorHumanResource($contractor)->get(array('crpcontractorhumanresourcefinal.Id',DB::raw('coalesce(T4.ReferenceNo,0) as DesignationReference'),'crpcontractorhumanresourcefinal.Name','crpcontractorhumanresourcefinal.CIDNo','crpcontractorhumanresourcefinal.Sex','crpcontractorhumanresourcefinal.CmnServiceTypeId','crpcontractorhumanresourcefinal.JoiningDate','crpcontractorhumanresourcefinal.Name','T1.Name as Salutation','T2.Name as Qualification','T3.Name as Trade','T4.Name as Designation','T5.Nationality as Country'));
				$humanResourcesAttachments=ContractorHumanResourceAttachmentFinalModel::singleContractorHumanResourceAllAttachments($contractor)->get(array('crpcontractorhumanresourceattachmentfinal.DocumentName','crpcontractorhumanresourceattachmentfinal.DocumentPath','crpcontractorhumanresourceattachmentfinal.CrpContractorHumanResourceFinalId as CrpContractorHumanResourceId'));
			}
		}

		return View::make('crps.contractoredithumanresource')
			->with('changeModel',$changeModel)
			->with('serviceTypes',$serviceTypes)
			->with('afterSaveRedirect',$afterSaveRedirect)
			->with('serviceByContractor',$serviceByContractor)
			->with('newHumanResourceSave',$newHumanResourceSave)
			->with('humanResourceEditFinalAttachments',$humanResourceEditFinalAttachments)
			->with('isEditByCDBUser',$isEditByCDBUser)
			->with('humanResourceEditRoute',$humanResourceEditRoute)
			->with('redirectUrl',$redirectUrl)
			->with('isEdit',$contractor)
			->with('editPage',$editPage)
			->with('contractorId',$contractor)
			->with('countries',$country)
			->with('salutations',$salutation)
			->with('qualifications',$qualification)
			->with('designations',$designation)
			->with('trades',$trades)
			->with('contractorHumanResources',$contractorHumanResources)
			->with('humanResourcesAttachments',$humanResourcesAttachments)
			->with('humanResourceEdit',$humanResourceEdit)
			->with('humanResourceEditAttachments',$humanResourceEditAttachments);

	}
	public function equipmentRegistration($contractor=null){
		$serviceByContractor=0;
		$newEquipmentSave=1;
		$editPage='contractor/editequipment';
		if(Session::has('ContractorRegistrationId')){
			$contractorId=Session::get('ContractorRegistrationId');
		}else{
			return Redirect::to('contractor/generalinforegistration')->withInput();
		}
		$equipmentEdit=array(new ContractorHumanResourceModel());
		$equipmentAttachments=array();
		$equipmentId=Input::get('equipmentid');
		if(!empty($equipmentId) && strlen($equipmentId)==36){
			$equipmentEdit=ContractorEquipmentModel::ContractorEquipmentHardListSingle($equipmentId)->get(array('Id','CmnEquipmentId','RegistrationNo','ModelNo','Quantity'));
			$equipmentAttachments=ContractorEquipmentAttachmentModel::EquipmentAttachment($equipmentId)->get(array('Id','DocumentName','DocumentPath'));
		}
		if((bool)$contractor!=null){
			$newEquipmentSave=0;
		}
		$equipments=CmnEquipmentModel::equipment()->whereNotNull('Code')->get(array('Id','Name','Code','IsRegistered','VehicleType'));
		$contractorEquipments=ContractorEquipmentModel::contractorEquipment($contractorId)->get(array('crpcontractorequipment.Id','crpcontractorequipment.RegistrationNo','crpcontractorequipment.ModelNo','crpcontractorequipment.Quantity','T1.Name'));
		$equipmentsAttachments=ContractorEquipmentModel::equipmentAttachments($contractorId)->get(array('T1.Id','T1.CrpContractorEquipmentId','T1.DocumentName','T1.DocumentPath'));
		return View::make('crps.contractorregistrationequipment')
			->with('serviceByContractor',$serviceByContractor)
			->with('newEquipmentSave',$newEquipmentSave)
			->with('isEdit',$contractor)
			->with('editPage',$editPage)
			->with('contractorId',$contractorId)
			->with('equipments',$equipments)
			->with('contractorEquipments',$contractorEquipments)
			->with('equipmentsAttachments',$equipmentsAttachments)
			->with('equipmentEdit',$equipmentEdit)
			->with('equipmentAttachments',$equipmentAttachments);
	}
	public function equipmentRegistrationEdit($contractor=null){
		$afterSaveRedirect=1;
		$serviceByContractor=0;
		$newEquipmentSave=0;
		$isEditByCDBUser=Input::get('usercdb');
		$editPage='contractor/editequipment';
		$redirectUrl=Input::get('redirectUrl');
		$equipmentEditRoute='contractor/editequipment';
		$equipmentEdit=array(new ContractorEquipmentFinalModel());
		$equipmentAttachments=array();
		$equipmentId=Input::get('equipmentid');
		if(!empty($equipmentId) && strlen($equipmentId)==36){
			if(!Input::has('usercdb')){
				$equipmentEdit=ContractorEquipmentFinalModel::contractorEquipmentHardListSingle($equipmentId)->get(array('Id','CmnEquipmentId','RegistrationNo','ModelNo','Quantity'));
				$equipmentAttachments=ContractorEquipmentAttachmentFinalModel::equipmentAttachment($equipmentId)->get(array('Id','DocumentName'));
			}
			if(Input::has('usercdb')){
				$equipmentEdit=ContractorEquipmentFinalModel::contractorEquipmentHardListSingle($equipmentId)->get(array('Id','CmnEquipmentId','RegistrationNo','ModelNo','Quantity'));
				$equipmentAttachments=ContractorEquipmentAttachmentFinalModel::equipmentAttachment($equipmentId)->get(array('Id','DocumentName'));
			}
		}
		$equipments=CmnEquipmentModel::equipment()->whereNotNull('Code')->get(array('Id','Name','Code','IsRegistered','VehicleType'));
		if((bool)$contractor!=null && !Input::has('usercdb')){
			$changeModel = false;
			$contractorEquipments=ContractorEquipmentModel::contractorEquipment($contractor)->get(array('crpcontractorequipment.Id','crpcontractorequipment.RegistrationNo','crpcontractorequipment.ModelNo','crpcontractorequipment.Quantity','T1.Name'));
			$equipmentsAttachments=ContractorEquipmentFinalModel::equipmentAttachments($contractor)->get(array('T1.Id','T1.CrpContractorEquipmentFinalId','T1.DocumentName'));
		}
		if((bool)$contractor!=null && Input::has('usercdb')){
			$changeModel = true;
			$contractorEquipments=ContractorEquipmentFinalModel::contractorEquipment($contractor)->get(array('crpcontractorequipmentfinal.Id','crpcontractorequipmentfinal.RegistrationNo','crpcontractorequipmentfinal.ModelNo','crpcontractorequipmentfinal.Quantity','T1.Name'));
			$equipmentsAttachments=ContractorEquipmentAttachmentFinalModel::singleContractorEquipmentAllAttachments($contractor)->get(array('crpcontractorequipmentattachmentfinal.DocumentName','crpcontractorequipmentattachmentfinal.DocumentPath','crpcontractorequipmentattachmentfinal.CrpContractorEquipmentFinalId as CrpContractorEquipmentId'));
			$contractorInFinalTable = DB::table('crpcontractorfinal')->where('Id',$contractor)->count();
			if($contractorInFinalTable == 0){
				$contractorEquipments=ContractorEquipmentModel::contractorEquipment($contractor)->get(array('crpcontractorequipment.Id','crpcontractorequipment.RegistrationNo','crpcontractorequipment.ModelNo','crpcontractorequipment.Quantity','T1.Name'));
				$equipmentsAttachments=ContractorEquipmentFinalModel::equipmentAttachments($contractor)->get(array('T1.Id','T1.CrpContractorEquipmentFinalId','T1.DocumentName','T1.DocumentPath'));
			}
		}
		return View::make('crps.contractoreditequipment')
			->with('changeModel',$changeModel)
			->with('afterSaveRedirect',$afterSaveRedirect)
			->with('serviceByContractor',$serviceByContractor)
			->with('newEquipmentSave',$newEquipmentSave)
			->with('isEditByCDBUser',$isEditByCDBUser)
			->with('equipmentEditRoute',$equipmentEditRoute)
			->with('redirectUrl',$redirectUrl)
			->with('isEdit',$contractor)
			->with('editPage',$editPage)
			->with('contractorId',$contractor)
			->with('equipments',$equipments)
			->with('contractorEquipments',$contractorEquipments)
			->with('equipmentsAttachments',$equipmentsAttachments)
			->with('equipmentEdit',$equipmentEdit)
			->with('equipmentAttachments',$equipmentAttachments);
	}
	public function saveGeneralInfo(){
		$postedValues=Input::except('ChangeOfLocationOwner','OtherServices','attachments','DocumentName','DocumentNameOwnerShipChange','attachmentsownershipchange','attachmentsfirmnamechange','DocumentNameFirmNameChange','attachmentsinduction','DocumentNameInductionCourse');
		$newGeneralInfoSave=Input::get('NewGeneralInfoSave');
		$isServiceByContractor=Input::get('ServiceByContractor');
		$validation = new ContractorModel;
		if(!($validation->validate($postedValues))){
			$errors = $validation->errors();
			if((int)$isServiceByContractor!=1){
				return Redirect::to('contractor/generalinforegistration')->withInput()->withErrors($errors);
			}else{
				return Redirect::to('contractor/applyservicegeneralinfo/'.Input::get('CrpContractorId'))->withInput()->withErrors($errors);
			}
		}

		/*To check if already applied */
		if(!Input::has('OldApplicationId')){
			$isFinalContractor = DB::table('crpcontractorfinal')->where('Id',Input::get('CrpContractorId'))->count();
			if($isFinalContractor==0){
				$finalTableId = DB::table('crpcontractor')->where('Id',Input::get('CrpContractorId'))->pluck('CrpContractorId');
			}else{
				$finalTableId = Input::get('CrpContractorId');
			}
			$previousApplications = DB::table('crpcontractor')->whereNotNull('CrpContractorId')->where('CrpContractorId',$finalTableId)->where(DB::raw('coalesce(RegistrationStatus,0)'),1)->whereNotIn('CmnApplicationRegistrationStatusId',array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_REJECTED))->count();
			if($previousApplications>0){
				$previousApplicationDetails = DB::table('crpcontractor')->where('CrpContractorId',$finalTableId)->where(DB::raw('coalesce(RegistrationStatus,0)'),1)->whereNotIn('CmnApplicationRegistrationStatusId',array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_REJECTED))->select(DB::raw("GROUP_CONCAT(CONCAT('Application No. ',ReferenceNo,' dt. ',ApplicationDate) SEPARATOR '<br/>') as Applications"))->pluck('Applications');
				return Redirect::to('contractor/mydashboard')->with("customerrormessage","<h4><strong> MESSAGE! You have following pending application(s) with CDB: </strong></h4><ol>$previousApplicationDetails</ol><strong>Please wait for us to process your previous application before submitting a new one!</strong> ");
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
					$destination=public_path().'/uploads/contractors';
					$destinationDB='uploads/contractors/'.$attachmentName;
					$multiAttachments1["DocumentName"]=(count($documentName)>1)?$documentName[$count]:$documentName[0];

					//CHECK IF IMAGE
					if(strpos($attachment->getClientMimeType(),'image/')>-1){
						$img = Image::make($attachment)->encode('jpg');
						$destinationDB = "uploads/contractors/".str_random(15) . '_min_' .".jpg";
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
					$destination=public_path().'/uploads/contractors';
					$destinationDB='uploads/contractors/'.$attachmentName;
					if(isset($documentName[$countownershipchange])){
						$multiAttachmentsownershipchange1["DocumentName"]=$documentName[$countownershipchange];

						//CHECK IF IMAGE
						if(strpos($attachmentownership->getClientMimeType(),'image/')>-1){
							$img = Image::make($attachmentownership)->encode('jpg');
							$destinationDB = "uploads/contractors/".str_random(15) . '_min_' .".jpg";
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
		if(Input::hasFile('attachmentsinduction')){
			$countownershipchange = 0;
			$multiAttachmentsInductionCourse=array();
			foreach(Input::file('attachmentsinduction') as $attachmentinduction){
				if((bool)$attachmentinduction){
					$documentName = Input::get("DocumentNameInductionCourse");
					$attachmentType=$attachmentinduction->getMimeType();
					$attachmentFileName=$attachmentinduction->getClientOriginalName();
					$attachmentName=str_random(6) . '_' . $attachmentinduction->getClientOriginalName();
					$destination=public_path().'/uploads/contractors';
					$destinationDB='uploads/contractors/'.$attachmentName;
					if(isset($documentName[$countownershipchange])){
						$multiAttachmentInductionCourse1["DocumentName"]=$documentName[$countownershipchange];

						//CHECK IF IMAGE
						if(strpos($attachmentinduction->getClientMimeType(),'image/')>-1){
							$img = Image::make($attachmentinduction)->encode('jpg');
							$destinationDB = "uploads/contractors/".str_random(15) . '_min_' .".jpg";
							$img->save($destinationDB,45);
							$attachmentType = "image/jpeg";
						}else{
							$uploadAttachments=$attachmentinduction->move($destination, $attachmentName);
						}
						//

						$multiAttachmentInductionCourse1["DocumentPath"]=$destinationDB;
						$multiAttachmentInductionCourse1["FileType"]=$attachmentType;
						array_push($multiAttachmentsInductionCourse, $multiAttachmentInductionCourse1);
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
					$destination=public_path().'/uploads/contractors';
					$destinationDB='uploads/contractors/'.$attachmentName;
					$multiAttachmentsownershipchange1["DocumentName"]=$documentName[$countfirmnamechange];

					//CHECK IF IMAGE
					if(strpos($attachmentfirmnamechange->getClientMimeType(),'image/')>-1){
						$img = Image::make($attachmentfirmnamechange)->encode('jpg');
						$destinationDB = "uploads/contractors/".str_random(15) . '_min_' .".jpg";
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
			$postedValues["ReferenceNo"]=$this->tableTransactionNo('ContractorModel','ReferenceNo');
			DB::beginTransaction();
			try{
				if(Input::has('ChangeOfLocationOwner')):
					$changeOfOwnerLocation=Input::get('ChangeOfLocationOwner');
					if($changeOfOwnerLocation){
						foreach($changeOfOwnerLocation as $xService):
							if($xService == CONST_SERVICETYPE_CHANGEOWNER){
								$ownerPartnerInputs = Input::get('ContractorHumanResourceModel');
								$oldOwnerPartners = DB::table('crpcontractorhumanresourcefinal')
									->where('CrpContractorFinalId',Input::get('CrpContractorId'))
									->where(DB::raw('coalesce(IsPartnerOrOwner,0)'),1)
									->get(array('CIDNo','Name','CmnCountryId','CmnDesignationId'));
								foreach($oldOwnerPartners as $oldOwnerPartner):
									$cidNo = $oldOwnerPartner->CIDNo;
									$name = $oldOwnerPartner->Name;
									$cmnCountryId = $oldOwnerPartner->CmnCountryId;
									$cmnDesignationId = $oldOwnerPartner->CmnDesignationId;
									DB::table('crpcontractorhrtrack')->insert(array('CIDNo'=>$cidNo,'ReferenceNo'=>$postedValues["ReferenceNo"],'Date'=>date('Y-m-d G:i:s'),'CrpContractorFinalId'=>Input::get('CrpContractorId'),'Name'=>$name,'CmnCountryId'=>$cmnCountryId,'CmnDesignationId'=>$cmnDesignationId));
								endforeach;
							}
						endforeach;
					}
				endif;

				if(Input::has('OldApplicationId')){
					$generatedId = Input::get('OldApplicationId');
					$instanceOfClass = ContractorModel::find($generatedId);
					$instanceOfClass->fill($postedValues);
					$instanceOfClass->update();
				}else{
					ContractorModel::create($postedValues);
				}
				if(Input::hasFile('attachments')){
					foreach($multiAttachments as $k=>$v){
						if(isset($isEditByCdb) && (int)$isEditByCdb==1 && $newGeneralInfoSave!=null  && (int)$newGeneralInfoSave==0){
							$multiAttachments[$k]["CrpContractorFinalId"]=$generatedId;
							$saveUploads=new ContractorAttachmentFinalModel($multiAttachments[$k]);
						}else{
							$multiAttachments[$k]["CrpContractorId"]=$generatedId;
							$saveUploads=new ContractorAttachmentModel($multiAttachments[$k]);
						}
						$saveUploads->save();
					}
				}
				if(Input::hasFile('attachmentsownershipchange')){
					foreach($multiAttachmentsownershipchange as $k=>$v){
						if(isset($isEditByCdb) && (int)$isEditByCdb==1 && $newGeneralInfoSave!=null  && (int)$newGeneralInfoSave==0){
							$multiAttachmentsownershipchange[$k]["CrpContractorFinalId"]=$generatedId;
							$saveUploads=new ContractorAttachmentFinalModel($multiAttachmentsownershipchange[$k]);
						}else{
							$multiAttachmentsownershipchange[$k]["CrpContractorId"]=$generatedId;
							$saveUploads=new ContractorAttachmentModel($multiAttachmentsownershipchange[$k]);
						}
						$saveUploads->save();
					}
				}
				if(Input::hasFile('attachmentsinduction')){
					foreach($multiAttachmentsInductionCourse as $k=>$v){
						if(isset($isEditByCdb) && (int)$isEditByCdb==1 && $newGeneralInfoSave!=null  && (int)$newGeneralInfoSave==0){
							$multiAttachmentsInductionCourse[$k]["CrpContractorFinalId"]=$generatedId;
							$saveUploads=new ContractorAttachmentFinalModel($multiAttachmentsInductionCourse[$k]);
						}else{
							$multiAttachmentsInductionCourse[$k]["CrpContractorId"]=$generatedId;
							$saveUploads=new ContractorAttachmentModel($multiAttachmentsInductionCourse[$k]);
						}
						$saveUploads->save();
					}
				}
				if(Input::hasFile('attachmentsfirmnamechange')){
					foreach($multiAttachmentsFirmNameChange as $k=>$v){
						if(isset($isEditByCdb) && (int)$isEditByCdb==1 && $newGeneralInfoSave!=null  && (int)$newGeneralInfoSave==0){
							$multiAttachmentsFirmNameChange[$k]["CrpContractorFinalId"]=$generatedId;
							$saveUploads=new ContractorAttachmentFinalModel($multiAttachmentsFirmNameChange[$k]);
						}else{
							$multiAttachmentsFirmNameChange[$k]["CrpContractorId"]=$generatedId;
							$saveUploads=new ContractorAttachmentModel($multiAttachmentsFirmNameChange[$k]);
						}
						$saveUploads->save();
					}
				}
				if(Input::has('RenewalService') && (int)$isServiceByContractor==1){
					$lateRenewalExpiryDate=ContractorFinalModel::contractorHardList($postedValues['CrpContractorId'])->pluck('RegistrationExpiryDate');
					$lateRenewalExpiryDate=strtotime($lateRenewalExpiryDate);
					$currentDate=strtotime(date('Y-m-d'));
					$appliedServiceRenewal = new ContractorAppliedServiceModel;
					$appliedServiceRenewal->CrpContractorId=$generatedId;
					$appliedServiceRenewal->CmnServiceTypeId = Input::get('RenewalService');
					$appliedServiceRenewal->save();
					if($currentDate>$lateRenewalExpiryDate){
						$appliedServiceRenewalLateFee = new ContractorAppliedServiceModel;
						$appliedServiceRenewalLateFee->CrpContractorId=$generatedId;
						$appliedServiceRenewalLateFee->CmnServiceTypeId = CONST_SERVICETYPE_LATEFEE;
						$appliedServiceRenewalLateFee->save();
					}
				}
				if(Input::has('ChangeOfLocationOwner') && (int)$isServiceByContractor==1){
					$changeOfOwnerLocation=Input::get('ChangeOfLocationOwner');
					for($idx = 0; $idx < count($changeOfOwnerLocation); $idx++){
						$appliedService = new ContractorAppliedServiceModel;
						$appliedService->CrpContractorId=$generatedId;
						$appliedService->CmnServiceTypeId = $changeOfOwnerLocation[$idx];
						$appliedService->save();
					}
				}

				if(Input::has('OtherServices') && (int)$isServiceByContractor==1){
					$otherServices=Input::get('OtherServices');
					for($idx = 0; $idx < count($otherServices); $idx++){
						$appliedService = new ContractorAppliedServiceModel;
						$appliedService->CrpContractorId=$generatedId;
						$appliedService->CmnServiceTypeId = $otherServices[$idx];
						$appliedService->save();
					}
				}
				foreach($postedValues as $key=>$value){
					if(gettype($value)=='array'){
						foreach($value as $key1=>$value1){
							$value1['CrpContractorId']=$generatedId;
							$childTable= new ContractorHumanResourceModel($value1);
							$a=$childTable->save();
						}
					}
				}

			}catch(Exception $e){
				DB::rollback();
				throw $e;
			}
			DB::commit();
			if((int)$isServiceByContractor==1){
				// if(Input::has('PostBackUrl')){
				// 	$postBackUrl = Input::get('PostBackUrl');
				// 	if(!empty($postBackUrl)){
				// 		return Redirect::to($postBackUrl.'/'.Input::get('CrpContractorId'));
				// 	}
				// }

				if(Input::has('OldApplicationId')){
					$servicesAppliedByContractor = DB::table('crpcontractorappliedservice')->where('CrpContractorId',Input::get("OldApplicationId"))->lists('CmnServiceTypeId');
				}else{
					$servicesAppliedByContractor = DB::table('crpcontractorappliedservice')->where('CrpContractorId',$generatedId)->lists('CmnServiceTypeId');
				}


				if(in_array(CONST_SERVICETYPE_CHANGEOFCATEGORYCLASSIFICATION,$servicesAppliedByContractor)):
					return Redirect::to('contractor/applyserviceworkclassification/'.$generatedId);
				endif;
				if(in_array(CONST_SERVICETYPE_RENEWAL,$servicesAppliedByContractor)):
					return Redirect::to('contractor/applyserviceworkclassification/'.$generatedId);
				endif;
				if(in_array(CONST_SERVICETYPE_UPDATEHUMANRESOURCE,$servicesAppliedByContractor)):
					$isEditByCdb=Input::get('EditByCdb');
					$redirectTo=Input::get('PostBackUrl');
					if(isset($isEditByCdb) && (int)$isEditByCdb==1){
						return Redirect::to('contractor/applyservicehumanresource'.'/'.$generatedId.'?redirectUrl='.$redirectTo)->with('savedsuccessmessage','Work Classification has been successfully updated.');
					}
				endif;
				if(in_array(CONST_SERVICETYPE_UPDATEEQUIPMENT,$servicesAppliedByContractor)):
					return Redirect::to('contractor/applyserviceequipment/'.$generatedId);
				endif;
				return Redirect::to('contractor/applyserviceconfirmation/'.$generatedId);
			}else{
				Session::put('ContractorRegistrationId',$generatedId);
				return Redirect::to('contractor/workclassificationregistration');
			}
		}else{
			$isEditByCdb=Input::get('EditByCdb');
			$redirectTo=Input::get('PostBackUrl');
			$isRejectedApp=Input::get('ApplicationRejectedReapply');
			DB::beginTransaction();
			try{
				if(isset($isEditByCdb) && (int)$isEditByCdb==1 && $newGeneralInfoSave!=null && (int)$newGeneralInfoSave==0){
					$instance=ContractorFinalModel::find($postedValues['Id']);
				}else{
					$instance=ContractorModel::find($postedValues['Id']);
				}
				$instance->fill($postedValues);
				$instance->update();
				if(Input::hasFile('attachments')){
					foreach($multiAttachments as $k=>$v){
						if(isset($isEditByCdb) && (int)$isEditByCdb==1 && $newGeneralInfoSave!=null  && (int)$newGeneralInfoSave==0){
							$multiAttachments[$k]["CrpContractorFinalId"]=$postedValues['Id'];
							$saveUploads=new ContractorAttachmentFinalModel($multiAttachments[$k]);
						}else{
							$multiAttachments[$k]["CrpContractorId"]=$postedValues['Id'];
							$saveUploads=new ContractorAttachmentModel($multiAttachments[$k]);
						}
						$saveUploads->save();
					}
				}
				if(Input::hasFile('attachmentsownershipchange')){
					foreach($multiAttachmentsownershipchange as $k=>$v){
						if(isset($isEditByCdb) && (int)$isEditByCdb==1 && $newGeneralInfoSave!=null  && (int)$newGeneralInfoSave==0){
							$multiAttachmentsownershipchange[$k]["CrpContractorFinalId"]=$postedValues['Id'];
							$saveUploads=new ContractorAttachmentFinalModel($multiAttachmentsownershipchange[$k]);
						}else{
							$multiAttachmentsownershipchange[$k]["CrpContractorId"]=$postedValues['Id'];
							$saveUploads=new ContractorAttachmentModel($multiAttachmentsownershipchange[$k]);
						}
						$saveUploads->save();
					}
				}
				if(Input::hasFile('attachmentsfirmnamechange')){
					foreach($multiAttachmentsFirmNameChange as $k=>$v){
						if(isset($isEditByCdb) && (int)$isEditByCdb==1 && $newGeneralInfoSave!=null  && (int)$newGeneralInfoSave==0){
							$multiAttachmentsFirmNameChange[$k]["CrpContractorFinalId"]=$postedValues['Id'];
							$saveUploads=new ContractorAttachmentFinalModel($multiAttachmentsFirmNameChange[$k]);
						}else{
							$multiAttachmentsFirmNameChange[$k]["CrpContractorId"]=$postedValues['Id'];
							$saveUploads=new ContractorAttachmentModel($multiAttachmentsFirmNameChange[$k]);
						}
						$saveUploads->save();
					}
				}
				if(Input::hasFile('attachmentsinduction')){
					if(isset($isEditByCdb) && (int)$isEditByCdb==1 && $newGeneralInfoSave!=null  && (int)$newGeneralInfoSave==0){
						DB::table('crpcontractorattachmentfinal')->where('DocumentName','Induction Course Certificate')->where("CrpContractorFinalId",$postedValues["Id"])->delete();
					}else{
						DB::table('crpcontractorattachment')->where('DocumentName','Induction Course Certificate')->where("CrpContractorId",$postedValues["Id"])->delete();
					}
					foreach($multiAttachmentsInductionCourse as $k=>$v){
						if(isset($isEditByCdb) && (int)$isEditByCdb==1 && $newGeneralInfoSave!=null  && (int)$newGeneralInfoSave==0){
							$multiAttachmentsInductionCourse[$k]["CrpContractorFinalId"]=$postedValues['Id'];
							$saveUploads=new ContractorAttachmentFinalModel($multiAttachmentsInductionCourse[$k]);
						}else{
							$multiAttachmentsInductionCourse[$k]["CrpContractorId"]=$postedValues['Id'];
							$saveUploads=new ContractorAttachmentModel($multiAttachmentsInductionCourse[$k]);
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

								if(isset($isEditByCdb) && (int)$isEditByCdb==1 && $newGeneralInfoSave!=null && (int)$newGeneralInfoSave==0){
									$value1['CrpContractorFinalId']=$postedValues['Id'];
									$childTable= new ContractorHumanResourceFinalModel($value1);
								}else{
									$value1['CrpContractorId']=$postedValues['Id'];
									$childTable= new ContractorHumanResourceModel($value1);
								}
								$a=$childTable->save();
							}else{
								if(isset($isEditByCdb) && (int)$isEditByCdb==1 && $newGeneralInfoSave!=null && (int)$newGeneralInfoSave==0){
									//ADDED BY SWM ON 7th June
									if(!isset($value1['ShowInCertificate'])) {
										$value1['ShowInCertificate'] = 0;
									}
									//ADDED BY SWM on 7th June
									$childTable1=ContractorHumanResourceFinalModel::find($value1['Id']);
								}else{
									//ADDED BY SWM ON 7th June
									if(!isset($value1['ShowInCertificate'])) {
										$value1['ShowInCertificate'] = 0;
									}
									//ADDED BY SWM on 7th June
									$childTable1=ContractorHumanResourceModel::find($value1['Id']);
								}
								$childTable1->fill($value1);
								$childTable1->update();
							}
						}
					}
				}
				DB::commit();
				$isNewRegistration = DB::table('crpcontractor')->where('Id',$postedValues['Id'])->pluck('CrpContractorId');
				if(isset($isRejectedApp) && (int)$isRejectedApp==1){
					if(!(bool)$isNewRegistration){
						Session::put('ContractorRegistrationId',$postedValues["Id"]);
						return Redirect::to('contractor/workclassificationregistration?rejectedapplicationreapply=true');
					}
				}

				if(isset($isEditByCdb) && (int)$isEditByCdb==1){
					if((bool)$redirectTo){
						return Redirect::to($redirectTo.'/'.$postedValues["Id"])->with('savedsuccessmessage','General Information has been successfully updated.');
					}else{
						Session::put('ContractorRegistrationId',$postedValues['Id']);
						if(Input::has('OldApplicationId')){
							$currentServicesApplied = array_merge(Input::has('ChangeOfLocationOwner')?Input::get('ChangeOfLocationOwner'):array(),Input::has('OtherServices')?Input::get('OtherServices'):array());
							$servicesAppliedByContractor = DB::table('crpcontractorappliedservice')->where('CrpContractorId',$postedValues["Id"])->lists('CmnServiceTypeId');
							foreach($currentServicesApplied as $currentService):
								if(!in_array($currentService,$servicesAppliedByContractor)){
									$appliedService = new ContractorAppliedServiceModel;
									$appliedService->CrpContractorId=$postedValues["Id"];
									$appliedService->CmnServiceTypeId = $currentService;
									$appliedService->save();
								}
							endforeach;
							if(in_array(CONST_SERVICETYPE_CHANGEOFCATEGORYCLASSIFICATION,$servicesAppliedByContractor)):
								return Redirect::to('contractor/applyserviceworkclassification/'.$postedValues["Id"]);
							endif;
							if(in_array(CONST_SERVICETYPE_RENEWAL,$servicesAppliedByContractor)):
								return Redirect::to('contractor/applyserviceworkclassification/'.$postedValues["Id"]);
							endif;
							if(in_array(CONST_SERVICETYPE_UPDATEHUMANRESOURCE,$servicesAppliedByContractor)):
								$isEditByCdb=Input::get('EditByCdb');
								$redirectTo=Input::get('PostBackUrl');
								if(isset($isEditByCdb) && (int)$isEditByCdb==1){
									return Redirect::to('contractor/applyservicehumanresource'.'/'.$postedValues["Id"].'?redirectUrl='.$redirectTo)->with('savedsuccessmessage','Work Classification has been successfully updated.');
								}
							endif;
							if(in_array(CONST_SERVICETYPE_UPDATEEQUIPMENT,$servicesAppliedByContractor)):
								return Redirect::to('contractor/applyserviceequipment/'.$postedValues["Id"]);
							endif;
							return Redirect::to('contractor/applyserviceconfirmation/'.$postedValues["Id"]);
						}
						return Redirect::to('contractor/applyservicehumanresource/'.$postedValues["Id"])->with('savedsuccessmessage','General Information has been successfully updated.');
					}
				}

				return Redirect::to('contractor/confirmregistration')->with('savedsuccessmessage','General Information has been successfully updated.');
			}catch(Exception $e){
				DB::rollback();
				throw $e;
			}
		}
	}
	public function saveWorkClassification(){
		$postedValues=Input::except('attachments','DocumentName');
		if(!isset($postedValues['ContractorWorkClassificationModel'])){
			return Redirect::back()->with('customerrormessage','Please select at least one category and class');
		}
		$isServiceByContractor=Input::get('ServiceByContractor');
		$isEdit=Input::get('IsEdit');
		$contractorId=Input::get('CrpContractorId');
		DB::beginTransaction();
		try{
			ContractorWorkClassificationModel::where('CrpContractorId',$postedValues['CrpContractorId'])->delete();
			$appliedServiceCount=ContractorAppliedServiceModel::where('CrpContractorId',$postedValues['CrpContractorId'])->where('CmnServiceTypeId',CONST_SERVICETYPE_CHANGEOFCATEGORYCLASSIFICATION)->count();
			if($appliedServiceCount==0){
				if(Input::has('ChangeInCategoryClassificationService') && (int)$isServiceByContractor==1){
					$appliedService = new ContractorAppliedServiceModel;
					$appliedService->CrpContractorId=Input::get('CrpApplicationContractorId');
					$appliedService->CmnServiceTypeId = Input::get('ChangeInCategoryClassificationService');
					$appliedService->save();
				}
			}
			if(Input::hasFile('attachments')){
				$count = 0;
				$multiAttachments=array();
				foreach(Input::file('attachments') as $attachment){
					if((bool)$attachment){
						$documentName = Input::get("DocumentName");
						$attachmentType=$attachment->getMimeType();
						$attachmentFileName=$attachment->getClientOriginalName();
						$attachmentName=str_random(6) . '_' . $attachment->getClientOriginalName();
						$destination=public_path().'/uploads/contractors';
						$destinationDB='uploads/contractors/'.$attachmentName;
						$multiAttachments1["DocumentName"]=$documentName[$count];

						//CHECK IF IMAGE
						if(strpos($attachment->getClientMimeType(),'image/')>-1){
							$img = Image::make($attachment)->encode('jpg');
							$destinationDB = "uploads/contractors/".str_random(15) . '_min_' .".jpg";
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
					if(isset($isEditByCdb) && (int)$isEditByCdb==1){
						$multiAttachments[$k]["CrpContractorFinalId"]=Input::get('CrpApplicationContractorId');
						$saveUploads=new ContractorAttachmentFinalModel($multiAttachments[$k]);
					}else{
						$multiAttachments[$k]["CrpContractorId"]=Input::get('CrpApplicationContractorId');
						$saveUploads=new ContractorAttachmentModel($multiAttachments[$k]);
					}
					$saveUploads->save();
				}
			}
			foreach($postedValues as $key=>$value){
				if(gettype($value)=='array'){
					foreach($value as $key1=>$value1){
						$childTable= new ContractorWorkClassificationModel($value1);
						$a=$childTable->save();
					}
				}
			}
		}catch(Exception $e){
			DB::rollback();
			throw $e;
		}
		DB::commit();
		$isEditByCdb=Input::get('EditByCdb');
		$redirectTo=Input::get('PostBackUrl');
		if(isset($isEditByCdb) && (int)$isEditByCdb==1){
			if((bool)$redirectTo)
				if($redirectTo!='contractor/applyserviceequipment' && $redirectTo!='contractor/applyservicehumanresource')
					return Redirect::to($redirectTo.'/'.$postedValues["CrpContractorId"].'?redirectUrl='.$redirectTo)->with('savedsuccessmessage','Work Classification has been successfully updated.');
			$servicesAppliedByContractor = DB::table('crpcontractorappliedservice')->where('CrpContractorId',$postedValues["CrpContractorId"])->lists('CmnServiceTypeId');
			if(in_array(CONST_SERVICETYPE_UPDATEHUMANRESOURCE,$servicesAppliedByContractor)):
				$isEditByCdb=Input::get('EditByCdb');
				$redirectTo=Input::get('PostBackUrl');
				if(isset($isEditByCdb) && (int)$isEditByCdb==1){
					return Redirect::to('contractor/applyservicehumanresource'.'/'.$postedValues["CrpContractorId"].'?redirectUrl='.$redirectTo)->with('savedsuccessmessage','Work Classification has been successfully updated.');
				}
			endif;
			if(in_array(CONST_SERVICETYPE_UPDATEEQUIPMENT,$servicesAppliedByContractor)):
				return Redirect::to('contractor/applyserviceequipment/'.$postedValues["CrpContractorId"]);
			endif;
			return Redirect::to('contractor/applyserviceconfirmation/'.$postedValues["CrpContractorId"]);
		}
		if((bool)$isEdit==null){
			return Redirect::to('contractor/humanresourceregistration');
		}else{
			return Redirect::to('contractor/confirmregistration')->with('savedsuccessmessage','Work Classification has been successfully updated.');
		}
	}
	public function saveHumanResource(){
		$save=true;
		$postedValues=Input::all();
		if(isset($postedValues['JoiningDate']))
			$postedValues['JoiningDate'] = $this->convertDate($postedValues['JoiningDate']);
		$hasCDBEdit=Input::get('HasCDBEdit');
		$contractorId=Input::get('CrpContractorId');
		$applicationDate = DB::table('crpcontractor')->where('Id',$contractorId)->pluck('ApplicationDate');
		if(!empty($postedValues['Id']))
			DB::table('crpcontractorhumanresourceattachment')->where('CrpContractorHumanResourceId',$postedValues['Id'])->where('CreatedOn','<',$applicationDate)->delete();
		$isServiceByContractor=Input::get('ServiceByContractor');
		$newHumanResourceSave=Input::get('NewHumanResourceSave');
		$isEditByCdb=Input::get('EditByCdb');
		$redirectTo=Input::get('PostBackUrl');
		$multiAttachments=array();
		$uuid=DB::select("select uuid() as Id");
		$generatedId=$uuid[0]->Id;
		$validation = new ContractorHumanResourceModel;
		$redirectToEdit = false;
		if(!($validation->validate($postedValues))){
			$errors = $validation->errors();
			if(empty($postedValues["Id"])){
				return Redirect::to('contractor/humanresourceregistration')->withInput()->withErrors($errors);
			}else{
				return Redirect::to('contractor/humanresourceregistration/'.$postedValues['CrpContractorId'])->withInput()->withErrors($errors);
			}
		}
		DB::beginTransaction();
		try{
			if(empty($postedValues["Id"])){
				$postedValues["Id"]=$generatedId;
				if(isset($isEditByCdb) && (int)$isEditByCdb==1 && $newHumanResourceSave!=null && (int)$newHumanResourceSave==0){
					$postedValues["CrpContractorFinalId"]=$contractorId;
					$instance = ContractorFinalModel::find($contractorId);
					if(!(bool)$instance){
						ContractorHumanResourceModel::create($postedValues);
					}else{
						ContractorHumanResourceFinalModel::create($postedValues);
					}

				}else{
					ContractorHumanResourceModel::create($postedValues);
				}
				$appliedServiceCount=ContractorAppliedServiceModel::where('CrpContractorId',$postedValues['CrpContractorId'])->where('CmnServiceTypeId',CONST_SERVICETYPE_UPDATEHUMANRESOURCE)->count();
				if($appliedServiceCount==0){
					if(Input::has('UpdateHumanResourceService') && (int)$isServiceByContractor==1){
						$appliedService = new ContractorAppliedServiceModel;
						$appliedService->CrpContractorId=$contractorId;
						$appliedService->CmnServiceTypeId = Input::get('UpdateHumanResourceService');
						$appliedService->save();
					}
				}
			}else{
				$save=false;
				if(isset($isEditByCdb) && (int)$isEditByCdb==1 && $newHumanResourceSave!=null && (int)$newHumanResourceSave==0){
					$instance=ContractorHumanResourceFinalModel::find($postedValues['Id']);
					if(!(bool)$instance){
						$redirectToEdit = true;
						$instance=ContractorHumanResourceModel::find($postedValues['Id']);
					}
				}else{
					$instance=ContractorHumanResourceModel::find($postedValues['Id']);
				}
				if($instance){
					$instance->fill($postedValues);
					$instance->update();
				}else{
					ContractorHumanResourceModel::create($postedValues);
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
							$destination=public_path().'/uploads/contractors';
							$destinationDB='uploads/contractors/'.$attachmentName;
							$multiAttachments1["DocumentName"]=isset($documentName[$count])?$documentName[$count]:'Contractor Document';

							//CHECK IF IMAGE
							if(strpos($attachment->getClientMimeType(),'image/')>-1){
								$img = Image::make($attachment)->encode('jpg');
								$destinationDB = "uploads/contractors/".str_random(15) . '_min_' .".jpg";
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
						if(isset($isEditByCdb) && (int)$isEditByCdb==1 && $newHumanResourceSave!=null  && (int)$newHumanResourceSave==0){
							$multiAttachments[$k]["CrpContractorHumanResourceFinalId"]=$generatedId;
						}else{
							$multiAttachments[$k]["CrpContractorHumanResourceId"]=$generatedId;
						}
					}else{
						if(isset($isEditByCdb) && (int)$isEditByCdb==1 && $newHumanResourceSave!=null  && (int)$newHumanResourceSave==0){
							$instance = ContractorFinalModel::find($contractorId);
							if(!(bool)$instance){
								$multiAttachments[$k]["CrpContractorHumanResourceId"]=$postedValues['Id'];
							}else{
								$multiAttachments[$k]["CrpContractorHumanResourceFinalId"]=$postedValues['Id'];
							}
						}else{
							$multiAttachments[$k]["CrpContractorHumanResourceId"]=$postedValues['Id'];
						}
					}

					//END
					if(isset($isEditByCdb) && (int)$isEditByCdb==1 && $newHumanResourceSave!=null && (int)$newHumanResourceSave==0){
						$instance = ContractorFinalModel::find($contractorId);
						if(!(bool)$instance){
							$saveUploads=new ContractorHumanResourceAttachmentModel($multiAttachments[$k]);
						}else{
							$saveUploads=new ContractorHumanResourceAttachmentFinalModel($multiAttachments[$k]);
						}

					}else{

						$saveUploads=new ContractorHumanResourceAttachmentModel($multiAttachments[$k]);
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
		if(isset($isEditByCdb) && (int)$isEditByCdb==1){
			if(!empty($hasCDBEdit)){
				if($redirectToEdit){
					return Redirect::to($humanResourceEditPage.'/'.$postedValues["CrpContractorId"].'?redirectUrl='.$redirectTo)->with('savedsuccessmessage','Human Resource has been successfully updated.');
				}
				return Redirect::to($humanResourceEditPage.'/'.$postedValues["CrpContractorId"].'?redirectUrl='.$redirectTo.'&usercdb=edit')->with('savedsuccessmessage','Human Resource has been successfully updated.');
			}else{
				return Redirect::to($humanResourceEditPage.'/'.$postedValues["CrpContractorId"].'?redirectUrl='.$redirectTo)->with('savedsuccessmessage','Human Resource has been successfully updated.');
			}
		}
		if($save){
			return Redirect::to('contractor/humanresourceregistration');
		}else{
			return Redirect::to('contractor/humanresourceregistration/'.$postedValues['CrpContractorId'])->with('savedsuccessmessage','Human Resource has been successfully updated.');;
		}
	}
	public function saveEquipment(){
		$save=true;
		$postedValues=Input::all();
		$hasCDBEdit=Input::get('HasCDBEdit');
		$contractorId=Input::get('CrpContractorId');
		$applicationDate = DB::table('crpcontractor')->where('Id',$contractorId)->pluck('ApplicationDate');
		if(!empty($postedValues['Id']))
			DB::table('crpcontractorequipmentattachment')->where('CrpContractorEquipmentId',$postedValues['Id'])->where('CreatedOn','<',$applicationDate)->delete();
		$isServiceByContractor=Input::get('ServiceByContractor');
		$newEquipmentSave=Input::get('NewEquipmentSave');
		$isEditByCdb=Input::get('EditByCdb');
		$redirectTo=Input::get('PostBackUrl');
		$multiAttachments=array();
		$uuid=DB::select("select uuid() as Id");
		$generatedId=$uuid[0]->Id;
		$validation = new ContractorEquipmentModel;
		$redirectToEdit = false;
		if(!($validation->validate($postedValues))){
			$errors = $validation->errors();
			if(empty($postedValues["Id"])){
				return Redirect::to('contractor/equipmentregistration')->withInput()->withErrors($errors);
			}else{
				return Redirect::to('contractor/equipmentregistration/'.$postedValues['CrpContractorId'])->withInput()->withErrors($errors);
			}
		}
		DB::beginTransaction();
		try{
			if(empty($postedValues["Id"])){
				$postedValues["Id"]=$generatedId;
				if(isset($isEditByCdb) && (int)$isEditByCdb==1 && $newEquipmentSave!=null && (int)$newEquipmentSave==0){
					$initialTableCount = DB::table('crpcontractor')->where('Id',$contractorId)->count();
					$finalTableCount = DB::table('crpcontractorfinal')->where('Id',$contractorId)->count();

					if($initialTableCount==1 && $finalTableCount == 0){
						$postedValues["CrpContractorId"]=$contractorId;
						ContractorEquipmentModel::create($postedValues);
					}else{
						$postedValues["CrpContractorFinalId"]=$contractorId;
						ContractorEquipmentFinalModel::create($postedValues);
					}

				}else{
					ContractorEquipmentModel::create($postedValues);
				}
				$appliedServiceCount=ContractorAppliedServiceModel::where('CrpContractorId',$postedValues['CrpContractorId'])->where('CmnServiceTypeId',CONST_SERVICETYPE_UPDATEEQUIPMENT)->count();
				if($appliedServiceCount==0){
					if(Input::has('UpdateEquipmentService') && (int)$isServiceByContractor==1){
						$appliedService = new ContractorAppliedServiceModel;
						$appliedService->CrpContractorId=$contractorId;
						$appliedService->CmnServiceTypeId = Input::get('UpdateEquipmentService');
						$appliedService->save();
					}
				}
			}else{
				$save=false;
				if(isset($isEditByCdb) && (int)$isEditByCdb==1 && $newEquipmentSave!=null && (int)$newEquipmentSave==0){
					$instance=ContractorEquipmentFinalModel::find($postedValues['Id']);
					if(!(bool)$instance){
						$redirectToEdit = true;
						$instance=ContractorEquipmentModel::find($postedValues['Id']);
					}
				}else{
					$instance=ContractorEquipmentModel::find($postedValues['Id']);
				}
				if($instance){
					$instance->fill($postedValues);
					$instance->update();
				}else{
					ContractorEquipmentModel::create($postedValues);
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
						$destination=public_path().'/uploads/contractors';
						$destinationDB='uploads/contractors/'.$attachmentName;
						$multiAttachments1["DocumentName"]=$documentName[$count];

						//CHECK IF IMAGE
						if(strpos($attachment->getClientMimeType(),'image/')>-1){
							$img = Image::make($attachment)->encode('jpg');
							$destinationDB = "uploads/contractors/".str_random(15) . '_min_' .".jpg";
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
						if(isset($isEditByCdb) && (int)$isEditByCdb==1 && $newEquipmentSave!=null && (int)$newEquipmentSave==0){
							$multiAttachments[$k]["CrpContractorEquipmentFinalId"]=$generatedId;
						}else{
							$multiAttachments[$k]["CrpContractorEquipmentId"]=$generatedId;
						}
					}else{
						if(isset($isEditByCdb) && (int)$isEditByCdb==1 && $newEquipmentSave!=null && (int)$newEquipmentSave==0){
							$multiAttachments[$k]["CrpContractorEquipmentFinalId"]=$postedValues['Id'];
						}else{
							$multiAttachments[$k]["CrpContractorEquipmentId"]=$postedValues['Id'];
						}
					}
					if(isset($isEditByCdb) && (int)$isEditByCdb==1 && $newEquipmentSave!=null && (int)$newEquipmentSave==0){
						$finalTableCount = DB::table('crpcontractorfinal')->where('Id',$contractorId)->count();
						$initialTableCount = DB::table('crpcontractor')->where('Id',$contractorId)->count();
						if($initialTableCount==1 && $finalTableCount == 0){
							$multiAttachments[$k]["CrpContractorEquipmentId"]=$postedValues['Id'];
							unset($multiAttachments[$k]["CrpContractorEquipmentFinalId"]);
							$saveUploads=new ContractorEquipmentAttachmentModel($multiAttachments[$k]);
						}else{
							$multiAttachments[$k]["CrpContractorEquipmentId"]=$postedValues['Id'];
							$saveUploads=new ContractorEquipmentAttachmentFinalModel($multiAttachments[$k]);
						}
					}else{
						$saveUploads=new ContractorEquipmentAttachmentModel($multiAttachments[$k]);
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
		if(isset($isEditByCdb) && (int)$isEditByCdb==1){
			if(!empty($hasCDBEdit)){
				if($redirectToEdit){
					return Redirect::to($equipmentEditPage.'/'.$postedValues["CrpContractorId"].'?redirectUrl='.$redirectTo)->with('savedsuccessmessage','Equipment has been successfully updated.');
				}
				return Redirect::to($equipmentEditPage.'/'.$postedValues["CrpContractorId"].'?redirectUrl='.$redirectTo.'&usercdb=edit')->with('savedsuccessmessage','Equipment has been successfully updated.');
			}else{
				return Redirect::to($equipmentEditPage.'/'.$postedValues["CrpContractorId"].'?redirectUrl='.$redirectTo)->with('savedsuccessmessage','Equipment has been successfully updated.');
			}
		}
		if($save){
			return Redirect::to('contractor/equipmentregistration');
		}else{
			return Redirect::to('contractor/equipmentregistration/'.$postedValues['CrpContractorId'])->with('savedsuccessmessage','Equipment has been successfully updated.');;
		}
	}
	public function confirmRegistration(){
		if(Session::has('ContractorRegistrationId')){
			$contractorId=Session::get('ContractorRegistrationId');
		}else{
			return Redirect::to('contractor/generalinforegistration')->withInput();
		}
		$generalInformation=ContractorModel::contractor($contractorId)->get(array('crpcontractor.Id','crpcontractor.NameOfFirm','crpcontractor.Address','crpcontractor.Email','crpcontractor.TelephoneNo','crpcontractor.MobileNo','crpcontractor.FaxNo','T1.Name as Country','T2.NameEn as Dzongkhag','T7.Name as OwnershipType','T7.ReferenceNo as OwnershipTypeReferenceNo'));
		$ownerPartnerDetails=ContractorHumanResourceModel::contractorPartner($contractorId)->get(array('crpcontractorhumanresource.Id','crpcontractorhumanresource.CIDNo','crpcontractorhumanresource.Name','crpcontractorhumanresource.Sex','crpcontractorhumanresource.ShowInCertificate','T1.Nationality as Country','T2.Name as Salutation','T3.Name as Designation'));
		$appliedWorkClassifications=ContractorWorkClassificationModel::contractorAppliedWorkClassification($contractorId)->get(array('T1.Code','T1.Name as Category','T2.Name as Classification'));
		$contractorHumanResources=ContractorHumanResourceModel::ContractorHumanResource($contractorId)->get(array('crpcontractorhumanresource.Id','crpcontractorhumanresource.Name','crpcontractorhumanresource.CIDNo','crpcontractorhumanresource.Sex','crpcontractorhumanresource.Name','T1.Name as Salutation','T2.Name as Qualification','T3.Name as Trade','T4.Name as Designation','T5.Nationality as Country'));
		$contractorEquipments=ContractorEquipmentModel::contractorEquipment($contractorId)->get(array('crpcontractorequipment.Id','crpcontractorequipment.RegistrationNo','crpcontractorequipment.ModelNo','crpcontractorequipment.Quantity','T1.Name'));
		$humanResourcesAttachments=ContractorHumanResourceModel::humanResourceAttachments($contractorId)->get(array('T1.Id','T1.CrpContractorHumanResourceId','T1.DocumentName','T1.DocumentPath'));
		$equipmentsAttachments=ContractorEquipmentModel::equipmentAttachments($contractorId)->get(array('T1.Id','T1.CrpContractorEquipmentId','T1.DocumentName','T1.DocumentPath'));
		$incorporationOwnershipTypes=ContractorAttachmentModel::attachment($contractorId)->get(array('DocumentName','DocumentPath'));
		return View::make('crps.contractorregistrationconfirmation')
			->with('contractorId',$contractorId)
			->with('generalInformation',$generalInformation)
			->with('ownerPartnerDetails',$ownerPartnerDetails)
			->with('appliedWorkClassifications',$appliedWorkClassifications)
			->with('contractorHumanResources',$contractorHumanResources)
			->with('contractorEquipments',$contractorEquipments)
			->with('humanResourcesAttachments',$humanResourcesAttachments)
			->with('equipmentsAttachments',$equipmentsAttachments)
			->with('incorporationOwnershipTypes',$incorporationOwnershipTypes);
	}
	public function saveConfirmation(){
		$contractorId=Input::get('ContractorId');
		$contractor = ContractorModel::find($contractorId);
		$contractor->RegistrationStatus=1;
		$contractor->CmnApplicationRegistrationStatusId=CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW;
		$contractor->save();
		$contractorDetails=ContractorModel::contractorHardList($contractorId)->get(array('NameOfFirm','Email','ReferenceNo','ApplicationDate','MobileNo'));
		$mailView="emails.crps.mailregistrationsuccess";
		$subject="Acknowledgement: Receipt of Application for Registration with CDB";
		$recipientAddress=$contractorDetails[0]->Email;
		$recipientName=$contractorDetails[0]->NameOfFirm;
		$referenceNo=$contractorDetails[0]->ReferenceNo;
		$applicationDate=$contractorDetails[0]->ApplicationDate;
		$mobileNo=$contractorDetails[0]->MobileNo;
		$smsMessage="Your application for contractor registration has been received. You can check status of your application using CID No. or Application No ($referenceNo) from CDB website.";
		$mailIntendedTo=1;//1=Contractor,2=Consultant,3=Architect,4=Engineer,5=Specialized Trade
		$feeStructures=DB::select("select T1.Code as CategoryCode,T1.Name as Category,T2.Code as AppliedClassificationCode,T2.Name as AppliedClassification,T2.RegistrationFee as AppliedRegistrationFee from cmncontractorworkcategory T1 join crpcontractorworkclassification X on T1.Id=X.CmnProjectCategoryId join cmncontractorclassification T2 on T2.Id=X.CmnAppliedClassificationId where X.CrpContractorId=? order by T1.Code,T1.Name",array($contractorId));
		$mailData=array(
			'mailIntendedTo'=>$mailIntendedTo,
			'applicantName'=>$recipientName,
			'applicationNo'=>$referenceNo,
			'applicationDate'=>$applicationDate,
			'feeStructures'=>$feeStructures,
			'mailMessage'=>"This is to acknowledge receipt of your application for registration of contractor with Construction Development Board (CDB). Your application will be processed in due course. You can check status of your application using your Citizenship Id No. or Application No. from the <a href=�#�>CDB website</a>. You will also be notified through email when your application is approved.",
		);
		$this->sendEmailMessage($mailView,$mailData,$subject,$recipientAddress,$recipientName);
		$this->sendSms($smsMessage,$mobileNo);
		Session::forget('ContractorRegistrationId');
		return Redirect::route('applicantregistrationsuccess',array('linktoprint'=>'contractor/printregistration','printreference'=>$contractorId,'applicationno'=>$referenceNo));
	}
	public function contractorList(){
		$parameters=array();
		$isMonitoring = false;
		$linkText='Edit';
		$link='contractor/editdetails/';
		$contractorId=Input::get('CrpContractorId');
		$CDBNo=Input::get('CDBNo');
		$registrationStatus=Input::get('RegistrationStatus');
		$tradeLicenseNo = Input::get('TradeLicenseNo');
		$fromDate = Input::get('FromDate');
		$toDate = Input::get('ToDate');
		$cidNo = Input::get('CIDNo');
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
		$query="select distinct T1.Id,T1.RegistrationExpiryDate,T1.CDBNo,T1.MobileNo,T1.DeRegisteredDate,T1.TelephoneNo,T1.Email,T1.NameOfFirm,Z.Name as Status,Z.ReferenceNo as StatusReference,T2.Name as Country,T3.NameEn as Dzongkhag,T4.Name as OwnershipType,B.Code as ClassificationCode,B.Name as ClassificationName from crpcontractorfinal T1 left join cmnlistitem Z on Z.Id = T1.CmnApplicationRegistrationStatusId join cmncountry T2 on T1.CmnCountryId=T2.Id join cmnlistitem T4 on T1.CmnOwnershipTypeId=T4.Id join (crpcontractorworkclassificationfinal A join cmncontractorclassification B on A.CmnApprovedClassificationId=B.Id) on T1.Id=A.CrpContractorFinalId and B.Priority=(select max(Priority) from crpcontractorworkclassificationfinal X join cmncontractorclassification Y on X.CmnApprovedClassificationId=Y.Id where X.CrpContractorFinalId=T1.Id) left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id  where 1";
		//array_push($parameters,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED);
		if(Route::current()->getUri()=="contractor/viewprintlist"){
			$linkText='View/Print';
			$link='contractor/viewprintdetails/';
		}elseif(Route::current()->getUri()=="contractor/newcommentsadverserecordslist"){
			$linkText='Add';
			$link='contractor/newcommentsadverserecords/';
		}elseif(Route::current()->getUri()=="contractor/editcommentsadverserecordslist"){
			$linkText='View';
			$link='contractor/editcommentsadverserecords/';
		}elseif(Route::current()->getUri()=="contractor/monitoringlist"){
			$isMonitoring = true;
		}
		if((bool)$contractorId!=NULL || (bool)$CDBNo!=NULL || (bool)$registrationStatus!=NULL){
				if((bool)$contractorId!=NULL){
			$query.=" and T1.Id=?";
			array_push($parameters,$contractorId);
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
		if((bool)$cidNo){
			$query.=" and ? in (select TRIM(P.CIDNo) from crpcontractorhumanresourcefinal P where P.CrpContractorFinalId = T1.Id and coalesce(P.IsPartnerOrOwner,0) = 1)";
			array_push($parameters,$cidNo);
		}
		if((bool)$fromDate){
			$query.=" and T1.RegistrationApprovedDate >= ?";
			array_push($parameters,$this->convertDate($fromDate));
		}
		if((bool)$toDate){
			$query.=" and T1.RegistrationApprovedDate <= ?";
			array_push($parameters,$this->convertDate($toDate));
		}
		$contractorLists=DB::select($query." order by T1.CDBNo,Z.ReferenceNo,NameOfFirm".$limit,$parameters);
		$status=CmnListItemModel::registrationStatus()->get(array('Id','Name'));
		return View::make('crps.contractorlist')
			->with('pageTitle','List of Contractors')
			->with('link',$link)
			->with('isMonitoring',$isMonitoring)
			->with('linkText',$linkText)
			->with('CDBNo',$CDBNo)
			->with('registrationStatus',$registrationStatus)
			->with('status',$status)
	              	->with('contractorId',$contractorId)
			->with('contractorLists',$contractorLists);
	}
	
	public function contractorDetails($contractorId=null,$forReport = false){
		$contractorTrackrecords=array();
		$commentsAdverseRecords=array();
		$registrationApprovedForPayment=0;
		$registrationApproved=0;
		$userContractor=0;
		if(Route::current()->getUri()=="contractor/verifyregistrationprocess/{contractorid}"){
			$view="crps.contractorverifyregistrationprocess";
			$modelPost="contractor/mverifyregistration";
		}elseif(Route::current()->getUri()=="contractor/approveregistrationprocess/{contractorid}"){
			$view="crps.contractorapproveregistrationprocess";
			$modelPost="contractor/mapproveregistration";
		}elseif(Route::current()->getUri()=="contractor/approvepaymentregistrationprocess/{contractorid}"){
			$curYear = date('Y');
			$tenYearsAgo = (int)$curYear - 10;
			$contractorTrackrecords=CrpContractorTrackRecordModel::trackRecord($contractorId)->whereRaw("WorkCompletionDate >= '$tenYearsAgo-01-01'")->get();
			$commentsAdverseRecords=ContractorCommentAdverseRecordModel::commentAdverseRecordList($contractorId)->get(array('Id','Date','Remarks','Type'));
			$registrationApprovedForPayment=1;
			$view="crps.contractorinformation";
			$modelPost=null;
		}elseif(Route::current()->getUri()=="contractor/viewregistrationprocess/{contractorid}"){
			$contractorTrackrecords=CrpContractorTrackRecordModel::trackRecord($contractorId)->get();
			$commentsAdverseRecords=ContractorCommentAdverseRecordModel::commentAdverseRecordList($contractorId)->get(array('Id','Date','Remarks','Type'));
			$registrationApprovedForPayment=1;
			$registrationApproved=1;
			$view="crps.contractorinformation";
			$modelPost=null;
		}
		else{
		    if(!$forReport){
                App::abort('404');
            }

		}

		$class=ContractorClassificationModel::classification()->select(DB::raw('Id,Name,coalesce(ReferenceNo,88888888) as ReferenceNo'))->get();
		$generalInformation=ContractorModel::contractor($contractorId)->get(array('crpcontractor.Id','crpcontractor.ReferenceNo','crpcontractor.PaymentReceiptNo', 'crpcontractor.PaymentReceiptDate','crpcontractor.CDBNo','crpcontractor.ApplicationDate','crpcontractor.CDBNo','crpcontractor.NameOfFirm','crpcontractor.RegisteredAddress','crpcontractor.RegistrationExpiryDate','crpcontractor.Village','crpcontractor.Gewog','crpcontractor.Address','crpcontractor.Email','crpcontractor.TelephoneNo','crpcontractor.MobileNo','crpcontractor.FaxNo','crpcontractor.CmnApplicationRegistrationStatusId','crpcontractor.RegistrationVerifiedDate','crpcontractor.RemarksByVerifier','crpcontractor.RemarksByApprover','crpcontractor.RegistrationApprovedDate','crpcontractor.RemarksByPaymentApprover','crpcontractor.RegistrationPaymentApprovedDate','T1.Name as Country','T2.NameEn as Dzongkhag','T4.FullName as Verifier','T5.FullName as Approver','T6.FullName as PaymentApprover','T7.Name as OwnershipType','T7.ReferenceNo as OwnershipTypeReferenceNo','T8.NameEn as RegisteredDzongkhag'));
		$ownerPartnerDetails=ContractorHumanResourceModel::contractorPartner($contractorId)->get(array('crpcontractorhumanresource.Id','crpcontractorhumanresource.CIDNo','crpcontractorhumanresource.Name','crpcontractorhumanresource.Sex','crpcontractorhumanresource.ShowInCertificate','crpcontractorhumanresource.Verified','crpcontractorhumanresource.Approved','T1.Nationality as Country','T2.Name as Salutation','T3.Name as Designation'));
		foreach($ownerPartnerDetails as $ownerPartnerDetail):
			$cidNo = $ownerPartnerDetail->CIDNo;
			$checkPartnerDeregistered = DB::table('crpcontractorfinal as T1')
				->join('crpcontractorhumanresourcefinal as T2','T1.Id','=','T2.CrpContractorFinalId')
				->whereIn(DB::raw("coalesce(T1.CmnApplicationRegistrationStatusId,'".CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW."')"),array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_DEREGISTERED,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_REVOKED,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_SUSPENDED,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_DEBARRED,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_SURRENDERED))
				->where(DB::raw('TRIM(T2.CIDNo)'),trim($cidNo))
				->where(DB::raw('coalesce(T2.IsPartnerOrOwner,0)'),1)
				->get(array(DB::raw("group_concat(concat(T1.NameOfFirm,' (',T1.CDBNo,')') SEPARATOR ', ') as Firms")));
			if((bool)$checkPartnerDeregistered[0]->Firms){
				$ownerPartnerDetail->OtherRemarks = "Owner/Partner of Deregistered/Surrendered Firm(s) - ".$checkPartnerDeregistered[0]->Firms;
			}else{
				$ownerPartnerDetail->OtherRemarks = '--';
			}
		endforeach;
		$contractorWorkClassifications=ContractorWorkClassificationModel::contractorWorkClassification($contractorId)->select(DB::raw('crpcontractorworkclassification.Id,crpcontractorworkclassification.CmnAppliedClassificationId,crpcontractorworkclassification.CmnVerifiedClassificationId,T1.Code,T1.Name as Category,coalesce(T1.ReferenceNo,99999999) as CategoryReferenceNo,T2.Name as AppliedClassification,T3.Name as VerifiedClassification,T4.Name as ApprovedClassification'))->get();
		$contractorHumanResources=ContractorHumanResourceModel::contractorHumanResource($contractorId)->get(array(DB::raw('distinct crpcontractorhumanresource.Id'),'crpcontractorhumanresource.Name','crpcontractorhumanresource.CIDNo','crpcontractorhumanresource.Sex','crpcontractorhumanresource.Name','crpcontractorhumanresource.Verified','crpcontractorhumanresource.EditedOn','crpcontractorhumanresource.JoiningDate','crpcontractorhumanresource.Approved','T1.Name as Salutation','T2.Name as Qualification','T3.Name as Trade','T4.Name as Designation','T5.Nationality as Country'));
		foreach($contractorHumanResources as $contractorHumanResource):
			$cidNo = $contractorHumanResource->CIDNo;
			$checkPartnerDeregistered = DB::table('crpcontractorfinal as T1')
				->join('crpcontractorhumanresourcefinal as T2','T1.Id','=','T2.CrpContractorFinalId')
				->whereIn(DB::raw("coalesce(T1.CmnApplicationRegistrationStatusId,'".CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW."')"),array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_DEREGISTERED,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_REVOKED,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_SUSPENDED,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_DEBARRED,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_SURRENDERED))
				->where(DB::raw('TRIM(T2.CIDNo)'),trim($cidNo))
				->where(DB::raw('coalesce(T2.IsPartnerOrOwner,0)'),1)
				->get(array(DB::raw("group_concat(concat(T1.NameOfFirm,' (',T1.CDBNo,')') SEPARATOR ', ') as Firms")));
			if((bool)$checkPartnerDeregistered[0]->Firms){
				$contractorHumanResource->OtherRemarks = "Owner/Partner of Deregistered/Surrendered Firm(s) - ".$checkPartnerDeregistered[0]->Firms;
			}else{
				$contractorHumanResource->OtherRemarks = '--';
			}
		endforeach;
		$contractorEquipments=ContractorEquipmentModel::contractorEquipment($contractorId)->get(array('crpcontractorequipment.Id','crpcontractorequipment.EditedOn','crpcontractorequipment.RegistrationNo','crpcontractorequipment.ModelNo','crpcontractorequipment.Quantity','crpcontractorequipment.Verified','crpcontractorequipment.Approved','T1.Name','T1.VehicleType'));
		$contractorHumanResourceAttachments=ContractorHumanResourceAttachmentModel::singleContractorHumanResourceAllAttachments($contractorId)->get(array('crpcontractorhumanresourceattachment.DocumentName','crpcontractorhumanresourceattachment.DocumentPath','crpcontractorhumanresourceattachment.CrpContractorHumanResourceId'));
		$contractorEquipmentAttachments=ContractorEquipmentAttachmentModel::singleContractorEquipmentAllAttachments($contractorId)->get(array('crpcontractorequipmentattachment.DocumentName','crpcontractorequipmentattachment.DocumentPath','crpcontractorequipmentattachment.CrpContractorEquipmentId'));
		$contractorAttachments = ContractorAttachmentModel::attachment($contractorId)->get(array('DocumentName','DocumentPath'));
		$feeStructures=DB::select("select T1.Code as CategoryCode,T1.Id as CategoryId,T1.Name as Category,T2.Id as AppliedClassificationId,T2.Code as AppliedClassificationCode,T2.Name as AppliedClassification,T2.RegistrationFee as AppliedRegistrationFee,T3.Id as VerifiedClassificationId,T3.Code as VerifiedClassificationCode,T3.Name as VerifiedClassification,T3.RegistrationFee as VerifiedRegistrationFee,T4.Id as ApprovedClassificationId,T4.Code as ApprovedClassificationCode,T4.Name as ApprovedClassification,T4.RegistrationFee as ApprovedRegistrationFee from cmncontractorworkcategory T1 join crpcontractorworkclassification X on T1.Id=X.CmnProjectCategoryId join cmncontractorclassification T2 on T2.Id=X.CmnAppliedClassificationId left join cmncontractorclassification T3 on T3.Id=X.CmnVerifiedClassificationId left join cmncontractorclassification T4 on T4.Id=X.CmnApprovedClassificationId where X.CrpContractorId=? order by T1.Code,T1.Name",array($contractorId));
		if($forReport){
		    return $feeStructures;
        }
		$registrationValidityYears=CrpService::registrationValidityYear(CONST_SERVICETYPE_NEW)->pluck('ContractorValidity');
		$incorporationOwnershipTypes=ContractorAttachmentModel::attachment($contractorId)->get(array('DocumentName','DocumentPath'));
		$contractorComments = ContractorCommentAdverseRecordModel::commentList($contractorId)->get(array('Id','Date','Remarks'));
		$contractorAdverseRecords = ContractorCommentAdverseRecordModel::adverseRecordList($contractorId)->get(array('Id','Date','Remarks'));
		$contractorEmployeesIds = DB::table('crpcontractorhumanresource')->where('CrpContractorId',$contractorId)->select(DB::raw("TRIM(CIDNo) as EmpCIDNo"))->lists('EmpCIDNo');
		$trainingsAttended = DB::table('crpcontractortrainingdetail as T1')
			->join('crpcontractortraining as T2','T1.CrpContractorTrainingId','=','T2.Id')
			->join('cmnlistitem as A','A.Id','=','T2.CmnTrainingTypeId')
			->leftJoin('cmnlistitem as T3','T3.Id','=','T2.CmnTrainingModuleId')
			->whereIn(DB::raw("TRIM(T1.CIDNo)"),$contractorEmployeesIds)
			->orderBy('TrainingFromDate','Desc')
			->get(array("T1.Participant","TrainingFromDate","TrainingToDate",'T1.CIDNo','T3.Name as Module','A.ReferenceNo as TrainingReference','A.Name as TrainingType'));
		return View::make($view)
			->with('modelPost',$modelPost)
			->with('classes',$class)
			->with('trainingsAttended',$trainingsAttended)
			->with('registrationApprovedForPayment',$registrationApprovedForPayment)
			->with('registrationApproved',$registrationApproved)
			->with('userContractor',$userContractor)
			->with('contractorId',$contractorId)
			->with('contractorComments',$contractorComments)
			->with('contractorAdverseRecords',$contractorAdverseRecords)
			->with('generalInformation',$generalInformation)
			->with('ownerPartnerDetails',$ownerPartnerDetails)
			->with('contractorWorkClassifications',$contractorWorkClassifications)
			->with('contractorHumanResources',$contractorHumanResources)
			->with('contractorEquipments',$contractorEquipments)
			->with('contractorHumanResourceAttachments',$contractorHumanResourceAttachments)
			->with('contractorEquipmentAttachments',$contractorEquipmentAttachments)
			->with('contractorAttachments',$contractorAttachments)
			->with('contractorTrackrecords',$contractorTrackrecords)
			->with('commentsAdverseRecords',$commentsAdverseRecords)
			->with('feeStructures',$feeStructures)
			->with('registrationValidityYears',$registrationValidityYears)
			->with('incorporationOwnershipTypes',$incorporationOwnershipTypes);
	}
	public function verifyList(){
		$recordLockException=false;
		$applicationPickedByUserFullName=null;
		if(Session::has('ApplicationAlreadyPicked')){
			$applicationPickedByUserFullName=Session::get('ApplicationAlreadyPicked');
			$recordLockException=true;
		};
		$redirectUrl = "contractor/verifyregistration";
		$contractorIdAll=Input::get('CrpContractorIdAll');
		$fromDateAll=Input::get('FromDateAll');
		$toDateAll=Input::get('ToDateAll');
		/*----end of parameters for all the applications*/
		$contractorIdMyTask=Input::get('CrpContractorIdMyTask');
		$fromDateMyTask=Input::get('FromDateMyTask');
		$toDateMyTask=Input::get('ToDateMyTask');
		/*----end of parameters for My Task the applications*/
		$query="select distinct T1.Id,T1.ReferenceNo,T1.ApplicationDate,T1.MobileNo,T1.TelephoneNo,T1.Email,T1.NameOfFirm,T2.Name as Country,T3.NameEn as Dzongkhag,T4.Name as OwnershipType,B.Code as ClassificationCode,B.Name as ClassificationName from crpcontractor T1 join cmncountry T2 on T1.CmnCountryId=T2.Id join cmnlistitem T4 on T1.CmnOwnershipTypeId=T4.Id left join (crpcontractorworkclassification A join cmncontractorclassification B on A.CmnAppliedClassificationId=B.Id) on T1.Id=A.CrpContractorId and B.Priority=(select max(Priority) from crpcontractorworkclassification X join cmncontractorclassification Y on X.CmnAppliedClassificationId=Y.Id where X.CrpContractorId=T1.Id) left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id where T1.CmnApplicationRegistrationStatusId=? and T1.SysLockedByUserId is null and T1.CrpContractorId is null";
		$queryMyTaskList="select distinct T1.Id,T1.ReferenceNo,T1.ApplicationDate,T1.MobileNo,T1.TelephoneNo,T1.Email,T1.NameOfFirm,T2.Name as Country,T3.NameEn as Dzongkhag,T4.Name as OwnershipType,B.Code as ClassificationCode,B.Name as ClassificationName from crpcontractor T1 join cmncountry T2 on T1.CmnCountryId=T2.Id join cmnlistitem T4 on T1.CmnOwnershipTypeId=T4.Id left join (crpcontractorworkclassification A join cmncontractorclassification B on A.CmnAppliedClassificationId=B.Id) on T1.Id=A.CrpContractorId and B.Priority=(select max(Priority) from crpcontractorworkclassification X join cmncontractorclassification Y on X.CmnAppliedClassificationId=Y.Id where X.CrpContractorId=T1.Id) left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id where T1.CmnApplicationRegistrationStatusId=? and T1.SysLockedByUserId=? and T1.CrpContractorId is null";
		$parameters=array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW);
		$parametersMyTaskList=array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW,Auth::user()->Id);
		if((bool)$contractorIdAll!=NULL || (bool)$fromDateAll!=NULL || (bool)$toDateAll!=NULL || (bool)$contractorIdMyTask!=NULL || (bool)$fromDateMyTask!=NULL || (bool)$toDateMyTask!=NULL){
			if((bool)$contractorIdAll!=NULL){
				$query.=" and T1.Id=?";
				array_push($parameters,$contractorIdAll);
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
			if((bool)$contractorIdMyTask!=NULL){
				$query.=" and T1.Id=?";
				array_push($parameters,$contractorIdMyTask);
			}
			if((bool)$fromDateMyTask!=NULL){
				$fromDateMyTask=$this->convertDate($fromDateMyTask);
				$query.=" and T1.ApplicationDate>=?";
				array_push($parameters,$fromDateMyTask);
			}
			if((bool)$toDateMyTask!=NULL){
				$toDateMyTask=$this->convertDate($toDateMyTask);
				$query.=" and T1.ApplicationDate<=?";
				array_push($parameters,$toDateMyTask);
			}
		}
		$contractorLists=DB::select($query." order by ApplicationDate,ReferenceNo,NameOfFirm",$parameters);
		$contractorMyTaskLists=DB::select($queryMyTaskList." order by ApplicationDate,NameOfFirm",$parametersMyTaskList);
		return View::make('crps.contractorregistrationapplicationprocesslist')
			->with('redirectUrl',$redirectUrl)
			->with('pageTitle',"Verify Contractor's Registration")
			->with('recordLockException',$recordLockException)
			->with('applicationPickedByUserFullName',$applicationPickedByUserFullName)
			->with('fromDateAll',$fromDateAll)
			->with('toDateAll',$toDateAll)
			->with('contractorIdAll',$contractorIdAll)
			->with('fromDateMyTask',$fromDateMyTask)
			->with('toDateMyTask',$toDateMyTask)
			->with('contractorIdMyTask',$contractorIdMyTask)
			->with('contractorLists',$contractorLists)
			->with('contractorMyTaskLists',$contractorMyTaskLists);
	}
	public function verifyRegistration(){
		$postedValues=Input::all();
//		$postedValues['RegistrationApprovedDate']=$this->convertDateTime($postedValues['RegistrationApprovedDate']);
//		$postedValues['RegistrationExpiryDate']=$this->convertDateTime($postedValues['RegistrationExpiryDate']);
//		$countryId = DB::table('crpcontractor')->where('Id',$postedValues['ContractorId'])->pluck('CmnCountryId');
//		if($countryId != '8f897032-c6e6-11e4-b574-080027dcfac6'){
//			$postedValues['CDBNo'] = (strpos($postedValues['CDBNo'],'NB')==-1)?"NB".$postedValues['CDBNo']:$postedValues['CDBNo'];
//		}
		DB::beginTransaction();
		try{
			$instance=ContractorModel::find($postedValues['ContractorReference']);
			$instance->fill($postedValues);
			$instance->update();

			$contractorAdverserecordInstance = new ContractorCommentAdverseRecordModel;
			$contractorAdverserecordInstance->CrpContractorFinalId = $postedValues['ContractorReference'];
			$contractorAdverserecordInstance->Date=date('Y-m-d');
			$contractorAdverserecordInstance->CmnApplicationRegistrationStatusId = CONST_CMN_APPLICATIONREGISTRATIONSTATUS_VERIFIED;
			$contractorAdverserecordInstance->Remarks="Verifed Registration";
			$contractorAdverserecordInstance->Type=3;
			$contractorAdverserecordInstance->save();

			foreach($postedValues as $key=>$value){
				if(gettype($value)=='array'){
					$modelName=$key;
					foreach($value as $key1=>$value1){
						foreach($value1 as $key2=>$value2){
							$val1=trim($value2);
							if(strlen($val1)==0){
								$value1[$key2]=null;
							}
						}
						$childTable= new $modelName();
						$childTable1=$childTable::find($value1['Id']);
						$childTable1->fill($value1);
						$childTable1->update();
					}
				}
			}
		}catch(Exception $e){
			DB::rollback();
			throw $e;
		}
		DB::commit();
		return Redirect::to('contractor/verifyregistration')
			->with('savedsuccessmessage','The application has been successfully verified.');
	}
	public function approveList(){
		$recordLockException=false;
		$applicationPickedByUserFullName=null;
		if(Session::has('ApplicationAlreadyPicked')){
			$applicationPickedByUserFullName=Session::get('ApplicationAlreadyPicked');
			$recordLockException=true;
		}
		$contractorIdAll=Input::get('CrpContractorIdAll');
		$fromDateAll=Input::get('FromDateAll');
		$toDateAll=Input::get('ToDateAll');
		/*----end of parameters for alll the applications*/
		$contractorIdMyTask=Input::get('CrpContractorIdMyTask');
		$fromDateMyTask=Input::get('FromDateMyTask');
		$toDateMyTask=Input::get('ToDateMyTask');
		/*----end of parameters for My Task the applications*/
		$query="select distinct T1.Id,T1.ReferenceNo,T1.ApplicationDate,T1.MobileNo,T1.TelephoneNo,T1.Email,T1.NameOfFirm,T1.CmnApplicationRegistrationStatusId,T2.Name as Country,T3.NameEn as Dzongkhag,T4.Name as OwnershipType,B.Code as ClassificationCode,B.Name as ClassificationName from crpcontractor T1 join cmncountry T2 on T1.CmnCountryId=T2.Id join cmnlistitem T4 on T1.CmnOwnershipTypeId=T4.Id join (crpcontractorworkclassification A join cmncontractorclassification B on A.CmnAppliedClassificationId=B.Id) on T1.Id=A.CrpContractorId and B.Priority=(select max(Priority) from crpcontractorworkclassification X join cmncontractorclassification Y on X.CmnAppliedClassificationId=Y.Id where X.CrpContractorId=T1.Id) left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id where T1.CmnApplicationRegistrationStatusId=? and T1.SysLockedByUserId is null and T1.CrpContractorId is null";
		$queryMyTaskList="select distinct T1.Id,T1.ReferenceNo,T1.ApplicationDate,T1.MobileNo,T1.TelephoneNo,T1.Email,T1.NameOfFirm,T1.CmnApplicationRegistrationStatusId,T2.Name as Country,T3.NameEn as Dzongkhag,T4.Name as OwnershipType,B.Code as ClassificationCode,B.Name as ClassificationName from crpcontractor T1 join cmncountry T2 on T1.CmnCountryId=T2.Id join cmnlistitem T4 on T1.CmnOwnershipTypeId=T4.Id join (crpcontractorworkclassification A join cmncontractorclassification B on A.CmnAppliedClassificationId=B.Id) on T1.Id=A.CrpContractorId and B.Priority=(select max(Priority) from crpcontractorworkclassification X join cmncontractorclassification Y on X.CmnAppliedClassificationId=Y.Id where X.CrpContractorId=T1.Id) left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id where T1.CmnApplicationRegistrationStatusId=? and T1.SysLockedByUserId=? and T1.CrpContractorId is null";
		if(Request::path()=="contractor/approvefeepayment"){
            $query.=" and case when T1.ApplicationDate <= '2017-08-02' then DATEDIFF(NOW(),T1.RegistrationApprovedDate)<=90 else DATEDIFF(NOW(),T1.RegistrationApprovedDate)<=30 end";
            $queryMyTaskList.=" and case when T1.ApplicationDate <= '2017-08-02' then DATEDIFF(NOW(),T1.RegistrationApprovedDate)<=90 else DATEDIFF(NOW(),T1.RegistrationApprovedDate)<=30 end";
			$redirectUrl = "contractor/approvefeepayment";
			$parameters=array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVEDFORPAYMENT);
			$parametersMyTaskList=array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVEDFORPAYMENT,Auth::user()->Id);
		}else{
			$redirectUrl = "contractor/approveregistration";
			$parameters=array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_VERIFIED);
			$parametersMyTaskList=array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_VERIFIED,Auth::user()->Id);
		}
		if((bool)$contractorIdAll!=NULL || (bool)$fromDateAll!=NULL || (bool)$toDateAll!=NULL || (bool)$contractorIdMyTask!=NULL || (bool)$fromDateMyTask!=NULL || (bool)$toDateMyTask!=NULL){
			if((bool)$contractorIdAll!=NULL){
				$query.=" and T1.Id=?";
				array_push($parameters,$contractorIdAll);
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
			if((bool)$contractorIdMyTask!=NULL){
				$query.=" and T1.Id=?";
				array_push($parameters,$contractorIdMyTask);
			}
			if((bool)$fromDateMyTask!=NULL){
				$fromDateMyTask=$this->convertDate($fromDateMyTask);
				$query.=" and T1.ApplicationDate>=?";
				array_push($parameters,$fromDateMyTask);
			}
			if((bool)$toDateMyTask!=NULL){
				$toDateMyTask=$this->convertDate($toDateMyTask);
				$query.=" and T1.ApplicationDate<=?";
				array_push($parameters,$toDateMyTask);
			}
		}
		$contractorLists=DB::select($query." order by ApplicationDate,NameOfFirm",$parameters);
		$contractorMyTaskLists=DB::select($queryMyTaskList." order by ApplicationDate,NameOfFirm",$parametersMyTaskList);
		return View::make('crps.contractorregistrationapplicationprocesslist')
			->with('redirectUrl',$redirectUrl)
			->with('pageTitle',"Approve Contractor's Registration")
			->with('recordLockException',$recordLockException)
			->with('applicationPickedByUserFullName',$applicationPickedByUserFullName)
			->with('fromDateAll',$fromDateAll)
			->with('toDateAll',$toDateAll)
			->with('contractorIdAll',$contractorIdAll)
			->with('fromDateMyTask',$fromDateMyTask)
			->with('toDateMyTask',$toDateMyTask)
			->with('contractorIdMyTask',$contractorIdMyTask)
			->with('contractorLists',$contractorLists)
			->with('contractorMyTaskLists',$contractorMyTaskLists);
	}
	public function approveRegistration(){
		$postedValues=Input::all();
		$postedValues['RegistrationApprovedDate']=$this->convertDateTime($postedValues['RegistrationApprovedDate']);
		$postedValues['RegistrationExpiryDate']=$this->convertDateTime($postedValues['RegistrationExpiryDate']);
		// $countryId = DB::table('crpcontractor')->where('Id',$postedValues['ContractorReference'])->pluck('CmnCountryId');
		// if($countryId != '8f897032-c6e6-11e4-b574-080027dcfac6'){
		// 	$postedValues['CDBNo'] = (strpos($postedValues['CDBNo'],'NB')==-1)?"NB".$postedValues['CDBNo']:$postedValues['CDBNo'];
		// }
		DB::beginTransaction();
		try{
			$instance=ContractorModel::find($postedValues['ContractorReference']);
			$instance->fill($postedValues);
			$instance->update();

			$contractorAdverserecordInstance = new ContractorCommentAdverseRecordModel;
			$contractorAdverserecordInstance->CrpContractorFinalId = $postedValues['ContractorReference'];
			$contractorAdverserecordInstance->Date=date('Y-m-d');
			$contractorAdverserecordInstance->CmnApplicationRegistrationStatusId = CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED;
			$contractorAdverserecordInstance->Remarks="Approved Registration";
			$contractorAdverserecordInstance->Type=3;
			$contractorAdverserecordInstance->save();
			foreach($postedValues as $key=>$value){
				if(gettype($value)=='array'){
					$modelName=$key;
					foreach($value as $key1=>$value1){
						foreach($value1 as $key2=>$value2){
							$val1=trim($value2);
							if(strlen($val1)==0){
								$value1[$key2]=null;
							}
						}
						$childTable= new $modelName();
						$childTable1=$childTable::find($value1['Id']);
						$childTable1->fill($value1);
						$childTable1->update();

					}
				}
			}
		}catch(Exception $e){
			DB::rollback();
			throw $e;
		}
		DB::commit();
		$contractorDetails=ContractorModel::contractorHardList(Input::get('ContractorReference'))->get(array('NameOfFirm','CDBNo','Email','ReferenceNo','ApplicationDate','MobileNo','RemarksByApprover','RemarksByVerifier'));
		$mailView="emails.crps.mailapplicationapproved";
		$subject="Approval of Your Registration with CDB";
		$recipientAddress=$contractorDetails[0]->Email;
		$cdbNo=$contractorDetails[0]->CDBNo;
		$recipientName=$contractorDetails[0]->NameOfFirm;
		$applicationNo=$contractorDetails[0]->ReferenceNo;
		$applicationDate=$contractorDetails[0]->ApplicationDate;
		$mobileNo=$contractorDetails[0]->MobileNo;
		$remarksByVerifier = $contractorDetails[0]->RemarksByVerifier;
		$remarksByApprover = $contractorDetails[0]->RemarksByApprover;
		$mailIntendedTo=1;//1=Contractor,2=Consultant,3=Architect,4=Engineer,5=Specialized Trade
		$feeStructures=DB::select("select T1.Code as CategoryCode,T1.Name as Category,T2.Code as AppliedClassificationCode,T2.Name as AppliedClassification,T2.RegistrationFee as AppliedRegistrationFee,T3.Code as VerifiedClassificationCode,T3.Name as VerifiedClassification,T3.RegistrationFee as VerifiedRegistrationFee,T4.Code as ApprovedClassificationCode,T4.Name as ApprovedClassification,T4.RegistrationFee as ApprovedRegistrationFee from cmncontractorworkcategory T1 join crpcontractorworkclassification X on T1.Id=X.CmnProjectCategoryId join cmncontractorclassification T2 on T2.Id=X.CmnAppliedClassificationId left join cmncontractorclassification T3 on T3.Id=X.CmnVerifiedClassificationId left join cmncontractorclassification T4 on T4.Id=X.CmnApprovedClassificationId where X.CrpContractorId=? order by T1.Code,T1.Name",array(Input::get('ContractorReference')));
		$mailData=array(
			'cdbNo'=>$cdbNo,
			'mailIntendedTo'=>$mailIntendedTo,
			'feeStructures'=>$feeStructures,
			'applicantName'=>$recipientName,
			'applicationNo'=>$applicationNo,
			'applicationDate'=>$applicationDate,
			'mailMessage'=>"Construction Development Board (CDB) has verified and approved your application for registration of contractor with CDB.  However, you need to pay your registration fees within one month (30 days) as per the details given below to CDB office or the Nearest Regional Revenue and Customs Office (RRCO).Upon payment to the RRCO, email money receipt to Accountant@cdb.gov.bt or registration@cdb.gov.bt. We will email you your username and password upon confirmation of your payment by CDB.<br/>Remarks by Verifier: $remarksByVerifier <br/>Remarks by Approver: $remarksByApprover"
		);
		$smsMessage="Your application for contractor registration has been approved by CDB. Please check your email for detailed information regarding your fees.";
		$this->sendEmailMessage($mailView,$mailData,$subject,$recipientAddress,$recipientName);
		$this->sendSms($smsMessage,$mobileNo);
		return Redirect::to('contractor/approveregistration')
			->with('savedsuccessmessage','The application has been successfully approved.');
	}
	public function approvePayment(){
		$postedValues=Input::except('crpcontractorregistrationpayment');
		$paymentValues = Input::get('crpcontractorregistrationpayment');
		$postedValues['PaymentReceiptDate']=$this->convertDate($postedValues['PaymentReceiptDate']);
		$postedValues["InitialDate"] = date('Y-m-d');
		$countryId = DB::table('crpcontractor')->where('Id',$postedValues['ContractorReference'])->pluck('CmnCountryId');
		if($countryId != '8f897032-c6e6-11e4-b574-080027dcfac6'){
			$postedValues['CDBNo'] = (strpos($postedValues['CDBNo'],'NB')==-1)?"NB".$postedValues['CDBNo']:$postedValues['CDBNo'];
		}
		DB::beginTransaction();
		try{
			$instance=ContractorModel::find($postedValues['ContractorReference']);
			$instance->fill($postedValues);
			$instance->update();



			$contractorAdverserecordInstance = new ContractorCommentAdverseRecordModel;
			$contractorAdverserecordInstance->CrpContractorFinalId = $postedValues['ContractorReference'];
			$contractorAdverserecordInstance->Date=date('Y-m-d');
			$contractorAdverserecordInstance->CmnApplicationRegistrationStatusId = CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVEDFORPAYMENT;
			$contractorAdverserecordInstance->Remarks="Approved For Payment";
			$contractorAdverserecordInstance->Type=3;
			$contractorAdverserecordInstance->save();

			$uuid=DB::select("select uuid() as Id");
			$generatedId=$uuid[0]->Id;
			$uuidContractor=DB::select("select uuid() as Id");
			$uuidContractorId=$uuidContractor[0]->Id;
			$contractorDetails=ContractorModel::contractorHardList(Input::get('ContractorReference'))->get(array('CmnOwnershipTypeId','NameOfFirm','CDBNo','Address','TPN','CmnCountryId','CmnDzongkhagId','MobileNo','TelephoneNo','FaxNo','RegistrationApprovedDate','RegistrationExpiryDate','Email','ReferenceNo','ApplicationDate','RemarksByVerifier','RemarksByApprover'));
			$feeStructures=DB::select("select T1.Code as CategoryCode,T1.Name as Category,T2.Code as AppliedClassificationCode,T2.Name as AppliedClassification,T2.RegistrationFee as AppliedRegistrationFee,T3.Code as VerifiedClassificationCode,T3.Name as VerifiedClassification,T3.RegistrationFee as VerifiedRegistrationFee,T4.Code as ApprovedClassificationCode,T4.Name as ApprovedClassification,T4.RegistrationFee as ApprovedRegistrationFee from cmncontractorworkcategory T1 join crpcontractorworkclassification X on T1.Id=X.CmnProjectCategoryId join cmncontractorclassification T2 on T2.Id=X.CmnAppliedClassificationId left join cmncontractorclassification T3 on T3.Id=X.CmnVerifiedClassificationId left join cmncontractorclassification T4 on T4.Id=X.CmnApprovedClassificationId where X.CrpContractorId=? order by T1.Code,T1.Name",array(Input::get('ContractorReference')));
			/*----------------------Contractor Email Details and New Details------------------*/
			$CDBNo=$contractorDetails[0]->CDBNo;
			$recipientAddress=$contractorDetails[0]->Email;
			$recipientName=$contractorDetails[0]->NameOfFirm;
			$applicationNo=$contractorDetails[0]->ReferenceNo;
			$applicationDate=$contractorDetails[0]->ApplicationDate;
			$mobileNo=$contractorDetails[0]->MobileNo;
			$tpn=$contractorDetails[0]->TPN;
			$remarksByVerifier = $contractorDetails[0]->RemarksByVerifier;
			$remarksByApprover = $contractorDetails[0]->RemarksByApprover;
			/*----------------------End of Contractor Email Details and New Details------------------*/
			$plainPassword=substr(md5(uniqid(mt_rand(), true)), 0, 5);
			$plainPassword.="@#".date('d');
			$password=Hash::make($plainPassword);
			$userCredentials=array('Id'=>$generatedId,'username'=>$contractorDetails[0]->Email,'password'=>$password,'FullName'=>$contractorDetails[0]->NameOfFirm,'Status'=>'1','CreatedBy'=>Auth::user()->Id);
			$roleData=array('SysUserId'=>$generatedId,'SysRoleId'=>CONST_ROLE_CONTRACTOR,'CreatedBy'=>Auth::user()->Id);
			User::create($userCredentials);
			RoleUserMapModel::create($roleData);
			DB::statement("call ProCrpContractorNewRegistrationFinalData(?,?,?,?)",array(Input::get('ContractorReference'),$generatedId,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED,Auth::user()->Id));
			foreach($paymentValues as $key=>$value):
				foreach($value as $x=>$y):
					$paymentArray[$x] = $y;
				endforeach;
				$paymentArray['Id'] = $this->UUID();
				$paymentArray['CrpContractorFinalId'] = $postedValues['ContractorReference'];
				ContractorRegistrationPayment::create($paymentArray);
				$paymentArray = array();
			endforeach;

		}catch(Exception $e){
			DB::rollback();
			throw $e;
		}
		DB::commit();
		$mailView="emails.crps.mailregistrationpaymentcompletion";
		$subject="Activation of Your CDB Certificate";
		$mailData=array(
			'mailIntendedTo'=>1,
			'cdbNo'=>$CDBNo,
			'tpn'=>$tpn,
			'applicantName'=>$recipientName,
			'applicationNo'=>$applicationNo,
			'applicationDate'=>$applicationDate,
			'username'=>$recipientAddress,
			'password'=>$plainPassword,
			'feeStructures'=>$feeStructures,
			'mailMessage'=>"This is to acknowledge receipt of your payment via Receipt No. ".Input::get('PaymentReceiptNo')." dated ".Input::get('PaymentReceiptDate')." for registration of your firm (".$recipientName.") with Construction Development Board (CDB). Your CDB No. is ".$CDBNo." and your CDB certificate has been activated. You can view and print it by logging in to your account using below given user credentials. Please change your password regularly to make your account secure.<br/>Remarks by Verifier: $remarksByVerifier <br/>Remarks by Approver: $remarksByApprover"
		);
		$smsMessage="Your registration fees for contractor registration has been received by CDB and your certificate has been activated. Your CDB No. is $CDBNo. Your username is $recipientAddress and password is $plainPassword";
		$this->sendEmailMessage($mailView,$mailData,$subject,$recipientAddress,$recipientName);
		$this->sendSms($smsMessage,$mobileNo);
		return Redirect::to('contractor/approvefeepayment')->with('savedsuccessmessage','Payment against the registration successfully recorded.');
	}
	public function setRecordLock($contractorId){
		$pickerByUserFullName=null;
		$redirectUrl=Input::get('redirectUrl');
		$notification = Input::get('notification');
		if((bool)$notification){
			DB::table('sysapplicationnotification')->where('ApplicationId',$contractorId)->update(array('IsRead'=>1));
		}
		$hasBeenPicked=ContractorModel::contractorHardList($contractorId)->pluck('SysLockedByUserId');
		if((bool)$hasBeenPicked!=null){
			$pickerByUserFullName=User::where('Id',$hasBeenPicked)->pluck('FullName');
		}else{
			$contractor=ContractorModel::find($contractorId);
			$contractor->SysLockedByUserId=Auth::user()->Id;
			$contractor->save();
		}
		return Redirect::to($redirectUrl)->with('ApplicationAlreadyPicked',$pickerByUserFullName);
	}
	public function rejectRegistration(){

		DB::beginTransaction();
		try{
			$finalContractorId =DB::table('crpcontractor')->where('Id',Input::get('ContractorReference'))->pluck('CrpContractorId');
			$rejectionCode=str_random(30);
			$contractorId=Input::get('ContractorReference');
			$contractor = ContractorModel::find($contractorId);
			$contractor->CmnApplicationRegistrationStatusId=CONST_CMN_APPLICATIONREGISTRATIONSTATUS_REJECTED;
			$contractor->RemarksByRejector=Input::get('RemarksByRejector');
			$contractor->RejectedDate=Input::get('RejectedDate');
			$contractor->SysRejecterUserId=Auth::user()->Id;
			$contractor->SysLockedByUserId=NULL;
			$contractor->SysRejectionCode=$rejectionCode;
			$contractor->save();
			$contractorAdverserecordInstance = new ContractorCommentAdverseRecordModel;
			$contractorAdverserecordInstance->CrpContractorFinalId = Input::get('ContractorReference');
			$contractorAdverserecordInstance->Date=date('Y-m-d');
			$contractorAdverserecordInstance->CmnApplicationRegistrationStatusId = CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVEDFORPAYMENT;
			$contractorAdverserecordInstance->Remarks="Approved For Payment";
			$contractorAdverserecordInstance->Type=3;
			$contractorAdverserecordInstance->save();

			if($finalContractorId){
				DB::table('crpcontractorequipmentfinal')->where('CrpContractorFinalId',$finalContractorId)->where(DB::raw('coalesce(DeleteRequest,0)'),1)->update(array('DeleteRequest'=>0));
				DB::table('crpcontractorhumanresourcefinal')->where('CrpContractorFinalId',$finalContractorId)->where(DB::raw('coalesce(DeleteRequest,0)'),1)->update(array('DeleteRequest'=>0));
			}


		}catch(Exception $e){
			DB::rollback();
			throw $e;
		}
		DB::commit();
		$contractorDetails=ContractorModel::contractorHardList(Input::get('ContractorReference'))->get(array('NameOfFirm','Email','ReferenceNo','ApplicationDate','RemarksByRejector','SysRejectionCode','MobileNo'));
		/*----------------------Contractor Email Details and New Details------------------*/
		$recipientAddress=$contractorDetails[0]->Email;
		$recipientName=$contractorDetails[0]->NameOfFirm;
		$applicationNo=$contractorDetails[0]->ReferenceNo;
		$applicationDate=$contractorDetails[0]->ApplicationDate;
		$remarksByRejector=$contractorDetails[0]->RemarksByRejector;
		$rejectionSysCode=$contractorDetails[0]->SysRejectionCode;
		$mobileNo=$contractorDetails[0]->MobileNo;
		$mailView="emails.crps.mailapplicationrejected";
		$subject="Rejection of Your Registration with CDB";
		$mailData=array(
			'prefix'=>'contractor',
			'applicantName'=>$recipientName,
			'applicationNo'=>$applicationNo,
			'applicationDate'=>$applicationDate,
			'remarksByRejector'=>$remarksByRejector,
			'referenceApplicant'=>Input::get('ContractorReference'),
			'rejectionSysCode'=>$rejectionSysCode,
			'mailMessage'=>"Construction Development Board (CDB) has rejected your application for registration of contractor with CDB. Please read the reason for rejection given below and reapply by making the necessary corrections.",
		);
		$smsMessage="Your application for contractor registration has been rejected. Please check your email ($recipientAddress) to view the reason for rejection.";
		$this->sendEmailMessage($mailView,$mailData,$subject,$recipientAddress,$recipientName);
		$this->sendSms($smsMessage,$mobileNo);
		return Redirect::to('contractor/'.Input::get('RedirectRoute'))->with('savedsuccessmessage','The application has been rejected.');
	}
	public function checkRejectedSecurityCode($contractorReference,$securityCode){
		if(strlen($contractorReference)==36 && strlen($securityCode)==30){
			$checkContractorReference=ContractorModel::where('SysRejectionCode',$securityCode)->pluck('Id');
			$currentStatus=ContractorModel::where('Id',$checkContractorReference)->pluck('CmnApplicationRegistrationStatusId');
			$rejectedDate=ContractorModel::where('Id',$checkContractorReference)->pluck('RejectedDate');
			$rejectedDate=new DateTime($rejectedDate);
			$currentDate=new DateTime(date('Y-m-d'));
			$noOfDays=$rejectedDate->diff($currentDate);
			if($checkContractorReference==$contractorReference && $currentStatus==CONST_CMN_APPLICATIONREGISTRATIONSTATUS_REJECTED && (int)$noOfDays->d < 31){
				DB::table('crpcontractor')->where('Id',$contractorReference)->update(array('ApplicationDate'=>date('Y-m-d')));
				return Redirect::to('contractor/generalinforegistration/'.$contractorReference.'?editbyapplicant=true&rejectedapplicationreapply=true');
			}else{
				return Redirect::to('ezhotin/rejectedapplicationmessage');
			}
		}else{
			App::abort('404');
		}
	}
	
	public function reinstate(){
          try{
			
		  	$inputs['ActionDate'] = date('Y-m-d');
		  	$inputs['MonitoringDate'] = $this->convertDate(Input::get('FromDate'));
		  	$inputs['Remarks'] = Input::get('Remarks');
		  	$inputs['Id'] =  $this->UUID();
		  	$inputs['ActionTaken'] = 5;
			$inputs['CrpContractorFinalId'] = Input::get('CrpContractorFinalId');
			$inputs['CreatedBy'] = Auth::user()->Id;
			$inputs['CreatedOn'] = date('Y-m-d');
		 	$monitoringId = $this->UUID();
		 	DB::table('crpmonitoringoffice')->insert($inputs);
		 	Artisan::call('cron:monitoring',array('id' => $monitoringId));
          
          }catch(Exception $e){
              dd($e->getMessage());
          }

		$postedValues=Input::all();
		$contractorReference=$postedValues['CrpContractorFinalId'];
		$contractorUserId=ContractorFinalModel::where('Id',$contractorReference)->pluck('SysUserId');
		DB::beginTransaction();
		try{
			$postedValues['CmnApplicationRegistrationStatusId'] = "463c2d4c-adbd-11e4-99d7-080027dcfac6";
			$postedValues['ReRegistrationDate']=$this->convertDate(Input::get('FromDate'));
			$instance=ContractorFinalModel::find($contractorReference);
			$instance->fill($postedValues);
			$instance->update();
			$redirectRoute="officenew";
			if((bool)$contractorUserId){
				$userInstance=User::find($contractorUserId);
				$userInstance->Status=1;
				$userInstance->save();
			}
		}catch(Exception $e){
			DB::rollback();
			throw $e;
		}
		DB::commit();
		return Redirect::to('monitoringreport/'.$redirectRoute)->with('savedsuccessmessage','Successfully updated');
	}


	public function deregisterBlackListRegistration(){
	
		$postedValues=Input::all();
		$contractorReference=$postedValues['ContractorReference'];
		$contractorUserId=ContractorFinalModel::where('Id',$contractorReference)->pluck('SysUserId');
		DB::beginTransaction();
		try{
			if(Input::has('DeRegisteredDate')){
				$postedValues['DeRegisteredDate']=$this->convertDate($postedValues['DeRegisteredDate']);
			}elseif(Input::has('BlacklistedDate')){
				$postedValues['BlacklistedDate']=$this->convertDate($postedValues['BlacklistedDate']);
			}elseif(Input::has('RevokedDate')){
				//for suspension
				$postedValues['RevokedDate']=$this->convertDate($postedValues['RevokedDate']);
			}else{
				$postedValues['ReRegistrationDate']=$this->convertDate($postedValues['ReRegistrationDate']);
			}
			$instance=ContractorFinalModel::find($contractorReference);
			$instance->fill($postedValues);
			$instance->update();
			if($postedValues['CmnApplicationRegistrationStatusId']==CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED){
				$redirectRoute="reregistration";
				if((bool)$contractorUserId){
					$userInstance=User::find($contractorUserId);
					$userInstance->Status=1;
					$userInstance->save();
				}

				$contractorAdverserecordInstance = new ContractorCommentAdverseRecordModel;
				$contractorAdverserecordInstance->CrpContractorFinalId = $contractorReference;
				$contractorAdverserecordInstance->Date=date('Y-m-d');
				$contractorAdverserecordInstance->CmnApplicationRegistrationStatusId = CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED;
				$contractorAdverserecordInstance->Remarks=Input::get('ReRegistrationRemarks');
				$contractorAdverserecordInstance->Type=1;
				$contractorAdverserecordInstance->save();
			}else{
				//for suspension
				if(Input::has('BlacklistedRemarks')){
					$redirectRoute="blacklist";
				}elseif(Input::has('RevokedRemarks')){
					$redirectRoute="revoke";
				}else{
					$redirectRoute="deregister";
				}
				if((bool)$contractorUserId){
					$userInstance=User::find($contractorUserId);
					$userInstance->Status=0;
					$userInstance->save();
				}
				/*---Insert Adverse Record i.e the remarks if the contractor is deregistered/blacklisted*/
				if(Input::has('BlacklistedRemarks')){
					$contractorAdverserecordInstance = new ContractorCommentAdverseRecordModel;
					$contractorAdverserecordInstance->CrpContractorFinalId = $contractorReference;
					$contractorAdverserecordInstance->Date=date('Y-m-d');
					$contractorAdverserecordInstance->Remarks=Input::get('BlacklistedRemarks');
					$contractorAdverserecordInstance->Type=1;
					$contractorAdverserecordInstance->save();
				}else{
					if(Input::has('RevokedRemarks')){
						$contractorAdverserecordInstance = new ContractorCommentAdverseRecordModel;
						$contractorAdverserecordInstance->CrpContractorFinalId = $contractorReference;
						$contractorAdverserecordInstance->Date=date('Y-m-d');
						$contractorAdverserecordInstance->CmnApplicationRegistrationStatusId = $postedValues['CmnApplicationRegistrationStatusId'];
						$contractorAdverserecordInstance->Remarks=Input::get('RevokedRemarks');
						$contractorAdverserecordInstance->Type=1;
						$contractorAdverserecordInstance->save();
					}else{
						$contractorAdverserecordInstance = new ContractorCommentAdverseRecordModel;
						$contractorAdverserecordInstance->CrpContractorFinalId = $contractorReference;
						$contractorAdverserecordInstance->Date=date('Y-m-d');
						$contractorAdverserecordInstance->CmnApplicationRegistrationStatusId = CONST_CMN_APPLICATIONREGISTRATIONSTATUS_DEREGISTERED;
						$contractorAdverserecordInstance->Remarks=Input::get('DeregisteredRemarks');
						$contractorAdverserecordInstance->Type=1;
						$contractorAdverserecordInstance->save();
					}

				}
			}
		}catch(Exception $e){
			DB::rollback();
			throw $e;
		}
		DB::commit();
		return Redirect::to('contractor/'.$redirectRoute)->with('savedsuccessmessage','Successfully updated');
	}



	public function blacklistDeregisterList(){
		$type=3;
		$reRegistration=1;
		$parameters=array();
		$contractorId=Input::get('ContractorId');
		$CDBNo=Input::get('CDBNo');
		$tradeLicenseNo = Input::get('TradeLicenseNo');
		$fromDate = Input::get('FromDate');
		$toDate = Input::get('ToDate');
		$statuses = DB::table('cmnlistitem')->whereIn('ReferenceNo',array(12008,12009,12010))->get(array('Id','Name'));
		$query="select distinct T1.Id,T1.CDBNo,T1.MobileNo,T1.TelephoneNo,T1.Email,T1.NameOfFirm,T2.Name as Country,T3.NameEn as Dzongkhag,T4.Name as OwnershipType,B.Code as ClassificationCode from crpcontractorfinal T1 join cmncountry T2 on T1.CmnCountryId=T2.Id join cmnlistitem T4 on T1.CmnOwnershipTypeId=T4.Id join (crpcontractorworkclassificationfinal A join cmncontractorclassification B on A.CmnApprovedClassificationId=B.Id) on T1.Id=A.CrpContractorFinalId and B.Priority=(select max(Priority) from crpcontractorworkclassificationfinal X join cmncontractorclassification Y on X.CmnApprovedClassificationId=Y.Id where X.CrpContractorFinalId=T1.Id) left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id where 1";
		if(Request::path()=="contractor/deregister"){
			$reRegistration=0;
			$type=1;
			$captionHelper="Registered";
			$captionSubject="Deregistration of Contractors";
			$query.=" and T1.CmnApplicationRegistrationStatusId=?";
			array_push($parameters,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED);
		}else if(Request::path()=="contractor/revoke"){
			$reRegistration=0;
			$type=2;
			$captionHelper="Registered";
			$captionSubject="Revoke/Suspend/Debar of Contractors";
			$query.=" and T1.CmnApplicationRegistrationStatusId=?";
			array_push($parameters,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED);
		}else if(Request::path()=="contractor/blacklist"){
			$reRegistration=0;
			$type=3;
			$captionHelper="Registered";
			$captionSubject="Blacklisting of Contractors";
			$query.=" and T1.CmnApplicationRegistrationStatusId=?";
			array_push($parameters,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED);
		}else if(Request::path()=="contractor/reregistration") {
			$captionHelper = "Deregistered or Revoked/Suspended/Debarred/Surrendered";
			$captionSubject = "Re-registration of Contractors";
			$query .= " and (T1.CmnApplicationRegistrationStatusId in (?,?,?,?,?))";
			array_push($parameters, CONST_CMN_APPLICATIONREGISTRATIONSTATUS_DEREGISTERED);
			array_push($parameters, CONST_CMN_APPLICATIONREGISTRATIONSTATUS_REVOKED);
			array_push($parameters, CONST_CMN_APPLICATIONREGISTRATIONSTATUS_SUSPENDED);
			array_push($parameters, CONST_CMN_APPLICATIONREGISTRATIONSTATUS_DEBARRED);
			array_push($parameters, CONST_CMN_APPLICATIONREGISTRATIONSTATUS_SURRENDERED);
		}elseif(Request::path() == "contractor/surrender"){
			$reRegistration=0;
			$type=4;
			$captionHelper="Active";
			$captionSubject="Surrender Certificate of Contractors";
			$query.=" and T1.CmnApplicationRegistrationStatusId=?";
			array_push($parameters,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED);
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
		if((bool)$contractorId!=NULL || (bool)$CDBNo!=NULL){
			$hasParams =true;
			if((bool)$contractorId!=NULL){
				$query.=" and T1.Id=?";
				array_push($parameters,$contractorId);
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

		
		$contractorLists=DB::select($query." order by NameOfFirm".$limit,$parameters);
		return View::make('crps.contractorderegisterationlist')
	
			->with('statuses',$statuses)
			->with('CDBNo',$CDBNo)
			->with('type',$type)
			->with('captionHelper',$captionHelper)
			->with('captionSubject',$captionSubject)
			->with('reRegistration',$reRegistration)
			->with('contractorLists',$contractorLists);
	
	}
	public function editDetails($contractorId){
		$registrationApprovedForPayment=0;
		$userContractor=0;
		$loggedInUser = Auth::user()->Id;
		$userRoles=DB::table('sysuserrolemap')->where('SysUserId',$loggedInUser)->lists('SysRoleId');
		$isAdmin = false;
		if(in_array(CONST_ROLE_ADMINISTRATOR,$userRoles)){
			$isAdmin = true;
		}

		$contractorWarning = DB::table('crpmonitoringoffice as T1')
		->where('T1.CrpContractorFInalId',$contractorId)
		->where('T1.ActionTaken','3')
		->orderBy('T1.ActionDate')
		->get(array('T1.Remarks','T1.ActionDate'));

		



		$generalInformation=ContractorFinalModel::contractor($contractorId)->get(array('crpcontractorfinal.Id','T3.Name as Status','crpcontractorfinal.DeregisteredDate','crpcontractorfinal.DeregisteredRemarks','crpcontractorfinal.RevokedDate','crpcontractorfinal.RevokedRemarks','crpcontractorfinal.DeRegisteredDate','crpcontractorfinal.SurrenderedDate','crpcontractorfinal.SurrenderedRemarks','crpcontractorfinal.CDBNo','crpcontractorfinal.TradeLicenseNo','crpcontractorfinal.TPN','crpcontractorfinal.ApplicationDate','crpcontractorfinal.RegistrationExpiryDate', 'crpcontractorfinal.NameOfFirm','crpcontractorfinal.RegisteredAddress','crpcontractorfinal.Gewog','crpcontractorfinal.Village','crpcontractorfinal.Address','crpcontractorfinal.Email','crpcontractorfinal.TelephoneNo','crpcontractorfinal.MobileNo','crpcontractorfinal.FaxNo','crpcontractorfinal.CmnApplicationRegistrationStatusId','T1.Name as Country','T2.NameEn as Dzongkhag','T4.Name as OwnershipType','T5.NameEn as RegisteredDzongkhag'));
		$ownerPartnerDetails=ContractorHumanResourceFinalModel::contractorPartner($contractorId)->get(array('crpcontractorhumanresourcefinal.Id','crpcontractorhumanresourcefinal.CIDNo','crpcontractorhumanresourcefinal.JoiningDate','crpcontractorhumanresourcefinal.Name','crpcontractorhumanresourcefinal.Sex','crpcontractorhumanresourcefinal.ShowInCertificate','T1.Nationality as Country','T2.Name as Salutation','T3.Name as Designation'));
		$contractorWorkClassifications=ContractorWorkClassificationFinalModel::contractorWorkClassification($contractorId)->select(DB::raw('crpcontractorworkclassificationfinal.Id,T1.Code,T1.Name as Category,coalesce(T1.ReferenceNo,99999999) as CategoryReferenceNo,T2.Name as AppliedClassification,T3.Name as VerifiedClassification,T4.Name as ApprovedClassification'))->get();
		$contractorHumanResources=ContractorHumanResourceFinalModel::contractorHumanResource($contractorId)->get(array(DB::raw('distinct crpcontractorhumanresourcefinal.Id'),'crpcontractorhumanresourcefinal.Name','crpcontractorhumanresourcefinal.EditedOn','crpcontractorhumanresourcefinal.CIDNo','crpcontractorhumanresourcefinal.JoiningDate','crpcontractorhumanresourcefinal.Sex','crpcontractorhumanresourcefinal.Name','T1.Name as Salutation','T2.Name as Qualification','T3.Name as Trade','T4.Name as Designation','T5.Nationality as Country','T10.CDBNO as CDBNo1', 'T11.ARNo as CDBNo2', 'T12.ARNo as CDBNo3'));
		$contractorEquipments=ContractorEquipmentFinalModel::contractorEquipment($contractorId)->get(array('crpcontractorequipmentfinal.Id','crpcontractorequipmentfinal.EditedOn','crpcontractorequipmentfinal.RegistrationNo','crpcontractorequipmentfinal.ModelNo','crpcontractorequipmentfinal.Quantity','T1.Name'));
		$contractorTrackrecords = DB::select("select WorkId,CDBNo,WorkStartDate, WorkCompletionDate,ReferenceNo,ProcuringAgency as Agency,NameOfWork,ProjectCategory as Category,BidAmount as AwardedAmount,FinalAmount,Dzongkhag,ReferenceNo,WorkStatus as Status,(coalesce(OntimeCompletionScore,0) + coalesce(QualityOfExecutionScore,0)) as APS,Remarks,APSFormPath from viewcontractorstrackrecords where CrpContractorFinalId = ? order by year(WorkStartDate) desc,ProcuringAgency",array($contractorId));
		$contractorHumanResourceAttachments=ContractorHumanResourceAttachmentFinalModel::singleContractorHumanResourceAllAttachments($contractorId)->get(array('crpcontractorhumanresourceattachmentfinal.DocumentName','crpcontractorhumanresourceattachmentfinal.DocumentPath','crpcontractorhumanresourceattachmentfinal.CrpContractorHumanResourceFinalId as CrpContractorHumanResourceId'));
		$contractorEquipmentAttachments=DB::select("select DocumentName,DocumentPath, CrpContractorEquipmentFinalId from crpcontractorequipmentattachmentfinal");
		$contractorComments = ContractorCommentAdverseRecordModel::commentList($contractorId)->get(array('Id','Date','Remarks'));
		$contractorAdverseRecords = ContractorCommentAdverseRecordModel::adverseRecordList($contractorId)->get(array('Id','Date','Remarks'));
		$contractorAttachments=ContractorEquipmentAttachmentFinalModel::EquipmentAttachment($contractorId)->get(array('DocumentName','DocumentPath'));
		$contractorEmployeesIds = DB::table('crpcontractorhumanresourcefinal')->where('CrpContractorFinalId',$contractorId)->select(DB::raw("TRIM(CIDNo) as EmpCIDNo"))->lists('EmpCIDNo');
		
		// $monitoringcomment = DB::table('crpcontractorcommentsadverserecord')
		// ->where('type','4')
		// ->select(DB::raw("a.Date,a.Remarks,a.CmnApplicationRegistrationStatusId,a.Id,a.CrpContractorFinalId"));
		
		// $monitoringcomment = DB::table('crpcontractorcommentsadverserecord as T1')
		// ->where('T1.Type','4');
		$monitoringcomment = MonitoringOfficeModel::where('CrpContractorFinalId',$contractorId)->get(array('Remarks','MonitoringDate','Id','.ActionTaken'));
		

		//crpmonitoringoffice

		
		$trainingsAttended = DB::table('crpcontractortrainingdetail as T1')
			->join('crpcontractortraining as T2','T1.CrpContractorTrainingId','=','T2.Id')
			->join('cmnlistitem as A','A.Id','=','T2.CmnTrainingTypeId')
			->leftJoin('cmnlistitem as T3','T3.Id','=','T2.CmnTrainingModuleId')
			->whereIn(DB::raw("TRIM(T1.CIDNo)"),$contractorEmployeesIds)
			->orWhere('T1.CrpContractorFinalId',$contractorId)
			->orderBy('TrainingFromDate','Desc')
			->get(array("T1.Participant","TrainingFromDate","TrainingToDate",'T1.CIDNo','T3.Name as Module','A.ReferenceNo as TrainingReference','A.Name as TrainingType'));
		return View::make('crps.contractorinformation')
			->with('isAdmin',$isAdmin)
			->with('registrationApprovedForPayment',$registrationApprovedForPayment)
			->with('trainingsAttended',$trainingsAttended)
			->with('contractorAttachments',$contractorAttachments)
			->with('userContractor',$userContractor)
			->with('contractorId',$contractorId)
			->with('generalInformation',$generalInformation)
			->with('ownerPartnerDetails',$ownerPartnerDetails)
			->with('contractorWorkClassifications',$contractorWorkClassifications)
			->with('contractorHumanResources',$contractorHumanResources)
			->with('contractorEquipments',$contractorEquipments)
			->with('contractorTrackrecords',$contractorTrackrecords)
			->with('contractorHumanResourceAttachments',$contractorHumanResourceAttachments)
			->with('contractorEquipmentAttachments',$contractorEquipmentAttachments)
			->with('contractorComments',$contractorComments)
			->with('contractorAdverseRecords',$contractorAdverseRecords)
			->with('monitoringcomment',$monitoringcomment)
			->with('contractorWarning',$contractorWarning);
			
	}
	public function newCommentAdverseRecord($contractorId){
		$contractor=ContractorFinalModel::contractorHardList($contractorId)->get(array('Id','CDBNo','NameOfFirm'));
		return View::make('crps.contractornewadverserecordsandcomments')
			->with('contractorId',$contractorId)
			->with('contractor',$contractor);
	}
	public function editCommentAdverseRecord($contractorId){
		$contractor=ContractorFinalModel::contractorHardList($contractorId)->get(array('Id','CDBNo','NameOfFirm'));
		$commentsAdverseRecords=ContractorCommentAdverseRecordModel::commentAdverseRecordList($contractorId)->get(array('Id','Date','Remarks','Type'));
		return View::make('crps.contractoreditadverserecordscomments')
			->with('contractor',$contractor)
			->with('commentsAdverseRecords',$commentsAdverseRecords);
	}
	
	public function saveCommentAdverseRecord(){
		if(Input::get('Type')=='4')
		{
			$date=$this->convertDate(Input::get('Date'));
			$postedValues['MonitoringDate'] =$date;
			$postedValues['Remarks'] = Input::get('Remarks');
			$postedValues['ActionTaken'] = Input::get('Type');
			$postedValues['CrpContractorFinalId'] = Input::get('CrpContractorFinalId');
			$monitoringId = Input::get('CrpMonitoringOfficeId');
			$contractorId = Input::get('CrpContractorFinalId');
			$postedValues['Id'] = $this->UUID();
			try{
				DB::table('crpmonitoringoffice')->insert($postedValues);
			}catch(Exception $e){
				dd($e->getMessage());
			}

		}
		else
		{
			$postedValues=Input::all();
			$date=$this->convertDate(Input::get('Date'));
			$postedValues['Date']=$date;
			$validation = new ContractorCommentAdverseRecordModel;
			if(!($validation->validate($postedValues))){
				$errors = $validation->errors();
				return Redirect::to('contractor/editdetails/'.$postedValues['CrpContractorFinalId'])->withErrors($errors)->withInput();
			}
			ContractorCommentAdverseRecordModel::create($postedValues);
		}
		return Redirect::to('contractor/editdetails/'.$postedValues['CrpContractorFinalId'])->with('savedsuccessmessage','Record sucessfully added.');
	}
	public function updateCommentAdverseRecord(){
		$postedValues=Input::all();
		$date=$this->convertDate(Input::get('Date'));
		$postedValues['Date']=$date;
		$instance=ContractorCommentAdverseRecordModel::find($postedValues['Id']);
		if($instance==null)
		{
			//$date=$this->convertDate(Input::get('Date'));
			//return Input::get('Date');
			
			$postedValues['MonitoringDate']=$date;
			$instance=MonitoringOfficeModel::find($postedValues['Id']);
			$instance->fill($postedValues);
			$instance->update();
			
		}
		else{
			$instance->fill($postedValues);
			$instance->update();
		}
		return Redirect::to('contractor/editdetails/'.$postedValues['CrpContractorFinalId'].'#commentsadverserecords')->with('savedsuccessmessage','Record has been successfully updated');;
	}
	public function listOfWorks(){
		$parameters=array();
		$underProcess=0;
		$procuringAgencyId=Input::get('ProcuringAgency');
		$workStartDateFrom=Input::get('WorkStartDateFrom');
		$workStartDateTo=Input::get('WorkStartDateTo');
		$workOrderNo=Input::get('WorkOrderNo');
		$workStatus=Input::get('WorkExecutionStatus');
		$cdbNo=Input::get('CDBNo');
		$query="select T1.Id,T1.NameOfWork,B.CDBNo,T1.WorkOrderNo,T1.ContractPeriod,T1.WorkStartDate,T1.WorkCompletionDate,T2.Name as ProcuringAgency,T3.Name as WorkCategory,T4.Name as ContractorClass,T5.Name as WorkExecutionStatus from crpbiddingform T1 left join (crpbiddingformdetail A join crpcontractorfinal B on B.Id = A.CrpContractorFinalId) on A.CrpBiddingFormId = T1.Id and A.CmnWorkExecutionStatusId = ? join cmnprocuringagency T2 on T1.CmnProcuringAgencyId=T2.Id join cmncontractorworkcategory T3 on T1.CmnContractorProjectCategoryId=T3.Id join cmncontractorclassification T4 on T1.CmnContractorClassificationId=T4.Id join cmnlistitem T5 on T1.CmnWorkExecutionStatusId=T5.Id where Type=0";
		array_push($parameters,CONST_CMN_WORKEXECUTIONSTATUS_AWARDED);
		if(Request::path()=="contractor/editcompletedworklist"){
			$query.=" and (T1.CmnWorkExecutionStatusId=? or T1.CmnWorkExecutionStatusId=?)";
			array_push($parameters,CONST_CMN_WORKEXECUTIONSTATUS_COMPLETED);
			array_push($parameters,CONST_CMN_WORKEXECUTIONSTATUS_TERMINATED);
		}elseif(Request::path()=="contractor/worklist" || Request::path()=="contractor/editbiddingformlist"){
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
				$query.=" and B.CDBNo = ?";
				array_push($parameters,$cdbNo);
			}
		}
		$listOfWorks=DB::select($query." order by ProcuringAgency,T1.WorkStartDate",$parameters);
		$procuringAgencies=ProcuringAgencyModel::procuringAgencyHardList()->get(array('Id','Name'));
		$workExecutionStatus=CmnListItemModel::workExecutionStatus()->whereRaw('(ReferenceNo=? or ReferenceNo=?)',array(3003,3004))->get(array('Id','Name'));
		return View::make('crps.contractorlistofworks')
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
		$redirectRoute='contractor/worklist';
		if(!empty($detailsOfCompletedWorks[0]->OntimeCompletionScore)){
			$redirectRoute='contractor/editcompletedworklist';
		}
		$contractDetails=CrpBiddingFormModel::biddingFormContractorCdbAll()
			->where('crpbiddingform.Id',$bidId)
//								->where('crpbiddingform.ByCDB',1)
			->get(array('crpbiddingform.Id','crpbiddingform.WorkOrderNo','crpbiddingform.NameOfWork','crpbiddingform.DescriptionOfWork','crpbiddingform.ContractPeriod','crpbiddingform.WorkStartDate','crpbiddingform.WorkCompletionDate','crpbiddingform.ApprovedAgencyEstimate','T1.Name as ProcuringAgency','T2.Name as ProjectCategory','T3.Name as ContractorClass','T4.NameEn as Dzongkhag'));
		$workAwardedContractor=CrpBiddingFormDetailModel::biddingFormContractorContractBidders($bidId)
			->where('crpbiddingformdetail.CmnWorkExecutionStatusId',CONST_CMN_WORKEXECUTIONSTATUS_AWARDED)
			->get(array('T1.CDBNo','crpbiddingformdetail.Id','crpbiddingformdetail.BidAmount','crpbiddingformdetail.EvaluatedAmount','T1.NameOfFirm'));
		return View::make('crps.contractorworkcompletionform')
			->with('model',$model)
			->with('redirectRoute',$redirectRoute)
			->with('detailsOfCompletedWorks',$detailsOfCompletedWorks)
			->with('workExecutionStatus',$workExecutionStatus)
			->with('contractDetails',$contractDetails)
			->with('workAwardedContractor',$workAwardedContractor);
	}
	public function printDetails($contractorId){
		$initialDate = "";
		if(Route::current()->getUri()=="contractor/viewprintdetails/{contractorid}"){
			$view='printpages.contractorviewprintinformation';
			$data['isfinalprint']=1;
			$initialDate = DB::table('crpcontractorfinal')->where('Id',$contractorId)->pluck('InitialDate');
			$generalInformation=ContractorFinalModel::contractor($contractorId)->get(array('crpcontractorfinal.Id','crpcontractorfinal.DeRegisteredDate','crpcontractorfinal.TradeLicenseNo','crpcontractorfinal.TPN','crpcontractorfinal.CDBNo','crpcontractorfinal.RegisteredAddress','crpcontractorfinal.RegistrationExpiryDate','crpcontractorfinal.ApplicationDate','crpcontractorfinal.NameOfFirm',DB::raw('coalesce(crpcontractorfinal.RegistrationApprovedDate,crpcontractorfinal.ApplicationDate) as RenewalDate'),'crpcontractorfinal.Address','crpcontractorfinal.Email','crpcontractorfinal.TelephoneNo','crpcontractorfinal.MobileNo','crpcontractorfinal.FaxNo','crpcontractorfinal.CmnApplicationRegistrationStatusId','T1.Name as Country','T2.NameEn as Dzongkhag','T3.Name as CurrentStatus','T4.Name as OwnershipType'));
			$ownerPartnerDetails=ContractorHumanResourceFinalModel::contractorPartner($contractorId)->get(array(DB::raw('distinct crpcontractorhumanresourcefinal.Id'),'crpcontractorhumanresourcefinal.CIDNo','crpcontractorhumanresourcefinal.Name','crpcontractorhumanresourcefinal.Sex','crpcontractorhumanresourcefinal.JoiningDate','crpcontractorhumanresourcefinal.ShowInCertificate','T1.Nationality as Country','T2.Name as Salutation','T3.Name as Designation'));
			$contractorWorkClassifications=ContractorWorkClassificationFinalModel::contractorWorkClassification($contractorId)->select(DB::raw('crpcontractorworkclassificationfinal.Id,T1.Code,T1.Name as Category,coalesce(T1.ReferenceNo,99999999) as CategoryReferenceNo,T2.Name as AppliedClassification,T3.Name as VerifiedClassification,T4.Name as ApprovedClassification'))->get();
			$contractorHumanResources=ContractorHumanResourceFinalModel::ContractorHumanResource($contractorId)->get(array('crpcontractorhumanresourcefinal.Id','crpcontractorhumanresourcefinal.Name','crpcontractorhumanresourcefinal.CIDNo','crpcontractorhumanresourcefinal.Sex','crpcontractorhumanresourcefinal.JoiningDate','crpcontractorhumanresourcefinal.Name','T1.Name as Salutation','T2.Name as Qualification','T3.Name as Trade','T4.Name as Designation','T5.Nationality as Country'));
			$contractorEquipments=ContractorEquipmentFinalModel::contractorEquipment($contractorId)->get(array('crpcontractorequipmentfinal.Id','crpcontractorequipmentfinal.RegistrationNo','crpcontractorequipmentfinal.ModelNo','crpcontractorequipmentfinal.Quantity','T1.Name'));
			//$contractorTrackrecords=CrpBiddingFormModel::contractorTrackRecords($contractorId)->get(array('crpbiddingform.WorkOrderNo','crpbiddingform.NameOfWork','crpbiddingform.DescriptionOfWork','crpbiddingform.ContractPeriod','crpbiddingform.WorkStartDate','crpbiddingform.WorkCompletionDate','T2.Name as ProcuringAgency','T3.Code as ProjectCategory','T4.Name as classification','T5.NameEn as Dzongkhag'));
			$contractorTrackrecords=CrpContractorTrackRecordModel::trackRecord($contractorId)->get(
				array(
					'WorkStatus','WorkCompletionDate','WorkStartDate','ProcuringAgency','WorkId','NameOfWork','ProjectCategory','FinalAmount as EvaluatedAmount','ContractPeriod','WorkStartDate','WorkCompletionDate','WorkStatus','OntimeCompletionScore','QualityOfExecutionScore','ProcuringAgency'
				)
			);
			$commentsAdverseRecords=ContractorCommentAdverseRecordModel::commentAdverseRecordList($contractorId)->get(array('Id','Date','Remarks','Type'));
			$data['contractorTrackrecords']=$contractorTrackrecords;
			$data['commentsAdverseRecords']=$commentsAdverseRecords;
		}else{
			$view="printpages.contractorprintregistrationinformation";
			$data['isfinalprint']=0;
			$generalInformation=ContractorModel::contractor($contractorId)->get(array('crpcontractor.ReferenceNo','crpcontractor.ApplicationDate','crpcontractor.NameOfFirm','crpcontractor.Address','crpcontractor.Email','crpcontractor.TelephoneNo','crpcontractor.MobileNo','crpcontractor.FaxNo','crpcontractor.CmnApplicationRegistrationStatusId','T1.Name as Country','T2.NameEn as Dzongkhag','T3.Name as CurrentStatus','T7.Name as OwnershipType','T7.ReferenceNo as OwnershipTypeReferenceNo'));
			$ownerPartnerDetails=ContractorHumanResourceModel::contractorPartner($contractorId)->get(array(DB::raw('distinct crpcontractorhumanresource.Id'),'crpcontractorhumanresource.CIDNo','crpcontractorhumanresource.JoiningDate','crpcontractorhumanresource.Name','crpcontractorhumanresource.Sex','crpcontractorhumanresource.ShowInCertificate','T1.Nationality as Country','T2.Name as Salutation','T3.Name as Designation'));
			$contractorWorkClassifications=DB::select("select T1.Code,T1.Name as Category,T2.Name as AppliedClassification from cmncontractorworkcategory T1 join crpcontractorworkclassification X on T1.Id=X.CmnProjectCategoryId join cmncontractorclassification T2 on T2.Id=X.CmnAppliedClassificationId where X.CrpContractorId=?",array($contractorId));
			$contractorHumanResources=ContractorHumanResourceModel::ContractorHumanResource($contractorId)->get(array('crpcontractorhumanresource.Id','crpcontractorhumanresource.Name','crpcontractorhumanresource.CIDNo','crpcontractorhumanresource.JoiningDate','crpcontractorhumanresource.Sex','crpcontractorhumanresource.Name','T1.Name as Salutation','T2.Name as Qualification','T3.Name as Trade','T4.Name as Designation','T5.Nationality as Country'));
			$contractorEquipments=ContractorEquipmentModel::contractorEquipment($contractorId)->get(array('crpcontractorequipment.Id','crpcontractorequipment.RegistrationNo','crpcontractorequipment.ModelNo','crpcontractorequipment.Quantity','T1.Name'));
			$feeStructures=DB::select("select T1.Code as CategoryCode,T1.Name as Category,T2.Code as AppliedClassificationCode,T2.Name as AppliedClassification,T2.RegistrationFee as AppliedRegistrationFee from cmncontractorworkcategory T1 join crpcontractorworkclassification X on T1.Id=X.CmnProjectCategoryId join cmncontractorclassification T2 on T2.Id=X.CmnAppliedClassificationId where X.CrpContractorId=? order by T1.Code,T1.Name",array($contractorId));
			$attachments=ContractorAttachmentModel::attachment($contractorId)->get(array('DocumentName'));
			$data['feeStructures']=$feeStructures;
			$data['attachments']=$attachments;
		}
		$data['InitialDate'] = $initialDate;
		$data['printTitle']="Contractor's Information";
		$data['generalInformation']=$generalInformation;
		$data['ownerPartnerDetails']=$ownerPartnerDetails;
		$data['contractorWorkClassifications']=$contractorWorkClassifications;
		$data['contractorHumanResources']=$contractorHumanResources;
		$data['contractorEquipments']=$contractorEquipments;
		$pdf = App::make('dompdf');
		$pdf->loadView($view,$data)->setPaper('a4')->setOrientation('landscape');
		return $pdf->stream();
	}
	public function checkCDBNo(){
		$inputCDBNo=Input::get('inputCDBNo');
		$cdbNoFinalCount=ContractorFinalModel::contractorHardListAll()->where('CDBNo',$inputCDBNo)->count();
		$cdbNoCount=ContractorModel::contractorHardListAll()->where('CDBNo',$inputCDBNo)->whereIn('CmnApplicationRegistrationStatusId',array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVEDFORPAYMENT,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_VERIFIED))->count();
		if((int)$cdbNoCount>0 || (int)$cdbNoFinalCount>0){
			return 0;
		}
		return 1;
	}
	public function fetchContractorsJSON(){
		$term = Input::get('term');
		$contractors = DB::table('crpcontractorfinal')->where(DB::raw('TRIM(NameOfFirm)'),DB::raw('like'),"$term%")->orWhereRaw("TRIM(CDBNo) like '$term%'")->get(array('Id',DB::raw('concat(TRIM(NameOfFirm)," (",CDBNo,")") as NameOfFirm')));
		$contractorsJson = array();
		foreach($contractors as $contractor){
			array_push($contractorsJson,array('id'=>$contractor->Id,'value'=>trim($contractor->NameOfFirm)));
		}
		return Response::json($contractorsJson);
	}
	public function deleteCommentAdverseRecord(){
		$id = Input::get('id');
		try{
			$instance=ContractorCommentAdverseRecordModel::find($id);
			if($instance==null)
			{
				DB::table('crpmonitoringoffice')->where('Id',$id)->delete();
			}
			else
			{
				DB::table('crpcontractorcommentsadverserecord')->where('Id',$id)->delete();
			}
			return 1;
		}catch(Exception $e){
			return 0;
		}
	}
	public function changeExpiryDate($id){
		$generalInformation=ContractorFinalModel::contractor($id)->get(array('crpcontractorfinal.Id','crpcontractorfinal.CDBNo','crpcontractorfinal.ApplicationDate','crpcontractorfinal.RegistrationExpiryDate', 'crpcontractorfinal.NameOfFirm','crpcontractorfinal.RegisteredAddress','crpcontractorfinal.Gewog','crpcontractorfinal.Village','crpcontractorfinal.Address','crpcontractorfinal.Email','crpcontractorfinal.TelephoneNo','crpcontractorfinal.MobileNo','crpcontractorfinal.FaxNo','crpcontractorfinal.CmnApplicationRegistrationStatusId','T1.Name as Country','T2.NameEn as Dzongkhag','T4.Name as OwnershipType','T5.NameEn as RegisteredDzongkhag'));
		return View::make('crps.changeexpirydate')
			->with('generalInformation',$generalInformation)
			->with('id',$id);
	}
	public function postChangeExpiryDate(){
		$id = Input::get('Id');
		$instance = ContractorFinalModel::find($id);
		$instance->fill(array('RegistrationExpiryDate'=>$this->convertDate(Input::get('RegistrationExpiryDate'))));
		$instance->update();

		return Redirect::to('contractor/editlist')->with('savedsuccessmessage','Expiry Date has been updated');
	}
	public function getFeeStructure($contractorId){
		$printTitle = "Contractor Fee Structure";
		$feeStructures=DB::select("select T1.Code as CategoryCode,T1.Name as Category,T2.Code as AppliedClassificationCode,T2.Name as AppliedClassification,T2.RegistrationFee as AppliedRegistrationFee from cmncontractorworkcategory T1 join crpcontractorworkclassification X on T1.Id=X.CmnProjectCategoryId join cmncontractorclassification T2 on T2.Id=X.CmnAppliedClassificationId where X.CrpContractorId=? order by T1.Code,T1.Name",array($contractorId));
		return View::make('printpages.contractorprintfeestructure')
			->with('printTitle',$printTitle)
			->with('feeStructures',$feeStructures);
	}
	public function viewRegistrationList(){
		$contractorIdMyTask=Input::get('CrpContractorIdMyTask');
		$fromDateMyTask='2016-06-01';
		$toDateMyTask=Input::get('ToDateMyTask');
		$query="select distinct T1.Id,T1.ReferenceNo,T1.ApplicationDate,T1.MobileNo,T1.TelephoneNo,T1.Email,T1.NameOfFirm,T1.CmnApplicationRegistrationStatusId,T2.Name as Country,T3.NameEn as Dzongkhag,T4.Name as OwnershipType,B.Code as ClassificationCode,B.Name as ClassificationName from (crpcontractor T1 join crpcontractorfinal X on X.Id = T1.Id) join cmncountry T2 on T1.CmnCountryId=T2.Id join cmnlistitem T4 on T1.CmnOwnershipTypeId=T4.Id join (crpcontractorworkclassification A join cmncontractorclassification B on A.CmnAppliedClassificationId=B.Id) on T1.Id=A.CrpContractorId and B.Priority=(select max(Priority) from crpcontractorworkclassification X join cmncontractorclassification Y on X.CmnAppliedClassificationId=Y.Id where X.CrpContractorId=T1.Id) left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id where T1.CmnApplicationRegistrationStatusId=? and T1.SysLockedByUserId is null and T1.CrpContractorId is null";
		$parameters=array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED);
		if((bool)$contractorIdMyTask!=NULL || (bool)$fromDateMyTask!=NULL || (bool)$toDateMyTask!=NULL){
			if((bool)$contractorIdMyTask!=NULL){
				$query.=" and T1.Id=?";
				array_push($parameters,$contractorIdMyTask);
			}
			if((bool)$fromDateMyTask!=NULL){
				$fromDateMyTask=$this->convertDate($fromDateMyTask);
				$query.=" and T1.ApplicationDate>=?";
				array_push($parameters,$fromDateMyTask);
			}
			if((bool)$toDateMyTask!=NULL){
				$toDateMyTask=$this->convertDate($toDateMyTask);
				$query.=" and T1.ApplicationDate<=?";
				array_push($parameters,$toDateMyTask);
			}
		}
		$contractorLists=DB::select($query." and X.SysFinalApproverUserId is null order by ApplicationDate,NameOfFirm",$parameters);
		return View::make('crps.contractorregistrationviewlist')
			->with('pageTitle',"Approve Contractor's Registration")
			->with('contractorIdMyTask',$contractorIdMyTask)
			->with('fromDateMyTask',$fromDateMyTask)
			->with('toDateMyTask',convertDateToClientFormat($toDateMyTask))
			->with('contractorLists',$contractorLists);
	}
	public function saveFinalRemarks(){
		DB::beginTransaction();
		$redirectRoute = 'contractor/viewapprovedapplications';
		try{
			if(Input::has('IsServiceApplication')){
				$redirectRoute = 'contractor/viewserviceapplication';
				$object = ContractorModel::find(Input::get('ContractorReference'));
			}else{
				$redirectRoute = 'contractor/viewapprovedapplications';
				$object = ContractorFinalModel::find(Input::get('ContractorReference'));
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
	public function postSaveSurrender(){
		$postedValues = Input::except("ContractorReference","_token");
		$postedValues['SurrenderedDate'] = $this->convertDate($postedValues['SurrenderedDate']);
		$id = Input::get("ContractorReference");
		DB::beginTransaction();
		try{
			$object = ContractorFinalModel::find($id);
			$object->fill($postedValues);
			$object->update();

			$contractorAdverserecordInstance = new ContractorCommentAdverseRecordModel;
			$contractorAdverserecordInstance->CrpContractorFinalId = $id;
			$contractorAdverserecordInstance->Date=date('Y-m-d');
			$contractorAdverserecordInstance->Remarks=Input::get('SurrenderedRemarks');
			$contractorAdverserecordInstance->CmnApplicationRegistrationStatusId = CONST_CMN_APPLICATIONREGISTRATIONSTATUS_SURRENDERED;
			$contractorAdverserecordInstance->Type=1;
			$contractorAdverserecordInstance->save();

		}catch(Exception $e){
			DB::rollBack();
		}
		DB::commit();
		return Redirect::to("contractor/surrender")->with("savedsuccessmessage","Record has been updated");
	}
	
	public function saveWorkClassificationFinal(){


		
		$postBackUrl = Input::get("PostBackUrl");
		$contractorReference = Input::get("CrpContractorId");
		$categoryClassificationInputs = Input::get("ContractorWorkClassificationModel");
		$isMonitoringAction = false;
		$applicationHistoryArray = array();
		DB::beginTransaction();

		try{
			$cdbNo = DB::table('crpcontractorfinal')->where('Id', $contractorReference)->pluck('CDBNo');
			$monitoringId = "";
			if($postBackUrl == "monitoringreport/officeaction") {
				$isMonitoringAction = true;
				$redirect = $postBackUrl;
				
				$monitoringId = $this->UUID();
				$monitoringArray['Id'] = $monitoringId;
				//$monitoringId = Input::get('MonitoringOfficeId');
				$monitoringArray["CrpContractorFinalId"] = $contractorReference;
				$monitoringArray['ActionTaken'] = 1;
				$monitoringArray['MonitoringDate'] = $this->convertDate(Input::get('MonitoringDate'));
				$monitoringArray['ActionDate'] = $this->convertDate(Input::get('MonitoringDate'));
				$monitoringArray['FromDate'] = $this->convertDate(Input::get('FromDate'));
				$monitoringArray['ToDate'] = $this->convertDate(Input::get('ToDate'));
				$monitoringArray['Remarks'] = Input::get('Remarks');
				$monitoringArray['EditedBy'] = Auth::user()->Id;
				$monitoringArray['EditedOn'] = date("Y-m-d G:i:s");
				$monitoringArray['CreatedBy'] = Auth::user()->Id;
				$monitoringArray['CreatedOn'] = date("Y-m-d G:i:s");
				
				
				//return $contractorReference;
//				MonitoringOfficeModel::create($monitoringArray);
				DB::table('crpmonitoringoffice')->insert($monitoringArray);
//				Artisan::call('cron:monitoring',array('id' => $monitoringId));



				$loggedInUserId = Auth::user()->Id;
				$recordIds = DB::table('crpcontractorworkclassificationfinal')->where('CrpContractorFinalId',$contractorReference)
				->get(array('Id','CmnProjectCategoryId','CmnApprovedClassificationId'));
				foreach($recordIds as $recordId):

					DB::statement("
					INSERT INTO `t_monitoring_downgrade_record` 
						(`id`, 
						`contractor_id`, 
						`category_id`, 
						`classification_id`, 
						`monitoring_id`, 
						`type`
						)
						VALUES
						(UUID(),'$contractorReference','$recordId->CmnProjectCategoryId'
						,'$recordId->CmnApprovedClassificationId','$monitoringId','1' );
					");


					DB::statement("insert into sysdeletedrecord (Id,ParentId,SysUserId) VALUES ('$recordId->Id','$recordId->Id','$loggedInUserId')");
				endforeach;

				DB::table('crpcontractorworkclassificationfinal')->where('CrpContractorFinalId',$contractorReference)->delete();
				if(Input::has('downgrade')){
					$applicationHistoryArray = array('ai_key'=>$this->UUID(),'ai_appDate'=>date('Y-m-d G:i:s'),'ai_CDBRegNum'=>trim($cdbNo),'ai_rg_type'=>'Downgrade by CDB official');
				}else{
					$applicationHistoryArray = array('ai_key'=>$this->UUID(),'ai_appDate'=>date('Y-m-d G:i:s'),'ai_CDBRegNum'=>trim($cdbNo),'ai_rg_type'=>'Change of Category/Classification by CDB official');
				}


			}else{
				$loggedInUserId = Auth::user()->Id;
				$recordIds = DB::table('crpcontractorworkclassificationfinal')
				->where('CrpContractorFinalId',$contractorReference)
				->get(array('Id','CmnProjectCategoryId','CmnApprovedClassificationId'));
				
				foreach($recordIds as $recordId):
					DB::statement("insert into sysdeletedrecord (Id,ParentId,SysUserId) VALUES ('$recordId->Id','$recordId->Id','$loggedInUserId')");
				endforeach;
				

				DB::table('crpcontractorworkclassificationfinal')->where('CrpContractorFinalId',$contractorReference)->delete();
				if(Input::has('downgrade')){
					$applicationHistoryArray = array('ai_key'=>$this->UUID(),'ai_appDate'=>date('Y-m-d G:i:s'),'ai_CDBRegNum'=>trim($cdbNo),'ai_rg_type'=>'Downgrade by CDB official');
				}else{
					$applicationHistoryArray = array('ai_key'=>$this->UUID(),'ai_appDate'=>date('Y-m-d G:i:s'),'ai_CDBRegNum'=>trim($cdbNo),'ai_rg_type'=>'Change of Category/Classification by CDB official');
				}
				$redirect = $postBackUrl.'/'.$contractorReference;

			}
			foreach($categoryClassificationInputs as $key=>$value):
				$saveArray['Id'] = $this->UUID();
				foreach($value as $a=>$b):
					$saveArray[$a] = $b;
					// if($postBackUrl == "monitoringreport/officeaction"){
					// 	if($a == "CmnProjectCategoryId"){
					// 		if($b == CONST_CATEGORY_W1){
					// 			$applicationHistoryArray['ai_w1_Approved'] = DB::table('cmncontractorclassification')->where('Id',$value['CmnAppliedClassificationId'])->pluck('Code');
					// 		}
					// 		if($b == CONST_CATEGORY_W2){
					// 			$applicationHistoryArray['ai_w2_Approved'] = DB::table('cmncontractorclassification')->where('Id',$value['CmnAppliedClassificationId'])->pluck('Code');
					// 		}
					// 		if($b == CONST_CATEGORY_W3){
					// 			$applicationHistoryArray['ai_w3_Approved'] = DB::table('cmncontractorclassification')->where('Id',$value['CmnAppliedClassificationId'])->pluck('Code');
					// 		}
					// 		if($b == CONST_CATEGORY_W4){
					// 			$applicationHistoryArray['ai_w4_Approved'] = DB::table('cmncontractorclassification')->where('Id',$value['CmnAppliedClassificationId'])->pluck('Code');
					// 		}
					// 	}
					// }
				endforeach;
				$saveArray["CrpContractorFinalId"] = $saveArray["CrpContractorId"];
				unset($saveArray["CrpContractorId"]);
				$saveArray['CmnVerifiedClassificationId'] = $saveArray["CmnAppliedClassificationId"];
				$saveArray['CmnApprovedClassificationId'] = $saveArray["CmnAppliedClassificationId"];
				
				
				if(!$isMonitoringAction){
					ContractorWorkClassificationFinalModel::create($saveArray);
				}else{ 
					ContractorWorkClassificationFinalModel::create($saveArray);
					unset($saveArray['CmnAppliedClassificationId']);
					unset($saveArray['CmnVerifiedClassificationId']);
					$saveArray['CmnClassificationId'] = $saveArray['CmnApprovedClassificationId'];
					unset($saveArray['CmnApprovedClassificationId']);
					unset($saveArray['CrpContractorFinalId']);
					$saveArray['CrpMonitoringOfficeId'] = $monitoringId;
					ContractorWorkClassificationMonitoringModel::create($saveArray);
				}
			endforeach;
			if(!$isMonitoringAction){
				DB::table('applicationhistory')->insert($applicationHistoryArray);
			}
		}catch(Exception $e){
			DB::rollback();
			throw $e;
		}
		DB::commit();
		return Redirect::to($redirect)->with('savedsuccessmessage','Record has been updated!');
	}


	
	public function getMonitoringReportIndex(){
		return View::make('crps.contractormonitoringreportindex');
	}
	public function getMonitoringReportOffice(){
		return View::make('crps.contractormonitoringreportoffice');
	}
	public function getMonitoringReportSites(){
		return View::make('crps.contractormonitoringreportsites');
	}
	public function postFetchDetails(){
		$id = Input::get('id');
		$hrDetails = DB::table('crpcontractorhumanresourcefinal as T1')
			->leftJoin('cmnlistitem as T2','T2.Id','=','T1.CmnDesignationId')
			->where('T1.CrpContractorFinalId',$id)->get(array('T2.Name as Designation','T1.Name as Personnel'));
		$eqDetails = DB::table('crpcontractorequipmentfinal as T1')
			->leftJoin('cmnequipment as T2','T2.Id','=','T1.CmnEquipmentId')
			->where('T1.CrpContractorFinalId',$id)->get(array(DB::raw('TRIM(T2.Name) as Equipment'),DB::raw("coalesce(T1.RegistrationNo,'') as RegistrationNo")));
		$workClassificationDetails = DB::table('crpcontractorworkclassificationfinal as T1')
			->join('cmncontractorclassification as T2','T2.Id','=','T1.CmnApprovedClassificationId')
			->join('cmncontractorworkcategory as T3','T3.Id','=','T1.CmnProjectCategoryId')
			->where('T1.CrpContractorFinalId',$id)
			->orderBy('T3.ReferenceNo')
			->get(array('T3.Code as Category','T2.Name as Class'));
		return Response::json(array('hrDetails'=>$hrDetails,'categoryDetails'=>$workClassificationDetails,'eqDetails'=>$eqDetails));
	}
	public function getSiteContractorInfo($id){

	}
}