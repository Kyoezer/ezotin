<?php

/**
 * Created by PhpStorm.
 * User: swm
 * Date: 8/8/2016
 * Time: 7:54 PM
 */
class JournalModel extends BaseModel
{
    protected $table = "webjournaluserapplication";
    protected $fillable = array(
        "Name","Email","Password","Contact","Status","CreatedBy","EditedBy"
    );
}