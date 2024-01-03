<?php
class NewetoolMyEtool extends EtoolController{
	protected $layout = 'horizontalmenumaster';
	public function dashboard(){
		$auditTrailActionMessage="Viewed dashboard page";
		$this->auditTrailEtoolCinet(NULL,NULL,$auditTrailActionMessage);
        $userId = Auth::user()->Id;
        $userProcuringAgencyId = DB::table('sysuser')->where('Id',$userId)->pluck('CmnProcuringAgencyId');
		$newsAndNotifications = DB::select("select Message, Date,CmnProcuringAgencyId from sysnewsandnotification where (DisplayIn = 2 and MessageFor = 2) and case when CmnProcuringAgencyId is not null then CmnProcuringAgencyId = '$userProcuringAgencyId' else 1=1 end order by Date Desc");
   
   
        $userAgencyId = DB::table('sysuser')->where('Id',Auth::user()->Id)->pluck('CmnProcuringAgencyId');

        $totalNotOpenTender = DB::select("SELECT  
                            COUNT(*) totalTender
                            FROM
                            etltender T1
                            LEFT JOIN etlevaluationscore a ON T1.`Id` = a.`EtlTenderId`
                            WHERE
                            T1.CmnProcuringAgencyId = '".$userAgencyId."'
                            AND T1.TenderSource = 1
                            AND COALESCE (T1.DeleteStatus, 'N') <> 'Y'
                            AND COALESCE (CmnWorkExecutionStatusId, 0) NOT IN (
                                'a13c5d39-b5a8-11e4-81ac-080027dcfac6',
                                '9cc4dab5-b5a8-11e4-81ac-080027dcfac6',
                                'a98f434b-d8ee-11e4-afa1-9c2a70cc8e06'
                            )
                            AND TIMESTAMPDIFF(MONTH,T1.TenderOpeningDateAndTime,CURRENT_TIMESTAMP)>0
                            AND a.`Id` IS  NULL  AND T1.`TenderStatus` NOT IN ('Cancelled')
                            AND T1.`IsSPRRTender`='Y'
                            ");
        $module=Request::segment(1);

        $this->layout->content =View::make('crps.cmnexternaluserdashboard')
								->with('newsAndNotifications',$newsAndNotifications)
								->with('module',$module)
                                ->with('totalNotOpenTender',$totalNotOpenTender[0]->totalTender);

	}
    public function getSelectChange(){
        $auditTrailActionMessage="Opened Change Work Awarded list";
        $this->auditTrailEtoolCinet(NULL,NULL,$auditTrailActionMessage);
        return View::make('new_etool.selectchange');
    }
    public function getChangeAwarded(){
        $workId = Input::get('WorkId');
        if($workId){
            $tender = DB::table('etltender as T1')
                        ->join('cmncontractorclassification as X','X.Id','=','T1.CmnContractorClassificationId')
                        ->join('cmncontractorworkcategory as Y','Y.Id','=','T1.CmnContractorCategoryId')
                        ->join('cmnprocuringagency as T2','T2.Id','=','T1.CmnProcuringAgencyId')
                        ->join('cmndzongkhag as T3','T3.Id','=','T1.CmnDzongkhagId')
                        ->whereRaw("case when T1.migratedworkid is null then concat(T2.Code,'/',year(T1.UploadedDate),'/',T1.WorkId) else T1.migratedworkid end = '$workId'")
                        ->get(array('T1.Id','T1.DescriptionOfWork','T3.NameEn as Dzongkhag','T1.NameOfWork','X.Code as Class','Y.Code as Category','T1.TentativeStartDate','T1.TentativeEndDate','T1.ProjectEstimateCost','T1.ContractPeriod'));
            if(count($tender)>0){
                $tenderEvaluationDetails = DB::table('etltenderbiddercontractor as T1')
                    ->join('etltender as Y','T1.EtlTenderId','=','Y.Id')
                    ->join('etlevaluationscore as X','X.EtlTenderBidderContractorId','=','T1.Id')
                    ->join('etltenderbiddercontractordetail as T2','T1.Id','=','T2.EtlTenderBidderContractorId')
                    ->join('crpcontractorfinal as T3','T3.Id','=','T2.CrpContractorFinalId')
                    ->where('T1.EtlTenderId',$tender[0]->Id)
                    ->where('Y.CmnWorkExecutionStatusId',CONST_CMN_WORKEXECUTIONSTATUS_AWARDED)
                    ->groupBy('T1.Id')
                    ->orderBy(DB::raw('coalesce(X.Score10,0)'),'DESC')
                    ->get(array(DB::raw('distinct(T1.Id)'),'Y.Id as TenderId','T1.ActualStartDate','T1.FinancialBidQuoted', 'X.Score10', DB::raw('group_concat(concat(T3.NameOfFirm," (",T3.CDBNo,")") SEPARATOR ", ") as Contractor')));
            }else{
                return Redirect::to('newEtl/etoolselectchange')->with('customerrormessage','No Work having this Work Id has been found');
            }
            $auditTrailActionMessage="Opened Change Awarded for Work Id $workId";
            $this->auditTrailEtoolCinet(NULL,NULL,$auditTrailActionMessage,$workId);
            return View::make('new_etool.changeawarded')
                            ->with('tenderEvaluationDetails',$tenderEvaluationDetails)
                            ->with('tender',$tender);
        }else{
            return Redirect::to('newEtl/etoolselectchange')->with('customerrormessage','Please enter a Work Id');
        }
    }
    public function postUpdate(){
        $inputs = Input::all();
        $contractorFirmName=DB::table('etltenderbiddercontractordetail as T1')
            ->join('crpcontractorfinal as T2','T1.CrpContractorFinalId','=','T2.Id')
            ->where('T1.EtlTenderBidderContractorId',$inputs['Id'])
            ->get(array(DB::raw("group_concat(concat(T2.NameOfFirm,' (CDB No.',T2.CDBNo,')')) as ContractorsFirmNames")));

        $workIdForAuditTrail=DB::table('etltender as T1')->join('cmnprocuringagency as T2','T1.CmnProcuringAgencyId','=','T2.Id')
            ->where('T1.Id',$inputs['EtlTenderId'])
            ->get(array(DB::raw("case when T1.migratedworkid is null then concat(T2.Code,'/',year(T1.UploadedDate),'/',T1.WorkId) else T1.migratedworkid end as WorkId")));

        $auditTrailActionMessage="Work Id ".$workIdForAuditTrail[0]->WorkId." awarded to ".$contractorFirmName[0]->ContractorsFirmNames;
        $this->auditTrailEtoolCinet(NULL,NULL,$auditTrailActionMessage,$workIdForAuditTrail[0]->WorkId);
    }

    public function seekclarification($tenderId,$cdbNo,$contractorId)
    {
        $seekClarificationList = DB::select(" SELECT a.Id,a.`Enquiry`,
        a.`Respond`,a.`Status`,a.`CDB_No`,b.`EGPTenderId` Tender_Id ,a.`Created_On`
          FROM `etlseekclarification` a 
         LEFT JOIN etltender b ON a.`Tender_Id`=b.id
         WHERE a.`Tender_Id` = '".$tenderId."' AND a.`CDB_No`='".$cdbNo."' order by a.Id desc");

        $this->layout->content =View::make('new_etool.seekClarificationList')
        ->with('seekClarificationList',$seekClarificationList)
        ->with('tenderId',$tenderId)
        ->with('cdbNo',$cdbNo)
        ->with('contractorId',$contractorId);
	}

    public function viewseekclarification($id,$contractorId)
    {
        $seekClaraificationDtls =  DB::select(" SELECT a.Id,a.`Enquiry`,
        a.`Respond`,a.`Status`,a.`CDB_No`,b.`EGPTenderId` Tender_Id,a.`Tender_Id` etlTenderId ,a.`Created_On`
          FROM `etlseekclarification` a 
         LEFT JOIN etltender b ON a.`Tender_Id`=b.id
         WHERE a.`Id` = '".$id."'");
        $this->layout->content =View::make('new_etool.viewseekClarificationList')
        ->with('seekClaraificationDtls',$seekClaraificationDtls)
        ->with('contractorId',$contractorId);
	}

    public function respondseekclarification($id)
    {
        $seekClaraificationDtls = SeekClarificationModel::where('Id',$id)->get();
        $this->layout->content =View::make('new_etool.respondseekclarification')->with('seekClaraificationDtls',$seekClaraificationDtls);
	}
    public function submitClarificationRespond()
    {

        $postedValues=Input::all();
        DB::update("update etlseekclarification set Respond = '".$postedValues['Respond']."',Status='C', Responded_By = ?, Responded_On = CURRENT_TIMESTAMP() where Id = ?",
        array( Auth::user()->Id,$postedValues['Id'] ));

        $seekClarificationList = SeekClarificationModel::where('Procuring_Agency_Id',Auth::user()->CmnProcuringAgencyId)->get();
        $this->layout->content =View::make('new_etool.seekClarificationList')
                                 ->with('seekClarificationList',$seekClarificationList);
       
	}
    

}