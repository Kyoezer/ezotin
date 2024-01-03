<?php
class FeedbackModel extends BaseModel{
	protected $fillable = array('Id', 'Name', 'Email','Address','Content');
	protected $table="webfeedback";
	protected $rules=array(
		'Name'=>'required',
		'Email'=>'required|email',
		'Address'=>'required',
		'Content'=>'required',
	);
	protected $messages=array(
		'Name.required'=>'Name field is required',
		'Email.required'=>'Email field is required',
		'Email.email'=>'Please enter a valid email address',
		'Address.required'=>'Address field is required',
		'Content.required'=>'Content field is required',
	)
}