<?php
class WebRegistrationPageModel extends BaseModel{
	protected $table="webregistrationpagecontent";
	protected $fillable = array('Id','Type', 'Content','CreatedBy','EditedBy');
}