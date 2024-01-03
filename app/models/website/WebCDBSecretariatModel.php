<?php
class WebCDBSecretariatModel extends BaseModel{
	protected $fillable = array('Id', 'FullName', 'DesignationId','DivisionId', 'DepartmentId', 'Email', 'PhoneNo', 'ExtensionNo', 'IsDirectorGeneral', 'DisplayOrder');
	protected $table="webcdbsecretariat";
	protected $rules = array(
		'FullName'=>'required',
		'DesignationId'=>'required',
		'DepartmentId'=>'required',
//		'Email'=>'required|email',
		'PhoneNo'=>'required',
		'ExtensionNo'=>'required',
        'ImageUpload'=>'image|max:2197152'
    );
     protected $messages=array(
     	'FullName.required'=>'Full Name field is required',
     	'DesignationId.required'=>'Designation field is required',
     	'DepartmentId.required'=>'Department field is required',
     	'Email.required'=>'Email field is required',
     	'Email.email'=>'Email field should be a valid email address. For example example@example.com',
     	'PhoneNo.required'=>'Contact No. field is required',
     	'ExtensionNo.required'=>'Extension No. field is required',
        'ImageUpload.image'=>'The file you are trying to upload should be a Image. The size of the file should not be more than 2 MB.',
    );
}