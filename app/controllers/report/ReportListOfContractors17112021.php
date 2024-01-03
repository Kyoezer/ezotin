<?php

class ReportListOfContractors extends ReportController{
    public function getIndex(){
        $parameters = array();
        $cdbNo = Input::get('CDBNo');
        $countryId = Input::has('CountryId')?Input::get('CountryId'):CONST_COUNTRY_BHUTAN;
        $dzongkhagId = Input::get('DzongkhagId');
        $categoryId = Input::get('ContractorCategoryId');
        $classificationId = Input::get('ContractorClassificationId');
        $limit = Input::has('Limit')?Input::get('Limit'):'All';
        $fromDate = Input::get('FromDate');
        $toDate = Input::get('ToDate');
        $statusId = Input::get('StatusId');

        $countries = CountryModel::country()->get(array('Id','Name'));
        $dzongkhags = DzongkhagModel::dzongkhag()->get(array('Id','NameEn'));
        $statuses = CmnListItemModel::registrationStatus()->whereIn('ReferenceNo',array(12003,12006,12008,12009,12010))->get(array('Id',DB::raw("case when Name = 'Approved' then 'Active' else Name end as Name")));
        $contractorCategories = ContractorWorkCategoryModel::contractorProjectCategory()->get(array("Id","Code","Name"));
        $contractorClassifications = ContractorClassificationModel::classification()->get(array('Id','Code','Name'));
        $query = "select  (SELECT con.Email from crpcontractor con where con.CDBNo=T1.CDBNo) Email,(select a.Name from crpcontractorhumanresourcefinal a where a.CrpContractorFinalId=(select b.Id from crpcontractorfinal b where b.CDBNo=T1.CDBNo) and a.IsPartnerOrOwner=1 limit 1)ownerName,
        (select if(a.Sex='M','Male','Female') from crpcontractorhumanresourcefinal a where a.CrpContractorFinalId=(select b.Id from crpcontractorfinal b where b.CDBNo=T1.CDBNo) and a.IsPartnerOrOwner=1 limit 1)gender,(select GROUP_CONCAT(distinct B.Name SEPARATOR ', ') from crpcontractortraining A join cmnlistitem B on B.Id = A.CmnTrainingModuleId join crpcontractortrainingdetail C on C.CrpContractorTrainingId = A.Id where C.CrpContractorFinalId = T1.Id and A.CmnTrainingTypeId = ?) as RefresherCourseModules,DATEDIFF(ExpiryDate,NOW()),CDBNo, NameOfFirm, Address,Status, ExpiryDate, Dzongkhag, Country, TelephoneNo, MobileNo, Classification1, Classification2, Classification3, Classification4
         from viewlistofcontractors T1 Where CmnApplicationRegistrationStatusId <>?";
        array_push($parameters,CONST_TRAININGTYPE_REFRESHER);
        array_push($parameters,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_DEREGISTERED);
        if((bool)$cdbNo){
            $query.=" and CDBNo = '$cdbNo'";
        }
        if((bool)$countryId){
//            if($countryId == CONST_COUNTRY_BHUTAN){
//                $query.=" and Dzongkhag is not null";
//            }
            $query.=" and CmnCountryId = ?";
            array_push($parameters,$countryId);
        }
        if((bool)$dzongkhagId){
            $query.=" and CmnDzongkhagId = ?";
            array_push($parameters,$dzongkhagId);
        }
        if((bool)$categoryId){
            $query.=" and (CategoryId1 = ? or CategoryId2 = ? or CategoryId3 = ? or CategoryId4 = ?)";
            for($i = 0; $i<4; $i++){
                array_push($parameters,$categoryId);
            }
        }
        if((bool)$classificationId){
            $query.=" and (ClassId1 = ? or ClassId2 = ? or ClassId3 = ? or ClassId4 = ?)";
            for($i = 0; $i<4; $i++){
                array_push($parameters,$classificationId);
            }
        }

        if(Request::segment(2) == 'listofcontractorsnearingexpiry'){
            if((bool)$fromDate){
                $query.=" and ExpiryDate >= ?";
                array_push($parameters,date_format(date_create($fromDate),'Y-m-d'));
            }
            if((bool)$toDate){
                $query.=" and ExpiryDate <= ?";
                array_push($parameters,date_format(date_create($toDate),'Y-m-d'));
            }
        }else{
            if((bool)$fromDate){
                $query.=" and InitialDate >= ?";
                array_push($parameters,date_format(date_create($fromDate),'Y-m-d'));
            }
            if((bool)$toDate){
                $query.=" and InitialDate <= ?";
                array_push($parameters,date_format(date_create($toDate),'Y-m-d'));
            }
        }
        if((bool)$statusId){
            $query.=" and CmnApplicationRegistrationStatusId = ?";
            array_push($parameters,$statusId);
            if($statusId == CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED){
                $query.=" and T1.ExpiryDate > NOW()";
            }
        }

        $orderAppend = "";
        if(Request::segment(2) == 'listofcontractorsnearingexpiry'){
            $orderAppend =" case when DATEDIFF(ExpiryDate,NOW())<=30 and DATEDIFF(ExpiryDate,NOW()) > 0 then 1 else 2 end,ExpiryDate ASC,";
            $query.=" and CmnApplicationRegistrationStatusId = ?";
            array_push($parameters,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED);
            $type = 3;
        }else{
            $type = 1;
        }

        $query.="  and (ClassId1 is not null or ClassId2 is not null or ClassId3 is not null or ClassId4 is not null) order by $orderAppend CDBNo";

        /*PAGINATION*/
        $pageNo = Input::has('page')?Input::get('page'):1;
        $pagination = $this->pagination($query,$parameters,20,$pageNo);
        $limitOffsetAppend = $pagination['LimitAppend'];
        $noOfPages = $pagination['NoOfPages'];
        $start = $pagination['Start'];
        /*END PAGINATION*/

        $contractorsList = DB::select($query."$limitOffsetAppend",$parameters);
        if(Input::has('export')){
            $export = Input::get('export');
            $dzongkhag = '';
            $country = '';
            $category = '';
            $classification = '';
            $status = '';
            if((bool)$dzongkhagId){
                $dzongkhag = DB::table('cmndzongkhag')->where('Id',$dzongkhagId)->pluck('NameEn');
            }
            if((bool)$countryId){
                $country = DB::table('cmncountry')->where('Id',$countryId)->pluck('Name');
            }
            if((bool)$categoryId){
                $category = DB::table('cmncontractorworkcategory')->where('Id',$categoryId)->pluck('Code');
            }
            if((bool)$classificationId){
                $classification = DB::table('cmncontractorclassification')->where('Id',$classificationId)->pluck('Code');
            }
            if((bool)$statusId){
                $status = DB::table('cmnlistitem')->where('Id',$statusId)->pluck('Name');
            }
            if($export == 'excel'){
                Excel::create('List of Contractors', function($excel) use ($contractorsList,$toDate,$fromDate,$cdbNo,$limit,$dzongkhag,$country,$category,$classification,$status) {
                    $excel->sheet('Sheet 1', function($sheet) use ($contractorsList,$cdbNo,$toDate,$fromDate,$limit,$dzongkhag,$country,$category,$classification,$status) {
                        $sheet->setOrientation('landscape');
                        $sheet->setFitToPage(1);
                        $sheet->setPaperSize(1);
                        $sheet->loadView('reportexcel.listofcontractors')
                            ->with('type',1)
                            ->with('cdbNo',$cdbNo)
                            ->with('limit',$limit)
                            ->with('fromDate',$fromDate)
                            ->with('toDate',$toDate)
                            ->with('dzongkhag',$dzongkhag)
                            ->with('country',$country)
                            ->with('category',$category)
                            ->with('classification',$classification)
                            ->with('status',$status)
                            ->with('contractorsList',$contractorsList);

                    });

                })->export('xlsx');
            }
        }
        return View::make('report.listofcontractors')
                        ->with('start',$start)
                        ->with('noOfPages',$noOfPages)
                        ->with('type',$type)
                        ->with('contractorsList',$contractorsList)
                        ->with('countries',$countries)
                        ->with('dzongkhags',$dzongkhags)
                        ->with('statuses',$statuses)
                        ->with('contractorCategories',$contractorCategories)
                        ->with('contractorClassifications',$contractorClassifications);
    }
    public function getCategoryWise(){
        $parameters = array();
        $parametersForPrint = array();
        $reportData = array();
        $hasParams = false;
        $fromDate = Input::get('FromDate');
        $toDate = Input::get('ToDate');
        $category = Input::get('ContractorCategoryId');
        $classification = Input::get('ContractorClassificationId');
        $statusId = Input::get('StatusId');
        $countryId = Input::get('CountryId');
        $dzongkhagId = Input::get('DzongkhagId');

        $categories = ContractorWorkCategoryModel::contractorProjectCategory()->get(array('Id','Code'));
        $classifications = ContractorClassificationModel::classification()->get(array('Id','Code'));
        $statuses = CmnListItemModel::registrationStatus()->get(array('Id','Name'));
        $countries = CountryModel::country()->get(array('Id','Name'));
        $dzongkhags = DzongkhagModel::dzongkhag()->get(array('Id','NameEn'));

        $query = "select count(distinct T1.Id) as Count from crpcontractorfinal T1 left join crpcontractorworkclassificationfinal T2 on T1.Id = T2.CrpContractorFinalId where 1";

        if((bool)$fromDate){
            $hasParams = true;
            $parametersForPrint['From Date'] = $fromDate;
            $query.=" and ApprovedDate >= ?";
            array_push($parameters,$this->convertDate($fromDate));
        }
        if((bool)$toDate){
            $hasParams = true;
            $parametersForPrint['To Date'] = $toDate;
            $query.=" and ApprovedDate <= ?";
            array_push($parameters,$this->convertDate($toDate));
        }
        if((bool)$category){
            $hasParams = true;
            $parametersForPrint['Category'] = ContractorWorkCategoryModel::contractorProjectCategory()->where('Id',$category)->pluck('Code');
            $query.=" and T2.CmnProjectCategoryId = ?";
            array_push($parameters,$category);
        }
        if((bool)$classification){
            $hasParams = true;
            $parametersForPrint['Classification'] = ContractorClassificationModel::classification()->where('Id',$classification)->pluck('Code');
            $query.=" and T2.CmnApprovedClassificationId = ?";
            array_push($parameters,$classification);
        }
        if((bool)$statusId){
            $hasParams = true;
            $parametersForPrint['Status'] = DB::table('cmnlistitem')->where('Id',$statusId)->pluck('Name');
            $query.=" and T1.CmnApplicationRegistrationStatusId = ?";
            array_push($parameters,$statusId);
        }
        if((bool)$countryId){
            $hasParams = true;
            $parametersForPrint['Country'] = DB::table('cmncountry')->where('Id',$countryId)->pluck('Name');
            $query.=" and T1.CmnCountryId = ?";
            array_push($parameters,$countryId);
        }
        if((bool)$dzongkhagId){
            $hasParams = true;
            $parametersForPrint['Dzongkhag'] = DB::table('cmndzongkhag')->where('Id',$dzongkhagId)->pluck("NameEn");
            $query.=" and T1.CmnDzongkhagId = ?";
            array_push($parameters,$dzongkhagId);
        }
        if($hasParams){
            $reportData = DB::select("$query", $parameters);
        }
        return View::make('report.contractorcategoryreport')
                ->with('statuses',$statuses)
                ->with('countries',$countries)
                ->with('dzongkhags',$dzongkhags)
                ->with('contractorClassifications',$classifications)
                ->with('contractorCategories',$categories)
                ->with('parametersForPrint',$parametersForPrint)
                ->with('reportData',$reportData);
    }

