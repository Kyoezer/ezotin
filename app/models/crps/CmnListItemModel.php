<?php
class CmnListItemModel extends BaseModel{
	protected $table="cmnlistitem";
	protected $fillable=array('Id','Code','Name','CmnListId','CreatedBy','EditedBy','CreatedOn','EditedOn');
    protected $rules=array(
        'Name'=>'required',
    );
    protected $messages=array(
        'Name.required'=>'Name field is required',
    );
    public function scopeMinistry($query){
        return $query->where('CmnListId',CONST_CMN_REFERENCE_MINISTRY)->orderBy('Name');
    }
    public function scopeDivision($query){
        return $query->where('CmnListId',CONST_CMN_REFERENCE_DIVISION)->orderBy('Name');
    }
    public function scopeEquipments($query){
        return $query->where('CmnListId',CONST_CMN_REFERENCE_EQUIPMENT)->orderBy('Name');
    }
    public function scopeFinancialInstitution($query){
        return $query->where('CmnListId',CONST_CMN_REFERENCE_FINANCIALINTITUTION)->orderBy('Name');
    }
    public function scopeDesignation($query){
        return $query->where('CmnListId',CONST_CMN_REFERENCE_DESIGNATION)->orderBy('Name');
    }
    public function scopeSalutation($query){
        return $query->where('CmnListId',CONST_CMN_REFERENCE_SALUTATION)->orderBy('Name');
    }
    public function scopeQualification($query){
        return $query->where('CmnListId',CONST_CMN_REFERENCE_QUALIFICATION)->orderBy('Name');
    }
    public function scopeTrade($query){
        return $query->where('CmnListId',CONST_CMN_REFERENCE_TRADE)->orderBy('Name');
    }
    public function scopeRegistrationStatus($query){
        return $query->where('CmnListId',CONST_CMN_REFERENCE_REGISTRATIONSTATUS)->orderBy('ReferenceNo');
    }
    public function scopeWorkExecutionStatus($query){
        return $query->where('CmnListId',CONST_CMN_REFERENCE_WORKEXECUTIONSTATUS)->orderBy('ReferenceNo');
    }
     public function scopeServiceSectorType($query){
        return $query->where('CmnListId',CONST_CMN_REFERENCE_SERVICESECTORTYPE)->orderBy('Code')->orderBy('Name');
    }
     public function scopeOwnershipType($query){
        return $query->where('CmnListId',CONST_CMN_REFERENCE_OWNERSHIPTYPE)->orderBy('Name');
    }
    public function scopeServiceType($query){
        return $query->where('CmnListId',CONST_CMN_REFERENCE_SERVICETYPE)->orderBy('Name');
    }
    public function scopeTrainingType($query){
        return $query->where('CmnListId',CONST_CMN_REFERENCE_TRAININGTYPE)->orderBy('ReferenceNo');
    }
    public function scopeTrainingModule($query){
        return $query->where('CmnListId',CONST_CMN_REFERENCE_TRAININGMODULE)->orderBy('Name');
    }
}