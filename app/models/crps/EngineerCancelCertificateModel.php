<?php
class EngineerCancelCertificateModel extends BaseModel{
	protected $table="crpengineercertificatecancellationrequest";
	protected $fillable=array('Id','SysLockedByUserId','ReferenceNo','AttachmentFilePath','ReasonForCancellation','ApplicationDate','CrpEngineerFinalId','CmnApplicationStatusId','CreatedBy','EditedBy','CreatedOn','EditedOn');
	protected $rules = array(
		'Attachment'=>'required',
		'ReasonForCancellation'=>'required'
	);
	protected $messages = array(
		'Attachment.required'=>'Attachment field is required',
		'ReasonForCancellation.required'=>'Reason For Cancellation field is required'
	);
    public function scopeCancellationList($query,$reference,$cancelRequestId){
    	return $query->join('crpengineerfinal as T1','T1.Id','=','crpengineercertificatecancellationrequest.CrpEngineerFinalId')
    				->join('cmncountry as T2','T1.CmnCountryId','=','T2.Id')
    				->join('cmnlistitem as T4','T1.CmnServiceSectorTypeId','=','T4.Id')
    				->join('cmnlistitem as T5','T1.CmnTradeId','=','T5.Id')
    				->leftJoin('cmndzongkhag as T3','T1.CmnDzongkhagId','=','T3.Id')
    				->where('crpengineercertificatecancellationrequest.Id',$cancelRequestId)
    				->where('crpengineercertificatecancellationrequest.CrpEngineerFinalId',$reference);
    }
}