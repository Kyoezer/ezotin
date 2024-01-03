<?php
class TrainingDetailModel extends BaseModel{
	protected $table="crpcontractortrainingdetail";
	protected $fillable=array('Id','CrpContractorTrainingId','CrpContractorFinalId','Participant','CIDNo','Designation','Qualification','ContactNo','Gender','CreatedBy','EditedBy','CreatedOn','EditedOn');
    protected $rules=array(
        'CrpContractorFinalId'=>'required',
        'Participant'=>'required',
        'CIDNo'=>'required',
        'Designation'=>'required',
        'Gender'=>'required',
    );
    protected $messages=array(
        'CrpContractorFinalId'=>'CDB No. field is required',
        'Participant'=>'Participant field is required',
        'CIDNo'=>'CID No field is required',
        'Designation'=>'Designation field is required',
        'Gender'=>'Gender field is required',
    );
}