<?php

class ReportListOfHrSpecializedfirm  extends ReportController
{
    public function getIndex(){
        $parametersForPrint = array();
        $specializedfirmListAll=SpecializedfirmFinalModel::SpecializedtradeHardListAll()->get(array('Id'));
       
        $designationId = CmnListItemModel::Designation()->orderBy('Name')->get(array('Id','Name'));
        $qualificationId = cmnlistitemmodel::Qualification()->orderBy('Name')->get(array('Id','Name'));
        $cdbNo = Input::get('SPNo');
        $individualRegNo= Input::get('IndividualRegNo');
        $designations = Input::get('CmnDesignationId');
        $qualifications = Input::get('CmnQualificationId');
      
        $limit=Input::get('Limit');

 $fromDate = Input::get('FromDate');
        $toDate = Input::get('ToDate');
        $query = "SELECT Name,CIDNo,Gender, IndividualRegNo, JoiningDate,Country,Designation,Qualification , SPNo, Firm FROM listofhrwithspecializedfirm where 1";

        $parameters = array();
        if((bool)$cdbNo){
            $query.=" and SPNo = ?";
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
            $query.=" order by SPNo";
           
        }
 
        /*PAGINATION*/
        $pageNo = Input::has('page')?Input::get('page'):1;
        $pagination = $this->pagination($query,$parameters,10,$pageNo);
        $limitOffsetAppend = $pagination['LimitAppend'];
        $noOfPages = $pagination['NoOfPages'];
        $start = $pagination['Start'];
        /*END PAGINATION*/
        $specializedfirmLists=DB::select($query.$limitOffsetAppend,$parameters);
        if(Input::has('export')){
            $export = Input::get('export');
            if($export == 'excel'){
                Excel::create('List of Hr Registered with Specialized Firm', function($excel) use ($specializedfirmLists,$parametersForPrint) {
                    $excel->sheet('Sheet 1', function($sheet) use ($specializedfirmLists,$parametersForPrint) {
                        $sheet->setOrientation('landscape');
                        $sheet->setFitToPage(1);
                        $sheet->setPaperSize(1);
                        $sheet->loadView('reportexcel.listofhrregisteredwithspecializedfirm')
                            ->with('parametersForPrint',$parametersForPrint)
                            ->with('specializedfirmLists',$specializedfirmLists);

                    });

                })->export('xlsx');
            }
        }
        return View::make('report.listofhrregisteredwithspecializedfirm')
            ->with('start',$start)
            ->with('noOfPages',$noOfPages)
            ->with('parametersForPrint',$parametersForPrint)
            ->with('specializedfirmListAll',$specializedfirmListAll)
        
            ->with('specializedfirmLists',$specializedfirmLists)
            ->with('designationId',$designationId)
            ->with('qualificationId',$qualificationId);
    }
}