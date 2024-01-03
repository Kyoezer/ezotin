<?php
class SpecializedfirmAttachmentFinalModel extends BaseModel{
	protected $table="crpspecializedtradetattachmentfinal";
	protected $fillable=array('Id','CrpSpecializedtradeFinalId','DocumentName','DocumentPath','FileType','CreatedBy','EditedBy','CreatedOn','EditedOn');
    protected $rules=array(
        
    );
    protected $messages=array(
        
    );
     public function scopeAttachment($query,$reference){
    	return $query->where('CrpSpecializedtradeFinalId',$reference);
    }
}