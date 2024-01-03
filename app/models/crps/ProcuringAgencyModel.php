<?php
class ProcuringAgencyModel extends BaseModel{
	protected $table="cmnprocuringagency";
	protected $fillable=array('Id','Code','Name','CmnMinistryId','CmnDivisionId','CmnProcuringAgencyId','CreatedBy','EditedBy','CreatedOn','EditedOn');
    protected $rules=array(
        'Name'=>'required',
        'CmnMinistryId'=>'required',
    );
    protected $messages=array(
        'CmnMinistryId.required'=>'Ministry field is required',
        'Name.required'=>'Name field is required',
    );
    public function scopeProcuringAgencyHardList($query){
        return $query->orderBy('Name');
    }
    public function scopeProcuringAgency($query){
        return $query->leftJoin('cmnlistitem as T1','cmnprocuringagency.CmnMinistryId','=','T1.Id')
            ->leftJoin('cmnprocuringagency as T2','cmnprocuringagency.CmnProcuringAgencyId','=','T2.Id')
            ->orderBy('T1.Name')
            ->orderBy('cmnprocuringagency.Name');
    }
}