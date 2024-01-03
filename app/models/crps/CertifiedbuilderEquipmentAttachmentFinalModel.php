<?php
class CertifiedbuilderEquipmentAttachmentFinalModel extends BaseModel{
	protected $table="crpcertifiedbuilderequipmentattachmentfinal";
	protected $fillable=array('Id','CrpCertifiedBuilderEquipmentFinalId','DocumentName','DocumentPath','FileType','CreatedBy','EditedBy','CreatedOn','EditedOn');
    protected $rules=array(
        
    );
    protected $messages=array(
        
    );
     public function scopeEquipmentAttachment($query,$reference){
    	return $query->where('CrpCertifiedBuilderEquipmentFinalId',$reference);
    }
    public function scopeSingleCertifiedbuilderEquipmentAllAttachments($query,$reference){
    	return $query->join('crpcertifiedbuilderequipmentfinal as T1','T1.Id','=','crpcertifiedbuilderequipmentattachmentfinal.CrpCertifiedBuilderEquipmentFinalId')
    				->where('T1.CrpCertifiedBuilderFinalId','=',$reference);
    }
}