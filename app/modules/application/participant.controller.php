<?php

class ParticipantController extends BaseController {

    public static $name = 'participant';
    public static $group = 'accounts';
    public static $entity = 'participant';
    public static $entity_name = 'Участники';

    /****************************************************************************/

    public static function returnRoutes() {

        $class = __CLASS__;

        Route::group(array('before' => 'user.auth', 'prefix' => 'participant'), function () use ($class) {
            Route::get('profile', array('as' => 'profile-edit', 'uses' => $class . '@profileEdit'));
        });
    }

    public static function returnShortCodes() {
    }

    public static function returnActions() {
    }

    public static function returnInfo() {
    }

    public static function returnMenu() {
    }

    /****************************************************************************/

    public function __construct() {

        $this->module = array(
            'name' => self::$name,
            'group' => self::$group,
            'rest' => self::$group,
            'tpl' => static::returnTpl(),
            'gtpl' => static::returnTpl(),
            'class' => __CLASS__,

            'entity' => self::$entity,
            'entity_name' => self::$entity_name,
        );
        View::share('module', $this->module);
    }

    /****************************************************************************/

    public function profileEdit(){

        $page_data = array(
            'page_title' => 'Личный кабинет',
            'page_description' => '',
            'page_keywords' => '',
            'profile' => Accounts::where('id', Auth::user()->id)->with('ulogin')->first(),
        );
        return View::make(Helper::acclayout('profile'), $page_data);
    }
}