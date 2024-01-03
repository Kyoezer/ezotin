<?php 
class CrpService extends BaseModel{
	protected $table="crpservice";
	protected $fillable = array('Id', 'ReferenceNo', 'Name', 'HasFee', 'ContractorAmount', 'ConsultantAmount', 'ArchitectGovtAmount', 'ArchitectPvtAmount', 'SpecializedTradeFirstRenewAmount', 'SpecializedTradeAfterFirstRenewAmount', 'EngineerGovtAmount', 'EngineerPvtAmount', 'ContractorValidity', 'ConsultantValidity', 'ArchitectGovtValidity', 'ArchitectPvtValidity', 'SpecializedTradeValidity', 'EngineerGovtValidity', 'EngineerPvtValidity', 'CreatedBy', 'EditedBy');
	public function scopeRegistrationValidityYear($query,$reference){
		return $query->where("Id",$reference);
	}
	public function scopeServiceDetails($query,$reference){
		return $query->where("Id",$reference);	
	}
}