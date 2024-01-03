<?php
class SpecializedfirmEquipmentAttachmentFinalModel extends BaseModel{
	protected $table="crpspecializedtradeequipmentattachmentfinal";
	protected $fillable=array('Id','CrpSpecializedtradeEquipmentFinalId','DocumentName','DocumentPath','FileType','CreatedBy','EditedBy','CreatedOn','EditedOn');
    protected $rules=array(
        
    );
    protected $messages=array(
        
    );
     public function scopeEquipmentAttachment($query,$reference){
    	return $query->where('CrpSpecializedtradeEquipmentFinalId',$reference);
    }
    public function scopeSingleSpecializedtradeEquipmentAllAttachments($query,$reference){
    	return $query->join('crpspecializedtradeequipmentfinal as T1','T1.Id','=','crpspecializedtradeequipmentattachmentfinal.CrpSpecializedtradeEquipmentFinalId')
    				->where('T1.CrpSpecializedtradeFinalId','=',$reference);
    }
}