<?php
/**
 * Created by PhpStorm.
 * User: Sangay
 * Date: 3/23/2015
 * Time: 10:58 AM
 */

class EtlContractorEquipmentModel extends BaseModel{
    protected $table = "etlcontractorequipment";
    protected $fillable = array("Id","EtlTenderBidderContractorId","RegistrationNo","EtlTierId","CmnEquipmentId","Quantity","OwnedOrHired","Points","CreatedBy","EditedBy");
}