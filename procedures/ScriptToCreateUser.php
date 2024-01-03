<?php 
	Route::get('createpagencyuseretool',function(){
			set_time_limit(0);
			$updatepassword=DB::select("select PAgency,login_name,name,password from pa_users");
			foreach($updatepassword as $v){
				$pAgencyId=DB::table('cmnprocuringagency')->where('Code',$v->PAgency)->pluck('Id');
                if((bool)$pAgencyId == NULL){
                    $pAgencyId = DB::table('cmnprocuringagency')->where('Name',$v->PAName)->pluck('Id');
                }
				$username=$v->login_name.'@etool.bt';
				$hashedPass=Hash::make($v->password);
                $email = $v->email;
				$fullName=$v->name;
				$cmnProcuringAgencyId=$pAgencyId;
				if((bool)$pAgencyId!=NULL){
					DB::beginTransaction();
					try{
						$uuid=DB::select("select uuid() as Id");
		                $generatedId=$uuid[0]->Id;
						$instanceUser=new User;
						$instanceUser->Id=$generatedId;
						$instanceUser->username=$username;
						$instanceUser->password=$hashedPass;
						$instanceUser->FullName=$fullName;
						$instanceUser->Email=$email;
						$instanceUser->Status=1;
						$instanceUser->CmnProcuringAgencyId=$pAgencyId;
						$instanceUser->save();

						$roleInstance= new RoleUserMapModel;
						$roleInstance->SysUserId=$generatedId;
						$roleInstance->SysRoleId=CONST_ROLE_PROCURINGAGENCYETOOL;
						$roleInstance->save();
					}catch(Exception $e){
						DB::rollback();
		        		throw $e;
					}
					DB::commit();
				}
			}
			echo "Users Created";
	});
?>