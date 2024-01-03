<?php
/**
 * Created by PhpStorm.
 * User: Sangay
 * Date: 3/23/2015
 * Time: 12:27 PM
 */

class ContractorListModel extends BaseModel{
    protected $table = "etltenderbiddercontractordetail";
    protected $fillable = array("Id","EtlTenderBidderContractorId","CrpContractorFinalId","Stake","Sequence","CreatedBy","EditedBy");
}