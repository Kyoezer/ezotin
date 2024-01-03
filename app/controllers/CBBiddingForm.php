<?php

class CBBiddingForm extends CBController {

	protected $layout = 'horizontalmenumaster';
	public function biddingReportOptions(){
		$listOfUploadeTenders=DB::select("select T1.Id, T1.ReferenceNo, T1.CmnWorkExecutionStatusId, T1.DateOfClosingSaleOfTender, T1.LastDateAndTimeOfSubmission, T1.TenderOpeningDateAndTime, T1.ProjectEstimateCost, T2.Code as Category, T3.Code as Classification, T1.NameOfWork, T1.ContractPeriod, concat(A.Code,'/',DATE_FORMAT(T1.DateOfSaleOfTender,'%Y'),'/',coalesce(T1.WorkId,'')) as EtlTenderWorkId from etltender T1 join cmncontractorworkcategory T2 on T1.CmnContractorCategoryId = T2.Id join cmncontractorclassification T3 on T1.CmnContractorClassificationId = T3.Id join (cmnprocuringagency A join sysuser B on A.Id = B.CmnProcuringAgencyId) on A.Id = T1.CmnProcuringAgencyId left join cbbiddingform X on T1.Id=X.EtlTenderId where B.Id = ? and T1.TenderSource = 2 and T1.CmnWorkExecutionStatusId is null and coalesce(X.CmnWorkExecutionStatusId,0) <> ? and coalesce(X.CmnWorkExecutionStatusId,0) <> ? order by T1.DateOfClosingSaleOfTender DESC",array(Auth::user()->Id, CONST_CMN_WORKEXECUTIONSTATUS_COMPLETED,CONST_CMN_WORKEXECUTIONSTATUS_AWARDED));
		return View::make('biddingformoptions')
					->with('listOfUploadeTenders',$listOfUploadeTenders);
	} 
	public function biddingReport($tenderId=null){
		$showProcuringAgency=0;
		$type=0;
		$bidId=Input::get('bidreference');
		$model='all/cbbiddingform';
		$redirectUrl='cb/editbiddingformlist';
		$dzongkhags=DzongkhagModel::dzongkhag()->get(array('Id','NameEn'));
		$contractors=ContractorFinalModel::contractorHardListAll()->where('CmnApplicationRegistrationStatusId',CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED)->get(array('Id','NameOfFirm','CDBNo'));
		$contractWorkDetails=array(new CBBiddingformModel());
		$contractBidders=array(new CBBiddingFormDetailModel());
		$designations = CmnListItemModel::designation()->get(array('Id','Name'));
		$qualifications = CmnListItemModel::qualification()->get(array('Id','Name'));
		$equipments = CmnEquipmentModel::equipment()->get(array('Id','Name','IsRegistered','VehicleType'));
		$bidEquipments = array(new CBBiddingFormEquipmentModel());
		$bidHRs = array(new CBBiddingFormHRModel());
		

		if((bool)$bidId!=NULL){
		    $contractWorkDetails=CBBiddingformModel::workCompletionDetails($bidId)->get();
		
			if(Request::path()=="cb/biddingform"){
				
				$contractBidders=CBBiddingFormDetailModel::biddingFormCertifiedBuilderContractBidders($bidId)->get(array('cbbiddingformdetail.Id','cbbiddingformdetail.BidAmount','cbbiddingformdetail.EvaluatedAmount','cbbiddingformdetail.CrpCertifiedBuilderId','cbbiddingformdetail.CmnWorkExecutionStatusId','T1.CDBNo','T1.NameOfFirm'));
				
				if(!isset($contractBidders[0])){
					$contractBidders=array(new CertifiedbuilderFinalModel());
				}
			
				$bidEquipments = CBBiddingFormEquipmentModel::where('CrpBiddingFormId',$bidId)->get(array('Id','CrpBiddingFormId','CmnEquipmentId','RegistrationNo','OwnedOrHired'));
				if(count($bidEquipments)==0){
					$bidEquipments = array(new CBBiddingFormEquipmentModel());
				}
				$bidHRs = CBBiddingFormHRModel::where('CrpBiddingFormId',$bidId)->get(array('Id','CrpBiddingFormId','CIDNo','Name','CmnDesignationId','CmnQualificationId'));
				if(count($bidHRs)==0){
					$bidHRs = array(new CBBiddingFormHRModel());
				}
			}
		}else{
			$contractWorkDetails=array(new CBBiddingformModel());
			$contractBidders=array(new CertifiedbuilderFinalModel());
		
		}
		
		$this->layout->content = View::make('crps.cbcmnbiddingform')
								->with('isCb',true)
								->with('bidEquipments',$bidEquipments)
								->with('bidHRs',$bidHRs)
								->with('bidId',$bidId)
								->with('tenderId',$tenderId)
								->with('equipments',$equipments)
								->with('designations',$designations)
								->with('qualifications',$qualifications)
								->with('model',$model)
								->with('redirectUrl',$redirectUrl)
								->with('type',$type)
								->with('showProcuringAgency',$showProcuringAgency)
								->with('dzongkhags',$dzongkhags)
								->with('contractors',$contractors)
								->with('contractWorkDetails',$contractWorkDetails)
								->with('contractBidders',$contractBidders);
	}
	public function listOfWorks(){
		$parameters=array();
		$underProcess=0;
		$workStartDateFrom=Input::get('WorkStartDateFrom');
		$workStartDateTo=Input::get('WorkStartDateTo');
		$workOrderNo=Input::get('WorkOrderNo');
		$workStatus=Input::get('WorkExecutionStatus');
	
		$query="select T1.Id,T1.NameOfClient,T1.NameOfWork,T1.WorkOrderNo,T1.ContractPeriod,T1.WorkStartDate,T1.WorkCompletionDate,T2.Name as ProcuringAgency,
		coalesce(T5.Name,'New') as WorkExecutionStatus from cbbiddingform 
		T1 left join cmnprocuringagency T2 on T1.CmnProcuringAgencyId=T2.Id left join 
		cmnlistitem T5 on T1.CmnWorkExecutionStatusId=T5.Id where ByCDB=0 and Type=0";
		
		if(Request::path()=="cb/editcompletedworklist"){
			$query.=" and (T1.CmnWorkExecutionStatusId=? or T1.CmnWorkExecutionStatusId=?)";
			array_push($parameters,CONST_CMN_WORKEXECUTIONSTATUS_COMPLETED);
			array_push($parameters,CONST_CMN_WORKEXECUTIONSTATUS_TERMINATED);
		}elseif(Request::path()=="cb/editbiddingformlist"){
			$underProcess=1;
			$query.=" and T1.CmnProcuringAgencyId=? and (coalesce(T1.CmnWorkExecutionStatusId,00)=? or T1.CmnWorkExecutionStatusId is null)";
			array_push($parameters,Auth::user()->CmnProcuringAgencyId);
			array_push($parameters,CONST_CMN_WORKEXECUTIONSTATUS_AWARDED);
		}elseif(Request::path()=="cb/worklist"){
			$underProcess=1;
			$query.=" and T1.CmnProcuringAgencyId=? and coalesce(T1.CmnWorkExecutionStatusId,00)=?";
			array_push($parameters,Auth::user()->CmnProcuringAgencyId);
			array_push($parameters,CONST_CMN_WORKEXECUTIONSTATUS_AWARDED);
		}

		else{
			App::abort('404');
		}
		if((bool)$workOrderNo!=NULL || (bool)$workStartDateFrom!=NULL || (bool)$workStartDateTo!=NULL || (bool)$workStatus!=NULL){
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
		}
		$listOfWorks=DB::select($query." order by T1.CreatedOn desc",$parameters);
		
		$workExecutionStatus=CmnListItemModel::workExecutionStatus()->whereRaw('(ReferenceNo=? or ReferenceNo=?)',array(3003,3004))->get(array('Id','Name'));
		return View::make('cblistofworks')
					->with('underProcess',$underProcess)
					->with('workExecutionStatus',$workExecutionStatus)
					->with('workStartDateFrom',$workStartDateFrom)
					->with('workStartDateTo',$workStartDateTo)
					->with('workStatus',$workStatus)
					->with('workOrderNo',$workOrderNo)
					->with('listOfWorks',$listOfWorks);
	}
	public function workCompletionForm($bidId){
		$model="all/cbworkcompletionform";
		$workExecutionStatus=CmnListItemModel::workExecutionStatus()->whereRaw('(ReferenceNo=? or ReferenceNo=? or ReferenceNo=?)',array(3003,3004,3005))->get(array('Id','Name','ReferenceNo'));
		$detailsOfCompletedWorks=CBBiddingformModel::workCompletionDetails($bidId)
													->get(array('ContractPriceInitial','ContractPriceFinal','CommencementDateOffcial','CommencementDateFinal','CompletionDateOffcial','CompletionDateFinal','OntimeCompletionScore','QualityOfExecutionScore','CmnWorkExecutionStatusId','Remarks'));
		$redirectRoute='cb/worklist';
		if(!empty($detailsOfCompletedWorks[0]->OntimeCompletionScore)){
			$redirectRoute='cb/editcompletedworklist';
		}else{
			$detailsOfCompletedWorks=DB::select('select T1.WorkStartDate as CommencementDateOffcial,NULL as CommencementDateFinal,T1.WorkCompletionDate as CompletionDateOffcial,NULL as CompletionDateFinal,T2.EvaluatedAmount as ContractPriceInitial,Null as ContractPriceFinal,NULL as OntimeCompletionScore,NULL as QualityOfExecutionScore,T1.CmnWorkExecutionStatusId,NULL as Remarks from cbbiddingform T1 join cbbiddingformdetail T2 on T1.Id=T2.CrpBiddingFormId where T1.Id=? Limit 1',array($bidId));
		}
		$contractDetails=CBBiddingformModel::biddingFormContractorCdbAll()
								->where('cbbiddingform.Id',$bidId)
								->where('cbbiddingform.ByCDB',0)
								->get(array('cbbiddingform.Id','cbbiddingform.NameOfClient','cbbiddingform.WorkId','cbbiddingform.NameOfWork','cbbiddingform.DescriptionOfWork','cbbiddingform.ContractPeriod','cbbiddingform.WorkStartDate','cbbiddingform.WorkCompletionDate','cbbiddingform.ApprovedAgencyEstimate','T1.Name as ProcuringAgency','T2.Name as ProjectCategory','T3.Name as ContractorClass','T4.NameEn as Dzongkhag'));
		
		
		
		
		$workAwardedContractor=CBBiddingFormDetailModel::biddingFormCertifiedBuilderContractBidders($bidId)
								->where('cbbiddingformdetail.CmnWorkExecutionStatusId',CONST_CMN_WORKEXECUTIONSTATUS_AWARDED)
								->get(array('cbbiddingformdetail.Id','cbbiddingformdetail.BidAmount','cbbiddingformdetail.EvaluatedAmount','T1.CDBNo','T1.NameOfFirm'));
		
		return View::make('cbworkcompletionform')
					->with('model',$model)
					->with('redirectRoute',$redirectRoute)
					->with('detailsOfCompletedWorks',$detailsOfCompletedWorks)
					->with('workExecutionStatus',$workExecutionStatus)
					->with('contractDetails',$contractDetails)
					->with('workAwardedContractor',$workAwardedContractor);
	}


}
