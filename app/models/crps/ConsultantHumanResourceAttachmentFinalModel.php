<?php
class ConsultantHumanResourceAttachmentFinalModel extends BaseModel{
	protected $table="crpconsultanthumanresourceattachmentfinal";
	protected $fillable=array('Id','CrpConsultantHumanResourceFinalId','DocumentName','DocumentPath','FileType','CreatedBy','EditedBy','CreatedOn','EditedOn');
    protected $rules=array(
        
    );
    protected $messages=array(
        
    );
    public function scopeHumanResourceAttachment($query,$reference){
    	return $query->where('CrpConsultantHumanResourceFinalId',$reference);
    }
    public function scopeSingleConsultantHumanResourceAllAttachments($query,$reference){
    	return $query->join('crpconsultanthumanresourcefinal as T1','T1.Id','=','crpconsultanthumanresourceattachmentfinal.CrpConsultantHumanResourceFinalId')
    				->where('T1.CrpConsultantFinalId','=',$reference);
    }
}