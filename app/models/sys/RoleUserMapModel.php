<?php
class RoleUserMapModel extends BaseModel{
	protected $table="sysuserrolemap";
	protected $fillable=array('Id','SysUserId','SysRoleId','CreatedBy','EditedBy');
}