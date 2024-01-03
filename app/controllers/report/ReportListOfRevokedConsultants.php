<?php

/**
 * Created by PhpStorm.
 * User: Sangay
 * Date: 2/11/2016
 * Time: 3:17 PM
 */
class ReportListOfRevokedConsultants extends ReportController
{
    public function getIndex(){
        $parametersForPrint = array();
        $contractorListAll=ContractorFinalModel::contractorHardListAll()->get(array('Id','NameOfFirm'));
        $consultantClassifications = DB::table('cmnconsultantservice')->orderBy('Code')->get(array("Code","Name"));
        $dzongkhagId = DzongkhagModel::dzongkhag()->orderBy('NameEn')->get(array('Id','NameEn'));
        $parameters=array();
        $cdbNo=Input::get('CdbRegistrationNo');
        $contractorId=Input::get('ContractorId');
        $dzongkhags = Input::get('CmnDzongkhagId');
        $classification = Input::get('Classification');
        $contractorLists = array();
        $limit=Input::get('Limit');
        $fromDate = Input::get('FromDate');
        $toDate = Input::get('ToDate');

        $query = "SELECT CDBNo,Name, NameOfFirm, Address,STATUS, DeRegisteredDate, Dzongkhag, Country, TelephoneNo, MobileNo,CategoryA, CategoryC, CategoryE,CategoryS FROM listofconsultants WHERE Status = 'Deregistered'";


        if((bool)$cdbNo){
            $parametersForPrint['CDB No.'] = $cdbNo;
            $query.=" and CDBNo=?";
            array_push($parameters,$cdbNo);
        }
        if((bool)$contractorId){
            $parametersForPrint['Contractor'] = $contractorId;
            $contractorId="%$contractorId%";
            $query.=" and NameOfFirm like ?";
            array_push($parameters,$contractorId);
        }
        if((bool)$dzongkhags){
            $parametersForPrint['Dzongkhag'] = $dzongkhags;
            $query.=" and Dzongkhag=?";
            array_push($parameters,$dzongkhags);
        }
        if((bool)$classification){
            $parametersForPrint['Classification having'] = $classification;
            $query.=" and (CategoryA LIKE '%$classification%' or CategoryE LIKE '%$classification%' or CategoryC LIKE '%$classification%'  or CategoryS LIKE '%$classification%')";
        }
        if((bool)$fromDate){
            $parametersForPrint['From Date'] = $fromDate;
            $query.=" and ApprovedDate >= ?";
            array_push($parameters,date_format(date_create($fromDate),'Y-m-d'));
        }
        if((bool)$toDate){
            $parametersForPrint['To Date'] = $toDate;
            $query.=" and ApprovedDate <= ?";
            array_push($parameters,date_format(date_create($toDate),'Y-m-d'));
        }
        $query.=" order by CDBNo,NameOfFirm";
        $pageNo = Input::has('page')?Input::get('page'):1;

        $pagination = $this->pagination($query,$parameters,10,$pageNo);
        $limitOffsetAppend = $pagination['LimitAppend'];
        $noOfPages = $pagination['NoOfPages'];
        $start = $pagination['Start'];

        $consultantLists=DB::select($query.$limitOffsetAppend,$parameters);
        if(Input::has('export')){
            $export = Input::get('export');
            if($export == 'excel'){
                Excel::create('List of Deregistered Consultants', function($excel) use ($consultantLists,$parametersForPrint) {
                    $excel->sheet('Sheet 1', function($sheet) use ($consultantLists,$parametersForPrint) {
                        $sheet->setOrientation('landscape');
                        $sheet->setFitToPage(1);
                        $sheet->setPaperSize(1);
                        $sheet->loadView('reportexcel.listofconsultantsrevoked')
                            ->with('parametersForPrint',$parametersForPrint)
                            ->with('consultantLists',$consultantLists);

                    });

                })->export('xlsx');
            }
        }
        return View::make('report.listofconsultantsrevoked')
            ->with('start',$start)
            ->with('noOfPages',$noOfPages)
            ->with('parametersForPrint',$parametersForPrint)
            ->with('contractorListAll',$contractorListAll)
            ->with('contractorId',$contractorId)
            ->with('consultantLists',$consultantLists)
            ->with('dzongkhagId',$dzongkhagId)
            ->with('consultantClassifications',$consultantClassifications);
    }
}