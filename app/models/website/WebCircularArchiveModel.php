<?php
class WebCircularArchiveModel extends BaseModel{
	protected $fillable = array('Id', 'Title', 'CircularTypeId', 'Content', 'ImageUpload', 'Attachment');
	protected $table="webcirculararchive";
}