<?php
class ConsultantAttachmentFinalModel extends BaseModel{
	protected $table="crpconsultantattachmentfinal";
	protected $fillable=array('Id','CrpConsultantFinalId','DocumentName','DocumentPath','FileType','CreatedBy','EditedBy','CreatedOn','EditedOn');
    protected $rules=array(
        
    );
    protected $messages=array(
        
    );
     public function scopeAttachment($query,$reference){
    	return $query->where('CrpConsultantFinalId',$reference);
    }
}