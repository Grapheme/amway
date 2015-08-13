<?php

class Xss extends BaseModel {

    public static function globalXssClean() {
        $sanitized = static::arrayStripTags(Input::get());
        Input::merge($sanitized);
    }

    public static function arrayStripTags($array) {

        $result = array();
        foreach ($array as $key => $value):
            $key = strip_tags($key);
            if (is_array($value)):
                $result[$key] = static::arrayStripTags($value);
            else:
                $result[$key] = trim(strip_tags($value, '<p><a><strong><ul><li><img><iframe><em><table><td><tr><h1><h2><h3><h4><h5><h6><div><span><br><hr>'));
            endif;
        endforeach;
        return $result;
    }
}