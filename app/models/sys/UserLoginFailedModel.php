<?php
class UserLoginFailedModel extends Eloquent{
	public $timestamps=false;
	protected $table="sysloginfailurelog";
	protected $fillable=array('Id','UserName','Password','IpAddress','AttemptDate');
}