<?php
class WebUsersModel extends BaseModel{
	protected $fillable = array('Id', 'Username','Password','CDBSecretariatId');
	protected $table="webuser";
}