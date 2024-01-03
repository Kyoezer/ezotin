<?php
class SpecializedfirmWorkClassificationModel extends BaseModel{
	protected $table="crpspecializedfirmworkclassification";
	protected $fillable=array('Id','CrpSpecializedTradeId','CmnAppliedCategoryId','CmnVerifiedCategoryId','CmnApprovedCategoryId','CreatedBy','EditedBy','CreatedOn','EditedOn');
    protected $rules=array(
        'CmnAppliedCategoryId'=>'required',
    );
    protected $messages=array(
        
    );
    public function scopeSpecializedTradeWorkClassification($query,$reference){
    	return $query->join('cmnspecializedtradecategory as T1','crpspecializedfirmworkclassification.CmnAppliedCategoryId','=','T1.Id')
    				->leftJoin('cmnspecializedtradecategory as T2','crpspecializedfirmworkclassification.CmnVerifiedCategoryId','=','T2.Id')
                    ->leftJoin('cmnspecializedtradecategory as T3','crpspecializedfirmworkclassification.CmnApprovedCategoryId','=','T3.Id')
                    ->where('crpspecializedfirmworkclassification.CrpSpecializedTradeId','=',$reference)
    				->orderBy('T1.Code')
                    ->orderBy('T1.Name');
    }
    public function scopeAppliedCategory($query,$reference){
        return $query->join('cmnspecializedtradecategory as T1','T1.Id','=','crpspecializedfirmworkclassification.CmnAppliedCategoryId')
                     ->where('crpspecializedfirmworkclassification.CrpSpecializedTradeId',$reference)
                     ->orderBy('T1.Code')
                     ->orderBy('T1.Name');
    }
    public function scopeVerifiedCategory($query,$reference){
       return $query->join('cmnspecializedtradecategory as T1','T1.Id','=','crpspecializedfirmworkclassification.CmnVerifiedCategoryId')
                     ->where('crpspecializedfirmworkclassification.CrpSpecializedTradeId',$reference)
                     ->orderBy('T1.Code')
                     ->orderBy('T1.Name');
    }
    public function scopeApprovedCategory($query,$reference){
        return $query->join('cmnspecializedtradecategory as T1','T1.Id','=','crpspecializedfirmworkclassification.CmnApprovedCategoryId')
                     ->where('crpspecializedfirmworkclassification.CrpSpecializedTradeId',$reference)
                     ->orderBy('T1.Code')
                     ->orderBy('T1.Name');
    }
}