<?php
class EngineerAttachmentFinalModel extends BaseModel{
	protected $table="crpengineerattachmentfinal";
	protected $fillable=array('Id','CrpEngineerFinalId','DocumentName','DocumentPath','FileType','CreatedBy','EditedBy','CreatedOn','EditedOn');
    protected $rules=array(
        
    );
    protected $messages=array(
        
    );
     public function scopeAttachment($query,$reference){
    	return $query->where('CrpEngineerFinalId',$reference);
    }
}