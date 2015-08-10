<?php

return array(

    'date_over_action' => '2015-09-15',
    'date_final_action' => '2015-10-24',

    'theme_path' => URL::to('/theme'),
    #'theme_path' => URL::to('/theme/' . Config::get('app.template') . '/dist'),
    #'mobile_theme_path' => URL::to('/theme/mobile/dist'),

    'paginate_limit' => 30,

    ## Disable functionality of changing url "on-the-fly" for generating
    ## seo-friendly url (via URL::route('page', '...')) with right various url-segments for multilingual pages.
    'disable_url_modification' => 0,

    'uploads_dir' => public_path('uploads/files'),
    'uploads_public_dir' => '/uploads/files',

    'uploads_photo_dir' => public_path('uploads'),
    'uploads_thumb_dir' => public_path('uploads/thumbnail'),
    'uploads_photo_public_dir' => '/uploads',
    'uploads_thumb_public_dir' => '/uploads/thumbnail',

    'uploads_video_dir' => 'uploads/video',

    'galleries_photo_dir'        => public_path('uploads/galleries'),
    'galleries_photo_public_dir' => '/uploads/galleries',
    'galleries_thumb_dir'        => public_path('uploads/galleries/thumbs'),
    'galleries_thumb_public_dir' => '/uploads/galleries/thumbs',

    'galleries_photo_size'       => -800, # 800 => 800x600 || 600x800 ; -800 => 800x1000 || 1000x800
    'galleries_thumb_size'       => -75, # 200 => 200x150 || 150x200 ; -200 => 200x300 || 300x200

    'galleries_cache_dir'        => public_path('uploads/galleries/cache'),
    'galleries_cache_public_dir' => '/uploads/galleries/cache',
    'galleries_cache_allowed_sizes' => [
        '200x200'
    ],


    'seo' => [
        'default_title'       => 'Поколение А',
        'default_description' => '',
        'default_keywords'    => '',
    ],

    'dics' => [
        'preload_cache_lifetime' => 60*24, ## время жизни кеша страниц, в минутах
    ],

    'monthes' => [
        '01' => 'Январь',
        '02' => 'Февраль',
        '03' => 'Март',
        '04' => 'Апрель',
        '05' => 'Май',
        '06' => 'Июнь',
        '07' => 'Июль',
        '08' => 'Август',
        '09' => 'Сентябрь',
        '10' => 'Октябрь',
        '11' => 'Ноябрь',
        '12' => 'Декабрь',
    ],


    ##
    ## MOBILE VERSION
    ## Template changing by mobile subdomain
    ##
    'mobile' => [
        'enabled'  => TRUE,
        'domain'   => 'm',
        'template' => 'mobile',
        'theme_path' => URL::to('/theme/mobile/dist'),
    ],
);
