<?php
class SpecializedfirmFinalModel extends BaseModel{
	protected $table="crpspecializedfirmfinal";
	protected $fillable=array('Id','ApplicationDate','InitialDate','TradeLicenseNo','CmnOwnershipTypeId','NameOfFirm','SPNo','TPN', 'CmnRegisteredDzongkhagId','RegisteredAddress','Address','CmnCountryId','CmnDzongkhagId','Gewog','Village','Email','MobileNo','TelephoneNo','CmnApplicationRegistrationStatusId','RegistrationApprovedDate','RegistrationExpiryDate','BlacklistedRemarks','BlacklistedDate','DeRegisteredDate','DeregisteredRemarks','ReRegistrationDate','ReRegistrationRemarks','CreatedBy','EditedBy','CreatedOn','EditedOn');
    protected $rules=array(
        
        
        'CmnOwnershipTypeId'=>'required',
        'NameOfFirm'=>'required',
        'Name'=>'required',
        'Address'=>'required',
        'CmnCountryId'=>'required',
        'CmnDzongkhagId'=>'required',
        'Email'=>'required|email',
        'MobileNo'=>'required',
        'Gewog'=>'required',
        'Village'=>'required',
    );
    protected $messages=array(
        
        'CmnOwnershipTypeId.required'=>'Ownership Type field is required',
        'NameOfFirm.required'=>'Proposed Name field is required',
        'Email.required'=>'Email field is required',
        'Email.email'=>'Invalid email format for email field',
        'Address.required'=>'Correspondence Address field is required',
        'CmnCountryId.required'=>'Country field is required',
        'MobileNo.required'=>'Mobile No. field is required',
        'CmnDzongkhagId.required'=>'Dzongkhag field is required',
        'Gewog.required'=>'Gewog field is required',
        'Village.required'=>'Village field is required',
    );
    public function scopeSpecializedTrade($query,$reference){
                     $query->join('cmnlistitem as T4','crpspecializedfirmfinal.CmnOwnershipTypeId','=','T4.Id')
                     ->leftJoin('cmndzongkhag as T2','T2.Id','=','crpspecializedfirmfinal.CmnDzongkhagId')
                     ->leftJoin('cmncountry as T8','crpspecializedfirmfinal.CmnCountryId','=','T8.Id')
                     ->leftJoin('cmnlistitem as T3','T3.Id','=','crpspecializedfirmfinal.CmnApplicationRegistrationStatusId')
                     ->leftJoin('cmndzongkhag as T5','crpspecializedfirmfinal.CmnRegisteredDzongkhagId','=','T5.Id')
                    ->where('crpspecializedfirmfinal.Id',$reference);
    }
    public function scopeSpecializedTradeHardList($query,$reference){
        return $query->where('Id',$reference);
    }

    
     public function scopeSpecializedTradeIdAfterAuth($query,$userid){
        return $query->where('SysUserId',$userid);
    }
    public function scopeSpecializedTradeHardListAll($query){
        return $query->orderBy('SPNo');
    }

}