<?php
class WebsiteAddNewCircular extends BaseController{
	public function addNewCircular($id=NULL){
		$circularTypes = DB::select("select Id, ReferenceNo,CircularName from webcirculartype");
		$circular = array(new WebCircularModel());

		if((bool)$id){
			$circular = DB::table('webcircular')->where("Id",$id)->get();
		}
		return View::make('website.addnewcircular')
						->with('circular',$circular)
						->with('circularTypes',$circularTypes);
	}

	public function addNewCircularData(){
		$uuid = DB::select("select UUID() as GUID");
		$generatedId = $uuid[0]->GUID;
		// $instance=new WebCircularModel;
		// $instance->Id = $generatedId;
		$content = Input::get('Content');
//		if($content){
//			return Redirect::to('web/addnewcircular')->with('customerrormessage','Content field is required');
//		}

		if (Input::hasFile('Image_Upload')) {
			$fileName = Input::file('Image_Upload')->getClientOriginalName();
			$destinationPath = public_path().sprintf("/uploads/webcirculars");
			Input::file('Image_Upload')->move($destinationPath, $fileName);
			$imageDestination = "uploads/webcirculars/".$fileName;
			// $instance->ImageUpload = $imageDestination;
			$postedValues['ImageUpload'] = $imageDestination;
		}

		if (Input::hasFile('Attachment')) {
			$fileName = Input::file('Attachment')->getClientOriginalName();
			$fileExtension = File::extension($fileName);
			$destinationPath = public_path().sprintf("/uploads/webcirculars");
			Input::file('Attachment')->move($destinationPath, $fileName.'.'.$fileExtension);
			$fileDestination = "uploads/webcirculars/".$fileName.'.'.$fileExtension;
			// $instance->Attachment = $fileDestination;
			$postedValues['Attachment'] = $fileDestination;
		}
		$postedValues['Title'] = Input::get('Title');
		$postedValues['CircularTypeId'] = Input::get('CircularType');
		$postedValues['Content'] = Input::get('Content');
		$postedValues['Featured']=Input::get('Featured');

		$id = Input::get('Id');
		if(empty($id)){
			$redirect = 'web/addnewcircular';
			$postedValues['Id'] = $generatedId;
			$displayOrderQuery = DB::table('webcircular')->where('CircularTypeId',Input::get('CircularType'))->max('DisplayOrder');
			if((bool)$displayOrderQuery){
				$postedValues['DisplayOrder'] = $displayOrderQuery+1;
			}else{
				$postedValues['DisplayOrder'] = 1;
			}

			WebCircularModel::create($postedValues);
			$append = 'added';
		}else{
			$redirect = 'web/archivecircular';
			$instance = WebCircularModel::find(Input::get('Id'));
			$postedValues['Id'] = Input::get('Id');
			$instance->fill($postedValues);
			$instance->update();
			$append = 'updated';
		}

		return Redirect::to($redirect)->with('savedsuccessmessage','Circular has been successfully '.$append);
	}

	public function listOfNews(){
		$pageTitle="List of News/Events";
		$slNo=1;
		$backRoute = "web/listofcirculars/news";
		$circularDetails=DB::select("select T1.Id,T1.Title,T1.Content,T1.ImageUpload,T1.Attachment,T2.CircularName,T1.CreatedOn from webcircular T1 join webcirculartype T2 on T1.CircularTypeId=T2.Id where T2.ReferenceNo=1 and coalesce(Display,0)=1 order by T1.DisplayOrder DESC");
		return View::make('website.listofcirculars')
						->with('backRoute',$backRoute)
						->with('circularDetails',$circularDetails)
						->with('pageTitle',$pageTitle)
						->with('slNo',$slNo);
		
	}

	public function listOfNotifications(){
		$pageTitle="List of Notifications/Circulars";
		$slNo=1;
		$backRoute = "web/listofcirculars/notifications";
		$circularDetails=DB::select("select T1.Id,T1.Title,T1.Content,T1.ImageUpload,T1.CreatedOn,T1.Attachment,T2.CircularName from webcircular T1 join webcirculartype T2 on T1.CircularTypeId=T2.Id where T2.ReferenceNo=2 order by T1.DisplayOrder DESC");
		return View::make('website.listofcirculars')
						->with('circularDetails',$circularDetails)
						->with('backRoute',$backRoute)
						->with('pageTitle',$pageTitle)
						->with('slNo',$slNo);
	}

	public function circularDetails($id){
		$circularDetails=DB::select("select * from webcircular where Id = ?",array($id));
		return View::make('website.circulardetails')
						->with('circularDetails',$circularDetails);
	}

