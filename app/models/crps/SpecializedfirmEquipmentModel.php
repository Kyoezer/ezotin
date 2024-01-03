<?php
class SpecializedfirmEquipmentModel extends BaseModel{
	protected $table="crpspecializedtradeequipment";
	protected $fillable=array('Id','CrpSpecializedtradeId','CmnEquipmentId','RegistrationNo','SerialNo','Quantity','Verified','Approved','CreatedBy','EditedBy','CreatedOn','EditedOn');
    protected $rules=array(
        'CmnEquipmentId'=>'required',
    );
    protected $messages=array(
        'CmnEquipmentId.required'=>'Equipment field is required',
    );
    public function scopeSpecializedtradeEquipment($query,$reference){
        return $query->join('cmnequipment as T1','T1.Id','=','crpspecializedtradeequipment.CmnEquipmentId')
                    ->where('CrpSpecializedtradeId',$reference);
    }
    public function scopeSpecializedtradeEquipmentHardListSingle($query,$reference){
        return $query->where('Id',$reference);
    }
    public function scopeEquipmentAttachments($query,$reference){
        return $query->join('crpspecializedtradeequipmentattachment as T1','T1.CrpSpecializedtradeEquipmentId','=','crpspecializedtradeequipment.Id')->where('crpspecializedtradeequipment.CrpSpecializedtradeId',$reference);
    }
}