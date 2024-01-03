<?php

/**
 * Created by PhpStorm.
 * User: swm
 * Date: 8/8/2016
 * Time: 7:54 PM
 */
class JournalUserModel extends BaseModel
{
    protected $table = "webjournaluser";
    protected $fillable = array(
        "Name","Email","Password","Contact","Status","CreatedBy","EditedBy"
    );
}