<?php
/**
 * Created by PhpStorm.
 * User: Sangay
 * Date: 4/8/2015
 * Time: 12:50 PM
 */

class EtlTenderCommitteeModel extends BaseModel{
    protected $table = "etltendercommittee";
    protected $fillable = array('Id','Type','EtlTenderId','Name','Designation','CreatedBy','EditedBy');
}