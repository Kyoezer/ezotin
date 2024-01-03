<?php

class ReportEngagedEquipments extends ReportController{
    public function getIndex(){
        $cdbNo = Input::get('CDBNo');
        $regNo = Input::get('RegistrationNo');
        $equipmentId = CmnEquipmentModel::Equipment()->orderBy('Name')->get(array('Id','Name'));

      
        $query = "select distinct * from engageequipmentetool where   1";
        $parameters = array();
        $equipments = Input::get('CmnEquipmentId');
        if((bool)$cdbNo){
            $query.=" and CDBNo like '%$cdbNo%'";
        }
        if((bool)$regNo){
            $query.=" and RegistrationNo like '%$regNo%'";
        }
        if((bool)$equipments){
            $parametersForPrint['T1.Name'] = $equipments;
            $query.=" and T1.Name=?";
            array_push($parameters,$equipments);
        }
    
        $query.=" order by Equipment";
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
                Excel::create('Engaged Equipments', function ($excel) use ($reportData) {
                    $excel->sheet('Sheet 1', function ($sheet) use ($reportData) {
                        $sheet->setOrientation('landscape');
                        $sheet->setFitToPage(1);
                        $sheet->loadView('reportexcel.listofengagedequipments')
                          
                            ->with('reportData', $reportData);
                         

                    });

                })->export('xlsx');
            }
        }
        return View::make('report.listofengagedequipments')
            ->with('start',$start)
            ->with('noOfPages',$noOfPages)
            ->with('equipmentId',$equipmentId)
     
            ->with('reportData',$reportData);
    }
}

