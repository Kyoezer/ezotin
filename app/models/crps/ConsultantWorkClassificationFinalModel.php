<?php
class ConsultantWorkClassificationFinalModel extends BaseModel{
	protected $table="crpconsultantworkclassificationfinal";
	protected $fillable=array('Id','CrpConsultantFinalId','CmnServiceCategoryId','CmnAppliedServiceId','CmnVerifiedServiceId','CmnApprovedServiceId','CreatedBy','EditedBy','CreatedOn','EditedOn');
    protected $rules=array(
        
    );
    protected $messages=array(
        
    );
    public function scopeServiceCategory($query,$reference){
        return $query->join('cmnconsultantservicecategory as T1','T1.Id','=','crpconsultantworkclassificationfinal.CmnServiceCategoryId')
                    ->where('crpconsultantworkclassificationfinal.CrpConsultantFinalId',$reference)
                    ->distinct()
                     ->orderBy('T1.Name');
    }
    public function scopeAppliedService($query,$reference){
        return $query->join('cmnconsultantservice as T1','T1.Id','=','crpconsultantworkclassificationfinal.CmnAppliedServiceId')
                     ->where('crpconsultantworkclassificationfinal.CrpConsultantFinalId',$reference)
                    ->groupBy('T1.Id')
                    ->orderBy('T1.Code');
    }
    public function scopeVerifiedService($query,$reference){
        return $query->join('cmnconsultantservice as T2','T2.Id','=','crpconsultantworkclassificationfinal.CmnVerifiedServiceId')
                     ->where('crpconsultantworkclassificationfinal.CrpConsultantFinalId',$reference)
                     ->groupBy('T2.Id')
                    ->orderBy('T2.Code');
    }
    public function scopeApprovedService($query,$reference){
        return $query->join('cmnconsultantservice as T2','T2.Id','=','crpconsultantworkclassificationfinal.CmnApprovedServiceId')
                     ->where('crpconsultantworkclassificationfinal.CrpConsultantFinalId',$reference)
                     ->groupBy('T2.Id')
                     ->orderBy('T2.Code');
    }
    public function scopeServices($query,$reference){
        return $query->join('cmnconsultantservicecategory as T1','T1.Id','=','crpconsultantworkclassificationfinal.CmnServiceCategoryId')
                    ->join('cmnconsultantservice as T2','T2.Id','=','crpconsultantworkclassificationfinal.CmnAppliedServiceId')
                    ->join('cmnconsultantservice as T3','T3.Id','=','crpconsultantworkclassificationfinal.CmnVerifiedServiceId')
                    ->join('cmnconsultantservice as T4','T4.Id','=','crpconsultantworkclassificationfinal.CmnApprovedServiceId')
                    ->where('crpconsultantworkclassificationfinal.CrpConsultantFinalId',$reference)
                    ->groupBy('T1.Name')
                    ->orderBy('T1.Name');
    }
}