<?php

class WorkCompletionFormEtool extends EtoolController{
	public function index($tenderId){
        $tenderDetails = DB::table('etltender as T1')
                            ->leftJoin('cmncontractorworkcategory as A','A.Id','=','T1.CmnContractorCategoryId')
                            ->leftJoin('cmncontractorclassification as B','T1.CmnContractorClassificationId','=','B.Id')
                            ->leftJoin('cmnprocuringagency as T2','T1.CmnProcuringAgencyId','=','T2.Id')
                            ->leftJoin('etltenderbiddercontractor as T3','T3.EtlTenderId','=','T1.Id')
                            ->leftJoin('etltenderbiddercontractordetail as T4','T4.EtlTenderBidderContractorId','=','T3.Id')
                            ->leftJoin('crpcontractorfinal as T5','T5.Id','=','T4.CrpContractorFinalId')
                            ->where('T1.Id','=',$tenderId)
                            ->whereNotNull('T3.AwardedAmount')
                            ->groupBy('T1.Id')
                            ->get(array(DB::raw("case when T1.migratedworkid is null then concat(T2.Code,'/',year(T1.UploadedDate),'/',T1.WorkId) else T1.migratedworkid end as WorkId"),'A.Code as Category','T1.ContractPeriod', 'T1.EMD','B.Code as Classification',DB::raw('group_concat(T5.NameOfFirm SEPARATOR ", ") as Contractor'),'T2.Name as ProcuringAgency','T1.NameOfWork','T1.DescriptionOfWork','T3.ActualStartDate','T3.ActualEndDate','T3.AwardedAmount'));
        $workStatuses=CmnListItemModel::workExecutionStatus()->whereIn('ReferenceNo',array(3003,3004,3005))->get(array('Id','Name','ReferenceNo'));
        $completionDetails = DB::table('etltender')
                                ->where('Id','=',$tenderId)
                                ->get(array('Remarks','LDImposed','LDNoOfDays','LDAmount', 'Hindrance','HindranceNoOfDays','CmnWorkExecutionStatusId','ContractPriceInitial','ContractPriceFinal','CommencementDateOfficial','CommencementDateFinal','CompletionDateOfficial','CompletionDateFinal','QualityOfExecutionScore','OntimeCompletionScore'));
        if(count($tenderDetails) == 0){
            return Redirect::back()->with('customerrormessage','Work is not awarded yet');
        }
        $auditTrailActionMessage="Viewed work completion form for Work Id ".$tenderDetails[0]->WorkId." awarded to ".$tenderDetails[0]->Contractor;
        $this->auditTrailEtoolCinet(NULL,NULL,$auditTrailActionMessage,$tenderDetails[0]->WorkId);
        return View::make('etool.workcompletionform')
                ->with('tenderId',$tenderId)
                ->with('tenderDetails',$tenderDetails)
                ->with('completionDetails',$completionDetails)
                ->with('workStatuses',$workStatuses);
	}
	public function listOfWorks(){
        $contractors = ContractorFinalModel::contractorHardListAll()->get(array('Id','NameOfFirm','CDBNo'));
        $query = "select distinct `T1`.`Id`, case when T1.migratedworkid is null then concat(T2.Code,'/',year(T1.UploadedDate),'/',T1.WorkId) else T1.migratedworkid end as WorkId, `T2`.`Name` as `ProcuringAgency`, `T1`.`NameOfWork`, `T3`.`Name` as `Category`, `T4`.`Name` as `Status`,(select group_concat(concat(A.NameOfFirm,' (CDB No.:',A.CDBNo,')') SEPARATOR \", \") from crpcontractorfinal A join etltenderbiddercontractordetail B on A.Id = B.CrpContractorFinalId where B.EtlTenderBidderContractorId = T5.Id) as Contractor, group_concat(T7.Id SEPARATOR \", \") as ContractorId, group_concat(T7.CDBNo SEPARATOR \", \") as ContractorNo from `etltender` as `T1` inner join (`cmnprocuringagency` as `T2` join sysuser as B on B.CmnProcuringAgencyId = T2.Id) on `T1`.`CmnProcuringAgencyId` = `T2`.`Id` inner join `cmncontractorworkcategory` as `T3` on `T3`.`Id` = `T1`.`CmnContractorCategoryId` left join `cmnlistitem` as `T4` on `T4`.`Id` = `T1`.`CmnWorkExecutionStatusId` left join `etltenderbiddercontractor` as `T5` on `T5`.`EtlTenderId` = `T1`.`Id` and `T5`.`ActualStartDate` is not NULL left join `etltenderbiddercontractordetail` as `T6` on `T6`.`EtlTenderBidderContractorId` = `T5`.`Id` join `crpcontractorfinal` as `T7` on `T6`.`CrpContractorFinalId` = `T7`.`Id` where T1.TenderSource = 1 and  coalesce(T1.DeleteStatus,'N') <> 'Y'  and T1.IsSPRRTender='N'";
        $queryForDistinctYears = "select distinct case when T1.migratedworkid is null then year(T1.UploadedDate) else SUBSTRING_INDEX(SUBSTRING_INDEX(T1.migratedworkid,'/',-2),'/',1) end as Year from etltender T1 join (cmnprocuringagency A join sysuser B on A.Id = B.CmnProcuringAgencyId) on A.Id = T1.CmnProcuringAgencyId left join `etltenderbiddercontractor` as `T5` on `T5`.`EtlTenderId` = `T1`.`Id` and `T5`.`ActualStartDate` is not NULL left join `etltenderbiddercontractordetail` as `T6` on `T6`.`EtlTenderBidderContractorId` = `T5`.`Id` left join `crpcontractorfinal` as `T7` on `T6`.`CrpContractorFinalId` = `T7`.`Id` where T1.TenderSource = 1 and coalesce(T1.DeleteStatus,'N') <> 'Y'  and T1.IsSPRRTender='N'";
        $appendToQuery = "";
        $appendToQuery2 = "";
        $userId = Auth::user()->Id;
        $userAgencyId = DB::table('sysuser')->where('Id',$userId)->pluck('CmnProcuringAgencyId');
        if((bool)Input::get('CrpContractorFinalId')){
            $crpContractorFinalId = Input::get('CrpContractorFinalId');
            $appendToQuery = " HAVING ContractorId LIKE '%$crpContractorFinalId%'";
            $appendToQuery2 = " having group_concat(T7.Id SEPARATOR \", \") LIKE '%$crpContractorFinalId%'";
        }
        if((bool)Input::get('WorkId')){
            $workId = Input::get('WorkId');
            $query .= " and case when T1.migratedworkid is null then concat(T2.Code,'/',year(T1.UploadedDate),'/',T1.WorkId) else T1.migratedworkid end LIKE '%$workId%'";
            $queryForDistinctYears .= " and case when T1.migratedworkid is null then concat(A.Code,'/',year(T1.UploadedDate),'/',T1.WorkId) else T1.migratedworkid end LIKE '%$workId%'";
        }
        $query.=" and T1.CmnProcuringAgencyId = ? and coalesce(T1.CmnWorkExecutionStatusId,0) = ? ";

        $distinctYears = DB::select("$queryForDistinctYears and T1.CmnProcuringAgencyId = ? and coalesce(T1.CmnWorkExecutionStatusId,0) = ? group by T1.Id$appendToQuery2 order by case when T1.migratedworkid is null then year(T1.UploadedDate) else SUBSTRING_INDEX(SUBSTRING_INDEX(T1.migratedworkid,'/',-2),'/',1) end DESC",array($userAgencyId,CONST_CMN_WORKEXECUTIONSTATUS_AWARDED));

        $tenders = array();
        $count = 0;
        foreach($distinctYears as $distinctYear):
            if($distinctYear != null):
                $tenders[$distinctYear->Year] = DB::select($query."and case when T1.migratedworkid is null then year(T1.UploadedDate) else SUBSTRING_INDEX(SUBSTRING_INDEX(T1.migratedworkid,'/',-2),'/',1) end ='$distinctYear->Year' group by `T1`.`Id`$appendToQuery order by case T1.migratedworkid when null then year(T1.UploadedDate) else SUBSTRING_INDEX(SUBSTRING_INDEX(T1.migratedworkid,'/',-2),'/',1) end DESC, T1.WorkId DESC",array($userAgencyId,CONST_CMN_WORKEXECUTIONSTATUS_AWARDED));
            else:
                unset($distinctYears[$count]);
            endif;
            $count++;
        endforeach;
        $auditTrailActionMessage="Viewed list of awarded works in completion page";
        $this->auditTrailEtoolCinet(NULL,NULL,$auditTrailActionMessage);
        return View::make('etool.listofworks')
                ->with('distinctYears',$distinctYears)
                ->with('contractors',$contractors)
                ->with('tenders',$tenders);
	}
    public function postWorkCompletion(){
        $inputs = Input::except('APSForm');
        $tableInputs = array();
        foreach($inputs as $key=>$value){
            $tableInputs[$key] = $value;
        }
        if($tableInputs['CmnWorkExecutionStatusId'] == CONST_CMN_WORKEXECUTIONSTATUS_COMPLETED){
            $tableInputs['CommencementDateOfficial'] = $this->convertDate($tableInputs['CommencementDateOfficial']);
            $tableInputs['CommencementDateFinal'] = $this->convertDate($tableInputs['CommencementDateFinal']);
            $tableInputs['CompletionDateOfficial'] = $this->convertDate($tableInputs['CompletionDateOfficial']);
            $tableInputs['CompletionDateFinal'] = $this->convertDate($tableInputs['CompletionDateFinal']);



        }
        $trackInputs["Operation"] = DB::table('cmnlistitem')->where('Id',$tableInputs['CmnWorkExecutionStatusId'])->pluck('Name');
        $trackInputs["User"] = Auth::user()->FullName;
        $trackInputs["OperationTime"] = date('Y-m-d G:i:s');
        $workId = DB::select("select case when T1.migratedworkid is not null then T1.migratedworkid else concat(T2.Code,'/',year(T1.UploadedDate),'/',T1.WorkId) end as WorkId from etltender T1 join cmnprocuringagency T2 on T1.CmnProcuringAgencyId = T2.Id where T1.Id = ?",array(Input::get('Id')));
        $trackInputs["WorkId"] = $workId[0]->WorkId;
        $trackInputs["Id"] = $this->UUID();

        DB::beginTransaction();
        try{
            if(Input::hasFile('APSForm')){
                $apsForm = Input::file('APSForm');
                $workIdWithUnderscore = str_replace("/",'_',$workId[0]->WorkId);
                $attachmentName=$workIdWithUnderscore.'_apsform.'.$apsForm->getClientOriginalExtension();
                $destination=public_path().'/uploads/apsform';
                $destinationDB='/uploads/apsform/'.$attachmentName;
                $tableInputs["APSFormPath"]=$destinationDB;
                $tableInputs["APSFormType"]=".".$apsForm->getClientOriginalExtension();
                $uploadAttachments=$apsForm->move($destination, $attachmentName);
            }

            $object = TenderModel::find($inputs['Id']);
            $object->fill($tableInputs);
            $object->update();
            DB::table('etlevaluationtrack')->insert(array($trackInputs));
        }catch (Exception $e){
            DB::rollback();
            throw $e;
        }
        DB::commit();
        $tenderDetails = DB::table('etltender as T1')
                            ->join('cmnlistitem as A','T1.CmnWorkExecutionStatusId','=','A.Id')
                            ->join('cmnprocuringagency as T2','T1.CmnProcuringAgencyId','=','T2.Id')
                            ->join('etltenderbiddercontractor as T3','T3.EtlTenderId','=','T1.Id')
                            ->join('etltenderbiddercontractordetail as T4','T4.EtlTenderBidderContractorId','=','T3.Id')
                            ->join('crpcontractorfinal as T5','T5.Id','=','T4.CrpContractorFinalId')
                            ->where('T1.Id','=',$inputs['Id'])
                            ->whereNotNull('T3.AwardedAmount')
                            ->groupBy('T1.Id')
                            ->get(array('A.Name as WorkExecutionStatus',DB::raw("case when T1.migratedworkid is null then concat(T2.Code,'/',year(T1.UploadedDate),'/',T1.WorkId) else T1.migratedworkid end as WorkId"),DB::raw('group_concat(T5.NameOfFirm SEPARATOR ", ") as Contractor')));
        $auditTrailActionMessage="Work status changed to ".$tenderDetails[0]->WorkExecutionStatus." for Work Id ".$tenderDetails[0]->WorkId." awarded to ".$tenderDetails[0]->Contractor;
        $this->auditTrailEtoolCinet(NULL,NULL,$auditTrailActionMessage,$tenderDetails[0]->WorkId);
        return Redirect::to('etl/listofworksetool')->with('savedsuccessmessage','Tender has been updated successfully!');
    }
}