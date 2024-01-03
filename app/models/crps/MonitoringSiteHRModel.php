<?php
class MonitoringSiteHRModel extends BaseModel{
	public $table="crpmonitoringsitehumanresource";
	protected $fillable=array('Id','CrpMonitoringSiteId','CIDNo','Name','Qualification','CmnDesignationId','Checked','CreatedBy','EditedBy','CreatedOn','EditedOn');
}