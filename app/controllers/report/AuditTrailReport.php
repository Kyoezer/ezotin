<?php
class AuditTrailReport extends ReportController{
	public function auditTrailGeneralReport(){
        $parameters=array();
        $userAction=Input::get('UserAction');
        $dbTableName=Input::get('DBTableName');
        $sysUserId=Input::get('SysUserId');
        $workId = Input::get('WorkId');
        $limit=Input::get('Limit');
        if((bool)$limit){
            if($limit != 'All'){
                $limit=" limit $limit";
            }else{
                $limit="";
            }
        }else{
            $limit.=" limit 50";
        }
        $dbtableNames=DB::select("SELECT t.TABLE_NAME as TableName,t.TABLE_COMMENT as TableComments FROM information_schema.tables t WHERE t.table_schema=? and t.TABLE_TYPE=? and t.table_name not in ('sysaudittrail','cmnlist','cmnsearch','cmnsearchresult','crpservicefeestructure','etlbidevalutionparameters','etltier','sysaudittrailetoolcinet','sysdeletedrecord','sysloginfailurelog','sysmenu','sysmenugroup','sysuserlog') order by TableName",array('cdb_local','BASE TABLE'));
		$users=User::get(array('Id','username','FullName'));
        $query="select T1.ActionDate,T1.RowId,T1.TableName,T1.ColumnName,T1.OldValue,T1.NewValue,coalesce(T2.FullName,T1.DBUser) as UserName,T2.username as UserEmail from sysaudittrail T1 left join (etltender T3 join cmnprocuringagency T4 on T4.Id = T3.CmnProcuringAgencyId) on T3.Id = T1.RowId left join sysuser T2 on T1.SysUserId=T2.Id where 1=1";
        if((bool)$userAction!=NULL){
            $query.=" and T1.Action=?";
            array_push($parameters,$userAction);
            if((bool)$sysUserId!=NULL){
                $query.=" and T1.SysUserId=?";
                array_push($parameters,$sysUserId);
            }
            if((bool)$dbTableName!=NULL){
                $query.=" and T1.TableName=?";
                array_push($parameters,$dbTableName);
            }
            if((bool)$workId){
                $query.=" and case when T3.migratedworkid is not null then T3.migratedworkid = '$workId' else concat(T4.Code,'/',year(T3.UploadedDate),T3.WorkId) = '$workId' end";
            }
        $auditTrails=DB::select($query." order by ActionDate,TableName,UserName".$limit,$parameters);    
        }else{
            $auditTrails=array();    
        }
        return View::make('sysreports.audittrailgeneralreport')
                        ->with('dbtableNames',$dbtableNames)
                        ->with('users',$users)
                        ->with('auditTrails',$auditTrails);
	}
    public function auditTrailEtoolCinetReport(){
        $parameters = array();
        $limit = Input::get('Limit');
        $user = Input::get('User');
        $procuringAgency = Input::get('ProcuringAgency');
        $workId = Input::get('WorkId');
        $date = date_create(date('Y-m-d'));
        date_sub($date, date_interval_create_from_date_string('7 days'));
        $fromDate = Input::has('FromDate')?date_format(date_create(Input::get('FromDate')),'Y-m-d'):date_format($date,'Y-m-d');
        $toDate = Input::has('ToDate')?date_format(date_create(Input::get('ToDate')),'Y-m-d'):date('Y-m-d');
        $users = DB::table('sysaudittrailetoolcinet as T1')
                    ->join('sysuser as T2','T2.Id','=','T1.SysUserId')
                    ->get(array(DB::raw('distinct(T2.Id)'),'T2.username','T2.FullName'));
        $procuringAgencies = DB::table('sysaudittrailetoolcinet as T1')
            ->join('sysuser as T2','T2.Id','=','T1.SysUserId')
            ->join('cmnprocuringagency as T3','T3.Id','=','T2.CmnProcuringAgencyId')
            ->get(array(DB::raw('distinct(T3.Id)'),'T3.Name'));
        $query = "select T1.ActionDate, T1.WorkId, T2.FullName as User,T2.username as UserEmail, T3.Name as Agency, T1.IndexAction, T1.MessageDisplayed, T1.Remarks from sysaudittrailetoolcinet T1 join sysuser T2 on T2.Id = T1.SysUserId join cmnprocuringagency T3 on T3.Id = T2.CmnProcuringAgencyId where 1";
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
        if((bool)$workId){
            $query.=" and WorkId = ?";
            array_push($parameters,$workId);
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
        return View::make('sysreports.audittrailetoolcinetreport')
                ->with('reportData',$reportData)
                ->with('procuringAgencies',$procuringAgencies)
                ->with('users',$users);
    }
}