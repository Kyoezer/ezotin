<?php
class ArchitectCommentAdverseRecordModel extends BaseModel{
	protected $table="crparchitectcommentsadverserecord";
	protected $fillable=array('Id','CrpArchitectFinalId','Date','Remarks','Type','CreatedBy','EditedBy','CreatedOn','EditedOn');
    protected $rules=array(
        'Type'=>'required|numeric',
        'CrpArchitectFinalId'=>'required',
        'Date'=>'required',
        'Remarks'=>'required',
    );
    protected $messages=array(
        'Type.required'=>'Type field is required',
        'Type.numeric'=>'Type field should be a number',
        'CrpArchitectFinalId.required'=>'You are not allowed to edit record from source code.',
        'Date.required'=>'Date field is required.',
        'Remarks.required'=>'Remarks field is required.',
    );
    public function scopeCommentAdverseRecordList($query,$reference){
        return $query->where('CrpArchitectFinalId',$reference)->orderBy('Date');
    }
}