<?php
class CertifiedbuilderCommentAdverseRecordModel extends BaseModel{
	protected $table="crpcertifiedbuildercommentsadverserecord";
	protected $fillable=array('Id','CrpCertifiedbuilderFinalId','Date','Remarks','Type','CreatedBy','EditedBy','CreatedOn','EditedOn');
    protected $rules=array(
        'Type'=>'required|numeric',
        'CrpCertifiedbuilderFinalId'=>'required',
        'Date'=>'required',
        'Remarks'=>'required',
    );
    protected $messages=array(
        'Type.required'=>'Type field is required',
        'Type.numeric'=>'Type field should be a number',
        'CrpCertifiedbuilderFinalId.required'=>'You are not allowed to edit record from source code.',
        'Date.required'=>'Date field is required.',
        'Remarks.required'=>'Remarks field is required.',
    );
    public function scopeCommentAdverseRecordList($query,$reference){
        return $query->where('CrpCertifiedbuilderFinalId',$reference)->orderBy('Date');
    }
    public function scopeCommentList($query,$reference){
        return $query->where('CrpCertifiedbuilderFinalId',$reference)->where('Type',1)->orderBy('Date');
    }
    public function scopeAdverseRecordList($query,$reference){
        return $query->where('CrpCertifiedbuilderFinalId',$reference)->where('Type',2)->orderBy('Date');
    }
}