<?php
class SpecializedfirmEquipmentAttachmentModel extends BaseModel{
	protected $table="crpspecializedtradeequipmentattachment";
	protected $fillable=array('Id','CrpSpecializedtradeEquipmentId','DocumentName','DocumentPath','FileType','CreatedBy','EditedBy','CreatedOn','EditedOn');
    protected $rules=array(
        
    );
    protected $messages=array(
        
    );
     public function scopeEquipmentAttachment($query,$reference){
    	return $query->where('CrpSpecializedtradeEquipmentId',$reference);
    }
    public function scopeSingleSpecializedtradeEquipmentAllAttachments($query,$reference){
    	return $query->join('crpspecializedtradeequipment as T1','T1.Id','=','crpspecializedtradeequipmentattachment.CrpSpecializedtradeEquipmentId')
    				->where('T1.CrpSpecializedtradeId','=',$reference);
    }
}