    public function listofcontractorswithworkinhand(){
        $query = 
       "select viewcontractorstrackrecords.CDBNo,crpcontractorfinal.NameOfFirm ,
           count(viewcontractorstrackrecords.WorkId)total from `viewcontractorstrackrecords` 
           left join crpcontractorfinal on viewcontractorstrackrecords.CrpContractorFinalId=crpcontractorfinal.Id 
           where viewcontractorstrackrecords.`WorkCompletionDate` >= '2010-01-01' and viewcontractorstrackrecords.ReferenceNo=3001 
       ";

       $parameters = array();
       $cdbNo = Input::has('CDBNo')?Input::get('CDBNo'):'';
       if((bool)$cdbNo){
           $query.=" and viewcontractorstrackrecords.CDBNo='".$cdbNo."'";
       }
       $query.=" group by viewcontractorstrackrecords.CrpContractorFinalId ";
       
       /*PAGINATION*/
       $pageNo = Input::has('page')?Input::get('page'):1;
       $pagination = $this->pagination($query,$parameters,10,$pageNo);
       $limitOffsetAppend = $pagination['LimitAppend'];
       $noOfPages = $pagination['NoOfPages'];
       $start = $pagination['Start'];
       /*END PAGINATION*/

       $contractorList = DB::select($query.$limitOffsetAppend,$parameters);
       if(Input::has('export')){
           $export = Input::get('export');
           if($export == 'excel'){
               Excel::create('List of Surveyor', function($excel) use ($surveyorList, $country,$dzongkhag,$cdbNo,$sector,$trade,$limit,$fromDate,$toDate,$status) {

                   $excel->sheet('Sheet 1', function($sheet) use ($surveyorList, $country,$dzongkhag,$cdbNo,$sector,$trade,$limit,$fromDate,$toDate,$status) {
                       $sheet->setOrientation('landscape');
                       $sheet->setFitToPage(1);
                       $sheet->loadView('reportexcel.listOfSurveyor')
                           ->with('dzongkhag',$dzongkhag)
                           ->with('country',$country)
                           ->with('ARNo',$cdbNo)
                           ->with('sector',$sector)
                           ->with('limit',$limit)
                           ->with('fromDate',$fromDate)
                           ->with('toDate',$toDate)
                           ->with('status',$status)
                           ->with('surveyorList',$surveyorList);

                   });

               })->export('xlsx');
           }
       }
       return View::make('report.listofcontractorworkinhand')
                       ->with('start',$start)
                       ->with('noOfPages',$noOfPages)
                       ->with('contractorList',$contractorList);
   }

    public function listofcontractorsotparticipatinganywork(){
        $query = "select crpcontractorfinal.CDBNo,crpcontractorfinal.NameOfFirm 
        from crpcontractorfinal
        where crpcontractorfinal.Id not in (select viewcontractorstrackrecords.CrpContractorFinalId from viewcontractorstrackrecords)";

    $parameters = array();
    $cdbNo = Input::has('CDBNo')?Input::get('CDBNo'):'';
    if((bool)$cdbNo){
        $query.=" and crpcontractorfinal.CDBNo='".$cdbNo."'";
    }
    
    /*PAGINATION*/
    $pageNo = Input::has('page')?Input::get('page'):1;
    $pagination = $this->pagination($query,$parameters,10,$pageNo);
    $limitOffsetAppend = $pagination['LimitAppend'];
    $noOfPages = $pagination['NoOfPages'];
    $start = $pagination['Start'];
    /*END PAGINATION*/

    $contractorList = DB::select($query.$limitOffsetAppend,$parameters);
    if(Input::has('export')){
        $export = Input::get('export');
        if($export == 'excel'){
            Excel::create('List of Surveyor', function($excel) use ($surveyorList, $country,$dzongkhag,$cdbNo,$sector,$trade,$limit,$fromDate,$toDate,$status) {

                $excel->sheet('Sheet 1', function($sheet) use ($surveyorList, $country,$dzongkhag,$cdbNo,$sector,$trade,$limit,$fromDate,$toDate,$status) {
                    $sheet->setOrientation('landscape');
                    $sheet->setFitToPage(1);
                    $sheet->loadView('reportexcel.listofcontractorworkinhand')
                    ->with('start',$start)
                    ->with('noOfPages',$noOfPages)
                    ->with('contractorList',$contractorList);

                });

            })->export('xlsx');
        }
    }
    return View::make('report.listofcontractornowork')
                    ->with('start',$start)
                    ->with('noOfPages',$noOfPages)
                    ->with('contractorList',$contractorList);
    }

