<?php
class ConsultantAttachmentModel extends BaseModel{
	protected $table="crpconsultantattachment";
	protected $fillable=array('Id','CrpConsultantId','DocumentName','DocumentPath','FileType','CreatedBy','EditedBy','CreatedOn','EditedOn');
    protected $rules=array(
        
    );
    protected $messages=array(
        
    );
     public function scopeAttachment($query,$reference){
    	return $query->where('CrpConsultantId',$reference);
    }
}