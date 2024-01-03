<?php
class EzotinReports extends ReportController{
	public function getApplicantsDueForPayment(){
        $date = Input::has('Date')?$this->convertDate(Input::get('Date')):date('Y-m-d');
  
        $contractorService = DB::select("SELECT DISTINCT `T1`.`ReferenceNo` AS `ReferenceNo`, T1.NameOfFirm, T1.CDBNo, T1.MobileNo, T1.ApplicationDate, SUM(K.`ApprovedAmount`) AS  ApprovedAmount, SUM(DISTINCT T.`TotalAmount`) AS TotalAmount, GROUP_CONCAT(DISTINCT `a`.`Name` SEPARATOR ',') AS `ServiceType` FROM crpcontractor T1 LEFT JOIN `crpcontractorappliedservice` `h` ON `h`.`CrpContractorId` = `T1`.`CrpContractorId` LEFT JOIN `crpservice` `a` ON `a`.`Id` = `h`.`CmnServiceTypeId` LEFT JOIN crpcontractorregistrationpayment K ON K.`CrpContractorFinalId` = T1.`Id` LEFT JOIN crpcontractorservicepayment T ON T.`CrpContractorId` = T1.`Id` WHERE T1.`CmnApplicationRegistrationStatusId` = '6195664d-c3c5-11e4-af9f-080027dcfac6' GROUP BY `T1`.`ReferenceNo` ORDER BY T1.`ApplicationDate` DESC ");
        
        $consultantService = DB::select("SELECT `T1`.`ReferenceNo` AS `ReferenceNo`, T1.NameOfFirm, T1.CDBNo, T1.MobileNo, T1.ApplicationDate, K.`ApprovedAmount`, SUM(DISTINCT T.`TotalAmount`) AS TotalAmount, GROUP_CONCAT(DISTINCT `a`.`Name` SEPARATOR ',') AS `ServiceType` FROM crpconsultant T1 LEFT JOIN `crpconsultantappliedservice` `h` ON `h`.`CrpConsultantId` = `T1`.`CrpConsultantId` LEFT JOIN `crpservice` `a` ON `a`.`Id` = `h`.`CmnServiceTypeId` LEFT JOIN crpconsultantregistrationpayment K ON K.`CrpConsultantFinalId` = T1.`Id` LEFT JOIN crpconsultantservicepayment T ON T.`CrpConsultantId` = T1.`Id` WHERE T1.`CmnApplicationRegistrationStatusId` LIKE '6195664d-c3c5-11e4-af9f-080027dcfac6' GROUP BY `T1`.`ReferenceNo` ORDER BY T1.`ApplicationDate` DESC");

        $specializedfirmService = DB::select("SELECT `T1`.`ReferenceNo` AS `ReferenceNo`, T1.NameOfFirm, T1.SPNo, T1.MobileNo, T1.ApplicationDate, K.`ApprovedAmount`, SUM(DISTINCT T.`TotalAmount`) AS TotalAmount, GROUP_CONCAT(DISTINCT `a`.`Name` SEPARATOR ',') AS `ServiceType` FROM crpspecializedfirm T1 LEFT JOIN `crpspecializedfirmappliedservice` `h` ON `h`.`CrpSpecializedTradeId` = `T1`.`CrpSpecializedTradeId` LEFT JOIN `crpservice` `a` ON `a`.`Id` = `h`.`CmnServiceTypeId` LEFT JOIN crpspecializedfirmregistrationpayment K ON K.`CrpSpecializedTradeFinalId` = T1.`Id` LEFT JOIN crpspecializedfirmservicepayment T ON T.`CrpSpecializedTradeId` = T1.`Id` WHERE T1.`CmnApplicationRegistrationStatusId` LIKE '6195664d-c3c5-11e4-af9f-080027dcfac6' GROUP BY `T1`.`ReferenceNo` ORDER BY T1.`ApplicationDate` DESC");
       
        $specializedtradeService = DB::select("SELECT `T1`.`ReferenceNo` AS `ReferenceNo`, T1.Name, T1.SPNo, T1.MobileNo, T1.ApplicationDate, K.`ApprovedAmount`, SUM(DISTINCT T.`TotalAmount`) AS TotalAmount, GROUP_CONCAT(DISTINCT `a`.`Name` SEPARATOR ',') AS `ServiceType` FROM crpspecializedtrade T1 LEFT JOIN `crpspecializedtradeappliedservice` `h` ON `h`.`CrpSpecializedTradeId` = `T1`.`CrpSpecializedTradeId` LEFT JOIN `crpservice` `a` ON `a`.`Id` = `h`.`CmnServiceTypeId` LEFT JOIN crpspecializedtraderegistrationpayment K ON K.`CrpSpecializedTradeFinalId` = T1.`Id` LEFT JOIN crpspecializedtradeservicepayment T ON T.`CrpSpecializedTradeId` = T1.`Id` WHERE T1.`CmnApplicationRegistrationStatusId` LIKE '6195664d-c3c5-11e4-af9f-080027dcfac6' GROUP BY `T1`.`ReferenceNo` ORDER BY T1.`ApplicationDate` DESC ");

        $architectService = DB::select("SELECT `T1`.`ReferenceNo` AS `ReferenceNo`, T1.Name, T1.ARNo, T1.MobileNo, T1.ApplicationDate,(CASE WHEN h.`CmnServiceTypeId` = '55a922e1-cbbf-11e4-83fb-080027dcfac6' THEN '2000' ELSE NULL END) AS Amount, SUM(DISTINCT T.`TotalAmount`) AS TotalAmount, GROUP_CONCAT(DISTINCT `a`.`Name` SEPARATOR ',') AS `ServiceType` FROM crparchitect T1 LEFT JOIN `crparchitectappliedservice` `h` ON `h`.`CrpArchitectId` = `T1`.`CrpArchitectId` LEFT JOIN `crpservice` `a` ON `a`.`Id` = `h`.`CmnServiceTypeId` LEFT JOIN `crparchitectregistrationpayment` K ON K.`CrpArchitectFinalId` = T1.`Id` LEFT JOIN `crparchitectservicepayment` T ON T.`CrpArchitectId` = T1.`Id` WHERE T1.`CmnApplicationRegistrationStatusId` = '6195664d-c3c5-11e4-af9f-080027dcfac6' GROUP BY `T1`.`ReferenceNo` ORDER BY T1.`ApplicationDate` DESC  ");

        $engineerService = DB::select("SELECT `T1`.`ReferenceNo` AS `ReferenceNo`, T1.Name, T1.CDBNo, T1.MobileNo, T1.ApplicationDate, (CASE WHEN h.`CmnServiceTypeId` = '55a922e1-cbbf-11e4-83fb-080027dcfac6' THEN '2000' ELSE NULL END) AS Amount, SUM(DISTINCT T.`TotalAmount`) AS TotalAmount, GROUP_CONCAT(DISTINCT `a`.`Name` SEPARATOR ',') AS `ServiceType` FROM crpengineer T1 LEFT JOIN `crpengineerappliedservice` `h` ON `h`.`CrpEngineerId` = `T1`.`CrpEngineerId` LEFT JOIN `crpservice` `a` ON `a`.`Id` = `h`.`CmnServiceTypeId` LEFT JOIN `crpengineerregistrationpayment` K ON K.`CrpEngineerFinalId` = T1.`Id` LEFT JOIN `crpengineerservicepayment` T ON T.`CrpEngineerId` = T1.`Id` WHERE T1.`CmnApplicationRegistrationStatusId` LIKE '6195664d-c3c5-11e4-af9f-080027dcfac6' GROUP BY `T1`.`ReferenceNo` ORDER BY T1.`ApplicationDate` DESC ");

        $surveyService = DB::select("SELECT `T1`.`ReferenceNo` AS `ReferenceNo`, T1.Name, T1.ARNo, T1.MobileNo, T1.ApplicationDate, (CASE WHEN h.`CmnServiceTypeId` = '55a922e1-cbbf-11e4-83fb-080027dcfac6' THEN '2000' ELSE NULL END) AS Amount, SUM(DISTINCT T.`TotalAmount`) AS TotalAmount, GROUP_CONCAT(DISTINCT `a`.`Name` SEPARATOR ',') AS `ServiceType` FROM crpsurvey T1 LEFT JOIN `crpsurveyappliedservice` `h` ON `h`.`CrpSurveyId` = `T1`.`CrpSurveyId` LEFT JOIN `crpservice` `a` ON `a`.`Id` = `h`.`CmnServiceTypeId` LEFT JOIN `crpsurveyregistrationpayment` K ON K.`CrpSurveyFinalId` = T1.`Id` LEFT JOIN `crpsurveyservicepayment` T ON T.`CrpSurveyId` = T1.`Id` WHERE T1.`CmnApplicationRegistrationStatusId` LIKE '6195664d-c3c5-11e4-af9f-080027dcfac6' GROUP BY `T1`.`ReferenceNo` ORDER BY T1.`ApplicationDate` DESC ");
        
	      $certifiedbuilderService = DB::select("SELECT `T1`.`ReferenceNo` AS `ReferenceNo`,T1.NameOfFirm,T1.CDBNo,T1.MobileNo,T1.ApplicationDate,K.`ApprovedAmount`,SUM(DISTINCT T.`TotalAmount`) AS TotalAmount,GROUP_CONCAT(DISTINCT `a`.`Name` SEPARATOR ',') AS `ServiceType` FROM crpcertifiedbuilder T1 LEFT JOIN `crpcertifiedbuilderappliedservice` `h` ON `h`.`CrpCertifiedBuilderId` = `T1`.`CrpCertifiedBuilderId` LEFT JOIN `crpservice` `a` ON `a`.`Id` = `h`.`CmnServiceTypeId` LEFT JOIN `crpcertifiedbuilderregistrationpayment` K ON K.`CrpCertifiedBuilderFinalId` = T1.`Id` LEFT JOIN `crpcertifiedbuilderservicepayment` T ON T.`CrpCertifiedBuilderId` = T1.`Id` WHERE T1.`CmnApplicationRegistrationStatusId` LIKE '6195664d-c3c5-11e4-af9f-080027dcfac6' GROUP BY `T1`.`ReferenceNo` ORDER BY T1.`ApplicationDate` DESC ");

        if(Input::has('export')){
               
                $export = Input::get('export');
                if($export == 'excel'){
                    Excel::create('List of Applications with Pending Payment  ', function($excel) use ($contractorService,$consultantService,$specializedfirmService,$engineerService ,$specializedtradeService,$surveyService,$architectService,$certifiedbuilderService) {
    
                        $excel->sheet('Sheet 1', function($sheet) use ($contractorService,$consultantService,$specializedfirmService,$engineerService ,$specializedtradeService,$surveyService,$architectService,$certifiedbuilderService) {
                            $sheet->setOrientation('landscape');
                            $sheet->setFitToPage(1);
                            $sheet->loadView('reportexcel.unpaidapplications')
                            
                ->with('contractorService',$contractorService)
           
                ->with('consultantService',$consultantService)

                ->with('specializedfirmService',$specializedfirmService)

                ->with('specializedtradeService',$specializedtradeService)

                ->with('engineerService',$engineerService)

                ->with('surveyService',$surveyService)
            
                ->with('architectService',$architectService)

		->with('certifiedbuilderService',$certifiedbuilderService);
                        
                                
    
                        });
    
                    })->export('xlsx');
                }
            }




        return View::make('report.unpaidapplications')
                ->with('date',$date)
           
                ->with('contractorService',$contractorService)
           
                ->with('consultantService',$consultantService)

                ->with('specializedfirmService',$specializedfirmService)

                ->with('specializedtradeService',$specializedtradeService)

                ->with('engineerService',$engineerService)

                ->with('surveyService',$surveyService)
            
                ->with('architectService',$architectService)

		->with('certifiedbuilderService',$certifiedbuilderService);
    }

