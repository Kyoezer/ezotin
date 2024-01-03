<?php
class WedTenderDownloadModel extends BaseModel{
	protected $fillable = array('Id', 'TenderId', 'Email', 'PhoneNo');
	protected $table = "webtenderdownload";
}