<?php
/**
 * Created by PhpStorm.
 * User: Sangay
 * Date: 6/29/2015
 * Time: 12:33 PM
 */

class CiNETReports extends CiNetController{
    public function getIndex(){
        $loggedInUser=Auth::user()->Id;
        $userRoles=DB::table('sysuserrolemap')->where('SysUserId',$loggedInUser)->lists('SysRoleId');
        $cinetReportsArray = array();
        if(in_array(CONST_ROLE_PROCURINGAGENCYCINET,$userRoles,true)){
            $cinetReports = DB::table('sysmenu as T1')
                ->join('sysuserreportmap as T2','T2.SysMenuId','=','T1.Id')
                ->where('T2.Type',2)
                ->where(DB::raw('coalesce(T2.PageView,0)'),'=',1)
                ->where('T2.SysUserId',Auth::user()->Id)
                ->orderBy('T1.ReferenceNo')
                ->get(array(DB::raw('distinct T1.ReferenceNo as MenuReference')));
            foreach($cinetReports as $cinetReports):
                array_push($cinetReportsArray,$cinetReports->MenuReference);
            endforeach;
        }
        return View::make('cinet.cinetreports')
            ->with('cinetReports',$cinetReportsArray);
    }
}