 public function applicantsCancelledAfterNonPayment(){
        $type = Input::get('Type'); //1 - Contractor, 2 - Consultant, 3 - Architect, 4 - SP
        $cdbNo = Input::get('CDBNo');

        $parameters = array();
        $parametersForPrint = array();
        $query = "SELECT 
        CASE
          WHEN T1.TypeCode = 1 
          THEN COALESCE(T2.CDBNo, T3.CDBNo) 
          ELSE 
          CASE
            WHEN T1.TypeCode = 2 
            THEN COALESCE(T4.CDBNo, T5.CDBNo) 
            ELSE 
            CASE
              WHEN T1.TypeCode = 5 
              THEN COALESCE(T10.CDBNo, T11.CDBNo) 
              ELSE 
              CASE
                WHEN T1.TypeCode = 3 
                THEN COALESCE(T6.ARNo, T7.ARNo) 
                ELSE COALESCE(T8.SPNo, T9.SPNo) 
              END 
            END 
          END 
        END AS CDBNo,
        CASE
          WHEN T1.TypeCode = 1 
          THEN T2.CmnApplicationRegistrationStatusId 
          ELSE 
          CASE
            WHEN T1.TypeCode = 2 
            THEN T4.CmnApplicationRegistrationStatusId 
            ELSE 
            CASE
              WHEN T1.TypeCode = 5 
              THEN T10.CmnApplicationRegistrationStatusId 
              ELSE 
              CASE
                WHEN T1.TypeCode = 3 
                THEN T6.CmnApplicationRegistrationStatusId 
                ELSE T8.CmnApplicationRegistrationStatusId 
              END 
            END 
          END 
        END AS StatusId,
        CASE
          WHEN T1.TypeCode = 1 
          THEN T2.CDBNo 
          ELSE 
          CASE
            WHEN T1.TypeCode = 2 
            THEN T4.CDBNo 
            ELSE 
            CASE
              WHEN T1.TypeCode = 5 
              THEN T10.CDBNo 
              ELSE 
              CASE
                WHEN T1.TypeCode = 3 
                THEN T6.ARNo 
                ELSE T8.SPNo 
              END 
            END 
          END 
        END AS CDBNo,
        CASE
          WHEN T1.TypeCode = 1 
          THEN T2.PaymentApprovedDate 
          ELSE 
          CASE
            WHEN T1.TypeCode = 2 
            THEN T4.PaymentApprovedDate 
            ELSE 
            CASE
              WHEN T1.TypeCode = 5 
              THEN T10.PaymentApprovedDate 
              ELSE 
              CASE
                WHEN T1.TypeCode = 3 
                THEN T6.PaymentApprovedDate 
                ELSE T8.PaymentApprovedDate 
              END 
            END 
          END 
        END AS PaymentApprovedDate,
        CASE
          WHEN T1.TypeCode = 1 
          THEN T2.ReferenceNo 
          ELSE 
          CASE
            WHEN T1.TypeCode = 2 
            THEN T4.ReferenceNo 
            ELSE 
            CASE
              WHEN T1.TypeCode = 5 
              THEN T10.ReferenceNo 
              ELSE 
              CASE
                WHEN T1.TypeCode = 3 
                THEN T6.ReferenceNo 
                ELSE T8.ReferenceNo 
              END 
            END 
          END 
        END AS ReferenceNo,
        CASE
          WHEN T1.TypeCode = 1 
          THEN T2.NameOfFirm 
          ELSE 
          CASE
            WHEN T1.TypeCode = 2 
            THEN T4.NameOfFirm 
            ELSE 
            CASE
              WHEN T1.TypeCode = 5 
              THEN T10.Name 
              ELSE 
              CASE
                WHEN T1.TypeCode = 3 
                THEN T6.Name 
                ELSE T8.Name 
              END 
            END 
          END 
        END AS Applicant,
        T1.DateOfNotification,
        T1.TypeCode 
      FROM
        crpapplicantpaymentnotice T1 
        LEFT JOIN (
            crpcontractor T2 
            LEFT JOIN crpcontractorfinal T3 
              ON T3.Id = T2.CrpContractorId
          ) 
          ON T2.Id = T1.ApplicantId 
        LEFT JOIN (
            crpconsultant T4 
            LEFT JOIN crpconsultantfinal T5 
              ON T5.Id = T4.CrpConsultantId
          ) 
          ON T4.Id = T1.ApplicantId 
        LEFT JOIN (
            crpengineer T10 
            LEFT JOIN crpengineerfinal T11 
              ON T11.Id = T10.CrpEngineerId
          ) 
          ON T4.Id = T1.ApplicantId 
        LEFT JOIN (
            crparchitect T6 
            LEFT JOIN crparchitectfinal T7 
              ON T7.Id = T6.CrpArchitectId
          ) 
          ON T6.Id = T1.ApplicantId 
        LEFT JOIN (
            crpspecializedtrade T8 
            LEFT JOIN crpspecializedtradefinal T9 
              ON T9.Id = T8.CrpSpecializedTradeId
          ) 
          ON T8.Id = T1.ApplicantId 
      WHERE 
        CASE
          WHEN T1.TypeCode = 1 
          THEN T2.CmnApplicationRegistrationStatusId 
          ELSE 
          CASE
            WHEN T1.TypeCode = 2 
            THEN T4.CmnApplicationRegistrationStatusId 
            ELSE 
            CASE
              WHEN T1.TypeCode = 5 
              THEN T10.CmnApplicationRegistrationStatusId 
              ELSE 
              CASE
                WHEN T1.TypeCode = 3 
                THEN T6.CmnApplicationRegistrationStatusId 
                ELSE T8.CmnApplicationRegistrationStatusId 
              END 
            END 
          END 
        END = ?";
        array_push($parameters,CONST_CMN_APPLICATIONREGISTRATIONSTATUS_REJECTED);
        if((bool)$type){
            $query.=" and T1.TypeCode = ?";
            array_push($parameters,(int)$type);
            $parametersForPrint['Type'] = ($type == 1)?"Contractor":(($type==2)?"Consultant":(($type==3)?"Architect":(($type==5)?"Engineer":"Specialized Trade")));
        }
        if((bool)$cdbNo){
            $query.=" and case when T1.TypeCode = 1 then T2.CDBNo = ? else case when T1.TypeCode = 2 then T4.CDBNo = ? else case when T1.TypeCode = 5 then T10.CDBNo = ? else case when T1.TypeCode = 3 then T6.ARNo = ? else T8.SPNo = ? end end end";
            array_push($parameters,$cdbNo);
            array_push($parameters,$cdbNo);
            array_push($parameters,$cdbNo);
            array_push($parameters,$cdbNo);
            $parametersForPrint['CDBNo'] = $cdbNo;
        }

        /*PAGINATION*/
        $pageNo = Input::has('page')?Input::get('page'):1;
        $pagination = $this->pagination($query,$parameters,25,$pageNo);
        $limitOffsetAppend = $pagination['LimitAppend'];
        $noOfPages = $pagination['NoOfPages'];
        $start = $pagination['Start'];
        /*END PAGINATION*/

        $reportData = DB::select("$query order by DateOfNotification DESC$limitOffsetAppend",$parameters);

        if(Input::get('export') == 'excel'){
            $reportData = DB::select("$query order by DateOfNotification DESC",$parameters);
            Excel::create('Applications cancelled after non payment', function($excel) use ($reportData,$parametersForPrint) {
                $excel->sheet('Sheet 1', function($sheet) use ($reportData,$parametersForPrint) {
                    $sheet->setOrientation('landscape');
                    $sheet->setFitToPage(1);
                    $sheet->setPaperSize(1);
                    $sheet->loadView('reportexcel.applicantscancelledafternonpayment')
                        ->with('parametersForPrint',$parametersForPrint)
                        ->with('reportData',$reportData);

                });

            })->export('xlsx');
        }else{
            $reportData = DB::select("$query order by DateOfNotification DESC$limitOffsetAppend",$parameters);
        }

        return View::make('report.applicantscancelledafternonpayment')
                    ->with('start',$start)
                    ->with('noOfPages',$noOfPages)
                    ->with('reportData',$reportData);

    }

public function worksMasterReport(){
        $hasParams = false;
        $procuringAgencies = ProcuringAgencyModel::procuringAgencyHardList()->get(array('Name as ProcuringAgency'));
        $categories = ContractorWorkCategoryModel::contractorProjectCategory()->get(array('Code as ProjectCategory'));
        $classes = ContractorClassificationModel::classification()->get(array('Code as Class'));
        $dzongkhags = DzongkhagModel::dzongkhag()->get(array('NameEn as Dzongkhag'));
        $procuringAgencyId = Input::get('ProcuringAgency');
        $classificationId = Input::get('Classification');
        $categoryId = Input::get('ProjectCategory');
        $dzongkhagId = Input::get('Dzongkhag');
        $cdbNo = Input::get('CDBNo');
        $limit = Input::get('Limit');
        $curYear = date('Y');
        $tenYearsAgo = (int)$curYear - 10;
        $class = Input::get("Class");
        $parameters = array();
        $parametersForPrint = array();
        $query = "select WorkId,CDBNo,Contractor,Classification,case ReferenceNo when 3003 then year(WorkCompletionDate) else year(WorkStartDate) end as Year,ProcuringAgency as Agency,WorkStartDate,CompletionDateFinal,NameOfWork,ProjectCategory as Category,BidAmount as AwardedAmount,coalesce(ContractPriceFinal,FinalAmount) as FinalAmount,Dzongkhag,ReferenceNo,WorkStatus as Status,(coalesce(OntimeCompletionScore,0) + coalesce(QualityOfExecutionScore,0)) as APS,Remarks,LDImposed,LDNoOfDays,LDAmount,Hindrance,HindranceNoOfDays from viewcontractorstrackrecords where 1=1";
        if((bool)$categoryId){
            $parametersForPrint['Category'] = $categoryId;
            $hasParams = true;
            $query.=" and ProjectCategory = ?";
            array_push($parameters,$categoryId);
        }
        if((bool)$procuringAgencyId){
            $parametersForPrint['Agency'] =  $procuringAgencyId;
            $hasParams = true;
            $query.=" and ProcuringAgency = ?";
            array_push($parameters,$procuringAgencyId);
        }
        if((bool)$cdbNo){
            $parametersForPrint['CDBNo'] = $cdbNo;
            $hasParams = true;
            $query.=" and CDBNo = ?";
            array_push($parameters,$cdbNo);
        }
        if((bool)$dzongkhagId){
            $parametersForPrint['Dzongkhag'] = $dzongkhagId;
            $hasParams = true;
            $query.=" and Dzongkhag = ?";
            array_push($parameters,$dzongkhagId);
        }
        if((bool)$class){
            $parametersForPrint['Class'] = $class;
            $hasParams = true;
            $query.=" and Classification = ?";
            array_push($parameters,$class);
        }
        $query.=" and WorkCompletionDate >= '$tenYearsAgo-01-01' order by case ReferenceNo when 3003 then year(WorkCompletionDate) else year(WorkStartDate) end,ProcuringAgency";
        if($hasParams){
            $reportData = DB::select($query, $parameters);
        }else{
            $reportData = array();
        }
        if(Input::has('export')){
            $export = Input::get('export');
            $dzongkhag = $dzongkhagId;
            $procuringAgency = $procuringAgencyId;
            $category = $categoryId;
            $cdbNo = $cdbNo;
            if($export == 'excel'){
                Excel::create("Master Report", function($excel) use ($reportData,$parametersForPrint,$cdbNo,$limit,$dzongkhag,$procuringAgency,$category) {
                    $excel->sheet('Sheet 1', function($sheet) use ($reportData,$parametersForPrint,$cdbNo,$limit,$dzongkhag,$procuringAgency,$category) {
                        $sheet->setOrientation('landscape');
                        $sheet->setFitToPage(1);
                        $sheet->setPaperSize(11);
                        $sheet->loadView('reportexcel.masterreport')
                            ->with('limit',$limit)
                            ->with('parametersForPrint',$parametersForPrint)
                            ->with('dzongkhag',$dzongkhag)
                            ->with('procuringAgency',$procuringAgency)
                            ->with('category',$category)
                            ->with('cdbNo',$cdbNo)
                            ->with('workDetails',$reportData);

                    });

                })->export('xlsx');
            }
        }
        return View::make('report.masterreport')
            ->with('workDetails',$reportData)
            ->with('parametersForPrint',$parametersForPrint)
            ->with('procuringAgencies',$procuringAgencies)
            ->with('categories',$categories)
            ->with('classes',$classes)
            ->with('hasParams',$hasParams)
            ->with('dzongkhags',$dzongkhags);
    }

public function auditDroppedMemos(){
        $fromDate = Input::get('FromDate');
        $toDate = Input::get('ToDate');
        $cdbNo = Input::get('CDBNo');
        $type = Input::get('Type'); //1 for contractor, 2 for consultant

        $parameters = array();
        $parametersForPrint = array();

        $query = "select concat(T4.FullName,' (',T4.username,')') as Dropper,T1.Agency, T1.AuditedPeriod,T1.AIN,T1.ParoNo,T1.AuditObservation,T1.DroppedDate, case when T1.Type = 1 then concat(T2.NameOfFirm,' (Contractor)') else concat(T3.NameOfFirm,' (Consultant)') end as NameOfFirm, case when T1.Type = 1 then T2.CDBNo else T3.CDBNo end as CDBNo, T1.Remarks from crpcontractorauditclearance T1 left join crpcontractorfinal T2 on T2.Id = T1.CrpContractorConsultantId left join crpconsultantfinal T3 on T3.Id = T1.CrpContractorConsultantId join sysuser T4 on T4.Id = T1.SysDroppedByUserId where coalesce(T1.Dropped,0)=1";

        if((bool)$fromDate){
            $query.=" and T1.DroppedDate >= ?";
            array_push($parameters,$this->convertDate($fromDate));
            $parametersForPrint['From Date'] = $this->convertDate($fromDate);
        }
        if((bool)$toDate){
            $query.=" and T1.DroppedDate <= ?";
            array_push($parameters,$this->convertDate($toDate));
            $parametersForPrint['To Date'] = $this->convertDate($toDate);
        }
        if((bool)$cdbNo){
            $query.=" and (T2.CDBNo = ? or T3.CDBNo = ?)";
            array_push($parameters,$cdbNo);
            array_push($parameters,$cdbNo);
            $parametersForPrint['CDBNo'] = $cdbNo;
        }
        if((bool)$type){
            $query.=" and T1.Type = ?";
            array_push($parameters,$type);
            if($type == 1){
                $parametersForPrint['Type'] = "Contractors";
            }else{
                $parametersForPrint['Type'] = "Consultants";
            }
        }

        /*PAGINATION*/
        $pageNo = Input::has('page')?Input::get('page'):1;
        $pagination = $this->pagination($query,$parameters,10,$pageNo);
        $limitOffsetAppend = $pagination['LimitAppend'];
        $noOfPages = $pagination['NoOfPages'];
        $start = $pagination['Start'];
        /*END PAGINATION*/



        if(Input::get('export') == 'excel'){
            $reportData = DB::select($query." order by DroppedDate desc",$parameters);
            Excel::create('Audit Memo Report (Dropped)', function($excel) use ($reportData,$parametersForPrint) {
                $excel->sheet('Sheet 1', function($sheet) use ($reportData,$parametersForPrint) {
                    $sheet->setOrientation('landscape');
                    $sheet->setFitToPage(1);
                    $sheet->setPaperSize(1);
                    $sheet->loadView('reportexcel.auditdroppedmemos')
                        ->with('parametersForPrint',$parametersForPrint)
                        ->with('reportData',$reportData);

                });

            })->export('xlsx');
        }else{
            $reportData = DB::select($query." order by DroppedDate desc".$limitOffsetAppend,$parameters);
        }
        return View::make('report.auditdroppedmemos')
                    ->with('start',$start)
                    ->with('noOfPages',$noOfPages)
                    ->with('parametersForPrint',$parametersForPrint)
                    ->with('reportData',$reportData);
    }

public function workOpportunityReport(){
        $fromDate = Input::has('FromDate')?$this->convertDate(Input::get('FromDate')):false;
        $toDate = Input::has('ToDate')?$this->convertDate(Input::get('ToDate')):false;
        $show = false;
        if(Input::has('x')){
            if(!(bool)$fromDate && !(bool)$toDate){
                return Redirect::to('ezotinrpt/etoolworkopportunityreport')->with('customerrormessage','Please select dates to view report');
            }else{
                $show =true;
            }
        }

        $totalNoOfWorks = DB::table('etltender as T1')
                            ->join('etltenderbiddercontractor as T2','T2.EtlTenderId','=','T1.Id')
                            ->whereNotNull('T2.AwardedAmount')
                            ->whereRaw("coalesce(T1.TenderSource,0) = 1")
                            ->whereRaw("case when T1.CmnWorkExecutionStatusId = ? then (T2.ActualStartDate >= ? and T2.ActualStartDate <= ?) else (T1.CommencementDateFinal >= ? and T1.CommencementDateFinal <= ?) end",array(CONST_CMN_WORKEXECUTIONSTATUS_AWARDED,$fromDate,$toDate,$fromDate,$toDate))
                            ->whereIn('T1.CmnWorkExecutionStatusId',array(CONST_CMN_WORKEXECUTIONSTATUS_AWARDED,CONST_CMN_WORKEXECUTIONSTATUS_COMPLETED))
                            ->get(array(DB::raw("distinct T1.Id")));

        $worksByClass = DB::table('etltender as T1')
                        ->join('cmncontractorclassification as A','T1.CmnContractorClassificationId','=','A.Id')
                        ->join('etltenderbiddercontractor as T2','T2.EtlTenderId','=','T1.Id')
                        ->whereNotNull('T2.AwardedAmount')
                        ->whereRaw("coalesce(T1.TenderSource,0) = 1")
                        ->whereRaw("case when T1.CmnWorkExecutionStatusId = ? then (T2.ActualStartDate >= ? and T2.ActualStartDate <= ?) else (T1.CommencementDateFinal >= ? and T1.CommencementDateFinal <= ?) end",array(CONST_CMN_WORKEXECUTIONSTATUS_AWARDED,$fromDate,$toDate,$fromDate,$toDate))
                        ->whereIn('T1.CmnWorkExecutionStatusId',array(CONST_CMN_WORKEXECUTIONSTATUS_AWARDED,CONST_CMN_WORKEXECUTIONSTATUS_COMPLETED))
                        ->groupBy('A.Id')
                        ->get(array('A.Code',DB::raw("count(T1.Id) as WorkCount")));

        $designations = DB::table('etltender as T1')
            ->join('etltenderbiddercontractor as T2','T2.EtlTenderId','=','T1.Id')
            ->join('etlcriteriahumanresource as T3','T3.EtlTenderId','=','T1.Id')
            ->join('cmnlistitem as T4','T4.Id','=','T3.CmnDesignationId')
            ->whereNotNull('T2.AwardedAmount')
            ->whereRaw("coalesce(T1.TenderSource,0) = 1")
            ->whereRaw("case when T1.CmnWorkExecutionStatusId = ? then (T2.ActualStartDate >= ? and T2.ActualStartDate <= ?) else (T1.CommencementDateFinal >= ? and T1.CommencementDateFinal <= ?) end",array(CONST_CMN_WORKEXECUTIONSTATUS_AWARDED,$fromDate,$toDate,$fromDate,$toDate))
            ->whereIn('T1.CmnWorkExecutionStatusId',array(CONST_CMN_WORKEXECUTIONSTATUS_AWARDED,CONST_CMN_WORKEXECUTIONSTATUS_COMPLETED))
            ->orderBy("T4.Name")
            ->get(array(DB::raw("distinct T3.CmnDesignationId"),"T4.Name as Designation"));

        foreach($designations as $designation):
            $designationId = $designation->CmnDesignationId;
            $countForDesignation = DB::table('etlcriteriahumanresource as T1')
                                    ->join('etltender as T2','T2.Id','=','T1.EtlTenderId')
                                    ->join('etltenderbiddercontractor as T3','T3.EtlTenderId','=','T2.Id')
                                    ->whereNotNull('T3.AwardedAmount')
                                    ->whereRaw("coalesce(T2.TenderSource,0) = 1")
                                    ->whereRaw("case when T2.CmnWorkExecutionStatusId = ? then (T3.ActualStartDate >= ? and T3.ActualStartDate <= ?) else (T2.CommencementDateFinal >= ? and T2.CommencementDateFinal <= ?) end",array(CONST_CMN_WORKEXECUTIONSTATUS_AWARDED,$fromDate,$toDate,$fromDate,$toDate))
                                    ->whereIn('T2.CmnWorkExecutionStatusId',array(CONST_CMN_WORKEXECUTIONSTATUS_AWARDED,CONST_CMN_WORKEXECUTIONSTATUS_COMPLETED))
                                    ->where('T1.CmnDesignationId',$designationId)
                                    ->whereRaw("T1.Points = (select Max(A.Points) from etlcriteriahumanresource A where A.EtlTenderId = T1.EtlTenderId and A.EtlTierId = T1.EtlTierId)")
                                    ->count();
            $designation->HRCount = $countForDesignation;
            unset($designation->CmnDesignationId);
        endforeach;

//        echo "<PRE>"; dd($designations);
        return View::make('report.workopportunityreport')
                ->with('show',$show)
                ->with('worksByClass',$worksByClass)
                ->with('designations',$designations)
                ->with('totalNoOfWorks',$totalNoOfWorks);
    }
 public function getServicesAvailed(){
        $fromDate = Input::has('FromDate')?$this->convertDate(Input::get('FromDate')):'2016-06-01';
        if($fromDate<'2016-06-01'){
            $fromDate = '2016-06-01';
        }
        $toDate = Input::has('ToDate')?$this->convertDate(Input::get('ToDate')):'--';

        $contractorServices = $consultantServices = array("Renewal"=>CONST_SERVICETYPE_RENEWAL,"Update General Info"=>CONST_SERVICETYPE_GENERALINFORMATION,"Change of Location"=>CONST_SERVICETYPE_CHANGELOCATION,"Change of Owner"=>CONST_SERVICETYPE_CHANGEOWNER,"Category/Class Change"=>CONST_SERVICETYPE_CHANGEOFCATEGORYCLASSIFICATION,"Update HR"=>CONST_SERVICETYPE_UPDATEHUMANRESOURCE,"Update Equipment"=>CONST_SERVICETYPE_UPDATEEQUIPMENT,"Late Fee"=>CONST_SERVICETYPE_LATEFEE,"Change of Firm Name"=>CONST_SERVICETYPE_CHANGEOFFIRMNAME,"Incorporation"=>CONST_SERVICETYPE_INCORPORATION);
        $architectServices = $engineerServices = array("Renewal"=>CONST_SERVICETYPE_RENEWAL,"Late Fee"=>CONST_SERVICETYPE_LATEFEE);
        $spServices = array("Renewal"=>CONST_SERVICETYPE_RENEWAL,"Late Fee"=>CONST_SERVICETYPE_LATEFEE,"Category/Class Change"=>CONST_SERVICETYPE_CHANGEOFCATEGORYCLASSIFICATION);
        //CONTRACTOR
        $contractorNewRegistrations = DB::table('crpcontractor as T1')
                                        ->join('crpcontractorfinal as T2','T2.Id','=','T1.Id')
                                        ->whereNull('T1.CrpContractorId')
                                        ->whereRaw("coalesce(T1.RegistrationStatus,0)=1")
                                        ->whereRaw("T1.ApplicationDate >= ?",array($fromDate))
            ->whereRaw("case when '$toDate' = '--' then 1 else T1.ApplicationDate <= ? end",array($toDate))
                                        ->count(); //REGISTRATION

        $contractorServiceV = array();
        foreach($contractorServices as $contractorServiceName => $contractorService):
            $count= DB::table('crpcontractor as T1')
                    ->join('crpcontractorappliedservice as T2','T2.CrpContractorId','=','T1.Id')
                    ->whereNotNull('T1.CrpContractorId')
                    ->whereRaw("coalesce(T1.RegistrationStatus,0)=1")
                    ->where('T2.CmnServiceTypeId',$contractorService)
                    ->whereRaw("T1.ApplicationDate >= ?",array($fromDate))
            ->whereRaw("case when '$toDate' = '--' then 1 else T1.ApplicationDate <= ? end",array($toDate))
            ->count();
        $contractorServiceV[$contractorService]['Count'] = $count;
        $contractorServiceV[$contractorService]['Name'] = $contractorServiceName;
        endforeach; //SERVICES
        //CONSULTANT
        $consultantNewRegistrations = DB::table('crpconsultant as T1')
            ->join('crpconsultantfinal as T2','T2.Id','=','T1.Id')
            ->whereNull('T1.CrpConsultantId')
            ->where('T1.CmnApplicationRegistrationStatusId',CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED)
            ->whereRaw("T1.ApplicationDate >= ?",array($fromDate))
            ->whereRaw("case when '$toDate' = '--' then 1 else T1.ApplicationDate <= ? end",array($toDate))
            ->count();

        $consultantServiceV = array();
        foreach($consultantServices as $consultantServiceName => $consultantService):
            $count= DB::table('crpconsultant as T1')
                ->join('crpconsultantappliedservice as T2','T2.CrpConsultantId','=','T1.Id')
                ->whereNotNull('T1.CrpConsultantId')
                ->whereRaw("coalesce(T1.RegistrationStatus,0)=1")
                ->where('T2.CmnServiceTypeId',$consultantService)
                ->whereRaw("T1.ApplicationDate >= ?",array($fromDate))
                ->whereRaw("case when '$toDate' = '--' then 1 else T1.ApplicationDate <= ? end",array($toDate))
                ->count();
            $consultantServiceV[$consultantService]['Count'] = $count;
            $consultantServiceV[$consultantService]['Name'] = $consultantServiceName;
        endforeach; //SERVICES

        //ARCHITECT
        $architectNewRegistrations = DB::table('crparchitect as T1')
            ->join('crparchitectfinal as T2','T2.Id','=','T1.Id')
            ->whereNull('T1.CrpArchitectId')
            ->where('T1.CmnApplicationRegistrationStatusId',CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED)
            ->whereRaw("T1.ApplicationDate >= ?",array($fromDate))
            ->whereRaw("case when '$toDate' = '--' then 1 else T1.ApplicationDate <= ? end",array($toDate))
            ->count();

        $architectServiceV = array();
        foreach($architectServices as $architectServiceName => $architectService):
            $count= DB::table('crparchitect as T1')
                ->join('crparchitectappliedservice as T2','T2.CrpArchitectId','=','T1.Id')
                ->whereNotNull('T1.CrpArchitectId')
                ->whereRaw("coalesce(T1.RegistrationStatus,0)=1")
                ->where('T2.CmnServiceTypeId',$architectService)
                ->whereRaw("T1.ApplicationDate >= ?",array($fromDate))
                ->whereRaw("case when '$toDate' = '--' then 1 else T1.ApplicationDate <= ? end",array($toDate))
                ->count();
            $architectServiceV[$architectService]['Count'] = $count;
            $architectServiceV[$architectService]['Name'] = $architectServiceName;
        endforeach; //SERVICES
        //ENGINEER
        $engineerNewRegistrations = DB::table('crpengineer as T1')
            ->join('crpengineerfinal as T2','T2.Id','=','T1.Id')
            ->whereNull('T1.CrpEngineerId')
            ->where('T1.CmnApplicationRegistrationStatusId',CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED)
            ->whereRaw("T1.ApplicationDate >= ?",array($fromDate))
            ->whereRaw("case when '$toDate' = '--' then 1 else T1.ApplicationDate <= ? end",array($toDate))
            ->count();

        $engineerServiceV = array();
        foreach($engineerServices as $engineerServiceName => $engineerService):
            $count= DB::table('crpengineer as T1')
                ->join('crpengineerappliedservice as T2','T2.CrpEngineerId','=','T1.Id')
                ->whereNotNull('T1.CrpEngineerId')
                ->whereRaw("coalesce(T1.RegistrationStatus,0)=1")
                ->where('T2.CmnServiceTypeId',$engineerService)
                ->whereRaw("T1.ApplicationDate >= ?",array($fromDate))
                ->whereRaw("case when '$toDate' = '--' then 1 else T1.ApplicationDate <= ? end",array($toDate))
                ->count();
            $engineerServiceV[$engineerService]['Count'] = $count;
            $engineerServiceV[$engineerService]['Name'] = $engineerServiceName;
        endforeach; //SERVICES

        //SP
        $spNewRegistrations = DB::table('crpspecializedtrade as T1')
            ->join('crpspecializedtradefinal as T2','T2.Id','=','T1.Id')
            ->whereNull('T1.CrpSpecializedTradeId')
            ->where('T1.CmnApplicationRegistrationStatusId',CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED)
            ->whereRaw("T1.ApplicationDate >= ?",array($fromDate))
            ->whereRaw("case when '$toDate' = '--' then 1 else T1.ApplicationDate <= ? end",array($toDate))
            ->count();

        $spServiceV = array();
        foreach($spServices as $spServiceName => $spService):
            $count= DB::table('crpspecializedtrade as T1')
                ->join('crpspecializedtradeappliedservice as T2','T2.CrpSpecializedTradeId','=','T1.Id')
                ->whereNotNull('T1.CrpSpecializedTradeId')
                ->whereRaw("coalesce(T1.RegistrationStatus,0)=1")
                ->where('T2.CmnServiceTypeId',$spService)
                ->whereRaw("T1.ApplicationDate >= ?",array($fromDate))
                ->whereRaw("case when '$toDate' = '--' then 1 else T1.ApplicationDate <= ? end",array($toDate))
                ->count();
            $spServiceV[$spService]['Count'] = $count;
            $spServiceV[$spService]['Name'] = $spServiceName;
        endforeach; //SERVICES
        return View::make('report.servicesavailed')
                    ->with('contractorNewRegistrations',$contractorNewRegistrations)
                    ->with('contractorServices',$contractorServices)
                    ->with('contractorServiceV',$contractorServiceV)
                    ->with('consultantNewRegistrations',$consultantNewRegistrations)
                    ->with('consultantServiceV',$consultantServiceV)
                    ->with('consultantServices',$consultantServices)
                    ->with('architectNewRegistrations',$architectNewRegistrations)
                    ->with('architectServices',$architectServices)
                    ->with('architectServiceV',$architectServiceV)
                    ->with('engineerNewRegistrations',$engineerNewRegistrations)
                    ->with('engineerServices',$engineerServices)
                    ->with('engineerServiceV',$engineerServiceV)
                    ->with('spNewRegistrations',$spNewRegistrations)
                    ->with('spServices',$spServices)
                    ->with('spServiceV',$spServiceV);

    }
}