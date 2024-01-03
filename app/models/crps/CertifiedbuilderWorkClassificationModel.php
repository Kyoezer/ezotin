<?php
class CertifiedbuilderWorkClassificationModel extends BaseModel{
	protected $table="crpcertifiedbuilderworkclassification";
	protected $fillable=array('Id','CrpCertifiedBuilderId','CmnAppliedCategoryId','CmnVerifiedCategoryId','CmnApprovedCategoryId','CreatedBy','EditedBy','CreatedOn','EditedOn');
    protected $rules=array(
        'CmnAppliedCategoryId'=>'required',
    );
    protected $messages=array(
        
    );
    public function scopeCertifiedBuilderWorkClassification($query,$reference){
    	return $query->join('cmncertifiedbuildercategory as T1','crpcertifiedbuilderworkclassification.CmnAppliedCategoryId','=','T1.Id')
    				->leftJoin('cmncertifiedbuildercategory as T2','crpcertifiedbuilderworkclassification.CmnVerifiedCategoryId','=','T2.Id')
                    ->leftJoin('cmncertifiedbuildercategory as T3','crpcertifiedbuilderworkclassification.CmnApprovedCategoryId','=','T3.Id')
                    ->where('crpcertifiedbuilderworkclassification.CrpCertifiedBuilderId','=',$reference)
    				->orderBy('T1.Code')
                    ->orderBy('T1.Name');
    }
    public function scopeAppliedCategory($query,$reference){
        return $query->join('cmncertifiedbuildercategory as T1','T1.Id','=','crpcertifiedbuilderworkclassification.CmnAppliedCategoryId')
                     ->where('crpcertifiedbuilderworkclassification.CrpCertifiedBuilderId',$reference)
                     ->orderBy('T1.Code')
                     ->orderBy('T1.Name');
    }
    public function scopeVerifiedCategory($query,$reference){
       return $query->join('cmncertifiedbuildercategory as T1','T1.Id','=','crpcertifiedbuilderworkclassification.CmnVerifiedCategoryId')
                     ->where('crpcertifiedbuilderworkclassification.CrpCertifiedBuilderId',$reference)
                     ->orderBy('T1.Code')
                     ->orderBy('T1.Name');
    }
    public function scopeApprovedCategory($query,$reference){
        return $query->join('cmncertifiedbuildercategory as T1','T1.Id','=','crpcertifiedbuilderworkclassification.CmnApprovedCategoryId')
                     ->where('crpcertifiedbuilderworkclassification.CrpCertifiedBuilderId',$reference)
                     ->orderBy('T1.Code')
                     ->orderBy('T1.Name');
    }
}