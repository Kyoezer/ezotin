<?php
class Consultant extends CrpsController{
	public function defaultIndex(){
		$feeStructures=DB::select('select Id,Name,ConsultantAmount,ConsultantValidity from crpservice where Id=? order by Name',array(CONST_SERVICETYPE_NEW));
		return View::make('crps.consultantindex')
					->with('feeStructures',$feeStructures);
	}
	public function serviceCategory(){
		$serviceCategories=ConsultantServiceCategoryModel::category()->get(array('Id','Code','Name'));
		$categoryId=Input::get('sref');
        if((bool)$categoryId==NULL || empty($categoryId)){
            $editServiceCategories=array(new ConsultantServiceCategoryModel());
        }else{
            $editServiceCategories=ConsultantServiceCategoryModel::category()->where('Id',$categoryId)->get(array('Id','Code','Name'));
        }
		return View::make('crps.consultantservicecategory')
					->with('serviceCategories',$serviceCategories)
					->with('editServiceCategories',$editServiceCategories);
	}
	public function service(){
		$services=ConsultantServiceModel::serviceList()->get(array('cmnconsultantservice.Id','cmnconsultantservice.Code as ServiceCode','cmnconsultantservice.Name as ServiceName','T1.Code as CategoryCode','T1.Name as CategoryName'));
		$categories=ConsultantServiceCategoryModel::category()->get(array('Id','Name'));
		$serviceId=Input::get('sref');
        if((bool)$serviceId==NULL || empty($serviceId)){
            $editServices=array(new ConsultantServiceModel());
        }else{
            $editServices=ConsultantServiceModel::service()->where('Id',$serviceId)->get(array('Id','Code','Name','CmnConsultantServiceCategoryId'));
        }
		return View::make('crps.consultantservice')
					->with('services',$services)
					->with('categories',$categories)
					->with('editServices',$editServices);
	}
	public function saveServiceCategory(){
		$postedValues=Input::all();
		$validation = new ConsultantServiceCategoryModel;
		if(!($validation->validate($postedValues))){
		    $errors = $validation->errors();
		    return Redirect::to('master/consultantservicecategory')->withErrors($errors)->withInput();
		}
		if(empty($postedValues["Id"])){
			ConsultantServiceCategoryModel::create($postedValues);
			return Redirect::to('master/consultantservicecategory')->with('savedsuccessmessage','Service Category has been successfully added');
		}else{
			$instance=ConsultantServiceCategoryModel::find($postedValues['Id']);
			$instance->fill($postedValues);
			$instance->update();
			return Redirect::to('master/consultantservicecategory')->with('savedsuccessmessage','Service Category has been successfully updated');
		}
	}
	public function saveService(){
		$postedValues=Input::all();
		$validation = new ConsultantServiceModel;
		if(!($validation->validate($postedValues))){
		    $errors = $validation->errors();
		    return Redirect::to('master/consultantservice')->withErrors($errors)->withInput();
		}
		if(empty($postedValues["Id"])){
			ConsultantServiceModel::create($postedValues);
			return Redirect::to('master/consultantservice')->with('savedsuccessmessage','Service has been successfully added');
		}else{
			$consultantModel= new ConsultantServiceModel();
			$instance=$consultantModel::find($postedValues['Id']);
			$instance->fill($postedValues);
			$instance->update();
			return Redirect::to('master/consultantservice')->with('savedsuccessmessage','Service has been successfully updated');
		}
	}
	public function checkProposedName(){
		$flagFirmName=true;
		$proposedName=Input::get('NameOfFirm');
		$firmNameCount=ConsultantModel::consultantHardListAll()->where('CmnApplicationRegistrationStatusId','<>',CONST_CMN_APPLICATIONREGISTRATIONSTATUS_REJECTED)->where('NameOfFirm',$proposedName)->count();
		$firmNameCountFinal=ConsultantFinalModel::consultantHardListAll()->where('NameOfFirm',$proposedName)->count();
		if($firmNameCount>0 || $firmNameCountFinal>0){
			$flagFirmName=false;
		}
		return json_encode(array(
    		'valid' => $flagFirmName,
		));
	}
	public function generalInfoRegistration($consultant=null){
		$isRejectedApp=0;
		$serviceByConsultant=0;
		$isRenewalService=0;
		$newGeneralInfoSave=1;
		$redirectUrl=Input::get('redirectUrl');
		$consultantGeneralInfo=array(new ConsultantModel());
		$consultantPartnerDetail=array(new ConsultantHumanResourceModel());
		$view="crps.consultantregistrationgeneralinfo";
		/*if(Route::current()->getUri()=="consultant/editgeneralinfo/{consultantid}"){
			$view="crps.consultanteditgeneralinfo";
		}*/
		if((bool)$consultant!=null && !Input::has('usercdb')){
			if(Input::has('editbyapplicant')){
				if(Input::has('rejectedapplicationreapply')){
					$isRejectedApp=1;
				}
				$view="crps.consultantregistrationgeneralinfo";
			}else{
				$view="crps.consultanteditgeneralinfo";
			}
			$consultantGeneralInfo=ConsultantModel::consultantHardList($consultant)->get(array('Id','ReferenceNo','ApplicationDate','CmnOwnershipTypeId','NameOfFirm','Address','Email','TelephoneNo','MobileNo','FaxNo','CmnCountryId','CmnDzongkhagId','Gewog','Village','CmnRegisteredDzongkhagId','TradeLicenseNo','TPN'));
			$consultantPartnerDetail=ConsultantHumanResourceModel::consultantPartnerHardList($consultant)->get(array('Id','CIDNo','Name','Sex','ShowInCertificate','CmnCountryId','CmnSalutationId','CmnDesignationId'));
		}
		if((bool)$consultant!=null && Input::has('usercdb')){
			$view="crps.consultanteditgeneralinfo";
			$newGeneralInfoSave=0;
			$consultantGeneralInfo=ConsultantFinalModel::consultantHardList($consultant)->get(array('Id','ReferenceNo','ApplicationDate','CmnOwnershipTypeId','NameOfFirm','Address','Email','TelephoneNo','MobileNo','FaxNo','CmnCountryId','CmnDzongkhagId','Gewog','Village','CmnRegisteredDzongkhagId','TradeLicenseNo','TPN'));
			$consultantPartnerDetail=ConsultantHumanResourceFinalModel::consultantPartnerHardList($consultant)->get(array('Id','CIDNo','Name','Sex','ShowInCertificate','CmnCountryId','CmnSalutationId','CmnDesignationId'));
		}
		$applicationReferenceNo=$this->tableTransactionNo('ConsultantModel','ReferenceNo');
		$country=CountryModel::country()->get(array('Id','Name'));
		$dzongkhag=DzongkhagModel::dzongkhag()->get(array('Id','NameEn'));
		$designation=CmnListItemModel::designation()->get(array('Id','Name'));
		$salutation=CmnListItemModel::salutation()->get(array('Id','Name'));
		$ownershipTypes=CmnListItemModel::ownershipType()->get(array('Id','Name','ReferenceNo'));
		return View::make($view)
					->with('isRejectedApp',$isRejectedApp)
					->with('redirectUrl',$redirectUrl)
					->with('isRenewalService',$isRenewalService)
					->with('newGeneralInfoSave',$newGeneralInfoSave)
					->with('isServiceByConsultant','')
					->with('serviceByConsultant',$serviceByConsultant)
					->with('isEdit',$consultant)
					->with('applicationReferenceNo',$applicationReferenceNo)
					->with('consultantGeneralInfo',$consultantGeneralInfo)
					->with('consultantPartnerDetails',$consultantPartnerDetail)
					->with('countries',$country)
					->with('dzongkhags',$dzongkhag)
					->with('designations',$designation)
					->with('salutations',$salutation)
					->with('ownershipTypes',$ownershipTypes);
	}
	public function workClassificationRegistration($consultant=null){
		$serviceByConsultant=0;
		$redirectUrl=Input::get('redirectUrl');
		$consultantId=$consultant;
		$finalEdit = false;
		$view="crps.consultantregistrationworkclassification";
		if(Route::current()->getUri()=="consultant/editworkclassification/{consultantid}"){
			$finalEdit = true;
			$view="crps.consultanteditworkclassification";
		}
		if((bool)$redirectUrl==NULL){
			if(Session::has('ConsultantRegistrationId')){
				$consultantId=Session::get('ConsultantRegistrationId');
			}else{
				return Redirect::to('consultant/generalinforegistration')->withInput();
			}
		}
		$serviceCategories=ConsultantServiceCategoryModel::category()->get(array('Id','Name'));
		if((bool)$consultant==null){
			if(Input::has('rejectedapplicationreapply')){
				$services=DB::select("select distinct T1.Id,T1.Code,T1.Name,T1.CmnConsultantServiceCategoryId,T2.CmnAppliedServiceId from cmnconsultantservicecategory X join cmnconsultantservice T1 on X.Id=T1.CmnConsultantServiceCategoryId left join crpconsultantworkclassification T2 on T1.Id=T2.CmnAppliedServiceId and T2.CrpConsultantId=? order by T1.Code",array($consultantId));
			}else{
				$services=ConsultantServiceModel::service()->select(DB::raw('Id,CmnConsultantServiceCategoryId,Code,Name,NULL as CmnAppliedServiceId'))->get();
			}
		}else{
			if($finalEdit){
				$services=DB::select("select distinct T1.Id,T1.Code,T1.Name,T1.CmnConsultantServiceCategoryId,T2.CmnAppliedServiceId from cmnconsultantservicecategory X join cmnconsultantservice T1 on X.Id=T1.CmnConsultantServiceCategoryId left join crpconsultantworkclassificationfinal T2 on T1.Id=T2.CmnAppliedServiceId and T2.CrpConsultantFinalId=? order by T1.Code",array($consultantId));
			}else{
				$services=DB::select("select distinct T1.Id,T1.Code,T1.Name,T1.CmnConsultantServiceCategoryId,T2.CmnAppliedServiceId from cmnconsultantservicecategory X join cmnconsultantservice T1 on X.Id=T1.CmnConsultantServiceCategoryId left join crpconsultantworkclassification T2 on T1.Id=T2.CmnAppliedServiceId and T2.CrpConsultantId=? order by T1.Code",array($consultantId));
			}
			
		}
		return View::make($view)
					->with('serviceByConsultant',$serviceByConsultant)
					->with('redirectUrl',$redirectUrl)
					->with('consultantId',$consultantId)
					->with('isEdit',$consultant)
					->with('finalEdit',$finalEdit)
					->with('serviceCategories',$serviceCategories)
					->with('services',$services);
	}
	public function humanResourceRegistration($consultant=null){
		$serviceByConsultant=0;
		$newHumanResourceSave=1;
		$editPage='consultant/edithumanresource';
		$humanResourceEditRoute='consultant/applyservicehumanresourceedit';
		if(Session::has('ConsultantRegistrationId')){
			$consultantId=Session::get('ConsultantRegistrationId');
		}else{
			return Redirect::to('consultant/generalinforegistration')->withInput();
		}
		$humanResourceEdit=array(new ConsultantHumanResourceModel());
		$humanResourceEditAttachments=array();
		$humanResourceId=Input::get('humanresourceid');
		if(!empty($humanResourceId) && strlen($humanResourceId)==36){
			$humanResourceEdit=ConsultantHumanResourceModel::consultantHumanResourceHardListSingle($humanResourceId)->get(array('Id','Name','CIDNo','Sex','CmnSalutationId','CmnQualificationId','CmnTradeId','CmnDesignationId','CmnServiceTypeId','JoiningDate','CmnCountryId'));
			$humanResourceEditAttachments=ConsultantHumanResourceAttachmentModel::humanResourceAttachment($humanResourceId)->get(array('Id','DocumentName','DocumentPath'));
		}
		if((bool)$consultant!=null){
			$newHumanResourceSave=0;
		}
		$salutation=CmnListItemModel::salutation()->get(array('Id','Name'));
		$country=CountryModel::country()->get(array('Id','Name'));
		$designation=CmnListItemModel::designation()->get(array('Id','Name'));
		$qualification=CmnListItemModel::qualification()->get(array('Id','Name'));
		$trades=CmnListItemModel::trade()->get(array('Id','Name'));
		$serviceTypes=CmnListItemModel::serviceType()->get(array('Id','Name'));
		$consultantHumanResources=ConsultantHumanResourceModel::consultantHumanResource($consultantId)->get(array('crpconsultanthumanresource.Id','crpconsultanthumanresource.Name','crpconsultanthumanresource.CIDNo','crpconsultanthumanresource.Sex','crpconsultanthumanresource.Name','crpconsultanthumanresource.JoiningDate','T1.Name as Salutation','T2.Name as Qualification','T3.Name as Trade','T4.Name as Designation','T5.Name as Country','T6.Name as ServiceType'));
		$humanResourcesAttachments=ConsultantHumanResourceModel::humanResourceAttachments($consultantId)->get(array('T1.Id','T1.CrpConsultantHumanResourceId','T1.DocumentName','T1.DocumentPath'));
  		return View::make('crps.consultantregistrationhumanresource')
  					->with('serviceByConsultant',$serviceByConsultant)
  					->with('newHumanResourceSave',$newHumanResourceSave)
  					->with('humanResourceEditRoute',$humanResourceEditRoute)
  					->with('isEdit',$consultant)
  					->with('editPage',$editPage)
  					->with('consultantId',$consultantId)
  					->with('countries',$country)
  					->with('salutations',$salutation)
  					->with('qualifications',$qualification)
  					->with('serviceTypes',$serviceTypes)
  					->with('designations',$designation)
					->with('trades',$trades)
					->with('consultantHumanResources',$consultantHumanResources)
					->with('humanResourcesAttachments',$humanResourcesAttachments)
					->with('humanResourceEdit',$humanResourceEdit)
					->with('humanResourceEditAttachments',$humanResourceEditAttachments);

	}
	public function humanResourceRegistrationEdit($consultant=null){
		$afterSaveRedirect=1;
		$serviceByConsultant=0;
		$newHumanResourceSave=0;
		$isEditByCDBUser=Input::get('usercdb');
		$humanResourceEditRoute='consultant/edithumanresource';
		$redirectUrl=Input::get('redirectUrl');
		$editPage='consultant/edithumanresource';
		$humanResourceEdit=array(new ConsultantHumanResourceModel());
		$humanResourceEditAttachments=array();
		$humanResourceId=Input::get('humanresourceid');
		$consultantHumanResources = array();
		$humanResourcesAttachments = array();
		$humanResourceEditFinalAttachments=array();
		if(!empty($humanResourceId) && strlen($humanResourceId)==36){
			$humanResourceEditFinalAttachments=ConsultantHumanResourceAttachmentFinalModel::humanResourceAttachment($humanResourceId)->get(array('Id','DocumentName','DocumentPath'));
			if(!Input::has('usercdb')){
				$humanResourceEdit=ConsultantHumanResourceModel::consultantHumanResourceHardListSingle($humanResourceId)->get(array('Id','Name','CIDNo','Sex','CmnSalutationId','CmnQualificationId','CmnTradeId','CmnDesignationId','JoiningDate', 'CmnCountryId','CmnServiceTypeId'));
				$humanResourceEditAttachments=ConsultantHumanResourceAttachmentModel::humanResourceAttachment($humanResourceId)->get(array('Id','DocumentName','DocumentPath'));
			}
			if(Input::has('usercdb')){
				$humanResourceEdit=ConsultantHumanResourceFinalModel::consultantHumanResourceHardListSingle($humanResourceId)->get(array('Id','Name','CIDNo','Sex','CmnSalutationId','CmnQualificationId','CmnTradeId','CmnDesignationId','JoiningDate', 'CmnCountryId','CmnServiceTypeId'));
				$humanResourceEditFinalAttachments=ConsultantHumanResourceAttachmentFinalModel::humanResourceAttachment($humanResourceId)->get(array('Id','DocumentName','DocumentPath'));
				$humanResourceEditAttachments=ConsultantHumanResourceAttachmentModel::humanResourceAttachment($humanResourceId)->get(array('Id','DocumentName','DocumentPath'));
				if(count($humanResourceEdit) == 0){
					$humanResourceEdit=ConsultantHumanResourceModel::consultantHumanResourceHardListSingle($humanResourceId)->get(array('Id','Name','CIDNo','Sex','CmnSalutationId','CmnQualificationId','CmnTradeId','CmnDesignationId','JoiningDate', 'CmnCountryId','CmnServiceTypeId'));
					$humanResourceEditAttachments=ConsultantHumanResourceAttachmentModel::humanResourceAttachment($humanResourceId)->get(array('Id','DocumentName','DocumentPath'));
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
		if((bool)$consultant!=null && !Input::has('usercdb')){
			$changeModel = false;
			$consultantHumanResources=ConsultantHumanResourceModel::ConsultantHumanResource($consultant)->get(array('crpconsultanthumanresource.Id', DB::raw('coalesce(T4.ReferenceNo,0) as DesignationReference'), 'crpconsultanthumanresource.Name','crpconsultanthumanresource.CIDNo','crpconsultanthumanresource.Sex','crpconsultanthumanresource.JoiningDate','crpconsultanthumanresource.Name','T1.Name as Salutation','T2.Name as Qualification','T3.Name as Trade','T4.Name as Designation','T5.Nationality as Country'));
			$humanResourcesAttachments=ConsultantHumanResourceModel::humanResourceAttachments($consultant)->get(array('T1.Id','T1.CrpConsultantHumanResourceId','T1.DocumentName','T1.DocumentPath'));
		}
		if((bool)$consultant!=null && Input::has('usercdb')){
			$changeModel = true;
			$consultantFinalHumanResources=ConsultantHumanResourceFinalModel::ConsultantHumanResource($consultant)->get(array('crpconsultanthumanresourcefinal.Id',DB::raw('coalesce(T4.ReferenceNo,0) as DesignationReference'),'crpconsultanthumanresourcefinal.Name','crpconsultanthumanresourcefinal.CIDNo','crpconsultanthumanresourcefinal.Sex','crpconsultanthumanresourcefinal.CmnServiceTypeId','crpconsultanthumanresourcefinal.JoiningDate','crpconsultanthumanresourcefinal.Name','T1.Name as Salutation','T2.Name as Qualification','T3.Name as Trade','T4.Name as Designation','T5.Nationality as Country'));
			$humanResourcesFinalAttachments=ConsultantHumanResourceAttachmentFinalModel::singleConsultantHumanResourceAllAttachments($consultant)->get(array('crpconsultanthumanresourceattachmentfinal.DocumentName','crpconsultanthumanresourceattachmentfinal.DocumentPath','crpconsultanthumanresourceattachmentfinal.CrpConsultantHumanResourceFinalId as CrpConsultantHumanResourceId'));
			$consultantInFinalTable = DB::table('crpconsultantfinal')->where('Id',$consultant)->count();
			if($consultantInFinalTable == 0){
				$consultantHumanResources=ConsultantHumanResourceModel::ConsultantHumanResource($consultant)->get(array('crpconsultanthumanresource.Id', DB::raw('coalesce(T4.ReferenceNo,0) as DesignationReference'), 'crpconsultanthumanresource.Name','crpconsultanthumanresource.CIDNo','crpconsultanthumanresource.Sex','crpconsultanthumanresource.JoiningDate','crpconsultanthumanresource.CmnServiceTypeId','crpconsultanthumanresource.Name','T1.Name as Salutation','T2.Name as Qualification','T3.Name as Trade','T4.Name as Designation','T5.Nationality as Country'));
				$humanResourcesAttachments=ConsultantHumanResourceModel::humanResourceAttachments($consultant)->get(array('T1.Id','T1.CrpConsultantHumanResourceId','T1.DocumentName','T1.DocumentPath'));
			}else{
				$consultantHumanResources=ConsultantHumanResourceFinalModel::ConsultantHumanResource($consultant)->get(array('crpconsultanthumanresourcefinal.Id',DB::raw('coalesce(T4.ReferenceNo,0) as DesignationReference'),'crpconsultanthumanresourcefinal.Name','crpconsultanthumanresourcefinal.CIDNo','crpconsultanthumanresourcefinal.Sex','crpconsultanthumanresourcefinal.CmnServiceTypeId','crpconsultanthumanresourcefinal.JoiningDate','crpconsultanthumanresourcefinal.Name','T1.Name as Salutation','T2.Name as Qualification','T3.Name as Trade','T4.Name as Designation','T5.Nationality as Country'));
				$humanResourcesAttachments=ConsultantHumanResourceAttachmentFinalModel::singleConsultantHumanResourceAllAttachments($consultant)->get(array('crpconsultanthumanresourceattachmentfinal.DocumentName','crpconsultanthumanresourceattachmentfinal.DocumentPath','crpconsultanthumanresourceattachmentfinal.CrpConsultantHumanResourceFinalId as CrpConsultantHumanResourceId'));
			}
		}

		return View::make('crps.consultantedithumanresource')
			->with('changeModel',$changeModel)
			->with('serviceTypes',$serviceTypes)
			->with('afterSaveRedirect',$afterSaveRedirect)
			->with('serviceByConsultant',$serviceByConsultant)
			->with('newHumanResourceSave',$newHumanResourceSave)
			->with('humanResourceEditFinalAttachments',$humanResourceEditFinalAttachments)
			->with('isEditByCDBUser',$isEditByCDBUser)
			->with('humanResourceEditRoute',$humanResourceEditRoute)
			->with('redirectUrl',$redirectUrl)
			->with('isEdit',$consultant)
			->with('editPage',$editPage)
			->with('consultantId',$consultant)
			->with('countries',$country)
			->with('salutations',$salutation)
			->with('qualifications',$qualification)
			->with('designations',$designation)
			->with('trades',$trades)
			->with('consultantHumanResources',$consultantHumanResources)
			->with('humanResourcesAttachments',$humanResourcesAttachments)
			->with('humanResourceEdit',$humanResourceEdit)
			->with('humanResourceEditAttachments',$humanResourceEditAttachments);

	}
	public function equipmentRegistration($consultant=null){
		$serviceByConsultant=0;
		$newEquipmentSave=1;
		$editPage='consultant/editequipment';
		if(Session::has('ConsultantRegistrationId')){
			$consultantId=Session::get('ConsultantRegistrationId');
		}else{
			return Redirect::to('consultant/generalinforegistration')->withInput();
		}
		$equipmentEdit=array(new ConsultantHumanResourceModel());
		$equipmentAttachments=array();
		$equipmentId=Input::get('equipmentid');
		if(!empty($equipmentId) && strlen($equipmentId)==36){
			$equipmentEdit=ConsultantEquipmentModel::consultantEquipmentHardListSingle($equipmentId)->get(array('Id','CmnEquipmentId','RegistrationNo','ModelNo','Quantity'));
			$equipmentAttachments=ConsultantEquipmentAttachmentModel::equipmentAttachment($equipmentId)->get(array('Id','DocumentName','DocumentPath'));
		}
		if((bool)$consultant!=null){
			$newEquipmentSave=0;
		}
		$equipments=CmnEquipmentModel::equipment()->whereNotNull('Code')->get(array('Id','Name','Code','IsRegistered','VehicleType'));
		$consultantEquipments=ConsultantEquipmentModel::consultantEquipment($consultantId)->get(array('crpconsultantequipment.Id','crpconsultantequipment.RegistrationNo','crpconsultantequipment.ModelNo','crpconsultantequipment.Quantity','T1.Name'));
		$equipmentsAttachments=ConsultantEquipmentModel::equipmentAttachments($consultantId)->get(array('T1.Id','T1.CrpConsultantEquipmentId','T1.DocumentName','T1.DocumentPath'));
		return View::make('crps.consultantregistrationequipment')
					->with('serviceByConsultant',$serviceByConsultant)
					->with('newEquipmentSave',$newEquipmentSave)
					->with('editPage',$editPage)
					->with('isEdit',$consultant)
					->with('consultantId',$consultantId)
					->with('equipments',$equipments)
					->with('consultantEquipments',$consultantEquipments)
					->with('equipmentsAttachments',$equipmentsAttachments)
					->with('equipmentEdit',$equipmentEdit)
					->with('equipmentAttachments',$equipmentAttachments);
	}
	public function equipmentRegistrationEdit($consultant=null){
		$afterSaveRedirect=1;
		$serviceByConsultant=0;
		$newEquipmentSave=0;
		$isEditByCDBUser=Input::get('usercdb');
		$editPage='consultant/editequipment';
		$redirectUrl=Input::get('redirectUrl');
		$equipmentEditRoute='consultant/editequipment';
		$equipmentEdit=array(new ConsultantHumanResourceModel());
		$equipmentAttachments=array();
		$equipmentId=Input::get('equipmentid');
		if(!empty($equipmentId) && strlen($equipmentId)==36){
			if(!Input::has('usercdb')){
				$equipmentEdit=ConsultantEquipmentModel::consultantEquipmentHardListSingle($equipmentId)->get(array('Id','CmnEquipmentId','RegistrationNo','ModelNo','Quantity'));
				$equipmentAttachments=ConsultantEquipmentAttachmentModel::equipmentAttachment($equipmentId)->get(array('Id','DocumentName','DocumentPath'));
			}
			if(Input::has('usercdb')){
				$equipmentEdit=ConsultantEquipmentFinalModel::consultantEquipmentHardListSingle($equipmentId)->get(array('Id','CmnEquipmentId','RegistrationNo','ModelNo','Quantity'));
				$equipmentAttachments=ConsultantEquipmentAttachmentFinalModel::equipmentAttachment($equipmentId)->get(array('Id','DocumentName','DocumentPath'));
			}
		}
		$changeModel = false;
		$equipments=CmnEquipmentModel::equipment()->whereNotNull('Code')->get(array('Id','Name','Code','IsRegistered','VehicleType'));
		if((bool)$consultant!=null && !Input::has('usercdb')){
			$changeModel = false;
			$consultantEquipments=ConsultantEquipmentModel::consultantEquipment($consultant)->get(array('crpconsultantequipment.Id','crpconsultantequipment.RegistrationNo','crpconsultantequipment.ModelNo','crpconsultantequipment.Quantity','T1.Name'));
			$equipmentsAttachments=ConsultantEquipmentModel::equipmentAttachments($consultant)->get(array('T1.Id','T1.CrpConsultantEquipmentId','T1.DocumentName','T1.DocumentPath'));
		}
		if((bool)$consultant!=null && Input::has('usercdb')){
			$changeModel = true;
			$consultantEquipments=ConsultantEquipmentFinalModel::consultantEquipment($consultant)->get(array('crpconsultantequipmentfinal.Id','crpconsultantequipmentfinal.RegistrationNo','crpconsultantequipmentfinal.ModelNo','crpconsultantequipmentfinal.Quantity','T1.Name'));
			$equipmentsAttachments=ConsultantEquipmentAttachmentFinalModel::singleConsultantEquipmentAllAttachments($consultant)->get(array('crpconsultantequipmentattachmentfinal.DocumentName','crpconsultantequipmentattachmentfinal.DocumentPath','crpconsultantequipmentattachmentfinal.CrpConsultantEquipmentFinalId as CrpConsultantEquipmentId'));
			if(count($consultantEquipments) == 0){
				$consultantEquipments=ConsultantEquipmentModel::consultantEquipment($consultant)->get(array('crpconsultantequipment.Id','crpconsultantequipment.RegistrationNo','crpconsultantequipment.ModelNo','crpconsultantequipment.Quantity','T1.Name'));
				$equipmentsAttachments=ConsultantEquipmentModel::equipmentAttachments($consultant)->get(array('T1.Id','T1.CrpConsultantEquipmentId','T1.DocumentName','T1.DocumentPath'));
			}
		}
		return View::make('crps.consultanteditequipment')
					->with('changeModel',$changeModel)
					->with('afterSaveRedirect',$afterSaveRedirect)
					->with('serviceByConsultant',$serviceByConsultant)
					->with('newEquipmentSave',$newEquipmentSave)
					->with('isEditByCDBUser',$isEditByCDBUser)
					->with('equipmentEditRoute',$equipmentEditRoute)
					->with('editPage',$editPage)
					->with('redirectUrl',$redirectUrl)
					->with('isEdit',$consultant)
					->with('consultantId',$consultant)
					->with('equipments',$equipments)
					->with('consultantEquipments',$consultantEquipments)
					->with('equipmentsAttachments',$equipmentsAttachments)
					->with('equipmentEdit',$equipmentEdit)
					->with('equipmentAttachments',$equipmentAttachments);
	}
	public function saveGeneralInfo(){
		$postedValues=Input::except('ChangeOfLocationOwner','attachments','OtherServices','DocumentName','DocumentNameOwnerShipChange','attachmentsownershipchange','attachmentsfirmnamechange','DocumentNameFirmNameChange');
		$newGeneralInfoSave=Input::get('NewGeneralInfoSave');
		$isServiceByConsultant=Input::get('ServiceByConsultant');
		$validation = new ConsultantModel;
		if(!($validation->validate($postedValues))){
		    $errors = $validation->errors();
		    if((int)$isServiceByConsultant!=1){
		    	return Redirect::to('consultant/generalinforegistration')->withInput()->withErrors($errors);
			}else{
				return Redirect::to('consultant/applyservicegeneralinfo/'.Input::get('CrpConsultantId'))->withInput()->withErrors($errors);
			}
		}
		/*To check if already applied */
		$previousApplications = DB::table('crpconsultant')->whereNotNull('CrpConsultantId')->where('CrpConsultantId',Input::get('CrpConsultantId'))->where(DB::raw('coalesce(RegistrationStatus,0)'),1)->whereNotIn('CmnApplicationRegistrationStatusId',array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_REJECTED))->count();

		if($previousApplications>0){
			$previousApplicationDetails = DB::table('crpconsultant')->where('CrpConsultantId',Input::get('CrpConsultantId'))->where(DB::raw('coalesce(RegistrationStatus,0)'),1)->whereNotIn('CmnApplicationRegistrationStatusId',array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_REJECTED))->select(DB::raw("GROUP_CONCAT(CONCAT('Application No. ',ReferenceNo,' dt. ',ApplicationDate) SEPARATOR '<br/>') as Applications"))->pluck('Applications');
			return Redirect::to('consultant/applyotherservices')->with("customerrormessage","<h4><strong> MESSAGE! You have following pending application(s) with CDB: </strong></h4><ol>$previousApplicationDetails</ol><strong>Please wait for us to process your previous application before submitting a new one!</strong> ");
		}
		/* END */
		if(Input::hasFile('attachments')){
			$count = 0;
			$multiAttachments=array();
			foreach(Input::file('attachments') as $attachment){
				$documentName = Input::get("DocumentName");
				$attachmentType=$attachment->getMimeType();
				$attachmentFileName=$attachment->getClientOriginalName();
				$attachmentName=str_random(6) . '_' . $attachment->getClientOriginalName();
				$destination=public_path().'/uploads/consultants';
				$destinationDB='uploads/consultants/'.$attachmentName;
				$multiAttachments1["DocumentName"]=isset($documentName[$count])?$documentName[$count]:'Document';

				//CHECK IF IMAGE
				if(strpos($attachment->getClientMimeType(),'image/')>-1){
					$img = Image::make($attachment)->encode('jpg');
					$destinationDB = "uploads/consultants/".str_random(15) . '_min_' .".jpg";
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
		if(Input::hasFile('attachmentsownershipchange')){
			$countownershipchange = 0;
			$multiAttachmentsownershipchange=array();
			foreach(Input::file('attachmentsownershipchange') as $attachmentownership){
				$documentName = Input::get("DocumentNameOwnerShipChange");
				$attachmentType=$attachmentownership->getMimeType();
				$attachmentFileName=$attachmentownership->getClientOriginalName();
				$attachmentName=str_random(6) . '_' . $attachmentownership->getClientOriginalName();
				$destination=public_path().'/uploads/consultants';
				$destinationDB='uploads/consultants/'.$attachmentName;
				$multiAttachmentsownershipchange1["DocumentName"]=isset($documentName[$countownershipchange])?$documentName[$countownershipchange]:'Ownership Change Attachment';

				//CHECK IF IMAGE
				if(strpos($attachmentownership->getClientMimeType(),'image/')>-1){
					$img = Image::make($attachmentownership)->encode('jpg');
					$destinationDB = "uploads/consultants/".str_random(15) . '_min_' .".jpg";
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
		if(Input::hasFile('attachmentsfirmnamechange')){
			$countfirmnamechange = 0;
			$multiAttachmentsFirmNameChange=array();
			foreach(Input::file('attachmentsfirmnamechange') as $attachmentfirmnamechange){
				$documentName = Input::get("DocumentNameFirmNameChange");
				$attachmentType=$attachmentfirmnamechange->getMimeType();
				$attachmentFileName=$attachmentfirmnamechange->getClientOriginalName();
				$attachmentName=str_random(6) . '_' . $attachmentfirmnamechange->getClientOriginalName();
				$destination=public_path().'/uploads/consultants';
				$destinationDB='uploads/consultants/'.$attachmentName;
				$multiAttachmentsownershipchange1["DocumentName"]=isset($documentName[$countfirmnamechange])?$documentName[$countfirmnamechange]:"Firm Name Change Document";

				//CHECK IF IMAGE
				if(strpos($attachmentfirmnamechange->getClientMimeType(),'image/')>-1){
					$img = Image::make($attachmentfirmnamechange)->encode('jpg');
					$destinationDB = "uploads/consultants/".str_random(15) . '_min_' .".jpg";
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
		if(empty($postedValues["Id"])){
			$uuid=DB::select("select uuid() as Id");
	        $generatedId=$uuid[0]->Id;
	        $postedValues["Id"]=$generatedId;
	        $postedValues["ReferenceNo"]=$this->tableTransactionNo('ConsultantModel','ReferenceNo');
	        DB::beginTransaction();
	        try{
				$postedValues['ApplicationDate'] = date('Y-m-d');
				ConsultantModel::create($postedValues);
				if(Input::hasFile('attachments')){
					foreach($multiAttachments as $k=>$v){
						if(isset($isEditByCdb) && (int)$isEditByCdb==1 && $newGeneralInfoSave!=null  && (int)$newGeneralInfoSave==0){
							$multiAttachments[$k]["CrpConsultantFinalId"]=$generatedId;
							$saveUploads=new ConsultantAttachmentFinalModel($multiAttachments[$k]);
						}else{
							$multiAttachments[$k]["CrpConsultantId"]=$generatedId;
							$saveUploads=new ConsultantAttachmentModel($multiAttachments[$k]);
						}
						$saveUploads->save();
					}
				}
				if(Input::hasFile('attachmentsownershipchange')){
					foreach($multiAttachmentsownershipchange as $k=>$v){
						if(isset($isEditByCdb) && (int)$isEditByCdb==1 && $newGeneralInfoSave!=null  && (int)$newGeneralInfoSave==0){
							$multiAttachmentsownershipchange[$k]["CrpConsultantFinalId"]=$generatedId;
							$saveUploads=new ConsultantAttachmentFinalModel($multiAttachmentsownershipchange[$k]);
						}else{
							$multiAttachmentsownershipchange[$k]["CrpConsultantId"]=$generatedId;
							$saveUploads=new ConsultantAttachmentModel($multiAttachmentsownershipchange[$k]);
						}
						$saveUploads->save();
					}
				}
				if(Input::hasFile('attachmentsfirmnamechange')){
					foreach($multiAttachmentsFirmNameChange as $k=>$v){
						if(isset($isEditByCdb) && (int)$isEditByCdb==1 && $newGeneralInfoSave!=null  && (int)$newGeneralInfoSave==0){
							$multiAttachmentsFirmNameChange[$k]["CrpConsultantFinalId"]=$generatedId;
							$saveUploads=new ConsultantAttachmentFinalModel($multiAttachmentsFirmNameChange[$k]);
						}else{
							$multiAttachmentsFirmNameChange[$k]["CrpConsultantId"]=$generatedId;
							$saveUploads=new ConsultantAttachmentModel($multiAttachmentsFirmNameChange[$k]);
						}
						$saveUploads->save();
					}
				}
				if(Input::has('RenewalService') && (int)$isServiceByConsultant==1){
					$lateRenewalExpiryDate=ConsultantFinalModel::consultantHardList($postedValues['CrpConsultantId'])->pluck('RegistrationExpiryDate');
				    $lateRenewalExpiryDate=strtotime($lateRenewalExpiryDate);
				    $currentDate=strtotime(date('Y-m-d'));
					$appliedServiceRenewal = new ConsultantAppliedServiceModel;
	        		$appliedServiceRenewal->CrpConsultantId=$generatedId;
				    $appliedServiceRenewal->CmnServiceTypeId = Input::get('RenewalService');
				    $appliedServiceRenewal->save();
				     if($currentDate>$lateRenewalExpiryDate){
				    	$appliedServiceRenewalLateFee = new ConsultantAppliedServiceModel;
				    	$appliedServiceRenewalLateFee->CrpConsultantId=$generatedId;
				    	$appliedServiceRenewalLateFee->CmnServiceTypeId = CONST_SERVICETYPE_LATEFEE;
				    	$appliedServiceRenewalLateFee->save();
				    }
				}
	        	if(Input::has('ChangeOfLocationOwner') && (int)$isServiceByConsultant==1){
		        	$changeOfOwnerLocation=Input::get('ChangeOfLocationOwner');
					for($idx = 0; $idx < count($changeOfOwnerLocation); $idx++){
						$appliedService = new ConsultantAppliedServiceModel;
					    $appliedService->CrpConsultantId=$generatedId;
					    $appliedService->CmnServiceTypeId = $changeOfOwnerLocation[$idx];
					    $appliedService->save();
					}
				}
				if(Input::has('OtherServices') && (int)$isServiceByConsultant==1){
					$otherServices=Input::get('OtherServices');
					for($idx = 0; $idx < count($otherServices); $idx++){
						$appliedService = new ConsultantAppliedServiceModel;
						$appliedService->CrpConsultantId=$generatedId;
						$appliedService->CmnServiceTypeId = $otherServices[$idx];
						$appliedService->save();
					}
				}
				foreach($postedValues as $key=>$value){
					if(gettype($value)=='array'){
						foreach($value as $key1=>$value1){
							$value1['CrpConsultantId']=$generatedId;
							$childTable= new ConsultantHumanResourceModel($value1);
							$a=$childTable->save();
						}
					}
				}

			}catch(Exception $e){
				DB::rollback();
	        	throw $e;
			}
			DB::commit();

			if((int)$isServiceByConsultant==1){
//				if(Input::has('PostBackUrl')){
//					$postBackUrl = Input::get('PostBackUrl');
//					if(!empty($postBackUrl)){
//						return Redirect::to($postBackUrl.'/'.Input::get('CrpConsultantId'));
//					}
//				}
				$servicesAppliedByConsultant = DB::table('crpconsultantappliedservice')->where('CrpConsultantId',$generatedId)->lists('CmnServiceTypeId');
				if(in_array(CONST_SERVICETYPE_CHANGEOFCATEGORYCLASSIFICATION,$servicesAppliedByConsultant)):
					return Redirect::to('consultant/applyserviceworkclassification/'.$generatedId);
				endif;
				if(in_array(CONST_SERVICETYPE_RENEWAL,$servicesAppliedByConsultant)):
					return Redirect::to('consultant/applyserviceworkclassification/'.$generatedId);
				endif;
				if(in_array(CONST_SERVICETYPE_UPDATEHUMANRESOURCE,$servicesAppliedByConsultant)):
					$isEditByCdb=Input::get('EditByCdb');
					$redirectTo=Input::get('PostBackUrl');
					if(isset($isEditByCdb) && (int)$isEditByCdb==1){
						return Redirect::to('consultant/applyservicehumanresource'.'/'.$generatedId.'?redirectUrl='.$redirectTo)->with('savedsuccessmessage','Work Classification has been successfully updated.');
					}
				endif;
				if(in_array(CONST_SERVICETYPE_UPDATEEQUIPMENT,$servicesAppliedByConsultant)):
					return Redirect::to('consultant/applyserviceequipment/'.$generatedId);
				endif;
				return Redirect::to('consultant/applyserviceconfirmation/'.$generatedId);
//				return Redirect::to('consultant/applyserviceworkclassification/'.$generatedId);
			}else{
				Session::put('ConsultantRegistrationId',$generatedId);
				return Redirect::to('consultant/workclassificationregistration');
			}
		}else{
			$isEditByCdb=Input::get('EditByCdb');
			$redirectTo=Input::get('PostBackUrl');
			$isRejectedApp=Input::get('ApplicationRejectedReapply');
			DB::beginTransaction();
	        try{
	        	if(isset($isEditByCdb) && (int)$isEditByCdb==1 && $newGeneralInfoSave!=null && (int)$newGeneralInfoSave==0){
					$instance=ConsultantFinalModel::find($postedValues['Id']);
				}else{
					$instance=ConsultantModel::find($postedValues['Id']);
				}
				$instance->fill($postedValues);
				$instance->update();
				if(Input::hasFile('attachments')){
					foreach($multiAttachments as $k=>$v){
						if(isset($isEditByCdb) && (int)$isEditByCdb==1 && $newGeneralInfoSave!=null  && (int)$newGeneralInfoSave==0){
							$multiAttachments[$k]["CrpConsultantFinalId"]=$postedValues['Id'];
							$saveUploads=new ConsultantAttachmentFinalModel($multiAttachments[$k]);
						}else{
							$multiAttachments[$k]["CrpConsultantId"]=$postedValues['Id'];
							$saveUploads=new ConsultantAttachmentModel($multiAttachments[$k]);
						}
						$saveUploads->save();
					}
				}
				if(Input::hasFile('attachmentsownershipchange')){
					foreach($multiAttachmentsownershipchange as $k=>$v){
						if(isset($isEditByCdb) && (int)$isEditByCdb==1 && $newGeneralInfoSave!=null  && (int)$newGeneralInfoSave==0){
							$multiAttachmentsownershipchange[$k]["CrpConsultantFinalId"]=$postedValues['Id'];
							$saveUploads=new ConsultantAttachmentFinalModel($multiAttachmentsownershipchange[$k]);
						}else{
							$multiAttachmentsownershipchange[$k]["CrpConsultantId"]=$postedValues['Id'];
							$saveUploads=new ConsultantAttachmentModel($multiAttachmentsownershipchange[$k]);
						}
						$saveUploads->save();
					}
				}
				if(Input::hasFile('attachmentsfirmnamechange')){
					foreach($multiAttachmentsFirmNameChange as $k=>$v){
						if(isset($isEditByCdb) && (int)$isEditByCdb==1 && $newGeneralInfoSave!=null  && (int)$newGeneralInfoSave==0){
							$multiAttachmentsFirmNameChange[$k]["CrpConsultantFinalId"]=$postedValues['Id'];
							$saveUploads=new ConsultantAttachmentFinalModel($multiAttachmentsFirmNameChange[$k]);
						}else{
							$multiAttachmentsFirmNameChange[$k]["CrpConsultantId"]=$postedValues['Id'];
							$saveUploads=new ConsultantAttachmentModel($multiAttachmentsFirmNameChange[$k]);
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
									$value1['CrpConsultantFinalId']=$postedValues['Id'];
									$childTable= new ConsultantHumanResourceFinalModel($value1);
								}else{
									$value1['CrpConsultantId']=$postedValues['Id'];
									$childTable= new ConsultantHumanResourceModel($value1);
								}
								$a=$childTable->save();

							}else{
								if(isset($isEditByCdb) && (int)$isEditByCdb==1 && $newGeneralInfoSave!=null && (int)$newGeneralInfoSave==0){
									$childTable1=ConsultantHumanResourceFinalModel::find($value1['Id']);
								}else{
									$childTable1=ConsultantHumanResourceModel::find($value1['Id']);
								}
								$childTable1->fill($value1);
								$childTable1->update();
							}
						}
					}
				}
				DB::commit();
				if(isset($isRejectedApp) && (int)$isRejectedApp==1){
					Session::put('ConsultantRegistrationId',$postedValues["Id"]);
					return Redirect::to('consultant/workclassificationregistration?rejectedapplicationreapply=true');
				}
				if(isset($isEditByCdb) && (int)$isEditByCdb==1){
					return Redirect::to($redirectTo.'/'.$postedValues["Id"])->with('savedsuccessmessage','General Information has been successfully updated.');
				}
				return Redirect::to('consultant/confirmregistration')->with('savedsuccessmessage','General Information has been successfully updated.');
			}catch(Exception $e){
				DB::rollback();
	        	throw $e;
			}
		}
	}
	public function saveWorkClassification(){
		$postedValues=Input::except('attachments','DocumentName');
		$finalEdit = false;
		if(Input::has('FinalEdit') && Input::get('FinalEdit') == 1){
			$finalEdit = true;
		}
		if(!isset($postedValues['ConsultantWorkClassificationModel'])){
			return Redirect::back()->with('customerrormessage','Please select at least one category and service');
		}
		$isServiceByConsultant=Input::get('ServiceByConsultant');
		$isEdit=Input::get('IsEdit');
		$ConsultantId=Input::get('CrpConsultantId');
		DB::beginTransaction();
		try{
			if($finalEdit){
				ConsultantWorkClassificationFinalModel::where('CrpConsultantFinalId',$postedValues['CrpConsultantId'])->delete();
			}else{
				ConsultantWorkClassificationModel::where('CrpConsultantId',$postedValues['CrpConsultantId'])->delete();	
			}
			
			if(Input::has('ChangeInCategoryClassificationService') && (int)$isServiceByConsultant==1){
				$appliedService = new ConsultantAppliedServiceModel;
			    $appliedService->CrpConsultantId=Input::get('CrpApplicationConsultantId');
			    $appliedService->CmnServiceTypeId = Input::get('ChangeInCategoryClassificationService');
			    $appliedService->save();
			}
			if(Input::hasFile('attachments')){
				$count = 0;
				$multiAttachments=array();
				foreach(Input::file('attachments') as $attachment){
					$documentName = Input::get("DocumentName");
					$attachmentType=$attachment->getMimeType();
					$attachmentFileName=$attachment->getClientOriginalName();
					$attachmentName=str_random(6) . '_' . $attachment->getClientOriginalName();
					$destination=public_path().'/uploads/consultants';
					$destinationDB='uploads/consultants/'.$attachmentName;
					$multiAttachments1["DocumentName"]=isset($documentName[$count])?$documentName[$count]:'Document';

					//CHECK IF IMAGE
					if(strpos($attachment->getClientMimeType(),'image/')>-1){
						$img = Image::make($attachment)->encode('jpg');
						$destinationDB = "uploads/consultants/".str_random(15) . '_min_' .".jpg";
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
					if(isset($isEditByCdb) && (int)$isEditByCdb==1 && (int)$newGeneralInfoSave==0){
						$multiAttachments[$k]["CrpConsultantFinalId"]=Input::get('CrpApplicationConsultantId');
						$saveUploads=new ConsultantAttachmentFinalModel($multiAttachments[$k]);
					}else{
						$multiAttachments[$k]["CrpConsultantId"]=Input::get('CrpApplicationConsultantId');
						$saveUploads=new ConsultantAttachmentModel($multiAttachments[$k]);
					}
					$saveUploads->save();
				}
			}
			foreach($postedValues as $key=>$value){
				if(gettype($value)=='array'){
					foreach($value as $key1=>$value1){
						if(isset($value1['CmnAppliedServiceId'])){
							if($finalEdit){
								$value1['CmnVerifiedServiceId'] = $value1['CmnAppliedServiceId'];
								$value1['CmnApprovedServiceId'] = $value1['CmnAppliedServiceId'];
								$value1['CrpConsultantFinalId'] = $value1['CrpConsultantId'];
								unset($value1['CrpConsultantId']);
								$value1['Id'] = $this->UUID();
								$childTable= new ConsultantWorkClassificationFinalModel($value1);
								$a=$childTable->save();
							}else{
								$childTable= new ConsultantWorkClassificationModel($value1);
								$a=$childTable->save();	
							}
						}
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
				if($redirectTo!='consultant/applyserviceequipment' && $redirectTo!='consultant/applyservicehumanresource')
					return Redirect::to($redirectTo.'/'.$postedValues["CrpConsultantId"].'?redirectUrl='.$redirectTo)->with('savedsuccessmessage','Work Classification has been successfully updated.');
//			return Redirect::to($redirectTo.'/'.$postedValues["CrpConsultantId"])->with('savedsuccessmessage','Work Classification has been successfully updated.');
			$servicesAppliedByConsultant = DB::table('crpconsultantappliedservice')->where('CrpConsultantId',$postedValues["CrpConsultantId"])->lists('CmnServiceTypeId');
			if(in_array(CONST_SERVICETYPE_UPDATEHUMANRESOURCE,$servicesAppliedByConsultant)):
				$isEditByCdb=Input::get('EditByCdb');
				$redirectTo=Input::get('PostBackUrl');
				if(isset($isEditByCdb) && (int)$isEditByCdb==1){
					return Redirect::to('consultant/applyservicehumanresource'.'/'.$postedValues["CrpConsultantId"].'?redirectUrl='.$redirectTo)->with('savedsuccessmessage','Work Classification has been successfully updated.');
				}
			endif;
			if(in_array(CONST_SERVICETYPE_UPDATEEQUIPMENT,$servicesAppliedByConsultant)):
				return Redirect::to('consultant/applyserviceequipment/'.$postedValues["CrpConsultantId"]);
			endif;
			return Redirect::to('consultant/applyserviceconfirmation/'.$postedValues["CrpConsultantId"]);
		}
		if((bool)$isEdit==null){
			return Redirect::to('consultant/humanresourceregistration');
		}else{
			return Redirect::to('consultant/confirmregistration')->with('savedsuccessmessage','Work Classification has been successfully updated.');
		}
	}
	public function saveHumanResource(){
		$save=true;
		$postedValues=Input::all();
		if(isset($postedValues['JoiningDate']))
			$postedValues['JoiningDate'] = $this->convertDate($postedValues['JoiningDate']);
		$hasCDBEdit=Input::get('HasCDBEdit');
		$consultantId=Input::get('CrpConsultantId');
		$applicationDate = DB::table('crpconsultant')->where('Id',$consultantId)->pluck('ApplicationDate');
		if(!empty($postedValues['Id']))
			DB::table('crpconsultanthumanresourceattachment')->where('CrpConsultantHumanResourceId',$postedValues['Id'])->where('CreatedOn','<',$applicationDate)->delete();
		$isServiceByConsultant=Input::get('ServiceByConsultant');
		$newHumanResourceSave=Input::get('NewHumanResourceSave');
		$isEditByCdb=Input::get('EditByCdb');
		$redirectTo=Input::get('PostBackUrl');
		$multiAttachments=array();
		$uuid=DB::select("select uuid() as Id");
		$generatedId=$uuid[0]->Id;
		$validation = new ConsultantHumanResourceModel;
		$redirectToEdit = false;
		if(!($validation->validate($postedValues))){
			$errors = $validation->errors();
			if(empty($postedValues["Id"])){
				return Redirect::to('consultant/humanresourceregistration')->withInput()->withErrors($errors);
			}else{
				return Redirect::to('consultant/humanresourceregistration/'.$postedValues['CrpConsultantId'])->withInput()->withErrors($errors);
			}
		}
		DB::beginTransaction();
		try{
			if(empty($postedValues["Id"])){
				$postedValues["Id"]=$generatedId;
				if(isset($isEditByCdb) && (int)$isEditByCdb==1 && $newHumanResourceSave!=null && (int)$newHumanResourceSave==0){
					$postedValues["CrpConsultantFinalId"]=$consultantId;
					$instance = ConsultantFinalModel::find($consultantId);
					if(!(bool)$instance){
						ConsultantHumanResourceModel::create($postedValues);
					}else{
						ConsultantHumanResourceFinalModel::create($postedValues);
					}

				}else{
					ConsultantHumanResourceModel::create($postedValues);
				}
				$appliedServiceCount=ConsultantAppliedServiceModel::where('CrpConsultantId',$postedValues['CrpConsultantId'])->where('CmnServiceTypeId',CONST_SERVICETYPE_UPDATEHUMANRESOURCE)->count();
				if($appliedServiceCount==0){
					if(Input::has('UpdateHumanResourceService') && (int)$isServiceByConsultant==1){
						$appliedService = new ConsultantAppliedServiceModel;
						$appliedService->CrpConsultantId=$consultantId;
						$appliedService->CmnServiceTypeId = Input::get('UpdateHumanResourceService');
						$appliedService->save();
					}
				}
			}else{
				$save=false;
				if(isset($isEditByCdb) && (int)$isEditByCdb==1 && $newHumanResourceSave!=null && (int)$newHumanResourceSave==0){
					$instance=ConsultantHumanResourceFinalModel::find($postedValues['Id']);
					if(!(bool)$instance){
						$redirectToEdit = true;
						$instance=ConsultantHumanResourceModel::find($postedValues['Id']);
					}
				}else{
					$instance=ConsultantHumanResourceModel::find($postedValues['Id']);
				}
				if($instance){
					$instance->fill($postedValues);
					$instance->update();
				}else{
					ConsultantHumanResourceModel::create($postedValues);
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
							$destination=public_path().'/uploads/consultants';
							$destinationDB='uploads/consultants/'.$attachmentName;
							$multiAttachments1["DocumentName"]=isset($documentName[$count])?$documentName[$count]:'Consultant Document';

							//CHECK IF IMAGE
							if(strpos($attachment->getClientMimeType(),'image/')>-1){
								$img = Image::make($attachment)->encode('jpg');
								$destinationDB = "uploads/consultants/".str_random(15) . '_min_' .".jpg";
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
							$multiAttachments[$k]["CrpConsultantHumanResourceFinalId"]=$generatedId;
						}else{
							$multiAttachments[$k]["CrpConsultantHumanResourceId"]=$generatedId;
						}
					}else{
						if(isset($isEditByCdb) && (int)$isEditByCdb==1 && $newHumanResourceSave!=null  && (int)$newHumanResourceSave==0){
							$instance = ConsultantFinalModel::find($consultantId);
							if(!(bool)$instance){
								$multiAttachments[$k]["CrpConsultantHumanResourceId"]=$postedValues['Id'];
							}else{
								$multiAttachments[$k]["CrpConsultantHumanResourceFinalId"]=$postedValues['Id'];
							}
						}else{
							$multiAttachments[$k]["CrpConsultantHumanResourceId"]=$postedValues['Id'];
						}
					}

					//END
					if(isset($isEditByCdb) && (int)$isEditByCdb==1 && $newHumanResourceSave!=null && (int)$newHumanResourceSave==0){
						$instance = ConsultantFinalModel::find($consultantId);
						if(!(bool)$instance){
							$saveUploads=new ConsultantHumanResourceAttachmentModel($multiAttachments[$k]);
						}else{
							$saveUploads=new ConsultantHumanResourceAttachmentFinalModel($multiAttachments[$k]);
						}

					}else{

						$saveUploads=new ConsultantHumanResourceAttachmentModel($multiAttachments[$k]);
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
					return Redirect::to($humanResourceEditPage.'/'.$postedValues["CrpConsultantId"].'?redirectUrl='.$redirectTo)->with('savedsuccessmessage','Human Resource has been successfully updated.');
				}
				return Redirect::to($humanResourceEditPage.'/'.$postedValues["CrpConsultantId"].'?redirectUrl='.$redirectTo.'&usercdb=edit')->with('savedsuccessmessage','Human Resource has been successfully updated.');
			}else{
				return Redirect::to($humanResourceEditPage.'/'.$postedValues["CrpConsultantId"].'?redirectUrl='.$redirectTo)->with('savedsuccessmessage','Human Resource has been successfully updated.');
			}
		}
		if($save){
			return Redirect::to('consultant/humanresourceregistration');
		}else{
			return Redirect::to('consultant/humanresourceregistration/'.$postedValues['CrpConsultantId'])->with('savedsuccessmessage','Human Resource has been successfully updated.');;
		}
	}
	public function saveEquipment(){
		$save=true;
		$postedValues=Input::all();
		$hasCDBEdit=Input::get('HasCDBEdit');
		$consultantId=Input::get('CrpConsultantId');
		$isServiceByConsultant=Input::get('ServiceByConsultant');
		$newEquipmentSave=Input::get('NewEquipmentSave');
		$isEditByCdb=Input::get('EditByCdb');
		$redirectTo=Input::get('PostBackUrl');
		$multiAttachments=array();
		$uuid=DB::select("select uuid() as Id");
        $generatedId=$uuid[0]->Id;
		$validation = new ConsultantEquipmentModel;
		if(!($validation->validate($postedValues))){
		    $errors = $validation->errors();
		    if(empty($postedValues["Id"])){
		    	return Redirect::to('consultant/equipmentregistration')->withInput()->withErrors($errors);
			}else{
				return Redirect::to('consultant/equipmentregistration/'.$postedValues['CrpConsultantId'])->withInput()->withErrors($errors);
			}
		}
		DB::beginTransaction();
		try{
			if(empty($postedValues["Id"])){
				$postedValues["Id"]=$generatedId;
				if(isset($isEditByCdb) && (int)$isEditByCdb==1 && $newEquipmentSave!=null && (int)$newEquipmentSave==0){
					$postedValues["CrpConsultantFinalId"]=$consultantId;
//					ConsultantEquipmentFinalModel::create($postedValues);
					$instance = ConsultantFinalModel::find($postedValues["Id"]);
					if(!(bool)$instance){
						ConsultantEquipmentModel::create($postedValues);
					}else{
						ConsultantEquipmentFinalModel::create($postedValues);
					}
				}else{
					ConsultantEquipmentModel::create($postedValues);
				}
				$appliedServiceCount=ConsultantAppliedServiceModel::where('CrpConsultantId',$postedValues['CrpConsultantId'])->where('CmnServiceTypeId',CONST_SERVICETYPE_UPDATEEQUIPMENT)->count();
				if($appliedServiceCount==0){
					if(Input::has('UpdateEquipmentService') && (int)$isServiceByConsultant==1){
						$appliedService = new ConsultantAppliedServiceModel;
					    $appliedService->CrpConsultantId=$consultantId;
					    $appliedService->CmnServiceTypeId = Input::get('UpdateEquipmentService');
					    $appliedService->save();
					}
				}	
			}else{
				$save=false;
				if(isset($isEditByCdb) && (int)$isEditByCdb==1 && $newEquipmentSave!=null && (int)$newEquipmentSave==0){
					$instance=ConsultantEquipmentFinalModel::find($postedValues['Id']);
					if(!(bool)$instance){
						$instance=ConsultantEquipmentModel::find($postedValues['Id']);
					}
				}else{
					$instance=ConsultantEquipmentModel::find($postedValues['Id']);
					if(!(bool)$instance){
						$instance=ConsultantEquipmentFinalModel::find($postedValues['Id']);
					}
				}
				if($instance){
					$instance->fill($postedValues);
					$instance->update();
				}else{
					ConsultantEquipmentModel::create($postedValues);
				}
			}
			if(Input::hasFile('attachments')){
				$count=0;
				foreach(Input::file('attachments') as $attachment){
					$documentName = Input::get("DocumentName");
					$attachmentType=$attachment->getMimeType();
					$attachmentFileName=$attachment->getClientOriginalName();
					$attachmentName=str_random(6) . '_' . $attachment->getClientOriginalName();
					$destination=public_path().'/uploads/consultants';
					$destinationDB='uploads/consultants/'.$attachmentName;
					$multiAttachments1["DocumentName"]=isset($documentName[$count])?$documentName[$count]:"Document";

					//CHECK IF IMAGE
					if(strpos($attachment->getClientMimeType(),'image/')>-1){
						$img = Image::make($attachment)->encode('jpg');
						$destinationDB = "uploads/consultants".str_random(15) . '_min_' .".jpg";
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
						if(isset($isEditByCdb) && (int)$isEditByCdb==1 && $newEquipmentSave!=null && (int)$newEquipmentSave==0){
							$multiAttachments[$k]["CrpConsultantEquipmentFinalId"]=$generatedId;
						}else{
							$multiAttachments[$k]["CrpConsultantEquipmentId"]=$generatedId;
						}
					}else{
						if(isset($isEditByCdb) && (int)$isEditByCdb==1 && $newEquipmentSave!=null && (int)$newEquipmentSave==0){
							$instance = ConsultantFinalModel::find($postedValues['Id']);
							if(!(bool)$instance){
								$multiAttachments[$k]["CrpConsultantEquipmentId"]=$postedValues['Id'];
							}else{
								$multiAttachments[$k]["CrpConsultantEquipmentFinalId"]=$postedValues['Id'];
							}
						}else{
							$multiAttachments[$k]["CrpConsultantEquipmentId"]=$postedValues['Id'];
						}
					}
					if(isset($isEditByCdb) && (int)$isEditByCdb==1 && $newEquipmentSave!=null && (int)$newEquipmentSave==0){
						$instance = ConsultantFinalModel::find($postedValues['Id']);
						if(!(bool)$instance){
							$saveUploads=new ConsultantEquipmentAttachmentModel($multiAttachments[$k]);
						}else{
							$saveUploads=new ConsultantEquipmentAttachmentFinalModel($multiAttachments[$k]);
						}
					}else{	
						$saveUploads=new ConsultantEquipmentAttachmentModel($multiAttachments[$k]);
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
				return Redirect::to($equipmentEditPage.'/'.$postedValues["CrpConsultantId"].'?redirectUrl='.$redirectTo.'&usercdb=edit')->with('savedsuccessmessage','Equipment has been successfully updated.');
			}else{
				return Redirect::to($equipmentEditPage.'/'.$postedValues["CrpConsultantId"].'?redirectUrl='.$redirectTo)->with('savedsuccessmessage','Equipment has been successfully updated.');
			}
		}
		if($save){
			return Redirect::to('consultant/equipmentregistration');
		}else{
			return Redirect::to('consultant/equipmentregistration/'.$postedValues['CrpConsultantId'])->with('savedsuccessmessage','Equipment has been successfully updated.');
		}
	}
	public function confirmRegistration(){
		if(Session::has('ConsultantRegistrationId')){
			$consultantId=Session::get('ConsultantRegistrationId');
		}else{
			return Redirect::to('consultant/generalinforegistration')->withInput();
		}
		$appliedCategories=ConsultantWorkClassificationModel::serviceCategory($consultantId)->get(array('T1.Id','T1.Name as Category'));
		$appliedCategoryServices=ConsultantWorkClassificationModel::appliedService($consultantId)->get(array('crpconsultantworkclassification.CmnServiceCategoryId','T1.Code as ServiceCode','T1.Name as ServiceName'));
		$generalInformation=ConsultantModel::consultant($consultantId)->get(array('crpconsultant.Id','crpconsultant.NameOfFirm','crpconsultant.Address','crpconsultant.Email','crpconsultant.TelephoneNo','crpconsultant.MobileNo','crpconsultant.FaxNo','T1.Name as Country','T2.NameEn as Dzongkhag','T7.Name as OwnershipType','T7.ReferenceNo as OwnershipTypeReferenceNo'));
		$ownerPartnerDetails=ConsultantHumanResourceModel::consultantPartner($consultantId)->get(array('crpconsultanthumanresource.Id','crpconsultanthumanresource.CIDNo','crpconsultanthumanresource.Name','crpconsultanthumanresource.Sex','crpconsultanthumanresource.ShowInCertificate','T1.Name as Country','T2.Name as Salutation','T3.Name as Designation'));
		$consultantHumanResources=ConsultantHumanResourceModel::consultantHumanResource($consultantId)->get(array('crpconsultanthumanresource.Id','crpconsultanthumanresource.Name','crpconsultanthumanresource.CIDNo','crpconsultanthumanresource.Sex','crpconsultanthumanresource.Name','T1.Name as Salutation','T2.Name as Qualification','T3.Name as Trade','T4.Name as Designation','T5.Name as Country'));
		$consultantEquipments=ConsultantEquipmentModel::consultantEquipment($consultantId)->get(array('crpconsultantequipment.Id','crpconsultantequipment.RegistrationNo','crpconsultantequipment.ModelNo','crpconsultantequipment.Quantity','T1.Name'));
		$humanResourcesAttachments=ConsultantHumanResourceModel::humanResourceAttachments($consultantId)->get(array('T1.Id','T1.CrpConsultantHumanResourceId','T1.DocumentName','T1.DocumentPath'));
		$equipmentsAttachments=ConsultantEquipmentModel::equipmentAttachments($consultantId)->get(array('T1.Id','T1.CrpConsultantEquipmentId','T1.DocumentName','T1.DocumentPath'));
		$incorporationOwnershipTypes=ConsultantAttachmentModel::attachment($consultantId)->get(array('DocumentName','DocumentPath'));
		return View::make('crps.consultantregistrationconfirmation')
					->with('consultantId',$consultantId)
					->with('categories',$appliedCategories)
					->with('services',$appliedCategoryServices)
					->with('generalInformation',$generalInformation)
					->with('ownerPartnerDetails',$ownerPartnerDetails)
					->with('consultantHumanResources',$consultantHumanResources)
					->with('consultantEquipments',$consultantEquipments)
					->with('humanResourcesAttachments',$humanResourcesAttachments)
					->with('equipmentsAttachments',$equipmentsAttachments)
					->with('incorporationOwnershipTypes',$incorporationOwnershipTypes);
	}
	public function saveConfirmation(){
		$consultantId=Input::get('ConsultantId');
		$consultant = ConsultantModel::find($consultantId);
		$consultant->RegistrationStatus=1;
		$consultant->CmnApplicationRegistrationStatusId=CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW;
		$consultant->save();
		$consultantDetails=ConsultantModel::consultantHardList($consultantId)->get(array('NameOfFirm','Email','ReferenceNo','ApplicationDate','MobileNo'));
		$mailView="emails.crps.mailregistrationsuccess";
		$subject="Acknowledgement: Receipt of Application for Registration with CDB";
		$recipientAddress=$consultantDetails[0]->Email;
		$recipientName=$consultantDetails[0]->NameOfFirm;
		$referenceNo=$consultantDetails[0]->ReferenceNo;
		$applicationDate=$consultantDetails[0]->ApplicationDate;
		$mobileNo=$consultantDetails[0]->MobileNo;
		$smsMessage="Your application for consultant registration has been received. You can check status of your application using CID No. or Application No ($referenceNo) from CDB website.";
		$mailIntendedTo=2;//1=Contractor,2=Consultant,3=Architect,4=Engineer,5=Specialized Trade
		$feeStructures=CrpService::serviceDetails(CONST_SERVICETYPE_NEW)->take(1)->get(array('ConsultantAmount','ConsultantValidity'));
		$serviceCategories=ConsultantServiceCategoryModel::category()->get(array('Id','Name'));
		$appliedCategoryServices=DB::select("select T1.Id as ServiceId,T1.Code as ServiceCode,T1.Name as ServiceName,T1.CmnConsultantServiceCategoryId,T2.Id,T2.CmnAppliedServiceId from cmnconsultantservicecategory X join cmnconsultantservice T1 on X.Id=T1.CmnConsultantServiceCategoryId join crpconsultantworkclassification T2 on T1.Id=T2.CmnAppliedServiceId where T2.CrpConsultantId=? order by T1.Code",array($consultantId));
		$mailData=array(
			'mailIntendedTo'=>$mailIntendedTo,
			'applicantName'=>$recipientName,
			'applicationNo'=>$referenceNo,
			'applicationDate'=>$applicationDate,
			'feeStructures'=>$feeStructures,
			'serviceCategories'=>$serviceCategories,
			'appliedCategoryServices'=>$appliedCategoryServices,
			'mailMessage'=>"This is to acknowledge receipt of your application for registration of consultant with Construction Development Board (CDB). Your application will be processed in due course. You can check status of your application using your Citizenship Id No. or Application No. from the <a href=?#?>CDB website</a>. You will also be notified through email when your application is approved."
		);
		$this->sendEmailMessage($mailView,$mailData,$subject,$recipientAddress,$recipientName);
		$this->sendSms($smsMessage,$mobileNo);
		Session::forget('ConsultantRegistrationId');
		return Redirect::route('applicantregistrationsuccess',array('linktoprint'=>'consultant/printregistration','printreference'=>$consultantId,'applicationno'=>$referenceNo));	
	}
	public function consultantList(){
		$parameters=array();
		$linkText='Edit';
		$link='consultant/editdetails/';
		$consultantId=Input::get('CrpConsultantId');
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

		$query="select T1.Id,T1.ReferenceNo,T1.RegistrationExpiryDate,T1.ApplicationDate,T1.CDBNo,T1.MobileNo,Z.Name as Status,Z.ReferenceNo as StatusReference,T1.TelephoneNo,T1.Email,T1.NameOfFirm,T2.Name as Country,T3.NameEn as Dzongkhag,T4.Name as OwnershipType from crpconsultantfinal T1 	left join cmnlistitem Z on Z.Id = T1.CmnApplicationRegistrationStatusId  join cmnlistitem T4 on T1.CmnOwnershipTypeId=T4.Id join cmncountry T2 on T1.CmnCountryId=T2.Id left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id where 1";
		//array_push($parameters,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED);
		if(Route::current()->getUri()=="consultant/viewprintlist"){
			$linkText='View/Print';
			$link='consultant/viewprintdetails/';
		}elseif(Route::current()->getUri()=="consultant/newcommentsadverserecordslist"){
			$linkText='Add';
			$link='consultant/newcommentsadverserecords/';
		}elseif(Route::current()->getUri()=="consultant/editcommentsadverserecordslist"){
			$linkText='View';
			$link='consultant/editcommentsadverserecords/';
		}
		if((bool)$consultantId!=NULL || (bool)$CDBNo!=NULL || (bool)$registrationStatus!=NULL){
			if((bool)$consultantId!=NULL){
				$query.=" and T1.Id=?";
				array_push($parameters,$consultantId);
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
		$consultantLists=DB::select($query." order by T1.CDBNo,NameOfFirm".$limit,$parameters);
		$status=CmnListItemModel::registrationStatus()->get(array('Id','Name'));
		return View::make('crps.consultantlist')
					->with('pageTitle','List of Consultants')
					->with('link',$link)
					->with('linkText',$linkText)
					->with('CDBNo',$CDBNo)
					->with('registrationStatus',$registrationStatus)
					->with('status',$status)
					->with('consultantId',$consultantId)
					->with('consultantLists',$consultantLists);
	}
	public function consultantDetails($consultantId=null,$forReport = false){
		$consultantTrackrecords=array();
		$registrationApprovedForPayment=0;
		$registrationApproved=0;
		$userConsultant=0;
		if(Route::current()->getUri()=="consultant/verifyregistrationprocess/{consultantid}"){
			$view="crps.consultantverifyregistrationprocess";
			$modelPost="consultant/mverifyregistration";
		}elseif(Route::current()->getUri()=="consultant/approveregistrationprocess/{consultantid}"){
			$view="crps.consultantapproveregistrationprocess";
			$modelPost="consultant/mapproveregistration";
		}elseif(Route::current()->getUri()=="consultant/approvepaymentregistrationprocess/{consultantid}"){
			$consultantTrackrecords=CrpBiddingFormModel::consultantTrackRecords($consultantId)->get(array('crpbiddingform.WorkOrderNo','T6.ReferenceNo','crpbiddingform.NameOfWork','crpbiddingform.DescriptionOfWork','crpbiddingform.ContractPeriod','crpbiddingform.WorkStartDate','crpbiddingform.WorkCompletionDate','T2.Name as ProcuringAgency','T3.Name as ServiceCategory','T4.Code as ServiceName','T5.NameEn as Dzongkhag'));
			$registrationApprovedForPayment=1;
			$view="crps.consultantinformation";
			$modelPost=null;
		}elseif(Route::current()->getUri()=="consultant/viewregistrationprocess/{consultantid}"){
			$consultantTrackrecords=CrpBiddingFormModel::consultantTrackRecords($consultantId)->get(array('crpbiddingform.WorkOrderNo','T6.ReferenceNo','crpbiddingform.NameOfWork','crpbiddingform.DescriptionOfWork','crpbiddingform.ContractPeriod','crpbiddingform.WorkStartDate','crpbiddingform.WorkCompletionDate','T2.Name as ProcuringAgency','T3.Name as ServiceCategory','T4.Code as ServiceName','T5.NameEn as Dzongkhag'));
			$registrationApprovedForPayment=1;
			$view="crps.consultantinformation";
			$modelPost=null;
			$registrationApproved = 1;
		}else{
		    if(!$forReport){
                App::abort('404');
            }
		}
		$serviceCategories=ConsultantServiceCategoryModel::category()->get(array('Id','Name'));
		$appliedCategoryServices=DB::select("select T1.Id as ServiceId,T1.Code as ServiceCode,T1.Name as ServiceName,T1.CmnConsultantServiceCategoryId,T2.Id,T2.CmnAppliedServiceId from cmnconsultantservicecategory X join cmnconsultantservice T1 on X.Id=T1.CmnConsultantServiceCategoryId join crpconsultantworkclassification T2 on T1.Id=T2.CmnAppliedServiceId where T2.CrpConsultantId=? order by T1.Code",array($consultantId));
		$verifiedCategoryServices=DB::select("select T1.Id as ServiceId,T1.Code as ServiceCode,T1.Name as ServiceName,T1.CmnConsultantServiceCategoryId,T2.Id,T2.CmnAppliedServiceId from cmnconsultantservicecategory X join cmnconsultantservice T1 on X.Id=T1.CmnConsultantServiceCategoryId join crpconsultantworkclassification T2 on T1.Id=T2.CmnVerifiedServiceId where T2.CrpConsultantId=? order by T1.Code",array($consultantId));
		$approvedCategoryServices=DB::select("select T1.Id as ServiceId,T1.Code as ServiceCode,T1.Name as ServiceName,T1.CmnConsultantServiceCategoryId,T2.Id,T2.CmnAppliedServiceId from cmnconsultantservicecategory X join cmnconsultantservice T1 on X.Id=T1.CmnConsultantServiceCategoryId join crpconsultantworkclassification T2 on T1.Id=T2.CmnApprovedServiceId where T2.CrpConsultantId=? order by T1.Code",array($consultantId));

		$generalInformation=ConsultantModel::consultant($consultantId)->get(array('crpconsultant.Id','crpconsultant.CDBNo','crpconsultant.PaymentReceiptNo', 'crpconsultant.PaymentReceiptDate','crpconsultant.ReferenceNo','crpconsultant.CDBNo','crpconsultant.ApplicationDate','crpconsultant.NameOfFirm','crpconsultant.RegisteredAddress','crpconsultant.Village','crpconsultant.Gewog','crpconsultant.Address','crpconsultant.Email','crpconsultant.TelephoneNo','crpconsultant.MobileNo','crpconsultant.FaxNo','crpconsultant.CmnApplicationRegistrationStatusId','crpconsultant.VerifiedDate','crpconsultant.RemarksByVerifier','crpconsultant.RemarksByApprover','crpconsultant.RegistrationApprovedDate','crpconsultant.RemarksByPaymentApprover','crpconsultant.RegistrationPaymentApprovedDate','T1.Name as Country','T2.NameEn as Dzongkhag','T4.FullName as Verifier','T5.FullName as Approver','T6.FullName as PaymentApprover','T7.Name as OwnershipType','T7.ReferenceNo as OwnershipTypeReferenceNo','T8.NameEn as RegisteredDzongkhag'));
		$ownerPartnerDetails=ConsultantHumanResourceModel::consultantPartner($consultantId)->get(array('crpconsultanthumanresource.Id','crpconsultanthumanresource.CIDNo','crpconsultanthumanresource.Verified','crpconsultanthumanresource.Approved','crpconsultanthumanresource.Name','crpconsultanthumanresource.Sex','crpconsultanthumanresource.ShowInCertificate','T1.Name as Country','T2.Name as Salutation','T3.Name as Designation'));
		$consultantHumanResources=ConsultantHumanResourceModel::consultantHumanResource($consultantId)->get(array('crpconsultanthumanresource.Id','crpconsultanthumanresource.Name','crpconsultanthumanresource.CIDNo','crpconsultanthumanresource.Sex','crpconsultanthumanresource.Name','crpconsultanthumanresource.Verified','crpconsultanthumanresource.Approved','T1.Name as Salutation','T2.Name as Qualification','T3.Name as Trade','T4.Name as Designation','T5.Name as Country'));
		$consultantEquipments=ConsultantEquipmentModel::consultantEquipment($consultantId)->get(array('crpconsultantequipment.Id','crpconsultantequipment.RegistrationNo','crpconsultantequipment.ModelNo','crpconsultantequipment.Quantity','crpconsultantequipment.Verified','crpconsultantequipment.Approved','T1.Name','T1.VehicleType'));
		$consultantHumanResourceAttachments=ConsultantHumanResourceAttachmentModel::singleConsultantHumanResourceAllAttachments($consultantId)->get(array('crpconsultanthumanresourceattachment.DocumentName','crpconsultanthumanresourceattachment.DocumentPath','crpconsultanthumanresourceattachment.CrpConsultantHumanResourceId'));
		$consultantEquipmentAttachments=ConsultantEquipmentAttachmentModel::singleConsultantEquipmentAllAttachments($consultantId)->get(array('crpconsultantequipmentattachment.DocumentName','crpconsultantequipmentattachment.DocumentPath','crpconsultantequipmentattachment.CrpConsultantEquipmentId'));
		$feeStructures=CrpService::serviceDetails(CONST_SERVICETYPE_NEW)->take(1)->get(array('ConsultantAmount','ConsultantValidity'));
		$incorporationOwnershipTypes=ConsultantAttachmentModel::attachment($consultantId)->get(array('DocumentName','DocumentPath'));
		$consultantComments = ConsultantCommentAdverseRecordModel::commentList($consultantId)->get(array('Id','Date','Remarks'));
		$consultantAdverseRecords = ConsultantCommentAdverseRecordModel::adverseRecordList($consultantId)->get(array('Id','Date','Remarks'));

		/*---*/
		if($forReport){
		    $reportDetails['feeStructures'] = $feeStructures;
		    $reportDetails['serviceCategories'] = $serviceCategories;
		    $reportDetails['appliedCategoryServices'] = $appliedCategoryServices;
		    $reportDetails['verifiedCategoryServices'] = $verifiedCategoryServices;
		    $reportDetails['approvedCategoryServices'] = $approvedCategoryServices;
		    return $reportDetails;
        }
		foreach($serviceCategories as $singleCategory):
			$appliedCategoryServicesArray[$singleCategory->Id] = DB::table('crpconsultantworkclassification as T1')->join("cmnconsultantservice as T2",'T1.CmnAppliedServiceId','=','T2.Id')->where('T1.CmnServiceCategoryId',$singleCategory->Id)->where('T1.CrpConsultantId',$consultantId)->groupBy('T1.CmnServiceCategoryId')->whereNotNull('T1.CmnAppliedServiceId')->get(array(DB::raw("group_concat(T2.Code SEPARATOR ',') as Service")));
			$verifiedCategoryServicesArray[$singleCategory->Id] = DB::table('crpconsultantworkclassification as T1')->join("cmnconsultantservice as T2",'T1.CmnVerifiedServiceId','=','T2.Id')->where('T1.CmnServiceCategoryId',$singleCategory->Id)->where('T1.CrpConsultantId',$consultantId)->groupBy('T1.CmnServiceCategoryId')->whereNotNull('T1.CmnVerifiedServiceId')->get(array(DB::raw("group_concat(T2.Code SEPARATOR ',') as Service")));
			$approvedCategoryServicesArray[$singleCategory->Id] = DB::table('crpconsultantworkclassification as T1')->join("cmnconsultantservice as T2",'T1.CmnApprovedServiceId','=','T2.Id')->where('T1.CmnServiceCategoryId',$singleCategory->Id)->where('T1.CrpConsultantId',$consultantId)->groupBy('T1.CmnServiceCategoryId')->whereNotNull('T1.CmnApprovedServiceId')->get(array(DB::raw("group_concat(T2.Code SEPARATOR ',') as Service")));
		endforeach;
		/*---*/
		return View::make($view)
					->with('modelPost',$modelPost)
					->with('appliedCategoryServicesArray',$appliedCategoryServicesArray)
					->with('verifiedCategoryServicesArray',$verifiedCategoryServicesArray)
					->with('approvedCategoryServicesArray',$approvedCategoryServicesArray)
					->with('serviceCategories',$serviceCategories)
					->with('consultantId',$consultantId)
					->with('registrationApprovedForPayment',$registrationApprovedForPayment)
					->with('registrationApproved',$registrationApproved)
					->with('userConsultant',$userConsultant)					
					->with('appliedCategoryServices',$appliedCategoryServices)
					->with('verifiedCategoryServices',$verifiedCategoryServices)
					->with('approvedCategoryServices',$approvedCategoryServices)
					->with('generalInformation',$generalInformation)
					->with('ownerPartnerDetails',$ownerPartnerDetails)
					->with('consultantHumanResources',$consultantHumanResources)
					->with('consultantEquipments',$consultantEquipments)
					->with('consultantTrackrecords',$consultantTrackrecords)
					->with('consultantHumanResourceAttachments',$consultantHumanResourceAttachments)
					->with('consultantEquipmentAttachments',$consultantEquipmentAttachments)
					->with('feeStructures',$feeStructures)
					->with('consultantComments',$consultantComments)
					->with('consultantAdverseRecords',$consultantAdverseRecords)
					->with('incorporationOwnershipTypes',$incorporationOwnershipTypes);
	}
	public function editDetails($consultantId){
		$registrationApprovedForPayment=0;
		$userConsultant=0;
		$loggedInUser = Auth::user()->Id;
		$userRoles=DB::table('sysuserrolemap')->where('SysUserId',$loggedInUser)->lists('SysRoleId');
		$isAdmin = false;
		if(in_array(CONST_ROLE_ADMINISTRATOR,$userRoles)){
			$isAdmin = true;
		}
		$serviceCategories=ConsultantServiceCategoryModel::category()->get(array('Id','Name'));
		$appliedCategoryServices=DB::select("select distinct T1.Id as ServiceId,T1.Code as ServiceCode,T1.Name as ServiceName,T1.CmnConsultantServiceCategoryId,T2.Id,T2.CmnAppliedServiceId from cmnconsultantservicecategory X join cmnconsultantservice T1 on X.Id=T1.CmnConsultantServiceCategoryId join crpconsultantworkclassificationfinal T2 on T1.Id=T2.CmnAppliedServiceId where T2.CrpConsultantFinalId=? order by T1.Code",array($consultantId));
		$verifiedCategoryServices=DB::select("select distinct T1.Id as ServiceId,T1.Code as ServiceCode,T1.Name as ServiceName,T1.CmnConsultantServiceCategoryId,T2.Id,T2.CmnAppliedServiceId from cmnconsultantservicecategory X join cmnconsultantservice T1 on X.Id=T1.CmnConsultantServiceCategoryId join crpconsultantworkclassificationfinal T2 on T1.Id=T2.CmnVerifiedServiceId where T2.CrpConsultantFinalId=? order by T1.Code",array($consultantId));
		$approvedCategoryServices=DB::select("select distinct T1.Id as ServiceId,T1.Code as ServiceCode,T1.Name as ServiceName,T1.CmnConsultantServiceCategoryId,T2.Id,T2.CmnAppliedServiceId from cmnconsultantservicecategory X join cmnconsultantservice T1 on X.Id=T1.CmnConsultantServiceCategoryId join crpconsultantworkclassificationfinal T2 on T1.Id=T2.CmnApprovedServiceId where T2.CrpConsultantFinalId=? order by T1.Code",array($consultantId));
		$generalInformation=ConsultantFinalModel::consultant($consultantId)->get(array('crpconsultantfinal.Id','crpconsultantfinal.ReferenceNo','crpconsultantfinal.DeRegisteredDate','crpconsultantfinal.BlacklistedDate','crpconsultantfinal.DeregisteredRemarks','crpconsultantfinal.BlacklistedRemarks','crpconsultantfinal.RevokedDate','crpconsultantfinal.RevokedRemarks','crpconsultantfinal.SurrenderedDate','crpconsultantfinal.SurrenderedRemarks',
		'crpconsultantfinal.CDBNo','crpconsultantfinal.TradeLicenseNo','crpconsultantfinal.TPN','crpconsultantfinal.ApplicationDate','crpconsultantfinal.NameOfFirm','crpconsultantfinal.RegisteredAddress','crpconsultantfinal.Gewog','crpconsultantfinal.Village','crpconsultantfinal.Address','crpconsultantfinal.Email','crpconsultantfinal.TelephoneNo','crpconsultantfinal.MobileNo','crpconsultantfinal.FaxNo','crpconsultantfinal.RegistrationExpiryDate','crpconsultantfinal.CmnApplicationRegistrationStatusId','T3.Name as Status','T1.Name as Country','T2.NameEn as Dzongkhag','T4.Name as OwnershipType','T5.NameEn as RegisteredDzongkhag'));

		//$generalInformation=ConsultantFinalModel::consultant($consultantId)->get(array('crpconsultantfinal.Id','crpconsultantfinal.ReferenceNo','crpconsultantfinal.DeRegisteredDate','crpconsultantfinal.BlacklistedDate','crpconsultantfinal.DeregisteredRemarks','crpconsultantfinal.BlacklistedRemarks','crpconsultantfinal.RevokedDate','crpconsultantfinal.RevokedRemarks','crpconsultantfinal.SurrenderedDate','crpconsultantfinal.SurrenderedRemarks',
                //'crpconsultantfinal.CDBNo','crpconsultantfinal.ApplicationDate','crpconsultantfinal.NameOfFirm','crpconsultantfinal.Address','crpconsultantfinal.Email','crpconsultantfinal.TelephoneNo','crpconsultantfinal.MobileNo','crpconsultantfinal.FaxNo','crpconsultantfinal.RegistrationExpiryDate','crpconsultantfinal.CmnApplicationRegistrationStatusId','T3.Name as Status','T1.Name as Country','T2.NameEn as Dzongkhag','T5.NameEn as RegisteredDzongkhag'));
		$ownerPartnerDetails=ConsultantHumanResourceFinalModel::consultantPartner($consultantId)->get(array('crpconsultanthumanresourcefinal.Id','crpconsultanthumanresourcefinal.CIDNo','crpconsultanthumanresourcefinal.Name','crpconsultanthumanresourcefinal.Sex','crpconsultanthumanresourcefinal.JoiningDate','crpconsultanthumanresourcefinal.ShowInCertificate','T1.Name as Country','T2.Name as Salutation','T3.Name as Designation'));
		$consultantHumanResources=ConsultantHumanResourceFinalModel::consultantHumanResource($consultantId)->get(array('crpconsultanthumanresourcefinal.Id','crpconsultanthumanresourcefinal.Name','crpconsultanthumanresourcefinal.CIDNo','crpconsultanthumanresourcefinal.Sex','crpconsultanthumanresourcefinal.JoiningDate','crpconsultanthumanresourcefinal.Name','T1.Name as Salutation','T2.Name as Qualification','T3.Name as Trade','T4.Name as Designation','T5.Name as Country','T10.CDBNO as CDBNo1', 'T11.ARNo as CDBNo2', 'T12.ARNo as CDBNo3'));
		$consultantEquipments=ConsultantEquipmentFinalModel::consultantEquipment($consultantId)->get(array('crpconsultantequipmentfinal.Id','crpconsultantequipmentfinal.RegistrationNo','crpconsultantequipmentfinal.ModelNo','crpconsultantequipmentfinal.Quantity','T1.Name'));
		$consultantTrackrecords=CrpBiddingFormModel::consultantTrackRecords($consultantId)->get(array('crpbiddingform.WorkOrderNo','crpbiddingform.NameOfWork','crpbiddingform.DescriptionOfWork','crpbiddingform.ContractPeriod','crpbiddingform.WorkStartDate','crpbiddingform.WorkCompletionDate','T2.Name as ProcuringAgency','T3.Name as ServiceCategory','T4.Code as ServiceName','T5.NameEn as Dzongkhag'));
		$consultantHumanResourceAttachments=ConsultantHumanResourceAttachmentFinalModel::singleConsultantHumanResourceAllAttachments($consultantId)->get(array('crpconsultanthumanresourceattachmentfinal.DocumentName','crpconsultanthumanresourceattachmentfinal.DocumentPath','crpconsultanthumanresourceattachmentfinal.CrpConsultantHumanResourceFinalId as CrpConsultantHumanResourceId'));
		$consultantEquipmentAttachments=ConsultantEquipmentAttachmentFinalModel::singleConsultantEquipmentAllAttachments($consultantId)->get(array('crpconsultantequipmentattachmentfinal.DocumentName','crpconsultantequipmentattachmentfinal.DocumentPath','crpconsultantequipmentattachmentfinal.CrpConsultantEquipmentFinalId as CrpConsultantEquipmentId'));
		$consultantComments = ConsultantCommentAdverseRecordModel::commentList($consultantId)->get(array('Id','Date','Remarks'));
		$consultantAdverseRecords = ConsultantCommentAdverseRecordModel::adverseRecordList($consultantId)->get(array('Id','Date','Remarks'));
		return View::make('crps.consultantinformation')
					->with('isAdmin',$isAdmin)
					->with('final',true)
					->with('serviceCategories',$serviceCategories)
					->with('consultantId',$consultantId)
					->with('registrationApprovedForPayment',$registrationApprovedForPayment)
					->with('userConsultant',$userConsultant)					
					->with('appliedCategoryServices',$appliedCategoryServices)
					->with('verifiedCategoryServices',$verifiedCategoryServices)
					->with('approvedCategoryServices',$approvedCategoryServices)
					->with('generalInformation',$generalInformation)
					->with('ownerPartnerDetails',$ownerPartnerDetails)
					->with('consultantHumanResources',$consultantHumanResources)
					->with('consultantEquipments',$consultantEquipments)
					->with('consultantTrackrecords',$consultantTrackrecords)
					->with('consultantHumanResourceAttachments',$consultantHumanResourceAttachments)
					->with('consultantEquipmentAttachments',$consultantEquipmentAttachments)
					->with('consultantComments',$consultantComments)
					->with('consultantAdverseRecords',$consultantAdverseRecords);
	}
	public function viewRegistrationList(){
		$consultantIdMyTask=Input::get('CrpConsultantIdMyTask');
		$fromDateMyTask='2016-06-01';
		$toDateMyTask=Input::get('ToDateMyTask');
		$query="select T1.Id,T1.ReferenceNo,T1.ApplicationDate,T1.MobileNo,T1.TelephoneNo,T1.Email,T1.NameOfFirm,T2.Name as Country,T3.NameEn as Dzongkhag,T4.Name as OwnershipType from (crpconsultant T1 join crpconsultantfinal X on T1.Id = X.Id) join cmnlistitem T4 on T1.CmnOwnershipTypeId=T4.Id join cmncountry T2 on T1.CmnCountryId=T2.Id left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id where T1.CmnApplicationRegistrationStatusId=? and T1.SysLockedByUserId is null and T1.CrpConsultantId is null";
		$parameters=array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED);
		if((bool)$consultantIdMyTask!=NULL || (bool)$fromDateMyTask!=NULL || (bool)$toDateMyTask!=NULL){
			if((bool)$consultantIdMyTask!=NULL){
				$query.=" and T1.Id=?";
				array_push($parameters,$consultantIdMyTask);
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
		$consultantLists=DB::select($query." and X.SysFinalApproverUserId is null order by ApplicationDate,NameOfFirm",$parameters);
		return View::make('crps.consultantregistrationviewlist')
			->with('pageTitle',"Approve Consultant's Registration")
			->with('consultantIdMyTask',$consultantIdMyTask)
			->with('fromDateMyTask',$fromDateMyTask)
			->with('toDateMyTask',convertDateToClientFormat($toDateMyTask))
			->with('consultantLists',$consultantLists);
	}
	public function verifyList(){
		$redirectUrl = "consultant/verifyregistration";
		$recordLockException=false;
		$applicationPickedByUserFullName=null;
		if(Session::has('ApplicationAlreadyPicked')){
			$applicationPickedByUserFullName=Session::get('ApplicationAlreadyPicked');
			$recordLockException=true;
		}

		$consultantIdAll=Input::get('CrpConsultantIdAll');
		$fromDateAll=Input::get('FromDateAll');
		$toDateAll=Input::get('ToDateAll');
		/*----end of parameters for alll the applications*/
		$consultantIdMyTask=Input::get('CrpConsultantIdMyTask');
		$fromDateMyTask=Input::get('FromDateMyTask');
		$toDateMyTask=Input::get('ToDateMyTask');
		/*----end of parameters for My Task the applications*/
		$query="select T1.Id,T1.ReferenceNo,T1.ApplicationDate,T1.MobileNo,T1.TelephoneNo,T1.Email,T1.NameOfFirm,T2.Name as Country,T3.NameEn as Dzongkhag,T4.Name as OwnershipType from crpconsultant T1 join cmnlistitem T4 on T1.CmnOwnershipTypeId=T4.Id join cmncountry T2 on T1.CmnCountryId=T2.Id left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id where T1.CmnApplicationRegistrationStatusId=? and T1.SysLockedByUserId is null and T1.CrpConsultantId is null";
		$queryMyTaskList="select T1.Id,T1.ReferenceNo,T1.ApplicationDate,T1.MobileNo,T1.TelephoneNo,T1.Email,T1.NameOfFirm,T2.Name as Country,T3.NameEn as Dzongkhag,T4.Name as OwnershipType from crpconsultant T1 join cmnlistitem T4 on T1.CmnOwnershipTypeId=T4.Id join cmncountry T2 on T1.CmnCountryId=T2.Id left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id where T1.CmnApplicationRegistrationStatusId=? and T1.SysLockedByUserId=? and T1.CrpConsultantId is null";
		$parameters=array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW);
		$parametersMyTaskList=array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW,Auth::user()->Id);
		if((bool)$consultantIdAll!=NULL || (bool)$fromDateAll!=NULL || (bool)$toDateAll!=NULL || (bool)$consultantIdMyTask!=NULL || (bool)$fromDateMyTask!=NULL || (bool)$toDateMyTask!=NULL){
			if((bool)$consultantIdAll!=NULL){
				$query.=" and T1.Id=?";
				array_push($parameters,$consultantIdAll);
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
			if((bool)$consultantIdMyTask!=NULL){
				$query.=" and T1.Id=?";
				array_push($parameters,$consultantIdMyTask);
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
		$consultantLists=DB::select($query." order by ApplicationDate,ReferenceNo,NameOfFirm",$parameters);
		$consultantMyTaskLists=DB::select($queryMyTaskList." order by ApplicationDate,ReferenceNo,NameOfFirm",$parametersMyTaskList);
		return View::make('crps.consultantregistrationapplicationprocesslist')
					->with('redirectUrl',$redirectUrl)
					->with('pageTitle',"Verify Consultant Registration")
					->with('recordLockException',$recordLockException)
					->with('applicationPickedByUserFullName',$applicationPickedByUserFullName)
					->with('fromDateAll',$fromDateAll)
					->with('toDateAll',$toDateAll)
					->with('consultantIdAll',$consultantIdAll)
					->with('fromDateMyTask',$fromDateMyTask)
					->with('toDateMyTask',$toDateMyTask)
					->with('consultantIdMyTask',$consultantIdMyTask)
					->with('consultantLists',$consultantLists)
					->with('consultantMyTaskLists',$consultantMyTaskLists);
	}
	public function verifyRegistration(){
		$postedValues=Input::all();
		
		DB::beginTransaction();
		try{
			$instance=ConsultantModel::find($postedValues['ConsultantReference']);
			$instance->fill($postedValues);
			$instance->update();
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
		return Redirect::to('consultant/verifyregistration')
						->with('savedsuccessmessage','The application has been successfully verified.');
	}
	public function approveList(){
		$recordLockException=false;
		$applicationPickedByUserFullName=null;
		if(Session::has('ApplicationAlreadyPicked')){
			$applicationPickedByUserFullName=Session::get('ApplicationAlreadyPicked');
			$recordLockException=true;
		}

		$consultantIdAll=Input::get('CrpConsultantIdAll');
		$fromDateAll=Input::get('FromDateAll');
		$toDateAll=Input::get('ToDateAll');
		/*----end of parameters for alll the applications*/
		$consultantIdMyTask=Input::get('CrpConsultantIdMyTask');
		$fromDateMyTask=Input::get('FromDateMyTask');
		$toDateMyTask=Input::get('ToDateMyTask');
		/*----end of parameters for My Task the applications*/
		$query="select T1.Id,T1.ReferenceNo,T1.ApplicationDate,T1.MobileNo,T1.TelephoneNo,T1.Email,T1.NameOfFirm,T1.CmnApplicationRegistrationStatusId,T2.Name as Country,T3.NameEn as Dzongkhag,T4.Name as OwnershipType from crpconsultant T1 join cmnlistitem T4 on T1.CmnOwnershipTypeId=T4.Id join cmncountry T2 on T1.CmnCountryId=T2.Id left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id where T1.CmnApplicationRegistrationStatusId=? and T1.SysLockedByUserId is null and T1.CrpConsultantId is null";
		$queryMyTaskList="select T1.Id,T1.ReferenceNo,T1.ApplicationDate,T1.MobileNo,T1.TelephoneNo,T1.Email,T1.NameOfFirm,T1.CmnApplicationRegistrationStatusId,T2.Name as Country,T3.NameEn as Dzongkhag,T4.Name as OwnershipType from crpconsultant T1 join cmnlistitem T4 on T1.CmnOwnershipTypeId=T4.Id join cmncountry T2 on T1.CmnCountryId=T2.Id left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id where T1.CmnApplicationRegistrationStatusId=? and T1.SysLockedByUserId=? and T1.CrpConsultantId is null";
		$redirectUrl = Request::path();
		if(Request::path()=="consultant/approvefeepayment"){
            $query.=" and case when T1.ApplicationDate <= '2017-08-02' then DATEDIFF(NOW(),T1.RegistrationApprovedDate)<=90 else DATEDIFF(NOW(),T1.RegistrationApprovedDate)<=30 end";
            $queryMyTaskList.=" and case when T1.ApplicationDate <= '2017-08-02' then DATEDIFF(NOW(),T1.RegistrationApprovedDate)<=90 else DATEDIFF(NOW(),T1.RegistrationApprovedDate)<=30 end";
			$parameters=array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVEDFORPAYMENT);
			$parametersMyTaskList=array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVEDFORPAYMENT,Auth::user()->Id);
		}else{
			$parameters=array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_VERIFIED);
			$parametersMyTaskList=array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_VERIFIED,Auth::user()->Id);
		}
		if((bool)$consultantIdAll!=NULL || (bool)$fromDateAll!=NULL || (bool)$toDateAll!=NULL || (bool)$consultantIdMyTask!=NULL || (bool)$fromDateMyTask!=NULL || (bool)$toDateMyTask!=NULL){
			if((bool)$consultantIdAll!=NULL){
				$query.=" and T1.Id=?";
				array_push($parameters,$consultantIdAll);
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
			if((bool)$consultantIdMyTask!=NULL){
				$query.=" and T1.Id=?";
				array_push($parameters,$consultantIdMyTask);
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
		$consultantLists=DB::select($query." order by ApplicationDate,ReferenceNo,NameOfFirm",$parameters);
		$consultantMyTaskLists=DB::select($queryMyTaskList." order by ApplicationDate,ReferenceNo,NameOfFirm",$parametersMyTaskList);
		return View::make('crps.consultantregistrationapplicationprocesslist')
					->with('redirectUrl',$redirectUrl)
					->with('pageTitle',"Approve Consultants Registration")
					->with('recordLockException',$recordLockException)
					->with('applicationPickedByUserFullName',$applicationPickedByUserFullName)
					->with('fromDateAll',$fromDateAll)
					->with('toDateAll',$toDateAll)
					->with('consultantIdAll',$consultantIdAll)
					->with('fromDateMyTask',$fromDateMyTask)
					->with('toDateMyTask',$toDateMyTask)
					->with('consultantIdMyTask',$consultantIdMyTask)
					->with('consultantLists',$consultantLists)
					->with('consultantMyTaskLists',$consultantMyTaskLists);
	}
	public function approveRegistration(){
		$postedValues=Input::all();
		$postedValues['RegistrationApprovedDate']=$this->convertDateTime($postedValues['RegistrationApprovedDate']);
		$postedValues['RegistrationExpiryDate']=$this->convertDateTime($postedValues['RegistrationExpiryDate']);
		DB::beginTransaction();
		try{	
			$instance=ConsultantModel::find($postedValues['ConsultantReference']);
			$instance->fill($postedValues);
			$instance->update();
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
		$consultantDetails=ConsultantModel::consultantHardList(Input::get('ConsultantReference'))->get(array('CDBNo','NameOfFirm','Email','ReferenceNo','ApplicationDate','MobileNo','RemarksByVerifier','RemarksByApprover'));
		$mailView="emails.crps.mailapplicationapproved";
		$subject="Approval of Your Registration with CDB";
		$cdbNo=$consultantDetails[0]->CDBNo;
		$recipientAddress=$consultantDetails[0]->Email;
		$recipientName=$consultantDetails[0]->NameOfFirm;
		$mobileNo=$consultantDetails[0]->MobileNo;
		$remarksByVerifier = $consultantDetails[0]->RemarksByVerifier;
		$remarksByApprover = $consultantDetails[0]->RemarksByApprover;
		$mailIntendedTo=2;//1=Contractor,2=Consultant,3=Architect,4=Engineer,5=Specialized Trade
		$feeStructures=CrpService::serviceDetails(CONST_SERVICETYPE_NEW)->take(1)->get(array('ConsultantAmount','ConsultantValidity'));
		$serviceCategories=ConsultantServiceCategoryModel::category()->get(array('Id','Name'));
		$appliedCategoryServices=DB::select("select T1.Id as ServiceId,T1.Code as ServiceCode,T1.Name as ServiceName,T1.CmnConsultantServiceCategoryId,T2.Id,T2.CmnAppliedServiceId from cmnconsultantservicecategory X join cmnconsultantservice T1 on X.Id=T1.CmnConsultantServiceCategoryId join crpconsultantworkclassification T2 on T1.Id=T2.CmnAppliedServiceId where T2.CrpConsultantId=? order by T1.Code",array(Input::get('ConsultantReference')));
		$verifiedCategoryServices=DB::select("select T1.Id as ServiceId,T1.Code as ServiceCode,T1.Name as ServiceName,T1.CmnConsultantServiceCategoryId,T2.Id,T2.CmnAppliedServiceId from cmnconsultantservicecategory X join cmnconsultantservice T1 on X.Id=T1.CmnConsultantServiceCategoryId join crpconsultantworkclassification T2 on T1.Id=T2.CmnVerifiedServiceId where T2.CrpConsultantId=? order by T1.Code",array(Input::get('ConsultantReference')));
		$approvedCategoryServices=DB::select("select T1.Id as ServiceId,T1.Code as ServiceCode,T1.Name as ServiceName,T1.CmnConsultantServiceCategoryId,T2.Id,T2.CmnAppliedServiceId from cmnconsultantservicecategory X join cmnconsultantservice T1 on X.Id=T1.CmnConsultantServiceCategoryId join crpconsultantworkclassification T2 on T1.Id=T2.CmnApprovedServiceId where T2.CrpConsultantId=? order by T1.Code",array(Input::get('ConsultantReference')));
		$mailData=array(
			'mailIntendedTo'=>$mailIntendedTo,
			'feeStructures'=>$feeStructures,
			'serviceCategories'=>$serviceCategories,
			'appliedCategoryServices'=>$appliedCategoryServices,
			'verifiedCategoryServices'=>$verifiedCategoryServices,
			'approvedCategoryServices'=>$approvedCategoryServices,
			'cdbNo'=>$cdbNo,
			'applicantName'=>$consultantDetails[0]->NameOfFirm,
			'applicationNo'=>$consultantDetails[0]->ReferenceNo,
			'applicationDate'=>$consultantDetails[0]->ApplicationDate,
			'mailMessage'=>"Construction Development Board (CDB) has verified and approved your application for registration of consultant with CDB.  However, you need to pay your registration fees as per the details given below within one month (30 days) to the CDB office or Nearest Regional Revenue and Customs Office (RRCO). Upon payment to the RRCO, email money receipt to Accountant@cdb.gov.bt or registration@cdb.gov.bt. We will email you your username and password upon confirmation of your payment by CDB.<br/>Remarks by Verifier: $remarksByVerifier <br/>Remarks by Approver: $remarksByApprover"
		);
		$smsMessage="Your application for consultant registration has been approved by CDB. Please check your email for detailed information regarding your fees.";
		$this->sendEmailMessage($mailView,$mailData,$subject,$recipientAddress,$recipientName);
		$this->sendSms($smsMessage,$mobileNo);
		return Redirect::to('consultant/approveregistration')
						->with('savedsuccessmessage','The application has been successfully approved.');
	}
	public function approvePayment(){
		$postedValues=Input::except('consultantregistrationpayment');
		$paymentValues = Input::get('consultantregistrationpayment');
		$postedValues['PaymentReceiptDate']=$this->convertDate($postedValues['PaymentReceiptDate']);
		$postedValues["InitialDate"] = date('Y-m-d');
		DB::beginTransaction();
		try{	
			$consultantReference= new ConsultantModel();
			$instance=$consultantReference::find($postedValues['ConsultantReference']);
			$instance->fill($postedValues);
			$instance->update();
			$uuid=DB::select("select uuid() as Id");
			$generatedId=$uuid[0]->Id;
			$consultantDetails=ConsultantModel::consultantHardList(Input::get('ConsultantReference'))->get(array('CDBNo','TPN','NameOfFirm','Email','ReferenceNo','ApplicationDate','MobileNo','RemarksByVerifier','RemarksByApprover'));
			$CDBNo=$consultantDetails[0]->CDBNo;
			$plainPassword=substr(md5(uniqid(mt_rand(), true)), 0, 5);
			$plainPassword.="@#".date('d');
			$password=Hash::make($plainPassword);
	        $userCredentials=array('Id'=>$generatedId,'username'=>$consultantDetails[0]->Email,'password'=>$password,'FullName'=>$consultantDetails[0]->NameOfFirm,'Status'=>1,'CreatedBy'=>Auth::user()->Id);
			$roleData=array('SysUserId'=>$generatedId,'SysRoleId'=>CONST_ROLE_CONSULTANT,'CreatedBy'=>Auth::user()->Id);
			User::create($userCredentials);
			RoleUserMapModel::create($roleData);
			DB::statement("call ProCrpConsultantNewRegistrationFinalData(?,?,?,?)",array(Input::get('ConsultantReference'),$generatedId,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED,Auth::user()->Id));
			$feeStructures=CrpService::serviceDetails(CONST_SERVICETYPE_NEW)->take(1)->get(array('ConsultantAmount','ConsultantValidity'));
			$serviceCategories=ConsultantServiceCategoryModel::category()->get(array('Id','Name'));
			$appliedCategoryServices=DB::select("select T1.Id as ServiceId,T1.Code as ServiceCode,T1.Name as ServiceName,T1.CmnConsultantServiceCategoryId,T2.Id,T2.CmnAppliedServiceId from cmnconsultantservicecategory X join cmnconsultantservice T1 on X.Id=T1.CmnConsultantServiceCategoryId join crpconsultantworkclassification T2 on T1.Id=T2.CmnAppliedServiceId where T2.CrpConsultantId=? order by T1.Code",array(Input::get('ConsultantReference')));
			$verifiedCategoryServices=DB::select("select T1.Id as ServiceId,T1.Code as ServiceCode,T1.Name as ServiceName,T1.CmnConsultantServiceCategoryId,T2.Id,T2.CmnAppliedServiceId from cmnconsultantservicecategory X join cmnconsultantservice T1 on X.Id=T1.CmnConsultantServiceCategoryId join crpconsultantworkclassification T2 on T1.Id=T2.CmnVerifiedServiceId where T2.CrpConsultantId=? order by T1.Code",array(Input::get('ConsultantReference')));
			$approvedCategoryServices=DB::select("select T1.Id as ServiceId,T1.Code as ServiceCode,T1.Name as ServiceName,T1.CmnConsultantServiceCategoryId,T2.Id,T2.CmnAppliedServiceId from cmnconsultantservicecategory X join cmnconsultantservice T1 on X.Id=T1.CmnConsultantServiceCategoryId join crpconsultantworkclassification T2 on T1.Id=T2.CmnApprovedServiceId where T2.CrpConsultantId=? order by T1.Code",array(Input::get('ConsultantReference')));
			foreach($paymentValues as $key=>$value):
				foreach($value as $a=>$b):
					$postedValues[$a] = $b;
				endforeach;
				$postedValues['CrpConsultantFinalId'] = Input::get('ConsultantReference');
				ConsultantRegistrationPayment::create($postedValues);
			endforeach;

		}catch(Exception $e){
			DB::rollback();
        	throw $e;
        	//return Redirect::to('master/specializedtradecategory')->withErrors($e->getErrors())->withInput();
		}
		DB::commit();
		$mailView="emails.crps.mailregistrationpaymentcompletion";
		$subject="Consultant Login Credentials";
		$applicationNo=$consultantDetails[0]->ReferenceNo;
		$applicationDate=$consultantDetails[0]->ApplicationDate;
		$recipientAddress=$consultantDetails[0]->Email;
		$recipientName=$consultantDetails[0]->NameOfFirm;
		$mobileNo=$consultantDetails[0]->MobileNo;
		$tpn = $consultantDetails[0]->TPN;
		$remarksByVerifier = $consultantDetails[0]->RemarksByVerifier;
		$remarksByApprover = $consultantDetails[0]->RemarksByApprover;
		$mailData=array(
			'mailIntendedTo'=>2,
			'applicantName'=>$recipientName,
			'applicationNo'=>$applicationNo,
			'applicationDate'=>$applicationDate,
			'tpn'=>$tpn,
			'username'=>$recipientAddress,
			'password'=>$plainPassword,
			'feeStructures'=>$feeStructures,
			'serviceCategories'=>$serviceCategories,
			'appliedCategoryServices'=>$appliedCategoryServices,
			'verifiedCategoryServices'=>$verifiedCategoryServices,
			'approvedCategoryServices'=>$approvedCategoryServices,
			'mailMessage'=>"This is to acknowledge receipt of your payment vide Receipt No. ".Input::get('PaymentReceiptNo')." dated ".Input::get('PaymentReceiptDate')." for registration of your firm (".$recipientName.") with Construction Development Board (CDB). Your CDB No. is ".$CDBNo." and your CDB certificate has been activated. You can view and print it by logging in to your account using below given user credentials. Please change your password regularly to make your account secure.<br/>Remarks by Verifier: $remarksByVerifier <br/>Remarks by Approver: $remarksByApprover"
		);
		$smsMessage="Your registration fees for consultant registration has been received by CDB and your certificate has been activated. Your CDB No. is $CDBNo. Your username is $recipientAddress and password is $plainPassword";
		$this->sendEmailMessage($mailView,$mailData,$subject,$recipientAddress,$recipientName);
		$this->sendSms($smsMessage,$mobileNo);
		return Redirect::to('consultant/approvefeepayment')->with('savedsuccessmessage','Payment aganist the registration successfully recorded.');
	}
	public function setRecordLock($consultantId){
		$pickerByUserFullName=null;
		$redirectUrl=Input::get('redirectUrl');
		$notification = Input::get('notification');
		if((bool)$notification){
			DB::table('sysapplicationnotification')->where('ApplicationId',$consultantId)->update(array('IsRead'=>1));
		}
		$hasBeenPicked=ConsultantModel::consultantHardList($consultantId)->pluck('SysLockedByUserId');
		if((bool)$hasBeenPicked!=null){
			$pickerByUserFullName=User::where('Id',$hasBeenPicked)->pluck('FullName');
		}else{
			$consultant=ConsultantModel::find($consultantId);
			$consultant->SysLockedByUserId=Auth::user()->Id;
			$consultant->save();
		}
		return Redirect::to($redirectUrl)->with('ApplicationAlreadyPicked',$pickerByUserFullName);
	}
	public function rejectRegistration(){
		DB::beginTransaction();
		try{
			$rejectionCode=str_random(30);
			$consultantId=Input::get('ConsultantReference');
			$consultant = ConsultantModel::find($consultantId);
			$consultant->CmnApplicationRegistrationStatusId=CONST_CMN_APPLICATIONREGISTRATIONSTATUS_REJECTED;
			$consultant->RemarksByRejector=Input::get('RemarksByRejector');
			$consultant->RejectedDate=Input::get('RejectedDate');
			$consultant->SysRejectorUserId=Auth::user()->Id;
			$consultant->SysLockedByUserId=NULL;
			$consultant->SysRejectionCode=$rejectionCode;
			$consultant->save();
		}catch(Exception $e){
			DB::rollback();
        	throw $e;
		}
		DB::commit();
		$consultantDetails=ConsultantModel::consultantHardList(Input::get('ConsultantReference'))->get(array('NameOfFirm','Email','ReferenceNo','ApplicationDate','RemarksByRejector','SysRejectionCode','MobileNo'));
		/*----------------------consultant Email Details and New Details------------------*/
		$recipientAddress=$consultantDetails[0]->Email;
		$recipientName=$consultantDetails[0]->NameOfFirm;
		$applicationNo=$consultantDetails[0]->ReferenceNo;
		$applicationDate=$consultantDetails[0]->ApplicationDate;
		$remarksByRejector=$consultantDetails[0]->RemarksByRejector;
		$rejectionSysCode=$consultantDetails[0]->SysRejectionCode;
		$mobileNo=$consultantDetails[0]->MobileNo;
		$mailView="emails.crps.mailapplicationrejected";
		$subject="Rejection of Your Registration with CDB";
		$mailData=array(
			'prefix'=>'consultant',
			'applicantName'=>$recipientName,
			'applicationNo'=>$applicationNo,
			'applicationDate'=>$applicationDate,
			'remarksByRejector'=>$remarksByRejector,
			'referenceApplicant'=>Input::get('ConsultantReference'),
			'rejectionSysCode'=>$rejectionSysCode,
			'mailMessage'=>"Construction Development Board (CDB) has rejected your application for registration of consultant with CDB. Please read the reason for rejection given below and reapply by making the necessary corrections.",
		);
		$smsMessage="Your application for consultant registration has been rejected. Please check your email ($recipientAddress) to view the reason for rejection.";
		$this->sendEmailMessage($mailView,$mailData,$subject,$recipientAddress,$recipientName);
		$this->sendSms($smsMessage,$mobileNo);
		return Redirect::to('consultant/'.Input::get('RedirectRoute'))->with('savedsuccessmessage','The application has been rejected.');
	}
	public function checkRejectedSecurityCode($consultantReference,$securityCode){
		if(strlen($consultantReference)==36 && strlen($securityCode)==30){
			$checkConsultantReference=ConsultantModel::where('SysRejectionCode',$securityCode)->pluck('Id');
			$currentStatus=ConsultantModel::where('Id',$checkConsultantReference)->pluck('CmnApplicationRegistrationStatusId');
			$rejectedDate=ConsultantModel::where('Id',$checkConsultantReference)->pluck('RejectedDate');
			$rejectedDate=new DateTime($rejectedDate);
			$currentDate=new DateTime(date('Y-m-d'));
			$noOfDays=$rejectedDate->diff($currentDate);
			if($checkConsultantReference==$consultantReference && $currentStatus==CONST_CMN_APPLICATIONREGISTRATIONSTATUS_REJECTED && (int)$noOfDays->d < 31){
				DB::table('crpconsultant')->where('Id',$consultantReference)->update(array('ApplicationDate'=>date('Y-m-d')));
				return Redirect::to('consultant/generalinforegistration/'.$consultantReference.'?editbyapplicant=true&rejectedapplicationreapply=true');	
			}else{
				return Redirect::to('ezhotin/rejectedapplicationmessage');
			}
		}else{
			App::abort('404');
		}
	}
	public function newCommentAdverseRecord($consultantId){
		$consultant=ConsultantFinalModel::consultantHardList($consultantId)->get(array('Id','CDBNo','NameOfFirm'));
		return View::make('crps.consultantnewadverserecordsandcomments')
					->with('consultantId',$consultantId)
					->with('consultant',$consultant);	
	}
	public function editCommentAdverseRecord($consultantId){
		$consultant=ConsultantFinalModel::consultantHardList($consultantId)->get(array('Id','CDBNo','NameOfFirm'));
		$commentsAdverseRecords=ConsultantCommentAdverseRecordModel::commentAdverseRecordList($consultantId)->get(array('Id','Date','Remarks','Type'));
		return View::make('crps.consultanteditadverserecordscomments')
					->with('consultant',$consultant)
					->with('commentsAdverseRecords',$commentsAdverseRecords);
	}
	public function saveCommentAdverseRecord(){
		$postedValues=Input::all();
		$date=$this->convertDate(Input::get('Date'));
		$postedValues['Date']=$date;
		$postedValues['CreatedBy'] = Auth::user()->Id;
		$validation = new ConsultantCommentAdverseRecordModel;
		if(!($validation->validate($postedValues))){
		    $errors = $validation->errors();
		    return Redirect::to('consultant/editdetails/'.$postedValues['CrpConsultantFinalId'].'#commentsadverserecords')->withErrors($errors)->withInput();
		}
		ConsultantCommentAdverseRecordModel::create($postedValues);
		return Redirect::to('consultant/editdetails/'.$postedValues['CrpConsultantFinalId'].'#commentsadverserecords')->with('savedsuccessmessage','Record sucessfully added.');
	}
	public function updateCommentAdverseRecord(){
		$postedValues=Input::all();
		$date=$this->convertDate(Input::get('Date'));
		$postedValues['Date']=$date;
		$instance=ConsultantCommentAdverseRecordModel::find($postedValues['Id']);
		$instance->fill($postedValues);
		$instance->update();
		return Redirect::to('consultant/editdetails/'.$postedValues['CrpConsultantFinalId'].'#commentsadverserecords')->with('savedsuccessmessage','Record has been successfully updated');
	}
	public function blacklistDeregisterList(){
		$reRegistration=1;
		$type=3;
		$parameters=array();
		$consultantId=Input::get('CrpConsultantId');
		$CDBNo=Input::get('CDBNo');
		$tradeLicenseNo = Input::get('TradeLicenseNo');
		$fromDate = Input::get('FromDate');
		$toDate = Input::get('ToDate');
		$query="select T1.Id,T1.ReferenceNo,T1.ApplicationDate,T1.CDBNo,T1.MobileNo,T1.TelephoneNo,T1.Email,T1.NameOfFirm,T2.Name as Country,T3.NameEn as Dzongkhag from crpconsultantfinal T1 join cmncountry T2 on T1.CmnCountryId=T2.Id left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id where 1=1";
		if(Request::path()=="consultant/deregister"){
			$reRegistration=0;
			$type=1;
			$captionHelper="Registered";
			$captionSubject="Deregistration of Consultant";
			$query.=" and T1.CmnApplicationRegistrationStatusId=?";
			array_push($parameters,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED);
		}else if(Request::path()=="consultant/blacklist"){
			$reRegistration=0;
			$type=2;
			$captionHelper="Registered";
			$captionSubject="Blacklisting of Consultant";
			$query.=" and T1.CmnApplicationRegistrationStatusId=?";
			array_push($parameters,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED);
		}else if(Request::path()=="consultant/reregistration"){
			$captionHelper="Deregistered or Blacklisted";
			$captionSubject="Re-registration of Consultant";
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
		if((bool)$consultantId!=NULL || (bool)$CDBNo!=NULL){
			if((bool)$consultantId!=NULL){
				$query.=" and T1.Id=?";
				array_push($parameters,$consultantId);
			}
			if((bool)$CDBNo!=NULL){
				$query.=" and T1.CDBNo=?";
	            array_push($parameters,$CDBNo);
			}
		}
		$consultantLists=DB::select($query." order by T1.CDBNo,ApplicationDate,NameOfFirm".$limit,$parameters);
		return View::make('crps.consultantderegisterationlist')
					->with('CDBNo',$CDBNo)
					->with('type',$type)
					->with('consultantId',$consultantId)
					->with('captionHelper',$captionHelper)
					->with('captionSubject',$captionSubject)
					->with('reRegistration',$reRegistration)
					->with('consultantLists',$consultantLists);
	}
	public function deregisterBlackListRegistration(){
		$postedValues=Input::all();
		$consultantReference=$postedValues['ConsultantReference'];
		$consultantUserId=ConsultantFinalModel::where('Id',$consultantReference)->pluck('SysUserId');
		DB::beginTransaction();
		try{
			if(Input::has('DeRegisteredDate')){
				$postedValues['DeRegisteredDate']=$this->convertDate($postedValues['DeRegisteredDate']);
			}elseif(Input::has('BlacklistedDate')){
				$postedValues['BlacklistedDate']=$this->convertDate($postedValues['BlacklistedDate']);
			}else{
				$postedValues['ReRegistrationDate']=$this->convertDate($postedValues['ReRegistrationDate']);
			}
			$instance=ConsultantFinalModel::find($consultantReference);
			$instance->fill($postedValues);
			$instance->update();
			$userInstance=User::find($consultantUserId);
			if($postedValues['CmnApplicationRegistrationStatusId']==CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED){
				$redirectRoute="reregistration";
				$userInstance=User::find($consultantUserId);
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
				$userInstance=User::find($consultantUserId);
				if((bool)$userInstance){
					$userInstance->Status=0;
					$userInstance->save();
				}
				/*---Insert Adverse Record i.e the remarks if the consultant is deregistered/blacklisted*/
				if(Input::has('BlacklistedRemarks')){
					$consultantAdverserecordInstance = new ConsultantCommentAdverseRecordModel;
					$consultantAdverserecordInstance->CrpConsultantFinalId = $consultantReference;
					$consultantAdverserecordInstance->Date=date('Y-m-d');
					$consultantAdverserecordInstance->Remarks=Input::get('BlacklistedRemarks');
					$consultantAdverserecordInstance->Type=2;
					$consultantAdverserecordInstance->save();
				}else{
					$consultantAdverserecordInstance = new ConsultantCommentAdverseRecordModel;
					$consultantAdverserecordInstance->CrpConsultantFinalId = $consultantReference;
					$consultantAdverserecordInstance->Date=date('Y-m-d');
					$consultantAdverserecordInstance->Remarks=Input::get('DeregisteredRemarks');
					$consultantAdverserecordInstance->Type=2;
					$consultantAdverserecordInstance->save();
				}
			}
		}catch(Exception $e){
			DB::rollback();
        	throw $e;
		}
		DB::commit();
		return Redirect::to('consultant/'.$redirectRoute)
						->with('savedsuccessmessage','Successfully updated');
	}
	public function listOfWorks(){
		$underProcess=0;
		$parameters=array();
		$procuringAgencyId=Input::get('ProcuringAgency');
		$workStartDateFrom=Input::get('WorkStartDateFrom');
		$workStartDateTo=Input::get('WorkStartDateTo');
		$workOrderNo=Input::get('WorkOrderNo');
		$workStatus=Input::get('WorkExecutionStatus');
		$cdbNo=Input::get('CDBNo');
		$query="select T1.Id,T1.NameOfWork,T1.WorkOrderNo,T1.ContractPeriod,T1.WorkStartDate,T1.WorkCompletionDate,T2.Name as ProcuringAgency,T3.Name as ServiceCategory,T4.Name as ServiceName,T4.Code as ServiceCode,T5.Name as WorkExecutionStatus from crpbiddingform T1 left join (crpbiddingformdetail A join crpconsultantfinal B on B.Id = A.CrpConsultantFinalId) on A.CrpBiddingFormId = T1.Id and A.CmnWorkExecutionStatusId = ? join cmnprocuringagency T2 on T1.CmnProcuringAgencyId=T2.Id join cmnconsultantservicecategory T3 on T1.CmnConsultantServiceCategoryId=T3.Id join cmnconsultantservice T4 on T1.CmnConsultantServiceId=T4.Id join cmnlistitem T5 on T1.CmnWorkExecutionStatusId=T5.Id where Type=1";
		array_push($parameters,CONST_CMN_WORKEXECUTIONSTATUS_AWARDED);
		if(Request::path()=="consultant/editcompletedworklist"){
			$query.=" and (T1.CmnWorkExecutionStatusId=? or T1.CmnWorkExecutionStatusId=?)";
			array_push($parameters,CONST_CMN_WORKEXECUTIONSTATUS_COMPLETED);
			array_push($parameters,CONST_CMN_WORKEXECUTIONSTATUS_TERMINATED);
		}elseif(Request::path()=="consultant/worklist" || Request::path()=="consultant/editbiddingformlist"){
			$underProcess=1;
			$query.=" and T1.CmnWorkExecutionStatusId=?";
			array_push($parameters,CONST_CMN_WORKEXECUTIONSTATUS_UNDERPROCESS);
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
			if((bool)$cdbNo){
				$query.=" and B.CDBNo = ?";
				array_push($parameters,$cdbNo);
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
		}
		$listOfWorks=DB::select($query." order by ProcuringAgency,T1.WorkStartDate",$parameters);
		$procuringAgencies=ProcuringAgencyModel::procuringAgencyHardList()->get(array('Id','Name'));
		$workExecutionStatus=CmnListItemModel::workExecutionStatus()->whereRaw('(ReferenceNo=? or ReferenceNo=?)',array(3003,3004))->get(array('Id','Name'));
		return View::make('crps.consultantlistofworks')
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
		$detailsOfCompletedWorks=CrpBiddingFormModel::workCompletionDetails($bidId)->get(array('ContractPriceInitial','ContractPriceFinal','CommencementDateOffcial','CommencementDateFinal','CompletionDateOffcial','CompletionDateFinal','OntimeCompletionScore','QualityOfExecutionScore','CmnWorkExecutionStatusId','Remarks'));
		$redirectRoute='consultant/worklist';
		if(!empty($detailsOfCompletedWorks[0]->OntimeCompletionScore)){
			$redirectRoute='consultant/editcompletedworklist';
		}
		$workExecutionStatus=CmnListItemModel::workExecutionStatus()->whereRaw('(ReferenceNo=? or ReferenceNo=?)',array(3003,3004))->get(array('Id','Name','ReferenceNo'));
		$contractDetails=CrpBiddingFormModel::biddingFormConsultantCdbAll()
								->where('crpbiddingform.Id',$bidId)
//								->where('crpbiddingform.ByCDB',1)
								->get(array('crpbiddingform.Id','crpbiddingform.WorkOrderNo','crpbiddingform.NameOfWork','crpbiddingform.DescriptionOfWork','crpbiddingform.ContractPeriod','crpbiddingform.WorkStartDate','crpbiddingform.WorkCompletionDate','crpbiddingform.ApprovedAgencyEstimate','T1.Name as ProcuringAgency','T2.Name as ServiceCategory','T3.Name as ServiceName','T3.Code as ServiceCode','T4.NameEn as Dzongkhag'));
		$workAwardedConsultant=CrpBiddingFormDetailModel::biddingFormConsultantContractBidders($bidId)
								->where('crpbiddingformdetail.CmnWorkExecutionStatusId',CONST_CMN_WORKEXECUTIONSTATUS_AWARDED)
								->get(array('crpbiddingformdetail.Id','crpbiddingformdetail.BidAmount','crpbiddingformdetail.EvaluatedAmount','T1.NameOfFirm'));
		return View::make('crps.consultantworkcompletionform')
					->with('model',$model)
					->with('redirectRoute',$redirectRoute)
					->with('detailsOfCompletedWorks',$detailsOfCompletedWorks)
					->with('workExecutionStatus',$workExecutionStatus)
					->with('contractDetails',$contractDetails)
					->with('workAwardedConsultants',$workAwardedConsultant);
	}
	public function printDetails($consultantId){
		if(Route::current()->getUri()=="consultant/viewprintdetails/{consultantid}"){
			$data['isfinalprint']=1;
			$view='printpages.consultantviewprintinformation';
			$generalInformation=ConsultantFinalModel::consultant($consultantId)->get(array('crpconsultantfinal.CDBNo','crpconsultantfinal.NameOfFirm','crpconsultantfinal.Address','crpconsultantfinal.Email','crpconsultantfinal.TelephoneNo','crpconsultantfinal.MobileNo','crpconsultantfinal.FaxNo','crpconsultantfinal.CmnApplicationRegistrationStatusId','T1.Name as Country','T2.NameEn as Dzongkhag','T3.Name as CurrentStatus','T4.Name as OwnershipType'));
			$ownerPartnerDetails=ConsultantHumanResourceFinalModel::consultantPartner($consultantId)->get(array('crpconsultanthumanresourcefinal.CIDNo','crpconsultanthumanresourcefinal.Name','crpconsultanthumanresourcefinal.Sex','crpconsultanthumanresourcefinal.JoiningDate','crpconsultanthumanresourcefinal.ShowInCertificate','T1.Name as Country','T2.Name as Salutation','T3.Name as Designation'));
			$serviceClassifications=ConsultantWorkClassificationFinalModel::services($consultantId)->select(DB::raw("T1.Name as Category,group_concat(T2.Code order by T2.Code separator ',') as AppliedService,group_concat(T3.Code order by T3.Code separator ',') as VerifiedService,group_concat(T4.Code order by T4.Code separator ',') as ApprovedService"))->get();
			$consultantHumanResources=ConsultantHumanResourceFinalModel::consultantHumanResource($consultantId)->get(array('crpconsultanthumanresourcefinal.Name','crpconsultanthumanresourcefinal.CIDNo','crpconsultanthumanresourcefinal.Sex','crpconsultanthumanresourcefinal.JoiningDate','crpconsultanthumanresourcefinal.Name','T1.Name as Salutation','T2.Name as Qualification','T3.Name as Trade','T4.Name as Designation','T5.Name as Country'));
			$consultantEquipments=ConsultantEquipmentFinalModel::consultantEquipment($consultantId)->get(array('crpconsultantequipmentfinal.RegistrationNo','crpconsultantequipmentfinal.ModelNo','crpconsultantequipmentfinal.Quantity','T1.Name'));
			$consultantTrackrecords=CrpBiddingFormModel::consultantTrackRecords($consultantId)->get(array('crpbiddingform.WorkOrderNo','crpbiddingform.NameOfWork','crpbiddingform.DescriptionOfWork','crpbiddingform.ContractPeriod','crpbiddingform.WorkStartDate','crpbiddingform.WorkCompletionDate','T2.Name as ProcuringAgency','T3.Name as ServiceCategory','T4.Code as ServiceName','T5.NameEn as Dzongkhag'));
			$commentsAdverseRecords=ConsultantCommentAdverseRecordModel::commentAdverseRecordList($consultantId)->get(array('Id','Date','Remarks','Type'));
			$data['consultantTrackrecords']=$consultantTrackrecords;
			$data['commentsAdverseRecords']=$commentsAdverseRecords;
		}else{
			$data['isfinalprint']=0;
			$feeStructures=CrpService::serviceDetails(CONST_SERVICETYPE_NEW)->take(1)->get(array('ConsultantAmount','ConsultantValidity'));
			$serviceCategories=ConsultantServiceCategoryModel::category()->get(array('Id','Name'));
			$appliedCategoryServices=DB::select("select T1.Id as ServiceId,T1.Code as ServiceCode,T1.Name as ServiceName,T1.CmnConsultantServiceCategoryId,T2.Id,T2.CmnAppliedServiceId from cmnconsultantservicecategory X join cmnconsultantservice T1 on X.Id=T1.CmnConsultantServiceCategoryId join crpconsultantworkclassification T2 on T1.Id=T2.CmnAppliedServiceId where T2.CrpConsultantId=? order by T1.Code",array($consultantId));
			$view="printpages.consultantprintregistrationinformation";
			$generalInformation=ConsultantModel::consultant($consultantId)->get(array('crpconsultant.ReferenceNo','crpconsultant.ApplicationDate','crpconsultant.NameOfFirm','crpconsultant.Address','crpconsultant.Email','crpconsultant.TelephoneNo','crpconsultant.MobileNo','crpconsultant.FaxNo','crpconsultant.CmnApplicationRegistrationStatusId','T1.Name as Country','T2.NameEn as Dzongkhag','T3.Name as CurrentStatus','T7.Name as OwnershipType','T7.ReferenceNo as OwnershipTypeReferenceNo'));
			$ownerPartnerDetails=ConsultantHumanResourceModel::consultantPartner($consultantId)->get(array('crpconsultanthumanresource.CIDNo','crpconsultanthumanresource.Name','crpconsultanthumanresource.Sex','crpconsultanthumanresource.ShowInCertificate','T1.Name as Country','T2.Name as Salutation','T3.Name as Designation'));
			$serviceClassifications=DB::select("select T1.Name as Category,group_concat(concat(T2.Code,' -',T2.Name) order by T2.Code separator '<br />') as AppliedService from cmnconsultantservicecategory T1 join cmnconsultantservice T2 on T1.Id=T2.CmnConsultantServiceCategoryId join crpconsultantworkclassification T3 on T2.Id=T3.CmnAppliedServiceId where T3.CrpConsultantId=? group by T1.Id",array($consultantId));
			$consultantHumanResources=ConsultantHumanResourceModel::consultantHumanResource($consultantId)->get(array('crpconsultanthumanresource.Name','crpconsultanthumanresource.CIDNo','crpconsultanthumanresource.JoiningDate','crpconsultanthumanresource.Sex','crpconsultanthumanresource.Name','crpconsultanthumanresource.Verified','crpconsultanthumanresource.Approved','T1.Name as Salutation','T2.Name as Qualification','T3.Name as Trade','T4.Name as Designation','T5.Name as Country'));
			$consultantEquipments=ConsultantEquipmentModel::consultantEquipment($consultantId)->get(array('crpconsultantequipment.RegistrationNo','crpconsultantequipment.ModelNo','crpconsultantequipment.Quantity','crpconsultantequipment.Verified','crpconsultantequipment.Approved','T1.Name'));
			$attachments=ConsultantAttachmentModel::attachment($consultantId)->get(array('DocumentName'));
			$data['attachments']=$attachments;
			$data['feeStructures']=$feeStructures;
			$data['serviceCategories']=$serviceCategories;
			$data['appliedCategoryServices']=$appliedCategoryServices;
		}
		$data['printTitle']='Consultant Information';
		$data['generalInformation']=$generalInformation;
		$data['ownerPartnerDetails']=$ownerPartnerDetails;
		$data['serviceClassifications']=$serviceClassifications;
		$data['consultantHumanResources']=$consultantHumanResources;
		$data['consultantEquipments']=$consultantEquipments;
		$pdf = App::make('dompdf');
		$pdf->loadView($view,$data)->setPaper('a4')->setOrientation('potrait');
		return $pdf->stream();
	}
	public function checkCDBNo(){
		$inputCDBNo=Input::get('inputCDBNo');
		$cdbNoFinalCount=ConsultantFinalModel::consultantHardListAll()->where('CDBNo',$inputCDBNo)->count();
		$cdbNoCount=ConsultantModel::consultantHardListAll()->where('CDBNo',$inputCDBNo)->whereIn('CmnApplicationRegistrationStatusId',array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVEDFORPAYMENT,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_VERIFIED))->count();
		if((int)$cdbNoFinalCount>0 || (int)$cdbNoCount>0){
			return 0;
		}
		return 1;
	}
    public function postFetchConsultantOnCDBNo(){
        $cdbNo = Input::get('cdbno');
        $consultant = DB::select('select Id, NameOfFirm, case CmnApplicationRegistrationStatusId when ? or ? then 1 else 0 end as Status from crpconsultantfinal where CDBNo = ?',array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_BLACKLISTED,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_DEREGISTERED,$cdbNo));
        return Response::json($consultant);
    }
    public function postBlackListedConsultant(){
        $cdbNo = Input::get('cdbNo');
        $consultant = DB::table('viewlistofconsultants')
                        ->where('CDBNo',$cdbNo)
                        ->get(array('NameOfFirm','Status'));
        $consultantName = $consultant[0]->NameOfFirm;
        $status = $consultant[0]->Status;
        $message = "This consultant $consultantName (CDB No. $cdbNo) is $status";
        return View::make('report.message')
                    ->with('cdbNo',$cdbNo)
                    ->with('message',$message);
    }
    public function fetchConsultantsJSON(){
        $term = Input::get('term');
        $consultants = DB::table('crpconsultantfinal')->where(DB::raw('TRIM(NameOfFirm)'),DB::raw('like'),"$term%")->get(array('Id',DB::raw('TRIM(NameOfFirm) as NameOfFirm')));
        $consultantsJSON = array();
        foreach($consultants as $consultant){
            array_push($consultantsJSON,array('id'=>$consultant->Id,'value'=>trim($consultant->NameOfFirm)));
        }
        return Response::json($consultantsJSON);
    }
    public function deleteCommentAdverseRecord(){
    	$id = Input::get('id');
    	try{
    		DB::table('crpconsultantcommentsadverserecord')->where('Id',$id)->delete();	
    		return 1;
    	}catch(Exception $e){
    		return 0;
    	}
    }
	public function saveFinalRemarks(){
		DB::beginTransaction();
		$redirectRoute = 'consultant/viewapprovedapplications';
		try{
			if(Input::has('IsServiceApplication')){
				$redirectRoute = 'consultant/viewserviceapplication';
				$object = ConsultantModel::find(Input::get('ConsultantReference'));
			}else{
				$redirectRoute = 'consultant/viewapprovedapplications';
				$object = ConsultantFinalModel::find(Input::get('ConsultantReference'));
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