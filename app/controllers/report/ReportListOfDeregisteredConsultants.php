<?php

/**
 * Created by PhpStorm.
 * User: Sangay
 * Date: 2/11/2016
 * Time: 3:17 PM
 */
class ReportListOfDeregisteredConsultants extends ReportController
{
    public function getIndex(){
        $parametersForPrint = array();
        $consultantListAll=ConsultantFinalModel::consultantHardListAll()->get(array('Id','NameOfFirm'));
        $consultantClassifications = DB::table('cmnconsultantservice')->orderBy('Code')->get(array("Code","Name"));
        $dzongkhagId = DzongkhagModel::dzongkhag()->orderBy('NameEn')->get(array('Id','NameEn'));
        $parameters=array();
        $cdbNo=Input::get('CdbRegistrationNo');
        $consultantId=Input::get('ConsultantId');
        $dzongkhags = Input::get('CmnDzongkhagId');
        $classification = Input::get('Classification');
        $consultantLists = array();
        $limit=Input::get('Limit');
        $fromDate = Input::get('FromDate');
        $toDate = Input::get('ToDate');

        $query = "select CDBNo, NameOfFirm, Name,Address,Status, ExpiryDate, Dzongkhag, Country, TelephoneNo, MobileNo, CategoryA, CategoryC, CategoryE, DeRegisteredDate from viewlistofconsultants Where StatusId =?";
        array_push($parameters,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_DEREGISTERED);
        if((bool)$cdbNo){
            $parametersForPrint['CDB No.'] = $cdbNo;
            $query.=" and CDBNo=?";
            array_push($parameters,$cdbNo);
        }
        if((bool)$consultantId){
            $parametersForPrint['Consultant'] = $consultantId;
            $consultantId="%$consultantId%";
            $query.=" and NameOfFirm like ?";
            array_push($parameters,$consultantId);
        }
        if((bool)$dzongkhags){
            $parametersForPrint['Dzongkhag'] = $dzongkhags;
            $query.=" and Dzongkhag=?";
            array_push($parameters,$dzongkhags);
        }
        if((bool)$classification){
            $parametersForPrint['Classification having'] = $classification;
            $query.=" and (CategoryA LIKE '%$classification%' or CategoryE LIKE '%$classification%' or CategoryC LIKE '%$classification%')";
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
        if((bool)$limit){
            $parametersForPrint['No. Of Records'] = $limit;
            if($limit != 'All'){
                $limit=" limit $limit";
            }else{
                $parametersForPrint['No ofRecords'] = '';
                $limit="";
            }
        }else{
            $parametersForPrint['No. Of Records'] = 20;
            $limit.=" limit 20";
        }
        $consultantLists=DB::select($query." order by CDBNo,NameOfFirm".$limit,$parameters);
        if(Input::has('export')){
            $export = Input::get('export');
            if($export == 'excel'){
                Excel::create('List of Deregistered Consultants', function($excel) use ($consultantLists,$parametersForPrint) {
                    $excel->sheet('Sheet 1', function($sheet) use ($consultantLists,$parametersForPrint) {
                        $sheet->setOrientation('landscape');
                        $sheet->setFitToPage(1);
                        $sheet->setPaperSize(1);
                        $sheet->loadView('reportexcel.listofconsultantsderegistered')
                            ->with('parametersForPrint',$parametersForPrint)
                            ->with('consultantLists',$consultantLists);

                    });

                })->export('xlsx');
            }
        }
        return View::make('report.listofconsultantsderegistered')
            ->with('parametersForPrint',$parametersForPrint)
            ->with('consultantListAll',$consultantListAll)
            ->with('consultantId',$consultantId)
            ->with('consultantLists',$consultantLists)
            ->with('dzongkhagId',$dzongkhagId)
            ->with('consultantClassifications',$consultantClassifications);
    }
}