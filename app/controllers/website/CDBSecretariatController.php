<?php
class CDBSecretariatController extends BaseController{
	public function cdbSecretariat(){
		$cdbSecretariats = array();
		$directorGeneral = DB::select('select T1.FullName,T1.Image,T1.Email,T1.PhoneNo,T1.ExtensionNo,T1.DisplayOrder,T1.IsDirectorGeneral,T2.DesignationName from webcdbsecretariat T1 join webcdbdesignations T2 on T1.DesignationId=T2.Id where coalesce(T1.IsDirectorGeneral,0) = 1');
		$departments = DB::table('webcdbdepartments as T1')
								->join('webcdbsecretariat as T2','T1.Id','=','T2.DepartmentId')
								->orderBy('T1.DisplayOrder')
								->get(array(DB::raw('distinct T1.Id'),'T1.DepartmentName'));
		$divisions = array();

		foreach($departments as $department):
			$divisions[$department->Id] = DB::table('webcdbdivision')->where('DepartmentId',$department->Id)->orderBy('DisplayOrder')->get(array('Id','DivisionName'));
			foreach($divisions[$department->Id] as $division):
				$cdbSecretariats[$division->Id] = DB::select('select T1.FullName,T1.Image,T1.Email,T1.PhoneNo,T1.ExtensionNo,T1.DisplayOrder,T1.IsDirectorGeneral,T2.DesignationName from webcdbsecretariat T1 join webcdbdesignations T2 on T1.DesignationId=T2.Id where T1.DivisionId = ? and coalesce(T1.IsDirectorGeneral,0) = 0 order by T1.DisplayOrder',array($division->Id));
			endforeach;
			$cdbSecretariats[$department->Id] = DB::select('select T1.FullName,T1.Image,T1.Email,T1.PhoneNo,T1.ExtensionNo,T1.DisplayOrder,T1.IsDirectorGeneral,T2.DesignationName from webcdbsecretariat T1 join webcdbdesignations T2 on T1.DesignationId=T2.Id where T1.DepartmentId = ? and T1.DivisionId is null and coalesce(T1.IsDirectorGeneral,0) = 0 order by T1.DisplayOrder',array($department->Id));
		endforeach;
		return View::make('website.cdbsecretariat')
				->with('directorGeneral',$directorGeneral)
				->with('cdbSecretariats',$cdbSecretariats)
				->with('divisions',$divisions)
				->with('departments',$departments);
	}

	public function addCDBSecretariat($id=null){
		$cdbSecretariat = array(new WebCDBSecretariatModel);
		$cdbdepartments = WebCDBDepartmentsModel::orderBy('DepartmentName','asc')
													->get(array('Id','DepartmentName'));

		$cdbdesignations = WebCDBDesignationModel::orderBy('DesignationName','asc')
													->get(array('Id','DesignationName'));
		$cdbdivisions = DB::table('webcdbdivision as T1')->leftJoin('webcdbdepartments as T2','T2.Id','=','T1.DepartmentId')->orderBy('T1.DisplayOrder')->get(array('T1.Id','T2.Id as DepartmentId','T1.DivisionName as DepartmentName','T2.DepartmentName as Department'));
		if((bool)$id){
			$cdbSecretariat = DB::select("select T1.Id, T1.FullName, T1.Email, T1.Image, T1.PhoneNo, T1.ExtensionNo, T1.IsDirectorGeneral, T1.DivisionId, T1.DesignationId, T1.DepartmentId,T1.DivisionId, T2.DesignationName, T3.DepartmentName, T3.DisplayOrder from webcdbsecretariat T1 left join webcdbdesignations T2 on T1.DesignationId=T2.Id left join webcdbdepartments T3 on T1.DepartmentId=T3.Id where T1.Id = ? order by IsDirectorGeneral desc",array($id));
		}

		return View::make('website.addcdbsecretariat')
						->with('cdbdivisions',$cdbdivisions)
						->with('cdbdepartments',$cdbdepartments)
						->with('cdbdesignations',$cdbdesignations)
						->with('cdbSecretariat',$cdbSecretariat);
	}

