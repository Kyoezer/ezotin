<?php
class SpecializedfirmHumanResourceAttachmentFinalModel extends BaseModel{
	protected $table="crpspecializedtradehumanresourceattachmentfinal";
	protected $fillable=array('Id','CrpSpecializedtradeHumanResourceFinalId','DocumentName','DocumentPath','FileType','CreatedBy','EditedBy','CreatedOn','EditedOn');
    protected $rules=array(
        
    );
    protected $messages=array(
        
    );
    public function scopeHumanResourceAttachment($query,$reference){
    	return $query->where('CrpSpecializedtradeHumanResourceFinalId',$reference);
    }
    public function scopeSingleSpecializedtradeHumanResourceAllAttachments($query,$reference){
    	return $query->join('crpspecializedtradehumanresourcefinal as T1','T1.Id','=','crpspecializedtradehumanresourceattachmentfinal.CrpSpecializedtradeHumanResourceFinalId')
    				->where('T1.CrpSpecializedtradeFinalId','=',$reference);
    }
}