<?php
class WebMenuManagementModel extends BaseModel{
	protected $fillable = array('Id', 'MenuRoute', 'MenuTitle', 'DisplayOrder', 'Description', 'ShowInWebsite');
	protected $table="webmenu";
}