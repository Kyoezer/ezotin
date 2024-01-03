<?php

class NewetoolSetQualifyingScore extends EtoolController{
    public function getIndex(){
        $savedScore = DB::table('etlqualifyingscore')->get(array('Id','QualifyingScore'));
        return View::make('new_etool.setqualifyingscore')
            ->with('savedScore',$savedScore);
    }
    public function postSave(){
        $inputs = Input::all();
        $object = new EtoolQualifyingScoreModel();
        if(!$object->validate($inputs)){
            return Redirect::to('newEtl/qualifyingscore')->withErrors($object->errors());
        }
        try{
            $instance = EtoolQualifyingScoreModel::find($inputs['Id']);
            $instance->fill($inputs);
            $instance->update();
            $auditTrailActionMessage="Changed Qualifying Score for Evaluation to".$inputs['QualifyingScore'];
            $this->auditTrailEtoolCinet(NULL,NULL,$auditTrailActionMessage);
            return Redirect::to('newEtl/qualifyingscore')->with('savedsuccessmessage','Record has been updated');
        }catch (Exception $e){
            throw $e;
        }
    }
}