<?php
class ArchitectAttachmentModel extends BaseModel{
	protected $table="crparchitectattachment";
	protected $fillable=array('Id','CrpArchitectId','DocumentName','DocumentPath','FileType','CreatedBy','EditedBy','CreatedOn','EditedOn');
    protected $rules=array(
        
    );
    protected $messages=array(
        
    );
     public function scopeAttachment($query,$reference){
    	return $query->where('CrpArchitectId',$reference);
    }
}