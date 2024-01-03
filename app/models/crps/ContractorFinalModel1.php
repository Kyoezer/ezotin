<?php
class ContractorFinalModel extends BaseModel{
	protected $table="crpcontractorfinal";
	protected $fillable=array('Id','CDBNo',"InitialDate",'CmnOwnershipTypeId','NameOfFirm','TradeLicenseNo','TPN', 'CmnRegisteredDzongkhagId','RegisteredAddress','Address','CmnCountryId','CmnDzongkhagId','Email','TelephoneNo','MobileNo','FaxNo','CmnApplicationRegistrationStatusId','BlacklistedRemarks','BlacklistedDate','RevokedDate', 'DeRegisteredDate','DeregisteredRemarks','RevokedRemarks', 'ReRegistrationDate','ReRegistrationRemarks','RegistrationApprovedDate','RegistrationExpiryDate','ChangeOfOwnershipRemarks','SurrenderedDate','SurrenderedRemarks','CreatedBy','EditedBy','CreatedOn','EditedOn');
    protected $rules=array(
        'CmnOwnershipTypeId'=>'required',
        'NameOfFirm'=>'required',
        'Address'=>'required',
        'CmnCountryId'=>'required',
        'CmnDzongkhagId'=>'required',
        'Email'=>'required|email',
        'MobileNo'=>'required',
    );
    protected $messages=array(
        'CmnOwnershipTypeId.required'=>'Ownership Type field is required',
        'NameOfFirm.required'=>'Proposed Name field is required',
        'Address.required'=>'Correspondence Address field is required',
        'CmnCountryId.required'=>'Country field is required',
        'CmnDzongkhagId.required'=>'Correspondence Address Dzongkhag field is required',
        'Email.required'=>'Email field is required',
        'Email.email'=>'Invalid Email format',
        'TelephoneNo.required'=>'Telephone No. field is required',
        'MobileNo.required'=>'Mobile No. field is required',
    );
	public function scopeContractorIdAfterAuth($query,$userid){
	    return $query->where('SysUserId',$userid);
	}
    public function scopeContractorHardList($query,$reference){
        return $query->where('Id',$reference);
    }
    public function scopeContractor($query,$reference){
        return $query->take(1)->join('cmncountry as T1','crpcontractorfinal.CmnCountryId','=','T1.Id')
                                ->join('cmnlistitem as T3','crpcontractorfinal.CmnApplicationRegistrationStatusId','=','T3.Id')
                                ->join('cmnlistitem as T4','crpcontractorfinal.CmnOwnershipTypeId','=','T4.Id')
                                ->leftJoin('cmndzongkhag as T2','crpcontractorfinal.CmnDzongkhagId','=','T2.Id')
                                ->leftJoin('cmndzongkhag as T5','crpcontractorfinal.CmnRegisteredDzongkhagId','=','T5.Id')
                                ->where('crpcontractorfinal.Id','=',$reference);
    }
    public function scopeContractorHardListAll($query){
        return $query->orderBy('NameOfFirm');
    }
}