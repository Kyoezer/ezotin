<?php
class SurveyAttachmentFinalModel extends BaseModel{
	protected $table="crpsurveyattachmentfinal";
	protected $fillable=array('Id','CrpSurveyFinalId','DocumentName','DocumentPath','FileType','CreatedBy','EditedBy','CreatedOn','EditedOn');
    protected $rules=array(
        
    );
    protected $messages=array(
        
    );
     public function scopeAttachment($query,$reference){
    	return $query->where('CrpSurveyFinalId',$reference);
    }
}