<?php

class JournalChecklistModel extends BaseModel
{
    protected $table = "webjournalchecklist";
    protected $fillable = array(
        "Name","Type","CreatedBy","EditedBy"
    );
}