	public function addCDBDesignation(){
		$uuid = DB::select("select UUID() as GUID");
		$generatedId = $uuid[0]->GUID;

		$instance = new WebCDBDesignationModel;
		$instance->Id = $generatedId;
		$instance->DesignationName = Input::get('DesignationName');

		$instance->save();

		return Redirect::to('web/addcdbsecretariat');
	}
	public function deleteCDBDesignation($id){
		DB::table('webcdbdesignations')->where('Id',$id)->delete();
		return Redirect::to('web/addcdbsecretariat');
	}

	public function addCDBDDepartment(){
		$uuid = DB::select("select UUID() as GUID");
		$generatedId = $uuid[0]->GUID;

		$lastDisplayOrder = WebCDBDepartmentsModel::orderBy('DisplayOrder','desc')
													->limit(0,1)
													->pluck('DisplayOrder');
		$departmentDisplayOrder = $lastDisplayOrder + 1;

		$instance = new WebCDBDepartmentsModel;
		$instance->Id = $generatedId;
		$instance->DepartmentName = Input::get('DepartmentName');
		$instance->DisplayOrder = $departmentDisplayOrder;

		$instance->save();

		return Redirect::to('web/addcdbsecretariat');
	}
	public function deleteCDBDDepartment($id){
		try{
			DB::table('webcdbdepartments')->where('Id',$id)->delete();
		}catch(Exception $e){
			$message = '';
			if((int)($e->getCode()) == 23000){
				$message = "The department you are trying to delete cannot be deleted because it has divisions and employees under it.";
			}
			return Redirect::to('web/addcdbsecretariat')->with('customerrormessage',$message);
		}

		return Redirect::to('web/addcdbsecretariat');
	}
	public function addCDBDivision(){
		$uuid = DB::select("select UUID() as GUID");
		$generatedId = $uuid[0]->GUID;

		$lastDisplayOrder = WebCDBDivisionModel::orderBy('DisplayOrder','desc')
			->limit(0,1)
			->pluck('DisplayOrder');
		$departmentDisplayOrder = $lastDisplayOrder + 1;

		$instance = new WebCDBDivisionModel;
		$instance->Id = $generatedId;
		$instance->DivisionName = Input::get('DepartmentName');
		$instance->DepartmentId = Input::get('DepartmentId');
		$instance->DisplayOrder = $departmentDisplayOrder;

		$instance->save();

		return Redirect::to('web/addcdbsecretariat');
	}

	public function deleteDBDivision($id){
		DB::table('webcdbdivision')->where('Id',$id)->delete();
		return Redirect::to('web/addcdbsecretariat')->with('savedsuccessmessage','Division and employees under it have been deleted');
	}

	public function addSecretariatDetails(){
		$postedValues=Input::all();
		$validation = new WebCDBSecretariatModel;
		if(!($validation->validate($postedValues))){
		    $errors = $validation->errors();
		    return Redirect::to('web/addcdbsecretariat')->withErrors($errors)->withInput();
		}
		$uuid = DB::select("select UUID() as GUID");
		$generatedId = $uuid[0]->GUID;
		$instance = new WebCDBSecretariatModel;
		$instance->Id = $generatedId;
		$instance->FullName = Input::get('FullName');
		$instance->Email = Input::get('Email');
		$instance->DesignationId = Input::get('DesignationId');
		$instance->DivisionId = Input::get('DivisionId');
		$instance->DepartmentId = Input::get('DepartmentId');
		$instance->PhoneNo = Input::get('PhoneNo');
		$instance->ExtensionNo = Input::get('ExtensionNo');
		$instance->IsDirectorGeneral = Input::get('IsDirectorGeneral');
		if (Input::hasFile('ImageUpload')) {
			$file = Input::file('ImageUpload');
			$fileName = $file->getClientOriginalName();
			$destinationPath = public_path().sprintf("/uploads/cdbsecretariat");
		    $imageDestination = "uploads/cdbsecretariat/".$fileName;
		    $file->move('uploads/cdbsecretariat/', $file->getClientOriginalName());
		    $image = Image::make(sprintf('uploads/cdbsecretariat/%s', $file->getClientOriginalName()))->resize(175,175)->save();
			$instance->Image = $imageDestination;
		}
		$instance->save();
		return Redirect::to('web/addcdbsecretariat');
	}

