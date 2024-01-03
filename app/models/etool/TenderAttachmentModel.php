<?php
/**
 * Created by PhpStorm.
 * User: Sangay
 * Date: 3/16/2015
 * Time: 4:43 PM
 */

class TenderAttachmentModel extends BaseModel{
    protected $table = 'etltenderattachment';
    protected $fillable = array('Id','EtlTenderId','DocumentName','DocumentPath','FileType','CreatedBy','EditedBy','CreatedOn','EditedOn');
}