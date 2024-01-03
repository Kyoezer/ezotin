<?php
class SystemController extends BaseController{
    public function fetchEtoolUsers(){
        $term = Input::get('term');
        $users = DB::table('sysuser as T1')
                        ->join('sysuserrolemap as T2','T2.SysUserId','=','T1.Id')
                        ->join('cmnprocuringagency as T3','T3.Id','=','T1.CmnProcuringAgencyId')
                        ->where('T2.SysRoleId','=',CONST_ROLE_PROCURINGAGENCYETOOL)
                        ->where('T1.FullName','LIKE',"%$term%")
                        ->get(array(DB::raw('distinct T1.Id'),DB::raw('concat(T1.FullName, "( ",T3.Name,")") as Name')));
        $usersJSON = array();
        foreach($users as $user):
            array_push($usersJSON,array('id'=>$user->Id,'value'=>$user->Name));
        endforeach;
        return Response::json($usersJSON);
    }

    public function fetchCinetUsers(){
        $term = Input::get('term');
        $users = DB::table('sysuser as T1')
            ->join('sysuserrolemap as T2','T2.SysUserId','=','T1.Id')
            ->join('cmnprocuringagency as T3','T3.Id','=','T1.CmnProcuringAgencyId')
            ->where('T2.SysRoleId','=',CONST_ROLE_PROCURINGAGENCYCINET)
            ->where('T1.FullName','LIKE',"%$term%")
            ->get(array(DB::raw('distinct T1.Id'),DB::raw('concat(T1.FullName, "( ",T3.Name,")") as Name')));
        $usersJSON = array();
        foreach($users as $user):
            array_push($usersJSON,array('id'=>$user->Id,'value'=>$user->Name));
        endforeach;
        return Response::json($usersJSON);
    }
    public function checkSession(){
        $userId = Input::get("userId");
        $currentUserId = isset(Auth::user()->Id)?Auth::user()->Id:'xx';
        if($userId != $currentUserId){
            $response["Response"] = 0;
        }else{
            $response["Response"] = 1;
        }
        return Response::json($response);
    }
}