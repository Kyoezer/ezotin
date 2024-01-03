<?php
class ContractorHumanResourceAttachmentModel extends BaseModel{
	protected $table="crpcontractorhumanresourceattachment";
	protected $fillable=array('Id','CrpContractorHumanResourceId','DocumentName','DocumentPath','FileType','CreatedBy','EditedBy','CreatedOn','EditedOn');
    protected $rules=array(
        
    );
    protected $messages=array(
        
    );
    public function scopeHumanResourceAttachment($query,$reference){
    	return $query->where('CrpContractorHumanResourceId',$reference);
    }
    public function scopeSingleContractorHumanResourceAllAttachments($query,$reference){
    	return $query->join('crpcontractorhumanresource as T1','T1.Id','=','crpcontractorhumanresourceattachment.CrpContractorHumanResourceId')
    				->where('T1.CrpContractorId','=',$reference);
    }
}