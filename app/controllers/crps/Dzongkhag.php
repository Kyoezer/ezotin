<?php
class Dzongkhag extends CrpsController{
	public function index(){
		return View::make('crps.cmndzongkhag');
	}
	public function save(){
		$postedValues=Input::all();
		$validation = new DzongkhagModel;
		if(!($validation->validate($postedValues))){
		    $errors = $validation->errors();
		    return Redirect::to('master/dzongkhag')->withErrors($errors)->withInput();
		}else{
			DzongkhagModel::create($postedValues);
			return Redirect::to('master/dzongkhag')->with('savedsuccessmessage','Dzongkhag Successfully added');
		}
	}
	public function update(){

	}
	public function search($searchId){
		$redirectUrl=Input::get('redirectUrl');
		$columns=$this->selectSearchColumns($searchId);
		$searchResults=DB::table('cmndzongkhag')->get(array('Id','Code','NameEn'));
		return View::make('crps.cmnsearch')
					->with('title','Dzongkhag')
					->with('redirectUrl',$redirectUrl)
					->with('columns',$columns)
					->with('searchResults',$searchResults);
	}
}