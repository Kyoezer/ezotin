<?php
class PostFaqWebsite extends WebsiteController {
	public function faq(){
		$faqDetails = DB::select("select * from webfrequentlyaskedquestion order by DisplayOrder");
		return View::make('website.faq')
						->with('faqDetails',$faqDetails);
	}
	public function addFAQs(){
		return View::make('website.addfaqs');
	}
	public function postQuestion(){
		$instance=new FAQModel;
		$instance->Question=Input::get('Question');
		$instance->Answer=Input::get('Answer');
		$instance->save();
		return Redirect::to('web/faqquestionlist')->with('savedsuccessmessage','FAQ successfully added.');
	}
	public function faqQuestionList(){
		$faqQustions = DB::select("select * from webfrequentlyaskedquestion order by displayOrder");
		$slNo=1;
		return View::make('website.faqquestionlist')
						->with('faqQustions',$faqQustions)
						->with('slNo',$slNo);
	}

	public function moveFaqUp($id,$order){
		$previousFAQ = DB::table('webfrequentlyaskedquestion')->where('DisplayOrder','<',$order)->orderBy('DisplayOrder','DESC')->take(1)->get(array('Id','DisplayOrder'));
		if(count($previousFAQ) == 1){
			$previousId = $previousFAQ[0]->Id;
			$previousDisplayOrder = $previousFAQ[0]->DisplayOrder;
			DB::table('webfrequentlyaskedquestion')->where('Id',$previousId)->update(array('DisplayOrder'=>$order));
			if((bool)$previousDisplayOrder){
				DB::table('webfrequentlyaskedquestion')->where('Id',$id)->update(array('DisplayOrder'=>$previousDisplayOrder));
			}
		}
		return Redirect::to('web/faqquestionlist')->with('savedsuccessmessage','Question has been moved up');

	}
	public function moveFaqDown($id,$order){
		$nextFAQ = DB::table('webfrequentlyaskedquestion')->where('DisplayOrder','>',$order)->orderBy('DisplayOrder')->take(1)->get(array('Id','DisplayOrder'));
		if(count($nextFAQ) == 1){
			$nextId = $nextFAQ[0]->Id;
			$nextDisplayOrder = $nextFAQ[0]->DisplayOrder;
			DB::table('webfrequentlyaskedquestion')->where('Id',$nextId)->update(array('DisplayOrder'=>$order));
			if((bool)$nextDisplayOrder){
				DB::table('webfrequentlyaskedquestion')->where('Id',$id)->update(array('DisplayOrder'=>$nextDisplayOrder));
			}
		}
		return Redirect::to('web/faqquestionlist')->with('savedsuccessmessage','Question has been moved down');
	}
	public function deleteQuestion($id){
		DB::table('webfrequentlyaskedquestion')->where('Id',$id)->delete();
		return Redirect::to('web/faqquestionlist');
	}

	public function faqQuestionDetails($id){
		$question=DB::table('webfrequentlyaskedquestion')->where('Id',$id)->pluck('Question');
		$answer=DB::table('webfrequentlyaskedquestion')->where('Id',$id)->pluck('Answer');
		return View::make('website.faqquestiondetails')
						->with('questionReference',$id)
						->with('question',$question)
						->with('answer',$answer);
	}
	public function postFaqAnswer(){
		$questionId=Input::get('QuestionReference');
		$answer=Input::get('Answer');
		$question=Input::get('Question');
		DB::update("update webfrequentlyaskedquestion set Question=?,Answer = ? where Id = ?",array($question,$answer,$questionId));
		return Redirect::to('web/faqquestionlist')->with('savedsuccessmessage','Answer has been successsfully updated.');
	}
}