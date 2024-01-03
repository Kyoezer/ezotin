<?php

/**
 * Created by PhpStorm.
 * User: SWM
 * Date: 2/25/2017
 * Time: 12:00 PM
 */
class ContractorTraining extends CrpsController
{
    public function getIndex(){
        $trainings = array();
        $parameters = array();

        $trainingType = Input::get('CmnTrainingTypeId');
        $trainingModule = Input::get('CmnTrainingModuleId');
        $year = Input::get('Year');

        $trainingTypes = CmnListItemModel::trainingType()->get(array('Id','Name','ReferenceNo'));
        $trainingModules = CmnListItemModel::trainingModule()->get(array('Id','Name'));
        $trainingYears = DB::table('crpcontractortraining')
                            ->select(DB::raw("DATE_FORMAT(TrainingFromDate, '%Y') as Year"))
                            ->orderBy('Year')->distinct()
                            ->lists('Year');
        $query = "select T1.Id,A.Name as Training, B.Name as Module, (select count(Id) from crpcontractortrainingdetail Where CrpContractorTrainingId = T1.Id) as ParticipantCount, DATE_FORMAT(T1.TrainingFromDate,'%d-%m-%Y') as TrainingFromDate, DATE_FORMAT(T1.TrainingToDate,'%d-%m-%Y') as TrainingToDate from crpcontractortraining T1 join cmnlistitem A on T1.CmnTrainingTypeId = A.Id left join cmnlistitem B on B.Id = T1.CmnTrainingModuleId where 1";
        if((bool)$trainingType || (bool)$trainingModule || (bool)$year){
            if((bool)$trainingType){
                $query.=" and T1.CmnTrainingTypeId  = ?";
                array_push($parameters,$trainingType);
            }
            if((bool)$trainingModule){
                $query.=" and T1.CmnTrainingModuleId = ?";
                array_push($parameters,$trainingModule);
            }
            if((bool)$year){
                $query.=" and (T1.TrainingFromDate >= ? and T1.TrainingFromDate <= ?)";
                $fromDate = substr($year,0,10);
                $toDate = substr($year,14,10);
                array_push($parameters,"$fromDate");
                array_push($parameters,"$toDate");
            }
        }

        /*PAGINATION*/
        $pageNo = Input::has('page')?Input::get('page'):1;
        $pagination = $this->pagination($query,$parameters,10,$pageNo);
        $limitOffsetAppend = $pagination['LimitAppend'];
        $noOfPages = $pagination['NoOfPages'];
        $start = $pagination['Start'];
        /*END PAGINATION*/

        $trainings = DB::select("$query order by T1.TrainingFromDate desc$limitOffsetAppend",$parameters);
        return View::make('crps.contractortrainingindex')
                    ->with('start',$start)
                    ->with('noOfPages',$noOfPages)
                    ->with('trainings',$trainings)
                    ->with('trainingTypes',$trainingTypes)
                    ->with('trainingYears',$trainingYears)
                    ->with('trainingModules',$trainingModules);
    }
    public function addNew(){
        $trainingTypes = CmnListItemModel::trainingType()->get(array('Id','Name','ReferenceNo'));
        $trainingModules = CmnListItemModel::trainingModule()->get(array('Id','Name'));
        return View::make('crps.contractortraining')
                ->with('trainingTypes',$trainingTypes)
                ->with('trainingModules',$trainingModules);
    }
    public function postSave(){
        $tableInputs = Input::except('Excel','_token');
        if(!Input::hasFile('Excel')){
            return Redirect::to('contractor/addtraining')->with('customerrormessage','Please upload an excel file!');
        }
        $hasContractor = false;
        if(isset($tableInputs['CmnTrainingModuleId'])){
            $hasContractor = true;
            $arrayNames = array("CrpContractorFinalId","Participant","CIDNo","Designation","Gender","Qualification","ContactNo");
        }else{
            $arrayNames = array("Participant","CIDNo","Gender","Qualification","ContactNo");
        }

        $tableInputs['TrainingFromDate'] = $this->convertDate($tableInputs['TrainingFromDate']);
        $tableInputs['TrainingToDate'] = $this->convertDate($tableInputs['TrainingToDate']);
        DB::beginTransaction();
        $tableInputs['Id'] = $mainTableId = $this->UUID();
        TrainingModel::create($tableInputs);
        try{
            Excel::load(Input::file('Excel'), function($reader) use($arrayNames,$mainTableId,$hasContractor) {
                $results = $reader->toArray();
                foreach($results as $key=>$value):
                    $count = 0;
                    $postedValue['Id'] = $this->UUID();
                    foreach($value as $x=>$y):
                        if($arrayNames[$count] == 'Gender'){
                            if((strtolower(trim($y)) == 'male') || (strtolower(trim($y)) == 'm')){
                                $y = 'M';
                            }else{
                                $y = 'F';
                            }
                        }
                        if($arrayNames[$count] == "CrpContractorFinalId"){
                            $crpContractorFinalId = DB::table('crpcontractorfinal')->where(DB::raw("TRIM(CDBNo)"),trim($y))->pluck('Id');
                            if((bool)$crpContractorFinalId){
                                $y = $crpContractorFinalId;
                            }else{
                                DB::rollBack();
                                return Redirect::to('contractor/addtraining')->with('customerrormessage',"Contractor $y does not exist");
                            }
                        }
                        $postedValue[$arrayNames[$count]] = ($y==NULL)?$y:trim($y);
                        $count++;
                    endforeach;
                    if(!$hasContractor){
                        $postedValue['CrpContractorFinalId'] = NULL;
                        $postedValue['Designation'] = NULL;
                    }
                    $postedValue['CrpContractorTrainingId'] = $mainTableId;
                    TrainingDetailModel::create($postedValue);
                endforeach;
            });
        }catch(Exception $e){
            DB::rollBack();
            return Redirect::to('contractor/addtraining')->with('customerrormessage',$e->getMessage());
        }
        DB::commit();
        return Redirect::to('contractor/addtraining')->with("savedsuccessmessage","Training list has been updated!");
    }
    public function getDelete(){
        $trainings = DB::table('crpcontractortraining as T1')
                                ->join('cmnlistitem as T2','T1.CmnTrainingTypeId','=','T2.Id')
                                ->leftJoin('cmnlistitem as T3','T1.CmnTrainingModuleId','=','T3.Id')
                                ->orderBy('T1.TrainingFromDate')->get(array('T1.Id','T2.Name as Training','T3.Name as Module',DB::raw("DATE_FORMAT(TrainingFromDate,'%d-%m-%Y') as TrainingFromDate"),DB::raw("DATE_FORMAT(TrainingToDate,'%d-%m-%Y') as TrainingToDate")));
        return View::make("crps.deletetraining")
                    ->with('trainings',$trainings);
    }
    public function postDelete(){
        $trainingId = Input::get('CrpContractorTrainingId');
        DB::beginTransaction();
        try{
            DB::table('crpcontractortraining')->where('Id',$trainingId)->delete();
        }catch(Exception $e){
            DB::rollBack();
            return Redirect::to('contractor/deletetraining')->with('customerrormessage',$e->getMessage());
        }
        DB::commit();
        return Redirect::to('contractor/deletetraining')->with('savedsuccessmessage',"<strong>SUCCESS! </strong>Training and participants has been deleted!");
    }
    public function getDetails($id){
        $training = DB::table('crpcontractortraining as T1')
                        ->join('cmnlistitem as T2','T2.Id','=','T1.CmnTrainingTypeId')
                        ->leftJoin('cmnlistitem as T3','T3.Id','=','T1.CmnTrainingModuleId')
                        ->where('T1.Id',$id)
                        ->get(array('T1.Id','T2.ReferenceNo as TrainingReference','T2.Name as Training','T3.name as Module',DB::raw("DATE_FORMAT(TrainingFromDate,'%d-%m-%Y') as TrainingFromDate"),DB::raw("DATE_FORMAT(TrainingToDate,'%d-%m-%Y') as TrainingToDate")));
        if(count($training)==0){
            App::abort(404);
        }
        $trainingDetails = DB::table('crpcontractortrainingdetail as T1')
                                ->leftJoin('crpcontractorfinal as T4','T4.Id','=','T1.CrpContractorFinalId')
                                ->where('T1.CrpContractorTrainingId',$id)
                                ->get(array('T1.Id','T4.CDBNo','T1.Participant','T1.CIDNo','T1.Designation','T1.Gender','T1.Qualification','T1.ContactNo'));
        return View::make('crps.contractortrainingdetail')
                    ->with('training',$training)
                    ->with('trainingDetails',$trainingDetails);
    }
    public function postDeleteParticipant($trainingId,$id){
        DB::beginTransaction();
        try{
            DB::table('crpcontractortrainingdetail')->where('Id',$id)->delete();
        }catch(Exception $e){
            DB::rollBack();
            return Redirect::to('contractor/trainingdetails/'.$trainingId)->with('customerrormessage',$e->getMessage());
        }
        DB::commit();
        return Redirect::to('contractor/trainingdetails/'.$trainingId)->with('savedsuccessmessage',"<strong>SUCCESS! </strong>Participant has been deleted!");
    }
    public function getEditParticipant($trainingType,$id){
        $details = DB::table('crpcontractortrainingdetail as T1')
                        ->leftJoin('crpcontractorfinal as T2','T2.Id','=','T1.CrpContractorFinalId')
                        ->where('T1.Id',$id)
                        ->get(array('T1.Id','T1.CrpContractorTrainingId','T1.CrpContractorFinalId',DB::raw("concat(TRIM(T2.NameOfFirm),' (',T2.CDBNo,')') as Contractor"),'T1.Participant','T1.CIDNo','T1.Designation','T1.Gender','T1.Qualification','T1.ContactNo'));
        if(count($details)==0){
            App::abort(404);
        }
        return View::make('crps.editcontractortrainingdetail')
                    ->with('trainingReference',$trainingType)
                    ->with('details',$details);
    }
    public function saveEditedParticipant(){
        $id = Input::get('Id');
        $redirectUrl = Input::get("RedirectUrl");
        $object = TrainingDetailModel::find($id);
        $object->fill(Input::except('_token','RedirectUrl'));
        $object->update();
        return Redirect::to($redirectUrl)->with('savedsuccessmessage',"<strong>SUCCESS! </strong>Participant details have been updated!");
    }
}