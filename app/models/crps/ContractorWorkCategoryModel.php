<?php
class ContractorWorkCategoryModel extends BaseModel{
	protected $table="cmncontractorworkcategory";
	protected $fillable=array('Id','Code','Name','CreatedBy','EditedBy','CreatedOn','EditedOn');
    protected $rules=array(
        'Code'=>'required',
        'Name'=>'required',
    );
    protected $messages=array(
        'Code.required'=>'Code field is required',
        'Name.required'=>'Name field is required',
    );
    public function scopeContractorProjectCategory($query){
    	return $query->orderBy('Code')->orderBy('Name');
    }
}