<?php

use Artisaninweb\SoapWrapper\Facades\SoapWrapper;

class WebServiceG2C extends BaseController{
    public function getIndex(){
        $array = array(
            "ssl" => array(
                'ciphers'=>'RC4-SHA',"verify_peer" => false,
                "verify_peer_name" => false
            )
        );
        $context = stream_context_create($array);
        $soapURL = "http://192.168.0.1:8080/G2CWebService/services/CitizenDtls?wsdl" ; //RCSC

        $soap_client = new SoapClient($soapURL,array('stream_context'=>$context,'soap_version'   => SOAP_1_2));
        $result = $soap_client->getCitizenDetails(array('cid'=>'11806000379'));
        dd($result);

        /*RCSC Details*/
        $result = $soap_client->getRCSCDetails(array('cidNo'=>'11806000373')); //RCSC
        $resultArray = $result->getRCSCDetailsReturn;
        dd($resultArray->status);
        /*END RCSC Details*/  /* CHECK IF civil servant or not */

        /*Citizen details*/
        $result = $soap_client->getCitizenDetails(array('cid'=>'11806000379'));
        $resultArray = $result->getCitizenDetailsReturn;
        dd($resultArray->permvillageName);
        /*End citizen details*/

        /*RCSC Details*/
        $result = $soap_client->getRCSCDetails(array('EmpNo'=>'201101143','EmpType'=>1)); //RCSC
        $resultArray = $result->getRCSCDetailsReturn;
        dd($resultArray->cidNo);
        /*END RCSC Details*/
//
        dd($result);
    }
    public function getCitizenDetails(){
        $cidNo = Input::get('cidNo');
        $array = array(
            "ssl" => array(
                'ciphers'=>'RC4-SHA',"verify_peer" => false,
                "verify_peer_name" => false
            )
        );
        $context = stream_context_create($array);
        $soapURL = "http://localhost:8080/G2CWebService/services/CitizenDtls?wsdl" ; //RCSC


        $soap_client = new SoapClient($soapURL,array('stream_context'=>$context,'soap_version' => SOAP_1_2,'exceptions'=>0));
	 


        $result = $soap_client->getCitizenDetails(array('cid'=>$cidNo));
        if(is_soap_fault($result)){
            $responseArray['gender'] = '';
            $responseArray['name'] = '';
            $responseArray['dzongkhag'] = '';
            $responseArray['gewog'] = '';
            $responseArray['village'] = '';
            $responseArray['dob'] = '';
        }else{
            $data = $result->getCitizenDetailsReturn;
            $genderRaw = (string)$data->gender;
            $responseArray['gender'] = ($genderRaw == "M")?"Male":"Female";
            $responseArray['name'] = (string)$data->fullName;
            $responseArray['dzongkhag'] = (string)$data->permDzongkhagName;
            $responseArray['gewog'] = (string)$data->permGewogName;
            $responseArray['village'] = (string)$data->permvillageName;
            $responseArray['dob'] = (string)$data->dob;
        }


        $rcscResult = $soap_client->getRCSCDetails(array('cidNo'=>$cidNo)); //RCSC
        if(is_soap_fault($rcscResult)){
            $responseArray["IsCivilServant"] = 0;
        }else{
            $resultArray = $rcscResult->getRCSCDetailsReturn;
            $hrIsGovtEmp = ((string)$resultArray->status=='Valid')?'1':'0';
            $responseArray['IsCivilServant'] = $hrIsGovtEmp;
        }
        return Response::json($responseArray);
    }

    public function getHrCheck($cid){
        $array = array(
            "ssl" => array(
                'ciphers'=>'RC4-SHA',"verify_peer" => false,
                "verify_peer_name" => false
            )
        );
        $context = stream_context_create($array);
        $soapURL = "http://localhost:8080/G2CWebService/services/CitizenDtls?wsdl" ; //RCSC

        $soap_client = new SoapClient($soapURL,array('stream_context'=>$context,'soap_version' => SOAP_1_2,'exceptions'=>0));


        $rcscResult = $soap_client->getRCSCDetails(array('cidNo'=>$cid));
        if(is_soap_fault($rcscResult)){
            echo "RCSC web service down";
        }else{
            $rcscData = $rcscResult->getRCSCDetailsReturn;
            echo '<pre>';dd($cid,$rcscResult);
        }

    }

    public function getTrial(){
        $url = "https://172.16.149.102:8243/services/citizen_details_secured?wsdl";
        $client = new SoapClient($url);
        $fcs = $client->__getFunctions();
        $res = $client->getParentDetail(array('cid'=>'11806000379'));
        echo "<pre>";
        dd($res);
    }

    public function getCitCheck($cid = NULL){
        if(!(bool)$cid){
            dd('Please pass CID in url after slash');
        }else{
            $array = array(
                "ssl" => array(
                    'ciphers'=>'RC4-SHA',"verify_peer" => false,
                    "verify_peer_name" => false
                )
            );
            $context = stream_context_create($array);
            $soapURL = "http://localhost:8080/G2CWebService/services/CitizenDtls?wsdl" ; //RCSC

            $webServiceCheck = @fopen("http://localhost:8080/G2CWebService/services/CitizenDtls?wsdl",'r');
            if(!$webServiceCheck){
                echo "TWAN down";
                if(Input::get('try')==1){
                    $soap_client = new SoapClient($soapURL,array('stream_context'=>$context,'soap_version'   => SOAP_1_2,'keep_alive'=>false,'exceptions'=>0));
                    $result = $soap_client->getCitizenDetails(array('cid'=>$cid));
                    if(is_soap_fault($result)){
                        echo "DCRC webservice down";
                    }else{
                        echo "<pre>";dd($result);
                    }
                }
            }else{
                try{
                    $soap_client = new SoapClient($soapURL,array('stream_context'=>$context,'soap_version'   => SOAP_1_2,'keep_alive'=>false,'exceptions'=>0));
                    $result = $soap_client->getCitizenDetails(array('cid'=>$cid));
                    if(is_soap_fault($result)){
                        echo "DCRC webservice down";
                    }else{
                        echo "<pre>";dd($result);
                    }
                }catch(Exception $e){
                    dd($e->getMessage());
                }
            }



        }
    }
    public function getVehCheck($reg,$type){
        $array = array(
            "ssl" => array(
                'ciphers'=>'RC4-SHA',"verify_peer" => false,
                "verify_peer_name" => false
            )
        );
        $context = stream_context_create($array);
        $soapURL = "http://localhost:8080/RSTAWebService/services/RSTADetails?wsdl" ; //RCSC


        $soap_client = new SoapClient($soapURL,array('stream_context'=>$context,'soap_version' => SOAP_1_2));

        $result = $soap_client->getRSTADetails(array('RegistrationNo'=>$reg,'VehicleType'=>$type));
        $data = $result->getRSTADetailsReturn;
        echo "<pre>";dd($data);
    }
}