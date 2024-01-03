<?php
class RoleManagement extends SystemController{
	public function index($roleId=NULL){
		$roles=array(new RoleModel());
		$roleMenuMaps=MenuGroupModel::join('sysmenu as T1','T1.SysMenuGroupId','=','sysmenugroup.Id')->whereNotIn('sysmenugroup.ReferenceNo',array(15,14,12,2,1))->where('T1.IsInactive',0)->orderBy('sysmenugroup.DisplayOrder')->orderBy('T1.MenuDisplayType')->orderBy('T1.DisplayOrder')->get(array('T1.Id','T1.MenuTitle','sysmenugroup.MenuGroupTitle','sysmenugroup.Icon','T1.MenuDisplayType'));
		if((bool)$roleId!=NULL){
			$roles=RoleModel::where('Id','=',$roleId)->get(array('Id','Name','Description'));
			$roleMenuMaps=DB::select("select T3.PageView,A.MenuGroupTitle,A.Icon,T1.Id,T1.MenuTitle,T1.MenuDisplayType from (sysmenugroup A join sysmenu T1 on T1.SysMenuGroupId=A.Id and A.ReferenceNo not in(15,14,12,2,1)) left join sysrolemenumap T3 on T1.Id=T3.SysMenuId and T3.SysRoleId=? where T1.IsInactive=0 order by A.DisplayOrder,T1.MenuDisplayType,T1.DisplayOrder",array($roleId));
		}
		return View::make('sys.rolemanagement')
					->with('roles',$roles)
    				->with('roleMenuMaps',$roleMenuMaps);
	}
	public function roleList(){
		$roleLists=RoleModel::roleListAll()->get(array('Id','Name','Description'));
		return View::make('sys.roleactionlist')
						->with('roleLists',$roleLists);
	}
	public function save(){
		$insert=false;
		$postedValues=Input::all();
		$validation = new RoleModel;
		if(!($validation->validate($postedValues))){
		    $errors = $validation->errors();
		    return Redirect::to('sys/role')->withErrors($errors)->withInput();
		}
		DB::beginTransaction();
		try{
			if(empty($postedValues['Id'])){
				$insert=true;
				$uuid=DB::select("select uuid() as Id");
		        $generatedId=$uuid[0]->Id;
		        $postedValues["Id"]=$generatedId;
				RoleModel::create($postedValues);
				foreach($postedValues as $key=>$value){
					if(gettype($value)=='array'){
						foreach($value as $key1=>$value1){
							$value1['SysRoleId']=$generatedId;
							$childTable= new RoleMenuMapModel($value1);
							$childTable->save();
						}
					}
				}
			}else{
				RoleMenuMapModel::where('SysRoleId','=',$postedValues['Id'])->delete();
				$instance=RoleModel::find($postedValues['Id']);
				$instance->fill($postedValues);
				$instance->update();
				foreach($postedValues as $key=>$value){
					if(gettype($value)=='array'){
						foreach($value as $key1=>$value1){
							$value1['SysRoleId']=$postedValues['Id'];
							$childTableReference= new RoleMenuMapModel($value1);
							$childTableReference->save();
						}
					}
				}
			}
		}catch(Exception $e){
			DB::rollback();
        	throw $e;
		}
		DB::commit();
		if($insert){
			return Redirect::to('sys/role')->with('savedsuccessmessage','Role has been sucessfully added.');
		}else{
			return Redirect::to('sys/actionsrole')->with('savedsuccessmessage','Role has been sucessfully updated.');
		}
	}

	public function assignReportsToRole(){
		$users = DB::table('sysuser as T1')
							->join('sysuserrolemap as T2','T2.SysUserId','=','T1.Id')
							->where('T2.SysRoleId',CONST_ROLE_PROCURINGAGENCYETOOL)
							->get(array(DB::raw('distinct T1.Id as Id')));
		$reportMenus = DB::table('sysmenu')->where(DB::raw('coalesce(TypeId,0)'),1)->get(array('Id'));
		$count = 0;
		foreach($users as $user):
			foreach($reportMenus as $menu):
				$saveArray = array();
				$saveArray['Id'] = $this->UUID();
				$saveArray['SysUserId'] = $user->Id;
				$saveArray['SysMenuId'] = $menu->Id;
				$saveArray['Type'] = 1;
				$saveArray['CreatedOn'] = date('Y-m-d G:i:s');
				$saveArray['CreatedBy'] = "894eba10-885b-11e5-ab33-5cf9dd5fc4f1";

				DB::table('sysuserreportmap')->insert($saveArray);
			endforeach;
			$count++;
		endforeach;

		echo $count." users assigned reports";
	}

