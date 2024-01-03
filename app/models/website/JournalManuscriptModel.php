<?php

/**
 * Created by PhpStorm.
 * User: swm
 * Date: 8/8/2016
 * Time: 7:54 PM
 */
class JournalManuscriptModel extends BaseModel
{
    protected $table = "webjournalmanuscriptapplication";
    protected $fillable = array(
        "File","CreatedBy","EditedBy"

    );
}