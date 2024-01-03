<?php
class SpecializedfirmModel extends BaseModel{
	public $table="crpspecializedfirm";
	protected $fillable=array('Id','CrpSpecializedTradeId','ReferenceNo','LocationChangeReason',"InitialDate",'ApplicationDate','SPNo','CmnOwnershipTypeId','NameOfFirm','TradeLicenseNo','TPN','CmnRegisteredDzongkhagId','Village','Gewog','CIDNo','Address','CmnCountryId','CmnDzongkhagId','Email','TelephoneNo','MobileNo','FaxNo','RegistrationStatus','CmnApplicationRegistrationStatusId','PaymentReceiptDate','PaymentReceiptNo','SysVerifierUserId','RegistrationVerifiedDate','RemarksByVerifier','SysApproverUserId','RemarksByApprover','RemarksByRejector','SysRejecterUserId','RejectedDate','RegistrationApprovedDate','RegistrationExpiryDate','DeregisteredBlacklistedRemarks','SysPaymentApproverUserId','RemarksByPaymentApprover','PaymentApprovedDate','RegistrationPaymentApprovedDate','SysLockedByUserId','SysRejectionCode','ChangeOfOwnershipRemarks','WaiveOffLateFee','NewLateFeeAmount','CreatedBy','EditedBy','CreatedOn','EditedOn');
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
    public function scopeSpecializedTradeListNewRegistration($query){
        return $query->join('cmncountry as T1','crpspecializedfirm.CmnCountryId','=','T1.Id')
                    ->leftJoin('cmndzongkhag as T2','crpspecializedfirm.CmnDzongkhagId','=','T2.Id')
                    ->orderBy('ApplicationDate')
                    ->orderBy('NameOfFirm')
                    ->where('crpspecializedfirm.CmnApplicationRegistrationStatusId',CONST_CMN_APPLICATIONREGISTRATIONSTATUS_NEW);
    }
    public function scopeSpecializedTradeHardList($query,$reference){
        return $query->where('Id',$reference);
    }
    public function scopeSpecializedTrade($query,$reference){
        return $query->take(1)->join('cmncountry as T1','crpspecializedfirm.CmnCountryId','=','T1.Id')
                                ->join('cmnlistitem as T7','crpspecializedfirm.CmnOwnershipTypeId','=','T7.Id')
                                ->leftJoin('sysuser as T4','crpspecializedfirm.SysVerifierUserId','=','T4.Id')
                                ->leftJoin('sysuser as T5','crpspecializedfirm.SysApproverUserId','=','T5.Id')
                                ->leftJoin('sysuser as T6','crpspecializedfirm.SysPaymentApproverUserId','=','T6.Id')
                                ->leftJoin('cmndzongkhag as T2','crpspecializedfirm.CmnDzongkhagId','=','T2.Id')
                                ->leftJoin('cmnlistitem as T3','crpspecializedfirm.CmnApplicationRegistrationStatusId','=','T3.Id')
                                ->leftJoin('cmndzongkhag as T8','crpspecializedfirm.CmnRegisteredDzongkhagId','=','T8.Id') 
                                ->where('crpspecializedfirm.Id','=',$reference);


                    
    }
    public function scopeSpecializedTradeHardListAll($query){
        return $query->orderBy('NameOfFirm');
    }
}