<?php
class SpecializedTradeAppliedServiceModel extends BaseModel{
	protected $table="crpspecializedtradeappliedservice";
	protected $fillable=array('Id','CrpSpecializedTradeId','CmnServiceTypeId','CreatedBy','EditedBy','CreatedOn','EditedOn');
	public function scopeAppliedService($query,$reference){
		return $query->join('crpservice as T1','crpspecializedtradeappliedservice.CmnServiceTypeId','=','T1.Id')
					->where('CrpSpecializedTradeId',$reference)
					->orderBy('T1.Name');
	}
	public function scopeServiceRenewalCount($query,$reference){
		return $query->join('crpspecializedtrade as T1','T1.Id','=','crpspecializedtradeappliedservice.CrpSpecializedTradeId')
					->where('T1.CrpSpecializedTradeId',$reference)
					->where('T1.CmnApplicationRegistrationStatusId',CONST_CMN_APPLICATIONREGISTRATIONSTATUS_APPROVED);

	}
}