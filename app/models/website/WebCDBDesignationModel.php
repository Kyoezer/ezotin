<?php
class WebCDBDesignationModel extends BaseModel{
	protected $fillable = array('Id', 'DesignationName', 'DisplayOrder');
	protected $table="webcdbdesignations";
}