<?php
class WebPostPageModel extends BaseModel{
	protected $fillable = array('Id', 'Title', 'Content', 'ImageUpload', 'Attachment', 'ParentId', 'DisplayOrder', 'MenuRoute', 'ShowInWebsite');
	protected $table="webpostpage";
}