	public function reportMap(){
		$userId = Input::get('SysUserId');
		$agencyParam = Input::get('Agency');
		if(!Input::has('page')){
			Session::forget('report_mapAgency');
		}
		if((bool)$agencyParam) {
			Session::put('report_mapAgency', $agencyParam);
			$agency = $agencyParam;
		}else{
			if(Session::has('report_mapAgency')){
				$agency = Session::get('report_mapAgency');
			}else{
				$agency = "xx";
			}
		}

		$procuringAgencies = DB::table('sysuser as T1')
								->join('cmnprocuringagency as T2','T2.Id','=','T1.CmnProcuringAgencyId')
								->get(array(DB::raw('distinct T2.Id as Id'),'T2.Name as ProcuringAgency'));
		$etoolCinetUsers = DB::table('sysuser as T1')
				->join('sysuserrolemap as T2','T2.SysUserId','=','T1.Id')
				->leftJoin('cmnprocuringagency as T3','T3.Id','=','T1.CmnProcuringAgencyId')
				->whereIn('T2.SysRoleId',array(CONST_ROLE_PROCURINGAGENCYETOOL,CONST_ROLE_PROCURINGAGENCYCINET))
				->where(DB::raw('coalesce(T1.Status,0)'),'=','1')
				->whereRaw("case when LENGTH('$userId')>30 then T1.Id = '$userId' else 1 end")
				->whereRaw("case when '$agency' <> 'xx' then T3.Name = '$agency' else 1 end")
				->orderBy('T2.SysRoleId')
				->orderBy('T1.FullName')
				->select(DB::raw('distinct T1.Id as Id'),'T1.username','T3.Name as Agency','T1.Email','T1.FullName',DB::raw("case T2.SysRoleId when '".CONST_ROLE_PROCURINGAGENCYCINET."' then 'CiNET' else 'Etool' end as Type "))
				->paginate(15);
		return View::make('sys.procuringagencyuserlist')
				->with('procuringAgencies',$procuringAgencies)
				->with('etoolCinetUsers',$etoolCinetUsers);
	}

	public function fetchPaUsersJSON(){
		$term = Input::get('term');
		$users = DB::table('sysuser as T1')
						->join('sysuserrolemap as T2','T2.SysUserId','=','T1.Id')
						->whereIn('T2.SysRoleId',array(CONST_ROLE_PROCURINGAGENCYCINET,CONST_ROLE_PROCURINGAGENCYETOOL))
						->whereRaw("(TRIM(T1.FullName) like '%$term%' or T1.username like '%$term%')")
						->get(array(DB::raw('distinct T1.Id as Id'),DB::raw('concat(TRIM(T1.FullName)," (",T1.username,")") as FullName')));
		$usersJson = array();
		foreach($users as $user){
			array_push($usersJson,array('id'=>$user->Id,'value'=>trim($user->FullName)));
		}
		return Response::json($usersJson);
	}

