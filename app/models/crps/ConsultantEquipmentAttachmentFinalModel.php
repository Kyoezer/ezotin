<?php
class ConsultantEquipmentAttachmentFinalModel extends BaseModel{
	protected $table="crpconsultantequipmentattachmentfinal";
	protected $fillable=array('Id','CrpConsultantEquipmentFinalId','DocumentName','DocumentPath','FileType','CreatedBy','EditedBy','CreatedOn','EditedOn');
    protected $rules=array(
        
    );
    protected $messages=array(
        
    );
     public function scopeEquipmentAttachment($query,$reference){
    	return $query->where('CrpConsultantEquipmentFinalId',$reference);
    }
    public function scopeSingleConsultantEquipmentAllAttachments($query,$reference){
    	return $query->join('crpconsultantequipmentfinal as T1','T1.Id','=','crpconsultantequipmentattachmentfinal.CrpConsultantEquipmentFinalId')
    				->where('T1.CrpConsultantFinalId','=',$reference);
    }
}