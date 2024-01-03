<?php
class ContractorClassificationModel extends BaseModel{
	protected $table="cmncontractorclassification";
	protected $fillable=array('Id','ReferenceNo','Code','Name','RegistrationFee','RenewalFee','ChangeOfCategoryClassFee','CreatedBy','EditedBy','CreatedOn','EditedOn');
    protected $rules=array(
        'Code'=>'required',
        'Name'=>'required',
        'RegistrationFee'=>'required|numeric',
        'RenewalFee'=>'required|numeric',
    );
    protected $messages=array(
        'Code.required'=>'Code field is required',
        'Name.required'=>'Name field is required',
        'RegistrationFee.required'=>'Registration Fee field is required',
        'RenewalFee.required'=>'Renewal Fee field is required',
        'RegistrationFee.numeric'=>'Registration Fee field should be a number',
        'RenewalFee.numeric'=>'Renewal Fee field should be a number',
        'ChangeOfCategoryClassFee.numeric'=>'Change of Category/Classification field should be a number',
    );
    public function scopeClassification($query){
        return $query->orderBy('Priority','desc');
    }
}