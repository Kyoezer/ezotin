<?php
class SurveyFinalModel extends BaseModel{
    protected $table="crpsurveyfinal";
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
        'CmnServiceSectorTypeId.required'=>'Survey Type field is required',
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
    public function scopeSurvey($query,$reference){
        return $query->join('cmnlistitem as T1','T1.Id','=','crpsurveyfinal.CmnServiceSectorTypeId')
                    ->join('cmnlistitem as T2','T2.Id','=','crpsurveyfinal.CmnSalutationId')
                    ->join('cmncountry as T3','T3.Id','=','crpsurveyfinal.CmnCountryId')
                    ->join('cmnlistitem as T5','T5.Id','=','crpsurveyfinal.CmnQualificationId')
		    ->leftJoin('cmnlistitem as T8','T8.Id','=','crpsurveyfinal.CmnTradeId')
                    ->leftJoin('cmncountry as T6','T6.Id','=','crpsurveyfinal.CmnUniversityCountryId')
                    ->leftJoin('cmndzongkhag as T4','T4.Id','=','crpsurveyfinal.CmnDzongkhagId')
                    ->leftJoin('cmnlistitem as T7','T7.Id','=','crpsurveyfinal.CmnApplicationRegistrationStatusId')
                    ->where('crpsurveyfinal.Id',$reference);
    }
    public function scopeSurveyHardList($query,$reference){
        return $query->where('Id',$reference);
    }
    public function scopeSurveyIdAfterAuth($query,$userid){
        return $query->where('SysUserId',$userid);
    }
    public function scopeSurveyHardListAll($query){
        return $query->orderBy('Name');
    }
}