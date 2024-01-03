<?php

/**
 * Created by PhpStorm.
 * User: swm
 * Date: 9/15/2016
 * Time: 12:49 PM
 */
class Webadvertisementattachment extends BaseModel
{
    protected $table = "webadvertisementattachment";
    protected $fillable = array('Id','WebAdvertisementId','AttachmentPath','AttachmentName','CreatedBy');
}