	public function listOfCDBSecretariat(){
		$cdbdepartments = WebCDBDepartmentsModel::orderBy('DisplayOrder','asc')
													->get(array('Id','DepartmentName'));
		$hasEmployees = DB::select("select distinct DepartmentId from webcdbsecretariat");
		$isDirectorGeneral = DB::select("select T1.Id, T1.FullName, T1.Email, T1.Image, T1.PhoneNo, T1.ExtensionNo, T1.IsDirectorGeneral, T2.DesignationName, T3.DepartmentName, T3.DisplayOrder from webcdbsecretariat T1 left join webcdbdesignations T2 on T1.DesignationId=T2.Id left join webcdbdepartments T3 on T1.DepartmentId=T3.Id where T1.IsDirectorGeneral = ?",array(1));

		$cdbSecretariat = DB::select("select T2.Id, T2.FullName,T4.DivisionName, T2.Email, T2.Image, T2.PhoneNo, T2.ExtensionNo, T2.IsDirectorGeneral, T2.DepartmentId,
						T1.DesignationName, 
						T3.DepartmentName, T3.DisplayOrder 
						from 
							webcdbdesignations T1 
							join webcdbsecretariat T2 on
								T1.Id=T2.DesignationId
							left join webcdbdepartments T3 on 
								T2.DepartmentId=T3.Id left join webcdbdivision T4 on T4.Id = T2.DivisionId
						order by T2.DisplayOrder asc");
		return View::make('website.listofcdbsecretariat')
						->with('cdbdepartments',$cdbdepartments)
						->with('cdbSecretariat',$cdbSecretariat)
						->with('isDirectorGeneral',$isDirectorGeneral)
						->with('hasEmployees',$hasEmployees);
	}

	public function cdbSecretatiatMoveUp($id){
		$displayOrder1 = WebCDBSecretariatModel::where('Id',$id)->pluck('DisplayOrder');

		$displayOrder2=$displayOrder1-1;

		if($displayOrder2 > 0){
			$id2 = WebCDBSecretariatModel::where('DisplayOrder',$displayOrder2)->pluck('Id');

			$temp = $displayOrder2;
			$displayOrder2 = $displayOrder1;
			$displayOrder1 = $temp;

			$update1 = DB::update("update webcdbsecretariat set DisplayOrder = ? where Id = ?",array($displayOrder2,$id));
			$update2 = DB::update("update webcdbsecretariat set DisplayOrder = ? where Id = ?",array($displayOrder1,$id2));
		}

		return Redirect::to('web/listofcdbsecretariat');
	}

	public function cdbSecretatiatMoveDown($id){
		$displayOrder1 = WebCDBSecretariatModel::where('Id',$id)->pluck('DisplayOrder');

		$displayOrder2=$displayOrder1+1;

		$id2 = WebCDBSecretariatModel::where('DisplayOrder',$displayOrder2)->pluck('Id');

		$temp = $displayOrder2;
		$displayOrder2 = $displayOrder1;
		$displayOrder1 = $temp;

		$update1 = DB::update("update webcdbsecretariat set DisplayOrder = ? where Id = ?",array($displayOrder2,$id));
		$update2 = DB::update("update webcdbsecretariat set DisplayOrder = ? where Id = ?",array($displayOrder1,$id2));

		return Redirect::to('web/listofcdbsecretariat');
	}


	public function editCDBSecretariat($id){
		$cdbdepartments = WebCDBDepartmentsModel::orderBy('DepartmentName','asc')
													->get(array('Id','DepartmentName'));

		$cdbdesignations = WebCDBDesignationModel::orderBy('DesignationName','asc')
													->get(array('Id','DesignationName'));

		$cdbSecretariat = DB::select("select T1.Id as CDBSecretariatId, T1.FullName, T1.Email, T1.Image, T1.PhoneNo, T1.ExtensionNo, T1.IsDirectorGeneral, T1.DesignationId, T1.DepartmentId, T2.DesignationName, T3.DepartmentName, T3.DisplayOrder from webcdbsecretariat T1 left join webcdbdesignations T2 on T1.DesignationId=T2.Id left join webcdbdepartments T3 on T1.DepartmentId=T3.Id where T1.Id = ?",array($id));

		return View::make('website.editcdbsecretariat')
						->with('cdbdepartments',$cdbdepartments)
						->with('cdbdesignations',$cdbdesignations)
						->with('cdbSecretariat',$cdbSecretariat);
	}

	public function updateCDBSecretariat(){
		$cdbSecretariatId = Input::get('cdbSecretariatId');

		if(Input::get('FullName') != NULL){
			$fullName = Input::get('FullName');
		}
		else{
			$fullName = Input::get('FullName1');
		}

		if(Input::get('Email') != NULL){
			$email = Input::get('Email');
		}
		else{
			$email = Input::get('Email1');
		}

		if(Input::get('DesignationId') != NULL){
			$designationId = Input::get('DesignationId');
		}
		else{
			$designationId = Input::get('DesignationId1');
		}
		$divisionId = Input::get('DivisionId');
		if(!(bool)$divisionId){
			$divisionId = NULL;
		}

		if(Input::get('DepartmentId') != NULL){
			$departmentId = Input::get('DepartmentId');

		}
		else{
			$departmentId = Input::get('DepartmentId1');
		}

		if(Input::get('PhoneNo') != NULL){
			$phoneNo = Input::get('PhoneNo');
		}
		else{
			$phoneNo = Input::get('PhoneNo1');
		}

		if(Input::get('ExtensionNo') != NULL){
			$extensionNo = Input::get('ExtensionNo');
		}
		else{
			$extensionNo = Input::get('ExtensionNo1');
		}

		$isHeadOfOrg = Input::get('IsDirectorGeneral');

		if (Input::hasFile('ImageUpload')) {
			$file=Input::get('ImageUpload1');
			File::delete($file);

			$file = Input::file('ImageUpload');
			$fileName = $file->getClientOriginalName();
			$destinationPath = public_path().sprintf("/uploads/cdbsecretariat");
			$fileDestination = "uploads/cdbsecretariat/".$fileName;
			$file->move('uploads/cdbsecretariat/', $file->getClientOriginalName());
			$image = Image::make(sprintf('uploads/cdbsecretariat/%s', $file->getClientOriginalName()))->resize(175,175)->save();
		}
		else {
			$fileDestination=Input::get('ImageUpload1');
		}

		$updateCDBSecretariat = DB::update("update webcdbsecretariat set FullName = ?, Email = ?, DesignationId = ?, DepartmentId = ?,DivisionId = ?, PhoneNo = ?, ExtensionNo = ?, Image = ?,IsDirectorGeneral = ? where Id = ?", array($fullName, $email, $designationId, $departmentId,$divisionId, $phoneNo, $extensionNo, $fileDestination,$isHeadOfOrg, $cdbSecretariatId,));

		return Redirect::to('web/listofcdbsecretariat');
	}

	public function deleteCDBSecretariat($id){
		$imageSource=DB::table('webcdbsecretariat')->where('Id',$id)->pluck('Image');
		if($imageSource != "uploads/cdbsecretariat/nophotoavailable.jpg"){
			File::delete($imageSource);
		}

		DB::table('webcdbsecretariat')->where('Id',$id)->delete();

		return Redirect::to('web/listofcdbsecretariat');
	}

}