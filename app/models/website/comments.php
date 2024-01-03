<?php
class comments extends BaseModel{
	protected $fillable = array('id', 'forum_id','comments','name');
	protected $table="comments";
}