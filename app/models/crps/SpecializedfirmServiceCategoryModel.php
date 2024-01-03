<?php
class SpecializedfirmServiceCategoryModel extends BaseModel{
	protected $table="cmnspecializedtradecategory";
	protected $fillable=array('Id','Code','Name','CreatedBy','EditedBy','CreatedOn','EditedOn');
    protected $rules=array(
        'Name'=>'required',
    );
    protected $messages=array(
        'Name.required'=>'Name field is required',
    );
    public function scopeCategory($query){
    	return $query->orderBy('Name');
    }
}