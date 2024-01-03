<?php
class CertifiedbuilderEquipmentAttachmentModel extends BaseModel{
	protected $table="crpcertifiedbuilderequipmentattachment";
	protected $fillable=array('Id','CrpCertifiedBuilderEquipmentId','DocumentName','DocumentPath','FileType','CreatedBy','EditedBy','CreatedOn','EditedOn');
    protected $rules=array(
        
    );
    protected $messages=array(
        
    );
     public function scopeEquipmentAttachment($query,$reference){
    	return $query->where('CrpCertifiedBuilderEquipmentId',$reference);
    }
    public function scopeSingleCertifiedbuilderEquipmentAllAttachments($query,$reference){
    	return $query->join('crpcertifiedbuilderequipment as T1','T1.Id','=','crpcertifiedbuilderequipmentattachment.CrpCertifiedBuilderEquipmentId')
    				->where('T1.CrpCertifiedBuilderId','=',$reference);
    }
}