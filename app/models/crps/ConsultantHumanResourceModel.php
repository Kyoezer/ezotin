<?php
class ConsultantHumanResourceModel extends BaseModel{
	protected $table="crpconsultanthumanresource";
	protected $fillable=array('Id','CrpConsultantId','CmnSalutationId','CmnServiceTypeId','CIDNo','Name','Sex','CmnCountryId','CmnQualificationId','CmnTradeId','CmnDesignationId','JoiningDate','ShowInCertificate','IsPartnerOrOwner','Verified','Approved','CreatedBy','EditedBy','CreatedOn','EditedOn');
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
        return $query->where('IsPartnerOrOwner',1)->where('CrpConsultantId',$reference);
    }
    public function scopeConsultantPartner($query,$reference){
        return $query->join('cmncountry as T1','crpconsultanthumanresource.CmnCountryId','=','T1.Id')
                            ->join('cmnlistitem as T2','crpconsultanthumanresource.CmnSalutationId','=','T2.Id')
                            ->join('cmnlistitem as T3','crpconsultanthumanresource.CmnDesignationId','=','T3.Id')
                            ->where('crpconsultanthumanresource.CrpConsultantId','=',$reference)
                            ->where('crpconsultanthumanresource.IsPartnerOrOwner',1);
    }
    public function scopeConsultantHumanResource($query,$reference){
        return $query->join('cmnlistitem as T1','T1.Id','=','crpconsultanthumanresource.CmnSalutationId')
                    ->leftJoin('cmnlistitem as T2','T2.Id','=','crpconsultanthumanresource.CmnQualificationId')     
                    ->leftJoin('cmnlistitem as T3','T3.Id','=','crpconsultanthumanresource.CmnTradeId') 
                    ->leftJoin('cmnlistitem as T6','T6.Id','=','crpconsultanthumanresource.CmnServiceTypeId')
                    ->join('cmnlistitem as T4','T4.Id','=','crpconsultanthumanresource.CmnDesignationId')
                    ->join('cmncountry as T5','T5.Id','=','crpconsultanthumanresource.CmnCountryId')
                    ->where('CrpConsultantId',$reference)
                    ->where(function($query){
                        $query->where('IsPartnerOrOwner',0)
                            ->orWhere(DB::raw('coalesce(T4.ReferenceNo,0)'),101);
                    })
                    ->orderBy('T4.Name')
                    ->orderBy('crpconsultanthumanresource.Name');
    }
    public function scopeConsultantHumanResourceHardListSingle($query,$reference){
        return $query->where('Id',$reference);
    }
    public function scopeHumanResourceAttachments($query,$reference){
        return $query->join('crpconsultanthumanresourceattachment as T1','T1.CrpConsultantHumanResourceId','=','crpconsultanthumanresource.Id')->where('crpconsultanthumanresource.CrpConsultantId',$reference);
    }
}