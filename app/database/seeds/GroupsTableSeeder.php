<?php

class GroupsTableSeeder extends Seeder{

	public function run(){
		
		#DB::table('groups')->truncate();

        Group::create(array(
			'name' => 'developer',
			'desc' => 'Разработчики',
			'dashboard' => 'admin'
		));

		Group::create(array(
			'name' => 'admin',
			'desc' => 'Администраторы',
			'dashboard' => 'admin'
		));

		Group::create(array(
			'name' => 'moderator',
			'desc' => 'Модераторы',
			'dashboard' => 'admin'
		));



        ## SELECT CONCAT("['module' => '", module, "', 'action' => '", action, "'],") FROM `actions` WHERE 1

        $actions = [
            ['module' => 'galleries', 'action' => 'view'],
            ['module' => 'galleries', 'action' => 'create'],
            ['module' => 'galleries', 'action' => 'edit'],
            ['module' => 'galleries', 'action' => 'delete'],
            ['module' => 'pages', 'action' => 'view'],
            ['module' => 'pages', 'action' => 'create'],
            ['module' => 'pages', 'action' => 'edit'],
            ['module' => 'pages', 'action' => 'delete'],
            ['module' => 'pages', 'action' => 'advanced'],
            ['module' => 'pages', 'action' => 'page_restore'],
            ['module' => 'seo', 'action' => 'edit'],
            ['module' => 'system', 'action' => 'system'],
            ['module' => 'system', 'action' => 'settings'],
            ['module' => 'system', 'action' => 'modules'],
            ['module' => 'system', 'action' => 'groups'],
            ['module' => 'system', 'action' => 'users'],
            ['module' => 'system', 'action' => 'locale_editor'],
        ];

        if (isset($actions) && is_array($actions) && count($actions)) {

            foreach ($actions as $act) {

                if (!is_array($act) || !isset($act['module']) || !$act['module'] || !isset($act['action']) || !$act['action'])
                    continue;

                Action::create(array(
                    'group_id' => '1',
                    'module'   => $act['module'],
                    'action'   => $act['action'],
                    'status'   => '1',
                ));
            }
        }
	}
}