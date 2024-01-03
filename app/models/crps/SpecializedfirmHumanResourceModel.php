<?php
class SpecializedfirmHumanResourceModel extends BaseModel{
	protected $table="crpspecializedtradehumanresource";
	protected $fillable=array('Id','CrpSpecializedtradeId','CmnSalutationId','CmnServiceTypeId','CIDNo','Name','Sex','CmnCountryId','CmnQualificationId','CmnTradeId','CmnDesignationId','JoiningDate','ShowInCertificate','IsPartnerOrOwner','Verified','Approved','CreatedBy','EditedBy','CreatedOn','EditedOn');
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
     public function scopeSpecializedtradePartnerHardList($query,$reference){
        return $query->where('IsPartnerOrOwner',1)->where('CrpSpecializedtradeId',$reference);
    }
    public function scopeSpecializedtradePartner($query,$reference){
        return $query->join('cmncountry as T1','crpspecializedtradehumanresource.CmnCountryId','=','T1.Id')
                            ->join('cmnlistitem as T2','crpspecializedtradehumanresource.CmnSalutationId','=','T2.Id')
                            ->join('cmnlistitem as T3','crpspecializedtradehumanresource.CmnDesignationId','=','T3.Id')
                            ->where('crpspecializedtradehumanresource.CrpSpecializedtradeId','=',$reference)
                            ->where('crpspecializedtradehumanresource.IsPartnerOrOwner',1);
    }
    public function scopeSpecializedtradeHumanResource($query,$reference){
        return $query->join('cmnlistitem as T1','T1.Id','=','crpspecializedtradehumanresource.CmnSalutationId')
                    ->leftJoin('cmnlistitem as T2','T2.Id','=','crpspecializedtradehumanresource.CmnQualificationId')     
                    ->leftJoin('cmnlistitem as T3','T3.Id','=','crpspecializedtradehumanresource.CmnTradeId') 
                    ->leftJoin('cmnlistitem as T6','T6.Id','=','crpspecializedtradehumanresource.CmnServiceTypeId')
                    ->join('cmnlistitem as T4','T4.Id','=','crpspecializedtradehumanresource.CmnDesignationId')
                    ->join('cmncountry as T5','T5.Id','=','crpspecializedtradehumanresource.CmnCountryId')
                    ->where('CrpSpecializedtradeId',$reference)
                    ->where(function($query){
                        $query->where('IsPartnerOrOwner',0)
                            ->orWhere(DB::raw('coalesce(T4.ReferenceNo,0)'),101);
                    })
                    ->orderBy('T4.Name')
                    ->orderBy('crpspecializedtradehumanresource.Name');
    }
    public function scopeSpecializedtradeHumanResourceHardListSingle($query,$reference){
        return $query->where('Id',$reference);
    }
    public function scopeHumanResourceAttachments($query,$reference){
        return $query->join('crpspecializedtradehumanresourceattachment as T1','T1.CrpSpecializedtradeHumanResourceId','=','crpspecializedtradehumanresource.Id')->where('crpspecializedtradehumanresource.CrpSpecializedtradeId',$reference);
    }
}