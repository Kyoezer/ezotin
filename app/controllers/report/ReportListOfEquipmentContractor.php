<?php

class ReportListOfEquipmentContractor  extends ReportController
{
    public function getIndex(){
        $parametersForPrint = array();
        $contractorListAll=ContractorFinalModel::contractorHardListAll()->get(array('Id'));
        $contractorId=Input::get('ContractorId');
        $contracId=Input::get('ContracId');

        $equipmentId = CmnEquipmentModel::Equipment()->orderBy('Name')->get(array('Id','Name'));
        $cdbNo = Input::get('CDBNo');
        $firm = Input::get('Firm');
        $contractorId=Input::get('ContractorId');
        $contracId=Input::get('ContracId');
        $contractorLists = array();
        $limit=Input::get('Limit');
        $equipments = Input::get('CmnEquipmentId');

        $query = "SELECT Firm,CDBNo,EquimentName,RegistrationNo FROM listofequipmentwithcontractor where 1";

      
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
        if((bool)$contractorId){
            $parametersForPrint['Contractor'] = $contractorId;
            $contractorId="%$contractorId%";
            $query.=" and Firm like ?";
            array_push($parameters,$contractorId);
        }
     
        if((bool)$contracId){
            $query.=" and RegistrationNo like '%$contracId%'";
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
                Excel::create('List of Equipment Registered with Contractor', function($excel) use ($contractorLists,$parametersForPrint) {
                    $excel->sheet('Sheet 1', function($sheet) use ($contractorLists,$parametersForPrint) {
                        $sheet->setOrientation('landscape');
                        $sheet->setFitToPage(1);
                        $sheet->setPaperSize(1);
                        $sheet->loadView('reportexcel.listofequipmentregisteredwithcontractor')
                            ->with('parametersForPrint',$parametersForPrint)
                            ->with('contractorLists',$contractorLists);

                    });

                })->export('xlsx');
            }
        }
        return View::make('report.listofequipmentregisteredwithcontractor')
            ->with('start',$start)
            ->with('noOfPages',$noOfPages)
            ->with('parametersForPrint',$parametersForPrint)
            ->with('contractorListAll',$contractorListAll)
            ->with('equipmentId',$equipmentId)
            ->with('contractorId',$contractorId)
            ->with('contracId',$contracId)
            ->with('contractorLists',$contractorLists);
         
    }
}