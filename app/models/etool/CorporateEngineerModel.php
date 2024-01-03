<?php

/**
 * Created by PhpStorm.
 * User: swm
 * Date: 8/2/2016
 * Time: 2:52 PM
 */
class CorporateEngineerModel extends BaseModel
{
    protected $table = "crpgovermentengineer";
    protected $fillable = array('Id','Name','CIDNo','PositionTitle','Agency','Qualification','Trade');
}