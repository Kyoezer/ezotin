<?php 
class CrpServiceFeeStructure extends BaseModel{
	protected $table="crpservicefeestructure";
	public function scopeFeeStructure($query,$referenceNo){
		return $query->where("ReferenceNo",$referenceNo);
	}
}