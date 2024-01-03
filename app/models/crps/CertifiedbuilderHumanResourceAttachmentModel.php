<?php
class CertifiedbuilderHumanResourceAttachmentModel extends BaseModel{
	protected $table="crpcertifiedbuilderhumanresourceattachment";
	protected $fillable=array('Id','CrpCertifiedbuilderHumanResourceId','DocumentName','DocumentPath','FileType','CreatedBy','EditedBy','CreatedOn','EditedOn');
    protected $rules=array(
        
    );
    protected $messages=array(
        
    );
    public function scopehumanResourceAttachment($query,$reference){
    	return $query->where('CrpCertifiedbuilderHumanResourceId',$reference);
    }
    public function scopesingleCertifiedbuilderHumanResourceAllAttachments($query,$reference){
    	return $query->join('crpcertifiedbuilderthumanresource as T1','T1.Id','=','crpcertifiedbuilderhumanresourceattachment.CrpCertifiedbuilderHumanResourceId')
    				->where('T1.CrpCertifiedbuilderId','=',$reference);
    }
}