<?php

/**
 * Created by PhpStorm.
 * User: swm
 * Date: 8/8/2016
 * Time: 7:54 PM
 */
class ArbitratorModel extends BaseModel
{
    protected $table = "webarbitrators";
    protected $fillable = array(
        "Id","RegNo","Name","Designation","Email","ContactNo","CasesInHand","FilePath","FileType"
    );
}