	public function archiveCircular(){
		$slno=1;
		$circularTypes = DB::select("select * from webcirculartype");
		$parameters = array();
		$circularLists = array();

		$query="select T1.Id, T1.Title, T1.Content, T1.CreatedOn, T2.CircularName, T1.DisplayOrder, T1.Display from webcircular T1 join webcirculartype T2 on T1.CircularTypeId = T2.Id";

		$circularTypeId = Input::get('CircularType');

		if((bool)$circularTypeId){
			$query.=" and T2.Id=?";
			array_push($parameters,$circularTypeId);

			$circularLists=DB::select($query." order by CreatedOn desc",$parameters);
		}

		return View::make('website.archivecirculars')
					->with('circularLists',$circularLists)
					->with('slno',$slno)
					->with('circularTypes',$circularTypes)
					->with('circularTypeId',$circularTypeId);
	}
	public function showHideCircular($id){
		$display = Input::get('display');
		if($display == 1){
			$display = 0;
		}else{
			$display = 1;
		}
		DB::table('webcircular')->where('Id',$id)->update(array('Display'=>$display));
		return Redirect::to('web/archivecircular?CircularType='.Input::get('circulartype'))->with('savedsuccessmessage','Record has been updated');
	}

	public function archiveCircularDetail($id){
		$title=DB::table('webcircular')
					->where('Id',$id)
					->pluck('Title');
		
		$content=DB::table('webcircular')
					->where('Id',$id)
					->pluck('Content');
		
		$circularTypeId=DB::table('webcircular')
					->where('Id',$id)
					->pluck('CircularTypeId');
		
		$imageUpload=DB::table('webcircular')
					->where('Id',$id)
					->pluck('ImageUpload');
		
		$attachment=DB::table('webcircular')
					->where('Id',$id)
					->pluck('Attachment');

		$instance=new WebCircularArchiveModel;

		$instance->Id=$id;
		$instance->Title=$title;
		$instance->CircularTypeId=$circularTypeId;
		$instance->Content=$content;
		$instance->ImageUpload=$imageUpload;
		$instance->Attachment=$attachment;

		$archiveCheck=$instance->save();

		if($archiveCheck){
			DB::table('webcircular')->where('Id',$id)->delete();
		}

		return Redirect::to('web/archivecircular');
	}

	public function postChangeDisplayOrder(){
		$id = Input::get('pk');
		$displayOrder = Input::get('value');
		DB::table('webcircular')->where('Id',$id)->update(array('DisplayOrder'=>$displayOrder));
	}
	public function deleteImage(){
		$id = Input::get('id');
		$table = Input::get('table');
		if($table == 'webcircular'){
			$path = DB::table($table)->where('Id',$id)->pluck('ImageUpload');
			DB::table($table)->where('Id',$id)->update(array('ImageUpload'=>NULL));
		}else{
			$path = DB::table($table)->where('Id',$id)->pluck('Image');
			DB::table($table)->where('Id',$id)->update(array('Image'=>NULL));
		}
		File::delete($path);
	}
	public function deleteFile(){
		$id = Input::get('id');
		$table = Input::get('table');
		if($table == "webadvertisementattachment"){
			$path = DB::table($table)->where('Id',$id)->pluck('AttachmentPath');
			DB::table($table)->where('Id',$id)->delete();
		}else{
			$path = DB::table($table)->where('Id',$id)->pluck('Attachment');
			DB::table($table)->where('Id',$id)->update(array('Attachment'=>NULL));
		}
		File::delete($path);

	}

	public function viewArchives(){
		$slno=1;
		$circularTypes = DB::select("select * from webcirculartype");
		$parameters = array();
		$circularLists = array();

		$query="select T1.Id, T1.Title, T1.Content, T1.CreatedOn, T2.CircularName from webcirculararchive T1 join webcirculartype T2 on T1.CircularTypeId = T2.Id";

		$circularTypeId = Input::get('CircularType');

		if((bool)$circularTypeId){
			$query.=" and T2.Id=?";
			array_push($parameters,$circularTypeId);

			$circularLists=DB::select($query." order by CreatedOn desc",$parameters);
		}
		
		return View::make('website.viewarchives')
					->with('circularLists',$circularLists)
					->with('slno',$slno)
					->with('circularTypes',$circularTypes)
					->with('circularTypeId',$circularTypeId);
	}

	public function viewArchiveDetails($id){
		$circularDetails=DB::select("select * from webcirculararchive where Id = ?",array($id));

		return View::make('website.circulardetails')
						->with('circularDetails',$circularDetails);
	}

}