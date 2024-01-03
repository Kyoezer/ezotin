<?php
class WorkIdEtool extends EtoolController{
	public function index(){
        $auditTrailActionMessage="Viewed set criteria page";
        $workId = Input::get('WorkId');
        $contractorCategoryId = Input::get('ContractorCategoryId');
        $contractorClassificationId = Input::get('ContractorClassificationId');
        $contractorCategories = ContractorWorkCategoryModel::contractorProjectCategory()->get(array("Id","Code","Name"));
        $contractorClassifications = ContractorClassificationModel::classification()->get(array('Id','Code','Name'));
        $userAgencyId = DB::table('sysuser')->where('Id',Auth::user()->Id)->pluck('CmnProcuringAgencyId');
        $parameters = array($userAgencyId);
        $query = "select distinct T1.Id,T1.Method,T1.TenderStatus,T1.EGPTenderId, T1.TenderOpeningDateAndTime, T2.Code as Category, T3.Code as Classification,
         T1.NameOfWork, T1.ContractPeriod, case when T1.migratedworkid is null then
          concat(A.Code,'/',year(T1.UploadedDate),'/',T1.WorkId) else T1.migratedworkid end as EtlTenderWorkId 
          from etltender T1 join cmncontractorworkcategory T2 on T1.CmnContractorCategoryId = T2.Id 
          join cmncontractorclassification T3 on T1.CmnContractorClassificationId = T3.Id join (cmnprocuringagency A 
          join sysuser B on A.Id = B.CmnProcuringAgencyId) on A.Id = T1.CmnProcuringAgencyId 
          where T1.CmnProcuringAgencyId = ? and T1.TenderSource = 1 and T3.ReferenceNo not in (4) 
          and T1.TenderSource = 1 and coalesce(T1.DeleteStatus,'N') <> 'Y'  and T1.IsSPRRTender='N'";
          
        $queryForDistinctYears = "select distinct case when T1.migratedworkid is null then year(T1.UploadedDate) 
        else SUBSTRING_INDEX(SUBSTRING_INDEX(T1.migratedworkid,'/',-2),'/',1) end as Year from etltender T1 
        join (cmnprocuringagency A join sysuser B on A.Id = B.CmnProcuringAgencyId) on A.Id = T1.CmnProcuringAgencyId 
        where T1.CmnProcuringAgencyId = ? and T1.TenderSource = 1 and coalesce(T1.DeleteStatus,'N') <> 'Y'  and T1.IsSPRRTender='N'";
        if((bool)$workId || (bool)$contractorCategoryId || (bool)$contractorClassificationId) {
            if ((bool)$workId) {
                $query .= " and case when T1.migratedworkid is null then concat(A.Code,'/',year(T1.UploadedDate),'/',T1.WorkId) else T1.migratedworkid end LIKE '%$workId%'";
                $queryForDistinctYears .= " and case when T1.migratedworkid is null then concat(A.Code,'/',year(T1.UploadedDate),'/',T1.WorkId) else T1.migratedworkid end LIKE '%$workId%'";
            }
            if ((bool)$contractorCategoryId) {
                $query .= " and T1.CmnContractorCategoryId = ?";
                $queryForDistinctYears .= " and T1.CmnContractorCategoryId = ?";
                array_push($parameters, $contractorCategoryId);
            }
            if ((bool)$contractorClassificationId) {
                $query .= " and T1.CmnContractorClassificationId = ?";
                $queryForDistinctYears .= " and T1.CmnContractorClassificationId = ?";
                array_push($parameters, $contractorClassificationId);
            }
        }

        array_push($parameters,CONST_CMN_WORKEXECUTIONSTATUS_COMPLETED);
        array_push($parameters,CONST_CMN_WORKEXECUTIONSTATUS_AWARDED);
        array_push($parameters,CONST_CMN_WORKEXECUTIONSTATUS_TERMINATED);
        array_push($parameters,CONST_CMN_WORKEXECUTIONSTATUS_CANCELLED);
        $distinctYears = DB::select("$queryForDistinctYears and coalesce(T1.CmnWorkExecutionStatusId,0) not in (?,?,?,?) order by case when T1.migratedworkid is null then year(T1.UploadedDate) else SUBSTRING_INDEX(SUBSTRING_INDEX(T1.migratedworkid,'/',-2),'/',1) end DESC",$parameters);
        $uploadedTenders = array();
        $count = 0;
        foreach($distinctYears as $distinctYear):
            if($distinctYear->Year != null):
                $uploadedTenders[$distinctYear->Year] = DB::select("$query and coalesce(T1.CmnWorkExecutionStatusId,0) 
                NOT IN (?,?,?,?) and case when T1.migratedworkid is null then 
                year(T1.UploadedDate) else SUBSTRING_INDEX(SUBSTRING_INDEX(T1.migratedworkid,'/',-2),'/',1) 
                end = '$distinctYear->Year' order by case when T1.migratedworkid is null then year(T1.UploadedDate) 
                else SUBSTRING_INDEX(SUBSTRING_INDEX(T1.migratedworkid,'/',-2),'/',1) end DESC, T1.WorkId DESC",$parameters);
            else:
                unset($distinctYears[$count]);
            endif;
        $count++;
        endforeach;
//        echo "<pre>"; dd($distinctYears);
        $this->auditTrailEtoolCinet(NULL,NULL,$auditTrailActionMessage);
        return View::make('etool.workid')
            ->with('distinctYears',$distinctYears)
            ->with('uploadedTenders',$uploadedTenders)
            ->with('contractorCategories',$contractorCategories)
            ->with('contractorClassifications',$contractorClassifications);
	}
}