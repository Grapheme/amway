<?php

class Xss extends BaseModel {

    public static function globalXssClean(){
        $sanitized = static::arrayStripTags(Input::get());
        Input::merge($sanitized);
    }

    public static function arrayStripTags($array){

        $result = array();
        foreach ($array as $key => $value):
            $key = strip_tags($key);
            if (is_array($value)):
                $result[$key] = static::arrayStripTags($value);
            else:
                $result[$key] = trim(strip_tags($value, '<p><a><strong><ul><li><img><iframe><em><table><td><tr>'));
            endif;
        endforeach;
        return $result;
    }
}