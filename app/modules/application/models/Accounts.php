<?php

class Accounts extends User {

    public static $rules = array(
        'name' => 'required',
        'email' => 'required|email',
        'location' => 'required',
        'age' => 'required',
        'phone' => 'required',
        'agree1' => 'required',
        'agree2' => 'required',
    );

    public function ulogin(){

        return $this->hasOne('Ulogin','user_id','id');
    }
}