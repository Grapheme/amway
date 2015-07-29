<?
    $menus = array();
/*
    $menus[] = array(
        'link' => URL::route('catalog.orders.index'),
        'title' => 'Активные',
        'class' => 'btn btn-default'
    );

    $menus[] = array(
        'link' => URL::route('catalog.orders.index', ['archive' => 1]),
        'title' => 'Архивные',
        'class' => 'btn btn-default'
    );
*/
    #Helper::d($menus);
?>
    
    <h1>
        Настройки
    </h1>

    {{ Helper::drawmenu($menus) }}