    public function topperformingcontractor(){
        $query = "SELECT a.CDBNo,a.NameOfFirm,count(a.CDBNo) totalWorkCompleted 
                FROM crpcontractorfinal a 
                left join etltenderbiddercontractordetail b on a.Id=b.CrpContractorFinalId 
                left join etltenderbiddercontractor c on b.EtlTenderBidderContractorId=c.Id 
                left join etltender d on c.EtlTenderId=d.Id 
                left join cmnlistitem e on d.CmnWorkExecutionStatusId=e.Id 
                where c.ActualStartDate is not null and e.Name = 'Completed' group by a.CDBNo order by count(a.CDBNo) desc ";

    $parameters = array();
    
    
    /*PAGINATION*/
    $pageNo = Input::has('page')?Input::get('page'):1;
    $pagination = $this->pagination($query,$parameters,10,$pageNo);
    $limitOffsetAppend = $pagination['LimitAppend'];
    $noOfPages = $pagination['NoOfPages'];
    $start = $pagination['Start'];
    /*END PAGINATION*/

    $contractorList = DB::select($query.$limitOffsetAppend,$parameters);
    if(Input::has('export')){
        $export = Input::get('export');
        if($export == 'excel'){
            Excel::create('List of Surveyor', function($excel) use ($surveyorList, $country,$dzongkhag,$cdbNo,$sector,$trade,$limit,$fromDate,$toDate,$status) {

                $excel->sheet('Sheet 1', function($sheet) use ($surveyorList, $country,$dzongkhag,$cdbNo,$sector,$trade,$limit,$fromDate,$toDate,$status) {
                    $sheet->setOrientation('landscape');
                    $sheet->setFitToPage(1);
                    $sheet->loadView('reportexcel.topperformingcontractor')
                    ->with('start',$start)
                    ->with('noOfPages',$noOfPages)
                    ->with('contractorList',$contractorList);

                });

            })->export('xlsx');
        }
    }
    return View::make('report.topperformingcontractor')
                    ->with('start',$start)
                    ->with('noOfPages',$noOfPages)
                    ->with('contractorList',$contractorList);
    }

    public function defaultcontractors(){
        $query = "SELECT * FROM `viewlistofcontractors` WHERE `ExpiryDate`<CURRENT_DATE()";

        $parameters = array();
        
        $cdbNo = Input::has('CDBNo')?Input::get('CDBNo'):'';
        if((bool)$cdbNo){
            $query.=" and CDBNo='".$cdbNo."'";
        }
        
        /*PAGINATION*/
        $pageNo = Input::has('page')?Input::get('page'):1;
        $pagination = $this->pagination($query,$parameters,10,$pageNo);
        $limitOffsetAppend = $pagination['LimitAppend'];
        $noOfPages = $pagination['NoOfPages'];
        $start = $pagination['Start'];
        /*END PAGINATION*/

        $contractorList = DB::select($query.$limitOffsetAppend,$parameters);
        if(Input::has('export')){
            $export = Input::get('export');
            if($export == 'excel'){
                Excel::create('List of Surveyor', function($excel) use ($surveyorList, $country,$dzongkhag,$cdbNo,$sector,$trade,$limit,$fromDate,$toDate,$status) {

                    $excel->sheet('Sheet 1', function($sheet) use ($surveyorList, $country,$dzongkhag,$cdbNo,$sector,$trade,$limit,$fromDate,$toDate,$status) {
                        $sheet->setOrientation('landscape');
                        $sheet->setFitToPage(1);
                        $sheet->loadView('reportexcel.defaultcontractors')
                        ->with('start',$start)
                        ->with('noOfPages',$noOfPages)
                        ->with('contractorList',$contractorList);

                    });

                })->export('xlsx');
            }
        }
        return View::make('report.defaultcontractors')
                        ->with('start',$start)
                        ->with('noOfPages',$noOfPages)
                        ->with('contractorList',$contractorList);
    }

    public function totalparticipant(){
        $query = "select    case when T1.migratedworkid is null then concat(T5.Code,'/',year(T1.UploadedDate),'/',T1.WorkId) else T1.migratedworkid end as WorkId, `T5`.`Name` as `ProcuringAgency`, `T1`.`DescriptionOfWork`, `T1`.`NameOfWork`, concat(T2.Name,' (',T2.Code,')') as Classification, `T3`.`Code` as `Category`, `T7`.`NameEn` as `Dzongkhag`,
                    (select count(*) from etltenderbiddercontractor where etltenderbiddercontractor.EtlTenderId=T1.Id)totalparticipant
                    from `etltender` as `T1` 
                    inner join `cmncontractorclassification` as `T2` on `T1`.`CmnContractorClassificationId` = `T2`.`Id` 
                    inner join `cmncontractorworkcategory` as `T3` on `T1`.`CmnContractorCategoryId` = `T3`.`Id` 
                    inner join `cmnprocuringagency` as `T5` on `T5`.`Id` = `T1`.`CmnProcuringAgencyId` 
                    inner join `cmndzongkhag` as `T7` on `T1`.`CmnDzongkhagId` = `T7`.`Id` 
                   
                ";

        $parameters = array();
        
        $workId = Input::has('workId')?Input::get('workId'):'';
        if((bool)$workId){
            $query.=" and (case when T1.migratedworkid is null then concat(T5.Code,'/',year(T1.UploadedDate),'/',T1.WorkId) else T1.migratedworkid end)='".$workId."'";
        }
        $query.="  order by T1.UploadedDate desc ";
        /*PAGINATION*/
        $pageNo = Input::has('page')?Input::get('page'):1;
        $pagination = $this->pagination($query,$parameters,10,$pageNo);
        $limitOffsetAppend = $pagination['LimitAppend'];
        $noOfPages = $pagination['NoOfPages'];
        $start = $pagination['Start'];
        /*END PAGINATION*/

        $contractorList = DB::select($query.$limitOffsetAppend,$parameters);
        if(Input::has('export')){
            $export = Input::get('export');
            if($export == 'excel'){
                Excel::create('List of Surveyor', function($excel) use ($surveyorList, $country,$dzongkhag,$cdbNo,$sector,$trade,$limit,$fromDate,$toDate,$status) {

                    $excel->sheet('Sheet 1', function($sheet) use ($surveyorList, $country,$dzongkhag,$cdbNo,$sector,$trade,$limit,$fromDate,$toDate,$status) {
                        $sheet->setOrientation('landscape');
                        $sheet->setFitToPage(1);
                        $sheet->loadView('reportexcel.totalparticipant')
                        ->with('start',$start)
                        ->with('noOfPages',$noOfPages)
                        ->with('contractorList',$contractorList);

                    });

                })->export('xlsx');
            }
        }
        return View::make('report.totalparticipant')
                        ->with('start',$start)
                        ->with('noOfPages',$noOfPages)
                        ->with('contractorList',$contractorList);
    }
     
    
    public function monitoringOffice(){
        $dzongkhags = DzongkhagModel::dzongkhag()->get(array('Id','NameEn'));
        $classifications = ContractorClassificationModel::classification()->get(array('Id','Name'));

        return View::make('report.officeMonitoring')
                ->with('dzongkhagList',$dzongkhags)
                ->with('classificationList',$classifications)
                ->with('inspectionType','OFFICE_ESTABLISHMENT');
    }
    
    public function privatesite(){
        $dzongkhags = DzongkhagModel::dzongkhag()->get(array('Id','NameEn'));
        $classifications = ContractorClassificationModel::classification()->get(array('Id','Name'));

        return View::make('report.officeMonitoring')
                ->with('dzongkhagList',$dzongkhags)
                ->with('classificationList',$classifications)
                ->with('inspectionType','PRIVATE_SITE');
    }
    
    public function publicsite(){
        $dzongkhags = DzongkhagModel::dzongkhag()->get(array('Id','NameEn'));
        $classifications = ContractorClassificationModel::classification()->get(array('Id','Name'));

        return View::make('report.officeMonitoring')
                ->with('dzongkhagList',$dzongkhags)
                ->with('classificationList',$classifications)
                ->with('inspectionType','PUBLIC_SITE');
    }
    


    public function actionTaken(){
        $dzongkhags = DzongkhagModel::dzongkhag()->get(array('Id','NameEn'));
        $classifications = ContractorClassificationModel::classification()->get(array('Id','Name'));

        return View::make('report.monitoringActionReport')
                ->with('dzongkhagList',$dzongkhags)
                ->with('classificationList',$classifications)
                ->with('inspectionType','PUBLIC_SITE');
    }
    


