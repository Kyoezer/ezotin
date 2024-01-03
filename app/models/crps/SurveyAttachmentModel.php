<?php
class SurveyAttachmentModel extends BaseModel{
	protected $table="crpsurveyattachment";
	protected $fillable=array('Id','CrpSurevyId','DocumentName','DocumentPath','FileType','CreatedBy','EditedBy','CreatedOn','EditedOn');
    protected $rules=array(
        
    );
    protected $messages=array(
        
    );
     public function scopeAttachment($query,$reference){
    	return $query->where('CrpSurveyId',$reference);
    }
}