<?php
class ConsultantAppliedServiceModel extends BaseModel{
	protected $table="crpconsultantappliedservice";
	protected $fillable=array('Id','CrpConsultantId','CmnServiceTypeId','CreatedBy','EditedBy','CreatedOn','EditedOn');
	public function scopeAppliedService($query,$reference){
		return $query->join('crpservice as T1','crpconsultantappliedservice.CmnServiceTypeId','=','T1.Id')
					->where('CrpConsultantId',$reference)
					->orderBy('T1.Name');
	}
}