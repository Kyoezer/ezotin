<?php

class ReportListOfHrContractor extends ReportController
{
    public function getIndex(){
        $parametersForPrint = array();
        $contractorListAll=ContractorFinalModel::contractorHardListAll()->get(array('Id'));       
        $designationId = CmnListItemModel::Designation()->orderBy('Name')->get(array('Id','Name'));
        $qualificationId = CmnListItemModel::Qualification()->orderBy('Name')->get(array('Id','Name'));
        $cdbNo = Input::get('CDBNo');
        $individualRegNo = Input::get('IndividualRegNo');
        $contractorId=Input::get('ContractorId');
        $designations = Input::get('CmnDesignationId');
        $qualifications = Input::get('CmnQualificationId');
        $contractorLists = array();
        $limit=Input::get('Limit');
        $fromDate = Input::get('FromDate');
        $toDate = Input::get('ToDate');
        $query = "SELECT NAME,CIDNo,Designation,Qualification,IndividualRegNo, Country,Gender,JoiningDate, CDBNo, Firm FROM listofhrwithcontractor WHERE 1";

        $parameters = array();
        if((bool)$cdbNo){
            $query.=" and CDBNo = ?";
            array_push($parameters,$cdbNo);
        }
        if((bool)$individualRegNo){
            $query.=" and IndividualRegNo = ?";
            array_push($parameters,$individualRegNo);
        }
  
        if((bool)$designations){
            $parametersForPrint['Designation'] = $designations;
            $query.=" and Designation=?";
            array_push($parameters,$designations);
        }
        if((bool)$qualifications){
            $parametersForPrint['Qualification'] = $qualifications;
            $query.=" and Qualification=?";
            array_push($parameters,$qualifications);
        }
 
     if((bool)$fromDate){
            $parametersForPrint['From Date'] = $fromDate;
            $query.=" and JoiningDate>= ?";
            array_push($parameters,date_format(date_create($fromDate),'Y-m-d'));
        }
        if((bool)$toDate){
            $parametersForPrint['To Date'] = $toDate;
            $query.=" and JoiningDate<= ?";
            array_push($parameters,date_format(date_create($toDate),'Y-m-d'));
        }

     else{
            $query.=" order by CDBNo";
           
        }
        /*PAGINATION*/
        $pageNo = Input::has('page')?Input::get('page'):1;
        $pagination = $this->pagination($query,$parameters,10,$pageNo);
        $limitOffsetAppend = $pagination['LimitAppend'];
        $noOfPages = $pagination['NoOfPages'];
        $start = $pagination['Start'];
        /*END PAGINATION*/
        $contractorLists=DB::select($query.$limitOffsetAppend,$parameters);
        if(Input::has('export')){
            $export = Input::get('export');
            if($export == 'excel'){
                Excel::create('List of Hr Registered with Contractors', function($excel) use ($contractorLists,$parametersForPrint) {
                    $excel->sheet('Sheet 1', function($sheet) use ($contractorLists,$parametersForPrint) {
                        $sheet->setOrientation('landscape');
                        $sheet->setFitToPage(1);
                        $sheet->setPaperSize(1);
                        $sheet->loadView('reportexcel.listofhrregisteredwithcontractor')
                            ->with('parametersForPrint',$parametersForPrint)
                            ->with('contractorLists',$contractorLists);

                    });

                })->export('xlsx');
            }
        }
        return View::make('report.listofhrregisteredwithcontractor')
            ->with('start',$start)
            ->with('noOfPages',$noOfPages)
            ->with('parametersForPrint',$parametersForPrint)
            ->with('contractorListAll',$contractorListAll)
            ->with('contractorId',$contractorId)
            ->with('contractorLists',$contractorLists)
            ->with('designationId',$designationId)
            ->with('qualificationId',$qualificationId);
    }
}