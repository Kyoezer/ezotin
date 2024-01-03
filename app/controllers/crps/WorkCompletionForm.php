<?php
class WorkCompletionForm extends CrpsController{
	public function listOfWorks(){
		return View::make('crps.cmnlistofworks');	
	}
	public function index(){
		return View::make('crps.workcompletionform');
	}
	public function save(){
		$postedValues=Input::all();
		$currentStatus=Input::get('CmnWorkExecutionStatusId');
		if($currentStatus==CONST_CMN_WORKEXECUTIONSTATUS_COMPLETED){
			$postedValues['CommencementDateOffcial']=$this->convertDate($postedValues['CommencementDateOffcial']);
			$postedValues['CommencementDateFinal']=$this->convertDate($postedValues['CommencementDateFinal']);
			$postedValues['CompletionDateOffcial']=$this->convertDate($postedValues['CompletionDateOffcial']);
			$postedValues['CompletionDateFinal']=$this->convertDate($postedValues['CompletionDateFinal']);
		}
		$workOrder = DB::table('crpbiddingform')->where('Id',$postedValues['Id'])->pluck('WorkOrderNo');
		if(Input::hasFile('APSForm')){
			$apsForm = Input::file('APSForm');
			$attachmentName=$workOrder.'_'.randomString().'_apsform.'.$apsForm->getClientOriginalExtension();
			$destination=public_path().'/uploads/apsform';
			$destinationDB='/uploads/apsform/'.$attachmentName;
			$postedValues["APSFormPath"]=$destinationDB;
			$postedValues["APSFormType"]=".".$apsForm->getClientOriginalExtension();
			$uploadAttachments=$apsForm->move($destination, $attachmentName);
		}


		$instance=CrpBiddingFormModel::find($postedValues['Id']);
		$instance->fill($postedValues);
		$instance->update();
		return Redirect::to($postedValues['RedirectRoute'])->with('savedsuccessmessage','Successfully Updated');
	}
}