<?php

class Accounts extends \User {

    public static $rules = array(
        'name' => 'required', 'email' => 'required|email', 'location' => 'required', 'age' => 'required',
        'phone' => 'required', 'agree1' => 'required', 'agree2' => 'required'
    );

    public static $api_rules = array(
        'token' => 'required', 'name' => '', 'email' => '', 'phone' => '',
        'vk_id' => '', 'inst_id' => ''
    );

    public static $update_rules = array(
        'name' => 'required', 'email' => 'required|email', 'location' => 'required', 'age' => 'required',
        'phone' => 'required'
    );

    public function ulogin() {

        return $this->hasOne('Ulogin', 'user_id', 'id');
    }

    public function ulogins() {

        return $this->hasMany('Ulogin', 'user_id', 'id');
    }

    public function likes() {

        return $this->hasMany('ParticipantLikes', 'participant_id', 'id');
    }
}