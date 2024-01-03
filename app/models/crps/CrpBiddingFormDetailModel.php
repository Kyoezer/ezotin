<?php
class CrpBiddingFormDetailModel extends BaseModel{
	protected $table="crpbiddingformdetail";
	protected $fillable=array('Id','CrpBiddingFormId','CrpContractorFinalId','CrpConsultantFinalId','CrpSpecializedTradeFinalId','BidAmount','EvaluatedAmount','CmnWorkExecutionStatusId','CreatedBy','EditedBy','CreatedOn','EditedOn');
    protected $rules=array(
        
    );
    protected $messages=array(
        
    );
    public function scopeBiddingFormContractorContractBidders($query,$reference){
        return $query->join('crpcontractorfinal as T1','crpbiddingformdetail.CrpContractorFinalId','=','T1.Id')
        			->where('crpbiddingformdetail.CrpBiddingFormId',$reference)
        			->orderBy('T1.NameOfFirm');
    }
    public function scopeBiddingFormConsultantContractBidders($query,$reference){
        return $query->join('crpconsultantfinal as T1','crpbiddingformdetail.CrpConsultantFinalId','=','T1.Id')
                    ->where('crpbiddingformdetail.CrpBiddingFormId',$reference)
                    ->orderBy('T1.NameOfFirm');
    }
    public function scopeBiddingFormSpecializedfirmContractBidders($query,$reference){
        return $query->join('crpspecializedfirmfinal as T1','crpbiddingformdetail.CrpSpecializedTradeFinalId','=','T1.Id')
        			->where('crpbiddingformdetail.CrpBiddingFormId',$reference)
        			->orderBy('T1.NameOfFirm');
    }
}