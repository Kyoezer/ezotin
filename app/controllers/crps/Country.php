<?php
class Country extends CrpsController{
	public function index(){
		return View::make('crps.cmncountry');
	}
	public function save(){
		$postedValues=Input::all();
		$validation = new CountryModel;
		if(!($validation->validate($postedValues))){
		    $errors = $validation->errors();
		    return Redirect::to('master/country')->withErrors($errors)->withInput();
		}else{
			CountryModel::create($postedValues);
			return Redirect::to('master/country')->with('savedsuccessmessage','Country Successfully added');
		}
	}
	public function update(){

	}
	public function search($searchId){
		$redirectUrl=Input::get('redirectUrl');
		$columns=$this->selectSearchColumns($searchId);
		$searchResults=DB::table('cmncountry')->get(array('Id','Code','Name','AlternateName','Nationality'));
		return View::make('crps.cmnsearch')
					->with('title','Country')
					->with('redirectUrl',$redirectUrl)
					->with('columns',$columns)
					->with('searchResults',$searchResults);
	}
}