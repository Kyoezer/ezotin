<?php
class NewsAndNotice extends SystemController{
	public function index($messageId=NULL){
		if((bool)$messageId!=NULL){
			$messages=SysNewsAndNotificationModel::where('Id',$messageId)->get();
		}else{
			$messages= array(new SysNewsAndNotificationModel());
		}
		return View::make('sys.newsandnotice')
					->with('messages',$messages);
	}
	public function editList(){
		$parameters=array();
		$messageFor=Input::get('MessageFor');
		$displayIn=Input::get('DisplayIn');
		$messageDateFrom=Input::get('MessageDateFrom');
		$messageDateTo=Input::get('MessageDateTo');
		if(!empty($limit)){
			$limit=Input::get('Limit');
		}else{
			$limit=20;
		}
		$query="select Id,MessageFor,DisplayIn,Date,Message from sysnewsandnotification where 1=1";
		if((bool)$messageFor!=NULL || (bool)$displayIn!=NULL || (bool)$messageDateFrom!=NULL || (bool)$messageDateTo!=NULL){
			if((bool)$messageFor!=NULL){
				$query.=" and MessageFor=?";
				array_push($parameters,$messageFor);
			}
			if((bool)$messageDateFrom!=NULL){
				$messageDateFrom=$this->convertDate($messageDateFrom);
				$query.=" and Date>=?";
	            array_push($parameters,$messageDateFrom);
			}
			if((bool)$messageDateTo!=NULL){
				$messageDateTo=$this->convertDate($messageDateTo);
				$query.=" and Date<=?";
	            array_push($parameters,$messageDateTo);
			}
			if((bool)$displayIn!=NULL){
				$query.=" and DisplayIn=?";
				array_push($parameters,$displayIn);
			}
		}
		$messages=DB::select($query.' order by MessageFor,DisplayIn,Date limit '.$limit,$parameters);
		return View::make('sys.newsandnoticeeditlist')
					->with('messageFor',$messageFor)
					->with('messageDateFrom',$messageDateFrom)
					->with('messageDateTo',$messageDateTo)
					->with('displayIn',$displayIn)
					->with('limit',$limit)
					->with('messages',$messages);
	}
	public function save(){
		$postedValues=Input::all();
		$date=$this->convertDate(Input::get('Date'));
		$postedValues['Date']=$date;
		$validation = new SysNewsAndNotificationModel;
		if(!($validation->validate($postedValues))){
		    $errors = $validation->errors();
		    return Redirect::to('sys/addnewsnotice')->withErrors($errors)->withInput();
		}
		if(empty($postedValues["Id"])){
			SysNewsAndNotificationModel::create($postedValues);
			return Redirect::to('sys/addnewsnotice')->with('savedsuccessmessage','Record sucessfully added.');
		}else{
			$instance=SysNewsAndNotificationModel::find($postedValues['Id']);
			$instance->fill($postedValues);
			$instance->update();
			return Redirect::to('sys/addnewsnotice')->with('savedsuccessmessage','Record sucessfully Update.');
		}
	}
}