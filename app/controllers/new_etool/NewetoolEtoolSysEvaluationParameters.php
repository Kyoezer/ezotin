<?php

class NewetoolEtoolSysEvaluationParameters extends EtoolController{
    public function getIndex(){
        $pointTypes = DB::table('etlpointtype')->orderBy('ReferenceNo')->get(array('Id','Name'));
        return View::make('new_etool.evaluationparameters')
                    ->with('pointTypes',$pointTypes);
    }
    public function getParameter($id){
        $pointType = DB::table('etlpointtype')->where('Id',$id)->get(array('Id','Name','MaxPoints','MinPoints'));
        $pointDefinitions = DB::table('etlpointdefinition')->where('EtlPointTypeId',$id)->orderBy('UpperLimit')->get(array('Id','LowerLimit','UpperLimit','Points'));
        if(!$pointDefinitions){
            $pointDefinitions = array(new EtlPointDefinitionModel());
        }
        return View::make('new_etool.pointdefinition')
                    ->with('pointType',$pointType)
                    ->with('pointDefinitions',$pointDefinitions);
    }
    public function preferenceScore()
    {
        
        $parameters = DB::table('etlbidevalutionparameters')->where('ReferenceNo','1004')->get(array('Id','Name','Points'));
        
        return View::make('new_etool.preferenceScore')
                    ->with('parameters',$parameters);

    }


    public function postSave(){
        $inputs = Input::all();
        $id = $inputs["Id"];
        $maxPoints = $inputs['MaxPoints'];
        $minPoints = $inputs['MinPoints'];

        $childTableInputs = array();
        DB::beginTransaction();
        DB::table('etlpointtype')->where('Id',$id)->update(array('MaxPoints'=>$maxPoints,'MinPoints'=>$minPoints));
        DB::table('etlpointdefinition')->where('EtlPointTypeId',$inputs['Id'])->delete();
        foreach($inputs['ChildTable'] as $key=>$value){
            $childTableInputs['Id'] = $this->UUID();
            $childTableInputs['EtlPointTypeId'] = $id;
            foreach($value as $x=>$y):
                $childTableInputs[$x] = $y;
            endforeach;
            //INSERT
            try{
                EtlPointDefinitionModel::create($childTableInputs);
            }catch (Exception $e){
                DB::rollBack();
                throw $e;
            }
            $childTableInputs = array();
        }
        DB::commit();
        return Redirect::to('etoolsysadm/editevaluationparameters/'.$id)->with('savedsuccessmessage','Points updated successfully');
    }

    public function postSaveBhutaneseEmploymentPreference(){
        $inputs = Input::all(); 
        $childTableInputs = array();
        DB::beginTransaction();
        foreach($inputs['ChildTable'] as $key=>$value){ 
            foreach($value as $x=>$y):
                $childTableInputs[$x] = $y;
                echo($y.'/');
            endforeach;
            try{

                $count = DB::table('etlbidevalutionparameters')->where('Id',123)->count();
                //$totalcount = $count.'/'.$totalcount;
               
            }catch (Exception $e){
                DB::rollBack();
                throw $e;
            }
            $childTableInputs = array();
        }
       // return $totalcount;
        DB::commit();
        return Redirect::to('etoolsysadm/editevaluationparameters/'.$id)->with('savedsuccessmessage','Points updated successfully');
    }
}