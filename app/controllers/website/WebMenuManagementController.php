<?php
class WebMenuManagementController extends BaseController{
	public function manageMainMenuDetails(){
		$uuid = DB::select("select UUID() as GUID");
		$generatedId = $uuid[0]->GUID;
		$displayOrder=WebPostPageModel::orderBy('DisplayOrder','desc')
											->where('ParentId',NULL)
											->limit(1,1)
											->pluck('DisplayOrder');

		$instance=new WebPostPageModel;
		$instance->Id = $generatedId;
		$instance->Title = Input::get('Title');
		$instance->DisplayOrder = $displayOrder+1;

		$instance->save();

		return Redirect::to('web/managesubmenu');
	}

	public function manageMenus(){
		$parentMenuList = WebPostPageModel::where('ParentId',NULL)
											->orderBy('DisplayOrder','asc')

											->get(array('Id','MenuRoute','Title','DisplayOrder','ShowInWebsite'));

		$parentList = WebPostPageModel::where('ParentId',NULL)
										->orderBy('DisplayOrder','asc')
										->whereRaw("coalesce(ShowInWebsite,0)=1")
										->get(array('Id','MenuRoute','Title','DisplayOrder'));

		$footerList = WebPostPageModel::where('ShowInFooter',1)
										->orderBy('FooterDisplayOrder','asc')
										->get(array('Id','MenuRoute','Title','FooterDisplayOrder'));
		$slno=1;
		$slno1 = 1;
		$slno2 = 1;

		$parentId = Input::get('ParentId');
		$subMenuList = array();

		if((bool)$parentId) {
			$subMenuList = "select T1.Id, T1.Title, T1.Content, T1.DisplayOrder, T1.ShowInWebsite from webpostpage T1";
			$parameters = array();

			if ((bool)$parentId) {
				$subMenuList .= " where T1.ParentId = ?";
				array_push($parameters, $parentId);
			}

			$subMenuList = DB::select("$subMenuList order by T1.DisplayOrder asc",$parameters);
		}

		return View::make('website.managemenus')
					->with('parentMenuList',$parentMenuList)
					->with('slno',$slno)
					->with('slno1',$slno1)
					->with('slno2',$slno2)
					->with('parentList',$parentList)
					->with('subMenuList',$subMenuList)
					->with('parentId',$parentId)
					->with('footerList',$footerList);
	}

	public function menuItemMoveUp($id){
		$displayOrder1 = WebPostPageModel::where('Id',$id)->pluck('DisplayOrder');

		$displayOrder2=$displayOrder1-1;

		if($displayOrder2 > 0){
			$id2 = WebPostPageModel::where('DisplayOrder',$displayOrder2)->pluck('Id');

			$temp = $displayOrder2;
			$displayOrder2 = $displayOrder1;
			$displayOrder1 = $temp;

			$update1 = DB::update("update webpostpage set DisplayOrder = ? where Id = ?",array($displayOrder2,$id2));
			$update2 = DB::update("update webpostpage set DisplayOrder = ? where Id = ?",array($displayOrder1,$id));
		}

		return Redirect::to('web/managemenus');
	}

	public function menuItemMoveDown($id){
		$displayOrder1 = WebPostPageModel::where('Id',$id)->pluck('DisplayOrder');

		$displayOrder2=$displayOrder1+1;

		$id2 = WebPostPageModel::where('DisplayOrder',$displayOrder2)->pluck('Id');

		$temp = $displayOrder2;
		$displayOrder2 = $displayOrder1;
		$displayOrder1 = $temp;

		$update1 = DB::update("update webpostpage set DisplayOrder = ? where Id = ?",array($displayOrder2,$id2));
		$update2 = DB::update("update webpostpage set DisplayOrder = ? where Id = ?",array($displayOrder1,$id));

		return Redirect::to('web/managemenus');
	}

	public function mainMenuActivate($id){
		$update1 = DB::update("update webpostpage set ShowInWebsite = ? where Id = ?",array(1,$id));

		return Redirect::to('web/managemenus');
	}

	public function mainMenuDectivate($id){
		$update1 = DB::update("update webpostpage set ShowInWebsite = ? where Id = ?",array(0,$id));

		return Redirect::to('web/managemenus');
	}

