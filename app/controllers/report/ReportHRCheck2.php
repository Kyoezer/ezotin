<?php

class ReportHRCheck extends ReportController{
    public function getIndex(){
        
        $name = Input::get('name');
        $cidNo = Input::get('CIDNo');
        $dzongkhag = Input::get('dzongkhag');
        $gewog = Input::get('gewog');
        $village = Input::get('village');
        $dob = Input::get('dob');
        $gender = Input::get('gender');
        $isCivilServant = Input::get('isCivilServant');
        $workingAgency = Input::get('workingAgency');
        $hrIsEngineer = false;
        $hrIsGovtEmp = false;
        $hrDetails = array();
        $responseArray = array();
        $hrWorks = array();
        $checkPartnerDeregistered = array();
        $individualName = "";
        $govtOrCorporateOrg = "";
        if((bool)$cidNo){
            try{
               
                
                if($gender=="M")
                    $responseArray['gender'] = "Male";
                else if($gender=="F")
                    $responseArray['gender'] = "Female";

                $responseArray['name'] = $name;
                
                $responseArray['dzongkhag'] =  $dzongkhag;
                $responseArray['gewog'] = $gewog;
                $responseArray['village'] = $village;
                $responseArray['dob'] = $dob;
               
                if($isCivilServant=='true')
                {
                    $responseArray['IsCivilServant'] = 'Yes';
                }
                else
                {
                    $responseArray['IsCivilServant'] = 'No';
                }
                
                // $array = array(
                //     "ssl" => array(
                //         'ciphers'=>'RC4-SHA',"verify_peer" => false,
                //         "verify_peer_name" => false
                //     )
                // );
                // $context = stream_context_create($array);
                // $soapURL = "http://localhost:8080/G2CWebService/services/CitizenDtls?wsdl" ;
                // $webServiceCheck = @fopen("http://localhost:8080/G2CWebService/services/CitizenDtls?wsdl",'r');
                // if(!$webServiceCheck) {
                //     $responseArray['gender'] = '';
                //     $responseArray['name'] = '';
                //     $responseArray['dzongkhag'] = '';
                //     $responseArray['gewog'] = '';
                //     $responseArray['village'] = '';
                //     $responseArray['dob'] = '';
                //     $responseArray['IsCivilServant'] = '';
                // }else{
                //     $soap_client = new SoapClient($soapURL,array('stream_context'=>$context,'soap_version'   => SOAP_1_2,'exceptions'=>0));
                //     $result = $soap_client->getCitizenDetails(array('cid'=>$cidNo)); //RCSC
                //     if(is_soap_fault($result)){
                //         $responseArray['gender'] = '';
                //         $responseArray['name'] = '';
                //         $responseArray['dzongkhag'] = '';
                //         $responseArray['gewog'] = '';
                //         $responseArray['village'] = '';
                //         $responseArray['dob'] = '';
                //     }else{
                //         $data = $result->getCitizenDetailsReturn;


                //         $genderRaw = (string)$data->gender;
                //         $responseArray['gender'] = (string)$data->gender;
                //         $responseArray['name'] = (string)$data->fullName;
                //         $responseArray['dzongkhag'] = (string)$data->permDzongkhagName;
                //         $responseArray['gewog'] = (string)$data->permGewogName;
                //         $responseArray['village'] = (string)$data->permvillageName;
                //         $responseArray['dob'] = (string)$data->dob;
                //     }
                //     $rcscResult = $soap_client->getRCSCDetails(array('cidNo'=>$cidNo)); //RCSC
                //     if(is_soap_fault($rcscResult)){
                //         $responseArray['IsCivilServant'] = "No";
                //     }else{
                //         $resultArray = $rcscResult->getRCSCDetailsReturn;
                //         $responseArray['IsCivilServant'] = ((string)$resultArray->status=='Valid')?'Yes':'No';
                //     }
                // }



            }catch(Exception $e){
                // $responseArray['gender'] = '';
                // $responseArray['name'] = '';
                // $responseArray['dzongkhag'] = '';
                // $responseArray['gewog'] = '';
                // $responseArray['village'] = '';
                // $responseArray['dob'] = '';
                // $responseArray['IsCivilServant'] = '';
            }


            DB::table('tblworkidtrack')->insert(array('workid'=>$cidNo,'username'=>isset(Auth::user()->Id)?Auth::user()->username:'','operation'=>'Report 6','op_time'=>date('Y-m-d G:i:s')));
            $hrEngineerCheck = DB::table('crpgovermentengineer')
                                ->where('CIDNo',$cidNo)
                                ->count('Id');
            $govtOrCorporateOrg = DB::table('crpgovermentengineer')->where(DB::raw('coalesce(Releaved,0)'),'=',0)->where(DB::raw('TRIM(CIDNo)'),'=',trim($cidNo))->pluck('Agency');
            if($hrEngineerCheck > 0){
                $individualName = DB::table('crpengineerfinal')->where('CIDNo',$cidNo)->pluck('Name');
                $hrIsEngineer = true;
                if($responseArray['IsCivilServant'] == 'Yes'){
                    $hrIsGovtEmp = true;
                }else{
                    $hrIsGovtEmp = false;
                }
            }else{
                if($responseArray['IsCivilServant'] == 'Yes'){
                    $hrIsGovtEmp = true;
                }else{
                    $hrIsGovtEmp = false;
                }
                $individualName = DB::table('crpcontractorhumanresourcefinal')->where('CIDNo',$cidNo)->pluck('Name');
                $hrIsEngineer = false;
            }

            $hrDetails = DB::select("select T1.CDBNo, T1.NameOfFirm, T2.IsPartnerOrOwner from crpcontractorfinal T1 join crpcontractorhumanresourcefinal as T2 on T1.Id = T2.CrpContractorFinalId where T2.CIDNo = '$cidNo' and T1.CmnApplicationRegistrationStatusId = '".CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED."' union
                                     select T1.CDBNo, T1.NameOfFirm, T2.IsPartnerOrOwner from crpconsultantfinal T1 join crpconsultanthumanresourcefinal as T2 on T1.Id = T2.CrpConsultantFinalId where T2.CIDNo = '$cidNo' and T1.CmnApplicationRegistrationStatusId = '".CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED."'");
		

	   $cbDetailsBiddingDetails = DB::select(" SELECT DISTINCT 
                                        (T3.Id),
                                        GROUP_CONCAT(B.CDBNo SEPARATOR ',') AS CDBNo,
                                        CASE
                                        WHEN T3.migratedworkid IS NULL 
                                        THEN CONCAT(
                                            T4.Code,
                                            '/',
                                            YEAR(T3.UploadedDate),
                                            '/',
                                            T3.WorkId
                                        ) 
                                        ELSE T3.migratedworkid 
                                        END AS WorkId,
                                        `T4`.`Name` AS `ProcuringAgency` 
                                    FROM
                                        `etlcontractorhumanresource` AS `T1` 
                                        INNER JOIN `etltenderbiddercontractor` AS `T2` 
                                        ON `T2`.`Id` = `T1`.`EtlTenderBidderContractorId` 
                                        INNER JOIN `etltenderbiddercontractordetail` AS `A` 
                                        ON `A`.`EtlTenderBidderContractorId` = `T2`.`Id` 
                                        INNER JOIN `crpcontractorfinal` AS `B` 
                                        ON `B`.`Id` = `A`.`CrpContractorFinalId` 
                                        INNER JOIN `etltender` AS `T3` 
                                        ON `T3`.`Id` = `T2`.`EtlTenderId` 
                                        INNER JOIN `cmnprocuringagency` AS `T4` 
                                        ON `T4`.`Id` = `T3`.`CmnProcuringAgencyId` 
                                    WHERE `T2`.`ActualStartDate` IS NOT NULL 
                                        AND COALESCE(T3.CmnWorkExecutionStatusId, 0) = '1ec69344-a256-11e4-b4d2-080027dcfac6'
                                        AND `T1`.`CIDNo` = 11811003638 
                                    GROUP BY `T3`.`Id`
                                    UNION
                                    SELECT a.`Id`,d.`CDBNo`,b.`WorkId`,e.`Name` FROM `cbbidhumanresource` a
                                    LEFT JOIN `cbbiddingform` b ON a.`CrpBiddingFormId`=b.`Id`
                                    LEFT JOIN `cbbiddingformdetail` c ON b.`Id`=c.`CrpBiddingFormId`
                                    LEFT JOIN `crpcertifiedbuilder` d ON c.`CrpCertifiedBuilderFinalId` = d.`Id`
                                    LEFT JOIN `cmnprocuringagency` AS e
                                        ON  b.`CmnProcuringAgencyId` = e.`Id` 
                                    WHERE a.`CIDNo`=".$cidNo);


            $hrWorksEtool = DB::table('etlcontractorhumanresource as T1')
                            ->join('etltenderbiddercontractor as T2','T2.Id','=','T1.EtlTenderBidderContractorId')
                            ->join('etltenderbiddercontractordetail as A','A.EtlTenderBidderContractorId','=','T2.Id')
                            ->join('crpcontractorfinal as B','B.Id','=','A.CrpContractorFinalId')
                            ->join('etltender as T3','T3.Id','=','T2.EtlTenderId')
                            ->join('cmnprocuringagency as T4','T4.Id','=','T3.CmnProcuringAgencyId')
                            ->whereNotNull('T2.ActualStartDate')
                            ->where(DB::raw('coalesce(T3.CmnWorkExecutionStatusId,0)'),'=',CONST_CMN_WORKEXECUTIONSTATUS_AWARDED)
                            ->where('T1.CIDNo',$cidNo)
                            ->groupBy('T3.Id')
                            ->get(array(DB::raw('distinct(T3.Id)'),DB::raw('group_concat(B.CDBNo SEPARATOR ",") as CDBNo'),DB::raw("case when T3.migratedworkid is null then concat(T4.Code,'/',year(T3.UploadedDate),'/',T3.WorkId) else T3.migratedworkid end as WorkId"),'T4.Name as ProcuringAgency'));
            if(count($hrWorksEtool) > 0){
                $workId = DB::table('etlcontractorhumanresource as T1')
                            ->join('etltenderbiddercontractor as T2','T2.Id','=','T1.EtlTenderBidderContractorId')
                            ->join('etltenderbiddercontractordetail as A','A.EtlTenderBidderContractorId','=','T2.Id')
                            ->join('crpcontractorfinal as B','B.Id','=','A.CrpContractorFinalId')
                            ->join('etltender as T3','T3.Id','=','T2.EtlTenderId')
                            ->join('cmnprocuringagency as T4','T4.Id','=','T3.CmnProcuringAgencyId')
                            ->whereNotNull('T2.ActualStartDate')
                            ->where(DB::raw('coalesce(T3.CmnWorkExecutionStatusId,0)'),'=',CONST_CMN_WORKEXECUTIONSTATUS_AWARDED)
                            ->where('T1.CIDNo',$cidNo)
                            ->groupBy('T1.Id')
                            ->get(array(DB::raw('distinct(T1.Id)'),DB::raw("group_concat(case when T3.migratedworkid is null then concat(T4.Code,'/',year(T3.UploadedDate),'/',T3.WorkId) else T3.migratedworkid end SEPARATOR \", \") as WorkId")));
                $cdbNo = DB::table('etlcontractorhumanresource as T1')
                            ->join('etltenderbiddercontractor as T2','T2.Id','=','T1.EtlTenderBidderContractorId')
                            ->join('etltenderbiddercontractordetail as A','A.EtlTenderBidderContractorId','=','T2.Id')
                            ->join('crpcontractorfinal as B','B.Id','=','A.CrpContractorFinalId')
                            ->join('etltender as T3','T3.Id','=','T2.EtlTenderId')
                            ->join('cmnprocuringagency as T4','T4.Id','=','T3.CmnProcuringAgencyId')
                            ->whereNotNull('T2.ActualStartDate')
                            ->where(DB::raw('coalesce(T3.CmnWorkExecutionStatusId,0)'),'=',CONST_CMN_WORKEXECUTIONSTATUS_AWARDED)
                            ->where('T1.CIDNo',$cidNo)
                            ->groupBy('T1.Id')
                            ->get(array(DB::raw('distinct(T3.Id)'),DB::raw('group_concat(B.CDBNo SEPARATOR ",") as CDBNo')));
                DB::table('etltrackhrcheck')->insert(array('Id'=>$this->UUID(), 'WorkId'=>$workId[0]->WorkId, 'CIDNo'=>$cidNo,'CDBNo'=>$cdbNo[0]->CDBNo,'Operation'=>"Check",'SysUserId'=>Auth::user()->Id,'OperationTime'=>date('Y-m-d G:i:s')));
            }
            $hrWorksCinet = DB::table('cinetbidhumanresource as T1')
                            ->join('crpbiddingform as T2','T2.Id','=','T1.CrpBiddingFormId')
                            ->join('crpbiddingformdetail as A','A.CrpBiddingFormId','=','T2.Id')
                            ->join('crpcontractorfinal as B','B.Id','=','A.CrpContractorFinalId')
                            ->join('cmnprocuringagency as T4','T4.Id','=','T2.CmnProcuringAgencyId')
                            ->where("A.CmnWorkExecutionStatusId",CONST_CMN_WORKEXECUTIONSTATUS_AWARDED)
                            ->where("T2.CmnWorkExecutionStatusId",CONST_CMN_WORKEXECUTIONSTATUS_AWARDED)
                            ->where('T1.CIDNo',$cidNo)
                            ->groupBy('T2.Id')
                            ->get(array(DB::raw('distinct(T2.Id)'),DB::raw('group_concat(B.CDBNo SEPARATOR ",") as CDBNo'),DB::raw('coalesce(T2.WorkOrderNo,T2.ReferenceNo) as WorkId'),'T4.Name as ProcuringAgency'));

            if(count($hrWorksCinet) > 0){
                $workId = DB::table('cinetbidhumanresource as T1')
                            ->join('crpbiddingform as T2','T2.Id','=','T1.CrpBiddingFormId')
                            ->join('crpbiddingformdetail as A','A.CrpBiddingFormId','=','T2.Id')
                            ->join('crpcontractorfinal as B','B.Id','=','A.CrpContractorFinalId')
                            ->join('cmnprocuringagency as T4','T4.Id','=','T2.CmnProcuringAgencyId')
                            ->where("A.CmnWorkExecutionStatusId",CONST_CMN_WORKEXECUTIONSTATUS_AWARDED)
                            ->where("T2.CmnWorkExecutionStatusId",CONST_CMN_WORKEXECUTIONSTATUS_AWARDED)
                            ->where('T1.CIDNo',$cidNo)
                            ->groupBy('T1.Id')
                            ->get(array(DB::raw('distinct(T1.Id)'),DB::raw('group_concat(coalesce(T2.WorkOrderNo,T2.ReferenceNo) SEPARATOR ",") as WorkId')));

                $cdbNo = DB::table('cinetbidhumanresource as T1')
                            ->join('crpbiddingform as T2','T2.Id','=','T1.CrpBiddingFormId')
                            ->join('crpbiddingformdetail as A','A.CrpBiddingFormId','=','T2.Id')
                            ->join('crpcontractorfinal as B','B.Id','=','A.CrpContractorFinalId')
                            ->join('cmnprocuringagency as T4','T4.Id','=','T2.CmnProcuringAgencyId')
                            ->where("A.CmnWorkExecutionStatusId",CONST_CMN_WORKEXECUTIONSTATUS_AWARDED)
                            ->where("T2.CmnWorkExecutionStatusId",CONST_CMN_WORKEXECUTIONSTATUS_AWARDED)
                            ->where('T1.CIDNo',$cidNo)
                            ->groupBy('T1.Id')
                            ->get(array(DB::raw('distinct(T1.Id)'),DB::raw('group_concat(B.CDBNo SEPARATOR ",") as CDBNo')));
                DB::table('etltrackhrcheck')->insert(array('Id'=>$this->UUID(), 'WorkId'=>$workId[0]->WorkId, 'CIDNo'=>$cidNo,'CDBNo'=>$cdbNo[0]->CDBNo,'Operation'=>"Check",'SysUserId'=>Auth::user()->Id,'OperationTime'=>date('Y-m-d G:i:s')));
            }
            $hrWorks = array_merge($hrWorksCinet,$hrWorksEtool);
            $checkPartnerDeregistered = DB::table('crpcontractorfinal as T1')
                                            ->join('crpcontractorhumanresourcefinal as T2','T1.Id','=','T2.CrpContractorFinalId')
                                            ->whereIn(DB::raw("coalesce(T1.CmnApplicationRegistrationStatusId,'".CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW."')"),array(CONST_CMN_APPLICATIONREGISTRATIONSTATUS_DEREGISTERED,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_REVOKED,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_SUSPENDED,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_DEBARRED))
                                            ->where(DB::raw('TRIM(T2.CIDNo)'),trim($cidNo))
                                            ->where(DB::raw('coalesce(T2.IsPartnerOrOwner,0)'),1)
                                            ->get(array(DB::raw('distinct T1.Id'),'T1.CDBNo','T1.NameOfFirm'));
        }
	$hrAttachment = DB::select("SELECT a.`DocumentName`,a.`DocumentPath` FROM `crpcontractorhumanresourceattachmentfinal` a
        LEFT JOIN crpcontractorhumanresourcefinal b ON a.`CrpContractorHumanResourceFinalId`=b.`Id`
        WHERE b.`CIDNo`=".$cidNo);

        return View::make('report.hrcheck')
		->with('cbDetailsBiddingDetails',$cbDetailsBiddingDetails)
                ->with('checkPartnerDeregistered',$checkPartnerDeregistered)
                ->with('responseArray',$responseArray)
                ->with('individualName',$individualName)
                ->with('hrIsEngineer',$hrIsEngineer)
                ->with('hrIsGovtEmp',$hrIsGovtEmp)
                ->with('hrDetails',$hrDetails)
                ->with('hrWorks',$hrWorks)
                ->with('govtOrCorporateOrg',$govtOrCorporateOrg)
                ->with('workingAgency',$workingAgency)
		->with('hrAttachment',$hrAttachment)
                ->with('hrDetails',$hrDetails);
    }
    public function getOtherFirms(){
        $cidNo = Input::get('CIDNo');
        $etlTenderBidderContractorId = Input::get('etlTenderBidderContractorId');
        $firms = DB::table('crpcontractorfinal as T1')
            ->join('etltenderbiddercontractordetail as T2','T1.Id','=','T2.CrpContractorFinalId')
            ->where('T2.EtlTenderBidderContractorId',$etlTenderBidderContractorId)
            ->get(array('T1.Id'));
        $contractors = array();
        foreach($firms as $firm){
            array_push($contractors,$firm->Id);
        }
        $otherFirms = DB::table('crpcontractorfinal as T1')
            ->join('crpcontractorhumanresourcefinal as T2','T1.Id','=','T2.CrpContractorFinalId')
            ->where('T2.CIDNo',$cidNo)
            ->whereNotIn('T1.Id',$contractors)
            ->get(array('T1.CDBNo','T1.NameOfFirm'));
        return View::make('report.hrotherfirms')
            ->with('otherFirms',$otherFirms);
    }
}