<?php
class MyCertifiedBuilder extends CBController{
	protected $layout = 'horizontalmenumaster';
	public function dashboard(){

		$module=Request::segment(1);
		$auditTrailActionMessage="Viewed dashboard page";
		$this->auditTrailEtoolCinet(NULL,NULL,$auditTrailActionMessage);
        $userId = Auth::user()->Id;
        $userProcuringAgencyId = DB::table('sysuser')->where('Id',$userId)->pluck('CmnProcuringAgencyId');
//		$newsAndNotifications=SysNewsAndNotificationModel::where('MessageFor',3)->where('DisplayIn',2)->orderBy('Date')->get(array('Message','Date'));
		$newsAndNotifications = DB::select("select Message, Date,CmnProcuringAgencyId from sysnewsandnotification where (DisplayIn = 2 and MessageFor = 3) and case when CmnProcuringAgencyId is not null then CmnProcuringAgencyId = '$userProcuringAgencyId' else 1=1 end order by Date Desc");
        $this->layout->content =View::make('crps.cmnexternaluserdashboard')
		->with('newsAndNotifications',$newsAndNotifications)
		->with('module',$module);
	}
}
