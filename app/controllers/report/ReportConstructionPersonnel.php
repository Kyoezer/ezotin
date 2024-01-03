<?php

/**
 * Created by PhpStorm.
 * User: swm
 * Date: 7/23/2016
 * Time: 11:32 AM
 */
class ReportConstructionPersonnel extends ReportController
{
    public function getIndex(){
        $qualification = Input::get('QualificationId');
        $sex = Input::get('Sex');

        $extraWhere = "";
        if((bool)$qualification){
            $checkParams = checkIfUUID(array($qualification));
            if($checkParams == 0){
                return Redirect::back()->with('customerrormessage','<strong>ERROR!</strong> Misuse detected. Your action will be stored in a log.');
            }
            $extraWhere .= " and A.CmnQualificationId = '$qualification'";
        }
        if((bool)$sex){
            if(($sex == "F") || ($sex == "M")){
                $extraWhere .= " and A.Sex = '$sex'";
            }else{
                return Redirect::back()->with('customerrormessage','<strong>ERROR!</strong> Misuse detected. Your action will be stored in a log.');
            }
        }
        $qualifications = CmnListItemModel::qualification()->get(array('Id','Name'));
        $query = "select T1.Name as Designation, (select count(*) from crpcontractorhumanresourcefinal A where A.CmnDesignationId = T1.Id$extraWhere and coalesce(A.IsPartnerOrOwner,0)=0) as ContractorNumber, (select count(*) from crpconsultanthumanresourcefinal A where A.CmnDesignationId = T1.Id$extraWhere and coalesce(A.IsPartnerOrOwner,0)=0) as ConsultantNumber from cmnlistitem T1 where T1.CmnListId = ? having (ContractorNumber > 0 or ConsultantNumber > 0) order by T1.Name";
        $reportData = DB::select($query,array(CONST_CMN_REFERENCE_DESIGNATION));
        if(Input::get('export') == 'excel'){
            Excel::create("Construction Industry Personnel", function($excel) use ($reportData) {
                $excel->sheet('Sheet 1', function($sheet) use ($reportData) {
                    $sheet->setOrientation('landscape');
                    $sheet->setFitToPage(1);
                    $sheet->loadView('reportexcel.constructionpersonnel')
                        ->with('reportData',$reportData);
                });
            })->export('xlsx');
        }
        return View::make('report.constructionpersonnel')
                    ->with('qualifications',$qualifications)
                    ->with('reportData',$reportData);
    }
    public function getQualificationGenderWise(){
        $sex = Input::get('Sex');

        $extraWhere = "";
        if((bool)$sex){
            if(($sex == "F") || ($sex == "M")){
                $extraWhere .= " and A.Sex = '$sex'";
            }else{
                return Redirect::back()->with('customerrormessage','<strong>ERROR!</strong> Misuse detected. Your action will be stored in a log.');
            }
        }
        $query = "select T1.Name as Qualification, (select count(*) from crpcontractorhumanresourcefinal A where A.CmnQualificationId = T1.Id$extraWhere and coalesce(A.IsPartnerOrOwner,0)<>1) as ContractorNumber, (select count(*) from crpconsultanthumanresourcefinal A where A.CmnQualificationId = T1.Id$extraWhere and coalesce(A.IsPartnerOrOwner,0)<>1) as ConsultantNumber from cmnlistitem T1 where T1.CmnListId = ? having (ContractorNumber > 0 or ConsultantNumber > 0) order by T1.Name";
        $reportData = DB::select($query,array(CONST_CMN_REFERENCE_QUALIFICATION));
        if(Input::get('export') == 'excel'){
            Excel::create("Construction Industry Personnel", function($excel) use ($reportData) {
                $excel->sheet('Sheet 1', function($sheet) use ($reportData) {
                    $sheet->setOrientation('landscape');
                    $sheet->setFitToPage(1);
                    $sheet->loadView('reportexcel.constructionpersonnel')
                        ->with('reportData',$reportData);
                });
            })->export('xlsx');
        }
        return View::make('report.constructionpersonnelqualificationgender')
            ->with('reportData',$reportData);
    }
}