<?php
class ListAndReportController extends BaseController{
	public function listOfContractors(){
		$contractorListAll=ContractorFinalModel::contractorHardListAll()->get(array('Id','NameOfFirm'));
		$contractorCategoryId = ContractorWorkCategoryModel::contractorProjectCategory()->orderBy('Code')->get(array("Id","Code","Name"));
		$contractorClassificationId = ContractorClassificationModel::classification()->orderBy('Priority')->get(array('Id','Code','Name'));
		$dzongkhagId = DzongkhagModel::dzongkhag()->orderBy('NameEn')->get(array('Id','NameEn'));
		$parameters=array();
		$cdbNo=Input::get('CdbRegistrationNo');
		$contractorId=Input::get('ContractorId');
		$dzongkhags = Input::get('CmnDzongkhagId');
		$contractorCategories = Input::get('CmnContractorCategoryId');
		$contractorClassifications = Input::get('CmnContractorClassificationId');
        $type = Input::get('Type');
		$contractorLists = array();
		$limit=Input::get('Limit');
        $fromDate = Input::get('FromDate');
        $toDate = Input::get('ToDate');
        $proprietor = Input::get('Proprietor');

        $query = "select distinct T1.CDBNo, T1.NameOfFirm, T1.ExpiryDate, T1.Address, T1.Status, T1.InitialDate, T1.Dzongkhag, T1.Country, T1.TelephoneNo, T1.MobileNo, T1.Classification1, T1.Classification2, T1.Classification3, T1.Classification4 from viewlistofcontractors T1 left join crpcontractorhumanresourcefinal T2 on T2.CrpContractorFinalId = T1.Id and T2.IsPartnerOrOwner = 1 Where CmnApplicationRegistrationStatusId <>?";
        array_push($parameters,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_DEREGISTERED);
        if((bool)$cdbNo){
            $query.=" and CDBNo=?";
            array_push($parameters,$cdbNo);
        }
        if((bool)$contractorId){
            $contractorId="%$contractorId%";
            $query.=" and NameOfFirm like ?";
            array_push($parameters,$contractorId);
        }
        if((bool)$proprietor){
            $proprietor = "%$proprietor%";
            $query.=" and (T2.CIDNo like ? or T2.Name like ?')";
            array_push($parameters,$proprietor);
        }
        if((bool)$dzongkhags){
            $query.=" and CmnDzongkhagId=?";
            array_push($parameters,$dzongkhags);
        }
        if((bool)$contractorClassifications && (bool)$contractorCategories){
            if($contractorCategories == CONST_CATEGORY_W1){
                $query.=" and CategoryId1 = ? and ClassId1 = ?";
                array_push($parameters,$contractorCategories);
                array_push($parameters,$contractorClassifications);
            }elseif($contractorCategories == CONST_CATEGORY_W2){
                $query.=" and CategoryId2 = ? and ClassId2 = ?";
                array_push($parameters,$contractorCategories);
                array_push($parameters,$contractorClassifications);
            }elseif($contractorCategories == CONST_CATEGORY_W3){
                $query.=" and CategoryId3 = ? and ClassId3 = ?";
                array_push($parameters,$contractorCategories);
                array_push($parameters,$contractorClassifications);
            }else{
                $query.=" and CategoryId4 = ? and ClassId4 = ?";
                array_push($parameters,$contractorCategories);
                array_push($parameters,$contractorClassifications);
            }

        }else{
            if((bool)$contractorClassifications){
                $query.=" and (ClassId1 = ? or ClassId2 = ? or ClassId3 = ? or ClassId4 = ?)";
                for($i = 0; $i<4; $i++){
                    array_push($parameters,$contractorClassifications);
                }
            }
            if((bool)$contractorCategories){
                $query.=" and (CategoryId1 = ? or CategoryId2 = ? or CategoryId3 = ? or CategoryId4 = ?)";
                for($i = 0; $i<4; $i++){
                    array_push($parameters,$contractorCategories);
                }
            }
        }

        if((bool)$fromDate){
            $query.=" and InitialDate >= ?";
            array_push($parameters,date_format(date_create($fromDate),'Y-m-d'));
        }
        if((bool)$toDate){
            $query.=" and InitialDate <= ?";
            array_push($parameters,date_format(date_create($toDate),'Y-m-d'));
        }
        if((bool)$type){
            if($type != 1){
                if($type == 2){
                    $query.=" and Country = ?";
                    $query.=" and Dzongkhag is not null";
                }else{
                    $query.=" and Country <> ?";
                }
                array_push($parameters,"Bhutan");
            }
        }
        if((bool)$limit){
            if($limit != 'All'){
                if(is_numeric($limit)){
                    $limit=" limit $limit";
                }else{
                    $limit=" limit 20";
                }
            }else{
                $limit="";
            }
        }else{
            $limit=" limit 20";
        }
		$contractorLists=DB::select($query." and (ClassId1 is not null or ClassId2 is not null or ClassId3 is not null or ClassId4 is not null) order by Country,CDBNo,NameOfFirm".$limit,$parameters);
		return View::make('website.listofcontractors')
                    ->with('type',1)
					->with('contractorListAll',$contractorListAll)
					->with('contractorId',$contractorId)
					->with('contractorLists',$contractorLists)
					->with('dzongkhagId',$dzongkhagId)
					->with('contractorCategoryId',$contractorCategoryId)
					->with('contractorClassificationId',$contractorClassificationId);
	}
	public function listOfContractorsRevoked(){
        $contractorListAll=ContractorFinalModel::contractorHardListAll()->get(array('Id','NameOfFirm'));
        $contractorCategoryId = ContractorWorkCategoryModel::contractorProjectCategory()->orderBy('Code')->get(array("Id","Code","Name"));
        $contractorClassificationId = ContractorClassificationModel::classification()->orderBy('Priority')->get(array('Id','Code','Name'));
        $dzongkhagId = DzongkhagModel::dzongkhag()->orderBy('NameEn')->get(array('Id','NameEn'));
        $parameters=array();
        $cdbNo=Input::get('CdbRegistrationNo');
        $contractorId=Input::get('ContractorId');
        $dzongkhags = Input::get('CmnDzongkhagId');
        $contractorCategories = Input::get('CmnContractorCategoryId');
        $contractorClassifications = Input::get('CmnContractorClassificationId');
        $contractorLists = array();
        $limit=Input::get('Limit');
        $fromDate = Input::get('FromDate');
        $toDate = Input::get('ToDate');

        $query = "select CDBNo, NameOfFirm, Address,Status,InitialDate, ExpiryDate, Dzongkhag, Country, TelephoneNo, MobileNo, Classification1, Classification2, Classification3, Classification4 from viewlistofcontractors Where CmnApplicationRegistrationStatusId in (?,?,?,?)";
        array_push($parameters,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_REVOKED);
        array_push($parameters,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_SUSPENDED);
        array_push($parameters,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_DEBARRED);
        array_push($parameters,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_SURRENDERED);
        if((bool)$cdbNo){
            $query.=" and CDBNo=?";
            array_push($parameters,$cdbNo);
        }
        if((bool)$contractorId){
            $contractorId="%$contractorId%";
            $query.=" and NameOfFirm like ?";
            array_push($parameters,$contractorId);
        }
        if((bool)$dzongkhags){
            $query.=" and CmnDzongkhagId=?";
            array_push($parameters,$dzongkhags);
        }
        if((bool)$contractorClassifications){
            $query.=" and (ClassId1 = ? or ClassId2 = ? or ClassId3 = ? or ClassId4 = ?)";
            for($i = 0; $i<4; $i++){
                array_push($parameters,$contractorClassifications);
            }
        }
        if((bool)$contractorCategories){
            $query.=" and (CategoryId1 = ? or CategoryId2 = ? or CategoryId3 = ? or CategoryId4 = ?)";
            for($i = 0; $i<4; $i++){
                array_push($parameters,$contractorCategories);
            }
        }
        if((bool)$fromDate){
            $query.=" and ExpiryDate >= ?";
            array_push($parameters,date_format(date_create($fromDate),'Y-m-d'));
        }
        if((bool)$toDate){
            $query.=" and ExpiryDate <= ?";
            array_push($parameters,date_format(date_create($toDate),'Y-m-d'));
        }
        if((bool)$limit){
            if($limit != 'All'){
                if(is_numeric($limit)){
                    $limit=" limit $limit";
                }else{
                    $limit=" limit 20";
                }
            }else{
                $limit="";
            }

        }else{
            $limit=" limit 20";
        }
        $contractorLists=DB::select($query." order by CDBNo,NameOfFirm".$limit,$parameters);
        return View::make('website.listofcontractors')
            ->with('type',2)
            ->with('contractorListAll',$contractorListAll)
            ->with('contractorId',$contractorId)
            ->with('contractorLists',$contractorLists)
            ->with('dzongkhagId',$dzongkhagId)
            ->with('contractorCategoryId',$contractorCategoryId)
            ->with('contractorClassificationId',$contractorClassificationId);
	}
	public function viewContractorDetails($id){
		$generalInformation=ContractorFinalModel::contractor($id)->get(array('crpcontractorfinal.Id','crpcontractorfinal.CDBNo','crpcontractorfinal.NameOfFirm','crpcontractorfinal.Address','crpcontractorfinal.Email','crpcontractorfinal.TelephoneNo','crpcontractorfinal.MobileNo','crpcontractorfinal.FaxNo','crpcontractorfinal.CmnApplicationRegistrationStatusId','T1.Name as Country','T2.NameEn as Dzongkhag','T3.Name as CurrentStatus','T4.Name as OwnershipType'));
		$ownerPartnerDetails=ContractorHumanResourceFinalModel::contractorPartner($id)->get(array('crpcontractorhumanresourcefinal.Id','crpcontractorhumanresourcefinal.CIDNo','crpcontractorhumanresourcefinal.Name','crpcontractorhumanresourcefinal.Sex','crpcontractorhumanresourcefinal.ShowInCertificate','T1.Name as Country','T2.Name as Salutation','T3.Name as Designation'));
		$contractorWorkClassifications=ContractorWorkClassificationFinalModel::contractorWorkClassification($id)->select(DB::raw('crpcontractorworkclassificationfinal.Id,T1.Code,T1.Name as Category,coalesce(T1.ReferenceNo,99999999) as CategoryReferenceNo,T2.Name as AppliedClassification,T3.Name as VerifiedClassification,T4.Name as ApprovedClassification'))->get();
		return View::make('website.viewcontractordetails')
						->with('generalInformation',$generalInformation)
						->with('ownerPartnerDetails',$ownerPartnerDetails)
						->with('contractorWorkClassifications',$contractorWorkClassifications);
	}
	public function listOfArchitects(){
		$dzongkhagId = DzongkhagModel::dzongkhag() -> orderBy('NameEn')->get(array('Id','NameEn'));
		$parameters=array();
		$architectDzongkhagId=Input::get('CmnDzongkhagId');
		$ARNo=Input::get('ARNo');
		$architectType=Input::get('ArchitectType');
		$architectName=Input::get('ArchitectName');
		$architectLists = array();
		$limit=Input::get('Limit');
		if((bool)$limit){
            if($limit != 'All'){
                if(is_numeric($limit)){
                    $limit=" limit $limit";
                }else{
                    $limit=" limit 20";
                }
            }else{
            	$limit="";
            }
        }else{
            $limit=" limit 20";
        }
        $query="select T1.Id,T1.ARNo,T1.ReferenceNo,T1.ApplicationDate,T1.CIDNo,T1.Name as ArchitectName,T1.EmployerName,T1.Email,T1.MobileNo,T2.Name as Country,T3.NameEn as Dzongkhag,T4.Name as Salutation,T5.Id as ArchitectTypeId,T5.Name as ArchitectType from crparchitectfinal T1 join cmncountry T2 on T1.CmnCountryId=T2.Id join cmnlistitem T4 on T1.CmnSalutationId=T4.Id join cmnlistitem T5 on T1.CmnServiceSectorTypeId=T5.Id left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id where 1=1";
		if((bool)$architectDzongkhagId!=NULL || (bool)$ARNo!=NULL || (bool)$architectType!=NULL || (bool)$architectName!=NULL){
			if((bool)$architectName!=NULL){
				$architectName="%$architectName%";
				$query.=" and T1.Name like ?";
				array_push($parameters,$architectName);
			}
			if((bool)$architectDzongkhagId){
				$query.=" and T1.CmnDzongkhagId=?";
				array_push($parameters,$architectDzongkhagId);
			}
			if((bool)$ARNo){
				$query.=" and T1.ARNo=?";
	            array_push($parameters,$ARNo);
			}
			if((bool)$architectType){
				$query.=" and T1.CmnServiceSectorTypeId=?";
	            array_push($parameters,$architectType);
			}
		}
		$architectLists=DB::select($query." order by ARNo,ArchitectName".$limit,$parameters);
		$architectServiceSectorTypes=CmnListItemModel::serviceSectorType()->get(array('Id','Name'));
		return View::make('website.listofarchitects')
					->with('ARNo',$ARNo)
					->with('architectType',$architectType)
					->with('architectServiceSectorTypes',$architectServiceSectorTypes)
					->with('architectLists',$architectLists)
					->with('dzongkhagId',$dzongkhagId);
	}
	public function viewArchitectDetails($id){
		$architectInformations=ArchitectFinalModel::architect($id)->get(array('crparchitectfinal.Id','crparchitectfinal.CIDNo','crparchitectfinal.Name','crparchitectfinal.Gewog','crparchitectfinal.Village','crparchitectfinal.Email','crparchitectfinal.MobileNo','crparchitectfinal.EmployerName','crparchitectfinal.EmployerAddress','crparchitectfinal.GraduationYear','crparchitectfinal.NameOfUniversity','T2.Name as Salutation'));
		return View::make('website.viewarchitectdetails')
					->with('architectInformations',$architectInformations);
	}
	public function listOfConsultants(){
		$parameters=array();
        $consultantName=Input::get('ConsultantName');
		$CDBNo=Input::get('CDBNo');
		$consultantLists = array();
		$limit=Input::get('Limit');
        $query = "select distinct CDBNo,ExpiryDate,NameOfFirm,Country,ExpiryDate,Address,TelephoneNo,MobileNo,Email from viewlistofconsultants where Status = ?";
        array_push($parameters,"Approved");
        $type = Input::get('Type');
        if((bool)$type){
            if($type != 1){
                if($type == 2){
                    $query.=" and Country = ?";
                }else{
                    $query.=" and Country <> ?";
                }
                array_push($parameters,"Bhutan");
            }
        }
		if((bool)$limit){
            if($limit != 'All'){
                if(is_numeric($limit)){
                    $limit=" limit $limit";
                }else{
                    $limit=" limit 20";
                }
            }else{
            	$limit="";
            }
        }else{
            $limit=" limit 20";
        }

        if((bool)$consultantName){
		    $consultantName = "%$consultantName%";
            $query.=" and NameOfFirm LIKE ?";
            array_push($parameters,$consultantName);
        }
        if((bool)$CDBNo){
            $query.=" and CDBNo=?";
            array_push($parameters,$CDBNo);
        }
		$consultantLists=DB::select($query." order by CDBNo,NameOfFirm".$limit,$parameters);
		return View::make('website.listofconsultants')
					->with('consultantLists',$consultantLists);
	}

