<?php
class ContractorAppliedServiceModel extends BaseModel{
	protected $table="crpcontractorappliedservice";
	protected $fillable=array('Id','CrpContractorId','CmnServiceTypeId','CreatedBy','EditedBy','CreatedOn','EditedOn');
	public function scopeAppliedService($query,$reference){
		return $query->join('crpservice as T1','crpcontractorappliedservice.CmnServiceTypeId','=','T1.Id')
					->where('CrpContractorId',$reference)
					->orderBy('T1.ReferenceNo')
					->orderBy('T1.Name');
	}
}