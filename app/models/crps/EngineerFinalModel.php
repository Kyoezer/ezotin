<?php
class EngineerFinalModel extends BaseModel{
    protected $table="crpengineerfinal";
    protected $fillable=array('Id','ReferenceNo','ApplicationDate','CDBNo','TPN','CmnServiceSectorTypeId','CmnTradeId','CIDNo','CmnSalutationId','Name','CmnCountryId','CmnDzongkhagId','Gewog','Village','Email','MobileNo','EmployerName','EmployerAddress','CmnQualificationId','GraduationYear','NameOfUniversity','CmnUniversityCountryId','CmnApplicationRegistrationStatusId','BlacklistedRemarks','BlacklistedDate','DeRegisteredDate','DeregisteredRemarks','ReRegistrationDate','ReRegistrationRemarks','CreatedBy','EditedBy','CreatedOn','EditedOn');
    protected $rules=array(
        'CmnServiceSectorTypeId'=>'required',
        'CmnTradeId'=>'required',
        'CIDNo'=>'required',
        'CmnSalutationId'=>'required',
        'Name'=>'required',
        'CmnCountryId'=>'required',
        'Email'=>'required',
        'MobileNo'=>'required',
        'CmnQualificationId'=>'required',
        'GraduationYear'=>'required|numeric',
        'NameOfUniversity'=>'required',
        'CmnUniversityCountryId'=>'required'
    );
    protected $messages=array(
        'CmnServiceSectorTypeId.required'=>'Architect Type field is required',
        'CmnTradeId.required'=>'Trade field is required',
        'CIDNo.required'=>'CID No/Work Permit No. field is required',
        'CmnSalutationId.required'=>'Salutation  field is required',
        'Name.required'=>'Name field is required',
        'CmnCountryId.required'=>'',
        'Email.required'=>'Email field is required',
        'Email.email'=>'Invalid email format',
        'MobileNo.required'=>'Mobile No. field is required',
        'CmnQualificationId.required'=>'Qualification field is required',
        'GraduationYear.required'=>'Year of Graduation field is required ',
        'GraduationYear.numeric'=>'Year of Graduation should be a number',
        'NameOfUniversity.required'=>'Name of University field is required',
        'CmnUniversityCountryId.required'=>'University Country field is required'
    );
    public function scopeEngineer($query,$reference){
        return $query->join('cmnlistitem as T1','T1.Id','=','crpengineerfinal.CmnServiceSectorTypeId')
                    ->join('cmnlistitem as T2','T2.Id','=','crpengineerfinal.CmnSalutationId')
                    ->join('cmncountry as T3','T3.Id','=','crpengineerfinal.CmnCountryId')
                    ->join('cmnlistitem as T8','T8.Id','=','crpengineerfinal.CmnTradeId')
                    ->join('cmnlistitem as T5','T5.Id','=','crpengineerfinal.CmnQualificationId')
                    ->join('cmncountry as T6','T6.Id','=','crpengineerfinal.CmnUniversityCountryId')
                    ->leftJoin('cmndzongkhag as T4','T4.Id','=','crpengineerfinal.CmnDzongkhagId')
                    ->leftJoin('cmnlistitem as T7','T7.Id','=','crpengineerfinal.CmnApplicationRegistrationStatusId')
                    ->where('crpengineerfinal.Id',$reference);
    }
     public function scopeEngineerHardList($query,$reference){
        return $query->where('Id',$reference);
    }
    public function scopeEngineerIdAfterAuth($query,$userid){
        return $query->where('SysUserId',$userid);
    }
    public function scopeEngineerHardListAll($query){
        return $query->orderBy('Name');
    }
}