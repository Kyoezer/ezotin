<?php

class ReportListOfEquipmentConsultant extends ReportController
{
    public function getIndex(){
        $parametersForPrint = array();
        $consultantListAll=ConsultantFinalModel::consultantHardListAll()->get(array('Id'));
        $consultantId=Input::get('ConsultantId');
        $consulId=Input::get('ConsulId');

        $equipmentId = CmnEquipmentModel::Equipment()->orderBy('Name')->get(array('Id','Name'));
        $cdbNo = Input::get('CDBNo');
        $firm = Input::get('Firm');
        $consultantId=Input::get('ConsultantId');
        $consulId=Input::get('ConsulId');
        $contractorLists = array();
        $limit=Input::get('Limit');
        $equipments = Input::get('CmnEquipmentId');

        $query = "SELECT Firm,CDBNo,EquimentName,RegistrationNo FROM listofequipmentwithconsultant where 1";

        $parameters = array();
        if((bool)$cdbNo){
            $query.=" and CDBNo like '%$cdbNo%'";
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
        if((bool)$consultantId){
            $parametersForPrint['Consultant'] = $consultantId;
            $consultantId="%$consultantId%";
            $query.=" and Firm like ?";
            array_push($parameters,$consultantId);
        }
        if((bool)$consulId){
            $query.=" and RegistrationNo like '%$consulId%'";
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
                Excel::create('List of Equipment Registered with Consultant', function($excel) use ($consultantLists,$parametersForPrint) {
                    $excel->sheet('Sheet 1', function($sheet) use ($consultantLists,$parametersForPrint) {
                        $sheet->setOrientation('landscape');
                        $sheet->setFitToPage(1);
                        $sheet->setPaperSize(1);
                        $sheet->loadView('reportexcel.listofequipmentregisteredwithconsultant')
                            ->with('parametersForPrint',$parametersForPrint)
                            ->with('consultantLists',$consultantLists);

                    });

                })->export('xlsx');
            }
        }
        return View::make('report.listofequipmentregisteredwithconsultant')
            ->with('start',$start)
            ->with('noOfPages',$noOfPages)
            ->with('parametersForPrint',$parametersForPrint)
            ->with('consultantListAll',$consultantListAll)
            ->with('equipmentId',$equipmentId)
            ->with('consultantId',$consultantId)
            ->with('consulId',$consulId)
            ->with('consultantLists',$consultantLists);
         
    }
}