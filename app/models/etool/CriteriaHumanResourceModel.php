<?php
/**
 * Created by PhpStorm.
 * User: Sangay
 * Date: 3/19/2015
 * Time: 11:34 AM
 */

class CriteriaHumanResourceModel extends BaseModel{
    protected $table = "etlcriteriahumanresource";
    protected $fillable = array('Id','EtlTenderId','EtlTierId','CmnDesignationId','Qualification','Points','DisplayOrder','CreatedBy','EditedBy');
}