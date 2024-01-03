<?php
class EngineerAttachmentModel extends BaseModel{
	protected $table="crpengineerattachment";
	protected $fillable=array('Id','CrpEngineerId','DocumentName','DocumentPath','FileType','CreatedBy','EditedBy','CreatedOn','EditedOn');
    protected $rules=array(
        
    );
    protected $messages=array(
        
    );
     public function scopeAttachment($query,$reference){
    	return $query->where('CrpEngineerId',$reference);
    }
}