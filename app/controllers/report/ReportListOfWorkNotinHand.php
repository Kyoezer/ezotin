<?php

class ReportListOfWorkNotinHand extends ReportController
{
    public function getIndex(){
        $parametersForPrint = array();
        $contractorId=Input::get('Works');
        $contractorListAll=ContractorFinalModel::contractorHardListAll()->get(array('Id'));
        $contractorClassificationId = ContractorClassificationModel::classification()->get(array('Id','Code','Name'));
        $dzongkhagId = DzongkhagModel::dzongkhag()->orderBy('NameEn')->get(array('Id','NameEn'));

        $cdbNo = Input::get('CDBNo');
        $contractorId=Input::get('Works');

        $dzongkhags = Input::get('CmnDzongkhagId');
  
        $contractorClassifications = Input::get('CmnContractorClassificationId');
        $contractorLists = array();
        $limit=Input::get('Limit');


        $query = "SELECT CDBNo,Dzongkhag, classification,  Works  FROM viewcontractorworkinhand WHERE Works = '0' AND 1";

        

        $parameters = array();
       if((bool)$cdbNo){
            $query.=" and CDBNo like '%$cdbNo%'";
        }
  
        if((bool)$contractorId!=NULL){
            $query.=" and Works=?";
            array_push($parameters,$contractorId);
        }
        if((bool)$dzongkhags){
            $parametersForPrint['Dzongkhag'] = $dzongkhags;
            $query.=" and Dzongkhag=?";
            array_push($parameters,$dzongkhags);
        }
        if((bool)$contractorClassifications){
            $parametersForPrint['Classification'] = $contractorClassifications;
            $query.=" and classification = ?";
        
                array_push($parameters,$contractorClassifications);
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
                Excel::create('List of Contractor with no work', function($excel) use ($contractorLists,$parametersForPrint) {
                    $excel->sheet('Sheet 1', function($sheet) use ($contractorLists,$parametersForPrint) {
                        $sheet->setOrientation('landscape');
                        $sheet->setFitToPage(1);
                        $sheet->setPaperSize(1);
                        $sheet->loadView('reportexcel.listofworknotinhand')
                            ->with('parametersForPrint',$parametersForPrint)
                            ->with('contractorLists',$contractorLists);

                    });

                })->export('xlsx');
            }
        }
        return View::make('report.listofworknotinhand')
            ->with('start',$start)
            ->with('noOfPages',$noOfPages)
            ->with('parametersForPrint',$parametersForPrint)
            ->with('contractorListAll',$contractorListAll)
            ->with('contractorId',$contractorId)
            ->with('contractorLists',$contractorLists)
            ->with('dzongkhagId',$dzongkhagId)
            ->with('contractorId',$contractorId)
            ->with('contractorClassificationId',$contractorClassificationId);
        
    }
}