<?php

class AdminSystemController extends BaseController {

    public static $name = 'system';
    public static $group = 'system';

    /****************************************************************************/

    ## Routing rules of module
    public static function returnRoutes($prefix = null) {
        #/*
        $class = __CLASS__;
        Route::group(array('before' => 'auth', 'prefix' => $prefix), function() use ($class) {
            Route::get($class::$group . '/phpinfo', array('as' => 'system.phpinfo', 'uses' => $class.'@getPhpInfo'));
        });
        #*/
    }

    ## Actions of module (for distribution rights of users)
    ## return false;   # for loading default actions from config
    ## return array(); # no rules will be loaded
    public static function returnActions() {
        return array(
            'system'        => 'Глобальный доступ к работе с настройками',
            'settings'      => 'Доступ к настройкам сайта',
            'modules'       => 'Работа с модулями',
            'groups'        => 'Работа с группами пользователей',
            'users'         => 'Работа с пользователями',
            'locale_editor' => 'Работа с редактором языков',
            'tpl_editor'    => 'Работа с редактором шаблонов',
            'menu_editor'   => 'Работа с редактором меню',
        );
    }

    ## Info about module (now only for admin dashboard & menu)
    public static function returnInfo() {
        return array(
        	'name' => self::$name,
        	'group' => self::$group,
        	'title' => '<i class="fa fa-exclamation-triangle"></i> Система',
            'visible' => 1,
        );
    }

    ## Menu elements of the module
    public static function returnMenu() {

        $menu = array();
        $menu_child = array();

        if (Allow::action('system', 'settings', false, true))
            $menu_child[] = array(
                'title' => 'Настройки',
                'link' => 'system/settings',
                'class' => 'fa-gear',
            );

        if (Allow::action('system', 'modules', false, true))
            $menu_child[] = array(
                'title' => 'Модули',
                'link' => 'system/modules',
                'class' => 'fa-gears',
            );

        if (Allow::action('system', 'groups', false, true))
            $menu_child[] = array(
                'title' => 'Группы',
                'link' => 'system/groups',
                'class' => 'fa-group',
            );

        if (Allow::action('system', 'users', false, true))
            $menu_child[] = array(
                'title' => 'Пользователи',
                'link' => 'system/users',
                'class' => 'fa-user',
            );

        if (Allow::action('system', 'menu_editor', false, true))
            $menu_child[] = array(
                'title' => 'Конструктор меню',
                'link' => 'system/menu_editor',
                'class' => 'fa-list',
            );

        if (Allow::action('system', 'locale_editor', false, true))
            $menu_child[] = array(
                'title' => 'Редактор языков',
                'link' => 'system/locale_editor',
                'class' => 'fa-language',
            );

        if (Allow::action('system', 'tpl_editor', false, true))
            $menu_child[] = array(
                'title' => 'Редактор шаблонов',
                'link' => 'system/tpl_editor',
                'class' => 'fa-th-large',
            );

        if (count($menu_child) && Allow::action('system', 'system', false, true))
            $menu[] = array(
                'title' => 'Система',
                'link' => '#',
                'class' => 'fa-gear',
                'system' => 1,
                'menu_child' => $menu_child,
            );

        return $menu;
    }

    /****************************************************************************/

	public function __construct(){
        #
	}


    public function getPhpInfo() {

        if (!Allow::superuser())
            App::abort(404);

        phpinfo();
        die;
    }
}
