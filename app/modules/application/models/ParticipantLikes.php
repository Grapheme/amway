<?php

class ParticipantLikes extends \BaseModel {

    protected $table = 'participant_likes';
    protected $guarded = array('id','_method','_token');
    protected $fillable = array('participant_id','user_id');
    public static $rules = array('participant_id'=>'required','user_id'=>'required');

    public function user() {
		
        return $this->belongsTo('Accounts', 'user_id');
    }

    public function participant() {
        return $this->belongsTo('Accounts', 'participant_id');
    }
}