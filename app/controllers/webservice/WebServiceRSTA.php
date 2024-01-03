<?php

use Artisaninweb\SoapWrapper\Facades\SoapWrapper;

class WebServiceRSTA extends BaseController{
    public function getVehicleDetails(){
        $fetched = 1;
        $registrationNo = Input::get('regNo');
        $vehicleType = Input::get('vehicleType');
        $array = array(
            "ssl" => array(
                'ciphers'=>'RC4-SHA',"verify_peer" => false,
                "verify_peer_name" => false
            )
        );
        $context = stream_context_create($array);
        $soapURL = "http://172.30.3.165:8080/EralisWebService/services/EralisDetials?wsdl" ; //RCSC

        $webServiceCheck = @fopen("http://172.30.3.165:8080/EralisWebService/services/EralisDetials?wsdl",'r');
        if(!$webServiceCheck) {
            $fetched = 0;
            $responseArray = array();
        }else{
            $soap_client = new SoapClient($soapURL,array('stream_context'=>$context,'soap_version' => SOAP_1_2));
            $equipmentTypeArray = array(2,3,4,5,6,7,15,16,17,18);
            $count = 0;
            foreach($equipmentTypeArray as $type):
                $result = $soap_client->getRSTADetails(array('RegistrationNo'=>$registrationNo,'VehicleType'=>$type));
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

        return Response::json(array('success'=>$fetched,'results'=>$responseArray));
    }
}
