<?php
class CertifiedbuilderEquipmentModel extends BaseModel{
	protected $table="crpcertifiedbuilderequipment";
	protected $fillable=array('Id','CrpCertifiedBuilderId','CmnEquipmentId','RegistrationNo','SerialNo','Quantity','Verified','Approved','CreatedBy','EditedBy','CreatedOn','EditedOn');
    protected $rules=array(
        'CmnEquipmentId'=>'required',
    );
    protected $messages=array(
        'CmnEquipmentId.required'=>'Equipment field is required',
    );
    public function scopeCertifiedBuilderEquipment($query,$reference){
        return $query->join('cmnequipment as T1','T1.Id','=','crpcertifiedbuilderequipment.CmnEquipmentId')
                    ->where('CrpCertifiedBuilderId',$reference);
    }
    public function scopeCertifiedBuilderEquipmentHardListSingle($query,$reference){
        return $query->where('Id',$reference);
    }
    public function scopeEquipmentAttachments($query,$reference){
        return $query->join('crpcertifiedbuilderequipmentattachment as T1','T1.CrpCertifiedBuilderEquipmentId','=','crpcertifiedbuilderequipment.Id')->where('crpcertifiedbuilderequipment.CrpCertifiedBuilderId',$reference);
    }
}