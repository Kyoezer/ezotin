<?php

class ReportEngagedHrEtool extends ReportController{
    public function getIndex(){

        $designationId = CmnListItemModel::Designation()->orderBy('Name')->get(array('Id','Name'));
        $cdbNo = Input::get('c.CDBNo');
       $cidNo = Input::get('c.CIDNo');
    $designations = Input::get('CmnDesignationId');

      
        $query = "SELECT c.CIDNo AS cidNo, c.CDBNo AS CDBNo, c.HRName AS hrName, c.Name AS Designation, t6.Name AS procuringAgency, c.WorkId AS WorkId FROM etltrackhumanresource c LEFT JOIN etltender t5 ON t5.WorkId = c.WorkId LEFT JOIN viewcontractorstrackrecords t9 ON t9.CDBNo = c.CDBNo LEFT JOIN cmnprocuringagency t6 ON t6.Id = t5.CmnProcuringAgencyId WHERE c.CIDNo IS NOT NULL AND c.HRName IS NOT NULL AND t9.`WorkStatus` = 'Awarded'";
        $parameters = array();
       
        if((bool)$cdbNo){
            $query.=" and c.CDBNo like '%$cdbNo%'";
        }
       
     if((bool)$cidNo ){
            $query.=" and c.CIDNo like '%$cidNo%'";
        }
      
 
        $query.=" order by c.CDBNo";
        /*PAGINATION*/
        $pageNo = Input::has('page')?Input::get('page'):1;
        $pagination = $this->pagination($query,$parameters,16,$pageNo);
        $limitOffsetAppend = $pagination['LimitAppend'];
        $noOfPages = $pagination['NoOfPages'];
        $start = $pagination['Start'];
        /*END PAGINATION*/
        $reportData = DB::select("$query$limitOffsetAppend",$parameters);
        if(Input::has('export')) {
            $export = Input::get('export');
            if ($export == 'excel') {
                Excel::create('Engaged Hr', function ($excel) use ($reportData) {
                    $excel->sheet('Sheet 1', function ($sheet) use ($reportData) {
                        $sheet->setOrientation('landscape');
                        $sheet->setFitToPage(1);
                        $sheet->loadView('reportexcel.listofengagedhretool')
                           
                            ->with('reportData', $reportData);
                           

                    });

                })->export('xlsx');
            }
        }
        return View::make('report.listofengagedhretool')
            ->with('start',$start)
            ->with('noOfPages',$noOfPages)
              ->with('designationId',$designationId)
     
            ->with('reportData',$reportData);
    }
}