	public function viewConsultantDetails($id){
		$consultantId=$id;
		$serviceClassifications=ConsultantWorkClassificationFinalModel::services($consultantId)->select(DB::raw("T1.Name as Category,group_concat(T2.Code order by T2.Code separator ',') as AppliedService,group_concat(T3.Code order by T3.Code separator ',') as VerifiedService,group_concat(T4.Code order by T4.Code separator ',') as ApprovedService"))->get();
		$generalInformation=ConsultantModel::consultant($consultantId)->get(array('crpconsultant.Id','crpconsultant.ReferenceNo','crpconsultant.CDBNo','crpconsultant.ApplicationDate','crpconsultant.NameOfFirm','crpconsultant.Address','crpconsultant.Email','crpconsultant.TelephoneNo','crpconsultant.MobileNo','crpconsultant.FaxNo','crpconsultant.CmnApplicationRegistrationStatusId','crpconsultant.VerifiedDate','crpconsultant.RemarksByVerifier','crpconsultant.RemarksByApprover','crpconsultant.RegistrationApprovedDate','crpconsultant.RemarksByPaymentApprover','crpconsultant.RegistrationPaymentApprovedDate','T1.Name as Country','T2.NameEn as Dzongkhag','T7.Name as OwnershipType'));
		$ownerPartnerDetails=ConsultantHumanResourceModel::consultantPartner($consultantId)->get(array('crpconsultanthumanresource.Id','crpconsultanthumanresource.CIDNo','crpconsultanthumanresource.Name','crpconsultanthumanresource.Sex','crpconsultanthumanresource.ShowInCertificate','T1.Name as Country','T2.Name as Salutation','T3.Name as Designation'));
		return View::make('website.viewconsultantdetails')
					->with('serviceClassifications',$serviceClassifications)
					->with('generalInformation',$generalInformation)
					->with('ownerPartnerDetails',$ownerPartnerDetails);
	}
	public function specializedTradeList(){
		$parameters=array();
		$specializedTradeName=Input::get('SpecializedTradeName');
		$SPNo=Input::get('SPNo');
		$limit=Input::get('Limit');
		if((bool)$limit){
            if($limit != 'All'){
                if(is_numeric($limit)){
                    $limit=" limit $limit";
                }else{
                    $limit=" limit 20";
                }
            }else{
            	$limit="";
            }
        }else{
            $limit=" limit 20";
        }
		$query="select T1.Id,T1.CIDNo,T1.Name as SpecializedTradeName,T1.EmployerName,T1.Email,T1.SPNo,T2.NameEn as Dzongkhag,T3.Name as Salutation from crpspecializedtradefinal T1 left join cmndzongkhag T2 on T1.CmnDzongkhagId=T2.Id join cmnlistitem T3 on T1.CmnSalutationId=T3.Id where 1=1";
		if((bool)$specializedTradeName!=NULL || (bool)$SPNo!=NULL){
			if((bool)$specializedTradeName!=NULL){
				$specializedTradeName="%$specializedTradeName%";
				$query.=" and T1.Name like ?";
				array_push($parameters,$specializedTradeName);
			}
			if((bool)$SPNo!=NULL){
				$query.=" and T1.SPNo=?";
	            array_push($parameters,$SPNo);
			}
		}
		$specializedTradeLists=DB::select($query." order by SPNo,SpecializedTradeName".$limit,$parameters);
		return View::make('website.specializedtradelist')
					->with('specializedTradeLists',$specializedTradeLists);
	}

