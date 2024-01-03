<?php

class ReportListOfEquipmentCertifiedBuilder  extends ReportController
{
    public function getIndex(){
        $parametersForPrint = array();
        $certifiedbuilderListAll=CertifiedbuilderFinalModel::certifiedBuilderHardListAll()->get(array('Id'));
        $certifiedbuilderId=Input::get('CertifiedBuilderId');
        $firmId=Input::get('CertifiedBuilderlId');

        $equipmentId = CmnEquipmentModel::Equipment()->orderBy('Name')->get(array('Id','Name'));
        $cdbNo = Input::get('CDBNo');
        $firm = Input::get('Firm');
        $certifiedbuilderId=Input::get('CertifiedBuilderId');
        $firmId=Input::get('FirmId');
        $contractorLists = array();
        $limit=Input::get('Limit');
        $equipments = Input::get('CmnEquipmentId');

        $query = "SELECT Firm,CDBNo,EquimentName,RegistrationNo FROM listofequipmentwithcertifiedbuilder where 1";

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
        if((bool)$certifiedbuilderId){
            $parametersForPrint['Certified Builder'] = $certifiedbuilderId;
            $certifiedbuilderId="%$certifiedbuilderId%";
            $query.=" and Firm like ?";
            array_push($parameters,$certifiedbuilderId);
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
        $certifiedbuilderLists=DB::select($query.$limitOffsetAppend,$parameters);
        if(Input::has('export')){
            $export = Input::get('export');
            if($export == 'excel'){
                Excel::create('List of Equipment Registered with Certified Builder', function($excel) use ($certifiedbuilderLists,$parametersForPrint) {
                    $excel->sheet('Sheet 1', function($sheet) use ($certifiedbuilderLists,$parametersForPrint) {
                        $sheet->setOrientation('landscape');
                        $sheet->setFitToPage(1);
                        $sheet->setPaperSize(1);
                        $sheet->loadView('reportexcel.listofequipmentregisteredwithcertifiedbuilder')
                            ->with('parametersForPrint',$parametersForPrint)
                            ->with('certifiedbuilderLists',$certifiedbuilderLists);

                    });

                })->export('xlsx');
            }
        }
        return View::make('report.listofequipmentregisteredwithcertifiedbuilder')
            ->with('start',$start)
            ->with('noOfPages',$noOfPages)
            ->with('parametersForPrint',$parametersForPrint)
            ->with('certifiedbuilderListAll',$certifiedbuilderListAll)
            ->with('equipmentId',$equipmentId)
            ->with('certifiedbuilderId',$certifiedbuilderId)
            ->with('firmId',$firmId)
            ->with('certifiedbuilderLists',$certifiedbuilderLists);
         
    }
}