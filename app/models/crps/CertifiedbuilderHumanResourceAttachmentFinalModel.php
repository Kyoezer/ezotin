<?php
class CertifiedbuilderHumanResourceAttachmentFinalModel extends BaseModel{
	protected $table="crpcertifiedbuilderhumanresourceattachmentfinal";
	protected $fillable=array('Id','CrpCertifiedbuilderHumanResourceFinalId','DocumentName','DocumentPath','FileType','CreatedBy','EditedBy','CreatedOn','EditedOn');
    protected $rules=array(
        
    );
    protected $messages=array(
        
    );
    public function scopeHumanResourceAttachment($query,$reference){
    	return $query->where('CrpCertifiedbuilderHumanResourceFinalId',$reference);
    }
    public function scopeSingleCertifiedbuilderHumanResourceAllAttachments($query,$reference){
    	return $query->join('crpcertifiedbuilderhumanresourcefinal as T1','T1.Id','=','crpcertifiedbuilderhumanresourceattachmentfinal.CrpCertifiedbuilderHumanResourceFinalId')
    				->where('T1.CrpCertifiedbuilderFinalId','=',$reference);
    }
}