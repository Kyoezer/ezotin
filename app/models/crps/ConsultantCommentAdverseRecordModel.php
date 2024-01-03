<?php
class ConsultantCommentAdverseRecordModel extends BaseModel{
	protected $table="crpconsultantcommentsadverserecord";
	protected $fillable=array('Id','CrpConsultantFinalId','Date','Remarks','Type','CreatedBy','EditedBy','CreatedOn','EditedOn');
    protected $rules=array(
        'Type'=>'required|numeric',
        'CrpConsultantFinalId'=>'required',
        'Date'=>'required',
        'Remarks'=>'required',
    );
    protected $messages=array(
        'Type.required'=>'Type field is required',
        'Type.numeric'=>'Type field should be a number',
        'CrpConsultantFinalId.required'=>'You are not allowed to edit record from source code.',
        'Date.required'=>'Date field is required.',
        'Remarks.required'=>'Remarks field is required.',
    );
    public function scopeCommentAdverseRecordList($query,$reference){
        return $query->where('CrpConsultantFinalId',$reference)->orderBy('Date');
    }
    public function scopeCommentList($query,$reference){
        return $query->where('CrpConsultantFinalId',$reference)->where('Type',1)->orderBy('Date');
    }
    public function scopeAdverseRecordList($query,$reference){
        return $query->where('CrpConsultantFinalId',$reference)->where('Type',2)->orderBy('Date');
    }
}