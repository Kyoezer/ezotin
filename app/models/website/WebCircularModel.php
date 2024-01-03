<?php
class WebCircularModel extends BaseModel{
	protected $fillable = array('Id', 'Title', 'CircularTypeId', 'Content', 'ImageUpload', 'Attachment', 'Featured','DisplayOrder');
	protected $table="webcircular";
}