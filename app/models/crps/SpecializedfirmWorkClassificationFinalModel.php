<?php
class SpecializedfirmWorkClassificationFinalModel extends BaseModel{
	protected $table="crpspecializedfirmworkclassificationfinal";
	protected $fillable=array('Id','CrpSpecializedTradeFinalId','CmnAppliedCategoryId','CmnVerifiedCategoryId','CmnApprovedCategoryId','CreatedBy','EditedBy','CreatedOn','EditedOn');
    protected $rules=array(
        'CmnAppliedCategoryId'=>'required',
    );
    protected $messages=array(
        
    );
    public function scopeSpecializedTradeWorkClassification($query,$reference){
    	return $query->join('cmnspecializedtradecategory as T1','crpspecializedfirmworkclassificationfinal.CmnAppliedCategoryId','=','T1.Id')
    				->leftJoin('cmnspecializedtradecategory as T2','crpspecializedfirmworkclassificationfinal.CmnVerifiedCategoryId','=','T2.Id')
                    ->leftJoin('cmnspecializedtradecategory as T3','crpspecializedfirmworkclassificationfinal.CmnApprovedCategoryId','=','T3.Id')
                    ->where('crpspecializedfirmworkclassificationfinal.CrpSpecializedTradeFinalId','=',$reference)
    				->orderBy('T1.Code')
                    ->orderBy('T1.Name');
    }
    public function scopeAppliedCategory($query,$reference){
        return $query->join('cmnspecializedtradecategory as T1','T1.Id','=','crpspecializedfirmworkclassificationfinal.CmnAppliedCategoryId')
                     ->where('crpspecializedfirmworkclassificationfinal.CrpSpecializedTradeFinalId',$reference)
                     ->orderBy('T1.Code')
                     ->orderBy('T1.Name');
    }
    public function scopeVerifiedCategory($query,$reference){
       return $query->join('cmnspecializedtradecategory as T1','T1.Id','=','crpspecializedfirmworkclassificationfinal.CmnVerifiedCategoryId')
                     ->where('crpspecializedfirmworkclassificationfinal.CrpSpecializedTradeFinalId',$reference)
                     ->orderBy('T1.Code')
                     ->orderBy('T1.Name');
    }
    public function scopeApprovedCategory($query,$reference){
        return $query->leftJoin('cmnspecializedtradecategory as T1','T1.Id','=','crpspecializedfirmworkclassificationfinal.CmnApprovedCategoryId')
                     ->where('crpspecializedfirmworkclassificationfinal.CrpSpecializedTradeFinalId',$reference)
                     ->orderBy('T1.Code')
                     ->orderBy('T1.Name');
    }
}