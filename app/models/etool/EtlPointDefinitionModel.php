<?php
/**
 * Created by PhpStorm.
 * User: Sangay
 * Date: 3/23/2015
 * Time: 10:33 AM
 */

class EtlPointDefinitionModel extends BaseModel{
    protected $table = "etlpointdefinition";
    protected $fillable = array("Id","EtlPointTypeId","UpperLimit","LowerLimit","Points","CreatedBy","EditedBy");
}