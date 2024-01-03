<?php
class ContractorWorkClassificationModel extends BaseModel{
	protected $table="crpcontractorworkclassification";
	protected $fillable=array('Id','CrpContractorId','CmnProjectCategoryId','CmnAppliedClassificationId','CmnVerifiedClassificationId','CmnApprovedClassificationId','CreatedBy','EditedBy','CreatedOn','EditedOn');
    protected $rules=array(
        
    );
    protected $messages=array(
        
    );
    public function scopeContractorWorkClassification($query,$reference){
    	return $query->join('cmncontractorworkcategory as T1','crpcontractorworkclassification.CmnProjectCategoryId','=','T1.Id')
    				->join('cmncontractorclassification as T2','crpcontractorworkclassification.CmnAppliedClassificationId','=','T2.Id')
    				->leftJoin('cmncontractorclassification as T3','crpcontractorworkclassification.CmnVerifiedClassificationId','=','T3.Id')
                    ->leftJoin('cmncontractorclassification as T4','crpcontractorworkclassification.CmnApprovedClassificationId','=','T4.Id')
                    ->where('crpcontractorworkclassification.CrpContractorId','=',$reference)
    				->orderBy('T1.Code');
    }
    public function scopeContractorAppliedWorkClassification($query,$reference){
        return $query->join('cmncontractorworkcategory as T1','crpcontractorworkclassification.CmnProjectCategoryId','=','T1.Id')
                    ->join('cmncontractorclassification as T2','crpcontractorworkclassification.CmnAppliedClassificationId','=','T2.Id')
                    ->where('crpcontractorworkclassification.CrpContractorId','=',$reference)
                    ->orderBy('T1.Code')
                    ->orderBy('T1.Name');
    }
}