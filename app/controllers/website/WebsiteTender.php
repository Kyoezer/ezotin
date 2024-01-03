<?php
class WebsiteTender extends WebsiteController{
	public function listOfTenders(){
        $contractorClassifications = Input::get('CmnContractorClassificationId');
        $contractorCategory = Input::get('CmnContractorCategoryId');
		$agencyId = Input::get('CmnProcuringAgencyId');
		$fromDate = Input::get('FromDate');
		$toDate = Input::get('ToDate');
		$contractorCategoryId = ContractorWorkCategoryModel::contractorProjectCategory()->orderBy('Code')->get(array("Id","Code","Name"));
        $contractorClassificationId = ContractorClassificationModel::classification()->orderBy('Priority')->get(array('Id','Code','Name'));
//		$agencies = ProcuringAgencyModel::procuringAgency()->get(array('cmnprocuringagency.Id','cmnprocuringagency.Name'));
		$agencies = DB::table('cmnprocuringagency')->orderBy('Name')->get(array('Id','Name'));
        $dzongkhagId = DzongkhagModel::dzongkhag() -> orderBy('NameEn')->get(array('Id','NameEn'));
		$slno=1;
		$parameters = array();
		$currentDate = date('Y-m-d');
		$listoftenders = "select concat(T2.Code,'/',DATE_FORMAT(T1.DateOfSaleOfTender,'%Y'),'/',T1.WorkId) as WorkId, T1.Id as Id, T2.Name as ProcuringAgencyName, T1.NameOfWork, T1.DescriptionOfWork, T3.NameEn as Dzongkhag, T4.Code as WorkCategory, T5.Code as WorkClassification, T1.ContractPeriod, T1.DateOfClosingSaleOfTender from etltender as T1 join cmnprocuringagency as T2 on T1.CmnProcuringAgencyId = T2.Id join cmndzongkhag as T3 on T1.CmnDzongkhagId = T3.Id join cmncontractorworkcategory as T4 on T1.CmnContractorCategoryId = T4.Id join cmncontractorclassification as T5 on T1.CmnContractorClassificationId = T5.Id where 1";
		if(!(bool)$fromDate && !(bool)$toDate){
			$listoftenders.=" and T1.LastDateAndTimeOfSubmission > ?";
			array_push($parameters,$currentDate);
		}
		$listoftenders .= " and T1.PublishInWebsite=1 and coalesce(T1.DeleteStatus,'N') <> 'Y' and coalesce(T1.CmnWorkExecutionStatusId,0) <> ? and coalesce(T1.CmnWorkExecutionStatusId,0) <> ?";

		array_push($parameters,CONST_CMN_WORKEXECUTIONSTATUS_COMPLETED);
		array_push($parameters,CONST_CMN_WORKEXECUTIONSTATUS_AWARDED);
		if((bool)$contractorClassifications || (bool)$contractorCategory) {
			if ((bool)$contractorClassifications) {
				$listoftenders .= " and T1.CmnContractorClassificationId = ?";
				array_push($parameters, $contractorClassifications);
			}
			if ((bool)$contractorCategory) {
				$listoftenders .= " and T1.CmnContractorCategoryId = ?";
				array_push($parameters, $contractorCategory);
			}
		}
		if((bool)$agencyId){
			$listoftenders.=" and T1.CmnProcuringAgencyId = ?";
			array_push($parameters,$agencyId);
		}
		if((bool)$fromDate){
			$listoftenders .= " and DATE_FORMAT(T1.TenderOpeningDateAndTime,'%Y-%m-%d') >= ?";
			array_push($parameters,$this->convertDate($fromDate));
		}
		if((bool)$toDate){
			$listoftenders .= " and DATE_FORMAT(T1.TenderOpeningDateAndTime,'%Y-%m-%d') <= ?";
			array_push($parameters,$this->convertDate($toDate));
		}

		$listoftenders = DB::select("$listoftenders order by T1.DateOfClosingSaleOfTender desc",$parameters);
		return View::make("website.listoftenders")
					->with('agencies',$agencies)
					->with('dzongkhagId',$dzongkhagId)
					->with('contractorCategoryId',$contractorCategoryId)
					->with('contractorClassificationId',$contractorClassificationId)
					->with('listoftenders',$listoftenders)
					->with('slno',$slno);
	}

	public function tenderDetails($id){
		$tenderDetails = DB::table('etltender as T1')
							->leftJoin('cmnprocuringagency as T2','T1.CmnProcuringAgencyId','=','T2.Id')
							->leftJoin('cmndzongkhag as T3','T1.CmnDzongkhagId','=','T3.Id')
							->join('cmncontractorworkcategory as T4','T1.CmnContractorCategoryId','=','T4.Id')
							->join('cmncontractorclassification as T5','T1.CmnContractorClassificationId','=','T5.Id')
							->where('T1.Id', '=', $id)
							->get(array(DB::raw('concat(T2.Code,"/",DATE_FORMAT(T1.DateOfSaleOfTender,"%Y"),"/",T1.WorkId) as WorkId'),'T1.Id as Id','T1.ShowCostInWebsite','T2.Name as ProcuringAgencyName','T1.NameOfWork','T1.DescriptionOfWork','T1.ContractPeriod','T3.NameEn as Dzongkhag','T4.Code as WorkCategoryCode','T4.Name as WorkCategory','T5.Code as WorkClassificationCode','T5.Name as WorkClassification','T1.CostOfTender','T1.DateOfSaleOfTender','T1.DateOfClosingSaleOfTender','T1.LastDateAndTimeOfSubmission','T1.TenderOpeningDateAndTime','T1.EMD','T1.ProjectEstimateCost','T1.TentativeStartDate','T1.TentativeEndDate','T1.ContactPerson','T1.ContactNo','T1.ContactEmail'));
		$attachmentCount = DB::table('etltenderattachment')->where('EtlTenderId',$id)->count();
		return View::make("website.tenderdetails")
					->with('attachmentCount',$attachmentCount)
					->with('tenderDetails',$tenderDetails);
	}

	public function downloadTender(){
		$uuid=DB::select("select UUID() as GUID");
		$generatedId=$uuid[0]->GUID;
		$tenderId = Input::get('TenderId');
		$email=Input::get('Email');
		$phoneNo=Input::get('PhoneNo');

		$postedValues=Input::all();
		$instance=new WedTenderDownloadModel;
		$instance->Id = $generatedId;
        $instance->Email = $email;
        $instance->PhoneNo = $phoneNo;
		$instance->TenderId = $tenderId;
		$check=$instance->save();

		return Redirect::to('web/getdownload/'.$tenderId);
	}

	public function getDownload($id){
		$attachmentLink=TenderAttachmentModel::where('EtlTenderId',$id)->pluck('DocumentPath');
		return Redirect::to($attachmentLink);
	}

}