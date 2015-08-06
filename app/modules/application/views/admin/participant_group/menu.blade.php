<?
$menus = array();
$menus[] = array(
        'link' => URL::route('participant_group.create'),
        'title' => 'Добавить',
        'class' => 'btn btn-primary'
);
?>
<h1 class="top-module-menu">
    <a href="{{ Request::path() }}">Группы участников</a>
</h1>

{{ Helper::drawmenu($menus) }}
