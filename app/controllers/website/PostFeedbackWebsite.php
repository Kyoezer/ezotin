<?php
class PostFeedbackWebsite extends WebsiteController {
	public function feedback(){
		return View::make('website.feedback');
	}
	public function postFeedback(){
		$uuid=DB::select("select UUID() as GUID");
		$generatedId=$uuid[0]->GUID;
		$validation = new FeedbackModel;
		if(!($validation->validate(Input::all()))){
		    $errors = $validation->errors();
		    return Redirect::to('web/feedback')->withInput()->withErrors($errors);
		}
		$instance=new FeedbackModel;
		$instance->Id=$generatedId;
		$instance->Name=Input::get('Name');
		$instance->Email=Input::get('Email');
		$instance->Address=Input::get('Address');
		$instance->Content=Input::get('Content');
		$instance->save();
		return Redirect::to('web/feedback')->with('savedsuccessmessage','Your feedback has been successfuly added.');
	}

	public function feedbackList(){
		$feedbacks = DB::select("select * from webfeedback order by CreatedOn desc");
		$slNo=1;
		return View::make('website.feedbacklist')
						->with('feedbacks',$feedbacks)
						->with('slNo',$slNo);
	}

	public function deleteFeedback($id){
		DB::table('webfeedback')->where('Id',$id)->delete();
		return Redirect::to('web/feedbacklist');
	}

	public function feedbackDetails($id){
		$feedback=DB::table('webfeedback')->where('Id',$id)->pluck('Content');
		return View::make('website.feedbackdetails')
						->with('feedbackReference',$id)
						->with('feedback',$feedback);
	}
	public function showFeebackOnWebsite($id){
		$updateFeedback = DB::update("update webfeedback set ShowOnWebsite = ? where Id = ?",array(1,$id));

		return Redirect::to('web/feedbacklist');
	}


}