<?php
class TrainingModel extends BaseModel{
	protected $table="crpcontractortraining";
	protected $fillable=array('Id','CmnTrainingTypeId','CmnTrainingModuleId','TrainingFromDate','TrainingToDate','CreatedBy','EditedBy','CreatedOn','EditedOn');
    protected $rules=array(
        'CmnTrainingTypeId'=>'required',
        'TrainingFromDate'=>'required',
        'TrainingToDate'=>'required',
    );
    protected $messages=array(
        'CmnTrainingTypeId.required'=>'Training Type field is required',
        'TrainingFromDate.required'=>'Training From Date field is required',
        'TrainingToDate.required'=>'Training From Date field is required',
    );
}