<?php
class ContractorHumanResourceModel extends BaseModel{
	protected $table="crpcontractorhumanresource";
	protected $fillable=array('Id','CrpContractorId','CmnSalutationId','CmnServiceTypeId','CIDNo','Name','Sex','CmnCountryId','CmnQualificationId','CmnTradeId','CmnDesignationId','JoiningDate','ShowInCertificate','IsPartnerOrOwner','Verified','Approved','CreatedBy','EditedBy','CreatedOn','EditedOn');
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
     public function scopeContractorPartnerHardList($query,$reference){
        return $query->where('IsPartnerOrOwner',1)->where('CrpContractorId',$reference);
    }
    public function scopeContractorPartner($query,$reference){
        return $query->join('cmncountry as T1','crpcontractorhumanresource.CmnCountryId','=','T1.Id')
                            ->join('cmnlistitem as T2','crpcontractorhumanresource.CmnSalutationId','=','T2.Id')
                            ->join('cmnlistitem as T3','crpcontractorhumanresource.CmnDesignationId','=','T3.Id')
                            ->where('crpcontractorhumanresource.CrpContractorId','=',$reference)
                            ->where('crpcontractorhumanresource.IsPartnerOrOwner',1);
    }
    public function scopeContractorHumanResource($query,$reference){
        return $query->join('cmnlistitem as T1','T1.Id','=','crpcontractorhumanresource.CmnSalutationId')
                    ->leftJoin('cmnlistitem as T2','T2.Id','=','crpcontractorhumanresource.CmnQualificationId')     
                    ->leftJoin('cmnlistitem as T3','T3.Id','=','crpcontractorhumanresource.CmnTradeId') 
                    ->leftJoin('cmnlistitem as T6','T6.Id','=','crpcontractorhumanresource.CmnServiceTypeId')
                    ->join('cmnlistitem as T4','T4.Id','=','crpcontractorhumanresource.CmnDesignationId')
                    ->join('cmncountry as T5','T5.Id','=','crpcontractorhumanresource.CmnCountryId')
                    ->where('CrpContractorId',$reference)
                    ->where(function($query){
                        $query->where('IsPartnerOrOwner',0)
                            ->orWhere(DB::raw('coalesce(T4.ReferenceNo,0)'),101);
                    })
                    ->orderBy('T4.Name')
                    ->orderBy('crpcontractorhumanresource.Name');
    }
    public function scopeContractorHumanResourceHardListSingle($query,$reference){
        return $query->where('Id',$reference);
    }
    public function scopeHumanResourceAttachments($query,$reference){
        return $query->join('crpcontractorhumanresourceattachment as T1','T1.CrpContractorHumanResourceId','=','crpcontractorhumanresource.Id')->where('crpcontractorhumanresource.CrpContractorId',$reference);
    }
}