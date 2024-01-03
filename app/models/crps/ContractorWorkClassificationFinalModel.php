<?php
class ContractorWorkClassificationFinalModel extends BaseModel{
	protected $table="crpcontractorworkclassificationfinal";
	protected $fillable=array('Id','CrpContractorFinalId','CmnProjectCategoryId','CmnAppliedClassificationId','CmnVerifiedClassificationId','CmnApprovedClassificationId','CreatedBy','EditedBy','CreatedOn','EditedOn');
    protected $rules=array(
        
    );
    protected $messages=array(
        
    );
    public function scopeContractorWorkClassification($query,$reference){
    	return $query->join('cmncontractorworkcategory as T1','crpcontractorworkclassificationfinal.CmnProjectCategoryId','=','T1.Id')
    				->join('cmncontractorclassification as T2','crpcontractorworkclassificationfinal.CmnAppliedClassificationId','=','T2.Id')
    				->join('cmncontractorclassification as T3','crpcontractorworkclassificationfinal.CmnVerifiedClassificationId','=','T3.Id')
                    ->join('cmncontractorclassification as T4','crpcontractorworkclassificationfinal.CmnApprovedClassificationId','=','T4.Id')
                    ->where('crpcontractorworkclassificationfinal.CrpContractorFinalId','=',$reference)
    				->orderBy('T1.Code');
    }
}