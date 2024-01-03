<?php
class ConsultantEquipmentAttachmentModel extends BaseModel{
	protected $table="crpconsultantequipmentattachment";
	protected $fillable=array('Id','CrpConsultantEquipmentId','DocumentName','DocumentPath','FileType','CreatedBy','EditedBy','CreatedOn','EditedOn');
    protected $rules=array(
        
    );
    protected $messages=array(
        
    );
     public function scopeEquipmentAttachment($query,$reference){
    	return $query->where('CrpConsultantEquipmentId',$reference);
    }
    public function scopeSingleConsultantEquipmentAllAttachments($query,$reference){
    	return $query->join('crpconsultantequipment as T1','T1.Id','=','crpconsultantequipmentattachment.CrpConsultantEquipmentId')
    				->where('T1.CrpConsultantId','=',$reference);
    }
}