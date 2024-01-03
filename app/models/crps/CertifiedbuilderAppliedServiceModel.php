<?php
class SpecializedfirmAppliedServiceModel extends BaseModel{
	protected $table="crpspecializedtradeappliedservice";
	protected $fillable=array('Id','CrpSpecializedtradeId','CmnServiceTypeId','CreatedBy','EditedBy','CreatedOn','EditedOn');
	public function scopeAppliedService($query,$reference){
		return $query->join('crpservice as T1','crpspecializedtradeappliedservice.CmnServiceTypeId','=','T1.Id')
					->where('CrpSpecializedtradeId',$reference)
					->orderBy('T1.ReferenceNo')
					->orderBy('T1.Name');
	}
}