    public function reportDetails(){
        $inspectionId = Input::get('inspectionId');
        $reportType = Input::get('reportType');
        //return $reportType ;
        $query = "";
        $ppeCheckList = "";
        if($reportType=='Office')
        {
           $query ="SELECT 
                a.createdOn,
                a.inspectionId,
                a.cdbId,
                a.isEstablished,
                a.isEstablishedRemarks,
                a.isSignboard,
                a.isSignboardRemarks,
                a.isFileSystem,
                a.isFileSystemRemarks,
                a.hasHandbook,
                a.hasHandbookRemarks,
                a.hasSafety,
                a.hasSafetyRemarks,
                a.hasSafetyOfficer,
                a.hasSafetyOfficerRemarks,
                a.latitude,
                a.longitude,
                a.localityName,
                a.isRequirementFulfilled,
                a.representativeName,
                a.representativeCidNo,
                a.representativeContactNo,
                a.remarks,
                b.cdbId,
                b.inspectionType,
                b.cdbNo,
                b.nameOfFirm,
                b.licenseNo,
                b.dzongkhagName,
                b.permAddress,
                b.email,
                b.telNo,
                b.mobNo,
                b.classificationName,
                b.validity,
                b.nameOfWork,
                b.procuringAgency,
                b.actualStartDate,
                b.actualEndDate,
                b.awardedAmount 
                FROM
                t_monitoring_office a 
                LEFT JOIN t_monitoring_profile_details b 
                    ON a.inspectionId = b.inspectionId
                    where a.inspectionId='".$inspectionId."'";
        }
        else if($reportType=='Office_human_resources')
        {
        //   $query ="SELECT * FROM t_monitoring_office_human_resources a WHERE a.inspectionId=".$inspectionId;
	  $query ="SELECT a.*,c.Name AS designation FROM t_monitoring_office_human_resources a
           LEFT JOIN crpcontractorhumanresourcefinal b ON b.CIDNo=a.cidNo
           LEFT JOIN cmnlistitem c ON b.CmnDesignationId = c.Id WHERE a.inspectionId=".$inspectionId;
        }
        else if($reportType=='Public ongoing site_human_resources')
        {
        //   $query ="SELECT * FROM t_monitoring_public_site_human_resources WHERE inspectionId=".$inspectionId;
	 $query ="SELECT g.*,e.Name AS designation  
                        FROM t_monitoring_public_site_human_resources g 
                        LEFT JOIN crpcontractorhumanresourcefinal b ON g.cid=b.`CIDNo` 
                        LEFT JOIN cmnlistitem e ON b.CmnDesignationId=e.Id
                        WHERE g.inspectionId=".$inspectionId;
        }
        else if($reportType=='Public ongoing site_equipment')
        {
           $query =" SELECT * FROM t_monitoring_public_site_equipment WHERE inspectionId=".$inspectionId;
        }
        else if($reportType=='Office_inspection')
        {
           $query ="SELECT * FROM t_monitoring_office_human_resources a WHERE a.monitoring_office_id=".$inspectionId;
        }
        else if($reportType=='Office_image' ||  $reportType=='Private ongoing site_image'   ||  $reportType=='Public ongoing site_image')
        {
            $docType = "";
            if($reportType=='Office_image')
                $docType = 'OFFICE';
            else if($reportType=='Private ongoing site_image')
                $docType = 'PRIVATE';
            else if($reportType=='Public ongoing site_image')
                $docType = 'PUBLIC';

            $query ="SELECT * FROM t_monitoring_document a WHERE a.inspection_id=".$inspectionId." AND a.document_type='".$docType."'";
        }
        else if($reportType=='Private ongoing site')
        {
            $query ="SELECT  cdbNo, nameOfFirm, licenseNo, dzongkhagName, a.localityName workAddress, email, telNo, mobNo, classificationName, validity, contractExtension, contractExtensionStartDate, contractExtensionEndDate, contractValue, hasOffice, hasOfficeRemarks, hasSignboard, hasSignboardRemarks, hasStore, hasStoreRemarks, hasDocument, hasDocumentRemarks, isInsured, isInsuredRemarks, hasLabourInsurance, hasLabourInsuranceRemarks, hasThirdPartyInsurance, hasThirdPartyInsuranceRemarks, hasWorkPlan, hasWorkPlanRemarks, workStatus, workStatusRemarks, hasCamp, hasPowerSupply, hasSanitary, hasWaterSupply, hasOhsIncharge, hasOhsInchargeRemarks, hasFireExtinguishingEquipment, hasFireExtinguishingEquipmentRemarks, hasFirstAidBox, hasFirstAidBoxRemarks, nationalLabours, foreignLabours, internNo, vtiGraduates, hasSpecializeTrade, hasSpecializeTradeRemarks, latitude, longitude, localityName, representativeName, representativeCidNo, representativeContactNo, remarks,a.createdOn inspection_date FROM t_monitoring_private_site_inspection a 
            left join t_monitoring_profile_details b on a.inspectionId=b.inspectionId
            where a.inspectionId=".$inspectionId;
        }
        else if($reportType=='Public ongoing site')
        {
           $query =" SELECT a.`awardedAmount`,a.`cdbNo`,a.`nameOfFirm`,a.`licenseNo`,a.`dzongkhagName`,a.`permAddress`,a.`email`,a.`telNo`,a.`mobNo`,a.`classificationName`,
           a.`validity`,a.`nameOfWork`,a.`procuringAgency`,a.`actualStartDate`,
           a.`actualEndDate`,a.`actualEndDate`,t_monitoring_public_site_inspection.`inspectionId`, `workId`, `procuringSiteEngineerName`, `hasAps`, `hasApsRemarks`, `hasOffice`, `hasOfficeRemarks`, `hasSignboard`, 
           `hasSignboardRemarks`, `hasStore`, `hasStoreRemarks`, `hasAccommodation`, `hasAccommodationRemarks`, `hasSanitary`, `hasSanitaryRemarks`, 
           `hasPotableWater`, `hasPotableWaterRemarks`, `hasWorkInsurance`, `hasWorkInsuranceRemarks`, `hasLabourInsurance`, `hasLabourInsuranceRemarks`,
           `hasThirdPartyInsurance`, `hasThirdPartyInsuranceRemarks`, `hasWorkPlan`, `hasWorkPlanRemarks`, `workStatus`, `workStatusRemarks`, `hasBoq`, `hasBoqRemarks`,
           `hasContractConditions`, `hasContractConditionsRemarks`, `hasApprovedDrawing`, `hasApprovedDrawingRemarks`, `hasWorkSpecifications`, `hasWorkSpecificationsRemarks`, 
           `hasSiteOrderBook`, `hasSiteOrderBookRemarks`, `hasHindranceRegister`, `hasHindranceRegisterRemarks`, `hasMbs`, `hasMbsRemarks`, `hasCms`, `hasCmsRemarks`, 
           `hasJournal`, `hasJournalRemarks`, `hasQualityAssurancePlan`, `hasQualityAssurancePlanRemarks`, `hasQualityControlPlan`, `hasQualityControlPlanRemarks`, `hasTestReport`, 
           `hasTestReportRemarks`, `hasLocalMaterials`, `hasLocalMaterialsRemarks`, `hasSpecializedTrade`, `hasSpecializedTradeRemarks`, `hasOhsIncharge`, `hasOhsInchargeRemarks`,
           `hasSafetySignages`, `hasSafetySignagesRemarks`, `hasFireExtinguishingEquipment`, `hasFireExtinguishingEquipmentRemarks`, `hasFirstAidBox`, `hasFirstAidBoxRemarks`,
           `hasPeripheralBoundary`, `hasPeripheralBoundaryRemarks`, `hasProperElectricalInstallations`, `hasProperElectricalInstallationsRemarks`, `hasCctv`, `hasCctvRemarks`, 
           `nationalLabours`, `foreignLabours`, `internNo`, `vtiGraduates`, `latitude`, `longitude`, `localityName`, `representativeName`, `representativeCidNo`, `representativeContactNo`, 
           `siteEngineerName`, 
           t_monitoring_public_site_inspection.`remarks`, t_monitoring_public_site_inspection.`createdBy`, t_monitoring_public_site_inspection.`createdOn`
            FROM `t_monitoring_public_site_inspection`
           LEFT JOIN `t_monitoring_profile_details` a ON t_monitoring_public_site_inspection.inspectionId=a.inspectionId AND a.`inspectionType`='PUBLIC'
            WHERE t_monitoring_public_site_inspection.inspectionId=".$inspectionId;

	$queryPPE = "SELECT * FROM `t_monitoring_ppe_dtls` WHERE `inspectionId`=".$inspectionId;
            $ppeCheckList = DB::select($queryPPE);
        }
else if($reportType=='Office_inspector')
        {
           $query =" SELECT * FROM t_monitoring_inspector_details WHERE inspectionId=".$inspectionId;
        }
        

       $detailReport = DB::select($query); 
        return View::make('report.monitoringDetailReport')
        ->with('detailReport',$detailReport)
        ->with('ppeCheckList',$ppeCheckList)
        ->with('reportType',$reportType);



    }
    
