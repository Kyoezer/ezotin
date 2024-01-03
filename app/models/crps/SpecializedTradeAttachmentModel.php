<?php
class SpecializedTradeAttachmentModel extends BaseModel{
	protected $table="crpspecializedtradeattachment";
	protected $fillable=array('Id','CrpSpecializedTradeId','DocumentName','DocumentPath','FileType','CreatedBy','EditedBy','CreatedOn','EditedOn');
    protected $rules=array(
        
    );
    protected $messages=array(
        
    );
     public function scopeAttachment($query,$reference){
    	return $query->where('CrpSpecializedTradeId',$reference);
    }
}