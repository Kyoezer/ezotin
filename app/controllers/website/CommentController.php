<?php
class CommentController extends WebsiteController {
	public function getViewForums(){
		$forum = DB::table('forum as T1')
					->leftJoin('comments as T2',function($join){
						$join->on('T2.forum_id','=','T1.id')
							 ->on('T2.status','=',DB::raw("'1'"));
					})
					->whereRaw("coalesce(T1.ReferenceNo,0) <> 1")
					->groupBy('T1.id')
					->orderBy('T1.CreatedOn', 'DESC')->get(array(DB::raw('count(T2.id) as CommentCount'),'T1.id','T1.topic','T1.CreatedOn'));
		return View::make('website.forum')
		->with('forum', $forum);
	}
	public function detailforums($id){

		if($id == 7){
			if(!Session::has('ArbitratorLoggedIn')){
				return Redirect::to('web/arbitrationforum')->with('customerrormessage','<strong>ERROR! </strong>Please login first');
			}
		}
		$forum = DB::table('forum')
					->where('id', '=',$id)
					//->leftJoin('comments as T2','T2.forum_id','=','T1.id')
					->get(array('id','topic','CreatedOn','content'));

		$comment = DB::table('forum as T1')
					->leftJoin('comments as T2', 'T2.forum_id', '=', 'T1.id')
					->where('T2.forum_id', $id)
					->where('T2.status', 1)
					->OrderBy('T2.CreatedOn', 'DESC')
					->get(array('T2.comments', 'T2.name', 'T2.CreatedOn'));

		return View::make('website.detailforums')
					->with('forum', $forum)
					->with('comment', $comment);
	}
	public function SaveComment(){
		{
		$postedValues=Input::all();
		$validation = new comments;
		if(!($validation->validate($postedValues))){
		    $errors = $validation->errors();
		    return Redirect::back()->withErrors($errors)->withInput();
		}
		else{
			comments::create($postedValues);
			return Redirect::back()->with('savedsuccessmessage','Your comment has been sent to administrator for approval. It will be displayed if approved.');
			}
	}
	}
}