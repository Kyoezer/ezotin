<?php
class EngineerModel extends BaseModel{
	public $table="crpengineer";
	protected $fillable=array('Id','CrpEngineerId','ReferenceNo',"InitialDate",'ApplicationDate','CDBNo','TPN','CmnServiceSectorTypeId','CmnTradeId','CIDNo','CmnSalutationId','Name','CmnCountryId','CmnDzongkhagId','Gewog','Village','Email','MobileNo','EmployerName','EmployerAddress','CmnQualificationId','GraduationYear','NameOfUniversity','CmnUniversityCountryId','RegistrationStatus','CmnApplicationRegistrationStatusId','SysVerifierUserId','VerifiedDate','RemarksByVerifier','SysApproverUserId','RemarksByApprover','SysRejectorUserId','RemarksByRejector','RejectedDate','SysPaymentApproverUserId','RemarksByPaymentApprover','PaymentApprovedDate','PaymentReceiptNo','PaymentReceiptDate','RegistrationApprovedDate','RegistrationExpiryDate','DeregisteredBlacklistedRemarks','SysLockedByUserId','WaiveOffLateFee','NewLateFeeAmount','CreatedBy','EditedBy','CreatedOn','EditedOn');
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
        return $query->join('cmnlistitem as T1','T1.Id','=','crpengineer.CmnServiceSectorTypeId')
                    ->join('cmnlistitem as T2','T2.Id','=','crpengineer.CmnSalutationId')
                    ->join('cmncountry as T3','T3.Id','=','crpengineer.CmnCountryId')
                    ->join('cmnlistitem as T8','T8.Id','=','crpengineer.CmnTradeId')
                    ->join('cmnlistitem as T9','T9.Id','=','crpengineer.CmnServiceSectorTypeId')
                    ->join('cmnlistitem as T5','T5.Id','=','crpengineer.CmnQualificationId')
                    ->join('cmncountry as T6','T6.Id','=','crpengineer.CmnUniversityCountryId')
                    ->leftJoin('cmndzongkhag as T4','T4.Id','=','crpengineer.CmnDzongkhagId')
                    ->leftJoin('cmnlistitem as T7','T7.Id','=','crpengineer.CmnApplicationRegistrationStatusId')
                    ->leftJoin('sysuser as T10','crpengineer.SysVerifierUserId','=','T10.Id')
                    ->leftJoin('sysuser as T11','crpengineer.SysApproverUserId','=','T11.Id')
                    ->leftJoin('sysuser as T12','crpengineer.SysPaymentApproverUserId','=','T12.Id')
                    ->where('crpengineer.Id',$reference);
    }
     public function scopeEngineerHardList($query,$reference){
        return $query->where('Id',$reference);
    }
     public function scopeEngineerHardListAll($query){
        return $query;
    }
}