	public function menuItemDelete($id){
		DB::table('webmenu')->where('Id',$id)
							->where('ParentId',NULL)
							->delete();

		return Redirect::to('web/managemenusmanagemenus');
	}


	public function manageSubMenu(){
		$parentList = DB::select("select * from webpostpage order by Title asc");

		return View::make('website.managesubmenu')
					->with('parentList',$parentList);
	}

	public function manageSubMenuDetails(){
		$uuid = DB::select("select UUID() as GUID");
		$generatedId = $uuid[0]->GUID;

		$parentId = Input::get('ParentId');
		$displayOrder=WebPostPageModel::where('ParentId',$parentId)
											->orderBy('DisplayOrder','desc')
											->limit(1,1)
											->pluck('DisplayOrder');

		$instance=new WebPostPageModel;
		$instance->Id = $generatedId;
		$instance->Title = Input::get('Title');
		$instance->ParentId = $parentId;
		$instance->DisplayOrder = $displayOrder+1;
		$instance->Content = Input::get('Content');

		if (Input::hasFile('Image_Upload')) {
			$fileName = Input::file('Image_Upload')->getClientOriginalName();
			$destinationPath = public_path().sprintf("/uploads");
			Input::file('Image_Upload')->move($destinationPath, $fileName);
			$imageDestination = "uploads/".$fileName;
			$instance->ImageUpload = $imageDestination;
		}

		if (Input::hasFile('Attachment')) {
			$fileName = Input::file('Attachment')->getClientOriginalName();
			$destinationPath = public_path().sprintf("/uploads");
			Input::file('Attachment')->move($destinationPath, $fileName);
			$fileDestination = "uploads/".$fileName;
			$instance->Attachment = $fileDestination;
		}

		$instance->save();

		return Redirect::to('web/managesubmenu');
	}

	public function editSubMenu($id){
		$parentList = DB::select("select * from webpostpage where ParentId = ? order by DisplayOrder asc",array(NULL));
		$page_details = DB::select("select * from webpostpage where id = ?", array($id));
		$parentId = DB::table('webpostpage')
						->where('Id',$id)
						->pluck('ParentId');
		return View::make('website.editsubmenu')
						->with('page_details',$page_details)
						->with('parentList',$parentList)
						->with('parentId',$parentId);
	}

	public function updateSubMenuDetails(){
		$Id = Input::get('Id');
		$Title = Input::get('Title');
		$parentId = Input::get('ParentId');
		$Content = Input::get('Content');

		if (Input::hasFile('Image_Upload')) {
			$file=Input::get('imageUpload');
			File::delete($file);

			$fileName=Input::file('Image_Upload')->getClientOriginalName();
			$destinationPath=public_path().sprintf("/uploads");
			Input::file('Image_Upload')->move($destinationPath, $fileName);
			$imageDestination="uploads/".$fileName;
		}
		else {
			$imageDestination=Input::get('imageUpload');
		}

		if (Input::hasFile('Attachment')) {
			$file=Input::get('attachments');
			File::delete($file);

			$fileName=Input::file('Attachment')->getClientOriginalName();
			$destinationPath=public_path().sprintf("/uploads");
			Input::file('Attachment')->move($destinationPath, $fileName);
			$fileDestination="uploads/".$fileName;
		}
		else {
			$fileDestination=Input::get('attachments');
		}

		if($parentId == "---SELECT ONE---"){
			$parentId = Input::get('parentId');
		}

		DB::update("update webpostpage set Title = ?, ParentId = ?,Content = ?, ImageUpload = ?, Attachment = ? where Id = ?",
			array($Title, $parentId, $Content, $imageDestination, $fileDestination, $Id));

		return Redirect::to('web/managemenus?ParentId='.$parentId);
	}

