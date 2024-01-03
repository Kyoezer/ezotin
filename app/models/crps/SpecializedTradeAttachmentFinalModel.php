<?php
class SpecializedTradeAttachmentFinalModel extends BaseModel{
	protected $table="crpspecializedtradeattachmentfinal";
	protected $fillable=array('Id','CrpSpecializedTradeFinalId','DocumentName','DocumentPath','FileType','CreatedBy','EditedBy','CreatedOn','EditedOn');
    protected $rules=array(
        
    );
    protected $messages=array(
        
    );
     public function scopeAttachment($query,$reference){
    	return $query->where('CrpSpecializedTradeFinalId',$reference);
    }
}