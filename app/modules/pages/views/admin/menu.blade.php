<?

$menus = array();

/*
$menus[] = array(
    'link' => URL::route($module['entity'] . '.index', array()),
    'title' => 'Страницы',
    'class' => 'btn btn-default'
);
if (isset($element) && is_object($element) && $element->id) {
    $menus[] = array(
        'link' => URL::route($module['entity'] . '.edit', array($element->id)),
        'title' => $element->name ?: $element->slug,
        'class' => 'btn btn-default'
    );
}
*/
$menus[] = array(
        'link' => URL::route($module['entity'] . '.create', []),
        'title' => 'Добавить',
        'class' => 'btn btn-primary'
);

$menus[] = array(
        'link' => URL::route($module['entity'] . '.hierarchy', []),
        'title' => 'Режим &laquo;Иерархия&raquo;',
        'class' => 'btn btn-warning'
);

if (isset($pages) && isset($list_mode) && $list_mode) {

    if (!isset($order_by))
        $order_by = null;

    if (!isset($order_type))
        $order_type = null;

    $order_by_param = 'name';
    $order_by_text = 'По названию';
    $title = (isset($order_by) && $order_by_param == $order_by ? '<i class="fa fa-check"></i> ' : '') . $order_by_text . ' ';
    $link = '?order_by=' . $order_by_param . '&order_type=';
    if ($order_by_param == $order_by) {
        if (isset($order_type) && $order_type == 'asc') {
            $title .= '<i class="fa fa-sort-alpha-asc"></i>';
            $link .= 'desc';
        } else {
            $title .= '<i class="fa fa-sort-alpha-desc"></i>';
            $link .= 'asc';
        }
    } else {
        $title .= '<i class="fa fa-sort-alpha-asc"></i>';
        $link .= 'asc';
    }
    $menus[] = array(
            'link' => $link,
            'title' => $title,
            'class' => 'btn btn-default'
    );


    $order_by_param = 'date';
    $order_by_text = 'По дате';
    $title = ($order_by_param == $order_by ? '<i class="fa fa-check"></i> ' : '') . $order_by_text . ' ';
    $link = '?order_by=' . $order_by_param . '&order_type=';
    if (isset($order_by) && $order_by_param == $order_by) {
        if (isset($order_type) && $order_type == 'asc') {
            $title .= '<i class="fa fa-sort-numeric-asc"></i>';
            $link .= 'desc';
        } else {
            $title .= '<i class="fa fa-sort-numeric-desc"></i>';
            $link .= 'asc';
        }
    } else {
        $title .= '<i class="fa fa-sort-numeric-desc"></i>';
        $link .= 'desc';
    }
    $menus[] = array(
            'link' => $link,
            'title' => $title,
            'class' => 'btn btn-default'
    );


    $order_by_param = 'url';
    $order_by_text = 'По URL';
    $title = ($order_by_param == $order_by ? '<i class="fa fa-check"></i> ' : '') . $order_by_text . ' ';
    $link = '?order_by=' . $order_by_param . '&order_type=';
    if (isset($order_by) && $order_by_param == $order_by) {
        if (isset($order_type) && $order_type == 'asc') {
            $title .= '<i class="fa fa-sort-alpha-asc"></i>';
            $link .= 'desc';
        } else {
            $title .= '<i class="fa fa-sort-alpha-desc"></i>';
            $link .= 'asc';
        }
    } else {
        $title .= '<i class="fa fa-sort-alpha-asc"></i>';
        $link .= 'asc';
    }
    $menus[] = array(
            'link' => $link,
            'title' => $title,
            'class' => 'btn btn-default'
    );
}


$cookie_order_by = isset($_COOKIE['admin__pages__order_by']) ? $_COOKIE['admin__pages__order_by'] : null;
$cookie_order_type = isset($_COOKIE['admin__pages__order_type']) ? $_COOKIE['admin__pages__order_type'] : null;
?>

<h1 class="top-module-menu">
    <a href="{{ URL::route($module['entity'] . '.index', ['order_by' => $cookie_order_by, 'order_type' => $cookie_order_type]) }}">Страницы</a>
    @if (isset($element) && is_object($element) && $element->id)
        &nbsp;&mdash;&nbsp;
        {{--<a href="{{ URL::route($module['entity'] . '.edit', array($element->id)) }}">--}}
        {{ $element->name ?: $element->slug }}
        {{--</a>--}}
    @endif
</h1>

{{ Helper::drawmenu($menus) }}
