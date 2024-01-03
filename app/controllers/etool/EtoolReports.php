<?php
/**
 * Created by PhpStorm.
 * User: Sangay
 * Date: 6/9/2015
 * Time: 2:49 PM
 */

class EtoolReports extends EtoolController{
    public function getIndex(){
        $loggedInUser=Auth::user()->Id;
        $userRoles=DB::table('sysuserrolemap')->where('SysUserId',$loggedInUser)->lists('SysRoleId');
        $etoolReportsArray = array();
        if(in_array(CONST_ROLE_PROCURINGAGENCYETOOL,$userRoles,true)){
            $etoolReports = DB::table('sysmenu as T1')
                ->join('sysuserreportmap as T2','T2.SysMenuId','=','T1.Id')
                ->where('T2.Type',1)
                ->where(DB::raw('coalesce(T2.PageView,0)'),'=',1)
                ->where('T2.SysUserId',Auth::user()->Id)
                ->orderBy('T1.ReferenceNo')
                ->get(array(DB::raw('distinct T1.ReferenceNo as MenuReference')));
            foreach($etoolReports as $etoolReport):
                array_push($etoolReportsArray,$etoolReport->MenuReference);
            endforeach;
        }
                	//$equipments = CmnEquipmentModel::equipment()->whereNotNull('Code')->get(array('Id','Name','VehicleType'));
	$equipments = DB::select("SELECT VehicleType ,`VehicleTypeName` AS Name FROM  cmnequipment WHERE VehicleType > 0 GROUP BY VehicleType");
        return View::make('new_etool.etoolreports')
            ->with('etoolReports',$etoolReportsArray)
            ->with('equipments',$equipments);
    }
}