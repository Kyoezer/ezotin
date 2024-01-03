<?php
class ConsultantWorkClassificationModel extends BaseModel{
	protected $table="crpconsultantworkclassification";
	protected $fillable=array('Id','CrpConsultantId','CmnServiceCategoryId','CmnAppliedServiceId','CmnVerifiedServiceId','CmnApprovedServiceId','CreatedBy','EditedBy','CreatedOn','EditedOn');
    protected $rules=array(
        
    );
    protected $messages=array(
        
    );
    public function scopeServiceCategory($query,$reference){
        return $query->join('cmnconsultantservicecategory as T1','T1.Id','=','crpconsultantworkclassification.CmnServiceCategoryId')
                    ->where('crpconsultantworkclassification.CrpConsultantId',$reference)
                    ->distinct()
                     ->orderBy('T1.Name');
    }
    public function scopeAppliedService($query,$reference){
        return $query->join('cmnconsultantservice as T1','T1.Id','=','crpconsultantworkclassification.CmnAppliedServiceId')
                     ->where('crpconsultantworkclassification.CrpConsultantId',$reference)
                     ->groupBy('T1.Id')
                     ->orderBy('T1.Code');
    }
    public function scopeVerifiedService($query,$reference){
        return $query->join('cmnconsultantservice as T2','T2.Id','=','crpconsultantworkclassification.CmnVerifiedServiceId')
                     ->where('crpconsultantworkclassification.CrpConsultantId',$reference)
            ->groupBy('T2.Id')
                     ->orderBy('T2.Code');
    }
    public function scopeApprovedService($query,$reference){
        return $query->join('cmnconsultantservice as T2','T2.Id','=','crpconsultantworkclassification.CmnApprovedServiceId')
                     ->where('crpconsultantworkclassification.CrpConsultantId',$reference)
                     ->orderBy('T2.Code');
    }
    public function scopeServices($query,$reference){
        return $query->join('cmnconsultantservicecategory as T1','T1.Id','=','crpconsultantworkclassification.CmnServiceCategoryId')
                    ->join('cmnconsultantservice as T2','T2.Id','=','crpconsultantworkclassification.CmnAppliedServiceId')
                    ->join('cmnconsultantservice as T3','T3.Id','=','crpconsultantworkclassification.CmnVerifiedServiceId')
                    ->join('cmnconsultantservice as T4','T4.Id','=','crpconsultantworkclassification.CmnApprovedServiceId')
                    ->where('crpconsultantworkclassification.CrpConsultantId',$reference)
                    ->groupBy('T1.Name')
                    ->orderBy('T1.Name');
    }
}