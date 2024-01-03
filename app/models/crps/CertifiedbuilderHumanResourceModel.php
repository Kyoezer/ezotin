<?php
class CertifiedbuilderHumanResourceModel extends BaseModel{
	protected $table="crpcertifiedbuilderhumanresource";
	protected $fillable=array('Id','CrpCertifiedbuilderId','CmnSalutationId','CmnServiceTypeId','CIDNo','Name','Sex','CmnCountryId','CmnQualificationId','CmnTradeId','CmnDesignationId','JoiningDate','ShowInCertificate','IsPartnerOrOwner','Verified','Approved','CreatedBy','EditedBy','CreatedOn','EditedOn');
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
     public function scopeCertifiedbuilderPartnerHardList($query,$reference){
        return $query->where('IsPartnerOrOwner',1)->where('CrpCertifiedbuilderId',$reference);
    }
    public function scopeCertifiedbuilderPartner($query,$reference){
        return $query->join('cmncountry as T1','crpcertifiedbuilderhumanresource.CmnCountryId','=','T1.Id')
                            ->join('cmnlistitem as T2','crpcertifiedbuilderhumanresource.CmnSalutationId','=','T2.Id')
                            ->join('cmnlistitem as T3','crpcertifiedbuilderhumanresource.CmnDesignationId','=','T3.Id')
                            ->where('crpcertifiedbuilderhumanresource.CrpCertifiedbuilderId','=',$reference)
                            ->where('crpcertifiedbuilderhumanresource.IsPartnerOrOwner',1);
    }
    public function scopeCertifiedbuilderHumanResource($query,$reference){
        return $query->join('cmnlistitem as T1','T1.Id','=','crpcertifiedbuilderhumanresource.CmnSalutationId')
                    ->leftJoin('cmnlistitem as T2','T2.Id','=','crpcertifiedbuilderhumanresource.CmnQualificationId')     
                    ->leftJoin('cmnlistitem as T3','T3.Id','=','crpcertifiedbuilderhumanresource.CmnTradeId') 
                    ->leftJoin('cmnlistitem as T6','T6.Id','=','crpcertifiedbuilderhumanresource.CmnServiceTypeId')
                    ->join('cmnlistitem as T4','T4.Id','=','crpcertifiedbuilderhumanresource.CmnDesignationId')
                    ->join('cmncountry as T5','T5.Id','=','crpcertifiedbuilderhumanresource.CmnCountryId')
                    ->where('CrpCertifiedbuilderId',$reference)
                    ->where(function($query){
                        $query->where('IsPartnerOrOwner',0)
                            ->orWhere(DB::raw('coalesce(T4.ReferenceNo,0)'),101);
                    })
                    ->orderBy('T4.Name')
                    ->orderBy('crpcertifiedbuilderhumanresource.Name');
    }
    public function scopecertifiedbuilderHumanResourceHardListSingle($query,$reference){
        return $query->where('Id',$reference);
    }
    public function scopehumanResourceAttachments($query,$reference){
        return $query->join('crpcertifiedbuilderhumanresourceattachment as T1','T1.CrpCertifiedbuilderHumanResourceId','=','crpcertifiedbuilderhumanresource.Id')->where('crpcertifiedbuilderhumanresource.CrpCertifiedbuilderId',$reference);
    }
}