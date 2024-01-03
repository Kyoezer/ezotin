<?php

/**
 * Created by PhpStorm.
 * User: SWM
 * Date: 3/30/2017
 * Time: 3:26 PM
 */
class ArbitratorUserModel extends BaseModel
{
    protected $table = 'webarbitratoruser';
    protected $fillable = array("Id","WebArbitratorId",'FullName','username','password','IsAdmin','Status',"CreatedBy",'CreatedOn','EditedBy','EditedOn');
}