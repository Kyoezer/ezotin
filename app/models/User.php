<?php

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends BaseModel implements UserInterface, RemindableInterface {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'sysuser';
	protected $fillable = array('Id','FullName','username','password','Email','ContactNo','Status','CmnProcuringAgencyId','CreatedBy','EditedBy');
	protected $rules=array(
        'FullName'=>'required_with:username',
        'username'=>'required_with:FullName',
        'Email'=> 'email',
        'password'=>'required_with:IsInsert|confirmed',
    );
    protected $messages=array(
        'FullName.required'=>'Name of User field is required',
        'username.required'=>'Username field is required',
        'Email.email'=>'Username field should be an email address',
        'password.required_with'=>'Password field required',
        'password.confirmed'=>'Password and Confirm Password field didnot match'
    );
    public function scopeSysUser($query){
    	return $query->whereRaw('coalesce(status,0)!=0')->orderBy('FullName');
    }
    public function scopeUserWithRoleList($query){
    	return $query->leftJoin('sysuserrolemap as T1','sysuser.Id','=','T1.SysUserId')->leftJoin('sysrole as T2','T1.SysRoleId','=','T2.Id')->leftJoin('cmnprocuringagency as T3','T3.Id','=','sysuser.CmnProcuringAgencyId')->orderBy('T2.Name')->orderBy('sysuser.FullName')->orderBy('sysuser.username');
    }
	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password');

	/**
	 * Get the unique identifier for the user.
	 *
	 * @return mixed
	 */
	public function getAuthIdentifier()
	{
		return $this->getKey();
	}

	/**
	 * Get the password for the user.
	 *
	 * @return string
	 */
	public function getAuthPassword()
	{
		return $this->password;
	}

	/**
	 * Get the e-mail address where password reminders are sent.
	 *
	 * @return string
	 */
	public function getReminderEmail()
	{
		return $this->email;
	}
	public function getRememberToken(){
	    return $this->remember_token;
	}

	public function setRememberToken($value){
	    $this->remember_token = $value;
	}

	public function getRememberTokenName(){
	    return 'remember_token';
	}

}