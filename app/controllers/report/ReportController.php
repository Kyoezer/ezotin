<?php

class ReportController extends BaseController{

	public function dashboard(){
		return View::make('report.dashboard');
	}

    public function auditTrailEtoolCinetReport(){
        $parameters = array();
        $limit = Input::get('Limit');
        $user = Input::get('User');
        $procuringAgency = Input::get('ProcuringAgency');
        $date = date_create(date('Y-m-d'));
        date_sub($date, date_interval_create_from_date_string('7 days'));
        $fromDate = Input::has('FromDate')?date_format(date_create(Input::get('FromDate')),'Y-m-d'):date_format($date,'Y-m-d');
        $toDate = Input::has('ToDate')?date_format(date_create(Input::get('ToDate')),'Y-m-d'):date('Y-m-d');
        $users = DB::table('sysaudittrailetoolcinet as T1')
                    ->join('sysuser as T2','T2.Id','=','T1.SysUserId')
                    ->get(array(DB::raw('distinct(T2.Id)'),'T2.FullName'));
        $procuringAgencies = DB::table('sysaudittrailetoolcinet as T1')
            ->join('sysuser as T2','T2.Id','=','T1.SysUserId')
            ->join('cmnprocuringagency as T3','T3.Id','=','T2.CmnProcuringAgencyId')
            ->get(array(DB::raw('distinct(T3.Id)'),'T3.Name'));
        $query = "select T1.ActionDate, T2.FullName as User, T3.Name as Agency, T1.IndexAction, T1.MessageDisplayed, T1.Remarks from sysaudittrailetoolcinet T1 join sysuser T2 on T2.Id = T1.SysUserId join cmnprocuringagency T3 on T3.Id = T2.CmnProcuringAgencyId where 1";
        if((bool)$user){
            $query.=" and T1.SysUserId = ?";
            array_push($parameters,$user);
        }
        if((bool)$fromDate){
            $query.=" and date_format(T1.ActionDate,'%Y-%m-%d') >= ?";
            array_push($parameters,$fromDate);
        }
        if((bool)$toDate){
            $query.=" and date_format(T1.ActionDate,'%Y-%m-%d') <= ?";
            array_push($parameters,$toDate);
        }
        $query.=" order by T1.ActionDate";
        if((bool)$limit){
            if($limit != 'All'){
                $query.=" limit $limit";
            }
        }else{
            $query.=" limit 50";
        }
        $reportData = DB::select($query,$parameters);
        return View::make('report.audittrailetoolcinetreport')
                ->with('reportData',$reportData)
                ->with('procuringAgencies',$procuringAgencies)
                ->with('users',$users);
    }

}