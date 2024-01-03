<?php

/**
 * Created by PhpStorm.
 * User: swm
 * Date: 8/8/2016
 * Time: 7:54 PM
 */
class JournalManuscripttasklistModel extends BaseModel
{
    protected $table = "webjournaltasklist";
    protected $fillable = array(
        "Application_No","Task_Status_Id","CreatedBy","EditedBy"
    );
}