	public function specializedTradeDetails($specializedTradeId){
		$specializedTradeInformations=SpecializedTradeFinalModel::specializedTrade($specializedTradeId)->get(array('crpspecializedtradefinal.Id','crpspecializedtradefinal.ReferenceNo','crpspecializedtradefinal.ApplicationDate','crpspecializedtradefinal.SPNo','crpspecializedtradefinal.CIDNo','crpspecializedtradefinal.Name','crpspecializedtradefinal.Gewog','crpspecializedtradefinal.Village','crpspecializedtradefinal.Email','crpspecializedtradefinal.MobileNo','crpspecializedtradefinal.EmployerName','crpspecializedtradefinal.EmployerAddress','crpspecializedtradefinal.TelephoneNo','T1.Name as Salutation','T2.NameEn as Dzongkhag','T3.Name as CurrentStatus'));
		$workClasssifications=DB::select("select T1.Code,T1.Name,T2.CmnApprovedCategoryId from cmnspecializedtradecategory T1 left join crpspecializedtradeworkclassificationfinal T2 on T1.Id=T2.CmnApprovedCategoryId and T2.CrpSpecializedTradeFinalId=? order by T1.Code,T1.Name",array($specializedTradeId));
		return View::make('website.viewspecializedtradesdetails')
					->with('specializedTradeInformations',$specializedTradeInformations)
					->with('workClasssifications',$workClasssifications);
	}

