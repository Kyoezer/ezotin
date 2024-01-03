<?php
class ArchitectAttachmentFinalModel extends BaseModel{
	protected $table="crparchitectattachmentfinal";
	protected $fillable=array('Id','CrpArchitectFinalId','DocumentName','DocumentPath','FileType','CreatedBy','EditedBy','CreatedOn','EditedOn');
    protected $rules=array(
        
    );
    protected $messages=array(
        
    );
     public function scopeAttachment($query,$reference){
    	return $query->where('CrpArchitectFinalId',$reference);
    }
}