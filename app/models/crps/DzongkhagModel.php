<?php
class DzongkhagModel extends BaseModel{
	protected $table="cmndzongkhag";
	protected $fillable=array('Id','Code','NameEn','CreatedBy','EditedBy','CreatedOn','EditedOn');
    protected $rules=array(
        'Code'=>'required',
        'NameEn'=>'required',
    );
    protected $messages=array(
        'Code.required'=>'Code field is required',
        'NameEn.required'=>'Name field is required',
    );
    public function scopeDzongkhag($query){
        return $query->orderBy('NameEn');
    }
}