    public function listOfArbitrators(){
        $arbitrators = DB::table("webarbitrators")->orderBy('RegNo')->whereRaw("coalesce(IsDeleted,0)=0")->get(array('RegNo',"Name","Designation","Email","ContactNo","CasesInHand","FilePath"));
        return View::make('website.listofarbitrators')
                    ->with('arbitrators',$arbitrators);
    }

	public function listOfEngineers(){
		$parameters=array();
		$serviceSectorTypeId=Input::get('CmnServiceSectorTypeId');
		$tradeId=Input::get('CmnTradeId');
		$engineerName=Input::get('EngineerName');
		$engineerLists = array();
		$limit=Input::get('Limit');
		if((bool)$limit){
            if($limit != 'All'){
                if(is_numeric($limit)){
                    $limit=" limit $limit";
                }else{
                    $limit=" limit 20";
                }
            }else{
            	$limit="";
            }
        }else{
            $limit=" limit 20";
        }
		$query="select T1.Id,T1.ReferenceNo,T1.ApplicationDate,T1.CIDNo,T1.Name as EngineerName,T1.EmployerName,T1.Email,T1.MobileNo,T2.Name as Country,T3.NameEn as Dzongkhag,T4.Name as Salutation,T5.Name as EngineerType,T6.Name as Trade from crpengineerfinal T1 join cmncountry T2 on T1.CmnCountryId=T2.Id left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id join cmnlistitem T4 on T1.CmnSalutationId=T4.Id join cmnlistitem T5 on T1.CmnServiceSectorTypeId=T5.Id join cmnlistitem T6 on T1.CmnTradeId=T6.Id where 1=1";
		if((bool)$serviceSectorTypeId!=NULL || (bool)$tradeId!=NULL || (bool)$engineerName!=NULL){
			if((bool)$serviceSectorTypeId){
				$query.=" and T1.CmnServiceSectorTypeId=?";
				array_push($parameters,$serviceSectorTypeId);
			}
			if((bool)$tradeId){
				$query.=" and T1.CmnTradeId=?";
				array_push($parameters,$tradeId);
			}
			if((bool)$engineerName){
				$engineerName="%$engineerName%";
				$query.=" and T1.Name like ?";
				array_push($parameters,$engineerName);
			}
		}
		$engineerLists=DB::select($query." order by EngineerName".$limit,$parameters);
		$serviceSectorTypes=CmnListItemModel::serviceSectorType()->get(array('Id','Name'));
		$trades=CmnListItemModel::trade()->whereIn('ReferenceNo', array(4001,4002,4003))->get(array('Id','Name'));
		return View::make('website.engineerlist')
					->with('serviceSectorTypes',$serviceSectorTypes)
					->with('trades',$trades)
					->with('engineerLists',$engineerLists);
	}

	public function viewEngineerDetails($engineerId){
		$engineerInformations=EngineerFinalModel::engineer($engineerId)->get(array('crpengineerfinal.Id','crpengineerfinal.CIDNo','crpengineerfinal.Name','crpengineerfinal.Gewog','crpengineerfinal.Village','crpengineerfinal.Email','crpengineerfinal.MobileNo','crpengineerfinal.EmployerName','crpengineerfinal.EmployerAddress','crpengineerfinal.GraduationYear','crpengineerfinal.NameOfUniversity','T1.Name as EngineerType','T2.Name as Salutation','T3.Name as Country','T4.NameEn as Dzongkhag','T5.Name as Qualification','T6.Name as UniversityCountry','T8.Name as Trade'));
		return View::make('website.viewengineerdetails')
					->with('engineerInformations',$engineerInformations);
	}
    public function getApt(){
        return View::make('website.apt');
    }
}