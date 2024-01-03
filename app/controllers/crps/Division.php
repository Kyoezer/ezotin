<?php

/**
 * Created by PhpStorm.
 * User: swm
 * Date: 5/24/2016
 * Time: 8:58 PM
 */
class Division extends CrpsController
{
    public function getIndex($id = NULL){
        $divisions = array(new CmnDivisionModel());
        $parentPAs = DB::table('cmnprocuringagency')
//            ->('CmnProcuringAgencyId')
            ->orderBy('Name')
            ->get(array('Name','Code','Id'));
        if((bool)$id){
            $divisions = DB::table('cmnprocuringagency')->where('Id',$id)->get(array('Id','Code','Name','CmnProcuringAgencyId'));
        }
        $saved = ProcuringAgencyModel::procuringAgency()->get(array('cmnprocuringagency.Id','cmnprocuringagency.Name','cmnprocuringagency.Code','T2.Name as Division'));

        return View::make('crps.cmndivision')
                ->with('saved',$saved)
                ->with('divisions',$divisions)
                ->with('parentPAs',$parentPAs);
    }
    public function postSave(){
        $inputs = Input::all();
        if(empty($inputs['Id'])){
            ProcuringAgencyModel::create($inputs);
            return Redirect::to('master/division')->with('savedsuccessmessage','Division has been added');
        }else{
            $instance = ProcuringAgencyModel::find($inputs['Id']);
            $instance->fill($inputs);
            $instance->update();
            return Redirect::to('master/division')->with('savedsuccessmessage','Division has been updated');
        }
    }
    public function getDelete($id){
        try{
            ProcuringAgencyModel::where('Id',$id)->delete();
            return Redirect::to('master/division')->with('savedsuccessmessage','Division has been deleted');
        }catch(Exception $e){
            return Redirect::to('master/division')->with('customerrormessage','Division is being used somewhere!');
        }
    }
}