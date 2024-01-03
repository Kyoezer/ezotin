<?php

class EzhotinHomeController extends BaseController {
	public function ezhotinIndex($type){
		Session::put('UserViewerType',$type);
		if((int)$type==1){//1=crps user login message
			$newsAndNotification=SysNewsAndNotificationModel::where('MessageFor',1)->where('DisplayIn',1)->orderBy('Date')->get(array('Message','Date'));
		}
		elseif((int)$type==2){//2=etool user login message
			$newsAndNotification=SysNewsAndNotificationModel::where('MessageFor',2)->where('DisplayIn',1)->orderBy('Date')->get(array('Message','Date'));
		}
		elseif((int)$type==3){//3=cinet user login message
			$newsAndNotification=SysNewsAndNotificationModel::where('MessageFor',3)->where('DisplayIn',1)->orderBy('Date')->get(array('Message','Date'));
		}
		elseif((int)$type==4){//4=registration services user login message
		//	$newsAndNotification=SysNewsAndNotificationModel::whereIn('MessageFor',array(4,5,6,7,8))->where('DisplayIn',1)->orderBy('Date')->get(array('Message','Date'));
			
//<meta http-equiv="Refresh" content="5; url=https://www.citizenservices.gov.bt/construction-services/">
return Redirect::to('https://www.citizenservices.gov.bt/construction-services/');

		}
		return View::make('ezhotin.ezhotinhome')
					->with('type',$type)
					->with('newsAndNotification',$newsAndNotification);
	}
	public function ezhotinDashboard(){
		$userApproveCount = 0;
		$applicantVerifyRegistration = array();
		$applicantVerifyService = array();
		$applicantApproveRegistration = array();
		$applicantApproveService = array();
		$applicantApprovePaymentRegistration = array();
		$applicantApprovePaymentService = array();
		$verifyRegistrationQuery = "";
		$verifyServiceQuery = "";
		$approveRegistrationQuery = "";
		$approveServiceQuery = "";
		$approvePaymentRegistrationQuery = "";
		$approvePaymentServiceQuery = "";
		$userRoles = DB::table('sysuserrolemap as T1')
						->where('T1.SysUserId',Auth::user()->Id)
						->lists('T1.SysRoleId');
		$userApprovePrivilege = DB::table('sysrolemenumap as T1')
									->join('sysmenu as T2','T2.Id','=','T1.SysMenuId')
									->whereIn('T1.SysRoleId',$userRoles)
									->where('T2.ReferenceNo',101)
									->count();
		$applicantVerifyPrivilegeCount = DB::table('sysrolemenumap as T1')
									->join('sysmenu as T2','T2.Id','=','T1.SysMenuId')
									->whereIn('T1.SysRoleId',$userRoles)
									->whereIn('T2.ReferenceNo',array(201,202,203,204,205,206,207,208,209,210))
									->orderBy('T2.ReferenceNo')
									->get(array('T2.ReferenceNo',DB::raw("SUBSTRING_INDEX(T2.MenuRoute,'/',1) as Type")));
		if(count($applicantVerifyPrivilegeCount)>0){
			foreach($applicantVerifyPrivilegeCount as $verifyPrivilege):
				if((int)$verifyPrivilege->ReferenceNo == 201){
					$verifyRegistrationQuery.="select count(distinct T1.Id) as ApplicationCount, 'Contractor Registration' as Type from crpcontractor T1 join cmncountry T2 on T1.CmnCountryId=T2.Id join cmnlistitem T4 on T1.CmnOwnershipTypeId=T4.Id left join (crpcontractorworkclassification A join cmncontractorclassification B on A.CmnAppliedClassificationId=B.Id) on T1.Id=A.CrpContractorId and B.Priority=(select max(Priority) from crpcontractorworkclassification X join cmncontractorclassification Y on X.CmnAppliedClassificationId=Y.Id where X.CrpContractorId=T1.Id) left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id where T1.CmnApplicationRegistrationStatusId='".CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW."' and T1.SysLockedByUserId is null and T1.CrpContractorId is null";
				}
				if((int)$verifyPrivilege->ReferenceNo == 202){
					if($verifyRegistrationQuery!=""){
						$verifyRegistrationQuery.=" union all ";
					}
					$verifyRegistrationQuery.="select count(distinct T1.Id) as ApplicationCount, 'Consultant Registration' as Type from crpconsultant T1 join cmnlistitem T4 on T1.CmnOwnershipTypeId=T4.Id join cmncountry T2 on T1.CmnCountryId=T2.Id left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id where T1.CmnApplicationRegistrationStatusId='".CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW."' and T1.SysLockedByUserId is null and T1.CrpConsultantId is null";
				}
				if((int)$verifyPrivilege->ReferenceNo == 203){
					if($verifyRegistrationQuery!=""){
						$verifyRegistrationQuery.=" union all ";
					}
					$verifyRegistrationQuery.="select count(distinct T1.Id) as ApplicationCount, 'Architect Registration' as Type from crparchitect T1 join cmncountry T2 on T1.CmnCountryId=T2.Id join cmnlistitem T4 on T1.CmnSalutationId=T4.Id join cmnlistitem T5 on T1.CmnServiceSectorTypeId=T5.Id left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id where T1.CmnApplicationRegistrationStatusId='".CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW."' and T1.SysLockedByUserId is null and T1.CrpArchitectId is null";
				}
				if((int)$verifyPrivilege->ReferenceNo == 204){
					if($verifyRegistrationQuery!=""){
						$verifyRegistrationQuery.=" union all ";
					}
					$verifyRegistrationQuery.="select count(distinct T1.Id) as ApplicationCount, 'Engineer Registration' as Type from crpengineer T1 join cmncountry T2 on T1.CmnCountryId=T2.Id left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id join cmnlistitem T4 on T1.CmnSalutationId=T4.Id join cmnlistitem T5 on T1.CmnServiceSectorTypeId=T5.Id join cmnlistitem T6 on T1.CmnTradeId=T6.Id where T1.CmnApplicationRegistrationStatusId='".CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW."' and T1.SysLockedByUserId is null";
				}
				if((int)$verifyPrivilege->ReferenceNo == 205){
					if($verifyRegistrationQuery!=""){
						$verifyRegistrationQuery.=" union all ";
					}
					$verifyRegistrationQuery.="select count(distinct T1.Id) as ApplicationCount, 'Specialized Trade Registration' as Type from crpspecializedtrade T1 join cmndzongkhag T2 on T1.CmnDzongkhagId=T2.Id join cmnlistitem T3 on T1.CmnSalutationId=T3.Id where T1.CmnApplicationRegistrationStatusId='".CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW."' and T1.SysLockedByUserId is null and T1.CrpSpecializedTradeId is null";
				}
				if((int)$verifyPrivilege->ReferenceNo == 206){
					$verifyServiceQuery.="select count(distinct T1.Id) as ApplicationCount, 'Contractor Service' as Type from crpcontractor T1 join cmncountry T2 on T1.CmnCountryId=T2.Id join cmnlistitem T4 on T1.CmnOwnershipTypeId=T4.Id join crpcontractorfinal T7 on T1.CrpContractorId=T7.Id join crpcontractorappliedservice T5 on T1.Id=T5.CrpContractorId join crpservice ST on T5.CmnServiceTypeId=ST.Id left join (crpcontractorworkclassification A join cmncontractorclassification B on A.CmnAppliedClassificationId=B.Id) on T1.Id=A.CrpContractorId and B.Priority=(select max(Priority) from crpcontractorworkclassification X join cmncontractorclassification Y on X.CmnAppliedClassificationId=Y.Id where X.CrpContractorId=T1.Id) left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id where T1.CmnApplicationRegistrationStatusId='".CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW."' and T1.SysLockedByUserId is null and T1.CrpContractorId is not null";
				}
				if((int)$verifyPrivilege->ReferenceNo == 207){
					if($verifyServiceQuery!=""){
						$verifyServiceQuery.=" union all ";
					}
					$verifyServiceQuery.="select count(distinct T1.Id) as ApplicationCount,'Consultant Service' as Type from crpconsultant T1 join cmnlistitem T4 on T1.CmnOwnershipTypeId=T4.Id join crpconsultantfinal T7 on T1.CrpConsultantId=T7.Id join cmncountry T2 on T1.CmnCountryId=T2.Id join crpconsultantappliedservice T5 on T1.Id=T5.CrpConsultantId join crpservice T6 on T5.CmnServiceTypeId=T6.Id left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id where T1.CmnApplicationRegistrationStatusId='".CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW."' and T1.SysLockedByUserId is null and T1.CrpConsultantId is not null";
				}
				if((int)$verifyPrivilege->ReferenceNo == 208){
					if($verifyServiceQuery!=""){
						$verifyServiceQuery.=" union all ";
					}
					$verifyServiceQuery.="select count(distinct T1.Id) as ApplicationCount, 'Architect Service' as Type from crparchitect T1 join crparchitectappliedservice T6 on T1.Id=T6.CrpArchitectId join crpservice T7 on T6.CmnServiceTypeId=T7.Id join cmncountry T2 on T1.CmnCountryId=T2.Id join cmnlistitem T4 on T1.CmnSalutationId=T4.Id join cmnlistitem T5 on T1.CmnServiceSectorTypeId=T5.Id left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id where T1.CmnApplicationRegistrationStatusId='".CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW."' and T1.SysLockedByUserId is null and T1.CrpArchitectId is not null";
				}
				if((int)$verifyPrivilege->ReferenceNo == 209){
					if($verifyServiceQuery!=""){
						$verifyServiceQuery.=" union all ";
					}
					$verifyServiceQuery.="select count(distinct T1.Id) as ApplicationCount, 'Engineer Service' as Type from crpengineer T1 join crpengineerappliedservice T6 on T1.Id=T6.CrpEngineerId join crpservice T7 on T6.CmnServiceTypeId=T7.Id join cmncountry T2 on T1.CmnCountryId=T2.Id join cmnlistitem T4 on T1.CmnSalutationId=T4.Id join cmnlistitem T5 on T1.CmnServiceSectorTypeId=T5.Id join cmnlistitem T8 on T1.CmnTradeId=T8.Id left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id where T1.CmnApplicationRegistrationStatusId='".CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW."' and T1.SysLockedByUserId is null and T1.CrpEngineerId is not null";
				}
				if((int)$verifyPrivilege->ReferenceNo == 210){
					if($verifyServiceQuery!=""){
						$verifyServiceQuery.=" union all ";
					}
					$verifyServiceQuery.="select count(distinct T1.Id) as ApplicationCount, 'Specialized Trade Service' as Type from crpspecializedtrade T1 join crpspecializedtradeappliedservice T5 on T1.Id=T5.CrpSpecializedTradeId join crpservice T4 on T5.CmnServiceTypeId=T4.Id join cmnlistitem T3 on T1.CmnSalutationId=T3.Id join cmndzongkhag T2 on T1.CmnDzongkhagId=T2.Id where T1.CmnApplicationRegistrationStatusId='".CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW."' and T1.SysLockedByUserId is null and T1.CrpSpecializedTradeId is not null";
				}
			endforeach;
		}


		if($verifyRegistrationQuery!=""){
			$applicantVerifyRegistration = DB::select($verifyRegistrationQuery);
		}
		if($verifyServiceQuery!=""){
			$applicantVerifyService = DB::select($verifyServiceQuery);
		}


		$applicantApprovePrivilegeCount = DB::table('sysrolemenumap as T1')
			->join('sysmenu as T2','T2.Id','=','T1.SysMenuId')
			->whereIn('T1.SysRoleId',$userRoles)
			->whereIn('T2.ReferenceNo',array(301,302,303,304,305,306,307,308,309,310))
			->orderBy('T2.ReferenceNo')
			->get(array('T2.ReferenceNo'));
		if(count($applicantApprovePrivilegeCount)>0){
			foreach($applicantApprovePrivilegeCount as $approvePrivilege):
				if((int)$approvePrivilege->ReferenceNo == 301){
					$approveRegistrationQuery.="select count(distinct T1.Id) as ApplicationCount, 'Contractor Registration' as Type from crpcontractor T1 join cmncountry T2 on T1.CmnCountryId=T2.Id join cmnlistitem T4 on T1.CmnOwnershipTypeId=T4.Id left join (crpcontractorworkclassification A join cmncontractorclassification B on A.CmnAppliedClassificationId=B.Id) on T1.Id=A.CrpContractorId and B.Priority=(select max(Priority) from crpcontractorworkclassification X join cmncontractorclassification Y on X.CmnAppliedClassificationId=Y.Id where X.CrpContractorId=T1.Id) left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id where T1.CmnApplicationRegistrationStatusId='".CONST_CMN_APPLICATIONREGISTRATIONSTATUS_VERIFIED."' and T1.SysLockedByUserId is null and T1.CrpContractorId is null";
				}
				if((int)$approvePrivilege->ReferenceNo == 302){
					if($approveRegistrationQuery!=""){
						$approveRegistrationQuery.=" union all ";
					}
					$approveRegistrationQuery.="select count(distinct T1.Id) as ApplicationCount, 'Consultant Registration' as Type from crpconsultant T1 join cmnlistitem T4 on T1.CmnOwnershipTypeId=T4.Id join cmncountry T2 on T1.CmnCountryId=T2.Id left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id where T1.CmnApplicationRegistrationStatusId='".CONST_CMN_APPLICATIONREGISTRATIONSTATUS_VERIFIED."' and T1.SysLockedByUserId is null and T1.CrpConsultantId is null";
				}
				if((int)$approvePrivilege->ReferenceNo == 303){
					if($approveRegistrationQuery!=""){
						$approveRegistrationQuery.=" union all ";
					}
					$approveRegistrationQuery.="select count(distinct T1.Id) as ApplicationCount, 'Architect Registration' as Type from crparchitect T1 join cmncountry T2 on T1.CmnCountryId=T2.Id join cmnlistitem T4 on T1.CmnSalutationId=T4.Id join cmnlistitem T5 on T1.CmnServiceSectorTypeId=T5.Id left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id where T1.CmnApplicationRegistrationStatusId='".CONST_CMN_APPLICATIONREGISTRATIONSTATUS_VERIFIED."' and T1.SysLockedByUserId is null and T1.CrpArchitectId is null";
				}
				if((int)$approvePrivilege->ReferenceNo == 304){
					if($approveRegistrationQuery!=""){
						$approveRegistrationQuery.=" union all ";
					}
					$approveRegistrationQuery.="select count(distinct T1.Id) as ApplicationCount, 'Engineer Registration' as Type from crpengineer T1 join cmncountry T2 on T1.CmnCountryId=T2.Id left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id join cmnlistitem T4 on T1.CmnSalutationId=T4.Id join cmnlistitem T5 on T1.CmnServiceSectorTypeId=T5.Id join cmnlistitem T6 on T1.CmnTradeId=T6.Id where T1.CmnApplicationRegistrationStatusId='".CONST_CMN_APPLICATIONREGISTRATIONSTATUS_VERIFIED."' and T1.SysLockedByUserId is null";
				}
				if((int)$approvePrivilege->ReferenceNo == 305){
					if($approveRegistrationQuery!=""){
						$approveRegistrationQuery.=" union all ";
					}
					$approveRegistrationQuery.="select count(distinct T1.Id) as ApplicationCount, 'Specialized Trade Registration' as Type from crpspecializedtrade T1 join cmndzongkhag T2 on T1.CmnDzongkhagId=T2.Id join cmnlistitem T3 on T1.CmnSalutationId=T3.Id where T1.CmnApplicationRegistrationStatusId='".CONST_CMN_APPLICATIONREGISTRATIONSTATUS_VERIFIED."' and T1.SysLockedByUserId is null and T1.CrpSpecializedTradeId is null";
				}
				if((int)$approvePrivilege->ReferenceNo == 306){
					$approveServiceQuery.="select count(distinct T1.Id) as ApplicationCount, 'Contractor Service' as Type from crpcontractor T1 join cmncountry T2 on T1.CmnCountryId=T2.Id join cmnlistitem T4 on T1.CmnOwnershipTypeId=T4.Id join crpcontractorfinal T7 on T1.CrpContractorId=T7.Id join crpcontractorappliedservice T5 on T1.Id=T5.CrpContractorId join crpservice ST on T5.CmnServiceTypeId=ST.Id left join (crpcontractorworkclassification A join cmncontractorclassification B on A.CmnAppliedClassificationId=B.Id) on T1.Id=A.CrpContractorId and B.Priority=(select max(Priority) from crpcontractorworkclassification X join cmncontractorclassification Y on X.CmnAppliedClassificationId=Y.Id where X.CrpContractorId=T1.Id) left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id where T1.CmnApplicationRegistrationStatusId='".CONST_CMN_APPLICATIONREGISTRATIONSTATUS_VERIFIED."' and T1.SysLockedByUserId is null and T1.CrpContractorId is not null";
				}
				if((int)$approvePrivilege->ReferenceNo == 307){
					if($approveServiceQuery!=""){
						$approveServiceQuery.=" union all ";
					}
					$approveServiceQuery.="select count(distinct T1.Id) as ApplicationCount, 'Consultant Service' as Type from crpconsultant T1 join cmnlistitem T4 on T1.CmnOwnershipTypeId=T4.Id join crpconsultantfinal T7 on T1.CrpConsultantId=T7.Id join cmncountry T2 on T1.CmnCountryId=T2.Id join crpconsultantappliedservice T5 on T1.Id=T5.CrpConsultantId join crpservice T6 on T5.CmnServiceTypeId=T6.Id left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id where T1.CmnApplicationRegistrationStatusId='".CONST_CMN_APPLICATIONREGISTRATIONSTATUS_VERIFIED."' and T1.SysLockedByUserId is null and T1.CrpConsultantId is not null";
				}
				if((int)$approvePrivilege->ReferenceNo == 308){
					if($approveServiceQuery!=""){
						$approveServiceQuery.=" union all ";
					}
					$approveServiceQuery.="select count(distinct T1.Id) as ApplicationCount, 'Architect Service' as Type from crparchitect T1 join crparchitectappliedservice T6 on T1.Id=T6.CrpArchitectId join crpservice T7 on T6.CmnServiceTypeId=T7.Id join cmncountry T2 on T1.CmnCountryId=T2.Id join cmnlistitem T4 on T1.CmnSalutationId=T4.Id join cmnlistitem T5 on T1.CmnServiceSectorTypeId=T5.Id left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id where T1.CmnApplicationRegistrationStatusId='".CONST_CMN_APPLICATIONREGISTRATIONSTATUS_VERIFIED."' and T1.SysLockedByUserId is null and T1.CrpArchitectId is not null";
				}
				if((int)$approvePrivilege->ReferenceNo == 309){
					if($approveServiceQuery!=""){
						$approveServiceQuery.=" union all ";
					}
					$approveServiceQuery.="select count(distinct T1.Id) as ApplicationCount, 'Engineer Service' as Type from crpengineer T1 join crpengineerappliedservice T6 on T1.Id=T6.CrpEngineerId join crpservice T7 on T6.CmnServiceTypeId=T7.Id join cmncountry T2 on T1.CmnCountryId=T2.Id join cmnlistitem T4 on T1.CmnSalutationId=T4.Id join cmnlistitem T5 on T1.CmnServiceSectorTypeId=T5.Id join cmnlistitem T8 on T1.CmnTradeId=T8.Id left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id where T1.CmnApplicationRegistrationStatusId='".CONST_CMN_APPLICATIONREGISTRATIONSTATUS_VERIFIED."' and T1.SysLockedByUserId is null and T1.CrpEngineerId is not null";
				}
				if((int)$approvePrivilege->ReferenceNo == 310){
					if($approveServiceQuery!=""){
						$approveServiceQuery.=" union all ";
					}
					$approveServiceQuery.="select count(distinct T1.Id) as ApplicationCount, 'Specialized Trade Service' as Type from crpspecializedtrade T1 join crpspecializedtradeappliedservice T5 on T1.Id=T5.CrpSpecializedTradeId join crpservice T4 on T5.CmnServiceTypeId=T4.Id join cmnlistitem T3 on T1.CmnSalutationId=T3.Id join cmndzongkhag T2 on T1.CmnDzongkhagId=T2.Id where T1.CmnApplicationRegistrationStatusId='".CONST_CMN_APPLICATIONREGISTRATIONSTATUS_VERIFIED."' and T1.SysLockedByUserId is null and T1.CrpSpecializedTradeId is not null";
				}
			endforeach;
		}
		if($approveRegistrationQuery!=""){
			$applicantApproveRegistration = DB::select($approveRegistrationQuery);
		}
		if($approveServiceQuery!=""){
			$applicantApproveService = DB::select($approveServiceQuery);
		}

		/*APPROVE PAYMENT*/
		$applicantApprovePaymentPrivilegeCount = DB::table('sysrolemenumap as T1')
			->join('sysmenu as T2','T2.Id','=','T1.SysMenuId')
			->whereIn('T1.SysRoleId',$userRoles)
			->whereIn('T2.ReferenceNo',array(401,402,403,404,406,407,408,409,410))
			->orderBy('T2.ReferenceNo')
			->get(array('T2.ReferenceNo'));
		if(count($applicantApprovePaymentPrivilegeCount)>0){
			foreach($applicantApprovePaymentPrivilegeCount as $approvePaymentPrivilege):
				if((int)$approvePaymentPrivilege->ReferenceNo == 401){
					$approvePaymentRegistrationQuery.="select count(distinct T1.Id) as ApplicationCount, 'Contractor Registration' as Type from crpcontractor T1 join cmncountry T2 on T1.CmnCountryId=T2.Id join cmnlistitem T4 on T1.CmnOwnershipTypeId=T4.Id left join (crpcontractorworkclassification A join cmncontractorclassification B on A.CmnAppliedClassificationId=B.Id) on T1.Id=A.CrpContractorId and B.Priority=(select max(Priority) from crpcontractorworkclassification X join cmncontractorclassification Y on X.CmnAppliedClassificationId=Y.Id where X.CrpContractorId=T1.Id) left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id where T1.CmnApplicationRegistrationStatusId='".CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVEDFORPAYMENT."' and T1.SysLockedByUserId is null and T1.CrpContractorId is null";
				}
				if((int)$approvePaymentPrivilege->ReferenceNo == 402){
					if($approvePaymentRegistrationQuery!=""){
						$approvePaymentRegistrationQuery.=" union all ";
					}
					$approvePaymentRegistrationQuery.="select count(distinct T1.Id) as ApplicationCount, 'Consultant Registration' as Type from crpconsultant T1 join cmnlistitem T4 on T1.CmnOwnershipTypeId=T4.Id join cmncountry T2 on T1.CmnCountryId=T2.Id left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id where T1.CmnApplicationRegistrationStatusId='".CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVEDFORPAYMENT."' and T1.SysLockedByUserId is null and T1.CrpConsultantId is null";
				}
				if((int)$approvePaymentPrivilege->ReferenceNo == 403){
					if($approvePaymentRegistrationQuery!=""){
						$approvePaymentRegistrationQuery.=" union all ";
					}
					$approvePaymentRegistrationQuery.="select count(distinct T1.Id) as ApplicationCount, 'Architect Registration' as Type from crparchitect T1 join cmncountry T2 on T1.CmnCountryId=T2.Id join cmnlistitem T4 on T1.CmnSalutationId=T4.Id join cmnlistitem T5 on T1.CmnServiceSectorTypeId=T5.Id left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id where T1.CmnApplicationRegistrationStatusId='".CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVEDFORPAYMENT."' and T1.SysLockedByUserId is null and T1.CrpArchitectId is null";
				}
				if((int)$approvePaymentPrivilege->ReferenceNo == 404){
					if($approvePaymentRegistrationQuery!=""){
						$approvePaymentRegistrationQuery.=" union all ";
					}
					$approvePaymentRegistrationQuery.="select count(distinct T1.Id) as ApplicationCount, 'Engineer Registration' as Type from crpengineer T1 join cmncountry T2 on T1.CmnCountryId=T2.Id left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id join cmnlistitem T4 on T1.CmnSalutationId=T4.Id join cmnlistitem T5 on T1.CmnServiceSectorTypeId=T5.Id join cmnlistitem T6 on T1.CmnTradeId=T6.Id where T1.CmnApplicationRegistrationStatusId='".CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVEDFORPAYMENT."' and T1.SysLockedByUserId is null";
				}
				if((int)$approvePaymentPrivilege->ReferenceNo == 406){
					$approvePaymentServiceQuery.="select count(distinct T1.Id) as ApplicationCount, 'Contractor Service' as Type from crpcontractor T1 join cmncountry T2 on T1.CmnCountryId=T2.Id join cmnlistitem T4 on T1.CmnOwnershipTypeId=T4.Id join crpcontractorfinal T7 on T1.CrpContractorId=T7.Id join crpcontractorappliedservice T5 on T1.Id=T5.CrpContractorId join crpservice ST on T5.CmnServiceTypeId=ST.Id left join (crpcontractorworkclassification A join cmncontractorclassification B on A.CmnAppliedClassificationId=B.Id) on T1.Id=A.CrpContractorId and B.Priority=(select max(Priority) from crpcontractorworkclassification X join cmncontractorclassification Y on X.CmnAppliedClassificationId=Y.Id where X.CrpContractorId=T1.Id) left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id where T1.CmnApplicationRegistrationStatusId='".CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVEDFORPAYMENT."' and T1.SysLockedByUserId is null and T1.CrpContractorId is not null";
				}
				if((int)$approvePaymentPrivilege->ReferenceNo == 407){
					if($approvePaymentServiceQuery!=""){
						$approvePaymentServiceQuery.=" union all ";
					}
					$approvePaymentServiceQuery.="select count(distinct T1.Id) as ApplicationCount, 'Consultant Service' as Type from crpconsultant T1 join cmnlistitem T4 on T1.CmnOwnershipTypeId=T4.Id join crpconsultantfinal T7 on T1.CrpConsultantId=T7.Id join cmncountry T2 on T1.CmnCountryId=T2.Id join crpconsultantappliedservice T5 on T1.Id=T5.CrpConsultantId join crpservice T6 on T5.CmnServiceTypeId=T6.Id left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id where T1.CmnApplicationRegistrationStatusId='".CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVEDFORPAYMENT."' and T1.SysLockedByUserId is null and T1.CrpConsultantId is not null";
				}
				if((int)$approvePaymentPrivilege->ReferenceNo == 408){
					if($approvePaymentServiceQuery!=""){
						$approvePaymentServiceQuery.=" union all ";
					}
					$approvePaymentServiceQuery.="select count(distinct T1.Id) as ApplicationCount, 'Architect Service' as Type from crparchitect T1 join crparchitectappliedservice T6 on T1.Id=T6.CrpArchitectId join crpservice T7 on T6.CmnServiceTypeId=T7.Id join cmncountry T2 on T1.CmnCountryId=T2.Id join cmnlistitem T4 on T1.CmnSalutationId=T4.Id join cmnlistitem T5 on T1.CmnServiceSectorTypeId=T5.Id left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id where T1.CmnApplicationRegistrationStatusId='".CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVEDFORPAYMENT."' and T1.SysLockedByUserId is null and T1.CrpArchitectId is not null";
				}
				if((int)$approvePaymentPrivilege->ReferenceNo == 409){
					if($approvePaymentServiceQuery!=""){
						$approvePaymentServiceQuery.=" union all ";
					}
					$approvePaymentServiceQuery.="select count(distinct T1.Id) as ApplicationCount, 'Engineer Service' as Type from crpengineer T1 join crpengineerappliedservice T6 on T1.Id=T6.CrpEngineerId join crpservice T7 on T6.CmnServiceTypeId=T7.Id join cmncountry T2 on T1.CmnCountryId=T2.Id join cmnlistitem T4 on T1.CmnSalutationId=T4.Id join cmnlistitem T5 on T1.CmnServiceSectorTypeId=T5.Id join cmnlistitem T8 on T1.CmnTradeId=T8.Id left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id where T1.CmnApplicationRegistrationStatusId='".CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVEDFORPAYMENT."' and T1.SysLockedByUserId is null and T1.CrpEngineerId is not null";
				}
				if((int)$approvePaymentPrivilege->ReferenceNo == 410){
					if($approvePaymentServiceQuery!=""){
						$approvePaymentServiceQuery.=" union all ";
					}
					$approvePaymentServiceQuery.="select count(distinct T1.Id) as ApplicationCount, 'Specialized Trade Service' as Type from crpspecializedtrade T1 join crpspecializedtradeappliedservice T5 on T1.Id=T5.CrpSpecializedTradeId join crpservice T4 on T5.CmnServiceTypeId=T4.Id join cmnlistitem T3 on T1.CmnSalutationId=T3.Id join cmndzongkhag T2 on T1.CmnDzongkhagId=T2.Id where T1.CmnApplicationRegistrationStatusId='".CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVEDFORPAYMENT."' and T1.SysLockedByUserId is null and T1.CrpSpecializedTradeId is not null";
				}
			endforeach;
		}
		if($approvePaymentRegistrationQuery!=""){
			$applicantApprovePaymentRegistration = DB::select($approvePaymentRegistrationQuery);
		}
		if($approvePaymentServiceQuery!=""){
			$applicantApprovePaymentService = DB::select($approvePaymentServiceQuery);
		}
		/*END APPROVE PAYMENT*/
		if((int)$userApprovePrivilege>0){
			$userApproveCount = DB::table('sysregapplication')->where(DB::raw('coalesce(Status,0)'),0)->count();
		}

		$newsAndNotifications=SysNewsAndNotificationModel::where('MessageFor',1)->where('DisplayIn',2)->get(array('Message','Date'));
        return View::make('ezhotin.dashboard')
					->with('userApproveCount',$userApproveCount)
					->with('applicantVerifyPrivilegeCount',$applicantVerifyPrivilegeCount)
					->with('applicantApprovePrivilegeCount',$applicantApprovePrivilegeCount)
					->with('applicantApprovePaymentPrivilegeCount',$applicantApprovePaymentPrivilegeCount)
					->with('applicantVerifyRegistration',$applicantVerifyRegistration)
					->with('applicantVerifyService',$applicantVerifyService)
					->with('applicantApproveRegistration',$applicantApproveRegistration)
					->with('applicantApproveService',$applicantApproveService)
					->with('applicantApprovePaymentRegistration',$applicantApprovePaymentRegistration)
					->with('applicantApprovePaymentService',$applicantApprovePaymentService)
					->with('userApprovePrivilege',$userApprovePrivilege)
					->with('newsAndNotifications',$newsAndNotifications);
	}
	public function ezhotinRegistrationSuccess(){
		$applicationNo=Input::get('applicationno');
		$linkToPrint=Input::get('linktoprint');
		$printReference=Input::get('printreference');
		$finalLinktoPrint=$linkToPrint.'/'.$printReference;
		return View::make('crps.cmnregistrationsuccess')
					->with('applicationNo',$applicationNo)
					->with('linkToPrint',$finalLinktoPrint);
	}
	public function rejectedApplicationMessage(){
		return View::make('ezhotin.rejectedapplicationmessage');
	}
	public function adminNavOptions(){
		return View::make('sys.administratornavigationoptions');
	}
	public function etoolCinetNavOptions(){

		$module=Request::segment(1);
		$loggedInUser=Auth::user()->Id;


		$userRoles=DB::table('sysuserrolemap')->where('SysUserId',$loggedInUser)->lists('SysRoleId');

		$isCINET = false;
		if(in_array(CONST_ROLE_PROCURINGAGENCYCINET,$userRoles,true))
		{
			$isCINET = true;
		}

		return View::make('sys.dualmodulenavigationoptions')
						->with('isCINET',$isCINET);
	}
    public function getForgotPassword(){
        return View::make('ezhotin.forgotpassword');
    }
	public function refreshDashboard(){

		$verifyRegistrationApplications = array();
		$verifyServiceApplications = array();

        $approveRegistrationApplications = array();
        $approveServiceApplications = array();

        $approvePaymentRegistrationApplications = array();
        $approvePaymentServiceApplications = array();

        $verifyRegistrationQuery = "";
		$verifyServiceQuery = "";
		$approveRegistrationQuery = "";
		$approveServiceQuery = "";
		$approvePaymentRegistrationQuery = "";
		$approvePaymentServiceQuery = "";
		$userRoles = DB::table('sysuserrolemap as T1')
			->where('T1.SysUserId',Auth::user()->Id)
			->lists('T1.SysRoleId');
		$userApprovePrivilege = DB::table('sysrolemenumap as T1')
			->join('sysmenu as T2','T2.Id','=','T1.SysMenuId')
			->whereIn('T1.SysRoleId',$userRoles)
			->where('T2.ReferenceNo',101)
			->count();
		$applicantVerifyPrivilegeCount = DB::table('sysrolemenumap as T1')
			->join('sysmenu as T2','T2.Id','=','T1.SysMenuId')
			->whereIn('T1.SysRoleId',$userRoles)
			->whereIn('T2.ReferenceNo',array(201,202,203,204,205,206,207,208,209,210))
			->orderBy('T2.ReferenceNo')
			->get(array('T2.ReferenceNo',DB::raw("SUBSTRING_INDEX(T2.MenuRoute,'/',1) as Type")));
		if(count($applicantVerifyPrivilegeCount)>0){
			foreach($applicantVerifyPrivilegeCount as $verifyPrivilege):
				if((int)$verifyPrivilege->ReferenceNo == 201){
					$verifyRegistrationQuery.="select count(distinct T1.Id) as ApplicationCount,'201' as TypeCode, 'Contractor Registration' as Type from crpcontractor T1 join cmncountry T2 on T1.CmnCountryId=T2.Id join cmnlistitem T4 on T1.CmnOwnershipTypeId=T4.Id left join (crpcontractorworkclassification A join cmncontractorclassification B on A.CmnAppliedClassificationId=B.Id) on T1.Id=A.CrpContractorId and B.Priority=(select max(Priority) from crpcontractorworkclassification X join cmncontractorclassification Y on X.CmnAppliedClassificationId=Y.Id where X.CrpContractorId=T1.Id) left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id where T1.CmnApplicationRegistrationStatusId='".CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW."' and T1.SysLockedByUserId is null and T1.CrpContractorId is null";
				}
				if((int)$verifyPrivilege->ReferenceNo == 202){
					if($verifyRegistrationQuery!=""){
						$verifyRegistrationQuery.=" union all ";
					}
					$verifyRegistrationQuery.="select count(distinct T1.Id) as ApplicationCount,'202' as TypeCode, 'Consultant Registration' as Type from crpconsultant T1 join cmnlistitem T4 on T1.CmnOwnershipTypeId=T4.Id join cmncountry T2 on T1.CmnCountryId=T2.Id left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id where T1.CmnApplicationRegistrationStatusId='".CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW."' and T1.SysLockedByUserId is null and T1.CrpConsultantId is null";
				}
				if((int)$verifyPrivilege->ReferenceNo == 203){
					if($verifyRegistrationQuery!=""){
						$verifyRegistrationQuery.=" union all ";
					}
					$verifyRegistrationQuery.="select count(distinct T1.Id) as ApplicationCount,'203' as TypeCode, 'Architect Registration' as Type from crparchitect T1 join cmncountry T2 on T1.CmnCountryId=T2.Id join cmnlistitem T4 on T1.CmnSalutationId=T4.Id join cmnlistitem T5 on T1.CmnServiceSectorTypeId=T5.Id left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id where T1.CmnApplicationRegistrationStatusId='".CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW."' and T1.SysLockedByUserId is null and T1.CrpArchitectId is null";
				}
				if((int)$verifyPrivilege->ReferenceNo == 204){
					if($verifyRegistrationQuery!=""){
						$verifyRegistrationQuery.=" union all ";
					}
					$verifyRegistrationQuery.="select count(distinct T1.Id) as ApplicationCount,'204' as TypeCode, 'Engineer Registration' as Type from crpengineer T1 join cmncountry T2 on T1.CmnCountryId=T2.Id left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id join cmnlistitem T4 on T1.CmnSalutationId=T4.Id join cmnlistitem T5 on T1.CmnServiceSectorTypeId=T5.Id join cmnlistitem T6 on T1.CmnTradeId=T6.Id where T1.CmnApplicationRegistrationStatusId='".CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW."' and T1.SysLockedByUserId is null and T1.CrpEngineerId is null";
				}
				if((int)$verifyPrivilege->ReferenceNo == 205){
					if($verifyRegistrationQuery!=""){
						$verifyRegistrationQuery.=" union all ";
					}
					$verifyRegistrationQuery.="select count(distinct T1.Id) as ApplicationCount, '205' as TypeCode, 'Specialized Trade Registration' as Type from crpspecializedtrade T1 join cmndzongkhag T2 on T1.CmnDzongkhagId=T2.Id join cmnlistitem T3 on T1.CmnSalutationId=T3.Id where T1.CmnApplicationRegistrationStatusId='".CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW."' and T1.SysLockedByUserId is null and T1.CrpSpecializedTradeId is null";
				}
				if((int)$verifyPrivilege->ReferenceNo == 206){
					$verifyServiceQuery.="select count(distinct T1.Id) as ApplicationCount, '206' as TypeCode, 'Contractor Service' as Type from crpcontractor T1 join cmncountry T2 on T1.CmnCountryId=T2.Id join cmnlistitem T4 on T1.CmnOwnershipTypeId=T4.Id join crpcontractorfinal T7 on T1.CrpContractorId=T7.Id join crpcontractorappliedservice T5 on T1.Id=T5.CrpContractorId join crpservice ST on T5.CmnServiceTypeId=ST.Id left join (crpcontractorworkclassification A join cmncontractorclassification B on A.CmnAppliedClassificationId=B.Id) on T1.Id=A.CrpContractorId and B.Priority=(select max(Priority) from crpcontractorworkclassification X join cmncontractorclassification Y on X.CmnAppliedClassificationId=Y.Id where X.CrpContractorId=T1.Id) left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id where T1.CmnApplicationRegistrationStatusId='".CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW."' and T1.SysLockedByUserId is null and T1.CrpContractorId is not null";
				}
				if((int)$verifyPrivilege->ReferenceNo == 207){
					if($verifyServiceQuery!=""){
						$verifyServiceQuery.=" union all ";
					}
					$verifyServiceQuery.="select count(distinct T1.Id) as ApplicationCount,'207' as TypeCode, 'Consultant Service' as Type from crpconsultant T1 join cmnlistitem T4 on T1.CmnOwnershipTypeId=T4.Id join crpconsultantfinal T7 on T1.CrpConsultantId=T7.Id join cmncountry T2 on T1.CmnCountryId=T2.Id join crpconsultantappliedservice T5 on T1.Id=T5.CrpConsultantId join crpservice T6 on T5.CmnServiceTypeId=T6.Id left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id where T1.CmnApplicationRegistrationStatusId='".CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW."' and T1.SysLockedByUserId is null and T1.CrpConsultantId is not null";
				}
				if((int)$verifyPrivilege->ReferenceNo == 208){
					if($verifyServiceQuery!=""){
						$verifyServiceQuery.=" union all ";
					}
					$verifyServiceQuery.="select count(distinct T1.Id) as ApplicationCount, '208' as TypeCode, 'Architect Service' as Type from crparchitect T1 join crparchitectappliedservice T6 on T1.Id=T6.CrpArchitectId join crpservice T7 on T6.CmnServiceTypeId=T7.Id join cmncountry T2 on T1.CmnCountryId=T2.Id join cmnlistitem T4 on T1.CmnSalutationId=T4.Id join cmnlistitem T5 on T1.CmnServiceSectorTypeId=T5.Id left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id where T1.CmnApplicationRegistrationStatusId='".CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW."' and T1.SysLockedByUserId is null and T1.CrpArchitectId is not null";
				}
				if((int)$verifyPrivilege->ReferenceNo == 209){
					if($verifyServiceQuery!=""){
						$verifyServiceQuery.=" union all ";
					}
					$verifyServiceQuery.="select count(distinct T1.Id) as ApplicationCount, '209' as TypeCode, 'Engineer Service' as Type from crpengineer T1 join crpengineerappliedservice T6 on T1.Id=T6.CrpEngineerId join crpservice T7 on T6.CmnServiceTypeId=T7.Id join cmncountry T2 on T1.CmnCountryId=T2.Id join cmnlistitem T4 on T1.CmnSalutationId=T4.Id join cmnlistitem T5 on T1.CmnServiceSectorTypeId=T5.Id join cmnlistitem T8 on T1.CmnTradeId=T8.Id left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id where T1.CmnApplicationRegistrationStatusId='".CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW."' and T1.SysLockedByUserId is null and T1.CrpEngineerId is not null";
				}
				if((int)$verifyPrivilege->ReferenceNo == 210){
					if($verifyServiceQuery!=""){
						$verifyServiceQuery.=" union all ";
					}
					$verifyServiceQuery.="select count(distinct T1.Id) as ApplicationCount, '210' as TypeCode, 'Specialized Trade Service' as Type from crpspecializedtrade T1 join crpspecializedtradeappliedservice T5 on T1.Id=T5.CrpSpecializedTradeId join crpservice T4 on T5.CmnServiceTypeId=T4.Id join cmnlistitem T3 on T1.CmnSalutationId=T3.Id join cmndzongkhag T2 on T1.CmnDzongkhagId=T2.Id where T1.CmnApplicationRegistrationStatusId='".CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW."' and T1.SysLockedByUserId is null and T1.CrpSpecializedTradeId is not null";
				}
			endforeach;
		}

		if($verifyRegistrationQuery!=""){
            $verifyRegistrationQuery = str_replace(' union all',' and coalesce(T1.HasNotification,0) <> 1 union all',$verifyRegistrationQuery);
            $verifyRegistrationQuery.=" and coalesce(T1.HasNotification,0) <> 1";

            $query = str_replace("count(distinct T1.Id) as ApplicationCount","distinct T1.Id,T1.ReferenceNo as ApplicationNo, T1.CmnApplicationRegistrationStatusId",$verifyRegistrationQuery);
            $verifyRegistrationApplications = DB::select($query);
		}
		if($verifyServiceQuery!=""){
            $verifyServiceQuery = str_replace(' union all',' and coalesce(T1.HasNotification,0) <> 1 union all',$verifyServiceQuery);
            $verifyServiceQuery.=" and coalesce(T1.HasNotification,0) <> 1";

            $query = str_replace("count(distinct T1.Id) as ApplicationCount","distinct T1.Id,T1.ReferenceNo as ApplicationNo, T1.CmnApplicationRegistrationStatusId",$verifyServiceQuery);
            $verifyServiceApplications = DB::select($query);
		}
        $verifyApplications = array_merge($verifyRegistrationApplications,$verifyServiceApplications);
        foreach($verifyApplications as $application):
            $typeCode = $application->TypeCode;
            $id = $application->Id;
            $applicationNo = $application->ApplicationNo;
			$applicationStatusId = $application->CmnApplicationRegistrationStatusId;

            if((int)$typeCode == 201 || (int)$typeCode == 206){
                $table = "crpcontractor";
                $message = "Application # $applicationNo (Contractor Registration) is pending verification";

                if((int)$typeCode == 206){
                    $detailQuery = DB::table('crpcontractor as T1')->join('crpcontractor as B','B.Id','=','T1.CrpContractorId')
                        ->leftJoin('crpcontractorappliedservice as A','T1.Id','=','A.CrpContractorId')
                        ->leftJoin('crpservice as T2','T2.Id','=','A.CmnServiceTypeId')
                        ->where('T1.Id',$id)
                        ->select(DB::raw("GROUP_CONCAT(T2.Name SEPARATOR ', ') as Services"),'B.CDBNo','T1.ApplicationDate')
                        ->get();
                    $cdbNo = $detailQuery[0]->CDBNo;
                    $services = $detailQuery[0]->Services;
                    $applicationDate = convertDateToClientFormat($detailQuery[0]->ApplicationDate);
                    $message = "Application # $applicationNo (Contractor $cdbNo) has applied for $services on $applicationDate. Verification is pending";
                }
            }
            if((int)$typeCode == 202 || (int)$typeCode == 207){
                $table = "crpconsultant";
                $message = "Application # $applicationNo (Consultant Registration) is pending verification";

                if((int)$typeCode == 207){
                    $detailQuery = DB::table('crpconsultant as T1')->join('crpconsultant as B','B.Id','=','T1.CrpConsultantId')
                        ->leftJoin('crpconsultantappliedservice as A','T1.Id','=','A.CrpConsultantId')
                        ->leftJoin('crpservice as T2','T2.Id','=','A.CmnServiceTypeId')
                        ->where('T1.Id',$id)
                        ->select(DB::raw("GROUP_CONCAT(T2.Name SEPARATOR ', ') as Services"),'B.CDBNo','T1.ApplicationDate')
                        ->get();
                    $cdbNo = $detailQuery[0]->CDBNo;
                    $services = $detailQuery[0]->Services;
                    $applicationDate = convertDateToClientFormat($detailQuery[0]->ApplicationDate);
                    $message = "Application # $applicationNo (Consultant $cdbNo) has applied for $services on $applicationDate. Verification is pending";
                }
            }
            if((int)$typeCode == 203 || (int)$typeCode == 208){
                $table = "crparchitect";
                $message = "Application # $applicationNo (Architect Registration) is pending verification";

                if((int)$typeCode == 208){
                    $detailQuery = DB::table('crparchitect as T1')->join('crparchitect as B','B.Id','=','T1.CrpArchitectId')
                        ->leftJoin('crparchitectappliedservice as A','T1.Id','=','A.CrpArchitectId')
                        ->leftJoin('crpservice as T2','T2.Id','=','A.CmnServiceTypeId')
                        ->where('T1.Id',$id)
                        ->select(DB::raw("GROUP_CONCAT(T2.Name SEPARATOR ', ') as Services"),'B.ARNo','T1.ApplicationDate')
                        ->get();
                    $cdbNo = $detailQuery[0]->ARNo;
                    $services = $detailQuery[0]->Services;
                    $applicationDate = convertDateToClientFormat($detailQuery[0]->ApplicationDate);
                    $message = "Application # $applicationNo (Architect $cdbNo) has applied for $services on $applicationDate. Verification is pending";
                }
            }
            if((int)$typeCode == 204 || (int)$typeCode == 209){
                $table = "crpengineer";
                $message = "Application # $applicationNo (Engineer Registration) is pending verification";

                if((int)$typeCode == 209){
                    $detailQuery = DB::table('crpengineer as T1')->join('crpengineer as B','B.Id','=','T1.CrpEngineerId')
                        ->leftJoin('crpengineerappliedservice as A','T1.Id','=','A.CrpEngineerId')
                        ->leftJoin('crpservice as T2','T2.Id','=','A.CmnServiceTypeId')
                        ->where('T1.Id',$id)
                        ->select(DB::raw("GROUP_CONCAT(T2.Name SEPARATOR ', ') as Services"),'B.CDBNo','T1.ApplicationDate')
                        ->get();
                    $cdbNo = $detailQuery[0]->CDBNo;
                    $services = $detailQuery[0]->Services;
                    $applicationDate = convertDateToClientFormat($detailQuery[0]->ApplicationDate);
                    $message = "Application # $applicationNo (Engineer $cdbNo) has applied for $services on $applicationDate. Verification is pending";
                }
            }
            if((int)$typeCode == 205 || (int)$typeCode == 210){
                $table = "crpspecializedtrade";
                $message = "Application # $applicationNo (Specialized Trade Registration) is pending verification";

                if((int)$typeCode == 210){
                    $detailQuery = DB::table('crpspecializedtrade as T1')->join('crpspecializedtrade as B','B.Id','=','T1.CrpSpecializedTradeId')
                        ->leftJoin('crpspecializedtradeappliedservice as A','T1.Id','=','A.CrpSpecializedTradeId')
                        ->leftJoin('crpservice as T2','T2.Id','=','A.CmnServiceTypeId')
                        ->where('T1.Id',$id)
                        ->select(DB::raw("GROUP_CONCAT(T2.Name SEPARATOR ', ') as Services"),'B.SPNo','T1.ApplicationDate')
                        ->get();
                    $cdbNo = $detailQuery[0]->SPNo;
                    $services = $detailQuery[0]->Services;
                    $applicationDate = convertDateToClientFormat($detailQuery[0]->ApplicationDate);
                    $message = "Application # $applicationNo (Specialized Trade $cdbNo) has applied for $services on $applicationDate. Verification is pending";
                }
            }

            DB::table($table)->where('Id',$id)->update(array('HasNotification'=>1));
            DB::table("sysapplicationnotification")->insert(array("Id"=>$this->UUID(),'IsRead'=>0,'NotificationTime'=>date("Y-m-d G:i:s"),"Message"=>$message,"ApplicationId"=>$id,"CmnApplicationStatusId"=>$applicationStatusId,'TypeCode'=>(int)$typeCode));
        endforeach;

		$applicantApprovePrivilegeCount = DB::table('sysrolemenumap as T1')
			->join('sysmenu as T2','T2.Id','=','T1.SysMenuId')
			->whereIn('T1.SysRoleId',$userRoles)
			->whereIn('T2.ReferenceNo',array(301,302,303,304,305,306,307,308,309,310))
			->orderBy('T2.ReferenceNo')
			->get(array('T2.ReferenceNo'));
		if(count($applicantApprovePrivilegeCount)>0){
			foreach($applicantApprovePrivilegeCount as $approvePrivilege):
				if((int)$approvePrivilege->ReferenceNo == 301){
					$approveRegistrationQuery.="select count(distinct T1.Id) as ApplicationCount, '301' as TypeCode, 'Contractor Registration' as Type from crpcontractor T1 join cmncountry T2 on T1.CmnCountryId=T2.Id join cmnlistitem T4 on T1.CmnOwnershipTypeId=T4.Id left join (crpcontractorworkclassification A join cmncontractorclassification B on A.CmnAppliedClassificationId=B.Id) on T1.Id=A.CrpContractorId and B.Priority=(select max(Priority) from crpcontractorworkclassification X join cmncontractorclassification Y on X.CmnAppliedClassificationId=Y.Id where X.CrpContractorId=T1.Id) left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id where T1.CmnApplicationRegistrationStatusId='".CONST_CMN_APPLICATIONREGISTRATIONSTATUS_VERIFIED."' and T1.SysLockedByUserId is null and T1.CrpContractorId is null";
				}
				if((int)$approvePrivilege->ReferenceNo == 302){
					if($approveRegistrationQuery!=""){
						$approveRegistrationQuery.=" union all ";
					}
					$approveRegistrationQuery.="select count(distinct T1.Id) as ApplicationCount, '302' as TypeCode, 'Consultant Registration' as Type from crpconsultant T1 join cmnlistitem T4 on T1.CmnOwnershipTypeId=T4.Id join cmncountry T2 on T1.CmnCountryId=T2.Id left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id where T1.CmnApplicationRegistrationStatusId='".CONST_CMN_APPLICATIONREGISTRATIONSTATUS_VERIFIED."' and T1.SysLockedByUserId is null and T1.CrpConsultantId is null";
				}
				if((int)$approvePrivilege->ReferenceNo == 303){
					if($approveRegistrationQuery!=""){
						$approveRegistrationQuery.=" union all ";
					}
					$approveRegistrationQuery.="select count(distinct T1.Id) as ApplicationCount, '303' as TypeCode, 'Architect Registration' as Type from crparchitect T1 join cmncountry T2 on T1.CmnCountryId=T2.Id join cmnlistitem T4 on T1.CmnSalutationId=T4.Id join cmnlistitem T5 on T1.CmnServiceSectorTypeId=T5.Id left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id where T1.CmnApplicationRegistrationStatusId='".CONST_CMN_APPLICATIONREGISTRATIONSTATUS_VERIFIED."' and T1.SysLockedByUserId is null and T1.CrpArchitectId is null";
				}
				if((int)$approvePrivilege->ReferenceNo == 304){
					if($approveRegistrationQuery!=""){
						$approveRegistrationQuery.=" union all ";
					}
					$approveRegistrationQuery.="select count(distinct T1.Id) as ApplicationCount, '304' as TypeCode, 'Engineer Registration' as Type from crpengineer T1 join cmncountry T2 on T1.CmnCountryId=T2.Id left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id join cmnlistitem T4 on T1.CmnSalutationId=T4.Id join cmnlistitem T5 on T1.CmnServiceSectorTypeId=T5.Id join cmnlistitem T6 on T1.CmnTradeId=T6.Id where T1.CmnApplicationRegistrationStatusId='".CONST_CMN_APPLICATIONREGISTRATIONSTATUS_VERIFIED."' and T1.SysLockedByUserId is null and T1.CrpEngineerId is null";
				}
				if((int)$approvePrivilege->ReferenceNo == 305){
					if($approveRegistrationQuery!=""){
						$approveRegistrationQuery.=" union all ";
					}
					$approveRegistrationQuery.="select count(distinct T1.Id) as ApplicationCount, '305' as TypeCode, 'Specialized Trade Registration' as Type from crpspecializedtrade T1 join cmndzongkhag T2 on T1.CmnDzongkhagId=T2.Id join cmnlistitem T3 on T1.CmnSalutationId=T3.Id where T1.CmnApplicationRegistrationStatusId='".CONST_CMN_APPLICATIONREGISTRATIONSTATUS_VERIFIED."' and T1.SysLockedByUserId is null and T1.CrpSpecializedTradeId is null";
				}
				if((int)$approvePrivilege->ReferenceNo == 306){
					$approveServiceQuery.="select count(distinct T1.Id) as ApplicationCount, '306' as TypeCode, 'Contractor Service' as Type from crpcontractor T1 join cmncountry T2 on T1.CmnCountryId=T2.Id join cmnlistitem T4 on T1.CmnOwnershipTypeId=T4.Id join crpcontractorfinal T7 on T1.CrpContractorId=T7.Id join crpcontractorappliedservice T5 on T1.Id=T5.CrpContractorId join crpservice ST on T5.CmnServiceTypeId=ST.Id left join (crpcontractorworkclassification A join cmncontractorclassification B on A.CmnAppliedClassificationId=B.Id) on T1.Id=A.CrpContractorId and B.Priority=(select max(Priority) from crpcontractorworkclassification X join cmncontractorclassification Y on X.CmnAppliedClassificationId=Y.Id where X.CrpContractorId=T1.Id) left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id where T1.CmnApplicationRegistrationStatusId='".CONST_CMN_APPLICATIONREGISTRATIONSTATUS_VERIFIED."' and T1.SysLockedByUserId is null and T1.CrpContractorId is not null";
				}
				if((int)$approvePrivilege->ReferenceNo == 307){
					if($approveServiceQuery!=""){
						$approveServiceQuery.=" union all ";
					}
					$approveServiceQuery.="select count(distinct T1.Id) as ApplicationCount, '307' as TypeCode, 'Consultant Service' as Type from crpconsultant T1 join cmnlistitem T4 on T1.CmnOwnershipTypeId=T4.Id join crpconsultantfinal T7 on T1.CrpConsultantId=T7.Id join cmncountry T2 on T1.CmnCountryId=T2.Id join crpconsultantappliedservice T5 on T1.Id=T5.CrpConsultantId join crpservice T6 on T5.CmnServiceTypeId=T6.Id left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id where T1.CmnApplicationRegistrationStatusId='".CONST_CMN_APPLICATIONREGISTRATIONSTATUS_VERIFIED."' and T1.SysLockedByUserId is null and T1.CrpConsultantId is not null";
				}
				if((int)$approvePrivilege->ReferenceNo == 308){
					if($approveServiceQuery!=""){
						$approveServiceQuery.=" union all ";
					}
					$approveServiceQuery.="select count(distinct T1.Id) as ApplicationCount, '308' as TypeCode, 'Architect Service' as Type from crparchitect T1 join crparchitectappliedservice T6 on T1.Id=T6.CrpArchitectId join crpservice T7 on T6.CmnServiceTypeId=T7.Id join cmncountry T2 on T1.CmnCountryId=T2.Id join cmnlistitem T4 on T1.CmnSalutationId=T4.Id join cmnlistitem T5 on T1.CmnServiceSectorTypeId=T5.Id left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id where T1.CmnApplicationRegistrationStatusId='".CONST_CMN_APPLICATIONREGISTRATIONSTATUS_VERIFIED."' and T1.SysLockedByUserId is null and T1.CrpArchitectId is not null";
				}
				if((int)$approvePrivilege->ReferenceNo == 309){
					if($approveServiceQuery!=""){
						$approveServiceQuery.=" union all ";
					}
					$approveServiceQuery.="select count(distinct T1.Id) as ApplicationCount, '309' as TypeCode, 'Engineer Service' as Type from crpengineer T1 join crpengineerappliedservice T6 on T1.Id=T6.CrpEngineerId join crpservice T7 on T6.CmnServiceTypeId=T7.Id join cmncountry T2 on T1.CmnCountryId=T2.Id join cmnlistitem T4 on T1.CmnSalutationId=T4.Id join cmnlistitem T5 on T1.CmnServiceSectorTypeId=T5.Id join cmnlistitem T8 on T1.CmnTradeId=T8.Id left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id where T1.CmnApplicationRegistrationStatusId='".CONST_CMN_APPLICATIONREGISTRATIONSTATUS_VERIFIED."' and T1.SysLockedByUserId is null and T1.CrpEngineerId is not null";
				}
				if((int)$approvePrivilege->ReferenceNo == 310){
					if($approveServiceQuery!=""){
						$approveServiceQuery.=" union all ";
					}
					$approveServiceQuery.="select count(distinct T1.Id) as ApplicationCount, '310' as TypeCode, 'Specialized Trade Service' as Type from crpspecializedtrade T1 join crpspecializedtradeappliedservice T5 on T1.Id=T5.CrpSpecializedTradeId join crpservice T4 on T5.CmnServiceTypeId=T4.Id join cmnlistitem T3 on T1.CmnSalutationId=T3.Id join cmndzongkhag T2 on T1.CmnDzongkhagId=T2.Id where T1.CmnApplicationRegistrationStatusId='".CONST_CMN_APPLICATIONREGISTRATIONSTATUS_VERIFIED."' and T1.SysLockedByUserId is null and T1.CrpSpecializedTradeId is not null";
				}
			endforeach;
		}
		if($approveRegistrationQuery!=""){
            $approveRegistrationQuery = str_replace(' union all',' and coalesce(T1.HasNotification,0) <> 1 union all',$approveRegistrationQuery);
            $approveRegistrationQuery.=" and coalesce(T1.HasNotification,0) <> 1";

            $query = str_replace("count(distinct T1.Id) as ApplicationCount","distinct T1.Id,T1.ReferenceNo as ApplicationNo, T1.CmnApplicationRegistrationStatusId",$approveRegistrationQuery);
            $approveRegistrationApplications = DB::select($query);
		}
		if($approveServiceQuery!=""){
            $approveServiceQuery = str_replace(' union all',' and coalesce(T1.HasNotification,0) <> 1 union all',$approveServiceQuery);
            $approveServiceQuery.=" and coalesce(T1.HasNotification,0) <> 1";

            $query = str_replace("count(distinct T1.Id) as ApplicationCount","distinct T1.Id,T1.ReferenceNo as ApplicationNo, T1.CmnApplicationRegistrationStatusId",$approveServiceQuery);
            $approveServiceApplications = DB::select($query);
		}

        $approveApplications = array_merge($approveRegistrationApplications,$approveServiceApplications);
        foreach($approveApplications as $application):
            $typeCode = $application->TypeCode;
            $id = $application->Id;
            $applicationNo = $application->ApplicationNo;
			$applicationStatusId = $application->CmnApplicationRegistrationStatusId;

            if((int)$typeCode == 301 || (int)$typeCode == 306){
                $table = "crpcontractor";
                $message = "Application # $applicationNo (Contractor Registration) is pending approval";

                if((int)$typeCode == 306){
                    $detailQuery = DB::table('crpcontractor as T1')->join('crpcontractor as B','B.Id','=','T1.CrpContractorId')
                        ->leftJoin('crpcontractorappliedservice as A','T1.Id','=','A.CrpContractorId')
                        ->leftJoin('crpservice as T2','T2.Id','=','A.CmnServiceTypeId')
                        ->where('T1.Id',$id)
                        ->select(DB::raw("GROUP_CONCAT(T2.Name SEPARATOR ', ') as Services"),'B.CDBNo','T1.ApplicationDate')
                        ->get();
                    $cdbNo = $detailQuery[0]->CDBNo;
                    $services = $detailQuery[0]->Services;
                    $applicationDate = convertDateToClientFormat($detailQuery[0]->ApplicationDate);
                    $message = "Application # $applicationNo (Contractor $cdbNo) has applied for $services on $applicationDate. Approval is pending";
                }
            }
            if((int)$typeCode == 302 || (int)$typeCode == 307){
                $table = "crpconsultant";
                $message = "Application # $applicationNo (Consultant Registration) is pending approval";

                if((int)$typeCode == 307){
                    $detailQuery = DB::table('crpconsultant as T1')->join('crpconsultant as B','B.Id','=','T1.CrpConsultantId')
                        ->leftJoin('crpconsultantappliedservice as A','T1.Id','=','A.CrpConsultantId')
                        ->leftJoin('crpservice as T2','T2.Id','=','A.CmnServiceTypeId')
                        ->where('T1.Id',$id)
                        ->select(DB::raw("GROUP_CONCAT(T2.Name SEPARATOR ', ') as Services"),'B.CDBNo','T1.ApplicationDate')
                        ->get();
                    $cdbNo = $detailQuery[0]->CDBNo;
                    $services = $detailQuery[0]->Services;
                    $applicationDate = convertDateToClientFormat($detailQuery[0]->ApplicationDate);
                    $message = "Application # $applicationNo (Consultant $cdbNo) has applied for $services on $applicationDate. Approval is pending";
                }
            }
            if((int)$typeCode == 303 || (int)$typeCode == 308){
                $table = "crparchitect";
                $message = "Application # $applicationNo (Architect Registration) is pending approval";

                if((int)$typeCode == 308){
                    $detailQuery = DB::table('crparchitect as T1')->join('crparchitect as B','B.Id','=','T1.CrpArchitectId')
                        ->leftJoin('crparchitectappliedservice as A','T1.Id','=','A.CrpArchitectId')
                        ->leftJoin('crpservice as T2','T2.Id','=','A.CmnServiceTypeId')
                        ->where('T1.Id',$id)
                        ->select(DB::raw("GROUP_CONCAT(T2.Name SEPARATOR ', ') as Services"),'B.ARNo','T1.ApplicationDate')
                        ->get();
                    $cdbNo = $detailQuery[0]->ARNo;
                    $services = $detailQuery[0]->Services;
                    $applicationDate = convertDateToClientFormat($detailQuery[0]->ApplicationDate);
                    $message = "Application # $applicationNo (Architect $cdbNo) has applied for $services on $applicationDate. Approval is pending";
                }
            }
            if((int)$typeCode == 304 || (int)$typeCode == 309){
                $table = "crpengineer";
                $message = "Application # $applicationNo (Engineer Registration) is pending approval";

                if((int)$typeCode == 309){
                    $detailQuery = DB::table('crpengineer as T1')->join('crpengineer as B','B.Id','=','T1.CrpEngineerId')
                        ->leftJoin('crpengineerappliedservice as A','T1.Id','=','A.CrpEngineerId')
                        ->leftJoin('crpservice as T2','T2.Id','=','A.CmnServiceTypeId')
                        ->where('T1.Id',$id)
                        ->select(DB::raw("GROUP_CONCAT(T2.Name SEPARATOR ', ') as Services"),'B.CDBNo','T1.ApplicationDate')
                        ->get();
                    $cdbNo = $detailQuery[0]->CDBNo;
                    $services = $detailQuery[0]->Services;
                    $applicationDate = convertDateToClientFormat($detailQuery[0]->ApplicationDate);
                    $message = "Application # $applicationNo (Engineer $cdbNo) has applied for $services on $applicationDate. Approval is pending";
                }
            }
            if((int)$typeCode == 305 || (int)$typeCode == 310){
                $table = "crpspecializedtrade";
                $message = "Application # $applicationNo (Specialized Trade Registration) is pending approval";

                if((int)$typeCode == 310){
                    $detailQuery = DB::table('crpspecializedtrade as T1')->join('crpspecializedtrade as B','B.Id','=','T1.CrpSpecializedTradeId')
                        ->leftJoin('crpspecializedtradeappliedservice as A','T1.Id','=','A.CrpSpecializedTradeId')
                        ->leftJoin('crpservice as T2','T2.Id','=','A.CmnServiceTypeId')
                        ->where('T1.Id',$id)
                        ->select(DB::raw("GROUP_CONCAT(T2.Name SEPARATOR ', ') as Services"),'B.SPNo','T1.ApplicationDate')
                        ->get();
                    $cdbNo = $detailQuery[0]->SPNo;
                    $services = $detailQuery[0]->Services;
                    $applicationDate = convertDateToClientFormat($detailQuery[0]->ApplicationDate);
                    $message = "Application # $applicationNo (Specialized Trade $cdbNo) has applied for $services on $applicationDate. Approval is pending";
                }
            }

            DB::table($table)->where('Id',$id)->update(array('HasNotification'=>1));
            DB::table("sysapplicationnotification")->insert(array("Id"=>$this->UUID(),'IsRead'=>0,'NotificationTime'=>date("Y-m-d G:i:s"),"Message"=>$message,"ApplicationId"=>$id,"CmnApplicationStatusId"=>$applicationStatusId,'TypeCode'=>(int)$typeCode));
        endforeach;

		/*APPROVE PAYMENT*/
		$applicantApprovePaymentPrivilegeCount = DB::table('sysrolemenumap as T1')
			->join('sysmenu as T2','T2.Id','=','T1.SysMenuId')
			->whereIn('T1.SysRoleId',$userRoles)
			->whereIn('T2.ReferenceNo',array(401,402,403,404,406,407,408,409,410))
			->orderBy('T2.ReferenceNo')
			->get(array('T2.ReferenceNo'));
		if(count($applicantApprovePaymentPrivilegeCount)>0){
			foreach($applicantApprovePaymentPrivilegeCount as $approvePaymentPrivilege):
				if((int)$approvePaymentPrivilege->ReferenceNo == 401){
					$approvePaymentRegistrationQuery.="select count(distinct T1.Id) as ApplicationCount,'401' as TypeCode, 'Contractor Registration' as Type from crpcontractor T1 join cmncountry T2 on T1.CmnCountryId=T2.Id join cmnlistitem T4 on T1.CmnOwnershipTypeId=T4.Id left join (crpcontractorworkclassification A join cmncontractorclassification B on A.CmnAppliedClassificationId=B.Id) on T1.Id=A.CrpContractorId and B.Priority=(select max(Priority) from crpcontractorworkclassification X join cmncontractorclassification Y on X.CmnAppliedClassificationId=Y.Id where X.CrpContractorId=T1.Id) left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id where T1.CmnApplicationRegistrationStatusId='".CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVEDFORPAYMENT."' and T1.SysLockedByUserId is null and T1.CrpContractorId is null";
				}
				if((int)$approvePaymentPrivilege->ReferenceNo == 402){
					if($approvePaymentRegistrationQuery!=""){
						$approvePaymentRegistrationQuery.=" union all ";
					}
					$approvePaymentRegistrationQuery.="select count(distinct T1.Id) as ApplicationCount,'402' as TypeCode, 'Consultant Registration' as Type from crpconsultant T1 join cmnlistitem T4 on T1.CmnOwnershipTypeId=T4.Id join cmncountry T2 on T1.CmnCountryId=T2.Id left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id where T1.CmnApplicationRegistrationStatusId='".CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVEDFORPAYMENT."' and T1.SysLockedByUserId is null and T1.CrpConsultantId is null";
				}
				if((int)$approvePaymentPrivilege->ReferenceNo == 403){
					if($approvePaymentRegistrationQuery!=""){
						$approvePaymentRegistrationQuery.=" union all ";
					}
					$approvePaymentRegistrationQuery.="select count(distinct T1.Id) as ApplicationCount,'403' as TypeCode, 'Architect Registration' as Type from crparchitect T1 join cmncountry T2 on T1.CmnCountryId=T2.Id join cmnlistitem T4 on T1.CmnSalutationId=T4.Id join cmnlistitem T5 on T1.CmnServiceSectorTypeId=T5.Id left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id where T1.CmnApplicationRegistrationStatusId='".CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVEDFORPAYMENT."' and T1.SysLockedByUserId is null and T1.CrpArchitectId is null";
				}
				if((int)$approvePaymentPrivilege->ReferenceNo == 404){
					if($approvePaymentRegistrationQuery!=""){
						$approvePaymentRegistrationQuery.=" union all ";
					}
					$approvePaymentRegistrationQuery.="select count(distinct T1.Id) as ApplicationCount,'404' as TypeCode, 'Engineer Registration' as Type from crpengineer T1 join cmncountry T2 on T1.CmnCountryId=T2.Id left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id join cmnlistitem T4 on T1.CmnSalutationId=T4.Id join cmnlistitem T5 on T1.CmnServiceSectorTypeId=T5.Id join cmnlistitem T6 on T1.CmnTradeId=T6.Id where T1.CmnApplicationRegistrationStatusId='".CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVEDFORPAYMENT."' and T1.SysLockedByUserId is null and T1.CrpEngineerId is null";
				}
				if((int)$approvePaymentPrivilege->ReferenceNo == 406){
					$approvePaymentServiceQuery.="select count(distinct T1.Id) as ApplicationCount,'406' as TypeCode, 'Contractor Service' as Type from crpcontractor T1 join cmncountry T2 on T1.CmnCountryId=T2.Id join cmnlistitem T4 on T1.CmnOwnershipTypeId=T4.Id join crpcontractorfinal T7 on T1.CrpContractorId=T7.Id join crpcontractorappliedservice T5 on T1.Id=T5.CrpContractorId join crpservice ST on T5.CmnServiceTypeId=ST.Id left join (crpcontractorworkclassification A join cmncontractorclassification B on A.CmnAppliedClassificationId=B.Id) on T1.Id=A.CrpContractorId and B.Priority=(select max(Priority) from crpcontractorworkclassification X join cmncontractorclassification Y on X.CmnAppliedClassificationId=Y.Id where X.CrpContractorId=T1.Id) left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id where T1.CmnApplicationRegistrationStatusId='".CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVEDFORPAYMENT."' and T1.SysLockedByUserId is null and T1.CrpContractorId is not null";
				}
				if((int)$approvePaymentPrivilege->ReferenceNo == 407){
					if($approvePaymentServiceQuery!=""){
						$approvePaymentServiceQuery.=" union all ";
					}
					$approvePaymentServiceQuery.="select count(distinct T1.Id) as ApplicationCount,'407' as TypeCode, 'Consultant Service' as Type from crpconsultant T1 join cmnlistitem T4 on T1.CmnOwnershipTypeId=T4.Id join crpconsultantfinal T7 on T1.CrpConsultantId=T7.Id join cmncountry T2 on T1.CmnCountryId=T2.Id join crpconsultantappliedservice T5 on T1.Id=T5.CrpConsultantId join crpservice T6 on T5.CmnServiceTypeId=T6.Id left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id where T1.CmnApplicationRegistrationStatusId='".CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVEDFORPAYMENT."' and T1.SysLockedByUserId is null and T1.CrpConsultantId is not null";
				}
				if((int)$approvePaymentPrivilege->ReferenceNo == 408){
					if($approvePaymentServiceQuery!=""){
						$approvePaymentServiceQuery.=" union all ";
					}
					$approvePaymentServiceQuery.="select count(distinct T1.Id) as ApplicationCount,'408' as TypeCode, 'Architect Service' as Type from crparchitect T1 join crparchitectappliedservice T6 on T1.Id=T6.CrpArchitectId join crpservice T7 on T6.CmnServiceTypeId=T7.Id join cmncountry T2 on T1.CmnCountryId=T2.Id join cmnlistitem T4 on T1.CmnSalutationId=T4.Id join cmnlistitem T5 on T1.CmnServiceSectorTypeId=T5.Id left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id where T1.CmnApplicationRegistrationStatusId='".CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVEDFORPAYMENT."' and T1.SysLockedByUserId is null and T1.CrpArchitectId is not null";
				}
				if((int)$approvePaymentPrivilege->ReferenceNo == 409){
					if($approvePaymentServiceQuery!=""){
						$approvePaymentServiceQuery.=" union all ";
					}
					$approvePaymentServiceQuery.="select count(distinct T1.Id) as ApplicationCount,'409' as TypeCode, 'Engineer Service' as Type from crpengineer T1 join crpengineerappliedservice T6 on T1.Id=T6.CrpEngineerId join crpservice T7 on T6.CmnServiceTypeId=T7.Id join cmncountry T2 on T1.CmnCountryId=T2.Id join cmnlistitem T4 on T1.CmnSalutationId=T4.Id join cmnlistitem T5 on T1.CmnServiceSectorTypeId=T5.Id join cmnlistitem T8 on T1.CmnTradeId=T8.Id left join cmndzongkhag T3 on T1.CmnDzongkhagId=T3.Id where T1.CmnApplicationRegistrationStatusId='".CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVEDFORPAYMENT."' and T1.SysLockedByUserId is null and T1.CrpEngineerId is not null";
				}
				if((int)$approvePaymentPrivilege->ReferenceNo == 410){
					if($approvePaymentServiceQuery!=""){
						$approvePaymentServiceQuery.=" union all ";
					}
					$approvePaymentServiceQuery.="select count(distinct T1.Id) as ApplicationCount,'410' as TypeCode, 'Specialized Trade Service' as Type from crpspecializedtrade T1 join crpspecializedtradeappliedservice T5 on T1.Id=T5.CrpSpecializedTradeId join crpservice T4 on T5.CmnServiceTypeId=T4.Id join cmnlistitem T3 on T1.CmnSalutationId=T3.Id join cmndzongkhag T2 on T1.CmnDzongkhagId=T2.Id where T1.CmnApplicationRegistrationStatusId='".CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVEDFORPAYMENT."' and T1.SysLockedByUserId is null and T1.CrpSpecializedTradeId is not null";
				}
			endforeach;
		}
		if($approvePaymentRegistrationQuery!=""){
            $approvePaymentRegistrationQuery = str_replace(' union all',' and coalesce(T1.HasNotification,0) <> 1 union all',$approvePaymentRegistrationQuery);
            $approvePaymentRegistrationQuery.=" and coalesce(T1.HasNotification,0) <> 1";

            $query = str_replace("count(distinct T1.Id) as ApplicationCount","distinct T1.Id,T1.ReferenceNo as ApplicationNo, T1.CmnApplicationRegistrationStatusId",$approvePaymentRegistrationQuery);
            $approvePaymentRegistrationApplications = DB::select($query);
		}
		if($approvePaymentServiceQuery!=""){
            $approvePaymentServiceQuery = str_replace(' union all',' and coalesce(T1.HasNotification,0) <> 1 union all',$approvePaymentServiceQuery);
            $approvePaymentServiceQuery.=" and coalesce(T1.HasNotification,0) <> 1";

            $query = str_replace("count(distinct T1.Id) as ApplicationCount","distinct T1.Id,T1.ReferenceNo as ApplicationNo, T1.CmnApplicationRegistrationStatusId",$approvePaymentServiceQuery);
            $approvePaymentServiceApplications = DB::select($query);
		}

        $approvePaymentApplications = array_merge($approvePaymentRegistrationApplications,$approvePaymentServiceApplications);
        foreach($approvePaymentApplications as $application):
            $typeCode = $application->TypeCode;
            $id = $application->Id;
            $applicationNo = $application->ApplicationNo;
			$applicationStatusId = $application->CmnApplicationRegistrationStatusId;

            if((int)$typeCode == 401 || (int)$typeCode == 406){
                $table = "crpcontractor";
                $message = "Application # $applicationNo (Contractor Registration) is pending approval of payment";

                if((int)$typeCode == 406){
                    $detailQuery = DB::table('crpcontractor as T1')->join('crpcontractor as B','B.Id','=','T1.CrpContractorId')
                        ->leftJoin('crpcontractorappliedservice as A','T1.Id','=','A.CrpContractorId')
                        ->leftJoin('crpservice as T2','T2.Id','=','A.CmnServiceTypeId')
                        ->where('T1.Id',$id)
                        ->select(DB::raw("GROUP_CONCAT(T2.Name SEPARATOR ', ') as Services"),'B.CDBNo','T1.ApplicationDate')
                        ->get();
                    $cdbNo = $detailQuery[0]->CDBNo;
                    $services = $detailQuery[0]->Services;
                    $applicationDate = convertDateToClientFormat($detailQuery[0]->ApplicationDate);
                    $message = "Application # $applicationNo (Contractor $cdbNo) has applied for $services on $applicationDate. Approval of payment is pending";
                }
            }
            if((int)$typeCode == 402 || (int)$typeCode == 407){
                $table = "crpconsultant";
                $message = "Application # $applicationNo (Consultant Registration) is pending approval of payment";

                if((int)$typeCode == 407){
                    $detailQuery = DB::table('crpconsultant as T1')->join('crpconsultant as B','B.Id','=','T1.CrpConsultantId')
                        ->leftJoin('crpconsultantappliedservice as A','T1.Id','=','A.CrpConsultantId')
                        ->leftJoin('crpservice as T2','T2.Id','=','A.CmnServiceTypeId')
                        ->where('T1.Id',$id)
                        ->select(DB::raw("GROUP_CONCAT(T2.Name SEPARATOR ', ') as Services"),'B.CDBNo','T1.ApplicationDate')
                        ->get();
                    $cdbNo = $detailQuery[0]->CDBNo;
                    $services = $detailQuery[0]->Services;
                    $applicationDate = convertDateToClientFormat($detailQuery[0]->ApplicationDate);
                    $message = "Application # $applicationNo (Consultant $cdbNo) has applied for $services on $applicationDate. Approval is pending of payment";
                }
            }
            if((int)$typeCode == 403 || (int)$typeCode == 408){
                $table = "crparchitect";
                $message = "Application # $applicationNo (Architect Registration) is pending approval of payment";

                if((int)$typeCode == 408){
                    $detailQuery = DB::table('crparchitect as T1')->join('crparchitect as B','B.Id','=','T1.CrpArchitectId')
                        ->leftJoin('crparchitectappliedservice as A','T1.Id','=','A.CrpArchitectId')
                        ->leftJoin('crpservice as T2','T2.Id','=','A.CmnServiceTypeId')
                        ->where('T1.Id',$id)
                        ->select(DB::raw("GROUP_CONCAT(T2.Name SEPARATOR ', ') as Services"),'B.ARNo','T1.ApplicationDate')
                        ->get();
                    $cdbNo = $detailQuery[0]->ARNo;
                    $services = $detailQuery[0]->Services;
                    $applicationDate = convertDateToClientFormat($detailQuery[0]->ApplicationDate);
                    $message = "Application # $applicationNo (Architect $cdbNo) has applied for $services on $applicationDate. Approval of payment is pending";
                }
            }
            if((int)$typeCode == 404 || (int)$typeCode == 409){
                $table = "crpengineer";
                $message = "Application # $applicationNo (Engineer Registration) is pending approval of payment";

                if((int)$typeCode == 409){
                    $detailQuery = DB::table('crpengineer as T1')->join('crpengineer as B','B.Id','=','T1.CrpEngineerId')
                        ->leftJoin('crpengineerappliedservice as A','T1.Id','=','A.CrpEngineerId')
                        ->leftJoin('crpservice as T2','T2.Id','=','A.CmnServiceTypeId')
                        ->where('T1.Id',$id)
                        ->select(DB::raw("GROUP_CONCAT(T2.Name SEPARATOR ', ') as Services"),'B.CDBNo','T1.ApplicationDate')
                        ->get();
                    $cdbNo = $detailQuery[0]->CDBNo;
                    $services = $detailQuery[0]->Services;
                    $applicationDate = convertDateToClientFormat($detailQuery[0]->ApplicationDate);
                    $message = "Application # $applicationNo (Engineer $cdbNo) has applied for $services on $applicationDate. Approval of payment is pending";
                }
            }
            if((int)$typeCode == 410){
                $table = "crpspecializedtrade";
                $detailQuery = DB::table('crpspecializedtrade as T1')->join('crpspecializedtrade as B','B.Id','=','T1.CrpSpecializedTradeId')
                    ->leftJoin('crpspecializedtradeappliedservice as A','T1.Id','=','A.CrpSpecializedTradeId')
                    ->leftJoin('crpservice as T2','T2.Id','=','A.CmnServiceTypeId')
                    ->where('T1.Id',$id)
                    ->select(DB::raw("GROUP_CONCAT(T2.Name SEPARATOR ', ') as Services"),'B.SPNo','T1.ApplicationDate')
                    ->get();
                $cdbNo = $detailQuery[0]->SPNo;
                $services = $detailQuery[0]->Services;
                $applicationDate = convertDateToClientFormat($detailQuery[0]->ApplicationDate);
                $message = "Application # $applicationNo (Specialized Trade $cdbNo) has applied for $services on $applicationDate. Approval of payment is pending";
            }

            DB::table($table)->where('Id',$id)->update(array('HasNotification'=>1));
            DB::table("sysapplicationnotification")->insert(array("Id"=>$this->UUID(),'IsRead'=>0,'NotificationTime'=>date("Y-m-d G:i:s"),"Message"=>$message,"ApplicationId"=>$id,"CmnApplicationStatusId"=>$applicationStatusId,'TypeCode'=>(int)$typeCode));
        endforeach;
		/*END APPROVE PAYMENT*/
		if((int)$userApprovePrivilege>0){
			$userApproveCount = DB::table('sysregapplication')->where(DB::raw('coalesce(Status,0)'),0)->whereRaw("coalesce(HasNotification,0)<>1")->count();
            if($userApproveCount > 0){
                $userApplications = DB::table('sysregapplication')->where(DB::raw('coalesce(Status,0)'),0)->whereRaw("coalesce(HasNotification,0)<>1")->get(array('Id','FullName','CDBNo','RegistrationType'));
                foreach($userApplications as $application):
                    $id = $application->Id;
                    $cdbNo = $application->CDBNo;
                    $registrationType = $application->RegistrationType;

                    if((int)$registrationType == 1){
                        $type = "Contractor";
                    }elseif((int)$registrationType == 2){
                        $type = "Consultant";
                    }elseif((int)$registrationType == 3){
                        $type = "Architect";
                    }elseif((int)$registrationType == 4){
                        $type = "Engineer";
                    }else{
                        $type = "Specialized Trade";
                    }

                    $message = "$type ($cdbNo) has applied for user account and is pending approval";
                    DB::table("sysregapplication")->where('Id',$id)->update(array('HasNotification'=>1));
                    DB::table("sysapplicationnotification")->insert(array("Id"=>$this->UUID(),'IsRead'=>0,'NotificationTime'=>date("Y-m-d G:i:s"),"Message"=>$message,"ApplicationId"=>$id,'TypeCode'=>101));
                endforeach;
            }
		}
        $userAllPrivileges = DB::table('sysrolemenumap as T1')
            ->join('sysmenu as T2','T2.Id','=','T1.SysMenuId')
            ->whereIn('T1.SysRoleId',$userRoles)
            ->whereIn('T2.ReferenceNo',array(101,201,202,203,204,205,206,207,208,209,210,301,302,303,304,305,306,307,308,309,310,401,402,403,404,406,407,408,409,410))
            ->orderBy('T2.ReferenceNo')
            ->lists('T2.ReferenceNo');
        $notifications = DB::table('sysapplicationnotification')->whereIn("TypeCode",$userAllPrivileges)->whereRaw("coalesce(IsRead,0) <> 1")->orderBy('NotificationTime','DESC')->get(array('Message','ApplicationId','TypeCode'));
		$notificationsNotFetched = DB::table('sysapplicationnotification')->whereIn("TypeCode",$userAllPrivileges)->whereRaw("Id not in (select SysApplicationNotificationId from sysapplicationnotificationuser WHERE SysUserId = ?)",array(Auth::user()->Id))->whereRaw("coalesce(IsRead,0) <> 1")->orderBy('NotificationTime')->get(array('Id'));
		$newNotificationCount = count($notificationsNotFetched);
		foreach($notificationsNotFetched as $notification):
			DB::table("sysapplicationnotificationuser")->insert(array("Id"=>$this->UUID(),"SysUserId"=>Auth::user()->Id,"SysApplicationNotificationId"=>$notification->Id));
		endforeach;
        return Response::json(array('notifications'=>$notifications,'totalCount'=>count($notifications),'hasNewNotifications'=>($newNotificationCount>0)?1:0));
	}

