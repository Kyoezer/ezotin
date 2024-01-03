<?php
class CmnDivisionModel extends BaseModel{
	protected $table="cmndivision";
	protected $fillable=array('Id','Code','Name','CmnMinistryId','CreatedBy','EditedBy','CreatedOn','EditedOn');
    protected $rules=array(
        'Name'=>'required',
        'CmnMinistryId' => 'required'
    );
    protected $messages=array(
        'Name.required'=>'Name field is required',
        'CmnMinistryId.required'=>'Ministry field is required',
    );
    public function scopeDivision($query){
        return $query->join('cmnlistitem as T2','T2.Id','=','cmndivision.CmnMinistryId')->orderBy('Name');
    }
}