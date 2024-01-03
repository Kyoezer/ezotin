<?php
class ContractorAttachmentFinalModel extends BaseModel{
	protected $table="crpcontractorattachmentfinal";
	protected $fillable=array('Id','CrpContractorFinalId','DocumentName','DocumentPath','FileType','CreatedBy','EditedBy','CreatedOn','EditedOn');
    protected $rules=array(
        
    );
    protected $messages=array(
        
    );
     public function scopeAttachment($query,$reference){
    	return $query->where('CrpContractorFinalId',$reference);
    }
}