<?php
class CountryModel extends BaseModel{
	protected $table="cmncountry";
	protected $fillable=array('Id','Code','Name','AlternateName','Nationality','CreatedBy','EditedBy','CreatedOn','EditedOn');
    protected $rules=array(
        'Code'=>'required',
        'Name'=>'required',
        'Nationality'=>'required',
    );
    protected $messages=array(
        'Code.required'=>'Code field is required',
        'Name.required'=>'Name field is required',
        'Nationality.required'=>'Nationality field is required',
    );
    public function scopeCountry($query){
        return $query->orderBy('Name');
    }
}