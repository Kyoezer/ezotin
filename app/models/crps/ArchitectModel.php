<?php
class ArchitectModel extends BaseModel{
	public $table="crparchitect";
	protected $fillable=array('Id','ReferenceNo','InitialDate','CrpArchitectId','ApplicationDate','ARNo','CmnServiceSectorTypeId','CIDNo','TPN','CmnSalutationId','Name','CmnCountryId','CmnDzongkhagId','Gewog','Village','Email','MobileNo','EmployerName','EmployerAddress','CmnQualificationId','GraduationYear','NameOfUniversity','CmnUniversityCountryId','RegistrationStatus','CmnApplicationRegistrationStatusId','SysVerifierUserId','VerifiedDate','RemarksByVerifier','SysApproverUserId','RemarksByApprover','SysRejectorUserId','RemarksByRejector','RejectedDate','SysRejectionCode','SysPaymentApproverUserId','RemarksByPaymentApprover','PaymentApprovedDate','PaymentReceiptNo','PaymentReceiptDate','RegistrationApprovedDate','RegistrationExpiryDate','DeregisteredBlacklistedRemarks','SysLockedByUserId','WaiveOffLateFee','NewLateFeeAmount','CreatedBy','EditedBy','CreatedOn','EditedOn');
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
        return $query->join('cmnlistitem as T1','T1.Id','=','crparchitect.CmnServiceSectorTypeId')
                    ->join('cmnlistitem as T2','T2.Id','=','crparchitect.CmnSalutationId')
                    ->join('cmncountry as T3','T3.Id','=','crparchitect.CmnCountryId')
                    ->join('cmnlistitem as T5','T5.Id','=','crparchitect.CmnQualificationId')
                    ->join('cmncountry as T6','T6.Id','=','crparchitect.CmnUniversityCountryId')
                    ->leftJoin('cmndzongkhag as T4','T4.Id','=','crparchitect.CmnDzongkhagId')
                    ->leftJoin('cmnlistitem as T7','T7.Id','=','crparchitect.CmnApplicationRegistrationStatusId')
                    ->leftJoin('sysuser as T8','crparchitect.SysVerifierUserId','=','T8.Id')
                    ->leftJoin('sysuser as T9','crparchitect.SysApproverUserId','=','T9.Id')
                    ->leftJoin('sysuser as T10','crparchitect.SysPaymentApproverUserId','=','T10.Id')
                    ->where('crparchitect.Id',$reference);
    }
    public function scopeArchitectHardList($query,$reference){
        return $query->where('Id',$reference);
    }
    public function scopeArchitectHardListAll($query){
        return $query->orderBy('Name');
    }
}