<?php
class MonitoringOfficeEquipmentModel extends BaseModel{
	public $table="crpmonitoringofficeequipment";
	protected $fillable=array('Id','CrpMonitoringOfficeId','CmnEquipmentId','RegistrationNo','Checked','CreatedBy','EditedBy','CreatedOn','EditedOn');
}