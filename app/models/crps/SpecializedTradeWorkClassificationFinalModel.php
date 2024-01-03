<?php
class SpecializedTradeWorkClassificationFinalModel extends BaseModel{
	protected $table="crpspecializedtradeworkclassificationfinal";
	protected $fillable=array('Id','CrpSpecializedTradeFinalId','CmnAppliedCategoryId','CmnVerifiedCategoryId','CmnApprovedCategoryId','CreatedBy','EditedBy','CreatedOn','EditedOn');
    protected $rules=array(
        'CmnAppliedCategoryId'=>'required',
    );
    protected $messages=array(
        
    );
    public function scopeSpecializedTradeWorkClassification($query,$reference){
    	return $query->join('cmnspecializedtradecategory as T1','crpspecializedtradeworkclassificationfinal.CmnAppliedCategoryId','=','T1.Id')
    				->leftJoin('cmnspecializedtradecategory as T2','crpspecializedtradeworkclassificationfinal.CmnVerifiedCategoryId','=','T2.Id')
                    ->leftJoin('cmnspecializedtradecategory as T3','crpspecializedtradeworkclassificationfinal.CmnApprovedCategoryId','=','T3.Id')
                    ->where('crpspecializedtradeworkclassificationfinal.CrpSpecializedTradeFinalId','=',$reference)
    				->orderBy('T1.Code')
                    ->orderBy('T1.Name');
    }
    public function scopeAppliedCategory($query,$reference){
        return $query->join('cmnspecializedtradecategory as T1','T1.Id','=','crpspecializedtradeworkclassificationfinal.CmnAppliedCategoryId')
                     ->where('crpspecializedtradeworkclassificationfinal.CrpSpecializedTradeFinalId',$reference)
                     ->orderBy('T1.Code')
                     ->orderBy('T1.Name');
    }
    public function scopeVerifiedCategory($query,$reference){
       return $query->join('cmnspecializedtradecategory as T1','T1.Id','=','crpspecializedtradeworkclassificationfinal.CmnVerifiedCategoryId')
                     ->where('crpspecializedtradeworkclassificationfinal.CrpSpecializedTradeFinalId',$reference)
                     ->orderBy('T1.Code')
                     ->orderBy('T1.Name');
    }
    public function scopeApprovedCategory($query,$reference){
        return $query->leftJoin('cmnspecializedtradecategory as T1','T1.Id','=','crpspecializedtradeworkclassificationfinal.CmnApprovedCategoryId')
                     ->where('crpspecializedtradeworkclassificationfinal.CrpSpecializedTradeFinalId',$reference)
                     ->orderBy('T1.Code')
                     ->orderBy('T1.Name');
    }
}