<?php

/**
 * Created by PhpStorm.
 * User: Sangay
 * Date: 2/11/2016
 * Time: 3:17 PM
 */
class ReportListOfDeregisteredContractors extends ReportController
{
    public function getIndex(){
        $parametersForPrint = array();
        $contractorListAll=ContractorFinalModel::contractorHardListAll()->get(array('Id','NameOfFirm'));
        $contractorCategoryId = ContractorWorkCategoryModel::contractorProjectCategory()->orderBy('Code')->get(array("Id","Code","Name"));
        $contractorClassificationId = ContractorClassificationModel::classification()->orderBy('Priority')->get(array('Id','Code','Name'));
        $dzongkhagId = DzongkhagModel::dzongkhag()->orderBy('NameEn')->get(array('Id','NameEn'));
        $parameters=array();
        $cdbNo=Input::get('CdbRegistrationNo');
        $contractorId=Input::get('ContractorId');
        $dzongkhags = Input::get('CmnDzongkhagId');
        $contractorCategories = Input::get('CmnContractorCategoryId');
        $contractorClassifications = Input::get('CmnContractorClassificationId');
        $contractorLists = array();
        $limit=Input::get('Limit');
        $fromDate = Input::get('FromDate');
        $toDate = Input::get('ToDate');

        $query = "select (select GROUP_CONCAT(distinct B.Name) from crpcontractortraining A join cmnlistitem B on B.Id = A.CmnTrainingModuleId join crpcontractortrainingdetail C on C.CrpContractorTrainingId = A.Id where C.CrpContractorFinalId = T1.Id and A.CmnTrainingTypeId = ?) as RefresherCourseModules, CDBNo,NAME, NameOfFirm, Address,Status, ExpiryDate, Dzongkhag, Country, TelephoneNo, MobileNo, Classification1, Classification2, Classification3, Classification4, DeRegisteredDate from viewlistofcontractors T1 Where CmnApplicationRegistrationStatusId =?";
        array_push($parameters,CONST_TRAININGTYPE_REFRESHER);
        array_push($parameters,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_DEREGISTERED);
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
        if((bool)$contractorClassifications){
            $parametersForPrint['Classification'] = $contractorClassifications;
            $query.=" and (Classification1 = ? or Classification2 = ? or Classification3 = ? or Classification4 = ?)";
            for($i = 0; $i<4; $i++){
                array_push($parameters,$contractorClassifications);
            }
        }
        if((bool)$contractorCategories){
            $parametersForPrint['Category'] = DB::table('cmncontractorworkcategory')->where('Id',$contractorCategories)->pluck('Code');
            $query.=" and (CategoryId1 = ? or CategoryId2 = ? or CategoryId3 = ? or CategoryId4 = ?)";
            for($i = 0; $i<4; $i++){
                array_push($parameters,$contractorCategories);
            }
        }
        if((bool)$fromDate){
            $parametersForPrint['From Date'] = $fromDate;
            $query.=" and DeRegisteredDate >= ?";
            array_push($parameters,date_format(date_create($fromDate),'Y-m-d'));
        }
        if((bool)$toDate){
            $parametersForPrint['To Date'] = $toDate;
            $query.=" and DeRegisteredDate <= ?";
            array_push($parameters,date_format(date_create($toDate),'Y-m-d'));
        }
        $query.=" order by CDBNo,NameOfFirm";
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
                Excel::create('List of Deregistered Contractors', function($excel) use ($contractorLists,$parametersForPrint) {
                    $excel->sheet('Sheet 1', function($sheet) use ($contractorLists,$parametersForPrint) {
                        $sheet->setOrientation('landscape');
                        $sheet->setFitToPage(1);
                        $sheet->setPaperSize(1);
                        $sheet->loadView('reportexcel.listofcontractorsderegistered')
                            ->with('parametersForPrint',$parametersForPrint)
                            ->with('contractorLists',$contractorLists);

                    });

                })->export('xlsx');
            }
        }
        return View::make('report.listofcontractorsderegistered')
            ->with('start',$start)
            ->with('noOfPages',$noOfPages)
            ->with('parametersForPrint',$parametersForPrint)
            ->with('contractorListAll',$contractorListAll)
            ->with('contractorId',$contractorId)
            ->with('contractorLists',$contractorLists)
            ->with('dzongkhagId',$dzongkhagId)
            ->with('contractorCategoryId',$contractorCategoryId)
            ->with('contractorClassificationId',$contractorClassificationId);
    }
}