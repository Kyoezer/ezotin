<?php
class CertifiedBuilderCategoryModel extends BaseModel{
	protected $table="cmncertifiedbuildercategory";
	protected $fillable=array('Id','Code','Name','CreatedBy','EditedBy','CreatedOn','EditedOn');
    protected $rules=array(
        'Name'=>'required',
    );
    protected $messages=array(
        'Name.required'=>'Name field is required',
    );
    public function scopeCategory($query){
    	return $query->orderBy('Code')->orderBy("Name");
    }
}