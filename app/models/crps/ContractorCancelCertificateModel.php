<?php
class ContractorCancelCertificateModel extends BaseModel{
	protected $table="crpcontractorcertificatecancellationrequest";
	protected $fillable=array('Id','SysLockedByUserId','AttachmentFilePath','ReasonForCancellation','ReferenceNo','ApplicationDate','CrpContractorFinalId','CmnApplicationStatusId','CreatedBy','EditedBy','CreatedOn','EditedOn');
    protected $rules = array(
		'Attachment'=>'required',
		'ReasonForCancellation'=>'required'
	);
	protected $messages = array(
		'Attachment.required'=>'Attachment field is required',
		'ReasonForCancellation.required'=>'Reason For Cancellation field is required'
	);
	public function scopeCancellationList($query,$reference,$cancelRequestId){
    	return $query->join('crpcontractorfinal as T1','T1.Id','=','crpcontractorcertificatecancellationrequest.CrpContractorFinalId')
    				->join('cmncountry as T2','T1.CmnCountryId','=','T2.Id')
    				->leftJoin('cmndzongkhag as T3','T1.CmnDzongkhagId','=','T3.Id')
    				->where('crpcontractorcertificatecancellationrequest.Id',$cancelRequestId)
    				->where('crpcontractorcertificatecancellationrequest.CrpContractorFinalId',$reference);
    }
}