    public function actionTakenReport(){
        
        $cdbNo = Input::get('cdbNo');
        $fromDate = Input::get('fromDate');
        $toDate = Input::get('toDate');
        if($fromDate!="")
        {
            $fromDate = date("Y-m-d", strtotime($fromDate));
            $toDate = date("Y-m-d", strtotime($toDate));
        }
        $actionType = Input::get('actionType');
        $dzongkhag = Input::get('dzongkhag');
           
        $query = "SELECT
        b.NameOfFirm,
        b.CDBNo,
        a.MonitoringDate,
        IF(
          a.ActionTaken = '1',
          'Downgrade',
          IF(
            a.ActionTaken = '2',
            'Suspend',
            IF(
              a.ActionTaken = '3',
              'Warning',
              IF(
                a.ActionTaken = '4',
                'Monitoring Record',
                IF(
                  a.ActionTaken = '5',
                  'Reinstate',
                  ''
                )
              )
            )
          )
        ) actionTaken,a.MonitoringDate,
       c.NameEn dzongkhagName,
        (SELECT f.Name FROM cmncontractorworkcategory f WHERE f.id=d.CmnProjectCategoryId) to_category,
        (SELECT f.Name FROM cmncontractorclassification f WHERE f.id=d.CmnClassificationId) to_classification,
        (SELECT f.Name FROM cmncontractorworkcategory f WHERE f.id=e.category_id) from_category,
        (SELECT f.Name FROM cmncontractorclassification f WHERE f.id=e.classification_id) from_classificaiton,
        a.ActionDate,a.Remarks
         
      FROM
        crpmonitoringoffice a
        LEFT JOIN crpcontractorfinal b
          ON a.CrpContractorFinalId = b.Id
        LEFT JOIN cmndzongkhag c
          ON b.CmnDzongkhagId = c.Id
        LEFT JOIN crpcontractorworkclassificationmonitoring d
          ON a.Id = d.CrpMonitoringOfficeId
        LEFT JOIN t_monitoring_downgrade_record e
          ON a.Id = e.monitoring_id AND d.CmnProjectCategoryId=e.category_id
        WHERE IF(''='".$cdbNo."',1=1,b.CDBNo='".$cdbNo."') AND
        IF(''='".$dzongkhag."',1=1,b.CmnDzongkhagId='".$dzongkhag."') AND
        IF(''='".$fromDate."',1=1,a.MonitoringDate BETWEEN '".$fromDate."' AND '".$toDate."') AND
        IF(''='".$actionType."',1=1,a.actionTaken='".$actionType."')";
        $actionTaken = DB::select($query);
        return View::make('report.actionReportDtls')->with('actionTaken',$actionTaken);
    }     
    
    

