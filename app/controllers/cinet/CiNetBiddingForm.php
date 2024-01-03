<?php
class CiNetBiddingForm extends CiNetController{
	protected $layout = 'horizontalmenumaster';
	public function biddingReportOptions(){
		$listOfUploadeTenders=DB::select("select T1.Id, T1.ReferenceNo, T1.CmnWorkExecutionStatusId, T1.DateOfClosingSaleOfTender, T1.LastDateAndTimeOfSubmission, T1.TenderOpeningDateAndTime, T1.ProjectEstimateCost, T2.Code as Category, T3.Code as Classification, T1.NameOfWork, T1.ContractPeriod, concat(A.Code,'/',DATE_FORMAT(T1.DateOfSaleOfTender,'%Y'),'/',coalesce(T1.WorkId,'')) as EtlTenderWorkId from etltender T1 join cmncontractorworkcategory T2 on T1.CmnContractorCategoryId = T2.Id join cmncontractorclassification T3 on T1.CmnContractorClassificationId = T3.Id join (cmnprocuringagency A join sysuser B on A.Id = B.CmnProcuringAgencyId) on A.Id = T1.CmnProcuringAgencyId left join crpbiddingform X on T1.Id=X.EtlTenderId where B.Id = ? and T1.TenderSource = 2 and T1.CmnWorkExecutionStatusId is null and coalesce(X.CmnWorkExecutionStatusId,0) <> ? and coalesce(X.CmnWorkExecutionStatusId,0) <> ? order by T1.DateOfClosingSaleOfTender DESC",array(Auth::user()->Id, CONST_CMN_WORKEXECUTIONSTATUS_COMPLETED,CONST_CMN_WORKEXECUTIONSTATUS_AWARDED));
		return View::make('cinet.biddingformoptions')
					->with('listOfUploadeTenders',$listOfUploadeTenders);
	} 
	public function biddingReport($tenderId=null){
		$showProcuringAgency=0;
		$type=0;
		$bidId=Input::get('bidreference');
		$model='all/mbiddingform';
		$redirectUrl='cinet/editbiddingformlist';
		$dzongkhags=DzongkhagModel::dzongkhag()->get(array('Id','NameEn'));
		$contractorProjectCategories=ContractorWorkCategoryModel::contractorProjectCategory()->get(array('Id','Code','Name'));
		$contractorClass=ContractorClassificationModel::classification()->get(array('Id','Name','Code'));
		$contractors=ContractorFinalModel::contractorHardListAll()->where('CmnApplicationRegistrationStatusId',CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED)->get(array('Id','NameOfFirm','CDBNo'));
		$contractWorkDetails=array(new CrpBiddingFormModel());
		$contractBidders=array(new CrpBiddingFormDetailModel());
		$designations = CmnListItemModel::designation()->get(array('Id','Name'));
		$qualifications = CmnListItemModel::qualification()->get(array('Id','Name'));
		$equipments = CmnEquipmentModel::equipment()->get(array('Id','Name','IsRegistered','VehicleType'));
		$bidEquipments = array(new BiddingFormEquipmentModel());
		$bidHRs = array(new BiddingFormHRModel());
		if((bool)$bidId!=NULL){
			//$redirectUrl='cinet/editbiddingformlist';
			$contractWorkDetails=CrpBiddingFormModel::workCompletionDetails($bidId)->get();
			if(Request::path()=="cinet/biddingform"){
				$contractBidders=CrpBiddingFormDetailModel::biddingFormContractorContractBidders($bidId)->get(array('crpbiddingformdetail.Id','crpbiddingformdetail.BidAmount','crpbiddingformdetail.EvaluatedAmount','crpbiddingformdetail.CrpContractorFinalId','crpbiddingformdetail.CmnWorkExecutionStatusId','T1.CDBNo','T1.NameOfFirm'));
				//$contractBidders=DB::select("select T2.Id,T2.BidAmount,T2.EvaluatedAmount,T2.CrpContractorFinalId,T2.CmnWorkExecutionStatusId,T1.CDBNo,T1.NameOfFirm from crpbiddingformdetail T2 join crpcontractorfinal T1 on T2.CrpContractorFinalId=T1.Id where T2.CrpBiddingFormId=? order by T1.CDBNo",array($bidId));
				if(!isset($contractBidders[0])){
					$contractBidders=array(new CrpBiddingFormDetailModel());
				}
				$bidEquipments = BiddingFormEquipmentModel::where('CrpBiddingFormId',$bidId)->get(array('Id','CrpBiddingFormId','CmnEquipmentId','RegistrationNo','OwnedOrHired'));
				if(count($bidEquipments)==0){
					$bidEquipments = array(new BiddingFormEquipmentModel());
				}
				$bidHRs = BiddingFormHRModel::where('CrpBiddingFormId',$bidId)->get(array('Id','CrpBiddingFormId','CIDNo','Name','CmnDesignationId','CmnQualificationId'));
				if(count($bidHRs)==0){
					$bidHRs = array(new BiddingFormHRModel());
				}
			}
		}
		if((bool)$tenderId!=null){
			//$redirectUrl='cinet/editbiddingformlist';
			$contractWorkDetails=DB::select("select NULL as Id,ReferenceNo,NameOfWork,DescriptionOfWork,CmnContractorCategoryId as CmnContractorProjectCategoryId,CmnContractorClassificationId,CmnDzongkhagId,ProjectEstimateCost as ApprovedAgencyEstimate,DateOfClosingSaleOfTender as BidSaleClosedDate,TenderOpeningDateAndTime as BidOpeningDate,ContractPeriod,TentativeStartDate as WorkStartDate,TentativeEndDate as WorkCompletionDate from etltender where Id=?",array($tenderId));
		}
		$this->layout->content = View::make('crps.cmnbiddingform')
								->with('isCinet',true)
								->with('bidEquipments',$bidEquipments)
								->with('bidHRs',$bidHRs)
								->with('tenderId',$tenderId)
								->with('equipments',$equipments)
								->with('designations',$designations)
								->with('qualifications',$qualifications)
								->with('model',$model)
								->with('redirectUrl',$redirectUrl)
								->with('type',$type)
								->with('showProcuringAgency',$showProcuringAgency)
								->with('dzongkhags',$dzongkhags)
								->with('contractorProjectCategories',$contractorProjectCategories)
								->with('contractorClass',$contractorClass)
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
		$query="select T1.Id,T1.NameOfWork,T1.WorkOrderNo,T1.ContractPeriod,T1.WorkStartDate,T1.WorkCompletionDate,T2.Name as ProcuringAgency,T3.Code as WorkCategory,T4.Code as ContractorClass,coalesce(T5.Name,'New') as WorkExecutionStatus from crpbiddingform T1 join cmnprocuringagency T2 on T1.CmnProcuringAgencyId=T2.Id join cmncontractorworkcategory T3 on T1.CmnContractorProjectCategoryId=T3.Id join cmncontractorclassification T4 on T1.CmnContractorClassificationId=T4.Id left join cmnlistitem T5 on T1.CmnWorkExecutionStatusId=T5.Id where ByCDB=0 and Type=0";
		if(Request::path()=="cinet/editcompletedworklist"){
			$query.=" and (T1.CmnWorkExecutionStatusId=? or T1.CmnWorkExecutionStatusId=?)";
			array_push($parameters,CONST_CMN_WORKEXECUTIONSTATUS_COMPLETED);
			array_push($parameters,CONST_CMN_WORKEXECUTIONSTATUS_TERMINATED);
		}elseif(Request::path()=="cinet/editbiddingformlist"){
			$underProcess=1;
			$query.=" and T1.CmnProcuringAgencyId=? and (coalesce(T1.CmnWorkExecutionStatusId,00)=? or T1.CmnWorkExecutionStatusId is null)";
			array_push($parameters,Auth::user()->CmnProcuringAgencyId);
			array_push($parameters,CONST_CMN_WORKEXECUTIONSTATUS_AWARDED);
		}elseif(Request::path()=="cinet/worklist"){
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
		$listOfWorks=DB::select($query." order by T1.WorkStartDate",$parameters);
		$workExecutionStatus=CmnListItemModel::workExecutionStatus()->whereRaw('(ReferenceNo=? or ReferenceNo=?)',array(3003,3004))->get(array('Id','Name'));
		return View::make('cinet.cinetlistofworks')
					->with('underProcess',$underProcess)
					->with('workExecutionStatus',$workExecutionStatus)
					->with('workStartDateFrom',$workStartDateFrom)
					->with('workStartDateTo',$workStartDateTo)
					->with('workStatus',$workStatus)
					->with('workOrderNo',$workOrderNo)
					->with('listOfWorks',$listOfWorks);
	}
	public function workCompletionForm($bidId){
		$model="all/mworkcompletionform";
		$workExecutionStatus=CmnListItemModel::workExecutionStatus()->whereRaw('(ReferenceNo=? or ReferenceNo=? or ReferenceNo=?)',array(3003,3004,3005))->get(array('Id','Name','ReferenceNo'));
		$detailsOfCompletedWorks=CrpBiddingFormModel::workCompletionDetails($bidId)
													->get(array('ContractPriceInitial','ContractPriceFinal','CommencementDateOffcial','CommencementDateFinal','CompletionDateOffcial','CompletionDateFinal','OntimeCompletionScore','QualityOfExecutionScore','CmnWorkExecutionStatusId','Remarks'));
		$redirectRoute='cinet/worklist';
		if(!empty($detailsOfCompletedWorks[0]->OntimeCompletionScore)){
			$redirectRoute='cinet/editcompletedworklist';
		}else{
			$detailsOfCompletedWorks=DB::select('select T1.WorkStartDate as CommencementDateOffcial,NULL as CommencementDateFinal,T1.WorkCompletionDate as CompletionDateOffcial,NULL as CompletionDateFinal,T2.EvaluatedAmount as ContractPriceInitial,Null as ContractPriceFinal,NULL as OntimeCompletionScore,NULL as QualityOfExecutionScore,T1.CmnWorkExecutionStatusId,NULL as Remarks from crpbiddingform T1 join crpbiddingformdetail T2 on T1.Id=T2.CrpBiddingFormId where T1.Id=? Limit 1',array($bidId));
		}
		$contractDetails=CrpBiddingFormModel::biddingFormContractorCdbAll()
								->where('crpbiddingform.Id',$bidId)
								->where('crpbiddingform.ByCDB',0)
								->get(array('crpbiddingform.Id','crpbiddingform.WorkOrderNo','crpbiddingform.NameOfWork','crpbiddingform.DescriptionOfWork','crpbiddingform.ContractPeriod','crpbiddingform.WorkStartDate','crpbiddingform.WorkCompletionDate','crpbiddingform.ApprovedAgencyEstimate','T1.Name as ProcuringAgency','T2.Name as ProjectCategory','T3.Name as ContractorClass','T4.NameEn as Dzongkhag'));
		$workAwardedContractor=CrpBiddingFormDetailModel::biddingFormContractorContractBidders($bidId)
								->where('crpbiddingformdetail.CmnWorkExecutionStatusId',CONST_CMN_WORKEXECUTIONSTATUS_AWARDED)
								->get(array('crpbiddingformdetail.Id','crpbiddingformdetail.BidAmount','crpbiddingformdetail.EvaluatedAmount','T1.NameOfFirm'));
		return View::make('cinet.cinetworkcompletionform')
					->with('model',$model)
					->with('redirectRoute',$redirectRoute)
					->with('detailsOfCompletedWorks',$detailsOfCompletedWorks)
					->with('workExecutionStatus',$workExecutionStatus)
					->with('contractDetails',$contractDetails)
					->with('workAwardedContractor',$workAwardedContractor);
	}
}