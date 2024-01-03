<?php
class CertifiedbuilderEquipmentFinalModel extends BaseModel{
	protected $table="crpcertifiedbuilderequipmentfinal";
	protected $fillable=array('Id','CrpCertifiedBuilderFinalId','CmnEquipmentId','RegistrationNo','SerialNo','Quantity','Verified','Approved','CreatedBy','EditedBy','CreatedOn','EditedOn');
    protected $rules=array(
        'CmnEquipmentId'=>'required'
    );
    protected $messages=array(
        'CmnEquipmentId.required'=>'Equipment field is required'
    );
    public function scopeCertifiedbuilderEquipment($query,$reference){
        return $query->join('cmnequipment as T1','T1.Id','=','crpcertifiedbuilderequipmentfinal.CmnEquipmentId')
                    ->where('CrpCertifiedBuilderFinalId',$reference);
    }
    public function scopeCertifiedbuilderEquipmentHardListSingle($query,$reference){
        return $query->where('Id',$reference);
    }
    public function scopeEquipmentAttachments($query,$reference){
        return $query->join('crpcertifiedbuilderequipmentattachment as T1','T1.CrpCertifiedBuilderEquipmentId','=','crpcertifiedbuilderequipmentfinal.Id')->where('crpcertifiedbuilderequipmentfinal.CrpCertifiedBuilderFinalId',$reference);
    }
}