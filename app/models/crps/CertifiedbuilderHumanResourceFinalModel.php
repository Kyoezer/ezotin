<?php
class CertifiedbuilderHumanResourceFinalModel extends BaseModel{
	protected $table="crpcertifiedbuilderhumanresourcefinal";
	protected $fillable=array('Id','CrpCertifiedBuilderFinalId','CmnSalutationId','CmnServiceTypeId','CIDNo','Name','Sex','CmnCountryId','CmnQualificationId','CmnTradeId','CmnDesignationId','JoiningDate','ShowInCertificate','IsPartnerOrOwner','CreatedBy','EditedBy','CreatedOn','EditedOn');
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
        return $query->where('IsPartnerOrOwner',1)->where('CrpCertifiedBuilderFinalId',$reference);
    }
    public function scopeCertifiedbuilderPartner($query,$reference){
        return $query->join('cmncountry as T1','crpcertifiedbuilderhumanresourcefinal.CmnCountryId','=','T1.Id')
                            ->join('cmnlistitem as T2','crpcertifiedbuilderhumanresourcefinal.CmnSalutationId','=','T2.Id')
                            ->leftJoin('cmnlistitem as T3','crpcertifiedbuilderhumanresourcefinal.CmnDesignationId','=','T3.Id')
                            ->where('crpcertifiedbuilderhumanresourcefinal.CrpCertifiedBuilderFinalId','=',$reference)
                            ->where('crpcertifiedbuilderhumanresourcefinal.IsPartnerOrOwner',1);
    }
    public function scopecertifiedbuilderHumanResource($query,$reference){
        return $query->join('cmnlistitem as T1','T1.Id','=','crpcertifiedbuilderhumanresourcefinal.CmnSalutationId')
                     ->join('cmnlistitem as T4','T4.Id','=','crpcertifiedbuilderhumanresourcefinal.CmnDesignationId')   
                    ->join('cmncountry as T5','T5.Id','=','crpcertifiedbuilderhumanresourcefinal.CmnCountryId')
                    ->leftJoin('cmnlistitem as T2','T2.Id','=','crpcertifiedbuilderhumanresourcefinal.CmnQualificationId')     
                    ->leftJoin('cmnlistitem as T3','T3.Id','=','crpcertifiedbuilderhumanresourcefinal.CmnTradeId') 
                    ->leftJoin('cmnlistitem as T6','T6.Id','=','crpcertifiedbuilderhumanresourcefinal.CmnServiceTypeId')
                    ->leftJoin('crpengineerfinal as T10','T10.CIDNo','=','crpcertifiedbuilderhumanresourcefinal.CIDNo')
                    ->leftJoin('crparchitectfinal as T11','T11.CIDNo','=','crpcertifiedbuilderhumanresourcefinal.CIDNo')
                    ->leftJoin('crpsurveyfinal as T12','T12.CIDNo','=','crpcertifiedbuilderhumanresourcefinal.CIDNo')
                    ->leftJoin('crpcertifiedbuilderfinal as T13','T13.CIDNo','=','crpcertifiedbuilderhumanresourcefinal.CIDNo')
                    ->where('CrpCertifiedBuilderFinalId',$reference)
                    ->where(function($query){
                        $query->where('IsPartnerOrOwner',0)
                              ->orWhere(DB::raw('coalesce(T4.ReferenceNo,0)'),101);
                    })
                    ->orderBy('crpcertifiedbuilderhumanresourcefinal.IsPartnerOrOwner')
                    ->orderBy('crpcertifiedbuilderhumanresourcefinal.Name');
    }
    public function scopecertifiedbuilderHumanResourceHardListSingle($query,$reference){
        return $query->where('Id',$reference);
    }
    public function scopeHumanResourceAttachments($query,$reference){
        return $query->join('crpcertifiedbuilderhumanresourceattachmentfinal as T1','T1.CrpCertifiedBuilderHumanResourceFinalId','=','crpcertifiedbuilderhumanresourcefinal.Id')->where('crpcertifiedbuilderhumanresourcefinal.CrpCertifiedBuilderFinalId',$reference);
    }
}