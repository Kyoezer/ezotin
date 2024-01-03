<?php
class MonitoringSiteEquipmentModel extends BaseModel{
	public $table="crpmonitoringsiteequipment";
	protected $fillable=array('Id','CrpMonitoringSiteId','CmnEquipmentId','RegistrationNo','Quantity','Checked','CreatedBy','EditedBy','CreatedOn','EditedOn');
}