<?php
class SpecializedfirmEquipmentFinalModel extends BaseModel{
	protected $table="crpspecializedtradeequipmentfinal";
	protected $fillable=array('Id','CrpSpecializedtradeFinalId','CmnEquipmentId','RegistrationNo','SerialNo','Quantity','Verified','Approved','CreatedBy','EditedBy','CreatedOn','EditedOn');
    protected $rules=array(
        'CmnEquipmentId'=>'required'
    );
    protected $messages=array(
        'CmnEquipmentId.required'=>'Equipment field is required'
    );
    public function scopeSpecializedtradeEquipment($query,$reference){
        return $query->join('cmnequipment as T1','T1.Id','=','crpspecializedtradeequipmentfinal.CmnEquipmentId')
                    ->where('CrpSpecializedtradeFinalId',$reference);
    }
    public function scopeSpecializedtradeEquipmentHardListSingle($query,$reference){
        return $query->where('Id',$reference);
    }
    public function scopeEquipmentAttachments($query,$reference){
        return $query->join('crpspecializedtradeequipmentattachment as T1','T1.CrpSpecializedtradeEquipmentId','=','crpspecializedtradeequipmentfinal.Id')->where('crpspecializedtradeequipmentfinal.CrpSpecializedtradeFinalId',$reference);
    }
}