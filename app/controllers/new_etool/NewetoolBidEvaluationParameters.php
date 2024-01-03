<?php

class NewetoolBidEvaluationParameters extends EtoolController{
    public function getIndex(){
        $organizationalStrength = DB::table('etlbidevalutionparameters')->where('ReferenceNo',1001)->orderBy('Points','DESC')->get(array('Id','Name','Points'));
        $employmentOfVTI = DB::table('etlbidevalutionparameters')->where('ReferenceNo',1002)->orderBy('Points','DESC')->get(array('Id','Name','Points'));
        $commitmentOfInternship = DB::table('etlbidevalutionparameters')->where('ReferenceNo',1003)->orderBy('Points','DESC')->get(array('Id','Name','Points'));
        return View::make('new_etool.setbidevaluationparameters')
                ->with('organizationalStrength',$organizationalStrength)
                ->with('employmentOfVTI',$employmentOfVTI)
                ->with('commitmentOfInternship',$commitmentOfInternship);
    }
    public function postSave(){
        $inputs = Input::all();
        $inputArray = array();
        DB::beginTransaction();
        foreach($inputs as $key=>$value){
            if(gettype($value) == 'array'){
                $model = $key;
                foreach($value as $key2=>$value2){
                    foreach($value2 as $key3=>$value3){
                        $inputArray[$key3] = $value3;
                    }
                    try{
                        $object = EtlBidEvaluationParameters::find($inputArray['Id']);
                        $object->fill($inputArray);
                        $object->update();
                    }catch(Exception $e){
                        DB::rollback();
                        throw $e;
                    }
                    $inputArray = array();
                }
            }
        }
        DB::commit();
        return Redirect::to('newEtl/bidevaluationparameters')->with('savedsuccessmessage','Record has been updated');
    }
}