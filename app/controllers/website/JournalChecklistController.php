<?php

class JournalChecklistController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $groupList = DB::select("SELECT a.Id,a.`Title` FROM `webjournalchecklistgroup` a ORDER BY a.Id asc");
        return View::make('website.journalreviewerchecklist')
        ->with('groupList',$groupList);
    }
    public function editgroupchecklist($Id)
    {
        $groupList = DB::select("SELECT a.Id,a.`Title` FROM `webjournalchecklistgroup` a Where a.Id=".$Id);
        return View::make('website.journaleditgroupchecklist')
        ->with('groupList',$groupList);
    }
    public function uploadeditgroupchecklist()
    {  
        $Id = Input::get('id');
        $title = Input::get('title');

        $update = JournalChecklistGroupModel::find($Id);
        $update->Title=$title;
        $update->save();
        
        // DB::table('webjournalchecklistgroup')->where('Id',$Id)->update(array('Title'=>$Title));
	 	return Redirect::to('web/reviewerchecklist');
        
    }
    public function addgroupchecklist()
    {
        return View::make('website.journaladdgroupchecklist');
    }
    public function journaladdgroupchecklistsave()
    {
        $user=new JournalChecklistGroupModel();
        $user->Title=Input::get('Title');
        $user->save();

		return Redirect::to('web/reviewerchecklist');
    }
    
	public function deletegroupchecklist($Id)
	{
        DB::table('webjournalchecklistgroup')->where('Id',$Id)->delete();
		return Redirect::to('web/reviewerchecklist');
	}
    public function journalreviewermainchecklist()
    {
        $Id = Input::get('Id');
        $ListId = Input::get('Checklistgroup_Id');
        $groupList = DB::select("SELECT a.Id,a.`Title` FROM `webjournalchecklistgroup` a ORDER BY a.Id asc");
        $checkList = DB::select("SELECT a.Id,a.`Name`,b.Title FROM `webjournalchecklist` a LEFT JOIN webjournalchecklistgroup b ON a.Checklistgroup_Id=b.Id ORDER BY a.Id ASC");
        // $groupListId = DB::select("SELECT b.Id,a.`Name`,b.Title FROM `webjournalchecklistgroup` b LEFT JOIN webjournalchecklist a ON a.Checklistgroup_Id = b.Id WHERE Checklistgroup_Id=".$ListId);
        return View::make('website.journalmainchecklist')
        ->with('ListId',$ListId)
        // ->with('groupListId',$groupListId)
        ->with('groupList',$groupList)
        ->with('checkList',$checkList);
    }
    public function checklistview()
    {
        $groupId = Input::get('groupchecklist');
        $groupList = DB::select("SELECT a.Id,a.`Title` FROM `webjournalchecklistgroup` a ORDER BY a.Id asc");
        $checkList = DB::select("SELECT a.Id,a.`Name`,b.Title FROM `webjournalchecklist` a 
        LEFT JOIN webjournalchecklistgroup b ON a.Checklistgroup_Id=b.Id 
        WHERE a.`Checklistgroup_Id`='".$groupId."' ORDER BY a.Id ASC");
        $ListId = Input::get('Checklistgroup_Id');
        return View::make('website.journalmainchecklist')
        ->with('ListId',$ListId)
        ->with('groupList',$groupList)
        ->with('checkList',$checkList);
    }
    public function editchecklist($Id)
    {
        $checkList = DB::select("SELECT a.Id,a.`Name` FROM `webjournalchecklist` a Where a.Id=".$Id);
        return View::make('website.journaleditchecklist')
        ->with('checkList',$checkList);
    }
    public function uploadeditchecklist()
    {  

        $Id = Input::get('id');
        $title = Input::get('title');

        $update = JournalChecklistModel::find($Id);
        $update->Name=$title;
        $update->save();
	 	return Redirect::to('web/journalreviewermainchecklist');
        
    }
    public function addchecklist()
    {
        return View::make('website.journaladdchecklist');
    }
    public function journaladdchecklistsave()
    {
        $user=new JournalChecklistModel();
        $user->Id=$this->UUID();
        $user->Name=Input::get('Name');
        $user->Type=Input::get('Type');
        $user->save();

		return Redirect::to('web/journalreviewermainchecklist');
    }
    
	public function deletechecklist($Id)
	{
        DB::table('webjournalchecklist')->where('Id',$Id)->delete();
		return Redirect::to('web/reviewermainchecklist');
	}
    public function journalreviewersubchecklist()
    {
        $checkList = DB::select("SELECT a.Id,a.`Name`,b.Title FROM `webjournalchecklist` a LEFT JOIN webjournalchecklistgroup b ON a.Checklistgroup_Id=b.Id ORDER BY a.Id ASC");
        $subcheckList = DB::select("SELECT 
                                        a.Id,
                                        a.`Name` AS subChecklist,
                                        b.Title AS groupChekclist,
                                        c.Name AS cheklist 
                                    FROM
                                    webjournalchecklistgroup b
                                        LEFT JOIN webjournalchecklist c 
                                        ON b.`Id`=c.`Checklistgroup_Id` 
                                        LEFT JOIN  `webjournalsubchecklist` a 
                                        ON c.`Id` = a.`Parent_Id` 
                                        ORDER BY  b.`Id`,c.`Id`,a.`Id` ASC");
        return View::make('website.journalsubchecklist')
        ->with('checkList',$checkList)
        ->with('subcheckList',$subcheckList);
    }
    public function editsubchecklist($Id)
    {
        $subcheckList = DB::select("SELECT a.Id,a.`Name` FROM `webjournalsubchecklist` a Where a.Id=".$Id);
        return View::make('website.journaleditsubchecklist')
        ->with('subcheckList',$subcheckList);
    }
    public function uploadeditsubchecklist()
    {  
        $Id = Input::get('id');
        $title = Input::get('title');

        $update = JournalSubChecklistModel::find($Id);
        $update->Name=$title;
        $update->save();
	
	 	return Redirect::to('web/journalreviewersubchecklist');
        
    }
    public function addsubchecklist()
    {
        return View::make('website.journaladdsubchecklist');
    }
    public function journaladdsubchecklistsave()
    {
        $user=new JournalSubChecklistModel();
        $user->Title=Input::get('Name');
        $user->Type=Input::get('Type');
        $user->save();

		return Redirect::to('web/reviewersubchecklist');
    }
    
	public function deletesubchecklist($Id)
	{
        DB::table('webjournalsubchecklist')->where('Id',$Id)->delete();
		return Redirect::to('web/reviewersubchecklist');
	}


}
