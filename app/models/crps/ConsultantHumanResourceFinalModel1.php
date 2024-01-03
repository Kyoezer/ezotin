<?php
class ConsultantHumanResourceFinalModel extends BaseModel{
	protected $table="crpconsultanthumanresourcefinal";
	protected $fillable=array('Id','CrpConsultantFinalId','CmnSalutationId','CmnServiceTypeId','CIDNo','Name','Sex','CmnCountryId','CmnQualificationId','CmnTradeId','CmnDesignationId','ShowInCertificate','IsPartnerOrOwner','JoiningDate','CreatedBy','EditedBy','CreatedOn','EditedOn');
    protected $rules=array(
        'CmnSalutationId'=>'required',
        'CIDNo'=>'required',
        'Name'=>'required',
        'Sex'=>'required',
        'CmnCountryId'=>'required',
        'CmnDesignationId'=>'required',
    );
    protected $messages=array(
        'CmnSalutationId.required'=>'Salutation field is required',
        'CIDNo.required'=>'CID No/Work Permit No. field is required',
        'Name.required'=>'Name field is required',
        'Sex.required'=>'Sex field is required',
        'CmnCountryId.required'=>'Country field is required',
        'CmnDesignationId.required'=>'Designation field is required',
    );
     public function scopeConsultantPartnerHardList($query,$reference){
        return $query->where('IsPartnerOrOwner',1)->where('CrpConsultantFinalId',$reference);
    }
    public function scopeConsultantPartner($query,$reference){
        return $query->join('cmncountry as T1','crpconsultanthumanresourcefinal.CmnCountryId','=','T1.Id')
                            ->leftJoin('cmnlistitem as T2','crpconsultanthumanresourcefinal.CmnSalutationId','=','T2.Id')
                            ->leftJoin('cmnlistitem as T3','crpconsultanthumanresourcefinal.CmnDesignationId','=','T3.Id')
                            ->where('crpconsultanthumanresourcefinal.CrpConsultantFinalId','=',$reference)
                            ->where('crpconsultanthumanresourcefinal.IsPartnerOrOwner',1);
    }
    public function scopeConsultantHumanResource($query,$reference){
        return $query->join('cmnlistitem as T1','T1.Id','=','crpconsultanthumanresourcefinal.CmnSalutationId')
                     ->leftJoin('cmnlistitem as T4','T4.Id','=','crpconsultanthumanresourcefinal.CmnDesignationId')
                    ->join('cmncountry as T5','T5.Id','=','crpconsultanthumanresourcefinal.CmnCountryId')
                    ->leftJoin('cmnlistitem as T2','T2.Id','=','crpconsultanthumanresourcefinal.CmnQualificationId')     
                    ->leftJoin('cmnlistitem as T3','T3.Id','=','crpconsultanthumanresourcefinal.CmnTradeId') 
                    ->leftJoin('cmnlistitem as T6','T3.Id','=','crpconsultanthumanresourcefinal.CmnServiceTypeId')
                    ->where('CrpConsultantFinalId',$reference)
                    ->where('IsPartnerOrOwner',0)
                    ->orderBy('crpconsultanthumanresourcefinal.Name');
    }
    public function scopeConsultantHumanResourceHardListSingle($query,$reference){
        return $query->where('Id',$reference);
    }
    public function scopeHumanResourceAttachments($query,$reference){
        return $query->join('crpconsultanthumanresourceattachmentfinal as T1','T1.CrpConsultantHumanResourceFinalId','=','crpconsultanthumanresourcefinal.Id')->where('crpconsultanthumanresourcefinal.CrpConsultantFinalId',$reference);
    }
}