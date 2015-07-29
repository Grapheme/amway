<?php

class ModulesTableSeeder extends Seeder {

    public function run() {

        Module::create(array(
            'name' => 'dictionaries',
            'on' => 1,
            'order' => 0
        ));

        Module::create(array(
            'name' => 'pages',
            'on' => 1,
            'order' => 1
        ));

        Module::create(array(
            'name' => 'galleries',
            'on' => 1,
            'order' => 2
        ));

        Module::create(array(
            'name' => 'seo',
            'on' => 1,
            'order' => 3
        ));

        Module::create(array(
            'name' => 'uploads',
            'on' => 1,
            'order' => 4
        ));

        Module::create(array(
            'name' => 'system',
            'on' => 1,
            'order' => 999
        ));

    }

}