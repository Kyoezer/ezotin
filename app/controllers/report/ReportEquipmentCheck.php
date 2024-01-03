<?php

class ReportEquipmentCheck extends ReportController{

    public function getReportPage(){
        $equipments = DB::select("SELECT VehicleType ,`VehicleTypeName` AS Name FROM  cmnequipment WHERE VehicleType > 0 GROUP BY VehicleType");
	//$equipments = CmnEquipmentModel::equipment()->whereNotNull('Code')->get(array('Id','Name','VehicleType'));
        return View::make('report.checkequipment')
        ->with('equipments',$equipments);
    }
    
    public function getIndex(){
        $registrationNo = Input::get('RegistrationNo');
        $rstaVehicleType = Input::get('VehicleType');
        $ownerCId = Input::get('ownerCId');
        $ownerName = Input::get('ownerName');
        $expiryDate = Input::get('expiryDate');
        $vehicleModel = Input::get('vehicleModel');
        $chassisNumber = Input::get('chassisNumber');
        $vehicleType = Input::get('VehicleType');
        $engineNumber = Input::get('engineNumber');


        $equipmentOwner = array();
        $equipmentDetails = array();
        $responseArray = array();
        $vehicleTypeFromDB = '';
        if((bool)$registrationNo){
            DB::table('tblworkidtrack')->insert(array('workid'=>$registrationNo,'username'=>(isset(Auth::user()->Id))?Auth::user()->username:'Applicant','operation'=>'Report 7','op_time'=>date('Y-m-d G:i:s')));
            if(!(bool)$vehicleType){
                $vehicleType = DB::table('crpcontractorequipmentfinal as T1')
                                ->join('cmnequipment as T2','T2.Id','=','T1.CmnEquipmentId')
                                ->whereRaw("TRIM(T1.RegistrationNo)=?",array(trim($registrationNo)))
                                ->pluck('T2.VehicleType');
                if(!(bool)$vehicleType){
                    $vehicleType = DB::table('crpconsultantequipmentfinal as T1')
                        ->join('cmnequipment as T2','T2.Id','=','T1.CmnEquipmentId')
                        ->whereRaw("TRIM(T1.RegistrationNo)=?",array(trim($registrationNo)))
                        ->pluck('T2.VehicleType');
                    $vehicleTypeFromDB = DB::table('crpconsultantequipmentfinal as T1')
                        ->join('cmnequipment as T2','T2.Id','=','T1.CmnEquipmentId')
                        ->whereRaw("TRIM(T1.RegistrationNo)=?",array(trim($registrationNo)))
                        ->pluck('T2.Name');
                }else{
                    $vehicleTypeFromDB = DB::table('crpcontractorequipmentfinal as T1')
                        ->join('cmnequipment as T2','T2.Id','=','T1.CmnEquipmentId')
                        ->whereRaw("TRIM(T1.RegistrationNo)=?",array(trim($registrationNo)))
                        ->pluck('T2.Name');
                }

                if(!(bool)$vehicleTypeFromDB){
                    $vehicleTypeFromDB = DB::table('etlcontractorequipment as T1')
                                            ->join('cmnequipment as T2','T2.Id','=','T1.CmnEquipmentId')
                                            ->whereRaw("TRIM(T1.RegistrationNo) = ?",array(trim($registrationNo)))
                                            ->pluck('T2.Name');
                }
            }
            //if((bool)$vehicleType){
                // $array = array(
                //     "ssl" => array(
                //         'ciphers'=>'RC4-SHA',"verify_peer" => false,
                //         "verify_peer_name" => false
                //     )
                // );
               // $context = stream_context_create($array);
                //$soapURL = "http://172.30.16.43:8080/EralisWebService/services/EralisDetials?wsdl" ; //RCSC

                $responseArray[0]['RegistrationNo'] = $registrationNo;
                $responseArray[0]['Owner'] = $ownerName; 
                $responseArray[0]['VehicleType'] =$rstaVehicleType;
                $responseArray[0]['CIDNo'] = $ownerCId;
                $responseArray[0]['VehicleModel'] = $vehicleModel;
                $responseArray[0]['chassisNumber'] = $chassisNumber;
                $responseArray[0]['engineNumber'] = $engineNumber;
                $responseArray[0]['ExpiryDate'] = $expiryDate;
                // $webServiceCheck = @fopen("http://172.30.16.43:8080/EralisWebService/services/EralisDetials?wsdl",'r');
                // if(!$webServiceCheck) {
                //     $responseArray['Owner'] = '';
                //     $responseArray['Region'] = '';
                //     $responseArray['VehicleType'] = '';
                //     $responseArray['RegistrationNo'] = '';
                // }else{
                //     $soap_client = new SoapClient($soapURL,array('stream_context'=>$context,'soap_version' => SOAP_1_2));
                //     $equipmentTypeArray = array(2,3,4,5,6,7,15,16,17,18);
                //     $count = 0;
                //     foreach($equipmentTypeArray as $type):
                //         $result = $soap_client->getRSTADetails(array('RegistrationNo'=>$registrationNo,'VehicleType'=>$type));
                //         $data = $result->getRSTADetailsReturn;
                //         if((bool)$data->registrationNo){
                //             $curCount = $count++;
                //             $responseArray[$curCount]['RegistrationNo'] = (string)$data->registrationNo;
                //             $responseArray[$curCount]['Owner'] = (string)$data->applicantName;
                //             $responseArray[$curCount]['Region'] = (string)$data->region;
                //             $responseArray[$curCount]['VehicleType'] = (string)$data->vehicleType;
                //             $responseArray[$curCount]['CIDNo'] = (string)$data->cidNo;
                //             $responseArray[$curCount]['ContactNo'] = (string)$data->contactNo;
                //             $responseArray[$curCount]['VehicleModel'] = (string)$data->vehicleModel;
                //             $responseArray[$curCount]['Address'] = (string)$data->address;
                //             $responseArray[$curCount]['ExpiryDate'] = (string)$data->expiryDate;
                //         }
                        
                //     endforeach;
                // }

            // }else{
            //     $responseArray['Owner'] = false;
            // }


            
            $equipmentOwner = DB::select("select distinct T2.CDBNo, T2.NameOfFirm from crpcontractorequipmentfinal as T1 join crpcontractorfinal as T2 on T2.Id = T1.CrpContractorFinalId where T1.RegistrationNo = '$registrationNo' union select distinct T2.CDBNo, T2.NameOfFirm from crpcontractorequipmentfinal as T1 join crpcontractorfinal as T2 on T2.Id = T1.CrpContractorFinalId where T1.RegistrationNo = '$registrationNo'");
            $equipmentDetailsEtool = DB::table('etlcontractorequipment as T1')
                ->join('etltenderbiddercontractor as T2',function($join){
                    $join->on('T1.EtlTenderBidderContractorId','=','T2.Id')
                        ->on('T2.ActualStartDate',DB::raw('is'),DB::raw('not null'));
                })
                ->join('etltender as T3','T2.EtlTenderId','=','T3.Id')
                ->join('cmnprocuringagency as T4','T3.CmnProcuringAgencyId','=','T4.Id')
                ->where('T3.CmnWorkExecutionStatusId',CONST_CMN_WORKEXECUTIONSTATUS_AWARDED)
                ->where(DB::raw('coalesce(T1.RegistrationNo,"")'),$registrationNo)
                ->get(array(DB::raw('distinct(T3.Id)'),DB::raw("case when T3.migratedworkid is null then concat(T4.Code,'/',year(T3.UploadedDate),'/',T3.WorkId) else T3.migratedworkid end as WorkId"),'T4.Name as ProcuringAgency'));
            if(count($equipmentDetailsEtool > 0)){
                $workId = DB::table('etlcontractorequipment as T1')
                    ->join('etltenderbiddercontractor as T2',function($join){
                        $join->on('T1.EtlTenderBidderContractorId','=','T2.Id')
                            ->on('T2.ActualStartDate',DB::raw('is'),DB::raw('not null'));
                    })
                    ->join('etltender as T3','T2.EtlTenderId','=','T3.Id')
                    ->join('cmnprocuringagency as T4','T3.CmnProcuringAgencyId','=','T4.Id')
                    ->where('T3.CmnWorkExecutionStatusId',CONST_CMN_WORKEXECUTIONSTATUS_AWARDED)
                    ->where(DB::raw('coalesce(T1.RegistrationNo,"")'),$registrationNo)
                    ->groupBy('T1.Id')
                    ->get(array(DB::raw('distinct(T1.Id)'),DB::raw("group_concat(case when T3.migratedworkid is null then concat(T4.Code,'/',year(T3.UploadedDate),'/',T3.WorkId) else T3.migratedworkid end) as WorkId")));
                $cdbNo = DB::table('etlcontractorequipment as T1')
                    ->join('etltenderbiddercontractor as T2',function($join){
                        $join->on('T1.EtlTenderBidderContractorId','=','T2.Id')
                            ->on('T2.ActualStartDate',DB::raw('is'),DB::raw('not null'));
                    })
                    ->join('etltender as T3','T2.EtlTenderId','=','T3.Id')
                    ->join('cmnprocuringagency as T4','T3.CmnProcuringAgencyId','=','T4.Id')
                    ->join('etltenderbiddercontractordetail as T5','T5.EtlTenderBidderContractorId','=','T2.Id')
                    ->join('crpcontractorfinal as T6','T6.Id','=','T5.CrpContractorFinalId')
                    ->where('T3.CmnWorkExecutionStatusId',CONST_CMN_WORKEXECUTIONSTATUS_AWARDED)
                    ->where(DB::raw('coalesce(T1.RegistrationNo,"")'),$registrationNo)
                    ->groupBy('T1.Id')
                    ->get(array(DB::raw('distinct(T3.Id)'),DB::raw('group_concat(T6.CDBNo) as CDBNo')));
                if(count($cdbNo)>0){
                    DB::table('etltrackequipmentcheck')->insert(array('Id'=>$this->UUID(), 'WorkId'=>isset($workId[0]->WorkId)?$workId[0]->WorkId:NULL, 'RegistrationNo'=>$registrationNo,'CDBNo'=>isset($cdbNo[0]->CDBNo)?$cdbNo[0]->CDBNo:NULL,'Operation'=>"Check",'SysUserId'=>(isset(Auth::user()->Id))?Auth::user()->Id:'Applicant','OperationTime'=>date('Y-m-d G:i:s')));
                }
            }
            $equipmentDetailsCinet = DB::table('cinetbidequipment as T1')
                ->join('crpbiddingform as T2','T1.CrpBiddingFormId','=','T2.Id')
                ->join('crpbiddingformdetail as T3','T3.CrpBiddingFormId','=','T2.Id')
                ->join('cmnprocuringagency as T4','T2.CmnProcuringAgencyId','=','T4.Id')
                ->where('T2.CmnWorkExecutionStatusId',CONST_CMN_WORKEXECUTIONSTATUS_AWARDED)
                ->where('T3.CmnWorkExecutionStatusId',CONST_CMN_WORKEXECUTIONSTATUS_AWARDED)
                ->where(DB::raw('coalesce(T1.RegistrationNo,"")'),$registrationNo)
                ->get(array(DB::raw('distinct(T2.Id)'),DB::raw("(T2.ReferenceNo) as WorkId"),'T4.Name as ProcuringAgency'));
            if(count($equipmentDetailsCinet > 0)){
                $workId = DB::table('cinetbidequipment as T1')
                            ->join('crpbiddingform as T2','T1.CrpBiddingFormId','=','T2.Id')
                            ->join('crpbiddingformdetail as T3','T3.CrpBiddingFormId','=','T2.Id')
                            ->join('cmnprocuringagency as T4','T2.CmnProcuringAgencyId','=','T4.Id')
                            ->where('T2.CmnWorkExecutionStatusId',CONST_CMN_WORKEXECUTIONSTATUS_AWARDED)
                            ->where('T3.CmnWorkExecutionStatusId',CONST_CMN_WORKEXECUTIONSTATUS_AWARDED)
                            ->where(DB::raw('coalesce(T1.RegistrationNo,"")'),$registrationNo)
                            ->groupBy('T1.Id')
                            ->get(array(DB::raw('distinct(T1.Id)'),DB::raw("group_concat((T2.ReferenceNo)) as WorkId")));
                $cdbNo = DB::table('cinetbidequipment as T1')
                            ->join('crpbiddingform as T2','T1.CrpBiddingFormId','=','T2.Id')
                            ->join('crpbiddingformdetail as T3','T3.CrpBiddingFormId','=','T2.Id')
                            ->join('crpcontractorfinal as A','A.Id','=','T3.CrpContractorFinalId')
                            ->join('cmnprocuringagency as T4','T2.CmnProcuringAgencyId','=','T4.Id')
                            ->where('T2.CmnWorkExecutionStatusId',CONST_CMN_WORKEXECUTIONSTATUS_AWARDED)
                            ->where('T3.CmnWorkExecutionStatusId',CONST_CMN_WORKEXECUTIONSTATUS_AWARDED)
                            ->where(DB::raw('coalesce(T1.RegistrationNo,"")'),$registrationNo)
                            ->groupBy('T1.Id')
                            ->get(array(DB::raw('distinct(T2.Id)'),DB::raw('group_concat(A.CDBNo) as CDBNo')));

                if(count($cdbNo)>0){
                    DB::table('etltrackequipmentcheck')->insert(array('Id'=>$this->UUID(), 'WorkId'=>isset($workId[0]->WorkId)?$workId[0]->WorkId:NULL, 'RegistrationNo'=>$registrationNo,'CDBNo'=>isset($cdbNo[0]->CDBNo)?$cdbNo[0]->CDBNo:NULL,'Operation'=>"Check",'SysUserId'=>(isset(Auth::user()->Id))?Auth::user()->Id:'Applicant','OperationTime'=>date('Y-m-d G:i:s')));
                }
            }
            $equipmentDetails = array_merge($equipmentDetailsCinet,$equipmentDetailsEtool);
        }
//        dd($vehicleTypeFromDB);
        return View::make('report.equipmentcheck')
                ->with('vehicleTypeFromDB',$vehicleTypeFromDB)
                ->with('responseArray',$responseArray)
                ->with('equipmentOwner',$equipmentOwner)
                ->with('equipmentDetails',$equipmentDetails);
    }
}