	public function individualTaskReport(){
		$fromDate = Input::has('FromDate')?$this->convertDate(Input::get('FromDate')):'2016-06-01';
		if($fromDate<'2016-06-01'){
			$fromDate = '2016-06-01';
		}
		$toDate = Input::has('ToDate')?$this->convertDate(Input::get('ToDate')):'--';
		$contractorRR  = $consultantRR = $architectRR = $engineerRR = $spRR = $contractorServiceR = $consultantServiceR = $architectServiceR = $engineerServiceR = $specializedTradeServiceR = $contractorRPA = $consultantRPA = $architectRPA = $engineerRPA = array();
		$consultantRR  = array();
		$architectRR  = array();
		$engineerRR  = array();
		$spRR  = array();
		$contractorServiceR  = array();
		$consultantServiceR  = array();
		$architectServiceR  = array();
		$engineerServiceR  = array();
		$specializedTradeServiceR  = array();
		$contractorRPA  = array();
		$consultantRPA  = array();
		$architectRPA  = array();
		$engineerRPA  = array();
		$contractorServicePA  = array();
		$consultantServicePA  = array();
		$architectServicePA  = array();
		$engineerServicePA  = array();
		$specializedTradeServicePA  = array();
		$contractorRA  = array();
		$consultantRA  = array();
		$architectRA  = array();
		$engineerRA  = array();
		$spRA  = array();
		$contractorServiceA  = array();
		$consultantServiceA  = array();
		$architectServiceA  = array();
		$engineerServiceA  = array();
		$specializedTradeServiceA  = array();
		$contractorRV  = array();
		$consultantRV  = array();
		$architectRV  = array();
		$engineerRV  = array();
		$spRV  = array();
		$contractorServiceV  = array();
		$consultantServiceV  = array();
		$architectServiceV  = array();
		$engineerServiceV  = array();
		$specializedTradeServiceV = array();
		$userApproveCount  = '--';
        $userPendingCount  = 0;
		$consultantServices  = $contractorServices= array("Renewal"=>CONST_SERVICETYPE_RENEWAL,"Update General Info"=>CONST_SERVICETYPE_GENERALINFORMATION,"Change of Location"=>CONST_SERVICETYPE_CHANGELOCATION,"Change of Owner"=>CONST_SERVICETYPE_CHANGEOWNER,"Category/Class Change"=>CONST_SERVICETYPE_CHANGEOFCATEGORYCLASSIFICATION,"Update HR"=>CONST_SERVICETYPE_UPDATEHUMANRESOURCE,"Update Equipment"=>CONST_SERVICETYPE_UPDATEEQUIPMENT,"Late Fee"=>CONST_SERVICETYPE_LATEFEE,"Change of Firm Name"=>CONST_SERVICETYPE_CHANGEOFFIRMNAME,"Incorporation"=>CONST_SERVICETYPE_INCORPORATION);
		$architectServices = $engineerServices = array("Renewal"=>CONST_SERVICETYPE_RENEWAL,"Late Fee"=>CONST_SERVICETYPE_LATEFEE);
		$spServices = array("Renewal"=>CONST_SERVICETYPE_RENEWAL,"Late Fee"=>CONST_SERVICETYPE_LATEFEE,"Category/Class Change"=>CONST_SERVICETYPE_CHANGEOFCATEGORYCLASSIFICATION);
		$userId = Input::has('UserId')?Input::get('UserId'):Auth::user()->Id;
		$userRoles = DB::table('sysuserrolemap as T1')
			->where('T1.SysUserId',$userId)
			->lists('T1.SysRoleId');
		$userAllPrivileges = DB::table('sysrolemenumap as T1')
			->join('sysmenu as T2','T2.Id','=','T1.SysMenuId')
			->whereIn('T1.SysRoleId',$userRoles)
			->whereIn('T2.ReferenceNo',array(101,201,202,203,204,205,206,207,208,209,210,301,302,303,304,305,306,307,308,309,310,401,402,403,404,406,407,408,409,410))
			->orderBy('T2.ReferenceNo')
			->lists('T2.ReferenceNo'); //101 -> User approve, 201 -> Contractor Verify New Reg, 202 -> Consultant Verify New Reg, 206 -> Contractor Verify Services, 207 -> Consultant Verify Services

        $crpsUsers = DB::table('sysuser as T1')
                        ->join('sysuserrolemap as T2','T2.SysUserId','=','T1.Id')
                        ->join('sysrolemenumap as T3','T3.SysRoleId','=','T2.SysRoleId')
                        ->join('sysmenu as T4','T4.Id','=','T3.SysMenuId')
                        ->whereRaw("coalesce(T1.Status,0)=1")
                        ->whereIn('T4.ReferenceNo',array(101,201,202,203,204,205,206,207,208,209,210,301,302,303,304,305,306,307,308,309,310,401,402,403,404,406,407,408,409,410))
                        ->select(DB::raw("distinct T1.Id"),"T1.username","T1.FullName")
                        ->get();


		if(in_array(101,$userAllPrivileges)){
			$userApproveCount = DB::select("select count(T1.Id) as Total, case when T1.RegistrationType = 1 then 'Contractor' else case when T1.RegistrationType = 2 then 'Consultant' else case when T1.RegistrationType = 3 then 'Architect' else case when T1.RegistrationType = 4 then 'Engineer' else 'Specialized Trade' end end end end as Type from sysregapplication T1 where T1.ApprovedBy = ? and DATE_FORMAT(T1.AppliedOn,'%Y-%m-%d')>= ? and case when '$toDate' = '--' then 1 else DATE_FORMAT(T1.AppliedOn,'%Y-%m-%d') <= ? end group by RegistrationType",array($userId,$fromDate,$toDate));
			$userPendingCountQuery = DB::select("select count(T1.Id) as Total from sysregapplication T1 where T1.ApprovedBy is NULL and DATE_FORMAT(T1.AppliedOn,'%Y-%m-%d')>= ? and case when '$toDate' = '--' then 1 else DATE_FORMAT(T1.AppliedOn,'%Y-%m-%d') <= ? end group by RegistrationType",array($fromDate,$toDate));
			$userPendingCount = isset($userPendingCountQuery[0]->Total)?$userPendingCountQuery[0]->Total:0;
		}
		$verificationPending = 0;
		if(in_array(201,$userAllPrivileges)){ //CONTRACTOR
			$contractorRV = DB::table('crpcontractor')
								->whereRaw("ApplicationDate >= ?",array($fromDate))
								->whereRaw("case when '$toDate' = '--' then 1 else ApplicationDate <= ? end",array($toDate))
								->whereNull('CrpContractorId')->where('SysVerifierUserId',$userId)->count();
			$pending = DB::table('crpcontractor')
                ->whereRaw("ApplicationDate >= ?",array($fromDate))
                ->whereRaw("case when '$toDate' = '--' then 1 else ApplicationDate <= ? end",array($toDate))
                ->whereRaw("coalesce(RegistrationStatus,0)=1")
                ->whereNull('CrpContractorId')->where('CmnApplicationRegistrationStatusId',CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW)->count();
            $verificationPending += $pending;
			$contractorRR = DB::table('crpcontractor')
								->whereRaw("ApplicationDate >= ?",array($fromDate))
								->whereRaw("case when '$toDate' = '--' then 1 else ApplicationDate <= ? end",array($toDate))
								->whereNull('CrpContractorId')->where('SysRejecterUserId',$userId)->count();
		}
		if(in_array(202,$userAllPrivileges)){ //CONSULTANT
			$consultantRV = DB::table('crpconsultant')
								->whereRaw("ApplicationDate >= ?",array($fromDate))
								->whereRaw("case when '$toDate' = '--' then 1 else ApplicationDate <= ? end",array($toDate))
								->whereNull('CrpConsultantId')->where('SysVerifierUserId',$userId)->count();
            $pending = DB::table('crpconsultant')
                ->whereRaw("ApplicationDate >= ?",array($fromDate))
                ->whereRaw("case when '$toDate' = '--' then 1 else ApplicationDate <= ? end",array($toDate))
                ->whereRaw("coalesce(RegistrationStatus,0)=1")
                ->whereNull('CrpConsultantId')->where('CmnApplicationRegistrationStatusId',CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW)->count();
            $verificationPending += $pending;
			$consultantRR = DB::table('crpconsultant')
								->whereRaw("ApplicationDate >= ?",array($fromDate))
								->whereRaw("case when '$toDate' = '--' then 1 else ApplicationDate <= ? end",array($toDate))
								->whereNull('CrpConsultantId')->where('SysRejectorUserId',$userId)->count();
		}
		if(in_array(203,$userAllPrivileges)){ //ARCHITECT
			$architectRV = DB::table('crparchitect')
								->whereRaw("ApplicationDate >= ?",array($fromDate))
								->whereRaw("case when '$toDate' = '--' then 1 else ApplicationDate <= ? end",array($toDate))
								->whereNull('CrpArchitectId')->where('SysVerifierUserId',$userId)->count();
            $pending = DB::table('crparchitect')
                ->whereRaw("ApplicationDate >= ?",array($fromDate))
                ->whereRaw("case when '$toDate' = '--' then 1 else ApplicationDate <= ? end",array($toDate))
                ->whereRaw("coalesce(RegistrationStatus,0)=1")
                ->whereNull('CrpArchitectId')->where('CmnApplicationRegistrationStatusId',CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW)->count();
            $verificationPending += $pending;
			$architectRR = DB::table('crparchitect')
								->whereRaw("ApplicationDate >= ?",array($fromDate))
								->whereRaw("case when '$toDate' = '--' then 1 else ApplicationDate <= ? end",array($toDate))
								->whereNull('CrpArchitectId')->where('SysRejectorUserId',$userId)->count();
		}
		if(in_array(204,$userAllPrivileges)){ //ENGINEER
			$engineerRV = DB::table('crpengineer')
								->whereRaw("ApplicationDate >= ?",array($fromDate))
								->whereRaw("case when '$toDate' = '--' then 1 else ApplicationDate <= ? end",array($toDate))
								->whereNull('CrpEngineerId')->where('SysVerifierUserId',$userId)->count();
            $pending = DB::table('crpengineer')
                ->whereRaw("ApplicationDate >= ?",array($fromDate))
                ->whereRaw("case when '$toDate' = '--' then 1 else ApplicationDate <= ? end",array($toDate))
                ->whereRaw("coalesce(RegistrationStatus,0)=1")
                ->whereNull('CrpEngineerId')->where('CmnApplicationRegistrationStatusId',CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW)->count();
            $verificationPending += $pending;
			$engineerRR = DB::table('crpengineer')
								->whereRaw("ApplicationDate >= ?",array($fromDate))
								->whereRaw("case when '$toDate' = '--' then 1 else ApplicationDate <= ? end",array($toDate))
								->whereNull('CrpEngineerId')->where('SysRejectorUserId',$userId)->count();
		}
		if(in_array(205,$userAllPrivileges)){ //SP
			$spRV = DB::table('crpspecializedtrade')
								->whereRaw("ApplicationDate >= ?",array($fromDate))
								->whereRaw("case when '$toDate' = '--' then 1 else ApplicationDate <= ? end",array($toDate))
								->whereNull('CrpSpecializedTradeId')->where('SysVerifierUserId',$userId)->count();
            $pending = DB::table('crpspecializedtrade')
                ->whereRaw("ApplicationDate >= ?",array($fromDate))
                ->whereRaw("case when '$toDate' = '--' then 1 else ApplicationDate <= ? end",array($toDate))
                ->whereRaw("coalesce(RegistrationStatus,0)=1")
                ->whereNull('CrpSpecializedTradeId')->where('CmnApplicationRegistrationStatusId',CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW)->count();
            $verificationPending += $pending;
			$spRR = DB::table('crpspecializedtrade')
								->whereRaw("ApplicationDate >= ?",array($fromDate))
								->whereRaw("case when '$toDate' = '--' then 1 else ApplicationDate <= ? end",array($toDate))
								->whereNull('CrpSpecializedTradeId')->where('SysRejectorUserId',$userId)->count();
		}
		if(in_array(206,$userAllPrivileges)){ //CONTRACTOR
            $pending = DB::table('crpcontractor as T1')
                ->join('crpcontractorappliedservice as T2','T2.CrpContractorId','=','T1.Id')
                ->whereNotNull('T1.CrpContractorId')
                ->where('T1.CmnApplicationRegistrationStatusId',CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW)
                ->whereRaw("T1.ApplicationDate >= ?",array($fromDate))
                ->whereRaw("coalesce(T1.RegistrationStatus,0)=1")
                ->whereRaw("case when '$toDate' = '--' then 1 else T1.ApplicationDate <= ? end",array($toDate))
                ->get(array(DB::raw("count(distinct T1.Id) as Total")));
            $verificationPending += $pending[0]->Total;

			foreach($contractorServices as $contractorServiceName => $contractorService):
				$count= DB::table('crpcontractor as T1')
					->join('crpcontractorappliedservice as T2','T2.CrpContractorId','=','T1.Id')
					->whereNotNull('T1.CrpContractorId')
					->where('T2.CmnServiceTypeId',$contractorService)
					->where('SysVerifierUserId',$userId)
					->whereRaw("T1.ApplicationDate >= ?",array($fromDate))
                	->whereRaw("case when '$toDate' = '--' then 1 else T1.ApplicationDate <= ? end",array($toDate))
					->get(array(DB::raw("count(distinct T1.Id) as Total")));
				$contractorServiceV[$contractorService]['Count'] = $count[0]->Total;
				$contractorServiceV[$contractorService]['Name'] = $contractorServiceName;

				$count = DB::table('crpcontractor as T1')
					->join('crpcontractorappliedservice as T2','T2.CrpContractorId','=','T1.Id')
					->whereNotNull('T1.CrpContractorId')
					->where('T2.CmnServiceTypeId',$contractorService)
					->where('SysRejecterUserId',$userId)
					->whereRaw("T1.ApplicationDate >= ?",array($fromDate))
                	->whereRaw("case when '$toDate' = '--' then 1 else T1.ApplicationDate <= ? end",array($toDate))
					->get(array(DB::raw("count(distinct T1.Id) as Total")));
				$contractorServiceR[$contractorService]['Count'] = $count[0]->Total;
				$contractorServiceR[$contractorService]['Name'] = $contractorServiceName;
			endforeach;
		}
		if(in_array(207,$userAllPrivileges)){ //CONSULTANT
            $pending = DB::table('crpconsultant as T1')
                ->join('crpconsultantappliedservice as T2','T2.CrpConsultantId','=','T1.Id')
                ->whereNotNull('T1.CrpConsultantId')
                ->whereRaw("T1.ApplicationDate >= ?",array($fromDate))
                ->whereRaw("case when '$toDate' = '--' then 1 else T1.ApplicationDate <= ? end",array($toDate))
                ->whereRaw("coalesce(T1.RegistrationStatus,0)=1")
                ->where('T1.CmnApplicationRegistrationStatusId',CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW)
                ->get(array(DB::raw("count(distinct T1.Id) as Total")));
            $verificationPending += $pending[0]->Total;

			foreach($consultantServices as $consultantServiceName => $consultantService):
				$count= DB::table('crpconsultant as T1')
					->join('crpconsultantappliedservice as T2','T2.CrpConsultantId','=','T1.Id')
					->whereNotNull('T1.CrpConsultantId')
					->where('T2.CmnServiceTypeId',$consultantService)
					->where('SysVerifierUserId',$userId)
					->whereRaw("T1.ApplicationDate >= ?",array($fromDate))
                	->whereRaw("case when '$toDate' = '--' then 1 else T1.ApplicationDate <= ? end",array($toDate))
					->get(array(DB::raw("count(distinct T1.Id) as Total")));
				$consultantServiceV[$consultantService]['Count'] = $count[0]->Total;
				$consultantServiceV[$consultantService]['Name'] = $consultantServiceName;

				$count = DB::table('crpconsultant as T1')
					->join('crpconsultantappliedservice as T2','T2.CrpConsultantId','=','T1.Id')
					->whereNotNull('T1.CrpConsultantId')
					->where('T2.CmnServiceTypeId',$consultantService)
					->where('SysRejectorUserId',$userId)
					->whereRaw("T1.ApplicationDate >= ?",array($fromDate))
                	->whereRaw("case when '$toDate' = '--' then 1 else T1.ApplicationDate <= ? end",array($toDate))
					->get(array(DB::raw("count(distinct T1.Id) as Total")));
				$consultantServiceR[$consultantService]['Count'] = $count[0]->Total;
				$consultantServiceR[$consultantService]['Name'] = $consultantServiceName;
			endforeach;
		}
		if(in_array(208,$userAllPrivileges)){ //ARCHITECT
            $pending = DB::table('crparchitect as T1')
                ->join('crparchitectappliedservice as T2','T2.CrpArchitectId','=','T1.Id')
                ->whereNotNull('T1.CrpArchitectId')
                ->whereRaw("T1.ApplicationDate >= ?",array($fromDate))
                ->whereRaw("case when '$toDate' = '--' then 1 else T1.ApplicationDate <= ? end",array($toDate))
                ->where('T1.CmnApplicationRegistrationStatusId',CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW)
                ->whereRaw("coalesce(T1.RegistrationStatus,0)=1")
                ->get(array(DB::raw("count(distinct T1.Id) as Total")));
            $verificationPending += $pending[0]->Total;

			foreach($architectServices as $architectServiceName => $architectService):
				$count= DB::table('crparchitect as T1')
					->join('crparchitectappliedservice as T2','T2.CrpArchitectId','=','T1.Id')
					->whereNotNull('T1.CrpArchitectId')
					->where('T2.CmnServiceTypeId',$architectService)
					->where('SysVerifierUserId',$userId)
					->whereRaw("T1.ApplicationDate >= ?",array($fromDate))
                	->whereRaw("case when '$toDate' = '--' then 1 else T1.ApplicationDate <= ? end",array($toDate))
					->get(array(DB::raw('count(distinct T1.Id) as Total')));
				$architectServiceV[$architectService]['Count'] = $count[0]->Total;
				$architectServiceV[$architectService]['Name'] = $architectServiceName;

				$count= DB::table('crparchitect as T1')
					->join('crparchitectappliedservice as T2','T2.CrpArchitectId','=','T1.Id')
					->whereNotNull('T1.CrpArchitectId')
					->where('T2.CmnServiceTypeId',$architectService)
					->where('SysRejectorUserId',$userId)
					->whereRaw("T1.ApplicationDate >= ?",array($fromDate))
                	->whereRaw("case when '$toDate' = '--' then 1 else T1.ApplicationDate <= ? end",array($toDate))
					->get(array(DB::raw('count(distinct T1.Id) as Total')));
				$architectServiceR[$architectService]['Count'] = $count[0]->Total;
				$architectServiceR[$architectService]['Name'] = $architectServiceName;
			endforeach;
		}
		if(in_array(209,$userAllPrivileges)){ //ENGINEER
            $pending = DB::table('crpengineer as T1')
                ->join('crpengineerappliedservice as T2','T2.CrpEngineerId','=','T1.Id')
                ->whereNotNull('T1.CrpEngineerId')
                ->whereRaw("T1.ApplicationDate >= ?",array($fromDate))
                ->whereRaw("case when '$toDate' = '--' then 1 else T1.ApplicationDate <= ? end",array($toDate))
                ->where('T1.CmnApplicationRegistrationStatusId',CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW)
                ->whereRaw("coalesce(T1.RegistrationStatus,0)=1")
                ->get(array(DB::raw("count(distinct T1.Id) as Total")));
            $verificationPending += $pending[0]->Total;

			foreach($engineerServices as $engineerServiceName => $engineerService):
				$count= DB::table('crpengineer as T1')
					->join('crpengineerappliedservice as T2','T2.CrpEngineerId','=','T1.Id')
					->whereNotNull('T1.CrpEngineerId')
					->where('T2.CmnServiceTypeId',$engineerService)
					->where('SysVerifierUserId',$userId)
					->whereRaw("T1.ApplicationDate >= ?",array($fromDate))
                	->whereRaw("case when '$toDate' = '--' then 1 else T1.ApplicationDate <= ? end",array($toDate))
					->get(array(DB::raw("count(distinct T1.Id) as Total")));
				$engineerServiceV[$engineerService]['Count'] = $count[0]->Total;
				$engineerServiceV[$engineerService]['Name'] = $engineerServiceName;

				$count= DB::table('crpengineer as T1')
					->join('crpengineerappliedservice as T2','T2.CrpEngineerId','=','T1.Id')
					->whereNotNull('T1.CrpEngineerId')
					->where('T2.CmnServiceTypeId',$engineerService)
					->where('SysRejectorUserId',$userId)
					->whereRaw("T1.ApplicationDate >= ?",array($fromDate))
                	->whereRaw("case when '$toDate' = '--' then 1 else T1.ApplicationDate <= ? end",array($toDate))
					->get(array(DB::raw("count(distinct T1.Id) as Total")));
				$engineerServiceR[$engineerService]['Count'] = $count[0]->Total;
				$engineerServiceR[$engineerService]['Name'] = $engineerServiceName;
			endforeach;
		}
		if(in_array(210,$userAllPrivileges)){ //SP
            $pending = DB::table('crpspecializedtrade as T1')
                ->join('crpspecializedtradeappliedservice as T2','T2.CrpSpecializedTradeId','=','T1.Id')
                ->whereNotNull('T1.CrpSpecializedTradeId')
                ->whereRaw("T1.ApplicationDate >= ?",array($fromDate))
                ->whereRaw("case when '$toDate' = '--' then 1 else T1.ApplicationDate <= ? end",array($toDate))
                ->whereRaw("coalesce(T1.RegistrationStatus,0)=1")
                ->where('T1.CmnApplicationRegistrationStatusId',CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW)
                ->get(array(DB::raw("count(distinct T1.Id) as Total")));
            $verificationPending += $pending[0]->Total;

			foreach($spServices as $specializedTradeServiceName => $specializedTradeService):
				$count= DB::table('crpspecializedtrade as T1')
					->join('crpspecializedtradeappliedservice as T2','T2.CrpSpecializedTradeId','=','T1.Id')
					->whereNotNull('T1.CrpSpecializedTradeId')
					->where('T2.CmnServiceTypeId',$specializedTradeService)
					->where('SysVerifierUserId',$userId)
					->whereRaw("T1.ApplicationDate >= ?",array($fromDate))
                	->whereRaw("case when '$toDate' = '--' then 1 else T1.ApplicationDate <= ? end",array($toDate))
					->get(array(DB::raw("count(distinct T1.Id) as Total")));
				$specializedTradeServiceV[$specializedTradeService]['Count'] = $count[0]->Total;
				$specializedTradeServiceV[$specializedTradeService]['Name'] = $specializedTradeServiceName;

				$count= DB::table('crpspecializedtrade as T1')
					->join('crpspecializedtradeappliedservice as T2','T2.CrpSpecializedTradeId','=','T1.Id')
					->whereNotNull('T1.CrpSpecializedTradeId')
					->where('T2.CmnServiceTypeId',$specializedTradeService)
					->where('SysRejectorUserId',$userId)
					->whereRaw("T1.ApplicationDate >= ?",array($fromDate))
                	->whereRaw("case when '$toDate' = '--' then 1 else T1.ApplicationDate <= ? end",array($toDate))
					->get(array(DB::raw("count(distinct T1.Id) as Total")));
				$specializedTradeServiceR[$specializedTradeService]['Count'] = $count[0]->Total;
				$specializedTradeServiceR[$specializedTradeService]['Name'] = $specializedTradeServiceName;
			endforeach;
		}
		$approvalPending = 0;
		if(in_array(301,$userAllPrivileges)){ //CONTRACTOR
			$contractorRA = DB::table('crpcontractor')
								->whereRaw("ApplicationDate >= ?",array($fromDate))
								->whereRaw("case when '$toDate' = '--' then 1 else ApplicationDate <= ? end",array($toDate))
								->whereNull('CrpContractorId')->where('SysApproverUserId',$userId)->count();
            $pending = DB::table('crpcontractor')
                ->whereRaw("ApplicationDate >= ?",array($fromDate))
                ->whereRaw("case when '$toDate' = '--' then 1 else ApplicationDate <= ? end",array($toDate))
                ->whereRaw("coalesce(RegistrationStatus,0)=1")
                ->whereNull('CrpContractorId')->where('CmnApplicationRegistrationStatusId',CONST_CMN_APPLICATIONREGISTRATIONSTATUS_VERIFIED)->count();
            $approvalPending += $pending;
			$contractorRR = DB::table('crpcontractor')
								->whereRaw("ApplicationDate >= ?",array($fromDate))
								->whereRaw("case when '$toDate' = '--' then 1 else ApplicationDate <= ? end",array($toDate))
								->whereNull('CrpContractorId')->where('SysRejecterUserId',$userId)->count();
		}
		if(in_array(302,$userAllPrivileges)){ //CONSULTANT
			$consultantRA = DB::table('crpconsultant')
								->whereRaw("ApplicationDate >= ?",array($fromDate))
								->whereRaw("case when '$toDate' = '--' then 1 else ApplicationDate <= ? end",array($toDate))
								->whereNull('CrpConsultantId')->where('SysApproverUserId',$userId)->count();
            $pending = DB::table('crpconsultant')
                ->whereRaw("ApplicationDate >= ?",array($fromDate))
                ->whereRaw("case when '$toDate' = '--' then 1 else ApplicationDate <= ? end",array($toDate))
                ->whereRaw("coalesce(RegistrationStatus,0)=1")
                ->whereNull('CrpConsultantId')->where('CmnApplicationRegistrationStatusId',CONST_CMN_APPLICATIONREGISTRATIONSTATUS_VERIFIED)->count();
            $approvalPending += $pending;
			$consultantRR = DB::table('crpconsultant')
								->whereRaw("ApplicationDate >= ?",array($fromDate))
								->whereRaw("case when '$toDate' = '--' then 1 else ApplicationDate <= ? end",array($toDate))
								->whereNull('CrpConsultantId')->where('SysRejectorUserId',$userId)->count();
		}
		if(in_array(303,$userAllPrivileges)){ //ARCHITECT
			$architectRA = DB::table('crparchitect')
								->whereRaw("ApplicationDate >= ?",array($fromDate))
								->whereRaw("case when '$toDate' = '--' then 1 else ApplicationDate <= ? end",array($toDate))
								->whereNull('CrpArchitectId')->where('SysApproverUserId',$userId)->count();
            $pending = DB::table('crparchitect')
                ->whereRaw("ApplicationDate >= ?",array($fromDate))
                ->whereRaw("case when '$toDate' = '--' then 1 else ApplicationDate <= ? end",array($toDate))
                ->whereRaw("coalesce(RegistrationStatus,0)=1")
                ->whereNull('CrpArchitectId')->where('CmnApplicationRegistrationStatusId',CONST_CMN_APPLICATIONREGISTRATIONSTATUS_VERIFIED)->count();
            $approvalPending += $pending;
			$architectRR = DB::table('crparchitect')
								->whereRaw("ApplicationDate >= ?",array($fromDate))
								->whereRaw("case when '$toDate' = '--' then 1 else ApplicationDate <= ? end",array($toDate))
								->whereNull('CrpArchitectId')->where('SysRejectorUserId',$userId)->count();
		}
		if(in_array(304,$userAllPrivileges)){ //ENGINEER
			$engineerRA = DB::table('crpengineer')
								->whereRaw("ApplicationDate >= ?",array($fromDate))
								->whereRaw("case when '$toDate' = '--' then 1 else ApplicationDate <= ? end",array($toDate))
								->whereNull('CrpEngineerId')->where('SysApproverUserId',$userId)->count();
            $pending = DB::table('crpengineer')
                ->whereRaw("ApplicationDate >= ?",array($fromDate))
                ->whereRaw("case when '$toDate' = '--' then 1 else ApplicationDate <= ? end",array($toDate))
                ->whereRaw("coalesce(RegistrationStatus,0)=1")
                ->whereNull('CrpEngineerId')->where('CmnApplicationRegistrationStatusId',CONST_CMN_APPLICATIONREGISTRATIONSTATUS_VERIFIED)->count();
            $approvalPending += $pending;
			$engineerRR = DB::table('crpengineer')
								->whereRaw("ApplicationDate >= ?",array($fromDate))
								->whereRaw("case when '$toDate' = '--' then 1 else ApplicationDate <= ? end",array($toDate))
								->whereNull('CrpEngineerId')->where('SysRejectorUserId',$userId)->count();
		}
		if(in_array(305,$userAllPrivileges)){ //SP
			$spRA = DB::table('crpspecializedtrade')
								->whereRaw("ApplicationDate >= ?",array($fromDate))
								->whereRaw("case when '$toDate' = '--' then 1 else ApplicationDate <= ? end",array($toDate))
								->whereNull('CrpSpecializedTradeId')->where('SysApproverUserId',$userId)->count();

            $pending = DB::table('crpspecializedtrade')
                ->whereRaw("ApplicationDate >= ?",array($fromDate))
                ->whereRaw("case when '$toDate' = '--' then 1 else ApplicationDate <= ? end",array($toDate))
                ->whereRaw("coalesce(RegistrationStatus,0)=1")
                ->whereNull('CrpSpecializedTradeId')->where('CmnApplicationRegistrationStatusId',CONST_CMN_APPLICATIONREGISTRATIONSTATUS_VERIFIED)->count();
            $approvalPending += $pending;

			$spRR = DB::table('crpspecializedtrade')
								->whereRaw("ApplicationDate >= ?",array($fromDate))
								->whereRaw("case when '$toDate' = '--' then 1 else ApplicationDate <= ? end",array($toDate))
								->whereNull('CrpSpecializedTradeId')->where('SysRejectorUserId',$userId)->count();
		}
		if(in_array(306,$userAllPrivileges)){ //CONTRACTOR
            $pending =  DB::table('crpcontractor as T1')
                ->join('crpcontractorappliedservice as T2','T2.CrpContractorId','=','T1.Id')
                ->whereNotNull('T1.CrpContractorId')
                ->whereRaw("T1.ApplicationDate >= ?",array($fromDate))
                ->whereRaw("case when '$toDate' = '--' then 1 else T1.ApplicationDate <= ? end",array($toDate))
                ->whereRaw("coalesce(T1.RegistrationStatus,0)=1")
                ->where('T1.CmnApplicationRegistrationStatusId',CONST_CMN_APPLICATIONREGISTRATIONSTATUS_VERIFIED)
                ->get(array(DB::raw("count(distinct T1.Id) as Total")));
            $approvalPending += $pending[0]->Total;

			foreach($contractorServices as $contractorServiceName => $contractorService):
				$count= DB::table('crpcontractor as T1')
					->join('crpcontractorappliedservice as T2','T2.CrpContractorId','=','T1.Id')
					->whereNotNull('T1.CrpContractorId')
					->where('T2.CmnServiceTypeId',$contractorService)
					->where('SysApproverUserId',$userId)
					->whereRaw("T1.ApplicationDate >= ?",array($fromDate))
                	->whereRaw("case when '$toDate' = '--' then 1 else T1.ApplicationDate <= ? end",array($toDate))
					->get(array(DB::raw("count(distinct T1.Id) as Total")));
				$contractorServiceA[$contractorService]['Count'] = $count[0]->Total;
				$contractorServiceA[$contractorService]['Name'] = $contractorServiceName;

				$count = DB::table('crpcontractor as T1')
					->join('crpcontractorappliedservice as T2','T2.CrpContractorId','=','T1.Id')
					->whereNotNull('T1.CrpContractorId')
					->where('T2.CmnServiceTypeId',$contractorService)
					->where('SysRejecterUserId',$userId)
					->whereRaw("T1.ApplicationDate >= ?",array($fromDate))
                	->whereRaw("case when '$toDate' = '--' then 1 else T1.ApplicationDate <= ? end",array($toDate))
					->get(array(DB::raw("count(distinct T1.Id) as Total")));
				$contractorServiceR[$contractorService]['Count'] = $count[0]->Total;
				$contractorServiceR[$contractorService]['Name'] = $contractorServiceName;
			endforeach;
		}
		if(in_array(307,$userAllPrivileges)){ //CONSULTANT
            $pending =  DB::table('crpconsultant as T1')
                ->join('crpconsultantappliedservice as T2','T2.CrpConsultantId','=','T1.Id')
                ->whereNotNull('T1.CrpConsultantId')
                ->where('T2.CmnServiceTypeId',$consultantService)
                ->whereRaw("T1.ApplicationDate >= ?",array($fromDate))
                ->whereRaw("case when '$toDate' = '--' then 1 else T1.ApplicationDate <= ? end",array($toDate))
                ->where('T1.CmnApplicationRegistrationStatusId',CONST_CMN_APPLICATIONREGISTRATIONSTATUS_VERIFIED)
                ->whereRaw("coalesce(T1.RegistrationStatus,0)=1")
                ->get(array(DB::raw("count(distinct T1.Id) as Total")));
            $approvalPending += $pending[0]->Total;

			foreach($consultantServices as $consultantServiceName => $consultantService):
				$count= DB::table('crpconsultant as T1')
					->join('crpconsultantappliedservice as T2','T2.CrpConsultantId','=','T1.Id')
					->whereNotNull('T1.CrpConsultantId')
					->where('T2.CmnServiceTypeId',$consultantService)
					->where('SysApproverUserId',$userId)
					->whereRaw("T1.ApplicationDate >= ?",array($fromDate))
                	->whereRaw("case when '$toDate' = '--' then 1 else T1.ApplicationDate <= ? end",array($toDate))
					->get(array(DB::raw("count(distinct T1.Id) as Total")));
				$consultantServiceA[$consultantService]['Count'] = $count[0]->Total;
				$consultantServiceA[$consultantService]['Name'] = $consultantServiceName;

				$count = DB::table('crpconsultant as T1')
					->join('crpconsultantappliedservice as T2','T2.CrpConsultantId','=','T1.Id')
					->whereNotNull('T1.CrpConsultantId')
					->where('T2.CmnServiceTypeId',$consultantService)
					->where('SysRejectorUserId',$userId)
					->whereRaw("T1.ApplicationDate >= ?",array($fromDate))
                	->whereRaw("case when '$toDate' = '--' then 1 else T1.ApplicationDate <= ? end",array($toDate))
					->get(array(DB::raw("count(distinct T1.Id) as Total")));
				$consultantServiceR[$consultantService]['Count'] = $count[0]->Total;
				$consultantServiceR[$consultantService]['Name'] = $consultantServiceName;
			endforeach;
		}
		if(in_array(308,$userAllPrivileges)){ //ARCHITECT
            $pending =  DB::table('crparchitect as T1')
                ->join('crparchitectappliedservice as T2','T2.CrpArchitectId','=','T1.Id')
                ->whereNotNull('T1.CrpArchitectId')
                ->whereRaw("T1.ApplicationDate >= ?",array($fromDate))
                ->whereRaw("case when '$toDate' = '--' then 1 else T1.ApplicationDate <= ? end",array($toDate))
                ->whereRaw("coalesce(T1.RegistrationStatus,0)=1")
                ->where('T1.CmnApplicationRegistrationStatusId',CONST_CMN_APPLICATIONREGISTRATIONSTATUS_VERIFIED)
                ->get(array(DB::raw("count(distinct T1.Id) as Total")));
            $approvalPending += $pending[0]->Total;

			foreach($architectServices as $architectServiceName => $architectService):
				$count= DB::table('crparchitect as T1')
					->join('crparchitectappliedservice as T2','T2.CrpArchitectId','=','T1.Id')
					->whereNotNull('T1.CrpArchitectId')
					->where('T2.CmnServiceTypeId',$architectService)
					->where('SysApproverUserId',$userId)
					->whereRaw("T1.ApplicationDate >= ?",array($fromDate))
                	->whereRaw("case when '$toDate' = '--' then 1 else T1.ApplicationDate <= ? end",array($toDate))
					->get(array(DB::raw("count(distinct T1.Id) as Total")));
				$architectServiceA[$architectService]['Count'] = $count[0]->Total;
				$architectServiceA[$architectService]['Name'] = $architectServiceName;

				$count= DB::table('crparchitect as T1')
					->join('crparchitectappliedservice as T2','T2.CrpArchitectId','=','T1.Id')
					->whereNotNull('T1.CrpArchitectId')
					->where('T2.CmnServiceTypeId',$architectService)
					->where('SysRejectorUserId',$userId)
					->whereRaw("T1.ApplicationDate >= ?",array($fromDate))
                	->whereRaw("case when '$toDate' = '--' then 1 else T1.ApplicationDate <= ? end",array($toDate))
					->get(array(DB::raw("count(distinct T1.Id) as Total")));
				$architectServiceR[$architectService]['Count'] = $count[0]->Total;
				$architectServiceR[$architectService]['Name'] = $architectServiceName;
			endforeach;
		}
		if(in_array(309,$userAllPrivileges)){ //ENGINEER
            $pending =  DB::table('crpengineer as T1')
                ->join('crpengineerappliedservice as T2','T2.CrpEngineerId','=','T1.Id')
                ->whereNotNull('T1.CrpEngineerId')
                ->whereRaw("T1.ApplicationDate >= ?",array($fromDate))
                ->whereRaw("case when '$toDate' = '--' then 1 else T1.ApplicationDate <= ? end",array($toDate))
                ->where('T1.CmnApplicationRegistrationStatusId',CONST_CMN_APPLICATIONREGISTRATIONSTATUS_VERIFIED)
                ->whereRaw("coalesce(T1.RegistrationStatus,0)=1")
                ->get(array(DB::raw("count(distinct T1.Id) as Total")));
            $approvalPending += $pending[0]->Total;

			foreach($engineerServices as $engineerServiceName => $engineerService):
				$count= DB::table('crpengineer as T1')
					->join('crpengineerappliedservice as T2','T2.CrpEngineerId','=','T1.Id')
					->whereNotNull('T1.CrpEngineerId')
					->where('T2.CmnServiceTypeId',$engineerService)
					->where('SysApproverUserId',$userId)
					->whereRaw("T1.ApplicationDate >= ?",array($fromDate))
                	->whereRaw("case when '$toDate' = '--' then 1 else T1.ApplicationDate <= ? end",array($toDate))
					->get(array(DB::raw("count(distinct T1.Id) as Total")));
				$engineerServiceA[$engineerService]['Count'] = $count[0]->Total;
				$engineerServiceA[$engineerService]['Name'] = $engineerServiceName;

				$count= DB::table('crpengineer as T1')
					->join('crpengineerappliedservice as T2','T2.CrpEngineerId','=','T1.Id')
					->whereNotNull('T1.CrpEngineerId')
					->where('T2.CmnServiceTypeId',$engineerService)
					->where('SysRejectorUserId',$userId)
					->whereRaw("T1.ApplicationDate >= ?",array($fromDate))
                	->whereRaw("case when '$toDate' = '--' then 1 else T1.ApplicationDate <= ? end",array($toDate))
					->get(array(DB::raw("count(distinct T1.Id) as Total")));
				$engineerServiceR[$engineerService]['Count'] = $count[0]->Total;
				$engineerServiceR[$engineerService]['Name'] = $engineerServiceName;
			endforeach;
		}

		if(in_array(310,$userAllPrivileges)){ //SP
            $pending =  DB::table('crpspecializedtrade as T1')
                ->join('crpspecializedtradeappliedservice as T2','T2.CrpSpecializedTradeId','=','T1.Id')
                ->whereNotNull('T1.CrpSpecializedTradeId')
                ->whereRaw("T1.ApplicationDate >= ?",array($fromDate))
                ->whereRaw("case when '$toDate' = '--' then 1 else T1.ApplicationDate <= ? end",array($toDate))
                ->whereRaw("coalesce(T1.RegistrationStatus,0)=1")
                ->where('T1.CmnApplicationRegistrationStatusId',CONST_CMN_APPLICATIONREGISTRATIONSTATUS_VERIFIED)
                ->get(array(DB::raw("count(distinct T1.Id) as Total")));
            $approvalPending += $pending[0]->Total;

			foreach($spServices as $specializedTradeServiceName => $specializedTradeService):
				$count= DB::table('crpspecializedtrade as T1')
					->join('crpspecializedtradeappliedservice as T2','T2.CrpSpecializedTradeId','=','T1.Id')
					->whereNotNull('T1.CrpSpecializedTradeId')
					->where('T2.CmnServiceTypeId',$specializedTradeService)
					->where('SysApproverUserId',$userId)
					->whereRaw("T1.ApplicationDate >= ?",array($fromDate))
                	->whereRaw("case when '$toDate' = '--' then 1 else T1.ApplicationDate <= ? end",array($toDate))
					->get(array(DB::raw("count(distinct T1.Id) as Total")));
				$specializedTradeServiceA[$specializedTradeService]['Count'] = $count[0]->Total;
				$specializedTradeServiceA[$specializedTradeService]['Name'] = $specializedTradeServiceName;

				$count= DB::table('crpspecializedtrade as T1')
					->join('crpspecializedtradeappliedservice as T2','T2.CrpSpecializedTradeId','=','T1.Id')
					->whereNotNull('T1.CrpSpecializedTradeId')
					->where('T2.CmnServiceTypeId',$specializedTradeService)
					->where('SysRejectorUserId',$userId)
					->whereRaw("T1.ApplicationDate >= ?",array($fromDate))
                	->whereRaw("case when '$toDate' = '--' then 1 else T1.ApplicationDate <= ? end",array($toDate))
					->get(array(DB::raw("count(distinct T1.Id) as Total")));
				$specializedTradeServiceR[$specializedTradeService]['Count'] = $count[0]->Total;
				$specializedTradeServiceR[$specializedTradeService]['Name'] = $specializedTradeServiceName;
			endforeach;
		}
        $paymentPending = 0;
		if(in_array(401,$userAllPrivileges)){ //CONTRACTOR
			$contractorRPA = DB::table('crpcontractor')
									->whereRaw("ApplicationDate >= ?",array($fromDate))
									->whereRaw("case when '$toDate' = '--' then 1 else ApplicationDate <= ? end",array($toDate))
								->whereNull('CrpContractorId')->where('SysPaymentApproverUserId',$userId)->count();

            $pending = DB::table('crpcontractor')
                ->whereRaw("ApplicationDate >= ?",array($fromDate))
                ->whereRaw("case when '$toDate' = '--' then 1 else ApplicationDate <= ? end",array($toDate))
                ->whereRaw("coalesce(RegistrationStatus,0)=1")
                ->whereNull('CrpContractorId')->where('CmnApplicationRegistrationStatusId',CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVEDFORPAYMENT)->count();
            $paymentPending += $pending;

			$contractorRR = DB::table('crpcontractor')
									->whereRaw("ApplicationDate >= ?",array($fromDate))
									->whereRaw("case when '$toDate' = '--' then 1 else ApplicationDate <= ? end",array($toDate))
								->whereNull('CrpContractorId')->where('SysRejecterUserId',$userId)->count();
		}
		if(in_array(402,$userAllPrivileges)){ //CONSULTANT
			$consultantRPA = DB::table('crpconsultant')
									->whereRaw("ApplicationDate >= ?",array($fromDate))
									->whereRaw("case when '$toDate' = '--' then 1 else ApplicationDate <= ? end",array($toDate))
								->whereNull('CrpConsultantId')->where('SysPaymentApproverUserId',$userId)->count();

            $pending = DB::table('crpconsultant')
                ->whereRaw("ApplicationDate >= ?",array($fromDate))
                ->whereRaw("case when '$toDate' = '--' then 1 else ApplicationDate <= ? end",array($toDate))
                ->whereRaw("coalesce(RegistrationStatus,0)=1")
                ->whereNull('CrpConsultantId')->where('CmnApplicationRegistrationStatusId',CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVEDFORPAYMENT)->count();
            $paymentPending += $pending;

			$consultantRR = DB::table('crpconsultant')
									->whereRaw("ApplicationDate >= ?",array($fromDate))
									->whereRaw("case when '$toDate' = '--' then 1 else ApplicationDate <= ? end",array($toDate))
								->whereNull('CrpConsultantId')->where('SysRejectorUserId',$userId)->count();
		}
		if(in_array(403,$userAllPrivileges)){ //ARCHITECT
			$architectRPA = DB::table('crparchitect')
									->whereRaw("ApplicationDate >= ?",array($fromDate))
									->whereRaw("case when '$toDate' = '--' then 1 else ApplicationDate <= ? end",array($toDate))
								->whereNull('CrpArchitectId')->where('SysPaymentApproverUserId',$userId)->count();

            $pending = DB::table('crparchitect')
                ->whereRaw("ApplicationDate >= ?",array($fromDate))
                ->whereRaw("case when '$toDate' = '--' then 1 else ApplicationDate <= ? end",array($toDate))
                ->whereRaw("coalesce(RegistrationStatus,0)=1")
                ->whereNull('CrpArchitectId')->where('CmnApplicationRegistrationStatusId',CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVEDFORPAYMENT)->count();
            $paymentPending += $pending;

			$architectRR = DB::table('crparchitect')
									->whereRaw("ApplicationDate >= ?",array($fromDate))
									->whereRaw("case when '$toDate' = '--' then 1 else ApplicationDate <= ? end",array($toDate))
								->whereNull('CrpArchitectId')->where('SysRejectorUserId',$userId)->count();
		}
		if(in_array(404,$userAllPrivileges)){ //ENGINEER
			$engineerRPA = DB::table('crpengineer')
									->whereRaw("ApplicationDate >= ?",array($fromDate))
									->whereRaw("case when '$toDate' = '--' then 1 else ApplicationDate <= ? end",array($toDate))
								->whereNull('CrpEngineerId')->where('SysPaymentApproverUserId',$userId)->count();

            $pending = DB::table('crpengineer')
                ->whereRaw("ApplicationDate >= ?",array($fromDate))
                ->whereRaw("case when '$toDate' = '--' then 1 else ApplicationDate <= ? end",array($toDate))
                ->whereRaw("coalesce(RegistrationStatus,0)=1")
                ->whereNull('CrpEngineerId')->where('CmnApplicationRegistrationStatusId',CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVEDFORPAYMENT)->count();
            $paymentPending += $pending;

			$engineerRR = DB::table('crpengineer')
									->whereRaw("ApplicationDate >= ?",array($fromDate))
									->whereRaw("case when '$toDate' = '--' then 1 else ApplicationDate <= ? end",array($toDate))
								->whereNull('CrpEngineerId')->where('SysRejectorUserId',$userId)->count();
		}
		if(in_array(406,$userAllPrivileges)){ //CONTRACTOR
            $pending =  DB::table('crpcontractor as T1')
                ->join('crpcontractorappliedservice as T2','T2.CrpContractorId','=','T1.Id')
                ->whereNotNull('T1.CrpContractorId')
                ->whereRaw("T1.ApplicationDate >= ?",array($fromDate))
                ->whereRaw("case when '$toDate' = '--' then 1 else T1.ApplicationDate <= ? end",array($toDate))
                ->where('T1.CmnApplicationRegistrationStatusId',CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVEDFORPAYMENT)
                ->whereRaw("coalesce(T1.RegistrationStatus,0)=1")
                ->get(array(DB::raw("count(distinct T1.Id) as Total")));
            $paymentPending += $pending[0]->Total;

			foreach($contractorServices as $contractorServiceName => $contractorService):
				$count= DB::table('crpcontractor as T1')
					->join('crpcontractorappliedservice as T2','T2.CrpContractorId','=','T1.Id')
					->whereNotNull('T1.CrpContractorId')
					->where('T2.CmnServiceTypeId',$contractorService)
					->where('SysPaymentApproverUserId',$userId)
					->whereRaw("T1.ApplicationDate >= ?",array($fromDate))
                	->whereRaw("case when '$toDate' = '--' then 1 else T1.ApplicationDate <= ? end",array($toDate))
					->get(array(DB::raw("count(distinct T1.Id) as Total")));
				$contractorServicePA[$contractorService]['Count'] = $count[0]->Total;
				$contractorServicePA[$contractorService]['Name'] = $contractorServiceName;

				$count = DB::table('crpcontractor as T1')
					->join('crpcontractorappliedservice as T2','T2.CrpContractorId','=','T1.Id')
					->whereNotNull('T1.CrpContractorId')
					->where('T2.CmnServiceTypeId',$contractorService)
					->where('SysRejecterUserId',$userId)
					->whereRaw("T1.ApplicationDate >= ?",array($fromDate))
                	->whereRaw("case when '$toDate' = '--' then 1 else T1.ApplicationDate <= ? end",array($toDate))
					->get(array(DB::raw("count(distinct T1.Id) as Total")));
				$contractorServiceR[$contractorService]['Count'] = $count[0]->Total;
				$contractorServiceR[$contractorService]['Name'] = $contractorServiceName;
			endforeach;
		}
		if(in_array(407,$userAllPrivileges)){ //CONSULTANT
            $pending =  DB::table('crpconsultant as T1')
                ->join('crpconsultantappliedservice as T2','T2.CrpConsultantId','=','T1.Id')
                ->whereNotNull('T1.CrpConsultantId')
                ->whereRaw("T1.ApplicationDate >= ?",array($fromDate))
                ->whereRaw("case when '$toDate' = '--' then 1 else T1.ApplicationDate <= ? end",array($toDate))
                ->where('T1.CmnApplicationRegistrationStatusId',CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVEDFORPAYMENT)
                ->whereRaw("coalesce(T1.RegistrationStatus,0)=1")
                ->get(array(DB::raw("count(distinct T1.Id) as Total")));
            $paymentPending += $pending[0]->Total;

			foreach($consultantServices as $consultantServiceName => $consultantService):
				$count= DB::table('crpconsultant as T1')
					->join('crpconsultantappliedservice as T2','T2.CrpConsultantId','=','T1.Id')
					->whereNotNull('T1.CrpConsultantId')
					->where('T2.CmnServiceTypeId',$consultantService)
					->where('SysPaymentApproverUserId',$userId)
					->whereRaw("T1.ApplicationDate >= ?",array($fromDate))
                	->whereRaw("case when '$toDate' = '--' then 1 else T1.ApplicationDate <= ? end",array($toDate))
					->get(array(DB::raw("count(distinct T1.Id) as Total")));
				$consultantServicePA[$consultantService]['Count'] = $count[0]->Total;
				$consultantServicePA[$consultantService]['Name'] = $consultantServiceName;

				$count = DB::table('crpconsultant as T1')
					->join('crpconsultantappliedservice as T2','T2.CrpConsultantId','=','T1.Id')
					->whereNotNull('T1.CrpConsultantId')
					->where('T2.CmnServiceTypeId',$consultantService)
					->where('SysRejectorUserId',$userId)
					->whereRaw("T1.ApplicationDate >= ?",array($fromDate))
                	->whereRaw("case when '$toDate' = '--' then 1 else T1.ApplicationDate <= ? end",array($toDate))
					->get(array(DB::raw('count(distinct T1.Id) as Total')));
				$consultantServiceR[$consultantService]['Count'] = $count[0]->Total;
				$consultantServiceR[$consultantService]['Name'] = $consultantServiceName;
			endforeach;
		}
		if(in_array(408,$userAllPrivileges)){ //ARCHITECT
            $pending =  DB::table('crparchitect as T1')
                ->join('crparchitectappliedservice as T2','T2.CrpArchitectId','=','T1.Id')
                ->whereNotNull('T1.CrpArchitectId')
                ->whereRaw("T1.ApplicationDate >= ?",array($fromDate))
                ->whereRaw("case when '$toDate' = '--' then 1 else T1.ApplicationDate <= ? end",array($toDate))
                ->where('T1.CmnApplicationRegistrationStatusId',CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVEDFORPAYMENT)
                ->whereRaw("coalesce(T1.RegistrationStatus,0)=1")
                ->get(array(DB::raw("count(distinct T1.Id) as Total")));
            $paymentPending += $pending[0]->Total;

			foreach($architectServices as $architectServiceName => $architectService):
				$count= DB::table('crparchitect as T1')
					->join('crparchitectappliedservice as T2','T2.CrpArchitectId','=','T1.Id')
					->whereNotNull('T1.CrpArchitectId')
					->where('T2.CmnServiceTypeId',$architectService)
					->where('SysPaymentApproverUserId',$userId)
					->whereRaw("T1.ApplicationDate >= ?",array($fromDate))
                	->whereRaw("case when '$toDate' = '--' then 1 else T1.ApplicationDate <= ? end",array($toDate))
					->get(array(DB::raw("count(distinct T1.Id) as Total")));
				$architectServicePA[$architectService]['Count'] = $count[0]->Total;
				$architectServicePA[$architectService]['Name'] = $architectServiceName;

				$count= DB::table('crparchitect as T1')
					->join('crparchitectappliedservice as T2','T2.CrpArchitectId','=','T1.Id')
					->whereNotNull('T1.CrpArchitectId')
					->where('T2.CmnServiceTypeId',$architectService)
					->where('SysRejectorUserId',$userId)
					->whereRaw("T1.ApplicationDate >= ?",array($fromDate))
                	->whereRaw("case when '$toDate' = '--' then 1 else T1.ApplicationDate <= ? end",array($toDate))
					->get(array(DB::raw('count(distinct T1.Id) as Total')));
				$architectServiceR[$architectService]['Count'] = $count[0]->Total;
				$architectServiceR[$architectService]['Name'] = $architectServiceName;
			endforeach;
		}
		if(in_array(409,$userAllPrivileges)){ //ENGINEER
            $pending =  DB::table('crpengineer as T1')
                ->join('crpengineerappliedservice as T2','T2.CrpEngineerId','=','T1.Id')
                ->whereNotNull('T1.CrpEngineerId')
                ->whereRaw("T1.ApplicationDate >= ?",array($fromDate))
                ->whereRaw("case when '$toDate' = '--' then 1 else T1.ApplicationDate <= ? end",array($toDate))
                ->where('T1.CmnApplicationRegistrationStatusId',CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVEDFORPAYMENT)
                ->whereRaw("coalesce(T1.RegistrationStatus,0)=1")
                ->get(array(DB::raw("count(distinct T1.Id) as Total")));
            $paymentPending += $pending[0]->Total;

			foreach($engineerServices as $engineerServiceName => $engineerService):
				$count= DB::table('crpengineer as T1')
					->join('crpengineerappliedservice as T2','T2.CrpEngineerId','=','T1.Id')
					->whereNotNull('T1.CrpEngineerId')
					->where('T2.CmnServiceTypeId',$engineerService)
					->where('SysPaymentApproverUserId',$userId)
					->whereRaw("T1.ApplicationDate >= ?",array($fromDate))
                	->whereRaw("case when '$toDate' = '--' then 1 else T1.ApplicationDate <= ? end",array($toDate))
					->get(array(DB::raw("count(distinct T1.Id) as Total")));
				$engineerServicePA[$engineerService]['Count'] = $count[0]->Total;
				$engineerServicePA[$engineerService]['Name'] = $engineerServiceName;

				$count= DB::table('crpengineer as T1')
					->join('crpengineerappliedservice as T2','T2.CrpEngineerId','=','T1.Id')
					->whereNotNull('T1.CrpEngineerId')
					->where('T2.CmnServiceTypeId',$engineerService)
					->where('SysRejectorUserId',$userId)
					->whereRaw("T1.ApplicationDate >= ?",array($fromDate))
                	->whereRaw("case when '$toDate' = '--' then 1 else T1.ApplicationDate <= ? end",array($toDate))
					->get(array(DB::raw("count(distinct T1.Id) as Total")));
				$engineerServiceR[$engineerService]['Count'] = $count[0]->Total;
				$engineerServiceR[$engineerService]['Name'] = $engineerServiceName;
			endforeach;
		}
		if(in_array(410,$userAllPrivileges)){ //SP
            $pending =   DB::table('crpspecializedtrade as T1')
                ->join('crpspecializedtradeappliedservice as T2','T2.CrpSpecializedTradeId','=','T1.Id')
                ->whereNotNull('T1.CrpSpecializedTradeId')
                ->whereRaw("T1.ApplicationDate >= ?",array($fromDate))
                ->whereRaw("case when '$toDate' = '--' then 1 else T1.ApplicationDate <= ? end",array($toDate))
                ->where('T1.CmnApplicationRegistrationStatusId',CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVEDFORPAYMENT)
                ->whereRaw("coalesce(T1.RegistrationStatus,0)=1")
                ->get(array(DB::raw("count(distinct T1.Id) as Total")));
            $paymentPending += $pending[0]->Total;

			foreach($spServices as $specializedTradeServiceName => $specializedTradeService):
				$count= DB::table('crpspecializedtrade as T1')
					->join('crpspecializedtradeappliedservice as T2','T2.CrpSpecializedTradeId','=','T1.Id')
					->whereNotNull('T1.CrpSpecializedTradeId')
					->where('T2.CmnServiceTypeId',$specializedTradeService)
					->where('SysPaymentApproverUserId',$userId)
					->whereRaw("T1.ApplicationDate >= ?",array($fromDate))
                	->whereRaw("case when '$toDate' = '--' then 1 else T1.ApplicationDate <= ? end",array($toDate))
					->get(array(DB::raw("count(distinct T1.Id) as Total")));
				$specializedTradeServicePA[$specializedTradeService]['Count'] = $count[0]->Total;
				$specializedTradeServicePA[$specializedTradeService]['Name'] = $specializedTradeServiceName;


				$count= DB::table('crpspecializedtrade as T1')
					->join('crpspecializedtradeappliedservice as T2','T2.CrpSpecializedTradeId','=','T1.Id')
					->whereNotNull('T1.CrpSpecializedTradeId')
					->where('T2.CmnServiceTypeId',$specializedTradeService)
					->where('SysRejectorUserId',$userId)
					->whereRaw("T1.ApplicationDate >= ?",array($fromDate))
                	->whereRaw("case when '$toDate' = '--' then 1 else T1.ApplicationDate <= ? end",array($toDate))
					->get(array(DB::raw("count(distinct T1.Id) as Total")));
				$specializedTradeServiceR[$specializedTradeService]['Count'] = $count[0]->Total;
				$specializedTradeServiceR[$specializedTradeService]['Name'] = $specializedTradeServiceName;
			endforeach;
		}
		return View::make('ezhotin.individualtaskreport')
				->with('crpsUsers',$crpsUsers)
                ->with('verificationPending',$verificationPending)
                ->with('approvalPending',$approvalPending)
                ->with('paymentPending',$paymentPending)
				->with('userPendingCount',$userPendingCount)
				->with('contractorServices',$contractorServices)
				->with('consultantServices',$consultantServices)
				->with('architectServices',$architectServices)
				->with('engineerServices',$engineerServices)
				->with('spServices',$spServices)
				->with('contractorRR',$contractorRR)
				->with('consultantRR',$consultantRR)
				->with('architectRR',$architectRR)
				->with('engineerRR',$engineerRR)
				->with('spRR',$spRR)
				->with('contractorServiceR',$contractorServiceR)
				->with('consultantServiceR',$consultantServiceR)
				->with('architectServiceR',$architectServiceR)
				->with('engineerServiceR',$engineerServiceR)
				->with('specializedTradeServiceR',$specializedTradeServiceR)
				->with('contractorRPA',$contractorRPA)
				->with('consultantRPA',$consultantRPA)
				->with('architectRPA',$architectRPA)
				->with('engineerRPA',$engineerRPA)
				->with('contractorServicePA',$contractorServicePA)
				->with('consultantServicePA',$consultantServicePA)
				->with('architectServicePA',$architectServicePA)
				->with('engineerServicePA',$engineerServicePA)
				->with('specializedTradeServicePA',$specializedTradeServicePA)
				->with('contractorRA',$contractorRA)
				->with('consultantRA',$consultantRA)
				->with('architectRA',$architectRA)
				->with('engineerRA',$engineerRA)
				->with('spRA',$spRA)
				->with('contractorServiceA',$contractorServiceA)
				->with('consultantServiceA',$consultantServiceA)
				->with('architectServiceA',$architectServiceA)
				->with('engineerServiceA',$engineerServiceA)
				->with('specializedTradeServiceA',$specializedTradeServiceA)
				->with('contractorRV',$contractorRV)
				->with('consultantRV',$consultantRV)
				->with('architectRV',$architectRV)
				->with('engineerRV',$engineerRV)
				->with('spRV',$spRV)
				->with('contractorServiceV',$contractorServiceV)
				->with('consultantServiceV',$consultantServiceV)
				->with('architectServiceV',$architectServiceV)
				->with('engineerServiceV',$engineerServiceV)
				->with('specializedTradeServiceV',$specializedTradeServiceV)
				->with('userApproveCount',$userApproveCount)
				->with('userAllPrivileges',$userAllPrivileges);
	}
}
