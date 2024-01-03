<?php
/**
 * Created by PhpStorm.
 * User: Sangay
 * Date: 3/19/2015
 * Time: 11:35 AM
 */

class CriteriaEquipmentModel extends BaseModel{
    protected $table = "etlcriteriaequipment";
    protected $fillable = array("Id","EtlTenderId","EtlTierId","CmnEquipmentId","Quantity","Points","DisplayOrder","CreatedBy","EditedBy");
}