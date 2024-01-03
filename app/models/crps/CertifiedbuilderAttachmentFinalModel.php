<?php
class CertifiedbuilderAttachmentFinalModel extends BaseModel{
	protected $table="crpcertifiedbuilderattachmentfinal";
	protected $fillable=array('Id','CrpCertifiedbuilderFinalId','DocumentName','DocumentPath','FileType','CreatedBy','EditedBy','CreatedOn','EditedOn');
    protected $rules=array(
        
    );
    protected $messages=array(
        
    );
     public function scopeAttachment($query,$reference){
    	return $query->where('CrpCertifiedbuilderFinalId',$reference);
    }
}