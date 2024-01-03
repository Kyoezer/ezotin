<?php
class WebCDBDivisionModel extends BaseModel{
	protected $fillable = array('Id', 'DivisionName','DepartmentId','DisplayOrder');
	protected $table="webcdbdivision";
}