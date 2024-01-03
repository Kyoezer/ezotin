<?php
class ContractorWorkClassificationMonitoringModel extends BaseModel{
	protected $table="crpcontractorworkclassificationmonitoring";
	protected $fillable=array('Id','CrpMonitoringOfficeId','CmnProjectCategoryId','CmnClassificationId','CreatedBy','EditedBy','CreatedOn','EditedOn');
}