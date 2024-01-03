<?php
class SpecializedTradeWorkClassificationModel extends BaseModel{
	protected $table="crpspecializedtradeworkclassification";
	protected $fillable=array('Id','CrpSpecializedTradeId','CmnAppliedCategoryId','CmnVerifiedCategoryId','CmnApprovedCategoryId','CreatedBy','EditedBy','CreatedOn','EditedOn');
    protected $rules=array(
        'CmnAppliedCategoryId'=>'required',
    );
    protected $messages=array(
        
    );
    public function scopeSpecializedTradeWorkClassification($query,$reference){
    	return $query->join('cmnspecializedtradecategory as T1','crpspecializedtradeworkclassification.CmnAppliedCategoryId','=','T1.Id')
    				->leftJoin('cmnspecializedtradecategory as T2','crpspecializedtradeworkclassification.CmnVerifiedCategoryId','=','T2.Id')
                    ->leftJoin('cmnspecializedtradecategory as T3','crpspecializedtradeworkclassification.CmnApprovedCategoryId','=','T3.Id')
                    ->where('crpspecializedtradeworkclassification.CrpSpecializedTradeId','=',$reference)
    				->orderBy('T1.Code')
                    ->orderBy('T1.Name');
    }
    public function scopeAppliedCategory($query,$reference){
        return $query->join('cmnspecializedtradecategory as T1','T1.Id','=','crpspecializedtradeworkclassification.CmnAppliedCategoryId')
                     ->where('crpspecializedtradeworkclassification.CrpSpecializedTradeId',$reference)
                     ->orderBy('T1.Code')
                     ->orderBy('T1.Name');
    }
    public function scopeVerifiedCategory($query,$reference){
       return $query->join('cmnspecializedtradecategory as T1','T1.Id','=','crpspecializedtradeworkclassification.CmnVerifiedCategoryId')
                     ->where('crpspecializedtradeworkclassification.CrpSpecializedTradeId',$reference)
                     ->orderBy('T1.Code')
                     ->orderBy('T1.Name');
    }
    public function scopeApprovedCategory($query,$reference){
        return $query->join('cmnspecializedtradecategory as T1','T1.Id','=','crpspecializedtradeworkclassification.CmnApprovedCategoryId')
                     ->where('crpspecializedtradeworkclassification.CrpSpecializedTradeId',$reference)
                     ->orderBy('T1.Code')
                     ->orderBy('T1.Name');
    }
}