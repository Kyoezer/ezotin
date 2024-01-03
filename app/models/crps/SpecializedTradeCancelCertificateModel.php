<?php
class SpecializedTradeCancelCertificateModel extends BaseModel{
	protected $table="crpspecializedtradecertificatecancellationrequest";
	protected $fillable=array('Id','SysLockedByUserId','ReasonForCancellation','AttachmentFilePath','ReferenceNo','ApplicationDate','CrpSpecializedTradeFinalId','CmnApplicationStatusId','CreatedBy','EditedBy','CreatedOn','EditedOn');
	protected $rules = array(
		'Attachment'=>'required',
		'ReasonForCancellation'=>'required'
	);
	protected $messages = array(
		'Attachment.required'=>'Attachment field is required',
		'ReasonForCancellation.required'=>'Reason For Cancellation field is required'
	);
    public function scopeCancellationList($query,$reference,$cancelRequestId){
        return $query->join('crpspecializedtradefinal as T1','T1.Id','=','crpspecializedtradecertificatecancellationrequest.CrpSpecializedTradeFinalId')
    				->join('cmndzongkhag as T2','T1.CmnDzongkhagId','=','T2.Id')
                    ->join('cmnlistitem as T3','T1.CmnSalutationId','=','T3.Id')
    				->where('crpspecializedtradecertificatecancellationrequest.Id',$cancelRequestId)
    				->where('crpspecializedtradecertificatecancellationrequest.CrpSpecializedTradeFinalId',$reference);
    }
}