<?php
class ConsultantServiceModel extends BaseModel{
	protected $table="cmnconsultantservice";
	protected $fillable=array('Id','CmnConsultantServiceCategoryId','Code','Name','CreatedBy','EditedBy','CreatedOn','EditedOn');
    protected $rules=array(
        'CmnConsultantServiceCategoryId'=>'required',
    	'Code'=>'required',
        'Name'=>'required',
    );
    protected $messages=array(
        'CmnConsultantServiceCategoryId.required'=>'Category field is required',
        'Name.required'=>'Name field is required',
        'Code.required'=>'Code field is required',
    );
    public function scopeService($query){
        return $query->orderBy('Code');
    }
    public function scopeServiceList($query){
        return $query->join('cmnconsultantservicecategory as T1','cmnconsultantservice.CmnConsultantServiceCategoryId','=','T1.Id')->orderBy('T1.Code')->orderBy('T1.Name')->orderBy('cmnconsultantservice.Code')->orderBy('cmnconsultantservice.Name');   
    }
}