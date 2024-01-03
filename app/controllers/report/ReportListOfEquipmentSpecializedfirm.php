<?php

class ReportListOfEquipmentSpecializedfirm  extends ReportController
{
    public function getIndex(){
        $parametersForPrint = array();
        $specializedfirmListAll=SpecializedfirmFinalModel::SpecializedTradeHardListAll()->get(array('Id'));
        $specializedfirmId=Input::get('ConsultantId');
        $firmId=Input::get('ConsulId');

        $equipmentId = CmnEquipmentModel::Equipment()->orderBy('Name')->get(array('Id','Name'));
        $cdbNo = Input::get('SPNo');
        $firm = Input::get('Firm');
        $specializedfirmId=Input::get('SpecializedfirmId');
        $firmId=Input::get('FirmId');
        $contractorLists = array();
        $limit=Input::get('Limit');
        $equipments = Input::get('CmnEquipmentId');

        $query = "SELECT Firm,SPNo,EquimentName,RegistrationNo FROM listofequipmentwithspecializedfirm where 1";

        $parameters = array();
        if((bool)$cdbNo){
            $query.=" and SPNo like '%$cdbNo%'";
        }
        if((bool)$equipments){
            $parametersForPrint['EquimentName'] = $equipments;
            $query.=" and EquimentName=?";
            array_push($parameters,$equipments);
        }
 
        $parameters = array();
        if((bool)$firm){
            $query.=" and Firm = ?";
            array_push($parameters,$Firm);
        }
        if((bool)$specializedfirmId){
            $parametersForPrint['Specialized Firm'] = $specializedfirmId;
            $specializedfirmId="%$specializedfirmId%";
            $query.=" and Firm like ?";
            array_push($parameters,$specializedfirmId);
        }
        if((bool)$firmId){
            $query.=" and RegistrationNo like '%$firmId%'";
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
                Excel::create('List of Equipment Registered with Specialized Firm', function($excel) use ($specializedfirmLists,$parametersForPrint) {
                    $excel->sheet('Sheet 1', function($sheet) use ($specializedfirmLists,$parametersForPrint) {
                        $sheet->setOrientation('landscape');
                        $sheet->setFitToPage(1);
                        $sheet->setPaperSize(1);
                        $sheet->loadView('reportexcel.listofequipmentregisteredwithconsultant')
                            ->with('parametersForPrint',$parametersForPrint)
                            ->with('specializedfirmLists',$specializedfirmLists);

                    });

                })->export('xlsx');
            }
        }
        return View::make('report.listofequipmentregisteredwithspecializedfirm')
            ->with('start',$start)
            ->with('noOfPages',$noOfPages)
            ->with('parametersForPrint',$parametersForPrint)
            ->with('specializedfirmListAll',$specializedfirmListAll)
            ->with('equipmentId',$equipmentId)
            ->with('specializedfirmId',$specializedfirmId)
            ->with('firmId',$firmId)
            ->with('specializedfirmLists',$specializedfirmLists);
         
    }
}