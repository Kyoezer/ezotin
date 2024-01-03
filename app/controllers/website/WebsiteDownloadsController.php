<?php
class WebsiteDownloadsController extends BaseController{
	public function addDownloads($id = null){
		$downloadCategories=DB::select("select * from webdownloadcategory order by CategoryName");
		$download = array(new WebDownloadsModel);
		if((bool)$id){
			$download = DB::table('webdownload')->where('Id',$id)->get(array('Id','FileName','CategoryId','Word','PDF','Other'));
		}
		return View::make('website.adddownloads')
						->with('download',$download)
						->with('downloadCategories',$downloadCategories);
	}

	public function addNewCategory(){
		$uuid = DB::select("select UUID() as GUID");
		$generatedId = $uuid[0]->GUID;
		$instance=new WebDownloadCategoryModel;
		$instance->Id = $generatedId;
		$instance->CategoryName = Input::get('CategoryName');
		$instance->save();
		return Redirect::to('web/adddownloads')->with('savedsuccessmessage','Download category has been successfully added.');
	}

	public function addDownloadData(){
		$uuid = DB::select("select UUID() as GUID");
		$generatedId = $uuid[0]->GUID;
		$oldId = Input::get('Id');
		if(!empty($oldId)){
			$instance = WebDownloadsModel::find($oldId);
		}else{
			$instance=new WebDownloadsModel;
			$instance->Id = $generatedId;
		}

		$instance->FileName = Input::get('FileName');
		$instance->CategoryId = Input::get('FileCategory');
		if (Input::hasFile('PDF')) {
			$fileName = Input::file('PDF')->getClientOriginalName();
			$destinationPath = public_path().sprintf("/uploads/downloads");
			Input::file('PDF')->move($destinationPath, $fileName);
			$fileDestination = "uploads/downloads/".$fileName;
			$instance->PDF = $fileDestination;
		}
		if (Input::hasFile('Word')) {
			$fileName = Input::file('Word')->getClientOriginalName();
			$destinationPath = public_path().sprintf("/uploads/downloads");
			Input::file('Word')->move($destinationPath, $fileName);
			$fileDestination = "uploads/downloads/".$fileName;
			$instance->Word = $fileDestination;
		}
		if (Input::hasFile('Others')) {
			$fileName = Input::file('Others')->getClientOriginalName();
			$destinationPath = public_path().sprintf("/uploads/downloads");
			Input::file('Others')->move($destinationPath, $fileName);
			$fileDestination = "uploads/downloads/".$fileName;
			$instance->Other = $fileDestination;
		}
		if(!empty($oldId)){
			$instance->update();
		}else{
			$instance->save();
		}

		return Redirect::to('web/adddownloads')->with('savedsuccessmessage','Document has been successfully added.');
	}

	public function downloads(){
		$downloadDetails = DB::select("select T1.CategoryName as CategoryName,T2.FileName as FileName,T2.Word as Word,T2.PDF as PDF,T2.Other as Other from webdownloadcategory T1 join webdownload T2 on T1.Id = T2.CategoryId order by T1.CategoryName,T2.FileName");
		$slno=1;
		return View::make('website.downloads')
						->with('downloadTitle','All Downloads')
						->with('downloadDetails',$downloadDetails)
						->with('slno',$slno);
	}
	public function optionDownloads($categoryId){
		$slno=1;
		$downloadTitle=DB::table('webdownloadcategory')->where('Id',$categoryId)->pluck('CategoryName');
		$downloadDetails = DB::select("select T1.CategoryName as CategoryName,T2.FileName as FileName,T2.Word as Word,T2.PDF as PDF,T2.Other as Other from webdownloadcategory T1 join webdownload T2 on T1.Id = T2.CategoryId where T1.Id=? order by T2.FileName",array($categoryId));
		return View::make('website.downloads')
						->with('downloadTitle',$downloadTitle)
						->with('downloadDetails',$downloadDetails)
						->with('slno',$slno);
	}
	public function editDownloads(){
		$downloads = DB::table('webdownload as T1')
						->join('webdownloadcategory as T2','T1.CategoryId','=','T2.Id')
						->orderBy('T2.CategoryName')
						->orderBy('T1.FileName')
						->get(array('T1.Id','T2.CategoryName','T1.FileName'));
		return View::make('website.viewdownloads')
					->with('downloads',$downloads);
	}
	public function deletedocument(){
		$id = Input::get('id');
		if((bool)$id){
			$type = Input::get('type');
			DB::table('webdownload')->where("Id",$id)->update(array($type=>NULL));
		}else{
			App::abort('404');
		}
		return Redirect::to('web/adddownloads/'.$id)->with('savedsuccessmessage','Document deleted!');
	}
}