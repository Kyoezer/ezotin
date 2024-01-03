<?php
/**
 * Created by PhpStorm.
 * User: Sangay
 * Date: 3/23/2015
 * Time: 10:58 AM
 */

class EtlContractorHumanResourceModel extends BaseModel{
    protected $table = "etlcontractorhumanresource";
    protected $fillable = array("Id","EtlTenderBidderContractorId","CIDNo","Name","EtlTierId","CmnDesignationId","Qualification","Points","CreatedBy","EditedBy");
}