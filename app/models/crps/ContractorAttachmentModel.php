<?php
class ContractorAttachmentModel extends BaseModel{
	protected $table="crpcontractorattachment";
	protected $fillable=array('Id','CrpContractorId','DocumentName','DocumentPath','FileType','CreatedBy','EditedBy','CreatedOn','EditedOn');
    protected $rules=array(
        
    );
    protected $messages=array(
        
    );
     public function scopeAttachment($query,$reference){
    	return $query->where('CrpContractorId',$reference);
    }
}