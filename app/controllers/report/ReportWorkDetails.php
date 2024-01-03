<?php

class ReportWorkDetails extends ReportController{
    public function getIndex(){
        $workId = Input::get('WorkId');

        $workId = urldecode($workId);
        $tenderDetails = array();
        $tenderId = '';
        $loggedInUser=Auth::user()->Id;
        $userRoles=DB::table('sysuserrolemap')->where('SysUserId',$loggedInUser)->lists('SysRoleId');
        $condition = "1=1";
        if(!in_array(CONST_ROLE_ADMINISTRATOR,$userRoles)){
            $userAgency = Auth::user()->CmnProcuringAgencyId;
            $userProcuringAgencyReference = DB::table('cmnprocuringagency')->where('Id',$userAgency)->pluck('ReferenceNo');
            if((int)$userProcuringAgencyReference != 901){
                $condition = " T1.CmnProcuringAgencyId = '$userAgency'";
            }
        }
        if(Input::has('WorkId')) {
            DB::table('tblworkidtrack')->insert(array('workid'=>Input::get('WorkId'),'username'=>Auth::user()->username,'operation'=>'Report 4','op_time'=>date('Y-m-d G:i:s')));
            $tenderId = DB::table('etltender as T1')
                        ->join('cmnprocuringagency as T2','T2.Id','=','T1.CmnProcuringAgencyId')
                        ->whereRaw("case when T1.migratedworkid is null then concat(T2.Code,'/',year(T1.UploadedDate),'/',T1.WorkId) else T1.migratedworkid end = '$workId'")
                        ->whereRaw($condition)
                       ->get(array('T1.Id','T1.CmnContractorClassificationId','T1.IsSPRRTender'));
        }
        if(!$tenderId){
            return Redirect::to('etl/reports')->with('customerrormessage','<b>CAUTION</b> <br/>
You do not have access to this Work Id.
You can only view the Work Ids you have created .
Viewing the Work Id created by other agencies is not allowed and you could be charged for breach of confidentiality.
Note that all the information viewed from your account is being traced.');
        }else{
	    $class = $tenderId[0]->CmnContractorClassificationId;
            if($class === "ef832830-c3ea-11e4-af9f-080027dcfac6"){
		return Redirect::to('etl/etoolsmallworksreport/'.$tenderId[0]->Id);
	    }else{
		if($tenderId[0]->IsSPRRTender=='Y')
                {
                    return Redirect::to('newEtl/etoolresultreport/'.$tenderId[0]->Id);
                }
                else
                {
                    return Redirect::to('etl/etoolresultreport/'.$tenderId[0]->Id);
                }
                            }
            
        }
    }
}