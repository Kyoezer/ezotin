<?php
class EngineerAppliedServiceModel extends BaseModel{
	protected $table="crpengineerappliedservice";
	protected $fillable=array('Id','CrpEngineerId','CmnServiceTypeId','CreatedBy','EditedBy','CreatedOn','EditedOn');
	public function scopeAppliedService($query,$reference){
		return $query->join('crpservice as T1','crpengineerappliedservice.CmnServiceTypeId','=','T1.Id')
					->where('CrpEngineerId',$reference)
					->orderBy('T1.Name');
	}
}