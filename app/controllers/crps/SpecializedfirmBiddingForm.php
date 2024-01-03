<?php
class SpecializedfirmBiddingForm extends CrpsController{

	public function index(){
		
		$bidId=Input::get('bidreference');		
		$showProcuringAgency=1;//if logged in from cinet we have to hide procuring agency
		$model='all/mbiddingform';
		$redirectUrl=Route::current()->getUri();
		$dzongkhags=DzongkhagModel::dzongkhag()->get(array('Id','NameEn'));
		$procuringAgencies=ProcuringAgencyModel::procuringAgencyHardList()->get(array('Id','Name','Code'));

		$specializedfirmProjectCategories=array(new SpecializedTradeCategoryModel());		
		$specializedfirm=array(new SpecializedfirmFinalModel);
		
		if((bool)$bidId!=NULL){
			$contractWorkDetails=CrpBiddingFormModel::workCompletionDetails($bidId)->get();
			if(Request::path()=="specializedfirm/biddingform"){
				$contractBidders=CrpBiddingFormDetailModel::biddingFormSpecializedfirmContractBidders($bidId)->get(array('crpbiddingformdetail.Id','crpbiddingformdetail.BidAmount','crpbiddingformdetail.EvaluatedAmount','crpbiddingformdetail.CrpSpecializedTradeFinalId','crpbiddingformdetail.CmnWorkExecutionStatusId','T1.SPNo'));
			}
		}else{
			$contractWorkDetails=array(new CrpBiddingFormModel());
			$contractBidders=array(new CrpBiddingFormDetailModel());
		}
		//$type=2 for specializedfirm
		if(Request::path()=="specializedfirm/biddingform"){
			$type=2;
			$specializedfirmProjectCategories=SpecializedTradeCategoryModel::Category()->get(array('Id','Code','Name'));
			
			$specializedfirm=SpecializedfirmFinalModel::SpecializedTradeHardListAll()->where('CmnApplicationRegistrationStatusId',CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED)->get(array('Id','NameOfFirm','SPNo'));
		}else{
			App::abort('404');
		}
		return View::make('crps.specializedfirmcmnbiddingform')
					->with('model',$model)
					->with('redirectUrl',$redirectUrl)
					->with('type',$type)
					->with('showProcuringAgency',$showProcuringAgency)
					->with('dzongkhags',$dzongkhags)
					->with('procuringAgencies',$procuringAgencies)
					->with('specializedfirmProjectCategories',$specializedfirmProjectCategories)
					->with('specializedfirm',$specializedfirm)
					->with('contractWorkDetails',$contractWorkDetails)
					->with('contractBidders',$contractBidders);
	}
	public function save(){
		$postedValues=Input::except('cinetHR','cinetEquipment');
		$postedValues['NitInMediaDate']=$this->convertDate($postedValues['NitInMediaDate']);
		$postedValues['BidSaleClosedDate']=$this->convertDateTime($postedValues['BidSaleClosedDate']);
		$postedValues['BidOpeningDate']=$this->convertDateTime($postedValues['BidOpeningDate']);
		$postedValues['AcceptanceDate']=$this->convertDate($postedValues['AcceptanceDate']);
		$postedValues['ContractSigningDate']=$this->convertDate($postedValues['ContractSigningDate']);
		$postedValues['WorkStartDate']=$this->convertDate($postedValues['WorkStartDate']);
		$postedValues['WorkCompletionDate']=$this->convertDate($postedValues['WorkCompletionDate']);
		$validation = new CrpBiddingFormModel;
		if(!($validation->validate($postedValues))){
		    $errors = $validation->errors();
		    return Redirect::back()->withInput()->withErrors($errors);
		}
        DB::beginTransaction();
		try{
			if(empty($postedValues['Id'])){
				$uuid=DB::select("select uuid() as Id");
        		$generatedId=$uuid[0]->Id;
				$postedValues["Id"]=$generatedId;
				CrpBiddingFormModel::create($postedValues);
				foreach($postedValues as $key=>$value){
					if(gettype($value)=='array'){
						foreach($value as $key1=>$value1){
							$value1['CrpBiddingFormId']=$generatedId;
							$childTable= new CrpBiddingFormDetailModel($value1);
							$childTable->save();
						}
					}
				}
				$message="Bidding successfully saved.";

			}else{
				$instance=CrpBiddingFormModel::find($postedValues['Id']);
				$instance->fill($postedValues);
				$instance->update();
				DB::table('crpbiddingformdetail')->where('CrpBiddingFormId',$postedValues['Id'])->delete();
				BiddingFormHRModel::where('CrpBiddingFormId',$postedValues['Id'])->delete();
				BiddingFormEquipmentModel::where('CrpBiddingFormId',$postedValues['Id'])->delete();
				foreach($postedValues as $key=>$value){
					if(gettype($value)=='array'){
						foreach($value as $key1=>$value1){
							foreach($value1 as $key2=>$value2){
								$val1=trim($value2);
								if(strlen($val1)==0){
									$value1[$key2]=null;
								}
							}
							if(!isset($value1['Id']) && empty($value1['Id'])){
								$value1['CrpBiddingFormId']=$postedValues['Id'];
								$childTable= new CrpBiddingFormDetailModel($value1);
								$a=$childTable->save();
							}else{
								$childTable1=CrpBiddingFormDetailModel::find($value1['Id']);
								$childTable1->fill($value1);
								$childTable1->update();
							}
						}
					}
				}
				$message="Bidding successfully updated.";

				
			}
			if(Input::has('cinetHR')){
				$hrInputs = Input::get('cinetHR');
				foreach($hrInputs as $x=>$y):
					$y['Id'] = $this->UUID();
					$y['CrpBiddingFormId'] = $postedValues['Id'];
					BiddingFormHRModel::create($y);
				endforeach;
			}
			if(Input::has('cinetEquipment')){
				$eqInputs = Input::get('cinetEquipment');
				foreach($eqInputs as $x=>$y):
					$y['Id'] = $this->UUID();
					$y['CrpBiddingFormId'] = $postedValues['Id'];
					BiddingFormEquipmentModel::create($y);
				endforeach;
			}
		}catch(Exception $e){
			DB::rollback();
        	throw $e;
		}
		DB::commit();
		return Redirect::to($postedValues['RedirectUrl'])->with('savedsuccessmessage',$message);
	}
    public function delete(){
        $id = Input::get('id');
        DB::table('crpbiddingform')->where('Id',$id)->delete();
    }
}