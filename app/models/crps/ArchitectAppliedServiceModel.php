<?php
class ArchitectAppliedServiceModel extends BaseModel{
	protected $table="crparchitectappliedservice";
	protected $fillable=array('Id','CrpArchitectId','CmnServiceTypeId','CreatedBy','EditedBy','CreatedOn','EditedOn');
	public function scopeAppliedService($query,$reference){
		return $query->join('crpservice as T1','crparchitectappliedservice.CmnServiceTypeId','=','T1.Id')
					->where('CrpArchitectId',$reference)
					->orderBy('T1.Name');
	}
}