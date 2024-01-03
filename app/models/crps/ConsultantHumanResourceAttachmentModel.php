<?php
class ConsultantHumanResourceAttachmentModel extends BaseModel{
	protected $table="crpconsultanthumanresourceattachment";
	protected $fillable=array('Id','CrpConsultantHumanResourceId','DocumentName','DocumentPath','FileType','CreatedBy','EditedBy','CreatedOn','EditedOn');
    protected $rules=array(
        
    );
    protected $messages=array(
        
    );
    public function scopeHumanResourceAttachment($query,$reference){
    	return $query->where('CrpConsultantHumanResourceId',$reference);
    }
    public function scopeSingleConsultantHumanResourceAllAttachments($query,$reference){
    	return $query->join('crpconsultanthumanresource as T1','T1.Id','=','crpconsultanthumanresourceattachment.CrpConsultantHumanResourceId')
    				->where('T1.CrpConsultantId','=',$reference);
    }
}