<?php
class BiddingForm extends CrpsController{
	public function index(){
		$bidId=Input::get('bidreference');
		$showProcuringAgency=1;//if logged in from cinet we have to hide procuring agency
		$model='all/mbiddingform';
		$redirectUrl=Route::current()->getUri();
		$dzongkhags=DzongkhagModel::dzongkhag()->get(array('Id','NameEn'));
		$procuringAgencies=ProcuringAgencyModel::procuringAgencyHardList()->get(array('Id','Name','Code'));
		$contractorProjectCategories=array(new ContractorWorkCategoryModel());
		$contractorClass=array(new ContractorClassificationModel());
		$contractors=array(new ContractorFinalModel);
		$consultantServiceCategories=array(new ConsultantServiceCategoryModel());
		$consultantServices=array(new ConsultantServiceModel());
		$consultants=array(new ConsultantFinalModel());
		if((bool)$bidId!=NULL){
			$contractWorkDetails=CrpBiddingFormModel::workCompletionDetails($bidId)->get();
			if(Request::path()=="contractor/biddingform"){
				$contractBidders=CrpBiddingFormDetailModel::biddingFormContractorContractBidders($bidId)->get(array('crpbiddingformdetail.Id','crpbiddingformdetail.BidAmount','crpbiddingformdetail.EvaluatedAmount','crpbiddingformdetail.CrpContractorFinalId','crpbiddingformdetail.CmnWorkExecutionStatusId','T1.CDBNo'));
			}elseif(Request::path()=="consultant/biddingform"){
				$contractBidders=CrpBiddingFormDetailModel::biddingFormConsultantContractBidders($bidId)->get(array('crpbiddingformdetail.Id','crpbiddingformdetail.BidAmount','crpbiddingformdetail.EvaluatedAmount','crpbiddingformdetail.CrpConsultantFinalId','crpbiddingformdetail.CmnWorkExecutionStatusId','T1.CDBNo'));
			}
		}else{
			$contractWorkDetails=array(new CrpBiddingFormModel());
			$contractBidders=array(new CrpBiddingFormDetailModel());
		}
		//$type=0 is for contractor $type=1 is for consultant
		if(Request::path()=="contractor/biddingform"){
			$type=0;
			$contractorProjectCategories=ContractorWorkCategoryModel::contractorProjectCategory()->get(array('Id','Code','Name'));
			$contractorClass=ContractorClassificationModel::classification()->get(array('Id','Name','Code'));
			$contractors=ContractorFinalModel::contractorHardListAll()->where('CmnApplicationRegistrationStatusId',CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED)->get(array('Id','NameOfFirm','CDBNo'));
		}elseif(Request::path()=="consultant/biddingform"){
			$type=1;
			$consultantServiceCategories=ConsultantServiceCategoryModel::category()->get(array('Id','Name'));
			$consultantServices=ConsultantServiceModel::service()->get(array('Id','Code','Name'));
			$consultants=ConsultantFinalModel::consultantHardListAll()->where('CmnApplicationRegistrationStatusId',CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED)->get(array('Id','NameOfFirm','CDBNo'));
		}else{
			App::abort('404');
		}

		$bidEquipments = BiddingFormEquipmentModel::where('CrpBiddingFormId',$bidId)->get(array('Id','CrpBiddingFormId','CmnEquipmentId','RegistrationNo','OwnedOrHired'));
		if(count($bidEquipments)==0){
			$bidEquipments = array(new BiddingFormEquipmentModel());
		}
		$bidHRs = BiddingFormHRModel::where('CrpBiddingFormId',$bidId)->get(array('Id','CrpBiddingFormId','CIDNo','Name','CmnDesignationId','CmnQualificationId'));
		if(count($bidHRs)==0){
			$bidHRs = array(new BiddingFormHRModel());
		}
		$designations = CmnListItemModel::designation()->get(array('Id','Name'));
		$qualifications = CmnListItemModel::qualification()->get(array('Id','Name'));
		$equipments = CmnEquipmentModel::equipment()->get(array('Id','Name','IsRegistered','VehicleType'));

		return View::make('crps.cmnbiddingform')
					->with('model',$model)
					->with('designations',$designations)
					->with('bidEquipments',$bidEquipments)
					->with('qualifications',$qualifications)
					->with('equipments',$equipments)
					->with('bidHRs',$bidHRs)
					->with('redirectUrl',$redirectUrl)
					->with('type',$type)
					->with('showProcuringAgency',$showProcuringAgency)
					->with('dzongkhags',$dzongkhags)
					->with('procuringAgencies',$procuringAgencies)
					->with('contractorProjectCategories',$contractorProjectCategories)
					->with('contractorClass',$contractorClass)
					->with('contractors',$contractors)
					->with('consultantServiceCategories',$consultantServiceCategories)
					->with('consultantServices',$consultantServices)
					->with('consultants',$consultants)
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