<?php
class forum extends BaseModel{
	protected $table="forum";
	protected $fillable=array('id','category_id','topic','content');
    protected $rules=array(
        'topic'=>'required',
        'content'=>'required'

    );
    protected $messages=array(
        'topic.required'=>'Topic field is Required',
        'content.required'=>'Content field is Required'
    );

}