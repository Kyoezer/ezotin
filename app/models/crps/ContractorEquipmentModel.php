<?php
class ContractorEquipmentModel extends BaseModel{
	protected $table="crpcontractorequipment";
	protected $fillable=array('Id','CrpContractorId','CmnEquipmentId','RegistrationNo','SerialNo','ModelNo','Quantity','Verified','Approved','CreatedBy','EditedBy','CreatedOn','EditedOn');
    protected $rules=array(
        'CmnEquipmentId'=>'required'
    );
    protected $messages=array(
        'CmnEquipmentId.required'=>'Equipment field is required'
    );
    public function scopeContractorEquipment($query,$reference){
        return $query->join('cmnequipment as T1','T1.Id','=','crpcontractorequipment.CmnEquipmentId')
                    ->where('CrpContractorId',$reference);
    }
    public function scopeContractorEquipmentHardListSingle($query,$reference){
        return $query->where('Id',$reference);
    }
    public function scopeEquipmentAttachments($query,$reference){
        return $query->join('crpcontractorequipmentattachment as T1','T1.CrpContractorEquipmentId','=','crpcontractorequipment.Id')->where('crpcontractorequipment.CrpContractorId',$reference);
    }
}