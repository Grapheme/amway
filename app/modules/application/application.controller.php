<?php

class ApplicationController extends BaseController {

    public static $name = 'application';
    public static $group = 'application';
    /****************************************************************************/
    public static function returnRoutes($prefix = null) {

    }
    /****************************************************************************/
	public function __construct(){}
    /****************************************************************************/
    public static function returnInfo() {

        return array(
            'name' => self::$name,
            'group' => self::$group,
            'title' => 'Эй Джен',
            'visible' => TRUE,
        );
    }

    public static function returnMenu() {

        $menu[] = array(
            'title' => 'Участники',
            'link' => 'participants',
            'class' => 'fa-users',
            'system' => 1,
            'menu_child' => NULL,
            'permit' => 'view'
        );
        return $menu;
    }

    public static function returnActions() {

        return array(
            'view' => 'Просмотр',
            'create' => 'Создание',
            'edit' => 'Редактирование',
            'delete' => 'Удаление',
        );
    }
    /****************************************************************************/
}