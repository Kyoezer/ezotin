<?php
class FAQModel extends BaseModel{
	protected $fillable = array('Id', 'Question', 'Answer');
	protected $table="webfrequentlyaskedquestion";
}