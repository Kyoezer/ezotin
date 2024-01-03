<?php
class UserLogModel extends Eloquent{
	protected $table="sysuserlog";
	public $timestamps=false;
	protected $fillable=array('Id','SysUserId','IpAddress','LoggedInDate','LoggedOutDate');
}