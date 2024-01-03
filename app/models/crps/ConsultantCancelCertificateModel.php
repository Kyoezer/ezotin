<?php
class ConsultantCancelCertificateModel extends BaseModel{
	protected $table="crpconsultantcertificatecancellationrequest";
	protected $fillable=array('Id','SysLockedByUserId','AttachmentFilePath','ReasonForCancellation','ReferenceNo','ApplicationDate','CrpConsultantFinalId','CmnApplicationStatusId','CreatedBy','EditedBy','CreatedOn','EditedOn');
	protected $rules = array(
		'Attachment'=>'required',
		'ReasonForCancellation'=>'required'
	);
	protected $messages = array(
		'Attachment.required'=>'Attachment field is required',
		'ReasonForCancellation.required'=>'Reason For Cancellation field is required'
	);
	public function scopeCancellationList($query,$reference,$cancelRequestId){
    	return $query->join('crpconsultantfinal as T1','T1.Id','=','crpconsultantcertificatecancellationrequest.CrpConsultantFinalId')
    				->join('cmncountry as T2','T1.CmnCountryId','=','T2.Id')
    				->leftJoin('cmndzongkhag as T3','T1.CmnDzongkhagId','=','T3.Id')
    				->where('crpconsultantcertificatecancellationrequest.Id',$cancelRequestId)
    				->where('crpconsultantcertificatecancellationrequest.CrpConsultantFinalId',$reference);
    }
}