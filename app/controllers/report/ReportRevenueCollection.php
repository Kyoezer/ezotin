<?php

class ReportRevenueCollection extends BaseController
{
    public function getIndex(){
        $reportAppend = "Summary";
        $fromDate = Input::get('FromDate');
        $toDate = Input::get('ToDate');


        $serviceTypes = DB::table('crpservice')->orderBy('Name')->get(array('Id','Name'));

        $query1 = "SELECT 'Contractor' as Type,1 as TypeCode,'New Registration' as ServiceType,'".CONST_SERVICETYPE_NEW."' as ServiceId,sum(T1.ApprovedAmount) as Amount FROM `crpcontractorregistrationpayment` T1 join crpcontractorfinal T2 on T2.Id = T1.CrpContractorFinalId join crpcontractor T3 on T2.Id = T3.Id where 1 AND T3.PaymentReceiptNo is NOT NULL";
                       
       
        if((bool)$fromDate){
            $fromDate = $this->convertDate($fromDate);
            $query1.=" and CAST(T1.CreatedOn as Date) >= '$fromDate'";
        }
        if((bool)$toDate){
            $toDate = $this->convertDate($toDate);
            $query1.=" and CAST(T1.CreatedOn as Date) <= '$toDate'";
        }

            $query2=" union ALL SELECT 'Contractor' as Type,1 as TypeCode,T2.Name as ServiceType,T2.Id as ServiceId,  sum(T1.TotalAmount) as Amount from crpcontractorservicepayment T1  join (crpcontractor B LEFT join crpcontractorfinal A on A.Id = B.CrpContractorId) on B.Id = T1.CrpContractorId join crpservice T2 on T2.Id = T1.CmnServiceTypeId where 1 AND B.PaymentReceiptNo is NOT NULL AND B.PaymentReceiptDate is not null";
        if((bool)$fromDate){
            $fromDate = $this->convertDate($fromDate);
            $query2.=" and CAST(T1.CreatedOn as Date) >= '$fromDate'";
        }
        if((bool)$toDate){
            $toDate = $this->convertDate($toDate);
            $query2.=" and CAST(T1.CreatedOn as Date) <= '$toDate'";
        }
        $query2.=" group by T1.CmnServiceTypeId having Amount >0";
//CONSULTANT
      
        $query3 = " union ALL SELECT 'Consultant' as Type,2 as TypeCode,'New Registration' as ServiceType,'".CONST_SERVICETYPE_NEW."' as ServiceId,sum(T1.Amount) as Amount FROM `crpconsultantregistrationpayment` T1 join crpconsultantfinal T2 on T2.Id = T1.CrpConsultantFinalId join crpconsultant T3 on T2.Id = T3.Id where 1 AND T3.PaymentReceiptNo is NOT NULL";

        if((bool)$fromDate){
            $fromDate = $this->convertDate($fromDate);
            $query3.=" and CAST(T1.CreatedOn as Date) >= '$fromDate'";
        }
        if((bool)$toDate){
            $toDate = $this->convertDate($toDate);
            $query3.=" and CAST(T1.CreatedOn as Date) <= '$toDate'";
        }

        $query4=" union ALL SELECT 'Consultant' as Type,2 as TypeCode,T2.Name as ServiceType,T2.Id as ServiceId,  sum(T1.TotalAmount) as Amount from crpconsultantservicepayment T1  join (crpconsultant B LEFT join crpconsultantfinal A on A.Id = B.CrpConsultantId) on B.Id = T1.CrpConsultantId join crpservice T2 on T2.Id = T1.CmnServiceTypeId where 1 AND B.PaymentReceiptNo is NOT NULL AND B.PaymentReceiptDate is not null";
        if((bool)$fromDate){
            $fromDate = $this->convertDate($fromDate);
            $query4.=" and CAST(T1.CreatedOn as Date) >= '$fromDate'";
        }
        if((bool)$toDate){
            $toDate = $this->convertDate($toDate);
            $query4.=" and CAST(T1.CreatedOn as Date) <= '$toDate'";
        }
        $query4.=" group by T1.CmnServiceTypeId having Amount >0";



        //FOR ARCHITECT
        $query5=" union ALL SELECT 'Architect' as Type,3 as TypeCode,T2.Name as ServiceType,T2.Id as ServiceId, sum(T1.TotalAmount) as Amount from crparchitectservicepayment T1 join (crparchitect B LEFT join crparchitectfinal A on A.Id = B.CrpArchitectId) on B.Id = T1.CrpArchitectId join crpservice T2 on T2.Id = T1.CmnServiceTypeId where 1 AND B.PaymentReceiptNo is NOT NULL AND B.PaymentReceiptDate is not null";
        if((bool)$fromDate){
            $fromDate = $this->convertDate($fromDate);
            $query5.=" and CAST(T1.CreatedOn as Date) >= '$fromDate'";
        }
        if((bool)$toDate){
            $toDate = $this->convertDate($toDate);
            $query5.=" and CAST(T1.CreatedOn as Date) <= '$toDate'";
        }
        $query5.=" group by T1.CmnServiceTypeId having Amount >0";

        $query6=" union ALL SELECT 'Architect' as Type,3 as TypeCode,'New Registration' as ServiceType,'".CONST_SERVICETYPE_NEW."' as ServiceId,sum(T1.Amount) as Amount FROM `crparchitectregistrationpayment` T1 join crparchitectfinal T2 on T2.Id = T1.CrpArchitectFinalId join crparchitect T3 on T3.Id = T2.Id where 1 AND T3.PaymentReceiptNo is NOT NULL";
        if((bool)$fromDate){
            $fromDate = $this->convertDate($fromDate);
            $query6.=" and CAST(T1.CreatedOn as Date) >= '$fromDate'";
        }
        if((bool)$toDate){
            $toDate = $this->convertDate($toDate);
            $query6.=" and CAST(T1.CreatedOn as Date) <= '$toDate'";
        }
      

              //FOR SPECIALIZED FIRM
              $query7=" union ALL SELECT 'Specialized Firm' as Type,4 as TypeCode,T2.Name as ServiceType,T2.Id as ServiceId,  sum(T1.TotalAmount) as Amount from crpspecializedfirmservicepayment T1  join (crpspecializedfirm B LEFT join crpspecializedfirmfinal A on A.Id = B.CrpSpecializedTradeId) on B.Id = T1.CrpSpecializedTradeId join crpservice T2 on T2.Id = T1.CmnServiceTypeId where 1 AND B.PaymentReceiptNo is NOT NULL AND B.PaymentReceiptDate is not null";
              if((bool)$fromDate){
                  $fromDate = $this->convertDate($fromDate);
                  $query7.=" and CAST(T1.CreatedOn as Date) >= '$fromDate'";
              }
              if((bool)$toDate){
                  $toDate = $this->convertDate($toDate);
                  $query7.=" and CAST(T1.CreatedOn as Date) <= '$toDate'";
              }
              $query7.=" group by T1.CmnServiceTypeId having Amount >0";
      
              $query8=" union ALL SELECT 'Specialized Firm' as Type,4 as TypeCode,'New Registration' as ServiceType,'".CONST_SERVICETYPE_NEW."' as ServiceId,sum(T1.ApprovedAmount) as Amount FROM `crpspecializedfirmregistrationpayment` T1 join crpspecializedfirmfinal T2 on T2.Id = T1.CrpSpecializedTradeFinalId join crpspecializedfirm T3 on T2.Id = T3.Id where 1 AND T3.PaymentReceiptNo is NOT NULL";
              if((bool)$fromDate){
                  $fromDate = $this->convertDate($fromDate);
                  $query8.=" and CAST(T1.CreatedOn as Date) >= '$fromDate'";
              }
              if((bool)$toDate){
                  $toDate = $this->convertDate($toDate);
                  $query8.=" and CAST(T1.CreatedOn as Date) <= '$toDate'";
              }
       

       //Surveyor
       $query9=" union ALL SELECT 'Surveyor' as Type,5 as TypeCode,T2.Name as ServiceType,T2.Id as ServiceId,  sum(T1.TotalAmount) as Amount from crpsurveyservicepayment T1 join (crpsurvey B LEFT join crpsurveyfinal A on A.Id = B.CrpSurveyId) on B.Id = T1.CrpSurveyId join crpservice T2 on T2.Id = T1.CmnServiceTypeId where 1 AND B.PaymentReceiptNo is NOT NULL AND B.PaymentReceiptDate is not null";
       if((bool)$fromDate){
           $fromDate = $this->convertDate($fromDate);
           $query9.=" and CAST(T1.CreatedOn as Date) >= '$fromDate'";
       }
       if((bool)$toDate){
           $toDate = $this->convertDate($toDate);
           $query9.=" and CAST(T1.CreatedOn as Date) <= '$toDate'";
       }
       $query9.=" group by T1.CmnServiceTypeId having Amount >0";

       $query10=" union ALL SELECT 'Surveyor' as Type,5 as TypeCode,'New Registration' as ServiceType,'".CONST_SERVICETYPE_NEW."' as ServiceId,sum(T1.Amount) as Amount FROM `crpsurveyregistrationpayment` T1 join crpsurveyfinal T2 on T2.Id = T1.CrpSurveyFinalId join crpsurvey T3 on T3.Id = T2.Id where 1  AND T3.PaymentReceiptNo is NOT NULL";
       if((bool)$fromDate){
           $fromDate = $this->convertDate($fromDate);
           $query10.=" and CAST(T1.CreatedOn as Date) >= '$fromDate'";
       }
       if((bool)$toDate){
           $toDate = $this->convertDate($toDate);
           $query10.=" and CAST(T1.CreatedOn as Date) <= '$toDate'";
       }


       //Engineer
       $query11=" union ALL SELECT 'Engineer' as Type,6 as TypeCode,T2.Name as ServiceType,T2.Id as ServiceId,  sum(T1.TotalAmount) as Amount from crpengineerservicepayment T1 join (crpengineer B LEFT join crpengineerfinal A on A.Id = B.CrpEngineerId) on B.Id = T1.CrpEngineerId join crpservice T2 on T2.Id = T1.CmnServiceTypeId where 1 AND B.PaymentReceiptNo is NOT NULL AND B.PaymentReceiptDate is not null";
       if((bool)$fromDate){
           $fromDate = $this->convertDate($fromDate);
           $query11.=" and CAST(T1.CreatedOn as Date) >= '$fromDate'";
       }
       if((bool)$toDate){
           $toDate = $this->convertDate($toDate);
           $query11.=" and CAST(T1.CreatedOn as Date) <= '$toDate'";
       }
       $query11.=" group by T1.CmnServiceTypeId having Amount >0";

       $query12=" union ALL SELECT 'Engineer' as Type,6 as TypeCode,'New Registration' as ServiceType,'".CONST_SERVICETYPE_NEW."' as ServiceId,sum(T1.Amount) as Amount FROM `crpengineerregistrationpayment` T1 join crpengineerfinal T2 on T2.Id = T1.CrpEngineerFinalId join crpengineer T3 on T3.Id = T2.Id where 1 AND T3.PaymentReceiptDate is NOT NULL";
       if((bool)$fromDate){
           $fromDate = $this->convertDate($fromDate);
           $query12.=" and CAST(T1.CreatedOn as Date) >= '$fromDate'";
       }
       if((bool)$toDate){
           $toDate = $this->convertDate($toDate);
           $query12.=" and CAST(T1.CreatedOn as Date) <= '$toDate'";
       }

              //SPECIALIZED TRADE
              $query13=" union ALL SELECT 'Specialized Trade' as Type,7 as TypeCode,T2.Name as ServiceType,T2.Id as ServiceId,  sum(T1.TotalAmount) as Amount from crpspecializedtradeservicepayment T1 join (crpspecializedtrade B LEFT join crpspecializedtradefinal A on A.Id = B.CrpSpecializedTradeId) on B.Id = T1.CrpSpecializedTradeId join crpservice T2 on T2.Id = T1.CmnServiceTypeId where 1 AND B.PaymentReceiptNo is NOT NULL AND B.PaymentReceiptDate is not null";
              if((bool)$fromDate){
                  $fromDate = $this->convertDate($fromDate);
                  $query13.=" and CAST(T1.CreatedOn as Date) >= '$fromDate'";
              }
              if((bool)$toDate){
                  $toDate = $this->convertDate($toDate);
                  $query13.=" and CAST(T1.CreatedOn as Date) <= '$toDate'";
              }
              $query13.=" group by T1.CmnServiceTypeId having Amount >0";
       
              $query14=" union ALL SELECT 'Specialized Trade' as Type,7 as TypeCode,'New Registration' as ServiceType,'".CONST_SERVICETYPE_NEW."' as ServiceId,sum(T1.Amount) as Amount FROM `crpspecializedtraderegistrationpayment` T1 join crpspecializedtradefinal T2 on T2.Id = T1.CrpSpecializedTradeFinalId join crpspecializedtrade T3 on T3.Id = T2.Id where 1 AND T3.PaymentReceiptNo is NOT NULL";
              if((bool)$fromDate){
                  $fromDate = $this->convertDate($fromDate);
                  $query14.=" and CAST(T1.CreatedOn as Date) >= '$fromDate'";
              }
              if((bool)$toDate){
                  $toDate = $this->convertDate($toDate);
                  $query14.=" and CAST(T1.CreatedOn as Date) <= '$toDate'";
              }

        $query = "$query1$query2$query3$query4$query5$query6$query7$query8$query9$query10$query11$query12$query13$query14  order by Type,ServiceType";
        $reportData = DB::select($query);

        return View::make("report.revenuecollection")
            ->with('reportAppend',$reportAppend)
                    ->with('summary',1)
                    ->with('serviceTypes',$serviceTypes)
                    ->with('reportData',$reportData);
    }
    public function getDetailed(){
        $reportAppend = "Detailed";
        $type = Input::get('Type');
        $serviceId = Input::get('ServiceId');
        $receiptNo = Input::get('ReceiptNo');

        $fromDate = Input::get('FromDate');
        $toDate = Input::get('ToDate');


        $serviceTypes = DB::table('crpservice')->orderBy('Name')->get(array('Id','Name'));

        $query1 = "SELECT distinct PaymentReceiptNo,'Contractor' as Type,concat(T3.NameOfFirm,' (',T3.CDBNo,')') as Name,CAST(T3.PaymentReceiptDate as Date) as PaymentDate,PaymentReceiptNo,1 as TypeCode,'New Registration' as ServiceType,'distinct .,".CONST_SERVICETYPE_NEW."' as ServiceId,sum(T1.ApprovedAmount) as Amount FROM `crpcontractorregistrationpayment` T1 join crpcontractorfinal T2 on T2.Id = T1.CrpContractorFinalId join crpcontractor T3 on T2.Id = T3.Id where 1 AND T3.PaymentReceiptNo is NOT NULL";

        if((bool)$fromDate){
            $fromDate = $this->convertDate($fromDate);
            $query1.=" and CAST(T1.CreatedOn as Date) >= '$fromDate'";
        }
        if((bool)$toDate){
            $toDate = $this->convertDate($toDate);
            $query1.=" and CAST(T1.CreatedOn as Date) <= '$toDate'";
        }
        if((bool)$receiptNo){
            $query1.=" and coalesce(T3.PaymentReceiptNo,'') = '$receiptNo'";
        }
        $query1.=" group by T1.CrpContractorFinalId";
        if((bool)$serviceId){
            if($serviceId != CONST_SERVICETYPE_NEW){
                $query1="";
            }
        }

    $query2="SELECT  PaymentReceiptNo,'Contractor' as Type,concat(B.NameOfFirm,' (',B.CDBNo,')') as Name,CAST(B.PaymentReceiptDate as Date) as PaymentDate,PaymentReceiptNo,1 as TypeCode,T2.Name as ServiceType,T2.Id as ServiceId, T1.TotalAmount as Amount from crpcontractorservicepayment T1  join (crpcontractor B Left join crpcontractorfinal A on A.Id = B.CrpContractorId) on B.Id = T1.CrpContractorId  join crpservice T2 on T2.Id = T1.CmnServiceTypeId where 1 AND B.PaymentReceiptNo is NOT NULL AND B.PaymentReceiptDate is not null";

        if((bool)$fromDate){
            $fromDate = $this->convertDate($fromDate);
            $query2.=" and CAST(T1.CreatedOn as Date) >= '$fromDate'";
        }
        if((bool)$toDate){
            $toDate = $this->convertDate($toDate);
            $query2.=" and CAST(T1.CreatedOn as Date) <= '$toDate'";
        }
        if((bool)$serviceId){
            if($serviceId != CONST_SERVICETYPE_NEW){
                $query2.=" and T1.CmnServiceTypeId = '$serviceId'";
            }
        }
        if((bool)$receiptNo){
            $query2.=" and B.PaymentReceiptNo = '$receiptNo'";
        }
        $query2.=" having Amount >0";
        if((bool)$serviceId){
            if($serviceId == CONST_SERVICETYPE_NEW){
                $query2="";
            }
        }

 
       //CONSULTANT 

       $query3 = "SELECT distinct PaymentReceiptNo,'Consultant' as Type,concat(T2.NameOfFirm,' (',T2.CDBNo,')') as Name,CAST(T3.PaymentReceiptDate as Date) as PaymentDate,PaymentReceiptNo,2 as TypeCode,'New Registration' as ServiceType,'distinct .,".CONST_SERVICETYPE_NEW."' as ServiceId,sum(T1.Amount) as Amount FROM `crpconsultantregistrationpayment` T1 join crpconsultantfinal T2 on T2.Id = T1.CrpConsultantFinalId join crpconsultant T3 on T2.Id = T3.Id where 1 AND T3.PaymentReceiptNo is NOT NULL";

        if((bool)$fromDate){
            $fromDate = $this->convertDate($fromDate);
            $query3.=" and CAST(T1.CreatedOn as Date) >= '$fromDate'";
        }
        if((bool)$toDate){
            $toDate = $this->convertDate($toDate);
            $query3.=" and CAST(T1.CreatedOn as Date) <= '$toDate'";
        }
        if((bool)$receiptNo){
            $query3.=" and coalesce(T3.PaymentReceiptNo,'') = '$receiptNo'";
        }
        $query3.=" group by T1.CrpConsultantFinalId";
        if((bool)$serviceId){
            if($serviceId != CONST_SERVICETYPE_NEW){
                $query3="";
            }
        }

        $query4="SELECT distinct PaymentReceiptNo,'Consultant' as Type,concat(B.NameOfFirm,' (',B.CDBNo,')') as Name,CAST(B.PaymentReceiptDate as Date) as PaymentDate,PaymentReceiptNo,2 as TypeCode,T2.Name as ServiceType,T2.Id as ServiceId, (T1.TotalAmount) as Amount from crpconsultantservicepayment T1  join (crpconsultant B LEFT join crpconsultantfinal A on A.Id = B.CrpConsultantId) on B.Id = T1.CrpConsultantId join crpservice T2 on T2.Id = T1.CmnServiceTypeId where 1 AND B.PaymentReceiptNo is NOT NULL AND B.PaymentReceiptDate is not null";
        if((bool)$fromDate){
            $fromDate = $this->convertDate($fromDate);
            $query4.=" and CAST(T1.CreatedOn as Date) >= '$fromDate'";
        }
        if((bool)$toDate){
            $toDate = $this->convertDate($toDate);
            $query4.=" and CAST(T1.CreatedOn as Date) <= '$toDate'";
        }
        if((bool)$serviceId){
            if($serviceId != CONST_SERVICETYPE_NEW){
                $query4.=" and T1.CmnServiceTypeId = '$serviceId'";
            }
        }
        if((bool)$receiptNo){
            $query4.=" and B.PaymentReceiptNo = '$receiptNo'";
        }
        $query4.=" having Amount >0";
        if((bool)$serviceId){
            if($serviceId == CONST_SERVICETYPE_NEW){
                $query4="";
            }
        }


       //ARCHITECT 

       $query5="SELECT distinct PaymentReceiptNo,'Architect' as Type,concat(B.Name,' (',B.ARNo,')') as Name,CAST(B.PaymentReceiptDate as Date) as PaymentDate,PaymentReceiptNo,3 as TypeCode,T2.Name as ServiceType,T2.Id as ServiceId, (T1.TotalAmount) as Amount from crparchitectservicepayment T1 join (crparchitect B left join crparchitectfinal A on A.Id = B.CrpArchitectId) on B.Id = T1.CrpArchitectId join crpservice T2 on T2.Id = T1.CmnServiceTypeId where 1 AND B.PaymentReceiptNo is NOT NULL AND B.PaymentReceiptDate is not null";
       if((bool)$fromDate){
           $fromDate = $this->convertDate($fromDate);
           $query5.=" and CAST(T1.CreatedOn as Date) >= '$fromDate'";
       }
       if((bool)$toDate){
           $toDate = $this->convertDate($toDate);
           $query5.=" and CAST(T1.CreatedOn as Date) <= '$toDate'";
       }
       if((bool)$receiptNo){
           $query5.=" and B.PaymentReceiptNo = '$receiptNo'";
       }
       if((bool)$serviceId){
           if($serviceId != CONST_SERVICETYPE_NEW){
               $query5.=" and T1.CmnServiceTypeId= '$serviceId'";
           }
       }
       $query5.=" having Amount >0";
       if((bool)$serviceId){
           if($serviceId == CONST_SERVICETYPE_NEW){
               $query5="";
           }
       }

       $query6="SELECT distinct PaymentReceiptNo,'Architect' as Type,concat(T2.Name,' (',T2.ARNo,')') as Name,CAST(T1.CreatedOn as Date) as PaymentDate,PaymentReceiptNo,3 as TypeCode,'New Registration' as ServiceType,'".CONST_SERVICETYPE_NEW."' as ServiceId,sum(T1.Amount) as Amount FROM `crparchitectregistrationpayment` T1 join crparchitectfinal T2 on T2.Id = T1.CrpArchitectFinalId join crparchitect T3 on T3.Id = T2.Id where 1 AND T3.PaymentReceiptNo is NOT NULL";
       if((bool)$fromDate){
           $fromDate = $this->convertDate($fromDate);
           $query6.=" and CAST(T1.CreatedOn as Date) >= '$fromDate'";
       }
       if((bool)$toDate){
           $toDate = $this->convertDate($toDate);
           $query6.=" and CAST(T1.CreatedOn as Date) <= '$toDate'";
       }
       if((bool)$receiptNo){
           $query6.=" and T3.PaymentReceiptNo = '$receiptNo'";
       }
       $query6.= " group by T1.CrpArchitectFinalId";

       if((bool)$serviceId){
           if($serviceId != CONST_SERVICETYPE_NEW){
               $query6="";
           }
       }
        
          //SPECIALIZEDFIRM 

          $query7 = "SELECT distinct PaymentReceiptNo,'Specialized Firm' as Type,concat(T2.NameOfFirm,' (',T2.SPNo,')') as Name,CAST(T1.CreatedOn as Date) as PaymentDate,PaymentReceiptNo,4 as TypeCode,'New Registration' as ServiceType,'distinct .,".CONST_SERVICETYPE_NEW."' as ServiceId,sum(T1.ApprovedAmount) as Amount FROM `crpspecializedfirmregistrationpayment` T1 join crpspecializedfirmfinal T2 on T2.Id = T1.CrpSpecializedTradeFinalId join crpspecializedfirm T3 on T2.Id = T3.Id where 1 AND T3.PaymentReceiptNo is NOT NULL";

          if((bool)$fromDate){
              $fromDate = $this->convertDate($fromDate);
              $query7.=" and CAST(T1.CreatedOn as Date) >= '$fromDate'";
          }
          if((bool)$toDate){
              $toDate = $this->convertDate($toDate);
              $query7.=" and CAST(T1.CreatedOn as Date) <= '$toDate'";
          }
          if((bool)$receiptNo){
              $query7.=" and coalesce(T3.PaymentReceiptNo,'') = '$receiptNo'";
          }
          $query7.=" group by T1.CrpSpecializedTradeFinalId";
          if((bool)$serviceId){
              if($serviceId != CONST_SERVICETYPE_NEW){
                  $query7="";
              }
          }
  
          $query8="SELECT distinct PaymentReceiptNo,'Specialized Firm' as Type,concat(B.NameOfFirm,' (',B.SPNo,')') as Name,CAST(B.PaymentReceiptDate as Date) as PaymentDate,PaymentReceiptNo,4 as TypeCode,T2.Name as ServiceType,T2.Id as ServiceId, (T1.TotalAmount) as Amount from crpspecializedfirmservicepayment T1  join (crpspecializedfirm B LEFT join crpspecializedfirmfinal A on A.Id = B.CrpSpecializedTradeId) on B.Id = T1.CrpSpecializedTradeId join crpservice T2 on T2.Id = T1.CmnServiceTypeId where 1 AND B.PaymentReceiptNo is NOT NULL AND B.PaymentReceiptDate is not null";
          if((bool)$fromDate){
              $fromDate = $this->convertDate($fromDate);
              $query8.=" and CAST(T1.CreatedOn as Date) >= '$fromDate'";
          }
          if((bool)$toDate){
              $toDate = $this->convertDate($toDate);
              $query8.=" and CAST(T1.CreatedOn as Date) <= '$toDate'";
          }
          if((bool)$serviceId){
              if($serviceId != CONST_SERVICETYPE_NEW){
                  $query8.=" and T1.CmnServiceTypeId = '$serviceId'";
              }
          }
          if((bool)$receiptNo){
              $query8.=" and B.PaymentReceiptNo = '$receiptNo'";
          }
          $query8.=" having Amount >0";
          if((bool)$serviceId){
              if($serviceId == CONST_SERVICETYPE_NEW){
                  $query8="";
              }
          }
 //SURVEYOR 

 $query9="SELECT distinct PaymentReceiptNo,'Surveyor' as Type,concat(B.Name,' (',B.ARNo,')') as Name,CAST(B.PaymentReceiptDate as Date) as PaymentDate,PaymentReceiptNo,5 as TypeCode,T2.Name as ServiceType,T2.Id as ServiceId, coalesce(T1.TotalAmount) as Amount from crpsurveyservicepayment T1 join (crpsurvey B LEFT join crpsurveyfinal A on A.Id = B.CrpSurveyId) on B.Id = T1.CrpSurveyId join crpservice T2 on T2.Id = T1.CmnServiceTypeId where 1 AND B.PaymentReceiptNo is NOT NULL AND B.PaymentReceiptDate is not null";
 if((bool)$fromDate){
     $fromDate = $this->convertDate($fromDate);
     $query9.=" and CAST(T1.CreatedOn as Date) >= '$fromDate'";
 }
 if((bool)$toDate){
     $toDate = $this->convertDate($toDate);
     $query9.=" and CAST(T1.CreatedOn as Date) <= '$toDate'";
 }
 if((bool)$receiptNo){
     $query9.=" and B.PaymentReceiptNo = '$receiptNo'";
 }
 if((bool)$serviceId){
     if($serviceId != CONST_SERVICETYPE_NEW){
         $query9.=" and T1.CmnServiceTypeId= '$serviceId'";
     }
 }
 $query9.=" having Amount >0";
 if((bool)$serviceId){
     if($serviceId == CONST_SERVICETYPE_NEW){
         $query9="";
     }
 }

 $query10="SELECT distinct PaymentReceiptNo,'Surveyor' as Type,concat(T2.Name,' (',T2.ARNo,')') as Name,CAST(T1.CreatedOn as Date) as PaymentDate,PaymentReceiptNo,5 as TypeCode,'New Registration' as ServiceType,'".CONST_SERVICETYPE_NEW."' as ServiceId,sum(T1.Amount) as Amount FROM `crpsurveyregistrationpayment` T1 join crpsurveyfinal T2 on T2.Id = T1.CrpSurveyFinalId join crpsurvey T3 on T3.Id = T2.Id where 1";
 if((bool)$fromDate){
     $fromDate = $this->convertDate($fromDate);
     $query10.=" and CAST(T1.CreatedOn as Date) >= '$fromDate'";
 }
 if((bool)$toDate){
     $toDate = $this->convertDate($toDate);
     $query10.=" and CAST(T1.CreatedOn as Date) <= '$toDate'";
 }
 if((bool)$receiptNo){
     $query10.=" and T3.PaymentReceiptNo = '$receiptNo'";
 }
 $query10.= " group by T1.CrpSurveyFinalId";

 if((bool)$serviceId){
     if($serviceId != CONST_SERVICETYPE_NEW){
         $query10="";
     }
 }
  
 //ENGINEER 

 $query11="SELECT distinct PaymentReceiptNo,'Engineer' as Type,concat(B.Name,' (',B.CDBNo,')') as Name,CAST(B.PaymentReceiptDate as Date) as PaymentDate,PaymentReceiptNo,6 as TypeCode,T2.Name as ServiceType,T2.Id as ServiceId, coalesce(T1.TotalAmount) as Amount from crpengineerservicepayment T1 join (crpengineer B LEFT join crpengineerfinal A on A.Id = B.CrpEngineerId) on B.Id = T1.CrpEngineerId join crpservice T2 on T2.Id = T1.CmnServiceTypeId where 1 AND B.PaymentReceiptNo is NOT NULL AND B.PaymentReceiptDate is not null";
 if((bool)$fromDate){
     $fromDate = $this->convertDate($fromDate);
     $query11.=" and CAST(T1.CreatedOn as Date) >= '$fromDate'";
 }
 if((bool)$toDate){
     $toDate = $this->convertDate($toDate);
     $query11.=" and CAST(T1.CreatedOn as Date) <= '$toDate'";
 }
 if((bool)$receiptNo){
     $query11.=" and B.PaymentReceiptNo = '$receiptNo'";
 }
 if((bool)$serviceId){
     if($serviceId != CONST_SERVICETYPE_NEW){
         $query11.=" and T1.CmnServiceTypeId= '$serviceId'";
     }
 }
 $query11.=" having Amount >0";
 if((bool)$serviceId){
     if($serviceId == CONST_SERVICETYPE_NEW){
         $query11="";
     }
 }

 $query12="SELECT distinct PaymentReceiptNo,'Engineer' as Type,concat(T2.Name,' (',T2.CDBNo,')') as Name,CAST(T1.CreatedOn as Date) as PaymentDate,PaymentReceiptNo,6 as TypeCode,'New Registration' as ServiceType,'".CONST_SERVICETYPE_NEW."' as ServiceId,sum(T1.Amount) as Amount FROM `crpengineerregistrationpayment` T1 join crpengineerfinal T2 on T2.Id = T1.CrpEngineerFinalId join crpengineer T3 on T3.Id = T2.Id where 1 AND T3.PaymentReceiptNo is NOT NULL";
 if((bool)$fromDate){
     $fromDate = $this->convertDate($fromDate);
     $query12.=" and CAST(T1.CreatedOn as Date) >= '$fromDate'";
 }
 if((bool)$toDate){
     $toDate = $this->convertDate($toDate);
     $query12.=" and CAST(T1.CreatedOn as Date) <= '$toDate'";
 }
 if((bool)$receiptNo){
     $query12.=" and T3.PaymentReceiptNo = '$receiptNo'";
 }
 $query12.= " group by T1.CrpEngineerFinalId";

 if((bool)$serviceId){
     if($serviceId != CONST_SERVICETYPE_NEW){
         $query12="";
     }
 }


   
 //SPECIALIZED TRADE 

 $query13="SELECT distinct PaymentReceiptNo,'Specialized Trade' as Type,concat(B.Name,' (',B.SPNo,')') as Name,CAST(B.PaymentReceiptDate as Date) as PaymentDate,PaymentReceiptNo,7 as TypeCode,T2.Name as ServiceType,T2.Id as ServiceId, coalesce(T1.TotalAmount) as Amount from crpspecializedtradeservicepayment T1 join (crpspecializedtrade B LEFT join crpspecializedtradefinal A on A.Id = B.CrpSpecializedTradeId) on B.Id = T1.CrpSpecializedTradeId join crpservice T2 on T2.Id = T1.CmnServiceTypeId where 1 AND B.PaymentReceiptNo is NOT NULL AND B.PaymentReceiptDate is not null";
 if((bool)$fromDate){
     $fromDate = $this->convertDate($fromDate);
     $query13.=" and CAST(T1.CreatedOn as Date) >= '$fromDate'";
 }
 if((bool)$toDate){
     $toDate = $this->convertDate($toDate);
     $query13.=" and CAST(T1.CreatedOn as Date) <= '$toDate'";
 }
 if((bool)$receiptNo){
     $query13.=" and B.PaymentReceiptNo = '$receiptNo'";
 }
 if((bool)$serviceId){
     if($serviceId != CONST_SERVICETYPE_NEW){
         $query13.=" and T1.CmnServiceTypeId= '$serviceId'";
     }
 }
 $query13.=" having Amount >0";
 if((bool)$serviceId){
     if($serviceId == CONST_SERVICETYPE_NEW){
         $query13="";
     }
 }

 $query14="SELECT distinct PaymentReceiptNo,'Specialized Trade' as Type,concat(T2.Name,' (',T2.SPNo,')') as Name,CAST(T1.CreatedOn as Date) as PaymentDate,PaymentReceiptNo,7 as TypeCode,'New Registration' as ServiceType,'".CONST_SERVICETYPE_NEW."' as ServiceId,sum(T1.Amount) as Amount FROM `crpspecializedtraderegistrationpayment` T1 join crpspecializedtradefinal T2 on T2.Id = T1.CrpSpecializedTradeFinalId join crpspecializedtrade T3 on T3.Id = T2.Id where 1 AND T3.PaymentReceiptNo is NOT NULL";
 if((bool)$fromDate){
     $fromDate = $this->convertDate($fromDate);
     $query14.=" and CAST(T1.CreatedOn as Date) >= '$fromDate'";
 }
 if((bool)$toDate){
     $toDate = $this->convertDate($toDate);
     $query14.=" and CAST(T1.CreatedOn as Date) <= '$toDate'";
 }
 if((bool)$receiptNo){
     $query14.=" and T3.PaymentReceiptNo = '$receiptNo'";
 }
 $query14.= " group by T1.CrpSpecializedTradeFinalId";

 if((bool)$serviceId){
     if($serviceId != CONST_SERVICETYPE_NEW){
         $query14="";
     }
 }
        $query = "";
        if((bool)$type){
            if($type == 1){
                if($query1!=""){
                    $query.="$query1";
                }
                if($query2!=""){
                    if($query != ""){
                        $query.=" union all ";
                    }
                    $query.="$query2";
                }
                $query .= " order by PaymentDate desc,ServiceType";
            }elseif($type == 2){
                if($query3!=""){
                    $query.="$query3";
                }
                if($query4!=""){
                    if($query != ""){
                        $query.=" union all ";
                    }
                    $query.="$query4";
                }
                $query.= " order by PaymentDate desc,ServiceType";
            }elseif($type == 3){
                if($query5!=""){
                    $query.="$query5";
                }
                if($query6!=""){
                    if($query != ""){
                        $query.=" union all ";
                    }
                    $query.="$query6";
                }
                $query.= " order by PaymentDate desc,ServiceType";
            }elseif($type == 4){
                if($query7!=""){
                    $query.="$query7";
                }
                if($query8!=""){
                    if($query != ""){
                        $query.=" union all ";
                    }
                    $query.="$query8";
                }
                $query.= " order by PaymentDate desc,ServiceType";
                        
            }elseif($type == 5){
                if($query9!=""){
                    $query.="$query9";
                }
                if($query10!=""){
                    if($query != ""){
                        $query.=" union all ";
                    }
                    $query.="$query10";
                }
                $query.= " order by PaymentDate desc,ServiceType";
            }elseif($type == 6){
                if($query11!=""){
                    $query.="$query11";
                }
                if($query12!=""){
                    if($query != ""){
                        $query.=" union all ";
                    }
                    $query.="$query12";
                }
                $query.= " order by PaymentDate desc,ServiceType";
          
            }elseif($type == 7){
                if($query13!=""){
                    $query.="$query13";
                }
                if($query14!=""){
                    if($query != ""){
                        $query.=" union all ";
                    }
                    $query.="$query14";
                }
                $query.= " order by PaymentDate desc,ServiceType";
                                  
            
            }
        }else{
            if($query1!=""){
                $query.="$query1";
            }
            if($query2!=""){
                if($query != ""){
                    $query.=" union all ";
                }
                $query.="$query2";
            }
            if($query3!=""){
                if($query != ""){
                    $query.=" union all ";
                }
                $query.="$query3";
            }
            if($query4!=""){
                if($query != ""){
                    $query.=" union all ";
                }
                $query.="$query4";
            }
            if($query5!=""){
                if($query != ""){
                    $query.=" union all ";
                }
                $query.="$query5";
            }
            if($query6!=""){
                if($query != ""){
                    $query.=" union all ";
                }
                $query.="$query6";
            }
            if($query7!=""){
                if($query != ""){
                    $query.=" union all ";
                }
                $query.="$query7";
            }
            if($query8!=""){
                if($query != ""){
                    $query.=" union all ";
                }
                $query.="$query8";
            }
            if($query9!=""){
                if($query != ""){
                    $query.=" union all ";
                }
                $query.="$query9";
            }
            if($query10!=""){
                if($query != ""){
                    $query.=" union all ";
                }
                $query.="$query10";
            }
            if($query11!=""){
                if($query != ""){
                    $query.=" union all ";
                }
                $query.="$query11";
            }
            if($query12!=""){
                if($query != ""){
                    $query.=" union all ";
                }
                $query.="$query12";
            }
            if($query13!=""){
                if($query != ""){
                    $query.=" union all ";
                }
                $query.="$query13";
            }
            if($query14!=""){
                if($query != ""){
                    $query.=" union all ";
                }
                $query.="$query14";
            }
            $query.= " order by PaymentDate desc,ServiceType";
        }
        $reportData = DB::select($query);

        return View::make("report.revenuecollection")
            ->with('reportAppend',$reportAppend)
            ->with('summary',0)
            ->with('serviceTypes',$serviceTypes)
            ->with('reportData',$reportData);
    }
}