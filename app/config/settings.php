<?php

return array(

    'sections' => function() {

        $settings = [];

        if (TRUE)
            $settings['main'] = [
                'title' => 'Основные',
                #'description' => 'Здесь собраны основные настройки сайта',
                'options' => array(
                    [
                        'group_title' => 'Настройки отправки почты',
                        'style' => 'margin: 0 0 5px 0',
                    ],

                    'feedback_address' => array(
                        'title' => 'Адрес почты для сообщений обратной связи',
                        'type' => 'text',
                    ),
                    'feedback_from_email' => array(
                        'title' => 'Адрес почты, от имени которого будут отправляться сообщения',
                        'type' => 'text',
                    ),
                    'feedback_from_name' => array(
                        'title' => 'Имя пользователя, от которого будут отправляться сообщения',
                        'type' => 'text',
                    ),

                    ['group_title' => 'Кеширование'],

                    'db_remember_timeout' => array(
                        'title' => 'Кол-во минут, на которое кешировать ВСЕ запросы к БД (не рекомендуется)',
                        'type' => 'text',
                    ),

                    ['group_title' => 'Прочее'],

                    'tpl_footer_counters' => array(
                        'title' => 'Код невидимых счетчиков (Я.Метрика, Google Analytics и т.д.)',
                        'type' => 'textarea',
                    ),
                ),
            ];

        if (Allow::action('catalog', 'catalog_allow', true, false))
            $settings['catalog'] = [
                'title' => 'Магазин',
                'options' => array(
                    'allow_products_order' => array(
                        'no_label' => true,
                        'title' => 'Разрешить сортировку всех товаров (не рекомендуется)',
                        'type' => 'checkbox',
                        'label_class' => 'normal_checkbox',
                    ),
                    'disable_attributes_for_products' => array(
                        'no_label' => true,
                        'title' => 'Отключить функционал работы с атрибутами для товаров',
                        'type' => 'checkbox',
                        'label_class' => 'normal_checkbox',
                    ),
                    'disable_attributes_for_categories' => array(
                        'no_label' => true,
                        'title' => 'Отключить функционал работы с атрибутами для категорий',
                        'type' => 'checkbox',
                        'label_class' => 'normal_checkbox',
                    ),
                ),
            ];

        return $settings;
    },

);

##
## ПРОТЕСТИРОВАННЫЕ ОПЦИИ
##
/*
                    'sitename' => array(
                        'title' => 'Название сайта',
                        'type' => 'text',
                    ),
                    'disabled' => array(
                        'no_label' => true,
                        'title' => 'Сайт отключен',
                        'type' => 'checkbox',
                        'label_class' => 'normal_checkbox',
                    ),
                    'description' => array(
                        'title' => 'Описание сайта',
                        'type' => 'textarea',
                    ),
                    'content' => array(
                        'title' => 'Визуальный текстовый редактор',
                        'type' => 'textarea_redactor',
                    ),
                    'photo' => array(
                        'title' => 'Поле для загрузки изображения',
                        'type' => 'image',
                        'params' => array(
                            'maxFilesize' => 1, // MB
                            #'acceptedFiles' => 'image/*',
                            #'maxFiles' => 2,
                        ),
                    ),
                    'gallery' => array(
                        'title' => 'Галерея изображений',
                        'type' => 'gallery',
                        'params' => array(
                            'maxfilesize' => 1, // MB
                            #'acceptedfiles' => 'image/*',
                        ),
                        'handler' => function($array, $element = false) {
                            return ExtForm::process('gallery', array(
                                #'module'  => 'dicval_meta',
                                #'unit_id' => $element->id,
                                'gallery' => $array,
                                'single'  => true,
                            ));
                        }
                    ),
                    'link_to_file' => array(
                        'title' => 'Поле для загрузки файла',
                        'type' => 'upload',
                        'accept' => '*', # .exe,image/*,video/*,audio/*
                        'label_class' => 'input-file',
                        'handler' => function($value, $element = false) {
                            if (@is_object($element) && @is_array($value)) {
                                $value['module'] = 'dicval';
                                $value['unit_id'] = $element->id;
                            }
                            return ExtForm::process('upload', $value);
                        },
                    ),
                    'theme' => array(
                        'title' => 'Тема оформления',
                        'type' => 'select',
                        'values' => ['Выберите..'] + ['Темная' => 'Темная', 'Светлая' => 'Светлая', 'Красная' => 'Красная'], ## Используется предзагруженный словарь
                    ),
*/