<?php
class ContractorEquipmentAttachmentFinalModel extends BaseModel{
	protected $table="crpcontractorequipmentattachmentfinal";
	protected $fillable=array('Id','CrpContractorEquipmentFinalId','DocumentName','DocumentPath','FileType','CreatedBy','EditedBy','CreatedOn','EditedOn');
    protected $rules=array(
        
    );
    protected $messages=array(
        
    );
     public function scopeEquipmentAttachment($query,$reference){
    	return $query->where('CrpContractorEquipmentFinalId',$reference);
    }
    public function scopeSingleContractorEquipmentAllAttachments($query,$reference){
    	return $query->join('crpcontractorequipmentfinal as T1','T1.Id','=','crpcontractorequipmentattachmentfinal.CrpContractorEquipmentFinalId')
    				->where('T1.CrpContractorFinalId','=',$reference);
    }
}