<?php

class ParticipantGroup extends \BaseModel {

    protected $table = 'participant_group';
    protected $guarded = array('id', '_method', '_token');
    protected $fillable = array('title', 'description');
    public static $rules = array('title' => 'required');

    public function participants() {

        return $this->hasMany('Accounts', 'participant_group_id', 'id');
    }

}