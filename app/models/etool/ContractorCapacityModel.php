<?php
/**
 * Created by PhpStorm.
 * User: Sangay
 * Date: 3/23/2015
 * Time: 10:50 AM
 */

class ContractorCapacityModel extends BaseModel{
    protected $table = "etlcontractorcapacity";
    protected $fillable = array("Id","EtlTenderBidderContractorId","CmnBankId","Amount","Sequence","CreatedBy","EditedBy");
}