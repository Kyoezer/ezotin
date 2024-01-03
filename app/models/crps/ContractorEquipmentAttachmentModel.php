<?php
class ContractorEquipmentAttachmentModel extends BaseModel{
	protected $table="crpcontractorequipmentattachment";
	protected $fillable=array('Id','CrpContractorEquipmentId','DocumentName','DocumentPath','FileType','CreatedBy','EditedBy','CreatedOn','EditedOn');
    protected $rules=array(
        
    );
    protected $messages=array(
        
    );
     public function scopeEquipmentAttachment($query,$reference){
    	return $query->where('CrpContractorEquipmentId',$reference);
    }
    public function scopeSingleContractorEquipmentAllAttachments($query,$reference){
    	return $query->join('crpcontractorequipment as T1','T1.Id','=','crpcontractorequipmentattachment.CrpContractorEquipmentId')
    				->where('T1.CrpContractorId','=',$reference);
    }
}