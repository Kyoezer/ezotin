<?php
class SpecializedTradeCommentAdverseRecordModel extends BaseModel{
	protected $table="crpspecializedtradecommentsadverserecord";
	protected $fillable=array('Id','CrpSpecializedTradeFinalId','Date','Remarks','Type','CreatedBy','EditedBy','CreatedOn','EditedOn');
    protected $rules=array(
        'Type'=>'required|numeric',
        'CrpSpecializedTradeFinalId'=>'required',
        'Date'=>'required',
        'Remarks'=>'required',
    );
    protected $messages=array(
        'Type.required'=>'Type field is required',
        'Type.numeric'=>'Type field should be a number',
        'CrpSpecializedTradeFinalId.required'=>'You are not allowed to edit record from source code.',
        'Date.required'=>'Date field is required.',
        'Remarks.required'=>'Remarks field is required.',
    );
    public function scopeCommentAdverseRecordList($query,$reference){
        return $query->where('CrpSpecializedTradeFinalId',$reference)->orderBy('Date');
    }
}