<?php
class CiNetController extends BaseController{
	public function __construct(){
        $route = Request::segment(1).'/'.Request::segment(2);
        if($route != "cinet/mydashboard"){
            $email = Auth::user()->Email;
            if(!(bool)$email){
                return Redirect::to('cinet/mydashboard')->with('customerrormessage',"<strong>WARNING!</strong> To continue using CiNET, please update your email address.")->send();
            }
        }
        $userId = Auth::user()->Id;
        $userProcuringAgencyId = DB::table('sysuser')->where('Id',$userId)->pluck('CmnProcuringAgencyId');
        $userEmailId = DB::table('sysuser')->where('Id',$userId)->pluck('Email');
        $fullName = DB::table('sysuser')->where('Id',$userId)->pluck('FullName');
        $worksForUser = DB::select("select T1.Id, T1.NameOfWork, T1.WorkOrderNo, T1.WorkStartDate from crpbiddingform T1 where T1.ByCDB = 0 and T1.CmnWorkExecutionStatusId = ? and T1.CmnProcuringAgencyId = ? and ((DATEDIFF(T1.WorkCompletionDate, CURDATE()) <= 20) && (DATEDIFF(T1.WorkCompletionDate, CURDATE())>=0)) and T1.Id not in (select EtoolCinetWorkId from sysnewsandnotification where CmnProcuringAgencyId = '$userProcuringAgencyId' and MessageFor = 3 and DisplayIn = 2)",array(CONST_CMN_WORKEXECUTIONSTATUS_AWARDED,$userProcuringAgencyId));
        if($worksForUser){
            foreach($worksForUser as $work){
                $message = "Work with Order No $work->WorkOrderNo ($work->NameOfWork) which started on $work->WorkStartDate is nearing completion. Please fill in the completion form when the work is completed.";
                $insertArray['Id'] = $this->UUID();
                $insertArray['MessageFor'] = 3;
                $insertArray['CmnProcuringAgencyId'] = $userProcuringAgencyId;
                $insertArray['DisplayIn'] = 2;
                $insertArray['Date'] = date('Y-m-d');
                $insertArray['Message'] = $message;
                $insertArray['EtoolCinetWorkId'] = $work->Id;
                SysNewsAndNotificationModel::create($insertArray);
                if($userEmailId){
                    $mailData=array(
                        'mailMessage'=>$message
                    );
                    $this->sendEmailMessage('emails.crps.mailnoticebyadministrator',$mailData,"Work Completion Notification",$userEmailId,$fullName);
                }
            }
        }else{
            $worksWithNotification = DB::table('sysnewsandnotification as T1')
                ->join('crpbiddingform as T2','T2.Id','=','T1.EtoolCinetWorkId')
                ->where('T1.CmnProcuringAgencyId',$userProcuringAgencyId)
                ->where('T1.MessageFor',3)
                ->where('T1.DisplayIn',2)
                ->where('T2.CmnWorkExecutionStatusId','<>',CONST_CMN_WORKEXECUTIONSTATUS_AWARDED)
                ->get(array('T1.Id'));
            if($worksWithNotification){
                foreach($worksWithNotification as $workWithNot){
                    DB::table('sysnewsandnotification')->where('Id',$workWithNot->Id)->delete();
                }
            }
        }
    }
}