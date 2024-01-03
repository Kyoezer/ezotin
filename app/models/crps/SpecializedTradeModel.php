<?php
class SpecializedTradeModel extends BaseModel{
	public $table="crpspecializedtrade";
	protected $fillable=array('Id','CrpSpecializedTradeId','ReferenceNo',"InitialDate",'ApplicationDate','SPNo','CIDNo','TPN','CmnSalutationId','Name','CmnDzongkhagId','Gewog','Village','Email','MobileNo','TelephoneNo','EmployerName','EmployerAddress','RegistrationStatus','CmnApplicationRegistrationStatusId','SysVerifierUserId','VerifiedDate','RemarksByVerifier','SysApproverUserId','RemarksByApprover','SysPaymentApproverUserId','RemarksByPaymentApprover','PaymentApprovedDate','PaymentReceiptNo','PaymentReceiptDate','SysRejectorUserId','RemarksByRejector','RejectedDate','RegistrationApprovedDate','RegistrationExpiryDate','DeregisteredBlacklistedRemarks','SysLockedByUserId','CreatedBy','EditedBy','CreatedOn','EditedOn');
    protected $rules=array(
        'CIDNo'=>'required',
        'CmnSalutationId'=>'required',
        'Name'=>'required',
        'CmnDzongkhagId'=>'required',
        'Email'=>'required|email',
        'MobileNo'=>'required',
        'Gewog'=>'required',
        'Village'=>'required',
    );
    protected $messages=array(
        'CIDNo.required'=>'CID No/Work Permit No. field is required',
        'CmnSalutationId.required'=>'Salutation  field is required',
        'Name.required'=>'Name field is required',
        'Email.required'=>'Email field is required',
        'Email.email'=>'Invalid email format for email field',
        'MobileNo.required'=>'Mobile No. field is required',
        'CmnDzongkhagId.required'=>'Dzongkhag field is required',
        'Gewog.required'=>'Gewog field is required',
        'Village.required'=>'Village field is required',
    );
    public function scopeSpecializedTrade($query,$reference){
                     $query->join('cmnlistitem as T1','T1.Id','=','crpspecializedtrade.CmnSalutationId')
                     ->join('cmndzongkhag as T2','T2.Id','=','crpspecializedtrade.CmnDzongkhagId')
                     ->leftJoin('cmnlistitem as T3','T3.Id','=','crpspecializedtrade.CmnApplicationRegistrationStatusId')
                     ->leftJoin('sysuser as T4','crpspecializedtrade.SysVerifierUserId','=','T4.Id')
                    ->leftJoin('sysuser as T5','crpspecializedtrade.SysApproverUserId','=','T5.Id')
                    ->leftJoin('sysuser as T6','crpspecializedtrade.SysPaymentApproverUserId','=','T6.Id')
                    ->where('crpspecializedtrade.Id',$reference);
    }
    public function scopeSpecializedTradeHardList($query,$reference){
        return $query->where('Id',$reference);
    }
    public function scopeSpecializedTradeHardListAll($query){
        return $query->orderBy('Name');
    }
}