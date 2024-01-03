<?php
class PhotoGalleryController extends BaseController{
	public function addPhotoAlbum(){
		$album = array(new WebPhotoGalleryAlbumModel);
		$albumList = DB::table('webphotogalleryalbums')->get(array('Id','AlbumName','AlbumDescription','AlbumImage'));
		return View::make('website.editalbum')
				->with('album',$album)
				->with('albumList',$albumList);
	}

	public function saveAlbum(){

	}

	public function photoGallery(){
		$albums = DB::table('webphotogalleryalbums as T1')->distinct('T1.Id')->get(array('T1.Id','T1.AlbumName','T1.AlbumImage'));
		$images = DB::table('webphotogallery')->orderBy('CreatedOn')->whereNotNull('PhotoGalleryAlbumId')->get(array('Id as ImageId','PhotoGalleryAlbumId','ImageSource','ImageThumbSource','ImageName','ImageDescription'));
		return View::make('website.photogallery')
					->with('albums',$albums)
					->with('images',$images);
	}



	public function editPhotoGallery($id=NULL){
		$image = array(new WebPhotoGalleryModel());
		$imageList = DB::select("select T1.Id as ImageId, T1.ImageSource,T2.AlbumName,T1.ImageDescription,T1.ImageName,T1.ImageThumbSource from webphotogallery T1 join webphotogalleryalbums T2 on T1.PhotoGalleryAlbumId = T2.Id order by T1.CreatedOn");
		$albumList = DB::table('webphotogalleryalbums')->get(array('Id','AlbumName'));
		if((bool)$id){
			$image = DB::table('webphotogallery')->where('Id',$id)->get(array('Id','PhotoGalleryAlbumId','ImageName','ImageDescription','ImageSource'));
		}
		return View::make('website.editphotogallery')
					->with('image',$image)
					->with('albumList',$albumList)
					->with('imageList',$imageList);
	}

	public function updateGallery(){
		$object = new WebPhotoGalleryModel;
		if(!$object->validate(Input::all()))
		{
			return Redirect::to('web/editphotogallery')->withErrors($object->errors());
		}

		$inputArray = array();
		$id = Input::get('Id');
		if(Input::hasFile('GalleryImage')) {
			$randomString=str_random(4);
			$fileName = Input::file('GalleryImage')->getClientOriginalName();
			$destinationPath = public_path().sprintf("/uploads/photogallery");
			Input::file('GalleryImage')->move($destinationPath,$randomString.'_'.$fileName);
			$imageDestination = "uploads/photogallery/".$randomString.'_'.$fileName;

			$inputArray['ImageSource'] = $imageDestination;
			$inputArray['ImageThumbSource'] = $imageDestination;
		}
		if(!Input::hasFile('GalleryImage') && empty($id)){
			return Redirect::to('web/editphotogallery')->with('customerrormessage','Something went wrong. Try Again.');
		}
		if(empty($id)){
			$instance = new WebPhotoGalleryModel;
			$instance->ImageSource = $inputArray['ImageSource'];
			$instance->ImageThumbSource = $inputArray['ImageThumbSource'];
			$instance->PhotoGalleryAlbumId = Input::get('PhotoGalleryAlbumId');
			$instance->ImageName= Input::get('ImageName');
			$instance->ImageDescription= Input::get('ImageDescription');
			$instance->save();
		}else{
			$inputArray['PhotoGalleryAlbumId'] = Input::get('PhotoGalleryAlbumId');
			$inputArray['ImageName'] = Input::get('ImageName');
			$inputArray['ImageDescription'] = Input::get('ImageDescription');
			$instance = WebPhotoGalleryModel::find(Input::get('Id'));
			$instance->fill($inputArray);
			$instance->update();
		}
		return Redirect::to('web/editphotogallery')->with('savedsuccessmessage','Photo has been successfully added.');
	}
	public function addNewAlbum(){
		$postedValues = array();
		$id = Input::get('Id');
		$object = new WebPhotoGalleryAlbumModel();
		if(!$object->validate(Input::all())){
			return Redirect::to('web/photoalbum')->withErrors($object->errors());
		}

		if(Input::hasFile('AlbumImage')){
			$randomString=randomString();
			$fileName = Input::file('AlbumImage')->getClientOriginalName();
			$destinationPath = public_path().sprintf("/uploads/photoalbum");
			Input::file('AlbumImage')->move($destinationPath,$randomString.'_'.$fileName);
			$imageDestination = "uploads/photoalbum/".$randomString.'_'.$fileName;
		}

		if(empty($id)){
			$instance = new WebPhotoGalleryAlbumModel;
			$instance->Id = $this->UUID();
			$instance->AlbumName = Input::get('AlbumName');
			$instance->AlbumImage = $imageDestination;
			$instance->save();
			$message = 'Record has been saved';
		}else{
			$instance = WebPhotoGalleryAlbumModel::find(Input::get('Id'));
			$postedValues['AlbumName'] = Input::get('AlbumName');
			if(Input::hasFile('AlbumImage')) {
				$postedValues['AlbumImage'] = $imageDestination;
			}
			$instance->fill($postedValues);
			$instance->update();
			$message = 'Record has been updated';
		}

		return Redirect::to('web/photoalbum')->with('savedsuccessmessage',$message);
	}
	public function deletePhoto($id){
		$imageSource=DB::table('webphotogallery')->where('Id',$id)->pluck('ImageSource');
		File::delete($imageSource);

		DB::table('webphotogallery')->where('Id',$id)->delete();

		return Redirect::to('web/editphotogallery');
	}

	public function photoAlbum($id=null){
		$album = array(new WebPhotoGalleryAlbumModel());
		if((bool)$id){
			$album = DB::table('webphotogalleryalbums')->where('Id',$id)->get(array('Id','AlbumName','AlbumImage'));
		}
		$saved = DB::table('webphotogalleryalbums')->get(array('Id','AlbumName','AlbumImage'));
		return View::make('website.editphotoalbum')
				->with('album',$album)
				->with('saved',$saved);
	}

	public function deleteAlbum($id){
		$imageSource=DB::table('webphotogalleryalbums')->where('Id',$id)->pluck('AlbumImage');
		File::delete($imageSource);

		DB::table('webphotogalleryalbums')->where('Id',$id)->delete();

		return Redirect::to('web/photoalbum');
	}

}