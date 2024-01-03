<?php
/**
 * Created by PhpStorm.
 * User: Sangay
 * Date: 3/23/2015
 * Time: 10:33 AM
 */

class AddContractorModel extends BaseModel{
    protected $table = "etltenderbiddercontractor";
    protected $fillable = array("Id","JointVenture","EtlTenderId","FinancialBidQuoted",
    "CmnOwnershipTypeId","EmploymentOfVTI","CommitmentOfInternship","ActualStartDate",
    "BhutaneseEmployement","ActualEndDate","Remarks","AwardedAmount","CreatedBy","EditedBy");
    protected $rules = array(
        "JointVenture" => 'required',
        "CDBNo" => 'required',
        "FinancialBidQuoted" => 'required|numeric',
        "CmnOwnershipTypeId" => 'required',
        "EmploymentOfVTI" => 'required',
        "CommitmentOfInternship" => 'required',
        "BhutaneseEmployement" => 'required'
    );
    protected $messages = array(
        "JointVenture.required" => 'Joint Venture field is required',
        "CDBNo.required" => 'CDB Number field is required',
        "FinancialBidQuoted.required" => 'Financial Bid Quoted field is required',
        "FinancialBidQuoted.numeric" => 'Financial Bid Quoted must be a number',
        "CmnOwnershipTypeId.required" => 'Ownership Type field is required',
        "EmploymentOfVTI.required" => 'Employment of VTI Graduate/Local Skilled Labour field is required',
        "CommitmentOfInternship/required" => 'Commitment of Internships to VTI Graduate field is required',
        "BhutaneseEmployement" =>'Bhutanese Employment field is required'
    );
}