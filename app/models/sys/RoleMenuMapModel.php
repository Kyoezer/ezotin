<?php
class RoleMenuMapModel extends BaseModel{
    protected $table="sysrolemenumap";
    protected $fillable=array('Id','SysRoleId','SysMenuId','PageView','CreatedBy','EditedBy');
}