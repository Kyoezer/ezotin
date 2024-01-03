<?php
class CBBiddingFormDetailModel extends BaseModel{
	protected $table="cbbiddingformdetail";
	protected $fillable=array('Id','CrpBiddingFormId','CrpContractorFinalId','CrpConsultantFinalId','CrpSpecializedTradeFinalId','CrpCertifiedBuilderFinalId','BidAmount','EvaluatedAmount','CmnWorkExecutionStatusId','CreatedBy','EditedBy','CreatedOn','EditedOn');
    protected $rules=array(
        
    );
    protected $messages=array(
        
    );
   
    public function scopebiddingFormCertifiedBuilderContractBidders($query,$reference){
        return $query->join('crpcertifiedbuilderfinal as T1','cbbiddingformdetail.CrpCertifiedBuilderFinalId','=','T1.Id')
        			->where('cbbiddingformdetail.CrpBiddingFormId',$reference)
        			->orderBy('T1.NameOfFirm');
    }
    
}