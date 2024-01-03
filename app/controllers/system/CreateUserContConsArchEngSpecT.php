<?php
class CreateUserContConsArchEngSpecT extends SystemController{
	public function index($userId=NULL){
		if((bool)$userId!=NULL){
			$users=User::where('Id','=',$userId)->get();
			$roles=DB::select("select T1.Id,T1.Name,T1.ReferenceNo,case when T2.Id is null then 0 else 1 end as Selected,T2.Id as UserMapId from sysrole T1 left join sysuserrolemap T2 on T1.Id=T2.SysRoleId and T2.SysUserId=? where coalesce(T1.ReferenceNo,0) in (2,3,4,5,6,9,10) order by T1.Name",array($userId));
		}else{
			$users=array(new User());
			$roles=RoleModel::whereRaw("coalesce(ReferenceNo,0) in (2,3,4,5,6,9,10)")->orderBy('Name')->get(array('Id','Name','ReferenceNo'));
		}
		$contractors=ContractorFinalModel::contractorHardListAll()->where('SysUserId',NULL)->get(array('Id',DB::raw('concat(NameOfFirm," (",coalesce(CDBNo,""),")") as NameOfFirm')));
		$consultants=ConsultantFinalModel::consultantHardListAll()->where('SysUserId',NULL)->get(array('Id',DB::raw('concat(NameOfFirm," (",coalesce(CDBNo,""),")") as NameOfFirm')));
		$architects=ArchitectFinalModel::architectHardListAll()->where('SysUserId',NULL)->get(array('Id',DB::raw('concat(Name," (",coalesce(ARNo,""),")") as Name')));
		$engineers=EngineerFinalModel::engineerHardListAll()->where('SysUserId',NULL)->get(array('Id',DB::raw('concat(Name," (",coalesce(CDBNo,""),")") as Name')));
		$surveyors=SurveyFinalModel::surveyHardListAll()->where('SysUserId',NULL)->get(array('Id',DB::raw('concat(Name," (",coalesce(ARNo,""),")") as Name')));
		$specializedTrades=SpecializedTradeFinalModel::specializedTradeHardListAll()->where('SysUserId',NULL)->get(array('Id',DB::raw('concat(Name," (",coalesce(SPNo,""),")") as Name')));
		$specializedfirms=SpecializedfirmFinalModel::SpecializedTradeHardListAll()->where('SysUserId',NULL)->get(array('Id',DB::raw('concat(NameOfFirm," (",coalesce(SPNo,""),")") as NameOfFirm')));
		return View::make('sys.createusercontconsarchengspect')
					->with('roles',$roles)
					->with('users',$users)
					->with('contractors',$contractors)
					->with('consultants',$consultants)
					->with('architects',$architects)
					->with('engineers',$engineers)
					->with('surveyors',$surveyors)
					->with('specializedfirms',$specializedfirms)
					->with('specializedTrades',$specializedTrades);
	}
}