<?php
class ArchitectCancelCertificateModel extends BaseModel{
	protected $table="crparchitectcertificatecancellationrequest";
	protected $fillable=array('Id','SysLockedByUserId','SysRejectorUserId','ReferenceNo','ApplicationDate','RemarksByApprover','RemarksByRejector','ReasonForCancellation','AttachmentFilePath','CrpArchitectFinalId','CmnApplicationStatusId','CreatedBy','EditedBy','CreatedOn','EditedOn');
	protected $rules = array(
		'Attachment'=>'required',
		'ReasonForCancellation'=>'required'
	);
	protected $messages = array(
		'Attachment.required'=>'Attachment field is required',
		'ReasonForCancellation.required'=>'Reason For Cancellation field is required'
	);
    public function scopeCancellationList($query,$reference,$cancelRequestId){
    	return $query->join('crparchitectfinal as T1','T1.Id','=','crparchitectcertificatecancellationrequest.CrpArchitectFinalId')
    				->join('cmncountry as T2','T1.CmnCountryId','=','T2.Id')
    				->leftJoin('cmndzongkhag as T3','T1.CmnDzongkhagId','=','T3.Id')
    				->where('crparchitectcertificatecancellationrequest.Id',$cancelRequestId)
    				->where('crparchitectcertificatecancellationrequest.CrpArchitectFinalId',$reference);
    }
}