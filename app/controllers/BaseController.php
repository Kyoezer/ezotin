<?php
class BaseController extends Controller {

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout(){
		if ( ! is_null($this->layout)){
			$this->layout = View::make($this->layout);
		}
	}
    public function pagination($query,$parameters,$recordsPerPage,$pageNo){
        if($pageNo != "All"){
            if(!(int)$pageNo>0){
                $pageNo = 1;
            }
        }
        $reportData = DB::select($query,$parameters);
        $noOfRecords = count($reportData);
        $noOfPages = ceil((int)$noOfRecords / (int)$recordsPerPage);
        $offset = ($pageNo - 1) * $recordsPerPage;
        if($pageNo == "All"){
            $returnArray['Start'] = 1;
            $limitAppend = " limit 0, $noOfRecords";
        }else{
            $returnArray['Start'] = ((int)$pageNo == 1)?1:((($pageNo-1)*$recordsPerPage)+1);
            $limitAppend = " limit $offset, $recordsPerPage";
        }

        $returnArray['NoOfPages'] = $noOfPages;
        $returnArray['LimitAppend'] = $limitAppend;


        return $returnArray;
    }
    public function correctBiddingForm(){
        $bids = DB::table('crpbiddingform as T1')
                    ->orderBy('migratedrecordid')
                    ->skip(6000)
                    ->take(1000)
                    ->get(array('T1.Id','T1.migratedrecordid','T1.CmnWorkExecutionStatusId'));
        foreach($bids as $bid):
            $trackRecordContractors = DB::table('trackrecord as T1')
                                        ->join('crpcontractorfinal as T2','T1.CDBNo','=','T2.CDBNo')
                                        ->whereIn('T1.Status',array('Awarded','Working','Completed','Completec'))
                                        ->where('T1.Recordid',$bid->migratedrecordid)
                                        ->get(array('T2.Id','T1.BidAmount','T1.EvalAmount'));
            $trackRecordContractorsList = DB::table('trackrecord as T1')
                ->join('crpcontractorfinal as T2','T1.CDBNo','=','T2.CDBNo')
                ->whereIn('T1.Status',array('Awarded','Working','Completed','Completec','Contract Terminated','Terminated'))
                ->where('T1.Recordid',$bid->migratedrecordid)
                ->lists('T2.Id');
            DB::table('crpbiddingformdetail')
                        ->where('CrpBiddingFormId',$bid->Id)
                        ->whereIn('CmnWorkExecutionStatusId',array(CONST_CMN_WORKEXECUTIONSTATUS_AWARDED,CONST_CMN_WORKEXECUTIONSTATUS_COMPLETED,CONST_CMN_WORKEXECUTIONSTATUS_TERMINATED))
                        ->whereNotIn('CrpContractorFinalId',$trackRecordContractorsList)
                        ->delete();

            $trackRecordStatus = DB::table('trackrecord as T1')
                                    ->where('T1.Recordid',$bid->migratedrecordid)
                                    ->whereIn('T1.Status',array('Awarded','Working','Completed','Completec','Contract Terminated','Terminated'))
                                    ->pluck('Status');
            if($trackRecordStatus == 'Working' || $trackRecordStatus == 'Awarded' || $trackRecordStatus == 'working' || $trackRecordStatus == 'awarded'){
                $trackRecordStatus = CONST_CMN_WORKEXECUTIONSTATUS_AWARDED;
            }
            if($trackRecordStatus == 'Completed' || $trackRecordStatus == 'Completec' || $trackRecordStatus == 'completed' || $trackRecordStatus == 'completec'){
                $trackRecordStatus = CONST_CMN_WORKEXECUTIONSTATUS_COMPLETED;
            }
            if($trackRecordStatus == 'Contract Terminated' || $trackRecordStatus == 'Terminated' || $trackRecordStatus == 'contract Terminated' || $trackRecordStatus == 'terminated'){
                $trackRecordStatus = CONST_CMN_WORKEXECUTIONSTATUS_TERMINATED;
            }
            DB::table('crpbiddingform')->where('Id',$bid->Id)->update(array('CmnWorkExecutionStatusId'=>$trackRecordStatus));

            if(count($trackRecordContractors) > 0){
                foreach($trackRecordContractors as $contractor):
                    $count = DB::table('crpbiddingformdetail')
                            ->where('CrpContractorFinalId',$contractor->Id)
                            ->where('CrpBiddingFormId',$bid->Id)
                            ->count();
                    if($count > 0){
                        DB::table('crpbiddingformdetail')
                                ->where('CrpContractorFinalId',$contractor->Id)
                                ->where('CrpBiddingFormId',$bid->Id)
                                ->update(array('CmnWorkExecutionStatusId'=>($trackRecordStatus == CONST_CMN_WORKEXECUTIONSTATUS_COMPLETED || $trackRecordStatus == CONST_CMN_WORKEXECUTIONSTATUS_AWARDED)?CONST_CMN_WORKEXECUTIONSTATUS_AWARDED:$trackRecordStatus));
                    }else{
                        DB::table('crpbiddingformdetail')
                                ->insert(array(
                                    'Id'=>$this->UUID(),
                                    'CrpBiddingFormId'=>$bid->Id,
                                    'CrpContractorFinalId'=>$contractor->Id,
                                    'BidAmount'=>$contractor->BidAmount,
                                    'EvaluatedAmount'=>$contractor->EvalAmount,
                                    'CmnWorkExecutionStatusId'=>($trackRecordStatus == CONST_CMN_WORKEXECUTIONSTATUS_COMPLETED || $trackRecordStatus == CONST_CMN_WORKEXECUTIONSTATUS_AWARDED)?CONST_CMN_WORKEXECUTIONSTATUS_AWARDED:$trackRecordStatus,
                                    'CreatedBy'=>'bf258f4b-c639-11e4-b574-080027dcfac6'
                                ));
                    }
                endforeach;
            }else{

            }
        endforeach;
        echo "Completed 7000";
    }
	public function UUID(){
		$uuid=DB::select("select uuid() as Id");
        $generatedId=$uuid[0]->Id;
        return $generatedId;
	}
	function selectSearchColumns($searchReference){
		$columns=DB::select("select ColumnName,ColumnText,DataTableOrderBy from cmnsearchresult where CmnSearchId=? order by ColumnOrderBy",array($searchReference));
		return $columns;
	}
	public function convertDate($value){
		$convertedDate=date('Y-m-d',strtotime($value));
		return $convertedDate;
	}
	/*Functions added by Sangay Wangdi*/
    public function convertDateTime($value){
        $convertedDateTime = date('Y-m-d G:i:s',strtotime($value));
        return $convertedDateTime;
    }
    public function postCheckHumanResource(){
        $hrIsGovtEmp = false;
        $cidNo = Input::get('cidNo');

        $array = array(
            "ssl" => array(
                'ciphers'=>'RC4-SHA',"verify_peer" => false,
                "verify_peer_name" => false
            )
        );
        $context = stream_context_create($array);
        $soapURL = "http://localhost:8080/G2CWebService/services/CitizenDtls?wsdl" ; //RCSC
        $webServiceCheck = @fopen("http://localhost:8080/G2CWebService/services/CitizenDtls?wsdl",'r');
        if(!$webServiceCheck) {
            $hrIsGovtEmp = false;
        }else{
            $soap_client = new SoapClient($soapURL,array('stream_context'=>$context,'soap_version'   => SOAP_1_2,'exceptions'=>0));

            $rcscResult = $soap_client->getRCSCDetails(array('cidNo'=>$cidNo)); //RCSC
            if(is_soap_fault($rcscResult)){
                $hrIsGovtEmp = false;
            }else{
                $resultArray = $rcscResult->getRCSCDetailsReturn;
                $hrIsGovtEmp = ((string)$resultArray->status=='Valid')?true:false;
            }
        }

        $etlTenderBidderContractorId = Input::get('etlTenderBidderContractorId');
        $etlTenderId = Input::get('etlTenderId');
        $checkOtherBidders = DB::table('etltenderbiddercontractor as T1')
                                ->join('etlcontractorhumanresource as T2','T2.EtlTenderBidderContractorId','=','T1.Id')
                                ->join('etltender as T3','T3.Id','=','T1.EtlTenderId')
                                ->where('T2.CIDNo',$cidNo)
                                ->where('T1.EtlTenderId',$etlTenderId)
                                ->where('T1.Id','<>',$etlTenderBidderContractorId)
                                ->whereNull('T3.CmnWorkExecutionStatusId')
                                ->count('T2.Id');
        if($checkOtherBidders > 0)
            return Response::json(array('reason'=>4,'message'=>"HR is already used by another bidder"));
        $tenderStartDate = DB::table('etltender')
                            ->where('Id',$etlTenderId)
                            ->pluck('TentativeStartDate');
        $firms = DB::table('crpcontractorfinal as T1')
                        ->join('etltenderbiddercontractordetail as T2','T1.Id','=','T2.CrpContractorFinalId')
                        ->where('T2.EtlTenderBidderContractorId',$etlTenderBidderContractorId)
                        ->get(array('T1.Id'));
        $contractors = array();
        foreach($firms as $firm){
            array_push($contractors,$firm->Id);
        }
        $checkOwnFirm = DB::table('crpcontractorfinal as T1')
                    ->join('crpcontractorhumanresourcefinal as T2','T1.Id','=','T2.CrpContractorFinalId')
                    ->where('T2.CIDNo',$cidNo)
                    ->whereNotIn('T1.Id',$contractors)
                    ->where('T1.CmnApplicationRegistrationStatusId',CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED)
                    ->get(array('T1.CDBNo','T1.NameOfFirm'));
        $isEngineer = DB::table('crpgovermentengineer')
                        ->where('CIDNo',$cidNo)
                        ->count('Id');
        if($isEngineer > 0){
            $hrIsEngineer = true;
        }else{
            $hrIsEngineer = false;
        }
        $noOfWorksEtool = DB::table('etltender as T1')
            ->join('etltenderbiddercontractor as T2','T2.EtlTenderId','=','T1.Id')
            ->join('etltenderbiddercontractordetail as A','A.EtlTenderBidderContractorId','=','T2.Id')
            ->join('etlcontractorhumanresource as T3','T3.EtlTenderBidderContractorId','=','T2.Id')
            ->where('T3.CIDNo',$cidNo)
            ->whereNotNull('T2.ActualStartDate')
            ->where('T2.ActualEndDate', '>',$tenderStartDate)
            ->where('T1.CmnWorkExecutionStatusId',CONST_CMN_WORKEXECUTIONSTATUS_AWARDED)
            ->count(DB::raw('distinct(T1.Id)'));

        $noOfWorksCinet = DB::table('cinetbidhumanresource as T1')
                            ->join('crpbiddingform as T2','T2.Id','=','T1.CrpBiddingFormId')
                            ->join('crpbiddingformdetail as A','A.CrpBiddingFormId','=','T2.Id')
                            ->join('crpcontractorfinal as B','B.Id','=','A.CrpContractorFinalId')
                            ->join('cmnprocuringagency as T4','T4.Id','=','T2.CmnProcuringAgencyId')
                            ->where("A.CmnWorkExecutionStatusId",CONST_CMN_WORKEXECUTIONSTATUS_AWARDED)
                            ->where("T2.CmnWorkExecutionStatusId",CONST_CMN_WORKEXECUTIONSTATUS_AWARDED)
                            ->where('T1.CIDNo',$cidNo)
                            ->groupBy('T2.Id')
                            ->count(DB::raw('distinct(T2.Id)'));
        $noOfWorks = $noOfWorksCinet + $noOfWorksEtool;
        if($isEngineer > 0){
            if($noOfWorks > 1){
                $check = false;
                $message = "Engineer is already involved in two works";
                $reason = 1;
            }else{
                $check = true;
            }
        }else{
            if($noOfWorks > 0){
                $check = false;
                $message = "HR personnel is already involved in another work";
                $reason = 1;
            }else{
                $check = true;
            }
        }
        $govtDbCheck = DB::table('crpgovermentengineer')->where(DB::raw('coalesce(Releaved,0)'),'=',0)->where(DB::raw('TRIM(CIDNo)'),'=',trim($cidNo))->count();
        if($hrIsEngineer){
            if($hrIsGovtEmp){
                return Response::json(array('reason'=>3,'message'=>"Engineer is a government employee"));
            }
        }else{
            if($hrIsGovtEmp){
                return Response::json(array('reason'=>3,'message'=>"Personnel is a government employee"));
            }
        }
        if($govtDbCheck){
            return Response::json(array('reason'=>3,'message'=>"Personnel is a government/corporate employee"));
        }
        if(count($checkOwnFirm) > 0){
            return Response::json(array('reason'=>2,'message'=>"HR is employed in another firm(s)"));
        }
        if($check){
            return Response::json(array('message'=>1));
        }else{
            return Response::json(array('message'=>$message,'reason'=>$reason));
        }
    }
    public function postCheckHumanResourceRegistration(){
        $hrIsGovtEmp = false;
        $cidNo = Input::get('cidNo');
        $firmId = Input::get('firmId');
        $type = Input::get('type');
        $isPartnerOrOwner = Input::get('partnerOwner');

        $array = array(
            "ssl" => array(
                'ciphers'=>'RC4-SHA',"verify_peer" => false,
                "verify_peer_name" => false
            )
        );
        $context = stream_context_create($array);
        $soapURL = "http://localhost:8080/G2CWebService/services/CitizenDtls?wsdl" ; //RCSC
        $webServiceCheck = @fopen("http://localhost:8080/G2CWebService/services/CitizenDtls?wsdl",'r');
        if(!$webServiceCheck) {
            $hrIsGovtEmp = false;
        }else{
            $soap_client = new SoapClient($soapURL,array('stream_context'=>$context,'soap_version'   => SOAP_1_2,'exceptions'=>0));

            $rcscResult = $soap_client->getRCSCDetails(array('cidNo'=>$cidNo)); //RCSC
            if(is_soap_fault($rcscResult)){
                $hrIsGovtEmp = false;
            }else{
                $resultArray = $rcscResult->getRCSCDetailsReturn;
                $hrIsGovtEmp = ((string)$resultArray->status=='Valid')?true:false;
            }
        }



        if($hrIsGovtEmp){
            return Response::json(array('reason'=>3,'message'=>"Personnel is a government employee"));
        }

        if((int)$type == 1){ //Type 1 for contractor
            $checkOtherContractorsFinal = DB::table('crpcontractorhumanresourcefinal as T1')
                ->where('CrpContractorFinalId','<>',$firmId)
                ->where('T1.CIDNo',$cidNo)
                ->count('T1.Id');
            $checkOtherConsultantsFinal = DB::table('crpconsultanthumanresourcefinal as T1')
                ->where('T1.CIDNo',$cidNo)
                ->count('T1.Id');
            $partnerOwnerCount = DB::table('crpconsultanthumanresourcefinal')
                ->where('CIDNo',$cidNo)
                ->where('IsPartnerOrOwner',1)
                ->count();

//            $checkOtherContractors = DB::table('crpcontractorhumanresource as T1')
//                ->where('CrpContractorId','<>',$firmId)
//                ->where('T1.CIDNo',$cidNo)
//                ->count('T1.Id');
//            $checkOtherConsultants = DB::table('crpconsultanthumanresource as T1')
//                ->where('T1.CIDNo',$cidNo)
//                ->count('T1.Id');

            $checkOwnFirm = DB::table('crpcontractorhumanresource as T1')
                ->where('CrpContractorId','=',$firmId)
                ->where('T1.CIDNo',$cidNo)
                ->count('T1.Id');
        }else{ // TYpe 2 for consultant
            $checkOtherContractorsFinal = DB::table('crpcontractorhumanresourcefinal as T1')
                ->where('T1.CIDNo',$cidNo)
                ->count('T1.Id');
            $checkOtherConsultantsFinal = DB::table('crpconsultanthumanresourcefinal as T1')
                ->where('T1.CIDNo',$cidNo)
                ->where('CrpConsultantFinalId','<>',$firmId)
                ->count('T1.Id');
            $partnerOwnerCount = DB::table('crpcontractorhumanresourcefinal')
                ->where('CIDNo',$cidNo)
                ->where('IsPartnerOrOwner',1)
                ->count();
//            $checkOtherContractors = DB::table('crpcontractorhumanresource as T1')
//                ->where('T1.CIDNo',$cidNo)
//                ->count('T1.Id');
//            $checkOtherConsultants = DB::table('crpconsultanthumanresource as T1')
//                ->where('T1.CIDNo',$cidNo)
//                ->where('CrpConsultantId','<>',$firmId)
//                ->count('T1.Id');
            $checkOwnFirm = DB::table('crpconsultanthumanresource as T1')
                ->where('CrpConsultantId','=',$firmId)
                ->where('T1.CIDNo',$cidNo)
                ->count('T1.Id');
        }

        if($checkOwnFirm > 0){
            return Response::json(array('reason'=>5,'message'=>"HR is already registered under your firm"));
        }

        $checkOtherFirms = $checkOtherConsultantsFinal+ $checkOtherContractorsFinal;
        if($checkOtherFirms > 0){
            if((int)$isPartnerOrOwner == 0){
                return Response::json(array('reason'=>4,'message'=>"HR is working for another firm"));
            }else{
                if($partnerOwnerCount == 0){
                    return Response::json(array('reason'=>4,'message'=>"HR is working for another firm"));
                }
            }
        }

        $isEngineer = DB::table('crpgovermentengineer')
            ->where('CIDNo',$cidNo)
            ->count('Id');
        if($isEngineer > 0){
            $hrIsEngineer = true;
            $relieved = DB::table('crpgovermentengineer')->where('CIDNo',$cidNo)->pluck('Releaved');
            if($relieved == 1){
                $hrIsGovtEmp = false;
            }else{
                $hrIsGovtEmp = true;
            }
        }else{
            $hrIsEngineer = false;
        }

    }
    public function postCheckEqDbandWebService(){
        $regNo = Input::get('regNo');
        $id = Input::get('id');
        $type = Input::get('type');

        if((int)$type == 1){
            $checkOwnFirmFinal = DB::table('crpcontractorequipmentfinal')
                                    ->where('CrpContractorFinalId',$id)
                                    ->where('RegistrationNo',$regNo)
                                    ->count();
            $checkOtherFirmFinal = DB::table('crpcontractorequipmentfinal')
                ->where('CrpContractorFinalId','<>',$id)
                ->where('RegistrationNo',$regNo)
                ->count();

        }else{
            $checkOwnFirmFinal = DB::table('crpconsultantequipmentfinal')
                ->where('CrpConsultantFinalId',$id)
                ->where('RegistrationNo',$regNo)
                ->count();
            $checkOtherFirmFinal = DB::table('crpconsultantequipmentfinal')
                ->where('CrpConsultantFinalId','<>',$id)
                ->where('RegistrationNo',$regNo)
                ->count();
        }

        if((int)$checkOwnFirmFinal > 0){
            return Response::json(array('Reason'=>1));
        }
        if((int)$checkOtherFirmFinal > 0){
            return Response::json(array('Reason'=>2));
        }

        $equipmentWorksEtool = DB::table('etlcontractorequipment as T1')
            ->join('etltenderbiddercontractor as T2','T1.EtlTenderBidderContractorId','=','T2.Id')
            ->join('etltender as T3','T3.Id','=','T2.EtlTenderId')
            ->whereNotNull('T2.ActualStartDate')
            ->where('T3.CmnWorkExecutionStatusId',CONST_CMN_WORKEXECUTIONSTATUS_AWARDED)
            ->where('T1.RegistrationNo',$regNo)
            ->count(DB::raw('distinct T3.Id'));

        $equipmentWorksCinet = DB::table('cinetbidequipment as T1')
                ->join('crpbiddingform as T2','T1.CrpBiddingFormId','=','T2.Id')
                ->join('crpbiddingformdetail as T3','T3.CrpBiddingFormId','=','T2.Id')
                ->join('cmnprocuringagency as T4','T2.CmnProcuringAgencyId','=','T4.Id')
                ->where('T2.CmnWorkExecutionStatusId',CONST_CMN_WORKEXECUTIONSTATUS_AWARDED)
                ->where('T3.CmnWorkExecutionStatusId',CONST_CMN_WORKEXECUTIONSTATUS_AWARDED)
                ->where(DB::raw('coalesce(T1.RegistrationNo,"")'),$regNo)
                ->count(DB::raw('distinct T2.Id'));

        $equipmentWorks = $equipmentWorksEtool + $equipmentWorksCinet;
        if($equipmentWorks > 0){
            return Response::json(array('Reason'=>3));
        }

        return Response::json(array('Reason'=>4));

    }
    public function postCheckEquipment(){
        $registrationNo = Input::get('registrationNo');
        $etlTenderBidderContractorId = Input::get('etlTenderBidderContractorId');
        $etlTenderId = Input::get('etlTenderId');
        $checkOtherBidders = DB::table('etltenderbiddercontractor as T1')
            ->join('etlcontractorequipment as T2','T2.EtlTenderBidderContractorId','=','T1.Id')
            ->join('etltender as T3','T3.Id','=','T1.EtlTenderId')
            ->where('T2.RegistrationNo',$registrationNo)
            ->where('T1.EtlTenderId',$etlTenderId)
            ->where('T1.Id','<>',$etlTenderBidderContractorId)
            ->whereNull('T3.CmnWorkExecutionStatusId')
            ->count('T2.Id');
        if($checkOtherBidders > 0)
            return Response::json(array('reason'=>2,'message'=>"Equipment is already used by another bidder"));
        $tenderStartDate = DB::table('etltender')
            ->where('Id',$etlTenderId)
            ->pluck('TentativeStartDate');
        $firms = DB::table('crpcontractorfinal as T1')
            ->join('etltenderbiddercontractordetail as T2','T1.Id','=','T2.CrpContractorFinalId')
            ->where('T2.EtlTenderBidderContractorId',$etlTenderBidderContractorId)
            ->get(array('T1.Id'));
        $contractors = array();
        foreach($firms as $firm){
            array_push($contractors,$firm->Id);
        }
        $checkOwnFirm = DB::table('crpcontractorfinal as T1')
            ->join('crpcontractorequipmentfinal as T2','T1.Id','=','T2.CrpContractorFinalId')
            ->where('T2.RegistrationNo',$registrationNo)
            ->whereNotIn('T1.Id',$contractors)
            ->count('T1.Id');
        $noOfWorksEtool = DB::table('etltender as T1')
            ->join('etltenderbiddercontractor as T2','T2.EtlTenderId','=','T1.Id')
            ->join('etlcontractorequipment as T3','T3.EtlTenderBidderContractorId','=','T2.Id')
            ->where('T3.RegistrationNo',$registrationNo)
            ->whereNotNull('T2.ActualStartDate')
            ->where('T2.ActualEndDate', '>',$tenderStartDate)
            ->where('T1.CmnWorkExecutionStatusId',CONST_CMN_WORKEXECUTIONSTATUS_AWARDED)
            ->count(DB::raw('distinct(T1.Id)'));

        $noOfWorksCinet = DB::table('cinetbidequipment as T1')
            ->join('crpbiddingform as T2','T1.CrpBiddingFormId','=','T2.Id')
            ->join('crpbiddingformdetail as T3','T3.CrpBiddingFormId','=','T2.Id')
            ->join('cmnprocuringagency as T4','T2.CmnProcuringAgencyId','=','T4.Id')
            ->whereNotNull('T2.WorkStartDate')
            ->where('T2.WorkCompletionDate', '>',$tenderStartDate)
            ->where('T2.CmnWorkExecutionStatusId',CONST_CMN_WORKEXECUTIONSTATUS_AWARDED)
            ->where('T3.CmnWorkExecutionStatusId',CONST_CMN_WORKEXECUTIONSTATUS_AWARDED)
            ->where(DB::raw('coalesce(T1.RegistrationNo,"")'),$regNo)
            ->count(DB::raw('distinct T2.Id'));

        $noOfWorks = $noOfWorksEtool + $noOfWorksCinet;
            if($noOfWorks > 0){
                $check = false;
                $message = "Equipment is already involved in another work ";
                $reason = 1;
            }else{
                $check = true;
            }

        if($check){
            if($checkOwnFirm > 0){
                $message = "Equipment belongs to another firm(s)";
                $reason = 1;
            }else{
                $reason = 2;
                $message = 1;
            }
            return Response::json(array('message'=>$message,'reason'=>$reason));
        }else{
            if($checkOwnFirm > 0){
                $reason = 3;
                $message = "Equipment is already engaged\nEquipment belongs to another firm(s)";
            }else{
                $reason = 4;
                $message = "Equipment is already engaged";
            }
            return Response::json(array('message'=>$message,'reason'=>$reason));
        }
    }
    public function postCheckHumanResourceOccupied(){
        $cidNo = Input::get('cidNo');
        $checkOwnFirm = DB::table('crpcontractorfinal as T1')
            ->join('crpcontractorhumanresourcefinal as T2','T1.Id','=','T2.CrpContractorFinalId')
            ->where('T2.CIDNo',$cidNo)
            ->count('T1.Id');
        if($checkOwnFirm > 0){
            return Response::json(array('message'=>1));
        }else{
            return Response::json(array('message'=>'Employee is already working for another firm'));
        }
    }
    public function postCheckEquipmentOccupied(){
        $registrationNo = Input::get('registrationNo');
        $checkOwnFirm = DB::table('crpcontractorfinal as T1')
            ->join('crpcontractorequipmentfinal as T2','T1.Id','=','T2.CrpContractorFinalId')
            ->where('T2.CIDNo',$registrationNo)
            ->count('T1.Id');
        if($checkOwnFirm > 0){
            return Response::json(array('message'=>1));
        }else{
            return Response::json(array('message'=>'Equipment belongs to another firm'));
        }
    }
    public function postCheckContractorStatus(){
        $statusReferenceNo = DB::table('crpcontractorfinal as T1')
            ->join('cmnlistitem as T2','T2.Id','=','T1.CmnApplicationRegistrationStatusId')
            ->pluck('T2.ReferenceNo');
        return $statusReferenceNo;
    }
    /*End of Functions by Sangay Wangdi */
	public function tableTransactionNo($model,$column){
		$newMaximumNo=$model::max($column);
		if((bool)$newMaximumNo!=NULL){
			$newMaximumNo+=1;
		}else{
			$newMaximumNo=1;
		}
		return $newMaximumNo;
	}
	public function sendEmailMessage($mailView,$mailData,$subject,$recipientAddress,$recipientName){
        try{
            Mail::send($mailView,$mailData,function($message) use ($recipientAddress,$recipientName,$subject){
                $message->to($recipientAddress,$recipientName)->subject($subject);
            });
        }catch(Exception $e){
            $errorMessage = $e->getMessage();
            $emailLogInputs['SentOn'] = date('Y-m-d G:i:s');
            $emailLogInputs['RecipientAddress'] = $recipientAddress;
            $emailLogInputs['RecipientName'] = $recipientName;
            $emailLogInputs['Status'] = 0;
            $emailLogInputs['ErrorMessage'] = $errorMessage;
            DB::table('sysemaillog')->insert($emailLogInputs);
        }

	}
	public function sendSms($message,$mobileNo)
    {
        $message = rawurlencode($message);
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'https://www.citizenservices.gov.bt/smsgateway/push.php?to=' . $mobileNo . '&msg=' . $message,
        ));
        $resp = curl_exec($curl);
        curl_close($curl);
        return $resp;
    }
	/*----------------------------------------------------Audit Trail for Etool and CiNet-------------------------------*/
	public function auditTrailEtoolCinet($messageDisplayed=null,$remarks=null,$indexAction,$workId=NULL){
		$userId=Auth::user()->Id;
		$actionDate=date('Y-m-d G:i:s');
		$auditInstance=new AuditTrailEtoolCinetModel();
		$auditInstance->Id=$this->UUID();
		$auditInstance->SysUserId=$userId;
		$auditInstance->ActionDate=$actionDate;
		$auditInstance->MessageDisplayed=$messageDisplayed;
		$auditInstance->MessageDisplayed=$remarks;
        $auditInstance->WorkId = $workId;
		$auditInstance->IndexAction=$indexAction;
		$auditInstance->save();
	}
    public function deleteRecord(){
        try{
            DB::beginTransaction();    
            $loggedInuser=Auth::user()->Id;
            $object=array();
            $deleteId=Input::get('deleteReference');
            $deleteTableName=Input::get('deleteReferenceModel');
            $databaseName=DB::connection()->getDatabaseName();
            $deleteTable=new $deleteTableName();
            $rootTable=$deleteTable->getTable();
            $cascadeTables=DB::select("SELECT DISTINCT TABLE_NAME FROM information_schema.REFERENTIAL_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = '$databaseName' AND REFERENCED_TABLE_NAME = '$rootTable' AND DELETE_RULE = 'CASCADE'");
            foreach($cascadeTables as $cascadeTable){
                $childTable=$cascadeTable->TABLE_NAME;
                $cascadeColumnName=DB::select("select column_name from information_schema.key_column_usage where TABLE_NAME='$childTable' and REFERENCED_TABLE_NAME ='$rootTable'");
                $foreignKeyColumn=$cascadeColumnName[0]->column_name;
                DB::statement("insert into sysdeletedrecord select Id,'$deleteId','$loggedInuser' from $childTable where $foreignKeyColumn='$deleteId'");
                DB::table($childTable)->where($foreignKeyColumn,'=',$deleteId)->delete();
//                DB::table('sysdeletedrecord')->where('ParentId','=',$deleteId)->delete();
            }   
            //---------------------------------------------------------------//
            DB::statement("insert into sysdeletedrecord select Id,'$deleteId','$loggedInuser' from $rootTable where Id='$deleteId'");
            $hasdeleted=$deleteTable::where('Id','=', $deleteId)->delete();
//            DB::table('sysdeletedrecord')->where('Id','=',$deleteId)->delete();
            //--------------------------------------------------------------//
            DB::commit();
            $object["hasdeleted"]=$hasdeleted;
            $object["message"]='Record deleted successfully.';
            return Response::json($object);
        }catch(Exception $e){
            DB::connection()->getPdo()->rollBack();
            $errorMessage='';
            $sqlError=$e->getMessage();
            $sqlErrorNo=strpos($sqlError,'1451')?1451:0;
            $sqlErrorNo=strpos($sqlError,'1644')?1644:0;
            switch($sqlErrorNo){
                case 1451:
                    $errorMessage='The record you wanted to delete is being used somewhere else.';
                break;
                case 1644:
                    $errorMessage=substr($sqlError,strpos($sqlError,'1644')+5,strpos($sqlError,' (SQL')-(strpos($sqlError,'1644')+5));
                break;
                default:
                    $errorMessage='ERROR! The record could not be deleted. There was some problem while trying to delete the record.';
                break;
            }
            return $errorMessage;
        }
    }
    public function checkHRWebService(){
        $id = Input::get('id');
        if(strlen($id) != 11){
            return 2;
        }
        $array = array(
            "ssl" => array(
                'ciphers'=>'RC4-SHA',"verify_peer" => false,
                "verify_peer_name" => false
            )
        );
        $context = stream_context_create($array);
        $soapURL = "http://192.168.0.1:8080/G2CWebService/services/CitizenDtls?wsdl"; //RCSC

        $soap_client = new SoapClient($soapURL,array('stream_context'=>$context,'soap_version' => SOAP_1_2));
        $result = $soap_client->getCitizenDetails(array('cid'=>$id));
        $resultArray = $result->getCitizenDetailsReturn;
        dd($resultArray->Name);
    }

    public function postDeleteFromDb(){
        $id = Input::get('id');
        $table = Input::get('table');
        try{
            DB::table($table)->where('Id',$id)->delete();
            return 1;
        }catch(Exception $e){
            return 0;
        }
    }
    public function deleteDbRow(){
        $id = Input::get('id');
        $model = Input::get('model');
        DB::beginTransaction();
        try{
            if($model == "ContractorHumanResourceFinalModel"){
                $applicationId = Input::get('applicationId');
                if((bool)$applicationId){
                    if($applicationId != "xx"){
                        DB::statement("INSERT INTO crpdeletedhumanresource (Id,ApplicantFinalId,ApplicationId,CIDNo,CmnDesignationId,Name,DeletedOn, SysDeletedByUserId) SELECT UUID(),CrpContractorFinalId,?,CIDNo,CmnDesignationId,Name,CURDATE(), ? from crpcontractorhumanresourcefinal WHERE Id = ?",array($applicationId,Auth::user()->Id,$id));
                    }
                }
            }
            $model::where('Id',$id)->delete();
            if($model == "ContractorHumanResourceAttachmentFinalModel"){
                ContractorHumanResourceAttachmentModel::where('Id',$id)->delete();
            }
            if($model == "ContractorEquipmentAttachmentFinalModel"){
                ContractorEquipmentAttachmentModel::where('Id',$id)->delete();
            }
            if($model == "ConsultantHumanResourceAttachmentFinalModel"){
                ConsultantHumanResourceAttachmentModel::where('Id',$id)->delete();
            }
            if($model == "ConsultantEquipmentAttachmentFinalModel"){
                ConsultantEquipmentAttachmentModel::where('Id',$id)->delete();
            }

        }catch(Exception $e){
            DB::rollback();
            dd($e->getMessage());
            return 0;
        }
        DB::commit();
        return 1;
    }
    public function postCheckHrDbAndWebService(){
        $cid = Input::get('cid');
        $array = array(
            "ssl" => array(
                'ciphers'=>'RC4-SHA',"verify_peer" => false,
                "verify_peer_name" => false
            )
        );
        $context = stream_context_create($array);
        $soapURL = "http://localhost:8080/G2CWebService/services/CitizenDtls?wsdl" ; //RCSC

        $soap_client = new SoapClient($soapURL,array('stream_context'=>$context,'soap_version'   => SOAP_1_2));
        $result = $soap_client->getCitizenDetails(array('cid'=>'11806000379')); //RCSC
        $data = $result->getCitizenDetailsReturn;


        $genderRaw = (string)$data->gender;
        $responseArray['gender'] = ($genderRaw == "M")?"Male":"Female";
        $responseArray['name'] = (string)$data->fullName;
        $responseArray['dzongkhag'] = (string)$data->permDzongkhagName;
        $responseArray['gewog'] = (string)$data->permGewogName;
        $responseArray['village'] = (string)$data->permvillageName;
        $responseArray['dob'] = (string)$data->dob;
    }
    public function saveConfirmationSendMail(){

    }
    public function updateDeleteNotification($id){
        DB::table('crpnotificationfordeletedhumanresource')->where('Id',$id)->update(array('IsDeleted'=>1));
        return Redirect::to(Input::get('redirect'))->with('savedsuccessmessage',"<strong>SUCCESS! </strong>Reminder has been archived!");
    }
    public function getTest(){
        return View::make('test');
    }
    public function postTest(){
        $file = Input::file('File');
        $fileName = $file->getClientOriginalName();
        //CHECK IF IMAGE
        if(strpos($file->getClientMimeType(),'image/')>-1){
            $img = Image::make(Input::file('File'))->encode('jpg');
            $img->save('uploads/test/xx.jpg',45);
        }

    }
    public function checkNewWebservice(){
        $array = array(
            "ssl" => array(
                'ciphers'=>'RC4-SHA',
                "verify_peer" => false,
                "verify_peer_name" => false
            )
        );
        $context = stream_context_create($array);
        $soapURL = "http://172.30.16.43:8080/EralisWebService/services/EralisDetials?wsdl"; //RCSC


        $webServiceCheck = @fopen("http://172.30.16.43:8080/EralisWebService/services/EralisDetials?wsdl",'r');
        if(!$webServiceCheck) {
            $responseArray['Owner'] = '';
            $responseArray['Region'] = '';
            $responseArray['VehicleType'] = '';
            $responseArray['RegistrationNo'] = '';
        }else{
            $soap_client = new SoapClient($soapURL,array('soap_version' => SOAP_1_2));
            $equipmentTypeArray = array(2,3,4,5,6,7,15,16,17,18);
            $count = 0;
            foreach($equipmentTypeArray as $type):
                $result = $soap_client->getRSTADetails(array('RegistrationNo'=>"BP-1-B1620",'VehicleType'=>$type));
                $data = $result->getRSTADetailsReturn;
                if((bool)$data->registrationNo){
                    $curCount = $count++;
                    $responseArray[$curCount]['RegistrationNo'] = (string)$data->registrationNo;
                    $responseArray[$curCount]['Owner'] = (string)$data->applicantName;
                    $responseArray[$curCount]['Region'] = (string)$data->region;
                    $responseArray[$curCount]['VehicleType'] = (string)$data->vehicleType;
                    $responseArray[$curCount]['CIDNo'] = (string)$data->cidNo;
                    $responseArray[$curCount]['ContactNo'] = (string)$data->contactNo;
                    $responseArray[$curCount]['VehicleModel'] = (string)$data->vehicleModel;
                    $responseArray[$curCount]['Address'] = (string)$data->address;
                    $responseArray[$curCount]['ExpiryDate'] = (string)$data->expiryDate;
                }
                
            endforeach;
            
        }
        echo "<PRE>"; dd($responseArray);
    }
}
