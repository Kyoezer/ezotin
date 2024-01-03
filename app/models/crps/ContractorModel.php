<?php
class ContractorModel extends BaseModel{
	public $table="crpcontractor";
	protected $fillable=array('Id','CrpContractorId','ReferenceNo','LocationChangeReason',"InitialDate",'ApplicationDate','CDBNo','CmnOwnershipTypeId','NameOfFirm','TradeLicenseNo','TPN','CmnRegisteredDzongkhagId','Village','Gewog','Address','CmnCountryId','CmnDzongkhagId','Email','TelephoneNo','MobileNo','FaxNo','RegistrationStatus','CmnApplicationRegistrationStatusId','PaymentReceiptDate','PaymentReceiptNo','SysVerifierUserId','RegistrationVerifiedDate','RemarksByVerifier','SysApproverUserId','RemarksByApprover','RemarksByRejector','SysRejecterUserId','RejectedDate','RegistrationApprovedDate','RegistrationExpiryDate','DeregisteredBlacklistedRemarks','SysPaymentApproverUserId','RemarksByPaymentApprover','PaymentApprovedDate','RegistrationPaymentApprovedDate','SysLockedByUserId','SysRejectionCode','ChangeOfOwnershipRemarks','WaiveOffLateFee','NewLateFeeAmount','CreatedBy','EditedBy','CreatedOn','EditedOn');
    protected $rules=array(
        'ReferenceNo'=>'required',
        'CmnOwnershipTypeId'=>'required',
        'ApplicationDate'=>'required',
        'NameOfFirm'=>'required',
        'CmnCountryId'=>'required',
        'Email'=>'required|email',
    );
    protected $messages=array(
        'ReferenceNo.required'=>'Reference No. field is required',
        'CmnOwnershipTypeId.required'=>'Ownership Type field is required',
        'ApplicationDate.required'=>'Application Date field is required',
        'NameOfFirm.required'=>'Proposed Name field is required',
        'CmnCountryId.required'=>'Country field is required',
        'Email.required'=>'Email field is required',
        'Email.email'=>'Invalid Email format',
    );
    public function scopeContractorListNewRegistration($query){
        return $query->join('cmncountry as T1','crpcontractor.CmnCountryId','=','T1.Id')
                    ->leftJoin('cmndzongkhag as T2','crpcontractor.CmnDzongkhagId','=','T2.Id')
                    ->orderBy('ApplicationDate')
                    ->orderBy('NameOfFirm')
                    ->where('crpcontractor.CmnApplicationRegistrationStatusId',CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW);
    }
    public function scopeContractorHardList($query,$reference){
        return $query->where('Id',$reference);
    }
    public function scopeContractor($query,$reference){
        return $query->take(1)->join('cmncountry as T1','crpcontractor.CmnCountryId','=','T1.Id')
                                ->join('cmnlistitem as T7','crpcontractor.CmnOwnershipTypeId','=','T7.Id')
                                ->leftJoin('sysuser as T4','crpcontractor.SysVerifierUserId','=','T4.Id')
                                ->leftJoin('sysuser as T5','crpcontractor.SysApproverUserId','=','T5.Id')
                                ->leftJoin('sysuser as T6','crpcontractor.SysPaymentApproverUserId','=','T6.Id')
                                ->leftJoin('cmndzongkhag as T2','crpcontractor.CmnDzongkhagId','=','T2.Id')
                                ->leftJoin('cmnlistitem as T3','crpcontractor.CmnApplicationRegistrationStatusId','=','T3.Id')
                                ->leftJoin('cmndzongkhag as T8','crpcontractor.CmnRegisteredDzongkhagId','=','T8.Id') 
                                ->where('crpcontractor.Id','=',$reference);
    }
    public function scopeContractorHardListAll($query){
        return $query->orderBy('NameOfFirm');
    }
}