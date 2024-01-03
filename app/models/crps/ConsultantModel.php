<?php
class ConsultantModel extends BaseModel{
	public $table="crpconsultant";
	protected $fillable=array('Id','CrpConsultantId','ReferenceNo',"InitialDate",'ApplicationDate','CDBNo','CmnOwnershipTypeId','NameOfFirm','TradeLicenseNo','TPN', 'CmnRegisteredDzongkhagId','RegisteredAddress','Village','Gewog','Address','CmnCountryId','CmnDzongkhagId','Email','TelephoneNo','MobileNo','FaxNo','RegistrationStatus','CmnApplicationRegistrationStatusId','PaymentReceiptDate','PaymentReceiptNo','SysVerifierUserId','VerifiedDate','RemarksByVerifier','SysApproverUserId','RemarksByApprover','SysRejectorUserId','RemarksByRejector','RejectedDate','SysRejectionCode','RegistrationApprovedDate','RegistrationExpiryDate','DeregisteredBlacklistedRemarks','SysPaymentApproverUserId','RemarksByPaymentApprover','PaymentApprovedDate','RegistrationPaymentApprovedDate','WaiveOffLateFee','NewLateFeeAmount','SysLockedByUserId','CreatedBy','EditedBy','CreatedOn','EditedOn');
    protected $rules=array(
        'ReferenceNo'=>'required',
        'ApplicationDate'=>'required',
        'NameOfFirm'=>'required',
        'Address'=>'required',
        'CmnCountryId'=>'required',
        'CmnDzongkhagId'=>'required',
        'Email'=>'required|email',
        'TelephoneNo'=>'required',
    );
    protected $messages=array(
        'ReferenceNo.required'=>'Reference No. field is required',
        'ApplicationDate.required'=>'Application Date field is required',
        'NameOfFirm.required'=>'Proposed Name field is required',
        'Address.required'=>'Registered Address field is required',
        'CmnCountryId.required'=>'Country field is required',
        'CmnDzongkhagId.required'=>'Registered Address Dzongkhag field is required',
        'Email.required'=>'Email field is required',
        'Email.email'=>'Invalid Email format',
        'TelephoneNo.required'=>'Telephone No. field is required',
        'MobileNo.required'=>'Mobile No. field is required',
    );
    public function scopeConsultantListNewRegistration($query){
        return $query->join('cmncountry as T1','crpconsultant.CmnCountryId','=','T1.Id')
                    ->leftJoin('cmndzongkhag as T2','crpconsultant.CmnDzongkhagId','=','T2.Id')
                    ->orderBy('ApplicationDate')
                    ->orderBy('NameOfFirm')
                    ->where('crpconsultant.CmnApplicationRegistrationStatusId',CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW);
    }
    public function scopeConsultantHardList($query,$reference){
        return $query->where('Id',$reference);
    }
    public function scopeConsultant($query,$reference){
        return $query->take(1)->join('cmncountry as T1','crpconsultant.CmnCountryId','=','T1.Id')
                                ->join('cmnlistitem as T7','crpconsultant.CmnOwnershipTypeId','=','T7.Id')
                                ->leftJoin('sysuser as T4','crpconsultant.SysVerifierUserId','=','T4.Id')
                                ->leftJoin('sysuser as T5','crpconsultant.SysApproverUserId','=','T5.Id')
                                ->leftJoin('sysuser as T6','crpconsultant.SysPaymentApproverUserId','=','T6.Id')
                                ->leftJoin('cmndzongkhag as T2','crpconsultant.CmnDzongkhagId','=','T2.Id')
                                ->leftJoin('cmnlistitem as T3','crpconsultant.CmnApplicationRegistrationStatusId','=','T3.Id')
                                ->leftJoin('cmndzongkhag as T8','crpconsultant.CmnRegisteredDzongkhagId','=','T8.Id')
                                ->where('crpconsultant.Id','=',$reference);
    }
    public function scopeConsultantHardListAll($query){
        return $query->orderBy('NameOfFirm');
    }
    public function scopeConsultantIdAfterAuth($query,$userid){
        return $query->where('SysUserId',$userid);
    }
}