	public function subMenuItemMoveUp($id){
		$displayOrder1 = WebPostPageModel::where('Id',$id)->pluck('DisplayOrder');
		$parentMenuId = WebPostPageModel::where('Id',$id)->pluck('ParentId');

		$displayOrder2=$displayOrder1-1;

		if($displayOrder2 > 0){
			$id2 = WebPostPageModel::where('DisplayOrder',$displayOrder2)->pluck('Id');

			$temp = $displayOrder2;
			$displayOrder2 = $displayOrder1;
			$displayOrder1 = $temp;

			$update1 = DB::update("update webpostpage set DisplayOrder = ? where Id = ?",array($displayOrder2,$id2));
			$update2 = DB::update("update webpostpage set DisplayOrder = ? where Id = ?",array($displayOrder1,$id));
		}

		return Redirect::to('web/managemenus?ParentId='.$parentMenuId);
	}

	public function subMenuItemMoveDown($id){
		$displayOrder1 = WebPostPageModel::where('Id',$id)->pluck('DisplayOrder');

		$displayOrder2=$displayOrder1+1;

		$id2 = WebPostPageModel::where('DisplayOrder',$displayOrder2)->pluck('Id');

		$temp = $displayOrder2;
		$displayOrder2 = $displayOrder1;
		$displayOrder1 = $temp;

		$update1 = DB::update("update webpostpage set DisplayOrder = ? where Id = ?",array($displayOrder2,$id2));
		$update2 = DB::update("update webpostpage set DisplayOrder = ? where Id = ?",array($displayOrder1,$id));

		return Redirect::to('web/managemenus');
	}

	public function subMenuItemDelete($id){
		DB::table('webpostpage')->where('Id',$id)->delete();

		return Redirect::to('web/managemenus');
	}

	public function subMenuActivate($id){
		$update = DB::update("update webpostpage set ShowInWebsite = ? where Id = ?",array(1,$id));

		return Redirect::to('web/managemenus');
	}

	public function subMenuDectivate($id){
		$update = DB::update("update webpostpage set ShowInWebsite = ? where Id = ?",array(0,$id));

		return Redirect::to('web/managemenus');
	}


	public function footerItemMoveUp($id){
		$displayOrder1 = WebPostPageModel::where('Id',$id)->pluck('FooterDisplayOrder');

		$displayOrder2=$displayOrder1-1;

		if($displayOrder2 > 0){
			$id2 = WebPostPageModel::where('FooterDisplayOrder',$displayOrder2)->pluck('Id');

			$temp = $displayOrder2;
			$displayOrder2 = $displayOrder1;
			$displayOrder1 = $temp;

			$update1 = DB::update("update webpostpage set FooterDisplayOrder = ? where Id = ?",array($displayOrder2,$id2));
			$update2 = DB::update("update webpostpage set FooterDisplayOrder = ? where Id = ?",array($displayOrder1,$id));
		}

		return Redirect::to('web/managemenus');
	}

	public function footerItemMoveDown($id){
		$displayOrder1 = WebPostPageModel::where('Id',$id)->pluck('FooterDisplayOrder');

		$displayOrder2=$displayOrder1+1;

		$id2 = WebPostPageModel::where('FooterDisplayOrder',$displayOrder2)->pluck('Id');

		$temp = $displayOrder2;
		$displayOrder2 = $displayOrder1;
		$displayOrder1 = $temp;

		$update1 = DB::update("update webpostpage set FooterDisplayOrder = ? where Id = ?",array($displayOrder2,$id2));
		$update2 = DB::update("update webpostpage set FooterDisplayOrder = ? where Id = ?",array($displayOrder1,$id));

		return Redirect::to('web/managemenus');
	}

	public function showInFooter($id){
		$displayOrder=WebPostPageModel::orderBy('FooterDisplayOrder','desc')
											->where('ShowInFooter',1)
											->limit(1,1)
											->pluck('FooterDisplayOrder');
		$displayOrder=$displayOrder+1;

		$update = DB::update("update webpostpage set ShowInFooter = ?, FooterDisplayOrder = ? where Id = ?",array(1,$displayOrder,$id));

		return Redirect::to('web/managemenus');
	}

	public function removeFromFooter($id){
		$update = DB::update("update webpostpage set ShowInFooter = ? where Id = ?",array(0,$id));

		return Redirect::to('web/managemenus');
	}


	public function pageDetails($id){
		$pagedetails = DB::select("select * from webpostpage where Id = ?", array($id));

		return View::make('website.pagedetails')
					->with('pagedetails',$pagedetails);
	}

}