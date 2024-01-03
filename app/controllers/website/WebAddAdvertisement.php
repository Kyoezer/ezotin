<?php
class WebAddAdvertisement extends BaseController{
	public function addAdvertisements($id = NULL){
		$advertisement = array(new WebAdvertisementsModel);
		$attachments = array();
		if((bool)$id){
			$advertisement = DB::table('webadvertisements')->where('Id',$id)->get(array('Id','Title','Content','ShowInMarquee','Image'));
			$attachments = DB::table('webadvertisementattachment')->where('WebAdvertisementId',$id)->get(array('Id','AttachmentName','AttachmentPath'));
		}
		return View::make('website.addadvertisements')
				->with('attachments',$attachments)
				->with('advertisement',$advertisement);
	}
	public function allAdvertisements(){
		$advertisements = DB::table('webadvertisements')->orderBy('CreatedOn','desc')->get(array('Id','Title','Content','CreatedOn'));
		return View::make('website.listofadvertisements')
					->with('advertisements',$advertisements);
	}
	public function addAdvertisementDetails(){
		if(Input::has('Id')){
			$instance = WebAdvertisementsModel::find(Input::get('Id'));
			$postedValues['Id'] = Input::get('Id');
			$postedValues['Title'] = Input::get('Title');
			$postedValues['Content']= Input::get('Content');
			$postedValues['ShowInMarquee']= Input::get('ShowInMarquee');
			if (Input::hasFile('ImageUpload')) {
				$fileName = randomString().'_'.Input::file('ImageUpload')->getClientOriginalName();
				$destinationPath = public_path().sprintf("/uploads");
				Input::file('ImageUpload')->move($destinationPath, $fileName);
				$imageDestination = "uploads/".$fileName;
				$postedValues['Image'] = $imageDestination;

				$oldFile = DB::table('webadvertisements')->where('Id',Input::get('Id'))->pluck('Image');
				if((bool)$oldFile){
					File::delete($oldFile);
				}
			}
			if (Input::hasFile('FileUpload')) {
				$fileCount = 0;
				foreach(Input::file('FileUpload') as $key=>$value){
					$attachmentName = randomString().'_'.$value->getClientOriginalName();
					$attachmentDestinationName = public_path().sprintf("/uploads");
					$value->move($attachmentDestinationName, $attachmentName);
					$fileDestination = "uploads/".$attachmentName;
					$fileValues['AttachmentName'] = Input::get('AttachmentName')[$fileCount];
					$fileValues['AttachmentPath'] = $fileDestination;
					$fileValues['Id'] = $this->UUID();
					$fileValues['WebAdvertisementId'] = Input::get('Id');
					Webadvertisementattachment::create($fileValues);
					$fileCount++;
				}
			}
			$instance->fill($postedValues);
			$instance->update();
			$append = "updated";
		}else{
			$uuid = DB::select("select UUID() as GUID");
			$generatedId = $uuid[0]->GUID;
			$instance = new WebAdvertisementsModel;
			$instance->Id = $generatedId;
			$instance->Title = Input::get('Title');
			$instance->Content = Input::get('Content');
			$instance->ShowInMarquee = Input::get('ShowInMarquee');
			if (Input::hasFile('ImageUpload')) {
				$fileName = randomString().'_'.Input::file('ImageUpload')->getClientOriginalName();
				$destinationPath = public_path().sprintf("/uploads");
				Input::file('ImageUpload')->move($destinationPath, $fileName);
				$imageDestination = "uploads/".$fileName;
				$instance->Image = $imageDestination;
			}

			$displayOrderQuery = DB::table('webadvertisements')->max('DisplayOrder');
			if((bool)$displayOrderQuery){
				$instance->DisplayOrder = $displayOrderQuery+1;
			}else{
				$instance->DisplayOrder = 1;
			}
			$instance->save();
			if (Input::hasFile('FileUpload')) {
				$fileCount = 0;
				foreach(Input::file('FileUpload') as $key=>$value){
					$attachmentName = randomString().'_'.$value->getClientOriginalName();
					$attachmentDestinationName = public_path().sprintf("/uploads");
					$value->move($attachmentDestinationName, $attachmentName);
					$fileDestination = "uploads/".$attachmentName;
					$fileValues['AttachmentName'] = Input::get('AttachmentName')[$fileCount];
					$fileValues['AttachmentPath'] = $fileDestination;
					$fileValues['Id'] = $this->UUID();
					$fileValues['WebAdvertisementId'] = $generatedId;
					Webadvertisementattachment::create($fileValues);
					$fileCount++;
				}
			}
			$append = 'saved';
		}

		return Redirect::to('web/editadvertisements')->with('savedsuccessmessage','Record has been '.$append);
	}
	public function advertisementDetails($id){
		$advertisementDetails = WebAdvertisementsModel::where('Id',$id)
														->get(array('Id','Title','Content','Image','CreatedOn'));
		$attachments = DB::table('webadvertisementattachment')->where('WebAdvertisementId',$id)->get(array('AttachmentName','AttachmentPath'));
		return View::make('website.advertisementdetails')
					->with('attachments',$attachments)
					->with('advertisementDetails',$advertisementDetails);
	}
	public function advertisementList(){
		$advertisements = DB::table('webadvertisements')
							->orderBy('CreatedOn','DESC')
							->get(array('Id','Title','Content','ShowInMarquee','DisplayOrder','CreatedOn'));
		return View::make('sys.advertisementlist')
					->with('advertisements',$advertisements);
	}
	public function deleteAdvertisement(){
		$id = Input::get('id');
		DB::table('webadvertisements')->where('Id',$id)->delete();
		return 1;
	}
	public function postChangeDisplayOrder(){
		$id = Input::get('pk');
		$displayOrder = Input::get('value');
		DB::table('webadvertisements')->where('Id',$id)->update(array('DisplayOrder'=>$displayOrder));
	}
}