	public function editPAUsersReport(){
		$type = Input::get('type');
		$id = Input::get('id');

		if((bool)$type && (bool)$id){
			$userDetails = DB::table('sysuser as T1')
							->leftJoin('cmnprocuringagency as T2','T2.Id','=','T1.CmnProcuringAgencyId')
							->where('T1.Id',$id)->get(array('T1.FullName','T1.username', 'T2.Name as ProcuringAgency'));
			if($type == "Etool"){
				$reportType = 1;
				$captionText = "Etool Reports";
				$reports = DB::table('sysuserreportmap as T1')
								->rightJoin('sysmenu as T2','T2.Id','=','T1.SysMenuId')
								->where('T1.SysUserId',$id)
								->where('T1.Type',DB::raw("1"))
								->orderBy('T2.ReferenceNo')
								->get(array('T1.Id','T1.PageView','T2.MenuTitle'));
                if(count($reports)==0){
				    $reportMenus = DB::table('sysmenu')->where(DB::raw('coalesce(TypeId,0)'),1)->get(array('Id'));
                    foreach($reportMenus as $menu):
                        $saveArray = array();
                        $saveArray['Id'] = $this->UUID();
                        $saveArray['SysUserId'] = $id;
                        $saveArray['SysMenuId'] = $menu->Id;
                        $saveArray['PageView'] = 0;
                        $saveArray['Type'] = 1;
                        $saveArray['CreatedOn'] = date('Y-m-d G:i:s');
                        $saveArray['CreatedBy'] = "894eba10-885b-11e5-ab33-5cf9dd5fc4f1";

                        DB::table('sysuserreportmap')->insert($saveArray);
                    endforeach;
                    $reports = DB::table('sysuserreportmap as T1')
                        ->rightJoin('sysmenu as T2','T2.Id','=','T1.SysMenuId')
                        ->where('T1.SysUserId',$id)
                        ->where('T1.Type',DB::raw("1"))
                        ->orderBy('T2.ReferenceNo')
                        ->get(array('T1.Id','T1.PageView','T2.MenuTitle'));
                }


			}else{
				$reportType = 2;
				$captionText = "CiNET Reports";
				$reports = DB::table('sysuserreportmap as T1')
						->rightJoin('sysmenu as T2','T2.Id','=','T1.SysMenuId')
						->where('T1.SysUserId',$id)
						->where('T1.Type',DB::raw("2"))
						->orderBy('T2.ReferenceNo')
						->get(array('T1.Id','T1.PageView','T2.MenuTitle'));
				if(count($reports)==0){
				    $reportMenus = DB::table('sysmenu')->where(DB::raw('coalesce(TypeId,0)'),2)->get(array('Id'));
                    foreach($reportMenus as $menu):
                        $saveArray = array();
                        $saveArray['Id'] = $this->UUID();
                        $saveArray['SysUserId'] = $id;
                        $saveArray['SysMenuId'] = $menu->Id;
                        $saveArray['PageView'] = 0;
                        $saveArray['Type'] = 2;
                        $saveArray['CreatedOn'] = date('Y-m-d G:i:s');
                        $saveArray['CreatedBy'] = "894eba10-885b-11e5-ab33-5cf9dd5fc4f1";

                        DB::table('sysuserreportmap')->insert($saveArray);
                    endforeach;
                    $reports = DB::table('sysuserreportmap as T1')
                        ->rightJoin('sysmenu as T2','T2.Id','=','T1.SysMenuId')
                        ->where('T1.SysUserId',$id)
                        ->where('T1.Type',DB::raw("2"))
                        ->orderBy('T2.ReferenceNo')
                        ->get(array('T1.Id','T1.PageView','T2.MenuTitle'));
                }
			}
		}else{
			App::abort('404');
		}

		return View::make('sys.pausersreportmap')
				->with('reportType',$reportType)
				->with('userDetails',$userDetails)
				->with('captionText',$captionText)
				->with('reports',$reports);

	}

	public function postSaveUserReportMap(){
		$inputs = Input::get("systemuserreportmap");
		foreach($inputs as $key=>$value):
			$id = $value['Id'];
			$pageView = $value['PageView'];
			DB::table('sysuserreportmap')->where('Id',$id)->update(array('PageView'=>$pageView,'EditedOn'=>date('Y-m-d G:i:s'),'EditedBy'=>Auth::user()->Id));
		endforeach;
		return Redirect::to('sys/procuringagencyreportmap')->with('savedsuccessmessage','Record has been updated');
	}

	public function getDelete($id){
		DB::table('sysrole')->where('Id',$id)->delete();
		return Redirect::to('sys/actionsrole')->with('successmessage','Role deleted');
	}
}