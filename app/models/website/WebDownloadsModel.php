<?php
class WebDownloadsModel extends BaseModel{
	protected $fillable = array('Id', 'FileName', 'Attachment');
	protected $table="webdownload";
}