<?php
class ContractorEquipmentFinalModel extends BaseModel{
	protected $table="crpcontractorequipmentfinal";
	protected $fillable=array('Id','CrpContractorFinalId','CmnEquipmentId','RegistrationNo','SerialNo','ModelNo','Quantity','Verified','Approved','CreatedBy','EditedBy','CreatedOn','EditedOn');
    protected $rules=array(
        'CmnEquipmentId'=>'required'
    );
    protected $messages=array(
        'CmnEquipmentId.required'=>'Equipment field is required'
    );
    public function scopeContractorEquipment($query,$reference){
        return $query->join('cmnequipment as T1','T1.Id','=','crpcontractorequipmentfinal.CmnEquipmentId')
                    ->where('CrpContractorFinalId',$reference)
                    ->orderBy('Name');
    }
    public function scopeContractorEquipmentHardListSingle($query,$reference){
        return $query->where('Id',$reference);
    }
    public function scopeEquipmentAttachments($query,$reference){
        return $query->join('crpcontractorequipmentattachmentfinal as T1','T1.CrpContractorEquipmentFinalId','=','crpcontractorequipmentfinal.Id')->where('crpcontractorequipmentfinal.CrpContractorFinalId',$reference);
    }
}