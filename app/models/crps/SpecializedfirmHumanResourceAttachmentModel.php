<?php
class SpecializedfirmHumanResourceAttachmentModel extends BaseModel{
	protected $table="crpspecializedtradehumanresourceattachment";
	protected $fillable=array('Id','CrpSpecializedtradeHumanResourceId','DocumentName','DocumentPath','FileType','CreatedBy','EditedBy','CreatedOn','EditedOn');
    protected $rules=array(
        
    );
    protected $messages=array(
        
    );
    public function scopeHumanResourceAttachment($query,$reference){
    	return $query->where('CrpSpecializedtradeHumanResourceId',$reference);
    }
    public function scopeSingleSpecializedtradeHumanResourceAllAttachments($query,$reference){
    	return $query->join('crpspecializedtradethumanresource as T1','T1.Id','=','crpspecializedtradehumanresourceattachment.CrpSpecializedtradeHumanResourceId')
    				->where('T1.CrpSpecializedtradeId','=',$reference);
    }
}