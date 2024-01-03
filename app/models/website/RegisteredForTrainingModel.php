<?php
class RegisteredForTrainingModel extends Eloquent{
	public $timestamps=false;
	protected $fillable = array('Id', 'FullName','CIDNoOfParticipant','Email','ContactNo','CDBNo','Agency','NameOfFirm','CmnContractorClassificationId','Designation','Qualification','Department','Dzongkhag','WebTrainingDetailsId','FilePath','venue');
	protected $table="webregisteredfortraining";
}