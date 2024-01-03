<?php
class CertifiedbuilderWorkClassificationFinalModel extends BaseModel{
	protected $table="crpcertifiedbuilderworkclassificationfinal";
	protected $fillable=array('Id','CrpCertifiedBuilderFinalId','CmnAppliedCategoryId','CmnVerifiedCategoryId','CmnApprovedCategoryId','CreatedBy','EditedBy','CreatedOn','EditedOn');
    protected $rules=array(
        'CmnAppliedCategoryId'=>'required',
    );
    protected $messages=array(
        
    );
    public function scopeCertifiedBuilderWorkClassification($query,$reference){
    	return $query->join('cmncertifiedbuildercategory as T1','crpcertifiedbuilderworkclassificationfinal.CmnAppliedCategoryId','=','T1.Id')
    				->leftJoin('cmncertifiedbuildercategory as T2','crpcertifiedbuilderworkclassificationfinal.CmnVerifiedCategoryId','=','T2.Id')
                    ->leftJoin('cmncertifiedbuildercategory as T3','crpcertifiedbuilderworkclassificationfinal.CmnApprovedCategoryId','=','T3.Id')
                    ->where('crpcertifiedbuilderworkclassificationfinal.CrpCertifiedBuilderFinalId','=',$reference)
    				->orderBy('T1.Code')
                    ->orderBy('T1.Name');
    }
    public function scopeAppliedCategory($query,$reference){
        return $query->join('cmncertifiedbuildercategory as T1','T1.Id','=','crpcertifiedbuilderworkclassificationfinal.CmnAppliedCategoryId')
                     ->where('crpcertifiedbuilderworkclassificationfinal.CrpCertifiedBuilderFinalId',$reference)
                     ->orderBy('T1.Code')
                     ->orderBy('T1.Name');
    }
    public function scopeVerifiedCategory($query,$reference){
       return $query->join('cmncertifiedbuildercategory as T1','T1.Id','=','crpcertifiedbuilderworkclassificationfinal.CmnVerifiedCategoryId')
                     ->where('crpcertifiedbuilderworkclassificationfinal.CrpCertifiedBuilderFinalId',$reference)
                     ->orderBy('T1.Code')
                     ->orderBy('T1.Name');
    }
    public function scopeApprovedCategory($query,$reference){
        return $query->leftJoin('cmncertifiedbuildercategory as T1','T1.Id','=','crpcertifiedbuilderworkclassificationfinal.CmnApprovedCategoryId')
                     ->where('crpcertifiedbuilderworkclassificationfinal.CrpCertifiedBuilderFinalId',$reference)
                     ->orderBy('T1.Code')
                     ->orderBy('T1.Name');
    }
}