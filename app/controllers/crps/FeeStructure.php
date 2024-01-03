<?php

/**
 * Created by PhpStorm.
 * User: swm
 * Date: 5/24/2016
 * Time: 3:57 PM
 */
class FeeStructure extends CrpsController
{
    public function getIndex($id=NULL){
        $feeDetails = array(new crpfeestructure());
        if((bool)$id){
            $feeDetails = DB::table('crpservicefeestructure')->where('Id',$id)->get(array('Id','Name','NewRegistrationFee','FirstRenewalFee','SecondRenewalFee','OwnershipChangeFee','LocationChangeFee','PenaltyLateFee','PenaltyLostFee','RegistrationValidity'));
        }
        $applicantTypes = DB::table('crpservicefeestructure')->orderBy('Name')->whereNotIn("ReferenceNo",array(1,6))->get(array('Id','Name','RegistrationValidity','NewRegistrationFee'));
        return View::make('crps.feestructure')
                    ->with('feeDetails',$feeDetails)
                    ->with('applicantTypes',$applicantTypes);
    }

    public function postSave(){
        $id = Input::get('Id');
        DB::beginTransaction();
        try{
            $instance = crpfeestructure::find($id);
            $instance->fill(Input::all());
            $instance->update();
        }catch (Exception $e){
            DB::rollBack();
            return Redirect::to('master/fees')->with('customerrormessage',$e->getMessage());
        }
        DB::commit();
        return Redirect::to('master/fees')->with('savedsuccessmessage','Fee Structure has been updated!');
    }

    public function getStructure(){
        return View::make('crps.feeStructureIndex');
    }
    public function getContractorFeeStructure(){
        $feeDetails = DB::table("crpservice as T1")->orderBy("T1.ReferenceNo")
                        ->get(array('Id','ReferenceNo','Name','ContractorAmount','ContractorValidity'));
        return View::make("crps.contractorfeestructure")
                ->with('feeDetails',$feeDetails);
    }
    public function getConsultantFeeStructure(){
        $feeDetails = DB::table("crpservice as T1")->orderBy("T1.ReferenceNo")
                        ->get(array('Id','ReferenceNo','Name','ConsultantAmount','ConsultantValidity'));
        return View::make("crps.consultantfeestructure")
                        ->with('feeDetails',$feeDetails);
    }
    public function getArchitectFeeStructure(){
        $feeDetails = DB::table("crpservice as T1")->orderBy("T1.ReferenceNo")
            ->whereIn(DB::raw("coalesce(T1.ReferenceNo,0)"),array(1,2,11))
            ->get(array('Id','ReferenceNo','Name','ArchitectGovtAmount','ArchitectPvtAmount','ArchitectGovtValidity','ArchitectPvtValidity'));
        return View::make("crps.architectfeestructure")
            ->with('feeDetails',$feeDetails);
    }
    public function getEngineerFeeStructure(){
        $feeDetails = DB::table("crpservice as T1")->orderBy("T1.ReferenceNo")
            ->whereIn(DB::raw("coalesce(T1.ReferenceNo,0)"),array(1,2,11))
            ->get(array('Id','ReferenceNo','Name','EngineerGovtAmount','EngineerPvtAmount','EngineerGovtValidity','EngineerPvtValidity'));
        return View::make("crps.engineerfeestructure")
            ->with('feeDetails',$feeDetails);
    }
    public function getSpecializedTradeFeeStructure(){
        $feeDetails = DB::table("crpservice as T1")->orderBy("T1.ReferenceNo")
            ->whereIn(DB::raw("coalesce(T1.ReferenceNo,0)"),array(1,2,11))
            ->get(array('Id','ReferenceNo','Name','SpecializedTradeValidity','SpecializedTradeFirstRenewAmount', 'SpecializedTradeAfterFirstRenewAmount'));
        return View::make("crps.specializedtradefeestructure")
            ->with('feeDetails',$feeDetails);
    }
    public function postSaveAllFees(){
        $postedValues = Input::get("CrpService");
        $redirectUrl = Input::get("RedirectUrl");
        $inputArray = array();
        DB::beginTransaction();
        try{
            foreach($postedValues as $a=>$b):
                foreach($b as $c=>$d):
                    $inputArray[$c] = $d;
                endforeach;
                $instance = CrpService::find($inputArray['Id']);
                $instance->fill($inputArray);
                $instance->update();
                //Update
                $inputArray = array();
            endforeach;
        }catch(Exception $e){
            DB::rollBack();
            return Redirect::to($redirectUrl)->with('customerrormessage',$e->getMessage());
        }
        DB::commit();
        return Redirect::to($redirectUrl)->with('savedsuccessmessage',"Fee structure has been updated");
    }
}