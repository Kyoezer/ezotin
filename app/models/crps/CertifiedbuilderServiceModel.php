<?php
class CertifiedbuilderServiceModel extends BaseModel{
	protected $table="cmncertifiedbuildercategory";
	protected $fillable=array('Id','Code','Name','CreatedBy','EditedBy','CreatedOn','EditedOn');
    protected $rules=array(
       
    	'Code'=>'required',
        'Name'=>'required',
    );
    protected $messages=array(
       
        'Name.required'=>'Name field is required',
        'Code.required'=>'Code field is required',
    );
    public function scopeService($query){
        return $query->orderBy('Code');
    }
    
}