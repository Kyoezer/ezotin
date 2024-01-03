<?php
class AuditTrailEtoolCinetModel extends Eloquent{
	public $timestamps=false;
    protected $table="sysaudittrailetoolcinet";
    protected $fillable=array('Id','SysUserId','ActionDate','WorkId','MessageDisplayed','Remarks','IndexAction');
}