<?php

class EtoolQualifyingScoreModel extends BaseModel{
    protected $table = 'etlqualifyingscore';
    protected $fillable = array('Id','QualifyingScore','CreatedBy','EditedBy');
    protected $rules = array(
        'QualifyingScore' => 'required'
    );
    protected $messages = array(
        'QualifyingScore.required' => "Qualifying Score field is required"
    );
}