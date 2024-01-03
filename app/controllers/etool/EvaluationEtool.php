<?php
class EvaluationEtool extends EtoolController{
	public function index(){
        $workId = Input::get('WorkId');
        $contractorCategoryId = Input::get('ContractorCategoryId');
        $contractorClassificationId = Input::get('ContractorClassificationId');
        $contractorCategories = ContractorWorkCategoryModel::contractorProjectCategory()->get(array("Id","Code","Name"));
        $contractorClassifications = ContractorClassificationModel::classification()->get(array('Id','Code','Name'));
        $userAgencyId = DB::table('sysuser')->where('Id',Auth::user()->Id)->pluck('CmnProcuringAgencyId');
        $parameters = array($userAgencyId);
       // OLD QUERY COMMENTED BY PRAMOD $query1 = "select distinct(T1.Id), T1.TenderOpeningDateAndTime, T2.Code as Category, T3.Code as Classification, T1.NameOfWork, T1.ContractPeriod, case when T1.migratedworkid is null then concat(A.Code,'/',year(T1.UploadedDate),'/',T1.WorkId) else T1.migratedworkid end as EtlTenderWorkId from etltender T1 join cmncontractorworkcategory T2 on T1.CmnContractorCategoryId = T2.Id join cmncontractorclassification T3 on T1.CmnContractorClassificationId = T3.Id join (cmnprocuringagency A join sysuser B on A.Id = B.CmnProcuringAgencyId) on A.Id = T1.CmnProcuringAgencyId inner join etlcriteriahumanresource C on C.EtlTenderId = T1.Id where T1.CmnProcuringAgencyId = ? and T1.TenderSource = 1 and coalesce(T1.DeleteStatus,'N') <> 'Y'";
       
       $query1 = "select distinct(T1.Id),
       IF(a.`Name` IS NULL,IF(b.`EtlTenderId` IS NULL,'Under Process','Evaluated'),a.`Name`) tenderStatus,
        T1.`Method`,T1.`TenderStatus`,T1.`EGPTenderId`,  T1.TenderOpeningDateAndTime, T2.Code as Category, T3.Code as Classification, T1.NameOfWork, T1.ContractPeriod, case when T1.migratedworkid is null
         then concat(a.Code,'/',year(T1.UploadedDate),'/',T1.WorkId) else T1.migratedworkid end
          as EtlTenderWorkId from etltender T1 join cmncontractorworkcategory T2 on
           T1.CmnContractorCategoryId = T2.Id join cmncontractorclassification T3 on
            T1.CmnContractorClassificationId = T3.Id join (cmnprocuringagency Ac 
            join sysuser Bc on Ac.Id = Bc.CmnProcuringAgencyId) on Ac.Id = T1.CmnProcuringAgencyId 
        LEFT JOIN `cmnlistitem` a ON T1.CmnWorkExecutionStatusId=a.id
        LEFT JOIN `etlevaluationscore` b ON T1.Id=b.`EtlTenderId`
        where T1.IsSPRRTender='N' and T1.CmnProcuringAgencyId = ? and T1.TenderSource = 1 and coalesce(T1.DeleteStatus,'N') <> 'Y'";
       
        $queryForDistinctYears = "select distinct case when T1.migratedworkid is null then year(T1.UploadedDate) else SUBSTRING_INDEX(SUBSTRING_INDEX(T1.migratedworkid,'/',-2),'/',1) end as Year from etltender T1 join (cmnprocuringagency Ac join sysuser Bc on Ac.Id = Bc.CmnProcuringAgencyId) on Ac.Id = T1.CmnProcuringAgencyId where T1.CmnProcuringAgencyId = ? and T1.TenderSource = 1 and coalesce(T1.DeleteStatus,'N') <> 'Y'";

        if((bool)$workId || (bool)$contractorCategoryId || (bool)$contractorClassificationId) {
            if ((bool)$workId) {
                $query1 .= " and case when T1.migratedworkid is null then concat(Ac.Code,'/',year(T1.UploadedDate),'/',T1.WorkId) else T1.migratedworkid end LIKE '%$workId%'";
                $queryForDistinctYears .= " and concat(Ac.Code,'/',DATE_FORMAT(T1.UploadedDate,'%Y'),'/',T1.WorkId) LIKE '%$workId%'";
            }
            if ((bool)$contractorCategoryId) {
                $query1 .= " and T1.CmnContractorCategoryId = ?";
                $queryForDistinctYears .= " and T1.CmnContractorCategoryId = ?";
                array_push($parameters, $contractorCategoryId);
            }
            if ((bool)$contractorClassificationId) {
                $query1 .= " and T1.CmnContractorClassificationId = ?";
                $queryForDistinctYears .= " and T1.CmnContractorClassificationId = ?";
                array_push($parameters, $contractorClassificationId);
            }
        }
        array_push($parameters,CONST_CMN_WORKEXECUTIONSTATUS_COMPLETED);
        array_push($parameters,CONST_CMN_WORKEXECUTIONSTATUS_TERMINATED);
        array_push($parameters,CONST_CMN_WORKEXECUTIONSTATUS_CANCELLED);
        $distinctYears = DB::select("$queryForDistinctYears and coalesce(T1.CmnWorkExecutionStatusId,0) not in (?,?,?) order by case when T1.migratedworkid is null then year(T1.UploadedDate) else SUBSTRING_INDEX(SUBSTRING_INDEX(T1.migratedworkid,'/',-2),'/',1) end DESC",$parameters);
        
        array_push($parameters,Auth::user()->Id);
        $query2 = " union all select distinct(T1.Id),
        IF(a.`Name` IS NULL,IF(b.`EtlTenderId` IS NULL,'Under Process','Evaluated'),a.`Name`) tenderStatus,
         T1.`Method`,T1.`TenderStatus`,T1.`EGPTenderId`, T1.TenderOpeningDateAndTime,
          T2.Code as Category, T3.Code as Classification, T1.NameOfWork, T1.ContractPeriod,
           case when T1.migratedworkid is null then concat(a.Code,'/',year(T1.UploadedDate),'/',T1.WorkId) else T1.migratedworkid end as EtlTenderWorkId 
           from etltender T1 
           join cmncontractorworkcategory T2 on T1.CmnContractorCategoryId = T2.Id 
           join cmncontractorclassification T3 on T1.CmnContractorClassificationId = T3.Id 
           join (cmnprocuringagency Ad join sysuser Bd on Ad.Id = Bd.CmnProcuringAgencyId) on Ad.Id = T1.CmnProcuringAgencyId 
         LEFT JOIN `cmnlistitem` a ON T1.CmnWorkExecutionStatusId=a.id
        LEFT JOIN `etlevaluationscore` b ON T1.Id=b.`EtlTenderId`
        where T1.IsSPRRTender='N' and Bd.Id = ? and T1.TenderSource = 1 and T3.ReferenceNo in (3,4) and coalesce(T1.DeleteStatus,'N') <> 'Y'  and T1.CmnContractorClassificationId not in ('ef832830-c3ea-11e4-af9f-080027dcfac6')  ";
        if((bool)$workId || (bool)$contractorCategoryId || (bool)$contractorClassificationId) {
            if ((bool)$workId) {
                $query2 .= " and case when T1.migratedworkid is null then concat(a.Code,'/',year(T1.UploadedDate),'/',T1.WorkId) else T1.migratedworkid end LIKE '%$workId%'";
            }
            if ((bool)$contractorCategoryId) {
                $query2 .= " and T1.CmnContractorCategoryId = ?";
                array_push($parameters, $contractorCategoryId);
            }
            if ((bool)$contractorClassificationId) {
                $query2 .= " and T1.CmnContractorClassificationId = ?";
                array_push($parameters, $contractorClassificationId);
            }
        }
        $query1.=" and coalesce(CmnWorkExecutionStatusId,0) NOT IN (?,?,?)";
        $query2.=" and coalesce(CmnWorkExecutionStatusId,0) NOT IN (?,?,?)";
        array_push($parameters,CONST_CMN_WORKEXECUTIONSTATUS_COMPLETED);
        array_push($parameters,CONST_CMN_WORKEXECUTIONSTATUS_TERMINATED);
        array_push($parameters,CONST_CMN_WORKEXECUTIONSTATUS_CANCELLED);
        $uploadedTenders = array();
        $count = 0;
        foreach($distinctYears as $distinctYear):
            if($distinctYear->Year != null):
                $uploadedTenders[$distinctYear->Year] = DB::select($query1." and case when T1.migratedworkid is null
                 then year(T1.UploadedDate) else SUBSTRING_INDEX(SUBSTRING_INDEX(T1.migratedworkid,'/',-2),'/',1)
                  end ='$distinctYear->Year'".$query2." and case when T1.migratedworkid is null then year(T1.UploadedDate)
                   else SUBSTRING_INDEX(SUBSTRING_INDEX(T1.migratedworkid,'/',-2),'/',1)
                 end ='$distinctYear->Year'"." order by EtlTenderWorkId DESC",$parameters);
                if(count($uploadedTenders[$distinctYear->Year]) == 0):
                    unset($distinctYears[$count]);
                endif;
            else:
                unset($distinctYears[$count]);
            endif;
            $count++;
        endforeach;
//        echo "<pre>"; dd($distinctYears);
//
        $auditTrailActionMessage="Viewed work evaluation page";
        $this->auditTrailEtoolCinet(NULL,NULL,$auditTrailActionMessage);
		return View::make('etool.evaluationlist')
            ->with('distinctYears',$distinctYears)
            ->with('uploadedTenders',$uploadedTenders)
            ->with('contractorCategories',$contractorCategories)
            ->with('contractorClassifications',$contractorClassifications);
    }
//    Etltendernonresponsivecontractor


    public function cancelSmallTender(){
        $inputs = Input::all();

        DB::table('etltender')->where('Id',$inputs['tenderId'])->update(array(
            "TenderStatus"=>'Cancelled',
            "CancelledRemarks"=>$inputs['remarks'],
            "CancelledBy"=>Auth::user()->Id,
            "CancelledDate"=> date('Y-m-d G:i:s')
        ));
    
        $save = true;
        DB::commit();
        return Redirect::to('etl/workevaluationdetailssmallcontractors/'.$inputs['tenderId'])->with('savedsuccessmessage',$save?'Record has been saved':"Record has been updated");
    }


    public function cancelTender(){
        $inputs = Input::all();

        DB::table('etltender')->where('Id',$inputs['tenderId'])->update(array(
            "TenderStatus"=>'Cancelled',
            "CancelledRemarks"=>$inputs['remarks'],
            "CancelledBy"=>Auth::user()->Id,
            "CancelledDate"=> date('Y-m-d G:i:s')
        ));
    
        $save = true;
        DB::commit();
        return Redirect::to('etl/workevaluationdetails/'.$inputs['tenderId'])->with('savedsuccessmessage',$save?'Record has been saved':"Record has been updated");
    }


    
    //    Etltendernonresponsivecontractor
    public function pushToNonResponsive(){
        $inputs = Input::all();
        

        DB::table('etltenderbiddercontractor')
        ->where('Id','=',$inputs['contractorId'])
        ->where('EtlTenderId','=',$inputs['tenderId'])
        ->update(
            array('IsNonResponsive'=>'Y',
                'NonResponsiveRemarks'=>$inputs['remarks'],
                'NonResponsiveUpdatedBy'=>Auth::user()->Id,
                'NonResponsiveUpdatedOn'=>date('Y-m-d G:i:s')
            ));
        $save = true;
        return Redirect::to('etl/workevaluationdetails/'.$inputs['tenderId'])->with('savedsuccessmessage',$save?'Record has been updated':"Record has been updated");
        
    }
    public function pushToResponsive(){
        $inputs = Input::all();
        DB::table('etltenderbiddercontractor')
        ->where('Id','=',$inputs['contractorId'])
        ->where('EtlTenderId','=',$inputs['tenderId'])
        ->update(
            array('IsNonResponsive'=>'N',
            'ResponsiveUpdatedBy'=>Auth::user()->Id,
            'ResponsiveUpdatedOn'=>date('Y-m-d G:i:s')
        ));
        $save = true;
        return Redirect::to('etl/workevaluationdetails/'.$inputs['tenderId'])->with('savedsuccessmessage',$save?'Record has been updated':"Record has been updated");
        
    }

    


    public function addNonResponsive(){
        $inputs = Input::all();
        DB::table('etltendernonresponsivecontractor')->insert(array(
            "Id"=>$this->UUID(),
            "TenderId"=>$inputs['tenderId'],
            "CdbNo" => $inputs['cdbNo'],
            "FirmName" => $inputs['nameOfFirm'],
            "CreatedBy" => Auth::user()->Id
        ));
        $save = true;
        DB::commit();
        return Redirect::to('etl/workevaluationdetails/'.$inputs['tenderId'])->with('savedsuccessmessage',$save?'Record has been saved':"Record has been updated");
        
    }

        
	public function evaluationCommittee($tenderId){
        $tenders = DB::table('etltender as T1')
            ->join('cmnprocuringagency as T3','T1.CmnProcuringAgencyId','=','T3.Id')
            ->where('T1.Id','=',$tenderId)
            ->get(array('T1.Id',DB::raw("case when T1.migratedworkid is null then concat(T3.Code,'/',year(T1.UploadedDate),'/',T1.WorkId) else T1.migratedworkid end as WorkId"),'T1.NameOfWork','T1.DescriptionOfWork'));
		$committee = DB::table('etltendercommittee')->where('Type','=',1)->where('EtlTenderId','=',$tenderId)->get(array('Id','Name','Designation','EtlTenderId','Type'));
        if($committee == NULL){
            $committee = array(new EtlTenderCommitteeModel());
        }
        $auditTrailActionMessage="Viewed page to add evaluation committee for ".$tenders[0]->WorkId;
        $this->auditTrailEtoolCinet(NULL,NULL,$auditTrailActionMessage,$tenders[0]->WorkId);
        return View::make('etool.evaluationcommitee')
                ->with('tenders',$tenders)
                ->with('committee',$committee)
                ->with('tenderId',$tenderId);
	}
    public function awardingCommittee($tenderId){
        $tenders = DB::table('etltender as T1')
            ->join('cmnprocuringagency as T3','T1.CmnProcuringAgencyId','=','T3.Id')
            ->where('T1.Id','=',$tenderId)
            ->get(array('T1.Id',DB::raw("case when T1.migratedworkid is null then concat(T3.Code,'/',year(T1.UploadedDate),'/',T1.WorkId) else T1.migratedworkid end as WorkId"),'T1.NameOfWork','T1.DescriptionOfWork'));
        $committee = DB::table('etltendercommittee')->where('Type','=',2)->where('EtlTenderId','=',$tenderId)->get(array('Id','Name','Designation','EtlTenderId','Type'));
        if($committee == NULL){
            $committee = array(new EtlTenderCommitteeModel());
        }
        $auditTrailActionMessage="Viewed page to add awarding committee for ".$tenders[0]->WorkId;
        $this->auditTrailEtoolCinet(NULL,NULL,$auditTrailActionMessage,$tenders[0]->WorkId);
        return View::make('etool.awardingcommittee')
            ->with('tenders',$tenders)
            ->with('committee',$committee)
            ->with('tenderId',$tenderId);
    }
    public function saveCommittee(){
        $inputs = Input::all();
        $inputArray = array();
        DB::beginTransaction();
        DB::table('etltendercommittee')->where('EtlTenderId','=',$inputs['EtlTenderId'])->where('Type','=',$inputs['Type'])->delete();
        foreach($inputs as $key=>$value){
            if(gettype($value) == 'array'){
                $model = $key;
                foreach($value as $x=>$y){
                    $inputArray = array();
                    foreach($y as $a=>$b){
                        $inputArray[$a] = $b;
                    }
                    try{
                        $model::create($inputArray);
                    }catch(Exception $e){
                        DB::rollback();
                        throw $e;
                    }
                }
            }
        }
        DB::commit();
        $redirectRoute='awardingcommiteeetool';
        $auditTrailActionMessage="Awarding Committee added for ".Input::get('HiddenWorkId');
        if((int)$inputs['Type']==1){
            $redirectRoute='evaluationcommiteeetool';
            $auditTrailActionMessage="Evaluation Committee added for ".Input::get('HiddenWorkId');;
        }
        $this->auditTrailEtoolCinet(NULL,NULL,$auditTrailActionMessage,Input::get('HiddenWorkId'));
        return Redirect::to('etl/'.$redirectRoute.'/'.$inputArray['EtlTenderId'])->with('savedsuccessmessage','Record has been saved!');
    }
	public function details($tenderId = NULL){


        $nonResponsivePushedContractors = DB::select('SELECT
            `T1`.`Id`,
            GROUP_CONCAT(T3.CDBNo SEPARATOR ", ") AS CDBNo,
            GROUP_CONCAT(T3.NameOfFirm SEPARATOR ", ") AS NameOfFirm,
            `T1`.`JointVenture`,
            `T1`.`Id`,
            `T5`.`Code` AS `Category`,
            `T6`.`Code` AS `Classification`,
            (SELECT COUNT(*)FROM viewcontractorstrackrecords vw WHERE vw.`CDBNo`=T3.CDBNo 
                            AND vw.`WorkStatus`="Awarded")  totalWorkInHand
          FROM
            `etltenderbiddercontractor` AS `T1`
            INNER JOIN `etltenderbiddercontractordetail` AS `T2`
              ON `T2`.`EtlTenderBidderContractorId` = `T1`.`Id`
            INNER JOIN `crpcontractorfinal` AS `T3`
              ON `T2`.`CrpContractorFinalId` = `T3`.`Id`
            INNER JOIN `etltender` AS `T4`
              ON `T1`.`EtlTenderId` = `T4`.`Id`
            INNER JOIN `cmncontractorworkcategory` AS `T5`
              ON `T5`.`Id` = `T4`.`CmnContractorCategoryId`
            INNER JOIN `cmncontractorclassification` AS `T6`
              ON `T6`.`Id` = `T4`.`CmnContractorClassificationId`
          WHERE `T1`.`EtlTenderId` = "'.$tenderId.'" and IsNonResponsive="Y"
          GROUP BY `T1`.`Id`');


        $nonResponse = DB::table('etltendernonresponsivecontractor')->where('TenderId','=',$tenderId)->get();
        $scoreCount = DB::table('etlevaluationscore')->where('EtlTenderId','=',$tenderId)->count();
        $qualifyingScore = DB::table('etlqualifyingscore')->pluck('QualifyingScore');
        $lowestBid = DB::table('etltenderbiddercontractor as T1')
            ->join('etlevaluationscore as T2','T2.EtlTenderBidderContractorId','=','T1.Id')
            ->where('T1.EtlTenderId','=',$tenderId)
            ->whereRaw("(T2.Score1 + T2.Score2 + T2.Score3 + T2.Score4 + T2.Score5 + T2.Score6) >= $qualifyingScore")
            ->min('T1.FinancialBidQuoted');
        if((int)$scoreCount > 0){

            $contractors = DB::select('SELECT
            `T2`.`Id`,
            T1.Id AS ScoreId,
            `T2`.`ActualStartDate`,
            `T2`.`FinancialBidQuoted`,
            `T2`.`AwardedAmount`,
            `T2`.`ActualEndDate`,
            GROUP_CONCAT(A.CDBNo SEPARATOR ", ") AS CDBNo,
            `T1`.`Score10`,
            `T1`.`Score1`,
            `T1`.`Score2`,
            `T1`.`Score3`,
            `T1`.`Score4`,
            `T1`.`Score5`,
            `T1`.`Score6`,
            `T1`.`Score7`,
            `T1`.`Score8`,
            `T1`.`Score9`,
            `T1`.`IsBhutaneseEmp`,
            `T1`.`Score11`,
            GROUP_CONCAT(A.NameOfFirm SEPARATOR ", ") AS NameOfFirm,
            `T2`.`JointVenture`,
            `T2`.`Id`,
            `T5`.`Code` AS `Category`,
            `T6`.`Code` AS `Classification`,
            (SELECT COUNT(*)FROM viewcontractorstrackrecords vw WHERE vw.`CDBNo`=A.CDBNo 
                            AND vw.`WorkStatus`="Awarded")  totalWorkInHand
          FROM
            `etlevaluationscore` AS `T1`
            RIGHT JOIN `etltenderbiddercontractor` AS `T2`
              ON `T2`.`Id` = `T1`.`EtlTenderBidderContractorId`
            INNER JOIN `etltenderbiddercontractordetail` AS `T3`
              ON `T2`.`Id` = `T3`.`EtlTenderBidderContractorId`
            INNER JOIN `etltender` AS `T4`
              ON `T4`.`Id` = `T2`.`EtlTenderId`
            INNER JOIN `crpcontractorfinal` AS `A`
              ON `A`.`Id` = `T3`.`CrpContractorFinalId`
            INNER JOIN `cmncontractorworkcategory` AS `T5`
              ON `T5`.`Id` = `T4`.`CmnContractorCategoryId`
            INNER JOIN `cmncontractorclassification` AS `T6`
              ON `T6`.`Id` = `T4`.`CmnContractorClassificationId`
          WHERE `T2`.`EtlTenderId` = "'.$tenderId.'" and IsNonResponsive="N"
          GROUP BY `T1`.`Id`
          ORDER BY COALESCE(T1.Score10, 0) DESC');



            // $contractors = DB::table('etlevaluationscore as T1')
            //     ->rightJoin('etltenderbiddercontractor as T2','T2.Id','=','T1.EtlTenderBidderContractorId')
            //     ->join('etltenderbiddercontractordetail as T3','T2.Id','=','T3.EtlTenderBidderContractorId')
            //     ->join('etltender as T4','T4.Id','=','T2.EtlTenderId')
            //     ->join('crpcontractorfinal as A','A.Id','=','T3.CrpContractorFinalId')
            //     ->join('cmncontractorworkcategory as T5','T5.Id','=','T4.CmnContractorCategoryId')
            //     ->join('cmncontractorclassification as T6','T6.Id','=','T4.CmnContractorClassificationId')
            //     ->where('T2.EtlTenderId','=',$tenderId)
            //     ->groupBy('T1.Id')
            //     ->orderBy(DB::raw('coalesce(T1.Score10,0)'),'DESC')
            //     ->get(array('T2.Id',DB::raw('T1.Id as ScoreId'),'T2.ActualStartDate','T2.FinancialBidQuoted','T2.AwardedAmount','T2.ActualEndDate', DB::raw('group_concat(A.CDBNo SEPARATOR ", ") as CDBNo'),'T1.Score10','T1.Score1','T1.Score2','T1.Score3','T1.Score4','T1.Score5','T1.Score6','T1.Score7','T1.Score8','T1.Score9','T1.IsBhutaneseEmp','T1.Score11', DB::raw('group_concat(A.NameOfFirm SEPARATOR ", ") as NameOfFirm'),'T2.JointVenture','T2.Id','T5.Code as Category','T6.Code as Classification'));



        }else{
            $contractors = DB::select('SELECT
            `T1`.`Id`,
            GROUP_CONCAT(T3.CDBNo SEPARATOR ", ") AS CDBNo,
            GROUP_CONCAT(T3.NameOfFirm SEPARATOR ", ") AS NameOfFirm,
            `T1`.`JointVenture`,
            `T1`.`Id`,
            `T5`.`Code` AS `Category`,
            `T6`.`Code` AS `Classification`,
            (SELECT COUNT(*)FROM viewcontractorstrackrecords vw WHERE vw.`CDBNo`=T3.CDBNo
            AND vw.`WorkStatus`="Awarded") totalWorkInHand
          FROM
            `etltenderbiddercontractor` AS `T1`
            INNER JOIN `etltenderbiddercontractordetail` AS `T2`
              ON `T2`.`EtlTenderBidderContractorId` = `T1`.`Id`
            INNER JOIN `crpcontractorfinal` AS `T3`
              ON `T2`.`CrpContractorFinalId` = `T3`.`Id`
            INNER JOIN `etltender` AS `T4`
              ON `T1`.`EtlTenderId` = `T4`.`Id`
            INNER JOIN `cmncontractorworkcategory` AS `T5`
              ON `T5`.`Id` = `T4`.`CmnContractorCategoryId`
            INNER JOIN `cmncontractorclassification` AS `T6`
              ON `T6`.`Id` = `T4`.`CmnContractorClassificationId`
          WHERE `T1`.`EtlTenderId` = "'.$tenderId.'" and IsNonResponsive="N"
          GROUP BY `T1`.`Id`');

           // $contractors = DB::select("");


            // $contractors = DB::table('etltenderbiddercontractormm as T1')
            //     ->join('etltenderbiddercontractordetail as T2','T2.EtlTenderBidderContractorId','=','T1.Id')
            //     ->join('crpcontractorfinal as T3','T2.CrpContractorFinalId','=','T3.Id')
            //     ->join('etltender as T4','T1.EtlTenderId','=','T4.Id')
            //     ->join('cmncontractorworkcategory as T5','T5.Id','=','T4.CmnContractorCategoryId')
            //     ->join('cmncontractorclassification as T6','T6.Id','=','T4.CmnContractorClassificationId')
            //     ->where('T1.EtlTenderId','=',$tenderId)
            //     ->groupBy('T1.Id')
            //     ->get(array('T1.Id',DB::raw('group_concat(T3.CDBNo SEPARATOR ", ") as CDBNo'),DB::raw('group_concat(T3.NameOfFirm SEPARATOR ", ") as NameOfFirm'),'T1.JointVenture','T1.Id','T5.Code as Category','T6.Code as Classification'));
        }
   
        $tenders = DB::table('etltender as T1')
            ->join('cmncontractorclassification as T2','T1.CmnContractorClassificationId','=','T2.Id')
            ->join('cmncontractorworkcategory as T3','T1.CmnContractorCategoryId','=','T3.Id')
            ->join('sysuser as T4','T4.CmnProcuringAgencyId','=','T1.CmnProcuringAgencyId')
            ->join('cmnprocuringagency as T5','T5.Id','=','T4.CmnProcuringAgencyId')
            ->join('cmndzongkhag as T7','T1.CmnDzongkhagId','=','T7.Id')
            ->where('T1.Id','=',$tenderId)
            ->where('T4.Id','=',Auth::user()->Id)
            ->get(array('T1.Id','T1.TenderStatus','T1.CancelledRemarks','T1.CancelledDate','T1.Method','T1.CmnWorkExecutionStatusId','T1.ProjectEstimateCost','T1.TentativeStartDate','T1.TentativeEndDate',DB::raw("case when T1.migratedworkid is null then concat(T5.Code,'/',year(T1.UploadedDate),'/',T1.WorkId) else T1.migratedworkid end as WorkId"),"T5.Name as ProcuringAgency","T1.DescriptionOfWork","T1.NameOfWork",DB::raw("concat(T2.Name,' (',T2.Code,')') as Classification"),"T3.Code as Category","T7.NameEn as Dzongkhag"));
        
            //die();
        $auditTrailActionMessage="Clicked details button in evaluation to view the details of work Id ".$tenders[0]->WorkId;
        $this->auditTrailEtoolCinet(NULL,NULL,$auditTrailActionMessage,$tenders[0]->WorkId);
        return View::make('etool.workevaluationdetails')
                ->with('qualifyingScore',$qualifyingScore)
                ->with('contractors',$contractors)
                ->with('lowestBid',$lowestBid)
                ->with('tenderId',$tenderId)
                ->with('nonResponsivePushedContractors',$nonResponsivePushedContractors)
                ->with('scoreCount',$scoreCount)
                ->with('nonResponse',$nonResponse)
                ->with('tenderStatus',$tenders[0]->TenderStatus)
                ->with('CancelledRemarks',$tenders[0]->CancelledRemarks)
                ->with('CancelledDate',$tenders[0]->CancelledDate)
                ->with('tenders',$tenders);
    }
    
	public function addSmallContractors($tenderId,$contractorId=NULL){
        $edit = false;
        $hasEq = false;
        $hasHr = false;
        $contractorDocument = array(new AddContractorModel());
        $addContractors = array(new AddContractorModel());
        $contractorEquipments = array(new EtlContractorEquipmentModel());
        $contractorHR = array(new ContractorHumanResourceModel());
        $contractorList = array(new ContractorListModel());
        $contractorCDBNos = '';
        $cmnBanks = CmnListItemModel::financialInstitution()->get(array('Id','Name'));
        $tenders = DB::table('etltender as T1')
            ->join('cmnprocuringagency as T3','T1.CmnProcuringAgencyId','=','T3.Id')
            ->join('cmndzongkhag as T4','T4.Id','=','T1.CmnDzongkhagId')
            ->where('T1.Id','=',$tenderId)
            ->get(array('T1.Id', DB::raw("'' as CDBNo,case when T1.migratedworkid is null then concat(T3.Code,'/',year(T1.UploadedDate),'/',T1.WorkId) else T1.migratedworkid end as WorkId"),'T4.NameEn as Dzongkhag','T1.NameOfWork','T1.DescriptionOfWork'));
        $totalContractors = array(new ContractorListModel());
        $auditTrailActionMessage="Clicked Add Contractor button in evaluation for work Id ".$tenders[0]->WorkId;
        if((bool)$contractorId){
            $hasEq = true;
            $hasHr = true;
            $auditTrailActionMessage="Edited Contractor details in evaluation for work Id ".$tenders[0]->WorkId;
            $cmnBanks = array();
            $edit = true;
            $addContractors = DB::table('etltenderbiddercontractor as T1')
                                ->join('etltender as T2','T1.EtlTenderId','=','T2.Id')
                                ->join('cmnprocuringagency as T4','T4.Id','=','T2.CmnProcuringAgencyId')
                                ->join('cmndzongkhag as T5','T5.Id','=','T2.CmnDzongkhagId')
                                ->join('etltenderbiddercontractordetail as T6','T6.EtlTenderBidderContractorId','=','T1.Id')
                                ->join('crpcontractorfinal as T7','T7.Id','=','T6.CrpContractorFinalId')
                                ->where('T1.Id','=',$contractorId)
                                ->get(array('T1.Id','T5.NameEn as Dzongkhag','T7.CDBNo','T7.NameOfFirm',
                                DB::raw("T1.BhutaneseEmployement,case when T2.migratedworkid is null then concat(T4.Code,'/',year(T2.UploadedDate),'/',T2.WorkId) else T2.migratedworkid end as WorkId"),'T2.NameOfWork','T2.DescriptionOfWork','T1.JointVenture','T1.FinancialBidQuoted',DB::raw('coalesce(T1.CmnOwnershipTypeId,"800") as CmnOwnershipTypeId'),DB::raw('coalesce(T1.EmploymentOfVTI,"800") as EmploymentOfVTI'),DB::raw('coalesce(T1.CommitmentOfInternship,"800") as CommitmentOfInternship')));

              $contractorDocument = DB::table('etltenderbiddercontractor as T1')
                ->join('etltenderbiddercontractordetail as T6','T6.EtlTenderBidderContractorId','=','T1.Id')
                ->join('crpcontractorattachmentfinal as T7','T7.CrpContractorFinalId','=','T6.CrpContractorFinalId')
                ->where('T1.Id','=',$contractorId)
                ->get(array('T7.DocumentName','T7.DocumentPath'));


            //SELECT * FROM `crpcontractorattachmentfinal` a WHERE a.`CrpContractorFinalId`=?
            //echo "<pre>"; dd($addContractors);
            $totalContractors = DB::table('etltenderbiddercontractor as T1')
                                    ->join('etltenderbiddercontractordetail as T2','T1.Id','=','T2.EtlTenderBidderContractorId')
                                    ->where('T1.Id','=',$contractorId)
                                    ->orderBy('T2.Sequence')
                                    ->get(array('T2.CrpContractorFinalId','T2.Sequence'));
            foreach($totalContractors as $totalContractor):
                $cmnBanks[$totalContractor->Sequence] = DB::table('etlcontractorcapacity as T1')
                            ->join('cmnlistitem as T2','T1.CmnBankId','=','T2.Id')
                            ->join('etltenderbiddercontractordetail as T3','T3.EtlTenderBidderContractorId','=','T1.EtlTenderBidderContractorId')
                            ->where('T2.CmnListId','=',CONST_CMN_REFERENCE_FINANCIALINTITUTION)
                            ->where('T3.EtlTenderBidderContractorId','=',$contractorId)
                            ->where('T3.CrpContractorFinalId','=',$totalContractor->CrpContractorFinalId)
                            ->where('T1.Sequence','=',$totalContractor->Sequence)
                            ->get(array(DB::raw('distinct(T2.Id)'),'T2.Name',DB::raw('coalesce(T1.Sequence,0) as Sequence'),'T1.Amount'));
                if(empty($cmnBanks[$totalContractor->Sequence])){
                    $cmnBanks[$totalContractor->Sequence] = CmnListItemModel::financialInstitution()->get(array('Id','Name'));
                }
            endforeach;
            $contractorEquipments = DB::table('etlcontractorequipment as T1')
                                        //->join('etltier as T2','T1.EtlTierId','=','T2.Id')
                                        //->orderBy('T2.Name')
                                        ->where('T1.EtlTenderBidderContractorId','=',$contractorId)
                                        ->get(array('T1.Id','T1.RegistrationNo','T1.EtlTierId','T1.CmnEquipmentId','T1.Quantity','T1.OwnedOrHired','T1.Points'));
            if(empty($contractorEquipments)){
                $hasEq = false;
                $contractorEquipments = array(new EtlContractorEquipmentModel());
            }
            $contractorHR = DB::table('etlcontractorhumanresource as T1')
                //->join('etltier as T2','T1.EtlTierId','=','T2.Id')
                ->where('T1.EtlTenderBidderContractorId','=',$contractorId)
                //->orderBy('T2.Name')
                ->get(array('T1.Id','T1.CIDNo','T1.Name','T1.EtlTierId','T1.CmnDesignationId','T1.Qualification','T1.Points'));
            if(empty($contractorHR)){
                $hasHr = false;
                $contractorHR = array(new ContractorHumanResourceModel());
            }
            $contractorList = DB::table('etltenderbiddercontractordetail as T1')
                                ->join('crpcontractorfinal as T2','T1.CrpContractorFinalId','=','T2.Id')
                                ->where('T1.EtlTenderBidderContractorId','=',$contractorId)
                                ->orderBy('T1.Sequence')
                                ->get(array('T2.CDBNo','T2.NameOfFirm','T1.CrpContractorFinalId','T1.Stake','T1.Sequence'));
            $tenders = array();
            $contractorCDBNos = DB::table('etltenderbiddercontractordetail as T1')
                                    ->join('crpcontractorfinal as T2','T1.CrpContractorFinalId','=','T2.Id')
                                    ->where('T1.EtlTenderBidderContractorId','=',$contractorId)
                                    ->get(array(DB::raw('group_concat(T2.CDBNo SEPARATOR ", ") as CDBNo')));
        }
        $cmnOwnershipTypes = DB::table('etlbidevalutionparameters')->where('ReferenceNo','=',1001)->get(array('Name','Points'));
        // $vtiEmployment = DB::table('etlbidevalutionparameters')->where('ReferenceNo','=',1002)->get(array('Name','Points'));
        // $vtiInternship = DB::table('etlbidevalutionparameters')->where('ReferenceNo','=',1003)->get(array('Name','Points'));

        $bhutaneseEmployment = DB::table('etlbidevalutionparameters')->where('ReferenceNo','=',1004)->get(array('Name','Points'));
        $criteriaEquipments = DB::table('etlcriteriaequipment as T1')
                               // ->join('etltier as T2','T1.EtlTierId','=','T2.Id')
                                ->join('cmnequipment as T3','T1.CmnEquipmentId','=','T3.Id')
                                ->where('T1.EtlTenderId','=',$tenderId)
                                //->orderBy('T2.MaxPoints','DESC')
                                ->orderBy('T3.Name')
                                ->orderBy('T1.Points','DESC')
                                ->get(array('T3.Name as Equipment',DB::raw("coalesce(T3.IsRegistered,0) as IsRegistered"),'T1.Points','T1.Quantity'));
        $criteriaHR = DB::table('etlcriteriahumanresource as T1')
                       // ->join('etltier as T2','T1.EtlTierId','=','T2.Id')
                        ->join('cmnlistitem as T4','T1.CmnDesignationId','=','T4.Id')
                        ->where('T1.EtlTenderId','=',$tenderId)
                        //->orderBy('T2.MaxPoints','DESC')
                        ->orderBy('T4.Name')
                        ->orderBy('T1.Points','DESC')
                        ->get(array('T1.Qualification','T4.Name as Designation','T1.Points'));
        $hrTiers = DB::table('etltier as T1')
                    ->join('etlcriteriahumanresource as T2','T2.EtlTierId','=','T1.Id')
                    ->orderBy('T1.MaxPoints','DESC')
                    ->orderBy('T2.Points','DESC')
                    ->where('T2.EtlTenderId','=',$tenderId)
                    ->get(array(DB::raw('distinct(T1.Id)'),'T1.Name'));
        $eqTiers = DB::table('etltier as T1')
                        ->join('etlcriteriaequipment as T2','T2.EtlTierId','=','T1.Id')
                        ->orderBy('T1.MaxPoints','DESC')
                        ->orderBy('T2.Points','DESC')
                        ->where('T2.EtlTenderId','=',$tenderId)
                        ->get(array(DB::raw('distinct(T1.Id)'),'T1.Name'));
        $hrDesignations = DB::table('etlcriteriahumanresource as T1')
                            //->join('etltier as T2','T1.EtlTierId','=','T2.Id')
                            ->join('cmnlistitem as T4','T1.CmnDesignationId','=','T4.Id')
                            ->where('T1.EtlTenderId','=',$tenderId)
                            //->orderBy('T2.MaxPoints','DESC')
//                            ->orderBy('T4.Name')
                            ->orderBy('T1.Points','DESC')
                            ->groupBy('T4.Id')
                            ->get(array(DB::raw('T4.Id'),'T1.Id as EtlCriteriaHumanResourceId','T1.EtlTierId','T4.Name as Designation'));
        $hrQualifications = DB::table('etlcriteriahumanresource as T1')
           // ->join('etltier as T2','T1.EtlTierId','=','T2.Id')
            ->join('cmnlistitem as T4','T1.CmnDesignationId','=','T4.Id')
            ->where('T1.EtlTenderId','=',$tenderId)
            //->orderBy('T2.MaxPoints','DESC')
//            ->orderBy('T4.Name')
            ->orderBy('T1.Points','DESC')
            ->get(array('T1.CmnDesignationId','T1.EtlTierId','T1.Qualification','T1.Points'));
        $eqEquipments = DB::table('etlcriteriaequipment as T1')
                            //->join('etltier as T2','T1.EtlTierId','=','T2.Id')
                            ->join('cmnequipment as T3','T1.CmnEquipmentId','=','T3.Id')
                            ->where('T1.EtlTenderId','=',$tenderId)
                            //->orderBy('T2.MaxPoints','DESC')
                            ->orderBy('T1.Points','DESC')
                            ->get(array('T3.Id','T3.VehicleType',DB::raw("coalesce(T3.IsRegistered,0) as IsRegistered"), 'T3.Name as Equipment','T1.Id as EtlCriteriaEQId','T1.Quantity',DB::raw('T1.Points/T1.Quantity as Points'),'T1.EtlTierId'));
        $maxHrPoints = 0;
        foreach($hrTiers as $hrTier){
            $hrTierDesignations  = DB::table('etlcriteriahumanresource as T1')
                                    ->join('etltier as T2','T1.EtlTierId','=','T2.Id')
                                    ->join('cmnlistitem as T4','T1.CmnDesignationId','=','T4.Id')
                                    ->where('T1.EtlTenderId','=',$tenderId)
                                    ->where('T1.EtlTierId',$hrTier->Id)
                                    ->orderBy('T2.Name')
                                    ->groupBy('T4.Id')
                                    ->get(array('T4.Id'));
            foreach($hrTierDesignations as $hrTierDesignation){
                $maxHrTierPoints = DB::table('etlcriteriahumanresource as T1')
                                        ->where('T1.EtlTenderId','=',$tenderId)
                                        ->where('T1.EtlTierId',$hrTier->Id)
                                        ->where('T1.CmnDesignationId',$hrTierDesignation->Id)
                                        ->max('T1.Points');
                $maxHrPoints+=$maxHrTierPoints;
            }
        }
        $noOfHrTiers = DB::table('etlcriteriahumanresource as T1')
                            ->where('T1.EtlTenderId','=',$tenderId)
                            ->count(DB::raw('distinct(T1.EtlTierId)'));
        if($noOfHrTiers == 3){
            $maxHrPoints = 100;
        }

        $maxEqPoints = 0;
        foreach($eqTiers as $eqTier){
            $eqTierEquipments  = DB::table('etlcriteriaequipment as T1')
                                ->join('etltier as T2','T1.EtlTierId','=','T2.Id')
                                ->join('cmnequipment as T3','T1.CmnEquipmentId','=','T3.Id')
                                ->where('T1.EtlTenderId','=',$tenderId)
                                ->where('T1.EtlTierId',$eqTier->Id)
                                ->orderBy('T2.Name')
                                ->get(array('T3.Id'));
            foreach($eqTierEquipments as $eqTierEquipment){
                $maxEqTierPoints = DB::table('etlcriteriaequipment as T1')
                    ->where('T1.EtlTenderId','=',$tenderId)
                    ->where('T1.EtlTierId',$eqTier->Id)
                    ->where('T1.CmnEquipmentId',$eqTierEquipment->Id)
                    ->max('T1.Points');
                $maxEqPoints+=$maxEqTierPoints;
            }
        }
        $noOfEqTiers =  DB::table('etlcriteriaequipment as T1')
                            ->where('T1.EtlTenderId','=',$tenderId)
                            ->count(DB::raw('distinct(T1.EtlTierId)'));
        if($noOfEqTiers == 3){
            $maxEqPoints = 100;
        }
        $contractors = ContractorFinalModel::contractorHardListAll()->get(array('Id','NameOfFirm','CDBNo'));
        $this->auditTrailEtoolCinet(NULL,NULL,$auditTrailActionMessage,empty($tenders)?$addContractors[0]->WorkId:$tenders[0]->WorkId);
		return View::make('etool.evaluationaddsmallcontractors')
                    ->with('hasEq',$hasEq)
                    ->with('hasHr',$hasHr)
                    ->with('contractorCDBNos',$contractorCDBNos)
                    // ->with('vtiEmployment',$vtiEmployment)
                    // ->with('vtiInternship',$vtiInternship)
                    ->with('bhutaneseEmployment',$bhutaneseEmployment)
                    ->with('edit',$edit)
                    ->with('addContractors',$addContractors)
                    ->with('contractorEquipments',$contractorEquipments)
                    ->with('contractorHR',$contractorHR)
                    ->with('contractorList',$contractorList)
                    ->with('totalContractors',$totalContractors)
                    ->with('contractors',$contractors)
                    ->with('tenders',$tenders)
                    ->with('tenderId',$tenderId)
                    ->with('cmnOwnershipTypes',$cmnOwnershipTypes)
                    ->with('cmnBanks',$cmnBanks)
                    ->with('hrTiers',$hrTiers)
                    ->with('eqTiers',$eqTiers)
                    ->with('hrDesignations',$hrDesignations)
                    ->with('hrQualifications',$hrQualifications)
                    ->with('eqEquipments',$eqEquipments)
                    ->with('criteriaEquipments',$criteriaEquipments)
                    ->with('criteriaHR',$criteriaHR)
                    ->with('maxEqPoints',$maxEqPoints)
                    ->with('maxHrPoints',$maxHrPoints)
                    ->with('contractorDocument',$contractorDocument);
	
    }

	public function addContractors($tenderId,$contractorId=NULL){
        $edit = false;
        $hasEq = false;
        $hasHr = false;
        $addContractors = array(new AddContractorModel());
        $contractorEquipments = array(new EtlContractorEquipmentModel());
        $contractorHR = array(new ContractorHumanResourceModel());
        $contractorList = array(new ContractorListModel());
        $contractorCDBNos = '';
        $cmnBanks = CmnListItemModel::financialInstitution()->get(array('Id','Name'));
        $tenders = DB::table('etltender as T1')
            ->leftjoin('cmnprocuringagency as T3','T1.CmnProcuringAgencyId','=','T3.Id')
            ->leftjoin('cmndzongkhag as T4','T4.Id','=','T1.CmnDzongkhagId')
            ->leftjoin('etltenderbiddercontractor as a','a.EtlTenderId','=','T1.Id')
            ->leftjoin('etltenderbiddercontractordetail as b','b.EtlTenderBidderContractorId','=','a.Id')
            ->leftjoin('crpcontractorfinal as c','c.Id','=','b.CrpContractorFinalId')
            ->where('T1.Id','=',$tenderId)
            ->get(array('T1.Id',DB::raw("case when T1.migratedworkid is null
             then concat(T3.Code,'/',year(T1.UploadedDate),'/',T1.WorkId) 
             else T1.migratedworkid end as WorkId"),'T4.NameEn as Dzongkhag',
             'c.CDBNo','c.NameOfFirm','T1.NameOfWork','T1.DescriptionOfWork'));
        $totalContractors = array(new ContractorListModel());
        $auditTrailActionMessage="Clicked Add Contractor button in evaluation for work Id ".$tenders[0]->WorkId;
        $contractorAttachment= "";
        if((bool)$contractorId){
            $hasEq = true;
            $hasHr = true;
            $auditTrailActionMessage="Edited Contractor details in evaluation for work Id ".$tenders[0]->WorkId;
            $cmnBanks = array();
            $edit = true;
            $addContractors = DB::table('etltenderbiddercontractor as T1')
                                ->join('etltender as T2','T1.EtlTenderId','=','T2.Id')
                                ->join('cmnprocuringagency as T4','T4.Id','=','T2.CmnProcuringAgencyId')
                                ->join('cmndzongkhag as T5','T5.Id','=','T2.CmnDzongkhagId')
                                ->join('etltenderbiddercontractordetail as b','b.EtlTenderBidderContractorId','=','T1.Id')
                                ->join('crpcontractorfinal as c','c.Id','=','b.CrpContractorFinalId')
                                ->where('T1.Id','=',$contractorId)
                                ->get(array('T1.Id','T5.NameEn as Dzongkhag',
                                DB::raw("c.CDBNo,c.NameOfFirm,T1.BhutaneseEmployement,case when T2.migratedworkid is null then concat(T4.Code,'/',year(T2.UploadedDate),'/',T2.WorkId) else T2.migratedworkid end as WorkId"),'T2.NameOfWork','T2.DescriptionOfWork','T1.JointVenture','T1.FinancialBidQuoted',DB::raw('coalesce(T1.CmnOwnershipTypeId,"800") as CmnOwnershipTypeId'),DB::raw('coalesce(T1.EmploymentOfVTI,"800") as EmploymentOfVTI'),DB::raw('coalesce(T1.CommitmentOfInternship,"800") as CommitmentOfInternship')));
           
            $contractorAttachment = DB::table('etltenderbiddercontractor as T1')
                ->join('etltender as T2','T1.EtlTenderId','=','T2.Id')
                ->join('etltenderbiddercontractordetail as b','b.EtlTenderBidderContractorId','=','T1.Id')
                ->join('crpcontractorfinal as c','c.Id','=','b.CrpContractorFinalId')
                ->join('crpcontractorattachmentfinal as d','d.CrpContractorFinalId','=','c.Id')
                ->where('T1.Id','=',$contractorId)
                ->get(array('d.DocumentName','d.DocumentPath'));
            //echo "<pre>"; dd($addContractors);
            $totalContractors = DB::table('etltenderbiddercontractor as T1')
                                    ->join('etltenderbiddercontractordetail as T2','T1.Id','=','T2.EtlTenderBidderContractorId')
                                    ->where('T1.Id','=',$contractorId)
                                    ->orderBy('T2.Sequence')
                                    ->get(array('T2.CrpContractorFinalId','T2.Sequence'));
            foreach($totalContractors as $totalContractor):
                $cmnBanks[$totalContractor->Sequence] = DB::table('etlcontractorcapacity as T1')
                            ->join('cmnlistitem as T2','T1.CmnBankId','=','T2.Id')
                            ->join('etltenderbiddercontractordetail as T3','T3.EtlTenderBidderContractorId','=','T1.EtlTenderBidderContractorId')
                            ->where('T2.CmnListId','=',CONST_CMN_REFERENCE_FINANCIALINTITUTION)
                            ->where('T3.EtlTenderBidderContractorId','=',$contractorId)
                            ->where('T3.CrpContractorFinalId','=',$totalContractor->CrpContractorFinalId)
                            ->where('T1.Sequence','=',$totalContractor->Sequence)
                            ->get(array(DB::raw('distinct(T2.Id)'),'T2.Name',DB::raw('coalesce(T1.Sequence,0) as Sequence'),'T1.Amount'));
                if(empty($cmnBanks[$totalContractor->Sequence])){
                    $cmnBanks[$totalContractor->Sequence] = CmnListItemModel::financialInstitution()->get(array('Id','Name'));
                    $cmnBanks = CmnListItemModel::financialInstitution()->get(array('Id','Name'));
                }
            endforeach;
            $contractorEquipments = DB::table('etlcontractorequipment as T1')
                                        ->join('etltier as T2','T1.EtlTierId','=','T2.Id')
                                        ->orderBy('T2.Name')
                                        ->where('T1.EtlTenderBidderContractorId','=',$contractorId)
                                        ->get(array('T1.Id','T1.RegistrationNo','T1.EtlTierId','T1.CmnEquipmentId','T1.Quantity','T1.OwnedOrHired','T1.Points'));
            if(empty($contractorEquipments)){
                $hasEq = false;
                $contractorEquipments = array(new EtlContractorEquipmentModel());
            }
            $contractorHR = DB::table('etlcontractorhumanresource as T1')
                ->join('etltier as T2','T1.EtlTierId','=','T2.Id')
                ->where('T1.EtlTenderBidderContractorId','=',$contractorId)
                ->orderBy('T2.Name')
                ->get(array('T1.Id','T1.CIDNo','T1.Name','T1.EtlTierId','T1.CmnDesignationId','T1.Qualification','T1.Points'));
            if(empty($contractorHR)){
                $hasHr = false;
                $contractorHR = array(new ContractorHumanResourceModel());
            }
            $contractorList = DB::table('etltenderbiddercontractordetail as T1')
                                ->join('crpcontractorfinal as T2','T1.CrpContractorFinalId','=','T2.Id')
                                ->where('T1.EtlTenderBidderContractorId','=',$contractorId)
                                ->orderBy('T1.Sequence')
                                ->get(array('T2.CDBNo','T2.NameOfFirm','T2.TradeLicenseNo','T2.TPN','T1.CrpContractorFinalId','T1.Stake','T1.Sequence'));
            $tenders = array();
            $contractorCDBNos = DB::table('etltenderbiddercontractordetail as T1')
                                    ->join('crpcontractorfinal as T2','T1.CrpContractorFinalId','=','T2.Id')
                                    ->where('T1.EtlTenderBidderContractorId','=',$contractorId)
                                    ->get(array(DB::raw('group_concat(T2.CDBNo SEPARATOR ", ") as CDBNo')));
        }
        $cmnOwnershipTypes = DB::table('etlbidevalutionparameters')->where('ReferenceNo','=',1001)->get(array('Name','Points'));
        // $vtiEmployment = DB::table('etlbidevalutionparameters')->where('ReferenceNo','=',1002)->get(array('Name','Points'));
        // $vtiInternship = DB::table('etlbidevalutionparameters')->where('ReferenceNo','=',1003)->get(array('Name','Points'));
        
        $bhutaneseEmployment = DB::table('etlbidevalutionparameters')->where('ReferenceNo','=',1004)->get(array('Name','Points'));
        $criteriaEquipments = DB::table('etlcriteriaequipment as T1')
                                ->join('etltier as T2','T1.EtlTierId','=','T2.Id')
                                ->join('cmnequipment as T3','T1.CmnEquipmentId','=','T3.Id')
                                ->where('T1.EtlTenderId','=',$tenderId)
                                ->orderBy('T2.MaxPoints','DESC')
                                ->orderBy('T3.Name')
                                ->orderBy('T1.Points','DESC')
                                ->get(array('T2.Name as Tier','T3.Name as Equipment',DB::raw("coalesce(T3.IsRegistered,0) as IsRegistered"),'T1.Points','T1.Quantity'));
        $criteriaHR = DB::table('etlcriteriahumanresource as T1')
                        ->join('etltier as T2','T1.EtlTierId','=','T2.Id')
                        ->join('cmnlistitem as T4','T1.CmnDesignationId','=','T4.Id')
                        ->where('T1.EtlTenderId','=',$tenderId)
                        ->orderBy('T2.MaxPoints','DESC')
                        ->orderBy('T4.Name')
                        ->orderBy('T1.Points','DESC')
                        ->get(array('T2.Name as Tier','T1.Qualification','T4.Name as Designation','T1.Points'));
        $hrTiers = DB::table('etltier as T1')
                    ->join('etlcriteriahumanresource as T2','T2.EtlTierId','=','T1.Id')
                    ->orderBy('T1.MaxPoints','DESC')
                    ->orderBy('T2.Points','DESC')
                    ->where('T2.EtlTenderId','=',$tenderId)
                    ->get(array(DB::raw('distinct(T1.Id)'),'T1.Name'));
        $eqTiers = DB::table('etltier as T1')
                        ->join('etlcriteriaequipment as T2','T2.EtlTierId','=','T1.Id')
                        ->orderBy('T1.MaxPoints','DESC')
                        ->orderBy('T2.Points','DESC')
                        ->where('T2.EtlTenderId','=',$tenderId)
                        ->get(array(DB::raw('distinct(T1.Id)'),'T1.Name'));
        $hrDesignations = DB::table('etlcriteriahumanresource as T1')
                            ->join('etltier as T2','T1.EtlTierId','=','T2.Id')
                            ->join('cmnlistitem as T4','T1.CmnDesignationId','=','T4.Id')
                            ->where('T1.EtlTenderId','=',$tenderId)
                            ->orderBy('T2.MaxPoints','DESC')
//                            ->orderBy('T4.Name')
                            ->orderBy('T1.Points','DESC')
                            ->groupBy('T4.Id')
                            ->get(array(DB::raw('T4.Id'),'T1.Id as EtlCriteriaHumanResourceId','T1.EtlTierId','T4.Name as Designation'));
        $hrQualifications = DB::table('etlcriteriahumanresource as T1')
            ->join('etltier as T2','T1.EtlTierId','=','T2.Id')
            ->join('cmnlistitem as T4','T1.CmnDesignationId','=','T4.Id')
            ->where('T1.EtlTenderId','=',$tenderId)
            ->orderBy('T2.MaxPoints','DESC')
//            ->orderBy('T4.Name')
            ->orderBy('T1.Points','DESC')
            ->get(array('T1.CmnDesignationId','T1.EtlTierId','T1.Qualification','T1.Points'));
        $eqEquipments = DB::table('etlcriteriaequipment as T1')
                            ->join('etltier as T2','T1.EtlTierId','=','T2.Id')
                            ->join('cmnequipment as T3','T1.CmnEquipmentId','=','T3.Id')
                            ->where('T1.EtlTenderId','=',$tenderId)
                            ->orderBy('T2.MaxPoints','DESC')
                            ->orderBy('T1.Points','DESC')
                            ->get(array('T3.Id','T3.VehicleType',DB::raw("coalesce(T3.IsRegistered,0) as IsRegistered"), 'T3.Name as Equipment','T1.Id as EtlCriteriaEQId','T1.Quantity',DB::raw('T1.Points/T1.Quantity as Points'),'T1.EtlTierId'));
        $maxHrPoints = 0;
        foreach($hrTiers as $hrTier){
            $hrTierDesignations  = DB::table('etlcriteriahumanresource as T1')
                                    ->join('etltier as T2','T1.EtlTierId','=','T2.Id')
                                    ->join('cmnlistitem as T4','T1.CmnDesignationId','=','T4.Id')
                                    ->where('T1.EtlTenderId','=',$tenderId)
                                    ->where('T1.EtlTierId',$hrTier->Id)
                                    ->orderBy('T2.Name')
                                    ->groupBy('T4.Id')
                                    ->get(array('T4.Id'));
            foreach($hrTierDesignations as $hrTierDesignation){
                $maxHrTierPoints = DB::table('etlcriteriahumanresource as T1')
                                        ->where('T1.EtlTenderId','=',$tenderId)
                                        ->where('T1.EtlTierId',$hrTier->Id)
                                        ->where('T1.CmnDesignationId',$hrTierDesignation->Id)
                                        ->max('T1.Points');
                $maxHrPoints+=$maxHrTierPoints;
            }
        }
        $noOfHrTiers = DB::table('etlcriteriahumanresource as T1')
                            ->where('T1.EtlTenderId','=',$tenderId)
                            ->count(DB::raw('distinct(T1.EtlTierId)'));
        if($noOfHrTiers == 4){
            $maxHrPoints = 100;
        }
        $maxEqPoints = 0;
        foreach($eqTiers as $eqTier){
            $eqTierEquipments  = DB::table('etlcriteriaequipment as T1')
                                ->join('etltier as T2','T1.EtlTierId','=','T2.Id')
                                ->join('cmnequipment as T3','T1.CmnEquipmentId','=','T3.Id')
                                ->where('T1.EtlTenderId','=',$tenderId)
                                ->where('T1.EtlTierId',$eqTier->Id)
                                ->orderBy('T2.Name')
                                ->get(array('T3.Id'));
            foreach($eqTierEquipments as $eqTierEquipment){
                $maxEqTierPoints = DB::table('etlcriteriaequipment as T1')
                    ->where('T1.EtlTenderId','=',$tenderId)
                    ->where('T1.EtlTierId',$eqTier->Id)
                    ->where('T1.CmnEquipmentId',$eqTierEquipment->Id)
                    ->max('T1.Points');
                $maxEqPoints+=$maxEqTierPoints;
            }
        }
        $noOfEqTiers =  DB::table('etlcriteriaequipment as T1')
                            ->where('T1.EtlTenderId','=',$tenderId)
                            ->count(DB::raw('distinct(T1.EtlTierId)'));
        if($noOfEqTiers == 3){
            $maxEqPoints = 100;
        }
        $contractors = ContractorFinalModel::contractorHardListAll()->get(array('Id','NameOfFirm','CDBNo'));
        $this->auditTrailEtoolCinet(NULL,NULL,$auditTrailActionMessage,empty($tenders)?$addContractors[0]->WorkId:$tenders[0]->WorkId);

//        return $contractorList;
        
        return View::make('etool.evaluationaddcontractors')
                    ->with('hasEq',$hasEq)
                    ->with('hasHr',$hasHr)
                    ->with('contractorCDBNos',$contractorCDBNos)
                    ->with('contractorAttachment',$contractorAttachment)
                    
                    // ->with('vtiEmployment',$vtiEmployment)
                    // ->with('vtiInternship',$vtiInternship)
                    ->with('bhutaneseEmployment',$bhutaneseEmployment)
                    ->with('edit',$edit)
                    ->with('addContractors',$addContractors)
                    ->with('contractorEquipments',$contractorEquipments)
                    ->with('contractorHR',$contractorHR)
                    ->with('contractorList',$contractorList)
                    ->with('totalContractors',$totalContractors)
                    ->with('contractors',$contractors)
                    ->with('tenders',$tenders)
                    ->with('tenderId',$tenderId)
                    ->with('cmnOwnershipTypes',$cmnOwnershipTypes)
                    ->with('cmnBanks',$cmnBanks)
                    ->with('hrTiers',$hrTiers)
                    ->with('eqTiers',$eqTiers)
                    ->with('hrDesignations',$hrDesignations)
                    ->with('hrQualifications',$hrQualifications)
                    ->with('eqEquipments',$eqEquipments)
                    ->with('criteriaEquipments',$criteriaEquipments)
                    ->with('criteriaHR',$criteriaHR)
                    ->with('maxEqPoints',$maxEqPoints)
                    ->with('maxHrPoints',$maxHrPoints);
	}
    
	public function processResult($tenderId){
        
        $contractorsList = DB::table('etltenderbiddercontractor')
                                ->where('EtlTenderId','=',$tenderId)
                                ->where('IsNonResponsive','=','N')
                                ->get(array('Id','JointVenture'));
        $dateOfSaleOfTender = DB::table('etltender')->whereId($tenderId)->pluck('TentativeStartDate');
        $year = date_format(date_create($dateOfSaleOfTender),'Y'); //Last being the current year in which the work is being tendered. Pg 9
        $fiveYearsAgo = $year - 5;
        $fourYearsAgo = $year - 4;
        $threeYearsAgo = $year - 3;
        $oneYearAgo = $year - 1;  
        $startDate = "$fiveYearsAgo-01-01";
        $endDate = "$year-12-31";
        $startDate2 = "$threeYearsAgo-01-01"; //2012-01-01
        $endDate2 = "$oneYearAgo-12-31"; //2014-12-31
        $contractorClassificationId = DB::table('etltender')->where('Id','=',$tenderId)->pluck('CmnContractorClassificationId');
        $workIdForAuditTrail=DB::table('etltender as T1')->join('cmnprocuringagency as T2','T1.CmnProcuringAgencyId','=','T2.Id')
                    ->where('T1.Id',$tenderId)
                    ->get(array(DB::raw("case when T1.migratedworkid is null then concat(T2.Code,'/',year(T1.UploadedDate),'/',T1.WorkId) else T1.migratedworkid end as WorkId")));

        $contractorCategoryId = DB::table('etltender')->where('Id','=',$tenderId)->pluck('CmnContractorCategoryId');
        $tenderValue = DB::table('etltender')->where('Id','=',$tenderId)->pluck('ProjectEstimateCost');
        $workExperienceResultArray = array();
        $equipmentResultArray = array();
        $hrResultArray = array();
        $bidCapacityResultArray = array();
        $creditLineResultArray = array();
        $creditLineArray = array();
        $pointsArray = array();
        $bidEvaluationPoints1Array = array();
        $bidEvaluationPoints2Array = array();
        $bidEvaluationPoints3Array = array();

        $hrTiers = DB::table('etltier as T1')
            ->join('etlcriteriahumanresource as T2','T2.EtlTierId','=','T1.Id')
            ->orderBy('T2.DisplayOrder')
            ->where('T2.EtlTenderId','=',$tenderId)
            ->get(array(DB::raw('distinct(T1.Id)'),'T1.Name'));
        $eqTiers = DB::table('etltier as T1')
            ->join('etlcriteriaequipment as T2','T2.EtlTierId','=','T1.Id')
            ->orderBy('T2.DisplayOrder')
            ->where('T2.EtlTenderId','=',$tenderId)
            ->get(array(DB::raw('distinct(T1.Id)'),'T1.Name'));
        $maxHrPoints = 0;
       
        foreach($hrTiers as $hrTier){
            $hrTierDesignations  = DB::table('etlcriteriahumanresource as T1')
                ->join('etltier as T2','T1.EtlTierId','=','T2.Id')
                ->join('cmnlistitem as T4','T1.CmnDesignationId','=','T4.Id')
                ->where('T1.EtlTenderId','=',$tenderId)
                ->where('T1.EtlTierId',$hrTier->Id)
                ->orderBy('T2.Name')
                ->groupBy('T4.Id')
                ->get(array('T4.Id'));
            foreach($hrTierDesignations as $hrTierDesignation){
                $maxHrTierPoints = DB::table('etlcriteriahumanresource as T1')
                    ->where('T1.EtlTenderId','=',$tenderId)
                    ->where('T1.EtlTierId',$hrTier->Id)
                    ->where('T1.CmnDesignationId',$hrTierDesignation->Id)
                    ->max('T1.Points');
                $maxHrPoints+=$maxHrTierPoints;
            }
        }
        $noOfHrTiers = DB::table('etlcriteriahumanresource as T1')
            ->where('T1.EtlTenderId','=',$tenderId)
            ->count(DB::raw('distinct(T1.EtlTierId)'));
        if($noOfHrTiers == 3){
            $maxHrPoints = 100;
        }
        $maxEqPoints = 0;
        foreach($eqTiers as $eqTier){
            $eqTierEquipments  = DB::table('etlcriteriaequipment as T1')
                ->join('etltier as T2','T1.EtlTierId','=','T2.Id')
                ->join('cmnequipment as T3','T1.CmnEquipmentId','=','T3.Id')
                ->where('T1.EtlTenderId','=',$tenderId)
                ->where('T1.EtlTierId',$eqTier->Id)
                ->orderBy('T2.Name')
                ->get(array('T3.Id'));
            foreach($eqTierEquipments as $eqTierEquipment){
                $maxEqTierPoints = DB::table('etlcriteriaequipment as T1')
                    ->where('T1.EtlTenderId','=',$tenderId)
                    ->where('T1.EtlTierId',$eqTier->Id)
                    ->where('T1.CmnEquipmentId',$eqTierEquipment->Id)
                    ->max('T1.Points');
                $maxEqPoints+=$maxEqTierPoints;
            }
        }
        $noOfEqTiers =  DB::table('etlcriteriaequipment as T1')
            ->where('T1.EtlTenderId','=',$tenderId)
            ->count(DB::raw('distinct(T1.EtlTierId)'));
        if($noOfEqTiers == 3){
            $maxEqPoints = 100;
        }
        foreach($contractorsList as $contractor){
           
            $contractors = DB::table('etltenderbiddercontractordetail')
                                ->where('EtlTenderBidderContractorId','=',$contractor->Id)
                                ->get(array('CrpContractorFinalId','Stake','Sequence'));
	     $quotedAmount = DB::table('etltenderbiddercontractor')->where('Id',$contractor->Id)->where('EtlTenderId',$tenderId)->pluck('FinancialBidQuoted');
            $loopCount = 1;
            $workExperienceResultArray[$contractor->Id] = 0;
            $performanceResultArray[$contractor->Id] = 0;
            $bidCapacityResultArray[$contractor->Id] = 0;
            $creditLineResultArray[$contractor->Id] = 0;
            $creditLineArray[$contractor->Id] = 0;
            foreach($contractors as $singleContractor) {
                $count = TenderModel::countContractorTenderCompleted($singleContractor->CrpContractorFinalId, $startDate, $endDate, $contractorClassificationId, $contractorCategoryId)->count() + CrpBiddingFormModel::countContractorWorkCompleted($singleContractor->CrpContractorFinalId, $startDate, $endDate, $contractorClassificationId, $contractorCategoryId)->count();
                $workExperienceResultArray[$contractor->Id] += $this->calculatePointsOnSimilarWorkExperience($count, $singleContractor->CrpContractorFinalId, $contractorClassificationId, $contractorCategoryId, $startDate, $endDate, $tenderValue,$singleContractor->Stake);
                $performanceResultArray[$contractor->Id] += $this->calculatePointsOnPerformance($count, $singleContractor->CrpContractorFinalId, $contractorClassificationId, $contractorCategoryId, $startDate, $endDate, $tenderValue,$singleContractor->Stake);
                $creditLineArray[$contractor->Id] += $this->calculateCreditLine($singleContractor->CrpContractorFinalId, $tenderId, $singleContractor->Stake,$singleContractor->Sequence);
                if ($loopCount == 1){
                    $equipmentResultArray[$contractor->Id] = $this->calculatePointsOnEquipment($tenderId, $singleContractor->CrpContractorFinalId,$maxEqPoints);
                    $hrResultArray[$contractor->Id] = $this->calculatePointsOnHR($tenderId, $singleContractor->CrpContractorFinalId,$maxHrPoints);
                }
                $bidCapicityForContractor = $this->calculateBidCapacity($singleContractor->CrpContractorFinalId,$startDate2,$endDate2,$contractorClassificationId, $contractorCategoryId,$singleContractor->Stake,$tenderId,(int)$quotedAmount);
                
                if(gettype($bidCapicityForContractor) == 'array'){
                    DB::table('etlevaluationscore')->where('EtlTenderId','=',$tenderId)->delete();
                    return Redirect::back()->with('customerrormessage',$bidCapicityForContractor['message']);
                }
                $bidCapacityResultArray[$contractor->Id] += $bidCapicityForContractor;
                $loopCount++;
            }
            $bidCapacityResultArray[$contractor->Id] = $this->calculateBidCapacityResult($bidCapacityResultArray[$contractor->Id],(int)$quotedAmount);
            
            //die($bidCapacityResultArray[$contractor->Id].'/'. $bidCapacityResultArray[$contractor->Id]);

            $creditLineResultArray[$contractor->Id] = $this->calculatePointsOnCreditLine($tenderId,$tenderValue,$creditLineArray[$contractor->Id]);
            $pointsArray[$contractor->Id] = $creditLineResultArray[$contractor->Id]+$bidCapacityResultArray[$contractor->Id] + $hrResultArray[$contractor->Id]+ $equipmentResultArray[$contractor->Id]+$performanceResultArray[$contractor->Id]+$workExperienceResultArray[$contractor->Id];
            $qualifyingScore = DB::table('etlqualifyingscore')->pluck('QualifyingScore');
//            if($pointsArray[$contractor->Id] >= $qualifyingScore){
                $bidEvaluationPoints1Array[$contractor->Id] = $this->calculateBidEvaluationPoints1($contractor->Id);
                $bidEvaluationPoints4Array[$contractor->Id] = $this->calculateBidEvaluationPoints4($contractor->Id);
                //EmploymentOfVTI
                //$bidEvaluationPoints2Array[$contractor->Id] = $this->calculateBidEvaluationPoints2($contractor->Id);
                //CommitmentOfInternship
                //$bidEvaluationPoints3Array[$contractor->Id] = $this->calculateBidEvaluationPoints3($contractor->Id);
//            }else{
//                $bidEvaluationPoints1Array[$contractor->Id] = NULL;
//                $bidEvaluationPoints2Array[$contractor->Id] = NULL;
//                $bidEvaluationPoints3Array[$contractor->Id] = NULL;
//            }
            try{
                $id = DB::table('etlevaluationscore')->where('EtlTenderId','=',$tenderId)->where('EtlTenderBidderContractorId','=',$contractor->Id)->pluck('Id');
                if(isset($id) && !empty($id)){
                    
                    DB::table('etlevaluationscore')->where('Id',$id)->update(array(
                        "EtlTenderId"=>$tenderId,
                        "EtlTenderBidderContractorId"=>$contractor->Id,
                        "Score1" => $workExperienceResultArray[$contractor->Id],
                        "Score2" => $equipmentResultArray[$contractor->Id],
                        "Score3" => $hrResultArray[$contractor->Id],
                        "Score4" => $performanceResultArray[$contractor->Id],
                        "Score5" => $bidCapacityResultArray[$contractor->Id],
                        "Score6" => $creditLineResultArray[$contractor->Id],
                        "Score7" => $bidEvaluationPoints1Array[$contractor->Id],
                        // "Score8" => $bidEvaluationPoints2Array[$contractor->Id],
                        // "Score9" => $bidEvaluationPoints3Array[$contractor->Id],
                        "Score11" => $bidEvaluationPoints4Array[$contractor->Id],
                        "EditedBy" => Auth::user()->Id,
                        "EditedOn" => date('Y-m-d G:i:s')
                    ));
                }else{
                    DB::table('etlevaluationscore')->insert(array(
                        "Id"=>$this->UUID(),
                        "EtlTenderId"=>$tenderId,
                        "EtlTenderBidderContractorId"=>$contractor->Id,
                        "Score1" => $workExperienceResultArray[$contractor->Id],
                        "Score2" => $equipmentResultArray[$contractor->Id],
                        "Score3" => $hrResultArray[$contractor->Id],
                        "Score4" => $performanceResultArray[$contractor->Id],
                        "Score5" => $bidCapacityResultArray[$contractor->Id],
                        "Score6" => $creditLineResultArray[$contractor->Id],
                        "Score7" => $bidEvaluationPoints1Array[$contractor->Id],
                        // "Score8" => $bidEvaluationPoints2Array[$contractor->Id],
                        // "Score9" => $bidEvaluationPoints3Array[$contractor->Id],
                        "Score11" => $bidEvaluationPoints4Array[$contractor->Id],
                        "CreatedBy" => Auth::user()->Id,
                        "CreatedOn" => date('Y-m-d G:i:s')
                    ));
                }

            }catch(Exception $e){
                throw $e;
            }
        }

        $lowestBid = DB::table('etltenderbiddercontractor as T1')
            ->join('etlevaluationscore as T2','T2.EtlTenderBidderContractorId','=','T1.Id')
            ->where('T1.EtlTenderId','=',$tenderId)
            ->where(DB::raw("(Score1+Score2+Score3+Score4+Score5+Score6)"),'>=',DB::raw("$qualifyingScore"))
            ->min('T1.FinancialBidQuoted');
        $scoresForTender =  DB::table('etltenderbiddercontractor as T1')
                                ->join('etlevaluationscore as T2','T2.EtlTenderBidderContractorId','=','T1.Id')
                                ->where('T1.EtlTenderId','=',$tenderId)
                                ->where(DB::raw("(Score1+Score2+Score3+Score4+Score5+Score6)"),'>=',DB::raw("$qualifyingScore"))
                                //OLD QUERY ->get(array('T1.FinancialBidQuoted','T2.Id',DB::raw('(T2.Score7 + T2.Score8 + T2.Score9) as PricePreference')));
                                ->get(array('T1.FinancialBidQuoted','T2.Id',DB::raw('(T2.Score7 + T2.Score11) as PricePreference')));
        foreach($scoresForTender as $score){
            $score10 = (90 * ($lowestBid/$score->FinancialBidQuoted)) + (.1 * $score->PricePreference);
            DB::table('etlevaluationscore')->where('Id','=',$score->Id)->update(array('Score10'=>$score10));
        }
        $auditTrailActionMessage="Clicked Process Result button for Work Id ".$workIdForAuditTrail[0]->WorkId;
        $this->auditTrailEtoolCinet(NULL,NULL,$auditTrailActionMessage,$workIdForAuditTrail[0]->WorkId);

        DB::table('etlevaluationtrack')->insert(array('Id'=>$this->UUID(),'WorkId'=>$workIdForAuditTrail[0]->WorkId,'Operation'=>'Process','User'=>Auth::user()->FullName,'OperationTime'=>date('Y-m-d G:i:s')));

        return Redirect::to('etl/workevaluationdetails/'.$tenderId)->with('savedsuccessmessage','Result has been processed');
	}
    public function resetResult($tenderId){
        DB::table('etlevaluationscore')->where('EtlTenderId','=',$tenderId)->delete();
        $workIdForAuditTrail=DB::table('etltender as T1')->join('cmnprocuringagency as T2','T1.CmnProcuringAgencyId','=','T2.Id')
                    ->where('T1.Id',$tenderId)
                    ->get(array(DB::raw("case when T1.migratedworkid is null then concat(T2.Code,'/',year(T1.UploadedDate),'/',T1.WorkId) else T1.migratedworkid end as WorkId")));
        $auditTrailActionMessage="Clicked Reset Result button for Work Id ".$workIdForAuditTrail[0]->WorkId;
        $this->auditTrailEtoolCinet(NULL,NULL,$auditTrailActionMessage,$workIdForAuditTrail[0]->WorkId);
        DB::table('etltender')->where('Id',$tenderId)->update(array('CmnWorkExecutionStatusId'=>NULL));
        DB::table('etltenderbiddercontractor')->where('EtlTenderId',$tenderId)->update(array('ActualStartDate'=>NULL,'ActualEndDate'=>NULL,'AwardedAmount'=>NULL,'Remarks'=>NULL));
        DB::table('etlevaluationtrack')->insert(array('Id'=>$this->UUID(),'WorkId'=>$workIdForAuditTrail[0]->WorkId,'Operation'=>'Reset Result','User'=>Auth::user()->FullName,'OperationTime'=>date('Y-m-d G:i:s')));
        return Redirect::to('etl/workevaluationdetails/'.$tenderId)->with('savedsuccessmessage','Result has been reset');;
    }
	public function pointDetails($tenderId,$contractorId){
        $tenderDetails = DB::table('etltender as T1')
                            ->join('cmnprocuringagency as A','T1.CmnProcuringAgencyId','=','A.Id')
                            ->join('etltenderbiddercontractor as T2','T1.Id','=','T2.EtlTenderId')
                            ->join('etltenderbiddercontractordetail as T3','T3.EtlTenderBidderContractorId','=','T2.Id')
                            ->join('crpcontractorfinal as T4','T3.CrpContractorFinalId','=','T4.Id')
                            ->where('T1.Id','=',$tenderId)
                            ->where('T2.Id','=',$contractorId)
                            ->get(array(DB::raw("case when T1.migratedworkid is null then concat(a.Code,'/',year(T1.UploadedDate),'/',T1.WorkId) else T1.migratedworkid end as EtlTenderWorkId"),DB::raw("group_concat(concat(T4.NameOfFirm, ' (CDB No: ',T4.CDBNo,')') SEPARATOR ', ') as Contractor"), 'T1.NameOfWork','T1.DescriptionOfWork'));
        $points = DB::table('etlevaluationscore')
                        ->where('EtlTenderId','=',$tenderId)
                        ->where('EtlTenderBidderContractorId','=',$contractorId)
                        // OLD QUERY ->get(array('Score1','Score2','Score3','Score4','Score5','Score6','Score7','Score8','Score9'));
                        ->get(array('Score1','Score2','Score3','Score4','Score5','Score6','Score7','Score11'));

        $contractorFirmName=DB::table('etltenderbiddercontractordetail as T1')
                                ->join('crpcontractorfinal as T2','T1.CrpContractorFinalId','=','T2.Id')
                                ->where('T1.EtlTenderBidderContractorId',$contractorId)
                                ->get(array(DB::raw("group_concat(concat(T2.NameOfFirm,' (CDB No.',T2.CDBNo,')')) as ContractorsFirmNames")));

        $auditTrailActionMessage="Viewed Score Details of ".$contractorFirmName[0]->ContractorsFirmNames." for Work Id ".$tenderDetails[0]->EtlTenderWorkId;
        $this->auditTrailEtoolCinet(NULL,NULL,$auditTrailActionMessage,$tenderDetails[0]->EtlTenderWorkId);                


		return View::make('etool.evaluationpointdetails')
                    ->with('tenderDetails',$tenderDetails)
                    ->with('points',$points);
	}
	public function detailsSmall($tenderId,$contractorId=NULL){


        
        $scoreCount = DB::table('etlevaluationscore')->where('EtlTenderId','=',$tenderId)->count();
        if((int)$scoreCount > 0){
            $contractors = DB::select('SELECT `T2`.`Id`, T1.Id AS ScoreId,
                            `T2`.`ActualStartDate`,
                            GROUP_CONCAT(A.CDBNo SEPARATOR ", ") AS CDBNo,
                            `T1`.`SmallAndRegisteredContractorPosition` AS `Score10`,
                            GROUP_CONCAT(A.NameOfFirm SEPARATOR ", ") AS NameOfFirm,
                            `T2`.`JointVenture`,
                            `T2`.`Id`,
                            `T5`.`Code` AS `Category`,
                            `T6`.`Code` AS `Classification`,
                            (SELECT COUNT(*)FROM viewcontractorstrackrecords vw WHERE vw.`CDBNo`=A.CDBNo 
                            AND vw.`WorkStatus`="Awarded")  totalWorkInHand
                        FROM
                            `etlevaluationscore` AS `T1`
                            RIGHT JOIN `etltenderbiddercontractor` AS `T2`
                            ON `T2`.`Id` = `T1`.`EtlTenderBidderContractorId`
                            INNER JOIN `etltenderbiddercontractordetail` AS `T3`
                            ON `T2`.`Id` = `T3`.`EtlTenderBidderContractorId`
                            INNER JOIN `etltender` AS `T4`
                            ON `T4`.`Id` = `T2`.`EtlTenderId`
                            INNER JOIN `crpcontractorfinal` AS `A`
                            ON `A`.`Id` = `T3`.`CrpContractorFinalId`
                            INNER JOIN `cmncontractorworkcategory` AS `T5`
                            ON `T5`.`Id` = `T4`.`CmnContractorCategoryId`
                            INNER JOIN `cmncontractorclassification` AS `T6`
                            ON `T6`.`Id` = `T4`.`CmnContractorClassificationId`
                        WHERE `T2`.`EtlTenderId` ="'.$tenderId.'"
                        GROUP BY `T1`.`Id`
                        ORDER BY `SmallAndRegisteredContractorPosition` ASC');
 
            // $contractors = DB::table('etlevaluationscore as T1')
            //     ->rightJoin('etltenderbiddercontractor as T2','T2.Id','=','T1.EtlTenderBidderContractorId')
            //     ->join('etltenderbiddercontractordetail as T3','T2.Id','=','T3.EtlTenderBidderContractorId')
            //     ->join('etltender as T4','T4.Id','=','T2.EtlTenderId')
            //     ->join('crpcontractorfinal as A','A.Id','=','T3.CrpContractorFinalId')
            //     ->join('cmncontractorworkcategory as T5','T5.Id','=','T4.CmnContractorCategoryId')
            //     ->join('cmncontractorclassification as T6','T6.Id','=','T4.CmnContractorClassificationId')
            //     ->where('T2.EtlTenderId','=',$tenderId)
            //     ->groupBy('T1.Id')
            //     ->orderBy('SmallAndRegisteredContractorPosition','ASC')
            //     ->get(array('T2.Id',DB::raw('T1.Id as ScoreId'),'T2.ActualStartDate', DB::raw('group_concat(A.CDBNo SEPARATOR ", ") as CDBNo'),'T1.SmallAndRegisteredContractorPosition as Score10',DB::raw('group_concat(A.NameOfFirm SEPARATOR ", ") as NameOfFirm'),'T2.JointVenture','T2.Id','T5.Code as Category','T6.Code as Classification'));
        }else{

            $contractors = DB::select(' SELECT
            `T1`.`Id`,
            GROUP_CONCAT(T3.CDBNo SEPARATOR ", ") AS CDBNo,
            GROUP_CONCAT(T3.NameOfFirm SEPARATOR ", ") AS NameOfFirm,
            `T1`.`JointVenture`,
            `T1`.`Id`,
            `T5`.`Code` AS `Category`,
            `T6`.`Code` AS `Classification`,
            (SELECT COUNT(*)FROM viewcontractorstrackrecords vw WHERE vw.`CDBNo`=T3.CDBNo 
                            AND vw.`WorkStatus`="Awarded")  totalWorkInHand
          FROM
            `etltenderbiddercontractor` AS `T1`
            INNER JOIN `etltenderbiddercontractordetail` AS `T2`
              ON `T2`.`EtlTenderBidderContractorId` = `T1`.`Id`
            INNER JOIN `crpcontractorfinal` AS `T3`
              ON `T2`.`CrpContractorFinalId` = `T3`.`Id`
            INNER JOIN `etltender` AS `T4`
              ON `T1`.`EtlTenderId` = `T4`.`Id`
            INNER JOIN `cmncontractorworkcategory` AS `T5`
              ON `T5`.`Id` = `T4`.`CmnContractorCategoryId`
            INNER JOIN `cmncontractorclassification` AS `T6`
              ON `T6`.`Id` = `T4`.`CmnContractorClassificationId`
          WHERE `T1`.`EtlTenderId` = "'.$tenderId.'"
          GROUP BY `T1`.`Id`');


            // $contractors = DB::table('etltenderbiddercontractorc as T1')
            //     ->join('etltenderbiddercontractordetail as T2','T2.EtlTenderBidderContractorId','=','T1.Id')
            //     ->join('crpcontractorfinal as T3','T2.CrpContractorFinalId','=','T3.Id')
            //     ->join('etltender as T4','T1.EtlTenderId','=','T4.Id')
            //     ->join('cmncontractorworkcategory as T5','T5.Id','=','T4.CmnContractorCategoryId')
            //     ->join('cmncontractorclassification as T6','T6.Id','=','T4.CmnContractorClassificationId')
            //     ->where('T1.EtlTenderId','=',$tenderId)
            //     ->groupBy('T1.Id')
            //     ->get(array('T1.Id',DB::raw('group_concat(T3.CDBNo SEPARATOR ", ") as CDBNo'),DB::raw('group_concat(T3.NameOfFirm SEPARATOR ", ") as NameOfFirm'),'T1.JointVenture','T1.Id','T5.Code as Category','T6.Code as Classification'));
        }
        $bidContractors = array();
        $showModal = 0;
        if($contractorId){
            $showModal = 1;
            $bidContractors = DB::table('etltenderbiddercontractor as T1')
                ->join('etltenderbiddercontractordetail as T2','T2.EtlTenderBidderContractorId','=','T1.Id')
                ->join('crpcontractorfinal as T3','T2.CrpContractorFinalId','=','T3.Id')
                ->join('etltender as T4','T1.EtlTenderId','=','T4.Id')
                ->join('cmncontractorworkcategory as T5','T5.Id','=','T4.CmnContractorCategoryId')
                ->join('cmncontractorclassification as T6','T6.Id','=','T4.CmnContractorClassificationId')
                ->where('T1.EtlTenderId','=',$tenderId)
                ->where('T1.Id',$contractorId)
                ->groupBy('T1.Id')
                ->get(array('T1.Id','T1.FinancialBidQuoted','T3.CDBNo','T3.NameOfFirm','T3.Id as CrpContractorFinalId'));
        }
        $tenders = DB::table('etltender as T1')
            ->join('sysuser as T2','T2.CmnProcuringAgencyId','=','T1.CmnProcuringAgencyId')
            ->join('cmnprocuringagency as T3','T2.CmnProcuringAgencyId','=','T3.Id')
            ->where('T1.Id','=',$tenderId)
            ->get(array(DB::raw('distinct(T1.Id)'),'T1.Id','T1.TenderStatus','T1.CancelledRemarks','T1.CancelledDate','T1.CmnWorkExecutionStatusId',DB::raw("case when T1.migratedworkid is null then concat(T3.Code,'/',year(T1.UploadedDate),'/',T1.WorkId) else T1.migratedworkid end as WorkId"),'T1.TentativeStartDate','T1.TentativeEndDate','T1.NameOfWork','T1.DescriptionOfWork','T1.ProjectEstimateCost'));
        $allContractors = ContractorFinalModel::contractorHardListAll()->get(array('Id','NameOfFirm','CDBNo'));
		return View::make('etool.workevaluationdetailssmall')
            ->with('contractors',$contractors)
            ->with('allContractors',$allContractors)
            ->with('bidContractors',$bidContractors)
            ->with('tenderId',$tenderId)
            ->with('scoreCount',$scoreCount)
            ->with('showModal',$showModal)
            ->with('tenderStatus',$tenders[0]->TenderStatus)
            ->with('CancelledRemarks',$tenders[0]->CancelledRemarks)
            ->with('CancelledDate',$tenders[0]->CancelledDate)
            ->with('tenders',$tenders);
	}


    
    public function postSaveAddContractor(){
        $inputs = Input::all();
        $currentTab = $inputs['CurrentTab'];
        $currentTabParameter = substr($currentTab,1,strlen($currentTab));
        $guid = $this->UUID();
        $parentTableInputs = array();
        $childTableInputs = array();
        $contractorCapacityInputs = Input::get("EtlContractorCapacity");
        $ContractorType = Input::get("ContractorType");
        
        DB::beginTransaction();
        foreach($inputs as $key => $value){
            if(gettype($value) != 'array'){
                $parentTableInputs[$key] = $value;
            }
        }
        $workId=DB::table('etltender as T1')->join('cmnprocuringagency as T2','T1.CmnProcuringAgencyId','=','T2.Id')
                    ->where('T1.Id',$parentTableInputs['EtlTenderId'])
                    ->get(array(DB::raw("case when T1.migratedworkid is null then concat(T2.Code,'/',
                    year(T1.UploadedDate),'/',T1.WorkId) else T1.migratedworkid end as WorkId")));
       
        if(isset($parentTableInputs['FinancialBidQuoted'])){
            
            try{
                if(empty($parentTableInputs['Id'])){
                    $save = true;
                    $parentTableInputs['Id'] = $guid;
                    AddContractorModel::create($parentTableInputs);
                }else{
                    $save = false;
                    $contractorDetails = Input::get('Contractor');
                    $cdbNosString = "";
                    if(!empty($contractorDetails)):
                        foreach($contractorDetails as $g=>$h):
                            if($cdbNosString != ""){
                                $cdbNosString.=", ";
                            }
                            if(isset($h["CDBNo"]))
                                $cdbNosString.=$h["CDBNo"];
                        endforeach;
                    endif;
                    $financialBidQuoted = $parentTableInputs['FinancialBidQuoted'];
                    $totalCapacity = 0;
                    if($ContractorType=='LARGE')
                    
                    foreach($contractorCapacityInputs as $u=>$e):
                        $totalCapacity+=floatval($e['Amount']);
                    endforeach;
                    $workIdForTrack = DB::select("select case when T1.migratedworkid is not null then T1.migratedworkid else concat(T2.Code,'/',year(T1.UploadedDate),'/',T1.WorkId) end as WorkId from etltender T1 join cmnprocuringagency T2 on T1.CmnProcuringAgencyId = T2.Id where T1.Id = ?",array(Input::get('EtlTenderId')));
                    $workIdForTrack = $workIdForTrack[0]->WorkId;
                    DB::table('etltrackbiddetails')->insert(array("Id"=>$this->UUID(),"WorkId"=>$workIdForTrack,"CDBNo"=>$cdbNosString,"FinancialBidQuoted"=>$financialBidQuoted,"CreditLine"=>$totalCapacity,"PerformedBy"=>Auth::user()->FullName,"OperationTime"=>date('Y-m-d G:i:s')));
                    DB::table('etltenderbiddercontractordetail')->where('EtlTenderBidderContractorId','=',$parentTableInputs['Id'])->delete();
                    $object = AddContractorModel::find($parentTableInputs['Id']);
                    $object->fill($parentTableInputs);
                    $object->update();
                }
            }catch(Exception $e){
                DB::rollback();
                throw $e;
            }
            
            if(!Input::has('Source')  && $ContractorType=='LARGE'){

                DB::table('etlcontractorcapacity')->where('EtlTenderBidderContractorId','=',$parentTableInputs['Id'])->delete();
                foreach($contractorCapacityInputs as $key=>$value){
                    foreach($value as $x => $y){
                        $childTableInputs[$x] = $y;
                    }
                    try{
                        $childTableInputs['EtlTenderBidderContractorId'] = $parentTableInputs['Id'];
                        $childTableInputs['Id'] = $this->UUID();
                        ContractorCapacityModel::create($childTableInputs);
                    }catch(Exception $e){
                        DB::rollback();
                        throw $e;
                    }
                }
            }
            $childTableInputs = array();
            $contractorListInputs = Input::get("Contractor");
            foreach($contractorListInputs as $key=>$value){
                $contractorExistsCheck = 0;
                foreach($value as $x => $y){
                    $childTableInputs[$x] = $y;
                }
                $contractorExistsCheck = DB::table('crpcontractorfinal as T1')
                                            ->join('etltenderbiddercontractordetail as T2','T2.CrpContractorFinalId','=','T1.Id')
                                            ->join('etltenderbiddercontractor as T3','T3.Id','=','T2.EtlTenderBidderContractorId')
                                            ->where('T3.EtlTenderId','=',$parentTableInputs['EtlTenderId'])
                                            ->where('T1.Id',$childTableInputs['CrpContractorFinalId'])
                                            ->count(DB::raw('distinct T2.Id'));
                $contractorStatus = DB::table('crpcontractorfinal as T1')
                                            ->join('cmnlistitem as T2','T2.Id','=','T1.CmnApplicationRegistrationStatusId')
                                            ->where('T1.Id',$childTableInputs['CrpContractorFinalId'])
                                            ->get(array('T1.CmnApplicationRegistrationStatusId','T2.Name as Status'));
                if($contractorStatus[0]->CmnApplicationRegistrationStatusId != CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED){
                    $contractorName = DB::table('crpcontractorfinal')->where('Id',$childTableInputs['CrpContractorFinalId'])->pluck('NameOfFirm');
                    DB::rollback();
                   
                    if($ContractorType=='LARGE')
                    {
                        return Redirect::to('etl/workevaluationaddcontractors/'.$parentTableInputs['EtlTenderId'])->with('customerrormessage',"This contractor ($contractorName) is ". $contractorStatus[0]->Status);
                    }
                    else if($ContractorType=='SMALL')
                    {
                        return Redirect::to('etl/smallWorkevaluationaddcontractors/'.$parentTableInputs['EtlTenderId'])->with('customerrormessage',"This contractor ($contractorName) is ". $contractorStatus[0]->Status);
                    }
                    
                }
                if((int)$contractorExistsCheck > 0){
                    $contractorName = DB::table('crpcontractorfinal')->where('Id',$childTableInputs['CrpContractorFinalId'])->pluck('NameOfFirm');
                    DB::rollback();
                    
                    if($ContractorType=='LARGE')
                    {
                        return Redirect::to('etl/workevaluationaddcontractors/'.$parentTableInputs['EtlTenderId'])->with('customerrormessage',"This contractor ($contractorName) has already bidded for this tender");
                    }
                    else if ($ContractorType=='SMALL')
                    {
                        return Redirect::to('etl/smallWorkevaluationaddcontractors/'.$parentTableInputs['EtlTenderId'])->with('customerrormessage',"This contractor ($contractorName) has already bidded for this tender");
                    }
                }
                try{
                    $childTableInputs['EtlTenderBidderContractorId'] = $parentTableInputs['Id'];
                    $childTableInputs['Id'] = $this->UUID();
                    ContractorListModel::create($childTableInputs);
                }catch(Exception $e){
                    DB::rollback();
                    throw $e;
                }
            }
            if(Input::has('Source')){
                DB::commit();
                $contractorFirmName=DB::table('etltenderbiddercontractordetail as T1')
                                ->join('crpcontractorfinal as T2','T1.CrpContractorFinalId','=','T2.Id')
                                ->where('T1.EtlTenderBidderContractorId',$parentTableInputs['Id'])
                                ->get(array(DB::raw("group_concat(concat(T2.NameOfFirm,' (CDB No.',T2.CDBNo,')')) as ContractorsFirmNames")));

                $auditTrailActionMessage=$save==true?"Added":"Edited";
                $auditTrailActionMessage.=" contractor(s) ".$contractorFirmName[0]->ContractorsFirmNames ." against Work Id ".$workId[0]->WorkId;
                $this->auditTrailEtoolCinet(NULL,NULL,$auditTrailActionMessage,$workId[0]->WorkId);
                return Redirect::to('etl/smallWorkevaluationaddcontractors/'.$parentTableInputs['EtlTenderId'].'/'.$parentTableInputs['Id'])->with('savedsuccessmessage',$save?"Record has been saved":"Record has been updated");
            }
            DB::commit();
            $contractorFirmName=DB::table('etltenderbiddercontractordetail as T1')
                                ->join('crpcontractorfinal as T2','T1.CrpContractorFinalId','=','T2.Id')
                                ->where('T1.EtlTenderBidderContractorId',$parentTableInputs['Id'])
                                ->get(array(DB::raw("group_concat(concat(T2.NameOfFirm,' (CDB No.',T2.CDBNo,')')) as ContractorsFirmNames")));

            $auditTrailActionMessage=$save==true?"Added":"Edited";
            $auditTrailActionMessage.=" contractor(s) ".$contractorFirmName[0]->ContractorsFirmNames ." against Work Id ".$workId[0]->WorkId;
            $this->auditTrailEtoolCinet(NULL,NULL,$auditTrailActionMessage,$workId[0]->WorkId);
            if($ContractorType=='SMALL')
                return Redirect::to('etl/smallWorkevaluationaddcontractors/'.$parentTableInputs['EtlTenderId'].'/'.$parentTableInputs['Id'])->with('savedsuccessmessage',$save?"Record has been saved":"Record has been updated");
            else if($ContractorType=='LARGE')
                return Redirect::to('etl/workevaluationaddcontractors/'.$parentTableInputs['EtlTenderId'].'/'.$parentTableInputs['Id'])->with('savedsuccessmessage',$save?"Record has been saved":"Record has been updated");
        }else{
            $save = false;
            $childTableInputs = array();
            $contractorHRInputs = Input::get("EtlContractorHumanResource");
            
            if(count($contractorHRInputs)>0)
            {
                foreach($contractorHRInputs as $key=>$value){
                    foreach($value as $x => $y){
                        $childTableInputs[$x] = $y;
                    }
                    if($childTableInputs['CIDNo']){
                        try{
                            if($childTableInputs['Id']!=''){
                                $childTableInputs['EtlTenderBidderContractorId'] = $parentTableInputs['Id']?$parentTableInputs['Id']:Input::get('Id');
                                $hrObject = EtlContractorHumanResourceModel::find($childTableInputs['Id']);
                                $hrObject->fill($childTableInputs);
                                $hrObject->update();
                            }else{
                                
                                $childTableInputs['EtlTenderBidderContractorId'] = $parentTableInputs['Id']?$parentTableInputs['Id']:Input::get('Id');
                                $childTableInputs['Id'] = $this->UUID();
                                EtlContractorHumanResourceModel::create($childTableInputs);
                            }
                        }catch(Exception $e){
                            throw $e;
                        }
                    }
                }
            }
            $childTableInputs = array();
            $contractorHRInputs = Input::get("EtlContractorEquipment");
            if(count($contractorHRInputs)>0)
            {
                foreach($contractorHRInputs as $key=>$value){
                    foreach($value as $x => $y){
                        $childTableInputs[$x] = $y;
                    }
                    if($childTableInputs['CmnEquipmentId']) {
                        if($ContractorType=='SMALL')
                        {  
                            $childTableInputs['EtlTierId']="e3788bef-cd50-11e4-93a9-9c2a70cc8e06";
                        }
                        //die($childTableInputs['EtlTierId'].'ccccccccccccccc');
                        try {
                            if($childTableInputs['Id']!=''){
                                $childTableInputs['EtlTenderBidderContractorId'] = $parentTableInputs['Id']?$parentTableInputs['Id']:Input::get('Id');
                                $eqObject = EtlContractorEquipmentModel::find($childTableInputs['Id']);
                                $eqObject->fill($childTableInputs);
                                $eqObject->update();
                            }else{
                                $childTableInputs['EtlTenderBidderContractorId'] = $parentTableInputs['Id']?$parentTableInputs['Id']:Input::get('Id');
                                $childTableInputs['Id'] = $this->UUID();
                                EtlContractorEquipmentModel::create($childTableInputs);
                            }
                        } catch (Exception $e) {
                            DB::rollback();
                            throw $e;
                        }
                    }
                }
            }
        }
        DB::commit();
        $contractorFirmName=DB::table('etltenderbiddercontractordetail as T1')
                                ->join('crpcontractorfinal as T2','T1.CrpContractorFinalId','=','T2.Id')
                                ->where('T1.EtlTenderBidderContractorId',$parentTableInputs['Id'])
                                ->get(array(DB::raw("group_concat(concat(T2.NameOfFirm,' (CDB No.',T2.CDBNo,')')) as ContractorsFirmNames")));

        $auditTrailActionMessage="Updated Human Resource and Equipment details of contractor(s) ".$contractorFirmName[0]->ContractorsFirmNames ." against Work Id ".$workId[0]->WorkId;
        $this->auditTrailEtoolCinet(NULL,NULL,$auditTrailActionMessage,$workId[0]->WorkId);
        

        if($ContractorType=='SMALL')
        {    
            return Redirect::to('etl/smallWorkevaluationaddcontractors/'.$parentTableInputs['EtlTenderId'].'/'.$parentTableInputs['Id'].'?currentTab='.$currentTabParameter.$currentTab)->with('savedsuccessmessage',$save?'Record has been saved':"Record has been updated");
        }
        else if($ContractorType=='LARGE')
        {
            return Redirect::to('etl/workevaluationaddcontractors/'.$parentTableInputs['EtlTenderId'].'/'.$parentTableInputs['Id'].'?currentTab='.$currentTabParameter.$currentTab)->with('savedsuccessmessage',$save?'Record has been saved':"Record has been updated");
        }
        
    }
    public function etlPostSeekClarification(){

        $inputs = Input::all();
       // die($inputs['Tender_Id'].'/'.$inputs['CDB_No'].'/'.$inputs['contractorId']);
        //SeekClarificationModel::create($inputs);

        DB::table('etlseekclarification')->insert(array('Tender_Id'=>$inputs['Tender_Id'],
        'CDB_No'=>$inputs['CDB_No'],'Enquiry'=>$inputs['Enquiry'],'Status'=>$inputs['Status']));

        $seekClarificationList = DB::select("SELECT a.Id,a.`Enquiry`,
        a.`Respond`,a.`Status`,a.`CDB_No`,b.`EGPTenderId` Tender_Id ,a.`Created_On`
          FROM `etlseekclarification` a 
         LEFT JOIN etltender b ON a.`Tender_Id`=b.id
         WHERE a.`Tender_Id` = '".$inputs['Tender_Id']."' AND a.`CDB_No`='".$inputs['CDB_No']."' order by a.Id desc");

        return View::make('etool.seekClarificationList')
        ->with('seekClarificationList',$seekClarificationList)
        ->with('tenderId',$inputs['Tender_Id'])
        ->with('cdbNo',$inputs['CDB_No'])
        ->with('contractorId',$inputs['contractorId']);

    }
    public function postFetchContractor(){
        $id = Input::get('id');
        $contractors = DB::table('etltenderbiddercontractordetail as T1')
                            ->join('crpcontractorfinal as T2','T1.CrpContractorFinalId','=','T2.Id')
                            ->join('etltenderbiddercontractor as T3','T3.Id','=','T1.EtlTenderBidderContractorId')
                            ->where('T1.EtlTenderBidderContractorId','=',$id)
                            ->orderBy('T2.NameOfFirm')
                            ->get(array('T1.EtlTenderBidderContractorId as Id','T3.FinancialBidQuoted',DB::raw('group_concat(T2.NameOfFirm SEPARATOR ", ") as Contractor')));
        return Response::json($contractors);
    }
    public function postAwardContractor(){
        $inputs = Input::all();
        $change = false;
        $status = DB::table('etltender as T1')
                        ->where('T1.Id',$inputs['EtlTenderId'])
                        ->pluck('T1.CmnWorkExecutionStatusId');
        if($status == CONST_CMN_WORKEXECUTIONSTATUS_AWARDED && !Input::has('WorkType')){
            $change = true;
            $contractorFirmName=DB::table('etltenderbiddercontractordetail as T1')
                ->join('crpcontractorfinal as T2','T1.CrpContractorFinalId','=','T2.Id')
                ->join('etltenderbiddercontractor as T3','T3.Id','=','T1.EtlTenderBidderContractorId')
                ->whereNotNull('T3.ActualStartDate')
                ->where('T3.EtlTenderId',$inputs['EtlTenderId'])
                ->get(array('T3.Id',DB::raw("group_concat(concat(T2.NameOfFirm,' (CDB No.',T2.CDBNo,')')) as ContractorsFirmNames")));
            $workIdForAuditTrail=DB::table('etltender as T1')->join('cmnprocuringagency as T2','T1.CmnProcuringAgencyId','=','T2.Id')
                ->where('T1.Id',$inputs['EtlTenderId'])
                ->get(array(DB::raw("case when T1.migratedworkid is null then concat(T2.Code,'/',year(T1.UploadedDate),'/',T1.WorkId) else T1.migratedworkid end as WorkId")));
            $auditTrailActionMessage="Work Id ".$workIdForAuditTrail[0]->WorkId." is no longer awarded to ".$contractorFirmName[0]->ContractorsFirmNames;
            $this->auditTrailEtoolCinet(NULL,NULL,$auditTrailActionMessage,$workIdForAuditTrail[0]->WorkId);
            DB::table('etltenderbiddercontractor')->where('Id',$contractorFirmName[0]->Id)->update(array('ActualStartDate'=>NULL,'ActualEndDate'=>NULL,'AwardedAmount'=>NULL));
        }
        $inputs['ActualStartDate'] = $this->convertDate($inputs['ActualStartDate']);
        $inputs['ActualEndDate'] = $this->convertDate($inputs['ActualEndDate']);
        DB::table('etltender')->where('Id','=',$inputs['EtlTenderId'])->update(array('CmnWorkExecutionStatusId'=>CONST_CMN_WORKEXECUTIONSTATUS_AWARDED));
        $object = AddContractorModel::find($inputs['Id']);
        $object->fill($inputs);
        $object->update();

        $contractorFirmName=DB::table('etltenderbiddercontractordetail as T1')
                                ->join('crpcontractorfinal as T2','T1.CrpContractorFinalId','=','T2.Id')
                                ->where('T1.EtlTenderBidderContractorId',$inputs['Id'])
                                ->get(array(DB::raw("group_concat(concat(T2.NameOfFirm,' (CDB No.',T2.CDBNo,')')) as ContractorsFirmNames")));

        $workIdForAuditTrail=DB::table('etltender as T1')->join('cmnprocuringagency as T2','T1.CmnProcuringAgencyId','=','T2.Id')
                    ->where('T1.Id',$inputs['EtlTenderId'])
                    ->get(array(DB::raw("case when T1.migratedworkid is null then concat(T2.Code,'/',year(T1.UploadedDate),'/',T1.WorkId) else T1.migratedworkid end as WorkId")));

        $auditTrailActionMessage="Work Id ".$workIdForAuditTrail[0]->WorkId." awarded to ".$contractorFirmName[0]->ContractorsFirmNames;
        $this->auditTrailEtoolCinet(NULL,NULL,$auditTrailActionMessage,$workIdForAuditTrail[0]->WorkId);

        DB::table('etlevaluationtrack')->insert(array('Id'=>$this->UUID(),'WorkId'=>$workIdForAuditTrail[0]->WorkId,'Operation'=>'Award','User'=>Auth::user()->FullName,'OperationTime'=>date('Y-m-d G:i:s')));
        if($change){
            return Redirect::to('etl/etoolchangetenderawarded?WorkId='.$workIdForAuditTrail[0]->WorkId)->with('savedsuccessmessage','Work awarded to has been changed');
        }
        $redirect = 'etl/workevaluationdetails';
        if(Input::has('WorkType')){
            $redirect = 'etl/workevaluationdetailssmallcontractors';
        }
        return Redirect::to($redirect.'/'.$inputs['EtlTenderId'])->with('savedsuccessmessage','Work has been awarded to Contractor');
    }
    
    public function processResultLargetoSmall($tenderId){
        $biddingContractors = DB::table('etltenderbiddercontractor')->where('EtlTenderId',$tenderId)->orderBy('FinancialBidQuoted')->get(array('Id','FinancialBidQuoted'));
        DB::table('etlevaluationscore')->where('EtlTenderId',$tenderId)->delete();
        $count = 1;
        foreach($biddingContractors as $biddingContractor){
            DB::table('etlevaluationscore')->insert(array('Id'=>$this->UUID(),'EtlTenderBidderContractorId'=>$biddingContractor->Id,'EtlTenderId'=>$tenderId,'SmallAndRegisteredContractorPosition'=>$count));
            $count++;
        }
        $workIdForAuditTrail=DB::table('etltender as T1')->join('cmnprocuringagency as T2','T1.CmnProcuringAgencyId','=','T2.Id')
                    ->where('T1.Id',$tenderId)
                    ->get(array(DB::raw("case when T1.migratedworkid is null then concat(T2.Code,'/',year(T1.UploadedDate),'/',T1.WorkId) else T1.migratedworkid end as WorkId")));
        $auditTrailActionMessage="Clicked Process Result button for Work Id ".$workIdForAuditTrail[0]->WorkId;
        $this->auditTrailEtoolCinet(NULL,NULL,$auditTrailActionMessage,$workIdForAuditTrail[0]->WorkId);
        DB::table('etlevaluationtrack')->insert(array('Id'=>$this->UUID(),'WorkId'=>$workIdForAuditTrail[0]->WorkId,'Operation'=>'Process','User'=>Auth::user()->FullName,'OperationTime'=>date('Y-m-d G:i:s')));
        return Redirect::to('etl/workevaluationdetailssmallcontractors/'.$tenderId)->with('savedsuccessmessage','Result has been processed');
    }


    public function processResultSmall($tenderId){
        $biddingContractors = DB::table('etltenderbiddercontractor')->where('EtlTenderId',$tenderId)->orderBy('FinancialBidQuoted')->get(array('Id','FinancialBidQuoted'));
        DB::table('etlevaluationscore')->where('EtlTenderId',$tenderId)->delete();
        $count = 1;
        foreach($biddingContractors as $biddingContractor){
            DB::table('etlevaluationscore')->insert(array('Id'=>$this->UUID(),'EtlTenderBidderContractorId'=>$biddingContractor->Id,'EtlTenderId'=>$tenderId,'SmallAndRegisteredContractorPosition'=>$count));
            $count++;
        }
        $workIdForAuditTrail=DB::table('etltender as T1')->join('cmnprocuringagency as T2','T1.CmnProcuringAgencyId','=','T2.Id')
                    ->where('T1.Id',$tenderId)
                    ->get(array(DB::raw("case when T1.migratedworkid is null then concat(T2.Code,'/',year(T1.UploadedDate),'/',T1.WorkId) else T1.migratedworkid end as WorkId")));
        $auditTrailActionMessage="Clicked Process Result button for Work Id ".$workIdForAuditTrail[0]->WorkId;
        $this->auditTrailEtoolCinet(NULL,NULL,$auditTrailActionMessage,$workIdForAuditTrail[0]->WorkId);
        DB::table('etlevaluationtrack')->insert(array('Id'=>$this->UUID(),'WorkId'=>$workIdForAuditTrail[0]->WorkId,'Operation'=>'Process','User'=>Auth::user()->FullName,'OperationTime'=>date('Y-m-d G:i:s')));
        return Redirect::to('etl/workevaluationdetailssmallcontractors/'.$tenderId)->with('savedsuccessmessage','Result has been processed');
    }
    public function resetResultSmall($tenderId){
        DB::table('etlevaluationscore')->where('EtlTenderId',$tenderId)->delete();
        $workIdForAuditTrail=DB::table('etltender as T1')->join('cmnprocuringagency as T2','T1.CmnProcuringAgencyId','=','T2.Id')
                    ->where('T1.Id',$tenderId)
                    ->get(array(DB::raw("case when T1.migratedworkid is null then concat(T2.Code,'/',year(T1.UploadedDate),'/',T1.WorkId) else T1.migratedworkid end as WorkId")));
        $auditTrailActionMessage="Clicked Reset Result button for Work Id ".$workIdForAuditTrail[0]->WorkId;
        DB::table('etltender')->where('Id',$tenderId)->update(array('CmnWorkExecutionStatusId'=>NULL));
        DB::table('etltenderbiddercontractor')->where('EtlTenderId',$tenderId)->update(array('ActualStartDate'=>NULL,'ActualEndDate'=>NULL,'AwardedAmount'=>NULL,'Remarks'=>NULL));
        $this->auditTrailEtoolCinet(NULL,NULL,$auditTrailActionMessage,$workIdForAuditTrail[0]->WorkId);
        DB::table('etlevaluationtrack')->insert(array('Id'=>$this->UUID(),'WorkId'=>$workIdForAuditTrail[0]->WorkId,'Operation'=>'Reset Result','User'=>Auth::user()->FullName,'OperationTime'=>date('Y-m-d G:i:s')));
        return Redirect::to('etl/workevaluationdetailssmallcontractors/'.$tenderId)->with('savedsuccessmessage','Result has been reset');
    }
    public function getResultReport($tenderId){
        $qualifyingScore = DB::table('etlqualifyingscore')->pluck('QualifyingScore');
        $tenderDetails = DB::table('etltender as T1')
                            ->join('cmncontractorclassification as T2','T1.CmnContractorClassificationId','=','T2.Id')
                            ->join('cmncontractorworkcategory as T3','T1.CmnContractorCategoryId','=','T3.Id')
                            ->join('cmnprocuringagency as T5','T5.Id','=','T1.CmnProcuringAgencyId')
                            ->join('cmndzongkhag as T7','T1.CmnDzongkhagId','=','T7.Id')
                            ->leftJoin('cmnlistitem as T8','T8.Id','=','T1.CmnWorkExecutionStatusId')
                            ->where('T1.Id','=',$tenderId)
                            ->get(array(DB::raw("case when T1.migratedworkid is null then concat(T5.Code,'/',year(T1.UploadedDate),'/',T1.WorkId) else T1.migratedworkid end as WorkId"),"T8.Name as Status","T1.NameOfWork","T2.Code as Classification","T3.Code as Category","T7.NameEn as Dzongkhag","T1.ProjectEstimateCost","T1.ContractPeriod"));
        $evaluationCommittee = DB::table('etltendercommittee')
                                ->where('Type',1)
                                ->where('EtlTenderId',$tenderId)
                                ->get(array('Name','Designation'));
        $tenderCommittee = DB::table('etltendercommittee')
                                ->where('Type',2)
                                ->where('EtlTenderId',$tenderId)
                                ->get(array('Name','Designation'));
        $contractorScores = DB::table('etlevaluationscore as T1')
                            ->join('etltenderbiddercontractor as T2','T1.EtlTenderBidderContractorId','=','T2.Id')
                            ->join('etltenderbiddercontractordetail as T3','T3.EtlTenderBidderContractorId','=','T2.Id')
                            ->join('crpcontractorfinal as T5','T5.Id','=','T3.CrpContractorFinalId')
                            ->where('T1.EtlTenderId',$tenderId)
                            ->orderBy(DB::raw('coalesce(T1.Score10,0)'),'DESC')
                            ->groupBy('T2.Id')
                            ->get(array('T1.Id as ScoreId','T2.ActualStartDate',DB::raw('group_concat(concat(T5.NameOfFirm, "(",T5.CDBNo,")") SEPARATOR ", ") as CDBNo'), 'T2.FinancialBidQuoted','T1.Score1','T1.Score2','T1.Score3','T1.Score4','T1.Score5','T1.Score6','T1.Score7','T1.Score8','T1.Score9','T1.Score10','T1.Score11','T1.IsBhutaneseEmp'));
	$lowestBid = DB::table('etltenderbiddercontractor as T1')
            ->join('etlevaluationscore as T2','T2.EtlTenderBidderContractorId','=','T1.Id')
            ->where('T1.EtlTenderId','=',$tenderId)
            ->whereRaw("(T2.Score1 + T2.Score2 + T2.Score3 + T2.Score4 + T2.Score5 + T2.Score6) >= $qualifyingScore")
            ->min('T1.FinancialBidQuoted');
        return View::make('etool.resultreport')
                    ->with('qualifyingScore',$qualifyingScore)
                    ->with('tenderDetails',$tenderDetails)
                    ->with('evaluationCommittee',$evaluationCommittee)
                    ->with('tenderCommittee',$tenderCommittee)
                    ->with('contractorScores',$contractorScores)
                    ->with('lowestBid',$lowestBid)
                    ->with('bhutaneseEmp','Bhutanese');
    }
    public function getSmallWorksReport($tenderId){
        $tenderDetails = DB::table('etltender as T1')
                            ->leftJoin('cmncontractorclassification as T2','T2.Id','=','T1.CmnContractorClassificationId')
                            ->leftJoin('cmncontractorworkcategory as T3','T3.Id','=','T1.CmnContractorCategoryId')
                            ->leftJoin('cmnprocuringagency as T4','T4.Id','=','T1.CmnProcuringAgencyId')
			    ->leftJoin('cmnlistitem as T5','T5.Id','=','T1.CmnWorkExecutionStatusId')
                            ->where('T1.Id',$tenderId)
                            ->get(array(DB::raw('distinct(T1.Id)'),DB::raw('T5.Name as Status'), DB::raw("case when T1.migratedworkid is null then concat(T4.Code,'/',year(T1.UploadedDate),'/',T1.WorkId) else T1.migratedworkid end as WorkId"),DB::raw('concat(T2.Code,",",T3.Code) as ClassCategory'),'T1.ProjectEstimateCost','T1.NameOfWork'));
        $bidContractors = DB::table('etltenderbiddercontractordetail as T1')
                            ->join('etltenderbiddercontractor as T2','T2.Id','=','T1.EtlTenderBidderContractorId')
                            ->join('crpcontractorfinal as T3','T3.Id','=','T1.CrpContractorFinalId')
                            ->join('etlevaluationscore as T4','T4.EtlTenderBidderContractorId','=','T2.Id')
                            ->where('T2.EtlTenderId',$tenderId)
                            ->orderBy('T4.SmallAndRegisteredContractorPosition')
                            ->get(array('T3.CDBNo','T3.NameOfFirm','T2.FinancialBidQuoted','T2.AwardedAmount'));
        $evaluationCommittee = DB::table('etltendercommittee')
            ->where('Type',1)
            ->where('EtlTenderId',$tenderId)
            ->get(array('Name','Designation'));
        $tenderCommittee = DB::table('etltendercommittee')
            ->where('Type',2)
            ->where('EtlTenderId',$tenderId)
            ->get(array('Name','Designation'));
        $lowestBid = DB::table('etlevaluationscore as T1')
                        ->join('etltenderbiddercontractor as T2','T1.EtlTenderBidderContractorId','=','T2.Id')
                        ->where('T2.EtlTenderId','=',$tenderId)
                        ->min('T2.FinancialBidQuoted');
        return View::make('etool.smallresultreport')
                    ->with('lowestBid',$lowestBid)
                    ->with('tenderDetails',$tenderDetails)
                    ->with('bidContractors',$bidContractors)
                    ->with('evaluationCommittee',$evaluationCommittee)
                    ->with('tenderCommittee',$tenderCommittee);
    }
    public function postFetchContractorOnCDBNo(){
        $cdbNo = Input::get('cdbno');
        $contractor = DB::select('select Id, NameOfFirm, case CmnApplicationRegistrationStatusId when ? or ? or ? or ? or ? then 1 else 0 end as Status from crpcontractorfinal where CDBNo = ?',array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_REVOKED,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_DEREGISTERED,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_DEBARRED,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_SUSPENDED,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_SURRENDERED,$cdbNo));
        return Response::json($contractor);
    }
    public function postDelete(){
        $id = Input::get('id');
        $table = Input::get('table');
        DB::table($table)->where('Id',$id)->delete();
        return 1;
    }

    public function eltPostSeekClarification(){
        $id = Input::get('id');
        $table = Input::get('table');
        DB::table($table)->where('Id',$id)->delete();
        return 1;
    }


    public function deleteNonResponsive(){
        $inputs = Input::all();
        $nonResponsiveId = $inputs['nonResponsiveId'];
        DB::table('etltendernonresponsivecontractor')->where('Id','=',$nonResponsiveId)->delete();
        DB::commit();
        $save = true;
        return Redirect::to('newEtl/workevaluationdetails/'.$inputs['tenderId'])->with('savedsuccessmessage',$save?'Non Responsive Contractor has been deleted':"Non Responsive Contractor has been deleted");
    }
    
    
}