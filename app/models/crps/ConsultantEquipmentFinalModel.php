<?php
class ConsultantEquipmentFinalModel extends BaseModel{
	protected $table="crpconsultantequipmentfinal";
	protected $fillable=array('Id','CrpConsultantFinalId','CmnEquipmentId','RegistrationNo','SerialNo','ModelNo','Quantity','Verified','Approved','CreatedBy','EditedBy','CreatedOn','EditedOn');
    protected $rules=array(
        'CmnEquipmentId'=>'required'
    );
    protected $messages=array(
        'CmnEquipmentId.required'=>'Equipment field is required'
    );
    public function scopeConsultantEquipment($query,$reference){
        return $query->join('cmnequipment as T1','T1.Id','=','crpconsultantequipmentfinal.CmnEquipmentId')
                    ->where('CrpConsultantFinalId',$reference);
    }
    public function scopeConsultantEquipmentHardListSingle($query,$reference){
        return $query->where('Id',$reference);
    }
    public function scopeEquipmentAttachments($query,$reference){
        return $query->join('crpconsultantequipmentattachment as T1','T1.CrpConsultantEquipmentId','=','crpconsultantequipmentfinal.Id')->where('crpconsultantequipmentfinal.CrpConsultantFinalId',$reference);
    }
}