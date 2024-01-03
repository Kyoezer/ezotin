<?php
class MonitoringOfficeHRModel extends BaseModel{
	public $table="crpmonitoringofficehumanresource";
	protected $fillable=array('Id','CrpMonitoringOfficeId','CIDNo','Name','Sex','CmnDesignationId','Checked','CreatedBy','EditedBy','CreatedOn','EditedOn');
}