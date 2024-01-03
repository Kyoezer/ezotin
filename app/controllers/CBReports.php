<?php

class CBReports extends CBController{
    public function getIndex(){
        // $loggedInUser=Auth::user()->Id;
        // $userRoles=DB::table('sysuserrolemap')->where('SysUserId',$loggedInUser)->lists('SysRoleId');
        // $cbReportsArray = array();
        // if(in_array(CONST_ROLE_PROCURINGAGENCYCINET,$userRoles,true)){
        //     $cbReports = DB::table('sysmenu as T1')
        //         ->join('sysuserreportmap as T2','T2.SysMenuId','=','T1.Id')
        //         ->where('T2.Type',2)
        //         ->where(DB::raw('coalesce(T2.PageView,0)'),'=',1)
        //         ->where('T2.SysUserId',Auth::user()->Id)
        //         ->orderBy('T1.ReferenceNo')
        //         ->get(array(DB::raw('distinct T1.ReferenceNo as MenuReference')));
        //     foreach($cbReports as $cbReports):
        //         array_push($cbReportsArray,$cbReports->MenuReference);
        //     endforeach;
        // }

        return View::make('cbreports');
    }
}