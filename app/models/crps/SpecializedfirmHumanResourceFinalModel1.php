<?php
class SpecializedfirmHumanResourceFinalModel extends BaseModel{
	protected $table="crpspecializedtradehumanresourcefinal";
	protected $fillable=array('Id','CrpSpecializedtradeFinalId','CmnSalutationId','CmnServiceTypeId','CIDNo','Name','Sex','CmnCountryId','CmnQualificationId','CmnTradeId','CmnDesignationId','JoiningDate','ShowInCertificate','IsPartnerOrOwner','CreatedBy','EditedBy','CreatedOn','EditedOn');
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
        return $query->where('IsPartnerOrOwner',1)->where('CrpSpecializedtradeFinalId',$reference);
    }
    public function scopeSpecializedtradePartner($query,$reference){
        return $query->join('cmncountry as T1','crpspecializedtradehumanresourcefinal.CmnCountryId','=','T1.Id')
                            ->join('cmnlistitem as T2','crpspecializedtradehumanresourcefinal.CmnSalutationId','=','T2.Id')
                            ->leftJoin('cmnlistitem as T3','crpspecializedtradehumanresourcefinal.CmnDesignationId','=','T3.Id')
                            ->where('crpspecializedtradehumanresourcefinal.CrpSpecializedtradeFinalId','=',$reference)
                            ->where('crpspecializedtradehumanresourcefinal.IsPartnerOrOwner',1);
    }
    public function scopeSpecializedtradeHumanResource($query,$reference){
        return $query->join('cmnlistitem as T1','T1.Id','=','crpspecializedtradehumanresourcefinal.CmnSalutationId')
                     ->join('cmnlistitem as T4','T4.Id','=','crpspecializedtradehumanresourcefinal.CmnDesignationId')   
                    ->join('cmncountry as T5','T5.Id','=','crpspecializedtradehumanresourcefinal.CmnCountryId')
                    ->leftJoin('cmnlistitem as T2','T2.Id','=','crpspecializedtradehumanresourcefinal.CmnQualificationId')     
                    ->leftJoin('cmnlistitem as T3','T3.Id','=','crpspecializedtradehumanresourcefinal.CmnTradeId') 
                    ->leftJoin('cmnlistitem as T6','T6.Id','=','crpspecializedtradehumanresourcefinal.CmnServiceTypeId')
                    ->where('CrpSpecializedtradeFinalId',$reference)
                    ->where(function($query){
                        $query->where('IsPartnerOrOwner',0)
                              ->orWhere(DB::raw('coalesce(T4.ReferenceNo,0)'),101);
                    })
                    ->orderBy('crpspecializedtradehumanresourcefinal.IsPartnerOrOwner')
                    ->orderBy('crpspecializedtradehumanresourcefinal.Name');
    }
    public function scopeSpecializedtradeHumanResourceHardListSingle($query,$reference){
        return $query->where('Id',$reference);
    }
    public function scopeHumanResourceAttachments($query,$reference){
        return $query->join('crpspecializedtradehumanresourceattachmentfinal as T1','T1.CrpSpecializedtradeHumanResourceFinalId','=','crpspecializedtradehumanresourcefinal.Id')->where('crpspecializedtradehumanresourcefinal.CrpSpecializedtradeFinalId',$reference);
    }
}