<?php
class CertifiedbuilderBiddingForm extends CrpsController{

	public function index(){

		$bidId=Input::get('bidreference');		
		$showProcuringAgency=1;//if logged in from cinet we have to hide procuring agency
		$model='all/certifiedbiddingform';
		$redirectUrl=Route::current()->getUri();
		$dzongkhags=DzongkhagModel::dzongkhag()->get(array('Id','NameEn'));
		$designations = CmnListItemModel::designation()->get(array('Id','Name'));
		$qualifications = CmnListItemModel::qualification()->get(array('Id','Name'));
		$procuringAgencies=ProcuringAgencyModel::procuringAgencyHardList()->get(array('Id','Name','Code'));
		$certifiedbuilder=array(new CertifiedbuilderFinalModel);
		$bidEquipments = array(new CBBiddingFormEquipmentModel());
		$equipments = CmnEquipmentModel::equipment()->get(array('Id','Name','IsRegistered','VehicleType'));
		$bidHRs = array(new CBBiddingFormHRModel());
		
		if((bool)$bidId!=NULL){
		    $contractWorkDetails=CBBiddingformModel::workCompletionDetails($bidId)->get();
		
			if(Request::path()=="certifiedbuilder/biddingform"){
				$contractBidders=CBBiddingFormDetailModel::biddingFormCertifiedBuilderContractBidders($bidId)->get(array('cbbiddingformdetail.Id','cbbiddingformdetail.BidAmount','cbbiddingformdetail.EvaluatedAmount','cbbiddingformdetail.CrpCertifiedBuilderId','cbbiddingformdetail.CmnWorkExecutionStatusId','T1.CDBNo','T1.NameOfFirm'));
				
				$bidEquipments = CBBiddingFormEquipmentModel::where('CrpBiddingFormId',$bidId)->get(array('Id','CrpBiddingFormId','CmnEquipmentId','RegistrationNo','OwnedOrHired'));
				if(count($bidEquipments)==0){
					$bidEquipments = array(new CBBiddingFormEquipmentModel());
				}
				$bidHRs = CBBiddingFormHRModel::where('CrpBiddingFormId',$bidId)->get(array('Id','CrpBiddingFormId','CIDNo','Name','CmnDesignationId','CmnQualificationId'));
				if(count($bidHRs)==0){
					$bidHRs = array(new CBBiddingFormHRModel());
				}
			}
		}else{
			$contractWorkDetails=array(new CBBiddingformModel());
			$contractBidders=array(new CertifiedbuilderFinalModel());
		}
		
		//$type=2 for certifiedbuilder
		if(Request::path()=="certifiedbuilder/biddingform"){
			$type=2;	
			$certifiedbuilder=CertifiedbuilderFinalModel::CertifiedBuilderHardListAll()->where('CmnApplicationRegistrationStatusId',CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED)->get(array('Id','NameOfFirm','CDBNo'));
		}else{
			App::abort('404');
		}
		
		return View::make('crps.certifiedbuildercmnbiddingform')
		            ->with('isCb',true)
					->with('bidEquipments',$bidEquipments)
					->with('bidHRs',$bidHRs)
					->with('model',$model)
					->with('redirectUrl',$redirectUrl)
					->with('type',$type)
					->with('bidId',$bidId)
					->with('designations',$designations)
					->with('qualifications',$qualifications)
					->with('equipments',$equipments)
					->with('showProcuringAgency',$showProcuringAgency)
					->with('dzongkhags',$dzongkhags)
					->with('procuringAgencies',$procuringAgencies)
					->with('certifiedbuilder',$certifiedbuilder)
					->with('contractWorkDetails',$contractWorkDetails)
					->with('contractBidders',$contractBidders);
	}
	public function save(){
		$postedValues=Input::except('cbHR','cbEquipment');
		// $postedValues['NitInMediaDate']=$this->convertDate($postedValues['NitInMediaDate']);
		// $postedValues['BidSaleClosedDate']=$this->convertDateTime($postedValues['BidSaleClosedDate']);
		// $postedValues['BidOpeningDate']=$this->convertDateTime($postedValues['BidOpeningDate']);
		// $postedValues['AcceptanceDate']=$this->convertDate($postedValues['AcceptanceDate']);
		$postedValues['ContractSigningDate']=$this->convertDate($postedValues['ContractSigningDate']);
		$postedValues['WorkStartDate']=$this->convertDate($postedValues['WorkStartDate']);
		$postedValues['WorkCompletionDate']=$this->convertDate($postedValues['WorkCompletionDate']);
		$validation = new CBBiddingformModel;
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
				CBBiddingformModel::create($postedValues);
				foreach($postedValues as $key=>$value){
					if(gettype($value)=='array'){
						foreach($value as $key1=>$value1){
							$value1['CrpBiddingFormId']=$generatedId;
							$childTable= new CBBiddingFormDetailModel($value1);
							$childTable->save();
						}
					}
				}
				$message="Bidding successfully saved.";

			}else{
				$instance=CBBiddingformModel::find($postedValues['Id']);
				$instance->fill($postedValues);
				$instance->update();
				DB::table('cbbiddingformdetail')->where('CrpBiddingFormId',$postedValues['Id'])->delete();
				CBBiddingFormHRModel::where('CrpBiddingFormId',$postedValues['Id'])->delete();
				CBBiddingFormEquipmentModel::where('CrpBiddingFormId',$postedValues['Id'])->delete();
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
								$childTable= new CBBiddingFormDetailModel($value1);
								$a=$childTable->save();
							}else{
								$childTable1=CBBiddingFormDetailModel::find($value1['Id']);
								$childTable1->fill($value1);
								$childTable1->update();
							}
						}
					}
				}
				$message="Bidding successfully updated.";

				
			}
			if(Input::has('cbHR')){
				$hrInputs = Input::get('cbHR');
				foreach($hrInputs as $x=>$y):
					$y['Id'] = $this->UUID();
					$y['CrpBiddingFormId'] = $postedValues['Id'];
					CBBiddingFormHRModel::create($y);
				endforeach;
			}
			if(Input::has('cbEquipment')){
				$eqInputs = Input::get('cbEquipment');
				foreach($eqInputs as $x=>$y):
					$y['Id'] = $this->UUID();
					$y['CrpBiddingFormId'] = $postedValues['Id'];
					CBBiddingFormEquipmentModel::create($y);
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
        DB::table('cbbiddingform')->where('Id',$id)->delete();
    }

}