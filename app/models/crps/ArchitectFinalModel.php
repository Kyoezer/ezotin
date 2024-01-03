<?php
class ArchitectFinalModel extends BaseModel{
    protected $table="crparchitectfinal";
    protected $fillable=array('Id','ReferenceNo','ApplicationDate','ARNo','CmnServiceSectorTypeId','CIDNo','CmnSalutationId','TPN','Name','CmnCountryId','CmnDzongkhagId','Gewog','Village','Email','MobileNo','EmployerName','EmployerAddress','CmnQualificationId','GraduationYear','NameOfUniversity','CmnUniversityCountryId','CmnApplicationRegistrationStatusId','RegistrationApprovedDate','RegistrationExpiryDate','BlacklistedRemarks','BlacklistedDate','DeRegisteredDate','DeregisteredRemarks','ReRegistrationDate','ReRegistrationRemarks','CreatedBy','EditedBy','CreatedOn','EditedOn');
    protected $rules=array(
        'CmnServiceSectorTypeId'=>'required',
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
    public function scopeArchitect($query,$reference){
        return $query->join('cmnlistitem as T1','T1.Id','=','crparchitectfinal.CmnServiceSectorTypeId')
                    ->join('cmnlistitem as T2','T2.Id','=','crparchitectfinal.CmnSalutationId')
                    ->join('cmncountry as T3','T3.Id','=','crparchitectfinal.CmnCountryId')
		    ->join('cmnlistitem as T8','T8.Id','=','crparchitectfinal.CmnTradeId')
                    ->join('cmnlistitem as T5','T5.Id','=','crparchitectfinal.CmnQualificationId')
                    ->leftJoin('cmncountry as T6','T6.Id','=','crparchitectfinal.CmnUniversityCountryId')
                    ->leftJoin('cmndzongkhag as T4','T4.Id','=','crparchitectfinal.CmnDzongkhagId')
                    ->leftJoin('cmnlistitem as T7','T7.Id','=','crparchitectfinal.CmnApplicationRegistrationStatusId')
                    ->where('crparchitectfinal.Id',$reference);
    }
    public function scopeArchitectHardList($query,$reference){
        return $query->where('Id',$reference);
    }
    public function scopeArchitectIdAfterAuth($query,$userid){
        return $query->where('SysUserId',$userid);
    }
    public function scopeArchitectHardListAll($query){
        return $query->orderBy('Name');
    }
}