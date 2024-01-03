<?php

class TopicController extends BaseController {
	
	public function index(){
		$list = DB::table('forum')
		->OrderBy('id', 'DESC')
		->get(array('topic','id', 'content'));
		return View::make('forum.topicindex')
					->with('list',$list);
	}
	public function ViewForum($id){
		$data = DB::table('forum')
			->where('id', $id)
			->get(array('topic','id', 'content'));

		$comment = DB::table('comments as T1')
			->Join('forum as T2', 'T2.id', '=', 'T1.forum_id')
			->where('T2.id', $id)
			->where('T1.status', 0)
			->get(array('comments','T1.id','T1.status','T1.name','T1.CreatedOn'));


		return View::make('forum.view')
				->with('data', $data)
				->with('comment', $comment);
	}
	public function Save(){
		$id = Input::get('id');
		$postedValues=Input::all();
		$validation = new forum;
		if(!($validation->validate($postedValues))){
		    $errors = $validation->errors();
		    return Redirect::to('topic')->withErrors($errors)->withInput();
		}
		if(empty($id)){
			forum::create($postedValues);
			return Redirect::to('forum/topic')->with('savedsuccessmessage','Forum has been successfully added');
		}else{
			$forum = forum::find($id);
	        $topic = Input::get('topic');
	        $content = Input::get('content');
	        $id = Input::get('id');
	        DB::table('forum')
            ->where('id', $id)
            ->update(['topic' => $topic, 'content'=>$content]);
			return Redirect::to('forum/topic')->with('savedsuccessmessage','Forum has been successfully updated');
		}
	}
	public function Delete($id){
		DB::table('forum')->where('id',$id)->delete();
		return Redirect::to('forum/topic')->with('savedsuccessmessage', 'Delete successfully');	
	}
	public function Edit($id){
		$data = DB::table('forum')->where('id', $id)->get(array('id','topic','content'));
		return View::make('forum.edit-topic')
					->with('data',$data);
	}
	public function Approve($id){
		DB::table('comments')->where('id', $id)
			->update(['status' => '1']);
			return Redirect::back()->with('savedsuccessmessage','Comment has Approved');
	}
	public function Disapprove($id){
		DB::table('comments')->where('id', $id)
			->update(['status' => '2']);
			return Redirect::back()->with('savedsuccessmessage','Comment has Disapproved');
	}
}
