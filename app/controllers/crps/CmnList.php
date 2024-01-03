<?php
class CmnList extends CrpsController{
	public function index(){
		return View::make('crps.cmnmasterdata');
	}
	public function designation(){
		$designationId=Input::get('sref');
		$cmnMasterItems=CmnListItemModel::designation()->get(array('Id','Code','Name'));
		if((bool)$designationId==NULL || empty($designationId)){
			$cmnItems=array(new CmnListItemModel());
		}else{
			$cmnItems=CmnListItemModel::designation()->where('Id',$designationId)->get(array('Id','Code','Name'));
		}
		return View::make('crps.cmnmasterdata')
					->with('title','Designation')
					->with('cmnItems',$cmnItems)
					->with('redirectRoute',Request::path())
					->with('cmnMasterItems',$cmnMasterItems)
					->with('listId',CONST_CMN_REFERENCE_DESIGNATION);
	}
	public function trainingModule(){
		$moduleId=Input::get('sref');
		$cmnMasterItems=CmnListItemModel::trainingModule()->get(array('Id','Code','Name'));
		if((bool)$moduleId==NULL || empty($moduleId)){
			$cmnItems=array(new CmnListItemModel());
		}else{
			$cmnItems=CmnListItemModel::trainingModule()->where('Id',$moduleId)->get(array('Id','Code','Name'));
		}
		return View::make('crps.cmnmasterdata')
			->with('title','Training Module')
			->with('cmnItems',$cmnItems)
			->with('redirectRoute',Request::path())
			->with('cmnMasterItems',$cmnMasterItems)
			->with('listId',CONST_CMN_REFERENCE_TRAININGMODULE);
	}
	public function qualification(){
		$cmnMasterItems=CmnListItemModel::qualification()->get(array('Id','Code','Name'));
		$qualificationId=Input::get('sref');
		if((bool)$qualificationId==NULL || empty($qualificationId)){
			$cmnItems=array(new CmnListItemModel());
		}else{
			$cmnItems=CmnListItemModel::qualification()->where('Id',$qualificationId)->get(array('Id','Code','Name'));
		}
		return View::make('crps.cmnmasterdata')
					->with('title','Qualification')
					->with('cmnItems',$cmnItems)
					->with('redirectRoute',Request::path())
					->with('cmnMasterItems',$cmnMasterItems)
					->with('listId',CONST_CMN_REFERENCE_QUALIFICATION);
	}
	public function workCompletionStatus(){
		return View::make('crps.cmnmasterdata')
					->with('title','Work Completion Status')
					->with('redirectRoute',Request::path())
					->with('listId',CONST_CMN_REFERENCE_WORKCOMPLETIONSTATUS);
	}
	public function trade(){
		$cmnMasterItems=CmnListItemModel::trade()->get(array('Id','Code','Name'));
		$tradeId=Input::get('sref');
		if((bool)$tradeId==NULL || empty($tradeId)){
			$cmnItems=array(new CmnListItemModel());
		}else{
			$cmnItems=CmnListItemModel::trade()->where('Id',$tradeId)->get(array('Id','Code','Name'));
		}
		return View::make('crps.cmnmasterdata')
					->with('title','Trade')
					->with('cmnItems',$cmnItems)
					->with('redirectRoute',Request::path())
					->with('cmnMasterItems',$cmnMasterItems)
					->with('listId',CONST_CMN_REFERENCE_TRADE);
	}
	public function ministry(){
		$cmnMasterItems=CmnListItemModel::ministry()->get(array('Id','Code','Name'));
		$ministryId=Input::get('sref');
		if((bool)$ministryId==NULL || empty($ministryId)){
			$cmnItems=array(new CmnListItemModel());
		}else{
			$cmnItems=CmnListItemModel::ministry()->where('Id',$ministryId)->get(array('Id','Code','Name'));
		}
		return View::make('crps.cmnmasterdata')
					->with('title','Ministry')
					->with('cmnItems',$cmnItems)
					->with('redirectRoute',Request::path())
					->with('cmnMasterItems',$cmnMasterItems)
					->with('listId',CONST_CMN_REFERENCE_MINISTRY);
	}
	public function division(){
		$cmnMasterItems=CmnListItemModel::division()->get(array('Id','Code','Name'));
		$divisionId=Input::get('sref');
		if((bool)$divisionId==NULL || empty($divisionId)){
			$cmnItems=array(new CmnListItemModel());
		}else{
			$cmnItems=CmnListItemModel::division()->where('Id',$divisionId)->get(array('Id','Code','Name'));
		}
		return View::make('crps.cmnmasterdata')
			->with('title','Division')
			->with('cmnItems',$cmnItems)
			->with('redirectRoute',Request::path())
			->with('cmnMasterItems',$cmnMasterItems)
			->with('listId',CONST_CMN_REFERENCE_DIVISION);
	}
	public function financialInstitution(){
		$cmnMasterItems=CmnListItemModel::financialInstitution()->get(array('Id','Code','Name'));
		$financialInstitutionId=Input::get('sref');
		if((bool)$financialInstitutionId==NULL || empty($financialInstitutionId)){
			$cmnItems=array(new CmnListItemModel());
		}else{
			$cmnItems=CmnListItemModel::financialInstitution()->where('Id',$financialInstitutionId)->get(array('Id','Code','Name'));
		}
		return View::make('crps.cmnmasterdata')
					->with('title','Financial Institution')
					->with('cmnItems',$cmnItems)
					->with('redirectRoute',Request::path())
					->with('cmnMasterItems',$cmnMasterItems)
					->with('listId',CONST_CMN_REFERENCE_FINANCIALINTITUTION);
	}
	public function salutation(){
		$cmnMasterItems=CmnListItemModel::salutation()->get(array('Id','Code','Name'));
		$salutationId=Input::get('sref');
		if((bool)$salutationId==NULL || empty($salutationId)){
			$cmnItems=array(new CmnListItemModel());
		}else{
			$cmnItems=CmnListItemModel::salutation()->where('Id',$salutationId)->get(array('Id','Code','Name'));
		}
		return View::make('crps.cmnmasterdata')
					->with('title','Salutation')
					->with('cmnItems',$cmnItems)
					->with('redirectRoute',Request::path())
					->with('cmnMasterItems',$cmnMasterItems)
					->with('listId',CONST_CMN_REFERENCE_SALUTATION);
	}
	public function serviceSectorType(){
		return View::make('crps.cmnmasterdata')
					->with('title','Service Sector Type')
					->with('redirectRoute',Route::current()->getUri())
					->with('listId',CONST_CMN_REFERENCE_SERVICESECTORTYPE);
	}
	public function ownershipType(){
		$cmnMasterItems=CmnListItemModel::ownershipType()->get(array('Id','Code','Name'));
		$ownershipTypeId=Input::get('sref');
		if((bool)$ownershipTypeId==NULL || empty($ownershipTypeId)){
			$cmnItems=array(new CmnListItemModel());
		}else{
			$cmnItems=CmnListItemModel::ownershipType()->where('Id',$ownershipTypeId)->get(array('Id','Code','Name'));
		}
		return View::make('crps.cmnmasterdata')
					->with('title','Ownership Type')
					->with('cmnItems',$cmnItems)
					->with('redirectRoute',Request::path())
					->with('cmnMasterItems',$cmnMasterItems)
					->with('listId',CONST_CMN_REFERENCE_OWNERSHIPTYPE);
	}
	public function save(){
		$postedValues=Input::all();
		$id=$postedValues["Id"];
		$validation = new CmnListItemModel;
		if(!($validation->validate($postedValues))){
		    $errors = $validation->errors();
		    return Redirect::to(Input::get('RedirectRoute'))->withErrors($errors)->withInput();
		}else{
			if(empty($id)){
				CmnListItemModel::create($postedValues);
				$message='Data save successfully';
			}else{
				$instance=CmnListItemModel::find($id);
				$instance->fill($postedValues);
				$instance->save();
				$message='Data updated successfully';
			}
			return Redirect::to(Input::get('RedirectRoute'))->with('savedsuccessmessage',$message);
		}
	}
	public function search($listid){
		$title=Input::get('title');
		$redirectUrl=Input::get('redirectUrl');
		$searchResults=CmnListItemModel::where('CmnListId',$listid)->get(array('Id','Code','Name'));
		return View::make('crps.cmnsearchlistitem')
					->with('searchResults',$searchResults)
					->with('title',$title)
					->with('redirectUrl',$redirectUrl);
	}
	public function deleteItem(){
		$id = Input::get('id');
		try{
			CmnListItemModel::where('Id',$id)->delete();
			return 1;
		}catch(Exception $e){
			return 2;
		}

	}
}