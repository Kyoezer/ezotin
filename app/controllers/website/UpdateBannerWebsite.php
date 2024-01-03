<?php
class UpdateBannerWebsite extends WebsiteController {
	public function editBanner(){
		$banner = DB::select("select * from webpagebanner");
		return View::make('website.editbanner')
						->with('banner',$banner);
	}

	public function updateBanner(){
		if (Input::hasFile('bannerImage')) {
			$file = Input::file('bannerImage');
			$fileName = $file->getClientOriginalName();
			$destinationPath = public_path().sprintf("/uploads/webbanners");
		    $file->move('uploads/webbanners/', $file->getClientOriginalName());
		    $imageDestination = "uploads/webbanners/".$fileName;
		    $image = Image::make(sprintf('uploads/webbanners/%s', $file->getClientOriginalName()))->resize(2024,220)->save();
			DB::update("update webpagebanner set ImageSource=? where Id=?", array($imageDestination,'0cac65de-f92c-11e4-b84c-080027dcfac6'));
			return Redirect::to('web/editbanner')->with('savedsuccessmessage','Website Banner has been successfully updated.');
		}else{
			App::abort('500');
		}
	}
}