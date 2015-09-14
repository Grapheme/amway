<?php

class Casting extends \BaseModel {

    protected $table = 'casting';
    protected $guarded = array('id', '_method', '_token');
    protected $fillable = array('name', 'city', 'time', 'phone');
    public static $rules = array('name' => 'required', 'city' => 'required', 'time' => 'required',
        'phone' => 'required');
}