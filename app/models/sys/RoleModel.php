<?php
class RoleModel extends BaseModel{
    protected $table="sysrole";
    protected $fillable=array('Id','Name','Description','CreatedBy','EditedBy');
	protected $rules=array(
        'Name'=>'required',
    );
    protected $messages=array(
        'Name.required'=>'Name field is required',
    );
    public function scopeRoleListAll($query){
    	return $query->orderBy('Name');
    }
}