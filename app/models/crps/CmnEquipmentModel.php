<?php
class CmnEquipmentModel extends BaseModel{
	protected $table="cmnequipment";
	protected $fillable=array('Id','Code','Name','IsRegistered','VehicleType', 'CreatedBy','EditedBy','CreatedOn','EditedOn');
    protected $rules=array(
        'Name'=>'required',
        'IsRegistered'=>'required',
    );
    protected $messages=array(
        'IsRegistered.required'=>'Is registered field field is required',
        'Name.required'=>'Name field is required',
    );
    public function scopeEquipment($query){
        return $query->orderBy('Name');
    }
}