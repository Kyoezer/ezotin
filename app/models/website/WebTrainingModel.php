<?php
class WebTrainingModel extends BaseModel{
	protected $table="webtrainingdetails";
	protected $fillable = array('Id', 'TrainingTypeId','MaxParticipants','ContractorsExpiryDate', 'TrainingTitle','TrainingDescription','StartDate','EndDate','TrainingVenue','TrainingTime','ContactPerson', 'Hotline', 'LastDateForRegistration','ShowInMarquee');
	protected $rules=array(
		'TrainingTypeId'=>'required',
		'TrainingDescription'=>'required',
		'TrainingTitle'=>'required',
		'StartDate'=>'required',
		'EndDate'=>'required',
		'TrainingVenue'=>'required',
		'TrainingTime'=>'required',
		'Hotline'=>'required',
		'LastDateForRegistration'=>'required',
	);
	protected $messages=array(
		'TrainingTypeId.required'=>'Training Type field is required',
		'TrainingDescription.required'=>'Training Description field is required',
		'TrainingTitle.required'=>'Training Title field is required',
		'StartDate.required'=>'Start Date field is required',
		'EndDate.required'=>'End Date field is required',
		'TrainingVenue.required'=>'Training Venue field is required',
		'TrainingTime.required'=>'Training Time field is required',
		'Hotline.required'=>'Training Time field is required',
		'LastDateForRegistration.required'=>'Training Time field is required',
	);
}