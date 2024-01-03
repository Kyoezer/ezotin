<?php

class ReportListOfHrConsultant extends ReportController
{
    public function getIndex(){
        $parametersForPrint = array();
        $consultantListAll=ConsultantFinalModel::consultantHardListAll()->get(array('Id'));
       
        $designationId = CmnListItemModel::Designation()->orderBy('Name')->get(array('Id','Name'));
        $qualificationId = CmnListItemModel::Qualification()->orderBy('Name')->get(array('Id','Name'));
        $cdbNo = Input::get('CDBNo');
    
        $designations = Input::get('CmnDesignationId');
        $qualifications = Input::get('CmnQualificationId');
        $contractorLists = array();
        $limit=Input::get('Limit');
  $fromDate = Input::get('FromDate');
        $toDate = Input::get('ToDate');

        $query = "SELECT Name,CIDNo,Gender,Country,JoiningDate,Designation,Qualification , CDBNo, Firm FROM listofhrwithconsultant where 1";

        $parameters = array();
        if((bool)$cdbNo){
            $query.=" and CDBNo = ?";
            array_push($parameters,$cdbNo);
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
        $consultantLists=DB::select($query.$limitOffsetAppend,$parameters);
        if(Input::has('export')){
            $export = Input::get('export');
            if($export == 'excel'){
                Excel::create('List of Hr Registered with Consultant', function($excel) use ($consultantLists,$parametersForPrint) {
                    $excel->sheet('Sheet 1', function($sheet) use ($consultantLists,$parametersForPrint) {
                        $sheet->setOrientation('landscape');
                        $sheet->setFitToPage(1);
                        $sheet->setPaperSize(1);
                        $sheet->loadView('reportexcel.listofhrregisteredwithconsultant')
                            ->with('parametersForPrint',$parametersForPrint)
                            ->with('consultantLists',$consultantLists);

                    });

                })->export('xlsx');
            }
        }
        return View::make('report.listofhrregisteredwithconsultant')
            ->with('start',$start)
            ->with('noOfPages',$noOfPages)
            ->with('parametersForPrint',$parametersForPrint)
            ->with('consultantListAll',$consultantListAll)
         
            ->with('consultantLists',$consultantLists)
            ->with('designationId',$designationId)
            ->with('qualificationId',$qualificationId);
    }
}