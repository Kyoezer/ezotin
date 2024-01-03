<?php
class MonitoringOfficeModel extends BaseModel{
	public $table="crpmonitoringoffice";
	protected $fillable=array('Id','Year','CrpContractorFinalId','MonitoringDate','HasOfficeEstablishment','HasSignBoard','CannotBeContacted','DeceivingOnLocationChange','MonitoringStatus','Remarks','CreatedBy','EditedBy','CreatedOn','EditedOn');
}