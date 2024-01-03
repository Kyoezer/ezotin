<?php
class ContractorHumanResourceFinalModel extends BaseModel{
	protected $table="crpcontractorhumanresourcefinal";
	protected $fillable=array('Id','CrpContractorFinalId','CmnSalutationId','CmnServiceTypeId','CIDNo','Name','Sex','CmnCountryId','CmnQualificationId','CmnTradeId','CmnDesignationId','JoiningDate','ShowInCertificate','IsPartnerOrOwner','CreatedBy','EditedBy','CreatedOn','EditedOn');
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
        return $query->where('IsPartnerOrOwner',1)->where('CrpContractorFinalId',$reference);
    }
    public function scopeContractorPartner($query,$reference){
        return $query->join('cmncountry as T1','crpcontractorhumanresourcefinal.CmnCountryId','=','T1.Id')
                            ->join('cmnlistitem as T2','crpcontractorhumanresourcefinal.CmnSalutationId','=','T2.Id')
                            ->leftJoin('cmnlistitem as T3','crpcontractorhumanresourcefinal.CmnDesignationId','=','T3.Id')
                            ->where('crpcontractorhumanresourcefinal.CrpContractorFinalId','=',$reference)
                            ->where('crpcontractorhumanresourcefinal.IsPartnerOrOwner',1);
    }
    public function scopeContractorHumanResource($query,$reference){
        return $query->join('cmnlistitem as T1','T1.Id','=','crpcontractorhumanresourcefinal.CmnSalutationId')
                     ->join('cmnlistitem as T4','T4.Id','=','crpcontractorhumanresourcefinal.CmnDesignationId')   
                    ->join('cmncountry as T5','T5.Id','=','crpcontractorhumanresourcefinal.CmnCountryId')
                    ->leftJoin('cmnlistitem as T2','T2.Id','=','crpcontractorhumanresourcefinal.CmnQualificationId')     
                    ->leftJoin('cmnlistitem as T3','T3.Id','=','crpcontractorhumanresourcefinal.CmnTradeId') 
                    ->leftJoin('cmnlistitem as T6','T6.Id','=','crpcontractorhumanresourcefinal.CmnServiceTypeId')
                    ->leftJoin('crpengineerfinal as T10','T10.CIDNo','=','crpcontractorhumanresourcefinal.CIDNo')
                    ->leftJoin('crparchitectfinal as T11','T11.CIDNo','=','crpcontractorhumanresourcefinal.CIDNo')
                    ->leftJoin('crpsurveyfinal as T12','T12.CIDNo','=','crpcontractorhumanresourcefinal.CIDNo')
                    ->leftJoin('crpspecializedtradefinal as T13','T13.CIDNo','=','crpcontractorhumanresourcefinal.CIDNo')
                    ->where('CrpContractorFinalId',$reference)
                    ->where(function($query){
                        $query->where('IsPartnerOrOwner',0)
                              ->orWhere(DB::raw('coalesce(T4.ReferenceNo,0)'),101);
                    })
                    // ->where(function($query){
                    //     $query->where('T10.CmnApplicationRegistrationStatusId','=',"463c2d4c-adbd-11e4-99d7-080027dcfac6")
                    //         ->orWhere('T11.CmnApplicationRegistrationStatusId','=',"463c2d4c-adbd-11e4-99d7-080027dcfac6")
                    //         ->orWhere('T12.CmnApplicationRegistrationStatusId','=',"463c2d4c-adbd-11e4-99d7-080027dcfac6");
                    // })
                    ->orderBy('crpcontractorhumanresourcefinal.IsPartnerOrOwner')
                    ->orderBy('crpcontractorhumanresourcefinal.Name');
    }
    public function scopeContractorHumanResourceHardListSingle($query,$reference){
        return $query->where('Id',$reference);
    }
    public function scopeHumanResourceAttachments($query,$reference){
        return $query->join('crpcontractorhumanresourceattachmentfinal as T1','T1.CrpContractorHumanResourceFinalId','=','crpcontractorhumanresourcefinal.Id')->where('crpcontractorhumanresourcefinal.CrpContractorFinalId',$reference);
    }
}