<?php

class Accounts extends User {

    public function ulogin(){

        return $this->hasOne('Ulogin','user_id','id');
    }
}