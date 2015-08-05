<?php

return array(

    'seo' => ['title', 'description', 'keywords', 'h1'],

    'versions' => 0,

    'disable_mainpage_route' => false, ## отключить маршрут главной страницы (mainpage)

    'disable_url_modification' => false, ## отключить модификаторы урлов. Установить в false!
    'disable_slug_to_template' => true, ## отключить автоматический поиск шаблона страницы по ее системному имени в случае, если страница не существует

    'preload_pages_limit' => 0, ## NULL - never; 0 - always; 100 - if less than 100 (+one more sql request)
    'preload_cache_lifetime' => 60*24, ## время жизни кеша страниц, в минутах

    /*
    'fields' => function() {

        return array(
            'image' => array(
                'title' => 'Картинка для шапки',
                'type' => 'image',
            ),
        );
    },
    #*/

    #/*
    'block_templates' => function() {

        $block_tpls = [

            'organizers' => [
                'title' => 'Организаторы',
                'fields' => [
                    'title' => [
                        'title' => 'Название',
                        'type' => 'text',
                    ],
                    'file_path' => [
                        'title' => 'Путь к логотипу',
                        'type' => 'text',
                    ],
                    'description' => [
                        'title' => 'Описание',
                        'type' => 'textarea',
                    ],
                    'url_path' => [
                        'title' => 'Ссылка на сайт',
                        'type' => 'text',
                    ],
                ],
            ],

            'first' => [
                'title' => 'Этапы конкурса',
                'fields' => [
                    'period' => [
                        'title' => 'Период',
                        'type' => 'text',
                    ],
                    'desc' => [
                        'title' => 'Описание',
                        'type' => 'text',
                    ],
                ],
            ],
            'nomination' => [
                'title' => 'Номинации',
                'fields' => [
                    'italic' => [
                        'title' => 'Номинация',
                        'type' => 'text',
                    ],
                    'title' => [
                        'title' => 'Название',
                        'type' => 'text',
                    ],
                    'desc' => [
                        'title' => 'Описание',
                        'type' => 'text',
                    ],
                ],
            ],

            'map' => [
                'title' => 'График перемещения',
                'fields' => [
                    'title' => [
                        'title' => 'Название',
                        'type' => 'text',
                    ],
                    'file_path' => [
                        'title' => 'Путь к файлу',
                        'type' => 'text',
                    ],
                ],
            ],
        ];

        return $block_tpls;
    }
    #*/
);