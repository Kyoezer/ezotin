<?php
class ContractorHumanResourceAttachmentFinalModel extends BaseModel{
	protected $table="crpcontractorhumanresourceattachmentfinal";
	protected $fillable=array('Id','CrpContractorHumanResourceFinalId','DocumentName','DocumentPath','FileType','CreatedBy','EditedBy','CreatedOn','EditedOn');
    protected $rules=array(
        
    );
    protected $messages=array(
        
    );
    public function scopeHumanResourceAttachment($query,$reference){
    	return $query->where('CrpContractorHumanResourceFinalId',$reference);
    }
    public function scopeSingleContractorHumanResourceAllAttachments($query,$reference){
    	return $query->join('crpcontractorhumanresourcefinal as T1','T1.Id','=','crpcontractorhumanresourceattachmentfinal.CrpContractorHumanResourceFinalId')
    				->where('T1.CrpContractorFinalId','=',$reference);
    }
}