    public function generateOfficeReport(){
        
        $cdbNo = Input::get('cdbNo');
        $fromDate = Input::get('fromDate');
        $toDate = Input::get('toDate');
        $inspectionType = Input::get('inspectionType');
        
        if($fromDate!="")
        {
            $fromDate = date("Y-m-d", strtotime($fromDate)).' 00:00:00';
            $toDate = date("Y-m-d", strtotime($toDate)).' 23:59:59';
        }
        $classification = Input::get('classification');
        $dzongkhag = Input::get('dzongkhag');
        $reportType = Input::get('reportType');
        $query = "";
        if($inspectionType=='OFFICE_ESTABLISHMENT')
        {
            
            if($reportType == "LOCATION" ||  $reportType == "DETAIL_REPORT" )
            {
                $query = "select a.createdOn,
                a.`inspectionId`, a.`cdbId`, a.`isEstablished`, a.`isEstablishedRemarks`, a.`isSignboard`, a.`isSignboardRemarks`, a.`isFileSystem`, a.`isFileSystemRemarks`, a.`hasHandbook`, a.`hasHandbookRemarks`, a.`hasSafety`, a.`hasSafetyRemarks`, a.`hasSafetyOfficer`, a.`hasSafetyOfficerRemarks`, a.`latitude`, a.`longitude`, a.`localityName`, a.`isRequirementFulfilled`, a.`representativeName`, a.`representativeCidNo`, a.`representativeContactNo`, a.`remarks`,  b.`cdbId`, b.`inspectionType`, b.`cdbNo`, b.`nameOfFirm`, b.`licenseNo`, b.`dzongkhagName`, b.`permAddress`, b.`email`, b.`telNo`, b.`mobNo`, b.`classificationName`, b.`validity`, b.`nameOfWork`, b.`procuringAgency`, b.`actualStartDate`, b.`actualEndDate`, b.`awardedAmount`
                from 
                t_monitoring_office a
                left join t_monitoring_profile_details b on a.inspectionId=b.inspectionId
                
                        WHERE IF(''='".$cdbNo."',1=1,b.cdbNo='".$cdbNo."') AND
                        IF(''='".$dzongkhag."',1=1,b.dzongkhagName='".$dzongkhag."') AND
                        IF(''='".$fromDate."',1=1,a.createdOn BETWEEN '".$fromDate."' AND '".$toDate."') AND
                        IF(''='".$classification."',1=1,b.classificationName='".$classification."') ";
            }
            else if($reportType == "SUMMARY")
            {
                $query = "SELECT COUNT(*) totalInspection, SUM(IF(a.`isEstablished`='Yes',1,0)) yesOfficeEstablishment, SUM(IF(a.isEstablished='No',1,0)) noOfficeEstablishment, SUM(IF(a.isEstablished='NOT Applicable',1,0)) notOfficeEstablishment, SUM(IF(a.`isSignboard`='Yes',1,0)) yesOfficeSignboard, SUM(IF(a.isSignboard='No',1,0)) noOfficeSignboard, SUM(IF(a.isSignboard='NOT Applicable',1,0)) notOfficeSignboard, SUM(IF(a.`isFileSystem`='Yes',1,0)) yesfilingSystem, SUM(IF(a.isFileSystem='No',1,0)) nofilingSystem, SUM(IF(a.isFileSystem='NOT Applicable',1,0)) notfilingSystem, SUM(IF(a.`isRequirementFulfilled`='Yes',1,0)) yesRequirementFullfilled, SUM(IF(a.isRequirementFulfilled='No',1,0)) noRequirementFullfilled, SUM(IF(a.isRequirementFulfilled='NOT Applicable',1,0)) notRequirementFullfilled 
                FROM t_monitoring_office a
                left join t_monitoring_profile_details b on a.inspectionId
                
                WHERE

                        IF(''='".$cdbNo."',1=1,b.cdbNo='".$cdbNo."') AND
                        IF(''='".$dzongkhag."',1=1,b.dzongkhagName='".$dzongkhag."') AND
                        IF(''='".$fromDate."',1=1,b.createdOn BETWEEN '".$fromDate."' AND '".$toDate."') AND
                        IF(''='".$classification."',1=1,b.classificationName='".$classification."')";
            }
            else if($reportType == "ACTION_TAKEN")
            {
                $query = "SELECT 
                        b.CDBNo,b.NameOfFirm,b.RegisteredAddress,a.Remarks,DATE_FORMAT(a.ActionDate,'%d/%m/%Y') actionDate,
                        IF(a.ActionTaken=1,'Downgrade',IF(a.ActionTaken=2,'Suspension',IF(ActionTaken='3','Warning','')))actionTaken
                        
                        FROM
                        crpmonitoringoffice a  
                        LEFT JOIN crpcontractorfinal b 
                        ON a.CrpContractorFinalId = b.Id 
                        LEFT JOIN crpcontractorworkclassificationfinal c 
                        ON b.Id = c.CrpContractorFinalId AND c.CmnApprovedClassificationId = (
                        SELECT d.CmnApprovedClassificationId FROM crpcontractorworkclassificationfinal d 
                        LEFT JOIN cmncontractorclassification e ON d.CmnApprovedClassificationId=e.Id
                        WHERE d.CrpContractorFinalId = c.Id
                        
                        ORDER BY e.Priority DESC LIMIT 1
                        )
                        WHERE 
                        IF(''='".$cdbNo."',1=1,b.CDBNo='".$cdbNo."') AND
                        IF(''='".$dzongkhag."',1=1,b.CmnDzongkhagId='".$dzongkhag."') AND
                        IF(''='".$fromDate."',1=1,a.ActionDate BETWEEN '".$fromDate."' AND '".$toDate."') AND
                        IF(''='".$classification."',1=1,c.CmnApprovedClassificationId='".$classification."')";
            }
            else if($reportType == "ACTION_TAKEN_SUMMARY")
            {
                $query = "SELECT 
                        IF(a.ActionTaken=1,'Downgrade',IF(a.ActionTaken=2,'Suspension',IF(ActionTaken='3','Warning','')))actionTaken,
                        COUNT(*) rowCount
                        FROM
                        crpmonitoringoffice a 
                        LEFT JOIN crpcontractorfinal b 
                        ON a.CrpContractorFinalId = b.Id 
                        LEFT JOIN crpcontractorworkclassificationfinal c 
                        ON b.Id = c.CrpContractorFinalId AND c.CmnApprovedClassificationId = (
                        SELECT d.CmnApprovedClassificationId FROM crpcontractorworkclassificationfinal d 
                        LEFT JOIN cmncontractorclassification e ON d.CmnApprovedClassificationId=e.Id
                        WHERE d.CrpContractorFinalId = c.Id
                        ORDER BY e.Priority DESC LIMIT 1
                        )
                        WHERE 
                        a.ActionTaken IS NOT NULL  AND 
                        IF(''='".$cdbNo."',1=1,b.CDBNo='".$cdbNo."') AND
                        IF(''='".$dzongkhag."',1=1,b.CmnDzongkhagId='".$dzongkhag."') AND
                        IF(''='".$fromDate."',1=1,a.ActionDate BETWEEN '".$fromDate."' AND '".$toDate."') AND
                        IF(''='".$classification."',1=1,c.CmnApprovedClassificationId='".$classification."')
                        GROUP BY a.ActionTaken 
                        ";
            }
        }
        else if($inspectionType=='PUBLIC_SITE')
        {
            if($reportType == "LOCATION" ||  $reportType == "DETAIL_REPORT" )
            {
                $query = "SELECT a.inspectionId id,b.nameOfFirm,b.cdbNo,b.nameOfWork,b.dzongkhagName , b.procuringAgency,b.actualStartDate,b.actualEndDate proposed_completion_date,b.awardedAmount contract_price,a.procuringSiteEngineerName site_engineer_name,
                a.`procuringSiteEngineerName`, a.`hasAps`, a.`hasApsRemarks`, a.`hasOffice`, a.`hasOfficeRemarks`, a.`hasSignboard`, a.`hasSignboardRemarks`, a.`hasStore`, a.`hasStoreRemarks`, a.`hasAccommodation`, a.`hasAccommodationRemarks`, a.`hasSanitary`, a.`hasSanitaryRemarks`, a.`hasPotableWater`, a.`hasPotableWaterRemarks`, a.`hasWorkInsurance`, a.`hasWorkInsuranceRemarks`, a.`hasLabourInsurance`, a.`hasLabourInsuranceRemarks`, a.`hasThirdPartyInsurance`, a.`hasThirdPartyInsuranceRemarks`, a.`hasWorkPlan`, a.`hasWorkPlanRemarks`, a.`workStatus`, a.`workStatusRemarks`, a.`hasBoq`, a.`hasBoqRemarks`, a.`hasContractConditions`, a.`hasContractConditionsRemarks`, a.`hasApprovedDrawing`, a.`hasApprovedDrawingRemarks`, a.`hasWorkSpecifications`, a.`hasWorkSpecificationsRemarks`, a.`hasSiteOrderBook`,a. `hasSiteOrderBookRemarks`, a.`hasHindranceRegister`, a.`hasHindranceRegisterRemarks`, a.`hasMbs`, a.`hasMbsRemarks`, a.`hasCms`, a.`hasCmsRemarks`, a.`hasJournal`, a.`hasJournalRemarks`, a.`hasQualityAssurancePlan`, a.`hasQualityAssurancePlanRemarks`, a.`hasQualityControlPlan`, a.`hasQualityControlPlanRemarks`, a.`hasTestReport`, `hasTestReportRemarks`, a.`hasLocalMaterials`, a.`hasLocalMaterialsRemarks`, a.`hasSpecializedTrade`, a.`hasSpecializedTradeRemarks`, a.`hasOhsIncharge`, `hasOhsInchargeRemarks`, a.`hasSafetySignages`, a.`hasSafetySignagesRemarks`, a.`hasFireExtinguishingEquipment`, `hasFireExtinguishingEquipmentRemarks`, a.`hasFirstAidBox`, a.`hasFirstAidBoxRemarks`, a.`hasPeripheralBoundary`, `hasPeripheralBoundaryRemarks`, `hasProperElectricalInstallations`, a.`hasProperElectricalInstallationsRemarks`, `hasCctv`, `hasCctvRemarks`, `nationalLabours`, `foreignLabours`, `internNo`, `vtiGraduates`, `latitude`, `longitude`, `localityName`, `representativeName`, `representativeCidNo`, `representativeContactNo`, `siteEngineerName`
               
               
               FROM t_monitoring_public_site_inspection a 
               left join t_monitoring_profile_details b on a.inspectionId=b.inspectionId
               WHERE IF('".$cdbNo."'='',1=1,b.cdbNo ='".$cdbNo."') 
               AND IF( '".$dzongkhag."'='',1=1,b.dzongkhagName =  '".$dzongkhag."') 
               AND IF(''='".$fromDate."',1=1, a.createdOn BETWEEN '".$fromDate."' AND '".$toDate."')";

            } 
            else if ($reportType == "SUMMARY" )
            {
                 $query = " 

                 SELECT
                 SUM(IF(a.hasAps = 'Yes', 1, 0))yesaps_maintained_by_gov_eng,
                 SUM(IF(a.hasAps = 'No', 1, 0)) noaps_maintained_by_gov_eng,
                 SUM(IF(a.hasAps = 'NOT Applicable', 1, 0)) notaps_maintained_by_gov_eng,
                 SUM(IF(a.hasOffice = 'Yes', 1, 0))yeshasOffice,
                 SUM(IF(a.hasOffice = 'No', 1, 0)) nohasOffice,
                 SUM(IF(a.hasOffice = 'NOT Applicable', 1, 0)) nothasOffice,
                 SUM(IF(a.hasSignboard = 'Yes', 1, 0))yeshasSignboard,
                 SUM(IF(a.hasSignboard = 'No', 1, 0)) nohasSignboard,
                 SUM(IF(a.hasSignboard = 'NOT Applicable', 1, 0)) nothasSignboard,
                 SUM(IF(a.hasOffice = 'NOT Applicable', 1, 0)) nothasOffice,
                 SUM(IF(a.hasStore = 'Yes', 1, 0))yeshasStore,
                 SUM(IF(a.hasStore = 'No', 1, 0)) nohasStore,
                 SUM(IF(a.hasStore = 'NOT Applicable', 1, 0)) nothasStore,
                 SUM(IF(a.hasAccommodation = 'Yes', 1, 0))yeshasAccommodation,
                 SUM(IF(a.hasAccommodation = 'No', 1, 0)) nohasAccommodation,
                 SUM(IF(a.hasAccommodation = 'NOT Applicable', 1, 0)) nothasAccommodation,
                 SUM(IF(a.hasSanitary = 'Yes', 1, 0))yeshasSanitary,
                 SUM(IF(a.hasSanitary = 'No', 1, 0)) nohasSanitary,
                 SUM(IF(a.hasSanitary = 'NOT Applicable', 1, 0)) nothasSanitary,
                 SUM(IF(a.hasPotableWater = 'Yes', 1, 0))yeshasPotableWater,
                 SUM(IF(a.hasPotableWater = 'No', 1, 0)) nohasPotableWater,
                 SUM(IF(a.hasPotableWater = 'NOT Applicable', 1, 0)) nothasPotableWater,
                 SUM(IF(a.hasWorkInsurance = 'Yes', 1, 0))yeshasWorkInsurance,
                 SUM(IF(a.hasWorkInsurance = 'No', 1, 0)) nohasWorkInsurance,
                 SUM(IF(a.hasWorkInsurance = 'NOT Applicable', 1, 0)) nothasWorkInsurance,
                 SUM(IF(a.hasLabourInsurance = 'Yes', 1, 0))yeshasLabourInsurance,
                 SUM(IF(a.hasLabourInsurance = 'No', 1, 0)) nohasLabourInsurance,
                 SUM(IF(a.hasLabourInsurance = 'NOT Applicable', 1, 0)) nothasLabourInsurance,
                 SUM(IF(a.hasThirdPartyInsurance = 'Yes', 1, 0))yeshasThirdPartyInsurance,
                 SUM(IF(a.hasThirdPartyInsurance = 'No', 1, 0)) nohasThirdPartyInsurance,
                 SUM(IF(a.hasThirdPartyInsurance = 'NOT Applicable', 1, 0)) nothasThirdPartyInsurance,
                 SUM(IF(a.hasWorkPlan = 'Yes', 1, 0))yeshasWorkPlan,
                 SUM(IF(a.hasWorkPlan = 'No', 1, 0)) nohasWorkPlan,
                 SUM(IF(a.hasWorkPlan = 'NOT Applicable', 1, 0)) nothasWorkPlan,
                 SUM(IF(a.hasBoq = 'Yes', 1, 0))yeshasBoq,
                 SUM(IF(a.hasBoq = 'No', 1, 0)) nohasBoq,
                 SUM(IF(a.hasBoq = 'NOT Applicable', 1, 0)) nothasBoq,
                 SUM(IF(a.hasContractConditions = 'Yes', 1, 0))yeshasContractConditions,
                 SUM(IF(a.hasContractConditions = 'No', 1, 0)) nohasContractConditions,
                 SUM(IF(a.hasContractConditions = 'NOT Applicable', 1, 0)) nothasContractConditions,
                 SUM(IF(a.hasApprovedDrawing = 'Yes', 1, 0))yeshasApprovedDrawing,
                 SUM(IF(a.hasApprovedDrawing = 'No', 1, 0)) nohasApprovedDrawing,
                 SUM(IF(a.hasApprovedDrawing = 'NOT Applicable', 1, 0)) nothasApprovedDrawing,
                 SUM(IF(a.hasWorkSpecifications = 'Yes', 1, 0))yeshasWorkSpecifications,
                 SUM(IF(a.hasWorkSpecifications = 'No', 1, 0)) nohasWorkSpecifications,
                 SUM(IF(a.hasWorkSpecifications = 'NOT Applicable', 1, 0)) nothasWorkSpecifications,
                 SUM(IF(a.hasSiteOrderBook = 'Yes', 1, 0))yeshasSiteOrderBook,
                 SUM(IF(a.hasSiteOrderBook = 'No', 1, 0)) nohasSiteOrderBook,
                 SUM(IF(a.hasSiteOrderBook = 'NOT Applicable', 1, 0)) nothasSiteOrderBook,
                 SUM(IF(a.hasHindranceRegister = 'Yes', 1, 0))yeshasHindranceRegister,
                 SUM(IF(a.hasHindranceRegister = 'No', 1, 0)) nohasHindranceRegister,
                 SUM(IF(a.hasHindranceRegister = 'NOT Applicable', 1, 0)) nothasHindranceRegister,
                 SUM(IF(a.hasMbs = 'Yes', 1, 0))yeshasMbs,
                 SUM(IF(a.hasMbs = 'No', 1, 0)) nohasMbs,
                 SUM(IF(a.hasMbs = 'NOT Applicable', 1, 0)) nothasMbs,
                 SUM(IF(a.hasJournal = 'Yes', 1, 0))yeshasJournal,
                 SUM(IF(a.hasJournal = 'No', 1, 0)) nohasJournal,
                 SUM(IF(a.hasJournal = 'NOT Applicable', 1, 0)) nothasJournal,
                 SUM(IF(a.hasQualityAssurancePlan = 'Yes', 1, 0))yeshasQualityAssurancePlan,
                 SUM(IF(a.hasQualityAssurancePlan = 'No', 1, 0)) nohasQualityAssurancePlan,
                 SUM(IF(a.hasQualityAssurancePlan = 'NOT Applicable', 1, 0)) nothasQualityAssurancePlan,
                 SUM(IF(a.hasQualityControlPlan = 'Yes', 1, 0))yeshasQualityControlPlan,
                 SUM(IF(a.hasQualityControlPlan = 'No', 1, 0)) nohasQualityControlPlan,
                 SUM(IF(a.hasQualityControlPlan = 'NOT Applicable', 1, 0)) nothasQualityControlPlan,
                 SUM(IF(a.hasTestReport = 'Yes', 1, 0))yeshasTestReport,
                 SUM(IF(a.hasTestReport = 'No', 1, 0)) nohasTestReport,
                 SUM(IF(a.hasTestReport = 'NOT Applicable', 1, 0)) nothasTestReport,
                 SUM(IF(a.hasLocalMaterials = 'Yes', 1, 0))yeshasLocalMaterials,
                 SUM(IF(a.hasLocalMaterials = 'No', 1, 0)) nohasLocalMaterials,
                 SUM(IF(a.hasLocalMaterials = 'NOT Applicable', 1, 0)) nothasLocalMaterials,
                 SUM(IF(a.hasSpecializedTrade = 'Yes', 1, 0))yeshasSpecializedTrade,
                 SUM(IF(a.hasSpecializedTrade = 'No', 1, 0)) nohasSpecializedTrade,
                 SUM(IF(a.hasSpecializedTrade = 'NOT Applicable', 1, 0)) nothasSpecializedTrade,
                 SUM(IF(a.hasOhsIncharge = 'Yes', 1, 0))yeshasOhsIncharge,
                 SUM(IF(a.hasOhsIncharge = 'No', 1, 0)) nohasOhsIncharge,
                 SUM(IF(a.hasOhsIncharge = 'NOT Applicable', 1, 0)) nothasOhsIncharge,
                 SUM(IF(a.hasSafetySignages = 'Yes', 1, 0))yeshasSafetySignages,
                 SUM(IF(a.hasSafetySignages = 'No', 1, 0)) nohasSafetySignages,
                 SUM(IF(a.hasSafetySignages = 'NOT Applicable', 1, 0)) nothasSafetySignages,
                 SUM(IF(a.hasFireExtinguishingEquipment = 'Yes', 1, 0))yeshasFireExtinguishingEquipment,
                 SUM(IF(a.hasFireExtinguishingEquipment = 'No', 1, 0)) nohasFireExtinguishingEquipment,
                 SUM(IF(a.hasFireExtinguishingEquipment = 'NOT Applicable', 1, 0)) nothasFireExtinguishingEquipment,
                 SUM(IF(a.hasFirstAidBox = 'Yes', 1, 0))yeshasFirstAidBox,
                 SUM(IF(a.hasFirstAidBox = 'No', 1, 0)) nohasFirstAidBox,
                 SUM(IF(a.hasFirstAidBox = 'NOT Applicable', 1, 0)) nothasFirstAidBox,
                 SUM(IF(a.hasPeripheralBoundary = 'Yes', 1, 0))yeshasPeripheralBoundary,
                 SUM(IF(a.hasPeripheralBoundary = 'No', 1, 0)) nohasPeripheralBoundary,
                 SUM(IF(a.hasPeripheralBoundary = 'NOT Applicable', 1, 0)) nothasPeripheralBoundary,
                 SUM(IF(a.hasProperElectricalInstallations = 'Yes', 1, 0))yeshasProperElectricalInstallations,
                 SUM(IF(a.hasProperElectricalInstallations = 'No', 1, 0)) nohasProperElectricalInstallations,
                 SUM(IF(a.hasProperElectricalInstallations = 'NOT Applicable', 1, 0)) nothasProperElectricalInstallations 
              FROM
                 t_monitoring_public_site_inspection a 
                 left join
                    t_monitoring_profile_details b 
                    on a.inspectionId = b.inspectionId 
              WHERE
                IF(''='".$cdbNo."',1=1,b.cdbNo = '".$cdbNo."') 
                AND IF(''='".$dzongkhag."',1=1,b.dzongkhagName = '".$dzongkhag."')
                AND IF(''='".$fromDate."',1=1, a.createdOn BETWEEN '".$fromDate."' AND '".$toDate."')";
            }

        }
        else if($inspectionType=='PRIVATE_SITE')
        {
            if($reportType == "LOCATION" ||  $reportType == "DETAIL_REPORT" )
            {
                $query ="
                SELECT  b.classificationName,b.validity,b.nameOfFirm,b.cdbNo,b.email,b.telNo,b.mobNo,b.permAddress,b.nameOfWork,b.dzongkhagName , b.procuringAgency,b.actualStartDate,b.actualEndDate proposed_completion_date,b.awardedAmount contract_price,
                a.`inspectionId`, a.`cdbId`, `contractExtension`, `contractExtensionStartDate`, `contractExtensionEndDate`, `contractValue`, `hasOffice`, `hasOfficeRemarks`, `hasSignboard`, `hasSignboardRemarks`, `hasStore`, `hasStoreRemarks`, `hasDocument`, `hasDocumentRemarks`, `isInsured`, `isInsuredRemarks`, `hasLabourInsurance`, `hasLabourInsuranceRemarks`, `hasThirdPartyInsurance`, `hasThirdPartyInsuranceRemarks`, `hasWorkPlan`, `hasWorkPlanRemarks`, `workStatus`, `workStatusRemarks`, `hasCamp`, `hasPowerSupply`, `hasSanitary`, `hasWaterSupply`, `hasOhsIncharge`, `hasOhsInchargeRemarks`, `hasFireExtinguishingEquipment`, `hasFireExtinguishingEquipmentRemarks`, `hasFirstAidBox`, `hasFirstAidBoxRemarks`, `nationalLabours`, `foreignLabours`, `internNo`, `vtiGraduates`, `hasSpecializeTrade`, `hasSpecializeTradeRemarks`, `latitude`, `longitude`, `localityName`, `representativeName`, `representativeCidNo`, `representativeContactNo`, `remarks`,a.`createdOn`
                FROM t_monitoring_private_site_inspection a left join t_monitoring_profile_details b on a.inspectionId=b.inspectionId  
               WHERE IF('".$cdbNo."'='',1=1,b.cdbNo ='".$cdbNo."') 
               AND IF( '".$dzongkhag."'='',1=1,b.dzongkhagName =  '".$dzongkhag."') 
               AND IF(''='".$fromDate."',1=1, a.createdOn BETWEEN '".$fromDate."' AND '".$toDate."')";
                }
                else if ($reportType == "SUMMARY" )
                {
                 $query = "SELECT SUM(IF(a.contractExtension='Yes',1,0)) yesContractorExtension, SUM(IF(a.contractExtension='No',1,0)) noContractorExtension, SUM(IF(a.contractExtension='Not Applicable',1,0)) notContractorExtension, SUM(IF(a.hasOffice='Yes',1,0)) yeshasOffice, SUM(IF(a.hasOffice='No',1,0)) nohasOffice, SUM(IF(a.hasOffice='Not Applicable',1,0)) nothasOffice, SUM(IF(a.hasSignboard='Yes',1,0)) yeshasSignboard, SUM(IF(a.hasSignboard='No',1,0)) nohasSignboard, SUM(IF(a.hasSignboard='Not Applicable',1,0)) nothasSignboard, SUM(IF(a.hasStore='Yes',1,0)) yeshasStore, SUM(IF(a.hasStore='No',1,0)) nohasStore, SUM(IF(a.hasStore='Not Applicable',1,0)) nothasStore, SUM(IF(a.hasDocument='Yes',1,0)) yeshasDocument, SUM(IF(a.hasDocument='No',1,0)) nohasDocument, SUM(IF(a.hasDocument='Not Applicable',1,0)) nothasDocument, SUM(IF(a.hasLabourInsurance='Yes',1,0)) yeshasLabourInsurance, SUM(IF(a.hasLabourInsurance='No',1,0)) nohasLabourInsurance, SUM(IF(a.hasLabourInsurance='Not Applicable',1,0)) nothasLabourInsurance, SUM(IF(a.hasThirdPartyInsurance='Yes',1,0)) yeshasThirdPartyInsurance, SUM(IF(a.hasThirdPartyInsurance='No',1,0)) nohasThirdPartyInsurance, SUM(IF(a.hasThirdPartyInsurance='Not Applicable',1,0)) nothasThirdPartyInsurance, SUM(IF(a.hasWorkPlan='Yes',1,0)) yeshasWorkPlan, SUM(IF(a.hasWorkPlan='No',1,0)) nohasWorkPlan, SUM(IF(a.hasWorkPlan='Not Applicable',1,0)) nothasWorkPlan, SUM(IF(a.hasCamp='Yes',1,0)) yeshasCamp, SUM(IF(a.hasCamp='No',1,0)) nohasCamp, SUM(IF(a.hasCamp='Not Applicable',1,0)) ntohasCamp, SUM(IF(a.hasPowerSupply='Yes',1,0)) yeshasPowerSupply, SUM(IF(a.hasPowerSupply='No',1,0)) nohasPowerSupply, SUM(IF(a.hasPowerSupply='Not Applicable',1,0)) nothasPowerSupply, SUM(IF(a.hasSanitary='Yes',1,0)) yeshasSanitary, SUM(IF(a.hasSanitary='No',1,0)) nohasSanitary, SUM(IF(a.hasSanitary='Not Applicable',1,0)) nothasSanitary, SUM(IF(a.hasWaterSupply='Yes',1,0)) yeshasWaterSupply, SUM(IF(a.hasWaterSupply='No',1,0)) nohasWaterSupply, SUM(IF(a.hasWaterSupply='Not Applicable',1,0)) nothasWaterSupply, SUM(IF(a.hasOhsIncharge='Yes',1,0)) yeshasOhsIncharge, SUM(IF(a.hasOhsIncharge='No',1,0)) nohasOhsIncharge, SUM(IF(a.hasOhsIncharge='Not Applicable',1,0)) nothasOhsIncharge, SUM(IF(a.hasFireExtinguishingEquipment='Yes',1,0)) yeshasFireExtinguishingEquipment, SUM(IF(a.hasFireExtinguishingEquipment='No',1,0)) nohasFireExtinguishingEquipment, SUM(IF(a.hasFireExtinguishingEquipment='Not Applicable',1,0)) nothasFireExtinguishingEquipment, SUM(IF(a.hasFirstAidBox='Yes',1,0)) yeshasFirstAidBox, SUM(IF(a.hasFirstAidBox='No',1,0)) nohasFirstAidBox, SUM(IF(a.hasFirstAidBox='Not Applicable',1,0)) nothasFirstAidBox, SUM(IF(a.hasSpecializeTrade='Yes',1,0)) yeshasSpecializeTrade, SUM(IF(a.hasSpecializeTrade='No',1,0)) nohasSpecializeTrade, SUM(IF(a.hasSpecializeTrade='Not Applicable',1,0)) nothasSpecializeTrade FROM t_monitoring_private_site_inspection a 
                            left join t_monitoring_profile_details b on a.inspectionId=b.inspectionId
                            where
                            IF(''='".$cdbNo."',1=1,b.cdbNo = '".$cdbNo."') 
                                AND IF(''='".$dzongkhag."',1=1,b.dzongkhagName = '".$dzongkhag."')
                                AND IF(''='".$fromDate."',1=1, a.createdOn BETWEEN '".$fromDate."' AND '".$toDate."')";

            }
        }
        $contractorsList = DB::select($query);
        return View::make('report.officeMonitoringReport')
        ->with('contractorsList',$contractorsList)
        ->with('reportType',$reportType)
        ->with('inspectionType',$inspectionType);
    }     
}
