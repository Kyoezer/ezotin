<?php

class ReportEngineerInformation extends ReportController{  
    public function getIndex(){
        $cdbNo = Input::get('CDBNo');
        $reportData = array();
        $parameters = array();
        $isAdmin = 0;
        if((bool)$cdbNo){
            $userRoles = DB::table("sysuserrolemap as T1")->where("SysUserId",Auth::user()->Id)->lists('T1.SysRoleId');
            if(in_array(CONST_ROLE_ADMINISTRATOR,$userRoles)){
              
            }
 

            $query="select T1.Id,T1.ReferenceNo as StatusReference,T7.Name as Status,T1.ApplicationDate,T1.CIDNo,T1.CDBNo,T1.Name as EngineerName,T1.EmployerName,T1.Email,T1.MobileNo,T2.Name as Country,T3.NameEn as Dzongkhag,T4.Name as Salutation,T5.Name as EngineerType,T6.Name as Trade from crpengineerfinal T1 join cmnlistitem T7 on T1.CmnApplicationRegistrationStatusId = T7.Id  join cmncountry T2 on T1.CmnCountryId=T2.Id left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id join cmnlistitem T4 on T1.CmnSalutationId=T4.Id join cmnlistitem T5 on T1.CmnServiceSectorTypeId=T5.Id join cmnlistitem T6 on T1.CmnTradeId=T6.Id where T1.CDBNo=?";
            array_push($parameters,$cdbNo);
            $reportData = DB::select($query,$parameters);
        }
        return View::make('report.engineerinformation')
            ->with('isAdmin',$isAdmin)
            ->with('reportData',$reportData);
    }
}