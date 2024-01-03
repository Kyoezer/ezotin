<?php
class WebVisitorsModel extends BaseModel{
	protected $fillable = array('Id', 'IPAddress');
	protected $table="webvisitors";
}