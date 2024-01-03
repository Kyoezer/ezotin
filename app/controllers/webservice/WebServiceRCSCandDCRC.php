<?php

/**
 * Created by PhpStorm.
 * User: swm
 * Date: 5/2/2016
 * Time: 7:07 PM
 */
use Artisaninweb\SoapWrapper\Facades\SoapWrapper;
class WebServiceRCSCandDCRC extends BaseController
{
    public function postCheck(){
        $cidNo = Input::get('cidNo');
        $array = array(
            "ssl" => array(
                'ciphers'=>'RC4-SHA',"verify_peer" => false,
                "verify_peer_name" => false
            )
        );
        $context = stream_context_create($array);
        $soapURL = "http://localhost:8080/G2CWebService/services/CitizenDtls?wsdl" ; //RCSC


        $soap_client = new SoapClient($soapURL,array('stream_context'=>$context,'soap_version' => SOAP_1_2));



        $dcrcResult = $soap_client->getCitizenDetails(array('cid'=>$cidNo));
        $data = $dcrcResult->getCitizenDetailsReturn;


        $genderRaw = (string)$data->gender;
        $responseArray['gender'] = ($genderRaw == "M")?"Male":"Female";
        $responseArray['name'] = (string)$data->fullName;
        $responseArray['dzongkhag'] = (string)$data->permDzongkhagName;
        $responseArray['gewog'] = (string)$data->permGewogName;
        $responseArray['village'] = (string)$data->permvillageName;
        $responseArray['dob'] = (string)$data->dob;

        $rcscResult = $soap_client->getRCSCDetails(array('cid'=>$cidNo));
        echo '<pre>';dd($rcscResult);

        return Response::json($responseArray);


    }
}