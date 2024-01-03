<?php
class WebCDBDepartmentsModel extends BaseModel{
	protected $fillable = array('Id', 'DepartmentName','DepartmentId', 'DisplayOrder');
	protected $table="webcdbdepartments";
}